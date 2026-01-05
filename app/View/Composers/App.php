<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class App extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        '*',
    ];

    /**
     * Retrieve the site name.
     */
    public function siteName(): string
    {
        return get_bloginfo('name', 'display');
    }

    public function flexibleModules(): array
    {
        if (! function_exists('get_field')) {
            return [];
        }

        $modules = get_field('flexible_modules');

        return is_array($modules) ? $modules : [];
    }

    public function flexiblePosts(): array
    {
        if (! function_exists('get_field')) {
            return [];
        }

        $posts = get_field('flexible_posts');

        return is_array($posts) ? $posts : [];
    }
}
