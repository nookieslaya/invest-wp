@extends('layouts.app')

@section('content')
  @php
    $archiveTitle = post_type_archive_title('', false);
    $archiveTitle = $archiveTitle ?: __('Inwestycje', 'sage');
  @endphp

  <section class="container-main invest-archive py-12 md:py-20">
    <div class="space-y-4">
      <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">{{ __('Inwestycje', 'sage') }}</p>
      <h1 class="text-3xl font-semibold text-slate-900 md:text-5xl">{{ $archiveTitle }}</h1>
      <p class="max-w-2xl text-base text-slate-600 md:text-lg">
        {{ __('Poznaj szczegoly inwestycji oraz dostepne mieszkania.', 'sage') }}
      </p>
    </div>

    @if (have_posts())
      <div class="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        @while (have_posts())
          @php
            the_post();

            $subtitle = function_exists('get_field') ? (get_field('investment_subtitle') ?: '') : '';
            $intro = function_exists('get_field') ? (get_field('investment_intro') ?: '') : '';
            $buildings = function_exists('get_field') ? (get_field('buildings', get_the_ID()) ?: []) : [];
            $buildings = is_array($buildings) ? $buildings : [];

            $apartmentsCount = 0;
            $roomsValues = [];
            $areaValues = [];

            foreach ($buildings as $building) {
              $floors = $building['floors'] ?? [];
              if (! is_array($floors)) {
                continue;
              }

              foreach ($floors as $floor) {
                $apartments = $floor['apartments'] ?? [];
                if (! is_array($apartments)) {
                  continue;
                }

                foreach ($apartments as $apartment) {
                  $apartmentsCount++;

                  $roomsRaw = $apartment['apartment_rooms'] ?? '';
                  $areaRaw = $apartment['apartment_area'] ?? '';

                  if (preg_match('/\d+/', (string) $roomsRaw, $match)) {
                    $roomsValues[] = (int) $match[0];
                  }

                  if (preg_match('/\d+(?:[\\.,]\\d+)?/', (string) $areaRaw, $match)) {
                    $areaValues[] = (float) str_replace(',', '.', $match[0]);
                  }
                }
              }
            }

            $formatArea = function ($value) {
              $formatted = number_format($value, 1, '.', '');
              return rtrim(rtrim($formatted, '0'), '.');
            };

            $roomsRange = $roomsValues ? min($roomsValues).(max($roomsValues) !== min($roomsValues) ? ' - '.max($roomsValues) : '') : '';
            $areaRange = $areaValues ? $formatArea(min($areaValues)).(max($areaValues) !== min($areaValues) ? ' - '.$formatArea(max($areaValues)) : '') : '';
          @endphp

          <a class="group overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition hover:shadow-lg no-underline" href="{{ get_permalink() }}">
            <div class="relative aspect-[16/11] overflow-hidden">
              @if (has_post_thumbnail())
                <img class="h-full w-full object-cover transition duration-300 group-hover:scale-105" src="{{ get_the_post_thumbnail_url(null, 'large') }}" alt="{{ get_the_title() }}">
              @endif
            </div>

            <div class="space-y-3 px-6 py-5">
              @if ($subtitle)
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">{{ $subtitle }}</p>
              @endif
              <h2 class="text-xl font-semibold text-slate-900">{{ get_the_title() }}</h2>
              <p class="text-sm text-slate-600">{{ $intro ?: wp_trim_words(get_the_excerpt(), 18) }}</p>

              <dl class="mt-4 space-y-2 text-sm text-slate-600">
                <div class="flex items-center justify-between">
                  <dt>{{ __('Ilosc mieszkan', 'sage') }}</dt>
                  <dd class="font-semibold text-slate-900">{{ $apartmentsCount ?: __('Brak danych', 'sage') }}</dd>
                </div>
                <div class="flex items-center justify-between">
                  <dt>{{ __('Liczba pokoi', 'sage') }}</dt>
                  <dd class="font-semibold text-slate-900">{{ $roomsRange ?: __('Brak danych', 'sage') }}</dd>
                </div>
                <div class="flex items-center justify-between">
                  <dt>{{ __('Metraz', 'sage') }}</dt>
                  <dd class="font-semibold text-slate-900">{{ $areaRange ? $areaRange.' m2' : __('Brak danych', 'sage') }}</dd>
                </div>
              </dl>
            </div>
          </a>
        @endwhile
      </div>
    @else
      <div class="mt-10">
        <x-alert type="warning">
          {!! __('Brak inwestycji do wyswietlenia.', 'sage') !!}
        </x-alert>
      </div>
    @endif
  </section>
@endsection
