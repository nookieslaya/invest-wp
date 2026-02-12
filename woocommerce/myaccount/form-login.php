<?php
/**
 * Bridge WooCommerce My Account login/register template to Blade view.
 */

defined('ABSPATH') || exit;

\App\render_woocommerce_view('woocommerce.myaccount.form-login', get_defined_vars());

