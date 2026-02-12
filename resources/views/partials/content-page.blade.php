@php
  $isWooCustomerPage = (
    (function_exists('is_cart') && is_cart())
    || (function_exists('is_checkout') && is_checkout())
    || (function_exists('is_account_page') && is_account_page())
    || (function_exists('is_order_received_page') && is_order_received_page())
  );
@endphp

@if ($isWooCustomerPage)
  <section class="py-10 md:py-16">
    <div class="container-main">
      <?php the_content(); ?>
      @if ($pagination())
        <nav class="page-nav mt-8" aria-label="Page">
          {!! $pagination !!}
        </nav>
      @endif
    </div>
  </section>
@else
  <?php the_content(); ?>
  <div class="mt-10">
    {{-- @includeIf('modules.flexible-modules') --}}
  </div>

  @if ($pagination())
    <nav class="page-nav" aria-label="Page">
      {!! $pagination !!}
    </nav>
  @endif
@endif

