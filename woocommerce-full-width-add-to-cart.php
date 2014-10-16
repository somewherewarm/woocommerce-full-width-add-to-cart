<?php
/*
Plugin Name: WooCommerce Full-Width Add to Cart
Description: By default, WooCommerce places the add-to-cart product template for all products inside the "summary" section, leaving very limited space for product types with long add-to-cart forms. This plugin allows you to move the WooCommerce single add-to-cart content right before the tabs section, only for the specified product types.
Version:     1.0
Author:      Manos Psychogyiopoulos, Bryce Adams
Author URI:  http://www.woothemes.com/
License:     GPL v2
*/

class WC_Full_Page_Add_To_Cart {

	public static function init() {

		add_action( 'init', __CLASS__ . '::wc_fw_single_add_to_cart' );

	}

	function wc_fw_single_add_to_cart() {

		// Unhook 'woocommerce_template_single_add_to_cart' from 'woocommerce_single_product_summary' 30
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );

		// Hook wc_fw_template_single_add_to_cart into 'woocommerce_template_single_add_to_cart' 30 and run 'woocommerce_single_product_summary' through it only if the product type doesn't need to be moved.
		add_action( 'woocommerce_single_product_summary', __CLASS__ . '::wc_fw_template_single_add_to_cart', 30 );

		// Hook wc_fw_single_add_to_cart_after_summary into 'woocommerce_after_single_product_summary' 30 and run 'woocommerce_single_product_summary' through it if the product type needs to be moved.
		add_action( 'woocommerce_after_single_product_summary', __CLASS__ . '::wc_fw_single_add_to_cart_wrapper_start', 4 );
		add_action( 'woocommerce_after_single_product_summary', __CLASS__ . '::wc_fw_single_add_to_cart_after_summary', 5 );
		add_action( 'woocommerce_after_single_product_summary', __CLASS__ . '::wc_fw_single_add_to_cart_wrapper_end', 6 );
	}

	function wc_fw_get_types_to_move() {

		return apply_filters( 'woocommerce_full_width_add_to_cart_types', array( 'composite' ) );
	}

	function wc_fw_single_add_to_cart_wrapper_start() {
	    echo '<div class="add-to-cart-summary" style="clear:both;">';
	}

	function wc_fw_single_add_to_cart_wrapper_end() {
	    echo '</div>';
	}

	function wc_fw_single_add_to_cart_after_summary() {

		global $product;

		// Get types to move
		$moved_types = self::wc_fw_get_types_to_move();

		if ( ! empty( $moved_types ) && is_array( $moved_types ) && in_array( $product->product_type, $moved_types ) ) {
			woocommerce_template_single_add_to_cart();
		}
	}

	function wc_fw_template_single_add_to_cart() {

		global $product;

		// Get types to move
		$moved_types = self::wc_fw_get_types_to_move();

		if ( empty( $moved_types ) || ( ! is_array( $moved_types ) ) )
			woocommerce_template_single_add_to_cart();
		elseif ( ! in_array( $product->product_type, $moved_types ) )
			woocommerce_template_single_add_to_cart();

	}

}

WC_Full_Page_Add_To_Cart::init();
