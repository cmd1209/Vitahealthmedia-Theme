<?php
$members = new WP_Query([
    'post_type' => 'team_member',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'orderby' => ['menu_order' => 'ASC', 'title' => 'ASC', 'ID' => 'ASC'],
    'no_found_rows' => true,
    'ignore_sticky_posts' => true,
]);
if (!$members->have_posts()) {
    return;
}
?>
<div class="team-list">
  <?php while ($members->have_posts()) : $members->the_post(); ?>
    <?php get_template_part('parts/team-card'); ?>
  <?php endwhile; ?>
</div>
<?php wp_reset_postdata(); ?>
