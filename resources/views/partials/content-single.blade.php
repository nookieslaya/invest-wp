@php
  $breadcrumbs = [
    ['label' => __('Home', 'sage'), 'url' => home_url('/')],
  ];
  $postsPageId = get_option('page_for_posts');
  if ($postsPageId) {
    $breadcrumbs[] = ['label' => get_the_title($postsPageId), 'url' => get_permalink($postsPageId)];
  }
  $breadcrumbs[] = ['label' => get_the_title(), 'url' => ''];

  $investmentTerms = get_the_terms(get_the_ID(), 'inwestycja');
  $investmentTerm = ($investmentTerms && ! is_wp_error($investmentTerms)) ? $investmentTerms[0] : null;

  $recentPosts = new WP_Query([
    'post_type' => 'post',
    'posts_per_page' => 3,
    'post__not_in' => [get_the_ID()],
    'ignore_sticky_posts' => true,
  ]);
@endphp

<article class="{{ implode(' ', get_post_class('h-entry')) }}">
  <header class="container-main space-y-4 py-10">
    <nav class="text-xs text-slate-500" aria-label="Breadcrumb">
      <ol class="flex flex-wrap items-center gap-2">
        @foreach ($breadcrumbs as $index => $crumb)
          <li class="flex items-center gap-2">
            @if ($crumb['url'])
              <a class="hover:text-slate-700" href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a>
            @else
              <span class="text-slate-700">{{ $crumb['label'] }}</span>
            @endif
            @if ($index < count($breadcrumbs) - 1)
              <span class="text-slate-400">/</span>
            @endif
          </li>
        @endforeach
      </ol>
    </nav>

    @if ($investmentTerm)
      <span class="inline-flex w-fit rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
        {{ $investmentTerm->name }}
      </span>
    @endif

    <h1 class="p-name text-3xl font-semibold text-slate-900 md:text-5xl">
      {!! $title !!}
    </h1>

    @include('partials.entry-meta')
  </header>

  <div class="container-main e-content prose prose-slate py-8">
    @php
      the_content();
    @endphp
  </div>

  @if ($pagination())
    <footer class="container-main py-6">
      <nav class="page-nav" aria-label="Page">
        {!! $pagination !!}
      </nav>
    </footer>
  @endif

  @if ($recentPosts->have_posts())
    <section class="container-main border-t border-slate-200 py-12">
      <h2 class="text-2xl font-semibold text-slate-900">Ostatnie wpisy</h2>
      <div class="mt-6 grid gap-6 md:grid-cols-3">
        @while ($recentPosts->have_posts())
          @php
            $recentPosts->the_post();
          @endphp
          @php
            $term = null;
            $terms = get_the_terms(get_the_ID(), 'inwestycja');
            if ($terms && ! is_wp_error($terms)) {
              $term = $terms[0];
            }
          @endphp

          <a class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:shadow-md" href="{{ get_permalink() }}">
            <div class="relative aspect-[16/11] overflow-hidden">
              @if (has_post_thumbnail())
                <img class="h-full w-full object-cover transition duration-300 group-hover:scale-105" src="{{ get_the_post_thumbnail_url(null, 'medium') }}" alt="{{ get_the_title() }}">
              @endif
              @if ($term)
                <span class="absolute left-3 top-3 rounded-full bg-white/90 px-2 py-0.5 text-[10px] font-semibold text-slate-900">
                  {{ $term->name }}
                </span>
              @endif
            </div>
            <div class="space-y-2 px-4 py-4">
              <p class="text-xs uppercase tracking-[0.2em] text-slate-500">{{ get_the_date('d.m.Y') }}</p>
              <h3 class="text-base font-semibold text-slate-900">{{ get_the_title() }}</h3>
              <p class="text-sm text-slate-600">{{ wp_trim_words(get_the_excerpt(), 12) }}</p>
            </div>
          </a>
        @endwhile
        @php
          wp_reset_postdata();
        @endphp
      </div>
    </section>
  @endif

</article>
