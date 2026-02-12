{{-- Purpose: Account navigation template that delegates rendering to shared navigation partial. --}}
@php
  defined('ABSPATH') || exit;
  do_action('woocommerce_before_account_navigation');
@endphp

@include('woocommerce.partials.account-nav')

<?php do_action('woocommerce_after_account_navigation'); ?>

