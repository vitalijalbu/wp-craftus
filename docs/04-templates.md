# 04 — Template & Pagine Custom

## Gerarchia template Blade

Sage risolve i template da `resources/views/` seguendo la gerarchia WordPress. Il filtro `sage/template/hierarchy` permette di alterarla.

```
front-page.blade.php     → Homepage statica
page.blade.php           → Pagine generiche
single.blade.php         → Singolo post
index.blade.php          → Archive / blog
search.blade.php         → Risultati ricerca
404.blade.php            → Pagina non trovata
woocommerce.blade.php    → Shop, prodotto, carrello, checkout
template-custom.blade.php    → Page template "Custom Template"
template-landing.blade.php   → Page template "Landing Page" (ACF)
```

---

## Template standard

### `page.blade.php`

Pagina generica. Rileva automaticamente le pagine WooCommerce e usa `woocommerce_content()`:

```blade
@extends('layouts.app')
@section('content')
  @while(have_posts()) @php(the_post())
    @if(is_woocommerce() || is_cart() || is_checkout() || is_account_page())
      @php woocommerce_content() @endphp
    @else
      @php the_content() @endphp
    @endif
  @endwhile
@endsection
```

### `front-page.blade.php`

Homepage: renderizza `the_content()` che contiene i blocchi Gutenberg costruiti nell'editor.

```blade
@extends('layouts.app')
@section('content')
  @while(have_posts()) @php(the_post()) @endphp
    @php the_content() @endphp
  @endwhile
@endsection
```

---

## Page Templates custom

### Come creare un page template

1. Crea `resources/views/template-nome.blade.php`
2. Aggiungi il commento header in cima:

```blade
{{--
  Template Name: Nome Visibile in Admin
  Template Post Type: page, post
--}}

@extends('layouts.app')
@section('content')
  ...
@endsection
```

3. Nell'editor WP → Attributi pagina → Scegli il template dal dropdown.

---

## Landing Page con ACF Flexible Content

`template-landing.blade.php` è il template **page builder** interno. Usa il campo ACF `page_sections` (Flexible Content) per comporre pagine da sezioni predefinite.

### Sezioni disponibili

| Layout ACF (`acf_fc_layout`) | Blade incluso | Campi principali |
|------------------------------|--------------|-----------------|
| `hero` | `sections.hero` | `label`, `title`, `subtitle`, `image`, `cta_1_*`, `cta_2_*`, `align` |
| `hero_carousel` | `sections.hero-carousel` | `slides[]` |
| `products_carousel` | `sections.products-carousel` | `label`, `title`, `category`, `limit`, `featured`, `view_all_*` |
| `products_grid` | `sections.products-grid` | `label`, `title`, `category`, `per_page`, `cols`, `bg`, `show_filters` |
| `categories_grid` | `sections.categories-grid` | `label`, `title`, `cols`, `number`, `bg` |
| `media_text` | `sections.media-text` | `label`, `title`, `text`, `image`, `image_position`, `cta_*`, `bg`, `accent` |
| `features` | `sections.features` | `label`, `title`, `items[]`, `cols`, `bg`, `style` |
| `stats` | `sections.stats` | `label`, `title`, `items[]`, `bg` |
| `cta_banner` | `sections.cta-banner` | `label`, `title`, `subtitle`, `cta_*`, `image`, `style`, `align` |
| `testimonials` | `sections.testimonials` | `label`, `title`, `items[]`, `bg` |
| `marquee` | `sections.marquee` | `items[]`, `bg`, `size` |
| `wysiwyg` | inline `prose` | `content` (HTML) |

### Fallback

Se `page_sections` è vuoto o ACF non è installato, il template mostra il titolo + contenuto standard con stile premium.

### Aggiungere una nuova sezione al builder

1. Crea `resources/views/sections/mia-sezione.blade.php`
2. Aggiungi il campo al Flexible Content in ACF Admin
3. Aggiungi il case in `template-landing.blade.php`:

```blade
@elseif($layout === 'mia_sezione')
  @include('sections.mia-sezione', [
    'title' => $section['title'] ?? '',
    'items' => $section['items'] ?? [],
  ])
```

---

## Creare pagine fullscreen / no-header-footer

Per una pagina completamente custom (es. splash, login branded):

```blade
{{--
  Template Name: Blank
  Template Post Type: page
--}}
<!doctype html>
<html @php(language_attributes())>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @php(wp_head())
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body @php(body_class())>
  @php(wp_body_open())
  @while(have_posts()) @php(the_post())
    @php the_content() @endphp
  @endwhile
  @php(wp_footer())
</body>
</html>
```

---

## Aggiungere dati a un template specifico

Usa un View Composer in `ThemeServiceProvider::boot()`:

```php
View::composer('template-landing', function ($view) {
    $view->with('featuredProducts', wc_get_featured_product_ids());
});
```

---

## Override gerarchia template per WooCommerce

In `app/filters.php`, il filtro `sage/template/hierarchy` forza `woocommerce.blade.php` per tutte le pagine WooCommerce:

```php
add_filter('sage/template/hierarchy', function (array $templates): array {
    if (is_woocommerce() || is_cart() || is_checkout() || is_account_page()) {
        array_unshift($templates, 'woocommerce');
    }
    return $templates;
});
```

Per aggiungere altri override personalizzati:

```php
add_filter('sage/template/hierarchy', function (array $templates): array {
    // Template specifico per CPT "portfolio"
    if (is_singular('portfolio')) {
        array_unshift($templates, 'single-portfolio');
    }
    return $templates;
});
```

Poi crea `resources/views/single-portfolio.blade.php`.

---

## Template con sidebar

```blade
@extends('layouts.app')

@section('content')
  {{-- contenuto principale --}}
@endsection

@section('sidebar')
  @php dynamic_sidebar('sidebar-primary') @endphp
@endsection
```

Il layout `app.blade.php` mostra l'`<aside>` solo se la section `sidebar` è definita.
