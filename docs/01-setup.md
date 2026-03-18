# 01 — Setup & Development

## Installazione

```bash
# Clone / copia il tema in wp-content/themes/sage-theme
composer install          # installa Acorn + dipendenze PHP
npm install               # installa Tailwind, Vite, Alpine, GSAP, Swiper…
```

Attiva il tema da **WP Admin → Aspetto → Temi**.

---

## Variabili d'ambiente

`vite.config.js` usa `process.env.APP_URL` come base URL del proxy. Puoi impostarlo in due modi:

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

## Comandi npm

| Comando | Descrizione |
|---------|-------------|
| `npm run dev` | Avvia Vite dev server con HMR |
| `npm run build` | Build di produzione in `public/build/` |
| `npm run translate` | Genera tutti i file di traduzione |
| `npm run translate:pot` | Crea/aggiorna il file `.pot` |
| `npm run translate:update` | Aggiorna i file `.po` |
| `npm run translate:compile` | Compila `.po` → `.mo` e `.json` |

---

## Struttura output build

```
public/build/
├── assets/
│   ├── app-[hash].css        # Stili Tailwind compilati
│   ├── app-[hash].js         # Bundle JS principale (Alpine boot)
│   ├── editor-[hash].css     # Stili editor blocchi
│   ├── editor-[hash].js      # JS editor blocchi
│   ├── vendor-alpine-[hash].js
│   ├── vendor-gsap-[hash].js
│   ├── vendor-loco-[hash].js
│   ├── vendor-swiper-[hash].js
│   └── theme.json            # theme.json generato con token Tailwind
└── manifest.json             # Mappa asset per Vite helper
```

I chunk vendor sono separati per ottimizzare il cache busting.

---

## Alias Vite

Definiti in `vite.config.js`:

```js
resolve: {
  alias: {
    '~':        '/resources/js',
    '@scripts': '/resources/js',
    '@styles':  '/resources/css',
    '@fonts':   '/resources/fonts',
    '@images':  '/resources/images',
  }
}
```

Uso in JS/CSS:
```js
import MyModule from '@scripts/modules/my-module'
```
```css
@import '@styles/partials/_buttons.css';
```

---

## Integrazione theme.json + Tailwind

Il plugin `wordpressThemeJson` di `@roots/vite-plugin` sincronizza i token di Tailwind con il theme.json durante la build. Il `theme.json` nella root è il **sorgente**, quello in `public/build/assets/theme.json` è quello caricato da WordPress.

Per disabilitare la sincronizzazione di categorie specifiche:

```js
wordpressThemeJson({
  disableTailwindColors:       false,  // true = non esporta colori Tailwind
  disableTailwindFonts:        false,
  disableTailwindFontSizes:    false,
  disableTailwindBorderRadius: false,
})
```

---

## Struttura directory tema

```
sage-theme/
├── app/                    # PHP: hooks, filtri, provider
│   ├── Providers/
│   │   └── ThemeServiceProvider.php
│   ├── setup.php           # after_setup_theme, sidebar, menus
│   ├── filters.php         # WooCommerce, body_class, REST API
│   └── customizer.php      # Customizer settings
├── resources/
│   ├── css/
│   │   ├── app.css         # Entry point CSS (Tailwind @import)
│   │   └── editor.css      # Stili editor Gutenberg
│   ├── js/
│   │   ├── app.js          # Entry point JS (Alpine boot)
│   │   ├── editor.js       # JS editor blocchi
│   │   └── modules/        # Moduli JS (GSAP, Swiper, ecc.)
│   ├── fonts/              # Font locali (se non da Google)
│   ├── images/             # Immagini statiche del tema
│   └── views/              # Template Blade
│       ├── layouts/
│       ├── sections/       # Header, footer, hero, CTA…
│       ├── partials/       # Frammenti riusabili
│       ├── components/     # Blade components (@component)
│       └── forms/
├── patterns/               # Block patterns PHP
├── public/build/           # Output Vite (gitignored)
├── vendor/                 # Composer packages (gitignored)
├── functions.php           # Entry point PHP → boot Acorn
├── style.css               # Intestazione tema WP
├── theme.json              # Design system (sorgente)
└── vite.config.js
```
