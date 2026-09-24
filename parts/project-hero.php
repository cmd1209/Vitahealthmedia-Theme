<?php
$image_id = get_post_thumbnail_id();
if (!$image_id || !wp_attachment_is_image($image_id)) {
    $fallback = get_page_by_path('project-blanco_kv', OBJECT, 'attachment');
    $image_id = $fallback && wp_attachment_is_image($fallback->ID) ? $fallback->ID : 0;
}
$heading_id = wp_unique_id('project-hero-title-');
$excerpt = has_excerpt() ? get_the_excerpt() : '';
?>
<header class="project-hero alignfull" aria-labelledby="<?php echo esc_attr($heading_id); ?>">
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
      <h1 class="project-hero__title" id="<?php echo esc_attr($heading_id); ?>"><?php the_title(); ?></h1>
      <?php if ($excerpt) : ?>
        <p class="lead project-hero__lead"><?php echo esc_html(wp_strip_all_tags($excerpt)); ?></p>
      <?php endif; ?>
    </div>
  </div>
</header>
