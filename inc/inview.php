<?php

function vita_health_inview_block_attribute($args) {
    $args['attributes'] = array_merge($args['attributes'] ?? [], [
        'vitaRevealOnScroll' => ['type' => 'boolean'],
    ]);

    return $args;
}
add_filter('register_block_type_args', 'vita_health_inview_block_attribute');

function vita_health_render_inview_block($content, $block) {
    $reveal = $block['attrs']['vitaRevealOnScroll'] ?? null;
    if (!is_bool($reveal) || $content === '') {
        return $content;
    }

    $html = new WP_HTML_Tag_Processor($content);
    if (!$html->next_tag()) {
        return $content;
    }

    $html->add_class($reveal ? 'vita-reveal' : 'vita-reveal-off');
    return $html->get_updated_html();
}
add_filter('render_block', 'vita_health_render_inview_block', 20, 2);

function vita_health_inview_editor_assets() {
    wp_enqueue_script(
        'vita-health-inview-controls',
        get_template_directory_uri() . '/assets/js/editor/inview-controls.js',
        ['wp-hooks', 'wp-element', 'wp-blocks', 'wp-block-editor', 'wp-components', 'wp-i18n'],
        filemtime(get_template_directory() . '/assets/js/editor/inview-controls.js'),
        true
    );
}
add_action('enqueue_block_editor_assets', 'vita_health_inview_editor_assets');
