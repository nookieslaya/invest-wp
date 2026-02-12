{{-- Purpose: Dedicated empty-cart view with clear CTA back to product archive. --}}
<?php defined('ABSPATH') || exit; ?>

<section class="py-10 md:py-16">
  <div class="container-main">
    @include('woocommerce.partials.notices')
    @include('woocommerce.partials.breadcrumb')

    @include('woocommerce.partials.empty-state', [
      'title' => __('Your cart is currently empty.', 'woocommerce'),
      'message' => __('Looks like you have not added any products yet. Browse the shop and start building your order.', 'sage'),
      'buttonLabel' => apply_filters('woocommerce_return_to_shop_text', __('Return to shop', 'woocommerce')),
      'buttonUrl' => apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop')),
    ])
  </div>
</section>

