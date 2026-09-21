<?php

// Native paragraph styles let editors choose semantic roles without CSS classes.
function vita_health_register_typography_styles() {
    register_block_style('core/paragraph', [
        'name' => 'vita-lead',
        'label' => __('Vita Lead', 'vitahealthmedia'),
    ]);
    register_block_style('core/paragraph', [
        'name' => 'vita-eyebrow',
        'label' => __('Vita Eyebrow', 'vitahealthmedia'),
    ]);
}
add_action('init', 'vita_health_register_typography_styles');

function vita_health_typography_editor_support() {
    // WordPress needs numeric sizes for its picker. These mirror tokens.css;
    // the corresponding frontend classes below resolve through the CSS tokens.
    $sizes = [
        ['xs', 12, __('Label small', 'vitahealthmedia')],
        ['sm', 14, __('Small', 'vitahealthmedia')],
        ['md', 16, __('Body', 'vitahealthmedia')],
        ['lg', 18, __('Lead size', 'vitahealthmedia')],
        ['xl', 20, __('Large', 'vitahealthmedia')],
        ['2xl', 24, __('Heading small', 'vitahealthmedia')],
        ['3xl', 32, __('Quote / heading', 'vitahealthmedia')],
        ['4xl', 40, __('Heading large', 'vitahealthmedia')],
        ['5xl', 56, __('Display', 'vitahealthmedia')],
        ['6xl', 64, __('Quote XL', 'vitahealthmedia')],
        ['7xl', 72, __('Display XL', 'vitahealthmedia')],
    ];
    add_theme_support('editor-font-sizes', array_map(static function ($size) {
        return ['slug' => 'vita-' . $size[0], 'size' => $size[1], 'name' => $size[2]];
    }, $sizes));
    add_theme_support('disable-custom-font-sizes');
}
add_action('after_setup_theme', 'vita_health_typography_editor_support');

function vita_health_typography_editor_styles() {
    if (!is_admin()) {
        return;
    }
    wp_enqueue_style('vita-health-tokens', get_template_directory_uri() . '/assets/css/tokens.css', [], filemtime(get_template_directory() . '/assets/css/tokens.css'));
    wp_enqueue_style('vita-health-typography', get_template_directory_uri() . '/assets/css/typography.css', ['vita-health-tokens'], filemtime(get_template_directory() . '/assets/css/typography.css'));
}
add_action('enqueue_block_assets', 'vita_health_typography_editor_styles', 5);
