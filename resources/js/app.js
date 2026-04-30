import.meta.glob(['../images/**', '../fonts/**'])

import Collapse from '@alpinejs/collapse'
import Focus from '@alpinejs/focus'
// ── Alpine.js ────────────────────────────────────────────────────────────────
import Alpine from 'alpinejs'
import { initQuantitySelectors } from './modules/quantity.js'
import './modules/wishlist.js'

// ── Accessibility: reduced motion ─────────────────────────────────────────────
const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches
const THEME_API_BASE = (window.themeData?.themeApiBase ?? '/wp-json/theme/v1').replace(/\/$/, '')

const hasAny = (selector) => document.querySelector(selector) !== null

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
// Dual-state (expanded ↔ scrolled) gestito via classi Alpine.
Alpine.data('siteHeader', () => ({
  mobileOpen: false,
  activeMenu: null,
  scrolled: true,

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
  },

  closeMenu() {
    if (!this.activeMenu) {
      return
    }
    document.getElementById('site-header')?.classList.remove('header-mega-open')
    this.activeMenu = null
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

  // ── Init: scroll + keyboard ─────────────────────────────────────────────────
  init() {
    // Header remains in fixed visual state (no scroll collapse behavior).
    this._scrollCtrl = new AbortController()
    this.scrolled = true

    // ── Misura l'altezza reale dell'header e aggiorna --header-height ────────
    const el = document.getElementById('site-header')
    const updateHeaderHeight = () => {
      if (el) {
        document.documentElement.style.setProperty('--header-height', el.offsetHeight + 'px')
      }
    }
    updateHeaderHeight()
    this._headerRO = new ResizeObserver(updateHeaderHeight)
    if (el) {
      this._headerRO.observe(el)
    }

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

    // Background change is handled by Alpine :class binding.
  },

  destroy() {
    this._scrollCtrl?.abort()
    this._headerRO?.disconnect()
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
          // Open drawer after fragments are replaced so cart content is visible
          this.open()
        } else {
          // No fragments provided — refresh async, open drawer once complete
          this.refreshFragment(() => this.open())
        }
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

  refreshFragment(onComplete) {
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
        onComplete?.()
      },
    )
  },
}))

// ── Alpine component: newsletter form ────────────────────────────────────────
Alpine.data('newsletterForm', (restUrl, nonce) => ({
  email: '',
  loading: false,
  success: false,
  error: '',

  async submit() {
    this.error = ''
    if (!this.email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.email)) {
      this.error = window.themeI18n?.invalidEmail ?? 'Inserisci un indirizzo email valido.'
      return
    }
    this.loading = true
    try {
      const res = await fetch(restUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': nonce },
        body: JSON.stringify({ email: this.email }),
      })
      const data = await res.json()
      if (res.ok && data.success) {
        this.success = true
        this.email = ''
      } else {
        this.error =
          data.message || (window.themeI18n?.genericError ?? 'Si è verificato un errore.')
      }
    } catch {
      this.error = window.themeI18n?.networkError ?? 'Errore di rete. Riprova.'
    } finally {
      this.loading = false
    }
  },
}))

// ── Alpine component: recently viewed products (localStorage) ─────────────────
Alpine.data('recentlyViewed', (excludeId = 0) => ({
  items: [],

  load() {
    try {
      const stored = JSON.parse(localStorage.getItem('theme:recently-viewed') || '[]')
      this.items = stored.filter((item) => item.id !== excludeId).slice(0, 6)
    } catch {
      this.items = []
    }
  },

  clear() {
    localStorage.removeItem('theme:recently-viewed')
    this.items = []
  },
}))

