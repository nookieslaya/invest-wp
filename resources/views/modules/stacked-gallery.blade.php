@php
  $sectionTitle = $module['section_title'] ?? '';
  $heading = $module['heading'] ?? '';
  $description = $module['description'] ?? '';
  $items = $module['items'] ?? [];
  $items = is_array($items) ? array_slice($items, 0, 6) : [];
@endphp

<section class="stacked-gallery py-12 md:py-16" data-stacked-gallery>
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

  <div class="stacked-gallery__full">
    <div class="stacked-gallery__stage" aria-live="polite">
      @foreach ($items as $item)
        @php
          $image = $item['image'] ?? null;
          $imageUrl = is_array($image) ? ($image['url'] ?? '') : '';
          $imageAlt = is_array($image) ? ($image['alt'] ?? '') : '';
          $label = $item['label'] ?? '';
        @endphp

        <figure class="stacked-gallery__item" data-stacked-item>
          @if ($imageUrl)
            <img class="stacked-gallery__image" src="{{ esc_url($imageUrl) }}" alt="{{ esc_attr($imageAlt) }}">
          @endif
          @if ($label)
            <figcaption class="stacked-gallery__caption">{{ $label }}</figcaption>
          @endif
        </figure>
      @endforeach
    </div>

    <div class="stacked-gallery__controls">
      <button class="stacked-gallery__control pointer-events-auto inline-flex h-15 w-15 items-center justify-center rounded-full border border-slate-900/40 bg-white/80 text-slate-900 hover:backdrop-blur transition hover:bg-slate-900/10" type="button" data-stacked-prev aria-label="{{ __('Poprzednie', 'sage') }}">
        <span aria-hidden="true">←</span>
      </button>
      <button class="stacked-gallery__control pointer-events-auto inline-flex h-15 w-15 items-center justify-center rounded-full border border-slate-900/40 bg-white/80 text-slate-900 hover:backdrop-blur transition hover:bg-slate-900/10" type="button" data-stacked-next aria-label="{{ __('Nastepne', 'sage') }}">
        <span aria-hidden="true">→</span>
      </button>
    </div>
  </div>
</section>
