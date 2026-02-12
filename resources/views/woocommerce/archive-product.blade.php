{{-- Purpose: Custom WooCommerce product archive template for shop/taxonomy/search listings. --}}
@extends('layouts.app')

@section('content')
  <?php do_action('woocommerce_before_main_content'); ?>
  @include('woocommerce.partials.notices')

  @php
    $archiveTitle = function_exists('woocommerce_page_title') ? woocommerce_page_title(false) : get_the_archive_title();
    $archiveTitle = $archiveTitle ?: __('Shop', 'woocommerce');
    $shopDescription = function_exists('wc_format_content') ? wc_format_content(get_the_archive_description()) : get_the_archive_description();
  @endphp

  <header class="mb-8 space-y-4 md:mb-10">
    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">{{ __('Store', 'sage') }}</p>
    <h1 class="text-3xl font-semibold leading-tight text-slate-900 md:text-5xl">{{ $archiveTitle }}</h1>
    @if ($shopDescription)
      <div class="max-w-3xl text-sm leading-relaxed text-slate-600 md:text-base">
        {!! wp_kses_post($shopDescription) !!}
      </div>
    @endif
  </header>

  @if (woocommerce_product_loop())
    <?php do_action('woocommerce_before_shop_loop'); ?>
    <section class="mb-8 flex flex-col gap-4 rounded-3xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between">
      <div class="text-sm text-slate-600">
        <?php woocommerce_result_count(); ?>
      </div>
      <div class="w-full sm:w-auto">
        <?php woocommerce_catalog_ordering(); ?>
      </div>
    </section>

    <ul class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:gap-6 xl:grid-cols-4">
      @while (have_posts())
        @php
          the_post();
          $product = wc_get_product(get_the_ID());
        @endphp
        @include('woocommerce.partials.product-card', ['product' => $product])
      @endwhile
    </ul>

    <div class="mt-10">
      <?php do_action('woocommerce_after_shop_loop'); ?>
    </div>
  @else
    <?php do_action('woocommerce_no_products_found'); ?>
    @include('woocommerce.partials.empty-state', [
      'title' => __('No products found', 'woocommerce'),
      'message' => __('Try adjusting filters or browse a different category.', 'sage'),
      'buttonLabel' => __('Back to shop', 'woocommerce'),
      'buttonUrl' => wc_get_page_permalink('shop'),
    ])
  @endif

  <?php do_action('woocommerce_after_main_content'); ?>
@endsection


