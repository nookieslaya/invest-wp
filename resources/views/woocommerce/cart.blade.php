{{-- Purpose: Custom responsive WooCommerce cart template with desktop row/mobile card behavior. --}}
<?php defined('ABSPATH') || exit; ?>

<section class="py-10 md:py-16">
  <div class="container-main">
    <?php do_action('woocommerce_before_cart'); ?>
    @include('woocommerce.partials.notices')
    @include('woocommerce.partials.breadcrumb')

    <header class="mb-8 space-y-3">
      <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">{{ __('Checkout', 'sage') }}</p>
      <h1 class="text-3xl font-semibold leading-tight text-slate-900 md:text-5xl">{{ __('Your cart', 'woocommerce') }}</h1>
    </header>

    @if (WC()->cart && ! WC()->cart->is_empty())
      <div class="grid gap-8 xl:grid-cols-[minmax(0,1fr)_22rem]">
        <form class="woocommerce-cart-form space-y-4" action="{{ esc_url(wc_get_cart_url()) }}" method="post">
          <?php do_action('woocommerce_before_cart_table'); ?>
          <div class="hidden grid-cols-[auto_minmax(0,1fr)_auto_auto_auto] items-center gap-4 rounded-2xl border border-slate-200 bg-slate-50 px-5 py-3 text-xs font-semibold uppercase tracking-[0.16em] text-slate-500 md:grid">
            <span class="sr-only">{{ __('Remove item', 'woocommerce') }}</span>
            <span>{{ __('Product', 'woocommerce') }}</span>
            <span>{{ __('Price', 'woocommerce') }}</span>
            <span>{{ __('Quantity', 'woocommerce') }}</span>
            <span>{{ __('Subtotal', 'woocommerce') }}</span>
          </div>

          <div class="space-y-4">
            <?php do_action('woocommerce_before_cart_contents'); ?>
            @foreach (WC()->cart->get_cart() as $cartItemKey => $cartItem)
              @php
                $_product = apply_filters('woocommerce_cart_item_product', $cartItem['data'], $cartItem, $cartItemKey);
                $productId = apply_filters('woocommerce_cart_item_product_id', $cartItem['product_id'], $cartItem, $cartItemKey);
                $productName = apply_filters('woocommerce_cart_item_name', $_product ? $_product->get_name() : '', $cartItem, $cartItemKey);
                $isVisible = $_product
                  && $_product->exists()
                  && $cartItem['quantity'] > 0
                  && apply_filters('woocommerce_cart_item_visible', true, $cartItem, $cartItemKey);
              @endphp

              @if ($isVisible)
                @php
                  $productPermalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cartItem) : '', $cartItem, $cartItemKey);
                  $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image('woocommerce_thumbnail', ['class' => 'h-20 w-20 rounded-2xl object-cover']), $cartItem, $cartItemKey);
                  $itemClass = apply_filters('woocommerce_cart_item_class', 'cart_item', $cartItem, $cartItemKey);
                  $minQuantity = $_product->is_sold_individually() ? 1 : 0;
                  $maxQuantity = $_product->is_sold_individually() ? 1 : $_product->get_max_purchase_quantity();
                @endphp

                <article class="woocommerce-cart-form__cart-item {{ esc_attr($itemClass) }} overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                  <div class="grid gap-4 p-4 md:grid-cols-[auto_minmax(0,1fr)_auto_auto_auto] md:items-center md:gap-6 md:p-5">
                    <div>
                      {!! apply_filters(
                        'woocommerce_cart_item_remove_link',
                        sprintf(
                          '<a role="button" href="%s" class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 text-xl text-slate-500 transition hover:border-slate-300 hover:text-slate-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-300" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
                          esc_url(wc_get_cart_remove_url($cartItemKey)),
                          esc_attr(sprintf(__('Remove %s from cart', 'woocommerce'), wp_strip_all_tags($productName))),
                          esc_attr($productId),
                          esc_attr($_product->get_sku())
                        ),
                        $cartItemKey
                      ) !!}
                    </div>

                    <div class="flex items-start gap-4">
                      <div class="shrink-0">
                        @if (! $productPermalink)
                          {!! $thumbnail !!}
                        @else
                          <a href="{{ esc_url($productPermalink) }}" class="inline-flex focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-300">
                            {!! $thumbnail !!}
                          </a>
                        @endif
                      </div>

                      <div class="min-w-0 space-y-2">
                        <h2 class="text-base font-semibold leading-tight text-slate-900">
                          @if (! $productPermalink)
                            {{ $productName }}
                          @else
                            {!! wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a class="no-underline hover:text-slate-700" href="%s">%s</a>', esc_url($productPermalink), esc_html($_product->get_name())), $cartItem, $cartItemKey)) !!}
                          @endif
                        </h2>

                        <div class="text-sm text-slate-600">
                          <?php do_action('woocommerce_after_cart_item_name', $cartItem, $cartItemKey); ?>
                          {!! wc_get_formatted_cart_item_data($cartItem) !!}
                        </div>

                        @if ($_product->backorders_require_notification() && $_product->is_on_backorder($cartItem['quantity']))
                          <p class="text-sm font-medium text-amber-700">
                            {!! wp_kses_post(apply_filters('woocommerce_cart_item_backorder_notification', __('Available on backorder', 'woocommerce'), $productId)) !!}
                          </p>
                        @endif
                      </div>
                    </div>

                    <div class="space-y-1 text-sm md:text-right">
                      <span class="text-xs uppercase tracking-[0.14em] text-slate-500 md:hidden">{{ __('Price', 'woocommerce') }}</span>
                      <div class="font-medium text-slate-900">
                        {!! apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cartItem, $cartItemKey) !!}
                      </div>
                    </div>

                    <div class="space-y-1">
                      <span class="text-xs uppercase tracking-[0.14em] text-slate-500 md:hidden">{{ __('Quantity', 'woocommerce') }}</span>
                      {!! apply_filters('woocommerce_cart_item_quantity', \Roots\view('woocommerce.partials.quantity', [
                        'product' => $_product,
                        'inputName' => "cart[{$cartItemKey}][qty]",
                        'inputValue' => $cartItem['quantity'],
                        'minValue' => $minQuantity,
                        'maxValue' => $maxQuantity,
                      ])->render(), $cartItemKey, $cartItem) !!}
                    </div>

                    <div class="space-y-1 text-sm md:text-right">
                      <span class="text-xs uppercase tracking-[0.14em] text-slate-500 md:hidden">{{ __('Subtotal', 'woocommerce') }}</span>
                      <div class="font-semibold text-slate-900">
                        {!! apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cartItem['quantity']), $cartItem, $cartItemKey) !!}
                      </div>
                    </div>
                  </div>
                </article>
              @endif
            @endforeach

            <?php do_action('woocommerce_cart_contents'); ?>
          </div>

          <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm md:p-5">
            @if (wc_coupons_enabled())
              <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <label class="sr-only" for="coupon_code">{{ __('Coupon code', 'woocommerce') }}</label>
                <input
                  type="text"
                  name="coupon_code"
                  id="coupon_code"
                  class="block w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200"
                  placeholder="{{ esc_attr__('Coupon code', 'woocommerce') }}"
                />
                <button type="submit" class="wc-button shrink-0 !px-4 !py-2 !text-[11px]" name="apply_coupon" value="{{ esc_attr__('Apply coupon', 'woocommerce') }}">
                  {{ __('Apply coupon', 'woocommerce') }}
                </button>
              </div>
              <?php do_action('woocommerce_cart_coupon'); ?>
            @endif

            <div class="mt-4 flex flex-wrap justify-end gap-3">
              <button type="submit" class="wc-button !px-4 !py-2 !text-[11px]" name="update_cart" value="{{ esc_attr__('Update cart', 'woocommerce') }}">
                {{ __('Update cart', 'woocommerce') }}
              </button>
            </div>

            <?php do_action('woocommerce_cart_actions'); ?>
            <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
          </div>

          <?php do_action('woocommerce_after_cart_contents'); ?>
          <?php do_action('woocommerce_after_cart_table'); ?>
        </form>

        <div class="space-y-4">
          <?php do_action('woocommerce_before_cart_collaterals'); ?>
          @include('woocommerce.partials.order-summary', ['context' => 'cart'])
        </div>
      </div>
    @else
      @include('woocommerce.partials.empty-state', [
        'title' => __('Your cart is currently empty.', 'woocommerce'),
        'message' => __('Looks like you have not added anything yet. Explore the catalog and add your first product.', 'sage'),
        'buttonLabel' => apply_filters('woocommerce_return_to_shop_text', __('Return to shop', 'woocommerce')),
        'buttonUrl' => apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop')),
      ])
    @endif

    <?php do_action('woocommerce_after_cart'); ?>
  </div>
</section>


