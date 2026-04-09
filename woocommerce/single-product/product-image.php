<?php
/**
 * Single product image — Swiper gallery with Thumbs + Alpine.js lightbox.
 * Overrides WooCommerce default template.
 *
 * @version 3.5.1 (WC reference version)
 */
defined('ABSPATH') || exit;

global $product;

$attachment_ids = $product->get_gallery_image_ids();
$main_id        = $product->get_image_id();
$all_ids        = array_merge([$main_id], $attachment_ids);
$all_ids        = array_filter(array_unique($all_ids));
$has_gallery    = count($all_ids) > 1;
$has_images     = ! empty($all_ids);

// Build lightbox URLs (full resolution) for Alpine.js
$lightbox_urls = [];
foreach ($all_ids as $img_id) {
    $lightbox_urls[] = wp_get_attachment_image_url($img_id, 'full');
}
$lightbox_urls_json = wp_json_encode(array_values(array_filter($lightbox_urls)));
$product_title      = esc_attr($product->get_name());
?>

<div
  class="product-gallery group"
  data-product-id="<?php echo esc_attr($product->get_id()); ?>"
  x-data="{
    open: false,
    current: 0,
    images: <?php echo $lightbox_urls_json; ?>,
    _trigger: null,
    show(i, el) {
        this._trigger = el ?? null;
        this.current  = i;
        this.open     = true;
        document.body.style.overflow = 'hidden';
        this.$nextTick(() => this.$refs.lbClose?.focus());
    },
    close() {
        this.open = false;
        document.body.style.overflow = '';
        this.$nextTick(() => this._trigger?.focus());
    },
    prev() { this.current = (this.current - 1 + this.images.length) % this.images.length; },
    next() { this.current = (this.current + 1) % this.images.length; },
    trapFocus(e) {
        if (!this.open) return;
        const el = this.$refs.lbDialog;
        const focusable = Array.from(el.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex=\'-1\'])'
        )).filter(n => !n.hasAttribute('disabled'));
        if (!focusable.length) return;
        const first = focusable[0], last = focusable[focusable.length - 1];
        if (e.shiftKey) { if (document.activeElement === first) { e.preventDefault(); last.focus(); } }
        else            { if (document.activeElement === last)  { e.preventDefault(); first.focus(); } }
    },
  }"
  @keydown.escape.window="close()"
  @keydown.arrow-left.window="open && prev()"
  @keydown.arrow-right.window="open && next()"
  @keydown.tab.window="trapFocus($event)"
