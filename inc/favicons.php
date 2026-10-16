<?php

/** Use the supplied theme package unless WordPress already manages a Site Icon. */
function vita_health_favicons() {
    if (get_option('site_icon') && has_site_icon()) {
        return;
    }

    // WordPress.com can supply a generic fallback without a configured Site Icon.
    // Let this package replace that fallback rather than printing both sets.
    remove_action('wp_head', 'wp_site_icon', 99);
    ?>
    <link rel="icon" type="image/png" href="<?php echo esc_url(get_theme_file_uri('assets/favicons/favicon-96x96.png')); ?>" sizes="96x96">
    <link rel="icon" type="image/svg+xml" href="<?php echo esc_url(get_theme_file_uri('assets/favicons/favicon.svg')); ?>">
    <link rel="shortcut icon" href="<?php echo esc_url(get_theme_file_uri('assets/favicons/favicon.ico')); ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo esc_url(get_theme_file_uri('assets/favicons/apple-touch-icon.png')); ?>">
    <link rel="manifest" href="<?php echo esc_url(get_theme_file_uri('assets/favicons/site.webmanifest')); ?>">
    <?php
}
add_action('wp_head', 'vita_health_favicons');
