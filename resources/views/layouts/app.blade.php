<!doctype html>
<html @php(language_attributes())>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>[x-cloak]{display:none!important}</style>
    @php(do_action('get_header'))
    @php(wp_head())
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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

    {{-- ─── Search overlay ──────────────────────────────────────────────────── --}}
    <div
      x-data="searchOverlay"
      x-show="open"
      x-cloak
      x-trap.inert.noscroll="open"
      x-transition:enter="transition ease-out duration-200"
      x-transition:enter-start="opacity-0"
      x-transition:enter-end="opacity-100"
      x-transition:leave="transition ease-in duration-150"
      x-transition:leave-start="opacity-100"
      x-transition:leave-end="opacity-0"
      class="fixed inset-0 z-100 bg-ink/95 backdrop-blur-sm flex items-start justify-center pt-24 px-6"
      style="display:none"
      role="dialog"
      aria-modal="true"
      aria-label="{{ __('Cerca', 'sage') }}"
      @keydown.escape.window="hide()"
    >
      <div class="w-full max-w-2xl">
        <form @submit.prevent="submit()" role="search">
          <label for="search-overlay-input" class="block text-[9px] font-sans font-semibold tracking-[0.25em] uppercase text-gold mb-6">
            {{ __('Cosa stai cercando?', 'sage') }}
          </label>
          <div class="flex items-end border-b border-white/20 pb-3 gap-4 group focus-within:border-white/60 transition-colors">
            <input
              id="search-overlay-input"
              type="search"
              x-model="query"
              x-ref="input"
              placeholder="{{ __('Cerca prodotti, categorie…', 'sage') }}"
              class="flex-1 bg-transparent font-serif text-3xl lg:text-5xl font-light text-white placeholder-white/20 focus:outline-none"
              autocomplete="off"
            >
            <button
              type="submit"
              class="shrink-0 text-white/40 hover:text-gold transition-colors pb-1"
              aria-label="{{ __('Cerca', 'sage') }}"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
              </svg>
            </button>
          </div>
        </form>
        <button
          @click="hide()"
          class="mt-8 flex items-center gap-2 text-[10px] font-sans font-semibold tracking-[0.2em] uppercase text-white/30 hover:text-white/60 transition-colors"
        >
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
          </svg>
          {{ __('Chiudi', 'sage') }} <span class="opacity-50 ml-1">Esc</span>
        </button>
      </div>
    </div>

    @php(do_action('get_footer'))
    @php(wp_footer())
  </body>
</html>
