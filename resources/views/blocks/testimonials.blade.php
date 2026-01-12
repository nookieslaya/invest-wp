@php
  $block = $block ?? [];
  $fields = $fields ?? null;
  $module = $module ?? null;
  $data = is_array($fields) && ! empty($fields) ? $fields : (is_array($module) ? $module : []);

  $sectionId = $sectionId ?? '';
  $sectionClass = $sectionClass ?? '';

  if (! empty($block)) {
    $sectionId = $sectionId ?: ($block['anchor'] ?? '');
    $sectionClass = trim('block-testimonials ' . $sectionClass);

    if (! empty($block['className'])) {
      $sectionClass .= ' ' . $block['className'];
    }

    if (! empty($block['align'])) {
      $sectionClass .= ' align' . $block['align'];
    }
  }

  $sectionClass = trim('py-16 md:py-24 ' . $sectionClass);

  $sectionTitle = $data['section_title'] ?? '';
  $heading = $data['heading'] ?? '';
  $description = $data['description'] ?? '';
  $items = $data['items'] ?? [];
@endphp

<section class="{{ $sectionClass }}" @if ($sectionId) id="{{ $sectionId }}" @endif>
  <div class="container-main space-y-10">
    <div class="max-w-3xl space-y-4">
      @if ($sectionTitle)
        <p class="section-title" data-title="{{ $sectionTitle }}">{{ $sectionTitle }}</p>
      @endif

      @if ($heading)
        <h2 class="text-3xl font-semibold text-slate-900 md:text-4xl">{{ $heading }}</h2>
      @endif

      @if ($description)
        <p class="text-base text-slate-600 md:text-lg">{{ $description }}</p>
      @endif
    </div>

    @if (is_array($items) && count($items) > 0)
      <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        @foreach ($items as $item)
          @php
            $quote = $item['quote'] ?? '';
            $name = $item['name'] ?? '';
            $role = $item['role'] ?? '';
            $image = $item['image'] ?? null;
            $imageUrl = is_array($image) ? ($image['url'] ?? '') : '';
            $imageAlt = is_array($image) ? ($image['alt'] ?? '') : '';
          @endphp

          <figure class="flex h-full flex-col justify-between rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            @if ($quote)
              <blockquote class="text-base text-slate-600">
                {!! nl2br(e($quote)) !!}
              </blockquote>
            @endif

            <div class="mt-6 flex items-center gap-4">
              @if ($imageUrl)
                <img class="h-12 w-12 rounded-full object-cover" src="{{ esc_url($imageUrl) }}" alt="{{ esc_attr($imageAlt) }}">
              @endif

              <div>
                @if ($name)
                  <p class="text-sm font-semibold text-slate-900">{{ $name }}</p>
                @endif
                @if ($role)
                  <p class="text-xs uppercase tracking-[0.2em] text-slate-500">{{ $role }}</p>
                @endif
              </div>
            </div>
          </figure>
        @endforeach
      </div>
    @endif
  </div>
</section>
