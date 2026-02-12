{{-- Purpose: Reusable accessible form field primitive for WooCommerce templates. --}}
@php
  $fieldId = $id ?? uniqid('wc-field-');
  $fieldName = $name ?? $fieldId;
  $fieldLabel = $label ?? '';
  $fieldType = $type ?? 'text';
  $fieldValue = $value ?? '';
  $fieldRequired = (bool) ($required ?? false);
  $fieldAutocomplete = $autocomplete ?? '';
  $fieldPlaceholder = $placeholder ?? '';
  $fieldWrapperClass = $wrapperClass ?? 'mb-5';
  $fieldInputClass = $inputClass ?? 'mt-1 block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200';
  $fieldOptions = $options ?? [];
  $fieldRows = $rows ?? 4;
  $attributes = $attributes ?? [];
  $attributeHtml = '';

  foreach ($attributes as $attributeKey => $attributeValue) {
    if ($attributeValue === null || $attributeValue === false) {
      continue;
    }

    $attributeHtml .= ' '.esc_attr($attributeKey).'="'.esc_attr((string) $attributeValue).'"';
  }
@endphp

<div class="{{ $fieldWrapperClass }}">
  @if ($fieldLabel)
    <label class="mb-2 block text-sm font-medium text-slate-700" for="{{ esc_attr($fieldId) }}">
      {{ $fieldLabel }}
      @if ($fieldRequired)
        <span class="text-rose-600" aria-hidden="true">*</span>
        <span class="sr-only">{{ __('Required', 'woocommerce') }}</span>
      @endif
    </label>
  @endif

  @if ($fieldType === 'textarea')
    <textarea
      id="{{ esc_attr($fieldId) }}"
      name="{{ esc_attr($fieldName) }}"
      rows="{{ esc_attr((string) $fieldRows) }}"
      class="{{ $fieldInputClass }}"
      placeholder="{{ esc_attr($fieldPlaceholder) }}"
      @if ($fieldAutocomplete) autocomplete="{{ esc_attr($fieldAutocomplete) }}" @endif
      @if ($fieldRequired) required aria-required="true" @endif
      {!! $attributeHtml !!}
    >{{ $fieldValue }}</textarea>
  @elseif ($fieldType === 'select')
    <select
      id="{{ esc_attr($fieldId) }}"
      name="{{ esc_attr($fieldName) }}"
      class="{{ $fieldInputClass }}"
      @if ($fieldRequired) required aria-required="true" @endif
      {!! $attributeHtml !!}
    >
      @foreach ($fieldOptions as $optionValue => $optionLabel)
        <option value="{{ esc_attr((string) $optionValue) }}" @selected((string) $fieldValue === (string) $optionValue)>{{ $optionLabel }}</option>
      @endforeach
    </select>
  @else
    <input
      type="{{ esc_attr($fieldType) }}"
      id="{{ esc_attr($fieldId) }}"
      name="{{ esc_attr($fieldName) }}"
      value="{{ esc_attr((string) $fieldValue) }}"
      class="{{ $fieldInputClass }}"
      placeholder="{{ esc_attr($fieldPlaceholder) }}"
      @if ($fieldAutocomplete) autocomplete="{{ esc_attr($fieldAutocomplete) }}" @endif
      @if ($fieldRequired) required aria-required="true" @endif
      {!! $attributeHtml !!}
    />
  @endif
</div>

