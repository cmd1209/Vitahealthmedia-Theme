<?php
$logos = [];
if ($attributes['useDefaults'] ?? true) {
    foreach (['hello' => 'Hello Health', 'kabi' => 'Fresenius Kabi', 'umschau' => 'Apotheken Umschau', 'viactiv' => 'VIACTIV', 'siemens' => 'Siemens Healthineers', 'korian' => 'Korian', 'gesund.de' => 'gesund.de'] as $file => $name) {
        $logos[] = '<img src="' . esc_url(get_template_directory_uri() . '/assets/images/partner/' . $file . '.png') . '" alt="' . esc_attr($name) . '" width="150" height="75" decoding="async">';
    }
} else {
    foreach (($attributes['logoIds'] ?? []) as $id) {
        $id = absint($id);
        if (!$id || !wp_attachment_is_image($id)) {
            continue;
        }
        $alt = get_post_meta($id, '_wp_attachment_image_alt', true);
        $logos[] = wp_get_attachment_image($id, 'medium', false, [
            'alt' => $alt ?: get_the_title($id), 'loading' => 'eager', 'decoding' => 'async',
        ]);
    }
}
if (!$logos) {
    return;
}
$heading_id = wp_unique_id('partner-logos-heading-');
?>
<section <?php echo get_block_wrapper_attributes(['class' => 'partner-logos alignfull', 'aria-labelledby' => $heading_id]); ?>>
  <h2 id="<?php echo esc_attr($heading_id); ?>"><?php echo esc_html($attributes['heading'] ?: __('Kunden', 'vitahealthmedia')); ?></h2>
  <button class="partner-logos__toggle" type="button" hidden aria-pressed="false" data-pause="<?php esc_attr_e('Pause logo animation', 'vitahealthmedia'); ?>" data-play="<?php esc_attr_e('Play logo animation', 'vitahealthmedia'); ?>" aria-label="<?php esc_attr_e('Pause logo animation', 'vitahealthmedia'); ?>">Ⅱ</button>
  <div class="partner-logos__viewport" tabindex="0" role="group" aria-label="<?php esc_attr_e('Customer logos', 'vitahealthmedia'); ?>">
    <div class="partner-logos__track">
      <ul class="partner-logos__set">
        <?php foreach ($logos as $logo) : ?>
          <li class="partner-logos__item"><?php echo $logo; ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</section>