// ── Alpine component: products grid (AJAX filters/load-more) ────────────────
Alpine.data('productsGrid', (config = {}) => ({
  activeCategory: config.activeCategory ?? 'all',
  products: Array.isArray(config.products) ? config.products : [],
  selectedCats: Array.isArray(config.selectedCats) ? config.selectedCats : [],
  page: 1,
  perPage: Number(config.perPage ?? 12),
  loading: false,
  hasMore: Boolean(config.hasMore),
  statusMsg: '',
  endpoint: config.endpoint ?? `${THEME_API_BASE}/products`,
  orderby: config.orderby ?? 'date',
  categoryMap: config.categoryMap ?? {},
  minPrice: 0,
  maxPrice: 0,
  inStockOnly: false,
  _filtersListener: null,
  _fetchCtrl: null,
  _requestSeq: 0,

  init() {
    if (this.activeCategory !== 'all') {
      this.selectedCats = this.resolveCategoryIds(this.activeCategory)
    }

    this._filtersListener = (event) => {
      const detail = event.detail ?? {}
      const cats = Array.isArray(detail.cats)
        ? detail.cats.map((id) => Number(id)).filter(Number.isFinite)
        : []

      this.selectedCats = cats
      this.activeCategory = cats.length === 1 ? cats[0] : 'all'
      this.minPrice = Number(detail.min_price ?? 0) || 0
      this.maxPrice = Number(detail.max_price ?? 0) || 0
      this.inStockOnly = Boolean(detail.in_stock)

      this.page = 1
      this.fetchProducts(true)
    }

    window.addEventListener('product-filter-changed', this._filtersListener)
  },

  destroy() {
    if (this._filtersListener) {
      window.removeEventListener('product-filter-changed', this._filtersListener)
    }
    this._fetchCtrl?.abort()
  },

  resolveCategoryIds(category) {
    if (category === 'all' || category === null || category === undefined) {
      return []
    }

    if (Number.isFinite(Number(category)) && Number(category) > 0) {
      return [Number(category)]
    }

    const mapped = this.categoryMap?.[String(category)]
    if (Number.isFinite(Number(mapped)) && Number(mapped) > 0) {
      return [Number(mapped)]
    }

    return []
  },

  async filterByCategory(category) {
    this.activeCategory = category
    this.selectedCats = this.resolveCategoryIds(category)
    this.minPrice = 0
    this.maxPrice = 0
    this.inStockOnly = false
    this.page = 1
    await this.fetchProducts(true)
  },

  async loadMore() {
    if (this.loading || !this.hasMore) {
      return
    }

    const nextPage = this.page + 1
    this.page = nextPage
    const ok = await this.fetchProducts(false)

    if (!ok) {
      this.page = nextPage - 1
    }
  },

  async fetchProducts(reset) {
    const requestSeq = ++this._requestSeq
    this._fetchCtrl?.abort()
    this._fetchCtrl = new AbortController()

    const params = new URLSearchParams({
      per_page: this.perPage,
      page: this.page,
      orderby: this.orderby,
    })

    const categoryIds = this.selectedCats.length
      ? this.selectedCats
      : this.resolveCategoryIds(this.activeCategory)

    categoryIds.forEach((id) => {
      params.append('cats[]', String(id))
    })

    if (this.minPrice > 0) {
      params.set('min_price', String(this.minPrice))
    }

    if (this.maxPrice > 0) {
      params.set('max_price', String(this.maxPrice))
    }

    if (this.inStockOnly) {
      params.set('in_stock', '1')
    }

    this.loading = true

    try {
      const resp = await fetch(`${this.endpoint}?${params.toString()}`, {
        credentials: 'same-origin',
        signal: this._fetchCtrl.signal,
      })

      if (!resp.ok) {
        throw new Error('Products fetch failed')
      }

      const payload = await resp.json()
      if (requestSeq !== this._requestSeq) {
        return false
      }

      const items = Array.isArray(payload?.products) ? payload.products : []
      const pages = Number(payload?.pages ?? 0)
      const total = Number(payload?.total ?? 0)

      if (reset) {
        this.products = items
      } else {
        this.products = [...this.products, ...items]
      }

      this.hasMore = pages > 0 && this.page < pages
      if (total === 0) {
        this.statusMsg = 'Nessun prodotto trovato'
      } else if (reset) {
        this.statusMsg = `${total} prodotti trovati`
      } else {
        this.statusMsg = `${this.products.length} di ${total} prodotti caricati`
      }

      this.$nextTick(() => {
        if (window.ScrollTrigger) {
          window.ScrollTrigger.refresh()
        }

        if (typeof window.initWishlistButtons === 'function') {
          window.initWishlistButtons()
        }
      })

      return true
    } catch (err) {
      if (requestSeq !== this._requestSeq || err?.name === 'AbortError') {
        return false
      }

      this.statusMsg = window.themeI18n?.networkError ?? 'Errore di rete. Riprova.'

      return false
    } finally {
      if (requestSeq === this._requestSeq) {
        this.loading = false
      }
    }
  },
}))

/**
 * Call on every product page to persist the product in localStorage.
 * @param {{ id: number, url: string, title: string, thumb: string, price: string }} product
 */
window.trackProductView = function (product) {
  if (!product?.id) {
    return
  }
  try {
    const KEY = 'theme:recently-viewed'
    const stored = JSON.parse(localStorage.getItem(KEY) || '[]')
    const filtered = stored.filter((p) => p.id !== product.id)
    filtered.unshift(product)
    localStorage.setItem(KEY, JSON.stringify(filtered.slice(0, 20)))
  } catch (_e) {
    // localStorage unavailable (private mode / storage full)
  }
}

// ── Alpine component: before/after slider ────────────────────────────────────
Alpine.data('beforeAfter', () => ({
  pos: 50,
  dragging: false,
  _el: null,

  startDrag(e) {
    this.dragging = true
    this._el = e.currentTarget
    this.updatePos(e)
  },

  drag(e) {
    if (!this.dragging) {
      return
    }
    this.updatePos(e)
  },

  stopDrag() {
    this.dragging = false
  },

  updatePos(e) {
    if (!this._el) {
      return
    }
    const rect = this._el.getBoundingClientRect()
    const clientX = e.touches ? e.touches[0].clientX : e.clientX
    this.pos = Math.min(100, Math.max(0, ((clientX - rect.left) / rect.width) * 100))
  },
}))

window.Alpine = Alpine

// ── Bootstrap ─────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', async () => {
  const needsCarousels = hasAny(
    '.js-hero-swiper, .js-products-swiper, .js-testimonials-swiper, .js-generic-swiper, .js-logos-swiper, .js-product-gallery, [x-data^="productLightbox"]',
  )

  let initCarousels = null
  if (needsCarousels) {
    const carouselModule = await import('./modules/carousel.js')
    initCarousels = carouselModule.initCarousels
  }

  Alpine.start()

  if (initCarousels) {
    initCarousels()
  }

  initQuantitySelectors()

  if (!prefersReducedMotion) {
    if (
      hasAny(
        '[data-scroll], [data-parallax], [data-clip-reveal], [data-line-reveal], [data-stagger-grid], [data-counter], .js-marquee-track, [data-h-scroll]',
      )
    ) {
      const [animationsModule, scrollEffectsModule] = await Promise.all([
        import('./modules/animations.js'),
        import('./modules/scroll-effects.js'),
      ])
      animationsModule.initLuxuryAnimations()
      scrollEffectsModule.initScrollEffects()
    }

    if (hasAny('[data-magnetic]')) {
      const magneticModule = await import('./modules/magnetic-hover.js')
      magneticModule.initMagneticHover()
    }
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
