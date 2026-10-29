<?php
get_header();
$selected_category = vita_health_selected_project_category_slug();
?>
<main id="main-content" class="project-archive">
  <header class="project-archive__hero">
    <img class="project-archive__hero-image" src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/projects/listing-hero.png'); ?>" width="640" height="360" alt="" fetchpriority="high">
    <div class="project-archive__hero-overlay" aria-hidden="true"></div>
    <div class="content-wrapper project-archive__hero-inner">
      <div class="project-archive__intro">
        <h1>Unsere Projekte</h1>
        <p>Wie uns das gelingt? Mit Leidenschaft für die Sache, ehrlichem Interesse an unseren Zielgruppen und verdammt gutem Content.</p>
      </div>
    </div>
  </header>

  <p class="project-archive__status" role="status" aria-live="polite" aria-atomic="true"></p>
  <div class="content-wrapper project-archive__content" tabindex="-1">
    <?php get_template_part('parts/project-category-nav', null, ['selected_slug' => $selected_category]); ?>

    <?php if (have_posts()) : ?>
      <div class="project-archive__grid">
        <?php while (have_posts()) : the_post();
            get_template_part('parts/project-card', null, [
                'title_id' => 'archive-project-' . get_the_ID(),
            ]);
        endwhile; ?>
      </div>
      <?php
      $current_page = max(1, get_query_var('paged'));
      $total_pages = (int) $wp_query->max_num_pages;
      if ($total_pages > 1) :
          $page_url = static function ($page) use ($selected_category) {
              $url = get_pagenum_link($page, false);
              return $selected_category ? add_query_arg('project_category', $selected_category, $url) : $url;
          };
      ?>
        <nav class="project-archive__pagination" aria-label="<?php esc_attr_e('Projektseiten', 'vitahealthmedia'); ?>">
          <?php if ($current_page > 1) : ?>
            <a class="button button--primary" href="<?php echo esc_url($page_url($current_page - 1)); ?>"><span class="button__icon" aria-hidden="true"><i data-lucide="chevron-left"></i></span>Neuere Projekte</a>
          <?php endif; ?>
          <?php if ($current_page < $total_pages) : ?>
            <a class="button button--primary" href="<?php echo esc_url($page_url($current_page + 1)); ?>">Ältere Projekte laden<span class="button__icon" aria-hidden="true"><i data-lucide="chevron-right"></i></span></a>
          <?php endif; ?>
        </nav>
      <?php endif; ?>
    <?php else : ?>
      <p class="project-archive__empty">Für diese Auswahl sind noch keine Projekte veröffentlicht.</p>
    <?php endif; ?>
  </div>
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
