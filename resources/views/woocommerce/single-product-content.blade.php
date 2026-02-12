@php
  if (post_password_required()) {
    echo get_the_password_form();
    return;
  }

  global $product;
  $product = function_exists('wc_get_product') ? wc_get_product(get_the_ID()) : null;

  $shopPageId = function_exists('wc_get_page_id') ? wc_get_page_id('shop') : 0;
  $shopUrl = $shopPageId && $shopPageId > 0 ? get_permalink($shopPageId) : home_url('/');

  $breadcrumbs = [
    ['label' => __('Home', 'sage'), 'url' => home_url('/')],
  ];

  if ($shopPageId && $shopPageId > 0) {
    $breadcrumbs[] = ['label' => get_the_title($shopPageId), 'url' => $shopUrl];
  }

  $breadcrumbs[] = ['label' => get_the_title(), 'url' => ''];
@endphp

<?php do_action('woocommerce_before_single_product'); ?>

@if ($product)
  @php
    $categoryList = wc_get_product_category_list($product->get_id(), ', ');
    $shortDescription = wp_trim_words(wp_strip_all_tags($product->get_short_description()), 30);
  @endphp

  <article class="container-main py-10 md:py-16">
    <nav class="text-xs text-slate-500" aria-label="Breadcrumb">
      <ol class="flex flex-wrap items-center gap-2">
        @foreach ($breadcrumbs as $index => $crumb)
          <li class="flex items-center gap-2">
            @if ($crumb['url'])
              <a class="hover:text-slate-700" href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a>
            @else
              <span class="text-slate-700">{{ $crumb['label'] }}</span>
            @endif
            @if ($index < count($breadcrumbs) - 1)
              <span class="text-slate-400">/</span>
            @endif
          </li>
        @endforeach
      </ol>
    </nav>

    <div class="mt-6 grid gap-10 lg:grid-cols-[1.1fr_0.9fr] lg:items-start">
      <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        @php
          if (function_exists('woocommerce_show_product_images')) {
            woocommerce_show_product_images();
          }
        @endphp
      </div>

      <div class="space-y-6">
        @if ($categoryList)
          <p class="text-xs uppercase tracking-[0.2em] text-slate-500">{!! $categoryList !!}</p>
        @endif

        <div class="space-y-3">
          <h1 class="text-3xl font-semibold text-slate-900 md:text-5xl">{{ $product->get_name() }}</h1>
          <div class="text-2xl font-semibold text-slate-900">{!! $product->get_price_html() !!}</div>
          @if ($shortDescription)
            <p class="text-base text-slate-600">{{ $shortDescription }}</p>
          @endif
        </div>

        <div class="prose prose-slate max-w-none">
          <?php the_content(); ?>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
          @php
            if (function_exists('woocommerce_template_single_add_to_cart')) {
              woocommerce_template_single_add_to_cart();
            }
          @endphp
        </div>

        <div class="text-sm text-slate-600">
          @php
            if (function_exists('woocommerce_template_single_meta')) {
              woocommerce_template_single_meta();
            }
          @endphp
        </div>
      </div>
    </div>
  </article>
@endif

<?php do_action('woocommerce_after_single_product'); ?>

