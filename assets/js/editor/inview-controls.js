(function (wp) {
  const el = wp.element.createElement;
  const __ = wp.i18n.__;
  const { InspectorControls } = wp.blockEditor;
  const { PanelBody, ToggleControl } = wp.components;
  const defaultReveal = /(?:^|\s)(?:leistung-card|quote-section__content|contact)(?=\s|$)/;

  wp.hooks.addFilter('editor.BlockEdit', 'vita-health/inview-controls', function (BlockEdit) {
    return function (props) {
      const blockType = wp.blocks.getBlockType(props.name);
      if (!blockType || !blockType.attributes || !blockType.attributes.vitaRevealOnScroll) {
        return el(BlockEdit, props);
      }

      const setting = props.attributes.vitaRevealOnScroll;
      const checked = typeof setting === 'boolean'
        ? setting
        : defaultReveal.test(props.attributes.className || '');

      return el(wp.element.Fragment, null,
        el(BlockEdit, props),
        props.isSelected && el(InspectorControls, null,
          el(PanelBody, { title: __('Animation', 'vitahealthmedia'), initialOpen: false },
            el(ToggleControl, {
              label: __('Reveal on scroll', 'vitahealthmedia'),
              help: __('Fade in once when this block enters the screen.', 'vitahealthmedia'),
              checked: checked,
              onChange: function (value) {
                props.setAttributes({ vitaRevealOnScroll: value });
              }
            })
          )
        )
      );
    };
  });
})(window.wp);
