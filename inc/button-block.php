<?php

function vita_health_button_attributes($args, $name) {
    if ($name !== 'core/button') {
        return $args;
    }

    // Server-registered attributes are also supplied to Gutenberg by WordPress.
    $args['attributes'] = array_merge($args['attributes'] ?? [], [
        'vitaVariant'      => ['type' => 'string', 'enum' => ['primary', 'secondary', 'highlight'], 'default' => 'primary'],
        'vitaSize'         => ['type' => 'string', 'enum' => ['default', 'small'], 'default' => 'default'],
        'vitaHasIcon'      => ['type' => 'boolean', 'default' => false],
        'vitaIconPosition' => ['type' => 'string', 'enum' => ['left', 'right'], 'default' => 'right'],
        'vitaIconName'     => ['type' => 'string', 'default' => 'chevron-right'],
    ]);
    return $args;
}
add_filter('register_block_type_args', 'vita_health_button_attributes', 10, 2);

function vita_health_render_button($content, $block) {
    $attributes = $block['attrs'] ?? [];
    $variant = $attributes['vitaVariant'] ?? 'primary';
    $variant = in_array($variant, ['primary', 'secondary', 'highlight'], true) ? $variant : 'primary';
    $size = ($attributes['vitaSize'] ?? 'default') === 'small' ? 'small' : 'default';

    $html = new WP_HTML_Tag_Processor($content);
    if (!$html->next_tag(['class_name' => 'wp-block-button__link'])) {
        return $content;
    }
    $html->add_class('button');
    $html->add_class('button--' . $variant);
    $html->add_class('button--' . $size);
    if (($attributes['vitaHasIcon'] ?? false) !== true) {
        return $html->get_updated_html();
    }

    $html->add_class('button--has-icon');
    $content = $html->get_updated_html();

    $name = $attributes['vitaIconName'] ?? 'chevron-right';
    $name = is_string($name) && preg_match('/^[a-z][a-z0-9-]*$/', $name) ? $name : 'chevron-right';
    $icon = '<span class="button__icon" aria-hidden="true"><i data-lucide="' . esc_attr($name) . '"></i></span>';
    $left = ($attributes['vitaIconPosition'] ?? 'right') === 'left';

    // Only insert into the native button link/tag; preserve its attributes and rich text.
    return preg_replace_callback(
        '~(<(a|button)\b(?:[^>"\']|"[^"]*"|\'[^\']*\')*>)(.*?)(</\2\s*>)~is',
        static function ($match) use ($icon, $left) {
            $tag = new WP_HTML_Tag_Processor($match[1]);
            if (!$tag->next_tag(['class_name' => 'wp-block-button__link'])) {
                return $match[0];
            }
            // Group rich text so flex gap applies once, even with bold/italic text.
            $text = '<span class="button__text">' . $match[3] . '</span>';
            return $match[1] . ($left ? $icon . $text : $text . $icon) . $match[4];
        },
        $content
    );
}
add_filter('render_block_core/button', 'vita_health_render_button', 10, 2);

function vita_health_button_editor_assets() {
    wp_enqueue_script(
        'vita-health-lucide',
        get_template_directory_uri() . '/assets/js/vendor/lucide.min.js',
        [],
        filemtime(get_template_directory() . '/assets/js/vendor/lucide.min.js'),
        true
    );
    wp_enqueue_script(
        'vita-health-button-controls',
        get_template_directory_uri() . '/assets/js/editor/button-controls.js',
        ['wp-hooks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n', 'vita-health-lucide'],
        filemtime(get_template_directory() . '/assets/js/editor/button-controls.js'),
        true
    );
}
add_action('enqueue_block_editor_assets', 'vita_health_button_editor_assets');

function vita_health_button_editor_styles() {
    if (!is_admin()) {
        return;
    }
    wp_enqueue_style('vita-health-tokens', get_template_directory_uri() . '/assets/css/tokens.css', [], filemtime(get_template_directory() . '/assets/css/tokens.css'));
    wp_enqueue_style('vita-health-buttons', get_template_directory_uri() . '/assets/css/buttons.css', ['vita-health-tokens'], filemtime(get_template_directory() . '/assets/css/buttons.css'));
}
// Also load shared styles inside the editor canvas (including iframe editors).
add_action('enqueue_block_assets', 'vita_health_button_editor_styles');
