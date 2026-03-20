# CLAUDE.md — sage-theme

> Documentazione tecnica completa del tema. Leggila prima di qualsiasi modifica.
> Aggiornata: 2026-03-20

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
14. [Regole di codice](#14-regole-di-codice)
15. [Customizer keys](#15-customizer-keys)
16. [File da NON toccare](#16-file-da-non-toccare)

---

## 1. Stack

| Layer        | Tecnologia                                        | Versione |
|--------------|---------------------------------------------------|----------|
| Framework    | Roots Sage + Acorn (Laravel per WP)               | 11 / 5   |
| Template FE  | Laravel Blade (`.blade.php`)                      | —        |
| CSS          | Tailwind CSS v4 + design tokens `@theme {}`       | 4.x      |
| Build        | Vite + @roots/vite-plugin                         | 7.x      |
| JS reattivo  | Alpine.js 3                                       | 3.x      |
| Animazioni   | GSAP 3 + ScrollTrigger                            | 3.x      |
| Carousel     | Swiper 12 + Locomotive Scroll 5                   | —        |
| Backend      | WordPress 6.x + WooCommerce                       | —        |
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
│   ├── ajax.php                  # handler AJAX / REST (search, form contatti, wishlist)
│   ├── customizer.php            # Pannello Customizer (colori social, CTA, annuncio)
│   ├── post-types.php            # CPT: portfolio, team, faq
│   ├── Providers/
│   │   └── ThemeServiceProvider.php   # boot Acorn (Laravel)
│   └── View/Composers/               # iniettano dati nelle view Blade
│
├── blocks/                       # Custom Gutenberg blocks (uno per cartella)
│   ├── hero/
│   │   ├── block.json            # metadati, attributi, supports
│   │   └── render.php            # template PHP lato frontend
│   ├── testimonial/
│   ├── stat/
│   └── icon-box/
│
├── patterns/                     # Block patterns (layout preconfigurati)
│   └── *.php                     # ogni file = un pattern
│
├── resources/
│   ├── css/
│   │   ├── app.css               # Tailwind v4 + @theme design tokens
│   │   └── editor.css            # stili editor Gutenberg (WYSIWYG)
│   ├── js/
│   │   ├── app.js                # Alpine.js bootstrap + GSAP + Swiper
│   │   ├── editor.js             # blocchi Gutenberg (React) + Style/Block Variations
│   │   └── modules/              # moduli JS separati
│   │       ├── carousel.js
│   │       ├── luxury-animations.js
│   │       ├── scroll-effects.js
│   │       ├── magnetic-hover.js
│   │       └── locomotive-scroll.js
│   └── views/                    # Blade templates
│       ├── layouts/
│       │   └── app.blade.php     # layout principale (html, head, body, footer)
│       ├── sections/             # header, hero, footer, announcement bar
│       ├── partials/             # componenti riutilizzabili (card, button, ecc.)
│       ├── woocommerce/          # override Blade per WooCommerce
│       └── *.blade.php           # index, single, archive, search, front-page, ecc.
│
├── woocommerce/                  # override PHP template WooCommerce
├── public/build/                 # output Vite (NON modificare)
├── theme.json                    # design tokens → Global Styles + Tailwind
├── functions.php                 # entry point (boot Acorn, non modificare)
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
3. Scrivi i controlli React in `editor.js`
4. Aggiungi CSS editor in `editor.css` (per WYSIWYG)
5. Registra il blocco in `app/setup.php`
6. `npm run build` → testa nell'editor

---

## 4. Design System — theme.json

Il file `theme.json` è la **fonte di verità** di tutti i token di design.
Viene compilato da Vite in `public/build/assets/theme.json` e sincronizzato automaticamente con:
- Gutenberg Global Styles (colori, font, spacing nell'editor)
- Tailwind v4 (via `@theme {}` in `app.css`)

### Token disponibili

| Token | Come usarlo in Tailwind | Come usarlo in PHP/CSS |
|---|---|---|
| `--color-ink` | `text-ink`, `bg-ink` | `var(--wp--preset--color--ink)` |
| `--color-accent` | `text-accent`, `bg-accent` | `var(--wp--preset--color--accent)` |
| `--color-cream` | `bg-cream` | `var(--wp--preset--color--cream)` |
| `--font-sans` (Poppins) | `font-sans` | `var(--wp--preset--font-family--sans)` |
| `--font-serif` (Inter) | `font-serif` | `var(--wp--preset--font-family--serif)` |
| `--font-size-hero` | `text-hero` | `var(--wp--preset--font-size--hero)` |
| Spacing slug `7` | — | `var(--wp--preset--spacing--7)` |

### Come aggiungere un colore

In `theme.json` → `settings.color.palette`:
```json
{ "slug": "brand-red", "color": "#e4002b", "name": "Brand Red" }
```

Poi in `app.css` dentro `@theme {}`:
```css
--color-brand-red: #e4002b;
```

Da quel momento è disponibile sia come `bg-brand-red` in Tailwind che nel color picker dell'editor.

### Come aggiungere una dimensione font

In `theme.json` → `settings.typography.fontSizes`:
```json
{ "slug": "display", "size": "clamp(3rem, 7vw, 6rem)", "name": "Display" }
```

### Il cliente può modificare tutto da WP

**Aspetto → Editor → icona paintbrush (Global Styles)**
Le modifiche del cliente vengono salvate nel DB e sovrascrivono theme.json senza toccare il codice.

---

## 5. Come creare un Custom Block

I blocchi custom vivono in `blocks/{nome}/`. Ogni blocco ha tre file:

### 5.1 `block.json` — metadati e attributi

```json
{
  "$schema": "https://schemas.wp.org/trunk/block.json",
  "apiVersion": 3,
  "name": "theme/nome-blocco",
  "title": "Nome Blocco",
  "category": "theme",
  "description": "Descrizione breve per il cliente.",
  "keywords": ["parola", "chiave"],
  "textdomain": "sage",
  "render": "file:render.php",
  "supports": {
    "anchor": true,
    "align": ["wide", "full"],
    "html": false,
    "color": false
  },
  "attributes": {
    "titolo": {
      "type": "string",
      "default": "Titolo di esempio"
    },
    "testo": {
      "type": "string",
      "default": ""
    },
    "layout": {
      "type": "string",
      "default": "vertical",
      "enum": ["vertical", "horizontal"]
    },
    "imageId": {
      "type": "integer",
      "default": 0
    },
    "imageUrl": {
      "type": "string",
      "default": ""
    },
    "abilitato": {
      "type": "boolean",
      "default": true
    }
  }
}
```

**Tipi di attributo supportati:** `string`, `integer`, `boolean`, `number`, `array`, `object`

**`supports` più utili:**
- `"anchor": true` — aggiunge campo ID (per link interni)
- `"align": ["wide", "full"]` — abilita larghezza piena/ampia
- `"html": false` — disabilita modifica HTML diretta (consigliato per SSR)
- `"color": false` — usa palette tua invece dei controlli colore default WP

### 5.2 `render.php` — template lato frontend

```php
<?php
/**
 * Block: theme/nome-blocco
 *
 * @var array    $attributes  Attributi del blocco.
 * @var string   $content     Inner blocks HTML (se il blocco ha InnerBlocks).
 * @var WP_Block $block       Istanza del blocco.
 */

// Sempre sanitizzare in output
$titolo  = esc_html($attributes['titolo'] ?? '');
$testo   = wp_kses_post($attributes['testo'] ?? '');
$layout  = $attributes['layout'] ?? 'vertical';
$image_id  = (int) ($attributes['imageId'] ?? 0);
$image_url = $image_id ? wp_get_attachment_image_url($image_id, 'large') : '';
$abilitato = (bool) ($attributes['abilitato'] ?? true);

if (! $abilitato) {
    return; // Non renderizzare se disabilitato
}

$layout_class = $layout === 'horizontal' ? 'flex-row gap-8' : 'flex-col gap-4';
?>
<div <?= get_block_wrapper_attributes(['class' => 'theme-nome-blocco']) ?>>
  <div class="flex <?= esc_attr($layout_class) ?> items-start">

    <?php if ($image_url) : ?>
      <?= wp_get_attachment_image($image_id, 'medium', false, [
          'class'   => 'w-16 h-16 object-cover',
          'loading' => 'lazy',
      ]) ?>
    <?php endif; ?>

    <div>
      <?php if ($titolo) : ?>
        <h3 class="font-serif text-2xl font-light text-ink"><?= $titolo ?></h3>
      <?php endif; ?>

      <?php if ($testo) : ?>
        <div class="mt-2 text-muted leading-relaxed"><?= $testo ?></div>
      <?php endif; ?>
    </div>

  </div>
</div>
```

**Regole render.php:**
- Usa sempre `get_block_wrapper_attributes()` sul tag wrapper — aggiunge classi, id, anchor
- `esc_html()` per testo semplice, `wp_kses_post()` per HTML fidato, `esc_url()` per URL
- `wp_get_attachment_image()` invece di `<img>` dirette (gestisce srcset, lazy loading)
- Non usare mai `echo $_GET[...]` o variabili non sanitizzate

### 5.3 Controlli editor in `editor.js`

Aggiungi in fondo a `resources/js/editor.js`:

```js
registerBlockType('theme/nome-blocco', {
  edit({ attributes, setAttributes }) {
    const { titolo, testo, layout, imageId, imageUrl, abilitato } = attributes

    return el(
      Fragment,
      null,

      el(
        InspectorControls,
        null,

        el(
          PanelBody,
          { title: __('Contenuto', 'sage'), initialOpen: true },
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

        el(
          PanelBody,
          { title: __('Immagine', 'sage'), initialOpen: false },
          el(MediaPanel, {    // componente helper già definito nel file
            imageId,
            imageUrl,
            onSelect: (media) => setAttributes({ imageId: media.id, imageUrl: media.url }),
            onRemove: () => setAttributes({ imageId: 0, imageUrl: '' }),
          }),
        ),

        el(
          PanelBody,
          { title: __('Layout', 'sage'), initialOpen: false },
          el(SelectControl, {
            label: __('Orientamento', 'sage'),
            value: layout ?? 'vertical',
            options: [
              { label: __('Verticale', 'sage'), value: 'vertical' },
              { label: __('Orizzontale', 'sage'), value: 'horizontal' },
            ],
            onChange: (val) => setAttributes({ layout: val }),
          }),
          el(ToggleControl, {
            label: __('Abilitato', 'sage'),
            checked: abilitato ?? true,
            onChange: (val) => setAttributes({ abilitato: val }),
          }),
        ),
      ),

      // Anteprima nel editor (server-side render via REST)
      el('div', useBlockProps(),
        el(ServerSideRender, { block: 'theme/nome-blocco', attributes })
      ),
    )
  },
  save: () => null, // sempre null per blocchi SSR
})
```

**Componenti disponibili da `@wordpress/components`:**
- `TextControl` — input testo singola riga
- `TextareaControl` — textarea multiriga
- `SelectControl` — select/dropdown
- `RangeControl` — slider numerico (min/max/step)
- `ToggleControl` — on/off switch
- `CheckboxControl` — checkbox
- `ColorPicker` — selettore colore
- `MediaUpload` + `MediaUploadCheck` — upload/selezione media (usa `MediaPanel` helper già nel file)

### 5.4 Registrazione in `app/setup.php`

```php
add_action('init', function () {
    $blocks = ['hero', 'testimonial', 'stat', 'icon-box', 'nome-blocco']; // aggiungi qui
    foreach ($blocks as $name) {
        $dir = get_template_directory() . "/blocks/{$name}";
        if (is_dir($dir)) {
            register_block_type($dir);
        }
    }
});
```

### 5.5 CSS editor in `editor.css` (opzionale, per WYSIWYG)

```css
/* Stile base del blocco nell'editor */
.editor-styles-wrapper .wp-block-theme-nome-blocco {
  border: 1px solid #e0e0e0;
  padding: 1.5rem;
}
```

---

## 6. Come creare un Pattern

I pattern sono layout preconfigurati che il cliente inserisce con un click dall'inserter.
Vivono in `patterns/*.php`.

### 6.1 Struttura di un pattern

```php
<?php
/**
 * Title: Nome Sezione – Descrizione
 * Slug: theme/nome-sezione
 * Categories: theme-sections
 * Keywords: parola, chiave, sezione
 * Description: Descrizione breve per il cliente.
 * Viewport Width: 1440
 */
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"6rem","bottom":"6rem"}}},"backgroundColor":"cream","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-cream-background-color has-background" style="padding-top:6rem;padding-bottom:6rem">

  <!-- wp:heading {"textAlign":"center","level":2,"fontFamily":"serif"} -->
  <h2 class="wp-block-heading has-text-align-center has-serif-font-family">Titolo della sezione</h2>
  <!-- /wp:heading -->

  <!-- wp:paragraph {"align":"center","textColor":"muted"} -->
  <p class="has-text-align-center has-muted-color has-text-color">Sottotitolo descrittivo della sezione.</p>
  <!-- /wp:paragraph -->

</div>
<!-- /wp:group -->
```

### 6.2 Come ottenere il blocco HTML

Il modo più semplice per creare pattern è:
1. Costruire il layout nell'editor di Gutenberg
2. Selezionare i blocchi → **Opzioni (⋮) → Copia come HTML**
3. Incollare il codice nel file `.php` del pattern

### 6.3 Categorie disponibili

| Slug | Etichetta | Uso |
|---|---|---|
| `theme-sections` | Theme – Sezioni | Hero, CTA, intro, media-text |
| `theme-cards` | Theme – Card | Product, team, testimonial card |
| `theme-carousel` | Theme – Carousel | Swiper, related, scroll orizzontale |

Per aggiungere una nuova categoria, in `app/setup.php`:
```php
register_block_pattern_category('theme-nuova', [
    'label'       => __('Theme – Nuova', 'sage'),
    'description' => __('Descrizione categoria.', 'sage'),
]);
```

### 6.4 I pattern NON si registrano — WordPress li rileva automaticamente

WordPress carica automaticamente tutti i file in `/patterns/*.php`.
L'header PHP (commenti `Title:`, `Slug:`, ecc.) è sufficiente per la registrazione.

### 6.5 Pattern con blocchi custom del tema

```php
<?php
/**
 * Title: Sezione Statistiche
 * Slug: theme/stats-section
 * Categories: theme-sections
 */
?>
<!-- wp:group {"align":"full","backgroundColor":"ink"} -->
<div class="wp-block-group alignfull has-ink-background-color has-background">

  <!-- wp:columns {"align":"wide"} -->
  <div class="wp-block-columns alignwide">

    <!-- wp:column -->
    <div class="wp-block-column">
      <!-- wp:theme/stat {"value":"500+","label":"Clienti","prefix":"","suffix":"","bg":"ink"} /-->
    </div>
    <!-- /wp:column -->

    <!-- wp:column -->
    <div class="wp-block-column">
      <!-- wp:theme/stat {"value":"12","label":"Anni di esperienza","suffix":"","bg":"ink"} /-->
    </div>
    <!-- /wp:column -->

  </div>
  <!-- /wp:columns -->

</div>
<!-- /wp:group -->
```

---

## 7. Come creare un Template (block template)

I block template permettono di definire la struttura di pagine specifiche usando blocchi HTML.
Vivono in `/templates/*.html`.

> **Nota:** I Blade template (in `resources/views/`) hanno PRIORITÀ sui block template HTML.
> Se esiste `resources/views/front-page.blade.php`, quello viene usato, non `templates/front-page.html`.
> Usa i block template per pagine dove vuoi che il cliente possa modificare la struttura dall'editor.

### 7.1 Template slugs standard WordPress

| File | Quando viene usato |
|---|---|
| `templates/index.html` | Fallback generale |
| `templates/front-page.html` | Homepage |
| `templates/single.html` | Singolo post |
| `templates/page.html` | Singola pagina |
| `templates/archive.html` | Archivi (category, tag, CPT) |
| `templates/search.html` | Risultati ricerca |
| `templates/404.html` | Pagina non trovata |
| `templates/single-portfolio.html` | Singolo CPT `portfolio` |

### 7.2 Struttura di un block template

```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:group {"tagName":"main","layout":{"type":"constrained"}} -->
<main class="wp-block-group">

  <!-- wp:post-title {"level":1} /-->
  <!-- wp:post-featured-image /-->
  <!-- wp:post-content /-->

</main>
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

### 7.3 Template parts

I template parts (header, footer, sidebar) vivono in `/parts/*.html`:

```
parts/
  header.html
  footer.html
  sidebar.html
```

Esempio `parts/header.html`:
```html
<!-- wp:group {"tagName":"header","className":"site-header"} -->
<header class="wp-block-group site-header">
  <!-- wp:site-logo /-->
  <!-- wp:navigation /-->
</header>
<!-- /wp:group -->
```

### 7.4 Il cliente può modificare i template

Da **Aspetto → Editor → Template** il cliente può:
- Vedere e modificare i template
- Creare template per pagine specifiche
- Ripristinare il template originale

---

## 8. Block Style Variations

Le Style Variations aggiungono stili alternativi ai blocchi core (senza creare blocchi nuovi).
Appaiono nel pannello "Stili" del blocco nell'editor.

### Dove si registrano

In `resources/js/editor.js`, nel listener `DOMContentLoaded`:

```js
// Registrazione
registerBlockStyle('core/button', {
  name: 'mio-stile',
  label: __('Mio Stile', 'sage'),
})

// Rimozione di uno stile esistente (es. lo stile "fill" di default)
unregisterBlockStyle('core/button', 'fill')
```

### CSS in `editor.css` e `app.css`

La classe aggiunta da WordPress è `is-style-{name}`:

```css
/* In editor.css — per WYSIWYG nell'editor */
.editor-styles-wrapper .wp-block-button.is-style-mio-stile .wp-block-button__link {
  background: transparent;
  border: 2px solid currentColor;
}

/* In app.css — per il frontend */
.wp-block-button.is-style-mio-stile .wp-block-button__link {
  background: transparent;
  border: 2px solid currentColor;
}
```

### Style Variations registrate nel tema

| Blocco | Stile | Classe CSS |
|---|---|---|
| `core/button` | Outline | `.is-style-outline` |
| `core/button` | Accent (Blue) | `.is-style-accent` |
| `core/button` | Ghost | `.is-style-ghost` |
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

---

## 9. Block Variations

Le Block Variations sono preset di blocchi core con attributi preconfigurati.
Appaiono nel block inserter con nome e icona propri.

### Dove si registrano

In `resources/js/editor.js`, nel listener `DOMContentLoaded`:

```js
registerBlockVariation('core/group', {
  name: 'theme-mia-variazione',
  title: __('Mia Variazione', 'sage'),
  description: __('Descrizione per il cliente.', 'sage'),
  category: 'theme',
  icon: 'layout',                    // icona dashicon
  attributes: {
    backgroundColor: 'cream',
    align: 'wide',
    style: {
      spacing: {
        padding: { top: '4rem', bottom: '4rem', left: '2rem', right: '2rem' },
      },
    },
  },
  innerBlocks: [                     // opzionale: blocchi interni preconfigurati
    ['core/heading', { level: 2, placeholder: 'Titolo sezione' }],
    ['core/paragraph', { placeholder: 'Testo descrittivo…' }],
  ],
  scope: ['inserter'],               // dove appare: 'inserter', 'transform', 'block'
})
```

### Variations registrate nel tema

| Variazione | Blocco base | Descrizione |
|---|---|---|
| Hero Section | `core/cover` | Cover full-width 80vh con overlay ink |
| Content Card | `core/group` | Group con padding + bordo su sfondo cream |
| Sezione Scura | `core/group` | Group full-width sfondo ink, testo white |
| Colonne 60/40 | `core/columns` | Due colonne asimmetriche, align wide |
| 3 Colonne uguali | `core/columns` | Tre colonne 33.33% |

---

## 10. Blade Templates (frontend PHP)

### Struttura base di una view

```blade
{{-- resources/views/single.blade.php --}}
@extends('layouts.app')

@section('content')
  @while(have_posts())
    @php(the_post())
    <article @php(post_class('max-w-3xl mx-auto py-16 px-6'))>
      <h1 class="font-serif text-4xl font-light">{!! get_the_title() !!}</h1>
      <div class="mt-8 prose">
        @php(the_content())
      </div>
    </article>
  @endwhile
@endsection
```

### Includere un partial

```blade
@include('partials.card-post', ['post' => $post])
```

### View Composers — iniettare dati

I Composers vivono in `app/View/Composers/`. Iniettano dati in una view specifica:

```php
// app/View/Composers/FrontPage.php
namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class FrontPage extends Composer
{
    protected static $views = ['front-page'];

    public function with(): array
    {
        return [
            'featuredPosts' => $this->getFeaturedPosts(),
        ];
    }

    private function getFeaturedPosts(): array
    {
        return get_posts(['posts_per_page' => 3, 'post_status' => 'publish']);
    }
}
```

Nella view:
```blade
@foreach($featuredPosts as $post)
  @include('partials.card-post', compact('post'))
@endforeach
```

### Output sicuro in Blade

```blade
{{-- Testo puro: ESCAPATO automaticamente --}}
{{ get_the_title() }}

{{-- HTML fidato da WP (già escapato da WP): usa {!! !!} --}}
{!! get_the_content() !!}
{!! wp_nav_menu(['echo' => false]) !!}

{{-- MAI: {!! $_GET['input'] !!}  ← XSS --}}
```

---

## 11. Alpine.js — componenti reattivi

I componenti Alpine vivono in `resources/js/app.js`.

### Aggiungere un componente

```js
// In app.js, dentro il blocco Alpine.data(...)
Alpine.data('mioComponente', () => ({
  aperto: false,
  valore: '',

  toggle() {
    this.aperto = !this.aperto
  },

  async caricaDati() {
    const res = await fetch('/wp-json/theme/v1/search?q=' + this.valore)
    const data = await res.json()
    // ...
  },
}))
```

Nella view Blade:
```blade
<div x-data="mioComponente">
  <button @click="toggle" :aria-expanded="aperto">Apri</button>
  <div x-show="aperto" x-transition>
    Contenuto
  </div>
</div>
```

### Alpine Store (stato globale)

```js
Alpine.store('layout', {
  cartCount: 0,
  mobileMenuOpen: false,
})
```

Usato da qualsiasi componente:
```html
<span x-text="$store.layout.cartCount"></span>
```

---

## 12. Custom Post Types

Registrati in `app/post-types.php`. Tutti `show_in_rest: true`.

| CPT | Slug | Taxonomy | Meta keys |
|---|---|---|---|
| `portfolio` | `/portfolio/*` | `portfolio_category` | `_portfolio_client`, `_portfolio_year`, `_portfolio_services`, `_portfolio_url` |
| `team` | `/team/*` | `team_department` | `_team_role`, `_team_email`, `_team_linkedin` |
| `faq` | `/faq/*` | `faq_category` | — |

### Aggiungere un CPT

In `app/post-types.php`:
```php
register_post_type('progetto', [
    'labels'       => ['name' => 'Progetti', 'singular_name' => 'Progetto'],
    'public'       => true,
    'show_in_rest' => true,           // obbligatorio per Gutenberg
    'supports'     => ['title', 'editor', 'thumbnail', 'excerpt'],
    'has_archive'  => true,
    'rewrite'      => ['slug' => 'progetti'],
    'menu_icon'    => 'dashicons-portfolio',
]);
```

Poi aggiungi il template Blade `resources/views/single-progetto.blade.php`.

---

## 13. REST API endpoints custom

Definiti in `app/filters.php` e `app/ajax.php`.

| Metodo | Route | Descrizione |
|---|---|---|
| `POST` | `/wp-json/theme/v1/newsletter` | Iscrizione newsletter (action hook `theme_newsletter_subscribe`) |
| `GET` | `/wp-json/theme/v1/search` | Ricerca live (post + prodotti) |
| `POST` | `/wp-json/theme/v1/wishlist` | Toggle wishlist prodotto |

### Aggiungere un endpoint

```php
add_action('rest_api_init', function () {
    register_rest_route('theme/v1', '/mio-endpoint', [
        'methods'             => 'GET',
        'callback'            => 'App\\mio_callback',
        'permission_callback' => '__return_true',
        'args'                => [
            'id' => [
                'required'          => true,
                'type'              => 'integer',
                'sanitize_callback' => 'absint',
            ],
        ],
    ]);
});
```

---

## 14. Regole di codice

### PHP
- **Namespace:** `App\` in tutti i file in `app/`
- **Output:** sempre `esc_html()`, `esc_url()`, `esc_attr()`, `wp_kses_post()`
- **Input:** sempre `sanitize_text_field()`, `sanitize_email()`, `absint()`, ecc.
- **Query:** sempre `WP_Query` o `$wpdb->prepare()` — mai SQL raw
- **Strict types:** aggiungi `declare(strict_types=1);` nei nuovi file

### JavaScript
- **ES modules** — no jQuery nel frontend (solo dove WC lo richiede)
- **Alpine** — tutti i componenti reattivi in `app.js`
- **Import** — i pacchetti `@wordpress/*` sono external (window.wp.*), non importarli da npm

### CSS / Tailwind
- **Utility-first** — usa classi Tailwind, non stili inline
- **Design tokens** — aggiungi variabili in `@theme {}` di `app.css`, non hardcodare colori
- **Editor** — duplica gli stili importanti in `editor.css` per WYSIWYG accurato

### Accessibilità
- Ogni elemento interattivo: `aria-label` o label visibile
- Immagini decorative: `alt=""` + `aria-hidden="true"`
- Carousels: pattern ARIA completo (`role="region"`, `aria-roledescription="carousel"`)
- Modal/drawer: `x-trap.inert` (Alpine Focus plugin)

---

## 15. Customizer keys

Accessibili con `get_theme_mod('chiave')`. Definite in `app/customizer.php`.

| Key | Sezione | Default |
|---|---|---|
| `social_instagram` | theme_social | `''` |
| `social_facebook` | theme_social | `''` |
| `social_tiktok` | theme_social | `''` |
| `social_youtube` | theme_social | `''` |
| `cta_url` | theme_theme | `''` |
| `footer_tagline` | theme_theme | testo default |
| `newsletter_heading` | theme_theme | testo default |
| `announcement_bar_active` | theme_announcement | `false` |
| `announcement_bar_text` | theme_announcement | `''` |
| `announcement_bar_cta_text` | theme_announcement | `''` |
| `announcement_bar_cta_url` | theme_announcement | `''` |

---

## 16. File da NON toccare

| Path | Motivo |
|---|---|
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
[ ] Crea           blocks/nome-blocco/render.php   (output PHP + Tailwind)
[ ] Aggiungi       registerBlockType() in app/setup.php → array $blocks
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
