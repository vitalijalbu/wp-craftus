# 04 — Template & Pagine

Il tema supporta **due sistemi di template** che coesistono:

| Sistema | File | Priorità | Modificabile dal cliente? |
|---------|------|----------|--------------------------|
| **Blade** (PHP) | `resources/views/*.blade.php` | Alta — usato per default | No (richiede dev) |
| **Block template** (HTML) | `templates/*.html` | Usato se esiste per lo slug | Sì — da Aspetto → Editor |

**Regola:** se esiste un Blade template per uno slug, viene usato. Il block template viene usato solo se il cliente lo crea dall'editor OPPURE se non esiste un Blade corrispondente.

---

## 1. Blade Templates (default del tema)

Sage risolve i template da `resources/views/` seguendo la gerarchia WordPress.

| Blade file | Quando viene usato |
|---|---|
| `front-page.blade.php` | Homepage (Impostazioni → Lettura → Pagina statica) |
| `page.blade.php` | Pagine generiche |
| `single.blade.php` | Singolo post |
| `index.blade.php` | Archivio blog / fallback |
| `search.blade.php` | Risultati ricerca |
| `404.blade.php` | Pagina non trovata |
| `woocommerce.blade.php` | Tutte le pagine WooCommerce |
| `archive-portfolio.blade.php` | Archivio CPT portfolio |
| `single-portfolio.blade.php` | Singolo portfolio |
| `archive-team.blade.php` | Archivio CPT team |

### Struttura base di un template Blade

```blade
{{-- resources/views/page.blade.php --}}
@extends('layouts.app')

@section('content')
  @while(have_posts())
    @php(the_post())
    <article @php(post_class('max-w-3xl mx-auto py-16 px-6'))>
      <h1 class="font-serif text-4xl font-light">{!! get_the_title() !!}</h1>
      <div class="mt-8 prose">
        @php(the_content())
      </div>
    </article>
  @endwhile
@endsection
```

### Output sicuro in Blade

```blade
{{-- Testo puro — Blade escapa automaticamente --}}
{{ get_the_title() }}

{{-- HTML fidato da WP — usa {!! !!} --}}
{!! get_the_content() !!}
{!! wp_nav_menu(['echo' => false]) !!}

{{-- MAI così — XSS --}}
{!! $_GET['parametro'] !!}
```

---

## 2. Page Templates custom

Crea un file `resources/views/template-nome.blade.php` con il commento header:

```blade
{{--
  Template Name: Nome Visibile in Admin
  Template Post Type: page
--}}

@extends('layouts.app')

@section('content')
  @while(have_posts())
    @php(the_post())
    {{-- contenuto custom --}}
  @endwhile
@endsection
```

Nell'editor WP → pannello Attributi pagina → scegli il template dal dropdown.

---

## 3. Block Templates (HTML) — per il cliente

I block template vivono in `templates/*.html` e sono editabili dal cliente da **Aspetto → Editor → Template**.

Sono utili per pagine dove vuoi che il cliente possa modificare la struttura stessa (non solo il contenuto).

### Slug standard WordPress

| File | Quando viene usato |
|------|-------------------|
| `templates/index.html` | Fallback generale |
| `templates/front-page.html` | Homepage |
| `templates/page.html` | Pagina generica |
| `templates/single.html` | Singolo post |
| `templates/archive.html` | Archivi |
| `templates/search.html` | Risultati ricerca |
| `templates/404.html` | Pagina non trovata |
| `templates/single-portfolio.html` | Singolo CPT portfolio |

### Struttura di un block template

```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:group {"tagName":"main","layout":{"type":"constrained"}} -->
<main class="wp-block-group">
  <!-- wp:post-title {"level":1} /-->
  <!-- wp:post-featured-image /-->
  <!-- wp:post-content /-->
</main>
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

### Template Parts

Template parts riutilizzabili (header, footer, sidebar) in `parts/*.html`:

```html
<!-- parts/header.html -->
<!-- wp:group {"tagName":"header","className":"site-header"} -->
<header class="wp-block-group site-header">
  <!-- wp:site-logo /-->
  <!-- wp:navigation /-->
</header>
<!-- /wp:group -->
```

### Quando usare Blade vs block template

| Scenario | Usa |
|----------|-----|
| Layout fisso controllato dal dev | Blade |
| Pagina che il cliente vuole ricostruire liberamente | Block template |
| Frontend con animazioni Alpine/GSAP specifiche | Blade |
| Contenuto puro Gutenberg (blog, portfolio) | Entrambi funzionano |

---

## 4. Template Blank (no header/footer)

Per landing page o splash completamente custom:

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
    @php(the_content())
  @endwhile
  @php(wp_footer())
</body>
</html>
```

---

## 5. Iniettare dati in un template (View Composers)

```php
// In app/Providers/ThemeServiceProvider.php → boot()
use Illuminate\Support\Facades\View;

View::composer('template-nome', function ($view) {
    $view->with('prodottiFeatured', wc_get_featured_product_ids());
});
```

Oppure con una classe dedicata in `app/View/Composers/NomeComposer.php`:

```php
namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class NomeComposer extends Composer
{
    protected static $views = ['template-nome', 'partials.card'];

    public function with(): array
    {
        return [
            'posts' => get_posts(['posts_per_page' => 3]),
        ];
    }
}
```

---

## 6. Template con sidebar

```blade
@extends('layouts.app')

@section('content')
  {{-- contenuto principale --}}
@endsection

@section('sidebar')
  @php(dynamic_sidebar('sidebar-primary'))
@endsection
```

Il layout `app.blade.php` mostra `<aside>` solo se la section `sidebar` è definita.

---

## 7. Override WooCommerce

In `app/filters.php`, un filtro su `template_include` forza `woocommerce.blade.php` per tutte le pagine WC:

```php
add_filter('template_include', function (string $template): string {
    if (function_exists('is_woocommerce') && (is_woocommerce() || is_cart() || ...)) {
        \Roots\app()->instance('sage.view', 'woocommerce');
    }
    return $template;
}, 101);
```

Per aggiungere altri override (es. CPT custom):

```php
add_filter('template_include', function (string $template): string {
    if (is_singular('progetto')) {
        \Roots\app()->instance('sage.view', 'single-progetto');
    }
    return $template;
}, 101);
```

Poi crea `resources/views/single-progetto.blade.php`.
