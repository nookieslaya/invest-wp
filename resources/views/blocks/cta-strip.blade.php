@php
  $fields = $fields ?? [];
  $block = $block ?? [];
  $sectionTitle = $fields['section_title'] ?? '';
  $heading = $fields['heading'] ?? '';
  $description = $fields['description'] ?? '';
  $primaryLink = $fields['primary_link'] ?? null;
  $secondaryLink = $fields['secondary_link'] ?? null;
  $image = $fields['image'] ?? null;
  $imageUrl = is_array($image) ? ($image['url'] ?? '') : '';
  $imageAlt = is_array($image) ? ($image['alt'] ?? '') : '';

  $sectionId = $block['anchor'] ?? '';
  $sectionClass = 'block-cta-strip';

  if (! empty($block['className'])) {
    $sectionClass .= ' ' . $block['className'];
  }

  if (! empty($block['align'])) {
    $sectionClass .= ' align' . $block['align'];
  }

  $sectionClass = trim('py-12 md:py-3 ' . $sectionClass);
@endphp

<section class="{{ $sectionClass }}" @if ($sectionId) id="{{ $sectionId }}" @endif>
  <div class="container-main">
    <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-slate-900 text-white">
      <div class="pointer-events-none absolute -right-12 -top-12 h-48 w-48 rounded-full bg-white/10 blur-3xl"></div>
      <div class="pointer-events-none absolute -bottom-16 left-10 h-56 w-56 rounded-full bg-white/5 blur-3xl"></div>

      <div class="grid gap-10 p-8 md:grid-cols-[1.2fr_0.8fr] md:items-center md:p-12">
        <div class="space-y-4">
          @if ($sectionTitle)
            <p class="section-title text-xs md:text-lg text-white/70" data-title="{{ $sectionTitle }}">
              {{ $sectionTitle }}
            </p>
          @endif

          @if ($heading)
            <h2 class="text-3xl font-semibold md:text-4xl">{{ $heading }}</h2>
          @endif

          @if ($description)
            <p class="text-base text-white/80 md:text-lg">{{ $description }}</p>
          @endif

          @if ($primaryLink || $secondaryLink)
            <div class="flex flex-wrap gap-4 pt-2">
              @if (is_array($primaryLink) && ! empty($primaryLink['url']) && ! empty($primaryLink['title']))
                <a
                  class="relative inline-flex items-center justify-center rounded-full border border-white/60 bg-white/10 px-6 py-3 text-xs font-semibold uppercase tracking-[0.2em] text-white transition hover:bg-white hover:text-slate-900 shadow-[0_18px_40px_-26px_rgba(255,255,255,0.5)] before:pointer-events-none before:absolute before:inset-0 before:rounded-full before:border before:border-white/30 before:translate-x-1 before:translate-y-1 before:transition-transform before:duration-200 before:content-[''] hover:before:translate-x-0 hover:before:translate-y-0 !no-underline"
                  href="{{ $primaryLink['url'] }}"
                  @if (! empty($primaryLink['target'])) target="{{ $primaryLink['target'] }}" @endif
                >
                  {{ $primaryLink['title'] }}
                </a>
              @endif

              @if (is_array($secondaryLink) && ! empty($secondaryLink['url']) && ! empty($secondaryLink['title']))
                <a
                  class="relative inline-flex items-center justify-center rounded-full border border-white/30 bg-white/5 px-6 py-3 text-xs font-semibold uppercase tracking-[0.2em] text-white/80 transition hover:bg-white/15 hover:text-white shadow-[0_18px_40px_-26px_rgba(255,255,255,0.25)] before:pointer-events-none before:absolute before:inset-0 before:rounded-full before:border before:border-white/20 before:translate-x-1 before:translate-y-1 before:transition-transform before:duration-200 before:content-[''] hover:before:translate-x-0 hover:before:translate-y-0 !no-underline"
                  href="{{ $secondaryLink['url'] }}"
                  @if (! empty($secondaryLink['target'])) target="{{ $secondaryLink['target'] }}" @endif
                >
                  {{ $secondaryLink['title'] }}
                </a>
              @endif
            </div>
          @endif
        </div>

        @if ($imageUrl)
          <div class="relative h-48 overflow-hidden rounded-3xl border border-white/10 bg-white/10 md:h-64">
            <img class="h-full w-full object-cover" src="{{ esc_url($imageUrl) }}" alt="{{ esc_attr($imageAlt) }}">
          </div>
        @endif
      </div>
    </div>
  </div>
</section>
