<?php

/** Empty legacy Page Heroes use the native Cover featured-image behavior. */
function vita_health_page_hero_featured_image($block) {
    $attributes = $block['attrs'] ?? [];
    if (($block['blockName'] ?? '') === 'core/cover'
        && preg_match('/(?:^|\s)page-hero(?:\s|$)/', $attributes['className'] ?? '')
        && !array_key_exists('useFeaturedImage', $attributes)
        && empty($attributes['url'])
        && empty($attributes['id'])
        && ($attributes['backgroundType'] ?? 'image') === 'image') {
        $block['attrs']['useFeaturedImage'] = true;
    }
    return $block;
}
add_filter('render_block_data', 'vita_health_page_hero_featured_image');

function vita_health_page_hero_editor_assets() {
    wp_enqueue_script('vita-health-page-hero-editor', get_template_directory_uri() . '/assets/js/editor/page-hero.js',
        ['wp-hooks', 'wp-blocks'], filemtime(get_template_directory() . '/assets/js/editor/page-hero.js'), true);
}
add_action('enqueue_block_editor_assets', 'vita_health_page_hero_editor_assets');
