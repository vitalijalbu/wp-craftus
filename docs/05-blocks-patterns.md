# 05 — Block Patterns

I pattern sono layout Gutenberg precostruiti disponibili nell'inseritore blocchi. Risiedono in `patterns/` come file PHP.

---

## Pattern registrati

I default core patterns sono **disabilitati** (`remove_theme_support('core-block-patterns')`) per mantenere solo i pattern del tema.

### Categorie

Registrate in `app/setup.php`:

| ID | Label admin | Descrizione |
|----|-------------|-------------|
| `4zampe-sections` | 4 Zampe – Sezioni | Hero, CTA, intro, layout |
| `4zampe-cards` | 4 Zampe – Card | Prodotti, servizi, blog |
| `4zampe-carousel` | 4 Zampe – Carousel | Slider e caroselli |

### Elenco pattern disponibili

| File | Slug | Categoria | Descrizione |
|------|------|-----------|-------------|
| `hero.php` | `4zampe/hero` | sections | Hero con sfondo, overlay, 2 CTA |
| `cta-banner.php` | `4zampe/cta-banner` | sections | Banner CTA con sfondo scuro |
| `stats.php` | `4zampe/stats` | sections | Contatori/statistiche a griglia |
| `testimonials.php` | `4zampe/testimonials` | sections | Citazioni clienti |
| `services-grid.php` | `4zampe/services-grid` | sections | Griglia 3 col con icone |
| `media-text.php` | `4zampe/media-text` | sections | Immagine a sinistra + testo |
| `media-text-right.php` | `4zampe/media-text-right` | sections | Immagine a destra + testo |
| `brand-logos.php` | `4zampe/brand-logos` | sections | Loghi partner/brand |
| `logos-grid.php` | `4zampe/logos-grid` | sections | Griglia loghi |
| `portfolio-grid.php` | `4zampe/portfolio-grid` | sections | Griglia portfolio |
| `contact-section.php` | `4zampe/contact-section` | sections | Sezione contatti |
| `intro-two-cols.php` | `4zampe/intro-two-cols` | sections | Intro 2 colonne |
| `page-hero.php` | `4zampe/page-hero` | sections | Hero interno pagine |
| `shop-hero.php` | `4zampe/shop-hero` | sections | Hero shop WooCommerce |
| `product-categories.php` | `4zampe/product-categories` | sections | Categorie prodotto |
| `usp-band.php` | `4zampe/usp-band` | sections | Barra USP/benefit |
| `newsletter-cta.php` | `4zampe/newsletter-cta` | sections | CTA newsletter |

---

## Struttura di un pattern

```php
<?php
/**
 * Title: Nome Visibile in Admin
 * Slug: namespace/slug-unico
 * Categories: categoria-id
 * Keywords: keyword1, keyword2
 * Description: Breve descrizione del pattern.
 * Block Types: core/cover          (opzionale: blocco trigger)
 * Viewport Width: 1440             (anteprima larghezza)
 */
?>
<!-- wp:cover { ... } -->
<div class="wp-block-cover">
  <!-- ... blocchi annidati ... -->
</div>
<!-- /wp:cover -->
```

---

## Creare un nuovo pattern

### 1. Crea il file

`patterns/mio-pattern.php`

```php
<?php
/**
 * Title: La Mia Sezione
 * Slug: theme/mia-sezione
 * Categories: 4zampe-sections
 * Keywords: sezione, custom
 * Viewport Width: 1440
 */
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull">

  <!-- wp:heading {"textAlign":"center","level":2} -->
  <h2 class="wp-block-heading has-text-align-center">Titolo Sezione</h2>
  <!-- /wp:heading -->

  <!-- wp:paragraph {"align":"center"} -->
  <p class="has-text-align-center">Descrizione della sezione.</p>
  <!-- /wp:paragraph -->

</div>
<!-- /wp:group -->
```

### 2. Ottieni il markup da Gutenberg

Il modo più rapido per ottenere il markup corretto è:
1. Costruisci il layout nell'editor
2. Passa alla vista **Codice** (⇧⌥⌘M o hamburger menu → Editor codice)
3. Copia il markup e incollalo nel file `.php`
4. Sostituisci i contenuti segnaposto

### 3. Il pattern appare automaticamente

WordPress scansiona la cartella `patterns/` e registra tutti i file trovati. Nessuna registrazione manuale necessaria.

---

## Usare i token del design system nei pattern

### Colori

```html
<!-- Colore da palette -->
class="has-primary-color has-text-color"
class="has-dark-background-color has-background"

<!-- Come attributo JSON del blocco -->
{"textColor":"primary","backgroundColor":"dark"}
```

### Tipografia

```html
class="has-hero-font-size"
class="has-serif-font-family"
class="has-sans-font-family"

<!-- Attributo JSON -->
{"fontSize":"hero","fontFamily":"serif"}
```

### Spacing (preset)

```html
{"style":{"spacing":{"padding":{"top":"var:preset|spacing|80"}}}}
```

### Larghezze layout

```html
{"layout":{"type":"constrained"}}           <!-- 1200px contentSize -->
{"align":"wide"}                             <!-- 1440px wideSize -->
{"align":"full"}                             <!-- 100vw -->
```

---

## Disabilitare pattern specifici di plugin

Se un plugin registra pattern indesiderati:

```php
// In app/setup.php o filters.php
add_action('init', function () {
    unregister_block_pattern('woocommerce/product-query');
    unregister_block_pattern('jetpack/contact-form');
}, 20);
```

---

## Registrare categorie pattern aggiuntive

```php
// In app/setup.php dentro after_setup_theme
register_block_pattern_category('mio-sito-blog', [
    'label'       => __('Blog Posts', 'sage'),
    'description' => __('Layout per articoli e archivi blog.', 'sage'),
]);
```

---

## Pattern con contenuto dinamico (PHP)

I pattern PHP possono contenere logica PHP per dati dinamici:

```php
<?php
/**
 * Title: Ultimi Prodotti
 * Slug: theme/ultimi-prodotti
 * Categories: 4zampe-sections
 */

$products = wc_get_products(['limit' => 3, 'status' => 'publish']);
?>
<!-- wp:group {"align":"full"} -->
<div class="wp-block-group alignfull">
  <?php foreach ($products as $product): ?>
    <!-- wp:paragraph -->
    <p><?php echo esc_html($product->get_name()); ?></p>
    <!-- /wp:paragraph -->
  <?php endforeach; ?>
</div>
<!-- /wp:group -->
```

> **Attenzione:** i pattern con PHP vengono eseguiti solo al momento della registrazione (inserimento pattern), non ad ogni rendering. Per contenuto sempre aggiornato usa i blocchi dinamici WooCommerce o custom.
