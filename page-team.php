<?php
/**
 * Template Name: Team
 * Template Post Type: page
 */
get_header();
$block_page = vita_health_has_team_block(get_post_field('post_content', get_queried_object_id()));
?>
<?php if ($block_page) : ?>
<main id="main-content" class="content-wrapper">
  <?php while (have_posts()) : the_post(); ?>
    <div <?php post_class('page-content'); ?>>
      <?php the_content(); ?>
      <?php wp_link_pages(); ?>
    </div>
  <?php endwhile; ?>
</main>
<?php else : ?>
<main id="main-content" class="team-section">
  <?php while (have_posts()) : the_post(); ?>
    <h1 class="team-section__title"><?php the_title(); ?></h1>
    <div class="team-section__inner">
      <?php if (trim(get_the_content()) !== '') : ?>
        <div class="team-section__intro lead content-flow">
          <?php the_content(); ?>
          <?php wp_link_pages(); ?>
        </div>
      <?php endif; ?>
      <?php get_template_part('parts/team-list'); ?>
    </div>
  <?php endwhile; ?>
</main>
<?php endif; ?>
<?php get_footer(); ?>
