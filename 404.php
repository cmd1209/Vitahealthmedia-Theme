<?php
/**
 * Missing-page template. WordPress supplies the 404 status before rendering.
 *
 * @package vitahealthmedia
 */

get_header();
?>

<main id="main-content" class="error-page" aria-labelledby="error-page-title">
  <div class="content-wrapper">
    <div class="error-page__content">
      <p class="eyebrow error-page__eyebrow"><?php esc_html_e('404 / SEITE NICHT GEFUNDEN', 'vitahealthmedia'); ?></p>
      <h1 id="error-page-title" class="error-page__title"><?php esc_html_e('Hier ist wohl etwas verloren gegangen.', 'vitahealthmedia'); ?></h1>
      <p class="lead error-page__copy"><?php esc_html_e('Die gesuchte Seite gibt es leider nicht (mehr). Aber keine Sorge – auf unserer Startseite geht’s weiter.', 'vitahealthmedia'); ?></p>
      <a class="button button--primary" href="<?php echo esc_url(home_url('/')); ?>">
        <?php esc_html_e('Zur Startseite', 'vitahealthmedia'); ?>
        <span class="button__icon" aria-hidden="true"><i data-lucide="arrow-right"></i></span>
      </a>
    </div>
  </div>
</main>

<?php get_footer(); ?>
