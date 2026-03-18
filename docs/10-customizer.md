# 10 — Customizer

File: `app/customizer.php`

Il Customizer WP espone opzioni tema configurabili da **WP Admin → Aspetto → Personalizza**.

---

## Sezioni disponibili

### Social Media (`4zampe_social`)

Priorità: 120 (dopo le sezioni core).

| Setting | Tipo | Default | Descrizione |
|---------|------|---------|-------------|
| `social_instagram` | URL | vuoto | URL profilo Instagram |
| `social_facebook` | URL | vuoto | URL pagina Facebook |
| `social_tiktok` | URL | vuoto | URL profilo TikTok |
| `social_youtube` | URL | vuoto | URL canale YouTube |

Le icone appaiono nel footer **solo se l'URL è compilato**. Se vuoto → icona nascosta.

### Opzioni Tema (`4zampe_theme`)

Priorità: 125.

| Setting | Tipo | Transport | Default | Descrizione |
|---------|------|-----------|---------|-------------|
| `cta_url` | URL | refresh | vuoto | Override URL pulsante "Contattaci" |
| `footer_tagline` | textarea | postMessage | testo default | Tagline sotto logo nel footer |
| `newsletter_heading` | text | postMessage | testo default | Titolo sezione newsletter nel footer |

---

## Usare i valori nei template

```php
// Recupero diretto
$ig_url     = get_theme_mod('social_instagram', '');
$tagline    = get_theme_mod('footer_tagline', 'Default tagline');
$newsletter = get_theme_mod('newsletter_heading', '');

// Helper per CTA (con fallback)
$cta_url = \App\theme_cta_url();
// → restituisce il valore customizer o home_url('/contatti')
```

In Blade:

```blade
@php
  $tagline = get_theme_mod('footer_tagline', '');
  $cta_url = function_exists('App\\theme_cta_url')
    ? \App\theme_cta_url()
    : esc_url(home_url('/contatti'));
@endphp

<p>{{ esc_html($tagline) }}</p>
<a href="{{ $cta_url }}">Contattaci</a>
```

---

## Aggiungere nuove opzioni Customizer

### Esempio: aggiungere un campo "Telefono" nel footer

```php
// In app/customizer.php, dentro il callback customize_register

$wp_customize->add_setting('footer_phone', [
    'default'           => '',
    'sanitize_callback' => 'sanitize_text_field',
    'transport'         => 'postMessage',
]);
$wp_customize->add_control('footer_phone', [
    'label'    => __('Telefono footer', 'sage'),
    'section'  => '4zampe_theme',
    'type'     => 'text',
    'priority' => 40,
]);
```

Poi in Blade:
```blade
@php $phone = get_theme_mod('footer_phone', '') @endphp
@if($phone)
  <a href="tel:{{ esc_attr($phone) }}">{{ esc_html($phone) }}</a>
@endif
```

### Esempio: aggiungere un'immagine (logo alternativo)

```php
$wp_customize->add_setting('logo_dark', [
    'default'           => '',
    'sanitize_callback' => 'absint',
]);
$wp_customize->add_control(new \WP_Customize_Media_Control($wp_customize, 'logo_dark', [
    'label'     => __('Logo versione scura', 'sage'),
    'section'   => '4zampe_theme',
    'mime_type' => 'image',
    'priority'  => 5,
]));
```

In Blade:
```blade
@php
  $logo_dark_id  = get_theme_mod('logo_dark');
  $logo_dark_url = $logo_dark_id ? wp_get_attachment_image_url($logo_dark_id, 'full') : '';
@endphp
@if($logo_dark_url)
  <img src="{{ esc_url($logo_dark_url) }}" alt="{{ get_bloginfo('name') }}">
@endif
```

---

## Transport: `refresh` vs `postMessage`

| Transport | Comportamento | Usa per |
|-----------|--------------|---------|
| `refresh` | Ricarica l'anteprima | URL, impostazioni complesse |
| `postMessage` | Aggiorna istantaneamente via JS | Testi, colori, toggle visibilità |

Per abilitare `postMessage` su un setting, devi anche registrare la logica JS in `resources/js/editor.js` o in uno script dedicato:

```js
// resources/js/customizer.js
wp.customize('footer_tagline', (value) => {
    value.bind((newVal) => {
        document.querySelector('.footer-tagline').textContent = newVal;
    });
});
```

---

## Aggiungere una nuova sezione Customizer

```php
$wp_customize->add_section('mio_sito_header', [
    'title'       => __('Header Options', 'sage'),
    'description' => __('Opzioni header avanzate.', 'sage'),
    'priority'    => 100,
]);
```

---

## Panel per raggruppare sezioni

Per siti complessi con molte opzioni:

```php
$wp_customize->add_panel('mio_sito_options', [
    'title'    => __('Opzioni Tema', 'sage'),
    'priority' => 200,
]);

$wp_customize->add_section('mio_sito_header', [
    'title' => __('Header', 'sage'),
    'panel' => 'mio_sito_options',
]);

$wp_customize->add_section('mio_sito_footer', [
    'title' => __('Footer', 'sage'),
    'panel' => 'mio_sito_options',
]);
```

---

## Tipi di controllo disponibili

```php
// Text
'type' => 'text'

// Textarea
'type' => 'textarea'

// URL
'type' => 'url'

// Checkbox
'type' => 'checkbox'

// Select
'type' => 'select',
'choices' => ['val1' => 'Label 1', 'val2' => 'Label 2']

// Radio
'type' => 'radio',
'choices' => [...]

// Colore
new \WP_Customize_Color_Control(...)

// Immagine
new \WP_Customize_Image_Control(...)

// Media (qualsiasi tipo file)
new \WP_Customize_Media_Control(...)
```
