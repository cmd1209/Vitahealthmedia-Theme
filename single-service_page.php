<?php
get_header();
$term = vita_health_get_service_term_for_page(get_queried_object_id());
?>
<main id="main-content" class="content-wrapper service-page">
  <?php while (have_posts()) : the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class('project-page'); ?>>
      <?php get_template_part('parts/project-hero', null, ['term' => $term]); ?>
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
