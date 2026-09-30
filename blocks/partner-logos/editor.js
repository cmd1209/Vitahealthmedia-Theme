(function (wp) {
  const el = wp.element.createElement;
  const __ = wp.i18n.__;
  const { useBlockProps, RichText, InspectorControls } = wp.blockEditor;
  const { PanelBody } = wp.components;

  wp.blocks.registerBlockType('vita-health/partner-logos', {
    edit: function ({ attributes, setAttributes }) {
      const config = window.vitaPartnerLogos || {};
      const media = wp.data.useSelect(function (select) {
        return (attributes.logoIds || []).map(function (id) { return select('core').getMedia(id); });
      }, [attributes.logoIds]);
      const logos = !config.hasSharedList && attributes.useDefaults === false
        ? media.filter(Boolean).map(function (image) {
          return { key: 'media:' + image.id, url: image.source_url, alt: image.alt_text || '' };
        })
        : (config.logos || []);

      return el(wp.element.Fragment, null,
        el(InspectorControls, null,
          el(PanelBody, { title: __('Partner Logos', 'vitahealthmedia') },
            el('p', null, __('The logo list is shared by every carousel.', 'vitahealthmedia')),
            config.manageUrl && el('a', { href: config.manageUrl, target: '_blank', rel: 'noopener noreferrer' }, __('Manage partner logos', 'vitahealthmedia'))
          )
        ),
        el('section', useBlockProps({ className: 'partner-logos' }),
          el(RichText, {
            tagName: 'p', className: 'eyebrow', value: attributes.heading, allowedFormats: [],
            onChange: function (heading) { setAttributes({ heading: heading }); }
          }),
          el('div', { className: 'partner-logos__editor-list' },
            logos.map(function (logo) {
              return el('div', { className: 'partner-logos__editor-item', key: logo.key },
                el('img', { src: logo.url, alt: logo.alt || '' })
              );
            })
          ),
          !logos.length && el('p', null, __('No partner logos selected yet.', 'vitahealthmedia'))
        )
      );
    },
    save: function () { return null; }
  });
})(window.wp);
