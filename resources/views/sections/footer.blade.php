@php
  // ── Social links from Customizer ──────────────────────────────────────────
  $socials = array_filter([
    'instagram' => ['label' => 'Instagram', 'url' => get_theme_mod('social_instagram', ''), 'icon' => 'M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z'],
    'facebook'  => ['label' => 'Facebook',  'url' => get_theme_mod('social_facebook',  ''), 'icon' => 'M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z'],
    'tiktok'    => ['label' => 'TikTok',    'url' => get_theme_mod('social_tiktok',    ''), 'icon' => 'M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.69a8.18 8.18 0 0 0 4.78 1.52V6.77a4.85 4.85 0 0 1-1.01-.08z'],
    'youtube'   => ['label' => 'YouTube',   'url' => get_theme_mod('social_youtube',   ''), 'icon' => 'M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z'],
  ], fn($s) => !empty($s['url']));

  // ── Shop categories — re-use object cache set by header ──────────────────
  $shop_cats = [];
  if (function_exists('get_terms')) {
    $cached = wp_cache_get('4zampe_header_wc_cats');
    if ($cached === false) {
      $cached = get_terms([
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
        'parent'     => 0,
        'number'     => 6,
        'exclude'    => get_option('default_product_cat'),
      ]);
      $cached = is_array($cached) ? array_values($cached) : [];
      wp_cache_set('4zampe_header_wc_cats', $cached, '', 5 * MINUTE_IN_SECONDS);
    }
    $shop_cats = $cached;
  }

  $footer_tagline       = get_theme_mod('footer_tagline',    __('Il tuo punto di riferimento per la cura e il benessere del tuo animale domestico.', 'sage'));
  $newsletter_heading   = get_theme_mod('newsletter_heading', __('Offerte esclusive, novità e consigli per il tuo animale.', 'sage'));
  $cta_url              = function_exists('App\\theme_cta_url') ? \App\theme_cta_url() : esc_url(home_url('/contatti'));
@endphp

