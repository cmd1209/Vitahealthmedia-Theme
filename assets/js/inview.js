(function () {
  const elements = document.querySelectorAll('.leistung-card, .quote-section__content, .contact, .vita-reveal');
  if (!elements.length || !('IntersectionObserver' in window)) return;
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  const observer = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (!entry.isIntersecting) return;

      entry.target.classList.add('is-in-view');
      observer.unobserve(entry.target);
    });
  }, { threshold: 0.15 });

  elements.forEach(function (element) {
    if (element.closest('.vita-reveal-off')) return;

    element.classList.add('inview-reveal');
    observer.observe(element);
  });
})();
