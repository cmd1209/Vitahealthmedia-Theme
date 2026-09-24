<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="navigation">
  <div class="navigation__wrapper">
    <a class="navigation__logo" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php esc_attr_e('Vita Health Media — Home', 'vitahealthmedia'); ?>">
      <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo-vita-l.svg'); ?>" width="134" height="80" alt="">
    </a>
  <?php if (has_nav_menu('primary')) : ?>
    <button class="navigation__toggle" type="button" aria-expanded="false" aria-controls="primary-navigation" aria-label="<?php esc_attr_e('Menü', 'vitahealthmedia'); ?>" hidden>
      <span></span><span></span><span></span>
    </button>
    <nav id="primary-navigation" class="navigation__panel" aria-label="<?php esc_attr_e('Primary Navigation', 'vitahealthmedia'); ?>">
      <?php
      wp_nav_menu([
          'theme_location' => 'primary',
          'container'     => false,
          'fallback_cb'   => false,
          'menu_class'    => 'navigation__links',
          'depth'         => 1,
      ]);
      ?>
    </nav>
  <?php endif; ?>
  </div>
</header>
