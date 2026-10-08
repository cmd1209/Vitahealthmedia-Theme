(function (wp) {
  const el = wp.element.createElement;
  const __ = wp.i18n.__;
  const { useBlockProps, InspectorControls } = wp.blockEditor;
  const { Placeholder, PanelBody, SelectControl } = wp.components;
  const ServerSideRender = wp.serverSideRender.default || wp.serverSideRender;

  wp.blocks.registerBlockType('vita-health/team-list', {
    edit: function () {
      return el('div', useBlockProps({ className: 'team-block-editor' }),
        el(ServerSideRender, {
          block: 'vita-health/team-list',
          EmptyResponsePlaceholder: function () {
            return el(Placeholder, { label: __('Team List', 'vitahealthmedia') },
              __('No published members yet. Add and publish members under Team, then reload this page.', 'vitahealthmedia'));
          }
        })
      );
    },
    save: function () { return null; }
  });

  wp.blocks.registerBlockType('vita-health/team-card', {
    edit: function ({ attributes, setAttributes }) {
      const members = wp.data.useSelect(function (select) {
        return select('core').getEntityRecords('postType', 'team_member', {
          per_page: -1, status: 'publish', orderby: 'title', order: 'asc', context: 'edit'
        });
      }, []);
      const picker = el(SelectControl, {
        label: __('Team member', 'vitahealthmedia'),
        value: String(attributes.memberId),
        options: [{ label: __('Select a member', 'vitahealthmedia'), value: '0' }].concat((members || []).map(function (member) {
          return { label: member.title.raw || member.slug, value: String(member.id) };
        })),
        onChange: function (value) { setAttributes({ memberId: Number(value) }); }
      });
      return el(wp.element.Fragment, null,
        el(InspectorControls, null, el(PanelBody, { title: __('Team Card', 'vitahealthmedia') }, picker)),
        el('div', useBlockProps({ className: 'team-block-editor' }),
          attributes.memberId ? el(ServerSideRender, {
            block: 'vita-health/team-card', attributes: attributes,
            EmptyResponsePlaceholder: function () {
              return el(Placeholder, { label: __('Team Card', 'vitahealthmedia') },
                __('This member is unavailable. Select a published member in the sidebar.', 'vitahealthmedia'));
            }
          }) : el(Placeholder, { label: __('Team Card', 'vitahealthmedia') }, picker,
            !members || members.length ? null : el('p', null, __('Publish a member under Team first.', 'vitahealthmedia')))
        )
      );
    },
    save: function () { return null; }
  });
})(window.wp);
