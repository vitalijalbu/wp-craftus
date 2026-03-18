# 06 — Header & Navigazione

File: `resources/views/sections/header.blade.php`

---

## Architettura header

L'header è **fixed**, gestito da Alpine.js (`x-data="siteHeader"`) con animazioni GSAP.

```
<header x-data="siteHeader">
  ├── .header-expanded        → barra visibile in cima (72px)
  │   ├── Logo
  │   ├── Nav desktop + mega-menu shop
  │   └── Actions (search, cart, CTA) + toggle mobile
  │
  ├── .header-scrolled-bar    → barra compatta (58px, dark)
  │   └── (stessa struttura, stile dark/compatto)
  │
  ├── #mega-shop              → mega-menu WooCommerce categorie
  │
  └── #mobile-drawer          → drawer full-screen mobile
```

---

## Comportamento scroll

Controllato da GSAP nel modulo JS:

- **Al top (< 80px):** mostra `.header-expanded`, nasconde `.header-scrolled-bar`
- **Scrolled (> 80px):** anima verso `.header-scrolled-bar`
- **Con hero:** header trasparente sopra il hero, diventa opaco con sfondo al scroll

La body class `has-hero` viene aggiunta da `app/filters.php` se il primo blocco della pagina è `core/cover`, `core/group`, o `theme/hero`.

```php
// filters.php
add_filter('body_class', function (array $classes): array {
    // controlla il primo blocco del post
    // aggiunge 'has-hero' se è un blocco hero
});
```

---

## Menus registrati

Registrati in `app/setup.php`:

```php
register_nav_menus([
    'primary_navigation' => __('Menu Principale', 'sage'),
    'footer_navigation'  => __('Menu Footer', 'sage'),
]);
```

### Assegnare il menu

WP Admin → Aspetto → Menu → Seleziona posizione → Salva.

### Come vengono renderizzati

L'header **non** usa `wp_nav_menu()` ma recupera direttamente gli item per renderizzare con Blade:

```php
$loc   = get_nav_menu_locations()['primary_navigation'] ?? 0;
$items = wp_get_nav_menu_items($loc) ?: [];
// filtra solo i top-level (parent = 0)
$top_items = array_filter($items, fn($i) => !$i->menu_item_parent);
```

Questo permette controllo totale sul markup HTML e sulle classi Tailwind.

### Aggiungere supporto ai sottomenu

Attualmente il menu principale mostra solo i top-level. Per aggiungere un dropdown nidificato:

```blade
@foreach($top_items as $item)
  @php
    $children = array_filter($all_items, fn($i) => $i->menu_item_parent == $item->ID);
  @endphp

  @if(!empty($children))
    <div x-data="{open:false}" class="relative">
      <button @click="open=!open">{{ $item->title }}</button>
      <ul x-show="open" class="absolute top-full ...">
        @foreach($children as $child)
          <li><a href="{{ $child->url }}">{{ $child->title }}</a></li>
        @endforeach
      </ul>
    </div>
  @else
    <a href="{{ $item->url }}">{{ $item->title }}</a>
  @endif
@endforeach
```

---

## Mega-menu Shop

Visibile solo se ci sono categorie WooCommerce (`$wc_cats` non vuoto).

### Struttura

```
#mega-shop (x-show="activeMenu === 'shop'")
└── grid (colonne categoria + card CTA)
    ├── Colonna categoria 1
    │   ├── Immagine categoria (lazy)
    │   ├── Link categoria
    │   └── Sottocategorie (max 6)
    ├── Colonna categoria 2–5
    └── Card CTA "In evidenza" (sfondo dark)
```

### Dati caricati

Le categorie sono recuperate con cache di oggetti (5 minuti):

```php
$wc_cats = wp_cache_get('4zampe_header_wc_cats');
if ($wc_cats === false) {
    $wc_cats = get_terms([
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
        'parent'     => 0,
        'number'     => 6,
        'exclude'    => get_option('default_product_cat'),
    ]);
    wp_cache_set('4zampe_header_wc_cats', $wc_cats, '', 5 * MINUTE_IN_SECONDS);
}
```

### Personalizzare il mega-menu

**Numero massimo categorie:** cambia `'number' => 6` nella query.

**Aggiungere un secondo mega-menu** (es. "Servizi"):

```blade
{{-- In header.blade.php, dopo il loop $top_items --}}
<button @mouseenter="openMenu('servizi')" @click="openMenu('servizi')">
  Servizi
</button>

{{-- Sotto il mega-shop --}}
<div x-show="activeMenu === 'servizi'" @mouseleave="closeMenu()" ...>
  {{-- contenuto mega-menu servizi --}}
</div>
```

---

## Mobile Drawer

```
#mobile-drawer (x-show="mobileOpen", x-trap.inert.noscroll)
├── nav.flex-1
│   ├── Accordion "Shop" → categorie WooCommerce (x-collapse)
│   └── Links top-level del menu principale
└── Footer drawer
    ├── CTA button
    └── Social links (da Customizer)
```

**Transizione:** slide-in da destra (`translate-x-full` → `translate-x-0`).

**Focus trap:** `x-trap` da Alpine Focus impedisce il focus fuori dal drawer quando è aperto.

---

## Alpine.js store: `siteHeader`

Registrato in `resources/js/app.js`. Stato disponibile:

| Proprietà | Tipo | Descrizione |
|-----------|------|-------------|
| `scrolled` | `Boolean` | true se scroll > 80px |
| `hasHero` | `Boolean` | true se pagina ha hero |
| `activeMenu` | `String\|null` | id del mega-menu aperto |
| `mobileOpen` | `Boolean` | stato drawer mobile |

Metodi:
- `openMenu(id)` — apre un mega-menu
- `closeMenu()` — chiude tutti i menu
- `toggleMobile()` — toggle drawer
- `closeMobile()` — chiude drawer (usato nei link)

---

## CTA header

L'URL del pulsante "Contattaci" è configurabile dal Customizer (→ Opzioni Tema → URL pulsante "Contattaci").

Il fallback è `/contatti`. La logica è in `app/customizer.php`:

```php
function theme_cta_url(): string {
    $override = get_theme_mod('cta_url', '');
    return $override ? esc_url($override) : esc_url(home_url('/contatti'));
}
```

---

## Spacer header

```blade
{{-- In header.blade.php, dopo il tag </header> --}}
<div class="h-[72px]"
     :class="$store.layout.hasHero ? 'hidden' : 'block'"
     aria-hidden="true"></div>
```

Compensa l'altezza del header fixed sulle pagine senza hero. Viene nascosto quando c'è un hero (il contenuto deve partire da top: 0).

---

## Adattare l'header per un nuovo sito

| Cosa cambiare | Dove |
|---------------|------|
| Altezza header | `h-[72px]` / `h-[58px]` in `header.blade.php` |
| Colore navbar trasparente | classi `:class="{...}"` sulle div `.header-expanded` |
| Logo | Customizer → Identità sito → Logo |
| CTA label | stringa tradotta `__('Contattaci', 'sage')` |
| CTA URL | Customizer → Opzioni Tema |
| Social nel drawer | Customizer → Social Media |
| Rimuovere mega-menu shop | rimuovi il blocco `@if(!empty($wc_cats))` |
