(function (wp) {
  // Match the server default when opening older heroes; native Cover controls
  // handle live Featured Image updates and intentional image overrides.
  wp.hooks.addFilter('blocks.getBlockAttributes', 'vita-health/page-hero-featured-image', function (attributes, blockType, innerHTML, savedAttributes) {
    if (blockType.name !== 'core/cover'
      || !/(?:^|\s)page-hero(?:\s|$)/.test(attributes.className || '')
      || Object.prototype.hasOwnProperty.call(savedAttributes || {}, 'useFeaturedImage')
      || attributes.url
      || attributes.id
      || (attributes.backgroundType || 'image') !== 'image') {
      return attributes;
    }
    return Object.assign({}, attributes, { useFeaturedImage: true });
  });
})(window.wp);
