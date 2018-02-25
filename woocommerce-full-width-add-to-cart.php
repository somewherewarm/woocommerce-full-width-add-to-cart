<?php
/**
 * Plugin Name: WooCommerce Stacked Product Layout
 * Description: A handy plugin for stacking the add-to-cart form content of complex WooCommerce product types below the main product image and summary. Useful if the add-to-cart section of your products appears very narrow or squeezed.
 * Version:     1.2.0
 * Author:      SomewhereWarm
 * Author URI:  https://somewherewarm.gr/
 *
 * Text Domain: woocommerce-full-width-add-to-cart
 * Domain Path: /languages/
 *
 * Requires at least: 4.4
 * Tested up to: 4.9
 *
 * WC requires at least: 2.6
 * WC tested up to: 3.3
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

/**
 * Main plugin class.
 */
class WC_Stacked_Product_Layout {

	/**
	 * Plugin version.
	 * @var string
	 */
	public static $version = '1.2.0';

	/**
	 * Legacy settings mode.
	 * @var bool
	 */
	private static $use_legacy_settings = true;

	/**
	 * Runtime cache for WC product types.
	 * @var array
	 */
	private static $product_types_cache;

	/**
	 * Plugin URL getter.
	 *
	 * @return string
	 */
	public static function plugin_url() {
		return untrailingslashit( plugins_url( '/', __FILE__ ) );
	}

	/**
	 * True if using WC < 3.3 settings.
	 *
	 * @return bool
	 */
	private static function using_legacy_settings() {
		return self::$use_legacy_settings;
	}

	/**
	 * Initialization.
	 */
	public static function init() {
		add_action( 'woocommerce_loaded', array( __CLASS__, 'load' ) );
	}

