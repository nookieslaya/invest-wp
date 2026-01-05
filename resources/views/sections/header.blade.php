@php
  $phoneNumber = function_exists('get_field') ? (get_field('phone_number', 'option') ?: '') : '';
  $phoneNumber = $phoneNumber ?: '+48 000 000 000';
  $phoneHref = preg_replace('/[^0-9+]/', '', $phoneNumber);
  $logoUrl = get_theme_file_uri('resources/images/logo-investment.svg');
@endphp

<header
  class="site-header sticky bg-white top-0 z-50 border-b border-transparent transition duration-300 data-[scrolled=true]:border-slate-200 data-[scrolled=true]:bg-white data-[scrolled=true]:shadow-[0_10px_25px_rgba(15,23,42,0.08)]"
  data-scrolled="false"
>
  <div class="relative md:border-b md:border-slate-200/70">
    <span
      class="pointer-events-none absolute -left-16 -top-10 h-32 w-32 rounded-full bg-slate-100/80 blur-2xl"
      aria-hidden="true"
    ></span>
    <span
      class="pointer-events-none absolute right-2 top-2 h-24 w-24 rounded-full bg-emerald-100/60 blur-2xl"
      aria-hidden="true"
    ></span>

    <div class="flex w-full container-main mx-auto flex-col relative z-10">
      <div class="flex items-center justify-between gap-4 py-4">
      <div class="flex items-center gap-3 pl-3">
        @if (function_exists('has_custom_logo') && has_custom_logo())
          {!! get_custom_logo() !!}
        @else
          <a class="flex items-center gap-3 text-slate-900 !no-underline" href="{{ home_url('/') }}">
            <img class="h-10 w-auto" src="{{ $logoUrl }}" alt="Investment">
            <span class="sr-only">{{ $siteName }}</span>
          </a>
        @endif
      </div>

      <div class="flex items-center gap-3">
        <a class="text-sm font-semibold text-slate-900" href="tel:{{ $phoneHref }}">
          {{ $phoneNumber }}
        </a>

        <button
          class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-900 md:hidden"
          type="button"
          data-menu-toggle
          aria-controls="primary-menu"
          aria-expanded="false"
        >
          Menu
        </button>
      </div>
      </div>

    </div>
  </div>

  @if (has_nav_menu('primary_navigation'))
    <nav
      class="absolute left-0 right-0 top-full hidden w-full overflow-hidden rounded-b-3xl border-t md:z-100 border-slate-200 bg-white/95 shadow-[0_20px_45px_rgba(15,23,42,0.08)] backdrop-blur md:static md:block md:overflow-visible md:rounded-none md:border-t md:border-slate-200/70 md:bg-white md:shadow-none md:backdrop-blur-0"
      data-primary-nav
      aria-label="{{ wp_get_nav_menu_name('primary_navigation') }}"
    >


      <div class="container-main relative -mt-2 z-100 mx-auto pb-6 pt-4 md:py-3">
        {!! wp_nav_menu([
          'theme_location' => 'primary_navigation',
          'menu_id' => 'primary-menu',
          'menu_class' => 'main-menu  m-0 flex list-none flex-col gap-3 p-0 md:flex-row md:items-center md:gap-6 md:z-100',
          'container' => false,
          'echo' => false,
        ]) !!}
      </div>
    </nav>
  @endif
</header>
