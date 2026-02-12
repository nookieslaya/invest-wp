<?php
/**
 * Bridge WooCommerce empty-cart template to Blade view.
 */

defined('ABSPATH') || exit;

\App\render_woocommerce_view('woocommerce.cart-empty', get_defined_vars());

