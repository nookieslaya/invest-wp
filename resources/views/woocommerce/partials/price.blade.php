{{-- Purpose: Reusable WooCommerce product price renderer. --}}
<?php $product = $product ?? null; ?>

@if ($product instanceof \WC_Product)
  <div class="woocommerce-price text-lg font-semibold text-slate-900">
    {!! $product->get_price_html() ?: wc_price((float) $product->get_price()) !!}
  </div>
@endif


