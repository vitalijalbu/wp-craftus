<?php
/**
 * Related Products — overrides WooCommerce default.
 * Uses the theme's product-card partial for consistent styling.
 *
 * @version 10.3.0 (WC reference version)
 */
defined('ABSPATH') || exit;

if (empty($related_products)) {
    return;
}
?>

<section
  class="related-products section bg-cream"
  aria-label="<?php esc_attr_e('Prodotti correlati', 'sage'); ?>"
>
  <div class="container">

    <div class="mb-10">
      <span class="section-label text-muted"><?php esc_html_e('Potrebbe interessarti', 'sage'); ?></span>
      <h2 class="section-title text-ink"><?php esc_html_e('Prodotti correlati', 'sage'); ?></h2>
    </div>

    <ul
      class="grid grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8"
      role="list"
      data-scroll="stagger"
    >
      <?php foreach ($related_products as $related_product) {
          $post_object = get_post($related_product->get_id());
          setup_postdata($GLOBALS['post'] = $post_object);
          $product = wc_get_product($related_product->get_id());
          if (! $product) {
              continue;
          }
          ?>
        <li data-scroll-item>
          <?php
                echo \Roots\view('partials.product-card', [
                    'product' => $product,
                ])->render();
          ?>
        </li>
      <?php } ?>
    </ul>

  </div>
</section>

<?php wp_reset_postdata(); ?>
