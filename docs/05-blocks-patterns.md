# 05 — Blocchi & Pattern

---

## Panoramica

Il tema estende Gutenberg in quattro modi:

| Funzionalità | File | Cosa fa |
|---|---|---|
| **Custom Blocks** | `blocks/*/` | Nuovi blocchi con controlli custom e render PHP |
| **Block Patterns** | `patterns/*/` | Layout preconfigurati inseribili con un click |
| **Style Variations** | `editor.js` + `editor.css` | Stili alternativi per blocchi core esistenti |
| **Block Variations** | `editor.js` | Preset di blocchi core con attributi preconfigurati |

---

## 1. Custom Blocks

### Blocchi registrati

| Slug | Titolo | Descrizione |
|------|--------|-------------|
| `theme/hero` | Hero – Sezione Principale | Hero full-screen con immagine, overlay, titolo, 2 CTA |
| `theme/testimonial` | Testimonianza | Card recensione con citazione, autore, stelle, immagine |
| `theme/stat` | Statistica | Numero grande con prefisso/suffisso, label, descrizione |
| `theme/icon-box` | Icon Box | Icona + titolo + testo + link, layout verticale/orizzontale |

I controlli di ogni blocco appaiono nel pannello laterale destro dell'editor (InspectorControls).

---

### Come creare un nuovo blocco — step by step

#### Step 1: Crea la cartella

```
blocks/nome-blocco/
├── block.json
└── render.php
```

#### Step 2: `block.json` — metadati e attributi

```json
{
  "$schema": "https://schemas.wp.org/trunk/block.json",
  "apiVersion": 3,
  "name": "theme/nome-blocco",
  "title": "Nome Blocco",
  "category": "theme",
  "description": "Descrizione per il cliente nell'inserter.",
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
    "titolo": { "type": "string", "default": "Titolo" },
    "testo":  { "type": "string", "default": "" },
    "layout": { "type": "string", "default": "vertical", "enum": ["vertical", "horizontal"] },
    "imageId":  { "type": "integer", "default": 0 },
    "imageUrl": { "type": "string",  "default": "" },
    "attivo":   { "type": "boolean", "default": true }
  }
}
```

**Tipi attributo:** `string`, `integer`, `boolean`, `number`, `array`, `object`

**`supports` utili:**
- `"anchor": true` — aggiunge campo ID per link interni (`#sezione`)
- `"align": ["wide","full"]` — attiva i pulsanti larghezza nell'editor
- `"html": false` — disabilita modifica HTML diretta (sempre consigliato per SSR)
- `"color": false` — non mostrare i controlli colore default WP (usi la tua palette)

#### Step 3: `render.php` — output frontend

