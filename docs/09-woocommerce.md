# 09 — WooCommerce

## Setup e supporto tema

Dichiarato in `app/setup.php` via `after_setup_theme`:

```php
add_theme_support('woocommerce', [
    'thumbnail_image_width' => 600,   // immagine card prodotto
    'single_image_width'    => 800,   // immagine pagina prodotto
    'product_grid'          => [
        'default_rows'    => 3,
        'min_rows'        => 1,
        'default_columns' => 3,
        'min_columns'     => 1,
        'max_columns'     => 4,
    ],
]);
add_theme_support('wc-product-gallery-zoom');
add_theme_support('wc-product-gallery-lightbox');
add_theme_support('wc-product-gallery-slider');
```

---

## Template WooCommerce: `woocommerce.blade.php`

Tutte le pagine WooCommerce (shop, categoria, prodotto, carrello, checkout, account) vengono reindirizzate a questo template via `sage/template/hierarchy`.

### Struttura

```blade
@extends('layouts.app')
@section('content')

  {{-- Page header: breadcrumb + titolo --}}
  <div class="bg-cream border-b pt-16 pb-10">
    <div class="max-w-360 mx-auto px-6 lg:px-10">
      @php woocommerce_breadcrumb() @endphp
      <h1>{{ $page_title }}</h1>
    </div>
  </div>

  {{-- Contenuto WC --}}
  <div class="woocommerce-page bg-surface">
    <div class="max-w-360 mx-auto px-6 lg:px-10 py-12 lg:py-16">
      @php woocommerce_content() @endphp
    </div>
  </div>

@endsection
```

### Titolo dinamico per tipo di pagina

```php
$page_title = get_the_title(wc_get_page_id('shop'));
if (is_cart())              $page_title = __('Carrello');
if (is_checkout())          $page_title = __('Checkout');
if (is_account_page())      $page_title = __('Il mio account');
if (is_product_category())  $page_title = single_term_title('', false);
if (is_product())           $page_title = get_the_title();
```

---

## Override template WooCommerce

WooCommerce permette di sovrascrivere qualsiasi suo template copiandolo in `resources/views/woocommerce/`.

### Struttura di override

```
resources/views/woocommerce/
├── archive-product.php          → pagina shop/categoria
├── single-product.php           → pagina prodotto
├── cart/
│   └── cart.php                 → carrello
├── checkout/
│   ├── form-checkout.php        → form checkout
│   └── thankyou.php             → pagina grazie
├── myaccount/
│   └── my-account.php           → area account
└── emails/
    └── email-header.php         → header email WC
```

### Come fare override

1. Trova il template originale in `wp-content/plugins/woocommerce/templates/`
2. Copia il file nella struttura corrispondente in `resources/views/woocommerce/`
3. Modifica il file copiato

> **Nota:** I template WooCommerce in Sage **non** sono file Blade, sono PHP standard. Questo è un limite di come WC carica i template.

### Override con Blade (avanzato)

Per usare Blade per i template WooCommerce, usa il filtro `wc_get_template`:

```php
// In app/filters.php
add_filter('wc_get_template', function ($template, $template_name, $args) {
    $blade_path = locate_template("resources/views/woocommerce/{$template_name}");
    if ($blade_path) {
        return $blade_path;
    }
    return $template;
}, 10, 3);
```

---

## Filtri WooCommerce attivi

Tutti definiti in `app/filters.php`:

### 1. CSS WooCommerce disabilitato

```php
add_filter('woocommerce_enqueue_styles', '__return_empty_array');
```

Disabilita tutti gli stili WooCommerce predefiniti. Gli stili WC vengono gestiti interamente da `resources/css/app.css`.

### 2. Prodotti per pagina e colonne

```php
add_filter('loop_shop_per_page', fn() => 12);
add_filter('loop_shop_columns',  fn() => 3);
```

### 3. Cap query prodotti (sicurezza memoria)

```php
// Blocchi shortcode
add_filter('woocommerce_shortcode_products_query', function (array $query): array {
    if (empty($query['posts_per_page']) || $query['posts_per_page'] < 0) {
        $query['posts_per_page'] = 12;
    }
    return $query;
});

// Blocchi Gutenberg WooCommerce
add_filter('pre_render_block', function ($pre_render, array $block) {
    // Imposta max 24 per blocchi woocommerce/*
    // ...
}, 5, 2);
```

Previene memory exhaustion con cataloghi grandi.

### 4. Cart count fragment (AJAX)

```php
add_filter('woocommerce_add_to_cart_fragments', function (array $fragments): array {
    $count = WC()->cart->get_cart_contents_count();
    $html  = sprintf(
        '<span class="cart-count-fragment ..." data-cart-count="%d">%d</span>',
        $count, $count
    );
    $fragments['span.cart-count-fragment'] = $html;
    return $fragments;
});
```

