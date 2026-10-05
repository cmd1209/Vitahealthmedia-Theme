<?php
$term = $args['term'] ?? null;
$is_service = $term instanceof WP_Term && $term->taxonomy === 'project_category';
$service_page_id = $is_service ? vita_health_get_service_page_id($term->term_id) : 0;
$image_id = $is_service ? ($service_page_id ? get_post_thumbnail_id($service_page_id) : 0) : get_post_thumbnail_id();
if (!$image_id || !wp_attachment_is_image($image_id)) {
    $fallback = get_page_by_path('project-blanco_kv', OBJECT, 'attachment');
    $image_id = $fallback && wp_attachment_is_image($fallback->ID) ? $fallback->ID : 0;
}
$heading_id = wp_unique_id('project-hero-title-');
$title = $is_service ? $term->name : get_the_title();
$lead = $is_service ? $term->description : (has_excerpt() ? get_the_excerpt() : '');
$gradient_class = $is_service ? 'vita-gradient--lavender' : vita_health_gradient_class(get_the_ID());
?>
<header class="project-hero alignfull <?php echo esc_attr($gradient_class); ?>" aria-labelledby="<?php echo esc_attr($heading_id); ?>">
  <?php if ($image_id) : ?>
    <?php echo wp_get_attachment_image($image_id, 'full', false, [
        'class' => 'project-hero__image',
        'sizes' => '100vw',
        'loading' => 'eager',
        'fetchpriority' => 'high',
        'decoding' => 'async',
        'alt' => '',
        'aria-hidden' => 'true',
    ]); ?>
  <?php endif; ?>
  <div class="project-hero__overlay" aria-hidden="true"></div>
  <div class="content-wrapper project-hero__inner">
    <div class="project-hero__content">
      <h1 class="project-hero__title" id="<?php echo esc_attr($heading_id); ?>"><?php echo esc_html($title); ?></h1>
      <?php if ($lead) : ?>
        <p class="lead project-hero__lead"><?php echo esc_html(wp_strip_all_tags($lead)); ?></p>
      <?php endif; ?>
    </div>
  </div>
</header>
