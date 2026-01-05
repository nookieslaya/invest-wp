const modules = document.querySelectorAll('[data-investment-floors-map]');

const activateFloor = (targetId) => {
  if (!targetId) {
    return;
  }

  const floorplan = document.querySelector('[data-investment-floorplan]');
  if (!floorplan) {
    return;
  }

  const buildingId = targetId.split('-floor-')[0];
  const buildingTrigger = floorplan.querySelector(`[data-building-trigger="${buildingId}"]`);
  if (buildingTrigger) {
    buildingTrigger.click();
  }

  const floorTrigger = floorplan.querySelector(`[data-floor-trigger="${targetId}"]`);
  if (floorTrigger) {
    floorTrigger.click();
  }

  floorplan.scrollIntoView({ behavior: 'smooth', block: 'start' });
};

const buildTargetUrl = (investmentUrl, targetId, anchor) => {
  if (!investmentUrl) {
    return '';
  }

  const url = new URL(investmentUrl, window.location.origin);
  const match = targetId.match(/^building-(\d+)-floor-(\d+)$/);
  if (match) {
    url.searchParams.set('building', match[1]);
    url.searchParams.set('floor', match[2]);
  }

  if (anchor) {
    url.hash = anchor.startsWith('#') ? anchor : `#${anchor}`;
  }

  return url.toString();
};

const resolveTargetId = (element, buildingIndex) => {
  const directTarget = element.dataset.floorTarget;
  if (directTarget) {
    return directTarget;
  }

  const floorIndex = element.dataset.floorIndex;
  if (floorIndex !== undefined && floorIndex !== '') {
    return `building-${buildingIndex}-floor-${floorIndex}`;
  }

  const floorId = element.dataset.floorId;
  if (!floorId) {
    return '';
  }

  if (floorId.startsWith('building-')) {
    return floorId;
  }

  const match = floorId.match(/^floor-(\d+)$/);
  if (match) {
    const parsed = Number.parseInt(match[1], 10);
    if (!Number.isNaN(parsed)) {
      return `building-${buildingIndex}-floor-${Math.max(parsed - 1, 0)}`;
    }
  }

  if (/^\d+$/.test(floorId)) {
    return `building-${buildingIndex}-floor-${floorId}`;
  }

  return `building-${buildingIndex}-${floorId}`;
};

const ensureFocusable = (element) => {
  const tag = element.tagName ? element.tagName.toLowerCase() : '';
  if (tag === 'a' || tag === 'button') {
    return;
  }

  if (!element.hasAttribute('tabindex')) {
    element.setAttribute('tabindex', '0');
  }
};

modules.forEach((module) => {
  const buildingIndexRaw = module.dataset.buildingIndex || '0';
  const buildingIndex = Number.parseInt(buildingIndexRaw, 10) || 0;
  const investmentUrl = module.dataset.investmentUrl || '';
  const investmentAnchor = module.dataset.investmentAnchor || 'pietra';
  const floors = Array.from(
    module.querySelectorAll('[data-floor-target], [data-floor-index], [data-floor-id]')
  );

  floors.forEach((floor) => {
    ensureFocusable(floor);

    floor.addEventListener('click', () => {
      const targetId = resolveTargetId(floor, buildingIndex);
      floors.forEach((item) => item.classList.toggle('is-active', item === floor));
      if (document.querySelector('[data-investment-floorplan]')) {
        activateFloor(targetId);
        return;
      }

      const targetUrl = buildTargetUrl(investmentUrl, targetId, investmentAnchor);
      if (targetUrl) {
        window.location.href = targetUrl;
      }
    });

    floor.addEventListener('keydown', (event) => {
      if (event.key !== 'Enter' && event.key !== ' ') {
        return;
      }

      event.preventDefault();
      const targetId = resolveTargetId(floor, buildingIndex);
      floors.forEach((item) => item.classList.toggle('is-active', item === floor));
      if (document.querySelector('[data-investment-floorplan]')) {
        activateFloor(targetId);
        return;
      }

      const targetUrl = buildTargetUrl(investmentUrl, targetId, investmentAnchor);
      if (targetUrl) {
        window.location.href = targetUrl;
      }
    });
  });
});
