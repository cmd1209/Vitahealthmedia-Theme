(() => {
  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

  document.querySelectorAll('.hero-video').forEach(hero => {
    const video = hero.querySelector('.hero-video__media');
    const toggle = hero.querySelector('.hero-video__toggle');
    if (!video || !toggle) return;

    let manuallyPaused = false;
    const play = () => {
      // Delay the media request until the visitor's motion preference is known.
      if (!video.getAttribute('src')) video.src = video.dataset.src;
      video.muted = true;
      video.play().catch(() => {
        toggle.hidden = reducedMotion.matches || !!video.error;
        updateLabel();
      });
    };
    const updatePreference = () => {
      toggle.hidden = reducedMotion.matches;
      if (reducedMotion.matches || manuallyPaused) video.pause();
      else play();
    };
    const updateLabel = () => {
      toggle.textContent = video.paused ? toggle.dataset.playLabel : toggle.dataset.pauseLabel;
    };

    video.addEventListener('play', updateLabel);
    video.addEventListener('pause', updateLabel);
    video.addEventListener('error', () => { toggle.hidden = true; });
    toggle.addEventListener('click', () => {
      manuallyPaused = !video.paused;
      if (manuallyPaused) video.pause();
      else play();
    });
    reducedMotion.addEventListener('change', updatePreference);
    updatePreference();
    updateLabel();
  });
})();
