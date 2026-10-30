<?php
$visible = min(3, max(1, (int) ($attributes['visibleSlides'] ?? 3)));
$label = trim($attributes['label'] ?? '') ?: __('Content slider', 'vitahealthmedia');
$viewport_id = wp_unique_id('content-slider-viewport-');
?>
<section <?php echo get_block_wrapper_attributes([
    'class' => 'project-slider content-slider',
    'style' => '--content-slider-visible:' . $visible,
    'aria-label' => $label,
]); ?>>
  <div class="project-slider__viewport" id="<?php echo esc_attr($viewport_id); ?>" tabindex="0" role="group" aria-label="<?php esc_attr_e('Browse slides', 'vitahealthmedia'); ?>">
    <div class="project-slider__track">
      <?php echo $content; // Saved inner blocks, rendered by WordPress. ?>
    </div>
  </div>
  <div class="project-slider__controls" hidden>
    <button type="button" class="project-slider__previous" aria-label="<?php esc_attr_e('Previous slide', 'vitahealthmedia'); ?>" aria-controls="<?php echo esc_attr($viewport_id); ?>">
      <span class="button__icon" aria-hidden="true"><i data-lucide="chevron-left"></i></span>
    </button>
    <button type="button" class="project-slider__next" aria-label="<?php esc_attr_e('Next slide', 'vitahealthmedia'); ?>" aria-controls="<?php echo esc_attr($viewport_id); ?>">
      <span class="button__icon" aria-hidden="true"><i data-lucide="chevron-right"></i></span>
    </button>
  </div>
</section>
