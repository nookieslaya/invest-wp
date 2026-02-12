{{-- Purpose: Custom WooCommerce single product layout with responsive gallery/summary sections. --}}
@extends('layouts.app')

@section('content')
  <?php do_action('woocommerce_before_main_content'); ?>
  @while (have_posts())
    @php
      the_post();
      global $product;
      $product = wc_get_product(get_the_ID());
    @endphp

    <?php do_action('woocommerce_before_single_product'); ?>
    @include('woocommerce.partials.notices')

    @if (post_password_required())
      {!! get_the_password_form() !!}
      @continue
    @endif

    @if ($product)
      <article id="product-{{ get_the_ID() }}" {!! wc_product_class('space-y-10', $product) !!}>
        <div class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_minmax(0,0.9fr)] lg:items-start">
          <section class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm md:p-6">
            <?php do_action('woocommerce_before_single_product_summary'); ?>
          </section>

          <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
            <?php do_action('woocommerce_single_product_summary'); ?>
          </section>
        </div>

        @if ($product->has_attributes())
          <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
            <h2 class="mb-4 text-xl font-semibold text-slate-900">{{ __('Product attributes', 'woocommerce') }}</h2>
            <?php wc_display_product_attributes($product); ?>
          </section>
        @endif

        <section class="space-y-8 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
          <?php do_action('woocommerce_after_single_product_summary'); ?>
        </section>
      </article>
    @endif

    <?php do_action('woocommerce_after_single_product'); ?>
  @endwhile

  <?php do_action('woocommerce_after_main_content'); ?>
@endsection


