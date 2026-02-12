{{-- Purpose: Custom My Account shell with responsive navigation and content panel. --}}
<?php defined('ABSPATH') || exit; ?>

<section class="py-10 md:py-16">
  <div class="container-main">
    @include('woocommerce.partials.notices')
    @include('woocommerce.partials.breadcrumb')

    <header class="mb-8 space-y-3">
      <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">{{ __('Customer area', 'sage') }}</p>
      <h1 class="text-3xl font-semibold leading-tight text-slate-900 md:text-5xl">{{ __('My account', 'woocommerce') }}</h1>
    </header>

    <div class="grid gap-6 lg:grid-cols-[18rem_minmax(0,1fr)]">
      <aside class="lg:sticky lg:top-8 lg:self-start">
        <?php do_action('woocommerce_account_navigation'); ?>
      </aside>

      <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
        <?php do_action('woocommerce_account_content'); ?>
      </div>
    </div>
  </div>
</section>


