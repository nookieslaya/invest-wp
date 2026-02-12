<?php
/**
 * Bridge WooCommerce My Account orders template to Blade view.
 */

defined('ABSPATH') || exit;

\App\render_woocommerce_view('woocommerce.myaccount.orders', get_defined_vars());

