@php(the_content())
<div class="mt-10">

  {{-- @includeIf('modules.flexible-modules') --}}
</div>

@if ($pagination())
  <nav class="page-nav" aria-label="Page">
    {!! $pagination !!}
  </nav>
@endif
