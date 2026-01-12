@php
  $fields = $fields ?? [];
  $block = $block ?? [];
  $title = $fields['title'] ?? '';
  $image = $fields['image'] ?? null;
  $imageUrl = is_array($image) ? ($image['url'] ?? '') : '';
  $imageAlt = is_array($image) ? ($image['alt'] ?? '') : '';
  $hasImage = ! empty($imageUrl);

  $sectionId = $block['anchor'] ?? '';
  $sectionClass = 'block-hero-strip';

  if (! empty($block['className'])) {
    $sectionClass .= ' ' . $block['className'];
  }

  if (! empty($block['align'])) {
    $sectionClass .= ' align' . $block['align'];
  }

  $sectionClass = trim('py-8 md:py-10 ' . $sectionClass);
@endphp

<section class="{{ $sectionClass }}" @if ($sectionId) id="{{ $sectionId }}" @endif>
  <div class="container-main px-0 md:px-6">
    <div class="relative overflow-hidden rounded-none md:rounded-3xl @if (! $hasImage) border border-slate-200 bg-slate-50 @endif" @if ($hasImage) data-hero-strip @endif>
      @if ($hasImage)
        <div class="absolute inset-0" data-hero-strip-media style="transform: translate3d(0, var(--hero-strip-parallax, 0px), 0) scale(1.05); will-change: transform;">
          <img class="h-full w-full object-cover" src="{{ esc_url($imageUrl) }}" alt="{{ esc_attr($imageAlt) }}">
        </div>
        <div class="absolute inset-0 bg-slate-900/60"></div>
      @endif

      <div class="relative z-10 flex min-h-[180px] items-center px-6 py-10 md:min-h-[200px] md:px-10 lg:min-h-[240px]">
        @if ($title)
          <h1 class="section-title text-[30px] md:text-[50px] @if ($hasImage) text-white/90 @else text-slate-900 @endif" data-title="{{ $title }}">
            {{ $title }}
          </h1>
        @endif
      </div>
    </div>
  </div>
</section>