```php
<?php
/**
 * Block: theme/nome-blocco
 * @var array    $attributes
 * @var string   $content
 * @var WP_Block $block
 */

$titolo   = esc_html($attributes['titolo'] ?? '');
$testo    = wp_kses_post($attributes['testo'] ?? '');
$layout   = $attributes['layout'] ?? 'vertical';
$image_id = (int) ($attributes['imageId'] ?? 0);
$attivo   = (bool) ($attributes['attivo'] ?? true);

if (! $attivo) return;

$flex = $layout === 'horizontal' ? 'flex-row gap-8' : 'flex-col gap-4';
?>
<div <?= get_block_wrapper_attributes(['class' => 'theme-nome-blocco']) ?>>
  <div class="flex <?= esc_attr($flex) ?> items-start">

    <?php if ($image_id) : ?>
      <?= wp_get_attachment_image($image_id, 'medium', false, [
          'class'   => 'w-16 h-16 object-cover shrink-0',
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

**Regole:**
- Usa sempre `get_block_wrapper_attributes()` sul tag wrapper esterno
- `esc_html()` per testo, `wp_kses_post()` per HTML fidato, `esc_url()` per URL
- `wp_get_attachment_image()` per immagini (gestisce srcset e lazy loading automaticamente)

#### Step 4: Controlli editor in `editor.js`

```js
registerBlockType('theme/nome-blocco', {
  edit({ attributes, setAttributes }) {
    const { titolo, testo, layout, imageId, imageUrl, attivo } = attributes

    return el(Fragment, null,
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
          el(MediaPanel, {           // helper già definito nel file
            imageId, imageUrl,
            onSelect: (m) => setAttributes({ imageId: m.id, imageUrl: m.url }),
            onRemove: () => setAttributes({ imageId: 0, imageUrl: '' }),
          }),
        ),

        el(PanelBody, { title: __('Layout', 'sage'), initialOpen: false },
          el(SelectControl, {
            label: __('Orientamento', 'sage'),
            value: layout ?? 'vertical',
            options: [
              { label: __('Verticale', 'sage'),    value: 'vertical' },
              { label: __('Orizzontale', 'sage'), value: 'horizontal' },
            ],
            onChange: (val) => setAttributes({ layout: val }),
          }),
          el(ToggleControl, {
            label: __('Visibile', 'sage'),
            checked: attivo ?? true,
            onChange: (val) => setAttributes({ attivo: val }),
          }),
        ),
      ),

      // Anteprima nel editor via REST
      el('div', useBlockProps(),
        el(ServerSideRender, { block: 'theme/nome-blocco', attributes })
      ),
    )
  },
  save: () => null,   // sempre null per blocchi server-side rendered
})
```

**Componenti `@wordpress/components` disponibili:**
| Componente | Usa per |
|---|---|
| `TextControl` | Input testo singola riga |
| `TextareaControl` | Testo multiriga |
| `SelectControl` | Dropdown/select |
| `RangeControl` | Slider numerico (min/max/step) |
| `ToggleControl` | Switch on/off |
| `CheckboxControl` | Checkbox |
| `MediaPanel` | Upload immagine (helper già nel file) |

#### Step 5: Registra in `app/setup.php`

```php
add_action('init', function () {
    $blocks = ['hero', 'testimonial', 'stat', 'icon-box', 'nome-blocco']; // ← aggiungi qui
    foreach ($blocks as $name) {
        $dir = get_template_directory() . "/blocks/{$name}";
        if (is_dir($dir)) register_block_type($dir);
    }
});
```

#### Step 6 (opzionale): CSS editor WYSIWYG

In `resources/css/editor.css`:
```css
.editor-styles-wrapper .wp-block-theme-nome-blocco {
  border: 1px solid #e0e0e0;
  padding: 1.5rem;
}
```

#### Checklist rapida

```
[ ] Crea  blocks/nome-blocco/block.json
[ ] Crea  blocks/nome-blocco/render.php
[ ] Aggiungi 'nome-blocco' all'array in setup.php
[ ] Aggiungi registerBlockType() in editor.js
[ ] (opt) CSS in editor.css per WYSIWYG
[ ] npm run build
[ ] Testa nell'editor WP
```

---

## 2. Block Patterns

I pattern sono layout precostruiti inseribili dall'inserter con un click.

### Come funziona la registrazione

**Automatica.** WordPress scansiona `/patterns/*.php` e registra ogni file trovato basandosi sull'header PHP. Non serve nessun `register_block_pattern()` manuale.

Stesso meccanismo di TwentyTwentyFive. L'unica differenza: usiamo slug di categoria custom invece di quelli built-in WP.

### Creare un pattern — step by step

#### Step 1: Crea il file

`patterns/nome-pattern.php`

```php
<?php
/**
 * Title: Nome Sezione – Descrizione
 * Slug: theme/nome-sezione
 * Categories: theme-sections
 * Keywords: parola, chiave, sezione
 * Description: Descrizione per il cliente.
 * Viewport Width: 1440
 */
