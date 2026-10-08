<?php
$member_id = absint($args['member_id'] ?? get_the_ID());
$name = get_the_title($member_id);
$job_title = get_post_meta($member_id, '_vita_team_job_title', true);
$quote = vita_health_sanitize_team_field('quote', get_post_meta($member_id, '_vita_team_quote', true));
$email = vita_health_sanitize_team_field('email', get_post_meta($member_id, '_vita_team_email', true));
$linkedin = vita_health_sanitize_team_field('linkedin', get_post_meta($member_id, '_vita_team_linkedin', true));
?>
<article class="team-card" aria-labelledby="team-member-<?php echo esc_attr($member_id); ?>">
  <?php if (has_post_thumbnail($member_id)) : ?>
    <div class="team-card__portrait">
      <?php echo get_the_post_thumbnail($member_id, 'medium_large', [
          'class' => 'team-card__image',
          'sizes' => '(max-width: 600px) 240px, 300px',
          'alt' => get_post_meta(get_post_thumbnail_id($member_id), '_wp_attachment_image_alt', true) ?: $name,
      ]); ?>
    </div>
  <?php endif; ?>
  <div class="team-card__info">
    <?php if ($quote !== '') : ?>
      <blockquote class="team-card__quote"><?php echo nl2br($quote); ?></blockquote>
    <?php endif; ?>
    <div class="team-card__author">
      <h2 class="team-card__name lead" id="team-member-<?php echo esc_attr($member_id); ?>"><?php echo esc_html($name); ?></h2>
      <?php if ($job_title !== '') : ?>
        <p class="team-card__job label"><?php echo nl2br(esc_html($job_title)); ?></p>
      <?php endif; ?>
      <?php if ($email || $linkedin) : ?>
        <div class="team-card__links label">
          <?php if ($email) : ?>
            <a href="<?php echo esc_url('mailto:' . $email); ?>" aria-label="<?php echo esc_attr(sprintf(__('E-Mail an %s', 'vitahealthmedia'), $name)); ?>"><?php esc_html_e('E-Mail', 'vitahealthmedia'); ?></a>
          <?php endif; ?>
          <?php if ($linkedin) : ?>
            <a href="<?php echo esc_url($linkedin); ?>" aria-label="<?php echo esc_attr(sprintf(__('%s auf LinkedIn', 'vitahealthmedia'), $name)); ?>">LinkedIn</a>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</article>
