<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header>
  <?php if (has_nav_menu('primary')) : ?>
    <nav aria-label="<?php esc_attr_e('Primary Navigation', 'vitahealthmedia'); ?>">
      <?php
      wp_nav_menu([
          'theme_location' => 'primary',
          'container'     => false,
          'fallback_cb'   => false,
      ]);
      ?>
    </nav>
  <?php endif; ?>
</header>
