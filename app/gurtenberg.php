<?php

// Custom Gutenberg functions.

namespace App;

function gutenberg_default_colors()
{
    add_theme_support('editor-color-palette', [
        [
            'name' => __('Midnight 666', 'sage'),
            'slug' => 'midnight',
            'color' => '#0f172a',
        ],
        [
            'name' => __('Graphite', 'sage'),
            'slug' => 'graphite',
            'color' => '#334155',
        ],
        [
            'name' => __('Slate', 'sage'),
            'slug' => 'slate',
            'color' => '#475569',
        ],
        [
            'name' => __('Stone', 'sage'),
            'slug' => 'stone',
            'color' => '#cbd5e1',
        ],
        [
            'name' => __('Cloud', 'sage'),
            'slug' => 'cloud',
            'color' => '#f8fafc',
        ],
        [
            'name' => __('Crimson', 'sage'),
            'slug' => 'crimson',
            'color' => '#da0f0f',
        ],
        [
            'name' => __('Rose', 'sage'),
            'slug' => 'rose',
            'color' => '#ef4444',
        ],
        [
            'name' => __('Blush', 'sage'),
            'slug' => 'blush',
            'color' => '#fee2e2',
        ],
        [
            'name' => __('Amber', 'sage'),
            'slug' => 'amber',
            'color' => '#d97706',
        ],
        [
            'name' => __('Gold', 'sage'),
            'slug' => 'gold',
            'color' => '#f59e0b',
        ],
        [
            'name' => __('Emerald', 'sage'),
            'slug' => 'emerald',
            'color' => '#16a34a',
        ],
        [
            'name' => __('Sky', 'sage'),
            'slug' => 'sky',
            'color' => '#0ea5e9',
        ],
    ]);
}

add_action('after_setup_theme', __NAMESPACE__.'\\gutenberg_default_colors');
