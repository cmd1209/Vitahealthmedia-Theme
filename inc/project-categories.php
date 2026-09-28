<?php

function vita_health_register_project_categories() {
    register_taxonomy('project_category', ['project'], [
        'labels' => [
            'name' => __('Leistungen', 'vitahealthmedia'),
            'singular_name' => __('Leistung', 'vitahealthmedia'),
            'search_items' => __('Leistungen suchen', 'vitahealthmedia'),
            'all_items' => __('Alle Leistungen', 'vitahealthmedia'),
            'edit_item' => __('Leistung bearbeiten', 'vitahealthmedia'),
            'add_new_item' => __('Neue Leistung hinzufügen', 'vitahealthmedia'),
        ],
        'public' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'hierarchical' => true,
        'query_var' => 'leistung',
        'rewrite' => ['slug' => 'leistung'],
    ]);

    register_term_meta('project_category', 'vita_project_icon', [
        'type' => 'string',
        'single' => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'sanitize_key',
        'auth_callback' => static function () {
            return current_user_can('manage_categories');
        },
    ]);
}
add_action('init', 'vita_health_register_project_categories');

function vita_health_project_icon_options() {
    return [
        'video' => __('Video', 'vitahealthmedia'),
        'seo' => __('SEO', 'vitahealthmedia'),
        'social-media' => __('Social Media', 'vitahealthmedia'),
        'kundenmagazin' => __('Kundenmagazin', 'vitahealthmedia'),
        'event' => __('Event', 'vitahealthmedia'),
        'podcast' => __('Podcast', 'vitahealthmedia'),
        'kampagne' => __('Kampagne', 'vitahealthmedia'),
        'strategie' => __('Strategie', 'vitahealthmedia'),
    ];
}

function vita_health_project_icon_field($term = null) {
    $selected = $term instanceof WP_Term ? get_term_meta($term->term_id, 'vita_project_icon', true) : '';
    $field = '<label for="vita-project-icon">' . esc_html__('Icon', 'vitahealthmedia') . '</label> ';
    $field .= '<select id="vita-project-icon" name="vita_project_icon">';
    $field .= '<option value="">' . esc_html__('Kein Icon', 'vitahealthmedia') . '</option>';
    foreach (vita_health_project_icon_options() as $slug => $label) {
        $field .= '<option value="' . esc_attr($slug) . '"' . selected($selected, $slug, false) . '>' . esc_html($label) . '</option>';
    }
    $field .= '</select>';

    if ($term instanceof WP_Term) {
        echo '<tr class="form-field"><th scope="row">' . esc_html__('Icon', 'vitahealthmedia') . '</th><td>' . $field . '</td></tr>';
    } else {
        echo '<div class="form-field">' . $field . '</div>';
    }
}
add_action('project_category_add_form_fields', 'vita_health_project_icon_field');
add_action('project_category_edit_form_fields', 'vita_health_project_icon_field');

function vita_health_save_project_icon($term_id) {
    if (!current_user_can('edit_term', $term_id) || !isset($_POST['vita_project_icon'])) {
        return;
    }
    $icon = sanitize_key(wp_unslash($_POST['vita_project_icon']));
    if ($icon && array_key_exists($icon, vita_health_project_icon_options())) {
        update_term_meta($term_id, 'vita_project_icon', $icon);
    } else {
        delete_term_meta($term_id, 'vita_project_icon');
    }
}
add_action('created_project_category', 'vita_health_save_project_icon');
add_action('edited_project_category', 'vita_health_save_project_icon');

