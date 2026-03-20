# 03 — Design System

Il design system è definito in `theme.json` (sorgente nella root del tema).
Viene compilato da Vite in `public/build/assets/theme.json` e sincronizzato con:
- **Gutenberg Global Styles** (colori, font, spacing nel pannello editor)
- **Tailwind CSS v4** (via `@theme {}` in `resources/css/app.css`)

Il cliente può modificare colori, font e spacing da **Aspetto → Editor → icona paintbrush (Global Styles)** senza toccare codice. Le modifiche vengono salvate nel DB e sovrascrivono i valori di `theme.json`.

---

## Colori

### Palette attuale (`theme.json → settings.color.palette`)

| Slug | Hex | Tailwind | Uso |
|------|-----|----------|-----|
| `ink` | `#0a0a0a` | `text-ink` / `bg-ink` | Testo principale, sfondi scuri |
| `ink-light` | `#1a1a1a` | `bg-ink-light` | Card scure, overlay |
| `white` | `#ffffff` | `text-white` / `bg-white` | — |
| `surface` | `#ffffff` | `bg-surface` | Sfondo sezioni chiare |
| `surface-alt` | `#f5f5f5` | `bg-surface-alt` | Sfondo alternativo |
| `cream` | `#f5f5f5` | `bg-cream` | Sezioni neutre |
| `muted` | `#6b6b6b` | `text-muted` | Testo secondario |
| `border` | `#e0e0e0` | `border-border` | Separatori, bordi |
| `accent` | `#0074C7` | `text-accent` / `bg-accent` | CTA, link hover, highlights |
| `accent-light` | `#eff6ff` | `bg-accent-light` | Badge, sfondi hover |
| `gold` | `#0074C7` | `text-gold` | Icone trust badge, stelle |

### Usare i colori

**In Tailwind (Blade/PHP):**
```html
<div class="bg-ink text-white">Sezione scura</div>
<a class="text-accent hover:text-ink">Link</a>
<div class="border border-border">Card</div>
```

**In Gutenberg (blocchi):**
```html
class="has-ink-color has-text-color"
class="has-accent-background-color has-background"
```

**Come attributo JSON blocco:**
```json
{ "textColor": "ink", "backgroundColor": "cream" }
```

**Come CSS custom property:**
```css
color: var(--wp--preset--color--accent);
background: var(--wp--preset--color--ink);
```

### Aggiungere un colore

In `theme.json → settings.color.palette`:
```json
{ "slug": "brand-red", "color": "#e4002b", "name": "Brand Red" }
```

In `resources/css/app.css` dentro `@theme {}`:
```css
--color-brand-red: #e4002b;
```

Dopo `npm run build` è disponibile come `bg-brand-red` in Tailwind e nel color picker dell'editor.

### Gradienti

| Slug | Descrizione |
|------|-------------|
| `ink-overlay` | `linear-gradient(180deg, transparent → rgba(10,10,10,0.65))` — overlay hero |
| `ink-overlay-strong` | Versione più scura — hero con testo bianco garantito |

---

## Tipografia

### Font families

| Slug | Font | Uso |
|------|------|-----|
| `sans` | Poppins | Corpo, UI, label, button, caption |
| `serif` | Inter | Titoli H1–H3, display, heading hero |

Entrambi caricati da Google Fonts in modo asincrono (non-render-blocking) via `app/setup.php`.

**In Tailwind:** `font-sans`, `font-serif`
**In Gutenberg:** selezionabili dal pannello Tipografia del blocco
**CSS:** `var(--wp--preset--font-family--sans)`, `var(--wp--preset--font-family--serif)`

### Font sizes

| Slug | Valore | px approx | Uso |
|------|--------|-----------|-----|
| `xs` | `0.75rem` | 12px | Caption, meta, label piccole |
| `sm` | `0.875rem` | 14px | Testo secondario |
| `base` | `1rem` | 16px | Corpo testo |
| `lg` | `1.125rem` | 18px | Lead paragraph |
| `xl` | `1.25rem` | 20px | — |
| `2xl` | `1.5rem` | 24px | H4 |
| `3xl` | `1.875rem` | 30px | H3 |
| `4xl` | `2.25rem` | 36px | H2 |
| `5xl` | `3rem` | 48px | H1 |
| `hero` | `clamp(2.5rem, 5vw, 4.5rem)` | ~40–72px | Hero display (fluid) |

