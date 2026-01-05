@php
  $sectionTitle = $module['section_title'] ?? '';
  $heading = $module['heading'] ?? '';
  $description = $module['description'] ?? '';

  $featuredQuery = new WP_Query([
      'post_type' => 'post',
      'posts_per_page' => 1,
      'ignore_sticky_posts' => true,
  ]);

  $secondaryQuery = new WP_Query([
      'post_type' => 'post',
      'posts_per_page' => 4,
      'offset' => 1,
      'ignore_sticky_posts' => true,
  ]);
@endphp

@if ($featuredQuery->have_posts())
  <section class="news-module py-8 md:py-10">
    <div class="container-main space-y-6">
      @if ($sectionTitle)
        <p class="section-title">{{ $sectionTitle }}</p>
      @endif

      @if ($heading)
        <h2 class="text-3xl font-semibold text-slate-900 md:text-4xl">{{ $heading }}</h2>
      @endif

      @if ($description)
        <p class="max-w-2xl text-base text-slate-600 md:text-lg">{{ $description }}</p>
      @endif
    </div>

    <div class="container-main mt-10 grid gap-8 lg:grid-cols-[1fr_1fr] lg:items-start">
      <div class="space-y-4">
        @while ($featuredQuery->have_posts())
          @php
            $featuredQuery->the_post();
            $term = null;
            $terms = get_the_terms(get_the_ID(), 'inwestycja');
            if ($terms && ! is_wp_error($terms)) {
              $term = $terms[0];
            }
          @endphp

          <a class="group block overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition hover:shadow-lg" href="{{ get_permalink() }}">
            <div class="relative aspect-[16/9] overflow-hidden">
              @if (has_post_thumbnail())
                <img class="h-full w-full object-cover transition duration-300 group-hover:scale-105" src="{{ get_the_post_thumbnail_url(null, 'large') }}" alt="{{ get_the_title() }}">
              @endif

              @if ($term)
                <span class="absolute left-4 top-4 rounded-full bg-white/90 px-3 py-1 text-xs font-semibold text-slate-900">
                  {{ $term->name }}
                </span>
              @endif
            </div>

            <div class="space-y-2 px-6 py-6">
              <p class="text-xs uppercase tracking-[0.2em] text-slate-500">{{ get_the_date('d.m.Y') }}</p>
              <h3 class="text-2xl font-semibold text-slate-900">{{ get_the_title() }}</h3>
              <p class="text-sm text-slate-600">{{ wp_trim_words(get_the_excerpt(), 22) }}</p>
              <span class="inline-flex items-center text-sm font-semibold text-slate-900">
                Czytaj dalej
              </span>
            </div>
          </a>
        @endwhile
        @php
          wp_reset_postdata();
        @endphp
      </div>

      <div class="space-y-6">
        @if ($secondaryQuery->have_posts())
          <div class="space-y-5">
            @while ($secondaryQuery->have_posts())
              @php
                $secondaryQuery->the_post();
                $term = null;
                $terms = get_the_terms(get_the_ID(), 'inwestycja');
                if ($terms && ! is_wp_error($terms)) {
                  $term = $terms[0];
                }
              @endphp

              <a class="group flex gap-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:shadow-md" href="{{ get_permalink() }}">
                <div class="relative h-24 w-24 shrink-0 overflow-hidden rounded-2xl">
                  @if (has_post_thumbnail())
                    <img class="h-full w-full object-cover transition duration-300 group-hover:scale-105" src="{{ get_the_post_thumbnail_url(null, 'medium') }}" alt="{{ get_the_title() }}">
                  @endif
                  @if ($term)
                    <span class="absolute left-2 top-2 rounded-full bg-white/90 px-2 py-0.5 text-[10px] font-semibold text-slate-900">
                      {{ $term->name }}
                    </span>
                  @endif
                </div>

                <div class="space-y-1">
                  <p class="text-xs uppercase tracking-[0.2em] text-slate-500">{{ get_the_date('d.m.Y') }}</p>
                  <h3 class="text-base font-semibold text-slate-900">{{ get_the_title() }}</h3>
                  <p class="text-sm text-slate-600" style="text-decoration: none!important">{{ wp_trim_words(get_the_excerpt(), 14) }}</p>
                </div>
              </a>
            @endwhile
            @php
              wp_reset_postdata();
            @endphp
          </div>
        @endif
      </div>
    </div>
  </section>
@endif
