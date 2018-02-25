WooCommerce Stacked Product Layout
======================

## Description

A handy plugin for stacking the add-to-cart section of complex WooCommerce product types below the main product image and summary. Useful if the add-to-cart section of your products appears very narrow or squeezed.

## Details

By default, WooCommerce displays all single-product summary elements (price, meta, short description and form content) in a single column next to the main product image. This layout works very well with products that contain few form elements, such as Simple or Variable products.

However, this styling/templating pattern is much less suitable for product forms containing multiple input elements, text blocks and/or images. Complex product types, such as Product Bundles and Composite Products, often end up looking squeezed between the main image and sidebar.

Many themes include options for modifying the WooCommerce single-product template layout, sometimes even for individual products. If your theme doesn't offer this flexibility, this plugin could do the trick.

For WooCommerce versions from **3.3** onwards, the plugin adds a dedicated "WooCommerce > Product Details" section under **Appearance > Customize**, which allows you to enable a **Stacked** layout and even associate specific **product types** with it.

If you are using an older WooCommerce version, navigate to **Settings > Products > Display** section, and locate the "Force Stacked Layout" option, which provides the same functionality.

**Note**: Recent versions of Product Bundles and Composite Products offer a **built-in solution that works with more themes** than the **Stacked Product Layout** plugin: Navigate to **Product Data > Advanced** and locate the **Form Location** option, then choose **After Summary** to stack the add-to-cart form under the main image and summary.

**Important**: The plugin may **not work** if your theme **overrides core WooCommerce template functions, or changes the default template action hook priorities**!
