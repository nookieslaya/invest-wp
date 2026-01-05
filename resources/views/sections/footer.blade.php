@php
  $footerLogo = function_exists('get_field') ? get_field('footer_logo', 'option') : null;
  $footerLogoUrl = is_array($footerLogo) ? ($footerLogo['url'] ?? '') : '';
  $footerLogoAlt = is_array($footerLogo) ? ($footerLogo['alt'] ?? '') : '';
  $footerAddress = function_exists('get_field') ? (get_field('footer_address', 'option') ?: '') : '';
  $footerHours = function_exists('get_field') ? (get_field('footer_hours', 'option') ?: '') : '';
  $footerMenuTitle = function_exists('get_field') ? (get_field('footer_menu_title', 'option') ?: '') : '';
  $footerCopyright = function_exists('get_field') ? (get_field('footer_copyright', 'option') ?: '') : '';
  $footerLogoUrl = $footerLogoUrl ?: get_theme_file_uri('resources/images/logo-investment.svg');
  $footerLogoAlt = $footerLogoAlt ?: get_bloginfo('name', 'display');
@endphp

<footer class="relative overflow-hidden border-t border-slate-200 bg-slate-50">
  <span
    class="pointer-events-none absolute -left-20 -top-20 h-48 w-48 rounded-full bg-emerald-100/60 blur-3xl"
    aria-hidden="true"
  ></span>
  <span
    class="pointer-events-none absolute right-10 top-12 h-36 w-36 rounded-full bg-slate-200/60 blur-3xl"
    aria-hidden="true"
  ></span>

  <div class="container-main relative z-10 py-14">
    <div class="flex items-center">
      <img class="h-12 w-auto" src="{{ $footerLogoUrl }}" alt="{{ $footerLogoAlt }}">
    </div>

    <div class="mt-10 grid gap-10 md:grid-cols-3">
      <div class="space-y-3">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Siedziba firmy</p>
        @if ($footerAddress)
          <p class="text-sm text-slate-700">{!! nl2br(e($footerAddress)) !!}</p>
        @endif
      </div>

      <div class="space-y-3">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Godziny pracy</p>
        @if ($footerHours)
          <p class="text-sm text-slate-700">{!! nl2br(e($footerHours)) !!}</p>
        @endif
      </div>

      <div class="space-y-3">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
          {{ $footerMenuTitle ?: __('Menu', 'sage') }}
        </p>
        @if (has_nav_menu('footer_navigation'))
          {!! wp_nav_menu([
            'theme_location' => 'footer_navigation',
            'menu_class' => 'footer-menu m-0 list-none p-0',
            'container' => false,
            'echo' => false,
          ]) !!}
        @endif
      </div>
    </div>
  </div>

  <div class="border-t border-slate-200 bg-white/70">
    <div class="container-main py-4 text-xs text-slate-500">
      {{ $footerCopyright ?: sprintf('© %s %s', date('Y'), get_bloginfo('name', 'display')) }}
    </div>
  </div>
</footer>
