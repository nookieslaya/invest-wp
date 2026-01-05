<?php

/**
 * Theme filters.
 */

namespace App;

/**
 * Add "… Continued" to the excerpt.
 *
 * @return string
 */
add_filter('excerpt_more', function () {
    return sprintf(' &hellip; <a href="%s">%s</a>', get_permalink(), __('Continued', 'sage'));
});

add_filter('nav_menu_css_class', function ($classes, $item, $args) {
    if (($args->theme_location ?? '') !== 'primary_navigation') {
        return $classes;
    }

    $classes[] = 'relative';

    if (in_array('menu-item-has-children', $classes, true)) {
        $classes[] = 'group';
    }

    return $classes;
}, 10, 3);

add_filter('nav_menu_link_attributes', function ($atts, $item, $args) {
    if (($args->theme_location ?? '') !== 'primary_navigation') {
        return $atts;
    }

    $class = 'relative !no-underline inline-flex w-full items-center gap-2 rounded-full px-3 py-2 text-sm font-medium text-slate-900 transition hover:text-slate-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-300 md:w-auto';

    if (in_array('menu-item-has-children', $item->classes ?? [], true)) {
        $class .= " after:ml-2 after:text-xs after:text-slate-500 after:content-['v']";
    }

    $atts['class'] = trim(($atts['class'] ?? '').' '.$class);

    return $atts;
}, 10, 3);

add_filter('nav_menu_submenu_css_class', function ($classes, $args, $depth) {
    if (($args->theme_location ?? '') !== 'primary_navigation') {
        return $classes;
    }

    $classes[] = 'm-0';
    $classes[] = '-mt-2';
    $classes[] = 'hidden';
    $classes[] = 'list-none';
    $classes[] = 'flex-col';
    $classes[] = 'gap-2';
    $classes[] = 'border-l';
    $classes[] = 'border-slate-200';
    $classes[] = 'p-0';
    $classes[] = 'pl-4';
    $classes[] = 'md:absolute';
    $classes[] = 'md:left-0';
    $classes[] = 'md:top-full';
    $classes[] = 'md:min-w-[220px]';
    $classes[] = 'md:rounded-xl';
    $classes[] = 'md:border';
    $classes[] = 'md:border-slate-200';
    $classes[] = 'md:bg-white';
    $classes[] = 'md:p-4';
    $classes[] = 'md:pl-0';
    $classes[] = 'md:shadow-xl';
    $classes[] = 'md:opacity-0';
    $classes[] = 'md:invisible';
    $classes[] = 'md:translate-y-2';
    $classes[] = 'md:transition';
    $classes[] = 'md:duration-200';

    return array_values(array_unique($classes));
}, 10, 3);

add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);
add_filter('comments_array', '__return_empty_array', 10, 2);
add_filter('get_comments_number', '__return_zero', 10, 2);
