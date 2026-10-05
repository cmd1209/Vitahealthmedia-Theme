<?php

/** Editable content for each Leistung; the taxonomy term remains its public URL. */
function vita_health_register_service_page_post_type() {
    register_post_type('service_page', [
        'labels' => [
            'name' => __('Leistungsseiten', 'vitahealthmedia'),
            'singular_name' => __('Leistungsseite', 'vitahealthmedia'),
            'edit_item' => __('Leistungsseite bearbeiten', 'vitahealthmedia'),
        ],
        'public' => false,
        'publicly_queryable' => true,
        'query_var' => true,
        'rewrite' => false,
        'show_ui' => true,
        'show_in_rest' => true,
        'show_in_menu' => 'edit.php?post_type=project',
        'exclude_from_search' => true,
        'show_in_nav_menus' => false,
        'map_meta_cap' => true,
        'capabilities' => ['create_posts' => 'do_not_allow'],
        'supports' => ['title', 'editor', 'thumbnail'],
    ]);
}
add_action('init', 'vita_health_register_service_page_post_type');

function vita_health_flush_service_rewrite_rules() {
    if (get_option('vita_service_rewrite_version') === '1') {
        return;
    }
    flush_rewrite_rules(false);
    update_option('vita_service_rewrite_version', '1', false);
}
add_action('admin_init', 'vita_health_flush_service_rewrite_rules', 30);

function vita_health_get_service_page_id($term_id) {
    $page_id = (int) get_term_meta($term_id, 'vita_service_page_id', true);
    if ($page_id && get_post_type($page_id) === 'service_page') {
        return $page_id;
    }

    $term = get_term($term_id, 'project_category');
    if (!$term instanceof WP_Term) {
        return 0;
    }
    $page = get_page_by_path($term->slug, OBJECT, 'service_page');
    return $page instanceof WP_Post && $page->post_status !== 'trash' ? $page->ID : 0;
}

function vita_health_ensure_service_page($term_id) {
    $term = get_term($term_id, 'project_category');
    if (!$term instanceof WP_Term) {
        return;
    }

    $existing_id = vita_health_get_service_page_id($term_id);
    if ($existing_id) {
        update_term_meta($term_id, 'vita_service_page_id', $existing_id);
        update_post_meta($existing_id, '_vita_service_term_id', $term_id);
        return;
    }

    $page_id = wp_insert_post([
        'post_type' => 'service_page',
        'post_status' => 'publish',
        'post_title' => $term->name,
    ], true);
    if (!is_wp_error($page_id)) {
        update_term_meta($term_id, 'vita_service_page_id', $page_id);
        update_post_meta($page_id, '_vita_service_term_id', $term_id);
    }
}
add_action('created_project_category', 'vita_health_ensure_service_page');

/** Set up existing categories when the editor next opens WordPress. */
function vita_health_ensure_existing_service_pages() {
    $terms = get_terms(['taxonomy' => 'project_category', 'hide_empty' => false]);
    if (is_wp_error($terms)) {
        return;
    }
    foreach ($terms as $term) {
        vita_health_ensure_service_page($term->term_id);
    }
}
add_action('admin_init', 'vita_health_ensure_existing_service_pages', 20);

function vita_health_service_page_edit_link($term) {
    $page_id = vita_health_get_service_page_id($term->term_id);
    if (!$page_id) {
        return;
    }
    echo '<tr class="form-field"><th scope="row">' . esc_html__('Seiteninhalt', 'vitahealthmedia') . '</th><td><a href="' . esc_url(get_edit_post_link($page_id)) . '">' . esc_html__('Leistungsseite bearbeiten', 'vitahealthmedia') . '</a><p class="description">' . esc_html__('Titel und Unterzeile werden hier aus Name und Beschreibung der Leistung übernommen.', 'vitahealthmedia') . '</p></td></tr>';
}
add_action('project_category_edit_form_fields', 'vita_health_service_page_edit_link');

function vita_health_get_service_term_for_page($page_id) {
    $term_id = (int) get_post_meta($page_id, '_vita_service_term_id', true);
    if ($term_id && vita_health_get_service_page_id($term_id) === $page_id) {
        return get_term($term_id, 'project_category');
    }

    $terms = get_terms([
        'taxonomy' => 'project_category',
        'hide_empty' => false,
        'meta_key' => 'vita_service_page_id',
        'meta_value' => $page_id,
        'number' => 1,
    ]);
    return !is_wp_error($terms) && $terms ? $terms[0] : null;
}

function vita_health_service_page_permalink($url, $post) {
    if ($post->post_type !== 'service_page') {
        return $url;
    }
    $term = vita_health_get_service_term_for_page($post->ID);
    if (!$term instanceof WP_Term) {
        return $url;
    }
    $term_url = get_term_link($term);
    return is_wp_error($term_url) ? $url : $term_url;
}
add_filter('post_type_link', 'vita_health_service_page_permalink', 10, 2);

function vita_health_service_page_preview_link($url, $post) {
    if ($post->post_type !== 'service_page') {
        return $url;
    }
    $query = [];
    wp_parse_str((string) wp_parse_url($url, PHP_URL_QUERY), $query);
    $query['post_type'] = 'service_page';
    $query['p'] = $post->ID;
    return add_query_arg($query, home_url('/'));
}
add_filter('preview_post_link', 'vita_health_service_page_preview_link', 10, 2);

function vita_health_redirect_direct_service_page() {
    if (!is_singular('service_page') || is_preview()) {
        return;
    }
    $term = vita_health_get_service_term_for_page(get_queried_object_id());
    if (!$term instanceof WP_Term) {
        return;
    }
    $url = get_term_link($term);
    if (!is_wp_error($url)) {
        wp_safe_redirect($url, 301);
        exit;
    }
}
add_action('template_redirect', 'vita_health_redirect_direct_service_page');
