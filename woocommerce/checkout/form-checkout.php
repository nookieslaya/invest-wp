<?php
/**
 * Bridge WooCommerce checkout form template to Blade view.
 */

defined('ABSPATH') || exit;

\App\render_woocommerce_view('woocommerce.checkout', get_defined_vars());

