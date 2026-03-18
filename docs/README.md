# Sage Theme — Enterprise Starter Kit

**Stack:** Roots Sage 11 · Laravel Acorn 5 · Tailwind CSS v4 · Vite 7 · Alpine.js 3 · GSAP 3

Questo tema è il tuo punto di partenza enterprise per tutti i siti WordPress futuri. Ogni sezione di questa documentazione copre un aspetto specifico dello stack.

---

## Indice

| # | Documento | Argomento |
|---|-----------|-----------|
| 01 | [Setup & Development](./01-setup.md) | Installazione, comandi build, variabili d'ambiente |
| 02 | [Architettura](./02-architecture.md) | Acorn, Blade, Service Provider, struttura file |
| 03 | [Design System](./03-design-system.md) | theme.json, colori, tipografia, spacing |
| 04 | [Template & Pagine Custom](./04-templates.md) | Gerarchia template, pagine custom, landing page ACF |
| 05 | [Block Patterns](./05-blocks-patterns.md) | Pattern registrati, categorie, come crearne di nuovi |
| 06 | [Header & Navigazione](./06-header-nav.md) | Header sticky/scrolled, mega-menu, mobile drawer |
| 07 | [Footer](./07-footer.md) | Newsletter band, colonne, social, legal bar |
| 08 | [Sidebar & Widget](./08-sidebar.md) | Sidebar registrate, uso nei template |
| 09 | [WooCommerce](./09-woocommerce.md) | Setup, template override, filtri, cart fragments |
| 10 | [Customizer](./10-customizer.md) | Opzioni tema, social links, CTA, footer |
| 11 | [JavaScript & Animazioni](./11-javascript.md) | Alpine.js, GSAP, Swiper, Locomotive Scroll |
| 12 | [REST API & Integrazioni](./12-rest-api.md) | Newsletter endpoint, hook di estensione |

---

## Requisiti

- PHP 8.2+
- WordPress 6.6+
- Node.js 20+
- Composer 2+
- WooCommerce (opzionale ma supportato out-of-the-box)

## Quick Start

```bash
# 1. Installa dipendenze PHP
composer install

# 2. Installa dipendenze Node
npm install

# 3. Sviluppo locale (hot reload)
npm run dev

# 4. Build di produzione
npm run build
```

> Imposta `APP_URL` in `.env` o direttamente in `vite.config.js` per il proxy del dev server.
