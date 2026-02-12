{{-- Purpose: Styled address edit form while preserving WooCommerce field generation and hooks. --}}
@php
  defined('ABSPATH') || exit;

  $pageTitle = $load_address === 'billing'
    ? __('Billing address', 'woocommerce')
    : __('Shipping address', 'woocommerce');

  do_action('woocommerce_before_edit_account_address_form');
@endphp

@if (! $load_address)
  <?php wc_get_template('myaccount/my-address.php'); ?>
@else
  <form method="post" novalidate class="space-y-6">
    <h2 class="text-2xl font-semibold text-slate-900">{!! apply_filters('woocommerce_my_account_edit_address_title', $pageTitle, $load_address) !!}</h2>

    <div class="woocommerce-address-fields rounded-3xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
      <?php do_action("woocommerce_before_edit_address_form_{$load_address}"); ?>
      <div class="woocommerce-address-fields__field-wrapper grid gap-4 sm:grid-cols-2">
        @foreach ($address as $key => $field)
          <?php woocommerce_form_field($key, $field, wc_get_post_data_by_key($key, $field['value'])); ?>
        @endforeach
      </div>

      <?php do_action("woocommerce_after_edit_address_form_{$load_address}"); ?>
      <div class="mt-6 flex flex-wrap justify-end gap-3">
        <button type="submit" class="wc-button !px-5 !py-2 !text-[11px]" name="save_address" value="{{ esc_attr__('Save address', 'woocommerce') }}">
          {{ __('Save address', 'woocommerce') }}
        </button>
        <?php wp_nonce_field('woocommerce-edit_address', 'woocommerce-edit-address-nonce'); ?>
        <input type="hidden" name="action" value="edit_address" />
      </div>
    </div>
  </form>
@endif

<?php do_action('woocommerce_after_edit_account_address_form'); ?>

