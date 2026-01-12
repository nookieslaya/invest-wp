@php
  $sectionTitle = $module['section_title'] ?? '';
  $heading = $module['heading'] ?? '';
  $description = $module['description'] ?? '';
  $image = $module['image'] ?? null;
  $imageUrl = is_array($image) ? ($image['url'] ?? '') : '';
  $imageAlt = is_array($image) ? ($image['alt'] ?? '') : '';
  $stats = $module['stats'] ?? [];
  $button = $module['button'] ?? null;
  $buttonUrl = is_array($button) ? ($button['url'] ?? '') : '';
  $buttonTitle = is_array($button) ? ($button['title'] ?? '') : '';
  $buttonTarget = is_array($button) ? ($button['target'] ?? '') : '';
@endphp

<section class="py-16 md:py-24">
  <div class="container-main grid gap-12 lg:grid-cols-2 lg:items-center">
    <div class="space-y-6">
      @if ($sectionTitle)
        <p class="section-title" data-title="{{ $sectionTitle }}">{{ $sectionTitle }}</p>
      @endif

      @if ($heading)
        <h2 class="text-xl font-semibold text-slate-900 md:text-4xl">{{ $heading }}</h2>
      @endif

      @if ($description)
        <p class="text-base text-slate-600 md:text-lg">{{ $description }}</p>
      @endif

      @if ($buttonUrl && $buttonTitle)
        <a
          class="relative inline-flex items-center justify-center rounded-full border border-slate-900/70 bg-transparent px-7 py-3 text-sm font-semibold text-slate-900 transition-colors duration-200 hover:bg-slate-900 hover:text-white shadow-[0_18px_40px_-26px_rgba(15,23,42,0.35)] before:pointer-events-none before:absolute before:inset-0 before:rounded-full before:border before:border-slate-900/40 before:translate-x-1 before:translate-y-1 before:transition-transform before:duration-200 before:content-[''] hover:before:translate-x-0 hover:before:translate-y-0 !no-underline"
          href="{{ $buttonUrl }}"
          @if ($buttonTarget) target="{{ $buttonTarget }}" @endif
          data-magnetic
          data-magnetic-strength="8"
        >
          {{ $buttonTitle }}
        </a>
      @endif
    </div>

    <div class="relative gap-10 flex flex-col md:flex-row justify-center items-center">
      @if ($imageUrl)
        <div class="relative md:-top-25 overflow-hidden rounded-full border border-slate-200 shadow-xl sm:h-[380px] sm:w-[380px] lg:h-[440px] lg:w-[440px]">
          <img class="h-[320px] w-[320px] md:h-full md:w-full  object-cover" src="{{ $imageUrl }}" alt="{{ $imageAlt }}">
        </div>
      @endif

      @if (is_array($stats) && count($stats) > 0)
        <div class="md:absolute md:left-1/2 md:-bottom-30 w-[85%] md:items-center md:gap-5 md:-translate-x-1/2 rounded-3xl border border-white/60 bg-white/90 p-6 shadow-2xl backdrop-blur">
          <div class="grid gap-6 sm:grid-cols-2">
            @foreach ($stats as $stat)
              @php
                $value = $stat['value'] ?? '';
                $label = $stat['label'] ?? '';
                $suffix = $stat['suffix'] ?? '';
              @endphp

              <div class="space-y-1">
                @if ($value )
                  <div class="text-2xl font-semibold text-slate-900 md:text-3xl">
                    {{ $value }}
                  </div>
                @endif

                @if ($label)
                  <p class="text-sm text-slate-500">{{ $label }}</p>
                @endif
              </div>
            @endforeach
          </div>
        </div>
      @endif
    </div>
  </div>
</section>
