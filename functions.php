<?php

function vita_health_register_menus() {
    register_nav_menu('primary', __('Primary Navigation', 'vitahealthmedia'));
}

add_action('after_setup_theme', 'vita_health_register_menus');

function vita_health_navigation_classes($classes, $item, $args) {
    if ($args->theme_location === 'primary' && trim($item->title) === 'Kontakt') {
        $classes[] = 'navigation__contact';
    }
    return $classes;
}

add_filter('nav_menu_css_class', 'vita_health_navigation_classes', 10, 3);

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
        'vita-health-components',
        get_template_directory_uri() . '/assets/css/components.css',
        ['vita-health-tokens', 'vita-health-base', 'vita-health-typography'],
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
}

add_action('wp_enqueue_scripts', 'vita_health_enqueue_assets');
