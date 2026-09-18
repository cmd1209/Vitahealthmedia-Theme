(function (wp, lucide) {
  const el = wp.element.createElement;
  const __ = wp.i18n.__;
  const { InspectorControls } = wp.blockEditor;
  const { PanelBody, SelectControl, ToggleControl } = wp.components;
  const iconMasks = {};

  function iconMask(name) {
    name = name || 'chevron-right';
    if (!iconMasks[name]) {
      const key = name.split('-').map(part => part.charAt(0).toUpperCase() + part.slice(1)).join('');
      const svg = lucide.createElement(lucide.icons[key] || lucide.icons.ChevronRight);
      iconMasks[name] = 'url("data:image/svg+xml,' + encodeURIComponent(svg.outerHTML) + '")';
    }
    return iconMasks[name];
  }

  function buttonClasses(attributes) {
    return 'button--' + (attributes.vitaVariant || 'primary') +
      ' button--' + (attributes.vitaSize || 'default');
  }

  // Editor-only classes: nothing is added to the block's saved HTML.
  wp.hooks.addFilter('editor.BlockListBlock', 'vita-health/button-classes', function (BlockListBlock) {
    return function (props) {
      if (props.name !== 'core/button') return el(BlockListBlock, props);
      const a = props.attributes;
      const wrapperProps = props.wrapperProps || {};
      // A CSS mask previews Lucide without inserting nodes into editable RichText.
      return el(BlockListBlock, Object.assign({}, props, {
        className: [props.className, 'vita-button-editor', buttonClasses(a),
          a.vitaHasIcon && ('vita-button-icon-' + (a.vitaIconPosition || 'right'))
        ].filter(Boolean).join(' '),
        wrapperProps: Object.assign({}, wrapperProps, {
          style: Object.assign({}, wrapperProps.style, {
            '--vita-button-icon': a.vitaHasIcon ? iconMask(a.vitaIconName) : 'none'
          })
        })
      }));
    };
  });

  wp.hooks.addFilter('editor.BlockEdit', 'vita-health/button-controls', function (BlockEdit) {
    return function (props) {
      if (props.name !== 'core/button') return el(BlockEdit, props);
      const a = props.attributes;
      return el(wp.element.Fragment, null,
        el(BlockEdit, props),
        props.isSelected && el(InspectorControls, null,
          el(PanelBody, { title: __('Vita Button', 'vitahealthmedia'), initialOpen: true },
            el(SelectControl, {
              label: __('Variant', 'vitahealthmedia'), value: a.vitaVariant || 'primary',
              options: [
                { label: __('Primary', 'vitahealthmedia'), value: 'primary' },
                { label: __('Secondary', 'vitahealthmedia'), value: 'secondary' },
                { label: __('Highlight', 'vitahealthmedia'), value: 'highlight' }
              ],
              onChange: value => props.setAttributes({ vitaVariant: value })
            }),
            el(SelectControl, {
              label: __('Size', 'vitahealthmedia'), value: a.vitaSize || 'default',
              options: [
                { label: __('Default', 'vitahealthmedia'), value: 'default' },
                { label: __('Small', 'vitahealthmedia'), value: 'small' }
              ],
              onChange: value => props.setAttributes({ vitaSize: value })
            }),
            el(ToggleControl, {
              label: __('Show icon', 'vitahealthmedia'), checked: !!a.vitaHasIcon,
              onChange: value => props.setAttributes({ vitaHasIcon: value })
            }),
            a.vitaHasIcon && el(SelectControl, {
              label: __('Icon', 'vitahealthmedia'), value: a.vitaIconName || 'chevron-right',
              options: [
                { label: __('Chevron right (default)', 'vitahealthmedia'), value: 'chevron-right' },
                { label: __('Arrow right', 'vitahealthmedia'), value: 'arrow-right' },
                { label: __('Arrow up right', 'vitahealthmedia'), value: 'arrow-up-right' },
                { label: __('External link', 'vitahealthmedia'), value: 'external-link' },
                { label: __('Download', 'vitahealthmedia'), value: 'download' },
                { label: __('Mail', 'vitahealthmedia'), value: 'mail' },
                { label: __('Send', 'vitahealthmedia'), value: 'send' },
                { label: __('Play', 'vitahealthmedia'), value: 'play' },
                { label: __('Plus', 'vitahealthmedia'), value: 'plus' },
                { label: __('Check', 'vitahealthmedia'), value: 'check' }
              ],
              onChange: value => props.setAttributes({ vitaIconName: value })
            }),
            a.vitaHasIcon && el(SelectControl, {
              label: __('Icon position', 'vitahealthmedia'), value: a.vitaIconPosition || 'right',
              options: [
                { label: __('Left', 'vitahealthmedia'), value: 'left' },
                { label: __('Right', 'vitahealthmedia'), value: 'right' }
              ],
              onChange: value => props.setAttributes({ vitaIconPosition: value })
            })
          )
        )
      );
    };
  });
})(window.wp, window.lucide);