?>
<!-- wp:group {"align":"full","backgroundColor":"cream","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-cream-background-color has-background">

  <!-- wp:heading {"textAlign":"center","level":2,"fontFamily":"serif"} -->
  <h2 class="wp-block-heading has-text-align-center has-serif-font-family">Titolo Sezione</h2>
  <!-- /wp:heading -->

  <!-- wp:paragraph {"align":"center","textColor":"muted"} -->
  <p class="has-text-align-center has-muted-color has-text-color">Sottotitolo descrittivo.</p>
  <!-- /wp:paragraph -->

</div>
<!-- /wp:group -->
```

#### Step 2: Ottieni il markup dall'editor WP

Il modo più rapido:
1. Costruisci il layout nell'editor di WordPress
2. **Tre punti (⋮) → Editor codice** (o `Ctrl/Cmd + Shift + Alt + M`)
3. Copia tutto il markup
4. Incollalo nel file `.php` del pattern, sostituendo il contenuto segnaposto

#### Step 3: Il pattern appare automaticamente nell'inserter

Nessun altro step. Ricarica l'editor WP e il pattern appare nella categoria indicata.

### Categorie disponibili

| Slug | Quando usarla |
|------|---------------|
| `theme-sections` | Sezioni full-width: hero, CTA, intro, media-text |
| `theme-cards` | Card: prodotti, team, testimonial |
| `theme-carousel` | Carousel, slider, scroll orizzontale |

Per usare le categorie built-in di WordPress (nessuna registrazione necessaria):

| Slug WP | Uso tipico |
|---------|-----------|
| `banner` | Hero, cover, banner |
| `header` | Header, navigazione |
| `footer` | Footer |
| `text` | Sezioni testo/tipografia |
| `query` | Loop post, archivi |
| `featured` | Featured content |

### Usare blocchi custom del tema in un pattern

```php
<?php
/**
 * Title: Sezione Statistiche
 * Slug: theme/stats-section
 * Categories: theme-sections
 */
?>
<!-- wp:columns {"align":"wide"} -->
<div class="wp-block-columns alignwide">

  <!-- wp:column -->
  <div class="wp-block-column">
    <!-- wp:theme/stat {"value":"500+","label":"Clienti","bg":"ink"} /-->
  </div>
  <!-- /wp:column -->

  <!-- wp:column -->
  <div class="wp-block-column">
    <!-- wp:theme/stat {"value":"12","label":"Anni","bg":"ink"} /-->
  </div>
  <!-- /wp:column -->

</div>
<!-- /wp:columns -->
```

### Pattern disponibili nel tema

| File | Slug | Categoria |
|------|------|-----------|
| `hero.php` | `theme/hero` | sections |
| `page-hero.php` | `theme/page-hero` | sections |
| `shop-hero.php` | `theme/shop-hero` | sections |
| `cta-banner.php` | `theme/cta-banner` | sections |
| `stats.php` | `theme/stats` | sections |
| `media-text.php` | `theme/media-text` | sections |
| `media-text-right.php` | `theme/media-text-right` | sections |
| `intro-two-cols.php` | `theme/intro-two-cols` | sections |
| `services-grid.php` | `theme/services-grid` | sections |
| `usp-band.php` | `theme/usp-band` | sections |
| `brand-logos.php` | `theme/brand-logos` | sections |
| `logos-grid.php` | `theme/logos-grid` | sections |
| `newsletter-cta.php` | `theme/newsletter-cta` | sections |
| `contact-section.php` | `theme/contact-section` | sections |
| `full-width-quote.php` | `theme/full-width-quote` | sections |
| `image-text-list.php` | `theme/image-text-list` | sections |
| `text-with-aside.php` | `theme/text-with-aside` | sections |
| `testimonials.php` | `theme/testimonials` | cards |
| `team-member-card.php` | `theme/team-member-card` | cards |
| `portfolio-grid.php` | `theme/portfolio-grid` | cards |
| `product-spotlight.php` | `theme/product-spotlight` | cards |
| `product-categories.php` | `theme/product-categories` | carousel |

---

## 3. Block Style Variations

Aggiungono stili alternativi ai blocchi core esistenti. Appaiono nel pannello "Stili" del blocco.

### Come si registrano

In `resources/js/editor.js`, dentro `window.addEventListener('DOMContentLoaded', ...)`:

```js
registerBlockStyle('core/button', {
  name: 'mio-stile',
  label: __('Mio Stile', 'sage'),
})
```

### CSS necessario

La classe aggiunta è `.is-style-{name}`. Va messa sia in `editor.css` (WYSIWYG) che in `app.css` (frontend):

```css
/* editor.css */
.editor-styles-wrapper .wp-block-button.is-style-mio-stile .wp-block-button__link {
  background: transparent;
  border: 2px solid currentColor;
}

/* app.css */
.wp-block-button.is-style-mio-stile .wp-block-button__link {
  background: transparent;
  border: 2px solid currentColor;
}
```

### Style Variations registrate nel tema

| Blocco | Stile | Classe CSS | Descrizione |
|--------|-------|-----------|-------------|
| `core/button` | Outline | `.is-style-outline` | Trasparente con bordo |
| `core/button` | Accent | `.is-style-accent` | Sfondo blu accent |
| `core/button` | Ghost | `.is-style-ghost` | Solo testo con underline |
| `core/heading` | Display | `.is-style-display` | Font grande fluid, weight 200 |
| `core/heading` | Overline | `.is-style-overline` | Piccolo, maiuscolo, blu, spaziato |
| `core/separator` | Spesso | `.is-style-thick` | 3px nero |
| `core/separator` | Accent | `.is-style-accent` | 2px blu |
| `core/quote` | Minimal | `.is-style-minimal` | No bordo, grigio |
| `core/quote` | Grande | `.is-style-large` | Font grande centrato |
| `core/image` | Arrotondato | `.is-style-rounded` | Circolare |
| `core/image` | Con cornice | `.is-style-framed` | Bordo + padding |
| `core/group` | Card | `.is-style-card` | Sfondo cream + bordo + padding |
| `core/group` | Bordered | `.is-style-bordered` | Solo bordo + padding |

---

## 4. Block Variations

Preset di blocchi core con attributi preconfigurati. Appaiono nell'inserter con nome e icona propri, sotto la categoria "Theme".

### Come si registrano

```js
registerBlockVariation('core/group', {
  name: 'theme-mia-variazione',
  title: __('Mia Variazione', 'sage'),
  description: __('Descrizione per il cliente.', 'sage'),
  category: 'theme',
  icon: 'layout',
  attributes: {
    backgroundColor: 'cream',
    align: 'wide',
    style: {
      spacing: {
        padding: { top: '4rem', bottom: '4rem', left: '2rem', right: '2rem' },
      },
    },
  },
  innerBlocks: [
    ['core/heading', { level: 2, placeholder: 'Titolo sezione' }],
    ['core/paragraph', { placeholder: 'Testo…' }],
  ],
  scope: ['inserter'],
})
```

### Variations registrate nel tema

| Nome | Blocco base | Descrizione |
|------|-------------|-------------|
| Hero Section | `core/cover` | Cover full-width 80vh, overlay ink, position center |
| Content Card | `core/group` | Group con padding + bordo su sfondo cream |
| Sezione Scura | `core/group` | Full-width, sfondo ink, testo white, padding 6rem |
| Colonne 60/40 | `core/columns` | Due colonne asimmetriche align wide |
| 3 Colonne uguali | `core/columns` | Tre colonne 33.33% align wide |

---

## 5. Rimuovere pattern di plugin indesiderati

```php
// In app/setup.php o filters.php
add_action('init', function () {
    unregister_block_pattern('woocommerce/product-query');
    unregister_block_pattern('jetpack/contact-form');
}, 20);
```

---

## 6. Usare i token design nei pattern

### Colori (in attributo JSON)
```json
{ "textColor": "accent", "backgroundColor": "ink" }
```

### Spacing (in attributo JSON)
```json
{ "style": { "spacing": { "padding": { "top": "var:preset|spacing|7" } } } }
```
Il valore `7` = 3rem (48px) dalla spacing scale.

### Font size (in attributo JSON)
```json
{ "fontSize": "hero", "fontFamily": "serif" }
```
