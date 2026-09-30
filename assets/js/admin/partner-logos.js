(function () {
  const config = window.vitaPartnerLogosAdmin;
  const list = document.getElementById('vita-partner-logo-list');
  const input = document.getElementById('vita-partner-logo-keys');
  const addButton = document.getElementById('vita-partner-logo-add');
  const addStartersButton = document.getElementById('vita-partner-logo-add-starters');
  if (!config || !list || !input || !addButton || !addStartersButton || !window.wp || !wp.media) {
    return;
  }

  const logos = config.logos.slice();

  function actionButton(text, action, index, disabled) {
    const button = document.createElement('button');
    button.type = 'button';
    button.className = 'button button-secondary';
    button.textContent = action === 'up' ? '↑' : action === 'down' ? '↓' : text;
    button.title = text + ': ' + logos[index].label;
    button.dataset.action = action;
    button.dataset.index = String(index);
    button.disabled = disabled;
    button.setAttribute('aria-label', button.title);
    return button;
  }

  function render() {
    list.replaceChildren();
    input.value = JSON.stringify(logos.map(function (logo) { return logo.key; }));

    if (!logos.length) {
      const empty = document.createElement('li');
      empty.textContent = config.labels.empty;
      list.appendChild(empty);
      return;
    }

    logos.forEach(function (logo, index) {
      const item = document.createElement('li');
      item.className = 'vita-partner-logo-item';

      const preview = document.createElement('span');
      preview.className = 'vita-partner-logo-item__preview';

      const image = document.createElement('img');
      image.src = logo.url;
      image.alt = logo.alt || '';
      preview.appendChild(image);
      item.appendChild(preview);

      const name = document.createElement('span');
      name.className = 'vita-partner-logo-item__name';
      name.textContent = logo.label;
      item.appendChild(name);

      const actions = document.createElement('div');
      actions.className = 'vita-partner-logo-item__actions';
      actions.appendChild(actionButton(config.labels.moveUp, 'up', index, index === 0));
      actions.appendChild(actionButton(config.labels.moveDown, 'down', index, index === logos.length - 1));
      actions.appendChild(actionButton(config.labels.remove, 'remove', index, false));
      item.appendChild(actions);
      list.appendChild(item);
    });
  }

  list.addEventListener('click', function (event) {
    const button = event.target.closest('button[data-action]');
    if (!button) {
      return;
    }
    const index = Number(button.dataset.index);
    const action = button.dataset.action;
    let focusIndex = index;
    if (action === 'remove') {
      logos.splice(index, 1);
      focusIndex = Math.min(index, logos.length - 1);
    } else {
      const next = index + (action === 'up' ? -1 : 1);
      if (next < 0 || next >= logos.length) {
        return;
      }
      const moved = logos.splice(index, 1)[0];
      logos.splice(next, 0, moved);
      focusIndex = next;
    }
    render();
    const nextButton = list.querySelector('button[data-action="' + action + '"][data-index="' + focusIndex + '"]');
    if (nextButton && !nextButton.disabled) {
      nextButton.focus();
    } else {
      addButton.focus();
    }
  });

  addButton.addEventListener('click', function () {
    const frame = wp.media({
      title: config.labels.add,
      button: { text: config.labels.add },
      library: { type: 'image' },
      multiple: true
    });
    frame.on('select', function () {
      frame.state().get('selection').each(function (attachment) {
        const media = attachment.toJSON();
        const key = 'media:' + media.id;
        if (logos.some(function (logo) { return logo.key === key; })) {
          return;
        }
        const sizes = media.sizes || {};
        logos.push({
          key: key,
          url: sizes.medium ? sizes.medium.url : media.url,
          alt: media.alt || media.title || '',
          label: media.title || 'Logo ' + media.id
        });
      });
      render();
    });
    frame.open();
  });

  addStartersButton.addEventListener('click', function () {
    config.starterLogos.forEach(function (starter) {
      if (!logos.some(function (logo) { return logo.key === starter.key; })) {
        logos.push(starter);
      }
    });
    render();
  });

  render();
})();
