<?php
/*
Plugin Name: WooCommerce Full-Width Add to Cart Forms
Description: If the add-to-cart form of your products appears very narrow or squeezed, you can use this plugin to move the add-to-cart form under the product image and summary section. The plugin allows you to select specific product types that will use this modified, full-width layout.
Version:     1.0
Author:      SomewhereWarm
Author URI:  http://www.somewherewarm.net
*/

class WC_Full_Page_Add_To_Cart {

	public static function init() {

		add_action( 'init', __CLASS__ . '::wc_fw_single_add_to_cart' );

		// Add "Add-to-Cart Layout" section under "Products"
		add_filter( 'woocommerce_get_sections_products', __CLASS__ . '::wc_fw_add_section' );

		// Add settings in the new section
		add_filter( 'woocommerce_get_settings_products', __CLASS__ . '::wc_fw_all_settings', 10, 2 );
	}

	function wc_fw_single_add_to_cart() {

		// Unhook 'woocommerce_template_single_add_to_cart' from 'woocommerce_single_product_summary' 30
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );

		// Hook wc_fw_template_single_add_to_cart into 'woocommerce_template_single_add_to_cart' 30 and run 'woocommerce_single_product_summary' through it only if the product type doesn't need to be moved.
		add_action( 'woocommerce_single_product_summary', __CLASS__ . '::wc_fw_template_single_add_to_cart', 30 );

		// Hook wc_fw_single_add_to_cart_after_summary into 'woocommerce_after_single_product_summary' 30 and run 'woocommerce_single_product_summary' through it if the product type needs to be moved.
		add_action( 'woocommerce_after_single_product_summary', __CLASS__ . '::wc_fw_single_add_to_cart_after_summary', 5 );
	}

	function wc_fw_get_types_to_move() {

		$moved_types = get_option( 'wc_fw_add_to_cart_layout_types', array() );

		return apply_filters( 'woocommerce_full_width_add_to_cart_types', $moved_types );
	}

	function wc_fw_single_add_to_cart_after_summary() {

		global $product;

		// Get types to move
		$moved_types = self::wc_fw_get_types_to_move();

		if ( ! empty( $moved_types ) && is_array( $moved_types ) && in_array( $product->product_type, $moved_types ) ) {
			echo '<div class="add-to-cart-summary" style="clear:both;">';
			woocommerce_template_single_add_to_cart();
			echo '</div>';
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

	function wc_fw_add_section( $sections ) {

		$sections[ 'wc_fw_add_to_cart' ] = __( 'Add-to-Cart Form Layout', 'woocommerce-full-width-add-to-cart' );

		return $sections;
	}

	function wc_fw_all_settings( $settings, $current_section ) {

		if ( $current_section == 'wc_fw_add_to_cart' ) {

			$settings = array();

			// Add Title to the Settings
			$settings[] = array( 'name' => __( 'Add-to-Cart Form Layout Settings', 'woocommerce-full-width-add-to-cart' ), 'type' => 'title', 'desc' => __( 'If the add-to-cart form of your products appears very narrow or squeezed, you can move the add-to-cart section under the product image and summary.', 'woocommerce-full-width-add-to-cart' ), 'id' => 'wc_fw_add_to_cart' );

			// Add product type multi-select
			$settings[] = array(
				'id'				=> 'wc_fw_add_to_cart_layout_types',
				'title'				=> __( 'Product Types', 'woocommerce-full-width-add-to-cart' ),
				'type'				=> 'multiselect',
				'class'				=> 'chosen_select',
				'css'				=> 'width: 450px;',
				'desc'				=> '<div>' . __( 'The product types selected here will use the modified, full-width layout.', 'woocommerce-full-width-add-to-cart' ) . '</div>',
				'default'			=> '',
				'options'			=> self::get_product_types(),
				'custom_attributes' => array(
					'data-placeholder' => __( 'Select some product types&hellip;', 'woocommerce-full-width-add-to-cart' )
				)
			);

			$settings[] = array( 'type' => 'sectionend', 'id' => 'wc_fw_add_to_cart' );

			return $settings;

		} else {

			return $settings;

		}

	}

	function get_product_types() {

		$types = array();

		$terms = get_terms( 'product_type', array( 'hide_empty' => 0 ) );

		foreach ( $terms as $term )
			$types[ $term->slug ] = ucfirst( $term->name ) . ' (' . $term->slug . ')';

		return $types;
	}
}

WC_Full_Page_Add_To_Cart::init();
