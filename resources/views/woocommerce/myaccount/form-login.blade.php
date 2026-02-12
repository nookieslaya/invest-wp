{{-- Purpose: Styled WooCommerce login/register forms for the My Account entry screen. --}}
@php
  defined('ABSPATH') || exit;
  $registrationEnabled = get_option('woocommerce_enable_myaccount_registration') === 'yes';
  $showUsernameField = get_option('woocommerce_registration_generate_username') === 'no';
  $showPasswordField = get_option('woocommerce_registration_generate_password') === 'no';
  do_action('woocommerce_before_customer_login_form');
@endphp

<section class="py-10 md:py-16">
  <div class="container-main">
    @include('woocommerce.partials.notices')
    @include('woocommerce.partials.breadcrumb')

    <div id="customer_login" class="{{ $registrationEnabled ? 'grid gap-6 lg:grid-cols-2' : '' }}">
      <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
        <h2 class="mb-6 text-2xl font-semibold text-slate-900">{{ __('Login', 'woocommerce') }}</h2>

        <form class="woocommerce-form woocommerce-form-login login space-y-1" method="post" novalidate>
          <?php do_action('woocommerce_login_form_start'); ?>
          @include('woocommerce.partials.form-field', [
            'id' => 'username',
            'name' => 'username',
            'label' => __('Username or email address', 'woocommerce'),
            'required' => true,
            'value' => ! empty($_POST['username']) && is_string($_POST['username']) ? wp_unslash($_POST['username']) : '',
            'autocomplete' => 'username',
          ])

          @include('woocommerce.partials.form-field', [
            'id' => 'password',
            'name' => 'password',
            'type' => 'password',
            'label' => __('Password', 'woocommerce'),
            'required' => true,
            'autocomplete' => 'current-password',
          ])

          <?php do_action('woocommerce_login_form'); ?>
          <div class="mt-3 flex flex-wrap items-center justify-between gap-4">
            <label class="inline-flex items-center gap-2 text-sm text-slate-700" for="rememberme">
              <input class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-300" name="rememberme" type="checkbox" id="rememberme" value="forever" />
              <span>{{ __('Remember me', 'woocommerce') }}</span>
            </label>

            <?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>
            <button type="submit" class="wc-button !px-5 !py-2 !text-[11px]" name="login" value="{{ esc_attr__('Log in', 'woocommerce') }}">
              {{ __('Log in', 'woocommerce') }}
            </button>
          </div>

          <p class="mt-4 text-sm text-slate-600">
            <a class="underline decoration-slate-300 underline-offset-4 hover:text-slate-900" href="{{ esc_url(wp_lostpassword_url()) }}">
              {{ __('Lost your password?', 'woocommerce') }}
            </a>
          </p>

          <?php do_action('woocommerce_login_form_end'); ?>
        </form>
      </div>

      @if ($registrationEnabled)
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
          <h2 class="mb-6 text-2xl font-semibold text-slate-900">{{ __('Register', 'woocommerce') }}</h2>

          <form method="post" class="woocommerce-form woocommerce-form-register register space-y-1" <?php do_action('woocommerce_register_form_tag'); ?>>
            <?php do_action('woocommerce_register_form_start'); ?>
            @if ($showUsernameField)
              @include('woocommerce.partials.form-field', [
                'id' => 'reg_username',
                'name' => 'username',
                'label' => __('Username', 'woocommerce'),
                'required' => true,
                'value' => ! empty($_POST['username']) ? wp_unslash($_POST['username']) : '',
                'autocomplete' => 'username',
              ])
            @endif

            @include('woocommerce.partials.form-field', [
              'id' => 'reg_email',
              'name' => 'email',
              'type' => 'email',
              'label' => __('Email address', 'woocommerce'),
              'required' => true,
              'value' => ! empty($_POST['email']) ? wp_unslash($_POST['email']) : '',
              'autocomplete' => 'email',
            ])

            @if ($showPasswordField)
              @include('woocommerce.partials.form-field', [
                'id' => 'reg_password',
                'name' => 'password',
                'type' => 'password',
                'label' => __('Password', 'woocommerce'),
                'required' => true,
                'autocomplete' => 'new-password',
              ])
            @else
              <p class="mb-5 text-sm leading-relaxed text-slate-600">
                {{ __('A link to set a new password will be sent to your email address.', 'woocommerce') }}
              </p>
            @endif

            <?php do_action('woocommerce_register_form'); ?>
            <div class="mt-3 flex flex-wrap items-center justify-end gap-3">
              <?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
              <button type="submit" class="wc-button !px-5 !py-2 !text-[11px]" name="register" value="{{ esc_attr__('Register', 'woocommerce') }}">
                {{ __('Register', 'woocommerce') }}
              </button>
            </div>

            <?php do_action('woocommerce_register_form_end'); ?>
          </form>
        </div>
      @endif
    </div>
  </div>
</section>

<?php do_action('woocommerce_after_customer_login_form'); ?>

