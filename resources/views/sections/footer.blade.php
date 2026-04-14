@php
  // ── Social links from Customizer ──────────────────────────────────────────
  $socials = array_filter([
    'instagram' => ['label' => 'Instagram',   'url' => get_theme_mod('social_instagram', '')],
    'facebook'  => ['label' => 'Facebook',    'url' => get_theme_mod('social_facebook',  '')],
    'tiktok'    => ['label' => 'TikTok',      'url' => get_theme_mod('social_tiktok',    '')],
    'youtube'   => ['label' => 'YouTube',     'url' => get_theme_mod('social_youtube',   '')],
    'twitter'   => ['label' => 'X (Twitter)', 'url' => get_theme_mod('social_twitter',   '')],
    'whatsapp'  => ['label' => 'WhatsApp',    'url' => function_exists('App\\theme_whatsapp_url') ? \App\theme_whatsapp_url() : ''],
  ], fn($s) => !empty($s['url']));

  // ── Shop categories — re-use object cache set by header ──────────────────
  $shop_cats = [];
  if (function_exists('get_terms')) {
    $cached = wp_cache_get('theme_header_wc_cats');
    if ($cached === false) {
      $cached = get_terms([
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
        'parent'     => 0,
        'number'     => 6,
        'exclude'    => get_option('default_product_cat'),
      ]);
      $cached = is_array($cached) ? array_values($cached) : [];
      wp_cache_set('theme_header_wc_cats', $cached, '', 5 * MINUTE_IN_SECONDS);
    }
    $shop_cats = $cached;
  }

  $footer_tagline       = get_theme_mod('footer_tagline',    __('Il tuo punto di riferimento per la cura e il benessere del tuo animale domestico.', 'sage'));
  $newsletter_heading   = get_theme_mod('newsletter_heading', __('Offerte esclusive, novità e consigli per il tuo animale.', 'sage'));
  $newsletter_active    = get_theme_mod('newsletter_active', false);
  $cta_url              = function_exists('App\\theme_cta_url') ? \App\theme_cta_url() : esc_url(home_url('/contatti'));
@endphp

