const mapModules = document.querySelectorAll('[data-investments-module]');

const mapStatusLabels = {
  w_sprzedazy: 'W sprzedaży',
  zrealizowane: 'Zrealizowane',
  w_przygotowaniu: 'W przygotowaniu',
};

const mapStatusColors = {
  w_sprzedazy: '#2f766a',
  zrealizowane: '#3d5a80',
  w_przygotowaniu: '#b07d3c',
};

const buildPinIcon = (color) => {
  const svg = `
    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40">
      <path d="M20 2c-6.6 0-12 5.4-12 12 0 9.4 12 24 12 24s12-14.6 12-24c0-6.6-5.4-12-12-12z" fill="${color}"/>
      <circle cx="20" cy="14" r="5" fill="white" fill-opacity="0.9"/>
    </svg>
  `;

  const encoded = encodeURIComponent(svg).replace(/%0A/g, '');
  return `data:image/svg+xml;charset=UTF-8,${encoded}`;
};

const mapLoader = (() => {
  let promise;

  return (apiKey) => {
    if (window.google && window.google.maps) {
      return Promise.resolve(window.google.maps);
    }

    if (!apiKey) {
      return Promise.reject(new Error('Missing Google Maps API key'));
    }

    if (promise) {
      return promise;
    }

    promise = new Promise((resolve, reject) => {
      const script = document.createElement('script');
      script.src = `https://maps.googleapis.com/maps/api/js?key=${encodeURIComponent(apiKey)}`;
      script.async = true;
      script.defer = true;
      script.onload = () => resolve(window.google.maps);
      script.onerror = () => reject(new Error('Failed to load Google Maps'));
      document.head.appendChild(script);
    });

    return promise;
  };
})();

const createPopupHtml = (investment) => {
  const imageHtml = investment.image
    ? `<div class="relative">
        <img class="map-popup__image h-32 w-full object-cover" src="${investment.image}" alt="">
        <button class="js-map-close absolute right-2 top-2 inline-flex h-7 w-7 items-center justify-center rounded-full bg-black/60 text-white" type="button" aria-label="Zamknij">
          <svg width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true">
            <path d="M2 2l8 8M10 2L2 10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
          </svg>
        </button>
      </div>`
    : '';

  const buttonHtml = investment.link_url
    ? `<a class="mt-3 !no-underline inline-flex items-center justify-center rounded-full bg-slate-900 px-4 py-2 text-xs font-semibold text-white no-underline" href="${investment.link_url}" ${investment.link_target ? `target="${investment.link_target}"` : ''}>${investment.link_title || 'Zobacz'}</a>`
    : '';

  const statusLabel = investment.status_label || mapStatusLabels[investment.status] || '';
  const statusColor = mapStatusColors[investment.status] || '#475569';

  return `
    <div class="map-popup w-60 font-sans text-slate-900">
      ${imageHtml}
      <div class="map-popup__body space-y-3">
        <div class="flex flex-wrap justify-between gap-2">
          <p class="text-sm font-semibold">${investment.name || 'Inwestycja'}</p>
          ${statusLabel ? `<span class="text-xs font-semibold" style="color:${statusColor}">${statusLabel}</span>` : ''}
        </div>
        ${buttonHtml}
      </div>
    </div>
  `;
};

const initMapModule = (module) => {
  const mapEl = module.querySelector('[data-investments-map]');
  const filters = module.querySelectorAll('[data-investment-filter]');
  const messageEl = mapEl ? mapEl.querySelector('[data-map-message]') : null;

  if (!mapEl) {
    return;
  }

  let investments = [];

  try {
    const encoded = mapEl.dataset.investments || '';
    if (encoded) {
      investments = JSON.parse(atob(encoded));
    }
  } catch (error) {
    investments = [];
  }

  if (!investments.length) {
    if (messageEl) {
      messageEl.textContent = 'Brak inwestycji do wyswietlenia.';
    }
    return;
  }

  const apiKey = mapEl.dataset.apiKey || '';

  mapLoader(apiKey)
    .then(() => {
      const center = {
        lat: investments[0].lat || 52.2297,
        lng: investments[0].lng || 21.0122,
      };

      const map = new window.google.maps.Map(mapEl, {
        center,
        zoom: 10,
        mapTypeControl: false,
        streetViewControl: false,
      });

      const infoWindow = new window.google.maps.InfoWindow();

      const markers = investments.map((investment) => {
        const iconColor = mapStatusColors[investment.status] || '#dc2626';
        const marker = new window.google.maps.Marker({
          position: { lat: investment.lat, lng: investment.lng },
          map,
          icon: {
            url: buildPinIcon(iconColor),
            scaledSize: new window.google.maps.Size(32, 32),
            anchor: new window.google.maps.Point(16, 32),
          },
        });

        marker.investmentStatus = investment.status;

        marker.addListener('click', () => {
          infoWindow.setContent(createPopupHtml(investment));
          infoWindow.open(map, marker);
        });

        return marker;
      });

      const updateMarkers = () => {
        const activeStatuses = new Set(
          Array.from(filters)
            .filter((input) => input.checked)
            .map((input) => input.value)
        );

        markers.forEach((marker) => {
          const shouldShow = activeStatuses.has(marker.investmentStatus);
          marker.setMap(shouldShow ? map : null);
        });
      };

      filters.forEach((input) => {
        input.addEventListener('change', updateMarkers);
      });

      updateMarkers();

      window.google.maps.event.addListener(infoWindow, 'domready', () => {
        const closeButton = document.querySelector('.js-map-close');
        if (closeButton) {
          closeButton.addEventListener('click', () => infoWindow.close(), { once: true });
        }
      });

      if (messageEl) {
        messageEl.remove();
      }
    })
    .catch((error) => {
      if (messageEl) {
        messageEl.textContent = error.message || 'Mapa nie jest gotowa.';
      }
    });
};

mapModules.forEach(initMapModule);
