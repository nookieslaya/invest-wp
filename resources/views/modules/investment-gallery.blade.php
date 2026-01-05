@php
  $sectionTitle = $module['section_title'] ?? '';
  $heading = $module['heading'] ?? '';
  $description = $module['description'] ?? '';
  $featured = $module['featured'] ?? [];
  $items = $module['items'] ?? [];

  $featuredImage = $featured['image'] ?? null;
  $featuredImageUrl = is_array($featuredImage) ? ($featuredImage['url'] ?? '') : '';
  $featuredImageAlt = is_array($featuredImage) ? ($featuredImage['alt'] ?? '') : '';
  $featuredHeading = $featured['heading'] ?? '';
  $featuredDescription = $featured['description'] ?? '';
  $featuredAccent = $featured['accent_color'] ?? '#d6dbe2';
@endphp

<section class="investment-gallery py-1 md:py-6 md:pb-16 ">
  <div class="container-main space-y-4">
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

  <div class="container-main mt-10 lg:mt-14">
    <div class=" max-h-full lg:max-h-[500px] grid gap-8 lg:grid-cols-[minmax(0,1.1fr)_minmax(0,1fr)] lg:auto-rows-fr lg:items-stretch">
      <article
        class="investment-gallery__card investment-gallery__card--large lg:row-span-2"
        data-gallery-card
        style="--gallery-accent: {{ $featuredAccent }}; --gallery-delay: 0s"
      >
        <div class="investment-gallery__media">
          <div class="investment-gallery__panel"></div>
          <div class="investment-gallery__drop"></div>
          @if ($featuredImageUrl)
            <img class="investment-gallery__image" src="{{ esc_url($featuredImageUrl) }}" alt="{{ esc_attr($featuredImageAlt) }}">
          @endif
          @if ($featuredHeading || $featuredDescription)
            <div class="investment-gallery__content">
              @if ($featuredHeading)
                <h3 class="md:text-lg text-xs  font-semibold text-white">{{ $featuredHeading }}</h3>
              @endif
              @if ($featuredDescription)
                <p class="md:text-sm text-[12px] text-white/85">{{ $featuredDescription }}</p>
              @endif
            </div>
          @endif
        </div>
      </article>

      @foreach ($items as $item)
        @php
          $itemImage = $item['image'] ?? null;
          $itemImageUrl = is_array($itemImage) ? ($itemImage['url'] ?? '') : '';
          $itemImageAlt = is_array($itemImage) ? ($itemImage['alt'] ?? '') : '';
          $itemHeading = $item['heading'] ?? '';
          $itemDescription = $item['description'] ?? '';
          $itemAccent = $item['accent_color'] ?? '#cfd6d1';
          $rowStart = $loop->iteration;
          $delay = ($loop->index + 1) * 0.2;
          $delayValue = number_format($delay, 2, '.', '');
        @endphp

        <article
          class="investment-gallery__card lg:col-start-2 lg:row-start-{{ $rowStart }}"
          data-gallery-card
          style="--gallery-accent: {{ $itemAccent }}; --gallery-delay: {{ $delayValue }}s"
        >
          <div class="investment-gallery__media investment-gallery__media--small">
            <div class="investment-gallery__panel"></div>
            <div class="investment-gallery__drop"></div>
            @if ($itemImageUrl)
              <img class="investment-gallery__image" src="{{ esc_url($itemImageUrl) }}" alt="{{ esc_attr($itemImageAlt) }}">
            @endif
            @if ($itemHeading || $itemDescription)
              <div class="investment-gallery__content">
                @if ($itemHeading)
                  <h3 class="md:text-lg text-xs font-semibold text-white">{{ $itemHeading }}</h3>
                @endif
                @if ($itemDescription)
                  <p class="md:text-sm text-[12px] text-white/85">{{ $itemDescription }}</p>
                @endif
              </div>
            @endif
          </div>
        </article>
      @endforeach
    </div>
  </div>
</section>
