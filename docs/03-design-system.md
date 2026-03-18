# 03 — Design System

Il design system è definito in `theme.json` (sorgente) e sincronizzato con Tailwind CSS durante la build. WordPress legge la versione compilata in `public/build/assets/theme.json`.

---

## Colori

### Palette (`theme.json` → `settings.color.palette`)

| Slug | Hex | Nome | Uso tipico |
|------|-----|------|-----------|
| `black` | `#111111` | Nero | Testo principale (`text-ink`) |
| `dark` | `#1a1a2e` | Dark Navy | Sfondi header/footer |
| `dark-900` | `#1e293b` | Dark 900 | Card overlay |
| `dark-50` | `#f0f6ff` | Blu 50 | Sfondi sezioni chiare |
| `primary` | `#3E80C4` | Blu Primary | CTA, link hover, accenti |
| `primary-dark` | `#2d69a8` | Blu Scuro | Hover button |
| `primary-light` | `#bfdbfe` | Blu Chiaro | Badge, highlight |
| `white` | `#ffffff` | Bianco | - |
| `off-white` | `#f8fafc` | Off White | Body background |
| `gray-100` | `#f1f5f9` | Grigio 100 | Border light |
| `gray-200` | `#e2e8f0` | Grigio 200 | Separatori |
| `gray-500` | `#64748b` | Grigio 500 | Testo secondario |
| `gray-600` | `#475569` | Grigio 600 | Testo muted |

### Usare i colori in Tailwind

I token WP vengono resi disponibili come CSS custom properties:

```css
/* Generato automaticamente da WordPress */
--wp--preset--color--primary: #3E80C4;
--wp--preset--color--dark: #1a1a2e;
```

In Tailwind (via `theme.json` sync):
```html
<div class="bg-primary text-white">...</div>
<div class="text-gray-500">...</div>
```

Nei blocchi Gutenberg si usa la classe utility WP:
```html
<p class="has-primary-color has-text-color">...</p>
```

### Gradienti

| Slug | Definizione |
|------|-------------|
| `primary-to-dark` | `linear-gradient(135deg, #3E80C4 0%, #1a1a2e 100%)` |
| `dark-overlay` | `linear-gradient(180deg, rgba(26,26,46,0.5) 0%, rgba(26,26,46,0.7) 100%)` |

---

## Tipografia

### Font families

| Slug | Font | Uso |
|------|------|-----|
| `sans` | Poppins | Corpo, UI, label, button |
| `serif` | Cormorant Garamond | Titoli hero, display |

**Caricamento font:** Google Fonts via `@import` in `resources/css/app.css`. Il preconnect hint è aggiunto in `app/setup.php` via `wp_head`.

### Font sizes

| Slug | Valore | Note |
|------|--------|-------|
| `sm` | `0.875rem` (14px) | Caption, meta |
| `base` | `1rem` (16px) | Corpo |
| `lg` | `1.125rem` (18px) | Lead |
| `xl` | `1.25rem` (20px) | - |
| `2xl` | `1.5rem` (24px) | H4 |
| `3xl` | `1.875rem` (30px) | H3 |
| `4xl` | `2.25rem` (36px) | H2 |
| `5xl` | `3rem` (48px) | H1 |
| `hero` | `clamp(2.5rem, 5vw, 4.5rem)` | Hero display (fluid) |

**In Gutenberg:** selezionabili dal pannello tipografia.

**In Blade/Tailwind:**
```html
<h1 class="text-5xl font-serif font-light">Titolo</h1>
<p class="text-hero font-serif">Hero display</p>
```

**CSS custom property:**
```css
font-size: var(--wp--preset--font-size--hero);
```

---

## Layout

| Setting | Valore |
|---------|--------|
| `contentSize` | `1200px` — larghezza contenuto standard |
| `wideSize` | `1440px` — larghezza wide/full |

In Tailwind il container principale usa `max-w-360` (1440px) con padding laterale `px-6 lg:px-10`.

---

## Spacing

Unità disponibili per i blocchi: `px`, `%`, `em`, `rem`, `vw`, `vh`.

Padding e margini sono abilitati via `"padding": true` in `settings.spacing`.

---

## Stili elementi globali (`styles.elements`)

Definiti in `theme.json`, si applicano automaticamente a tutti i blocchi:

```json
"elements": {
  "h1": { "fontWeight": "700", "lineHeight": "1.1" },
  "h2": { "fontWeight": "700", "lineHeight": "1.2" },
  "h3": { "fontWeight": "600", "lineHeight": "1.3" },
  "link": {
    "color": "#111111",
    ":hover": { "color": "#3E80C4" }
  },
  "button": {
    "backgroundColor": "#3E80C4",
    "color": "#ffffff",
    "borderRadius": "4px",
    "padding": "0.75rem 1.5rem"
  }
}
```

---

## Personalizzare il design system per un nuovo sito

1. **Colori:** aggiorna `settings.color.palette` in `theme.json`
2. **Font:** cambia `fontFamilies` in `theme.json` + aggiorna l'`@import` in `app.css`
3. **Dimensioni layout:** modifica `contentSize` / `wideSize`
4. **Rebuild:** `npm run build`

I token Tailwind vengono rigenerati automaticamente dal plugin `wordpressThemeJson`.

---

## Editor CSS (`resources/css/editor.css`)

Stili aggiuntivi iniettati nell'editor Gutenberg. Qui puoi:
- Stilizzare blocchi core nel contesto editor
- Aggiungere stili `.wp-block-*` che rispecchiano il frontend
- Definire classi CSS custom per blocchi avanzati

Viene caricato via `block_editor_settings_all` filter in `app/setup.php`.
