<?php

function vita_health_project_slider_assets() {
    wp_enqueue_style(
        'vita-health-project-slider',
        get_template_directory_uri() . '/assets/css/components/project-slider.css',
        ['vita-health-base', 'vita-health-typography', 'vita-health-buttons', 'vita-health-colors'],
        filemtime(get_template_directory() . '/assets/css/components/project-slider.css')
    );
    wp_enqueue_script(
        'vita-health-project-slider',
        get_template_directory_uri() . '/assets/js/project-slider.js',
        ['vita-health-icons'],
        filemtime(get_template_directory() . '/assets/js/project-slider.js'),
        true
    );
}
add_action('wp_enqueue_scripts', 'vita_health_project_slider_assets', 20);

// Native Shortcode blocks allow placement on any page without an editor build.
function vita_health_project_slider_shortcode($attributes) {
    $attributes = shortcode_atts(['count' => 3, 'order' => 'DESC'], $attributes, 'vita_projects');
    ob_start();
    get_template_part('parts/project-slider', null, $attributes);
    return ob_get_clean();
}
add_shortcode('vita_projects', 'vita_health_project_slider_shortcode');

function vita_health_project_slider_query($settings) {
    $query = [
        'post_type' => 'project',
        'post_status' => 'publish',
        'posts_per_page' => min(24, max(1, absint($settings['count'] ?? 3))),
        'orderby' => ['date' => strtoupper($settings['order'] ?? 'DESC') === 'ASC' ? 'ASC' : 'DESC', 'ID' => 'DESC'],
        'no_found_rows' => true,
        'ignore_sticky_posts' => true,
    ];
    $mode = $settings['mode'] ?? 'latest';
    if ($mode === 'latest') {
        return $query;
    }
    $taxonomy = get_taxonomy($settings['taxonomy'] ?? '');
    if (!in_array($mode, ['terms', 'related'], true) || !$taxonomy || !$taxonomy->public || !is_object_in_taxonomy('project', $taxonomy->name)) {
        return null;
    }
    if ($mode === 'related') {
        $current = absint($settings['currentPostId'] ?? (get_queried_object_id() ?: get_the_ID()));
        $current_type = get_post_type($current);
        if (!in_array($current_type, ['project', 'post'], true) || !is_object_in_taxonomy($current_type, $taxonomy->name)) {
            return null;
        }
        $terms = wp_get_object_terms($current, $taxonomy->name, ['fields' => 'ids']);
        $query['post__not_in'] = [$current];
    } else {
        $terms = array_filter(array_map('absint', (array) ($settings['terms'] ?? [])));
    }
    if (is_wp_error($terms) || !$terms) {
        return null;
    }
    $query['tax_query'] = [[
        'taxonomy' => $taxonomy->name,
        'field' => 'term_id',
        'terms' => $terms,
        'operator' => 'IN',
        'include_children' => false,
    ]];
    return $query;
}

function vita_health_register_project_slider_block() {
    wp_register_script(
        'vita-health-project-slider-editor',
        get_template_directory_uri() . '/blocks/project-slider/editor.js',
        ['wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-server-side-render'],
        filemtime(get_template_directory() . '/blocks/project-slider/editor.js'),
        true
    );
    register_block_type(get_template_directory() . '/blocks/project-slider', [
        'render_callback' => static function ($attributes, $content, $block) {
            if (!empty($block->context['postId'])) {
                $attributes['currentPostId'] = $block->context['postId'];
            }
            ob_start();
            get_template_part('parts/project-slider', null, $attributes);
            return ob_get_clean();
        },
    ]);
}
add_action('init', 'vita_health_register_project_slider_block');

function vita_health_project_slider_editor_data() {
    $options = [];
    foreach (get_object_taxonomies('project', 'objects') as $taxonomy) {
        if (!$taxonomy->public) {
            continue;
        }
        $terms = get_terms(['taxonomy' => $taxonomy->name, 'hide_empty' => false]);
        $options[] = [
            'name' => $taxonomy->name,
            'label' => $taxonomy->label,
            'terms' => is_wp_error($terms) ? [] : array_map(static function ($term) {
                return ['id' => $term->term_id, 'name' => $term->name];
            }, $terms),
        ];
    }
    wp_add_inline_script('vita-health-project-slider-editor', 'window.vitaProjectTaxonomies = ' . wp_json_encode($options) . ';', 'before');
}
add_action('enqueue_block_editor_assets', 'vita_health_project_slider_editor_data');

function vita_health_project_slider_editor_styles() {
    if (is_admin()) {
        wp_enqueue_style('vita-health-project-slider', get_template_directory_uri() . '/assets/css/components/project-slider.css',
            ['vita-health-base', 'vita-health-typography', 'vita-health-buttons'],
            filemtime(get_template_directory() . '/assets/css/components/project-slider.css'));
    }
}
add_action('enqueue_block_assets', 'vita_health_project_slider_editor_styles');
