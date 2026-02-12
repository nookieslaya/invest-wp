{{-- Purpose: Styled addresses overview for billing/shipping management in My Account. --}}
@php
  defined('ABSPATH') || exit;

  $customerId = get_current_user_id();
  $addresses = ! wc_ship_to_billing_address_only() && wc_shipping_enabled()
    ? apply_filters('woocommerce_my_account_get_addresses', [
      'billing' => __('Billing address', 'woocommerce'),
      'shipping' => __('Shipping address', 'woocommerce'),
    ], $customerId)
    : apply_filters('woocommerce_my_account_get_addresses', [
      'billing' => __('Billing address', 'woocommerce'),
    ], $customerId);
@endphp

<p class="mb-6 text-sm leading-relaxed text-slate-600">
  {!! apply_filters('woocommerce_my_account_my_address_description', __('The following addresses will be used on the checkout page by default.', 'woocommerce')) !!}
</p>

<div class="grid gap-4 md:grid-cols-2">
  @foreach ($addresses as $addressName => $addressTitle)
    @php
      $formattedAddress = wc_get_account_formatted_address($addressName);
      $actionText = $formattedAddress ? __('Edit %s', 'woocommerce') : __('Add %s', 'woocommerce');
    @endphp

    <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
      <header class="mb-4 flex flex-wrap items-start justify-between gap-3">
        <h2 class="text-lg font-semibold text-slate-900">{{ $addressTitle }}</h2>
        <a class="text-sm font-medium text-slate-700 underline decoration-slate-300 underline-offset-4 hover:text-slate-900" href="{{ esc_url(wc_get_endpoint_url('edit-address', $addressName)) }}">
          {{ sprintf($actionText, $addressTitle) }}
        </a>
      </header>

      <address class="not-italic text-sm leading-relaxed text-slate-700">
        @if ($formattedAddress)
          {!! wp_kses_post($formattedAddress) !!}
        @else
          {{ __('You have not set up this type of address yet.', 'woocommerce') }}
        @endif
      </address>

      <?php do_action('woocommerce_my_account_after_my_address', $addressName); ?>
    </article>
  @endforeach
</div>



