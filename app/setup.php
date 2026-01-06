<?php

/**
 * Theme setup.
 */

namespace App;

use Illuminate\Support\Facades\Vite;

/**
 * Inject styles into the block editor.
 *
 * @return array
 */
add_filter('block_editor_settings_all', function ($settings) {
    $style = Vite::asset('resources/css/editor.css');

    $settings['styles'][] = [
        'css' => "@import url('{$style}')",
    ];

    return $settings;
});

/**
 * Inject scripts into the block editor.
 *
 * @return void
 */
add_filter('admin_head', function () {
    if (! get_current_screen()?->is_block_editor()) {
        return;
    }

    $dependencies = json_decode(Vite::content('editor.deps.json'));

    foreach ($dependencies as $dependency) {
        if (! wp_script_is($dependency)) {
            wp_enqueue_script($dependency);
        }
    }

    echo Vite::withEntryPoints([
        'resources/js/editor.js',
    ])->toHtml();
});

/**
 * Use the generated theme.json file.
 *
 * @return string
 */
add_filter('theme_file_path', function ($path, $file) {
    return $file === 'theme.json'
        ? public_path('build/assets/theme.json')
        : $path;
}, 10, 2);

/**
 * Register the initial theme setup.
 *
 * @return void
 */
add_action('after_setup_theme', function () {
    /**
     * Disable full-site editing support.
     *
     * @link https://wptavern.com/gutenberg-10-5-embeds-pdfs-adds-verse-block-color-options-and-introduces-new-patterns
     */
    remove_theme_support('block-templates');

    /**
     * Register the navigation menus.
     *
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus([
        'primary_navigation' => __('Primary Navigation', 'sage'),
        'footer_navigation' => __('Footer Navigation', 'sage'),
    ]);

    /**
     * Disable the default block patterns.
     *
     * @link https://developer.wordpress.org/block-editor/developers/themes/theme-support/#disabling-the-default-block-patterns
     */
    remove_theme_support('core-block-patterns');

    /**
     * Enable plugins to manage the document title.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support('title-tag');

    /**
     * Enable post thumbnail support.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    /**
     * Enable responsive embed support.
     *
     * @link https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#responsive-embedded-content
     */
    add_theme_support('responsive-embeds');

    /**
     * Enable HTML5 markup support.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support('html5', [
        'caption',
        'comment-form',
        'comment-list',
        'gallery',
        'search-form',
        'script',
        'style',
    ]);

    /**
     * Enable selective refresh for widgets in customizer.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#customize-selective-refresh-widgets
     */
    add_theme_support('customize-selective-refresh-widgets');
}, 20);

/**
 * Register the theme sidebars.
 *
 * @return void
 */
