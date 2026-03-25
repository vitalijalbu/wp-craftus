# CLAUDE.md — sage-theme

> Documentazione tecnica completa del tema. Leggila prima di qualsiasi modifica.
> Aggiornata: 2026-03-24 — Enterprise Edition 2.1

---

## Indice

1. [Stack](#1-stack)
2. [Struttura directory](#2-struttura-directory)
3. [Flusso di lavoro](#3-flusso-di-lavoro)
4. [Design System — theme.json](#4-design-system--themejson)
5. [Come creare un Custom Block](#5-come-creare-un-custom-block)
6. [Come creare un Pattern](#6-come-creare-un-pattern)
7. [Come creare un Template (block template)](#7-come-creare-un-template-block-template)
8. [Block Style Variations](#8-block-style-variations)
9. [Block Variations](#9-block-variations)
10. [Blade Templates (frontend PHP)](#10-blade-templates-frontend-php)
11. [Alpine.js — componenti reattivi](#11-alpinejs--componenti-reattivi)
12. [Custom Post Types](#12-custom-post-types)
13. [REST API endpoints custom](#13-rest-api-endpoints-custom)
14. [Wishlist System](#14-wishlist-system)
15. [WooCommerce — Integrazione](#15-woocommerce--integrazione)
16. [Regole di codice](#16-regole-di-codice)
17. [Customizer keys](#17-customizer-keys)
18. [File da NON toccare](#18-file-da-non-toccare)
19. [Audit — Correzioni applicate](#19-audit--correzioni-applicate)
20. [Pattern mancanti — backlog](#20-pattern-mancanti--backlog)

---

## 1. Stack

| Layer        | Tecnologia                                        | Versione |
|--------------|---------------------------------------------------|----------|
| Framework    | Roots Sage + Acorn (Laravel per WP)               | 11 / 5   |
| Template FE  | Laravel Blade (`.blade.php`)                      | —        |
| CSS          | Tailwind CSS v4 + design tokens `@theme {}`       | 4.x      |
| Build        | Vite + @roots/vite-plugin                         | 8.x      |
| JS reattivo  | Alpine.js 3 + Collapse + Focus plugins            | 3.x      |
| Animazioni   | GSAP 3 + ScrollTrigger                            | 3.x      |
| Carousel     | Swiper 12                                         | 12.x     |
| Backend      | WordPress 6.x + WooCommerce 9.x                   | —        |
| PHP          | 8.2+ strict types                                 | —        |
| Linting      | Biome                                             | 2.x      |

**Questo è un tema ibrido:**
- Il frontend usa **Blade** (PHP server-side rendering)
- L'editor Gutenberg usa **React** (blocchi custom) + **theme.json** (Global Styles)
- Il cliente gestisce colori/font/spacing da **Aspetto → Editor → Global Styles** (icona paintbrush)
- I template Blade hanno priorità sui block template HTML quando esistono per la stessa slug

---

## 2. Struttura directory

```
sage-theme/
│
├── app/                          # PHP backend (namespace App\)
│   ├── setup.php                 # theme supports, menu, font, blocchi, pattern categories
│   ├── filters.php               # WP filters, REST API, performance, WC tweaks
│   ├── ajax.php                  # REST endpoints: search, quick-view, products, wishlist, contact
│   ├── customizer.php            # Pannello Customizer (colori social, CTA, annuncio)
│   ├── post-types.php            # CPT: portfolio, team, faq
│   ├── Providers/
│   │   └── ThemeServiceProvider.php   # boot Acorn (Laravel)
│   └── View/Composers/               # iniettano dati nelle view Blade
│
├── blocks/                       # Custom Gutenberg blocks (uno per cartella)
│   ├── hero/                     # Block hero full-width con overlay e CTA
│   ├── testimonial/              # Citazione cliente con rating e foto autore
│   ├── stat/                     # Numero/statistica con prefisso e suffisso
│   └── icon-box/                 # Feature card con icona, titolo, testo, link
│
├── patterns/                     # Block patterns (22 layout preconfigurati)
│   └── *.php
│
├── resources/
│   ├── css/
│   │   ├── app.css               # Tailwind v4 + @theme design tokens
│   │   └── editor.css            # Stili editor Gutenberg (WYSIWYG)
│   ├── js/
│   │   ├── app.js                # Alpine.js bootstrap + GSAP + store + components
│   │   ├── editor.js             # Blocchi Gutenberg (React) + Style/Block Variations
│   │   └── modules/
│   │       ├── carousel.js       # Swiper initialization
│   │       ├── luxury-animations.js  # GSAP complex timelines
│   │       ├── scroll-effects.js     # ScrollTrigger effects
│   │       ├── magnetic-hover.js     # Hover animations
│   │       ├── locomotive-scroll.js  # Smooth scroll
│   │       └── wishlist.js       # Wishlist localStorage + custom element
│   └── views/                    # Blade templates
│       ├── layouts/
│       │   └── app.blade.php     # Layout principale (html, head, body, footer)
│       ├── sections/             # header, hero, footer, ecc.
│       ├── partials/             # Componenti riutilizzabili (card, wishlist-drawer, ecc.)
│       ├── woocommerce/          # Override Blade per WooCommerce
│       └── *.blade.php           # index, single, archive, search, front-page, ecc.
│
├── woocommerce/                  # Override PHP template WooCommerce
├── public/build/                 # Output Vite (NON modificare)
├── theme.json                    # Design tokens → Global Styles + Tailwind
├── functions.php                 # Entry point (boot Acorn, non modificare)
├── vite.config.js
├── package.json
└── composer.json
```

---

## 3. Flusso di lavoro

```bash
# Sviluppo locale
npm run dev          # Vite HMR — ricompila CSS/JS al salvataggio
npm run build        # Build produzione → public/build/

# PHP
composer install     # installa dipendenze PHP (Acorn, ecc.)

# Linting
npm run lint         # controlla JS con Biome
npm run fix-all      # auto-fix Biome
```

**Ordine di sviluppo consigliato per una nuova feature:**
1. Definisci attributi in `block.json`
2. Scrivi il render in `render.php` (Tailwind)
3. Scrivi i controlli React in `editor.js` (`registerBlockType`)
4. Aggiungi CSS editor in `editor.css` (per WYSIWYG)
5. Registra il blocco in `app/setup.php` → array `$blocks`
6. `npm run build` → testa nell'editor

---

## 4. Design System — theme.json

Il file `theme.json` è la **fonte di verità** di tutti i token di design.
Viene compilato da Vite in `public/build/assets/theme.json` e sincronizzato automaticamente con:
- Gutenberg Global Styles (colori, font, spacing nell'editor)
- Tailwind v4 (via `@theme {}` in `app.css`)

### 4.1 Font

**Solo Poppins** — un unico font per tutto il tema (body + headings).

| Slug | Font | Tailwind | CSS var |
|------|------|----------|---------|
| `sans` | Poppins | `font-sans` | `var(--wp--preset--font-family--sans)` |
| `serif` | Poppins (Titoli) | `font-serif` | `var(--wp--preset--font-family--serif)` |

> `serif` è un alias di `sans` — entrambi Poppins. Mantiene compatibilità con tutte le classi `font-serif` esistenti nel codice.

Caricato da Google Fonts in `app/setup.php` (admin_head + wp_head):
```
Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400
```

### 4.2 Token colori

| Token | Tailwind | Hex | Uso |
|-------|----------|-----|-----|
| `--color-accent` | `text-accent`, `bg-accent` | `#0074C7` | Blu principale — buttons, link hover |
| `--color-accent-light` | `bg-accent-light` | `#eff6ff` | Tint blu per badge/bg |
| `--color-gold` | `text-gold`, `bg-gold` | `#0074C7` | Alias accent per compatibilità legacy |
| `--color-primary` | `text-primary`, `bg-primary` | `#0074C7` | Alias accent per WooCommerce |
| `--color-ink` | `text-ink`, `bg-ink` | `#0a0a0a` | Testo scuro principale |
| `--color-ink-light` | `bg-ink-light` | `#1a1a1a` | Hover su ink |
| `--color-muted` | `text-muted` | `#6b6b6b` | Testo secondario/caption |
| `--color-border` | `border-border` | `#e0e0e0` | Divisori, bordi card |
| `--color-surface` | `bg-surface` | `#ffffff` | Background principale |
| `--color-surface-alt` | `bg-surface-alt` | `#f5f5f5` | Background alternativo |
| `--color-cream` | `bg-cream` | `#f5f5f5` | Background sezioni (alias surface-alt) |
| `--color-success` | `text-success`, `bg-success` | `#16a34a` | Stato success |
| `--color-warning` | `text-warning` | `#d97706` | Stato warning |
| `--color-error` | `text-error` | `#dc2626` | Stato error |

**Come aggiungere un colore:**
In `theme.json` → `settings.color.palette`:
```json
{ "slug": "brand-red", "color": "#e4002b", "name": "Brand Red" }
```
Poi in `app.css` dentro `@theme {}`:
```css
--color-brand-red: #e4002b;
```

### 4.3 Font sizes

| Slug | Size | Tailwind |
|------|------|----------|
| `xs` | 0.75rem | `text-xs` |
| `sm` | 0.875rem | `text-sm` |
| `base` | 1rem | `text-base` |
| `lg` | 1.125rem | `text-lg` |
| `xl` | 1.25rem | `text-xl` |
| `2xl` | 1.5rem | `text-2xl` |
| `3xl` | clamp(1.5rem, 2.5vw, 1.875rem) | `text-3xl` |
| `4xl` | clamp(1.75rem, 3.5vw, 2.25rem) | `text-4xl` |
| `5xl` | clamp(2rem, 4.5vw, 3rem) | `text-5xl` |
| `hero` | clamp(2.5rem, 5vw, 4.5rem) | `text-hero` |

### 4.4 Spacing

11 preset (slug numerico 1–11):
`4px → 8px → 12px → 16px → 24px → 32px → 48px → 64px → 96px → 128px → 192px`

Uso: `var(--wp--preset--spacing--7)` = 48px

### 4.5 Button palette (theme.json)

I button WordPress Gutenberg usano l'accent blu per default:

| Stile | BG | Testo | Bordo |
|-------|----|-------|-------|
| Default (filled) | `#0074C7` (accent) | `#ffffff` | — |
| Outline | `transparent` | `#0074C7` | `1px solid #0074C7` |
| Accent | `#0074C7` | `#ffffff` | — |

Il cliente può scegliere stile dal pannello Stili del blocco button nell'editor.

### 4.6 Il cliente può modificare tutto da WP

**Aspetto → Editor → icona paintbrush (Global Styles)**
Le modifiche del cliente vengono salvate nel DB e sovrascrivono theme.json senza toccare il codice.

---

## 5. Come creare un Custom Block

I blocchi custom vivono in `blocks/{nome}/`. Ogni blocco ha tre file obbligatori.

### 5.1 `block.json`

```json
{
  "$schema": "https://schemas.wp.org/trunk/block.json",
  "apiVersion": 3,
  "name": "theme/nome-blocco",
  "title": "Nome Blocco",
  "category": "theme",
  "textdomain": "sage",
  "render": "file:render.php",
  "supports": {
    "anchor": true,
    "align": ["wide", "full"],
    "html": false,
    "color": false
  },
  "attributes": {
    "titolo":   { "type": "string",  "default": "Titolo" },
    "testo":    { "type": "string",  "default": "" },
    "imageId":  { "type": "integer", "default": 0 },
    "imageUrl": { "type": "string",  "default": "" },
    "bg":       { "type": "string",  "default": "surface", "enum": ["surface","cream","ink"] },
    "abilitato":{ "type": "boolean", "default": true }
  }
}
```

### 5.2 `render.php`

```php
<?php
$titolo    = esc_html($attributes['titolo']    ?? '');
$testo     = wp_kses_post($attributes['testo'] ?? '');
$image_id  = (int)($attributes['imageId']      ?? 0);
$image_url = $image_id ? wp_get_attachment_image_url($image_id, 'large') : '';
$bg        = $attributes['bg'] ?? 'surface';

$bg_class = match($bg) {
    'ink'   => 'bg-ink text-white',
    'cream' => 'bg-cream',
    default => 'bg-surface',
};
?>
<div <?= get_block_wrapper_attributes(['class' => "theme-nome-blocco {$bg_class}"]) ?>>
  <?php if ($image_url): ?>
    <?= wp_get_attachment_image($image_id, 'medium', false, ['class' => 'w-full', 'loading' => 'lazy']) ?>
  <?php endif; ?>
  <?php if ($titolo): ?>
    <h3 class="font-sans text-2xl font-semibold"><?= $titolo ?></h3>
  <?php endif; ?>
  <?php if ($testo): ?>
    <div class="mt-2 text-muted"><?= $testo ?></div>
  <?php endif; ?>
</div>
```

**Regole render.php:**
- `get_block_wrapper_attributes()` sempre sul tag wrapper (aggiunge id, anchor, classi extra)
- `esc_html()` testo semplice, `wp_kses_post()` HTML fidato, `esc_url()` URL
- `wp_get_attachment_image()` (mai `<img>` diretti — srcset + lazy loading automatici)

### 5.3 Controlli editor in `editor.js`

Aggiungi in fondo a `resources/js/editor.js`:

```js
registerBlockType('theme/nome-blocco', {
  edit({ attributes, setAttributes }) {
    const { titolo, testo, imageId, imageUrl, bg, abilitato } = attributes
    return el(
      Fragment, null,
      el(InspectorControls, null,
        el(PanelBody, { title: __('Contenuto', 'sage'), initialOpen: true },
          el(TextControl, {
            label: __('Titolo', 'sage'),
            value: titolo ?? '',
            onChange: (val) => setAttributes({ titolo: val }),
          }),
          el(TextareaControl, {
            label: __('Testo', 'sage'),
            value: testo ?? '',
            onChange: (val) => setAttributes({ testo: val }),
          }),
        ),
        el(PanelBody, { title: __('Immagine', 'sage'), initialOpen: false },
          el(MediaPanel, {
            imageId, imageUrl,
            onSelect: (m) => setAttributes({ imageId: m.id, imageUrl: m.url }),
            onRemove: () => setAttributes({ imageId: 0, imageUrl: '' }),
          }),
        ),
        el(PanelBody, { title: __('Stile', 'sage'), initialOpen: false },
          el(SelectControl, {
            label: __('Sfondo', 'sage'),
            value: bg ?? 'surface',
            options: bgOptions,   // helper già definito nel file
            onChange: (val) => setAttributes({ bg: val }),
          }),
          el(ToggleControl, {
            label: __('Abilitato', 'sage'),
            checked: abilitato ?? true,
            onChange: (val) => setAttributes({ abilitato: val }),
          }),
        ),
      ),
      el('div', useBlockProps(),
        el(ServerSideRender, { block: 'theme/nome-blocco', attributes })
      ),
    )
  },
  save: () => null, // sempre null per blocchi SSR
})
```

**Componenti disponibili da `@wordpress/components`:**
`TextControl`, `TextareaControl`, `SelectControl`, `RangeControl`, `ToggleControl`, `CheckboxControl`, `Button`, `PanelBody`

**Helper già disponibili in editor.js:**
- `bgOptions` — array SelectControl per sfondo (surface/cream/ink)
- `MediaPanel({imageId, imageUrl, onSelect, onRemove})` — upload/selezione media

### 5.4 Registrazione in `app/setup.php`

```php
$blocks = ['hero', 'testimonial', 'stat', 'icon-box', 'nome-blocco']; // aggiungi qui
```

### 5.5 CSS editor in `editor.css` (per WYSIWYG)

```css
.editor-styles-wrapper .wp-block-theme-nome-blocco {
  border: 1px dashed #e0e0e0;
  padding: 1.5rem;
}
```

---

## 6. Come creare un Pattern

I pattern vivono in `patterns/*.php`. WordPress li carica automaticamente.

### Struttura header PHP

```php
<?php
/**
 * Title: Nome Sezione – Descrizione
 * Slug: theme/nome-sezione
 * Categories: theme-sections
 * Keywords: parola, chiave, sezione
 * Viewport Width: 1440
 */
?>
```

### Categorie disponibili

| Slug | Etichetta | Uso |
|------|-----------|-----|
| `theme-sections` | Theme – Sezioni | Hero, CTA, intro, media-text, stats |
| `theme-cards` | Theme – Card | Product, team, testimonial card |

### Pattern inventory (22 pattern)

| Slug | Title | Categoria |
|------|-------|-----------|
| `theme/hero` | Hero – Immagine di Sfondo con CTA | theme-sections |
| `theme/page-hero` | Page Hero – Intestazione Pagina | theme-sections |
| `theme/shop-hero` | Hero Shop – Fullscreen con CTA | theme-sections |
| `theme/intro-two-cols` | Intro – Due Colonne | theme-sections |
| `theme/media-text` | Media + Testo – Immagine a Sinistra | theme-sections |
| `theme/media-text-right` | Media + Testo – Immagine a Destra | theme-sections |
| `theme/image-text-list` | Immagine con Lista di Benefici | theme-sections |
| `theme/stats` | Stats – Numeri in Evidenza | theme-sections |
| `theme/testimonials` | Testimonial – Citazione Singola | theme-sections |
| `theme/full-width-quote` | Citazione Full Width | theme-sections |
| `theme/cta-banner` | CTA Banner | theme-sections |
| `theme/newsletter-cta` | CTA Newsletter – Dark | theme-sections |
| `theme/contact-section` | Sezione Contatti – Form + Info | theme-sections |
| `theme/usp-band` | Barra USP – Vantaggi | theme-sections |
| `theme/brand-logos` | Griglia Brand – Loghi Marchi | theme-sections |
| `theme/logos-grid` | Loghi Partner / Clienti | theme-sections |
| `theme/product-categories` | Griglia Categorie Prodotti | theme-sections |
| `theme/product-spotlight` | Prodotto in Evidenza | theme-sections |
| `theme/services-grid` | Griglia Servizi – 3 Colonne | theme-sections, theme-cards |
| `theme/portfolio-grid` | Portfolio – Griglia Case Study | theme-sections, theme-cards |
| `theme/team-member-card` | Scheda Membro del Team | theme-sections, theme-cards |
| `theme/text-with-aside` | Testo con Colonna Laterale | theme-sections |

**Il modo più rapido per creare un pattern:**
1. Costruisci il layout nell'editor
2. Seleziona blocchi → **Opzioni (⋮) → Copia come HTML**
3. Incolla nel file `.php` del pattern

---

## 7. Come creare un Template (block template)

I block template HTML vivono in `/templates/*.html` e sono modificabili dal cliente tramite **Aspetto → Editor → Template**.

> **Priorità:** I Blade template hanno precedenza. Se esiste `resources/views/single.blade.php`, Sage usa quello e ignora `templates/single.html`.

### Template slugs standard

| File | Quando viene usato |
|------|--------------------|
| `templates/index.html` | Fallback generale |
| `templates/front-page.html` | Homepage |
| `templates/single.html` | Singolo post |
| `templates/page.html` | Singola pagina |
| `templates/archive.html` | Archivi |
| `templates/404.html` | Pagina 404 |

### Template parts

Le parti di layout (header/footer) vivono in `/parts/*.html` e sono editabili dal Site Editor.

---

## 8. Block Style Variations

Registrate in `resources/js/editor.js` nel listener `DOMContentLoaded`.

| Blocco | Stile | Classe CSS |
|--------|-------|-----------|
| `core/button` | Outline | `.is-style-outline` |
| `core/button` | Accent (Blue filled) | `.is-style-accent` |
| `core/button` | Ghost (underline) | `.is-style-ghost` |
| `core/heading` | Display | `.is-style-display` |
| `core/heading` | Overline | `.is-style-overline` |
| `core/separator` | Spesso | `.is-style-thick` |
| `core/separator` | Accent | `.is-style-accent` |
| `core/quote` | Minimal | `.is-style-minimal` |
| `core/quote` | Grande | `.is-style-large` |
| `core/image` | Arrotondato | `.is-style-rounded` |
| `core/image` | Con cornice | `.is-style-framed` |
| `core/group` | Card | `.is-style-card` |
| `core/group` | Bordered | `.is-style-bordered` |

**CSS:** in `editor.css` (editor) e `app.css` (frontend) con `.is-style-{name}`.

---

## 9. Block Variations

Preset preconfigurati con il design system. Appaiono nell'inserter sotto la categoria "Theme".

| Variazione | Blocco base | Descrizione |
|------------|------------|-------------|
| Hero Section | `core/cover` | Cover full-width 80vh con overlay ink |
| Content Card | `core/group` | Group con padding + bordo su sfondo surface-alt |
| Sezione Scura | `core/group` | Group full-width sfondo ink, testo white |
| Colonne 60/40 | `core/columns` | Due colonne asimmetriche, align wide |
| 3 Colonne uguali | `core/columns` | Tre colonne 33.33% |

---

## 10. Blade Templates (frontend PHP)

### Gerarchia template key

| File | Quando viene usato |
|------|--------------------|
| `layouts/app.blade.php` | Layout master (html, head, header, footer) |
| `front-page.blade.php` | Homepage |
| `page.blade.php` | Pagine generiche |
| `page-{slug}.blade.php` | Pagina specifica per slug (es. `page-wishlist.blade.php`) |
| `single.blade.php` | Singolo post |
| `single-{post-type}.blade.php` | Singolo CPT |
| `archive.blade.php` | Archivi |
| `archive-{post-type}.blade.php` | Archivio CPT |
| `woocommerce.blade.php` | Tutte le pagine WooCommerce |
| `template-*.blade.php` | Template di pagina selezionabile dall'editor |

> **WooCommerce:** `woocommerce.blade.php` usa `the_content()` per cart/checkout/account (supporta WC Cart Block di WC 9.x) e `woocommerce_content()` per shop/archivi.

### Includere una sezione

```blade
@include('sections.header')
@include('partials.cart-drawer')
@include('partials.product-card', ['product' => $product])
```

### View Composers

Iniettano dati in view specifiche. Vivono in `app/View/Composers/`.

```php
class FrontPage extends Composer
{
    protected static $views = ['front-page'];
    public function with(): array { return ['featuredPosts' => ...]; }
}
```

### Output sicuro in Blade

```blade
{{ get_the_title() }}          {{-- escapato automaticamente --}}
{!! get_the_content() !!}      {{-- HTML fidato da WP --}}
{{-- MAI: {!! $_GET['input'] !!} ← XSS --}}
```

---

## 11. Alpine.js — componenti reattivi

### Store globale (`app.js`)

```js
Alpine.store('layout', {
  hasHero: false,    // true se la pagina ha un hero (transparent header)
  cartCount: 0,      // badge carrello WooCommerce
})
```

### Componenti registrati

| Componente | x-data | Descrizione |
|------------|--------|-------------|
| `siteHeader` | `x-data="siteHeader"` | Header scrolled/expanded, mega menu, mobile drawer |
| `searchOverlay` | `x-data="searchOverlay"` | Overlay ricerca live con REST API |
| `cartDrawer()` | `x-data="cartDrawer()"` | Drawer carrello WooCommerce off-canvas |

### Aggiungere un componente

```js
// In app.js
Alpine.data('mioComponente', () => ({
  aperto: false,
  toggle() { this.aperto = !this.aperto },
}))
```

Nel Blade:
```blade
<div x-data="mioComponente">
  <button @click="toggle">Apri</button>
  <div x-show="aperto" x-transition>Contenuto</div>
</div>
```

---

## 12. Custom Post Types

Registrati in `app/post-types.php`. Tutti `show_in_rest: true` per compatibilità Gutenberg.

| CPT | Slug | Archive | Taxonomy | Template Blade |
|-----|------|---------|----------|----------------|
| `portfolio` | `/portfolio/*` | ✅ | `portfolio_category` | `single-portfolio.blade.php`, `archive-portfolio.blade.php` |
| `team` | `/team/*` | ❌ | `team_department` | `single-team.blade.php`, `archive-team.blade.php` |
| `faq` | `/faq/*` | ❌ | `faq_category` | `archive-faq.blade.php` |

### Meta keys

| CPT | Meta key | Tipo |
|-----|----------|------|
| portfolio | `_portfolio_client`, `_portfolio_year`, `_portfolio_services`, `_portfolio_url` | string |
| team | `_team_role`, `_team_email`, `_team_linkedin` | string |

---

## 13. REST API endpoints custom

Tutti definiti in `app/ajax.php` e `app/filters.php`. Tutti pubblici (`permission_callback: __return_true`).

| Metodo | Route | File | Descrizione |
|--------|-------|------|-------------|
| `GET` | `/wp-json/theme/v1/search` | ajax.php | Live search (post + prodotti) |
| `GET` | `/wp-json/theme/v1/quick-view/{id}` | ajax.php | Dati prodotto per quick view |
| `GET` | `/wp-json/theme/v1/products` | ajax.php | Prodotti filtrati (faceted search) |
| `GET` | `/wp-json/theme/v1/wishlist-products` | ajax.php | Prodotti per ID (wishlist page) |
| `POST` | `/wp-json/theme/v1/wishlist` | ajax.php | Toggle wishlist (utenti loggati) |
| `POST` | `/wp-json/theme/v1/newsletter` | filters.php | Iscrizione newsletter |
| `POST` | `/wp-json/theme/v1/contact` | ajax.php | Form contatti (legacy: admin_post) |

### Parametri chiave

**GET /search** — `q` (min 2 char), `per_page` (default 6, max 12), `type` (any/post/product)

**GET /products** — `cats[]`, `min_price`, `max_price`, `in_stock`, `orderby` (date/price/price-desc/popularity/rating/title), `per_page` (max 48), `page`

**GET /wishlist-products** — `ids` (comma-separated product IDs, max 50)
→ Risposta: `{ products: [{ id, title, url, thumb, price_html, in_stock, on_sale }] }`

**GET /quick-view/{id}** — nessun param
→ Risposta: `{ id, title, url, price_html, thumb, gallery, short_desc, category, in_stock, on_sale, rating, attributes, add_to_cart_url }`

---

## 14. Wishlist System

Sistema wishlist completo basato su localStorage per ospiti, user meta per utenti loggati.

### Architettura

```
localStorage("theme:wishlist")  ←→  wishlist.js  ←→  .wishlist-btn[data-product-id]
                                          ↓
                               /wp-json/theme/v1/wishlist-products?ids=...
                                          ↓
                               <wishlist-products> custom element
```

### Frontend (wishlist.js)

**Funzioni esposte:**
- `window.initWishlistButtons()` — re-inizializza i pulsanti (usare dopo AJAX/load dinamico)

**Classi CSS riconosciute:**
- `.wishlist-btn[data-product-id="123"]` — toggle button (aggiunge/rimuove da wishlist)
- `.wishlist-count-bubble` — badge con conteggio (aggiornato automaticamente)
- `.wishlist-dot` — punto indicatore (`.is-visible` se wishlist non vuota)

**Stato active:**
- `.wishlist-btn.active` → prodotto nella wishlist

### Custom element `<wishlist-products>`

Renderizza i prodotti della wishlist sulla pagina dedicata.

```html
<wishlist-products
  products-limit="50"
  empty-label="La tua wishlist è vuota."
  class="grid grid-cols-2 lg:grid-cols-4 gap-6"
></wishlist-products>
```

Fetch automatico da `/wp-json/theme/v1/wishlist-products?ids=...` al mount.

### Pagina wishlist

La pagina con slug `/wishlist` usa `resources/views/page-wishlist.blade.php` automaticamente (Sage template hierarchy).

**Setup:** Crea una pagina WordPress con slug `wishlist` — il template viene applicato automaticamente.

### Aggiungere un pulsante wishlist su una product card

```blade
<button
  class="wishlist-btn"
  data-product-id="{{ $product->get_id() }}"
  aria-label="{{ __('Aggiungi alla wishlist', 'sage') }}"
  aria-pressed="false"
>
  <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
  </svg>
</button>
```

Stile `.wishlist-btn.active` → imposta `fill="currentColor"` via CSS in `app.css`.

---

## 15. WooCommerce — Integrazione

### Theme support (setup.php)

```php
add_theme_support('woocommerce', [
    'thumbnail_image_width' => 600,
    'single_image_width'    => 800,
    'product_grid' => ['default_rows' => 3, 'default_columns' => 3, 'max_columns' => 4],
]);
add_theme_support('wc-product-gallery-zoom');
add_theme_support('wc-product-gallery-lightbox');
add_theme_support('wc-product-gallery-slider');
```

### PHP Template overrides (woocommerce/)

| File | Scopo |
|------|-------|
| `content-product.php` | Loop item card (griglia prodotti) |
| `single-product/product-image.php` | Gallery principale single product |
| `single-product/tabs.php` | Description/Reviews/Related tabs |
| `single-product/related.php` | Prodotti correlati |
| `emails/email-header.php` | Header email transazionale |
| `emails/email-footer.php` | Footer email transazionale |
| `emails/email-styles.php` | CSS inline email |

### Filtri WC applicati (filters.php)

- `woocommerce_add_to_cart_fragments` — aggiorna badge carrello `.cart-count-fragment` via AJAX
- `woocommerce_shortcode_products_query` — limita a 12 prodotti max (evita memory exhaustion)
- `woocommerce_before_main_content` — rimuove breadcrumb default

### Cart page fix (woocommerce.blade.php)

Per compatibilità con **WC Cart Block (WC 9.x+)**:
```blade
@if(is_cart() || is_checkout() || is_account_page())
  @while(have_posts()) @php the_post() @endphp
    @php the_content() @endphp
  @endwhile
@else
  @php woocommerce_content() @endphp
@endif
```

### Cart Drawer

`partials/cart-drawer.blade.php` — drawer off-canvas con:
- Fragment WC per aggiornamento automatico add-to-cart
- Free shipping progress bar (soglia configurabile via `get_theme_mod('free_shipping_threshold')`)
- Alpine component `cartDrawer()` con jQuery WC events

---

## 16. Regole di codice

### PHP
- **Namespace:** `App\` in tutti i file in `app/`
- **Output:** sempre `esc_html()`, `esc_url()`, `esc_attr()`, `wp_kses_post()`
- **Input:** sempre `sanitize_text_field()`, `sanitize_email()`, `absint()`
- **Query:** sempre `WP_Query` o `$wpdb->prepare()` — mai SQL raw
- **Strict types:** `declare(strict_types=1)` nei nuovi file

### JavaScript
- **ES modules** — no jQuery nel frontend (solo dove WC lo richiede)
- **Alpine** — tutti i componenti reattivi in `app.js`
- **Import** — `@wordpress/*` sono external (window.wp.*), non importare da npm
- **Biome** — linting con `npm run lint`, fix con `npm run fix-all`

### CSS / Tailwind
- **Utility-first** — usa classi Tailwind, non stili inline
- **Design tokens** — aggiungi variabili in `@theme {}` di `app.css`, non hardcodare colori o font
- **Font:** usa solo `font-sans` (body) e `font-serif` (titoli) — entrambi Poppins
- **Editor** — duplica gli stili importanti in `editor.css` per WYSIWYG accurato

### Accessibilità
- Ogni elemento interattivo: `aria-label` o label visibile
- Immagini decorative: `alt=""` + `aria-hidden="true"`
- Carousels: `role="region"`, `aria-roledescription="carousel"`
- Modal/drawer: `x-trap.inert` (Alpine Focus plugin)

---

## 17. Customizer keys

Accessibili con `get_theme_mod('chiave')`. Definite in `app/customizer.php`.

| Key | Sezione | Default | Uso |
|-----|---------|---------|-----|
| `social_instagram` | theme_social | `''` | Link Instagram nel footer/mobile drawer |
| `social_facebook` | theme_social | `''` | Link Facebook |
| `social_tiktok` | theme_social | `''` | Link TikTok |
| `social_youtube` | theme_social | `''` | Link YouTube |
| `cta_url` | theme_theme | `''` | URL del pulsante CTA header |
| `header_cta_label` | theme_theme | `''` | Testo del pulsante CTA header |
| `footer_tagline` | theme_theme | testo default | Tagline nel footer |
| `newsletter_heading` | theme_theme | testo default | Titolo sezione newsletter |
| `announcement_bar_active` | theme_announcement | `false` | Mostra/nascondi barra annunci |
| `announcement_bar_text` | theme_announcement | `''` | Testo barra annunci |
| `announcement_bar_cta_text` | theme_announcement | `''` | Testo CTA barra |
| `announcement_bar_cta_url` | theme_announcement | `''` | URL CTA barra |
| `free_shipping_threshold` | theme_wc | `0` | Soglia spedizione gratuita (€) nel cart drawer |

> **Header CTA:** Viene mostrato solo se `cta_url` O `header_cta_label` sono impostati nel Customizer.

---

## 18. File da NON toccare

| Path | Motivo |
|------|--------|
| `vendor/` | Gestito da Composer |
| `node_modules/` | Gestito da npm |
| `public/build/` | Generato da Vite — sovrascritto ad ogni build |
| `composer.lock` | Aggiorna solo con `composer update` intenzionale |
| `package-lock.json` | Aggiorna solo con `npm install` intenzionale |
| `functions.php` | Solo boot Acorn — non aggiungere logica qui |

---

## Quick reference — checklist nuovo blocco

```
[ ] Crea cartella  blocks/nome-blocco/
[ ] Crea           blocks/nome-blocco/block.json   (name, attributes, render)
[ ] Crea           blocks/nome-blocco/render.php   (output PHP + Tailwind, font-sans per body)
[ ] Aggiungi       'nome-blocco' in app/setup.php → array $blocks
[ ] Aggiungi       registerBlockType('theme/nome-blocco', { edit, save }) in editor.js
[ ] Aggiungi CSS   editor.css  (per WYSIWYG)
[ ] npm run build
[ ] Testa nell'editor WP
[ ] (Opzionale) Crea pattern in patterns/nome.php che usa il blocco
```

## Quick reference — checklist nuovo pattern

```
[ ] Crea file      patterns/nome-pattern.php
[ ] Aggiungi header PHP (Title, Slug, Categories, Keywords)
[ ] Copia HTML     dall'editor WP (Copia come HTML)
[ ] Incolla blocchi nel file .php
[ ] Ricarica l'editor WP → il pattern appare nell'inserter
```

## Quick reference — checklist nuova pagina custom

```
[ ] Crea           resources/views/page-{slug}.blade.php  (per slug specifico)
[ ] OPPURE         resources/views/template-nome.blade.php (selezionabile da editor)
[ ] Crea la pagina in WP Admin con lo slug corretto
[ ] Se serve un endpoint: aggiungi in app/ajax.php con sanitize + permission_callback
```

---

## 19. Audit — Correzioni applicate

Risultati dell'audit deep 2026-03-24 e fix applicati al codebase.

### 19.1 Bug critici risolti

| File | Problema | Fix |
|------|----------|-----|
| `resources/css/editor.css` | `font-family: "Inter"` hardcoded su h1-h6 (r.16) e `core/quote` (r.118) | Sostituito con `"Poppins"` |
| `resources/css/app.css` | Classe `.container` assente — tutte le Blade view la usano senza definizione | Aggiunta con `max-width:1200px`, `margin-inline:auto`, padding fluid |
| `resources/css/app.css` | `.theme-form`, `.theme-form__row/label/input/textarea/privacy/checkbox-label/submit/feedback` assenti — form contatti completamente non stilizzato | Aggiunte tutte le classi |
| `resources/css/app.css` | `.theme-btn`, `.theme-btn--primary/outline/ink/full` assenti — bottoni del pattern contatti senza stile | Aggiunte |
| `resources/css/app.css` | `.wishlist-btn`, `.wishlist-count-bubble`, `.wishlist-dot` assenti — wishlist.js li usa ma CSS non le definisce | Aggiunte con stati `.active` e `.is-visible` |
| `theme.json` | `useRootPaddingAwareAlignments` assente — allineamenti full-width imprecisi | Aggiunto `true` |
| `theme.json` | `settings.dimensions` assente — nessuna scelta aspect ratio nell'editor | Aggiunti 4 preset (portrait, landscape, square, wide) |
| `theme.json` | `settings.shadow` assente — nessun shadow preset per i blocchi | Aggiunti 3 preset (subtle, medium, large) |
| `theme.json` | `styles.spacing.blockGap` assente — gap verticale tra blocchi non definito | Impostato a spacing-6 (32px) |
| `theme.json` | `styles.elements.link.:focus` assente — violazione WCAG 2.1 AA | Aggiunto `:focus` con colore accent + underline |
| `theme.json` | `styles.blocks.core/list` assente — liste senza font/spacing coerente | Aggiunto con font-sans e padding-left |
| `theme.json` | `styles.blocks.core/table` assente — tabelle senza bordi/font | Aggiunto con border e font-sm |
| `theme.json` | `styles.blocks.core/gallery` assente — gap gallery non definito | Aggiunto con blockGap: spacing-4 |

### 19.2 Problemi nei pattern (da correggere manualmente)

| Pattern | Problema | Azione consigliata |
|---------|----------|---------------------|
| Vari pattern con colonne | `isStackedOnMobile` non impostato su `core/columns` | Aggiungere `"isStackedOnMobile": true` nell'HTML del blocco |
| `contact-section.php` | URL tel/email hardcoded (`href="tel:+39..."`) | Sostituire con valori da Customizer (`get_theme_mod()`) |
| Pattern con sfondi (`hero`, `newsletter-cta`, ecc.) | Colore `#f6f4f2` hardcoded invece di token `cream` | Sostituire con `var(--wp--preset--color--cream)` |
| Pattern con testi scuri | `#0f0f0f` invece di token `ink` | Sostituire con `var(--wp--preset--color--ink)` |

### 19.3 Pattern: come aggiungere `isStackedOnMobile`

Nei file pattern `.php`, cerca blocchi `core/columns` e aggiungi l'attributo:

```html
<!-- wp:columns {"isStackedOnMobile":true} -->
```

---

## 20. Pattern mancanti — backlog

7 pattern ad alta priorità non ancora creati. Aggiungerli in `patterns/`.

| Slug | Titolo | Categoria | Priorità |
|------|--------|-----------|----------|
| `theme/pricing-table` | Tabella Prezzi — 3 Piani | theme-sections | Alta |
| `theme/timeline` | Timeline / Processo — Verticale | theme-sections | Alta |
| `theme/faq-accordion` | FAQ — Accordion | theme-sections | Alta |
| `theme/before-after` | Before/After — Slider Confronto | theme-sections | Media |
| `theme/video-section` | Sezione Video — Sfondo o Inline | theme-sections | Media |
| `theme/map-contact` | Mappa + Contatti — Split | theme-sections | Media |
| `theme/review-aggregate` | Recensioni Aggregate — Rating + Badge | theme-sections | Media |

### Schema header per nuovo pattern

```php
<?php
/**
 * Title: Pricing Table — 3 Piani
 * Slug: theme/pricing-table
 * Categories: theme-sections
 * Keywords: prezzi, pricing, piani, abbonamento
 * Viewport Width: 1440
 */
?>
```

### Classi CSS già disponibili per i nuovi pattern

| Pattern | Classi pronte in app.css |
|---------|--------------------------|
| FAQ Accordion | `.faq-item`, `.faq-item__trigger`, `.faq-item__icon`, `.faq-item__answer`, `.faq-item--dark` |
| Timeline | `.process-step`, `.process-step--v`, `.process-step--h`, `.process-step__icon-wrap`, `.process-step__title` |
| Before/After | `.before-after`, `.before-after__before`, `.before-after__handle`, `.before-after__label` |
| Countdown | `.countdown-unit`, `.countdown-num`, `.countdown-label` |
