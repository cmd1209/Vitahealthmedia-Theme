<?php

function vita_health_partner_logo_defaults() {
    return [
        'hello' => 'Hello Health',
        'kabi' => 'Fresenius Kabi',
        'umschau' => 'Apotheken Umschau',
        'viactiv' => 'VIACTIV',
        'siemens' => 'Siemens Healthineers',
        'korian' => 'Korian',
        'gesund.de' => 'gesund.de',
    ];
}

function vita_health_partner_logo_keys() {
    $saved = get_option('vita_partner_logo_keys', null);
    if (is_array($saved)) {
        return $saved;
    }
    return array_map(static function ($slug) {
        return 'theme:' . $slug;
    }, array_keys(vita_health_partner_logo_defaults()));
}

function vita_health_partner_logos_are_shared() {
    return is_array(get_option('vita_partner_logo_keys', null));
}

function vita_health_partner_logo_details($key) {
    if (!is_string($key)) {
        return null;
    }

    if (strpos($key, 'theme:') === 0) {
        $slug = substr($key, 6);
        $defaults = vita_health_partner_logo_defaults();
        if (!isset($defaults[$slug])) {
            return null;
        }
        return [
            'key' => $key,
            'url' => get_template_directory_uri() . '/assets/images/partner/' . $slug . '.png',
            'alt' => $defaults[$slug],
            'label' => $defaults[$slug],
        ];
    }

    if (!preg_match('/^media:([1-9][0-9]*)$/', $key, $matches)) {
        return null;
    }
    $id = absint($matches[1]);
    if (!$id || !wp_attachment_is_image($id)) {
        return null;
    }
    $url = wp_get_attachment_image_url($id, 'medium') ?: wp_get_attachment_url($id);
    if (!$url) {
        return null;
    }
    $title = get_the_title($id);
    $alt = get_post_meta($id, '_wp_attachment_image_alt', true);
    return [
        'key' => 'media:' . $id,
        'url' => $url,
        'alt' => $alt ?: $title,
        'label' => $title ?: __('Unbenanntes Logo', 'vitahealthmedia'),
    ];
}

function vita_health_partner_logo_list() {
    $logos = [];
    foreach (vita_health_partner_logo_keys() as $key) {
        $logo = vita_health_partner_logo_details($key);
        if ($logo) {
            $logos[] = $logo;
        }
    }
    return $logos;
}

function vita_health_normalize_partner_logo_keys($raw) {
    $keys = [];
    foreach (array_slice($raw, 0, 100) as $key) {
        if (!is_string($key)) {
            continue;
        }
        $logo = vita_health_partner_logo_details($key);
        if ($logo && !in_array($logo['key'], $keys, true)) {
            $keys[] = $logo['key'];
        }
    }
    return $keys;
}

function vita_health_register_partner_logos() {
    $uri = get_template_directory_uri();
    $path = get_template_directory();
    wp_register_script('vita-health-partner-logos-editor', $uri . '/blocks/partner-logos/editor.js',
        ['wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-data', 'wp-core-data', 'wp-i18n'],
        filemtime($path . '/blocks/partner-logos/editor.js'), true);
    wp_add_inline_script('vita-health-partner-logos-editor', 'window.vitaPartnerLogos = ' . wp_json_encode([
        'logos' => vita_health_partner_logo_list(),
        'hasSharedList' => vita_health_partner_logos_are_shared(),
        'manageUrl' => current_user_can('edit_pages') ? admin_url('admin.php?page=vita-partner-logos') : '',
    ]) . ';', 'before');
    wp_register_style('vita-health-partner-logos', $uri . '/assets/css/components/partner-logos.css',
        ['vita-health-tokens', 'vita-health-base'], filemtime($path . '/assets/css/components/partner-logos.css'));
    wp_register_script('vita-health-partner-logos', $uri . '/assets/js/partner-logos.js', [],
        filemtime($path . '/assets/js/partner-logos.js'), true);
    register_block_type($path . '/blocks/partner-logos');
}
add_action('init', 'vita_health_register_partner_logos');
