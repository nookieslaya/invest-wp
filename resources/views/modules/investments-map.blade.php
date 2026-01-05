@php
  $sectionTitle = $module['section_title'] ?? '';
  $heading = $module['heading'] ?? '';
  $description = $module['description'] ?? '';
  $apiKey = function_exists('get_field') ? (get_field('google_maps_api_key', 'option') ?: '') : '';
  $investments = function_exists('get_field') ? (get_field('investments', 'option') ?: []) : [];
  $statusLabels = [
    'w_sprzedazy' => 'W sprzedazy',
    'zrealizowane' => 'Zrealizowane',
    'w_przygotowaniu' => 'W przygotowaniu',
  ];
  $markers = [];

  if (is_array($investments)) {
    foreach ($investments as $investment) {
      $lat = $investment['lat'] ?? '';
      $lng = $investment['lng'] ?? '';

      if ($lat === '' || $lng === '') {
        continue;
      }

      $status = $investment['status'] ?? '';
      $image = $investment['image'] ?? null;
      $imageUrl = is_array($image) ? ($image['url'] ?? '') : '';
      $link = $investment['link'] ?? null;
      $linkUrl = is_array($link) ? ($link['url'] ?? '') : '';
      $linkTitle = is_array($link) ? ($link['title'] ?? '') : '';
      $linkTarget = is_array($link) ? ($link['target'] ?? '') : '';

      $markers[] = [
        'name' => $investment['name'] ?? '',
        'status' => $status,
        'status_label' => $statusLabels[$status] ?? '',
        'lat' => (float) $lat,
        'lng' => (float) $lng,
        'image' => $imageUrl,
        'link_url' => $linkUrl,
        'link_title' => $linkTitle ?: 'Zobacz',
        'link_target' => $linkTarget,
      ];
    }
  }

  $markersJson = wp_json_encode($markers);
  $markersEncoded = base64_encode($markersJson);

  $statusColors = [
  'w_sprzedazy' => 'bg-[#2f766a]/20',
  'zrealizowane' => 'bg-[#3d5a80]/20',
  'w_przygotowaniu' => 'bg-[#b07d3c]/20',
];

@endphp

<section class="py-16 md:py-24" data-investments-module>
  <div class="container-main space-y-6">
    @if ($sectionTitle)
      <p class="section-title" data-title="{{ $sectionTitle }}">{{ $sectionTitle }}</p>
    @endif

    @if ($heading)
      <h2 class="text-3xl font-semibold text-slate-900 md:text-4xl">{{ $heading }}</h2>
    @endif

    @if ($description)
      <p class="max-w-2xl text-base text-slate-600 md:text-lg">{{ $description }}</p>
    @endif
  </div>

  <div class="container-main mt-8 flex flex-wrap items-center gap-4 text-sm text-slate-700">
    @foreach ($statusLabels as $statusValue => $label)
      <label class="inline-flex items-center gap-2 rounded-full border border-slate-200  px-4 py-2 {{ $statusColors[$statusValue] ?? 'bg-white' }}">
        <input
          class="h-4 w-4 rounded border-slate-300 text-slate-900"
          type="checkbox"
          value="{{ $statusValue }}"
          checked
          data-investment-filter
        >
        <span>{{ $label }}</span>
      </label>
    @endforeach
  </div>

  <div class="container-main mt-8">
    <div
      class="relative h-[520px] w-full overflow-hidden rounded-3xl border border-slate-200 bg-slate-50"
      data-investments-map
      data-api-key="{{ esc_attr($apiKey) }}"
      data-investments="{{ esc_attr($markersEncoded) }}"
    >
      <div class="absolute inset-0 flex items-center justify-center text-sm text-slate-500" data-map-message>
        Mapa nie jest gotowa.
      </div>
    </div>
  </div>
</section>
