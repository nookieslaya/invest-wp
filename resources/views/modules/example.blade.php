@php
  $heading = $module['example_heading'] ?? '';
  $text = $module['example_text'] ?? '';
  $link = $module['example_link'] ?? null;
  $linkUrl = is_array($link) ? ($link['url'] ?? '') : '';
  $linkTitle = is_array($link) ? ($link['title'] ?? '') : '';
  $linkTarget = is_array($link) ? ($link['target'] ?? '') : '';
@endphp

<section class="container-main">
  @if ($heading)
    <h2 class="module__title">{{ $heading }}</h2>
  @endif

  @if ($text)
    <p class="module__text">{{ $text }}</p>
  @endif

  @if ($linkUrl && $linkTitle)
    <a class="module__link" href="{{ $linkUrl }}" @if ($linkTarget) target="{{ $linkTarget }}" @endif>
      {{ $linkTitle }}
    </a>
  @endif
</section>
