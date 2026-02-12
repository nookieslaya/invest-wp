{{-- Purpose: Render a consistent WooCommerce quantity input block. --}}
<?php $product = $product ?? null; ?>

@if ($product instanceof \WC_Product)
  @php
    $inputName = $inputName ?? 'quantity';
    $inputValue = isset($inputValue) ? (float) $inputValue : 1;
    $minValue = isset($minValue) ? (float) $minValue : 1;
    $maxValue = isset($maxValue) ? (float) $maxValue : $product->get_max_purchase_quantity();
    $inputId = $inputId ?? uniqid('quantity_', true);
  @endphp

  <div class="woocommerce-quantity">
    {!! woocommerce_quantity_input([
      'input_name' => $inputName,
      'input_value' => $inputValue,
      'max_value' => $maxValue,
      'min_value' => $minValue,
      'product_name' => $product->get_name(),
      'input_id' => $inputId,
    ], $product, false) !!}
  </div>
@endif


