(function (wp) {
  const el = wp.element.createElement;
  const __ = wp.i18n.__;
  const { InspectorControls, InnerBlocks, useBlockProps, useInnerBlocksProps } = wp.blockEditor;
  const { PanelBody, RangeControl, TextControl } = wp.components;

  wp.blocks.registerBlockType('vita-health/content-slider', {
    edit: function ({ attributes, setAttributes }) {
      const blockProps = useBlockProps({
        className: 'project-slider content-slider',
        style: { '--content-slider-visible': attributes.visibleSlides }
      });
      const innerProps = useInnerBlocksProps({ className: 'project-slider__track' }, {
        allowedBlocks: ['vita-health/content-slide'],
        template: [['vita-health/content-slide'], ['vita-health/content-slide'], ['vita-health/content-slide']],
        renderAppender: InnerBlocks.ButtonBlockAppender
      });

      return el(wp.element.Fragment, null,
        el(InspectorControls, null,
          el(PanelBody, { title: __('Slider settings', 'vitahealthmedia') },
            el(RangeControl, {
              label: __('Visible slides', 'vitahealthmedia'),
              help: __('Desktop: 1–3 slides. Tablets: up to 2. Phones: 1.', 'vitahealthmedia'),
              min: 1, max: 3, step: 1, value: attributes.visibleSlides,
              onChange: function (value) { setAttributes({ visibleSlides: value || 1 }); }
            }),
            el(TextControl, {
              label: __('Accessible slider label', 'vitahealthmedia'),
              value: attributes.label,
              onChange: function (label) { setAttributes({ label: label }); }
            })
          )
        ),
        el('div', blockProps,
          el('p', { className: 'content-slider__editor-help' },
            __('Add blocks inside each slide. Use List View to select, reorder, duplicate, or remove slides. All slides remain visible while editing.', 'vitahealthmedia')),
          el('div', innerProps)
        )
      );
    },
    save: function () {
      return el(InnerBlocks.Content);
    }
  });

  wp.blocks.registerBlockType('vita-health/content-slide', {
    edit: function () {
      const blockProps = useBlockProps({ className: 'content-slider__slide' });
      const innerProps = useInnerBlocksProps(blockProps, {
        template: [['core/paragraph', { placeholder: __('Add slide content…', 'vitahealthmedia') }]],
        renderAppender: InnerBlocks.ButtonBlockAppender
      });
      return el('div', innerProps);
    },
    save: function () {
      return el('div', useBlockProps.save({ className: 'content-slider__slide' }), el(InnerBlocks.Content));
    }
  });
})(window.wp);
