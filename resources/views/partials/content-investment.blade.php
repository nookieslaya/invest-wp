@php
  $subtitle = function_exists('get_field') ? (get_field('investment_subtitle') ?: '') : '';
  $intro = function_exists('get_field') ? (get_field('investment_intro') ?: '') : '';
  $buildings = function_exists('get_field') ? (get_field('buildings') ?: []) : [];
  $buildings = is_array($buildings) ? $buildings : [];
  $demoSvgPath = get_theme_file_path('resources/svg/floorplan-demo.svg');
  $demoSvg = is_file($demoSvgPath) ? file_get_contents($demoSvgPath) : '';
  $requestedApartment = get_query_var('mieszkanie');
  if (! $requestedApartment && isset($_GET['mieszkanie'])) {
    $requestedApartment = sanitize_text_field(wp_unslash($_GET['mieszkanie']));
  }
  if (! $requestedApartment && isset($_SERVER['REQUEST_URI'])) {
    $path = wp_parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $parts = array_values(array_filter(explode('/', trim((string) $path, '/'))));
    $index = array_search('mieszkanie', $parts, true);
    if ($index !== false && isset($parts[$index + 1])) {
      $requestedApartment = sanitize_text_field(rawurldecode($parts[$index + 1]));
    }
  }
  $investmentLink = get_permalink();
  $buildApartmentLink = function ($svgId) use ($investmentLink) {
    if (! $svgId) {
      return '';
    }

    return add_query_arg('mieszkanie', $svgId, $investmentLink).'#mieszkanie';
  };
  $selectedApartment = null;
  if ($requestedApartment) {
    foreach ($buildings as $building) {
      $buildingName = $building['building_name'] ?? '';
      $floors = $building['floors'] ?? [];
      $floors = is_array($floors) ? $floors : [];

      foreach ($floors as $floor) {
        $floorLabel = $floor['floor_label'] ?? '';
        $floorImage = $floor['floor_image'] ?? null;
        $floorImageUrl = is_array($floorImage) ? ($floorImage['url'] ?? '') : '';
        $apartments = $floor['apartments'] ?? [];
        $apartments = is_array($apartments) ? $apartments : [];

        foreach ($apartments as $apartment) {
          $svgId = $apartment['apartment_svg_id'] ?? '';
          if ($svgId !== $requestedApartment) {
            continue;
          }

          $floorplan = $apartment['apartment_floorplan'] ?? null;
          $floorplanUrl = is_array($floorplan) ? ($floorplan['url'] ?? '') : '';
          $roomsList = $apartment['apartment_room_list'] ?? [];
          $roomsList = is_array($roomsList) ? $roomsList : [];
          $roomsTotal = 0;
          foreach ($roomsList as $room) {
            $roomArea = $room['room_area'] ?? null;
            if (is_numeric($roomArea)) {
              $roomsTotal += (float) $roomArea;
            }
          }

          $cardFile = $apartment['apartment_card_file'] ?? null;
          $cardUrl = is_array($cardFile) ? ($cardFile['url'] ?? '') : '';
          $cardLabel = is_array($cardFile) ? ($cardFile['title'] ?? '') : '';

          $status = $apartment['apartment_status'] ?? '';
          $statusLabel = $status === 'available' ? 'Wolne' : ($status === 'reserved' ? 'Rezerwacja' : ($status === 'sold' ? 'Sprzedane' : ''));

          $selectedApartment = [
            'label' => $apartment['apartment_label'] ?? '',
            'area' => $apartment['apartment_area'] ?? '',
            'rooms' => $apartment['apartment_rooms'] ?? '',
            'building' => $buildingName,
            'floor' => $floorLabel,
            'status' => $statusLabel,
            'floorplan_url' => $floorplanUrl,
            'rooms_list' => $roomsList,
            'rooms_total' => $roomsTotal,
            'card_url' => $cardUrl,
            'card_label' => $cardLabel,
          ];

          break 3;
        }
      }
    }
  }
  $placeholder = '--';
  $parseNumber = function ($value) {
    if ($value === null || $value === '') {
      return null;
    }

    if (is_numeric($value)) {
      return (float) $value;
    }

    if (! is_string($value)) {
      return null;
    }

    $clean = str_replace([' ', ','], ['', '.'], $value);
    $clean = preg_replace('/[^0-9.\-]/', '', $clean);
    if ($clean === '' || ! is_numeric($clean)) {
      return null;
    }

    return (float) $clean;
  };
  $formatMoney = function ($value) use ($placeholder) {
    if (! is_numeric($value)) {
      return $placeholder;
    }

    return number_format((float) round($value), 0, '.', ' ') . ' PLN';
  };
  $formatDate = function ($value) {
    if (! $value) {
      return '';
    }

    $date = date_create($value);
    if (! $date) {
      return (string) $value;
    }

    return $date->format('d.m.Y');
  };
  $calculatePricePerM2 = function ($price, $area) {
    if ($price === null || ! $area) {
      return null;
    }

    return round($price / $area);
  };
  $buildExtras = function ($extraCosts) use ($parseNumber) {
    $items = [];
    $total = 0;
    $extraCosts = is_array($extraCosts) ? $extraCosts : [];

    foreach ($extraCosts as $extra) {
      $label = trim((string) ($extra['extra_label'] ?? ''));
      $price = $parseNumber($extra['extra_price'] ?? null);

      if ($label === '' && $price === null) {
        continue;
      }

      $items[] = [
        'label' => $label,
        'price' => $price,
      ];

      if ($price !== null) {
        $total += $price;
      }
    }

    return [
      'items' => $items,
      'total' => $total,
    ];
  };
  $buildHistory = function ($historyEntries) use ($parseNumber, $formatDate) {
    $items = [];
    $historyEntries = is_array($historyEntries) ? $historyEntries : [];

    foreach ($historyEntries as $entry) {
      $date = $formatDate($entry['history_date'] ?? '');
      $price = $parseNumber($entry['history_price'] ?? null);

      if ($date === '' && $price === null) {
        continue;
      }

      $items[] = [
        'date' => $date,
        'price' => $price,
      ];
    }

    return $items;
  };
  $filterHistory = function ($historyItems, $currentPrice) {
    if ($currentPrice === null || ! is_array($historyItems)) {
      return is_array($historyItems) ? $historyItems : [];
    }

    $filtered = [];
    foreach ($historyItems as $entry) {
      $price = $entry['price'] ?? null;
      if ($price === null) {
        $filtered[] = $entry;
        continue;
      }
      if (abs($price - $currentPrice) < 0.01) {
        continue;
      }
      $filtered[] = $entry;
    }

    return $filtered;
  };

