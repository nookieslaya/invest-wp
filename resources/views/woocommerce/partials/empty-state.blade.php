{{-- Purpose: Reusable empty-state card for WooCommerce pages. --}}
@php
  $title = $title ?? __('Nothing to display yet.', 'sage');
  $message = $message ?? __('Try browsing available products to get started.', 'sage');
  $buttonLabel = $buttonLabel ?? '';
  $buttonUrl = $buttonUrl ?? '';
@endphp

<section class="rounded-3xl border border-dashed border-slate-300 bg-slate-50 px-6 py-12 text-center md:px-10">
  <h2 class="text-2xl font-semibold text-slate-900">{{ $title }}</h2>
  <p class="mx-auto mt-3 max-w-xl text-sm leading-relaxed text-slate-600 md:text-base">
    {{ $message }}
  </p>

  @if ($buttonLabel && $buttonUrl)
    <div class="mt-6">
      <a class="wc-button inline-flex items-center justify-center rounded-full px-5 py-2 text-xs font-semibold uppercase tracking-[0.2em]" href="{{ esc_url($buttonUrl) }}">
        {{ $buttonLabel }}
      </a>
    </div>
  @endif
</section>

