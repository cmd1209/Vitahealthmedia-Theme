<?php

function vita_health_register_content_slider_blocks() {
    $path = get_template_directory();
    $uri = get_template_directory_uri();

    wp_register_script(
        'vita-health-content-slider-editor',
        $uri . '/blocks/content-slider/editor.js',
        ['wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n'],
        filemtime($path . '/blocks/content-slider/editor.js'),
        true
    );
    wp_register_style(
        'vita-health-content-slider',
        $uri . '/assets/css/components/content-slider.css',
        ['vita-health-project-slider'],
        filemtime($path . '/assets/css/components/content-slider.css')
    );
    register_block_type($path . '/blocks/content-slider');
    register_block_type($path . '/blocks/content-slide');
}
add_action('init', 'vita_health_register_content_slider_blocks');
