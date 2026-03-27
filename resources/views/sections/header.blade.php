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
    $wc_cats = wp_cache_get('theme_header_wc_cats');
    if ($wc_cats === false) {
      $wc_cats = get_terms([
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
        'parent'     => 0,
        'number'     => 6,
        'exclude'    => get_option('default_product_cat'),
      ]);
      $wc_cats = is_array($wc_cats) ? array_values($wc_cats) : [];
      wp_cache_set('theme_header_wc_cats', $wc_cats, '', 5 * MINUTE_IN_SECONDS);
    }
  }

  // Load all nav items and build a parent→children map for dropdown support
  $top_items    = [];
  $children_map = [];
  if (has_nav_menu('primary_navigation')) {
    $loc       = get_nav_menu_locations()['primary_navigation'] ?? 0;
    $all_items = $loc ? (wp_get_nav_menu_items($loc) ?: []) : [];
    foreach ($all_items as $_it) {
      if ($_it->menu_item_parent) {
        $children_map[(int) $_it->menu_item_parent][] = $_it;
      }
    }
    $top_items = array_values(array_filter($all_items, fn($i) => !$i->menu_item_parent));
  }

  $cart_count = (function_exists('WC') && WC()->cart)
    ? (int) WC()->cart->get_cart_contents_count()
    : 0;

  $cta_url   = function_exists('App\\theme_cta_url')  ? \App\theme_cta_url()  : esc_url(home_url('/contatti'));
  $cta_label = function_exists('App\\theme_cta_label') ? \App\theme_cta_label() : __('Contattaci', 'sage');
  $cta_raw_url   = get_theme_mod('cta_url', '');
  $cta_raw_label = get_theme_mod('header_cta_label', '');
  $show_cta  = !empty($cta_raw_url) || !empty($cta_raw_label);
@endphp