@endphp

<article class="investment-single">
  @if (! $selectedApartment)
    <header class="container-main space-y-4 py-12">
      <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Inwestycja</p>
      <h1 class="text-3xl font-semibold text-slate-900 md:text-5xl">
        {!! $title !!}
      </h1>
      @if ($subtitle)
        <p class="text-lg font-semibold text-slate-700">{{ $subtitle }}</p>
      @endif
      @if ($intro)
        <p class="max-w-2xl text-base text-slate-600 md:text-lg">{{ $intro }}</p>
      @endif
    </header>
  @endif

  @if ($selectedApartment)
    <section class="container-main border-b border-slate-200 pb-10" id="mieszkanie">
      <nav class="text-xs text-slate-500" aria-label="Breadcrumb">
        <ol class="flex flex-wrap items-center gap-2">
          <li class="flex items-center gap-2">
            <a class="hover:text-slate-700" href="{{ home_url('/') }}">{{ __('Home', 'sage') }}</a>
            <span class="text-slate-400">/</span>
          </li>
          <li class="flex items-center gap-2">
            <a class="hover:text-slate-700" href="{{ get_post_type_archive_link('investment') }}">{{ __('Inwestycje', 'sage') }}</a>
            <span class="text-slate-400">/</span>
          </li>
          <li class="flex items-center gap-2">
            <a class="hover:text-slate-700" href="{{ get_permalink() }}">{{ get_the_title() }}</a>
            <span class="text-slate-400">/</span>
          </li>
          <li class="flex items-center gap-2">
            <span class="text-slate-700">{{ $selectedApartment['label'] ?: __('Mieszkanie', 'sage') }}</span>
          </li>
        </ol>
      </nav>

      <div class="mt-6 flex flex-wrap justify-between gap-6">
        <div class="flex items-center gap-4">
          @if (has_post_thumbnail())
            <img class="h-16 w-16 rounded-2xl object-cover" src="{{ get_the_post_thumbnail_url(null, 'thumbnail') }}" alt="{{ get_the_title() }}">
          @else
            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
              B
            </div>
          @endif
          <div class="space-y-1">
            @if ($selectedApartment['building'])
              <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">{{ $selectedApartment['building'] }}</p>
            @endif
            <h2 class="text-2xl font-semibold text-slate-900 md:text-3xl">{{ $selectedApartment['label'] ?: __('Mieszkanie', 'sage') }}</h2>
          </div>
        </div>

        <div class="flex flex-wrap items-center gap-6 text-sm text-slate-600">
          <div class="flex items-center gap-2">
            <span class="text-xs uppercase tracking-[0.2em] text-slate-400">Metraz</span>
            <span class="font-semibold text-slate-900">{{ $selectedApartment['area'] ?: $placeholder }}{{ $selectedApartment['area'] ? ' m2' : '' }}</span>
          </div>
          <div class="flex items-center gap-2">
            <span class="text-xs uppercase tracking-[0.2em] text-slate-400">Status</span>
            <span class="font-semibold text-slate-900">{{ $selectedApartment['status'] ?: $placeholder }}</span>
          </div>
        </div>
      </div>

      <div class="mt-8 grid items-start gap-8 lg:grid-cols-[1.2fr_0.8fr]">
        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
          @if ($selectedApartment['floorplan_url'])
            <img class="w-full rounded-2xl border border-slate-200 bg-slate-50" src="{{ $selectedApartment['floorplan_url'] }}" alt="{{ $selectedApartment['label'] ?: __('Mieszkanie', 'sage') }}">
          @else
            <div class="flex min-h-[240px] items-center justify-center rounded-2xl border border-dashed border-slate-200 bg-slate-50 text-sm text-slate-500">
              Brak rzutu mieszkania.
            </div>
          @endif
        </div>

        <div class="self-start rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
          <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Pomieszczenia</p>
          <div class="mt-6 space-y-4 text-sm text-slate-700">
            @if (count($selectedApartment['rooms_list']) > 0)
              @foreach ($selectedApartment['rooms_list'] as $room)
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                  <span>{{ $room['room_name'] ?? 'Pomieszczenie' }}</span>
                  <span class="font-semibold text-slate-900">
                    {{ $room['room_area'] ?? $placeholder }}{{ isset($room['room_area']) && $room['room_area'] !== '' ? ' m2' : '' }}
                  </span>
                </div>
              @endforeach
            @else
              <p class="text-sm text-slate-500">Brak dodanych pomieszczen.</p>
            @endif
          </div>

          <div class="mt-6 border-t border-slate-200 pt-4 text-sm text-slate-700">
            <div class="flex items-center justify-between font-semibold text-slate-900">
              <span>Suma</span>
              <span>
                @if ($selectedApartment['rooms_total'] > 0)
                  {{ $selectedApartment['rooms_total'] }} m2
                @elseif ($selectedApartment['area'])
                  {{ $selectedApartment['area'] }} m2
                @else
                  {{ $placeholder }}
                @endif
              </span>
            </div>
          </div>

          <div class="mt-6">
            <a class="floor-view-button inline-flex !no-underline" href="#zapytaj">
              {{ __('Zapytaj o mieszkanie', 'sage') }}
            </a>
          </div>

          @if ($selectedApartment['card_url'])
            <div class="mt-4">
              <a class="floor-view-button inline-flex !no-underline" href="{{ $selectedApartment['card_url'] }}" download>
                {{ "Pobierz kartę lokalu" ?: __('Karta lokalu', 'sage') }}
              </a>
            </div>
          @endif
        </div>
      </div>
    </section>

    @php
      $officePhone = function_exists('get_field') ? (get_field('phone_number', 'option') ?: '') : '';
      $officeAddress = function_exists('get_field') ? (get_field('footer_address', 'option') ?: '') : '';
      $officeHours = function_exists('get_field') ? (get_field('footer_hours', 'option') ?: '') : '';
      $investmentTitle = get_the_title();
      $apartmentLabel = $selectedApartment['label'] ?: __('Mieszkanie', 'sage');
      $apartmentInfoParts = array_filter([
        $investmentTitle ? 'Inwestycja: '.$investmentTitle : '',
        $selectedApartment['building'] ? 'Budynek: '.$selectedApartment['building'] : '',
        $selectedApartment['floor'] ? 'Pietro: '.$selectedApartment['floor'] : '',
        $apartmentLabel ? 'Lokal: '.$apartmentLabel : '',
        $selectedApartment['area'] ? 'Metraz: '.$selectedApartment['area'].' m2' : '',
      ]);
      $apartmentInfo = implode(' | ', $apartmentInfoParts);
      $defaultMessage = 'Prosze o kontakt w sprawie inwestycji. '.$apartmentInfo;
      $currentUrl = isset($_SERVER['REQUEST_URI']) ? home_url($_SERVER['REQUEST_URI']) : get_permalink();
      $contactStatus = isset($_GET['contact']) ? sanitize_text_field(wp_unslash($_GET['contact'])) : '';
    @endphp

    <section class="container-main border-b border-slate-200 py-12" id="zapytaj">
      
      <div class="grid gap-10 lg:grid-cols-[minmax(0,0.9fr)_minmax(0,1.1fr)] lg:items-stretch">
        <div class="contact-panel h-full">
        <div class="contact-panel__content space-y-5">
          <p class="contact-panel__eyebrow">Biuro sprzedazy</p>
          @if ($officePhone)
            <p class="contact-panel__link">
              <a class="hover:text-slate-700" href="tel:{{ preg_replace('/\\s+/', '', $officePhone) }}">{{ $officePhone }}</a>
            </p>
          @endif
          @if ($officeAddress)
            <div class="contact-panel__text">{!! nl2br(e($officeAddress)) !!}</div>
          @endif
          @if ($officeHours)
            <div class="contact-panel__text">{!! nl2br(e($officeHours)) !!}</div>
          @endif
        </div>
        </div>

        <div class="contact-panel">
          <div class="contact-panel__content">
            <h3 class="contact-panel__title">{{ __('Formularz kontaktowy', 'sage') }}</h3>
            <p class="mt-2 contact-panel__text">{{ __('Prosze zostawic dane, a oddzwonimy.', 'sage') }}</p>

          @if ($contactStatus === 'sent')
            <div class="mt-4 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">
              {{ __('Dziekujemy. Wiadomosc zostala wyslana.', 'sage') }}
            </div>
          @elseif ($contactStatus === 'error')
            <div class="mt-4 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
              {{ __('Nie udalo sie wyslac formularza. Sprobuj ponownie.', 'sage') }}
            </div>
          @endif

            <form class="mt-6 grid gap-4" action="{{ esc_url(admin_url('admin-post.php')) }}" method="post">
            @php
              wp_nonce_field('investment_contact', 'investment_contact_nonce');
            @endphp
            <input type="hidden" name="action" value="investment_contact">
            <input type="hidden" name="redirect" value="{{ esc_url($currentUrl) }}">
            <input type="hidden" name="redirect_anchor" value="zapytaj">
            <input type="hidden" name="investment_title" value="{{ esc_attr($investmentTitle) }}">
            <input type="hidden" name="apartment_info" value="{{ esc_attr($apartmentInfo) }}">
            <input type="hidden" name="apartment_url" value="{{ esc_url($currentUrl) }}">

            <div class="grid gap-2">
              <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500" for="contact-name">{{ __('Imie i nazwisko', 'sage') }}</label>
              <input class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900" type="text" id="contact-name" name="contact_name" required>
            </div>

            <div class="grid gap-2">
              <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500" for="contact-phone">{{ __('Telefon', 'sage') }}</label>
              <input class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900" type="tel" id="contact-phone" name="contact_phone" required>
            </div>

            <div class="grid gap-2">
              <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500" for="contact-email">{{ __('Email', 'sage') }}</label>
              <input class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900" type="email" id="contact-email" name="contact_email" required>
            </div>

            <div class="grid gap-2">
              <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500" for="contact-message">{{ __('Wiadomosc', 'sage') }}</label>
              <textarea class="min-h-[140px] w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900" id="contact-message" name="contact_message" required>{{ $defaultMessage }}</textarea>
            </div>

              <button class="floor-view-button inline-flex w-full justify-center" type="submit">
                {{ __('Wyslij zapytanie', 'sage') }}
              </button>
            </form>
          </div>
        </div>
      </div>
    </section>
  @endif

  @if (! $selectedApartment)
    <section class="investment-floorplan py-10" data-investment-floorplan id="pietra">
    <div class="container-main space-y-8">
      @if (count($buildings) > 1)
        <div class="flex flex-wrap gap-3">
          @foreach ($buildings as $index => $building)
            @php
              $buildingId = 'building-'.$index;
              $buildingName = $building['building_name'] ?? 'Budynek';
            @endphp
            <button
              class="building-tab rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-900 hover:text-white"
              type="button"
              data-building-trigger="{{ $buildingId }}"
            >
              {{ $buildingName }}
            </button>
          @endforeach
        </div>
      @endif

      <div class="grid gap-10">
        <div class="space-y-6">
          @foreach ($buildings as $index => $building)
            @php
              $buildingId = 'building-'.$index;
              $buildingName = $building['building_name'] ?? 'Budynek';
              $floors = $building['floors'] ?? [];
              $floors = is_array($floors) ? $floors : [];
            @endphp
            <div class="space-y-3" data-building-panel="{{ $buildingId }}">
              <div class="flex flex-wrap gap-2">
                @foreach ($floors as $floorIndex => $floor)
                  @php
                    $floorId = $buildingId.'-floor-'.$floorIndex;
                    $floorLabel = $floor['floor_label'] ?? 'Pietro';
                  @endphp
                  <button
                    class="floor-tab rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold transition hover:bg-slate-900 hover:text-white bg-slate-900 text-white"
                    type="button"
                    data-floor-trigger="{{ $floorId }}"
                  >
                    {{ $floorLabel }}
                  </button>
                @endforeach
              </div>

              @foreach ($floors as $floorIndex => $floor)
                @php
                  $floorId = $buildingId.'-floor-'.$floorIndex;
                  $floorImage = $floor['floor_image'] ?? null;
                  $floorImageUrl = is_array($floorImage) ? ($floorImage['url'] ?? '') : '';
                  $floorView = $floor['floor_view'] ?? 'plan';
                  $floorSvg = $floor['floor_svg'] ?? '';
                  $floorSvgFile = $floor['floor_svg_file'] ?? null;
                  $apartments = $floor['apartments'] ?? [];
                  $apartments = is_array($apartments) ? $apartments : [];
                  $apartmentMap = [];

                  if (! $floorSvg && $floorSvgFile) {
                    $svgFileId = null;
                    if (is_array($floorSvgFile)) {
                      $svgFileId = $floorSvgFile['ID'] ?? $floorSvgFile['id'] ?? null;
                    } elseif (is_numeric($floorSvgFile)) {
                      $svgFileId = (int) $floorSvgFile;
                    }

                    if ($svgFileId) {
                      $svgPath = get_attached_file($svgFileId);
                      if ($svgPath && file_exists($svgPath)) {
                        $floorSvg = file_get_contents($svgPath);
                      }
                    }
                  }

                  $hasFloorImage = ! empty($floorImageUrl);
                  $hasPlanView = ! empty($floorSvg);
                  $hasTableView = count($apartments) > 0;
                  $initialView = $floorView === 'table' && $hasTableView ? 'table' : ($hasPlanView ? 'plan' : ($hasTableView ? 'table' : 'plan'));

                  foreach ($apartments as $apartment) {
                    $svgId = $apartment['apartment_svg_id'] ?? '';
                    if (! $svgId) {
                      continue;
                    }

                    $areaRaw = $apartment['apartment_area'] ?? '';
                    $areaValue = $parseNumber($areaRaw);
                    $priceValue = $parseNumber($apartment['apartment_price_current'] ?? null);
                    if ($priceValue === null) {
                      $priceValue = $parseNumber($apartment['apartment_price'] ?? null);
                    }
                    $pricePerM2 = $calculatePricePerM2($priceValue, $areaValue);
                    $promo = ! empty($apartment['apartment_is_promo']);
                    $promoLabel = trim((string) ($apartment['apartment_promo_label'] ?? ''));
                    $lowest30 = $parseNumber($apartment['apartment_price_lowest_30'] ?? null);
                    $history = $buildHistory($apartment['apartment_price_history'] ?? []);
                    $history = $filterHistory($history, $priceValue);
                    $extrasData = $buildExtras($apartment['apartment_extra_costs'] ?? []);
                    $floorplan = $apartment['apartment_floorplan'] ?? null;
                    $floorplanUrl = is_array($floorplan) ? ($floorplan['url'] ?? '') : '';
                    $roomsList = $apartment['apartment_room_list'] ?? [];
                    $roomsList = is_array($roomsList) ? $roomsList : [];
                    $roomsTotal = 0;
                    foreach ($roomsList as $room) {
                      $roomArea = $room['room_area'] ?? null;
                      if (is_numeric($roomArea)) {
                        $roomsTotal += (float) $roomArea;
                      }
                    }
                    $cardFile = $apartment['apartment_card_file'] ?? null;
                    $cardUrl = is_array($cardFile) ? ($cardFile['url'] ?? '') : '';
                    $cardLabel = is_array($cardFile) ? ($cardFile['title'] ?? '') : '';
                    $apartmentLink = $buildApartmentLink($svgId);

                    $apartmentMap[$svgId] = [
                      'label' => $apartment['apartment_label'] ?? '',
                      'area' => $areaRaw,
                      'area_value' => $areaValue,
                      'rooms' => $apartment['apartment_rooms'] ?? '',
                      'price' => $priceValue,
                      'price_current' => $priceValue,
                      'price_per_m2' => $pricePerM2,
                      'price_lowest_30' => $lowest30,
                      'promo' => $promo,
                      'promo_label' => $promoLabel,
                      'history' => $history,
                      'extras' => $extrasData['items'],
                      'extras_total' => $extrasData['total'],
                      'floor' => $floorLabel,
                      'building' => $buildingName,
                      'floorplan_url' => $floorplanUrl,
                      'rooms_list' => $roomsList,
                      'rooms_total' => $roomsTotal,
                      'card_url' => $cardUrl,
                      'card_label' => $cardLabel,
                      'link' => $apartmentLink,
                      'status' => $apartment['apartment_status'] ?? '',
                    ];
                  }

                  $apartmentEncoded = base64_encode(wp_json_encode($apartmentMap));
                @endphp

                <div
                  class="floor-panel hidden rounded-3xl border border-slate-200 bg-white p-6 shadow-sm"
                  data-floor-panel="{{ $floorId }}"
                  data-apartments="{{ esc_attr($apartmentEncoded) }}"
                  data-apartment-selected="{{ esc_attr($requestedApartment) }}"
                  data-floor-view-default="{{ $initialView }}"
                >
                  @if ($hasPlanView)
                    <div class="floorplan-wrapper relative{{ $hasFloorImage ? ' floorplan-wrapper--overlay' : '' }} {{ $initialView === 'plan' ? '' : 'hidden' }}" data-floor-view="plan">
                      @if ($hasPlanView && $hasTableView)
                        <div class="floor-view-toggle mb-4 flex flex-wrap gap-3">
                          <button
                            class="floor-view-button {{ $initialView === 'plan' ? 'is-active' : '' }}"
                            type="button"
                            data-floor-view-toggle="plan"
                          >
                            Rzut
                          </button>
                          <button
                            class="floor-view-button {{ $initialView === 'table' ? 'is-active' : '' }}"
                            type="button"
                            data-floor-view-toggle="table"
                          >
                            Tabela
                          </button>
                        </div>
                      @endif
                      @if ($floorImageUrl)
                        <img class="floorplan-base w-full rounded-2xl" src="{{ $floorImageUrl }}" alt="{{ $floorLabel }}">
                      @endif

                      <div class="floorplan-svg-wrapper">
                        {!! $floorSvg !!}
                      </div>
                      <div class="floorplan-legend mt-6 flex flex-wrap gap-2 text-xs font-semibold">
                        <span class="investment-status" data-status="available">Wolne</span>
                        <span class="investment-status" data-status="reserved">Rezerwacja</span>
                        <span class="investment-status" data-status="sold">Sprzedane</span>
                      </div>
                    </div>
                  @endif

                  @if ($hasTableView)
                    <div class="floor-table {{ $initialView === 'table' ? '' : 'hidden' }}" data-floor-view="table">
                      @if ($hasPlanView && $hasTableView)
                        <div class="floor-view-toggle mb-4 flex flex-wrap gap-3">
                          <button
                            class="floor-view-button {{ $initialView === 'plan' ? 'is-active' : '' }}"
                            type="button"
                            data-floor-view-toggle="plan"
                          >
                            Rzut
                          </button>
                          <button
                            class="floor-view-button {{ $initialView === 'table' ? 'is-active' : '' }}"
                            type="button"
                            data-floor-view-toggle="table"
                          >
                            Tabela
                          </button>
                        </div>
                      @endif
                      <div class="space-y-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Lista mieszkan</p>
                        <div class="floor-table__scroll overflow-x-auto">
                          <table class="investment-table min-w-[960px] w-full text-left text-sm text-slate-700">
                            <thead class="text-xs uppercase text-slate-500">
                              <tr>
                                <th class="py-2 pr-3">Mieszkanie</th>
                                <th class="py-2 pr-3">Metraz</th>
                                <th class="py-2 pr-3">Pokoje</th>
                                <th class="py-2 pr-3">Cena</th>
                                <th class="py-2 pr-3">Cena za m2</th>
                                <th class="py-2 pr-3">Koszty dodatkowe</th>
                                <th class="py-2 pr-3">Historia cen</th>
                                <th class="py-2">Status</th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach ($apartments as $apartment)
                                @php
                                  $label = $apartment['apartment_label'] ?? $placeholder;
                                  $svgId = $apartment['apartment_svg_id'] ?? '';
                                  $apartmentLink = $buildApartmentLink($svgId);
                                  $area = $apartment['apartment_area'] ?? $placeholder;
                                  $rooms = $apartment['apartment_rooms'] ?? $placeholder;
                                  $areaValue = $parseNumber($apartment['apartment_area'] ?? null);
                                  $priceValue = $parseNumber($apartment['apartment_price_current'] ?? null);
                                  if ($priceValue === null) {
                                    $priceValue = $parseNumber($apartment['apartment_price'] ?? null);
                                  }
                                  $pricePerM2 = $calculatePricePerM2($priceValue, $areaValue);
                                  $promoLabel = trim((string) ($apartment['apartment_promo_label'] ?? ''));
                                  $isPromo = ! empty($apartment['apartment_is_promo']);
                                  $lowest30 = $parseNumber($apartment['apartment_price_lowest_30'] ?? null);
                                  $lowest30Display = $lowest30 !== null ? $formatMoney($lowest30) : ($isPromo ? $placeholder : '');
                                  $extrasData = $buildExtras($apartment['apartment_extra_costs'] ?? []);
                                  $extrasItems = $extrasData['items'];
                                  $extrasTotal = $extrasData['total'];
                                  $historyItems = $buildHistory($apartment['apartment_price_history'] ?? []);
                                  $historyItems = $filterHistory($historyItems, $priceValue);
                                  $status = $apartment['apartment_status'] ?? '';
                                @endphp
                                <tr class="border-t border-slate-200/70">
                                  <td class="py-2 pr-3 font-semibold text-slate-900">
                                    @if ($apartmentLink)
                                      <a class="hover:text-slate-700 !no-underline" href="{{ $apartmentLink }}">{{ $label }}</a>
                                    @else
                                      {{ $label }}
                                    @endif
                                  </td>
                                  <td class="py-2 pr-3">{{ $area }}</td>
                                  <td class="py-2 pr-3">{{ $rooms }}</td>
                                  <td class="py-2 pr-3 whitespace-normal">
                                    <div class="font-semibold text-slate-900">{{ $formatMoney($priceValue) }}</div>
                                    @if ($promoLabel)
                                      <div class="text-xs font-semibold text-emerald-700">{{ $promoLabel }}</div>
                                    @endif
                                    @if ($lowest30Display)
                                      <div class="text-xs text-slate-500">Najnizsza 30 dni: {{ $lowest30Display }}</div>
                                    @endif
                                  </td>
                                  <td class="py-2 pr-3">{{ $pricePerM2 ? $formatMoney($pricePerM2).'/m2' : $placeholder }}</td>
                                  <td class="py-2 pr-3 whitespace-normal">
                                    @if (count($extrasItems) > 0)
                                      <div class="flex items-center gap-2">
                                        <span class="font-semibold text-slate-900">{{ $formatMoney($extrasTotal) }}</span>
                                        <div class="price-tooltip">
                                          <button class="price-tooltip__trigger" type="button" aria-label="Koszty dodatkowe">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                              <circle cx="12" cy="12" r="9"></circle>
                                              <path d="M12 8h.01"></path>
                                              <path d="M11 12h1v4h1"></path>
                                            </svg>
                                          </button>
                                          <div class="price-tooltip__panel">
                                            <p class="price-tooltip__title">Koszty dodatkowe</p>
                                            <ul class="space-y-1 text-xs text-slate-600">
                                              @foreach ($extrasItems as $extra)
                                                <li>{{ $extra['label'] ? $extra['label'].': ' : '' }}{{ $formatMoney($extra['price']) }}</li>
                                              @endforeach
                                            </ul>
                                          </div>
                                        </div>
                                      </div>
                                    @else
                                      {{ $placeholder }}
                                    @endif
                                  </td>
                                  <td class="py-2 pr-3">
                                    @if (count($historyItems) > 0)
                                      <div class="price-tooltip">
                                        <button class="price-tooltip__trigger" type="button" aria-label="Historia cen">
                                          <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="9"></circle>
                                            <path d="M12 7v5l3 3"></path>
                                          </svg>
                                        </button>
                                        <div class="price-tooltip__panel">
                                          <p class="price-tooltip__title">Historia cen</p>
                                          <ul class="space-y-1 text-xs text-slate-600">
                                            @foreach ($historyItems as $history)
                                              <li>{{ $history['date'] ? $history['date'].': ' : '' }}{{ $formatMoney($history['price']) }}</li>
                                            @endforeach
                                          </ul>
                                        </div>
                                      </div>
                                    @else
                                      {{ $placeholder }}
                                    @endif
                                  </td>
                                  <td class="py-2">
                                    <span class="investment-status" data-status="{{ $status }}">
                                      {{ $status === 'available' ? 'Wolne' : ($status === 'reserved' ? 'Rezerwacja' : ($status === 'sold' ? 'Sprzedane' : $placeholder)) }}
                                    </span>
                                  </td>
                                </tr>
                              @endforeach
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  @endif
                </div>
              @endforeach
            </div>
          @endforeach
        </div>

      </div>
    </div>
    </section>

    <div class="container-main prose prose-slate max-w-none pb-16">
      @php
        the_content();
      @endphp
    </div>
  @endif
</article>
