<footer class="footer">
  <div class="content-wrapper footer__wrapper">
    <div class="footer__content">
      <div class="footer__address">
        <img class="footer__logo" src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo-vita-m_ivory.svg'); ?>" width="106" height="50" alt="Vita Health Media">
        <address class="footer__company">
          <span class="body-bold">Vita Health Media GmbH</span><br>
          <span class="body-small">Büro Hamburg: Jessenstraße 4–6, 22767 Hamburg<br></span>
          <span class="body-small">Büro München: Thierschstraße 25, 80538 München</span>
        </address>
      </div>
      <div class="footer__menus">
        <?php
        $footer_menus = [
            'footer-services' => __('Leistungen und Seitenbereiche', 'vitahealthmedia'),
            'footer-links'    => __('Weitere Links und Rechtliches', 'vitahealthmedia'),
        ];
        foreach ($footer_menus as $location => $label) :
            // Use the existing named menu until a menu is assigned to this location.
            $menu = wp_nav_menu([
                'theme_location' => $location,
                'menu'           => has_nav_menu($location) ? '' : $location,
                'container'      => false,
                'menu_class'     => 'footer__links',
                'fallback_cb'    => false,
                'depth'          => 1,
                'echo'           => false,
            ]);
            if ($menu) :
                ?>
                <nav aria-label="<?php echo esc_attr($label); ?>">
                  <?php echo $menu; // Markup generated and escaped by WordPress. ?>
                </nav>
            <?php endif; ?>
        <?php endforeach; ?>
      </div>
    </div>
    <p class="footer__copyright">&copy; <?php echo esc_html(wp_date('Y')); ?> Vita Health Media GmbH</p>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
