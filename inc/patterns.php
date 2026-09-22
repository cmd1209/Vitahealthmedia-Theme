<?php

function vita_health_register_pattern_category() {
    register_block_pattern_category('vita-health', [
        'label' => __('Vita Health', 'vitahealthmedia'),
    ]);
    // Explicit registration also discovers newly deployed patterns when the theme's
    // automatic pattern-file cache has not yet been refreshed.
    $patterns = [
        'quote-section' => __('Quote Section', 'vitahealthmedia'),
        'headline-lead' => __('Headline + Lead', 'vitahealthmedia'),
        'leistung-card' => __('Leistung Card', 'vitahealthmedia'),
        'leistung-grid' => __('Leistung Grid', 'vitahealthmedia'),
        'leistung-section' => __('Leistung Section', 'vitahealthmedia'),
        'partner-logos' => __('Partner Logos', 'vitahealthmedia'),
    ];
    foreach ($patterns as $slug => $title) {
        if (WP_Block_Patterns_Registry::get_instance()->is_registered('vita-health/' . $slug)) {
            continue;
        }
        register_block_pattern('vita-health/' . $slug, [
            'title'         => $title,
            'categories'    => ['vita-health'],
            'viewportWidth' => 1493,
            'filePath'      => get_template_directory() . '/patterns/' . $slug . '.php',
        ]);
    }
}
add_action('init', 'vita_health_register_pattern_category');

function vita_health_pattern_editor_support() {
    add_theme_support('align-wide');
    $palette = get_theme_support('editor-color-palette');
    $colors = $palette ? $palette[0] : [];
    $colors[] = [
        'name'  => __('Coral highlight', 'vitahealthmedia'),
        'slug'  => 'vita-coral',
        // WordPress's native color picker requires a literal, matching --color-accent-warm.
        'color' => '#ff8c73',
    ];
    add_theme_support('editor-color-palette', $colors);
}
add_action('after_setup_theme', 'vita_health_pattern_editor_support');

function vita_health_pattern_styles() {
    if (is_admin()) {
        wp_enqueue_style('vita-health-base', get_template_directory_uri() . '/assets/css/base.css', ['vita-health-tokens'], filemtime(get_template_directory() . '/assets/css/base.css'));
    }
    wp_enqueue_style(
        'vita-health-quote-section',
        get_template_directory_uri() . '/assets/css/components/quote-section.css',
        ['vita-health-base', 'vita-health-typography'],
        filemtime(get_template_directory() . '/assets/css/components/quote-section.css')
    );
    wp_enqueue_style(
        'vita-health-leistung',
        get_template_directory_uri() . '/assets/css/components/leistung.css',
        ['vita-health-base', 'vita-health-typography'],
        filemtime(get_template_directory() . '/assets/css/components/leistung.css')
    );
}
add_action('enqueue_block_assets', 'vita_health_pattern_styles');
