<?php

/** Match --content-default (1180px); preserve portrait and landscape ratios. */
function vita_health_content_image_size() {
    add_image_size('large', 1180, 0, false);
}
add_action('after_setup_theme', 'vita_health_content_image_size');

// Core's Large editor constraint otherwise reads the site's old 1024px option.
function vita_health_large_image_editor_dimensions($dimensions, $size) {
    return $size === 'large' ? [1180, 0] : $dimensions;
}
add_filter('editor_max_image_size', 'vita_health_large_image_editor_dimensions', 10, 2);

function vita_health_content_image_styles() {
    wp_enqueue_style('vita-health-content-images', get_template_directory_uri() . '/assets/css/content-images.css',
        ['vita-health-base'], filemtime(get_template_directory() . '/assets/css/content-images.css'));
}
add_action('enqueue_block_assets', 'vita_health_content_image_styles');
