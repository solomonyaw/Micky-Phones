jQuery(function($) {

    postboxes.add_postbox_toggles(pagenow);

    var cart = {
        items:    [],
        customer: {},
        coupons:  [],
        discount: {},
        shipping: {},
        taxes:    [],
        customer_note: '',
        private_note: ''
    };

    var coupons = {
        init: function() {
            $(document).on('click', '.remove-coupon', coupons.onRemoveCoupon);
            $('.edit-coupon-modal').click(coupons.onEditCoupon);
            $('[data-action="save-coupon"]').click(coupons.onSaveCoupon);
            $(document).on('keydown', coupons.onEscCoupon);

            $('#coupon-modal').on('shown.bs.modal', function () {
                $('[name="coupon-value"]').select2('open');
            });

             $( '[name="coupon-value"]' ).select2({
                dropdownParent: $('#coupon-modal'),
                minimumInputLength: 1,
                minimumResultsForSearch: -1,
                escapeMarkup: function(m) { return m; },
                multiple: false,
                ajax: {
                    url: ajaxurl,
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            action: 'phone-orders-for-woocommerce',
                            method: 'get_coupons_list',
                            term: params.term,
                            exclude: cart.coupons
                        };
                    },
                    processResults: function (data, params) {
                        return {results: data};
                    },
                    cache: true
                },
            }).on('select2:select', coupons.onSaveCoupon);

        },

        display: function() {
            $('.coupons-list-item').remove();

            cart.coupons.forEach(function(coupon) {
                var new_item = $('.coupons-list-add')
                    .clone()
                    .removeClass('coupons-list-add')
                    .addClass('coupons-list-item')
                    .insertBefore($('.coupons-list-add'))
                    .data('coupon', coupon);

                new_item.find('.label-total').html(wc_phone_orders.Coupon + ': ' + coupon);
                new_item.find('.coupon-value').html('<a class="remove-coupon" href="#">[' + wc_phone_orders.Remove + ']</a>');
            });

            totals.calculate_subtotal();
        },

        save: function(val) {
            if (val && cart.coupons.indexOf(val) < 0) {
                cart.coupons.push(val);
                coupons.display();

                shipping.loadRates();
            }
        },

        onRemoveCoupon: function (e) {
            e.preventDefault();
            var coupon = $(this).closest('.coupons-list-item').data('coupon');
            var index = cart.coupons.indexOf(coupon);
            cart.coupons.splice(index, 1);
            coupons.display();
        },

        onEditCoupon: function(e) {
            e.preventDefault();
            $('#coupon-modal').modal({keyboard:true});
        },

        onSaveCoupon: function() {
            var val = $('#coupon-modal [name="coupon-value"]').val();
            coupons.save(val);
            $('#coupon-modal [name="coupon-value"]').val('').trigger('change');
            $('#coupon-modal').modal('hide');
        },

        onEscCoupon: function(e) {
            if (e.keyCode == 27) {
                $('#coupon-modal').modal('hide');
            }
            // var val = $('#coupon-modal [name="coupon-value"]').val();
            // coupons.save(val);
            // $('#coupon-modal [name="coupon-value"]').val('').trigger('change');
            // $('#coupon-modal').modal('hide');
        }
    };

    var itemslist = {
        init: function() {
            $( '#select-items' ).select2({
                minimumInputLength: 1,
                minimumResultsForSearch: -1,
                escapeMarkup: function(m) { return m; },
                multiple: false,
                width: '100%',
                ajax: {
                    url: ajaxurl,
                    dataType: 'json',
                    delay: 10,
                    data: function (params) {
                        var exclude = $.merge(exclude_products, $.map(cart.items, function(item) {
                                return item.product_id
                            }));
                        return {
                            action: 'woocommerce_json_search_products_and_variations',
                            security: wc_phone_orders.search_products_nonce,
                            term: params.term,
                            exclude: exclude
                        };
                    },
                    processResults: function (data, params) {
                        var terms = [];
                        if ( data ) {
                            $.each( data, function( id, text ) {
                                terms.push( { id: id, text: text } );
                            });
                        }
                        return {results: terms};
                    },
                    cache: true
                },
            }).on('select2:select', itemslist.onSelectItem);

            $(document).on('click', '[data-action="save-custom-item"]', this.onSaveCustomItem);
            $(document).on('submit', '#custom-item-modal form', this.onSaveCustomItem);

            $('.link-add-custom-item').click(this.onStartAddCustomItem);
            $(document).on('change', '.qty, .cost', this.onLineChanged);
            $(document).on('click', '.delete-order-item', this.onDeleteLine);

            $(document).on('keyup', '.qty', this.onQtyKeyUp);
            $(document).on('keydown', '.select2-search__field', this.onProductSearchKeyUp);

            $('#select-items').select2('open');
        },

        addLineItemToOrder: function(item_id, qty) {
            if (qty === undefined) {
                qty = 1;
            }

            $.post(
                ajaxurl,
                {
                    action: 'phone-orders-for-woocommerce',
                    method: 'load_item',
                    id: item_id
                },
                function (response) {
                    if (response.success == true) {
                        $('#order_line_items').append(response.data.html);
                        cart.items.push(response.data.item);
                        $('[name="order_item_qty[' + response.data.item.product_id + ']"]').focus();
                        totals.calculate_subtotal();
                    }
                    else {
                        alert('error');
                    }
                },
                'json'
            );
        },

        recalculate_row: function($row) {

            var qty = parseInt($row.find('.qty').val());
            var price = parseFloat($row.find('.cost').val());
            var id = $row.data('order_item_id');

            var amount = price * qty;

            $row.find('.total').text(amount);

            cart.items.forEach(function(item) {
                if(item.product_id == id) {
                    item.qty = qty;
                    item.line_total = amount;
                }
            });

            shipping.loadRates();
            totals.calculate_subtotal();

            var msg = '';

            var stockamount = $row.data('stockamount');
            if (stockamount !== null && stockamount !== undefined) {
                if (qty > stockamount) {
                    msg = 'Only ' + stockamount + ' items can be purchased';
                }
            }

            $row.find('.item-msg').text(msg);
        },

        onSelectItem: function (evt) {
            var item = evt.params.data;
            if (!$('.woc-line-items [data-type=id][value=' + item.id + ']').length) {
                itemslist.addLineItemToOrder(item.id);
            }
            $(this).val('').trigger('change.select2');
        },

        onSaveCustomItem: function (e) {
            e.preventDefault();

            var $form = $('#custom-item-modal form');

            if ( $('#input-custom-item-name').val() == '' ) {
                alert(wc_phone_orders.Field_Name_Required);
                return;
            }

            $.post(
                ajaxurl,
                {
                    action: 'phone-orders-for-woocommerce',
                    method: 'create_item',
                    data: $form.serialize()
                },
                function (response) {
                    if (response.success == true) {
                        var id = response.data.id;
                        itemslist.addLineItemToOrder(id, $form.find('[name=qty]').val());
                        $('#custom-item-modal').modal('hide');
                    }
                    else {
                        alert('error');
                    }
                },
                'json'
            );

        },

        onStartAddCustomItem: function(e) {
            e.preventDefault();
            $('#custom-item-modal').modal().on('shown.bs.modal', function () {
                $(this).find('input:first').focus();
            }).on('hide.bs.modal', function () {
                $(this).find('input').val('');
            });
        },

        onLineChanged: function () {
            itemslist.recalculate_row( $(this).closest('.item') );
        },

        onDeleteLine: function(e) {
            e.preventDefault();

            var $line_item = $(this).closest('.item');
            var id = $line_item.data('order_item_id');
            $line_item.remove();
            cart.items = cart.items.filter(function(item) {
                return item.product_id != id;
            });

            itemslist.recalculate_row( $line_item );
        },

        onQtyKeyUp: function(e) {
            if (e.keyCode == 13) {
                $('#select-items').select2('open');
            }
        },

        onProductSearchKeyUp: function(e) {
            if (e.keyCode == 9) {
                $( '#select-customer' ).select2('open');
            }
        }

    };

    var customer = {

        address_type: '',

        init: function() {
            $( '#select-customer' ).select2( {
                minimumInputLength: 0,
                multiple: false,
                escapeMarkup: function( m ) { return m; },
                placeholder: 'Guest',
                allowClear: false,
                ajax: {
                    url: ajaxurl,
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            action: 'woocommerce_json_search_customers',
                            security: wc_phone_orders.search_customers_nonce,
                            term: params.term
                        };
                    },
                    processResults: function (data, params) {
                        var results = $.map(data, function( text, id ) {
                            return { id: id, text: text };
                        });

                        return { results: results };
                    },
                    cache: true
                }
            } )
            .on('select2:select', customer.onSelectCustomer);
            // .on('select2:open', function(a,b,c) {
            //     if ( $('#search-customer-box .add_new_customer').length == 0 ) {
            //         $('#search-customer-box .select2-search--dropdown')
            //             .append('<li class="select2-results__option add_new_customer">' + wc_phone_orders.New_Customer + '</li>');
            //     }
            // });

            $('#create-new-customer').click(this.onStartCreateCustomer);
            $(document).on('submit', '.edit_customer_address', customer.onEditAddress);
            $(document).on('click', '[data-action="save-new-customer"]', customer.onSaveNewCustomer);
            $('.clear-customer').click(customer.onClearCustomer);
            $(document).on('click', '[data-edit-address]', customer.onStartEditAddress);
            $('#edit-address-modal [data-action="save-edit-address"]').click(customer.onSaveAddress);

            $('#edit-address-modal').on('shown.bs.modal', function () {
                $(this).find('input:first').focus();
            });

            $(document).on('change', '.ship-different-address', this.onChangeUseDifferentShipAddress);

            var inputs = $('#edit-address-modal input, #edit-address-modal select');
            var last_input = inputs.last();
            inputs.on('keyup', function (e) {
                if (e.keyCode == 13) {
                    if (last_input.is(this)) {
                        customer.onSaveAddress();
                    } else {
                        var index = inputs.index(this);
                        var next = inputs.eq(index + 1);
                        if (next) {
                            next.focus();
                        }
                    }
                }
            });
        },

        onStartCreateCustomer: function (e) {
            e && e.preventDefault();
            $('.custom-customer-form').trigger('reset');
            customer.showNewCustomerForm();
        },

        onChangeUseDifferentShipAddress: function() {
            var enabled = $('.ship-different-address').is(':checked');
            $('.shipping-details').toggle(enabled);
            cart.customer['ship-different-address'] = enabled;
            actions.setVisibility();
        },

        showNewCustomerForm: function() {
            var countries = wc_phone_orders.countries;

            $('#new-customer-modal').modal().on('shown.bs.modal', function () {
                $(this).find('input:first').focus();
                var $country = $('#input-new-customer-country');
                if (!($country.hasClass('select2-hidden-accessible'))) {
                    $country.unbind('change').on('change', function () {
                        var country = $country.val();
                        $('#input-new-customer-state').parent().remove();
                        var input = '<div>';
                        if (countries[country] === undefined || countries[country] instanceof Array) {
                            input += '<input type="text" class="form-control" id="input-new-customer-state" name="state"/>';
                        }
                        else {
                            input += '<select class="form-control" id="input-new-customer-state" name="state">';
                            $.each(countries[country], function (index, value) {
                                input += '<option value="' + index + '">' + value + '</option>';
                            });
                            input += '</select>';
                        }
                        input += '</div>';

                        $('[for=input-new-customer-state]').after(input);
                        if ($('#input-new-customer-state').prop("tagName") === 'SELECT') {
                            $('#input-new-customer-state').select2({
                                minimumResultsForSearch: Infinity
                            });
                        }
                    }).select2({
                        minimumResultsForSearch: Infinity
                    });
                }
                $country.change();

            }).on('hide.bs.modal', function () {
                $(this).find('input').val('');
            });
        },

        addCustomerToOrder: function(user_id) {
            $.get(
                ajaxurl,
                {
                    action: 'phone-orders-for-woocommerce',
                    method: 'get_customer',
                    data: user_id
                }, function (response) {
                    if (response.success) {
                        $('.customer-box').html(response.data.html);
                        cart.customer = response.data.fields;

                        if (cart.customer.id && cart.customer.id > 0) {
                            shipping.loadRates();
                            totals.calculate_subtotal();

                            $('#select-customer')
                                .append ('<option value="'
                                            + cart.customer.id + '">'
                                            + (cart.customer.billing_first_name ? cart.customer.billing_first_name : '')
                                            + ' '
                                            + (cart.customer.billing_last_name ? cart.customer.billing_last_name : '')
                                            + '</option>')
                                .val(cart.customer.id)
                                .trigger('change');
                        }

                        $('.clear-customer').toggle(cart.customer.id > 0);

                        actions.setVisibility();
                    }
                }
            );

        },

        onSelectCustomer: function (evt) {
            var item = evt.params.data;
            customer.addCustomerToOrder(item.id);
        },

        onEditAddress: function(e) {
            e && e.preventDefault();
            $.post(
                ajaxurl,
                {
                    action: 'phone-orders-for-woocommerce',
                    method: 'update_customer',
                    data: $('.edit_customer_address').serialize()
                },
                function (response) {
                    shipping.loadRates();
                    totals.calculate_subtotal();
                    actions.setVisibility();
                },
                'json'
            );
        },

        onSaveNewCustomer: function (e) {
            e.preventDefault();

            $.post(
                ajaxurl,
                {
                    action: 'phone-orders-for-woocommerce',
                    method: 'create_customer',
                    data: $('#new-customer-modal form').serialize()
                },
                function (response) {
                    if (response.success) {
                        var id = response.data.id;
                        customer.addCustomerToOrder(id);
                        $(this).val('').trigger('change.select2');
                        $('#new-customer-modal').modal('hide');
                    }
                    else {
                        alert(response.data);
                    }
                    actions.setVisibility();
                },
                'json'
            );
        },

        onClearCustomer: function(e) {
            e.preventDefault();
            e.stopPropagation();

            $('#select-customer').val('').trigger('change.select2');
            customer.addCustomerToOrder(0);
            shipping.loadRates();
			actions.setVisibility();
        },

        onStartEditAddress: function(e) {
            e.preventDefault();

            var form = $('#edit-address-modal form');
            customer.address_type = $(this).data('edit-address');

			// email logic
			if ( customer.address_type == 'shipping' )
					$('#edit-address-modal form #billing_email').hide();
			else
					$('#edit-address-modal form #billing_email').show();

            // prepare form
            form.trigger('reset');
            form.find('[name]').each(function(i, item) {
                var $this = $(this);
                var val = cart.customer[customer.address_type + '_' + $this.attr('name')];
                $this.val( val );
            });

            // show modal
            $('#edit-address-modal').modal();
        },

        onSaveAddress: function(e) {
            e && e.preventDefault();

            var form = $('#edit-address-modal form');

            $.each( form.serializeArray(), function(i, item) {
                var key = customer.address_type + '_' + item.name;
                cart.customer[key] = item.value;
            } );

            $('#edit-address-modal').modal('hide');
            $.get(
                ajaxurl,
                {
                    action: 'phone-orders-for-woocommerce',
                    method: 'get_formatted_address',
                    data: cart.customer
                },
                function (response) {
                    $('.customer-box').html(response.data.html);
                    var enabled = cart.customer['ship-different-address'];
                    $('.ship-different-address').prop('checked', enabled);
					if( enabled ) {
						$('.shipping-details').toggle();
                    }
                }
            );

            shipping.loadRates();
			actions.setVisibility();
            totals.recalculate();
        }

    };

    var discount = {
        init: function() {
            $('.edit-discount-modal').click(discount.onStartEditDiscount);
            $('[data-action="save-discount"]').click(discount.onSaveDiscount);
            $('[data-action="remove-discount"]').click(discount.onRemoveDiscount);

            $('#discount-modal').on('shown.bs.modal', function () {
                $('[name="discount-amount"]').focus();
                $('[name="discount-amount"]')[0].select();
            });

            $('[name="discount-type"]').change(function() {
                $('.discount-type-toggler .active').removeClass('active');
                $(this).parent().addClass('active');
            });

            $('#discount-modal form').submit(function(e) {
                e.preventDefault();
                $('[data-action="save-discount"]').click();
                return false;
            });
        },

        saveDiscount: function(val) {
            cart.discount = val;
            totals.calculate_subtotal();
            $('.edit-discount-modal').text((cart.discount.amount ? wc_phone_orders.Discount : wc_phone_orders.Add_Discount) + ':');
        },

        onStartEditDiscount: function(e) {
            e.preventDefault();
            $('#discount-modal').modal();
            $('#discount-modal [name="discount-amount"]').val(cart.discount.amount || 0);
            $('[name="discount-type"]').prop('checked', false);
            $('[name="discount-type"][value="' + cart.discount.type + '"]').prop('checked', true);
        },

        onSaveDiscount: function() {
            var data = $('#discount-modal form').serializeArray(),
                _discount = {};
            data.forEach(function(item) {
                if (item.name == 'discount-type') {
                    _discount.type = item.value;
                } else if (item.name == 'discount-amount') {
                    _discount.amount = parseFloat(item.value);
                }
            });
            discount.saveDiscount(_discount);
            $('#discount-modal').modal('hide');
        },

        onRemoveDiscount: function() {
            discount.saveDiscount({});
            $('#discount-modal').modal('hide');
        }
    };

    var shipping = {

        init: function() {
            $('.edit-shipping-modal').click(this.onStartEditShipping);
            $('[data-action="save-shipping"]').click(this.onSave);
            $('#shipping-modal form').submit(this.onSave);
            $('[data-action="remove-shipping"]').click(this.onRemove);

            $('#shipping-modal').on('shown.bs.modal', function () {
                $('input:first', this).focus();
            });
        },

        loadRates: function() {
            $.ajax({
                url: ajaxurl,
                data: {
                    'action': 'phone-orders-for-woocommerce',
                    'method': 'get_shipping_rates',
                    'cart': cart
                },
                dataType: 'json',
                type: 'POST',
                success: function(response) {
                    if (response.success) {
                        var shipping_id = cart.shipping ? cart.shipping.id : null;
                        var html = $.map(response.data, function(val) {
                            return '<li>'
                                    + '<input name="shipping_method" type="radio" value="' + val.id + '" id="shipping_method_'
                                    + val.id + '" class="shipping_method"'
                                    + ' data-id="' + val.id + '"'
                                    + ' data-shipping=\'' + JSON.stringify(val) + '\'>'
                                    + ' <label for="shipping_method_' + val.id + '">' + val.label
                                    + (val.cost ? (': <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span>' + val.cost + '</span></label>') : '')
                                + '</li>';
                        }).join('');

                        if (!html) {
                            html = wc_phone_orders.No_Shipping_Methods_Available;
                        }

                        $('#shipping_method').html(html);

                        if (cart.shipping && cart.shipping.id) {
                            // check selected shipping
                            var loadedRate = null;
                            response.data.forEach(function(item) {
                                if (item.id == cart.shipping.id) {
                                    loadedRate = item;
                                }
                            });

                            if (!loadedRate) {
                                cart.shipping = {};
                                shipping.onStartEditShipping();
                            } else if (loadedRate.cost != cart.shipping.cost) {
                                cart.shipping = loadedRate;
                            }

                            shipping.updateTotals();
                        }

                    }
                }
            });
        },

        onStartEditShipping: function(e) {
            if (e) e.preventDefault();

            $('[name="shipping_method"]').prop('checked', false);
            if (cart.shipping && cart.shipping.id) {
                $('[data-id="' + cart.shipping.id + '"]').prop('checked', true);
            }

            $('#shipping-modal').modal({keyboard: true});
        },

        onSave: function(e) {
            e && e.preventDefault();

            cart.shipping = $(':checked', '#shipping_method').data('shipping');
            shipping.updateTotals();
            $('#shipping-modal').modal('hide');
        },

        onRemove: function(e) {
            cart.shipping = {};
            shipping.updateTotals();
            $('#shipping-modal').modal('hide');
        },

        updateTotals: function() {
            if (cart.shipping && cart.shipping.id) {
                $('.edit-shipping-modal').text('Shipping');
                $('.total-shipping-label').text(cart.shipping.label);
                var cost = cart.shipping.cost ? ('<span class="woocommerce-Price-currencySymbol">$</span>' + parseFloat(cart.shipping.cost).toFixed(2)) : '-';
                $('.shipping-value').html(cost);
            } else {
                $('.edit-shipping-modal').text(wc_phone_orders.Add_Shipping + ':');
                $('.total-shipping-label').text('');
                $('.shipping-value').text('');
            }

            totals.calculate_subtotal();
        }

    };

    var taxes = {
        init: function() {
            $('.edit-tax-modal').click(this.onStartEdit);
            $('[data-action="add-tax"]').click(this.onSave);
            $('.wc-order-totals').on('click', '[data-action="remove-tax"]', this.onRemove);
        },

        display: function() {
            $('.taxes-list-item').remove();

            cart.taxes.forEach(function(tax) {
                var new_item = $('.taxes-list-add')
                    .clone()
                    .removeClass('taxes-list-add')
                    .addClass('taxes-list-item')
                    .insertBefore($('.taxes-list-add'))
                    .data('tax_id', tax.id);

                new_item.find('.label-total').html(tax.label);
                new_item.find('.tax-value').html(tax.rate + ' <a data-action="remove-tax" href="#">[' + wc_phone_orders.Remove + ']</a>');
            });
        },

        onStartEdit: function(e) {
            e.preventDefault();
            $('[name="add_order_tax"]').prop('checked', false);
            //$('#add_order_tax_' + cart.taxes.id).prop('checked', true);
            $('#tax-modal').modal();
        },

        onSave: function(e) {
            var id = $(':checked', '.tax-form').val();

            if (! id) return;

            for (var i = 0; i < cart.taxes.length; i++) {
                if (cart.taxes[i].id === id) {
                    alert(wc_phone_orders.Tax_Already_Added);
                    return;
                }
            }

            cart.taxes.push({
                id: id,
                label: $('label[for="add_order_tax_' + id + '"]').text(),
                rate: $('.order_tax_rate_' + id).text()
            });

            taxes.display();
            $('#tax-modal').modal('hide');
        },

        onRemove: function(e) {
            e.preventDefault();
            var tax_id = $(this).closest('.taxes-list-item').data('tax_id');
            for (var i = 0; i < cart.taxes.length; i++) {
                if (cart.taxes[i].id === tax_id) {
                    cart.taxes.splice(i, 1);
                    break;
                }
            }
            taxes.display();
        }
    };

    var settings = {

        auto_recalculate: false,
        order_status: 'wc-pending',

        init: function() {
            settings.updateValues();
            $('#wpo-settings-auto_recalculate').click(this.onAutoRecalculateChange);
            $('#wpo-settings-order_status').change(this.onAutoRecalculateChange);
        },

        updateValues: function() {
            settings.auto_recalculate = $('#wpo-settings-auto_recalculate').is(':checked');
            $('[data-action="recalculate"]').toggle(!this.auto_recalculate);
            settings.order_status = $('#wpo-settings-order_status').val();
        },

        onAutoRecalculateChange: function () {

            settings.updateValues();

            $.ajax({
                url: ajaxurl,
                data: {
                    action: 'phone-orders-for-woocommerce',
                    method: 'save_settings',
                    data: {
                        'auto_recalculate': settings.auto_recalculate ? 1 : 0,
                        'order_status': settings.order_status
                    }
                },
                dataType: 'json',
                type: 'POST'
            });
        }
    };

    var totals = {
        init: function () {
            $('[data-action="recalculate"]').click(this.recalculate);
        },

        set_status_updated_to: function (toggle) {
            $('.order-total-line').toggleClass('order-total-line--updated', toggle);
            $('.order-taxes-line').toggleClass('order-total-line--updated', toggle);
            $('.ajax-msg').html('');
        },

        calculate_subtotal: function () {
            var subtotal = 0,
                discount_value = 0,
                discount_amount = (cart.discount && cart.discount.amount) ? parseFloat(cart.discount.amount) : 0;

            for (var i = 0; i < cart.items.length; i++) {
                subtotal += parseFloat(cart.items[i].line_total);
            }

            discount_value = (cart.discount.type === 'percent') ? subtotal * discount_amount / 100 : discount_amount;

            $('.discount-value').html(discount_value.toFixed(2));
            $('.subtotal-value').html(subtotal.toFixed(2));

            totals.set_status_updated_to(false);

            if (settings.auto_recalculate) {
                totals.recalculate();
            }
        },

        recalculate: function () {
            $.ajax({
                url: ajaxurl,
                data: {
                    action: 'phone-orders-for-woocommerce',
                    method: 'recalculate',
                    cart: cart
                },
                dataType: 'json',
                type: 'POST',
                success: function(response) {
                    if (response.success) {

                        if (response.data && response.data.taxes) {
                            $('.order-taxes-line .total').html(response.data.taxes);
                        }

                        if (response.data && response.data.total) {
                            $('.order-total-line .total').html(response.data.total);
                        }

                        totals.set_status_updated_to(true);
                    }
                }
            });
        },

        clear_form: function () {
            cart = {
                items:    [],
                customer: {},
                coupons:  [],
                discount: {},
                shipping: {},
                taxes:    [],
                customer_note: '',
                private_note: ''
            };

            $('#order_line_items, #customer-order-note, #private-order-note').html('');
            customer.addCustomerToOrder(0);
            totals.recalculate();

            $('#select-items').select2('open');
        }
    };

    var actions = {
        init: function () {
            $('[data-action="create-order"]').click(this.createOrder);
            $('[data-action="new-order"]').click(this.newOrder);
            // $('[data-action="pay-order"]').click(this.pay);
            $('[data-action="send-order"]').click(this.send);
            $('[data-action="view-order"]').click(this.view);

            this.setVisibility();
        },

        showMessage: function (msg) {
            $('.order-actions .description-content').html(msg);
            $('.order-actions .description').show();
        },

        setVisibility: function () {

            var show_create_order = !cart.order_id && !$.isEmptyObject(cart.customer);
            $('[data-action="create-order"]').toggle(show_create_order);

            var show_view_order = !!cart.order_id;
            $('[data-action="view-order"]').toggle(show_view_order);

            var show_send_order = !!cart.order_id && !!cart.customer.billing_email;
            $('[data-action="send-order"]').toggle(show_send_order);

            var show_pay_order = !!cart.order_id;
            $('[data-action="pay-order"]').toggle(show_pay_order);

            var show_new_order = !!cart.order_id;
            $('[data-action="new-order"]').toggle(show_new_order);

            $('.order-actions').toggle(show_create_order || show_view_order || show_send_order || show_pay_order || show_new_order);
        },

        newOrder: function () {
            location.reload();
        },

        pay: function (e) {
            e.preventDefault();
        },

        send: function (e) {
            e.preventDefault();
            $.ajax({
                url: ajaxurl,
                data: {
                    action: 'phone-orders-for-woocommerce',
                    method: 'create_order_email_invoice',
                    order_id: cart.order_id
                },
                dataType: 'json',
                type: 'POST',
                success: function(response) {
                    if (response.success) {
                        totals.set_status_updated_to(true);
                        actions.showMessage( response.data.message );
                    } else {
                        actions.showMessage( response.data );
                    }
                }
            });
        },

        view: function () {
            window.open("post.php?post=" + cart.order_id + "&action=edit");
        },

        createOrder: function(e) {
            e.preventDefault();
            $.ajax({
                url: ajaxurl,
                data: {
                    action: 'phone-orders-for-woocommerce',
                    method: 'create_order',
                    cart: cart
                },
                dataType: 'json',
                type: 'POST',
                success: function(response) {
                    if (response.success) {
                        totals.set_status_updated_to(true);
                        actions.showMessage( response.data.message );
                        cart.order_id = response.data.order_id;
                        $('#woo-phone-orders').addClass('order-created');
                        $('[data-action="pay-order"]').attr('href', response.data.payment_url);
                    } else {
                        actions.showMessage( response.data );
                    }
                    actions.setVisibility();
                }
            });
        }
    };

    itemslist.init();
    customer.init();
    coupons.init();
    discount.init();
    shipping.init();
    // taxes.init();
    settings.init();
    totals.init();
    actions.init();


    $('#customer-note').change(function() {
        cart.customer_note = $(this).val();
    });

    $('#private-note').change(function() {
        cart.private_note = $(this).val();
    });

    $('[data-action="log"]').click(function(e) {
        console.log(cart);
    });

    $('[data-action="test"]').click(function(e) {
        $('#pay-modal').modal();
    });
    function reset() {

    }


});