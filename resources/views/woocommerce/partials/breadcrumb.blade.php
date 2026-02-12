{{-- Purpose: Render WooCommerce breadcrumbs with semantic list markup. --}}
@if (function_exists('woocommerce_breadcrumb'))
  <div class="mb-8">
    @php
      woocommerce_breadcrumb([
        'delimiter' => '<li class="mx-2 text-slate-300" aria-hidden="true">/</li>',
        'wrap_before' => '<nav class="woocommerce-breadcrumb" aria-label="'.esc_attr__('Breadcrumb', 'sage').'"><ol class="flex flex-wrap items-center text-sm text-slate-500">',
        'wrap_after' => '</ol></nav>',
        'before' => '<li class="inline-flex items-center">',
        'after' => '</li>',
      ]);
    @endphp
  </div>
@endif

