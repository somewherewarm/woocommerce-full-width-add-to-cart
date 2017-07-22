=== WooCommerce Stacked Product Layout ===

Contributors: SomewhereWarm, franticpsyx
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=sw@somewherewarm.net&item_name=Donation+for+WooCommerce+Full+Width+Forms
Tags: woocommerce, composite, bundle, form, add-to-cart, template, full-width, stacked, layout
Requires at least: 4.1
Tested up to: 4.8
Stable tag: 1.1.7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A handy plugin for stacking the add-to-cart section of complex WooCommerce product types below the main product image and summary.

== Description ==

By default, WooCommerce places single-product summaries right next to the main product image/gallery section, which is ideal for displaying product descriptions, prices and meta. Add-to-cart forms are normally displayed inside the single-product summary, too, which works well when displaying Simple or Variable product forms. 

However, this layout is not particularly suitable for complex product types, such as Composites or Bundles, which require much more space for their form content.

If you are having issues with very narrow or squeezed product add-to-cart forms, you can use this plugin to move the add-to-cart form below the product image and summary section. 

The plugin adds a "Force Stacked Layout" option under the WooCommerce **Settings > Products > Display** section, where you can select which product types should use the modified, stacked layout.

**Important**: The plugin may **not work** if your theme **overrides core WooCommerce template functions, or changes the default template action hook priorities**!

== Installation ==

1. Upload the plugin to the **/wp-content/plugins/** directory.
2. Activate the plugin through the **Plugins** menu in WordPress.
3. Go to **WooCommerce > Settings > Products > Display** and configure the *Force Stacked Layout* option.


== Changelog ==

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

