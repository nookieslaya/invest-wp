{{-- Purpose: Render WooCommerce notices with consistent, accessible Tailwind UI. --}}
@php
  $noticesByType = function_exists('wc_get_notices') ? wc_get_notices() : [];
  $noticeTypes = ['error', 'success', 'notice'];
  $hasNotices = false;

  foreach ($noticeTypes as $noticeType) {
    if (! empty($noticesByType[$noticeType])) {
      $hasNotices = true;
      break;
    }
  }
@endphp

@if ($hasNotices)
  <div class="woocommerce-notices-wrapper mb-6 space-y-3" role="status" aria-live="polite">
    @foreach ($noticeTypes as $noticeType)
      @php
        $items = $noticesByType[$noticeType] ?? [];
        $noticeClasses = $noticeType === 'error'
          ? 'border-rose-200 bg-rose-50 text-rose-900'
          : ($noticeType === 'success'
            ? 'border-emerald-200 bg-emerald-50 text-emerald-900'
            : 'border-slate-200 bg-slate-50 text-slate-800');
      @endphp

      @foreach ($items as $notice)
        <div class="rounded-2xl border px-4 py-3 text-sm leading-relaxed {{ $noticeClasses }}" role="{{ $noticeType === 'error' ? 'alert' : 'status' }}">
          {!! wc_kses_notice($notice['notice'] ?? '') !!}
        </div>
      @endforeach
    @endforeach
  </div>
  <?php wc_clear_notices(); ?>
@endif