Aggiorna automaticamente il badge carrello nell'header dopo ogni "Aggiungi al carrello" **senza refresh della pagina**.

Il selettore `span.cart-count-fragment` è presente sia nel header expanded che nel bar scrolled e nel mobile.

---

## Carrello nel header

```blade
{{-- In header.blade.php --}}
@if(function_exists('WC'))
  <a href="{{ esc_url(wc_get_cart_url()) }}" class="icon-btn relative">
    {{-- icona carrello --}}
    <span
      class="cart-count-fragment absolute ..."
      data-cart-count="{{ $cart_count }}"
      x-text="cartCount"
    >{{ $cart_count }}</span>
  </a>
@endif
```

Il contatore è gestito da due meccanismi:
- **SSR:** valore PHP iniziale `$cart_count`
- **Alpine:** `x-text="cartCount"` aggiornato da Alpine
- **AJAX fragment:** WooCommerce sostituisce tutti `span.cart-count-fragment` dopo add-to-cart

---

## Stilizzare i componenti WooCommerce

Poiché gli stili WC sono disabilitati, tutti i componenti vanno stilizzati in `resources/css/app.css`.

### Struttura CSS consigliata

```css
/* ── WooCommerce base ─── */
.woocommerce-page {}

/* Griglia prodotti */
.woocommerce ul.products {
  @apply grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6;
}

.woocommerce ul.products li.product {
  @apply group relative;
}

/* Card prodotto */
.woocommerce ul.products li.product a img {
  @apply w-full aspect-square object-cover transition-transform duration-500 group-hover:scale-105;
}

.woocommerce ul.products li.product .woocommerce-loop-product__title {
  @apply text-sm font-semibold text-ink mt-3;
}

.woocommerce ul.products li.product .price {
  @apply text-sm text-primary font-medium;
}

/* Button "Aggiungi al carrello" */
.woocommerce ul.products li.product .button,
.woocommerce .single_add_to_cart_button {
  @apply bg-primary text-white text-xs font-semibold px-5 py-2.5 hover:bg-primary-dark transition-colors;
}

/* Breadcrumb */
.woocommerce-breadcrumb {
  @apply text-xs text-muted;
}

/* Flash notice */
.woocommerce-message,
.woocommerce-error,
.woocommerce-info {
  @apply p-4 mb-6 text-sm rounded;
}
.woocommerce-message  { @apply bg-green-50 text-green-800 border border-green-200; }
.woocommerce-error    { @apply bg-red-50 text-red-800 border border-red-200; }
.woocommerce-info     { @apply bg-blue-50 text-blue-800 border border-blue-200; }
```

---

## Pagina prodotto singola

La pagina prodotto è renderizzata da `woocommerce_content()` nel template `woocommerce.blade.php`.

Per personalizzare layout e hook:

```php
// In app/filters.php o in un file dedicato woocommerce.php

// Rimuovere il titolo prodotto dalla posizione default
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);

// Aggiungere prima della gallery
add_action('woocommerce_before_single_product_summary', function () {
    // custom HTML
}, 5);

// Spostare la descrizione breve
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 25);
```

---

## Checkout personalizzato

Per stilizzare i campi checkout:

```css
/* Fields */
.woocommerce form .form-row input,
.woocommerce form .form-row select,
.woocommerce form .form-row textarea {
  @apply w-full border border-gray-200 px-4 py-3 text-sm text-ink focus:outline-none focus:border-primary transition-colors;
}

/* Label */
.woocommerce form .form-row label {
  @apply block text-xs font-medium text-gray-600 mb-1.5;
}
```

---

## WooCommerce + pattern

Il tema include pattern specifici per WooCommerce:

| Pattern | Slug | Uso |
|---------|------|-----|
| `shop-hero.php` | `theme/shop-hero` | Hero per la pagina shop |
| `product-categories.php` | `theme/product-categories` | Griglia categorie in homepage |

Questi pattern usano blocchi core WooCommerce (`woocommerce/product-query`, `woocommerce/product-category`, ecc.) pre-configurati.

---

## Siti senza WooCommerce

Per usare questo tema senza WooCommerce:

1. Rimuovi le righe `add_theme_support('woocommerce', ...)` da `app/setup.php`
2. Rimuovi i filtri WC da `app/filters.php`
3. Rimuovi il filtro `sage/template/hierarchy` da `app/filters.php`
4. Rimuovi `@if(function_exists('WC'))` dall'header
5. Elimina `woocommerce.blade.php`

Tutti i blocchi WC saranno assenti dall'editor (nessun errore).
