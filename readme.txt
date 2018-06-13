=== WooCommerce Stacked Product Layout ===

Contributors: SomewhereWarm, franticpsyx
Tags: woocommerce, product, composite, bundle, form, add-to-cart, template, full-width, stacked, layout, customizer
Requires at least: 4.4
Tested up to: 4.9
WC requires at least: 2.6
WC tested up to: 3.4
Stable tag: 1.2.1
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

A handy plugin for stacking the add-to-cart section of complex WooCommerce product types below the main product image and summary.

== Description ==

By default, WooCommerce displays all single-product summary elements (price, meta, short description and form content) in a single column next to the main product image. This layout works very well with products that contain few form elements, such as Simple or Variable products.

However, this styling/templating pattern is much less suitable for product forms containing multiple input elements, text blocks and/or images. Complex product types, such as Product Bundles and Composite Products, often end up looking squeezed between the main image and sidebar.

Many themes include options for modifying the WooCommerce single-product template layout, sometimes even for individual products. If your theme doesn't offer this flexibility, this plugin could do the trick.

For WooCommerce versions from **3.3** onwards, the plugin adds a dedicated "WooCommerce > Product Details" section under **Appearance > Customize**, which allows you to enable a **Stacked** layout and even associate specific **product types** with it.

If you are using an older WooCommerce version, navigate to **Settings > Products > Display** section, and locate the "Force Stacked Layout" option, which provides the same functionality.

**Note**: Recent versions of Product Bundles and Composite Products offer a **built-in solution that works with more themes** than the **Stacked Product Layout** plugin: Navigate to **Product Data > Advanced** and locate the **Form Location** option, then choose **After Summary** to stack the add-to-cart form under the main image and summary.

**Important**: The plugin may **not work** if your theme **overrides core WooCommerce template functions, or changes the default template action hook priorities**!

== Installation ==

1. Upload the plugin to the **/wp-content/plugins/** directory.
2. Activate the plugin through the **Plugins** menu in WordPress.
3. Go to **WooCommerce > Settings > Products > Display** and configure the *Force Stacked Layout* option.

== Changelog ==

= 1.2.1 =
* Fix - "WooCommerce > Product Details" customizer section visibility.
* Fix - Compatibility for "Form Location" option, recently introduced in Composite Products, Product Bundles and Mix and Match Products.
* Tweak - Declare WooCommerce 3.4 support.

= 1.2.0 =
* Fix - Added WooCommerce 3.3 compatibility. Navigate to "Appearance > Customize" and look for the "WooCommerce > Product Details" section.

= 1.1.7 =
* Tweak - Enqueue styles and apply "width: 100%" rule to the 'stacked-summary' div.

= 1.1.6 =
* Fix - WooCommerce 3.0 compatibility.

= 1.1.5 =
* Fix - Move the hook-in point from the `woocommerce_single_product_summary` to the `woocommerce_before_single_product` action - resolves some issues with themes that shuffle around the summary/description.

= 1.1.4 =
* Fix - Missing product types in plugin settings - issue introduced in v1.1.2.

= 1.1.3 =
* Tweak - Updated plugin name.
* Tweak - Added `woocommerce_full_width_add_to_cart_section_classes` filter to allow adding classes to the stacked add-to-cart container.

= 1.1.2 =
* Tweak - Added docblocks.
* Tweak - Front-end modifications now hooked at the `woocommerce_single_product_summary` action.
* Fix - A stray, pesky PHP warning. Shame.

= 1.1.1 =
* Tweak - Plugin name and description.

= 1.0.2 =
* Tweak - Moved settings under **Settings > Products > Display**.

= 1.0.1 =
* Fix - Static PHP notices. Ops.

= 1.0 =
* Initial version.

== Upgrade Notice ==
Added WooCommerce 3.3/3.4 compatibility. Navigate to "Appearance > Customize" and look for the "WooCommerce > Product Details" section.