@include('partials.announcement-bar')

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
    <div class="flex items-center justify-between container h-16">

      {{-- LEFT: Logo ───────────────────────────────────────────────────────── --}}
      <a
        href="{{ esc_url(home_url('/')) }}"
        class="shrink-0 flex items-center focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent"
        aria-label="{{ esc_attr(get_bloginfo('name')) }}"
      >
        @if(has_custom_logo())
          {!! get_custom_logo() !!}
        @else
          <span
            class="font-sans text-xl font-light tracking-[0.22em] uppercase transition-colors duration-300"
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
              class="nav-link-t flex items-center gap-1"
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
            @php
              $is_mega       = get_post_meta($item->ID, '_menu_item_megamenu', true) === '1';
              $item_children = $children_map[$item->ID] ?? [];
              $mega_id       = 'nav-' . $item->ID;
            @endphp
            @if($is_mega && !empty($item_children))
              <button
                type="button"
                id="btn-mega-{{ $mega_id }}"
                class="nav-link-t flex items-center gap-1"
                :class="hasHero && !scrolled ? 'text-white/80 hover:text-white' : ''"
                @mouseenter="openMenu('{{ $mega_id }}')"
                @click="openMenu('{{ $mega_id }}')"
                :aria-expanded="(activeMenu === '{{ $mega_id }}').toString()"
                aria-controls="mega-{{ $mega_id }}"
                aria-haspopup="true"
              >
                {{ esc_html($item->title) }}
                <svg class="w-2.5 h-2.5 transition-transform duration-200" :class="activeMenu==='{{ $mega_id }}'?'rotate-180':''" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m19 9-7 7-7-7"/></svg>
              </button>
            @else
              <a
                href="{{ esc_url($item->url) }}"
                class="nav-link-t"
                :class="hasHero && !scrolled ? 'text-white/80 hover:text-white' : ''"
              >{{ esc_html($item->title) }}</a>
            @endif
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

          {{-- Wishlist --}}
          <a
            href="{{ esc_url(home_url('/wishlist')) }}"
            class="icon-btn relative"
            :class="hasHero && !scrolled ? 'text-white/70 hover:text-white' : ''"
            aria-label="{{ __('Wishlist', 'sage') }}"
          >
            <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/></svg>
            <span
              class="wishlist-count-bubble absolute -top-1.5 -right-1.5 min-w-4 h-4 bg-accent text-ink font-bold rounded-full flex items-center justify-center px-0.5 leading-none text-[10px]" style="display:none"
            ></span>
          </a>

          {{-- Cart --}}
          @if(function_exists('WC'))
            <button
              type="button"
              @click="$dispatch('open-cart')"
              class="icon-btn relative"
              :class="hasHero && !scrolled ? 'text-white/70 hover:text-white' : ''"
              aria-label="{{ __('Apri carrello', 'sage') }}"
            >
              <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/></svg>
              <span
                class="cart-count-fragment absolute -top-1.5 -right-1.5 min-w-4 h-4 bg-accent text-ink     font-bold rounded-full flex items-center justify-center px-0.5 leading-none transition-opacity"
                data-cart-count="{{ $cart_count }}"
                :class="cartCount === 0 ? 'opacity-0 pointer-events-none' : 'opacity-100'"
                x-text="cartCount"
              >{{ $cart_count }}</span>
            </button>
          @endif

          {{-- CTA --}}
          @if($show_cta)
            <a
              href="{{ $cta_url }}"
              class="btn-slide"
              :class="hasHero && !scrolled
                ? 'border-white/40 text-white hover:bg-white hover:text-ink'
                : 'border-ink/25 text-ink hover:bg-ink hover:text-white'"
            >{{ esc_html($cta_label) }}</a>
          @endif

        </div>
      </div>

      {{-- Mobile toggle (visible only on mobile) ──────────────────────────── --}}
      <div class="flex lg:hidden items-center gap-3">
        @if(function_exists('WC'))
          <button
            type="button"
            @click="$dispatch('open-cart')"
            class="icon-btn relative"
            :class="hasHero && !scrolled && !mobileOpen ? 'text-white/70' : 'text-ink'"
            aria-label="{{ __('Apri carrello', 'sage') }}"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/></svg>
            <span
              class="cart-count-fragment absolute -top-1 -right-1 min-w-4 h-4 bg-accent text-ink     font-bold rounded-full flex items-center justify-center px-0.5 leading-none"
              data-cart-count="{{ $cart_count }}"
              :class="cartCount === 0 ? 'hidden' : 'flex'"
              x-text="cartCount"
            >{{ $cart_count }}</span>
          </button>
        @endif
        {{-- Wishlist (mobile) --}}
        <a
          href="{{ esc_url(home_url('/wishlist')) }}"
          class="icon-btn relative"
          :class="hasHero && !scrolled && !mobileOpen ? 'text-white/70' : 'text-ink'"
          aria-label="{{ __('Wishlist', 'sage') }}"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/></svg>
          <span class="wishlist-count-bubble absolute -top-1 -right-1 min-w-4 h-4 bg-accent text-ink font-bold rounded-full flex items-center justify-center px-0.5 leading-none text-[10px]" style="display:none"></span>
        </a>

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
                class="block font-semibold tracking-[0.15em] uppercase text-ink/80 hover:text-accent transition-colors duration-200 mb-4"
              >{{ esc_html($cat->name) }}</a>

              @if(!is_wp_error($sub_cats) && !empty($sub_cats))
                <ul class="space-y-2" role="list">
                  @foreach($sub_cats as $sub)
                    <li>
                      <a
                        href="{{ esc_url(get_term_link($sub)) }}"
                        class="flex items-center gap-2 text-muted hover:text-ink transition-colors duration-150"
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
              <p class="    font-semibold tracking-[0.28em] uppercase text-accent mb-5">
                {{ __('In evidenza', 'sage') }}
              </p>
              <h3 class="font-sans text-xl font-light text-white leading-snug mb-3">
                {{ __('Tutto per il tuo animale', 'sage') }}
              </h3>
              <p class="text-white/40 leading-relaxed">
                {{ __('Selezione premium, consegna rapida.', 'sage') }}
              </p>
            </div>
            <a
              href="{{ esc_url(function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop')) }}"
              class="mt-8 btn-slide-light self-start"
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
       DROPDOWN PANELS — nav items with "Megamenu" checkbox
       ════════════════════════════════════════════════════════════════════════ --}}
  @foreach($top_items as $item)
    @php
      $is_mega       = get_post_meta($item->ID, '_menu_item_megamenu', true) === '1';
      $item_children = $children_map[$item->ID] ?? [];
      $mega_id       = 'nav-' . $item->ID;
    @endphp
    @if($is_mega && !empty($item_children))
      <div
        id="mega-{{ $mega_id }}"
        role="region"
        aria-labelledby="btn-mega-{{ $mega_id }}"
        x-show="activeMenu === '{{ $mega_id }}'"
        x-cloak
        @mouseenter="activeMenu = '{{ $mega_id }}'"
        @mouseleave="closeMenu()"
        class="absolute top-full left-0 right-0 bg-surface shadow-[0_16px_60px_rgba(0,0,0,0.08)] overflow-hidden border-b border-border"
        style="display:none; clip-path: inset(0% 0% 100% 0%)"
      >
        <div class="max-w-360 mx-auto px-8 lg:px-12 py-6">
          <ul class="flex flex-wrap gap-x-8 gap-y-1">
            @foreach($item_children as $child)
              <li>
                <a
                  href="{{ esc_url($child->url) }}"
                  class="mega-item block text-sm text-ink/70 hover:text-primary transition-colors py-2"
                >{{ esc_html($child->title) }}</a>
              </li>
            @endforeach
          </ul>
        </div>
      </div>
    @endif
  @endforeach

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
    class="fixed inset-0 top-18 bg-primary z-40 overflow-y-auto flex flex-col lg:hidden"
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
            <span class="font-sans text-2xl font-light text-white tracking-wide">{{ __('Shop', 'sage') }}</span>
            <svg class="w-4 h-4 text-white/30 transition-transform duration-300" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m19 9-7 7-7-7"/></svg>
          </button>
          <div x-show="open" x-collapse class="py-4">
            <div class="grid grid-cols-2 gap-x-4 gap-y-1">
              @foreach($wc_cats as $cat)
                <a
                  href="{{ esc_url(get_term_link($cat)) }}"
                  class="flex items-center gap-2 py-2 font-medium tracking-wide text-white/50 hover:text-accent transition-colors"
                  @click="closeMobile()"
                >
                  <span class="w-1 h-1 bg-accent/40 rounded-full shrink-0" aria-hidden="true"></span>
                  {{ esc_html($cat->name) }}
                </a>
              @endforeach
            </div>
            <a
              href="{{ esc_url(function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop')) }}"
              class="mt-5 inline-flex items-center gap-2     font-semibold tracking-[0.2em] uppercase text-accent"
              @click="closeMobile()"
            >{{ __('Vedi tutto lo shop', 'sage') }} →</a>
          </div>
        </div>
      @endif

      {{-- Regular nav items (or dropdown accordions for megamenu items) ──────── --}}
      @foreach($top_items as $item)
        @php
          $is_mega_mob   = get_post_meta($item->ID, '_menu_item_megamenu', true) === '1';
          $mob_children  = $children_map[$item->ID] ?? [];
        @endphp
        @if($is_mega_mob && !empty($mob_children))
          <div x-data="{ open: false }">
            <button
              type="button"
              @click="open = !open"
              :aria-expanded="open.toString()"
              class="w-full flex items-center justify-between py-5 border-b border-white/8"
            >
              <span class="font-sans text-2xl font-light text-white tracking-wide">{{ esc_html($item->title) }}</span>
              <svg class="w-4 h-4 text-white/30 transition-transform duration-300" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m19 9-7 7-7-7"/></svg>
            </button>
            <div x-show="open" x-collapse class="py-3 space-y-1">
              @foreach($mob_children as $child)
                <a
                  href="{{ esc_url($child->url) }}"
                  class="flex items-center gap-2 py-2 text-[13px] text-white/50 hover:text-accent transition-colors"
                  @click="closeMobile()"
                >
                  <span class="w-1 h-1 bg-accent/40 rounded-full shrink-0" aria-hidden="true"></span>
                  {{ esc_html($child->title) }}
                </a>
              @endforeach
            </div>
          </div>
        @else
          <a
            href="{{ esc_url($item->url) }}"
            class="flex items-center justify-between py-5 border-b border-white/8 font-sans text-2xl font-light text-white hover:text-accent transition-colors tracking-wide"
            @click="closeMobile()"
          >{{ esc_html($item->title) }}</a>
        @endif
      @endforeach

    </nav>

    {{-- Drawer footer ────────────────────────────────────────────────────────── --}}
    <div class="px-6 py-8 border-t border-white/8 space-y-4">
      @if($show_cta)
        <a
          href="{{ $cta_url }}"
          class="block w-full text-center py-4 bg-accent text-ink     font-semibold tracking-[0.22em] uppercase hover:bg-accent/90 transition-colors"
          @click="closeMobile()"
        >{{ esc_html($cta_label) }}</a>
      @endif

      @php
        $social_ig = get_theme_mod('social_instagram', '');
        $social_fb = get_theme_mod('social_facebook',  '');
        $social_tk = get_theme_mod('social_tiktok',    '');
      @endphp
      @if($social_ig || $social_fb || $social_tk)
        <div class="flex items-center gap-5 justify-center pt-1">
          @foreach(array_filter(['instagram' => $social_ig, 'facebook' => $social_fb, 'tiktok' => $social_tk]) as $name => $url)
            <a href="{{ esc_url($url) }}" target="_blank" rel="noopener noreferrer" aria-label="{{ ucfirst($name) }}" class="    font-semibold tracking-[0.15em] uppercase text-white/25 hover:text-accent transition-colors">
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
  class="h-18"
  :class="$store.layout.hasHero ? 'hidden' : 'block'"
  aria-hidden="true"
></div>
