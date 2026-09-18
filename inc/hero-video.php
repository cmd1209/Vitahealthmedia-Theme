<?php

function vita_health_sanitize_hero_video($value) {
    $id = absint($value);
    return $id && get_post_type($id) === 'attachment' && wp_attachment_is('video', $id) ? $id : 0;
}

function vita_health_customize_hero_video($wp_customize) {
    $wp_customize->add_section('vita_health_hero_video', [
        'title' => __('Hero Video', 'vitahealthmedia'),
    ]);
    $wp_customize->add_setting('vita_health_hero_video_id', [
        'type'              => 'theme_mod',
        'default'           => 0,
        'sanitize_callback' => 'vita_health_sanitize_hero_video',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'vita_health_hero_video_id', [
        'label'       => __('Background video', 'vitahealthmedia'),
        'description' => __('Choose a web-ready video from the Media Library. Without a video, the hero uses its gradient background.', 'vitahealthmedia'),
        'section'     => 'vita_health_hero_video',
        'mime_type'   => 'video',
    ]));
}
add_action('customize_register', 'vita_health_customize_hero_video');

function vita_health_register_hero_video_block() {
    wp_register_script(
        'vita-health-hero-video-editor',
        get_template_directory_uri() . '/blocks/hero-video/editor.js',
        ['wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-data', 'wp-core-data', 'wp-i18n'],
        filemtime(get_template_directory() . '/blocks/hero-video/editor.js'),
        true
    );
    wp_register_style(
        'vita-health-hero-video',
        get_template_directory_uri() . '/assets/css/components/hero-video.css',
        ['vita-health-tokens', 'vita-health-typography', 'vita-health-buttons'],
        filemtime(get_template_directory() . '/assets/css/components/hero-video.css')
    );
    register_block_type(get_template_directory() . '/blocks/hero-video');
}
add_action('init', 'vita_health_register_hero_video_block');

function vita_health_hero_editor_styles() {
    if (is_admin()) {
        // Reuse the existing type scale in the block canvas; no editor-only type system.
        wp_enqueue_style('vita-health-typography', get_template_directory_uri() . '/assets/css/typography.css', ['vita-health-tokens'], filemtime(get_template_directory() . '/assets/css/typography.css'));
    }
}
add_action('enqueue_block_assets', 'vita_health_hero_editor_styles');
