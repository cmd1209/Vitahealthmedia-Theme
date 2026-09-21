<?php
/**
 * Title: Leistung Grid
 * Slug: vita-health/leistung-grid
 * Categories: vita-health
 * Description: A responsive grid with freely duplicable, removable, and reorderable cards.
 * Viewport Width: 1180
 */
$cards = [
    ['video', 'Video', 'Video-Content für den Gesundheitsmarkt – von YouTube-Serien und Webinaren bis zu TikToks und TV-Spots.'],
    ['seo', 'SEO', 'Wissenschaftlich fundierte, verständliche und SEO-optimierte Inhalte für digitale Gesundheitskommunikation.'],
    ['social-media', 'Social Media', 'Wir finden die passende Plattform, Sprache und Strategie, um Ihre Zielgruppen wirkungsvoll zu erreichen.'],
    ['kundenmagazin', 'Kundenmagazin', 'Wir entwickeln hochwertige Printformate und Layouts, die Gesundheitsinhalte verständlich und attraktiv vermitteln.'],
    ['event', 'Event', 'Von Informationsveranstaltungen bis Afterwork: Wir konzipieren und realisieren Gesundheitsevents mit relevanten Inhalten.'],
    ['podcast', 'Podcast', 'Wir entwickeln und produzieren Gesundheits-Podcasts – als Interview, Erklärformat oder Reportage.'],
    ['kampagne', 'Kampagne', 'Digital oder klassisch: Wir entwickeln Kampagnen, die Botschaften sichtbar machen und Menschen erreichen.'],
    ['strategie', 'Strategie', 'Wir entwickeln Kommunikationsstrategien, die Maßnahmen bündeln, schärfen und wirksamer machen.'],
];
?>
<!-- wp:group {"className":"leistung-grid","templateLock":false,"allowedBlocks":["core/group"],"metadata":{"name":"Leistung Grid"},"layout":{"type":"default"}} -->
<div class="wp-block-group leistung-grid">
<?php foreach ($cards as [$icon, $title, $description]) {
    get_template_part('parts/leistung-card', null, compact('icon', 'title', 'description'));
} ?>
</div>
<!-- /wp:group -->
