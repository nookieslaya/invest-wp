<!doctype html>
<html {!! get_language_attributes() !!}>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php do_action('get_header'); ?>
    <?php wp_head(); ?>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>

  <body class="{{ esc_attr(implode(' ', get_body_class())) }}">
    <?php wp_body_open(); ?>
    <div id="app">
      <a class="sr-only focus:not-sr-only" href="#main">
        {{ __('Skip to content', 'sage') }}
      </a>

      @include('sections.header')

      <main id="main" class="main">
        @includeIf('modules.flexible-modules')
        @yield('content')
      </main>

      @hasSection('sidebar')
        <aside class="sidebar">
          @yield('sidebar')
        </aside>
      @endif

      @include('sections.footer')
    </div>

    <?php do_action('get_footer'); ?>
    <?php wp_footer(); ?>
  </body>
</html>
