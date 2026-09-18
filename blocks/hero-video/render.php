<?php
/** @var array $attributes Block attributes. */
/** @var string $content Rendered native InnerBlocks. */
get_template_part('parts/hero-video', null, [
    'headline' => $attributes['headline'] ?? '',
    'video_id' => $attributes['videoId'] ?? 0,
    'gradient' => $attributes['gradient'] ?? 'purple-green',
    'buttons'  => $content,
    'is_block' => true,
]);
