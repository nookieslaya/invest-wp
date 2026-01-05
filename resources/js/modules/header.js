const initHeader = () => {
  const header = document.querySelector('.site-header');

  if (!header) {
    return;
  }

  const setScrolled = () => {
    header.setAttribute('data-scrolled', window.scrollY > 10 ? 'true' : 'false');
  };

  setScrolled();
  window.addEventListener('scroll', setScrolled, { passive: true });

  const toggle = header.querySelector('[data-menu-toggle]');
  const nav = header.querySelector('[data-primary-nav]');

  if (toggle && nav) {
    const mediaQuery = window.matchMedia('(min-width: 768px)');
    const submenuItems = Array.from(nav.querySelectorAll('.menu-item-has-children'));

    const closeAllSubmenus = (exception) => {
      submenuItems.forEach((item) => {
        if (exception && (item === exception || item.contains(exception))) {
          return;
        }

        const link = item.querySelector('a');
        const submenu = item.querySelector('ul');
        item.classList.remove('is-submenu-open');

        if (link) {
          link.setAttribute('aria-expanded', 'false');
        }

        if (submenu) {
          submenu.classList.add('hidden');
          submenu.classList.remove('flex');
          submenu.setAttribute('aria-hidden', 'true');
        }
      });
    };

    const setSubmenuState = (item, isOpen) => {
      const link = item.querySelector('a');
      const submenu = item.querySelector('ul');

      item.classList.toggle('is-submenu-open', isOpen);

      if (link) {
        link.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
      }

      if (submenu) {
        submenu.classList.toggle('hidden', !isOpen);
        submenu.classList.toggle('flex', isOpen);
        submenu.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
      }
    };

    submenuItems.forEach((item, index) => {
      const link = item.querySelector('a');
      const submenu = item.querySelector('ul');

      if (!link || !submenu) {
        return;
      }

      if (!submenu.id) {
        submenu.id = `submenu-${index + 1}`;
      }

      link.setAttribute('aria-expanded', 'false');
      link.setAttribute('aria-controls', submenu.id);
      submenu.setAttribute('aria-hidden', 'true');

      link.addEventListener('click', (event) => {
        if (mediaQuery.matches) {
          return;
        }

        event.preventDefault();
        const isOpen = item.classList.contains('is-submenu-open');
        if (isOpen) {
          setSubmenuState(item, false);
          return;
        }

        closeAllSubmenus(item);
        setSubmenuState(item, true);
      });
    });

    const setMenuState = (isOpen) => {
      nav.classList.toggle('hidden', !isOpen);
      toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
      header.setAttribute('data-menu-open', isOpen ? 'true' : 'false');

      if (!isOpen) {
        closeAllSubmenus();
      }
    };

    const handleResize = (event) => {
      if (event.matches) {
        nav.classList.remove('hidden');
        toggle.setAttribute('aria-expanded', 'false');
        header.setAttribute('data-menu-open', 'false');
        closeAllSubmenus();
        return;
      }

      setMenuState(false);
    };

    handleResize(mediaQuery);
    mediaQuery.addEventListener('change', handleResize);

    toggle.addEventListener('click', () => {
      const isHidden = nav.classList.contains('hidden');
      setMenuState(isHidden);
    });
  }
};

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initHeader);
} else {
  initHeader();
}
