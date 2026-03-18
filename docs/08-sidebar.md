# 08 — Sidebar & Widget

## Sidebar registrate

Definite in `app/setup.php` via hook `widgets_init`:

```php
register_sidebar([
    'name' => __('Primary', 'sage'),
    'id'   => 'sidebar-primary',
    'before_widget' => '<section class="widget %1$s %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>',
]);

register_sidebar([
    'name' => __('Footer', 'sage'),
    'id'   => 'sidebar-footer',
    // stessa config
]);
```

| ID | Uso consigliato |
|----|----------------|
| `sidebar-primary` | Colonna laterale nelle pagine blog/archivi |
| `sidebar-footer` | Widget nel footer (alternative a colonne hardcoded) |

---

## Usare una sidebar in un template

### Metodo 1 — section `sidebar` (layout app.blade.php)

```blade
@extends('layouts.app')

@section('content')
  {{-- contenuto principale --}}
@endsection

@section('sidebar')
  @php dynamic_sidebar('sidebar-primary') @endphp
@endsection
```

Il layout `app.blade.php` renderizza automaticamente l'`<aside>` se la section `sidebar` è definita:

```blade
{{-- In layouts/app.blade.php --}}
@hasSection('sidebar')
  <aside class="sidebar">@yield('sidebar')</aside>
@endif
```

### Metodo 2 — inline in qualsiasi template

```blade
@if(is_active_sidebar('sidebar-primary'))
  <aside class="w-80 shrink-0">
    @php dynamic_sidebar('sidebar-primary') @endphp
  </aside>
@endif
```

---

## Layout a 2 colonne con sidebar

Esempio di template con layout content + sidebar:

```blade
{{--
  Template Name: Con Sidebar
--}}

@extends('layouts.app')

@section('content')
  <div class="max-w-360 mx-auto px-6 lg:px-10 py-16">
    <div class="flex gap-12">

      {{-- Contenuto principale --}}
      <div class="flex-1 min-w-0">
        @while(have_posts()) @php(the_post())
          <h1>{{ get_the_title() }}</h1>
          @php the_content() @endphp
        @endwhile
      </div>

      {{-- Sidebar --}}
      @if(is_active_sidebar('sidebar-primary'))
        <aside class="w-72 shrink-0 hidden lg:block">
          @php dynamic_sidebar('sidebar-primary') @endphp
        </aside>
      @endif

    </div>
  </div>
@endsection
```

---

## Registrare una nuova sidebar

```php
// In app/setup.php, dentro add_action('widgets_init', ...)
register_sidebar([
    'name'          => __('Blog Sidebar', 'sage'),
    'id'            => 'sidebar-blog',
    'before_widget' => '<section class="widget %1$s %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h3 class="widget-title text-sm font-semibold uppercase tracking-wider mb-4">',
    'after_title'   => '</h3>',
]);
```

---

## Stilizzare i widget

I widget hanno classi WP automatiche: `widget widget_text`, `widget widget_recent_posts`, ecc.

In `resources/css/app.css`:

```css
/* Stili widget generici */
.widget {
  @apply mb-8;
}

.widget-title {
  @apply text-sm font-semibold uppercase tracking-wider mb-4 text-ink;
}

/* Widget specifico */
.widget_recent_posts ul {
  @apply space-y-2;
}

.widget_recent_posts li a {
  @apply text-sm text-muted hover:text-primary transition-colors;
}
```

---

## Sidebar con blocchi Gutenberg (Widget Block Editor)

WordPress 5.8+ usa il block editor per i widget. I widget registrati con `register_sidebar` appaiono in **WP Admin → Aspetto → Widget** con l'interfaccia a blocchi.

Per disabilitare il block editor per i widget (tornare al widget classico):

```php
// In app/setup.php
add_filter('use_widgets_block_editor', '__return_false');
```

---

## Note per siti senza sidebar

Se il sito non ha sidebar (layout full-width), puoi:

1. **Non assegnare widget** — `is_active_sidebar()` restituisce `false` e la sidebar non appare
2. **Rimuovere le sidebar** — commenta `register_sidebar` in `app/setup.php`
3. **Rimuovere la section dal layout** — togli il blocco `@hasSection('sidebar')` da `app.blade.php`
