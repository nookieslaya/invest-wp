{{-- Purpose: Reusable navigation UI for the WooCommerce My Account area. --}}
<?php $menuItems = function_exists('wc_get_account_menu_items') ? wc_get_account_menu_items() : []; ?>

@if (! empty($menuItems))
  <nav aria-label="{{ esc_attr__('Account pages', 'woocommerce') }}">
    <ul class="flex gap-2 overflow-x-auto pb-1 lg:flex-col lg:gap-1 lg:overflow-visible">
      @foreach ($menuItems as $endpoint => $label)
        @php
          $isCurrent = wc_is_current_account_menu_item($endpoint);
          $linkClasses = $isCurrent
            ? 'bg-slate-900 text-white'
            : 'bg-white text-slate-700 hover:bg-slate-100';
        @endphp
        <li class="shrink-0">
          <a
            class="inline-flex w-full items-center rounded-2xl border border-slate-200 px-4 py-3 text-sm font-medium transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-300 lg:w-auto {{ $linkClasses }}"
            href="{{ esc_url(wc_get_account_endpoint_url($endpoint)) }}"
            @if ($isCurrent) aria-current="page" @endif
          >
            {{ $label }}
          </a>
        </li>
      @endforeach
    </ul>
  </nav>
@endif


