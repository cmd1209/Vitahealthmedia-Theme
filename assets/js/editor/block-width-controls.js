(function (wp) {
  const el = wp.element.createElement;
  const __ = wp.i18n.__;
  const { BlockControls } = wp.blockEditor;
  const { ToolbarDropdownMenu } = wp.components;
  const widths = [
    { value: 'fill', title: __('Fill container', 'vitahealthmedia') },
    { value: 'narrow', title: __('Narrow — 720px', 'vitahealthmedia') },
    { value: 'medium', title: __('Medium — 960px', 'vitahealthmedia') },
    { value: 'content', title: __('Default content — 1180px', 'vitahealthmedia') },
    { value: 'wide', title: __('Wide — 1440px', 'vitahealthmedia') },
    { value: 'full', title: __('Full width', 'vitahealthmedia') }
  ];
  const placements = [
    { value: 'left', title: __('Place block left', 'vitahealthmedia') },
    { value: 'center', title: __('Place block center', 'vitahealthmedia') },
    { value: 'right', title: __('Place block right', 'vitahealthmedia') }
  ];

  function supportsWidth(name) {
    const type = wp.blocks.getBlockType(name);
    return !!(type && type.attributes && type.attributes.vitaWidth);
  }

  function WidthControl({ attributes, setAttributes }) {
    const canPlace = ['narrow', 'medium', 'content'].includes(attributes.vitaWidth);
    const widthControls = widths.map(function (width) {
      return {
        title: width.title,
        icon: attributes.vitaWidth === width.value ? 'yes' : undefined,
        isActive: attributes.vitaWidth === width.value,
        role: 'menuitemradio',
        onClick: function () {
          setAttributes({
            vitaWidth: width.value,
            vitaPlacement: ['narrow', 'medium', 'content'].includes(width.value) ? attributes.vitaPlacement : undefined
          });
        }
      };
    });
    const placementControls = placements.map(function (placement) {
      const active = canPlace && (attributes.vitaPlacement || 'center') === placement.value;
      return {
        title: placement.title,
        icon: active ? 'yes' : undefined,
        isActive: active,
        isDisabled: !canPlace,
        role: 'menuitemradio',
        onClick: function () { setAttributes({ vitaPlacement: placement.value }); }
      };
    });
    const resetControl = {
      title: __('Reset width to theme default', 'vitahealthmedia'),
      icon: !attributes.vitaWidth ? 'yes' : undefined,
      onClick: function () { setAttributes({ vitaWidth: undefined, vitaPlacement: undefined }); }
    };

    return el(BlockControls, { group: 'block' },
      el(ToolbarDropdownMenu, {
        icon: 'align-wide',
        label: __('Width', 'vitahealthmedia'),
        text: __('Width', 'vitahealthmedia'),
        controls: [widthControls, placementControls, [resetControl]]
      })
    );
  }

  wp.hooks.addFilter('editor.BlockEdit', 'vita-health/block-width-controls', function (BlockEdit) {
    return function (props) {
      if (!supportsWidth(props.name)) return el(BlockEdit, props);
      return el(wp.element.Fragment, null,
        el(BlockEdit, props),
        props.isSelected && el(WidthControl, { attributes: props.attributes, setAttributes: props.setAttributes })
      );
    };
  });

  // Preview-only attributes preserve existing blocks' saved HTML and validation.
  wp.hooks.addFilter('editor.BlockListBlock', 'vita-health/block-width-preview', function (BlockListBlock) {
    return function (props) {
      if (!supportsWidth(props.name)) return el(BlockListBlock, props);
      return el(BlockListBlock, Object.assign({}, props, {
        wrapperProps: Object.assign({}, props.wrapperProps, {
          'data-vita-width': props.attributes.vitaWidth || undefined,
          'data-vita-placement': props.attributes.vitaWidth ? props.attributes.vitaPlacement : undefined
        })
      }));
    };
  });
})(window.wp);
