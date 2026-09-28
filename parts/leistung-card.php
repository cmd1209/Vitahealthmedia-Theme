<?php
$term = $args['term'] ?? null;
$archive_url = $args['archive_url'] ?? '';
if (!$term instanceof WP_Term || !$archive_url) {
    return;
}

$icon = get_term_meta($term->term_id, 'vita_project_icon', true);
$icon_options = vita_health_project_icon_options();
$icon_url = isset($icon_options[$icon])
    ? get_template_directory_uri() . '/assets/images/leistung/' . $icon . '.svg'
    : '';
$icon_markup = '';
if ($icon_url) {
    $icon_markup = file_get_contents(get_template_directory() . '/assets/images/leistung/' . $icon . '.svg');
    $icon_markup = $icon_markup ? str_replace('fill="#D5C7FF"', 'fill="currentColor"', $icon_markup) : '';
    $icon_markup = preg_replace('/\s+id="[^"]*"/', '', $icon_markup);
}
$url = add_query_arg('project_category', $term->slug, $archive_url);
?>
<a class="leistung-card" href="<?php echo esc_url($url); ?>" aria-label="<?php echo esc_attr(sprintf(__('Projekte: %s', 'vitahealthmedia'), $term->name)); ?>">
  <?php if ($icon_markup) : ?>
    <span class="leistung-card__icon" aria-hidden="true"><?php echo $icon_markup; // Trusted SVG from the theme's fixed icon list. ?></span>
  <?php endif; ?>
  <div class="leistung-card__text">
    <h3 class="leistung-card__title lead"><?php echo esc_html($term->name); ?></h3>
    <?php if ($term->description) : ?>
      <p class="leistung-card__description label"><?php echo esc_html($term->description); ?></p>
    <?php endif; ?>
  </div>
</a>
