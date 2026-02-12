<?php
/**
 * Bridge WooCommerce thank-you template to Blade view.
 */

defined('ABSPATH') || exit;

\App\render_woocommerce_view('woocommerce.thankyou', get_defined_vars());

