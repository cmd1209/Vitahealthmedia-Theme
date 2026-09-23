(function (wp) {
  const el = wp.element.createElement;
  const __ = wp.i18n.__;
  const { InspectorControls, useBlockProps } = wp.blockEditor;
  const { PanelBody, RangeControl, SelectControl, Placeholder } = wp.components;
  const ServerSideRender = wp.serverSideRender.default || wp.serverSideRender;
  wp.blocks.registerBlockType('vita-health/project-slider', {
    edit: function ({ attributes, setAttributes, context }) {
      const taxonomies = window.vitaProjectTaxonomies || [];
      const taxonomy = taxonomies.find(function (item) { return item.name === attributes.taxonomy; });
      return el(wp.element.Fragment, null,
        el(InspectorControls, null,
          el(PanelBody, { title: __('Projects query', 'vitahealthmedia') },
            el(RangeControl, { label: __('Number of projects', 'vitahealthmedia'), min: 1, max: 24, value: attributes.count,
              onChange: function (count) { setAttributes({ count: count }); } }),
            el(SelectControl, { label: __('Order', 'vitahealthmedia'), value: attributes.order,
              options: [{ label: __('Newest first', 'vitahealthmedia'), value: 'DESC' }, { label: __('Oldest first', 'vitahealthmedia'), value: 'ASC' }],
              onChange: function (order) { setAttributes({ order: order }); } }),
            el(SelectControl, { label: __('Show', 'vitahealthmedia'), value: attributes.mode,
              options: [
                { label: __('All projects', 'vitahealthmedia'), value: 'latest' },
                { label: __('Selected categories / terms', 'vitahealthmedia'), value: 'terms' },
                { label: __('Related to the current project', 'vitahealthmedia'), value: 'related' }
              ], onChange: function (mode) { setAttributes({ mode: mode }); } }),
            attributes.mode !== 'latest' && el(SelectControl, {
              label: __('Project taxonomy', 'vitahealthmedia'), value: attributes.taxonomy,
              options: [{ label: __('Choose a taxonomy', 'vitahealthmedia'), value: '' }].concat(taxonomies.map(function (item) { return { label: item.label, value: item.name }; })),
              onChange: function (name) { setAttributes({ taxonomy: name, terms: [] }); }
            }),
            !taxonomies.length && el('p', null, __('No project categories exist yet. Once a public taxonomy is added to Projects, reload the editor to select it here.', 'vitahealthmedia')),
            attributes.mode === 'terms' && taxonomy && el(SelectControl, {
              label: __('Matching any selected term', 'vitahealthmedia'), multiple: true, value: attributes.terms.map(String),
              options: taxonomy.terms.map(function (term) { return { label: term.name, value: String(term.id) }; }),
              onChange: function (terms) { setAttributes({ terms: terms.map(Number) }); }
            }),
            attributes.mode === 'related' && el('p', null, __('Shows projects sharing a term with the current project, excluding itself. On a normal page, use selected terms instead.', 'vitahealthmedia'))
          )
        ),
        el('div', useBlockProps({ className: 'project-slider-editor' }),
          el(ServerSideRender, {
            block: 'vita-health/project-slider', attributes: attributes,
            urlQueryArgs: { post_id: context.postId || 0 },
            EmptyResponsePlaceholder: function () { return el(Placeholder, { label: __('Projects Slider', 'vitahealthmedia') }, __('No matching projects. Check the query settings in the sidebar.', 'vitahealthmedia')); }
          })
        )
      );
    },
    save: function () { return null; }
  });
})(window.wp);
