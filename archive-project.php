<?php
get_header();

$archive_url = get_post_type_archive_link('project');
$selected_category = isset($_GET['project_category']) && is_string($_GET['project_category'])
    ? sanitize_title(wp_unslash($_GET['project_category']))
    : '';
$project_ids = get_posts([
    'post_type' => 'project',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'fields' => 'ids',
    'no_found_rows' => true,
]);
$categories = $project_ids ? get_terms([
    'taxonomy' => 'category',
    'hide_empty' => true,
    'object_ids' => $project_ids,
]) : [];
$categories = is_wp_error($categories) ? [] : $categories;
$valid_slugs = wp_list_pluck($categories, 'slug');
if (!in_array($selected_category, $valid_slugs, true)) {
    $selected_category = '';
}
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
    <nav class="project-archive__filters" aria-label="<?php esc_attr_e('Projekte filtern', 'vitahealthmedia'); ?>">
      <a class="project-archive__filter<?php echo $selected_category === '' ? ' is-active' : ''; ?>" href="<?php echo esc_url($archive_url); ?>"<?php echo $selected_category === '' ? ' aria-current="page"' : ''; ?>>Alle <?php echo wp_count_posts('project')->publish; ?> Projekte</a>
      <?php foreach ($categories as $category) :
          $active = $selected_category === $category->slug;
      ?>
        <a class="project-archive__filter<?php echo $active ? ' is-active' : ''; ?>" href="<?php echo esc_url(add_query_arg('project_category', $category->slug, $archive_url)); ?>"<?php echo $active ? ' aria-current="page"' : ''; ?>><?php echo esc_html($category->name); ?></a>
      <?php endforeach; ?>
    </nav>

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
            <a class="button button--primary" href="<?php echo esc_url($page_url($current_page - 1)); ?>"><span class="button__icon" aria-hidden="true"><i data-lucide="chevron-left"></i></span>Neuere Beiträge</a>
          <?php endif; ?>
          <?php if ($current_page < $total_pages) : ?>
            <a class="button button--primary" href="<?php echo esc_url($page_url($current_page + 1)); ?>">Ältere Beiträge laden<span class="button__icon" aria-hidden="true"><i data-lucide="chevron-right"></i></span></a>
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
