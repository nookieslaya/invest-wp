@php
  $eyebrow = $module['eyebrow'] ?? __('Shop', 'sage');
  $title = $module['title'] ?? '';
  $text = $module['text'] ?? '';
  $button = $module['button'] ?? null;
  $image = $module['image'] ?? null;

  $buttonUrl = is_array($button) ? ($button['url'] ?? '') : '';
  $buttonTitle = is_array($button) ? ($button['title'] ?? '') : '';
  $buttonTarget = is_array($button) ? ($button['target'] ?? '') : '';
@endphp

<section class="shop-hero relative overflow-hidden border-b border-slate-200 bg-white">
  <span class="pointer-events-none absolute -left-16 -top-10 h-40 w-40 rounded-full bg-slate-100/80 blur-2xl" aria-hidden="true"></span>
  <span class="pointer-events-none absolute right-6 top-6 h-32 w-32 rounded-full bg-emerald-100/60 blur-2xl" aria-hidden="true"></span>

  <div class="container-main relative z-10 grid gap-10 py-12 md:grid-cols-[1.05fr_0.95fr] md:items-center md:py-16">
    <div class="space-y-5">
      <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">{{ $eyebrow }}</p>
      @if ($title)
        <h1 class="text-3xl font-semibold text-slate-900 md:text-5xl">{{ $title }}</h1>
      @endif
      @if ($text)
        <div class="max-w-2xl text-base text-slate-600 md:text-lg">
          {!! wp_kses_post($text) !!}
        </div>
      @endif
      @if ($buttonUrl && $buttonTitle)
        <a
          class="wc-button inline-flex !no-underline"
          href="{{ $buttonUrl }}"
          @if ($buttonTarget) target="{{ $buttonTarget }}" @endif
        >
          {{ $buttonTitle }}
        </a>
      @endif
    </div>

    @if ($image)
      <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        @if (is_array($image) && ! empty($image['ID']))
          {!! wp_get_attachment_image($image['ID'], 'large', false, ['class' => 'h-full w-full object-cover']) !!}
        @else
          <img class="h-full w-full object-cover" src="{{ is_string($image) ? $image : '' }}" alt="{{ $title }}">
        @endif
      </div>
    @endif
  </div>
</section>
