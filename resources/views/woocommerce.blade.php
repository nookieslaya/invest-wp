{{-- Purpose: Generic fallback WooCommerce wrapper for non-product templates. --}}
@extends('layouts.app')

@section('content')
  <?php do_action('woocommerce_before_main_content'); ?>
  @include('woocommerce.partials.notices')
  <?php woocommerce_content(); ?>
  <?php do_action('woocommerce_after_main_content'); ?>
@endsection


