<?php
/** Projects are queried at render time; Featured Images remain the image source. */
$settings = wp_parse_args($args ?? [], [
    'count' => 3,
    'order' => 'DESC',
]);
$query_args = vita_health_project_slider_query($settings);
if (!$query_args) {
    return;
}
$projects = new WP_Query($query_args);
if (!$projects->have_posts()) {
    return;
}
$section_id = wp_unique_id('project-slider-');
$taxonomies = array_filter(get_object_taxonomies('project', 'objects'), static function ($taxonomy) {
    return $taxonomy->public;
});
?>
<section class="project-slider alignfull" aria-label="<?php esc_attr_e('Projekte', 'vitahealthmedia'); ?>">
  <div class="project-slider__viewport" id="<?php echo esc_attr($section_id); ?>-viewport" tabindex="0" role="group" aria-label="<?php esc_attr_e('Projekte durchblättern', 'vitahealthmedia'); ?>">
    <div class="project-slider__track">
      <?php while ($projects->have_posts()) : $projects->the_post();
          $title_id = $section_id . '-project-' . get_the_ID();
          $category = '';
          foreach ($taxonomies as $taxonomy) {
              $terms = get_the_terms(get_the_ID(), $taxonomy->name);
              if ($terms && !is_wp_error($terms)) {
                  $category = $terms[0]->name;
                  break;
              }
          }
          $excerpt = has_excerpt() ? get_the_excerpt() : '';
          $image_alt = get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true);
          $button_variant = $projects->current_post % 3 === 2 ? 'highlight' : 'primary';
      ?>
        <article class="project-card">
          <a class="project-card__link" href="<?php the_permalink(); ?>" aria-labelledby="<?php echo esc_attr($title_id); ?>">
            <div class="project-card__media">
              <?php echo get_the_post_thumbnail(get_the_ID(), 'large', [
                  'class' => 'project-card__image',
                  'alt' => $image_alt ?: wp_strip_all_tags(get_the_title()),
                  'loading' => 'lazy',
                  'decoding' => 'async',
                  'sizes' => '(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33.333vw',
              ]); ?>
            </div>
            <div class="project-card__overlay" aria-hidden="true"></div>
            <div class="project-card__content">
              <?php if ($category) : ?>
                <p class="project-card__category eyebrow"><?php echo esc_html($category); ?></p>
              <?php endif; ?>
              <h3 class="project-card__title" id="<?php echo esc_attr($title_id); ?>"><?php the_title(); ?></h3>
              <?php if ($excerpt) : ?>
                <p class="project-card__excerpt"><?php echo esc_html(wp_strip_all_tags($excerpt)); ?></p>
              <?php endif; ?>
              <span class="button button--<?php echo esc_attr($button_variant); ?> button--default button--small-mobile">
                <?php esc_html_e('Projekt ansehen', 'vitahealthmedia'); ?>
                <span class="button__icon" aria-hidden="true"><i data-lucide="chevron-right"></i></span>
              </span>
            </div>
          </a>
        </article>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
  </div>
  <div class="project-slider__controls" hidden>
    <button type="button" class="project-slider__previous" aria-label="<?php esc_attr_e('Vorheriges Projekt', 'vitahealthmedia'); ?>" aria-controls="<?php echo esc_attr($section_id); ?>-viewport">
      <span class="button__icon" aria-hidden="true"><i data-lucide="chevron-left"></i></span>
    </button>
    <button type="button" class="project-slider__next" aria-label="<?php esc_attr_e('Nächstes Projekt', 'vitahealthmedia'); ?>" aria-controls="<?php echo esc_attr($section_id); ?>-viewport">
      <span class="button__icon" aria-hidden="true"><i data-lucide="chevron-right"></i></span>
    </button>
  </div>
</section>
