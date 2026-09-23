(function () {
  document.querySelectorAll('.project-slider').forEach(function (section) {
    const viewport = section.querySelector('.project-slider__viewport');
    const cards = Array.from(section.querySelectorAll('.project-card'));
    const controls = section.querySelector('.project-slider__controls');
    const previous = section.querySelector('.project-slider__previous');
    const next = section.querySelector('.project-slider__next');
    if (!viewport || !cards.length || !controls) return;
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

    function update() {
      const max = viewport.scrollWidth - viewport.clientWidth;
      controls.hidden = max <= 1;
      previous.setAttribute('aria-disabled', String(viewport.scrollLeft <= 1));
      next.setAttribute('aria-disabled', String(viewport.scrollLeft >= max - 1));
    }

    function move(direction) {
      const width = cards[0].getBoundingClientRect().width;
      const index = Math.round(viewport.scrollLeft / width) + direction;
      viewport.scrollTo({
        left: Math.max(0, Math.min(index * width, viewport.scrollWidth - viewport.clientWidth)),
        behavior: reducedMotion.matches ? 'instant' : 'smooth'
      });
    }

    previous.addEventListener('click', function () {
      if (previous.getAttribute('aria-disabled') !== 'true') move(-1);
    });
    next.addEventListener('click', function () {
      if (next.getAttribute('aria-disabled') !== 'true') move(1);
    });
    viewport.addEventListener('keydown', function (event) {
      if (event.target !== viewport || !['ArrowLeft', 'ArrowRight', 'Home', 'End'].includes(event.key)) return;
      event.preventDefault();
      if (event.key === 'Home' || event.key === 'End') {
        viewport.scrollTo({ left: event.key === 'Home' ? 0 : viewport.scrollWidth, behavior: 'instant' });
      } else {
        move(event.key === 'ArrowLeft' ? -1 : 1);
      }
    });
    viewport.addEventListener('scroll', update, { passive: true });
    new ResizeObserver(update).observe(viewport);
    update();
  });
})();
