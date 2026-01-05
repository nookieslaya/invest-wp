const modules = document.querySelectorAll('[data-investment-floorplan]');

const placeholder = '--';
const statusLabels = {
  available: 'Wolne',
  reserved: 'Rezerwacja',
  sold: 'Sprzedane',
};

const hasValue = (value) => value !== null && value !== undefined && value !== '';
const formatText = (value) => (hasValue(value) ? value : placeholder);
const formatMoney = (value) => {
  if (typeof value !== 'number' || Number.isNaN(value)) {
    return placeholder;
  }

  return `${Math.round(value).toLocaleString('pl-PL')} PLN`;
};
const formatPerM2 = (value) => {
  if (typeof value !== 'number' || Number.isNaN(value)) {
    return placeholder;
  }

  return `${Math.round(value).toLocaleString('pl-PL')} PLN/m2`;
};

const parseIndex = (value) => {
  if (value === null || value === undefined || value === '') {
    return null;
  }

  const parsed = Number.parseInt(value, 10);
  return Number.isNaN(parsed) ? null : parsed;
};

const searchParams = new URLSearchParams(window.location.search);
const requestedBuilding = parseIndex(searchParams.get('building') ?? searchParams.get('budynek'));
const requestedFloor = parseIndex(searchParams.get('floor') ?? searchParams.get('pietro'));
const requestedBuildingId = requestedBuilding !== null ? `building-${requestedBuilding}` : null;
const requestedTargetId =
  requestedBuilding !== null && requestedFloor !== null
    ? `building-${requestedBuilding}-floor-${requestedFloor}`
    : null;

