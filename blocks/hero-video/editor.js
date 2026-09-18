(function (wp) {
  const el = wp.element.createElement;
  const __ = wp.i18n.__;
  const { useBlockProps, RichText, InnerBlocks, InspectorControls, MediaUpload, MediaUploadCheck } = wp.blockEditor;
  const { PanelBody, Button, SelectControl } = wp.components;
  const template = [['core/buttons', {}, [
    ['core/button', { text: 'Mehr über uns', url: '#mission', vitaVariant: 'secondary' }],
    ['core/button', { text: 'Alle Projekte ansehen', url: '#referenzen', vitaVariant: 'primary', vitaHasIcon: true }]
  ]]];

  wp.blocks.registerBlockType('vita-health/hero-video', {
    edit: function (props) {
      const { attributes, setAttributes } = props;
      const media = wp.data.useSelect(function (select) {
        return attributes.videoId ? select('core').getMedia(attributes.videoId) : null;
      }, [attributes.videoId]);
      const videoUrl = media && media.media_type === 'video' ? media.source_url : '';
      const blockProps = useBlockProps({
        className: 'hero-video hero-video--gradient-' + (attributes.gradient || 'purple-green')
      });

      return el(wp.element.Fragment, null,
        el(InspectorControls, null,
          el(PanelBody, { title: __('Hero Video', 'vitahealthmedia'), initialOpen: true },
            el(MediaUploadCheck, null,
              el(MediaUpload, {
                allowedTypes: ['video'], value: attributes.videoId,
                onSelect: function (video) { setAttributes({ videoId: video.id }); },
                render: function (control) {
                  return el(Button, { variant: 'secondary', onClick: control.open },
                    attributes.videoId ? __('Replace video', 'vitahealthmedia') : __('Select or upload video', 'vitahealthmedia')
                  );
                }
              }),
              !!attributes.videoId && el(Button, {
                variant: 'tertiary', isDestructive: true,
                onClick: function () { setAttributes({ videoId: 0 }); }
              }, __('Remove video', 'vitahealthmedia'))
            ),
            el(SelectControl, {
              label: __('Gradient', 'vitahealthmedia'), value: attributes.gradient || 'purple-green',
              options: [
                { label: __('Purple → Green', 'vitahealthmedia'), value: 'purple-green' },
                { label: __('Green → Purple', 'vitahealthmedia'), value: 'green-purple' },
                { label: __('Dark Green', 'vitahealthmedia'), value: 'dark-green' },
                { label: __('None', 'vitahealthmedia'), value: 'none' }
              ],
              onChange: function (value) { setAttributes({ gradient: value }); }
            })
          )
        ),
        el('section', blockProps,
          videoUrl && el('video', {
            key: videoUrl, className: 'hero-video__media', src: videoUrl,
            muted: true, playsInline: true, preload: 'metadata', 'aria-hidden': true, tabIndex: -1
          }),
          el('div', { className: 'hero-video__overlay', 'aria-hidden': true }),
          el('div', { className: 'content-wrapper hero-video__inner' },
            el('div', { className: 'hero-video__content' },
              el('p', { className: 'hero-video__editor-media' },
                videoUrl ? __('Video selected — playback is shown on the published page.', 'vitahealthmedia') :
                  attributes.videoId ? __('Loading video, or media is unavailable. Replace it in the sidebar.', 'vitahealthmedia') :
                    __('No video selected. Choose a background video in the sidebar.', 'vitahealthmedia')
              ),
              el(RichText, {
                tagName: 'h1', className: 'quote-xl hero-video__quote', value: attributes.headline,
                placeholder: __('Write the hero headline…', 'vitahealthmedia'),
                allowedFormats: ['core/bold', 'core/italic'],
                onChange: function (value) { setAttributes({ headline: value }); }
              }),
              el('div', { className: 'hero-video__buttons' },
                el(InnerBlocks, { allowedBlocks: ['core/buttons'], template: template, templateLock: false })
              )
            )
          )
        )
      );
    },
    // Preserve native button blocks; PHP renders the hero shell and headline.
    save: function () { return el(InnerBlocks.Content); }
  });
})(window.wp);
