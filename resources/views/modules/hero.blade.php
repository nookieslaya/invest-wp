@php
  $slides = $module['slides'] ?? [];
  $hasSlides = is_array($slides) && count($slides) > 0;
  $hasMultiple = is_array($slides) && count($slides) > 1;
@endphp

@if ($hasSlides)
  <section class="relative -top-20 z-1 h-screen w-full overflow-hidden md:-top-25" data-hero-slider>
    <div class="flex h-full  w-full transition-transform duration-700 ease-out" data-hero-track>
      @foreach ($slides as $index => $slide)
        @php
          $image = $slide['image'] ?? null;
          $imageUrl = is_array($image) ? ($image['url'] ?? '') : '';
          $imageAlt = is_array($image) ? ($image['alt'] ?? '') : '';
          $heading = $slide['heading'] ?? '';
          $text = $slide['text'] ?? '';
          $button = $slide['button'] ?? null;
          $buttonUrl = is_array($button) ? ($button['url'] ?? '') : '';
          $buttonTitle = is_array($button) ? ($button['title'] ?? '') : '';
          $buttonTarget = is_array($button) ? ($button['target'] ?? '') : '';
        @endphp

        <div class="relative  h-full w-full shrink-0" data-hero-slide data-hero-index="{{ $index }}">
          @if ($imageUrl)
            <img class="absolute inset-0 h-full w-full object-cover" src="{{ $imageUrl }}" alt="{{ $imageAlt }}">
          @endif

          <div class="absolute inset-0 bg-slate-900/50"></div>

          <div class="relative z-10 flex h-full items-center">
            <div class="w-full flex flex-col items-center gap-5 md:justify-items-normal md:gap-0 md:flex-row justify-between container-main text-white">
              <div class="max-w-2xl space-y-4">
                @if ($heading)
                  <h1 class="text-4xl font-semibold text-center leading-tight md:text-start lg:text-8xl md:text-6xl">{{ $heading }}</h1>
                @endif

                @if ($text)
                  <p class="text-base text-white/90 md:text-3xl text-center md:text-start">{{ $text }}</p>
                @endif
              </div>

                @if ($buttonUrl && $buttonTitle)
                  <a
                    class="relative !no-underline inline-flex items-center justify-center h-50 w-50 rounded-full border border-white/60 bg-white/0 px-7 py-7  text-md font-semibold text-white transition-colors duration-200 md:mr-20 hover:bg-white hover:text-slate-900 shadow-[0_18px_40px_-26px_rgba(255,255,255,0.75)] before:pointer-events-none before:absolute before:inset-0 before:rounded-full before:border before:border-white/30 before:translate-x-1 before:translate-y-1 before:transition-transform before:duration-200 before:content-[''] hover:before:translate-x-0 hover:before:translate-y-0"
                    href="{{ $buttonUrl }}"
                    @if ($buttonTarget) target="{{ $buttonTarget }}" @endif
                    data-magnetic
                    data-magnetic-strength="10"
                  >
                    {{ $buttonTitle }}
                  </a>
                @endif
            </div>
          </div>
        </div>
      @endforeach
    </div>

    @if ($hasMultiple)
    <div class="pointer-events-none z-100 absolute inset-0 flex items-end gap-5 justify-center px-6 pb-20 md:items-end md:flex-col md:justify-center md:gap-2 md:pb-0">
        <button
          class="pointer-events-auto inline-flex h-15 w-15 items-center justify-center rounded-full border border-white/40 bg-transparent text-white hover:backdrop-blur transition hover:bg-white/10"
          type="button"
          aria-label="Previous slide"
          data-hero-prev
        >
          &#8592;
        </button>
        <div class="pointer-events-auto text-sm font-semibold pb-4 md:mr-3 md:pb-0 text-white/90">
          <span data-hero-current>01</span>/<span data-hero-total>01</span>
        </div>
        <button
          class="pointer-events-auto inline-flex h-15 w-15 items-center justify-center rounded-full border border-white/40 bg-transparent text-white hover:backdrop-blur transition hover:bg-white/10"
          type="button"
          aria-label="Next slide"
          data-hero-next
        >
          &#8594;
        </button>
      </div>
    @endif
  </section>
@endif
