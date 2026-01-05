@php
  $sectionTitle = $module['section_title'] ?? '';
  $baseImage = $module['base_image'] ?? null;
  $baseImageUrl = is_array($baseImage) ? ($baseImage['url'] ?? '') : '';
  $baseImageAlt = is_array($baseImage) ? ($baseImage['alt'] ?? '') : '';
  $investmentLink = $module['investment_link'] ?? null;
  $investmentUrl = is_array($investmentLink) ? ($investmentLink['url'] ?? '') : '';
  $buildingIndex = $module['building_index'] ?? 0;
  $floorsSvg = $module['floors_svg'] ?? '';
  $floorsSvgFile = $module['floors_svg_file'] ?? null;

  if (! $floorsSvg && $floorsSvgFile) {
    $svgFileId = null;
    if (is_array($floorsSvgFile)) {
      $svgFileId = $floorsSvgFile['ID'] ?? $floorsSvgFile['id'] ?? null;
    } elseif (is_numeric($floorsSvgFile)) {
      $svgFileId = (int) $floorsSvgFile;
    }

    if ($svgFileId) {
      $svgPath = get_attached_file($svgFileId);
      if ($svgPath && file_exists($svgPath)) {
        $floorsSvg = file_get_contents($svgPath);
      }
    }
  }
@endphp

<section
  class="investment-floors-map py-12 md:py-16"
  data-investment-floors-map
  data-building-index="{{ (int) $buildingIndex }}"
  @if ($investmentUrl)
    data-investment-url="{{ esc_url($investmentUrl) }}"
    data-investment-anchor="pietra"
  @endif
>
  <div class="container-main space-y-4">
    @if ($sectionTitle)
      <p class="section-title" data-title="{{ $sectionTitle }}">{{ $sectionTitle }}</p>
    @endif
  </div>

  <div class="container-main mt-8">
    <div class="investment-floors-map__stage">
      @if ($baseImageUrl)
        <img class="investment-floors-map__base" src="{{ esc_url($baseImageUrl) }}" alt="{{ esc_attr($baseImageAlt) }}">
      @endif

      @if ($floorsSvg)
        <div class="investment-floors-map__svg">
          {!! $floorsSvg !!}
        </div>
      @endif
    </div>
  </div>
</section>
