(() => {
  const header = document.querySelector('.navigation');
  if (!header) return;

  const toggle = header.querySelector('.navigation__toggle');
  const panel = header.querySelector('.navigation__panel');
  const mobile = window.matchMedia('(max-width: 960px)');
  const updateSticky = () => {
    // Separate thresholds by more than the logo's 33px height change so
    // scroll anchoring cannot immediately reverse the state we just set.
    const threshold = header.classList.contains('is-sticky') ? 32 : 80;
    header.classList.toggle('is-sticky', window.scrollY > threshold);
  };
  window.addEventListener('scroll', updateSticky, { passive: true });
  updateSticky();
  if (!toggle || !panel) return;

  let background = [];
  const setOpen = (open, restoreFocus = false) => {
    header.classList.toggle('is-open', open);
    document.documentElement.classList.toggle('navigation-open', open);
    toggle.setAttribute('aria-expanded', String(open));
    if (open) {
      // Preserve existing inert state while making the page behind the overlay unavailable.
      background = [...document.body.children]
        .filter(element => element !== header && !element.contains(header) && !element.inert);
      background.forEach(element => { element.inert = true; });
      panel.querySelector('a')?.focus();
    } else {
      background.forEach(element => { element.inert = false; });
      background = [];
      if (restoreFocus) toggle.focus();
    }
  };

  toggle.hidden = false;
  header.classList.add('is-enhanced');
  toggle.addEventListener('click', () => setOpen(!header.classList.contains('is-open')));
  panel.addEventListener('click', event => {
    if (event.target.closest('a') && header.classList.contains('is-open')) setOpen(false, true);
  });
  header.addEventListener('keydown', event => {
    if (!header.classList.contains('is-open')) return;
    if (event.key === 'Escape') {
      event.preventDefault();
      setOpen(false, true);
    }
    if (event.key === 'Tab') {
      const focusable = [...header.querySelectorAll('a[href], button:not([hidden])')];
      const first = focusable[0];
      const last = focusable[focusable.length - 1];
      if (event.shiftKey && document.activeElement === first) {
        event.preventDefault();
        last.focus();
      } else if (!event.shiftKey && document.activeElement === last) {
        event.preventDefault();
        first.focus();
      }
    }
  });
  mobile.addEventListener('change', () => {
    const focusWasInPanel = panel.contains(document.activeElement);
    setOpen(false);
    if (mobile.matches && focusWasInPanel) toggle.focus();
    else if (!mobile.matches && document.activeElement === toggle) panel.querySelector('a')?.focus();
  });
})();