const initFloorPanel = (panel) => {
  if (panel.dataset.floorInit === 'true') {
    return;
  }

  const svg = panel.querySelector('svg');
  if (!svg) {
    return;
  }

  panel.dataset.floorInit = 'true';

  let apartmentMap = {};
  const encoded = panel.dataset.apartments || '';
  if (encoded) {
    try {
      apartmentMap = JSON.parse(atob(encoded));
    } catch (error) {
      apartmentMap = {};
    }
  }

  const units = Array.from(svg.querySelectorAll('[data-apartment-id]'));
  const tooltipHost = panel.querySelector('.floorplan-wrapper') || panel;
  let tooltip = tooltipHost.querySelector('.apartment-tooltip');

  if (!tooltip) {
    tooltip = document.createElement('div');
    tooltip.className = 'apartment-tooltip';
    tooltipHost.appendChild(tooltip);
  }

  const setActive = (apartmentId) => {
    units.forEach((unit) => unit.classList.toggle('is-active', unit.dataset.apartmentId === apartmentId));
  };

  const moveTooltip = (event, target) => {
    if (!tooltip || !tooltipHost) {
      return;
    }
    const rect = tooltipHost.getBoundingClientRect();
    let x = null;
    let y = null;

    if (event && typeof event.clientX === 'number' && typeof event.clientY === 'number') {
      x = event.clientX - rect.left + 16;
      y = event.clientY - rect.top + 16;
    } else if (target && target.getBoundingClientRect) {
      const targetRect = target.getBoundingClientRect();
      x = targetRect.left - rect.left + targetRect.width + 12;
      y = targetRect.top - rect.top;
    }

    if (x === null || y === null) {
      return;
    }

    tooltip.style.setProperty('--tooltip-x', `${x}px`);
    tooltip.style.setProperty('--tooltip-y', `${y}px`);
  };

  const createRow = (label, value) => {
    const row = document.createElement('div');
    row.className = 'apartment-tooltip__row';

    const labelEl = document.createElement('span');
    labelEl.className = 'apartment-tooltip__label';
    labelEl.textContent = label;

    const valueEl = document.createElement('span');
    valueEl.className = 'apartment-tooltip__value';
    valueEl.textContent = value;

    row.appendChild(labelEl);
    row.appendChild(valueEl);

    return row;
  };

  const createStatusRow = (label, status) => {
    const row = document.createElement('div');
    row.className = 'apartment-tooltip__row';

    const labelEl = document.createElement('span');
    labelEl.className = 'apartment-tooltip__label';
    labelEl.textContent = label;

    const statusEl = document.createElement('span');
    statusEl.className = 'investment-status apartment-tooltip__status';
    statusEl.dataset.status = status || '';
    statusEl.textContent = statusLabels[status] || status || placeholder;

    row.appendChild(labelEl);
    row.appendChild(statusEl);

    return row;
  };

  const renderTooltip = (data) => {
    if (!tooltip) {
      return;
    }

    tooltip.innerHTML = '';

    const title = document.createElement('p');
    title.className = 'apartment-tooltip__title';
    title.textContent = data.label || 'Mieszkanie';

    const areaText = formatText(data.area);
    const areaDisplay = areaText !== placeholder && !String(areaText).includes('m2') ? `${areaText} m2` : areaText;
    const roomsDisplay = formatText(data.rooms);

    const meta = document.createElement('p');
    meta.className = 'apartment-tooltip__meta';
    // meta.textContent = `${areaDisplay} ? ${roomsDisplay} pokoje`;

    const grid = document.createElement('div');
    grid.className = 'apartment-tooltip__grid';
    grid.appendChild(createRow('Metraz', areaDisplay));
    grid.appendChild(createRow('Pokoje', roomsDisplay));
    grid.appendChild(createRow('Cena', formatMoney(data.price_current ?? data.price)));
    grid.appendChild(createRow('Cena za m2', formatPerM2(data.price_per_m2)));

    if (data.promo || hasValue(data.promo_label)) {
      grid.appendChild(createRow('Promocja', data.promo_label || 'Promocja'));
    }

    if (hasValue(data.price_lowest_30) || data.promo) {
      grid.appendChild(createRow('Najnizsza 30 dni', formatMoney(data.price_lowest_30)));
    }

    const extras = Array.isArray(data.extras) ? data.extras : [];
    const extrasTotal = extras.length > 0 ? formatMoney(data.extras_total) : placeholder;
    grid.appendChild(createRow('Koszty dodatkowe', extrasTotal));

    grid.appendChild(createStatusRow('Status', data.status));

    tooltip.appendChild(title);
    tooltip.appendChild(meta);
    tooltip.appendChild(grid);

    if (extras.length > 0) {
      const extrasTitle = document.createElement('p');
      extrasTitle.className = 'apartment-tooltip__section';
      extrasTitle.textContent = 'Koszty dodatkowe';
      tooltip.appendChild(extrasTitle);

      const extrasList = document.createElement('ul');
      extrasList.className = 'apartment-tooltip__list';
      extras.forEach((extra) => {
        const li = document.createElement('li');
        const label = extra.label ? `${extra.label}: ` : '';
        li.textContent = `${label}${formatMoney(extra.price)}`;
        extrasList.appendChild(li);
      });
      tooltip.appendChild(extrasList);
    }

    const history = Array.isArray(data.history) ? data.history : [];
    if (history.length > 0) {
      const historyTitle = document.createElement('p');
      historyTitle.className = 'apartment-tooltip__section';
      historyTitle.textContent = 'Historia cen';
      tooltip.appendChild(historyTitle);

      const historyList = document.createElement('ul');
      historyList.className = 'apartment-tooltip__list';
      history.forEach((entry) => {
        const li = document.createElement('li');
        const date = entry.date ? `${entry.date}: ` : '';
        li.textContent = `${date}${formatMoney(entry.price)}`;
        historyList.appendChild(li);
      });
      tooltip.appendChild(historyList);
    }

    const hint = document.createElement('p');
    hint.className = 'apartment-tooltip__hint';
    hint.textContent = 'Kliknij, aby zobaczyc';
    tooltip.appendChild(hint);

    tooltip.classList.add('is-visible');
  };

  const hideTooltip = () => {
    if (tooltip) {
      tooltip.classList.remove('is-visible');
    }
  };

  units.forEach((unit) => {
    const apartmentId = unit.dataset.apartmentId;
    if (!unit.hasAttribute('tabindex')) {
      unit.setAttribute('tabindex', '0');
    }

    const unitData = apartmentMap[apartmentId];
    if (unitData && unitData.status) {
      unit.dataset.status = unitData.status;
    } else {
      unit.dataset.status = '';
    }

    unit.addEventListener('mouseenter', (event) => {
      const data = apartmentMap[apartmentId];
      setActive(apartmentId);
      if (data) {
        renderTooltip(data);
        moveTooltip(event);
      }
    });
    unit.addEventListener('mousemove', moveTooltip);
    unit.addEventListener('mouseleave', () => {
      hideTooltip();
      setActive(null);
    });
    unit.addEventListener('focus', (event) => {
      const data = apartmentMap[apartmentId];
      setActive(apartmentId);
      if (data) {
        renderTooltip(data);
        moveTooltip(event, unit);
      }
    });
    unit.addEventListener('blur', () => {
      hideTooltip();
      setActive(null);
    });
    unit.addEventListener('click', () => {
      const data = apartmentMap[apartmentId];
      if (data && data.link) {
        window.location.href = data.link;
      }
    });
  });

  if (units.length > 0) {
    setActive(units[0].dataset.apartmentId);
  }
};


