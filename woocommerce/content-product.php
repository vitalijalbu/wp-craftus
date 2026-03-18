<?php
/**
 * WooCommerce product loop item
 * Overrides: woocommerce/templates/content-product.php
 *
 * Delegates rendering to our Blade product-card partial via Acorn's view helper.
 */

defined('ABSPATH') || exit;

global $product;

// Ensure visibility.
if (empty($product) || ! $product->is_visible()) {
    return;
}

echo \Roots\view('partials.product-card', ['product' => $product])->render();
