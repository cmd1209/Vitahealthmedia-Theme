(function () {
  const archive = document.querySelector('.project-archive');
  if (!archive) {
    return;
  }

  const content = archive.querySelector('.project-archive__content');
  const status = archive.querySelector('.project-archive__status');
  let activeRequest = null;

  async function loadPage(url, navigationType) {
    if (activeRequest) {
      activeRequest.abort();
    }

    const request = new AbortController();
    activeRequest = request;
    content.setAttribute('aria-busy', 'true');

    try {
      const response = await fetch(url, {
        credentials: 'same-origin',
        signal: request.signal,
      });
      if (!response.ok) {
        throw new Error('Could not load projects');
      }

      const page = new DOMParser().parseFromString(await response.text(), 'text/html');
      const nextContent = page.querySelector('.project-archive__content');
      if (!nextContent || activeRequest !== request) {
        throw new Error('Project archive was not found');
      }

      content.innerHTML = nextContent.innerHTML;
      if (navigationType !== 'history') {
        window.history.pushState({ projectArchive: true }, '', url);
      }
      if (window.lucide) {
        window.lucide.createIcons();
      }

      const count = content.querySelectorAll('.project-archive__grid .project-card').length;
      status.textContent = count === 1 ? '1 Projekt angezeigt.' : count + ' Projekte angezeigt.';

      if (navigationType === 'page') {
        content.scrollIntoView({ block: 'start' });
        content.focus({ preventScroll: true });
      } else if (navigationType === 'filter') {
        const selectedFilter = content.querySelector('.project-archive__filter.is-active');
        if (selectedFilter) {
          selectedFilter.focus({ preventScroll: true });
        }
      }
    } catch (error) {
      if (error.name !== 'AbortError' && activeRequest === request) {
        window.location.assign(url);
      }
    } finally {
      if (activeRequest === request) {
        activeRequest = null;
        content.removeAttribute('aria-busy');
      }
    }
  }

  content.addEventListener('click', function (event) {
    const link = event.target.closest('.project-archive__filters a, .project-archive__pagination a');
    if (!link || event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
      return;
    }

    const url = new URL(link.href, window.location.href);
    if (url.origin !== window.location.origin || link.target || link.hasAttribute('download')) {
      return;
    }

    event.preventDefault();
    loadPage(url.href, link.closest('.project-archive__pagination') ? 'page' : 'filter');
  });

  window.addEventListener('popstate', function () {
    loadPage(window.location.href, 'history');
  });
})();