>

  <?php if (! $has_images) { ?>
    <div class="product-gallery__main aspect-square flex flex-col items-center justify-center bg-surface-alt text-border">
      <svg class="w-16 h-16 mb-2" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Z"/>
      </svg>
      <span class="text-xs text-muted tracking-wider uppercase"><?php esc_html_e('Immagine non disponibile', 'sage'); ?></span>
    </div>
  <?php } else { ?>

    <!-- Main Swiper -->
    <div class="js-product-gallery swiper product-gallery__main overflow-hidden" aria-label="<?php esc_attr_e('Galleria immagini prodotto', 'sage'); ?>">
      <div class="swiper-wrapper">
        <?php foreach (array_values($all_ids) as $index => $img_id) {
            $full_url  = wp_get_attachment_image_url($img_id, 'woocommerce_single');
            $srcset    = wp_get_attachment_image_srcset($img_id, 'woocommerce_single');
            $alt       = esc_attr(get_post_meta($img_id, '_wp_attachment_image_alt', true) ?: get_the_title($img_id));

            // Build WebP srcset
            $meta        = wp_get_attachment_metadata($img_id);
            $upload      = wp_get_upload_dir();
            $webp_srcset = '';
            if (! empty($meta['file']) && ! empty($meta['sizes'])) {
                $file_dir    = $upload['baseurl'] . '/' . dirname($meta['file']);
                $webp_parts  = [];
                foreach ($meta['sizes'] as $size_data) {
                    $wf = $size_data['sources']['image/webp']['file'] ?? '';
                    $ww = (int) ($size_data['width'] ?? 0);
                    if ($wf && $ww) {
                        $webp_parts[] = esc_url($file_dir . '/' . $wf) . ' ' . $ww . 'w';
                    }
                }
                $webp_srcset = implode(', ', $webp_parts);
            }
        ?>
          <div class="swiper-slide">
            <button
              type="button"
              @click="show(<?php echo $index; ?>, $el)"
              class="block w-full aspect-square overflow-hidden cursor-zoom-in"
              aria-label="<?php printf(esc_attr__('Ingrandisci immagine %d', 'sage'), $index + 1); ?>"
            >
              <picture>
                <?php if ($webp_srcset) { ?>
                  <source type="image/webp" srcset="<?php echo esc_attr($webp_srcset); ?>" sizes="(max-width: 768px) 100vw, 50vw">
                <?php } ?>
                <?php if ($srcset) { ?>
                  <source srcset="<?php echo esc_attr($srcset); ?>" sizes="(max-width: 768px) 100vw, 50vw">
                <?php } ?>
                <img
                  src="<?php echo esc_url($full_url); ?>"
                  alt="<?php echo $alt; ?>"
                  class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-[1.02]"
                  <?php echo $index === 0 ? 'loading="eager" fetchpriority="high"' : 'loading="lazy"'; ?>
                  decoding="async"
                >
              </picture>
            </button>
          </div>
        <?php } ?>
      </div>

      <?php if ($has_gallery) { ?>
        <!-- Navigation arrows -->
        <button type="button" class="product-gallery__prev" aria-label="<?php esc_attr_e('Immagine precedente', 'sage'); ?>">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
        </button>
        <button type="button" class="product-gallery__next" aria-label="<?php esc_attr_e('Immagine successiva', 'sage'); ?>">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
        </button>

        <!-- Slide counter -->
        <div class="product-gallery__counter">
          <span class="js-gallery-current">1</span> / <?php echo count($all_ids); ?>
        </div>
      <?php } ?>
    </div>

    <?php if ($has_gallery) { ?>
      <!-- Thumbs Swiper -->
      <div class="js-product-thumbs swiper product-gallery__thumbs mt-3" aria-label="<?php esc_attr_e('Miniature prodotto', 'sage'); ?>">
        <div class="swiper-wrapper">
          <?php foreach (array_values($all_ids) as $index => $img_id) {
              $thumb_url = wp_get_attachment_image_url($img_id, 'thumbnail');
              $alt       = esc_attr(get_post_meta($img_id, '_wp_attachment_image_alt', true) ?: get_the_title($img_id));
          ?>
            <div class="swiper-slide">
              <button type="button" class="product-gallery__thumb" aria-label="<?php printf(esc_attr__('Immagine %d', 'sage'), $index + 1); ?>">
                <img
                  src="<?php echo esc_url($thumb_url); ?>"
                  alt="<?php echo $alt; ?>"
                  class="w-full h-full object-cover"
                  loading="lazy"
                  decoding="async"
                >
              </button>
            </div>
          <?php } ?>
        </div>
      </div>
    <?php } ?>

    <!-- Lightbox overlay -->
    <div
      x-ref="lbDialog"
      x-show="open"
      x-transition:enter="transition ease-out duration-200"
      x-transition:enter-start="opacity-0"
      x-transition:enter-end="opacity-100"
      x-transition:leave="transition ease-in duration-200"
      x-transition:leave-start="opacity-100"
      x-transition:leave-end="opacity-0"
      class="fixed inset-0 z-200 flex items-center justify-center bg-ink/92 backdrop-blur-sm"
      role="dialog"
      aria-modal="true"
      :aria-label="'<?php echo $product_title; ?> — ' + (current + 1) + ' / ' + images.length"
      @click.self="close()"
      style="display:none;"
    >
      <!-- Close -->
      <button
        x-ref="lbClose"
        @click="close()"
        class="btn btn-icon btn-light absolute top-5 right-5 z-10"
        aria-label="<?php esc_attr_e('Chiudi', 'sage'); ?>"
      >
        <svg class="size-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
      </button>

      <!-- Prev -->
      <button
        @click="prev()"
        class="btn btn-icon btn-light absolute left-4 md:left-6 z-10"
        aria-label="<?php esc_attr_e('Immagine precedente', 'sage'); ?>"
        x-show="images.length > 1"
      >
        <svg class="size-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
      </button>

      <!-- Image -->
      <div class="max-w-5xl max-h-[90vh] w-full px-16 md:px-20 flex items-center justify-center">
        <template x-for="(img, i) in images" :key="i">
          <img
            x-show="current === i"
            :src="img"
            :alt="'<?php echo $product_title; ?> — ' + (i + 1)"
            class="max-h-[85vh] max-w-full object-contain shadow-2xl"
            width="1200"
            height="1500"
          >
        </template>
      </div>

      <!-- Next -->
      <button
        @click="next()"
        class="btn btn-icon btn-light absolute right-4 md:right-6 z-10"
        aria-label="<?php esc_attr_e('Immagine successiva', 'sage'); ?>"
        x-show="images.length > 1"
      >
        <svg class="size-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
      </button>

      <!-- Counter -->
      <p
        class="absolute bottom-5 left-1/2 -translate-x-1/2 text-white/50 tracking-widest text-sm"
        aria-live="polite"
        x-show="images.length > 1"
        x-text="(current + 1) + ' / ' + images.length"
      ></p>
    </div>

  <?php } ?>
</div>
