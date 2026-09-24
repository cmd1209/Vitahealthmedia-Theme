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
          get_template_part('parts/project-card', null, [
              'title_id' => $title_id,
              'heading_tag' => 'h3',
              'category' => $category,
              'button_size' => 'default',
          ]);
      ?>
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
  <?php $archive_url = get_post_type_archive_link('project'); ?>
  <?php if ($archive_url) : ?>
    <div class="project-slider__archive-link">
      <a class="button button--primary" href="<?php echo esc_url($archive_url); ?>">
        <?php esc_html_e('Alle Projekte ansehen', 'vitahealthmedia'); ?>
        <span class="button__icon" aria-hidden="true"><i data-lucide="chevron-right"></i></span>
      </a>
    </div>
  <?php endif; ?>
</section>
