<?php

function vita_health_gradient_choices() {
    return [
        'lavender' => __('Lavender', 'vitahealthmedia'),
        'coral' => __('Coral', 'vitahealthmedia'),
        'terracotta' => __('Terracotta', 'vitahealthmedia'),
        'evergreen' => __('Evergreen', 'vitahealthmedia'),
        'sage' => __('Sage', 'vitahealthmedia'),
        'mint' => __('Mint', 'vitahealthmedia'),
    ];
}

function vita_health_sanitize_gradient($value) {
    $value = is_string($value) ? sanitize_key($value) : '';
    return array_key_exists($value, vita_health_gradient_choices()) ? $value : '';
}

function vita_health_get_gradient($post_id) {
    $saved = vita_health_sanitize_gradient(get_post_meta($post_id, '_vita_gradient', true));
    if ($saved) {
        return $saved;
    }

    // An unset choice stays consistent as post order and archive pages change.
    $choices = array_keys(vita_health_gradient_choices());
    return $choices[absint($post_id) % count($choices)];
}

function vita_health_gradient_class($post_id) {
    return 'vita-gradient--' . vita_health_get_gradient($post_id);
}

function vita_health_register_gradient_meta() {
    foreach (['project', 'post'] as $post_type) {
        register_post_meta($post_type, '_vita_gradient', [
            'type' => 'string',
            'single' => true,
            'sanitize_callback' => 'vita_health_sanitize_gradient',
            'auth_callback' => static function ($allowed, $meta_key, $post_id) {
                return current_user_can('edit_post', $post_id);
            },
        ]);
    }
}
add_action('init', 'vita_health_register_gradient_meta');

function vita_health_add_gradient_meta_boxes() {
    foreach (['project', 'post'] as $post_type) {
        add_meta_box(
            'vita-health-gradient',
            __('Featured image gradient', 'vitahealthmedia'),
            'vita_health_render_gradient_meta_box',
            $post_type,
            'side',
            'default',
            ['__block_editor_compatible_meta_box' => true]
        );
    }
}
add_action('add_meta_boxes', 'vita_health_add_gradient_meta_boxes');

function vita_health_render_gradient_meta_box($post) {
    $saved = vita_health_sanitize_gradient(get_post_meta($post->ID, '_vita_gradient', true));
    $automatic = vita_health_get_gradient($post->ID);
    $choices = vita_health_gradient_choices();
    wp_nonce_field('vita_health_save_gradient', 'vita_health_gradient_nonce');
    ?>
    <fieldset class="vita-gradient-picker">
      <legend class="screen-reader-text"><?php esc_html_e('Featured image gradient', 'vitahealthmedia'); ?></legend>
      <label>
        <input type="radio" name="vita_health_gradient" value="" <?php checked($saved, ''); ?>>
        <?php printf(esc_html__('Automatic (%s)', 'vitahealthmedia'), esc_html($choices[$automatic])); ?>
      </label>
      <?php foreach ($choices as $slug => $label) : ?>
        <label>
          <input type="radio" name="vita_health_gradient" value="<?php echo esc_attr($slug); ?>" <?php checked($saved, $slug); ?>>
          <span class="vita-gradient-swatch vita-gradient--<?php echo esc_attr($slug); ?>" aria-hidden="true"></span>
          <?php echo esc_html($label); ?>
        </label>
      <?php endforeach; ?>
    </fieldset>
    <?php
}

function vita_health_save_gradient($post_id, $post) {
    if (!in_array($post->post_type, ['project', 'post'], true)
        || !isset($_POST['vita_health_gradient_nonce'], $_POST['vita_health_gradient'])
        || !is_string($_POST['vita_health_gradient_nonce'])
        || !is_string($_POST['vita_health_gradient'])
        || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['vita_health_gradient_nonce'])), 'vita_health_save_gradient')
        || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        || wp_is_post_revision($post_id)
        || !current_user_can('edit_post', $post_id)) {
        return;
    }

    $gradient = vita_health_sanitize_gradient(wp_unslash($_POST['vita_health_gradient']));
    if ($gradient) {
        update_post_meta($post_id, '_vita_gradient', $gradient);
    } else {
        delete_post_meta($post_id, '_vita_gradient');
    }
}
add_action('save_post', 'vita_health_save_gradient', 10, 2);

function vita_health_gradient_post_class($classes, $class, $post_id) {
    if (in_array(get_post_type($post_id), ['project', 'post'], true)) {
        $classes[] = vita_health_gradient_class($post_id);
    }
    return $classes;
}
add_filter('post_class', 'vita_health_gradient_post_class', 10, 3);

function vita_health_gradient_body_class($classes) {
    if (is_singular(['project', 'post'])) {
        $classes[] = vita_health_gradient_class(get_queried_object_id());
    }
    return $classes;
}
add_filter('body_class', 'vita_health_gradient_body_class');

function vita_health_gradient_admin_assets($hook) {
    if (!in_array($hook, ['post.php', 'post-new.php'], true)) {
        return;
    }
    $screen = get_current_screen();
    if (!$screen || !in_array($screen->post_type, ['project', 'post'], true)) {
        return;
    }
    wp_enqueue_style('vita-health-tokens', get_template_directory_uri() . '/assets/css/tokens.css', [], filemtime(get_template_directory() . '/assets/css/tokens.css'));
    wp_enqueue_style('vita-health-colors', get_template_directory_uri() . '/assets/css/color.css', ['vita-health-tokens'], filemtime(get_template_directory() . '/assets/css/color.css'));
}
add_action('admin_enqueue_scripts', 'vita_health_gradient_admin_assets');

function vita_health_gradient_editor_styles() {
    if (!is_admin()) {
        return;
    }

    wp_enqueue_style(
        'vita-health-tokens',
        get_template_directory_uri() . '/assets/css/tokens.css',
        [],
        filemtime(get_template_directory() . '/assets/css/tokens.css')
    );
    wp_enqueue_style(
        'vita-health-colors',
        get_template_directory_uri() . '/assets/css/color.css',
        ['vita-health-tokens'],
        filemtime(get_template_directory() . '/assets/css/color.css')
    );
}
add_action('enqueue_block_assets', 'vita_health_gradient_editor_styles');
