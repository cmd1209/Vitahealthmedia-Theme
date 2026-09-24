<?php get_header(); ?>

<main id="main-content" class="content-wrapper">
  <?php while (have_posts()) : the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class('project-page'); ?>>
      <?php get_template_part('parts/project-hero'); ?>
      <?php if (trim(get_the_content())) : ?>
        <div class="project-page__content">
          <?php the_content(); ?>
          <?php wp_link_pages(); ?>
        </div>
      <?php endif; ?>
    </article>
  <?php endwhile; ?>
</main>

<?php get_footer(); ?>