**In Tailwind:** `text-xs`, `text-sm`, `text-base`, `text-hero`, ecc.
**In Gutenberg:** selezionabili dal pannello Tipografia

### Stili heading globali (da `styles.elements`)

| Elemento | Font | Weight | Line height |
|----------|------|--------|-------------|
| H1 | serif (Inter) | 300 | 1.05 |
| H2 | serif (Inter) | 300 | 1.1 |
| H3 | serif (Inter) | 400 | 1.2 |
| H4 | sans (Poppins) | 500 | 1.3 |

---

## Layout

| Setting | Valore | Uso |
|---------|--------|-----|
| `contentSize` | `1200px` | Larghezza testo/contenuto standard |
| `wideSize` | `1440px` | Blocchi con `align: wide` |
| `align: full` | `100vw` | Blocchi a tutta larghezza |

**Nota:** `align-wide` è abilitato (`add_theme_support('align-wide')`), quindi i pulsanti ampio/pieno appaiono nell'editor su tutti i blocchi che lo supportano.

---

## Spacing scale

Definita in `theme.json → settings.spacing.spacingSizes`. Disponibile nei controlli padding/margin dell'editor.

| Slug | Valore | px approx |
|------|--------|-----------|
| `1` | `0.25rem` | 4px |
| `2` | `0.5rem` | 8px |
| `3` | `0.75rem` | 12px |
| `4` | `1rem` | 16px |
| `5` | `1.5rem` | 24px |
| `6` | `2rem` | 32px |
| `7` | `3rem` | 48px |
| `8` | `4rem` | 64px |
| `9` | `6rem` | 96px |
| `10` | `8rem` | 128px |
| `11` | `12rem` | 192px |

**CSS:** `var(--wp--preset--spacing--7)` (48px)
**In blocco JSON:** `"padding": { "top": "var:preset|spacing|7" }`

---

## Stili blocchi core (`styles.blocks`)

`theme.json` include stili default per i blocchi core, così l'editor rispecchia fedelmente il frontend:

| Blocco | Cosa viene stilizzato |
|---|---|
| `core/button` | Font, peso, spacing, bordo radius 0, colore ink |
| `core/heading` | Font family serif |
| `core/paragraph` | Font sans, line-height 1.7 |
| `core/quote` | Bordo sinistro accent, font corsivo Inter |
| `core/pullquote` | Bordo top/bottom ink, font Grande |
| `core/separator` | Colore border `#e0e0e0` |
| `core/image` | border-radius 0 |
| `core/cover` | Padding verticale da spacing scale |
| `core/group` | Padding default da spacing scale |
| `core/columns` | Gap da spacing scale |
| `core/code` | Sfondo cream, bordo, font-size sm |

---

## Global Styles — controllo dal cliente

Il pannello **Global Styles** (Aspetto → Editor → paintbrush) permette al cliente di:

- Sovrascrivere colori della palette
- Cambiare i font e le dimensioni
- Modificare spacing globale
- Cambiare stili di elementi (heading, link, button)

Le modifiche vengono salvate nel DB come `wp_global_styles` — non toccano `theme.json`.
Per **resettare al default del tema**: Global Styles → menu → "Ripristina predefiniti del tema".

---

## Personalizzare per un nuovo sito

1. **Colori:** aggiorna `settings.color.palette` in `theme.json` + variabili `@theme {}` in `app.css`
2. **Font:** cambia `fontFamilies` in `theme.json` + aggiorna l'URL Google Fonts in `app/setup.php`
3. **Dimensioni layout:** modifica `contentSize` / `wideSize`
4. **Stili heading:** aggiorna `styles.elements` in `theme.json`
5. **Rebuild:** `npm run build`

---

## Editor CSS (`resources/css/editor.css`)

Stili iniettati nell'editor Gutenberg per WYSIWYG accurato. Contiene:
- Stili base (font, colori, heading)
- CSS per tutte le **Block Style Variations** (`is-style-outline`, `is-style-display`, ecc.)
- Stili blocchi core (`wp-block-button__link`, `wp-block-quote`, ecc.)

Vedere [05 — Blocchi & Pattern](./05-blocks-patterns.md) per la lista completa delle Style Variations.