	/**
	 * Load plugin.
	 */
	public static function load() {

		// Use customizer and new option keys when WC > 3.3.
		if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '3.3.0' ) >= 0 ) {
			self::$use_legacy_settings = false;
		}

		self::update_options();
		self::add_hooks();
	}

	/**
	 * Check plugin version and update plugin options.
	 */
	public static function update_options() {

		// Check if new options exist -- if not, copy them over from legacy option.
		if ( ! self::using_legacy_settings() ) {

			$layout_option = get_option( 'wc_spl_product_details_layout' );

			if ( ! $layout_option ) {
				$legacy_option = get_option( 'wc_fw_add_to_cart_layout_types', array() );
				update_option( 'wc_spl_product_details_layout', ! empty( $legacy_option ) ? 'stacked' : 'default' );
				update_option( 'wc_spl_product_details_layout_types', ! empty( $legacy_option ) ? $legacy_option : array() );
			}
		}
	}

	/**
	 * Add hooks.
	 */
	public static function add_hooks() {

		// Hook the 'woocommerce_template_single_add_to_cart' function to the 'woocommerce_after_single_product_summary' hook if needed.
		add_action( 'woocommerce_before_single_product', array( __CLASS__, 'single_add_to_cart' ) );

		if ( self::using_legacy_settings() ) {
			// Display options under 'WooCommerce > Settings > Products > Display'.
			add_filter( 'woocommerce_product_settings', array( __CLASS__, 'all_settings' ) );
		} else {
			// Display options in Customizer.
			add_action( 'customize_register', array( __CLASS__, 'add_customizer_section' ) );
			// add_action( 'customize_controls_print_styles', array( __CLASS__, 'add_customizer_styles' ) );
			add_action( 'customize_controls_print_scripts', array( __CLASS__, 'add_customizer_scripts' ), 30 );
		}
	}

	/**
	 * Adds a new 'Product Details' section under the WC customizer options.
	 *
	 * @param  WP_Customize_Manager  $wp_customize
	 */
	public static function add_customizer_section( $wp_customize ) {

		require_once( 'includes/class-wc-spl-customize-multiple-select-control.php' );

		$wp_customize->add_section(
			'wc_spl_product_details',
			array(
				'title'    => __( 'Product Details', 'woocommerce-full-width-add-to-cart' ),
				'priority' => 30,
				'panel'    => 'woocommerce-full-width-add-to-cart',
			)
		);

		$wp_customize->add_setting(
			'wc_spl_product_details_layout',
			array(
				'type'              => 'option',
				'capability'        => 'manage_woocommerce',
				'sanitize_callback' => array( __CLASS__, 'sanitize_product_layout' ),
				'default'           => 'default'
			)
		);

		$wp_customize->add_control(
			'wc_spl_product_details_layout',
			array(
				'label'       => __( 'Layout', 'woocommerce-full-width-add-to-cart' ),
				'description' => __( 'Choose the Stacked option to move the add-to-cart form below the main product image and summary.', 'woocommerce-full-width-add-to-cart' ),
				'section'     => 'wc_spl_product_details',
				'settings'    => 'wc_spl_product_details_layout',
				'type'        => 'select',
				'choices'     => array(
					'default' => __( 'Default', 'woocommerce-full-width-add-to-cart' ),
					'stacked' => __( 'Stacked', 'woocommerce-full-width-add-to-cart' )
				)
			)
		);

		$wp_customize->add_setting(
			'wc_spl_product_details_layout_types',
			array(
				'type'              => 'option',
				'capability'        => 'manage_woocommerce',
				'sanitize_callback' => array( __CLASS__, 'sanitize_stacked_product_types' ),
				'default'           => ''
			)
		);

		$wp_customize->add_control(
			new WC_SPL_Customize_Multiple_Select_Control( $wp_customize, 'wc_spl_product_details_layout_types', array(
				'label'       => __( 'Restrict To&hellip;', 'woocommerce-full-width-add-to-cart' ),
				'description' => __( 'Only use this layout with specific product types?', 'woocommerce-full-width-add-to-cart' ),
				'section'     => 'wc_spl_product_details',
				'settings'    => 'wc_spl_product_details_layout_types',
				'type'        => 'wc-spl-multiple-select',
				'choices'     => self::get_product_types()
			) )
		);
	}

	/**
	 * Customizer styles.
	 */
	public static function add_customizer_styles() {
		?>
		<style type="text/css">
			.customize-control-wc_spl_product_details_layout_types label {
				cursor: default;
			}
		</style>
		<?php
	}

	/**
	 * Customizer scripts.
	 */
	public static function add_customizer_scripts() {

		?><script type="text/javascript">
			jQuery( document ).ready( function( $ ) {

				wp.customize.bind( 'ready', function() {

					$( document.body ).trigger( 'wc-enhanced-select-init' );

					var $layout_select = $( '#customize-control-wc_spl_product_details_layout select' ),
						$layout_types  = $( '#customize-control-wc_spl_product_details_layout_types' );

					$layout_select.on( 'change', function() {

						if ( 'stacked' === $( this ).val() ) {
							$layout_types.slideDown( 200 );
						} else {
							$layout_types.hide();
						}

						return false;
					} );

					$layout_types.on( 'click', 'label', function() {
						return false;
					} );

					$layout_select.change();

				} );

			} );
		</script><?php
	}

	/**
	 * Sanitize the 'Product Details > Layout' option.
	 *
	 * @param  string  $value
	 * @return string
	 */
	public static function sanitize_product_layout( $value ) {
		$options = array( 'default', 'stacked' );
		return in_array( $value, $options, true ) ? $value : 'default';
	}

	/**
	 * Sanitize the 'Product Details > Product Types' option.
	 *
	 * @param  array  $value
	 * @return array
	 */
	public static function sanitize_stacked_product_types( $value ) {
		$value = array_intersect( $value, array_keys( self::get_product_types() ) );
		return $value;
	}

	/**
	 * Hook the 'woocommerce_template_single_add_to_cart' function to the 'woocommerce_after_single_product_summary' hook if needed.
	 */
	public static function single_add_to_cart() {

		// Unhook 'woocommerce_template_single_add_to_cart' from 'woocommerce_single_product_summary' 30.
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );

		// Hook template_single_add_to_cart into 'woocommerce_template_single_add_to_cart' 30 and run 'woocommerce_single_product_summary' through it only if the product type doesn't need to be moved.
		add_action( 'woocommerce_single_product_summary', array( __CLASS__, 'template_single_add_to_cart' ), 30 );

		// Hook single_add_to_cart_after_summary into 'woocommerce_after_single_product_summary' 5 and run 'woocommerce_single_product_summary' through it if the product type needs to be moved.
		add_action( 'woocommerce_after_single_product_summary', array( __CLASS__, 'single_add_to_cart_after_summary' ), 5 );
	}

	/**
	 * Indicates if a layout is active.
	 *
	 * @since  1.2.0
	 * @return bool
	 */
	public static function is_layout_active( $layout ) {

		global $product;

		$is_active = false;

		if ( 'default' === $layout ) {
			$is_active = ! self::is_layout_active( 'stacked' );
		} elseif ( 'stacked' === $layout ) {
			$types      = self::get_stacked_types();
			$is_active  = ! empty( $types ) && is_array( $types ) && $product->is_type( $types );
		}

		return $is_active;
	}

	/**
	 * Get product types to apply the stacked layout to.
	 *
	 * @since  1.2.0
	 * @return array
	 */
	private static function get_stacked_types() {

		if ( self::using_legacy_settings() ) {
			$move_types = get_option( 'wc_fw_add_to_cart_layout_types', array() );
		} else {
			$move_types = get_option( 'wc_spl_product_details_layout_types', array() );
			$layout     = get_option( 'wc_spl_product_details_layout', 'default' );
		}

		if ( 'default' === $layout ) {
			$move_types = array();
		} elseif ( 'stacked' === $layout ) {
			$move_types = empty( $move_types ) ? array_keys( self::get_product_types() ) : $move_types;
		}

		return apply_filters( 'woocommerce_full_width_add_to_cart_types', $move_types );
	}

	/**
	 * When displaying a type that needs to be moved, call 'woocommerce_template_single_add_to_cart'.
	 * Hooked into 'woocommerce_after_single_product_summary'.
	 */
	public static function single_add_to_cart_after_summary() {

		if ( self::is_layout_active( 'stacked' ) ) {

			$classes = apply_filters( 'woocommerce_full_width_add_to_cart_section_classes', array( 'add-to-cart-summary', 'stacked-summary' ) );
			$classes = implode( ' ', $classes );

			echo '<div class="' . $classes . '">';
			woocommerce_template_single_add_to_cart();
			echo '</div>';

			wp_register_style( 'wc-single-product-stacked', self::plugin_url() . '/assets/css/wc-single-product-stacked.css', false, self::$version );
			wp_enqueue_style( 'wc-single-product-stacked' );
		}
	}

	/**
	 * When displaying a type that doesn't need to be moved, call 'woocommerce_template_single_add_to_cart' as usual.
	 * Hooked into 'woocommerce_single_product_summary', same position as before.
	 */
	public static function template_single_add_to_cart() {

		if ( self::is_layout_active( 'default' ) ) {
			woocommerce_template_single_add_to_cart();
		}
	}

	/**
	 * Plugin settings added in the WC Product tab under the Display section.
	 *
	 * @param  array  $settings
	 * @return array
	 */
	public static function all_settings( $settings ) {

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

	/**
	 * Gets all registered product types.
	 *
	 * @return array
	 */
	public static function get_product_types() {

		if ( ! empty( self::$product_types_cache ) ) {
			return self::$product_types_cache;
		}

		$types = array();
		$terms = get_terms( 'product_type', array( 'hide_empty' => 0 ) );

		if ( ! empty( $terms ) ) {
			foreach ( $terms as $term ) {
				$types[ $term->slug ] = ucfirst( $term->name );
			}
		}

		self::$product_types_cache = $types;

		return $types;
	}
}

WC_Stacked_Product_Layout::init();
