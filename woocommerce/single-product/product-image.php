<?php
/**
 * Single product image — overrides WooCommerce default.
 * Uses <picture> component via wp_get_attachment_image with srcset/WebP.
 *
 * @version 3.5.1 (WC reference version)
 */
defined('ABSPATH') || exit;

global $product;

$attachment_ids = $product->get_gallery_image_ids();
$main_id = $product->get_image_id();
$all_ids = array_merge([$main_id], $attachment_ids);
$all_ids = array_filter(array_unique($all_ids));
$has_gallery = count($all_ids) > 1;
?>

<div
  class="product-gallery"
  x-data="productGallery()"
  data-product-id="<?php echo esc_attr($product->get_id()); ?>"
>

  {{-- Main image --}}
  <div
    class="product-gallery__main relative overflow-hidden aspect-[4/5] mb-3"
    role="img"
    :aria-label="currentAlt"
  >
    <?php foreach ($all_ids as $index => $img_id) {
        $full_url = wp_get_attachment_image_url($img_id, 'woocommerce_single');
        $srcset = wp_get_attachment_image_srcset($img_id, 'woocommerce_single');
        $alt = esc_attr(get_post_meta($img_id, '_wp_attachment_image_alt', true) ?: get_the_title($img_id));

        // Build WebP srcset
        $meta = wp_get_attachment_metadata($img_id);
        $upload = wp_get_upload_dir();
        $webp_srcset = '';
        if (! empty($meta['file']) && ! empty($meta['sizes'])) {
            $file_dir = $upload['baseurl'].'/'.dirname($meta['file']);
            $webp_parts = [];
            foreach ($meta['sizes'] as $size_data) {
                $wf = $size_data['sources']['image/webp']['file'] ?? '';
                $ww = (int) ($size_data['width'] ?? 0);
                if ($wf && $ww) {
                    $webp_parts[] = esc_url($file_dir.'/'.$wf).' '.$ww.'w';
                }
            }
            $webp_srcset = implode(', ', $webp_parts);
        }
        ?>
      <picture
        class="absolute inset-0 w-full h-full transition-opacity duration-400"
        :class="active === <?php echo $index; ?> ? 'opacity-100' : 'opacity-0 pointer-events-none'"
        :aria-hidden="active !== <?php echo $index; ?>"
        data-alt="<?php echo $alt; ?>"
      >
        <?php if ($webp_srcset) { ?>
          <source type="image/webp" srcset="<?php echo esc_attr($webp_srcset); ?>" sizes="(max-width: 768px) 100vw, 50vw">
        <?php } ?>
        <?php if ($srcset) { ?>
          <source srcset="<?php echo esc_attr($srcset); ?>" sizes="(max-width: 768px) 100vw, 50vw">
        <?php } ?>
        <img
          src="<?php echo esc_url($full_url); ?>"
          alt="<?php echo $alt; ?>"
          class="w-full h-full object-cover"
          <?php echo $index === 0 ? 'loading="eager" fetchpriority="high"' : 'loading="lazy"'; ?>
          decoding="async"
        >
      </picture>
    <?php } ?>

    <?php if ($has_gallery) { ?>
      {{-- Prev / Next arrows --}}
      <button
        type="button"
        @click="prev()"
        class="absolute left-3 top-1/2 -translate-y-1/2 w-9 h-9 bg-surface/80 backdrop-blur-sm flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity hover:bg-surface"
        aria-label="<?php esc_attr_e('Immagine precedente', 'sage'); ?>"
      >
        <svg class="w-4 h-4 text-ink" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
      </button>
      <button
        type="button"
        @click="next()"
        class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 bg-surface/80 backdrop-blur-sm flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity hover:bg-surface"
        aria-label="<?php esc_attr_e('Immagine successiva', 'sage'); ?>"
      >
        <svg class="w-4 h-4 text-ink" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
      </button>
    <?php } ?>
  </div>

  <?php if ($has_gallery) { ?>
    {{-- Thumbnails strip --}}
    <div
      class="product-gallery__thumbs grid gap-2"
      style="grid-template-columns: repeat(<?php echo min(count($all_ids), 5); ?>, 1fr)"
      role="list"
      aria-label="<?php esc_attr_e('Galleria immagini prodotto', 'sage'); ?>"
    >
      <?php foreach ($all_ids as $index => $img_id) {
          $thumb_url = wp_get_attachment_image_url($img_id, 'thumbnail');
          $alt = esc_attr(get_post_meta($img_id, '_wp_attachment_image_alt', true) ?: get_the_title($img_id));
          ?>
        <button
          type="button"
          @click="setActive(<?php echo $index; ?>)"
          class="product-gallery__thumb aspect-square overflow-hidden border transition-colors"
          :class="active === <?php echo $index; ?> ? 'border-ink' : 'border-transparent hover:border-border'"
          :aria-pressed="(active === <?php echo $index; ?>).toString()"
          aria-label="<?php printf(esc_attr__('Immagine %d', 'sage'), $index + 1); ?>"
          role="listitem"
        >
          <img
            src="<?php echo esc_url($thumb_url); ?>"
            alt="<?php echo $alt; ?>"
            class="w-full h-full object-cover"
            loading="lazy"
            decoding="async"
          >
        </button>
      <?php } ?>
    </div>
  <?php } ?>

</div>

<script>
document.addEventListener('alpine:init', () => {
  Alpine.data('productGallery', () => ({
    active: 0,
    total: <?php echo count($all_ids); ?>,
    get currentAlt() {
      const el = this.$el.querySelector(`[data-alt]`);
      const pics = this.$el.querySelectorAll('picture[data-alt]');
      return pics[this.active]?.dataset.alt ?? '';
    },
    setActive(index) { this.active = index; },
    next() { this.active = (this.active + 1) % this.total; },
    prev() { this.active = (this.active - 1 + this.total) % this.total; },
  }));
});
</script>
