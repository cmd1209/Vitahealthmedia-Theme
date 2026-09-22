<?php get_header(); ?>

<main id="main-content" class="content-wrapper">
  <?php while (have_posts()) : the_post(); ?>
    <div id="post-<?php the_ID(); ?>" <?php post_class('page-content'); ?>>
      <!-- <h1 class="h1"><?php the_title(); ?></h1> -->
      <?php the_content(); ?>
      <?php wp_link_pages(); ?>
    </div>
  <?php endwhile; ?>
</main>

<?php get_footer(); ?>
