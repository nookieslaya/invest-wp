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
        <a
          class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-900 md:h-auto md:w-auto md:rounded-none md:border-0 md:bg-transparent md:text-sm md:font-semibold"
          href="tel:{{ $phoneHref }}"
        >
          <span class="hidden md:inline">{{ $phoneNumber }}</span>
          <span class="sr-only md:hidden">Call</span>
          <span class="md:hidden" aria-hidden="true">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.8.3 1.6.6 2.3a2 2 0 0 1-.5 2.1L8 9a16 16 0 0 0 7 7l.9-1.2a2 2 0 0 1 2.1-.5c.7.3 1.5.5 2.3.6a2 2 0 0 1 1.7 2z"></path>
            </svg>
          </span>
        </a>

        <button
          class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-900 md:hidden"
          type="button"
          data-menu-toggle
          aria-controls="primary-menu"
          aria-expanded="false"
        >
          <span class="sr-only">Menu</span>
          <span class="menu-toggle__icon menu-toggle__icon--open" aria-hidden="true">
            <svg class="menu-toggle__svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
              <line x1="3" y1="6" x2="21" y2="6"></line>
              <line x1="3" y1="12" x2="21" y2="12"></line>
              <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
          </span>
          <span class="menu-toggle__icon menu-toggle__icon--close" aria-hidden="true">
            <svg class="menu-toggle__svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
              <line x1="6" y1="6" x2="18" y2="18"></line>
              <line x1="18" y1="6" x2="6" y2="18"></line>
            </svg>
          </span>
        </button>
      </div>
      </div>

    </div>
  </div>

  @if (has_nav_menu('primary_navigation'))
    <nav
      class="absolute left-0 right-0 top-full hidden w-full overflow-hidden rounded-b-3xl border-t border-slate-200 md:z-100 bg-white/95 shadow-[0_20px_45px_rgba(15,23,42,0.08)] backdrop-blur md:static md:block md:overflow-visible md:rounded-none md:border-t md:border-slate-200/70 md:bg-white md:shadow-none md:backdrop-blur-0"
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
