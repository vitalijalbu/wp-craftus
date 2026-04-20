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
  $newsletter_privacy_url = esc_url(get_privacy_policy_url() ?: home_url('/privacy'));
  $newsletter_privacy_label = wp_kses(
    str_replace(
      '{url}',
      $newsletter_privacy_url,
      __('Accetto la <a href="{url}" class="underline">Privacy Policy</a>.', 'sage')
    ),
    [
      'a' => [
        'href' => [],
        'class' => [],
        'target' => [],
        'rel' => [],
      ],
      'strong' => [],
      'em' => [],
    ]
  );
  $cta_url              = function_exists('App\\theme_cta_url') ? \App\theme_cta_url() : esc_url(home_url('/contatti'));
  $custom_logo_id       = (int) get_theme_mod('custom_logo');
@endphp

<footer class="bg-dark text-white" role="contentinfo">

  {{-- ─── Newsletter band ─────────────────────────────────────────────────── --}}
  @if($newsletter_active)
  <div class="border-b border-white/10">
    <div class="container py-10 flex flex-col md:flex-row items-center justify-between gap-6">
      <div>
        <p class="    font-semibold tracking-[0.25em] uppercase text-primary mb-1">Newsletter</p>
        <p class="text-xl font-light">{{ esc_html($newsletter_heading) }}</p>
      </div>

      <div class="w-full max-w-sm">
        @include('partials.newsletter-form', [
          'bg' => 'surface',
          'placeholder' => __('La tua email', 'sage'),
          'btn_label' => __('Iscriviti', 'sage'),
          'rest_url' => esc_url(rest_url('theme/v1/newsletter')),
          'nonce' => wp_create_nonce('wp_rest'),
          'privacy_label' => $newsletter_privacy_label,
        ])
      </div>
    </div>
  </div>
  @endif

  {{-- ─── Main grid ───────────────────────────────────────────────────────── --}}
  <div class="container py-14 lg:py-20">
    <div class="grid grid-cols-2 lg:grid-cols-12 gap-10 lg:gap-6">

      {{-- Brand column --}}
      <div class="col-span-2 lg:col-span-4">
        <a href="{{ esc_url(home_url('/')) }}" class="site-logo site-logo--footer block mb-5" aria-label="{{ esc_attr(get_bloginfo('name')) }}">
          @if($custom_logo_id)
            {!! wp_get_attachment_image($custom_logo_id, 'full', false, ['class' => 'custom-logo', 'alt' => get_bloginfo('name'), 'loading' => 'lazy', 'decoding' => 'async']) !!}
          @else
            <span class="text-2xl font-light tracking-[0.25em] uppercase">{{ get_bloginfo('name') }}</span>
          @endif
        </a>
        @if($footer_tagline)
          <p class="leading-relaxed max-w-xs mb-8">
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
                class="size-8 flex items-center justify-center"
              >
                <x-dynamic-component :component="'icons.' . $slug" class="w-3.5 h-3.5" />
              </a>
            @endforeach
          </div>
        @endif
      </div>

      {{-- Explore nav --}}
      <div class="col-span-1 lg:col-span-2 lg:col-start-6">
        <p class="font-semibold tracking-[0.25em] uppercase mb-5">{{ __('Esplora', 'sage') }}</p>
        <ul class="space-y-3">
          @if(has_nav_menu('footer_navigation'))
            @php
              $loc          = get_nav_menu_locations()['footer_navigation'] ?? 0;
              $footer_items = $loc ? (wp_get_nav_menu_items($loc) ?: []) : [];
              $footer_items = array_filter($footer_items, fn($i) => !$i->menu_item_parent);
            @endphp
            @foreach($footer_items as $item)
              <li>
                <a href="{{ esc_url($item->url) }}">
                  {{ esc_html($item->title) }}
                </a>
              </li>
            @endforeach
          @endif
        </ul>
      </div>

      {{-- Shop categories --}}
      <div class="col-span-1 lg:col-span-2">
        <p class="font-semibold tracking-[0.25em] uppercase mb-5">{{ __('Shop', 'sage') }}</p>
        <ul class="space-y-3">
          @foreach($shop_cats as $cat)
            <li>
              <a href="{{ esc_url(get_term_link($cat)) }}">
                {{ esc_html($cat->name) }}
              </a>
            </li>
          @endforeach
          @if(function_exists('wc_get_page_permalink'))
            <li>
              <a href="{{ esc_url(wc_get_page_permalink('shop')) }}" class="text-white/90! hover:text-white!">
                {{ __('Tutti i prodotti →', 'sage') }}
              </a>
            </li>
          @endif
        </ul>
      </div>

      {{-- Info links — Menu Footer — Informazioni (Aspetto → Menu) --}}
      <div class="col-span-2 lg:col-span-2">
        <p class="font-semibold tracking-[0.25em] uppercase mb-5">{{ __('Informazioni', 'sage') }}</p>
        @if(has_nav_menu('footer_info_navigation'))
          @php
            $info_loc   = get_nav_menu_locations()['footer_info_navigation'] ?? 0;
            $info_items = $info_loc ? (wp_get_nav_menu_items($info_loc) ?: []) : [];
            $info_items = array_filter($info_items, fn($i) => !$i->menu_item_parent);
          @endphp
          <ul class="space-y-3">
            @foreach($info_items as $item)
              <li>
                <a href="{{ esc_url($item->url) }}">
                  {{ esc_html($item->title) }}
                </a>
              </li>
            @endforeach
          </ul>
        @endif
      </div>

    </div>
  </div>

  {{-- Divider --}}
  <div class="h-px bg-border mx-6 lg:mx-10"></div>

  {{-- Legal info — obblighi di legge organizzazione non profit --}}
  @php
    $org_cf      = get_theme_mod('org_codice_fiscale', '');
    $org_email   = get_theme_mod('org_email',          '');
    $org_phone   = get_theme_mod('org_phone',          '');
    $org_address = get_theme_mod('org_address',        '');
  @endphp
  @if($org_cf || $org_email || $org_phone || $org_address)
  <div class="container py-4">
    <p class="text-xs text-muted leading-relaxed text-center sm:text-left">
      @if($org_cf) {{ __('C.F.', 'sage') }} {{ esc_html($org_cf) }}@endif
      @if($org_email && $org_cf) &nbsp;·&nbsp; @endif
      @if($org_email)
        {{ __('Email:', 'sage') }} <a href="mailto:{{ esc_attr($org_email) }}" class="hover:text-primary transition-colors">{{ esc_html($org_email) }}</a>
      @endif
      @if($org_phone && ($org_cf || $org_email)) &nbsp;·&nbsp; @endif
      @if($org_phone)
        {{ __('Tel.:', 'sage') }} <a href="tel:{{ esc_attr(preg_replace('/\s+/', '', $org_phone)) }}" class="hover:text-primary transition-colors">{{ esc_html($org_phone) }}</a>
      @endif
      @if($org_address && ($org_cf || $org_email || $org_phone)) &nbsp;·&nbsp; @endif
      @if($org_address) {{ esc_html($org_address) }} @endif
    </p>
  </div>
  @endif

  {{-- Legal bar --}}
  <div class="container py-5 flex flex-col sm:flex-row items-center justify-between gap-3">
    <p class="text-sm text-muted">
      © {{ date('Y') }} {{ get_bloginfo('name') }}. {{ __('Tutti i diritti riservati.', 'sage') }}
    </p>
    {{-- Legal links — Menu Footer — Legal (Aspetto → Menu) --}}
    @if(has_nav_menu('footer_legal_navigation'))
      @php
        $legal_loc   = get_nav_menu_locations()['footer_legal_navigation'] ?? 0;
        $legal_items = $legal_loc ? (wp_get_nav_menu_items($legal_loc) ?: []) : [];
        $legal_items = array_filter($legal_items, fn($i) => !$i->menu_item_parent);
      @endphp
      <div class="flex items-center gap-5 flex-wrap">
        @foreach($legal_items as $item)
          <a href="{{ esc_url($item->url) }}" class="text-sm text-muted hover:text-primary transition-colors">
            {{ esc_html($item->title) }}
          </a>
        @endforeach
      </div>
    @endif
  </div>

</footer>
