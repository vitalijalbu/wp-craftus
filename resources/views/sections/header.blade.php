{{--
  header.blade.php — Luxury e-commerce header
  Layout:   [Logo ←]  ····················  [Nav | Actions →]
  States:   expanded (top) ↔ scrolled compact (GSAP animated)
  Alpine:   x-data="siteHeader" (registered in app.js)
  GSAP:     $watch('scrolled') drives expand/collapse timeline
--}}

@php
  // ── Resolve data once ────────────────────────────────────────────────────
  $wc_cats = [];
  if (function_exists('get_terms')) {
    $wc_cats = wp_cache_get('4zampe_header_wc_cats');
    if ($wc_cats === false) {
      $wc_cats = get_terms([
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
        'parent'     => 0,
        'number'     => 6,
        'exclude'    => get_option('default_product_cat'),
      ]);
      $wc_cats = is_array($wc_cats) ? array_values($wc_cats) : [];
      wp_cache_set('4zampe_header_wc_cats', $wc_cats, '', 5 * MINUTE_IN_SECONDS);
    }
  }

  $top_items = [];
  if (has_nav_menu('primary_navigation')) {
    $loc   = get_nav_menu_locations()['primary_navigation'] ?? 0;
    $items = wp_get_nav_menu_items($loc) ?: [];
    $top_items = array_values(array_filter($items, fn($i) => !$i->menu_item_parent));
  }

  $cart_count = (function_exists('WC') && WC()->cart)
    ? (int) WC()->cart->get_cart_contents_count()
    : 0;

  $cta_url = function_exists('App\\theme_cta_url')
    ? \App\theme_cta_url()
    : esc_url(home_url('/contatti'));
@endphp

<header
  id="site-header"
  x-data="siteHeader"
  @click.outside="closeMenu()"
  class="fixed top-0 left-0 right-0 z-50"
  role="banner"
