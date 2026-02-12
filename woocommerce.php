<?php

/**
 * Purpose: Route WooCommerce front-end requests to the appropriate Blade templates.
 */

if (function_exists('is_singular') && is_singular('product')) {
    echo \Roots\view('woocommerce.single-product')->render();
    return;
}

if (
    (function_exists('is_shop') && is_shop())
    || (function_exists('is_product_taxonomy') && is_product_taxonomy())
    || (function_exists('is_search') && is_search())
) {
    echo \Roots\view('woocommerce.archive-product')->render();
    return;
}

echo \Roots\view('woocommerce')->render();
