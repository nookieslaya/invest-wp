{{-- Purpose: Styled My Account orders list with responsive table/card presentation. --}}
@php
  defined('ABSPATH') || exit;
  $buttonClass = $wp_button_class ?? '';
  do_action('woocommerce_before_account_orders', $has_orders);
@endphp

@if ($has_orders)
  <section class="space-y-4">
    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white">
      <div class="overflow-x-auto">
        <table class="woocommerce-orders-table woocommerce-MyAccount-orders min-w-full text-left text-sm">
          <thead class="bg-slate-50 text-xs uppercase tracking-[0.14em] text-slate-500">
            <tr>
              @foreach (wc_get_account_orders_columns() as $columnId => $columnName)
                <th scope="col" class="px-4 py-3">{{ $columnName }}</th>
              @endforeach
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-200">
            @foreach ($customer_orders->orders as $customerOrder)
              @php
                $order = wc_get_order($customerOrder);
                $itemCount = $order->get_item_count() - $order->get_item_count_refunded();
              @endphp
              <tr class="woocommerce-orders-table__row--status-{{ esc_attr($order->get_status()) }}">
                @foreach (wc_get_account_orders_columns() as $columnId => $columnName)
                  <td class="px-4 py-4 align-top">
                    @if (has_action('woocommerce_my_account_my_orders_column_'.$columnId))
                      <?php do_action('woocommerce_my_account_my_orders_column_'.$columnId, $order); ?>
                    @elseif ($columnId === 'order-number')
                      <a href="{{ esc_url($order->get_view_order_url()) }}" class="font-semibold text-slate-900 no-underline hover:text-slate-700" aria-label="{{ esc_attr(sprintf(__('View order number %s', 'woocommerce'), $order->get_order_number())) }}">
                        {{ _x('#', 'hash before order number', 'woocommerce').$order->get_order_number() }}
                      </a>
                    @elseif ($columnId === 'order-date')
                      <time datetime="{{ esc_attr($order->get_date_created()->date('c')) }}">{{ wc_format_datetime($order->get_date_created()) }}</time>
                    @elseif ($columnId === 'order-status')
                      {{ wc_get_order_status_name($order->get_status()) }}
                    @elseif ($columnId === 'order-total')
                      {!! wp_kses_post(sprintf(_n('%1$s for %2$s item', '%1$s for %2$s items', $itemCount, 'woocommerce'), $order->get_formatted_order_total(), $itemCount)) !!}
                    @elseif ($columnId === 'order-actions')
                      <?php $actions = wc_get_account_orders_actions($order); ?>
                      @if (! empty($actions))
                        <div class="flex flex-wrap gap-2">
                          @foreach ($actions as $actionKey => $action)
                            @php
                              $actionAriaLabel = empty($action['aria-label'])
                                ? sprintf(__('%1$s order number %2$s', 'woocommerce'), $action['name'], $order->get_order_number())
                                : $action['aria-label'];
                            @endphp
                            <a href="{{ esc_url($action['url']) }}" class="woocommerce-button button {{ sanitize_html_class($actionKey) }}{{ esc_attr($buttonClass) }} wc-button !px-4 !py-2 !text-[11px]" aria-label="{{ esc_attr($actionAriaLabel) }}">
                              {{ $action['name'] }}
                            </a>
                          @endforeach
                        </div>
                      @endif
                    @endif
                  </td>
                @endforeach
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <?php do_action('woocommerce_before_account_orders_pagination'); ?>
    @if ((int) $customer_orders->max_num_pages > 1)
      <div class="flex flex-wrap items-center justify-between gap-3">
        @if ($current_page !== 1)
          <a class="woocommerce-button woocommerce-button--previous button{{ esc_attr($buttonClass) }} wc-button !px-4 !py-2 !text-[11px]" href="{{ esc_url(wc_get_endpoint_url('orders', $current_page - 1)) }}">
            {{ __('Previous', 'woocommerce') }}
          </a>
        @endif

        @if ((int) $customer_orders->max_num_pages !== (int) $current_page)
          <a class="woocommerce-button woocommerce-button--next button{{ esc_attr($buttonClass) }} wc-button !px-4 !py-2 !text-[11px]" href="{{ esc_url(wc_get_endpoint_url('orders', $current_page + 1)) }}">
            {{ __('Next', 'woocommerce') }}
          </a>
        @endif
      </div>
    @endif
  </section>
@else
  @include('woocommerce.partials.empty-state', [
    'title' => __('No order has been made yet.', 'woocommerce'),
    'message' => __('You can place your first order by browsing our products.', 'sage'),
    'buttonLabel' => __('Browse products', 'woocommerce'),
    'buttonUrl' => apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop')),
  ])
@endif

<?php do_action('woocommerce_after_account_orders', $has_orders); ?>

