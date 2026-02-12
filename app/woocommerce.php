<?php

/**
 * WooCommerce theme integration.
 */

namespace App;

/**
 * Register WooCommerce feature support and image defaults.
 *
 * @return void
 */
add_action('after_setup_theme', function () {
    add_theme_support('woocommerce', [
        'thumbnail_image_width' => 520,
        'single_image_width' => 960,
        'product_grid' => [
            'default_rows' => 3,
            'min_rows' => 1,
            'default_columns' => 4,
            'min_columns' => 2,
            'max_columns' => 4,
        ],
    ]);

    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}, 30);

/**
 * Replace WooCommerce wrappers and default notice placements.
 *
 * @return void
 */
add_action('after_setup_theme', function () {
    if (! class_exists(\WooCommerce::class)) {
        return;
    }

    remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
    remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
    remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
    remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

    add_action('woocommerce_before_main_content', __NAMESPACE__.'\\woocommerce_wrapper_start', 10);
    add_action('woocommerce_before_main_content', __NAMESPACE__.'\\woocommerce_render_breadcrumb', 20);
    add_action('woocommerce_after_main_content', __NAMESPACE__.'\\woocommerce_wrapper_end', 10);

    remove_action('woocommerce_before_shop_loop', 'woocommerce_output_all_notices', 10);
    remove_action('woocommerce_before_single_product', 'woocommerce_output_all_notices', 10);
    remove_action('woocommerce_before_cart', 'woocommerce_output_all_notices', 10);
    remove_action('woocommerce_before_checkout_form_cart_notices', 'woocommerce_output_all_notices', 10);
    remove_action('woocommerce_cart_is_empty', 'wc_empty_cart_message', 10);

    remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
    remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
    remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display', 10);
}, 40);

/**
 * Opening wrapper for WooCommerce content rendered via Woo hooks.
 *
 * @return void
 */
function woocommerce_wrapper_start(): void
{
    echo '<section class="py-10 md:py-16"><div class="container-main">';
}

/**
 * Closing wrapper for WooCommerce content rendered via Woo hooks.
 *
 * @return void
 */
function woocommerce_wrapper_end(): void
{
    echo '</div></section>';
}

/**
 * Render custom breadcrumb partial for product archive/single views.
 *
 * @return void
 */
function woocommerce_render_breadcrumb(): void
{
    if (is_cart() || is_checkout() || is_account_page()) {
        return;
    }

    echo \Roots\view('woocommerce.partials.breadcrumb')->render();
}

/**
 * Standardize breadcrumb HTML wrappers for accessible, utility-first markup.
 *
 * @param  array  $defaults
 * @return array
 */
add_filter('woocommerce_breadcrumb_defaults', function ($defaults) {
    $defaults['delimiter'] = '<li class="mx-2 text-slate-300" aria-hidden="true">/</li>';
    $defaults['wrap_before'] = '<nav class="woocommerce-breadcrumb" aria-label="'.esc_attr__('Breadcrumb', 'sage').'"><ol class="flex flex-wrap items-center text-sm text-slate-500">';
    $defaults['wrap_after'] = '</ol></nav>';
    $defaults['before'] = '<li class="inline-flex items-center">';
    $defaults['after'] = '</li>';

    return $defaults;
});

/**
 * Add Tailwind-friendly classes to WooCommerce generated form fields.
 *
 * @param  array       $args
 * @param  string      $key
 * @param  string|null $value
 * @return array
 */
add_filter('woocommerce_form_field_args', function ($args, $key, $value) {
    $fieldClass = array_merge((array) ($args['class'] ?? []), [
        'form-row',
        'mb-5',
    ]);
    $labelClass = array_merge((array) ($args['label_class'] ?? []), [
        'mb-2',
        'block',
        'text-sm',
        'font-medium',
        'text-slate-700',
    ]);
    $inputClass = array_merge((array) ($args['input_class'] ?? []), [
        'mt-1',
        'block',
        'w-full',
        'rounded-2xl',
        'border',
        'border-slate-300',
        'bg-white',
        'px-4',
        'py-3',
        'text-sm',
        'text-slate-900',
        'placeholder:text-slate-400',
        'focus:border-slate-900',
        'focus:outline-none',
        'focus:ring-2',
        'focus:ring-slate-200',
    ]);

    if (($args['type'] ?? '') === 'textarea') {
        $inputClass[] = 'min-h-28';
    }

    if (in_array($args['type'] ?? '', ['select', 'country', 'state'], true)) {
        $inputClass[] = 'pr-10';
    }

    if (($args['type'] ?? '') === 'checkbox') {
        $labelClass[] = 'inline-flex';
        $labelClass[] = 'items-center';
        $labelClass[] = 'gap-2';
    }

    $args['class'] = array_values(array_unique(array_filter($fieldClass)));
    $args['label_class'] = array_values(array_unique(array_filter($labelClass)));
    $args['input_class'] = array_values(array_unique(array_filter($inputClass)));

    return $args;
}, 10, 3);

/**
 * Add utility classes to quantity inputs globally.
 *
 * @param  array $classes
 * @return array
 */
add_filter('woocommerce_quantity_input_classes', function ($classes) {
    $classes[] = 'h-11';
    $classes[] = 'w-20';
    $classes[] = 'rounded-full';
    $classes[] = 'border';
    $classes[] = 'border-slate-300';
    $classes[] = 'px-3';
    $classes[] = 'text-center';
    $classes[] = 'text-sm';
    $classes[] = 'focus:border-slate-900';
    $classes[] = 'focus:outline-none';
    $classes[] = 'focus:ring-2';
    $classes[] = 'focus:ring-slate-200';

    return array_values(array_unique($classes));
});

/**
 * Keep action buttons visually consistent in loops.
 *
 * @param  array             $args
 * @param  \WC_Product|false $product
 * @return array
 */
add_filter('woocommerce_loop_add_to_cart_args', function ($args, $product) {
    $args['class'] = trim(($args['class'] ?? '').' wc-button !px-4 !py-2 !text-[11px]');

    return $args;
}, 10, 2);

/**
 * Render a Blade view from WooCommerce bridge templates.
 *
 * @param  string $view
 * @param  array  $data
 * @return void
 */
if (! function_exists(__NAMESPACE__.'\\render_woocommerce_view')) {
    function render_woocommerce_view(string $view, array $data = []): void
    {
        echo \Roots\view($view, $data)->render();
    }
}

