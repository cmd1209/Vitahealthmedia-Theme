<?php
// Starter markup shared by the single-card and grid patterns. Once inserted,
// every card is ordinary independent Gutenberg content, not a dynamic block.
$title = $args['title'] ?? 'Video';
$description = $args['description'] ?? 'Video-Content für den Gesundheitsmarkt – von YouTube-Serien und Webinaren bis zu TikToks und TV-Spots.';
$icon = $args['icon'] ?? 'video';
?>
<!-- wp:group {"className":"leistung-card","templateLock":"contentOnly","metadata":{"name":"Leistung Card"},"layout":{"type":"default"}} -->
<div class="wp-block-group leistung-card">
<!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"leistung-card__icon"} -->
<figure class="wp-block-image size-full leistung-card__icon"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/leistung/' . $icon . '.svg'); ?>" alt=""/></figure>
<!-- /wp:image -->
<!-- wp:group {"className":"leistung-card__text","layout":{"type":"default"}} -->
<div class="wp-block-group leistung-card__text">
<!-- wp:heading {"level":3,"className":"leistung-card__title lead"} -->
<h3 class="wp-block-heading leistung-card__title lead"><?php echo esc_html($title); ?></h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"className":"leistung-card__description label"} -->
<p class="leistung-card__description label"><?php echo esc_html($description); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
