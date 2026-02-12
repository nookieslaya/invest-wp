{{-- Purpose: Custom order-received template with clear summary cards and post-purchase CTAs. --}}
<?php defined('ABSPATH') || exit; ?>

<section class="py-10 md:py-16">
  <div class="container-main">
    @include('woocommerce.partials.breadcrumb')

    <div class="woocommerce-order rounded-3xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
      @if ($order)
        <?php do_action('woocommerce_before_thankyou', $order->get_id()); ?>
        @if ($order->has_status('failed'))
          <h1 class="text-2xl font-semibold text-slate-900 md:text-3xl">{{ __('Payment failed', 'woocommerce') }}</h1>
          <p class="mt-3 text-sm leading-relaxed text-slate-600 md:text-base">
            {{ __('Unfortunately your order cannot be processed as the originating bank or merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce') }}
          </p>
          <div class="mt-6 flex flex-wrap gap-3">
            <a class="wc-button inline-flex items-center justify-center rounded-full px-5 py-2 text-xs font-semibold uppercase tracking-[0.2em]" href="{{ esc_url($order->get_checkout_payment_url()) }}">
              {{ __('Pay', 'woocommerce') }}
            </a>
            @if (is_user_logged_in())
              <a class="wc-button inline-flex items-center justify-center rounded-full px-5 py-2 text-xs font-semibold uppercase tracking-[0.2em]" href="{{ esc_url(wc_get_page_permalink('myaccount')) }}">
                {{ __('My account', 'woocommerce') }}
              </a>
            @endif
          </div>
        @else
          <header class="space-y-3">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700">{{ __('Order complete', 'sage') }}</p>
            <h1 class="text-3xl font-semibold leading-tight text-slate-900 md:text-5xl">{{ __('Thank you. Your order has been received.', 'woocommerce') }}</h1>
          </header>

          <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
            <article class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
              <p class="text-xs uppercase tracking-[0.14em] text-slate-500">{{ __('Order number', 'woocommerce') }}</p>
              <p class="mt-2 text-base font-semibold text-slate-900">{{ $order->get_order_number() }}</p>
            </article>

            <article class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
              <p class="text-xs uppercase tracking-[0.14em] text-slate-500">{{ __('Date', 'woocommerce') }}</p>
              <p class="mt-2 text-base font-semibold text-slate-900">{{ wc_format_datetime($order->get_date_created()) }}</p>
            </article>

            @if (is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email())
              <article class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                <p class="text-xs uppercase tracking-[0.14em] text-slate-500">{{ __('Email', 'woocommerce') }}</p>
                <p class="mt-2 break-all text-base font-semibold text-slate-900">{{ $order->get_billing_email() }}</p>
              </article>
            @endif

            <article class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
              <p class="text-xs uppercase tracking-[0.14em] text-slate-500">{{ __('Total', 'woocommerce') }}</p>
              <p class="mt-2 text-base font-semibold text-slate-900">{!! $order->get_formatted_order_total() !!}</p>
            </article>

            @if ($order->get_payment_method_title())
              <article class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                <p class="text-xs uppercase tracking-[0.14em] text-slate-500">{{ __('Payment method', 'woocommerce') }}</p>
                <p class="mt-2 text-base font-semibold text-slate-900">{{ $order->get_payment_method_title() }}</p>
              </article>
            @endif
          </div>

          <div class="mt-8 rounded-2xl border border-slate-200 bg-slate-50 p-5 text-sm text-slate-700">
            <?php wc_get_template('checkout/order-received.php', ['order' => $order]); ?>
          </div>

          <div class="mt-6 flex flex-wrap gap-3">
            @if (is_user_logged_in())
              <a class="wc-button inline-flex items-center justify-center rounded-full px-5 py-2 text-xs font-semibold uppercase tracking-[0.2em]" href="{{ esc_url(wc_get_page_permalink('myaccount')) }}">
                {{ __('Go to my account', 'sage') }}
              </a>
            @endif
            <a class="wc-button inline-flex items-center justify-center rounded-full px-5 py-2 text-xs font-semibold uppercase tracking-[0.2em]" href="{{ esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))) }}">
              {{ apply_filters('woocommerce_return_to_shop_text', __('Continue shopping', 'sage')) }}
            </a>
          </div>
        @endif

        <?php do_action('woocommerce_thankyou_'.$order->get_payment_method(), $order->get_id()); ?>
        <?php do_action('woocommerce_thankyou', $order->get_id()); ?>
      @else
        <h1 class="text-2xl font-semibold text-slate-900 md:text-3xl">{{ __('Order received', 'woocommerce') }}</h1>
        <div class="mt-3 text-sm leading-relaxed text-slate-600 md:text-base">
          <?php wc_get_template('checkout/order-received.php', ['order' => false]); ?>
        </div>
      @endif
    </div>
  </div>
</section>


