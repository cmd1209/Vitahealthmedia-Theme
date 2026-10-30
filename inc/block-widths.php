<?php

function vita_health_block_width_attribute($args, $name) {
    // Child blocks such as Columns, Slides, and navigation items belong to
    // their parent's layout. Raw HTML and references have no reliable wrapper.
    if (!empty($args['parent']) || !empty($args['ancestor']) || in_array($name, [
        'core/block', 'core/pattern', 'core/template-part', 'core/html',
        'core/shortcode', 'core/freeform', 'core/missing', 'core/more',
        'core/nextpage', 'core/post-template', 'core/navigation',
    ], true)) {
        return $args;
    }

    $args['attributes']['vitaWidth'] = [
        'type' => 'string',
        'enum' => ['fill', 'narrow', 'medium', 'content', 'wide', 'full'],
    ];
    $args['attributes']['vitaPlacement'] = [
        'type' => 'string',
        'enum' => ['left', 'center', 'right'],
    ];
    return $args;
}
add_filter('register_block_type_args', 'vita_health_block_width_attribute', 20, 2);

function vita_health_render_block_width($content, $block) {
    $width = $block['attrs']['vitaWidth'] ?? '';
    if (!in_array($width, ['fill', 'narrow', 'medium', 'content', 'wide', 'full'], true)) {
        return $content;
    }
    $type = WP_Block_Type_Registry::get_instance()->get_registered($block['blockName'] ?? '');
    if (!$type || !isset($type->attributes['vitaWidth'])) {
        return $content;
    }

    $html = new WP_HTML_Tag_Processor($content);
    if (!$html->next_tag() || in_array($html->get_tag(), ['SCRIPT', 'STYLE', 'LINK', 'META'], true)) {
        return $content;
    }
    $html->set_attribute('data-vita-width', $width);
    $placement = $block['attrs']['vitaPlacement'] ?? '';
    if (in_array($placement, ['left', 'center', 'right'], true)) {
        $html->set_attribute('data-vita-placement', $placement);
    }
    return $html->get_updated_html();
}
add_filter('render_block', 'vita_health_render_block_width', 30, 2);

function vita_health_block_width_editor_assets() {
    wp_enqueue_script(
        'vita-health-block-width-controls',
        get_template_directory_uri() . '/assets/js/editor/block-width-controls.js',
        ['wp-hooks', 'wp-element', 'wp-blocks', 'wp-block-editor', 'wp-components', 'wp-i18n'],
        filemtime(get_template_directory() . '/assets/js/editor/block-width-controls.js'),
        true
    );
}
add_action('enqueue_block_editor_assets', 'vita_health_block_width_editor_assets');

function vita_health_block_width_styles() {
    wp_enqueue_style(
        'vita-health-block-widths',
        get_template_directory_uri() . '/assets/css/block-widths.css',
        is_admin() ? ['vita-health-base', 'vita-health-editor-layout'] : ['vita-health-base'],
        filemtime(get_template_directory() . '/assets/css/block-widths.css')
    );
}
// Load after component styles; the explicit choice takes precedence over presets.
add_action('enqueue_block_assets', 'vita_health_block_width_styles', 100);
