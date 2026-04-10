# 11 — JavaScript & Animazioni

Entry point: `resources/js/app.js`
Build: Vite con code splitting per vendor

---

## Architettura JS

```
app.js
├── Alpine.js boot (con plugin Collapse + Focus)
├── Alpine components registrati:
│   ├── siteHeader        → header scroll + mega-menu
│   ├── searchOverlay     → overlay ricerca
│   └── (altri se aggiunti)
├── Alpine stores:
│   └── layout            → { hasHero }
└── Import moduli:
    ├── modules/gsap-scroll.js     → GSAP + ScrollTrigger animations
    └── modules/carousel.js        → Swiper
```

---

## Alpine.js

### Boot e registrazione componenti

```js
// resources/js/app.js
import Alpine from 'alpinejs'
import Collapse from '@alpinejs/collapse'
import Focus    from '@alpinejs/focus'

Alpine.plugin(Collapse)
Alpine.plugin(Focus)

// Componente siteHeader
Alpine.data('siteHeader', () => ({
    scrolled:   false,
    hasHero:    document.body.classList.contains('has-hero'),
    activeMenu: null,
    mobileOpen: false,

    init() {
        // Ascolta scroll per aggiornare `scrolled`
        window.addEventListener('scroll', () => {
            this.scrolled = window.scrollY > 80
        }, { passive: true })
    },

    openMenu(id)   { this.activeMenu = id },
    closeMenu()    { this.activeMenu = null },
    toggleMobile() { this.mobileOpen = !this.mobileOpen },
    closeMobile()  { this.mobileOpen = false },
}))

// Componente searchOverlay
Alpine.data('searchOverlay', () => ({
    open:  false,
    query: '',

    init() {
        window.addEventListener('open-search', () => {
            this.open = true
            this.$nextTick(() => this.$refs.input?.focus())
        })
    },

    hide()   { this.open = false; this.query = '' },
    submit() {
        if (this.query.trim()) {
            window.location.href = `/?s=${encodeURIComponent(this.query)}`
        }
    },
}))

// Alpine Store condiviso
Alpine.store('layout', {
    hasHero: document.body.classList.contains('has-hero'),
})

Alpine.start()
```

### Aggiungere un nuovo componente Alpine

```js
// In app.js, prima di Alpine.start()
Alpine.data('mioComponente', () => ({
    aperto: false,
    toggle() { this.aperto = !this.aperto },
}))
```

In Blade:
```blade
<div x-data="mioComponente">
  <button @click="toggle()">Apri</button>
  <div x-show="aperto" x-collapse>
    Contenuto...
  </div>
</div>
```

### Comunicazione tra componenti (eventi Alpine)

```js
// Emette un evento
this.$dispatch('open-search')

// Ascolta un evento
@window-open-search.window="open = true"
// oppure
window.addEventListener('open-search', () => { ... })
```

---

## GSAP + ScrollTrigger

Modulo: `resources/js/modules/gsap-scroll.js` (o `scroll-effects.js`)

### Pattern base

```js
import { gsap } from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'

gsap.registerPlugin(ScrollTrigger)

// Animazione header al scroll
function initHeaderScroll() {
    const expanded  = document.querySelector('.header-expanded')
    const scrollBar = document.querySelector('.header-scrolled-bar')
    if (!expanded || !scrollBar) return

    ScrollTrigger.create({
        start: 'top+=80 top',
        onEnter:      () => showScrolledBar(expanded, scrollBar),
        onLeaveBack:  () => showExpandedBar(expanded, scrollBar),
    })
}
```

### Animazioni scroll su elementi

Usa il data attribute `data-scroll` per triggherare animazioni da CSS o JS:

```blade
<h1 data-scroll="text-reveal">Titolo</h1>
<div data-scroll="slide-up">Contenuto</div>
<div data-scroll="line-in" aria-hidden="true"></div>
```

```js
// In gsap-scroll.js
document.querySelectorAll('[data-scroll="slide-up"]').forEach(el => {
    gsap.from(el, {
        y: 40,
        opacity: 0,
        duration: 0.8,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: el,
            start: 'top 85%',
        }
    })
})
```

