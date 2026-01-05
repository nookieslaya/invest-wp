@php
  $logo = $module['logo'] ?? null;
  $logoUrl = is_array($logo) ? ($logo['url'] ?? '') : '';
  $logoAlt = is_array($logo) ? ($logo['alt'] ?? '') : '';
  $stage = $module['stage'] ?? '';
  $headingLeft = $module['heading_left'] ?? '';
  $headingRight = $module['heading_right'] ?? '';
  $backgroundDesktop = $module['background_desktop'] ?? null;
  $backgroundDesktopUrl = is_array($backgroundDesktop) ? ($backgroundDesktop['url'] ?? '') : '';
  $backgroundMobile = $module['background_mobile'] ?? null;
  $backgroundMobileUrl = is_array($backgroundMobile) ? ($backgroundMobile['url'] ?? '') : '';
  $captionLocation = $module['caption_location'] ?? '';
  $captionName = $module['caption_name'] ?? '';
  $backgroundMobileFinal = $backgroundMobileUrl ?: $backgroundDesktopUrl;
@endphp

@if ($backgroundDesktopUrl)
  <section
    class="investment-hero -top-30 relative w-full bg-emerald-800 text-white"
    data-investment-hero
    style="--hero-image-desktop: url('{{ esc_url($backgroundDesktopUrl) }}'); --hero-image-mobile: url('{{ esc_url($backgroundMobileFinal) }}');"
  >
    <div class="investment-hero__sticky min-h-[100svh]">
      <div class="investment-hero__image absolute inset-0" aria-hidden="true">
        <div class="investment-hero__image-inner absolute inset-0 bg-cover bg-center"></div>
        <div class="investment-hero__overlay absolute inset-0 bg-gradient-to-b from-slate-950/5 via-slate-950/30 to-slate-950/70"></div>
      </div>

      <div class="investment-hero__wrapper relative z-10 flex min-h-[100svh] items-center">
        <div class="container-main flex min-h-[100svh] flex-col justify-between gap-10 py-10 md:py-6">
        <div class="investment-hero__investment flex flex-wrap items-center gap-4 text-xs font-semibold uppercase tracking-[0.25em] text-white/80">
          @if ($logoUrl)
            <img class="investment-hero__investment-logo relative top-20 h-10 w-auto shrink-0" src="{{ esc_url($logoUrl) }}" alt="{{ esc_attr($logoAlt) }}">
          @endif

          @if ($stage)
            <span>{{ $stage }}</span>
          @endif
        </div>

        <div class="investment-hero__heading">
          <div class="investment-hero__heading-row flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
            @if ($headingLeft)
              <div class="investment-hero__heading-left text-4xl font-semibold leading-tight md:text-7xl lg:text-8xl md:pl-20">
                {!! wp_kses_post($headingLeft) !!}
              </div>
            @endif

            @if ($headingRight)
              <div class="investment-hero__heading-right text-4xl font-semibold leading-tight md:text-7xl lg:text-8xl md:text-right  md:pr-20">
                {!! wp_kses_post($headingRight) !!}
              </div>
            @endif
          </div>
        </div>

        @if ($captionName || $captionLocation)
          <div class="investment-hero__caption flex w-full items-end justify-between gap-6 text-xs font-semibold uppercase tracking-[0.2em] text-white/80">
            <div class="space-y-1 md:pb-10">
              @if ($captionLocation)
                <p>{{ $captionLocation }}</p>
              @endif
            </div>
            <div class="space-y-1 text-right md:pb-10">
              @if ($captionName)
                <p>{{ $captionName }}</p>
              @endif
            </div>
          </div>
        @endif
        </div>
      </div>
    </div>
  </section>
@endif


