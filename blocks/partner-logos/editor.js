(function (wp) {
  const el = wp.element.createElement;
  const __ = wp.i18n.__;
  const { useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck } = wp.blockEditor;
  const { Button, PanelBody } = wp.components;
  const defaults = ['hello', 'kabi', 'umschau', 'viactiv', 'siemens', 'korian', 'gesund.de'];

  wp.blocks.registerBlockType('vita-health/partner-logos', {
    edit: function ({ attributes, setAttributes }) {
      const media = wp.data.useSelect(function (select) {
        return attributes.logoIds.map(function (id) { return select('core').getMedia(id); });
      }, [attributes.logoIds]);
      const logos = attributes.useDefaults
        ? defaults.map(function (name) { return { id: name, source_url: window.vitaPartnerLogos.assetUrl + name + '.png', alt_text: name }; })
        : media;
      function move(index, direction) {
        const ids = attributes.logoIds.slice();
        [ids[index], ids[index + direction]] = [ids[index + direction], ids[index]];
        setAttributes({ logoIds: ids });
      }
      return el(wp.element.Fragment, null,
        el(InspectorControls, null,
          el(PanelBody, { title: __('Partner Logos', 'vitahealthmedia') },
            el('p', null, __('Choose logos from the Media Library. Use transparent images with light artwork for the green background.', 'vitahealthmedia')),
            el(MediaUploadCheck, null,
              el(MediaUpload, {
                allowedTypes: ['image'], multiple: true, gallery: true, value: attributes.logoIds,
                onSelect: function (images) {
                  setAttributes({ logoIds: images.map(function (image) { return image.id; }), useDefaults: false });
                },
                render: function ({ open }) {
                  return el(Button, { variant: 'secondary', onClick: open }, __('Select / edit logos', 'vitahealthmedia'));
                }
              })
            ),
            !attributes.useDefaults && el(Button, { variant: 'tertiary', onClick: function () {
              setAttributes({ useDefaults: true, logoIds: [] });
            } }, __('Restore starter logos', 'vitahealthmedia'))
          )
        ),
        el('section', useBlockProps({ className: 'partner-logos' }),
          el(RichText, {
            tagName: 'h2', value: attributes.heading, allowedFormats: [],
            onChange: function (heading) { setAttributes({ heading: heading }); }
          }),
          el('div', { className: 'partner-logos__editor-list' },
            logos.map(function (logo, index) {
              return el('div', { className: 'partner-logos__editor-item', key: logo ? logo.id : index },
                logo && el('img', { src: logo.source_url, alt: logo.alt_text || '' }),
                !logo && el('p', null, __('Logo unavailable or loading', 'vitahealthmedia')),
                !attributes.useDefaults && el('div', null,
                  el(Button, { label: __('Move logo left', 'vitahealthmedia'), disabled: index === 0, onClick: function () { move(index, -1); } }, '←'),
                  el(Button, { label: __('Move logo right', 'vitahealthmedia'), disabled: index === logos.length - 1, onClick: function () { move(index, 1); } }, '→'),
                  el(Button, { label: __('Remove logo', 'vitahealthmedia'), onClick: function () {
                    setAttributes({ logoIds: attributes.logoIds.filter(function (_, i) { return i !== index; }) });
                  } }, '×')
                )
              );
            })
          ),
          !logos.length && el('p', null, __('Select logos in the block sidebar to begin.', 'vitahealthmedia'))
        )
      );
    },
    save: function () { return null; }
  });
})(window.wp);
