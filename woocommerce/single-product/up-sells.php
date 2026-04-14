<?php
/**
 * Up-sells — overrides WooCommerce default.
 * Rendered below the product summary on single product pages.
 * Uses the theme's product-card partial for consistent styling.
 *
 * @version 9.6.0 (WC reference version)
 */
defined('ABSPATH') || exit;

if (empty($upsells)) {
    return;
}
?>

<section
  class="upsells section bg-surface"
  aria-label="<?php esc_attr_e('Potresti apprezzare', 'sage'); ?>"
>
  <div class="container">

    <div class="mb-10">
      <span class="section-label text-muted"><?php esc_html_e('Upgrade', 'sage'); ?></span>
      <h2 class="section-title text-ink"><?php esc_html_e('Potresti apprezzare', 'sage'); ?></h2>
    </div>

    <ul
      class="grid grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8"
      role="list"
      data-scroll="stagger"
    >
      <?php foreach ($upsells as $upsell) {
          $post_object = get_post($upsell->get_id());
          setup_postdata($GLOBALS['post'] = $post_object);
          $product = wc_get_product($upsell->get_id());
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
