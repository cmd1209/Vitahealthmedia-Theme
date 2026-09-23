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