<footer class="bg-ink text-white" role="contentinfo">

  {{-- ─── Newsletter band ─────────────────────────────────────────────────── --}}
  <div class="border-b border-white/10">
    <div class="max-w-360 mx-auto px-6 lg:px-10 py-10 flex flex-col md:flex-row items-center justify-between gap-6">
      <div>
        <p class="text-[9px] font-sans font-semibold tracking-[0.25em] uppercase text-gold mb-1">Newsletter</p>
        <p class="font-serif text-xl font-light text-white/90">{{ esc_html($newsletter_heading) }}</p>
      </div>

      {{-- Newsletter form — submits to REST API /wp-json/4zampe/v1/newsletter --}}
      <form
        class="flex w-full max-w-sm gap-0"
        x-data="{ email: '', state: 'idle', message: '' }"
        @submit.prevent="
          if (!email) return;
          state = 'loading';
          fetch('{{ esc_url(rest_url('4zampe/v1/newsletter')) }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': '{{ wp_create_nonce('wp_rest') }}' },
            body: JSON.stringify({ email }),
          })
          .then(r => r.json())
          .then(d => { state = d.success ? 'done' : 'error'; message = d.message || ''; })
          .catch(() => { state = 'error'; message = '{{ __('Errore. Riprova.', 'sage') }}'; });
        "
        novalidate
      >
        <template x-if="state !== 'done'">
          <div class="flex w-full">
            <label for="footer-newsletter-email" class="sr-only">{{ __('Email', 'sage') }}</label>
            <input
              id="footer-newsletter-email"
              type="email"
              x-model="email"
              placeholder="{{ __('La tua email', 'sage') }}"
              :disabled="state === 'loading'"
              class="flex-1 bg-white/5 border border-white/15 border-r-0 px-4 py-3 text-sm font-sans text-white placeholder-white/30 focus:outline-none focus:border-gold/50 transition-colors disabled:opacity-50"
              required
            >
            <button
              type="submit"
              :disabled="state === 'loading'"
              class="bg-gold text-ink text-[10px] font-sans font-semibold tracking-[0.2em] uppercase px-5 py-3 hover:bg-gold/90 transition-colors whitespace-nowrap disabled:opacity-60"
            >
              <span x-show="state !== 'loading'">{{ __('Iscriviti', 'sage') }}</span>
              <span x-show="state === 'loading'" aria-live="polite">…</span>
            </button>
          </div>
        </template>
        <p
          x-show="state === 'done'"
          class="text-sm font-sans text-gold py-3"
          aria-live="polite"
          x-text="message"
        ></p>
        <p
          x-show="state === 'error'"
          class="text-sm font-sans text-red-400 py-3"
          aria-live="assertive"
          x-text="message"
        ></p>
      </form>
    </div>
  </div>

  {{-- ─── Main grid ───────────────────────────────────────────────────────── --}}
  <div class="max-w-360 mx-auto px-6 lg:px-10 py-14 lg:py-20">
    <div class="grid grid-cols-2 lg:grid-cols-12 gap-10 lg:gap-6">

      {{-- Brand column --}}
      <div class="col-span-2 lg:col-span-4">
        <a href="{{ esc_url(home_url('/')) }}" class="block mb-5" aria-label="{{ esc_attr(get_bloginfo('name')) }}">
          @if(has_custom_logo())
            {!! get_custom_logo() !!}
          @else
            <span class="font-serif text-2xl font-light tracking-[0.25em] uppercase text-white">{{ get_bloginfo('name') }}</span>
          @endif
        </a>
        <p class="text-[12px] font-sans text-white/40 leading-relaxed max-w-xs mb-8">
          {{ esc_html($footer_tagline) }}
        </p>

        {{-- Social icons — only renders if URLs are set in Customizer --}}
        @if(!empty($socials))
          <div class="flex items-center gap-4" role="list" aria-label="{{ __('Social media', 'sage') }}">
            @foreach($socials as $social)
              <a
                href="{{ esc_url($social['url']) }}"
                target="_blank"
                rel="noopener noreferrer"
                aria-label="{{ esc_attr($social['label']) }}"
                role="listitem"
                class="w-8 h-8 flex items-center justify-center border border-white/10 text-white/30 hover:text-gold hover:border-gold/40 transition-all duration-200 focus-visible:outline-2 focus-visible:outline-gold"
              >
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path d="{{ $social['icon'] }}"/>
                </svg>
              </a>
            @endforeach
          </div>
        @endif
      </div>

      {{-- Explore nav --}}
      <div class="col-span-1 lg:col-span-2 lg:col-start-6">
        <p class="text-[9px] font-sans font-semibold tracking-[0.25em] uppercase text-white/30 mb-5">{{ __('Esplora', 'sage') }}</p>
        <ul class="space-y-3">
          @if(has_nav_menu('footer_navigation'))
            @php
              $loc          = get_nav_menu_locations()['footer_navigation'] ?? 0;
              $footer_items = $loc ? (wp_get_nav_menu_items($loc) ?: []) : [];
              $footer_items = array_filter($footer_items, fn($i) => !$i->menu_item_parent);
            @endphp
            @foreach($footer_items as $item)
              <li>
                <a href="{{ esc_url($item->url) }}" class="text-[12px] font-sans text-white/40 hover:text-white transition-colors duration-150">
                  {{ esc_html($item->title) }}
                </a>
              </li>
            @endforeach
          @endif
        </ul>
      </div>

      {{-- Shop categories --}}
      <div class="col-span-1 lg:col-span-2">
        <p class="text-[9px] font-sans font-semibold tracking-[0.25em] uppercase text-white/30 mb-5">{{ __('Shop', 'sage') }}</p>
        <ul class="space-y-3">
          @foreach($shop_cats as $cat)
            <li>
              <a href="{{ esc_url(get_term_link($cat)) }}" class="text-[12px] font-sans text-white/40 hover:text-white transition-colors duration-150">
                {{ esc_html($cat->name) }}
              </a>
            </li>
          @endforeach
          @if(function_exists('wc_get_page_permalink'))
            <li>
              <a href="{{ esc_url(wc_get_page_permalink('shop')) }}" class="text-[12px] font-sans text-gold/70 hover:text-gold transition-colors duration-150">
                {{ __('Tutti i prodotti →', 'sage') }}
              </a>
            </li>
          @endif
        </ul>
      </div>

      {{-- Info links --}}
      <div class="col-span-2 lg:col-span-2">
        <p class="text-[9px] font-sans font-semibold tracking-[0.25em] uppercase text-white/30 mb-5">{{ __('Informazioni', 'sage') }}</p>
        <ul class="space-y-3">
          @foreach([
            [home_url('/chi-siamo'), __('Chi siamo', 'sage')],
            [home_url('/contatti'),  __('Contatti',  'sage')],
            [home_url('/blog'),      __('Blog',      'sage')],
            [home_url('/faq'),       __('FAQ',       'sage')],
          ] as [$url, $label])
            <li>
              <a href="{{ esc_url($url) }}" class="text-[12px] font-sans text-white/40 hover:text-white transition-colors duration-150">
                {{ $label }}
              </a>
            </li>
          @endforeach
        </ul>
      </div>

    </div>
  </div>

  {{-- Gold gradient divider --}}
  <div class="h-px bg-linear-to-r from-transparent via-gold/30 to-transparent mx-6 lg:mx-10" data-scroll="line-in"></div>

  {{-- Legal bar --}}
  <div class="max-w-360 mx-auto px-6 lg:px-10 py-6 flex flex-col sm:flex-row items-center justify-between gap-3">
    <p class="text-[10px] font-sans text-white/20">
      © {{ date('Y') }} {{ get_bloginfo('name') }}. {{ __('Tutti i diritti riservati.', 'sage') }}
    </p>
    <div class="flex items-center gap-5">
      @foreach([
        [home_url('/privacy-policy'),       __('Privacy Policy', 'sage')],
        [home_url('/cookie-policy'),        __('Cookie Policy',  'sage')],
        [home_url('/termini-e-condizioni'), __('Termini',        'sage')],
      ] as [$url, $label])
        <a href="{{ esc_url($url) }}" class="text-[10px] font-sans text-white/20 hover:text-white/50 transition-colors">
          {{ $label }}
        </a>
      @endforeach
    </div>
  </div>

</footer>