add_action('widgets_init', function () {
    $config = [
        'before_widget' => '<section class="widget %1$s %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ];

    register_sidebar([
        'name' => __('Primary', 'sage'),
        'id' => 'sidebar-primary',
    ] + $config);

    register_sidebar([
        'name' => __('Footer', 'sage'),
        'id' => 'sidebar-footer',
    ] + $config);
});

add_action('init', function () {
    register_post_type('investment', [
        'labels' => [
            'name' => __('Inwestycje', 'sage'),
            'singular_name' => __('Inwestycja', 'sage'),
            'add_new' => __('Dodaj nowa', 'sage'),
            'add_new_item' => __('Dodaj nowa inwestycje', 'sage'),
            'edit_item' => __('Edytuj inwestycje', 'sage'),
            'new_item' => __('Nowa inwestycja', 'sage'),
            'view_item' => __('Zobacz inwestycje', 'sage'),
            'search_items' => __('Szukaj inwestycji', 'sage'),
            'not_found' => __('Brak inwestycji', 'sage'),
            'not_found_in_trash' => __('Brak inwestycji w koszu', 'sage'),
            'all_items' => __('Wszystkie inwestycje', 'sage'),
            'menu_name' => __('Inwestycje', 'sage'),
        ],
        'public' => true,
        'has_archive' => 'inwestycje',
        'menu_position' => 20,
        'menu_icon' => 'dashicons-building',
        'supports' => ['title', 'editor', 'excerpt', 'thumbnail'],
        'rewrite' => [
            'slug' => 'inwestycje',
            'with_front' => false,
        ],
        'show_in_rest' => true,
    ]);

    register_taxonomy('inwestycja', ['post'], [
        'labels' => [
            'name' => __('Inwestycje', 'sage'),
            'singular_name' => __('Inwestycja', 'sage'),
            'search_items' => __('Szukaj inwestycji', 'sage'),
            'all_items' => __('Wszystkie inwestycje', 'sage'),
            'edit_item' => __('Edytuj inwestycje', 'sage'),
            'update_item' => __('Aktualizuj inwestycje', 'sage'),
            'add_new_item' => __('Dodaj nowa inwestycje', 'sage'),
            'new_item_name' => __('Nowa inwestycja', 'sage'),
            'menu_name' => __('Inwestycje', 'sage'),
        ],
        'public' => true,
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'rewrite' => [
            'slug' => 'inwestycja',
            'with_front' => false,
        ],
    ]);

    add_rewrite_endpoint('mieszkanie', EP_PERMALINK);

});

add_action('init', function () {
    $types = ['post', 'page', 'attachment'];
    foreach ($types as $type) {
        remove_post_type_support($type, 'comments');
        remove_post_type_support($type, 'trackbacks');
    }
}, 20);

add_action('admin_post_nopriv_investment_contact', function () {
    $handle = function () {
        if (! isset($_POST['investment_contact_nonce']) || ! wp_verify_nonce($_POST['investment_contact_nonce'], 'investment_contact')) {
            return 'error';
        }

        $name = isset($_POST['contact_name']) ? sanitize_text_field(wp_unslash($_POST['contact_name'])) : '';
        $phone = isset($_POST['contact_phone']) ? sanitize_text_field(wp_unslash($_POST['contact_phone'])) : '';
        $email = isset($_POST['contact_email']) ? sanitize_email(wp_unslash($_POST['contact_email'])) : '';
        $message = isset($_POST['contact_message']) ? sanitize_textarea_field(wp_unslash($_POST['contact_message'])) : '';
        $formSubject = isset($_POST['form_subject']) ? sanitize_text_field(wp_unslash($_POST['form_subject'])) : '';
        $apartmentInfo = isset($_POST['apartment_info']) ? sanitize_text_field(wp_unslash($_POST['apartment_info'])) : '';
        $investmentTitle = isset($_POST['investment_title']) ? sanitize_text_field(wp_unslash($_POST['investment_title'])) : '';
        $apartmentUrl = isset($_POST['apartment_url']) ? esc_url_raw(wp_unslash($_POST['apartment_url'])) : '';

        if (! $name || ! $phone || ! $email || ! $message) {
            return 'error';
        }

        $subject = $formSubject ?: ($investmentTitle ? "Zapytanie o mieszkanie - {$investmentTitle}" : 'Zapytanie o mieszkanie');
        $bodyLines = [
            "Imie i nazwisko: {$name}",
            "Telefon: {$phone}",
            "Email: {$email}",
        ];

        if ($investmentTitle) {
            $bodyLines[] = "Inwestycja: {$investmentTitle}";
        }

        if ($apartmentInfo) {
            $bodyLines[] = "Lokal: {$apartmentInfo}";
        }

        if ($apartmentUrl) {
            $bodyLines[] = "Link: {$apartmentUrl}";
        }

        $bodyLines[] = '';
        $bodyLines[] = 'Wiadomosc:';
        $bodyLines[] = $message;

        $headers = [];
        if ($email) {
            $headers[] = "Reply-To: {$email}";
        }

        $customRecipient = function_exists('get_field') ? (get_field('contact_form_email', 'option') ?: '') : '';
        $recipient = $customRecipient ?: get_option('admin_email');
        $sent = wp_mail($recipient, $subject, implode("\n", $bodyLines), $headers);

        return $sent ? 'sent' : 'error';
    };

    $status = $handle();
    $redirect = isset($_POST['redirect']) ? esc_url_raw(wp_unslash($_POST['redirect'])) : wp_get_referer();
    $anchor = isset($_POST['redirect_anchor']) ? sanitize_text_field(wp_unslash($_POST['redirect_anchor'])) : '';
    $redirect = $redirect ?: home_url('/');
    $redirect = remove_query_arg('contact', $redirect);
    $redirect = add_query_arg('contact', $status, $redirect);
    if ($anchor) {
        $redirect .= '#'.ltrim($anchor, '#');
    }

    wp_safe_redirect($redirect);
    exit;
});

add_action('admin_post_investment_contact', function () {
    do_action('admin_post_nopriv_investment_contact');
});

add_filter('query_vars', function ($vars) {
    $vars[] = 'mieszkanie';
    return $vars;
});

add_filter('post_type_link', function ($postLink, $post) {
    if (($post->post_type ?? '') !== 'investment') {
        return $postLink;
    }

    return home_url('/inwestycje/'.$post->post_name.'/');
}, 10, 2);

add_filter('upload_mimes', function ($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';
    return $mimes;
});

add_filter('wp_check_filetype_and_ext', function ($data, $file, $filename, $mimes) {
    $filetype = wp_check_filetype($filename, $mimes);
    if ($filetype['ext'] === 'svg' || $filetype['ext'] === 'svgz') {
        return [
            'ext' => $filetype['ext'],
            'type' => $filetype['type'],
            'proper_filename' => $data['proper_filename'] ?? null,
        ];
    }

    return $data;
}, 10, 4);

class Inwestycja_Radio_Walker extends \Walker_Category
{
    public function start_el(&$output, $category, $depth = 0, $args = [], $id = 0): void
    {
        $taxonomy = $args['taxonomy'] ?? 'category';
        $name = "tax_input[{$taxonomy}][]";
        $checked = in_array($category->term_id, (array) ($args['selected_cats'] ?? []), true);

        $output .= '<li id="'.$taxonomy.'-'.$category->term_id.'">';
        $output .= '<label class="selectit">';
        $output .= '<input type="radio" name="'.$name.'" value="'.$category->term_id.'"'.checked($checked, true, false).' /> ';
        $output .= esc_html($category->name);
        $output .= '</label>';
    }

    public function end_el(&$output, $category, $depth = 0, $args = []): void
    {
        $output .= "</li>\n";
    }
}

add_filter('wp_terms_checklist_args', function ($args, $post_id = null) {
    if (($args['taxonomy'] ?? '') !== 'inwestycja') {
        return $args;
    }

    $args['walker'] = new \App\Inwestycja_Radio_Walker();
    $args['checked_ontop'] = false;

    return $args;
}, 10, 2);

if (! function_exists(__NAMESPACE__.'\\get_field_partial')) {
    function get_field_partial(string $partial)
    {
        $partial = str_replace('.', '/', $partial);
        $path = get_theme_file_path("app/fields/{$partial}.php");

        if (! file_exists($path)) {
            return null;
        }

        return require $path;
    }
}

add_action('acf/init', function () {
    if (! function_exists('acf_add_local_field_group')) {
        return;
    }

    if (function_exists('acf_add_options_page')) {
        acf_add_options_page([
            'page_title' => 'General Settings',
            'menu_title' => 'General Settings',
            'menu_slug' => 'theme-general-settings',
            'capability' => 'edit_posts',
            'redirect' => false,
        ]);
    }

    if (! class_exists(\StoutLogic\AcfBuilder\FieldsBuilder::class)) {
        return;
    }

    $fieldsPath = get_theme_file_path('app/fields');

    if (! is_dir($fieldsPath)) {
        return;
    }

    $fieldFiles = glob($fieldsPath.'/*.php');

    if (! $fieldFiles) {
        return;
    }

    foreach ($fieldFiles as $file) {
        $fields = require $file;

        if ($fields instanceof \StoutLogic\AcfBuilder\FieldsBuilder) {
            acf_add_local_field_group($fields->build());
        }
    }
});

add_action('admin_enqueue_scripts', function ($hook) {
    if (! in_array($hook, ['post.php', 'post-new.php'], true)) {
        return;
    }

    $css = '
      .acf-field[data-name="apartments"] > .acf-input > .acf-repeater > .acf-table > tbody > tr.acf-row > td {
        background: #ffffff !important;
      }
      .acf-field[data-name="apartments"] > .acf-input > .acf-repeater > .acf-table > tbody > tr.acf-row:nth-of-type(even) > td {
        background: #e2e8f0 !important;
      }
      .acf-field[data-name="apartments"] > .acf-input > .acf-repeater > .acf-table > tbody > tr.acf-row {
        outline: 2px solid #e2e8f0;
        outline-offset: -2px;
      }
      .acf-field[data-name="apartments"] > .acf-input > .acf-repeater > .acf-table > tbody > tr.acf-row > td.acf-row-handle {
        background: inherit !important;
        border-right: 1px solid #e2e8f0;
      }
      .acf-field[data-name="apartments"] > .acf-input > .acf-repeater > .acf-table > tbody > tr.acf-row > td:first-child {
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
      }
      .acf-field[data-name="apartments"] > .acf-input > .acf-repeater > .acf-table > tbody > tr.acf-row > td:last-child {
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
      }
    ';

    wp_register_style('wszedzie-acf-apartments', false);
    wp_enqueue_style('wszedzie-acf-apartments');
    wp_add_inline_style('wszedzie-acf-apartments', $css);
});
