{{-- Purpose: Reusable responsive product card for shop/archive loops. --}}
<?php $product = $product ?? null; ?>

@if ($product instanceof \WC_Product && $product->is_visible())
  @php
    $productLink = apply_filters('woocommerce_loop_product_link', get_permalink($product->get_id()), $product);
    $categoryList = wc_get_product_category_list($product->get_id(), ', ');
    $ratingHtml = wc_get_rating_html($product->get_average_rating());
    $shortDescription = wp_trim_words(wp_strip_all_tags($product->get_short_description()), 14);
    $GLOBALS['product'] = $product;
  @endphp

  <li class="group flex h-full flex-col overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg">
    <a class="relative block overflow-hidden bg-slate-100 no-underline" href="{{ esc_url($productLink) }}">
      {!! $product->get_image('woocommerce_thumbnail', ['class' => 'aspect-[4/3] h-full w-full object-cover transition duration-300 group-hover:scale-105']) !!}
      @if ($product->is_on_sale())
        <span class="absolute left-3 top-3 inline-flex rounded-full bg-rose-600 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.12em] text-white">
          {{ __('Sale', 'woocommerce') }}
        </span>
      @endif
    </a>

    <div class="flex flex-1 flex-col gap-3 p-5">
      @if ($categoryList)
        <p class="text-xs uppercase tracking-[0.16em] text-slate-500">{!! $categoryList !!}</p>
      @endif

      <h2 class="text-base font-semibold leading-tight text-slate-900">
        <a class="no-underline hover:text-slate-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-300" href="{{ esc_url($productLink) }}">
          {{ $product->get_name() }}
        </a>
      </h2>

      @if ($ratingHtml)
        <div class="woocommerce-product-rating text-sm text-amber-500">{!! $ratingHtml !!}</div>
      @endif

      @if ($shortDescription)
        <p class="text-sm leading-relaxed text-slate-600">{{ $shortDescription }}</p>
      @endif

      <div class="mt-auto space-y-3 pt-2">
        @include('woocommerce.partials.price', ['product' => $product])
        <div class="woocommerce-card-actions">
          <?php woocommerce_template_loop_add_to_cart(); ?>
        </div>
      </div>
    </div>
  </li>
@endif



