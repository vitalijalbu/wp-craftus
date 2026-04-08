import.meta.glob(['../images/**', '../fonts/**'])

import Collapse from '@alpinejs/collapse'
import Focus from '@alpinejs/focus'
// ── Alpine.js ────────────────────────────────────────────────────────────────
import Alpine from 'alpinejs'

// ── GSAP ─────────────────────────────────────────────────────────────────────
import { gsap } from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'
import { initCarousels } from './modules/carousel.js'
// ── Modules ───────────────────────────────────────────────────────────────────
import { initLuxuryAnimations } from './modules/luxury-animations.js'
import { initMagneticHover } from './modules/magnetic-hover.js'
import { initScrollEffects } from './modules/scroll-effects.js'
import './modules/wishlist.js'

// ── Accessibility: reduced motion ─────────────────────────────────────────────
const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches

if (!prefersReducedMotion) {
  gsap.registerPlugin(ScrollTrigger)
  window.ScrollTrigger = ScrollTrigger
} else {
  window.ScrollTrigger = {
    refresh: () => {
      /* noop */
    },
    update: () => {
      /* noop */
    },
  }
}

window.gsap = gsap

// ── Alpine plugins ────────────────────────────────────────────────────────────
Alpine.plugin(Collapse)
Alpine.plugin(Focus)

// ── Alpine store: shared layout state ─────────────────────────────────────────
Alpine.store('layout', {
  hasHero: false,
  cartCount: 0,
  init() {
    this.hasHero = document.body.classList.contains('has-hero')
    this.cartCount = parseInt(
      document.querySelector('[data-cart-count]')?.dataset.cartCount ?? '0',
      10,
    )
    // Sync cart count when WooCommerce refreshes fragments via AJAX
    document.body.addEventListener('wc_fragments_refreshed', () => {
      const el = document.querySelector('[data-cart-count]')
      if (el) {
        this.cartCount = parseInt(el.dataset.cartCount ?? '0', 10)
      }
    })
  },
})

// ── Alpine component: site header ─────────────────────────────────────────────
// Dual-state (expanded ↔ scrolled) with GSAP timeline — same pattern as Madison.
Alpine.data('siteHeader', () => ({
  mobileOpen: false,
  activeMenu: null,
  scrolled: false,

  get hasHero() {
    return this.$store.layout.hasHero
  },
  get cartCount() {
    return this.$store.layout.cartCount
  },

  // ── Mega-menu ──────────────────────────────────────────────────────────────
  openMenu(id) {
    if (this.activeMenu === id) {
      this.closeMenu()
      return
    }
    this.activeMenu = id
    document.getElementById('site-header')?.classList.add('header-mega-open')
    this.$nextTick(() => {
      const panel = document.getElementById('mega-' + id)
      if (!panel) {
        return
      }
      gsap.fromTo(
        panel,
        { clipPath: 'inset(0% 0% 100% 0%)', opacity: 0 },
        { clipPath: 'inset(0% 0% 0% 0%)', opacity: 1, duration: 0.42, ease: 'expo.out' },
      )
      gsap.fromTo(
        panel.querySelectorAll('.mega-item'),
        { opacity: 0, y: 14 },
        { opacity: 1, y: 0, duration: 0.38, ease: 'expo.out', stagger: 0.04, delay: 0.08 },
      )
    })
  },

  closeMenu() {
    if (!this.activeMenu) {
      return
    }
    const id = this.activeMenu
    const panel = document.getElementById('mega-' + id)
    document.getElementById('site-header')?.classList.remove('header-mega-open')
    if (!panel) {
      this.activeMenu = null
      return
    }
    gsap.to(panel, {
      clipPath: 'inset(0% 0% 100% 0%)',
      opacity: 0,
      duration: 0.28,
      ease: 'expo.in',
      onComplete: () => {
        this.activeMenu = null
      },
    })
  },

  // ── Mobile ─────────────────────────────────────────────────────────────────
  toggleMobile() {
    this.mobileOpen = !this.mobileOpen
    document.body.classList.toggle('overflow-hidden', this.mobileOpen)
  },
  closeMobile() {
    this.mobileOpen = false
    document.body.classList.remove('overflow-hidden')
  },

  // ── Init: scroll + keyboard + GSAP dual-state watcher ──────────────────────
  init() {
    // Scroll detection — AbortController enables cleanup if component is destroyed
    this._scrollCtrl = new AbortController()
    window.addEventListener(
      'scroll',
      () => {
        const sy = window.scrollY
        if (!this.scrolled && sy > 80) {
          this.scrolled = true
        }
        if (this.scrolled && sy < 35) {
          this.scrolled = false
        }
      },
      { passive: true, signal: this._scrollCtrl.signal },
    )

    // Escape key
    document.addEventListener(
      'keydown',
      (e) => {
        if (e.key !== 'Escape') {
          return
        }
        this.closeMenu()
        this.closeMobile()
      },
      { signal: this._scrollCtrl.signal },
    )

    // no GSAP swap — background change is handled by Alpine :class binding
  },

  destroy() {
    this._scrollCtrl?.abort()
  },
}))

