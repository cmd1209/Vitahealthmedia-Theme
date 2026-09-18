<?php get_header(); ?>

<main id="main-content">
  <div class="content-wrapper">
    <?php if (is_home()) : ?>
      <?php get_template_part('parts/hero-video'); ?>
    <?php else : ?>
      <?php while (have_posts()) : the_post(); ?>
        <?php the_content(); ?>
      <?php endwhile; ?>
    <?php endif; ?>
  </div>
</main>

<?php get_footer(); ?>
