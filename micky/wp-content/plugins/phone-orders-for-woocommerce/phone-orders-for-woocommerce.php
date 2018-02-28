<?php
/**
 * Plugin Name: Phone Orders for Woocommerce
 * Plugin URI:
 * Description: Create manual/phone orders in Woocommerce quickly
 * Author: AlgolPlus
 * Author URI: http://algolplus.com/
 * Version: 2.5
 * Text Domain: phone-orders-for-woocommerce
 * WC requires at least: 3.0
 * WC tested up to: 3.3
 *
 * Copyright: (c) 2017 AlgolPlus LLC. (algol.plus@gmail.com)
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package     phone-orders-for-woocommerce
 * @author      AlgolPlus LLC
 * @Category    Plugin
 * @copyright   Copyright (c) 2017 AlgolPlus LLC
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

// For backend only
if ( is_admin() )  {

	if ( ! function_exists( 'wc3' ) ) {
		/**
		* Handle WooCommerce 3.0.0 property getters.
		*
		* @param  object $object   The object to get the property from.
		* @param  string $property The property to get.
		* @return mixed            The property.
		*/
		function wc3( $object, $property ) {
			return version_compare( WC_VERSION, '3.0.0', '<' ) ? $object->$property : $object->{"get_$property"}();
		}
	}

	include_once 'classes/class-wc-phone-orders-main.php';
	include_once 'classes/class-wc-phone-orders-ajax.php';
	new WC_Phone_Orders_Main();
}
