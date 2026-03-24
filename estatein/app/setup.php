<?php

/**
 * Theme setup.
 */

namespace App;

use Illuminate\Support\Facades\Vite;
use function Roots\add_actions;

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
add_action('admin_head', function () {
    if (! get_current_screen()?->is_block_editor()) {
        return;
    }

    if (! Vite::isRunningHot()) {
        $dependencies = json_decode(Vite::content('editor.deps.json'));

        foreach ($dependencies as $dependency) {
            if (! wp_script_is($dependency)) {
                wp_enqueue_script($dependency);
            }
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
 * Disable on-demand block asset loading.
 *
 * @link https://core.trac.wordpress.org/ticket/61965
 */
add_filter('should_load_separate_core_block_assets', '__return_false');

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
        'home_menu' => __('Home Menu', 'estatein'),
        'footer_home' => __('Footer - Home', 'estatein'),
        'footer_about_us' => __('Footer - About Us', 'estatein'),
        'footer_properties' => __('Footer - Properties', 'estatein'),
        'footer_services' => __('Footer - Services', 'estatein'),
        'footer_contact_us' => __('Footer - Contact Us', 'estatein'),
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
        'name' => __('Primary', 'estatein'),
        'id' => 'sidebar-primary',
    ] + $config);

    register_sidebar([
        'name' => __('Footer', 'estatein'),
        'id' => 'sidebar-footer',
    ] + $config);
});

function get_or_create_page(string $slug, string $title): int {
    $existing = get_page_by_path($slug);

    return $existing
        ? $existing->ID
        : wp_insert_post([
            'post_title' => $title,
            'post_name' => $slug,
            'post_status' => 'publish',
            'post_type' => 'page',
        ]);
}

function get_or_create_menu(string $name, array $items): int {
    $menu = wp_get_nav_menu_object($name);

    if ($menu) {
        return $menu->term_id;
    }

    $menu_id = wp_create_nav_menu($name);

    foreach ($items as $item) {
        wp_update_nav_menu_item($menu_id, 0, $item);
    }

    return $menu_id;
}

add_action('after_switch_theme', function () {
    $pages = [
        'home' => 'Home',
        'about-us' => 'About Us',
        'properties' => 'Properties',
        'services' => 'Services',
        'contact-us' => 'Contact Us',
    ];

    $page_ids = [];
    foreach ($pages as $slug => $title) {
        $page_ids[$slug] = get_or_create_page($slug, $title);
    }

    // Set home page as front page
    update_option('show_on_front', 'page');
    update_option('page_on_front', $page_ids['home']);

    // Set permalink structure
    global $wp_rewrite;
    $wp_rewrite->set_permalink_structure('/%postname%/');
    $wp_rewrite->flush_rules();

    // Create home menu with page links
    $home_menu_items = [];
    foreach (['home', 'about-us', 'properties', 'services'] as $slug) {
        $home_menu_items[] = [
            'menu-item-title' => $pages[$slug],
            'menu-item-object' => 'page',
            'menu-item-object-id' => $page_ids[$slug],
            'menu-item-type' => 'post_type',
            'menu-item-status' => 'publish',
        ];
    }

    // Create footer menus with custom links
    $footer_menus = [
        'footer_home' => [
            'name' => 'Home',
            'items' => [
                'Hero Section' => '/',
                'Features' => '/#features',
                'Properties' => '/#properties',
                'Testimonials' => '/#testimonials',
                "FAQ's" => '/#faqs',
            ],
        ],
        'footer_about_us' => [
            'name' => 'About Us',
            'items' => [
                'Our Story' => '/about-us#our-story',
                'Our Works' => '/about-us#our-works',
                'How It Works' => '/about-us#how-it-works',
                'Our Team' => '/about-us#our-team',
                'Our Clients' => '/about-us#our-clients',
            ],
        ],
        'footer_properties' => [
            'name' => 'Properties',
            'items' => [
                'Portfolio' => '/properties#portfolio',
                'Categories' => '/properties#categories',
            ],
        ],
        'footer_services' => [
            'name' => 'Services',
            'items' => [
                'Valuation Mastery' => '/services#valuation-mastery',
                'Strategic Marketing' => '/services#strategic-marketing',
                'Negotiation Wizardry' => '/services#negotiation-wizardry',
                'Closing Success' => '/services#closing-success',
                'Property Management' => '/services#property-management',
            ],
        ],
        'footer_contact_us' => [
            'name' => 'Contact Us',
            'items' => [
                'Contact Form' => '/contact-us#contact-form',
                'Our Offices' => '/contact-us#our-offices',
            ],
        ],
    ];

    $menu_locations = [
        'home_menu' => get_or_create_menu('Home Menu', $home_menu_items),
    ];

    foreach ($footer_menus as $location => $menu_data) {
        $items = [];
        foreach ($menu_data['items'] as $title => $url) {
            $items[] = [
                'menu-item-title' => $title,
                'menu-item-url' => home_url($url),
                'menu-item-type' => 'custom',
                'menu-item-status' => 'publish',
            ];
        }
        $menu_locations[$location] = get_or_create_menu($menu_data['name'], $items);
    }

    set_theme_mod('nav_menu_locations', [
        ...get_theme_mod('nav_menu_locations', []),
        ...$menu_locations,
    ]);
});