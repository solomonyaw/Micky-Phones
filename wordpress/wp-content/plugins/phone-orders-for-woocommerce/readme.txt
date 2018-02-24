=== Phone Orders for Woocommerce ===
Contributors: algolplus
Donate link: http://algolplus.com/plugins/
Tags: woocommerce, backend, phone, phone orders, manual, manual orders
Requires at least: 4.6
Tested up to: 4.9
Stable tag: 1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Create manual/phone orders in Woocommerce faster!

== Description ==

The plugin speeds up creating manual/phone orders in Woocommerce backend.

The plugin requires Woocommerce 3.0+ and ["manage_woocommerce"](https://docs.woocommerce.com/document/roles-capabilities/)  capability!

Visit "Woocommerce" > "Make New Order". After creating the order, you will see buttons "View Order", "Pay Order", "Send Invoice".

= Features =

* it works much faster than standard "Orders -> Add New"
* create new products on fly
* add new customer from same page
* apply coupons to the order

== Installation ==

= Automatic Installation =
Go to Wordpress dashboard, click  Plugins / Add New  , type 'woocommerce phone orders' and hit Enter.
Install and activate plugin, visit WooCommerce > Export Orders.

= Manual Installation =
[Please, visit the link and follow the instructions](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation)


== Frequently Asked Questions ==

= How to pay order?  =
If you pay directly from admin area - use [this free plugin](https://wordpress.org/plugins/woo-mp/). It supports Stripe and Authorize.Net.
If you pay as new client - you should use [this plugin](https://codecanyon.net/item/shop-as-customer-for-woocommerce/7043722) to switch customer on frontend pages.

= Button "Create Order" does nothing  =
Probably, there is a conflict with another plugin. [Please, check javascript errors](https://codex.wordpress.org/Using_Your_Browser_to_Diagnose_JavaScript_Errors#Step_3:_Diagnosis) 

= I need extra features ! =
Please, contact aprokaev@gmail.com.


== Screenshots ==

1. Filled order 
2. Order was created
3. Edit customer details
4. Adjust discount type and amount
5. Select shipping method 

== Changelog ==

= 2.5 2018-02-06 =
* Added "Free Shipping" method (in admin area only). Don't forget to assign it to necessary shipping zones!

= 2.4 2017-12-13 =
* Bug fixed - "create customer" fills address and phone

= 2.3 2017-11-17 =
* Bug fixed - localization works now

= 2.2 2017-09-07 =
* Added field "Private Note"
* Bug fixed - fill billing email for registered user

= 2.1 2017-08-04 =
* Create new products on fly
* Add new customer from same page
* Apply coupons to the order

= 2.0 2017-07-04 =
* Rebuild UI (show buttons after order creation )
* Skip out of stock products

= 1.0 2017-06-10 =
* Initial release