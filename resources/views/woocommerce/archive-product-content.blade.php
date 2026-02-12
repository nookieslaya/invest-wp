@php
  $shopPageId = function_exists('wc_get_page_id') ? wc_get_page_id('shop') : 0;
  $shopTitle = function_exists('woocommerce_page_title') ? woocommerce_page_title(false) : '';
  $shopTitle = $shopTitle ?: __('Sklep', 'sage');

  $shopDescription = '';
  if ($shopPageId && $shopPageId > 0) {
    $shopDescription = get_post_field('post_excerpt', $shopPageId);
  }

  $archiveDescription = term_description();
  $archiveDescription = $archiveDescription ?: $shopDescription;
  $shopModules = function_exists('get_field') && $shopPageId
    ? (get_field('flexible_modules', $shopPageId) ?: [])
    : [];
@endphp

@if (! empty($shopModules))
  @includeIf('modules.flexible-modules', ['flexibleModules' => $shopModules])
@else
  <section class="container-main py-10 md:py-16">
    <div class="space-y-4">
      <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">{{ __('Sklep', 'sage') }}</p>
      <h1 class="text-3xl font-semibold text-slate-900 md:text-5xl">{{ $shopTitle }}</h1>
      @if ($archiveDescription)
        <div class="max-w-2xl text-base text-slate-600 md:text-lg">
          {!! wp_kses_post($archiveDescription) !!}
        </div>
      @endif
    </div>
  </section>
@endif

<section class="container-main py-12 md:py-20">
  @php
    if (function_exists('wc_print_notices')) {
      wc_print_notices();
    }
  @endphp

  @if (have_posts())
    <div class="flex flex-wrap items-center justify-between gap-4 text-sm text-slate-600">
      <div>
        {!! function_exists('woocommerce_result_count') ? woocommerce_result_count() : '' !!}
      </div>
      <div class="flex items-center gap-3">
        {!! function_exists('woocommerce_catalog_ordering') ? woocommerce_catalog_ordering() : '' !!}
      </div>
    </div>

    <div class="mt-8 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
      @while (have_posts())
        @php
          the_post();
          $product = function_exists('wc_get_product') ? wc_get_product(get_the_ID()) : null;
        @endphp

        @if ($product)
          @php
            $categoryList = wc_get_product_category_list($product->get_id(), ', ');
            $shortDescription = wp_trim_words(wp_strip_all_tags($product->get_short_description()), 18);
          @endphp

          <article class="group overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition hover:shadow-lg">
            <a class="block h-full no-underline" href="{{ get_permalink() }}">
              <div class="relative aspect-[16/11] overflow-hidden bg-slate-100">
                {!! $product->get_image('woocommerce_thumbnail', ['class' => 'h-full w-full object-cover transition duration-300 group-hover:scale-105']) !!}
                @if ($product->is_on_sale())
                  <span class="absolute left-4 top-4 rounded-full bg-emerald-600 px-3 py-1 text-xs font-semibold text-white">
                    {{ __('Promocja', 'sage') }}
                  </span>
                @endif
              </div>

              <div class="space-y-2 px-6 py-5">
                @if ($categoryList)
                  <p class="text-xs uppercase tracking-[0.2em] text-slate-500">{!! $categoryList !!}</p>
                @endif
                <h2 class="text-xl font-semibold text-slate-900">{{ $product->get_name() }}</h2>
                @if ($shortDescription)
                  <p class="text-sm text-slate-600">{{ $shortDescription }}</p>
                @endif
                <div class="text-lg font-semibold text-slate-900">{!! $product->get_price_html() !!}</div>
              </div>
            </a>
          </article>
        @endif
      @endwhile
    </div>

    <div class="mt-10">
      {!! get_the_posts_pagination([
        'mid_size' => 1,
        'prev_text' => __('Poprzednie', 'sage'),
        'next_text' => __('Nastepne', 'sage'),
      ]) !!}
    </div>
  @else
    <div class="mt-10">
      <x-alert type="warning">
        {!! __('Brak produktow do wyswietlenia.', 'sage') !!}
      </x-alert>
    </div>
  @endif
</section>
