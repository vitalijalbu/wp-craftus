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

    {{-- ─── Search overlay with live results ───────────────────────────────── --}}
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
      class="fixed inset-0 z-100 bg-ink/95 backdrop-blur-sm flex items-start justify-center pt-20 px-6"
      style="display:none"
      role="dialog"
      aria-modal="true"
      :aria-label="'{{ __('Cerca', 'sage') }}'"
      @keydown.escape.window="hide()"
    >
      <div class="w-full max-w-2xl">

        {{-- Input --}}
        <form @submit.prevent="submit()" role="search">
          <label for="search-overlay-input" class="block font-semibold tracking-[0.25em] uppercase text-accent mb-6">
            {{ __('Cosa stai cercando?', 'sage') }}
          </label>
          <div class="flex items-end border-b border-white/20 pb-3 gap-4 focus-within:border-white/60 transition-colors">
            <input
              id="search-overlay-input"
              type="search"
              x-model="query"
              x-ref="input"
              @input.debounce.350ms="fetchResults()"
              placeholder="{{ __('Cerca prodotti, articoli…', 'sage') }}"
              class="flex-1 bg-transparent font-serif text-3xl lg:text-5xl font-light text-white placeholder-white/20 focus:outline-none"
              autocomplete="off"
              aria-controls="search-live-results"
              :aria-expanded="results.length > 0 ? 'true' : 'false'"
            >
            <button
              type="submit"
              class="shrink-0 text-white/40 hover:text-accent transition-colors pb-1"
              aria-label="{{ __('Cerca', 'sage') }}"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
              </svg>
            </button>
          </div>
        </form>

        {{-- Live results --}}
        <div
          id="search-live-results"
          role="listbox"
          aria-label="{{ __('Risultati ricerca', 'sage') }}"
          class="mt-6"
        >
          {{-- Loading --}}
          <div x-show="loading" class="flex justify-center py-8">
            <svg class="w-5 h-5 text-white/30 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"/>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
            </svg>
          </div>

          {{-- Results list --}}
          <ul
            x-show="results.length > 0 && !loading"
            class="divide-y divide-white/8"
          >
            <template x-for="item in results" :key="item.id">
              <li role="option">
                <a
                  :href="item.url"
                  class="flex items-center gap-4 py-4 group"
                  @click="hide()"
                >
                  <div
                    class="shrink-0 w-12 h-12 bg-white/5 overflow-hidden"
                    :class="item.thumb ? '' : 'flex items-center justify-center'"
                  >
                    <img
                      x-show="item.thumb"
                      :src="item.thumb"
                      :alt="item.title"
                      class="w-full h-full object-cover"
                      loading="lazy"
                      decoding="async"
                    >
                    <svg x-show="!item.thumb" class="w-5 h-5 text-white/20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/></svg>
                  </div>
                  <div class="flex-1 min-w-0">
                    <p class="font-serif text-base font-light text-white group-hover:text-accent transition-colors truncate" x-text="item.title"></p>
                    <p class="text-xs text-white/40 truncate mt-0.5" x-text="item.excerpt"></p>
                  </div>
                  <div class="shrink-0 text-right" x-show="item.price">
                    <span class="text-sm font-medium text-accent" x-html="item.price"></span>
                  </div>
                </a>
              </li>
            </template>
          </ul>

          {{-- View all results link --}}
          <div x-show="results.length > 0 && !loading && query.length > 1" class="pt-5 border-t border-white/8">
            <a
              :href="'{{ home_url("/?s=") }}' + encodeURIComponent(query)"
              @click="hide()"
              class="text-xs font-semibold tracking-[0.18em] uppercase text-white/40 hover:text-accent transition-colors"
            >
              {{ __('Vedi tutti i risultati', 'sage') }}
              <span x-show="totalCount > 0">(<span x-text="totalCount"></span>)</span>
              →
            </a>
          </div>

          {{-- No results --}}
          <p
            x-show="noResults && !loading"
            class="text-sm text-white/30 py-4"
          >
            {{ __('Nessun risultato trovato.', 'sage') }}
          </p>
        </div>

        {{-- Close --}}
        <button
          @click="hide()"
          class="mt-8 flex items-center gap-2     font-semibold tracking-[0.2em] uppercase text-white/30 hover:text-white/60 transition-colors"
        >
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
          </svg>
          {{ __('Chiudi', 'sage') }} <span class="opacity-50 ml-1">Esc</span>
        </button>
      </div>
    </div>

    @include('partials.cart-drawer')
    @include('partials.back-to-top')
    @include('partials.cookie-banner')

    @php(do_action('get_footer'))
    @php(wp_footer())
  </body>
</html>
