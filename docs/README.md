# Sage Theme — Documentazione

**Stack:** Roots Sage 11 · Acorn 5 · Tailwind CSS v4 · Vite 7 · Alpine.js 3 · GSAP 3 · WordPress 6.x

Questo è un **tema ibrido**: il frontend usa Blade (PHP server-side), l'editor Gutenberg usa React (blocchi custom) e il cliente gestisce colori/font/spacing da **Aspetto → Editor → Global Styles** senza toccare codice.

---

## Indice

| # | Documento | Argomento |
|---|-----------|-----------|
| 01 | [Setup & Sviluppo](./01-setup.md) | Installazione, comandi build, struttura directory |
| 02 | [Architettura](./02-architecture.md) | Acorn, Blade, Service Provider, View Composers |
| 03 | [Design System](./03-design-system.md) | theme.json, colori, tipografia, spacing, Global Styles |
| 04 | [Template & Pagine](./04-templates.md) | Blade templates, block templates, gerarchia, page templates |
| 05 | [Blocchi & Pattern](./05-blocks-patterns.md) | Custom blocks, block patterns, style variations, block variations |
| 06 | [Header & Nav](./06-header-nav.md) | Header sticky, mega-menu Alpine, mobile drawer |
| 07 | [Footer](./07-footer.md) | Newsletter band, colonne, social, legal bar |
| 08 | [Sidebar & Widget](./08-sidebar.md) | Sidebar registrate, uso nei template |
| 09 | [WooCommerce](./09-woocommerce.md) | Setup, template override, filtri, cart fragments |
| 10 | [Customizer](./10-customizer.md) | Opzioni tema, social links, CTA, announcement bar |
| 11 | [JavaScript & Animazioni](./11-javascript.md) | Alpine.js, GSAP, Swiper, Locomotive Scroll |
| 12 | [REST API](./12-rest-api.md) | Endpoint custom, newsletter, live search |

---

## Requisiti

- PHP 8.2+
- WordPress 6.6+
- Node.js 20+
- Composer 2+
- WooCommerce (opzionale, supportato out-of-the-box)

---

## Quick Start

```bash
composer install     # dipendenze PHP (Acorn)
npm install          # dipendenze Node (Tailwind, Vite, Alpine…)
npm run dev          # dev server con HMR
npm run build        # build produzione → public/build/
```

Imposta `APP_URL` in `.env` o in `vite.config.js` per il proxy del dev server.

---

## Cosa può fare il cliente senza toccare codice

| Funzionalità | Dove |
|---|---|
| Cambiare colori del tema | Aspetto → Editor → Global Styles (paintbrush) |
| Cambiare font e dimensioni | Aspetto → Editor → Global Styles |
| Cambiare spacing globale | Aspetto → Editor → Global Styles |
| Costruire pagine con blocchi | Editor pagina/post |
| Inserire pattern preconfigurati | Editor → inserter blocchi |
| Modificare menu navigazione | Aspetto → Menu |
| Social links, CTA, announcement bar | Aspetto → Personalizza |
