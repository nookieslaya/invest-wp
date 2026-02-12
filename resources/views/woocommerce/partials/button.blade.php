{{-- Purpose: Reusable button/link primitive for WooCommerce interfaces. --}}
@php
  $tag = $tag ?? 'a';
  $label = $label ?? '';
  $href = $href ?? '#';
  $type = $type ?? 'button';
  $name = $name ?? '';
  $value = $value ?? '';
  $ariaLabel = $ariaLabel ?? '';
  $class = $class ?? '';
  $baseClass = 'wc-button inline-flex items-center justify-center rounded-full px-5 py-2 text-xs font-semibold uppercase tracking-[0.2em]';
@endphp

@if ($tag === 'button')
  <button
    type="{{ esc_attr($type) }}"
    @if ($name) name="{{ esc_attr($name) }}" @endif
    @if ($value !== '') value="{{ esc_attr($value) }}" @endif
    @if ($ariaLabel) aria-label="{{ esc_attr($ariaLabel) }}" @endif
    class="{{ $baseClass }} {{ $class }}"
  >
    {{ $label }}
  </button>
@else
  <a
    href="{{ esc_url($href) }}"
    @if ($ariaLabel) aria-label="{{ esc_attr($ariaLabel) }}" @endif
    class="{{ $baseClass }} {{ $class }}"
  >
    {{ $label }}
  </a>
@endif

