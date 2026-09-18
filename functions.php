<?php

require_once get_template_directory() . '/inc/button-block.php';

function vita_health_register_menus() {
    register_nav_menu('primary', __('Primary Navigation', 'vitahealthmedia'));
    register_nav_menu('footer-services', __('Footer Services', 'vitahealthmedia'));
    register_nav_menu('footer-links', __('Footer Links', 'vitahealthmedia'));
}

add_action('after_setup_theme', 'vita_health_register_menus');

function vita_health_navigation_classes($classes, $item, $args) {
    if ($args->theme_location === 'primary' && trim($item->title) === 'Kontakt') {
        $classes[] = 'navigation__contact';
    }
    return $classes;
}

add_filter('nav_menu_css_class', 'vita_health_navigation_classes', 10, 3);

function vita_health_footer_link_attributes($attributes, $item, $args) {
    if ($args->theme_location === 'footer-services' && isset($attributes['href']) && strpos($attributes['href'], '#') === 0) {
        $attributes['href'] = home_url('/') . $attributes['href'];
    }
    return $attributes;
}

add_filter('nav_menu_link_attributes', 'vita_health_footer_link_attributes', 10, 3);

function vita_health_enqueue_assets() {
    $theme_version = wp_get_theme()->get('Version');

    wp_enqueue_style(
        'vita-health-tokens',
        get_template_directory_uri() . '/assets/css/tokens.css',
        [],
        $theme_version
    );

    wp_enqueue_style(
        'vita-health-base',
        get_template_directory_uri() . '/assets/css/base.css',
        ['vita-health-tokens'],
        $theme_version
    );

    wp_enqueue_style(
        'vita-health-typography',
        get_template_directory_uri() . '/assets/css/typography.css',
        ['vita-health-tokens', 'vita-health-base'],
        $theme_version
    );

    wp_enqueue_style(
        'vita-health-buttons',
        get_template_directory_uri() . '/assets/css/buttons.css',
        ['vita-health-tokens'],
        filemtime(get_template_directory() . '/assets/css/buttons.css')
    );

    wp_enqueue_style(
        'vita-health-components',
        get_template_directory_uri() . '/assets/css/components.css',
        ['vita-health-tokens', 'vita-health-base', 'vita-health-typography', 'vita-health-buttons'],
        $theme_version
    );

    wp_enqueue_style(
        'vita-health-style',
        get_stylesheet_uri(),
        ['vita-health-components'],
        $theme_version
    );

    wp_enqueue_script(
        'vita-health-navigation',
        get_template_directory_uri() . '/assets/js/navigation.js',
        [],
        $theme_version,
        true
    );

    wp_enqueue_script(
        'vita-health-lucide',
        get_template_directory_uri() . '/assets/js/vendor/lucide.min.js',
        [],
        filemtime(get_template_directory() . '/assets/js/vendor/lucide.min.js'),
        true
    );

    wp_enqueue_script(
        'vita-health-icons',
        get_template_directory_uri() . '/assets/js/icons.js',
        ['vita-health-lucide'],
        filemtime(get_template_directory() . '/assets/js/icons.js'),
        true
    );
}

add_action('wp_enqueue_scripts', 'vita_health_enqueue_assets');
