{{-- Purpose: Styled account-details form with preserved WooCommerce extensibility hooks. --}}
@php
  defined('ABSPATH') || exit;
  do_action('woocommerce_before_edit_account_form');
@endphp

<form class="woocommerce-EditAccountForm edit-account space-y-6" action="" method="post" <?php do_action('woocommerce_edit_account_form_tag'); ?>>
  <?php do_action('woocommerce_edit_account_form_start'); ?>
  <div class="grid gap-4 sm:grid-cols-2">
    @include('woocommerce.partials.form-field', [
      'id' => 'account_first_name',
      'name' => 'account_first_name',
      'label' => __('First name', 'woocommerce'),
      'required' => true,
      'value' => $user->first_name,
      'autocomplete' => 'given-name',
    ])

    @include('woocommerce.partials.form-field', [
      'id' => 'account_last_name',
      'name' => 'account_last_name',
      'label' => __('Last name', 'woocommerce'),
      'required' => true,
      'value' => $user->last_name,
      'autocomplete' => 'family-name',
    ])
  </div>

  @include('woocommerce.partials.form-field', [
    'id' => 'account_display_name',
    'name' => 'account_display_name',
    'label' => __('Display name', 'woocommerce'),
    'required' => true,
    'value' => $user->display_name,
    'attributes' => [
      'aria-describedby' => 'account_display_name_description',
    ],
  ])
  <p id="account_display_name_description" class="-mt-3 text-xs text-slate-500">
    {{ __('This will be how your name will be displayed in the account section and in reviews.', 'woocommerce') }}
  </p>

  @include('woocommerce.partials.form-field', [
    'id' => 'account_email',
    'name' => 'account_email',
    'type' => 'email',
    'label' => __('Email address', 'woocommerce'),
    'required' => true,
    'value' => $user->user_email,
    'autocomplete' => 'email',
  ])

  <?php do_action('woocommerce_edit_account_form_fields'); ?>
  <fieldset class="space-y-4 rounded-3xl border border-slate-200 bg-slate-50 p-5">
    <legend class="px-2 text-sm font-semibold uppercase tracking-[0.14em] text-slate-600">{{ __('Password change', 'woocommerce') }}</legend>

    @include('woocommerce.partials.form-field', [
      'id' => 'password_current',
      'name' => 'password_current',
      'type' => 'password',
      'label' => __('Current password (leave blank to leave unchanged)', 'woocommerce'),
    ])

    @include('woocommerce.partials.form-field', [
      'id' => 'password_1',
      'name' => 'password_1',
      'type' => 'password',
      'label' => __('New password (leave blank to leave unchanged)', 'woocommerce'),
    ])

    @include('woocommerce.partials.form-field', [
      'id' => 'password_2',
      'name' => 'password_2',
      'type' => 'password',
      'label' => __('Confirm new password', 'woocommerce'),
    ])
  </fieldset>

  <?php do_action('woocommerce_edit_account_form'); ?>
  <div class="flex flex-wrap justify-end gap-3">
    <?php wp_nonce_field('save_account_details', 'save-account-details-nonce'); ?>
    <button type="submit" class="woocommerce-Button button wc-button !px-5 !py-2 !text-[11px]" name="save_account_details" value="{{ esc_attr__('Save changes', 'woocommerce') }}">
      {{ __('Save changes', 'woocommerce') }}
    </button>
    <input type="hidden" name="action" value="save_account_details" />
  </div>

  <?php do_action('woocommerce_edit_account_form_end'); ?>
</form>

<?php do_action('woocommerce_after_edit_account_form'); ?>

