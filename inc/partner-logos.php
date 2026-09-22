<?php

function vita_health_register_partner_logos() {
    $uri = get_template_directory_uri();
    $path = get_template_directory();
    wp_register_script('vita-health-partner-logos-editor', $uri . '/blocks/partner-logos/editor.js',
        ['wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-data', 'wp-core-data', 'wp-i18n'],
        filemtime($path . '/blocks/partner-logos/editor.js'), true);
    wp_add_inline_script('vita-health-partner-logos-editor', 'window.vitaPartnerLogos = ' . wp_json_encode([
        'assetUrl' => $uri . '/assets/images/partner/',
    ]) . ';', 'before');
    wp_register_style('vita-health-partner-logos', $uri . '/assets/css/components/partner-logos.css',
        ['vita-health-tokens', 'vita-health-base'], filemtime($path . '/assets/css/components/partner-logos.css'));
    wp_register_script('vita-health-partner-logos', $uri . '/assets/js/partner-logos.js', [],
        filemtime($path . '/assets/js/partner-logos.js'), true);
    register_block_type($path . '/blocks/partner-logos');
}
add_action('init', 'vita_health_register_partner_logos');
