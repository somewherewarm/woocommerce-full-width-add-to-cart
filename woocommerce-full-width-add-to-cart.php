<?php
/*
Plugin Name: WooCommerce Stacked Add-to-Cart Product Forms
Description: A handy plugin for stacking the add-to-cart section of complex WooCommerce product types under the main image and summary. Useful if the add-to-cart section of your products appears very narrow or squeezed.
Version:     1.1.1
Author:      SomewhereWarm
Author URI:  http://www.somewherewarm.net/
*/

class WC_Full_Page_Add_To_Cart {

	public static function init() {

		add_action( 'init', __CLASS__ . '::wc_fw_single_add_to_cart' );

		// Add settings section under "Products->Display"
		add_filter( 'woocommerce_product_settings', __CLASS__ . '::wc_fw_all_settings' );

	}

	public static function wc_fw_single_add_to_cart() {

		// Unhook 'woocommerce_template_single_add_to_cart' from 'woocommerce_single_product_summary' 30
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );

		// Hook wc_fw_template_single_add_to_cart into 'woocommerce_template_single_add_to_cart' 30 and run 'woocommerce_single_product_summary' through it only if the product type doesn't need to be moved.
		add_action( 'woocommerce_single_product_summary', __CLASS__ . '::wc_fw_template_single_add_to_cart', 30 );

		// Hook wc_fw_single_add_to_cart_after_summary into 'woocommerce_after_single_product_summary' 30 and run 'woocommerce_single_product_summary' through it if the product type needs to be moved.
		add_action( 'woocommerce_after_single_product_summary', __CLASS__ . '::wc_fw_single_add_to_cart_after_summary', 5 );
	}

	public static function wc_fw_get_types_to_move() {

		$moved_types = get_option( 'wc_fw_add_to_cart_layout_types', array() );

		return apply_filters( 'woocommerce_full_width_add_to_cart_types', $moved_types );
	}

	public static function wc_fw_single_add_to_cart_after_summary() {

		global $product;

		// Get types to move
		$moved_types = self::wc_fw_get_types_to_move();

		if ( ! empty( $moved_types ) && is_array( $moved_types ) && in_array( $product->product_type, $moved_types ) ) {
			echo '<div class="add-to-cart-summary" style="clear:both;">';
			woocommerce_template_single_add_to_cart();
			echo '</div>';
		}
	}

	public static function wc_fw_template_single_add_to_cart() {

		global $product;

		// Get types to move
		$moved_types = self::wc_fw_get_types_to_move();

		if ( empty( $moved_types ) || ( ! is_array( $moved_types ) ) )
			woocommerce_template_single_add_to_cart();
		elseif ( ! in_array( $product->product_type, $moved_types ) )
			woocommerce_template_single_add_to_cart();

	}

	public static function wc_fw_all_settings( $settings ) {

		$fw_setting = array(
			'id'                => 'wc_fw_add_to_cart_layout_types',
			'title'             => __( 'Force Stacked Layout', 'woocommerce-full-width-add-to-cart' ),
			'type'              => 'multiselect',
			'class'             => 'chosen_select',
			'css'               => 'width: 450px;',
			'desc'              => '<div>' . __( 'The product types selected here will use a modified product details layout, with the add-to-cart form stacked under the main product image and summary.', 'woocommerce-full-width-add-to-cart' ) . '</div>',
			'desc_tip'          => true,
			'default'           => '',
			'options'           => self::get_product_types(),
			'custom_attributes' => array(
				'data-placeholder' => __( 'Select some product types&hellip;', 'woocommerce-full-width-add-to-cart' )
			)
		);

		$new_settings = array();

		foreach ( $settings as $i => $setting ) {
			$new_settings[] = $settings[ $i ];
			if ( $setting[ 'id' ] === 'woocommerce_enable_ajax_add_to_cart' ) {
				$new_settings[] = $fw_setting;
			}
		}

		return $new_settings;

	}

	public static function get_product_types() {

		$types = array();

		$terms = get_terms( 'product_type', array( 'hide_empty' => 0 ) );

		foreach ( $terms as $term )
			$types[ $term->slug ] = ucfirst( $term->name );

		return $types;
	}
}

WC_Full_Page_Add_To_Cart::init();
