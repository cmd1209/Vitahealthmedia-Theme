(function (wp) {
  const el = wp.element.createElement;
  const ServerSideRender = wp.serverSideRender.default || wp.serverSideRender;

  wp.blocks.registerBlockType('vita-health/leistung-grid', {
    edit: function () {
      return el('div', wp.blockEditor.useBlockProps(),
        el(ServerSideRender, { block: 'vita-health/leistung-grid' })
      );
    },
    save: function () {
      return null;
    }
  });

  wp.hooks.addFilter('editor.BlockEdit', 'vita-health/legacy-leistung-grid', function (BlockEdit) {
    return function (props) {
      const classes = props.attributes && props.attributes.className || '';
      if (props.name === 'core/group' && /(?:^|\s)leistung-grid(?:\s|$)/.test(classes)) {
        return el('div', { className: 'leistung-grid-legacy-preview' },
          el(ServerSideRender, { block: 'vita-health/leistung-grid' })
        );
      }
      return el(BlockEdit, props);
    };
  });
})(window.wp);