### Animazione header GSAP

L'header ha due barre (`header-expanded` e `header-scrolled-bar`). GSAP le anima alla soglia di scroll:

```js
function showScrolledBar(expanded, bar) {
    gsap.to(expanded, { opacity: 0, y: -10, duration: 0.2 })
    gsap.set(bar, { display: 'block' })
    gsap.from(bar, { opacity: 0, y: -10, duration: 0.3 })
}

function showExpandedBar(expanded, bar) {
    gsap.to(bar, { opacity: 0, duration: 0.2, onComplete: () => gsap.set(bar, { display: 'none' }) })
    gsap.set(expanded, { opacity: 1, y: 0 })
}
```

---

## Swiper (Carousel)

Modulo: `resources/js/modules/carousel.js`

### Setup base

```js
import Swiper from 'swiper'
import { Navigation, Pagination, Autoplay, EffectFade } from 'swiper/modules'

function initCarousel(selector, options = {}) {
    const defaults = {
        modules: [Navigation, Pagination, Autoplay],
        loop: true,
        autoplay: { delay: 4000, disableOnInteraction: false },
        pagination: { el: '.swiper-pagination', clickable: true },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    }
    return new Swiper(selector, { ...defaults, ...options })
}

// Hero carousel
initCarousel('.hero-carousel .swiper', { speed: 800 })

// Testimonials
initCarousel('.testimonials-swiper', {
    slidesPerView: 1,
    spaceBetween: 32,
    breakpoints: {
        768:  { slidesPerView: 2 },
        1200: { slidesPerView: 3 },
    },
})
```

### Carousel prodotti (products-carousel section)

```js
initCarousel('.products-swiper', {
    slidesPerView: 1.2,
    spaceBetween: 16,
    breakpoints: {
        640:  { slidesPerView: 2.2 },
        1024: { slidesPerView: 3.5 },
        1280: { slidesPerView: 4 },
    },
    freeMode: true,
})
```

### Markup HTML per Swiper

```blade
<div class="swiper products-swiper">
  <div class="swiper-wrapper">
    @foreach($products as $product)
      <div class="swiper-slide">
        @include('partials.product-card', ['product' => $product])
      </div>
    @endforeach
  </div>
  <div class="swiper-pagination"></div>
  <div class="swiper-button-prev"></div>
  <div class="swiper-button-next"></div>
</div>
```

---

## Moduli lazy loading

Per non inizializzare tutti i moduli su ogni pagina:

```js
// In app.js
document.addEventListener('DOMContentLoaded', () => {
    // Inizializza solo se ci sono elementi nel DOM
    if (document.querySelector('.swiper')) import('./modules/carousel')
    if (document.querySelector('[data-scroll]')) import('./modules/scroll-effects')
})
```

---

## Editor JS (`resources/js/editor.js`)

Script caricato solo nell'editor Gutenberg. Usa per:
- Registrare variazioni di blocchi
- Aggiungere filtri editor (slot fills)
- Personalizzare toolbar blocchi

```js
// resources/js/editor.js
import { addFilter } from '@wordpress/hooks'
import { createHigherOrderComponent } from '@wordpress/compose'

// Esempio: aggiunge una classe custom a tutti i blocchi paragrafo
addFilter(
    'blocks.registerBlockType',
    'theme/paragraph-class',
    (settings, name) => {
        if (name === 'core/paragraph') {
            return { ...settings, className: 'theme-paragraph' }
        }
        return settings
    }
)
```

---

## Chunk splitting Vite

I vendor sono divisi in chunk separati per ottimizzare il caching:

```js
// vite.config.js
manualChunks: {
    'vendor-alpine': ['alpinejs', '@alpinejs/collapse', '@alpinejs/focus'],
    'vendor-gsap':   ['gsap', 'gsap/ScrollTrigger'],
    'vendor-swiper': ['swiper'],
}
```

Un utente che visita più pagine scarica i vendor solo una volta (cached). Solo `app.js` varia tra deploy.
