<?php
/**
 * Single Product tabs — overrides WooCommerce default.
 * Renders product tabs (Description, Reviews, Additional Info) as Alpine.js tabs.
 *
 * @version 9.8.0 (WC reference version)
 */
defined('ABSPATH') || exit;

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array: [title, priority, callback, id (optional)].
 */
$product_tabs = apply_filters('woocommerce_product_tabs', []);

if (empty($product_tabs)) {
    return;
}

$tab_keys = array_keys($product_tabs);
$first = $tab_keys[0];
?>

<section
  class="product-tabs mt-12 lg:mt-16"
  x-data="{ active: '<?php echo esc_js($first); ?>' }"
  aria-label="<?php esc_attr_e('Informazioni prodotto', 'sage'); ?>"
>

  {{-- Tab list --}}
  <div
    class="flex gap-0 border-b border-border"
    role="tablist"
    aria-label="<?php esc_attr_e('Schede informazioni', 'sage'); ?>"
  >
    <?php foreach ($product_tabs as $key => $tab) {
        $tab_id = 'tab-'.esc_attr($key);
        $panel_id = 'panel-'.esc_attr($key);
        ?>
      <button
        type="button"
        id="<?php echo $tab_id; ?>"
        role="tab"
        :aria-selected="(active === '<?php echo esc_js($key); ?>').toString()"
        aria-controls="<?php echo $panel_id; ?>"
        class="product-tab-btn"
        :class="active === '<?php echo esc_js($key); ?>'
          ? 'product-tab-btn--active'
          : ''"
        @click="active = '<?php echo esc_js($key); ?>'"
        @keydown.arrow-right.prevent="
          const keys = <?php echo json_encode($tab_keys); ?>;
          const idx = keys.indexOf(active);
          active = keys[(idx + 1) % keys.length];
          $nextTick(() => $el.parentElement.querySelector('[aria-selected=true]').focus());
        "
        @keydown.arrow-left.prevent="
          const keys = <?php echo json_encode($tab_keys); ?>;
          const idx = keys.indexOf(active);
          active = keys[(idx - 1 + keys.length) % keys.length];
          $nextTick(() => $el.parentElement.querySelector('[aria-selected=true]').focus());
        "
      >
        <?php echo wp_kses_post($tab['title']); ?>
      </button>
    <?php } ?>
  </div>

  {{-- Tab panels --}}
  <?php foreach ($product_tabs as $key => $tab) {
      $panel_id = 'panel-'.esc_attr($key);
      $tab_id = 'tab-'.esc_attr($key);
      ?>
    <div
      id="<?php echo $panel_id; ?>"
      role="tabpanel"
      aria-labelledby="<?php echo $tab_id; ?>"
      :hidden="active !== '<?php echo esc_js($key); ?>'"
      class="product-tab-panel prose max-w-none py-8 lg:py-10"
    >
      <?php
            if (isset($tab['callback'])) {
                call_user_func($tab['callback'], $key, $tab);
            }
      ?>
    </div>
  <?php } ?>

</section>
