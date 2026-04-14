/**
 * Swiper carousel instances.
 * Each selector initializes with luxury defaults.
 */

import Swiper from 'swiper'
import {
  A11y,
  Autoplay,
  EffectFade,
  FreeMode,
  Navigation,
  Pagination,
  Scrollbar,
  Thumbs,
} from 'swiper/modules'

// ── Alpine.js ────────────────────────────────────────────────────────────────
import Alpine from 'alpinejs'

// ── Alpine component: product lightbox ────────────────────────────────────────
Alpine.data('productLightbox', (imagesJson, productTitle) => ({
  open: false,
  current: 0,
  images: imagesJson || [],
  _trigger: null,

  show(i, el) {
    this._trigger = el ?? null
    this.current = i
    this.open = true
    document.body.style.overflow = 'hidden'
    this.$nextTick(() => this.$refs.lbClose?.focus())
  },

  close() {
    this.open = false
    document.body.style.overflow = ''
    this.$nextTick(() => this._trigger?.focus())
  },

  prev() {
    this.current = (this.current - 1 + this.images.length) % this.images.length
  },

  next() {
    this.current = (this.current + 1) % this.images.length
  },

  trapFocus(e) {
    if (!this.open) return
    const el = this.$refs.lbDialog
    if (!el) return
    const focusable = Array.from(
      el.querySelectorAll(
        "button, [href], input, select, textarea, [tabindex]:not([tabindex='-1'])",
      ),
    ).filter((n) => !n.hasAttribute('disabled'))
    if (!focusable.length) return
    const first = focusable[0]
    const last = focusable[focusable.length - 1]
    if (e.shiftKey) {
      if (document.activeElement === first) {
        e.preventDefault()
        last.focus()
      }
    } else {
      if (document.activeElement === last) {
        e.preventDefault()
        first.focus()
      }
    }
  },
}))

