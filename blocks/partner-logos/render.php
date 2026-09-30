<?php
$logos = vita_health_partner_logo_list();
if (!vita_health_partner_logos_are_shared() && !($attributes['useDefaults'] ?? true)) {
    $logos = [];
    foreach (($attributes['logoIds'] ?? []) as $id) {
        $logo = vita_health_partner_logo_details('media:' . absint($id));
        if ($logo) {
            $logos[] = $logo;
        }
    }
}
if (!$logos) {
    return;
}
$heading_id = wp_unique_id('partner-logos-heading-');
?>
<section <?php echo get_block_wrapper_attributes(['class' => 'partner-logos alignfull', 'aria-labelledby' => $heading_id]); ?>>
  <p class="eyebrow vita-eyebrow" id="<?php echo esc_attr($heading_id); ?>"><?php echo esc_html($attributes['heading'] ?: __('Kunden', 'vitahealthmedia')); ?></p>
  <button class="partner-logos__toggle" type="button" hidden aria-pressed="false" data-pause="<?php esc_attr_e('Pause logo animation', 'vitahealthmedia'); ?>" data-play="<?php esc_attr_e('Play logo animation', 'vitahealthmedia'); ?>" aria-label="<?php esc_attr_e('Pause logo animation', 'vitahealthmedia'); ?>">Ⅱ</button>
  <div class="partner-logos__viewport" tabindex="0" role="group" aria-label="<?php esc_attr_e('Customer logos', 'vitahealthmedia'); ?>">
    <div class="partner-logos__track">
      <ul class="partner-logos__set">
        <?php foreach ($logos as $logo) : ?>
          <li class="partner-logos__item"><img src="<?php echo esc_url($logo['url']); ?>" alt="<?php echo esc_attr($logo['alt']); ?>" width="150" height="75" decoding="async"></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</section>
