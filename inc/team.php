<?php

/** Team members are managed independently and displayed on the Team page. */
function vita_health_register_team_member() {
    register_post_type('team_member', [
        'labels' => [
            'name' => __('Team', 'vitahealthmedia'),
            'singular_name' => __('Teammitglied', 'vitahealthmedia'),
            'add_new_item' => __('Teammitglied hinzufügen', 'vitahealthmedia'),
            'edit_item' => __('Teammitglied bearbeiten', 'vitahealthmedia'),
            'not_found' => __('Keine Teammitglieder gefunden.', 'vitahealthmedia'),
            'featured_image' => __('Porträt', 'vitahealthmedia'),
            'set_featured_image' => __('Porträt auswählen', 'vitahealthmedia'),
            'remove_featured_image' => __('Porträt entfernen', 'vitahealthmedia'),
        ],
        'public' => false,
        'show_ui' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-groups',
        'supports' => ['title', 'thumbnail', 'page-attributes'],
        'rewrite' => false,
    ]);

    foreach (['job_title', 'quote', 'email', 'linkedin'] as $field) {
        register_post_meta('team_member', '_vita_team_' . $field, [
            'type' => 'string',
            'single' => true,
            'sanitize_callback' => static function ($value) use ($field) {
                return vita_health_sanitize_team_field($field, $value);
            },
            'auth_callback' => static function ($allowed, $meta_key, $post_id) {
                return current_user_can('edit_post', $post_id);
            },
        ]);
    }
}
add_action('init', 'vita_health_register_team_member');

function vita_health_sanitize_team_field($field, $value) {
    if (!is_string($value)) {
        return '';
    }
    switch ($field) {
        case 'quote':
            return wp_kses($value, ['mark' => [], 'br' => []]);
        case 'email':
            $email = sanitize_email($value);
            return is_email($email) ? $email : '';
        case 'linkedin':
            $url = esc_url_raw($value, ['https']);
            $host = strtolower((string) wp_parse_url($url, PHP_URL_HOST));
            return $host === 'linkedin.com' || substr($host, -13) === '.linkedin.com' ? $url : '';
        default:
            return sanitize_textarea_field($value);
    }
}

function vita_health_team_meta_box() {
    add_meta_box('vita-team-details', __('Teammitglied – Details', 'vitahealthmedia'), 'vita_health_render_team_meta_box', 'team_member', 'normal', 'high');
}
add_action('add_meta_boxes', 'vita_health_team_meta_box');

function vita_health_render_team_meta_box($post) {
    wp_nonce_field('vita_health_save_team', 'vita_health_team_nonce');
    ?>
    <p><label for="vita-team-job-title"><strong><?php esc_html_e('Jobtitel', 'vitahealthmedia'); ?></strong></label></p>
    <textarea class="widefat" rows="2" id="vita-team-job-title" name="vita_team[job_title]"><?php echo esc_textarea(get_post_meta($post->ID, '_vita_team_job_title', true)); ?></textarea>
    <p><label for="vita_team_quote"><strong><?php esc_html_e('Zitat', 'vitahealthmedia'); ?></strong></label></p>
    <?php
    wp_editor(get_post_meta($post->ID, '_vita_team_quote', true), 'vita_team_quote', [
        'textarea_name' => 'vita_team[quote]',
        'textarea_rows' => 5,
        'media_buttons' => false,
        'quicktags' => true,
        'wpautop' => false,
        'tinymce' => [
            'toolbar1' => 'styleselect,undo,redo',
            'toolbar2' => '',
            'style_formats' => wp_json_encode([
                ['title' => __('Koralle hervorheben', 'vitahealthmedia'), 'inline' => 'mark'],
            ]),
            'content_style' => 'mark { background: transparent; color: #ff8c73; }',
            'forced_root_block' => false,
        ],
    ]);
    ?>
    <p class="description"><?php esc_html_e('Anführungszeichen bitte mit eingeben. Wörter auswählen und unter Formate „Koralle hervorheben“ wählen. Die Reihenfolge wird über Attribute → Reihenfolge festgelegt (kleinste Zahl zuerst).', 'vitahealthmedia'); ?></p>
    <p><label for="vita-team-email"><strong><?php esc_html_e('E-Mail (optional)', 'vitahealthmedia'); ?></strong></label></p>
    <input class="widefat" type="email" id="vita-team-email" name="vita_team[email]" value="<?php echo esc_attr(get_post_meta($post->ID, '_vita_team_email', true)); ?>">
    <p><label for="vita-team-linkedin"><strong><?php esc_html_e('LinkedIn (optional)', 'vitahealthmedia'); ?></strong></label></p>
    <input class="widefat" type="url" id="vita-team-linkedin" name="vita_team[linkedin]" placeholder="https://www.linkedin.com/in/…" value="<?php echo esc_attr(get_post_meta($post->ID, '_vita_team_linkedin', true)); ?>">
    <?php
}

