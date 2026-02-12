{{-- Purpose: Shared order summary panel used by cart and checkout screens. --}}
@php
  $context = $context ?? 'cart';
  $title = $title ?? __('Order summary', 'sage');
@endphp

<aside class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm lg:sticky lg:top-8">
  <h2 class="text-lg font-semibold text-slate-900">{{ $title }}</h2>

  @if ($context === 'cart')
    <p class="mt-2 text-sm text-slate-600">
      {{ __('Shipping and taxes are calculated during checkout.', 'woocommerce') }}
    </p>
    <div class="mt-4 space-y-4">
      <?php do_action('woocommerce_before_cart_totals'); ?>
      <?php woocommerce_cart_totals(); ?>
      <?php do_action('woocommerce_after_cart_totals'); ?>
    </div>
  @elseif ($context === 'checkout')
    <div class="mt-4 space-y-4">
      <?php do_action('woocommerce_checkout_order_review'); ?>
    </div>
  @endif
</aside>



