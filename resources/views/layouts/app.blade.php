<!doctype html>
<html @php(language_attributes())>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php(do_action('get_header'))
    @php(wp_head())
    @php(
      $isWooPage = function_exists('is_woocommerce')
        && (is_woocommerce() || is_cart() || is_checkout() || is_account_page())
    )
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @if($isWooPage)
      @vite('resources/css/woocommerce.css')
    @endif
  </head>

  <body @php(body_class('antialiased'))>
    @php(wp_body_open())

    <div id="app">
      <a class="skip-to-content" href="#main">{{ __('Vai al contenuto', 'sage') }}</a>

      @include('sections.header')

      <main id="main" class="main">
        @yield('content')
      </main>

      @hasSection('sidebar')
        <aside class="sidebar">@yield('sidebar')</aside>
      @endif

      @include('sections.footer')
    </div>

    @include('partials.cart-drawer')
    @include('partials.back-to-top')
    @include('partials.cookie-banner')

    @php(do_action('get_footer'))
    @php(wp_footer())
  </body>
</html>