function vita_health_save_team($post_id) {
    if (!isset($_POST['vita_health_team_nonce'], $_POST['vita_team'])
        || !is_string($_POST['vita_health_team_nonce'])
        || !is_array($_POST['vita_team'])
        || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['vita_health_team_nonce'])), 'vita_health_save_team')
        || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        || wp_is_post_revision($post_id)
        || !current_user_can('edit_post', $post_id)) {
        return;
    }
    foreach (['job_title', 'quote', 'email', 'linkedin'] as $field) {
        if (!array_key_exists($field, $_POST['vita_team'])) {
            continue;
        }
        $value = vita_health_sanitize_team_field($field, wp_unslash($_POST['vita_team'][$field]));
        if ($value !== '') {
            update_post_meta($post_id, '_vita_team_' . $field, $value);
        } else {
            delete_post_meta($post_id, '_vita_team_' . $field);
        }
    }
}
add_action('save_post_team_member', 'vita_health_save_team');

function vita_health_team_assets() {
    wp_enqueue_style('vita-health-team', get_template_directory_uri() . '/assets/css/components/team.css',
        ['vita-health-base', 'vita-health-typography'], filemtime(get_template_directory() . '/assets/css/components/team.css'));
}
add_action('enqueue_block_assets', 'vita_health_team_assets');

function vita_health_register_team_blocks() {
    wp_register_script('vita-health-team-editor', get_template_directory_uri() . '/blocks/team/editor.js',
        ['wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-server-side-render', 'wp-data', 'wp-core-data', 'wp-i18n'],
        filemtime(get_template_directory() . '/blocks/team/editor.js'), true);

    register_block_type(get_template_directory() . '/blocks/team-list', [
        'render_callback' => static function () {
            ob_start();
            get_template_part('parts/team-list');
            return ob_get_clean();
        },
    ]);
    register_block_type(get_template_directory() . '/blocks/team-card', [
        'render_callback' => static function ($attributes) {
            $member_id = absint($attributes['memberId'] ?? 0);
            $member = $member_id ? get_post($member_id) : null;
            if (!$member || $member->post_type !== 'team_member' || $member->post_status !== 'publish') {
                return '';
            }
            ob_start();
            get_template_part('parts/team-card', null, ['member_id' => $member->ID]);
            return ob_get_clean();
        },
    ]);
}
add_action('init', 'vita_health_register_team_blocks');

/** Preserve legacy auto-list pages; block-built pages own their composition. */
function vita_health_has_team_block($content, $seen = []) {
    if (has_block('vita-health/team-list', $content) || has_block('vita-health/team-card', $content)) {
        return true;
    }
    foreach (parse_blocks($content) as $block) {
        $ref = absint($block['attrs']['ref'] ?? 0);
        if ($block['blockName'] === 'core/block' && $ref && !in_array($ref, $seen, true)) {
            $reusable = get_post($ref);
            if ($reusable && vita_health_has_team_block($reusable->post_content, array_merge($seen, [$ref]))) {
                return true;
            }
        }
        if (!empty($block['innerBlocks']) && vita_health_has_team_block(serialize_blocks($block['innerBlocks']), $seen)) {
            return true;
        }
    }
    return false;
}
