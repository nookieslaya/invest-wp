<?php
/**
 * Bridge WooCommerce cart template to Blade view.
 */

defined('ABSPATH') || exit;

\App\render_woocommerce_view('woocommerce.cart', get_defined_vars());

