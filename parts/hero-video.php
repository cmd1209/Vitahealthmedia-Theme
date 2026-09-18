<?php
$video_id = vita_health_sanitize_hero_video($args['video_id'] ?? get_theme_mod('vita_health_hero_video_id', 0));
$video_url = $video_id ? wp_get_attachment_url($video_id) : false;
$heading_id = wp_unique_id('hero-video-title-');
$gradient = $args['gradient'] ?? 'purple-green';
$gradient = in_array($gradient, ['purple-green', 'green-purple', 'dark-green', 'none'], true) ? $gradient : 'purple-green';
$wrapper = ['class' => 'hero-video hero-video--gradient-' . $gradient, 'aria-labelledby' => $heading_id];
?>

<section <?php
if (!empty($args['is_block'])) {
    echo get_block_wrapper_attributes($wrapper);
} else {
    echo 'class="' . esc_attr($wrapper['class']) . '" aria-labelledby="' . esc_attr($heading_id) . '"';
}
?>>
  <?php if ($video_url) : ?>
    <video class="hero-video__media" data-src="<?php echo esc_url($video_url); ?>" autoplay muted loop playsinline preload="none" aria-hidden="true" tabindex="-1"></video>
  <?php endif; ?>
  <div class="hero-video__overlay" aria-hidden="true"></div>

  <div class="content-wrapper hero-video__inner">
    <div class="hero-video__content">
      <h1 id="<?php echo esc_attr($heading_id); ?>" class="quote-xl hero-video__quote">
        <?php if (isset($args['headline'])) : ?>
          <?php echo wp_kses($args['headline'], ['strong' => [], 'em' => [], 'br' => []]); ?>
        <?php else : ?>
        <?php esc_html_e('Wir wollen das Leben der Menschen verbessern, indem wir ihnen helfen, ihre', 'vitahealthmedia'); ?>
        <span class="hero-video__highlight"><?php esc_html_e('Gesundheit', 'vitahealthmedia'); ?></span>
        <?php esc_html_e('besser zu verstehen.', 'vitahealthmedia'); ?>
        <?php endif; ?>
      </h1>
      <div class="hero-video__buttons">
        <?php if (array_key_exists('buttons', $args)) : ?>
          <?php echo $args['buttons']; // Native InnerBlocks already rendered by WordPress. ?>
        <?php else : ?>
        <a class="button button--secondary button--default button--small-mobile" href="<?php echo esc_url(home_url('/#mission')); ?>">
          <?php esc_html_e('Mehr über uns', 'vitahealthmedia'); ?>
        </a>
        <a class="button button--primary button--default button--small-mobile" href="<?php echo esc_url(home_url('/#referenzen')); ?>">
          <?php esc_html_e('Alle Projekte ansehen', 'vitahealthmedia'); ?>
          <span class="button__icon" aria-hidden="true"><i data-lucide="chevron-right"></i></span>
        </a>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <?php if ($video_url) : ?>
    <button class="button button--primary button--small hero-video__toggle" type="button" hidden data-pause-label="<?php esc_attr_e('Video pausieren', 'vitahealthmedia'); ?>" data-play-label="<?php esc_attr_e('Video abspielen', 'vitahealthmedia'); ?>">
      <?php esc_html_e('Video pausieren', 'vitahealthmedia'); ?>
    </button>
  <?php endif; ?>
</section>
