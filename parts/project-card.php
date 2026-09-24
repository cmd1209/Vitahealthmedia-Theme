<?php
$title_id = $args['title_id'] ?? wp_unique_id('project-title-');
$variant = $args['variant'] ?? 'secondary';
$heading_tag = ($args['heading_tag'] ?? 'h2') === 'h3' ? 'h3' : 'h2';
$category = $args['category'] ?? '';
$button_size = ($args['button_size'] ?? 'small') === 'default' ? 'button--default button--small-mobile' : 'button--small';
$excerpt = has_excerpt() ? get_the_excerpt() : '';
$image_alt = get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true);
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
      <<?php echo $heading_tag; ?> class="project-card__title" id="<?php echo esc_attr($title_id); ?>"><?php the_title(); ?></<?php echo $heading_tag; ?>>
      <?php if ($excerpt) : ?>
        <p class="project-card__excerpt"><?php echo esc_html(wp_strip_all_tags($excerpt)); ?></p>
      <?php endif; ?>
      <span class="button button--<?php echo esc_attr($variant); ?> <?php echo esc_attr($button_size); ?>">
        <?php esc_html_e('Projekt ansehen', 'vitahealthmedia'); ?>
        <span class="button__icon" aria-hidden="true"><i data-lucide="chevron-right"></i></span>
      </span>
    </div>
  </a>
</article>
