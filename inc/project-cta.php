<?php

function vita_health_project_cta_choices() {
    return [
        'primary' => __('Primary', 'vitahealthmedia'),
        'secondary' => __('Secondary', 'vitahealthmedia'),
        'highlight' => __('Highlight', 'vitahealthmedia'),
    ];
}

function vita_health_sanitize_project_cta_variant($value) {
    $value = is_string($value) ? sanitize_key($value) : '';
    return array_key_exists($value, vita_health_project_cta_choices()) ? $value : '';
}

function vita_health_get_project_cta_variant($post_id) {
    $saved = vita_health_sanitize_project_cta_variant(get_post_meta($post_id, '_vita_project_cta_variant', true));
    return $saved ?: 'primary';
}

function vita_health_register_project_cta_meta() {
    register_post_meta('project', '_vita_project_cta_variant', [
        'type' => 'string',
        'single' => true,
        'sanitize_callback' => 'vita_health_sanitize_project_cta_variant',
        'auth_callback' => static function ($allowed, $meta_key, $post_id) {
            return current_user_can('edit_post', $post_id);
        },
    ]);
}
add_action('init', 'vita_health_register_project_cta_meta');

function vita_health_add_project_cta_meta_box() {
    add_meta_box(
        'vita-health-project-cta',
        __('Project card CTA', 'vitahealthmedia'),
        'vita_health_render_project_cta_meta_box',
        'project',
        'side',
        'default',
        ['__block_editor_compatible_meta_box' => true]
    );
}
add_action('add_meta_boxes', 'vita_health_add_project_cta_meta_box');

function vita_health_render_project_cta_meta_box($post) {
    $selected = vita_health_get_project_cta_variant($post->ID);
    wp_nonce_field('vita_health_save_project_cta', 'vita_health_project_cta_nonce');
    ?>
    <fieldset class="vita-project-cta-picker">
      <legend class="screen-reader-text"><?php esc_html_e('Project card CTA color', 'vitahealthmedia'); ?></legend>
      <?php foreach (vita_health_project_cta_choices() as $slug => $label) : ?>
        <label>
          <input type="radio" name="vita_health_project_cta_variant" value="<?php echo esc_attr($slug); ?>" <?php checked($selected, $slug); ?>>
          <span class="vita-project-cta-swatch vita-project-cta-swatch--<?php echo esc_attr($slug); ?>" aria-hidden="true"></span>
          <?php echo esc_html($label); ?>
        </label>
      <?php endforeach; ?>
    </fieldset>
    <p class="description"><?php esc_html_e('Used on this project’s cards in the slider and archive.', 'vitahealthmedia'); ?></p>
    <?php
}

function vita_health_save_project_cta($post_id, $post) {
    if ($post->post_type !== 'project'
        || !isset($_POST['vita_health_project_cta_nonce'], $_POST['vita_health_project_cta_variant'])
        || !is_string($_POST['vita_health_project_cta_nonce'])
        || !is_string($_POST['vita_health_project_cta_variant'])
        || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['vita_health_project_cta_nonce'])), 'vita_health_save_project_cta')
        || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        || wp_is_post_revision($post_id)
        || !current_user_can('edit_post', $post_id)) {
        return;
    }

    $variant = vita_health_sanitize_project_cta_variant(wp_unslash($_POST['vita_health_project_cta_variant']));
    if ($variant) {
        update_post_meta($post_id, '_vita_project_cta_variant', $variant);
    } else {
        delete_post_meta($post_id, '_vita_project_cta_variant');
    }
}
add_action('save_post', 'vita_health_save_project_cta', 10, 2);
