<?php
/**
 * Cross-sells — overrides WooCommerce default.
 * Displayed in the cart page below the cart table.
 * Uses the theme's product-card partial for consistent styling.
 *
 * @version 3.0.0 (WC reference version)
 */
defined('ABSPATH') || exit;

if (empty($cross_sells)) {
    return;
}
?>

<section
  class="cross-sells section-luxury bg-surface-alt"
  aria-label="<?php esc_attr_e('Completa il tuo ordine', 'sage'); ?>"
>
  <div class="container">

    <div class="mb-10">
      <span class="section-label text-muted"><?php esc_html_e('Spesso acquistati insieme', 'sage'); ?></span>
      <h2 class="section-title text-ink"><?php esc_html_e('Completa il tuo ordine', 'sage'); ?></h2>
    </div>

    <ul
      class="grid grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8"
      role="list"
    >
      <?php
      $cross_sells_limit = apply_filters('woocommerce_cross_sells_total', $cross_sells_columns ?? 4);
      $shown = 0;
      foreach ($cross_sells as $cross_sell) {
          if ($cross_sells_limit > 0 && $shown >= $cross_sells_limit) {
              break;
          }
          $post_object = get_post($cross_sell->get_id());
          setup_postdata($GLOBALS['post'] = $post_object);
          $product = wc_get_product($cross_sell->get_id());
          if (! $product) {
              continue;
          }
          $shown++;
          ?>
        <li>
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
