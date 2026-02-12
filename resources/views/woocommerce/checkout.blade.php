{{-- Purpose: Custom two-column WooCommerce checkout view with styled notices and summaries. --}}
@php
  defined('ABSPATH') || exit;
  do_action('woocommerce_before_checkout_form', $checkout);
@endphp

@if (! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in())
  <section class="py-10 md:py-16">
    <div class="container-main">
      @include('woocommerce.partials.empty-state', [
        'title' => __('Checkout unavailable', 'sage'),
        'message' => apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce')),
        'buttonLabel' => __('Go to my account', 'woocommerce'),
        'buttonUrl' => wc_get_page_permalink('myaccount'),
      ])
    </div>
  </section>
@else
  <section class="py-10 md:py-16">
    <div class="container-main">
      @include('woocommerce.partials.notices')
      @include('woocommerce.partials.breadcrumb')

      <header class="mb-8 space-y-3">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">{{ __('Checkout', 'woocommerce') }}</p>
        <h1 class="text-3xl font-semibold leading-tight text-slate-900 md:text-5xl">{{ __('Complete your order', 'sage') }}</h1>
      </header>

      <form
        name="checkout"
        method="post"
        class="checkout woocommerce-checkout grid gap-8 xl:grid-cols-[minmax(0,1fr)_24rem]"
        action="{{ esc_url(wc_get_checkout_url()) }}"
        enctype="multipart/form-data"
        aria-label="{{ esc_attr__('Checkout', 'woocommerce') }}"
      >
        @if ($checkout->get_checkout_fields())
          <div class="space-y-6">
            <?php do_action('woocommerce_checkout_before_customer_details'); ?>
            <section id="customer_details" class="space-y-6">
              <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
                <h2 class="mb-4 text-xl font-semibold text-slate-900">{{ __('Billing details', 'woocommerce') }}</h2>
                <?php do_action('woocommerce_checkout_billing'); ?>
              </div>

              <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
                <h2 class="mb-4 text-xl font-semibold text-slate-900">{{ __('Shipping details', 'woocommerce') }}</h2>
                <?php do_action('woocommerce_checkout_shipping'); ?>
              </div>
            </section>

            <?php do_action('woocommerce_checkout_after_customer_details'); ?>
          </div>
        @endif

        <div class="space-y-4">
          <?php do_action('woocommerce_checkout_before_order_review_heading'); ?>
          <h2 id="order_review_heading" class="text-xl font-semibold text-slate-900">{{ __('Your order', 'woocommerce') }}</h2>

          <?php do_action('woocommerce_checkout_before_order_review'); ?>
          <div id="order_review" class="woocommerce-checkout-review-order">
            @include('woocommerce.partials.order-summary', ['context' => 'checkout', 'title' => __('Order summary', 'sage')])
          </div>

          <?php do_action('woocommerce_checkout_after_order_review'); ?>
        </div>
      </form>

      <?php do_action('woocommerce_after_checkout_form', $checkout); ?>
    </div>
  </section>
@endif


