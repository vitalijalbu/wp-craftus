# 07 — Footer

File: `resources/views/sections/footer.blade.php`

---

## Struttura footer

```
<footer class="bg-ink text-white">
  ├── Newsletter band          → form Alpine.js → REST API /newsletter
  ├── Main grid (12 col)
  │   ├── Brand (col-span-4)   → logo, tagline, social icons
  │   ├── Esplora (col-span-2) → footer_navigation menu
  │   ├── Shop (col-span-2)    → categorie WooCommerce
  │   └── Informazioni (col-span-2) → link statici
  ├── Divider gradiente oro
  └── Legal bar               → copyright + Privacy/Cookie/Termini
```

---

## Newsletter band

Il form invia una POST a `/wp-json/theme/v1/newsletter` via `fetch()` Alpine.js.

### Stati Alpine

| State | UI |
|-------|----|
| `idle` | form visibile |
| `loading` | button disabilitato, mostra `…` |
| `done` | nasconde form, mostra messaggio successo |
| `error` | mostra messaggio errore in rosso |

### Personalizzare testo heading newsletter

**Customizer** → Opzioni Tema → Titolo newsletter footer.

Oppure via `theme_mod`:
```php
update_theme_mod('newsletter_heading', 'La tua nuova headline.');
```

---

## Colonne footer

### Colonna Brand

- **Logo:** usa `get_custom_logo()` se impostato, altrimenti `get_bloginfo('name')` in serif
- **Tagline:** configurabile da Customizer → Opzioni Tema → Tagline footer
- **Social icons:** visibili solo se l'URL è compilato in Customizer → Social Media

Social supportati: Instagram, Facebook, TikTok, YouTube.

### Colonna Esplora (footer_navigation)

Popola la colonna con le voci del menu **"Menu Footer"**:

```php
$loc          = get_nav_menu_locations()['footer_navigation'] ?? 0;
$footer_items = wp_get_nav_menu_items($loc) ?: [];
$footer_items = array_filter($footer_items, fn($i) => !$i->menu_item_parent);
```

Assegna il menu da WP Admin → Aspetto → Menu → posizione "Menu Footer".

### Colonna Shop

Categorie WooCommerce di primo livello (stessa cache del header). Mostra un link "Tutti i prodotti →" che punta alla pagina shop WC.

### Colonna Informazioni

Link statici hardcoded puntando a slug comuni:

```php
[home_url('/chi-siamo'), __('Chi siamo', 'sage')],
[home_url('/contatti'),  __('Contatti',  'sage')],
[home_url('/blog'),      __('Blog',      'sage')],
[home_url('/faq'),       __('FAQ',       'sage')],
```

Per personalizzarli: modifica direttamente l'array in `footer.blade.php`, oppure trasforma in un terzo menu WP registrato.

---

## Legal bar

```blade
© {{ date('Y') }} {{ get_bloginfo('name') }}. Tutti i diritti riservati.

Privacy Policy | Cookie Policy | Termini
```

I link puntano agli slug: `/privacy-policy`, `/cookie-policy`, `/termini-e-condizioni`.

---

## Aggiungere/rimuovere colonne

La grid è `grid-cols-12`. Per aggiungere una colonna:

```blade
{{-- Dopo la colonna Informazioni --}}
<div class="col-span-1 lg:col-span-2">
  <p class="text-[9px] font-sans font-semibold tracking-[0.25em] uppercase text-white/30 mb-5">
    {{ __('Contatti', 'sage') }}
  </p>
  <address class="text-[12px] font-sans text-white/40 not-italic space-y-2">
    <p>Via Roma 1, Milano</p>
    <p>info@miosito.it</p>
  </address>
</div>
```

Per rimuovere la colonna Shop (siti senza WooCommerce): cancella o commenta il blocco `{{-- Shop categories --}}`.

---

## Footer senza newsletter

Per disabilitare la newsletter band:

```blade
{{-- Commenta o rimuovi questo blocco in footer.blade.php --}}
{{-- ─── Newsletter band ─── --}}
<div class="border-b border-white/10">
  ...
</div>
```

---

## Cache categorie footer

Il footer riusa la cache impostata dall'header (`wp_cache_get('theme_header_wc_cats')`). Se l'header non è stato renderizzato (es. footer in una chiamata REST), fa una query di fallback.

Per invalidare la cache manualmente:
```php
wp_cache_delete('theme_header_wc_cats');
```

---

## Adattare il footer per un nuovo sito

| Cosa | Dove |
|------|------|
| Tagline | Customizer → Opzioni Tema |
| Newsletter heading | Customizer → Opzioni Tema |
| Social links | Customizer → Social Media |
| Logo | Customizer → Identità sito |
| Link "Informazioni" | hardcoded in `footer.blade.php` |
| Colori | `bg-ink` (dark navy), `text-gold` — modifica classi Tailwind |
| Togliere colonna Shop | rimuovi blocco "Shop categories" |
