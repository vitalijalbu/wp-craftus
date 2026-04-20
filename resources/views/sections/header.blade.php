{{--
  header.blade.php — Luxury e-commerce header
  Layout:   [Logo ←]  ····················  [Nav | Actions →]
  States:   expanded (top) ↔ scrolled compact (GSAP animated)
  Alpine:   x-data="siteHeader" (registered in app.js)
  GSAP:     $watch('scrolled') drives expand/collapse timeline
--}}

@php
  // ── Resolve data once ────────────────────────────────────────────────────
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
  $custom_logo_id = (int) get_theme_mod('custom_logo');
@endphp

@include('partials.announcement-bar')

<header
  id="site-header"
  x-data="siteHeader"
  @click.outside="closeMenu()"
  class="fixed top-0 left-0 right-0 z-50"
  :class="{ 'header--hero-top': hasHero && !scrolled }"
  role="banner"
>

  {{-- ════════════════════════════════════════════════════════════════════════
       EXPANDED BAR — visible at top of page (collapses on scroll via GSAP)
       ════════════════════════════════════════════════════════════════════════ --}}
  <div
    x-ref="expandedWrapper"
    class="header-expanded border-b transition-colors duration-300"
    :class="{
      'text-white bg-transparent': hasHero && !scrolled,
      'bg-white': !hasHero || scrolled
    }"
  >
    <div class="container flex items-center justify-between h-16">

      {{-- LEFT: Logo ───────────────────────────────────────────────────────── --}}
      <a
        href="{{ esc_url(home_url('/')) }}"
        class="site-logo site-logo--header shrink-0 flex items-center focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-primary"
        aria-label="{{ esc_attr(get_bloginfo('name')) }}"
      >
        @if($custom_logo_id)
          {!! wp_get_attachment_image($custom_logo_id, 'full', false, ['class' => 'custom-logo', 'alt' => get_bloginfo('name'), 'decoding' => 'async', 'fetchpriority' => 'high']) !!}
        @else
          <span
            class="font-sans text-xl font-light tracking-[0.22em] uppercase transition-colors duration-300"
            :class="hasHero && !scrolled ? 'text-white' : 'text-ink'"
          >{{ get_bloginfo('name') }}</span>
        @endif
      </a>

      {{-- RIGHT: Navigation + Actions (desktop) ───────────────────────────── --}}
      <div class="hidden lg:flex items-center gap-8 h-full">

        {{-- Nav links ─────────────────────────────────────────────────────── --}}
        <nav aria-label="{{ __('Menu principale', 'sage') }}" class="flex items-stretch gap-7 h-full">

          @foreach($top_items as $item)
            @php
              $is_mega       = get_post_meta($item->ID, '_menu_item_megamenu', true) === '1';
              $item_children = $children_map[$item->ID] ?? [];
              $mega_id       = 'nav-' . $item->ID;
              $active_classes = [
                'current-menu-item',
                'current_page_item',
                'current-menu-ancestor',
                'current-page-ancestor',
                'current-menu-parent',
                'current_page_parent',
              ];

              $item_classes = (array) ($item->classes ?? []);
              $is_current   = !empty(array_intersect($active_classes, $item_classes));

              // Fallback: exact URL match (useful when WP doesn't assign current-* classes,
              // e.g. custom links to Shop archive).
              $item_path    = untrailingslashit((string) wp_parse_url((string) ($item->url ?? ''), PHP_URL_PATH));
              $current_path = untrailingslashit((string) wp_parse_url((string) home_url(add_query_arg([], $wp->request ?? '')), PHP_URL_PATH));
              if (!$is_current && $item_path && $current_path && $item_path === $current_path) {
                $is_current = true;
              }

              // Front page fallback for custom Home links.
              if (!$is_current && is_front_page()) {
                $item_url = isset($item->url) ? (string) $item->url : '';
                $item_url_no_frag = strtok($item_url, '#') ?: '';
                $item_url_no_qs = strtok($item_url_no_frag, '?') ?: $item_url_no_frag;
                $home_root = trailingslashit(home_url('/'));
                $item_root = trailingslashit($item_url_no_qs);
                if ($item_root && $item_root === $home_root) {
                  $is_current = true;
                }
              }

              // Extra fallback for WooCommerce shop page.
              if (!$is_current && function_exists('is_shop') && is_shop() && function_exists('wc_get_page_permalink')) {
                $shop_path = untrailingslashit((string) wp_parse_url((string) wc_get_page_permalink('shop'), PHP_URL_PATH));
                if ($item_path && $shop_path && $item_path === $shop_path) {
                  $is_current = true;
                }
              }
            @endphp
            @if($is_mega && !empty($item_children))
              <button
                type="button"
                id="btn-mega-{{ $mega_id }}"
                class="nav-link-t flex items-center gap-1 {{ $is_current ? 'active' : '' }}"
                :class="hasHero && !scrolled ? 'text-white/80 hover:text-white' : ''"
                @mouseenter="openMenu('{{ $mega_id }}')"
                @click="openMenu('{{ $mega_id }}')"
                :aria-expanded="(activeMenu === '{{ $mega_id }}').toString()"
                aria-controls="mega-{{ $mega_id }}"
                aria-haspopup="true"
              >
                {{ esc_html($item->title) }}
                <x-icons.chevron-down class="w-2.5 h-2.5 transition-transform duration-200" ::class="activeMenu==='{{ $mega_id }}'?'rotate-180':''" stroke-width="2.5" />
              </button>
            @else
              <a
                href="{{ esc_url($item->url) }}"
                class="nav-link-t {{ $is_current ? 'active' : '' }}"
                :class="hasHero && !scrolled ? 'text-white/80 hover:text-white' : ''"
                @if($is_current) aria-current="page" @endif
              >{{ esc_html($item->title) }}</a>
            @endif
          @endforeach

        </nav>

        {{-- Divider ───────────────────────────────────────────────────────── --}}
        <span class="w-px h-4 bg-current opacity-15" aria-hidden="true"></span>

        {{-- Actions ───────────────────────────────────────────────────────── --}}
        <div class="flex items-center gap-4">

          {{-- Wishlist --}}
          <a
            href="{{ esc_url(home_url('/wishlist')) }}"
            class="icon-btn relative"
            :class="hasHero && !scrolled ? 'text-white/70 hover:text-white' : ''"
            aria-label="{{ __('Wishlist', 'sage') }}"
          >
            <x-icons.heart class="size-6" />
            <span class="icon-badge wishlist-count-bubble"></span>
          </a>

          {{-- Account --}}
          @if(function_exists('WC'))
            <a
              href="{{ esc_url(wc_get_page_permalink('myaccount')) }}"
              class="icon-btn"
              :class="hasHero && !scrolled ? 'text-white/70 hover:text-white' : ''"
              aria-label="{{ __('Il mio account', 'sage') }}"
            >
              <x-icons.user class="size-6" />
            </a>
          @endif

          {{-- Cart --}}
          @if(function_exists('WC'))
            <button
              type="button"
              @click="$dispatch('open-cart')"
              class="icon-btn relative"
              :class="hasHero && !scrolled ? 'text-white/70 hover:text-white' : ''"
              aria-label="{{ __('Apri carrello', 'sage') }}"
            >
              <x-icons.cart class="size-6" />
              <span
                class="icon-badge cart-count-fragment"
                data-cart-count="{{ $cart_count }}"
                data-count="{{ $cart_count }}"
                :class="cartCount === 0 ? 'opacity-0 pointer-events-none' : 'opacity-100'"
                x-text="cartCount"
              >{{ $cart_count }}</span>
            </button>
          @endif

          {{-- CTA --}}
          @if($show_cta)
            <a
              href="{{ esc_url($cta_url) }}"
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
            <x-icons.cart class="size-5" />
            <span
              class="icon-badge cart-count-fragment"
              data-cart-count="{{ $cart_count }}"
              data-count="{{ $cart_count }}"
              :class="cartCount === 0 ? 'opacity-0 pointer-events-none' : 'opacity-100'"
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
          <x-icons.heart class="size-5" />
          <span class="icon-badge wishlist-count-bubble"></span>
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
          <x-icons.menu x-show="!mobileOpen" class="size-6" />
          <x-icons.x-mark x-show="mobileOpen" class="size-6" stroke-width="1.5" />
        </button>
      </div>

    </div>
  </div>

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
        class="absolute top-full left-0 right-0 bg-surface shadow-[0_16px_60px_rgba(0,0,0,0.08)] overflow-hidden border-b border-border mega-clip-enter"
        x-cloak
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
    class="fixed inset-0 top-18 bg-ink z-40 overflow-y-auto flex flex-col lg:hidden"
    x-cloak
    role="dialog"
    aria-modal="true"
    aria-label="{{ __('Menu di navigazione', 'sage') }}"
  >
    <nav class="flex-1 px-6 py-8 space-y-0.5" aria-label="{{ __('Menu mobile', 'sage') }}">

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
              <x-icons.chevron-down class="size-4 text-white/30 transition-transform duration-300" ::class="open ? 'rotate-180' : ''" />
            </button>
            <div x-show="open" x-collapse class="py-3 space-y-1">
              @foreach($mob_children as $child)
                <a
                  href="{{ esc_url($child->url) }}"
                  class="flex items-center gap-2 py-2 text-[13px] text-white/50 hover:text-primary transition-colors"
                  @click="closeMobile()"
                >
                  <span class="w-1 h-1 bg-primary/40 rounded-full shrink-0" aria-hidden="true"></span>
                  {{ esc_html($child->title) }}
                </a>
              @endforeach
            </div>
          </div>
        @else
          <a
            href="{{ esc_url($item->url) }}"
            class="flex items-center justify-between py-5 border-b border-white/8 font-sans text-2xl font-light text-white hover:text-primary transition-colors tracking-wide"
            @click="closeMobile()"
          >{{ esc_html($item->title) }}</a>
        @endif
      @endforeach

    </nav>

    {{-- Drawer footer ────────────────────────────────────────────────────────── --}}
    <div class="px-6 py-8 border-t border-white/8 space-y-4">
      @if($show_cta)
        <a
          href="{{ esc_url($cta_url) }}"
          class="block w-full text-center py-4 bg-primary text-white font-semibold tracking-[0.22em] uppercase hover:bg-primary-dark transition-colors"
          @click="closeMobile()"
        >{{ esc_html($cta_label) }}</a>
      @endif

      @php
        $mob_socials = array_filter([
          'instagram' => ['label' => 'Instagram',   'url' => get_theme_mod('social_instagram', '')],
          'facebook'  => ['label' => 'Facebook',    'url' => get_theme_mod('social_facebook',  '')],
          'tiktok'    => ['label' => 'TikTok',      'url' => get_theme_mod('social_tiktok',    '')],
          'youtube'   => ['label' => 'YouTube',     'url' => get_theme_mod('social_youtube',   '')],
          'twitter'   => ['label' => 'X',           'url' => get_theme_mod('social_twitter',   '')],
        ], fn($s) => !empty($s['url']));
        $mob_wa_url = function_exists('App\\theme_whatsapp_url') ? \App\theme_whatsapp_url() : '';
      @endphp
      @if(!empty($mob_socials) || $mob_wa_url)
        <div class="flex items-center gap-5 justify-center flex-wrap pt-1">
          @foreach($mob_socials as $social)
            <a href="{{ esc_url($social['url']) }}" target="_blank" rel="noopener noreferrer" aria-label="{{ esc_attr($social['label']) }}" class="font-semibold tracking-[0.15em] uppercase text-white/25 hover:text-primary transition-colors">
              {{ $social['label'] }}
            </a>
          @endforeach
          @if($mob_wa_url)
            <a href="{{ esc_url($mob_wa_url) }}" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp" class="font-semibold tracking-[0.15em] uppercase text-white/25 hover:text-primary transition-colors">
              WhatsApp
            </a>
          @endif
        </div>
      @endif
    </div>
  </div>

</header>
