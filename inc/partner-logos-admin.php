<?php

function vita_health_partner_logos_admin_menu() {
    add_menu_page(
        __('Partnerlogos', 'vitahealthmedia'),
        __('Partnerlogos', 'vitahealthmedia'),
        'edit_pages',
        'vita-partner-logos',
        'vita_health_partner_logos_admin_page',
        'dashicons-format-gallery',
        30
    );
}
add_action('admin_menu', 'vita_health_partner_logos_admin_menu');

function vita_health_partner_logos_admin_assets($hook) {
    if ($hook !== 'toplevel_page_vita-partner-logos') {
        return;
    }

    wp_enqueue_media();
    $path = get_template_directory();
    $uri = get_template_directory_uri();
    wp_enqueue_style(
        'vita-health-partner-logos-admin',
        $uri . '/assets/css/admin/partner-logos.css',
        [],
        filemtime($path . '/assets/css/admin/partner-logos.css')
    );
    wp_enqueue_script(
        'vita-health-partner-logos-admin',
        $uri . '/assets/js/admin/partner-logos.js',
        ['media-views'],
        filemtime($path . '/assets/js/admin/partner-logos.js'),
        true
    );
    $starter_logos = [];
    foreach (array_keys(vita_health_partner_logo_defaults()) as $slug) {
        $starter_logos[] = vita_health_partner_logo_details('theme:' . $slug);
    }
    wp_add_inline_script('vita-health-partner-logos-admin', 'window.vitaPartnerLogosAdmin = ' . wp_json_encode([
        'logos' => vita_health_partner_logo_list(),
        'starterLogos' => $starter_logos,
        'labels' => [
            'add' => __('Logos hinzufügen', 'vitahealthmedia'),
            'moveUp' => __('Nach oben', 'vitahealthmedia'),
            'moveDown' => __('Nach unten', 'vitahealthmedia'),
            'remove' => __('Entfernen', 'vitahealthmedia'),
            'empty' => __('Noch keine Logos ausgewählt.', 'vitahealthmedia'),
        ],
    ]) . ';', 'before');
}
add_action('admin_enqueue_scripts', 'vita_health_partner_logos_admin_assets');

function vita_health_partner_logos_admin_page() {
    if (!current_user_can('edit_pages')) {
        wp_die(esc_html__('Du darfst Partnerlogos nicht bearbeiten.', 'vitahealthmedia'));
    }
    ?>
    <div class="wrap vita-partner-logos-admin">
      <h1><?php esc_html_e('Partnerlogos', 'vitahealthmedia'); ?></h1>
      <p><?php esc_html_e('Diese Liste wird in allen Partnerlogo-Karussells angezeigt. Du kannst Logos aus der Mediathek hinzufügen, entfernen und sortieren. Bitte hinterlege den Namen des Partners als Alternativtext in der Mediathek.', 'vitahealthmedia'); ?></p>
      <?php if (!vita_health_partner_logos_are_shared()) : ?>
        <div class="notice notice-info inline"><p><?php esc_html_e('Beim ersten Speichern ersetzt diese gemeinsame Liste bisherige Logoauswahlen einzelner Karussells.', 'vitahealthmedia'); ?></p></div>
      <?php endif; ?>
      <?php if (isset($_GET['updated']) && $_GET['updated'] === '1') : ?>
        <div class="notice notice-success is-dismissible"><p><?php esc_html_e('Partnerlogos gespeichert.', 'vitahealthmedia'); ?></p></div>
      <?php endif; ?>
      <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
        <input type="hidden" name="action" value="vita_save_partner_logos">
        <?php wp_nonce_field('vita_save_partner_logos'); ?>
        <input type="hidden" name="vita_partner_logo_keys" id="vita-partner-logo-keys" value="">
        <ul id="vita-partner-logo-list" class="vita-partner-logo-list" aria-live="polite"></ul>
        <p>
          <button type="button" id="vita-partner-logo-add" class="button button-secondary"><?php esc_html_e('Logos hinzufügen', 'vitahealthmedia'); ?></button>
          <button type="button" id="vita-partner-logo-add-starters" class="button button-secondary"><?php esc_html_e('Fehlende Starterlogos hinzufügen', 'vitahealthmedia'); ?></button>
        </p>
        <?php submit_button(__('Änderungen speichern', 'vitahealthmedia')); ?>
      </form>
    </div>
    <?php
}

function vita_health_save_partner_logos() {
    if (!current_user_can('edit_pages')) {
        wp_die(esc_html__('Du darfst Partnerlogos nicht bearbeiten.', 'vitahealthmedia'));
    }
    check_admin_referer('vita_save_partner_logos');

    $raw = isset($_POST['vita_partner_logo_keys']) && is_string($_POST['vita_partner_logo_keys'])
        ? json_decode(wp_unslash($_POST['vita_partner_logo_keys']), true)
        : null;
    if (!is_array($raw)) {
        wp_die(esc_html__('Ungültige Logoauswahl.', 'vitahealthmedia'));
    }

    $keys = vita_health_normalize_partner_logo_keys($raw);
    update_option('vita_partner_logo_keys', $keys, false);
    wp_safe_redirect(add_query_arg('updated', '1', admin_url('admin.php?page=vita-partner-logos')));
    exit;
}
add_action('admin_post_vita_save_partner_logos', 'vita_health_save_partner_logos');
