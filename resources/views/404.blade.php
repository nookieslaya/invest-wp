@extends('layouts.app')

@section('content')
  <section class="py-16 md:py-24">
    <div class="container-main grid gap-10 lg:grid-cols-[1.2fr_0.8fr] lg:items-center">
      <div class="space-y-6">
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">
          {{ __('Błąd 404', 'sage') }}
        </p>
        <h1 class="text-4xl font-semibold text-slate-900 md:text-6xl">
          {{ __('Nie znaleźliśmy tej strony.', 'sage') }}
        </h1>
        <p class="max-w-xl text-base text-slate-600 md:text-lg">
          {{ __('Wygląda na to, że adres jest nieaktualny albo strona została przeniesiona. Skorzystaj z menu lub wróć na stronę główną.', 'sage') }}
        </p>
        <div class="flex flex-wrap gap-4">
          <a class="floor-view-button inline-flex !no-underline" href="{{ home_url('/') }}">
            {{ __('Wróć na stronę główną', 'sage') }}
          </a>
        </div>
      </div>

      <div class="relative overflow-hidden rounded-3xl border border-slate-200 bg-white px-10 py-12 shadow-sm">
        <div class="pointer-events-none absolute -right-10 -top-10 h-36 w-36 rounded-full bg-slate-100"></div>
        <div class="pointer-events-none absolute -bottom-16 -left-10 h-48 w-48 rounded-full bg-slate-50 -z-1"></div>
        <p class="text-xs uppercase tracking-[0.4em] text-slate-400">
          {{ __('Kod błędu', 'sage') }}
        </p>
        <p class="mt-6 text-7xl font-semibold text-slate-900 md:text-8xl !z-10">404</p>
        <p class="mt-4 text-sm text-slate-500 !z-10">
          {{ __('Strona jest niedostępna.', 'sage') }}
        </p>
      </div>
    </div>
  </section>
@endsection
