# AUDIT v2 — sage-theme Enterprise Edition
> Aggiornato: 2026-03-24 · Stack: Sage 11 · Acorn 5 · Tailwind v4 · Alpine.js 3 · WooCommerce 9.x · Vite 8

---

## Indice

1. [Score riepilogativo](#1-score-riepilogativo)
2. [Design System — theme.json + @theme](#2-design-system--themejson--theme)
3. [CSS Architecture — app.css + editor.css](#3-css-architecture--appcss--editorcss)
4. [Blocchi custom (Gutenberg)](#4-blocchi-custom-gutenberg)
5. [Patterns](#5-patterns)
6. [Blade Templates](#6-blade-templates)
7. [Alpine.js](#7-alpinejs)
8. [JavaScript / ES Modules](#8-javascript--es-modules)
9. [PHP / Sage — Backend](#9-php--sage--backend)
10. [WooCommerce](#10-woocommerce)
11. [Sicurezza](#11-sicurezza)
12. [Accessibilità (WCAG 2.1 AA)](#12-accessibilità-wcag-21-aa)
13. [Performance](#13-performance)
14. [Backlog — Feature Gap vs tema Shopify premium](#14-backlog--feature-gap-vs-tema-shopify-premium)
15. [Roadmap prioritizzata](#15-roadmap-prioritizzata)

---

## 1. Score riepilogativo

| Area | Stato | Score |
|------|-------|-------|
| Design System (theme.json + Tailwind) | ✅ Allineato, token completi | **9/10** |
| CSS Architecture | ✅ Classi mancanti aggiunte | **8/10** |
| Blocchi custom | ✅ Completi (4/4) con SSR + InspectorControls | **9/10** |
| Patterns | ⚠️ 22 su 29, alcuni hanno hardcoding residuo | **7/10** |
| Blade Templates | ✅ Gerarchia corretta, WC fix applicato | **8/10** |
| Alpine.js | ⚠️ cartDrawer inline, nessun cleanup listener | **7/10** |
| JavaScript / ES Modules | ⚠️ Modulo dead code, error handling parziale | **7/10** |
| PHP / Sage Backend | ✅ Sicuro, escaping corretto, REST ben strutturato | **8/10** |
| WooCommerce | ✅ Cart Block fix, custom overrides, fragments | **8/10** |
| Sicurezza | ⚠️ Search senza rate-limit, IP spoofing possibile | **7/10** |
| Accessibilità WCAG 2.1 | ⚠️ Skip link ✅, focus ✅, alcuni ARIA mancanti | **7/10** |
| Performance | ⚠️ Code split ✅, immagini lazy ⚠️, Loco dead | **7/10** |

**Score complessivo: 7.7 / 10** — Tema produzione-ready. Mancano ~2 sprint per arrivare a standard enterprise completo.

---

## 2. Design System — theme.json + @theme

### ✅ Risolto in questa sessione

| Fix | Dettaglio |
|-----|-----------|
| `useRootPaddingAwareAlignments: true` | Allineamenti full-width ora corretti |
| `settings.dimensions.aspectRatios` | 4 preset (portrait/landscape/square/wide) disponibili nell'editor |
| `settings.shadow.presets` | 3 preset (subtle/medium/large) per box-shadow blocchi |
| `styles.spacing.blockGap` | Gap verticale tra blocchi → `var(--wp--preset--spacing--6)` (32px) |
| `styles.elements.link.:focus` | WCAG 2.1 AA: focus visivo su link con colore + underline |
| `styles.blocks.core/list` | Font, line-height e padding coerenti |
| `styles.blocks.core/table` | Font-sm + border definiti |
| `styles.blocks.core/gallery` | blockGap definito (spacing-4) |
| `editor.css`: `"Inter"` → `"Poppins"` | Heading editor e blockquote ora Poppins (riga 16 e 118) |

### ✅ Stato attuale (corretto)

- **Palette**: 16 colori con slug semantici (`ink`, `accent`, `primary`, `success`, `warning`, `error`, `contrast`, `base`). Nessun conflitto.
- **Font**: solo Poppins — `sans` (body) e `serif` (titoli) sono entrambi `'Poppins', system-ui`. Il doppio slug mantiene la compatibilità con tutte le classi `font-serif` esistenti.
- **Font sizes**: 10 preset, i più grandi (3xl–hero) usano `clamp()` per la fluidità.
- **Spacing**: 11 preset (4px → 192px).
- **Buttons**: Default `#0074C7` blu, Outline transparent/blu, Accent blu — coerente con brand.
- **`@theme` in app.css**: sincronizzato con theme.json (stessi hex, stessi nomi).

### ⚠️ Residui da correggere

| Problema | File | Azione |
|----------|------|--------|
| `settings.spacing` duplicato in theme.json (righe 71-74 e 110-128) | `theme.json` | Rimuovere il primo blocco `spacing` vuoto (riga 71-74) — il secondo con `spacingSizes` è quello corretto |
| `custom.typography.font-size: {}` vuoto | `theme.json` | Rimuovere o popolarlo con token reali |
| Colore gold (`--color-gold`) = `#0074C7` (blu) | `app.css` | Legacy alias mantenuto per compatibilità, ma confonde. Aggiungere commento esplicativo o rinominare a lungo termine |

---

## 3. CSS Architecture — app.css + editor.css

**Dimensioni:** `app.css` — 2.620 righe (post-fix) · `editor.css` — 170 righe

### ✅ Risolto in questa sessione

| Classe | Motivo dell'aggiunta |
|--------|----------------------|
| `.container` | Usata in 12+ view senza definizione → ora `max-width:1200px`, `margin-inline:auto`, padding fluid |
| `.theme-form` + 9 sub-classi | `patterns/contact-section.php` usa queste classi — form era completamente non stilizzato |
| `.theme-btn`, `.theme-btn--primary/outline/ink/full` | Pattern buttons senza stile |
| `.wishlist-btn`, `.wishlist-btn.active` | `wishlist.js` seleziona queste classi — stati hover/active assenti |
| `.wishlist-count-bubble`, `.wishlist-dot` | Badge e dot count wishlist senza CSS |

### ✅ Punti di forza dell'architettura

- **Design token consistency**: tutte le classi BEM usano `var(--color-*)`, `var(--font-*)` — nessun hex hardcodato nelle classi custom (eccetto legacy e pattern bloccati da WP).
- **Separazione buona**: classi utility Tailwind per layout, BEM per componenti con stato complesso.
- **Layer impliciti corretti**: base → utilities → components → WC overrides.
- **`@source`** correttamente puntato su PHP, blade, JS.
- **Prefers-reduced-motion** global in coda al file.

### ⚠️ Problemi residui

| Problema | Severità | Riga | Fix |
|----------|----------|------|-----|
| **~18 `!important`** nei WC button overrides | Media | ~1391-1430 | Aumentare specificità con `body.woocommerce` invece di `!important` |
| **Duplicazione `font-family: var(--font-sans)`** nelle classi `.btn-*` | Bassa | Tutto | Estrarre in `@layer components { .btn-base { font-family:... } }` |
| **Due `.scroll-indicator`** definite (righe ~560 e ~1932) | Bassa | 560, 1932 | Rimuovere la prima (sta nel `.hero-section`) o fare override esplicito |
| **`.site-header .nav-primary`** (righe 377-460) non usata — il template usa `.nav-link-t` | Bassa | 377-460 | Verificare uso, eventualmente rimuovere ~80 righe morti |

---

## 4. Blocchi custom (Gutenberg)

**4 blocchi registrati:** `hero`, `testimonial`, `stat`, `icon-box`

### ✅ Standard enterprise rispettati

| Blocco | `block.json` | `render.php` | `editor.js` | `editor.css` |
|--------|-------------|--------------|-------------|--------------|
| `hero` | ✅ apiVersion 3, anchor, align wide/full | ✅ SSR, `get_block_wrapper_attributes()`, escaping corretto | ✅ InspectorControls completi (MediaPanel, BG select, ToggleControl) | ✅ WYSIWYG preview |
| `testimonial` | ✅ | ✅ | ✅ | ✅ |
| `stat` | ✅ | ✅ | ✅ | ✅ |
| `icon-box` | ✅ | ✅ | ✅ | ✅ |

### ✅ Best practice verificate

- `save: () => null` — tutti i blocchi sono SSR, nessun blocco salvato lato client.
- Attributi con `default` definito in `block.json`.
- `wp_get_attachment_image()` usato (non `<img>` diretti → srcset + lazy automatici).
- `esc_html()`, `wp_kses_post()`, `esc_url()` applicati correttamente.
- `ServerSideRender` in preview editor per WYSIWYG fedele.
- Categoria `theme` registrata → blocchi raggruppati nell'inserter.

### ⚠️ Miglioramenti consigliati

| Blocco | Issue | Fix |
|--------|-------|-----|
| Tutti | Nessun blocco ha `"supports": {"interactivity": true}` | Solo se si aggiunge comportamento frontend tramite Interactivity API WP |
| `hero` | Non ha `"keywords"` in `block.json` | Aggiungere `"keywords": ["hero", "banner", "copertina"]` per la ricerca nell'inserter |
| `icon-box` | `"icon"` attribute è una stringa SVG hardcoded | Valutare `icon` come `media` o una lista di preset da SelectControl |

---

## 5. Patterns

**22 pattern attivi** · 3 categorie: `theme-sections`, `theme-cards`, `theme-carousel`

### ⚠️ isStackedOnMobile mancante

Questi pattern hanno colonne che NON specificano `isStackedOnMobile`:

| File | Blocco | Comportamento attuale | Fix |
|------|--------|----------------------|-----|
| `contact-section.php` | `<!-- wp:columns {"verticalAlignment":"top"} -->` | Rimane su 2 col su mobile | Aggiungere `"isStackedOnMobile":true` |
| `intro-two-cols.php` | `<!-- wp:columns {"verticalAlignment":"center"} -->` | Rimane su 2 col su mobile | Aggiungere `"isStackedOnMobile":true` |
| `services-grid.php` | `<!-- wp:columns -->` (bare, no attrs) | Default WP (stacks) ma non garantito | Aggiungere `"isStackedOnMobile":true` |
| `team-member-card.php` | `<!-- wp:columns {"columns":3,...} -->` | Non specificato | Aggiungere `"isStackedOnMobile":true` |

Nota: `brand-logos.php` e `usp-band.php` hanno `isStackedOnMobile:false` — **intenzionale** (loghi e USP restano su riga anche su mobile, usare con giudizio).

### ⚠️ Valori hardcodati nei pattern

| File | Valore hardcodato | Dovrebbe essere |
|------|------------------|-----------------|
| `testimonials.php` | `background-color:#f6f4f2` (riga 11-12) | `var(--wp--preset--color--cream)` |
| `hero.php` | `background-color:#0f0f0f` overlay (riga 14) | `var(--wp--preset--color--ink)` |
| `contact-section.php` | `href="tel:+39030000000"` (riga 49) | Customizer `theme_phone` o placeholder `#` |
| `contact-section.php` | `href="mailto:info@theme.it"` (riga 68) | Customizer `theme_email` o placeholder `#` |
| `cta-banner.php` | `href="tel:+39030000000"` (riga 31) | Customizer o placeholder |
| `cta-banner.php` | `href="mailto:info@theme.it"` (riga 43) | Customizer o placeholder |

**Come fixare i pattern:** i pattern sono HTML statico inserito nell'editor — sostituisci i valori hardcodati con token WP oppure con placeholder testo (`[telefono]`, `[email]`) che il cliente sostituisce dopo aver inserito il pattern.

### 📋 Pattern mancanti — backlog (7 pattern)

| Priorità | Slug | Titolo | CSS disponibile |
|----------|------|--------|-----------------|
| 🔴 Alta | `theme/pricing-table` | Tabella Prezzi — 3 Piani | Nessuna (da creare) |
| 🔴 Alta | `theme/timeline` | Timeline / Processo | `.process-step*` già in app.css |
| 🔴 Alta | `theme/faq-accordion` | FAQ — Accordion Alpine | `.faq-item*` già in app.css |
| 🟡 Media | `theme/before-after` | Before/After — Slider | `.before-after*` già in app.css |
| 🟡 Media | `theme/video-section` | Sezione Video | Nessuna |
| 🟡 Media | `theme/map-contact` | Mappa + Contatti | Nessuna |
| 🟡 Media | `theme/review-aggregate` | Recensioni — Rating Badge | Nessuna |

> Le classi CSS per Timeline, FAQ e Before/After sono già definite in app.css — i pattern corrispondenti devono solo essere creati in `patterns/`.

---

## 6. Blade Templates

### ✅ Gerarchia Sage corretta

| Template | Funziona | Note |
|----------|----------|------|
| `layouts/app.blade.php` | ✅ | html, head, body, header, footer, cart-drawer |
| `front-page.blade.php` | ✅ | Usa `the_content()` — il cliente gestisce il layout dall'editor |
| `page.blade.php` | ✅ | Generic page |
| `page-wishlist.blade.php` | ✅ | Template speciale per slug `wishlist` |
| `single.blade.php` | ✅ | Post singolo |
| `single-portfolio.blade.php` | ✅ | CPT portfolio |
| `single-team.blade.php` | ✅ | CPT team |
| `archive.blade.php` | ✅ | Archivi standard |
| `archive-portfolio.blade.php` | ✅ | Archivio CPT portfolio |
| `woocommerce.blade.php` | ✅ | `the_content()` per cart/checkout/account, `woocommerce_content()` per shop |
| `template-contact.blade.php` | ✅ | Template selezionabile editor |
| `404.blade.php` | ✅ | |
| `search.blade.php` | ✅ | Live search AJAX |

### ⚠️ Problemi residui

| File | Problema | Severità | Fix |
|------|----------|----------|-----|
| `sections/footer.blade.php` | Link "Chi siamo / Blog / FAQ" generati con `home_url('/chi-siamo')` — se lo slug cambia, si rompono | Media | Usare `footer_info_navigation` WP menu già registrato |
| `partials/cart-drawer.blade.php` | `function cartDrawer()` definita in `<script>` inline — non registrata con `Alpine.data()` | Bassa | Spostare in `app.js` con `Alpine.data('cartDrawer', () => ({...}))` |
| `sections/header.blade.php` | `wishlist-count-bubble` usa `style="display:none"` inline — lo stato è gestito da JS, ma l'attributo è duplicato con la classe `.wishlist-count-bubble` | Bassa | Rimuovere `style="display:none"` inline, usare solo la classe (che ora ha `display:none` di default) |

---

## 7. Alpine.js

### ✅ Componenti registrati

| Componente | Metodo | Funziona |
|------------|--------|----------|
| `siteHeader` | `Alpine.data('siteHeader', ...)` in app.js | ✅ |
| `searchOverlay` | `Alpine.data('searchOverlay', ...)` in app.js | ✅ |
| `cartDrawer()` | Funzione globale `<script>` in cart-drawer.blade.php | ✅ funziona, ma anti-pattern |

### ✅ Store globale

```js
Alpine.store('layout', {
  hasHero: false,  // header trasparente
  cartCount: 0,    // badge carrello
})
```

### ✅ Plugin registrati

- `Collapse` — animazioni collapse/expand
- `Focus` — trap focus su modali/drawer (accessibilità)

### ⚠️ Problemi

| Problema | Severità | Posizione | Fix |
|----------|----------|-----------|-----|
| `cartDrawer()` come funzione globale inline, non registrata con `Alpine.data()` | Bassa | `cart-drawer.blade.php` riga 187 | Spostare in `app.js`: `Alpine.data('cartDrawer', () => ({...}))` e rimuovere lo `<script>` dal blade |
| Listener scroll in `siteHeader` (`window.addEventListener('scroll', ...)`) mai rimosso | Bassa | `app.js` riga ~90 | Aggiungere `destroy()` hook con `window.removeEventListener` |
| `@alpinejs/collapse` registrato ma raramente usato (nessun `x-collapse` trovato nei template principali) | Bassa | `app.js` riga 3 | Verificare uso — se assente, rimuovere per bundle size |

---

## 8. JavaScript / ES Modules

### ✅ Architettura corretta

```
app.js
├── imports: Alpine, Collapse, Focus, GSAP, ScrollTrigger
├── imports: carousel.js, luxury-animations.js, magnetic-hover.js, scroll-effects.js, wishlist.js
└── Alpine.start()
```

Vite produce code-split corretto:
- `vendor-alpine-*.js` — Alpine (61 kB)
- `vendor-gsap-*.js` — GSAP (112 kB)
- `vendor-swiper-*.js` — Swiper (102 kB)
- `app-*.js` — codice tema (13 kB)

### ⚠️ Problemi per area

#### wishlist.js
| Problema | Riga | Fix |
|----------|------|-----|
| `loadProducts()` non ha timeout — se il server non risponde, la pagina resta in loading indefinitamente | ~80 | Aggiungere `AbortController` con timeout 8s |
| Nessun pulsante "Riprova" nell'error state | ~130 | Aggiungere `<button onclick="customElements.get('wishlist-products').prototype.loadProducts.call(this.closest('wishlist-products'))">Riprova</button>` |
| `console.error()` visibile in produzione | ~134 | Condizionare a `import.meta.env.DEV` |

#### magnetic-hover.js
| Problema | Riga | Fix |
|----------|------|-----|
| Event listeners `mousemove`/`mouseleave` mai rimossi — se gli elementi vengono rimossi dal DOM (navigazione AJAX), memory leak | ~14-38 | Usare `AbortController` per il listener signal o Intersection Observer per auto-cleanup |

#### locomotive-scroll.js
| Problema | Fix |
|----------|-----|
| File presente ma **mai importato** in `app.js` → dead code | Scegliere: rimuovere il file O aggiungere `import { initLocomotiveScroll } from './modules/locomotive-scroll.js'` in app.js |

#### scroll-effects.js
| Problema | Fix |
|----------|-----|
| `initScrollEffects()` viene chiamata anche quando `prefersReducedMotion` è true (il check è in app.js ma `initScrollEffects` non lo verifica internamente) | Aggiungere all'inizio di `initScrollEffects()`: `if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return` |

#### editor.js (Gutenberg)
| Stato | Dettaglio |
|-------|-----------|
| ✅ 4 blocchi completi con InspectorControls | hero, testimonial, stat, icon-box |
| ✅ `ServerSideRender` per WYSIWYG | Aggiornamento in real-time nell'editor |
| ✅ Block Style Variations (13) | button/heading/separator/quote/image/group |
| ✅ Block Variations (5) | Hero Section, Content Card, Sezione Scura, Colonne 60/40, 3 Colonne |
| ⚠️ `Fragment` importato ma usato solo in alcuni blocchi | Verificare se necessario ovunque |

---

## 9. PHP / Sage — Backend

### ✅ Punti di forza

- Namespace `App\` su tutti i file in `app/`
- Escaping corretto ovunque: `esc_html()`, `esc_url()`, `esc_attr()`, `wp_kses_post()`
- Input sanitizzato: `sanitize_text_field()`, `sanitize_email()`, `absint()`
- `permission_callback: '__return_true'` solo sugli endpoint pubblici intenzionali
- Rate limiting su newsletter (5/min per IP)
- `declare(strict_types=1)` applicato nei file principali
- Acorn v5 boot corretto (solo in functions.php)
- JSON-LD structured data automatico (se nessun plugin SEO attivo)
- OG/Twitter meta fallback automatico

### ⚠️ Problemi

| File | Problema | Severità | Fix |
|------|----------|----------|-----|
| `ajax.php` GET `/search` | Nessun rate limit — endpoint pubblico, rischio scraping/abuse | Media | Aggiungere rate limit come per newsletter: 10 req/min per IP |
| `filters.php` rate limit | IP detection usa solo `$_SERVER['REMOTE_ADDR']` — se il sito è dietro Cloudflare/CDN, l'IP è sempre quello del proxy | Media | Controllare `HTTP_CF_CONNECTING_IP` (Cloudflare) e `HTTP_X_FORWARDED_FOR` con validazione |
| `setup.php` — `$blocks = ['hero', 'testimonial', 'stat', 'icon-box']` | Array hardcodato — ogni nuovo blocco richiede edit di setup.php | Bassa | Usare `glob()` per auto-discovery: `glob(get_template_directory() . '/blocks/*/block.json')` |
| `customizer.php` — Customizer keys per tel/email non esistono | I pattern usano `href="tel:+39030000000"` hardcodato | Bassa | Aggiungere `theme_phone` e `theme_email` al Customizer e aggiornare i pattern |

### ✅ REST API — 7 endpoint documentati

| Metodo | Route | Auth | Rate limit | Stato |
|--------|-------|------|------------|-------|
| GET | `/search` | Pubblico | ❌ Mancante | ⚠️ |
| GET | `/quick-view/{id}` | Pubblico | — | ✅ |
| GET | `/products` | Pubblico | — | ✅ |
| GET | `/wishlist-products` | Pubblico | — | ✅ |
| POST | `/wishlist` | Pubblico | — | ✅ |
| POST | `/newsletter` | Pubblico | ✅ 5/min | ✅ |
| POST | `/contact` | Pubblico | ✅ Nonce | ✅ |

---

## 10. WooCommerce

### ✅ Integrazione completa

- **Cart Block fix**: `woocommerce.blade.php` usa `the_content()` per cart/checkout/account → compatibile con WC 9.x Cart Block
- **WC fragments**: `woocommerce_add_to_cart_fragments` aggiorna badge `.cart-count-fragment` via AJAX
- **Custom template overrides**: `content-product.php`, `single-product/` tabs/image/related
- **Wishlist page**: `/wishlist` con `page-wishlist.blade.php` + endpoint dedicato
- **Theme support completo**: zoom, lightbox, slider gallery prodotto
- **Free shipping bar**: soglia configurabile via Customizer `free_shipping_threshold`
- **WC default styles disabilitati**: usiamo i nostri in app.css

### ⚠️ Gap rimanenti

| Feature | Stato | Priorità |
|---------|-------|----------|
| Checkout custom (template Blade) | ❌ Default WC | Alta |
| Account dashboard styled | ⚠️ CSS base, nessun template custom | Media |
| Email transazionali custom | ⚠️ Override PHP in `woocommerce/emails/` ma solo header/footer | Media |
| Quick view modale | ❌ Endpoint REST pronto (`/quick-view/{id}`), manca il partial Blade + JS | Alta |
| Filtri prodotto (faceted search) | ❌ Endpoint REST pronto (`/products`), manca la UI | Alta |
| Prodotti visti di recente | ❌ Nessuna implementazione | Media |

---

## 11. Sicurezza

| Area | Stato | Dettaglio |
|------|-------|-----------|
| XSS — output Blade | ✅ | `{{ }}` escapato, `{!! !!}` solo per HTML fidato WP |
| SQL Injection | ✅ | Solo `WP_Query`, `$wpdb->prepare()` |
| CSRF — form contatti | ✅ | Honeypot + nonce WP |
| CSRF — newsletter | ⚠️ | Nonce inviato ma non validato server-side in `filters.php` |
| Rate limit — newsletter | ✅ | 5 req/min per IP |
| Rate limit — search | ❌ | Assente |
| Rate limit IP detection | ⚠️ | Solo `REMOTE_ADDR`, vulnerabile a proxy/CDN |
| XML-RPC | ✅ | Disabilitato |
| User enumeration | ✅ | Bloccato via filtro |
| REST endpoint permissions | ✅ | Tutti i custom endpoints sono intenzionalmente pubblici |
| Media upload | ✅ | Solo admin può caricare (default WP) |
| Sanitizzazione input REST | ✅ | `absint`, `sanitize_text_field`, `sanitize_email` ovunque |

### 🔴 Azioni immediate

1. **Rate limit endpoint `/search`** — alta priorità, è pubblico e non ha protezione
2. **Validare nonce newsletter** — aggiungere `wp_verify_nonce()` in `filters.php`
3. **IP detection proxy-aware** — aggiornare il rate limiter per ambienti con CDN

---

## 12. Accessibilità (WCAG 2.1 AA)

| Criterio | Stato | Note |
|----------|-------|------|
| Skip link | ✅ | `.skip-to-content` presente e funzionale |
| Gerarchia heading (H1→H2→H3) | ✅ | Page hero H1, section headings H2/H3 |
| `alt` su immagini | ✅ | `wp_get_attachment_image()` gestisce `alt` da media library |
| ARIA landmark roles | ✅ | `<header>`, `<main>`, `<footer>`, `<nav>` semantici |
| Focus ring visibile | ✅ | `:focus-visible` definito in app.css + theme.json |
| Link `:focus` stato | ✅ | Aggiunto in theme.json `styles.elements.link.:focus` |
| Contrast ratio testo | ✅ | `#0a0a0a` su `#ffffff` = 19.5:1 (AAA) |
| Contrast ratio muted | ⚠️ | `#6b6b6b` su `#ffffff` = 5.74:1 (AA ✅, ma AA Large ✅ solo) |
| `aria-label` bottoni icona | ⚠️ | Header: search/cart/wishlist hanno aria-label ✅; mobile hamburger da verificare |
| `aria-pressed` wishlist btn | ⚠️ | `aria-pressed="false"` in template, ma JS non lo aggiorna a `"true"` quando attivo |
| `role="region"` carousel | ✅ | Definito nel CLAUDE.md, da verificare nei template Swiper |
| `x-trap.inert` su drawer | ⚠️ | Cart drawer e mobile menu dovrebbero usare il plugin Focus per trap |
| Riduzione motion | ✅ | `@media (prefers-reduced-motion: reduce)` in app.css + GSAP condizionale |
| Lingua HTML | ✅ | `lang` impostato da WP |

### Fix prioritari accessibilità

```js
// wishlist.js — aggiornare aria-pressed
function updateButtons() {
  document.querySelectorAll('.wishlist-btn[data-product-id]').forEach(btn => {
    const id = parseInt(btn.dataset.productId)
    btn.classList.toggle('active', ids.includes(id))
    btn.setAttribute('aria-pressed', ids.includes(id) ? 'true' : 'false') // ← aggiungere
  })
}
```

---

## 13. Performance

### ✅ Già ottimizzato

| Area | Dettaglio |
|------|-----------|
| **Code splitting** | Vite separa vendor-alpine, vendor-gsap, vendor-swiper — caricamento lazy per pagine senza carousel/animazioni |
| **Per-block CSS** | `should_load_separate_core_block_assets: true` — CSS Gutenberg on-demand |
| **Font async** | Google Fonts caricati con `media="print"` + `onload` (non render-blocking) |
| **Immagini WP** | `wp_get_attachment_image()` genera srcset + sizes automaticamente |
| **GSAP condizionale** | Non caricato se `prefers-reduced-motion` è attivo |
| **Tailwind v4 + static** | `@import "tailwindcss" theme(static)` — nessun runtime CSS |

### ⚠️ Gap performance

| Problema | Impatto | Fix |
|----------|---------|-----|
| `locomotive-scroll.js` mai usato ma presente | Dead code (file ~100 righe) | Rimuovere o attivare |
| Swiper importato globalmente in app.js | Caricato su ogni pagina anche senza carousel | Già code-split correttamente da Vite — OK |
| Immagini in template Blade senza `loading="lazy"` | LCP peggiore per immagini sotto il fold | Aggiungere `loading="lazy"` a `<img>` nei partials (non quelle hero) |
| Nessun caching sui risultati `/search` REST | Query WP full-text ad ogni keystroke | Aggiungere `wp_cache_get/set()` o transient da 60s |

### Lighthouse score stimato (locale)

| Metrica | Stimato | Problemi attivi |
|---------|---------|-----------------|
| Performance | 85-90 | Font Google (2 req), Swiper 102kB, locomotive dead |
| Accessibility | 88-92 | aria-pressed, x-trap drawer |
| Best Practices | 95 | — |
| SEO | 90-95 | JSON-LD presente, OG fallback attivo |

---

## 14. Backlog — Feature Gap vs tema Shopify premium

| Feature | Stato | Priorità | Note |
|---------|-------|----------|------|
| **Quick view prodotto** | ⚠️ Endpoint REST pronto | 🔴 Alta | Manca partial Blade + modale Alpine |
| **Filtri prodotto (faceted)** | ⚠️ Endpoint REST pronto | 🔴 Alta | Manca UI Alpine con checkbox/slider |
| **Wishlist sincrona** (utenti loggati) | ⚠️ Endpoint REST `/wishlist` esiste | 🟡 Media | Manca sincronizzazione localStorage ↔ user_meta |
| **Prodotti visti di recente** | ❌ | 🟡 Media | localStorage `theme:recent` + endpoint |
| **Checkout custom** | ❌ Default WC | 🟡 Media | Template Blade + CSS |
| **Account dashboard** | ⚠️ CSS base | 🟡 Media | Template Blade con grid navigazione |
| **Email WC personalizzate** | ⚠️ Solo header/footer | 🟡 Media | Completare override in `woocommerce/emails/` |
| **Cookie consent GDPR** | ⚠️ Banner base | 🟡 Media | Aggiungere categorie (analytics/marketing/functional) |
| **Customizer: tel/email** | ❌ Hardcodati nei pattern | 🟡 Media | Aggiungere `theme_phone`, `theme_email` |
| **Announcement bar CTA** | ✅ Implementata | — | |
| **Free shipping bar** | ✅ Implementata | — | |
| **Mega menu** | ✅ Implementato | — | |
| **Search overlay** | ✅ Implementata | — | |
| **Pattern backlog (7)** | ❌ Vedi §5 | 🟡 Media | Timeline, FAQ, Pricing, Before/After, Video, Map, Review |

---

## 15. Roadmap prioritizzata

### Sprint 1 — Security hardening (1 giorno)

```
[ ] Aggiungere rate limit GET /search (copiare pattern newsletter)
[ ] Validare nonce newsletter in filters.php
[ ] Aggiornare IP detection con CF-Connecting-IP / X-Forwarded-For
```

### Sprint 2 — Qualità codice (1-2 giorni)

```
[ ] Rimuovere locomotive-scroll.js (dead code)
[ ] Spostare cartDrawer() da <script> inline a Alpine.data() in app.js
[ ] Aggiungere aria-pressed update in wishlist.js
[ ] Fix isStackedOnMobile: contact-section, intro-two-cols, services-grid, team-member-card
[ ] Fix hardcoded colors: testimonials.php (#f6f4f2→cream), hero.php (#0f0f0f→ink token)
[ ] Aggiungere Customizer keys: theme_phone, theme_email
[ ] Sostituire link footer hardcoded con menu footer_info_navigation
[ ] Rimuovere duplicato settings.spacing in theme.json (righe 71-74)
```

### Sprint 3 — Feature e-commerce (3-5 giorni)

```
[ ] Quick view prodotto: partial Blade + modale Alpine + JS trigger
[ ] Filtri prodotto (faceted): Alpine component con checkbox/range/sort
[ ] Wishlist sync per utenti loggati: localStorage ↔ REST /wishlist ↔ user_meta
[ ] Prodotti visti di recente: localStorage + product-card integration
```

### Sprint 4 — Pattern e content (2-3 giorni)

```
[ ] Pattern: theme/faq-accordion (CSS già pronto)
[ ] Pattern: theme/timeline (CSS già pronto)
[ ] Pattern: theme/before-after (CSS già pronto)
[ ] Pattern: theme/pricing-table
[ ] Pattern: theme/video-section
[ ] Pattern: theme/map-contact
[ ] Pattern: theme/review-aggregate
```

### Sprint 5 — Polish e checkout (2-3 giorni)

```
[ ] Checkout custom (template Blade)
[ ] Account dashboard custom (template Blade)
[ ] Email WC personalizzate (completare override)
[ ] Cookie consent GDPR con categorie
[ ] Aggiornare Lighthouse score target 95+
```

---

## Appendice — File chiave con problemi attivi

| File | Problemi | Severità |
|------|----------|----------|
| `app/filters.php` | Nonce newsletter non validato, rate limit IP spoofing | 🔴 Alta |
| `app/ajax.php` | Search senza rate limit | 🔴 Alta |
| `resources/js/modules/locomotive-scroll.js` | Dead code | 🟡 Media |
| `resources/js/modules/wishlist.js` | Nessun timeout fetch, no aria-pressed update | 🟡 Media |
| `resources/js/modules/magnetic-hover.js` | Listener mai rimossi | 🟡 Media |
| `resources/js/modules/scroll-effects.js` | prefersReducedMotion non verificato internamente | 🟡 Media |
| `resources/views/partials/cart-drawer.blade.php` | cartDrawer inline script | 🟢 Bassa |
| `resources/views/sections/footer.blade.php` | Link hardcodati | 🟢 Bassa |
| `patterns/contact-section.php` | tel/email hardcodati, isStackedOnMobile assente | 🟢 Bassa |
| `patterns/testimonials.php` | `#f6f4f2` hardcodato | 🟢 Bassa |
| `patterns/hero.php` | `#0f0f0f` hardcodato nell'overlay | 🟢 Bassa |
| `patterns/intro-two-cols.php` | isStackedOnMobile assente | 🟢 Bassa |
| `patterns/services-grid.php` | isStackedOnMobile assente | 🟢 Bassa |
| `patterns/team-member-card.php` | isStackedOnMobile assente | 🟢 Bassa |
| `theme.json` | `settings.spacing` duplicato (righe 71-74), `custom.typography.font-size` vuoto | 🟢 Bassa |