>

  {{-- ════════════════════════════════════════════════════════════════════════
       EXPANDED BAR — visible at top of page (collapses on scroll via GSAP)
       ════════════════════════════════════════════════════════════════════════ --}}
  <div
    x-ref="expandedWrapper"
    class="header-expanded border-b transition-colors duration-300"
    :class="{
      'border-white/10 bg-transparent': hasHero && !scrolled,
      'border-border/60 bg-surface/95 backdrop-blur-md': !hasHero || scrolled
    }"
  >
    <div class="flex items-center justify-between max-w-360 mx-auto px-8 lg:px-12 h-[72px]">

      {{-- LEFT: Logo ───────────────────────────────────────────────────────── --}}
      <a
        href="{{ esc_url(home_url('/')) }}"
        class="shrink-0 flex items-center focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-gold"
        aria-label="{{ esc_attr(get_bloginfo('name')) }}"
      >
        @if(has_custom_logo())
          {!! get_custom_logo() !!}
        @else
          <span
            class="font-serif text-[22px] font-light tracking-[0.22em] uppercase transition-colors duration-300"
            :class="hasHero && !scrolled ? 'text-white' : 'text-ink'"
          >{{ get_bloginfo('name') }}</span>
        @endif
      </a>

      {{-- RIGHT: Navigation + Actions (desktop) ───────────────────────────── --}}
      <div class="hidden lg:flex items-center gap-8">

        {{-- Nav links ─────────────────────────────────────────────────────── --}}
        <nav aria-label="{{ __('Menu principale', 'sage') }}" class="flex items-center gap-7">

          @if(!empty($wc_cats))
            <button
              type="button"
              id="btn-mega-shop"
              class="nav-link-4z flex items-center gap-1"
              :class="hasHero && !scrolled ? 'text-white/80 hover:text-white' : ''"
              @mouseenter="openMenu('shop')"
              @click="openMenu('shop')"
              :aria-expanded="(activeMenu === 'shop').toString()"
              aria-controls="mega-shop"
              aria-haspopup="true"
            >
              {{ __('Shop', 'sage') }}
              <svg class="w-2.5 h-2.5 transition-transform duration-200" :class="activeMenu==='shop'?'rotate-180':''" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m19 9-7 7-7-7"/></svg>
            </button>
          @endif

          @foreach($top_items as $item)
            <a
              href="{{ esc_url($item->url) }}"
              class="nav-link-4z"
              :class="hasHero && !scrolled ? 'text-white/80 hover:text-white' : ''"
            >{{ esc_html($item->title) }}</a>
          @endforeach

        </nav>

        {{-- Divider ───────────────────────────────────────────────────────── --}}
        <span class="w-px h-4 bg-current opacity-15" aria-hidden="true"></span>

        {{-- Actions ───────────────────────────────────────────────────────── --}}
        <div class="flex items-center gap-4">

          {{-- Search --}}
          <button
            type="button"
            class="icon-btn"
            :class="hasHero && !scrolled ? 'text-white/70 hover:text-white' : ''"
            aria-label="{{ __('Cerca', 'sage') }}"
            @click="$dispatch('open-search')"
          >
            <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
          </button>

          {{-- Cart --}}
          @if(function_exists('WC'))
            <a
              href="{{ esc_url(wc_get_cart_url()) }}"
              class="icon-btn relative"
              :class="hasHero && !scrolled ? 'text-white/70 hover:text-white' : ''"
              aria-label="{{ __('Carrello', 'sage') }}"
            >
              <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/></svg>
              <span
                class="cart-count-fragment absolute -top-1.5 -right-1.5 min-w-4 h-4 bg-gold text-ink text-[9px] font-bold font-sans rounded-full flex items-center justify-center px-0.5 leading-none transition-opacity"
                data-cart-count="{{ $cart_count }}"
                :class="cartCount === 0 ? 'opacity-0 pointer-events-none' : 'opacity-100'"
                x-text="cartCount"
              >{{ $cart_count }}</span>
            </a>
          @endif

          {{-- CTA --}}
          <a
            href="{{ $cta_url }}"
            class="btn-4z-outline"
            :class="hasHero && !scrolled
              ? 'border-white/40 text-white hover:bg-white hover:text-ink'
              : 'border-ink/25 text-ink hover:bg-ink hover:text-white'"
          >{{ __('Contattaci', 'sage') }}</a>

        </div>
      </div>

      {{-- Mobile toggle (visible only on mobile) ──────────────────────────── --}}
      <div class="flex lg:hidden items-center gap-3">
        @if(function_exists('WC'))
          <a
            href="{{ esc_url(wc_get_cart_url()) }}"
            class="icon-btn relative"
            :class="hasHero && !scrolled && !mobileOpen ? 'text-white/70' : 'text-ink'"
            aria-label="{{ __('Carrello', 'sage') }}"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/></svg>
            <span
              class="cart-count-fragment absolute -top-1 -right-1 min-w-4 h-4 bg-gold text-ink text-[9px] font-bold font-sans rounded-full flex items-center justify-center px-0.5 leading-none"
              data-cart-count="{{ $cart_count }}"
              :class="cartCount === 0 ? 'hidden' : 'flex'"
              x-text="cartCount"
            >{{ $cart_count }}</span>
          </a>
        @endif
        <button
          type="button"
          class="icon-btn"
          :class="hasHero && !scrolled && !mobileOpen ? 'text-white/70' : 'text-ink'"
          @click="toggleMobile()"
          :aria-expanded="mobileOpen.toString()"
          aria-controls="mobile-drawer"
          :aria-label="mobileOpen ? '{{ __('Chiudi menu', 'sage') }}' : '{{ __('Apri menu', 'sage') }}'"
        >
          <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
          <svg x-show="mobileOpen"  class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
        </button>
      </div>

    </div>
  </div>

  {{-- ════════════════════════════════════════════════════════════════════════
       SCROLLED COMPACT BAR — revealed by GSAP when scrolled > 80px
       ════════════════════════════════════════════════════════════════════════ --}}
  <div
    x-ref="scrolledBar"
    class="header-scrolled-bar bg-ink/97 backdrop-blur-md border-b border-white/8"
    style="display:none"
  >
    <div class="flex items-center justify-between max-w-360 mx-auto px-8 lg:px-12 h-[58px]">

      {{-- Logo ─────────────────────────────────────────────────────────────── --}}
      <a
        href="{{ esc_url(home_url('/')) }}"
        class="shrink-0 flex items-center"
        aria-label="{{ esc_attr(get_bloginfo('name')) }}"
      >
        @if(has_custom_logo())
          {!! get_custom_logo() !!}
        @else
          <span class="font-serif text-lg font-light tracking-[0.22em] uppercase text-white">
            {{ get_bloginfo('name') }}
          </span>
        @endif
      </a>

      {{-- Right: Nav + Actions ─────────────────────────────────────────────── --}}
      <div class="hidden lg:flex items-center gap-7">

        <nav aria-label="{{ __('Menu principale', 'sage') }}" class="flex items-center gap-6">
          @if(!empty($wc_cats))
            <button
              type="button"
              class="nav-link-4z text-white/75 hover:text-gold"
              style="font-size: 10px"
              @mouseenter="openMenu('shop')"
              @click="openMenu('shop')"
              :aria-expanded="(activeMenu === 'shop').toString()"
              aria-controls="mega-shop"
              aria-haspopup="true"
            >
              {{ __('Shop', 'sage') }}
              <svg class="w-2 h-2 inline-block ml-0.5 transition-transform" :class="activeMenu==='shop'?'rotate-180':''" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m19 9-7 7-7-7"/></svg>
            </button>
          @endif
          @foreach($top_items as $item)
            <a href="{{ esc_url($item->url) }}" class="nav-link-4z text-white/75 hover:text-gold" style="font-size: 10px">
              {{ esc_html($item->title) }}
            </a>
          @endforeach
        </nav>

        <span class="w-px h-3.5 bg-white/15" aria-hidden="true"></span>

        <div class="flex items-center gap-4">
          <button type="button" class="icon-btn text-white/60 hover:text-white" aria-label="{{ __('Cerca', 'sage') }}" @click="$dispatch('open-search')">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
          </button>
          @if(function_exists('WC'))
            <a href="{{ esc_url(wc_get_cart_url()) }}" class="icon-btn relative text-white/60 hover:text-white" aria-label="{{ __('Carrello', 'sage') }}">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/></svg>
              <span
                class="cart-count-fragment absolute -top-1.5 -right-1.5 min-w-4 h-4 bg-gold text-ink text-[9px] font-bold font-sans rounded-full flex items-center justify-center px-0.5 leading-none transition-opacity"
                data-cart-count="{{ $cart_count }}"
                :class="cartCount === 0 ? 'opacity-0 pointer-events-none' : 'opacity-100'"
                x-text="cartCount"
              >{{ $cart_count }}</span>
            </a>
          @endif
          <a href="{{ $cta_url }}" class="btn-4z-outline border-white/30 text-white hover:bg-white hover:text-ink" style="padding: 6px 16px; font-size: 9px">
            {{ __('Contattaci', 'sage') }}
          </a>
        </div>
      </div>

      {{-- Mobile toggle in compact bar ─────────────────────────────────────── --}}
      <button
        type="button"
        class="lg:hidden icon-btn text-white"
        @click="toggleMobile()"
        :aria-expanded="mobileOpen.toString()"
        aria-controls="mobile-drawer"
      >
        <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
        <svg x-show="mobileOpen"  class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
      </button>

    </div>
  </div>

  {{-- ════════════════════════════════════════════════════════════════════════
       MEGA-MENU: SHOP
       ════════════════════════════════════════════════════════════════════════ --}}
  @if(!empty($wc_cats))
    <div
      id="mega-shop"
      role="region"
      aria-labelledby="btn-mega-shop"
      x-show="activeMenu === 'shop'"
      x-cloak
      @mouseenter="activeMenu = 'shop'"
      @mouseleave="closeMenu()"
      class="absolute top-full left-0 right-0 bg-surface shadow-[0_24px_80px_rgba(0,0,0,0.1)] overflow-hidden border-b border-border"
      style="display:none; clip-path: inset(0% 0% 100% 0%)"
    >
      <div class="max-w-360 mx-auto px-8 lg:px-12 py-10">
        <div
          class="grid gap-px bg-border/60"
          style="grid-template-columns: repeat({{ min(count($wc_cats), 5) }}, 1fr) 260px"
        >

          {{-- Category columns ─────────────────────────────────────────────── --}}
          @foreach(array_slice($wc_cats, 0, 5) as $cat)
            @php
              $cat_link     = get_term_link($cat);
              $cat_thumb_id = get_term_meta($cat->term_id, 'thumbnail_id', true);
              $cat_img      = $cat_thumb_id ? wp_get_attachment_image_url($cat_thumb_id, 'medium') : '';
              $sub_cats     = get_terms(['taxonomy' => 'product_cat', 'parent' => $cat->term_id, 'hide_empty' => true, 'number' => 6]);
            @endphp
            <div class="mega-item megamenu-col bg-surface p-7 group">
              @if($cat_img)
                <a href="{{ esc_url($cat_link) }}" class="block mb-5 overflow-hidden aspect-4/3" tabindex="-1">
                  <img
                    src="{{ esc_url($cat_img) }}"
                    alt="{{ esc_attr($cat->name) }}"
                    loading="lazy"
                    decoding="async"
                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                  >
                </a>
              @endif
              <a
                href="{{ esc_url($cat_link) }}"
                class="block font-sans text-[11px] font-semibold tracking-[0.15em] uppercase text-ink/80 hover:text-gold transition-colors duration-200 mb-4"
              >{{ esc_html($cat->name) }}</a>

              @if(!is_wp_error($sub_cats) && !empty($sub_cats))
                <ul class="space-y-2" role="list">
                  @foreach($sub_cats as $sub)
                    <li>
                      <a
                        href="{{ esc_url(get_term_link($sub)) }}"
                        class="flex items-center gap-2 text-[11px] font-sans text-muted hover:text-ink transition-colors duration-150"
                      >
                        <span class="w-3 h-px bg-border inline-block shrink-0" aria-hidden="true"></span>
                        {{ esc_html($sub->name) }}
                      </a>
                    </li>
                  @endforeach
                </ul>
              @endif
            </div>
          @endforeach

          {{-- Featured dark CTA card ─────────────────────────────────────── --}}
          <div class="mega-item bg-ink p-8 flex flex-col justify-between">
            <div>
              <p class="text-[9px] font-sans font-semibold tracking-[0.28em] uppercase text-gold mb-5">
                {{ __('In evidenza', 'sage') }}
              </p>
              <h3 class="font-serif text-[22px] font-light text-white leading-snug mb-3">
                {{ __('Tutto per il tuo animale', 'sage') }}
              </h3>
              <p class="text-[11px] font-sans text-white/40 leading-relaxed">
                {{ __('Selezione premium, consegna rapida.', 'sage') }}
              </p>
            </div>
            <a
              href="{{ esc_url(function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop')) }}"
              class="mt-8 btn-4z-outline-light self-start"
            >
              {{ __('Vai allo shop', 'sage') }}
              <svg class="w-3 h-3 ml-1.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
            </a>
          </div>

        </div>
      </div>
    </div>
  @endif

  {{-- ════════════════════════════════════════════════════════════════════════
       MOBILE DRAWER
       ════════════════════════════════════════════════════════════════════════ --}}
  <div
    id="mobile-drawer"
    x-show="mobileOpen"
    x-trap.inert.noscroll="mobileOpen"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-x-full"
    x-transition:enter-end="opacity-100 translate-x-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-x-0"
    x-transition:leave-end="opacity-0 translate-x-full"
    class="fixed inset-0 top-[72px] bg-ink z-40 overflow-y-auto flex flex-col lg:hidden"
    style="display:none"
    role="dialog"
    aria-modal="true"
    aria-label="{{ __('Menu di navigazione', 'sage') }}"
  >
    <nav class="flex-1 px-6 py-8 space-y-0.5" aria-label="{{ __('Menu mobile', 'sage') }}">

      {{-- Shop accordion ────────────────────────────────────────────────────── --}}
      @if(!empty($wc_cats))
        <div x-data="{ open: false }">
          <button
            type="button"
            @click="open = !open"
            :aria-expanded="open.toString()"
            class="w-full flex items-center justify-between py-5 border-b border-white/8"
          >
            <span class="font-serif text-[28px] font-light text-white tracking-wide">{{ __('Shop', 'sage') }}</span>
            <svg class="w-4 h-4 text-white/30 transition-transform duration-300" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m19 9-7 7-7-7"/></svg>
          </button>
          <div x-show="open" x-collapse class="py-4">
            <div class="grid grid-cols-2 gap-x-4 gap-y-1">
              @foreach($wc_cats as $cat)
                <a
                  href="{{ esc_url(get_term_link($cat)) }}"
                  class="flex items-center gap-2 py-2 text-[12px] font-sans font-medium tracking-wide text-white/50 hover:text-gold transition-colors"
                  @click="closeMobile()"
                >
                  <span class="w-1 h-1 bg-gold/40 rounded-full shrink-0" aria-hidden="true"></span>
                  {{ esc_html($cat->name) }}
                </a>
              @endforeach
            </div>
            <a
              href="{{ esc_url(function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop')) }}"
              class="mt-5 inline-flex items-center gap-2 text-[10px] font-sans font-semibold tracking-[0.2em] uppercase text-gold"
              @click="closeMobile()"
            >{{ __('Vedi tutto lo shop', 'sage') }} →</a>
          </div>
        </div>
      @endif

      {{-- Regular nav items ────────────────────────────────────────────────── --}}
      @foreach($top_items as $item)
        <a
          href="{{ esc_url($item->url) }}"
          class="flex items-center justify-between py-5 border-b border-white/8 font-serif text-[28px] font-light text-white hover:text-gold transition-colors tracking-wide"
          @click="closeMobile()"
        >{{ esc_html($item->title) }}</a>
      @endforeach

    </nav>

    {{-- Drawer footer ────────────────────────────────────────────────────────── --}}
    <div class="px-6 py-8 border-t border-white/8 space-y-4">
      <a
        href="{{ $cta_url }}"
        class="block w-full text-center py-4 bg-gold text-ink text-[10px] font-sans font-semibold tracking-[0.22em] uppercase hover:bg-gold/90 transition-colors"
        @click="closeMobile()"
      >{{ __('Contattaci', 'sage') }}</a>

      @php
        $social_ig = get_theme_mod('social_instagram', '');
        $social_fb = get_theme_mod('social_facebook',  '');
        $social_tk = get_theme_mod('social_tiktok',    '');
      @endphp
      @if($social_ig || $social_fb || $social_tk)
        <div class="flex items-center gap-5 justify-center pt-1">
          @foreach(array_filter(['instagram' => $social_ig, 'facebook' => $social_fb, 'tiktok' => $social_tk]) as $name => $url)
            <a href="{{ esc_url($url) }}" target="_blank" rel="noopener noreferrer" aria-label="{{ ucfirst($name) }}" class="text-[10px] font-sans font-semibold tracking-[0.15em] uppercase text-white/25 hover:text-gold transition-colors">
              {{ ucfirst($name) }}
            </a>
          @endforeach
        </div>
      @endif
    </div>
  </div>

</header>

{{-- Spacer: compensates for fixed header on non-hero pages --}}
<div
  class="h-[72px]"
  :class="$store.layout.hasHero ? 'hidden' : 'block'"
  aria-hidden="true"
></div>
