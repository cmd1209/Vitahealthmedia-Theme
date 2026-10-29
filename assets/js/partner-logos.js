(function () {
  document.querySelectorAll('.partner-logos').forEach(function (section) {
    const viewport = section.querySelector('.partner-logos__viewport');
    const track = section.querySelector('.partner-logos__track');
    const original = section.querySelector('.partner-logos__set');
    const toggle = section.querySelector('.partner-logos__toggle');
    if (!original || !original.children.length) return;
    let hovered = viewport.matches(':hover');
    let focused = viewport.matches(':focus-within');

    function updateToggle() {
      const paused = section.classList.contains('is-paused') || hovered || focused;
      toggle.setAttribute('aria-pressed', String(paused));
      toggle.setAttribute('aria-label', paused ? toggle.dataset.play : toggle.dataset.pause);
      toggle.textContent = paused ? '▶' : 'Ⅱ';
    }

    function update() {
      track.querySelectorAll('.partner-logos__clone').forEach(function (clone) { clone.remove(); });
      const width = original.getBoundingClientRect().width;
      if (!width) return;
      track.style.setProperty('--partner-loop-width', width + 'px');
      // Fill wide screens even when the shared list has only one or two logos.
      const copies = Math.ceil(viewport.clientWidth / width);
      for (let i = 0; i < copies; i++) {
        const clone = original.cloneNode(true);
        clone.classList.add('partner-logos__clone');
        clone.setAttribute('aria-hidden', 'true');
        clone.inert = true;
        track.appendChild(clone);
      }
      section.classList.add('is-enhanced');
      toggle.hidden = false;
    }
    toggle.addEventListener('click', function () {
      section.classList.toggle('is-paused');
      updateToggle();
    });
    viewport.addEventListener('mouseenter', function () {
      hovered = true;
      updateToggle();
    });
    viewport.addEventListener('mouseleave', function () {
      hovered = false;
      updateToggle();
    });
    viewport.addEventListener('focusin', function () {
      focused = true;
      updateToggle();
    });
    viewport.addEventListener('focusout', function (event) {
      focused = viewport.contains(event.relatedTarget);
      updateToggle();
    });
    updateToggle();
    update();
    new ResizeObserver(update).observe(viewport);
  });
})();
