# 02 — Architettura

## Stack overview

```
WordPress Core
  └── Roots Acorn 5          (container Laravel + service provider)
        └── Sage 11          (routing Blade, view composers)
              ├── Blade      (templating engine)
              ├── Vite       (asset bundling)
              └── Tailwind   (utility CSS)
```

---

## Entry point PHP: `functions.php`

```php
// functions.php
require_once __DIR__ . '/vendor/autoload.php';
\Roots\bootloader()->boot();
```

Acorn bootstrappa il container Laravel dentro WordPress. Da questo momento in poi hai accesso a service provider, facade, config, e il sistema di view Blade.

---

## ThemeServiceProvider

`app/Providers/ThemeServiceProvider.php` è il service provider principale. Estende `SageServiceProvider` di Acorn.

```php
class ThemeServiceProvider extends SageServiceProvider
{
    public function register() { parent::register(); }
    public function boot()     { parent::boot(); }
}
```

**Dove aggiungere logica custom:**

```php
public function boot()
{
    parent::boot();

    // Bind di una classe nel container
    $this->app->singleton(MyService::class, fn() => new MyService());

    // Condivisione di dati a tutte le view
    View::share('siteName', get_bloginfo('name'));
}
```

---

## Namespace PHP: `App\`

Tutte le classi in `app/` sono nel namespace `App\` (PSR-4 via Composer):

```json
// composer.json
"autoload": {
  "psr-4": { "App\\": "app/" }
}
```

I file `setup.php`, `filters.php`, `customizer.php` usano `namespace App;` e sono caricati automaticamente da Acorn.

---

## Sistema di template Blade

### Gerarchia base

Sage intercetta la gerarchia template di WordPress e risolve i template Blade da `resources/views/`.

| WP cerca | Blade corrisponde |
|----------|-------------------|
| `front-page.php` | `front-page.blade.php` |
| `page.php` | `page.blade.php` |
| `single.php` | `single.blade.php` |
| `archive.php` | `index.blade.php` |
| `404.php` | `404.blade.php` |
| `search.php` | `search.blade.php` |

### Layout base

Tutti i template estendono `layouts.app`:

```blade
{{-- resources/views/page.blade.php --}}
@extends('layouts.app')

@section('content')
  @while(have_posts()) @php(the_post())
    @php the_content() @endphp
  @endwhile
@endsection
```

### Layout app.blade.php

```
layouts/app.blade.php
├── <head> + wp_head() + @vite([...])
├── @include('sections.header')
├── <main> @yield('content') </main>
├── @hasSection('sidebar') <aside>... </aside>
├── @include('sections.footer')
└── wp_footer()
```

---

## View Composers

I View Composer passano dati PHP alle view Blade senza inquinare i template.

**Registrazione** in `ThemeServiceProvider::boot()`:

```php
use Illuminate\Support\Facades\View;

View::composer('sections.header', function ($view) {
    $view->with('cartCount', WC()->cart->get_cart_contents_count());
});
```

**Oppure con una classe dedicata:**

```php
// app/View/Composers/HeaderComposer.php
namespace App\View\Composers;

use Illuminate\View\View;

class HeaderComposer
{
    public function compose(View $view): void
    {
        $view->with('cartCount', WC()->cart->get_cart_contents_count());
    }
}

// In ThemeServiceProvider::boot():
View::composer('sections.header', HeaderComposer::class);
```

---

## Blade Directives personalizzate

Puoi registrare directive Blade custom in `ThemeServiceProvider::boot()`:

```php
Blade::directive('icon', function ($name) {
    return "<?php echo get_template_part('resources/views/icons/' . {$name}); ?>";
});
```

---

## Façade Vite in PHP

```php
use Illuminate\Support\Facades\Vite;

// URL di un asset compilato
$url = Vite::asset('resources/images/logo.svg');

// Controlla se il dev server è attivo
$isHot = Vite::isRunningHot();

// Legge il contenuto di un file build
$deps = json_decode(Vite::content('editor.deps.json'));
```

---

## Struttura `app/`

```
app/
├── Providers/
│   └── ThemeServiceProvider.php   # Boot principale
├── View/
│   └── Composers/                 # View composers (crea qui nuovi)
├── setup.php                      # after_setup_theme, menus, sidebar
├── filters.php                    # Filtri WP, WC, body_class
└── customizer.php                 # Customizer API + theme_cta_url()
```

---

## Aggiungere un nuovo file PHP

1. Crea `app/mio-file.php` con `namespace App;`
2. Acorn lo scopre automaticamente via PSR-4 — non serve `require_once`

In alternativa, per file di configurazione o servizi:

```bash
# Crea un Service Provider dedicato
wp acorn make:provider NomeServiceProvider
```