const initBuilding = (module) => {
  const buildingTriggers = Array.from(module.querySelectorAll('[data-building-trigger]'));
  const buildingPanels = Array.from(module.querySelectorAll('[data-building-panel]'));

  const setFloorView = (panel, view) => {
    const viewPanels = Array.from(panel.querySelectorAll('[data-floor-view]'));
    const toggleButtons = Array.from(panel.querySelectorAll('[data-floor-view-toggle]'));

    viewPanels.forEach((viewPanel) => {
      viewPanel.classList.toggle('hidden', viewPanel.dataset.floorView !== view);
    });

    toggleButtons.forEach((button) => {
      button.classList.toggle('is-active', button.dataset.floorViewToggle === view);
    });

    if (view === 'plan') {
      initFloorPanel(panel);
    }

    module.classList.toggle('is-table-view', view === 'table');
  };

  const setActiveBuilding = (buildingId, floorOverride = null) => {
    buildingTriggers.forEach((trigger) => {
      trigger.classList.toggle('is-active', trigger.dataset.buildingTrigger === buildingId);
    });

    buildingPanels.forEach((panel) => {
      const isActive = panel.dataset.buildingPanel === buildingId;
      panel.classList.toggle('hidden', !isActive);
      if (isActive) {
        const floors = panel.querySelectorAll('[data-floor-panel]');
        const triggers = panel.querySelectorAll('[data-floor-trigger]');
        const firstFloorId =
          floorOverride || triggers[0]?.dataset.floorTrigger || floors[0]?.dataset.floorPanel;
        if (firstFloorId) {
          setActiveFloor(panel, firstFloorId);
        }
      }
    });
  };

  const setActiveFloor = (panel, floorId) => {
    const floorTriggers = Array.from(panel.querySelectorAll('[data-floor-trigger]'));
    const floorPanels = Array.from(panel.querySelectorAll('[data-floor-panel]'));

    floorTriggers.forEach((trigger) => {
      trigger.classList.toggle('is-active', trigger.dataset.floorTrigger === floorId);
    });

    floorPanels.forEach((floorPanel) => {
      const isActive = floorPanel.dataset.floorPanel === floorId;
      floorPanel.classList.toggle('hidden', !isActive);
      if (isActive) {
        const floorView = floorPanel.dataset.floorViewDefault || 'plan';
        setFloorView(floorPanel, floorView);
      }
    });
  };

  buildingPanels.forEach((panel) => {
    const floorTriggers = Array.from(panel.querySelectorAll('[data-floor-trigger]'));
    floorTriggers.forEach((trigger) => {
      trigger.addEventListener('click', () => setActiveFloor(panel, trigger.dataset.floorTrigger));
    });

    const viewToggles = Array.from(panel.querySelectorAll('[data-floor-view-toggle]'));
    viewToggles.forEach((toggle) => {
      toggle.addEventListener('click', () => {
        const floorPanel = toggle.closest('[data-floor-panel]');
        if (!floorPanel) {
          return;
        }

        setFloorView(floorPanel, toggle.dataset.floorViewToggle);
      });
    });
  });

  buildingTriggers.forEach((trigger) => {
    trigger.addEventListener('click', () => setActiveBuilding(trigger.dataset.buildingTrigger));
  });

  const firstBuildingId =
    buildingTriggers[0]?.dataset.buildingTrigger ||
    buildingPanels[0]?.dataset.buildingPanel;
  const preferredBuildingId = requestedBuildingId || firstBuildingId;
  if (preferredBuildingId) {
    const floorOverride =
      preferredBuildingId === requestedBuildingId ? requestedTargetId : null;
    setActiveBuilding(preferredBuildingId, floorOverride);
  }
};

modules.forEach(initBuilding);


