<?php
get_header();
$term = get_queried_object();
$page_id = $term instanceof WP_Term ? vita_health_get_service_page_id($term->term_id) : 0;
$service_page = $page_id ? get_post($page_id) : null;
?>
<main id="main-content" class="service-page">
  <?php get_template_part('parts/project-hero', null, ['term' => $term]); ?>

  <?php if ($service_page instanceof WP_Post && $service_page->post_type === 'service_page' && $service_page->post_status === 'publish') :
      global $post;
      $post = $service_page;
      setup_postdata($post);
  ?>
    <article class="content-wrapper project-page__content">
      <?php the_content(); ?>
      <?php wp_link_pages(); ?>
    </article>
  <?php
      wp_reset_postdata();
  endif; ?>
</main>
<?php get_footer(); ?>
