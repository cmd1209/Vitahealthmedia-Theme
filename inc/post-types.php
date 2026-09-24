<?php

function vita_register_project_post_type() {
    register_post_type('project', [
        'labels' => [
            'name'          => 'Projekte',
            'singular_name' => 'Projekt',
            'add_new_item'  => 'Neues Projekt hinzufügen',
            'edit_item'     => 'Projekt bearbeiten',
        ],
        'public'       => true,
        'show_in_rest' => true,
        'taxonomies'   => ['category'],
        'menu_icon'    => 'dashicons-portfolio',
        'supports'     => [
            'title',
            'editor',
            'thumbnail',
            'excerpt',
        ],
        'has_archive'  => true,
        'rewrite'      => [
            'slug' => 'projekte',
        ],
    ]);
}

add_action('init', 'vita_register_project_post_type');

function vita_project_archive_query($query) {
    if (is_admin() || !$query->is_main_query() || !$query->is_post_type_archive('project')) {
        return;
    }

    $query->set('posts_per_page', 6);
    $category = isset($_GET['project_category']) && is_string($_GET['project_category'])
        ? sanitize_title(wp_unslash($_GET['project_category']))
        : '';
    if ($category && get_term_by('slug', $category, 'category')) {
        $query->set('tax_query', [[
            'taxonomy' => 'category',
            'field' => 'slug',
            'terms' => $category,
        ]]);
    }
}
add_action('pre_get_posts', 'vita_project_archive_query');
