<?php
$selected_slug = $args['selected_slug'] ?? '';
$archive_url = get_post_type_archive_link('project');
$categories = vita_health_project_categories();
if (!$archive_url) {
    return;
}
?>
<nav class="project-archive__filters" aria-label="<?php esc_attr_e('Projekte filtern', 'vitahealthmedia'); ?>">
  <a class="project-archive__filter<?php echo $selected_slug ? '' : ' is-active'; ?>" href="<?php echo esc_url($archive_url); ?>"<?php echo $selected_slug ? '' : ' aria-current="page"'; ?>>Alle <?php echo esc_html(wp_count_posts('project')->publish); ?> Projekte</a>
  <?php foreach ($categories as $category) :
      $url = add_query_arg('project_category', $category->slug, $archive_url);
      $active = $selected_slug === $category->slug;
  ?>
    <a class="project-archive__filter<?php echo $active ? ' is-active' : ''; ?>" href="<?php echo esc_url($url); ?>"<?php echo $active ? ' aria-current="page"' : ''; ?>><?php echo esc_html($category->name); ?></a>
  <?php endforeach; ?>
</nav>