// ── Alpine component: search overlay with live results ────────────────────────
Alpine.data('searchOverlay', () => ({
  open: false,
  query: '',
  results: [],
  totalCount: 0,
  loading: false,
  noResults: false,
  _abortCtrl: null,

  show() {
    this.open = true
    this.$nextTick(() => this.$refs.input?.focus())
  },
  hide() {
    this.open = false
    this.query = ''
    this.results = []
    this.noResults = false
  },
  submit() {
    if (!this.query.trim()) {
      return
    }
    window.location.href = `/?s=${encodeURIComponent(this.query.trim())}`
  },

  async fetchResults() {
    const q = this.query.trim()
    if (q.length < 2) {
      this.results = []
      this.noResults = false
      return
    }
    // Cancel any in-flight request
    if (this._abortCtrl) {
      this._abortCtrl.abort()
    }
    this._abortCtrl = new AbortController()

    this.loading = true
    this.noResults = false
    try {
      const base = window.themeRestUrl || '/wp-json/theme/v1'
      const url = `${base}/search?q=${encodeURIComponent(q)}&per_page=6`
      const res = await fetch(url, { signal: this._abortCtrl.signal })
      if (!res.ok) {
        throw new Error('Network error')
      }
      const data = await res.json()
      this.results = data.results ?? []
      this.totalCount = data.count ?? 0
      this.noResults = this.results.length === 0
    } catch (err) {
      if (err.name !== 'AbortError') {
        this.results = []
        this.noResults = false
      }
    } finally {
      this.loading = false
    }
  },

  init() {
    window.addEventListener('open-search', () => this.show())
  },
}))

// ── Alpine component: cart drawer (WooCommerce off-canvas) ────────────────────
Alpine.data('cartDrawer', () => ({
  isOpen: false,
  count: 0,
  loading: false,

  init() {
    // Read initial count from the fragment span rendered server-side
    const countEl = document.querySelector('[data-cart-count]')
    if (countEl) {
      this.count = parseInt(countEl.dataset.cartCount || '0', 10)
    }

    // WooCommerce fires added_to_cart as a jQuery custom event, not a native DOM event
    if (typeof jQuery !== 'undefined') {
      jQuery(document.body).on('added_to_cart', (e, fragments, cart_hash) => {
        if (fragments) {
          jQuery.each(fragments, (selector, html) => jQuery(selector).replaceWith(html))
          const el = document.querySelector('[data-cart-count]')
          if (el) {
            this.count = parseInt(el.dataset.cartCount || '0', 10)
          }
          this.loading = false
        } else {
          this.refreshFragment()
        }
        this.open()
      })

      // WC fragment refresh (e.g. after removing an item)
      jQuery(document.body).on('wc_fragment_refresh', () => this.refreshFragment())
    }
  },

  open() {
    this.isOpen = true
  },
  close() {
    this.isOpen = false
  },

  refreshFragment() {
    if (typeof jQuery === 'undefined') {
      return
    }
    this.loading = true
    jQuery.post(
      window.wc_cart_fragments_params?.ajax_url ?? '/wp-admin/admin-ajax.php',
      { action: 'woocommerce_get_refreshed_fragments' },
      (res) => {
        if (res?.fragments) {
          jQuery.each(res.fragments, (key, val) => jQuery(key).replaceWith(val))
        }
        const el = document.querySelector('[data-cart-count]')
        if (el) {
          this.count = parseInt(el.dataset.cartCount || '0', 10)
        }
        this.loading = false
      },
    )
  },
}))

window.Alpine = Alpine

// ── Bootstrap ─────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  Alpine.start()
  initCarousels()

  if (!prefersReducedMotion) {
    initLuxuryAnimations()
    initScrollEffects()
    initMagneticHover()
  } else {
    // Run non-animated fallbacks (still register scroll effects as visible)
    document
      .querySelectorAll('[data-scroll], [data-parallax], [data-clip-reveal]')
      .forEach((el) => {
        el.style.opacity = '1'
        el.style.transform = 'none'
      })
  }
})