export function initCarousels() {
  // ── Hero carousel ─────────────────────────────────────────────────────────
  document.querySelectorAll('.js-hero-swiper').forEach((el) => {
    new Swiper(el, {
      modules: [Navigation, Pagination, Autoplay, EffectFade, A11y],
      effect: 'fade',
      fadeEffect: { crossFade: true },
      autoplay: { delay: 5500, disableOnInteraction: false, pauseOnMouseEnter: true },
      loop: true,
      speed: 1000,
      pagination: { el: el.querySelector('.swiper-pagination'), clickable: true },
      navigation: {
        nextEl: el.querySelector('.swiper-button-next'),
        prevEl: el.querySelector('.swiper-button-prev'),
      },
      a11y: {
        prevSlideMessage: 'Slide precedente',
        nextSlideMessage: 'Slide successiva',
        paginationBulletMessage: 'Vai alla slide {{index}}',
      },
    })
  })

  // ── Products carousel ─────────────────────────────────────────────────────
  document.querySelectorAll('.js-products-swiper').forEach((el) => {
    new Swiper(el, {
      modules: [Navigation, Pagination, FreeMode, Scrollbar, A11y],
      slidesPerView: 1.15,
      spaceBetween: 16,
      freeMode: { enabled: true, sticky: false },
      scrollbar: {
        el: el.closest('[data-products-carousel]')?.querySelector('.swiper-scrollbar'),
        draggable: true,
      },
      navigation: {
        nextEl: el.closest('[data-products-carousel]')?.querySelector('.swiper-button-next'),
        prevEl: el.closest('[data-products-carousel]')?.querySelector('.swiper-button-prev'),
      },
      breakpoints: {
        640: { slidesPerView: 2.2, spaceBetween: 20 },
        1024: { slidesPerView: 4, spaceBetween: 24 },
        1280: { slidesPerView: 6, spaceBetween: 24 },
      },
      a11y: {
        prevSlideMessage: 'Prodotto precedente',
        nextSlideMessage: 'Prodotto successivo',
      },
    })
  })

  // ── Testimonials carousel ─────────────────────────────────────────────────
  document.querySelectorAll('.js-testimonials-swiper').forEach((el) => {
    new Swiper(el, {
      modules: [Navigation, Pagination, Autoplay, A11y],
      slidesPerView: 1,
      spaceBetween: 32,
      autoplay: { delay: 6000, disableOnInteraction: false, pauseOnMouseEnter: true },
      loop: true,
      speed: 800,
      pagination: { el: el.querySelector('.swiper-pagination'), clickable: true },
      navigation: {
        nextEl: el.closest('[data-testimonials]')?.querySelector('.swiper-button-next'),
        prevEl: el.closest('[data-testimonials]')?.querySelector('.swiper-button-prev'),
      },
      breakpoints: {
        768: { slidesPerView: 2, spaceBetween: 32 },
        1024: { slidesPerView: 3, spaceBetween: 40 },
      },
      a11y: {
        prevSlideMessage: 'Recensione precedente',
        nextSlideMessage: 'Recensione successiva',
        paginationBulletMessage: 'Vai alla recensione {{index}}',
      },
    })
  })

  // ── Generic carousel (data-swiper-options JSON) ───────────────────────────
  document.querySelectorAll('.js-generic-swiper').forEach((el) => {
    const opts = JSON.parse(el.dataset.swiperOptions ?? '{}')
    new Swiper(el, {
      modules: [Navigation, Pagination, Autoplay, FreeMode, A11y],
      slidesPerView: opts.perView ?? 1,
      spaceBetween: opts.gap ?? 24,
      loop: opts.loop ?? false,
      autoplay: opts.autoplay
        ? { delay: opts.autoplayDelay ?? 4000, pauseOnMouseEnter: true }
        : false,
      pagination: { el: el.querySelector('.swiper-pagination'), clickable: true },
      navigation: {
        nextEl: el.querySelector('.swiper-button-next'),
        prevEl: el.querySelector('.swiper-button-prev'),
      },
      breakpoints: opts.breakpoints ?? {},
      a11y: {
        prevSlideMessage: 'Slide precedente',
        nextSlideMessage: 'Slide successiva',
        paginationBulletMessage: 'Vai alla slide {{index}}',
      },
    })
  })

  // ── Logo carousel (brand / partner logos, FreeMode autoplay) ────────────────
  document.querySelectorAll('.js-logos-swiper').forEach((el) => {
    new Swiper(el, {
      modules: [FreeMode, Autoplay, A11y],
      slidesPerView: 2.5,
      spaceBetween: 32,
      loop: true,
      freeMode: { enabled: true },
      autoplay: { delay: 0, disableOnInteraction: false },
      speed: 4000,
      breakpoints: {
        480: { slidesPerView: 3, spaceBetween: 40 },
        768: { slidesPerView: 4.5, spaceBetween: 48 },
        1024: { slidesPerView: 6, spaceBetween: 56 },
      },
      a11y: { enabled: false },
    })
  })

  // ── Product gallery (Swiper + Thumbs) ─────────────────────────────────────
  document.querySelectorAll('.js-product-gallery').forEach((el) => {
    const wrapper = el.closest('.product-gallery')
    const thumbsEl = wrapper?.querySelector('.js-product-thumbs')

    // Init thumbs swiper first (if gallery has > 1 image)
    let thumbsSwiper = null
    if (thumbsEl) {
      thumbsSwiper = new Swiper(thumbsEl, {
        modules: [FreeMode, A11y],
        slidesPerView: 4,
        spaceBetween: 8,
        freeMode: true,
        watchSlidesProgress: true,
        breakpoints: {
          640: { slidesPerView: 5, spaceBetween: 10 },
          1024: { slidesPerView: 5, spaceBetween: 12 },
        },
        a11y: {
          slideRole: 'tab',
          slideLabelMessage: 'Miniatura {{index}} di {{slidesLength}}',
        },
      })
    }

    // Main gallery swiper
    const mainSwiper = new Swiper(el, {
      modules: [Navigation, EffectFade, Thumbs, A11y],
      effect: 'fade',
      fadeEffect: { crossFade: true },
      speed: 600,
      loop: false,
      navigation: {
        prevEl: wrapper?.querySelector('.product-gallery__prev'),
        nextEl: wrapper?.querySelector('.product-gallery__next'),
      },
      thumbs: thumbsSwiper ? { swiper: thumbsSwiper } : undefined,
      a11y: {
        prevSlideMessage: 'Immagine precedente',
        nextSlideMessage: 'Immagine successiva',
      },
      on: {
        slideChange(swiper) {
          const counter = wrapper?.querySelector('.js-gallery-current')
          if (counter) {
            counter.textContent = swiper.activeIndex + 1
          }
        },
      },
    })

    // Store reference for potential external use
    el._swiper = mainSwiper
  })
}
