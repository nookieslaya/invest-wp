@extends('layouts.app')

@section('content')
  @php
    $postsPageId = get_option('page_for_posts');
    $pageTitle = $postsPageId ? get_the_title($postsPageId) : __('Aktualnosci', 'sage');
    $pageDescription = $postsPageId ? get_post_field('post_excerpt', $postsPageId) : '';
  @endphp

  <section class="news-module py-16 md:py-24">
    <div class="container-main space-y-4">
    
      <h1 class="text-3xl font-semibold uppercase tracking-[0.2em] text-slate-500 md:text-5xl">{{ $pageTitle }}</h1>
      @if ($pageDescription)
        <p class="max-w-2xl text-base text-slate-600 md:text-lg">{{ $pageDescription }}</p>
      @endif
    </div>

    @if (have_posts())
      <div class="container-main mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        @while (have_posts())
          @php
            the_post();
            $term = null;
            $terms = get_the_terms(get_the_ID(), 'inwestycja');
            if ($terms && ! is_wp_error($terms)) {
              $term = $terms[0];
            }
          @endphp

          <a class="group overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition hover:shadow-lg no-underline" href="{{ get_permalink() }}">
            <div class="relative aspect-[16/11] overflow-hidden">
              @if (has_post_thumbnail())
                <img class="h-full w-full object-cover transition duration-300 group-hover:scale-105" src="{{ get_the_post_thumbnail_url(null, 'large') }}" alt="{{ get_the_title() }}">
              @endif
              @if ($term)
                <span class="absolute left-4 top-4 rounded-full bg-white/90 px-3 py-1 text-xs font-semibold text-slate-900">
                  {{ $term->name }}
                </span>
              @endif
            </div>

            <div class="space-y-2 px-6 py-5">
              <p class="text-xs uppercase tracking-[0.2em] text-slate-500">{{ get_the_date('d.m.Y') }}</p>
              <h2 class="text-xl font-semibold text-slate-900">{{ get_the_title() }}</h2>
              <p class="text-sm text-slate-600">{{ wp_trim_words(get_the_excerpt(), 20) }}</p>
            </div>
          </a>
        @endwhile
      </div>

      <div class="container-main mt-10">
        {!! get_the_posts_pagination([
          'mid_size' => 1,
          'prev_text' => __('Poprzednie', 'sage'),
          'next_text' => __('Nastepne', 'sage'),
        ]) !!}
      </div>
    @else
      <div class="container-main mt-10">
        <x-alert type="warning">
          {!! __('Brak wpisow do wyswietlenia.', 'sage') !!}
        </x-alert>
      </div>
    @endif
  </section>
@endsection

{{-- @section('sidebar')
  @include('sections.sidebar')
@endsection --}}
