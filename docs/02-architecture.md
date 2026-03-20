# 02 — Architettura

## Tema ibrido — tre layer sovrapposti

```
┌─────────────────────────────────────────────┐
│  CLIENTE                                    │
│  Global Styles (colori, font, spacing)      │
│  Editor Gutenberg (blocchi e pattern)       │
└────────────────────┬────────────────────────┘
                     │
┌────────────────────▼────────────────────────┐
│  EDITOR LAYER                               │
│  theme.json      → design tokens            │
│  editor.js       → blocchi React + Variations│
│  editor.css      → WYSIWYG stili            │
│  blocks/*/       → blocchi SSR custom       │
│  patterns/*/     → layout preconfigurati    │
└────────────────────┬────────────────────────┘
                     │
┌────────────────────▼────────────────────────┐
│  FRONTEND LAYER                             │
│  Blade templates → PHP server-side render   │
│  Tailwind CSS    → utility classes          │
│  Alpine.js       → reattività leggera       │
│  GSAP + Swiper   → animazioni e carousel    │
└─────────────────────────────────────────────┘
```

```
WordPress Core
  └── Roots Acorn 5          (Laravel container + service provider)
        └── Sage 11          (routing Blade, view composers)
              ├── Blade      (templating engine)
              ├── Vite       (asset bundling)
              ├── Tailwind   (utility CSS)
              └── Gutenberg  (blocchi + Global Styles via theme.json)
```

---

## Entry point PHP: `functions.php`

```php
// functions.php — non modificare
require_once __DIR__ . '/vendor/autoload.php';
\Roots\bootloader()->boot();
```

Acorn avvia il container Laravel dentro WordPress. Da questo momento hai service provider, facade, Blade, e Vite helper.

---

## ThemeServiceProvider

`app/Providers/ThemeServiceProvider.php` — estende `SageServiceProvider`.

```php
class ThemeServiceProvider extends SageServiceProvider
{
    public function register() { parent::register(); }

    public function boot()
    {
        parent::boot();

        // Bind nel container
        $this->app->singleton(MyService::class, fn() => new MyService());

        // Dati condivisi a tutte le view Blade
        View::share('siteName', get_bloginfo('name'));
    }
}
```

---

## Namespace PHP: `App\`

Tutti i file in `app/` usano il namespace `App\` (PSR-4 via Composer):

```php
// Inizio di ogni file in app/
<?php
namespace App;
```

Acorn carica automaticamente ogni file in `app/` — non servono `require_once` manuali.

---

## Sistema di template Blade

Sage intercetta la gerarchia template WordPress e risolve i file Blade da `resources/views/`.

| WordPress cerca | Blade risolve |
|----------------|---------------|
| `front-page.php` | `front-page.blade.php` |
| `page.php` | `page.blade.php` |
| `single.php` | `single.blade.php` |
| `archive.php` | `index.blade.php` |
| `404.php` | `404.blade.php` |
| `search.php` | `search.blade.php` |

### Layout base

```blade
{{-- resources/views/page.blade.php --}}
@extends('layouts.app')

@section('content')
  @while(have_posts()) @php(the_post())
    @php(the_content())
  @endwhile
@endsection
```

### Struttura di `layouts/app.blade.php`

```
<html>
  <head> + wp_head() + @vite([...])
  @include('sections.header')        ← header sticky con Alpine
  <main> @yield('content') </main>
  @hasSection('sidebar')
    <aside>...</aside>
  @include('sections.footer')
  wp_footer()
</html>
```

---

## View Composers

Iniettano dati PHP nelle view Blade senza inquinare i template.

**Inline in ThemeServiceProvider:**
```php
View::composer('sections.header', function ($view) {
    $view->with('cartCount', WC()->cart->get_cart_contents_count());
});
```

**Con classe dedicata** in `app/View/Composers/`:
```php
namespace App\View\Composers;
use Roots\Acorn\View\Composer;

class FrontPage extends Composer
{
    protected static $views = ['front-page'];

    public function with(): array
    {
        return [
            'featuredPosts' => get_posts(['posts_per_page' => 3]),
        ];
    }
}
```

---

## Facade Vite in PHP

```php
use Illuminate\Support\Facades\Vite;

// URL di un asset compilato
$url  = Vite::asset('resources/images/logo.svg');

// Dev server attivo?
$hot  = Vite::isRunningHot();

// Legge il contenuto di un file build
$deps = json_decode(Vite::content('editor.deps.json'));
```

---

## Aggiungere logica PHP

**Hook e filtri semplici** → aggiungi in `app/filters.php` o `app/setup.php`.

**Nuovo file PHP** → crea `app/mio-file.php` con `namespace App;`. Acorn lo carica automaticamente via PSR-4.

**Servizio con DI** → crea un Service Provider:
```bash
wp acorn make:provider NomeServiceProvider
```

---

## Blocchi Gutenberg — architettura

Ogni custom block ha:

```
blocks/nome-blocco/
├── block.json     → metadati, attributi, supports (letto da WP)
└── render.php     → output PHP (eseguito ad ogni render, lato server)
```

Il file `editor.js` contiene:
- `registerBlockType()` per ogni blocco (UI React nell'editor)
- `registerBlockStyle()` per le Style Variations
- `registerBlockVariation()` per le Block Variations

**Flusso quando il cliente salva un blocco:**
1. React (editor.js) serializza gli attributi in JSON nel post content
2. WordPress salva il post
3. Al render frontend, WP chiama `render.php` passando `$attributes`
4. PHP genera l'HTML con Tailwind

---

## Global Styles — architettura

```
theme.json (sorgente)
    ↓ Vite build
public/build/assets/theme.json (caricato da WP)
    ↓
Global Styles panel (Aspetto → Editor → paintbrush)
    ↓ il cliente modifica
wp_global_styles (DB — sovrascrive theme.json)
    ↓
CSS custom properties su :root (generate da WP)
```

Le modifiche del cliente nel pannello Global Styles vengono salvate nel DB e hanno priorità sui valori di `theme.json`. Per resettare: Global Styles → menu ⋮ → "Ripristina predefiniti del tema".

---

## Performance — scelte architetturali

| Scelta | Effetto |
|---|---|
| `should_load_separate_core_block_assets → true` | CSS blocchi caricato on-demand (solo i blocchi usati nella pagina) |
| Emoji script rimossi | -15 KB + 1 DNS lookup |
| jQuery Migrate rimosso | -10 KB |
| Script WC con `defer` | Non bloccano il rendering |
| Google Fonts con `preload` + `print/onload` | Non bloccano il rendering |
| Chunk vendor separati (Alpine, GSAP, Swiper) | Cache browser ottimale |
| `fetchpriority="high"` sul logo | LCP migliorato |
