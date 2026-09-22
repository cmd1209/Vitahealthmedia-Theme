(function () {
  document.querySelectorAll('.partner-logos').forEach(function (section) {
    const viewport = section.querySelector('.partner-logos__viewport');
    const track = section.querySelector('.partner-logos__track');
    const original = section.querySelector('.partner-logos__set');
    const toggle = section.querySelector('.partner-logos__toggle');
    if (!original || !original.children.length) return;

    function update() {
      track.querySelectorAll('.partner-logos__clone').forEach(function (clone) { clone.remove(); });
      const width = original.getBoundingClientRect().width;
      if (!width) return;
      track.style.setProperty('--partner-loop-width', width + 'px');
      // Fill wide screens even when an editor selects only one or two logos.
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
      const paused = section.classList.toggle('is-paused');
      toggle.setAttribute('aria-pressed', String(paused));
      toggle.setAttribute('aria-label', paused ? toggle.dataset.play : toggle.dataset.pause);
      toggle.textContent = paused ? '▶' : 'Ⅱ';
    });
    update();
    new ResizeObserver(update).observe(viewport);
  });
})();
