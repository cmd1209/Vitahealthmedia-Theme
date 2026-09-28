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
      <div class="related-projects_header">
        <h2 class="wp-block-heading headline-lead__heading h2">Mehr <mark style="background-color:rgba(0, 0, 0, 0)" class="has-inline-color has-vita-coral-color">Projekte</mark> entdecken</h2>
      </div>
      <?php if (!has_block('vita-health/project-slider', get_the_ID())) :
        get_template_part('parts/project-slider', null, [
          'count' => 4,
          'mode' => 'related',
          'taxonomy' => 'project_category',
          'currentPostId' => get_the_ID(),
          ]);
        endif; ?>
  <?php endwhile; ?>
  <?php
$archive_content_page = get_page_by_path('projekt-archiv-inhalt');
if ($archive_content_page && $archive_content_page->post_status === 'publish') :
  $archive_content_query = new WP_Query([
    'post_type' => 'page',
    'page_id' => $archive_content_page->ID,
    'post_status' => 'publish',
    'posts_per_page' => 1,
    'no_found_rows' => true,
    ]);
    if ($archive_content_query->have_posts()) :
      ?>
  <div class="content-wrapper project-archive__extra">
    <?php while ($archive_content_query->have_posts()) : $archive_content_query->the_post();
        the_content();
    endwhile; ?>
  </div>
<?php
    endif;
    wp_reset_postdata();
endif;
?>
</main>

<?php get_footer(); ?>
