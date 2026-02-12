{{-- Purpose: Custom My Account dashboard intro panel with quick-link guidance. --}}
@php
  defined('ABSPATH') || exit;

  $allowedHtml = [
    'a' => [
      'href' => [],
      'class' => [],
    ],
    'strong' => [],
  ];
@endphp

<section class="space-y-5">
  <header class="space-y-2">
    <h2 class="text-2xl font-semibold text-slate-900">{{ __('Account dashboard', 'sage') }}</h2>
    <p class="text-sm leading-relaxed text-slate-600">
      {!! wp_kses(
        sprintf(
          __('Hello %1$s (not %1$s? <a href="%2$s">Log out</a>)', 'woocommerce'),
          '<strong>'.esc_html($current_user->display_name).'</strong>',
          esc_url(wc_logout_url())
        ),
        $allowedHtml
      ) !!}
    </p>
  </header>

  <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 text-sm leading-relaxed text-slate-700">
    @php
      $dashboardDescription = __('From your account dashboard you can view your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">billing address</a>, and <a href="%3$s">edit your password and account details</a>.', 'woocommerce');

      if (wc_shipping_enabled()) {
        $dashboardDescription = __('From your account dashboard you can view your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">shipping and billing addresses</a>, and <a href="%3$s">edit your password and account details</a>.', 'woocommerce');
      }
    @endphp

    {!! wp_kses(
      sprintf(
        $dashboardDescription,
        esc_url(wc_get_endpoint_url('orders')),
        esc_url(wc_get_endpoint_url('edit-address')),
        esc_url(wc_get_endpoint_url('edit-account'))
      ),
      $allowedHtml
    ) !!}
  </div>
</section>

<?php do_action('woocommerce_account_dashboard'); ?>
<?php do_action('woocommerce_before_my_account'); ?>
<?php do_action('woocommerce_after_my_account'); ?>