<footer class="bg-ink text-white" role="contentinfo">

  {{-- ─── Newsletter band ─────────────────────────────────────────────────── --}}
  @if($newsletter_active)
  <div class="border-b border-white/10">
    <div class="container py-10 flex flex-col md:flex-row items-center justify-between gap-6">
      <div>
        <p class="    font-semibold tracking-[0.25em] uppercase text-primary mb-1">Newsletter</p>
        <p class="font-serif text-xl font-light text-white/90">{{ esc_html($newsletter_heading) }}</p>
      </div>

      {{-- Newsletter form — submits to REST API /wp-json/theme/v1/newsletter --}}
      <form
        class="flex w-full max-w-sm gap-0"
        x-data="{ email: '', state: 'idle', message: '' }"
        @submit.prevent="
          if (!email) return;
          state = 'loading';
          fetch('{{ esc_url(rest_url('theme/v1/newsletter')) }}', {
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
              class="flex-1 bg-white/5 border border-white/15 border-r-0 px-4 py-3 text-sm text-white placeholder-white/30 focus:outline-none focus:border-primary/50 transition-colors disabled:opacity-50"
              required
            >
            <button
              type="submit"
              :disabled="state === 'loading'"
              class="btn-primary whitespace-nowrap disabled:opacity-60"
            >
              <span x-show="state !== 'loading'">{{ __('Iscriviti', 'sage') }}</span>
              <span x-show="state === 'loading'" aria-live="polite">…</span>
            </button>
          </div>
        </template>
        <p
          x-show="state === 'done'"
          class="text-sm text-primary py-3"
          aria-live="polite"
          x-text="message"
        ></p>
        <p
          x-show="state === 'error'"
          class="text-sm text-error py-3"
          aria-live="assertive"
          x-text="message"
        ></p>
      </form>
    </div>
  </div>
  @endif

  {{-- ─── Main grid ───────────────────────────────────────────────────────── --}}
  <div class="container py-14 lg:py-20">
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
        @if($footer_tagline)
          <p class="text-white/55 leading-relaxed max-w-xs mb-8">
            {{ esc_html($footer_tagline) }}
          </p>
        @endif

        {{-- Social icons — only renders if URLs are set in Customizer --}}
        @if(!empty($socials))
          <div class="flex items-center gap-4" role="list" aria-label="{{ __('Social media', 'sage') }}">
            @foreach($socials as $slug => $social)
              <a
                href="{{ esc_url($social['url']) }}"
                target="_blank"
                rel="noopener noreferrer"
                aria-label="{{ esc_attr($social['label']) }}"
                role="listitem"
                class="size-8 flex items-center justify-center border border-white/15 text-white/50 hover:text-primary hover:border-primary/60 transition-all duration-200 focus-visible:outline-2 focus-visible:outline-primary"
              >
                <x-dynamic-component :component="'icons.' . $slug" class="w-3.5 h-3.5" />
              </a>
            @endforeach
          </div>
        @endif
      </div>

      {{-- Explore nav --}}
      <div class="col-span-1 lg:col-span-2 lg:col-start-6">
        <p class="font-semibold tracking-[0.25em] uppercase text-white/50 mb-5">{{ __('Esplora', 'sage') }}</p>
        <ul class="space-y-3">
          @if(has_nav_menu('footer_navigation'))
            @php
              $loc          = get_nav_menu_locations()['footer_navigation'] ?? 0;
              $footer_items = $loc ? (wp_get_nav_menu_items($loc) ?: []) : [];
              $footer_items = array_filter($footer_items, fn($i) => !$i->menu_item_parent);
            @endphp
            @foreach($footer_items as $item)
              <li>
                <a href="{{ esc_url($item->url) }}" class="text-white/55 hover:text-white transition-colors duration-150">
                  {{ esc_html($item->title) }}
                </a>
              </li>
            @endforeach
          @endif
        </ul>
      </div>

      {{-- Shop categories --}}
      <div class="col-span-1 lg:col-span-2">
        <p class="font-semibold tracking-[0.25em] uppercase text-white/50 mb-5">{{ __('Shop', 'sage') }}</p>
        <ul class="space-y-3">
          @foreach($shop_cats as $cat)
            <li>
              <a href="{{ esc_url(get_term_link($cat)) }}" class="text-white/55 hover:text-white transition-colors duration-150">
                {{ esc_html($cat->name) }}
              </a>
            </li>
          @endforeach
          @if(function_exists('wc_get_page_permalink'))
            <li>
              <a href="{{ esc_url(wc_get_page_permalink('shop')) }}" class="text-primary/70 hover:text-primary transition-colors duration-150">
                {{ __('Tutti i prodotti →', 'sage') }}
              </a>
            </li>
          @endif
        </ul>
      </div>

      {{-- Info links — Menu Footer — Informazioni (Aspetto → Menu) --}}
      <div class="col-span-2 lg:col-span-2">
        <p class="font-semibold tracking-[0.25em] uppercase text-white/50 mb-5">{{ __('Informazioni', 'sage') }}</p>
        @if(has_nav_menu('footer_info_navigation'))
          @php
            $info_loc   = get_nav_menu_locations()['footer_info_navigation'] ?? 0;
            $info_items = $info_loc ? (wp_get_nav_menu_items($info_loc) ?: []) : [];
            $info_items = array_filter($info_items, fn($i) => !$i->menu_item_parent);
          @endphp
          <ul class="space-y-3">
            @foreach($info_items as $item)
              <li>
                <a href="{{ esc_url($item->url) }}" class="text-white/55 hover:text-white transition-colors duration-150">
                  {{ esc_html($item->title) }}
                </a>
              </li>
            @endforeach
          </ul>
        @endif
      </div>

    </div>
  </div>

  {{-- Gold gradient divider --}}
  <div class="h-px bg-linear-to-r from-transparent via-white/20 to-transparent mx-6 lg:mx-10" data-scroll="line-in"></div>

  {{-- Legal bar --}}
  <div class="container py-6 flex flex-col sm:flex-row items-center justify-between gap-3">
    <p class="text-white/55">
      © {{ date('Y') }} {{ get_bloginfo('name') }}. {{ __('Tutti i diritti riservati.', 'sage') }}
    </p>
    {{-- Legal links — Menu Footer — Legal (Aspetto → Menu) --}}
    @if(has_nav_menu('footer_legal_navigation'))
      @php
        $legal_loc   = get_nav_menu_locations()['footer_legal_navigation'] ?? 0;
        $legal_items = $legal_loc ? (wp_get_nav_menu_items($legal_loc) ?: []) : [];
        $legal_items = array_filter($legal_items, fn($i) => !$i->menu_item_parent);
      @endphp
      <div class="flex items-center gap-5">
        @foreach($legal_items as $item)
          <a href="{{ esc_url($item->url) }}" class="text-white/55 hover:text-white/80 transition-colors">
            {{ esc_html($item->title) }}
          </a>
        @endforeach
      </div>
    @endif
  </div>

</footer>
