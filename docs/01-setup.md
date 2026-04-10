# 01 — Setup & Sviluppo

## Installazione

```bash
# 1. Copia il tema in wp-content/themes/sage-theme
# 2. Installa dipendenze PHP
composer install

# 3. Installa dipendenze Node
npm install

# 4. Attiva il tema da WP Admin → Aspetto → Temi
```

---

## Variabili d'ambiente

`vite.config.js` usa `APP_URL` come base URL del proxy. Impostalo prima di `npm run dev`:

**Opzione A — file `.env` nella root del tema (consigliato)**
```ini
APP_URL=https://miosito.test
```

**Opzione B — export shell**
```bash
export APP_URL=https://miosito.test
npm run dev
```

Il fallback di default è `http://example.test`.

---

## Comandi di sviluppo

| Comando | Descrizione |
|---------|-------------|
| `npm run dev` | Vite dev server con HMR (hot reload CSS + JS) |
| `npm run build` | Build produzione → `public/build/` |
| `npm run lint` | Controlla JS con Biome |
| `npm run fix-all` | Auto-fix Biome |
| `composer install` | Installa dipendenze PHP |

**Quando fare `npm run build`:**
- Dopo aver modificato `editor.js` o `editor.css` (cambiano blocchi/stili nell'editor)
- Dopo aver modificato `theme.json` (cambia il design system)
- Prima di andare in produzione

---

## Struttura directory

```
sage-theme/
│
├── app/                          # PHP backend (namespace App\)
│   ├── setup.php                 # theme supports, menu, font, registrazione blocchi
│   ├── filters.php               # filtri WP, REST API, performance, WC
│   ├── ajax.php                  # handler AJAX: search, form contatti, wishlist
│   ├── customizer.php            # pannello Customizer (social, CTA, annuncio)
│   ├── post-types.php            # CPT: portfolio, team, faq
│   ├── Providers/
│   │   └── ThemeServiceProvider.php   # boot Acorn
│   └── View/Composers/               # View Composers (dati iniettati in Blade)
│
├── blocks/                       # Custom Gutenberg blocks
│   ├── hero/                     # ── block.json + render.php per blocco
│   ├── testimonial/
│   ├── stat/
│   └── icon-box/
│
├── patterns/                     # Block patterns (auto-registrati da WP)
│   └── *.php                     # ogni file = un pattern
│
├── resources/
│   ├── css/
│   │   ├── app.css               # Tailwind v4 + @theme design tokens
│   │   └── editor.css            # stili Gutenberg (WYSIWYG + Style Variations)
│   ├── js/
│   │   ├── app.js                # Alpine.js boot + GSAP + Swiper
│   │   ├── editor.js             # blocchi React + Style Variations + Block Variations
│   │   └── modules/              # moduli JS
│   │       ├── carousel.js
│   │       ├── animations.js
│   │       ├── scroll-effects.js
│   │       ├── magnetic-hover.js
│   └── views/                    # Blade templates
│       ├── layouts/
│       │   └── app.blade.php     # layout principale
│       ├── sections/             # header, footer, hero, announcement…
│       ├── partials/             # componenti riutilizzabili
│       └── *.blade.php           # index, single, archive, front-page…
│
├── woocommerce/                  # override PHP template WooCommerce
├── public/build/                 # output Vite — NON modificare
├── theme.json                    # design tokens (sorgente)
├── functions.php                 # entry point PHP — NON modificare
├── vite.config.js
├── package.json
└── composer.json
```

---

## Output build

```
public/build/
├── assets/
│   ├── app-[hash].css            # Tailwind compilato
│   ├── app-[hash].js             # Alpine boot
│   ├── editor-[hash].css         # stili editor
│   ├── editor-[hash].js          # blocchi React + Variations
│   ├── vendor-alpine-[hash].js   # Alpine (chunk separato → cache)
│   ├── vendor-gsap-[hash].js     # GSAP
│   ├── vendor-swiper-[hash].js   # Swiper
│   └── theme.json                # design system per WP
└── manifest.json                 # mappa asset per Vite helper
```

I chunk vendor sono separati per ottimizzare il cache busting — se aggiorni solo il codice del tema, i vendor rimangono cachati nel browser.

---

## Integrazione theme.json + Tailwind

Il plugin `wordpressThemeJson` di `@roots/vite-plugin` sincronizza i token di `theme.json` con Tailwind durante la build.

Il `theme.json` nella root è il **sorgente**.
Quello in `public/build/assets/theme.json` è quello letto da WordPress.

**Non modificare mai** `public/build/assets/theme.json` direttamente — viene sovrascritto ad ogni build.

Per disabilitare la sincronizzazione di categorie specifiche in `vite.config.js`:
```js
wordpressThemeJson({
  disableTailwindColors:       false,
  disableTailwindFonts:        false,
  disableTailwindFontSizes:    false,
  disableTailwindBorderRadius: false,
})
```

---

## File da non modificare mai

| Path | Motivo |
|---|---|
| `vendor/` | Gestito da Composer |
| `node_modules/` | Gestito da npm |
| `public/build/` | Generato da Vite |
| `functions.php` | Solo boot Acorn — nessuna logica qui |
| `composer.lock` | Aggiorna solo con `composer update` intenzionale |
| `package-lock.json` | Aggiorna solo con `npm install` intenzionale |