/** Copy the original shared categories before disconnecting them from Projects. */
function vita_health_migrate_project_categories() {
    if (get_option('vita_project_categories_migrated')) {
        return;
    }

    $old_terms = get_terms(['taxonomy' => 'category', 'hide_empty' => false]);
    if (is_wp_error($old_terms)) {
        return;
    }
    $term_map = [];
    foreach ($old_terms as $term) {
        $existing = get_term_by('slug', $term->slug, 'project_category');
        if ($existing) {
            $term_map[$term->term_id] = $existing->term_id;
            continue;
        }
        $starter_descriptions = [
            'video' => 'Video-Content für den Gesundheitsmarkt – von YouTube-Serien und Webinaren bis zu TikToks und TV-Spots.',
            'seo' => 'Wissenschaftlich fundierte, verständliche und SEO-optimierte Inhalte für digitale Gesundheitskommunikation.',
            'social-media' => 'Wir finden die passende Plattform, Sprache und Strategie, um Ihre Zielgruppen wirkungsvoll zu erreichen.',
            'kundenmagazin' => 'Wir entwickeln hochwertige Printformate und Layouts, die Gesundheitsinhalte verständlich und attraktiv vermitteln.',
            'event' => 'Von Informationsveranstaltungen bis Afterwork: Wir konzipieren und realisieren Gesundheitsevents mit relevanten Inhalten.',
            'podcast' => 'Wir entwickeln und produzieren Gesundheits-Podcasts – als Interview, Erklärformat oder Reportage.',
            'kampagne' => 'Digital oder klassisch: Wir entwickeln Kampagnen, die Botschaften sichtbar machen und Menschen erreichen.',
            'strategie' => 'Wir entwickeln Kommunikationsstrategien, die Maßnahmen bündeln, schärfen und wirksamer machen.',
        ];
        $created = wp_insert_term($term->name, 'project_category', [
            'slug' => $term->slug,
            'description' => $term->description ?: ($starter_descriptions[$term->slug] ?? ''),
        ]);
        if (is_wp_error($created)) {
            return;
        }
        $term_map[$term->term_id] = $created['term_id'];
        if (array_key_exists($term->slug, vita_health_project_icon_options())) {
            update_term_meta($created['term_id'], 'vita_project_icon', $term->slug);
        }
    }

    $project_ids = get_posts([
        'post_type' => 'project',
        'post_status' => 'any',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'no_found_rows' => true,
    ]);
    foreach ($project_ids as $project_id) {
        $old_ids = wp_get_object_terms($project_id, 'category', ['fields' => 'ids']);
        if (is_wp_error($old_ids)) {
            return;
        }
        $new_ids = array_values(array_filter(array_map(static function ($id) use ($term_map) {
            return $term_map[$id] ?? null;
        }, $old_ids)));
        if ($new_ids) {
            $assigned = wp_set_object_terms($project_id, $new_ids, 'project_category');
            if (is_wp_error($assigned)) {
                return;
            }
        }
        if ($old_ids && is_wp_error(wp_set_object_terms($project_id, [], 'category'))) {
            return;
        }
    }

    update_option('vita_project_categories_migrated', 1, false);
}
add_action('admin_init', 'vita_health_migrate_project_categories');

function vita_health_project_categories() {
    $terms = get_terms(['taxonomy' => 'project_category', 'hide_empty' => false]);
    if (is_wp_error($terms)) {
        return [];
    }
    $order = array_flip(array_keys(vita_health_project_icon_options()));
    usort($terms, static function ($left, $right) use ($order) {
        $left_order = $order[$left->slug] ?? PHP_INT_MAX;
        $right_order = $order[$right->slug] ?? PHP_INT_MAX;
        return $left_order <=> $right_order ?: strcasecmp($left->name, $right->name);
    });
    return $terms;
}

function vita_health_render_leistung_grid() {
    $archive_url = get_post_type_archive_link('project');
    $terms = vita_health_project_categories();
    if (!$archive_url || !$terms) {
        return '';
    }

    ob_start();
    echo '<div class="wp-block-group leistung-grid">';
    foreach ($terms as $term) {
        get_template_part('parts/leistung-card', null, ['term' => $term, 'archive_url' => $archive_url]);
    }
    echo '</div>';
    return ob_get_clean();
}

function vita_health_register_leistung_grid_block() {
    wp_register_script(
        'vita-health-leistung-grid-editor',
        get_template_directory_uri() . '/blocks/leistung-grid/editor.js',
        ['wp-blocks', 'wp-element', 'wp-block-editor', 'wp-server-side-render', 'wp-hooks'],
        filemtime(get_template_directory() . '/blocks/leistung-grid/editor.js'),
        true
    );
    register_block_type('vita-health/leistung-grid', [
        'api_version' => 3,
        'title' => __('Leistungen', 'vitahealthmedia'),
        'category' => 'design',
        'editor_script' => 'vita-health-leistung-grid-editor',
        'render_callback' => 'vita_health_render_leistung_grid',
    ]);
}
add_action('init', 'vita_health_register_leistung_grid_block');

/** Existing pages saved the old grid as core/group; render them from current terms too. */
function vita_health_render_legacy_leistung_grid($content, $block) {
    if (is_admin() || ($block['blockName'] ?? '') !== 'core/group') {
        return $content;
    }
    $classes = $block['attrs']['className'] ?? '';
    if (preg_match('/(?:^|\s)leistung-grid(?:\s|$)/', $classes)) {
        return vita_health_render_leistung_grid();
    }
    return $content;
}
add_filter('render_block', 'vita_health_render_legacy_leistung_grid', 10, 2);
