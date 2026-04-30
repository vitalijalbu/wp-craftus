<?php
/**
 * Single product image — Swiper gallery with Thumbs + Alpine.js lightbox.
 * Overrides WooCommerce default template.
 *
 * @version 10.5.0 (WC reference version)
 */
defined('ABSPATH') || exit;

global $product;

$attachment_ids = $product->get_gallery_image_ids();
$main_id = $product->get_image_id();
$all_ids = array_merge([$main_id], $attachment_ids);
$all_ids = array_filter(array_unique($all_ids));
$has_gallery = count($all_ids) > 1;
$has_images = ! empty($all_ids);

// Build lightbox URLs (full resolution) for Alpine.js
$lightbox_urls = [];
foreach ($all_ids as $img_id) {
    $lightbox_urls[] = wp_get_attachment_image_url($img_id, 'full');
}
$lightbox_urls_json = wp_json_encode(array_values(array_filter($lightbox_urls)));
$product_title = esc_attr($product->get_name());
?>

<div
  class="product-gallery group"
  data-product-id="<?php echo esc_attr($product->get_id()); ?>"
  data-lightbox-images="<?php echo esc_attr($lightbox_urls_json); ?>"
  x-data="productLightbox($el.dataset.lightboxImages)"
  @keydown.escape.window="close()"
  @keydown.arrow-left.window="open && prev()"
  @keydown.arrow-right.window="open && next()"
  @keydown.tab.window="trapFocus($event)"
>

  <?php if (! $has_images) { ?>
    <div class="product-gallery__main aspect-square flex flex-col items-center justify-center bg-surface-alt text-border">
      <?php echo \Roots\view('components.icons.image-placeholder', ['attributes' => new \Illuminate\View\ComponentAttributeBag(['class' => 'w-16 h-16 mb-2', 'stroke-width' => '1'])])->render(); ?>
      <span class="text-xs text-muted tracking-wider uppercase"><?php esc_html_e('Immagine non disponibile', 'sage'); ?></span>
    </div>
  <?php } else { ?>

    <!-- Main Swiper -->
    <div class="js-product-gallery swiper product-gallery__main overflow-hidden rounded-2xl" aria-label="<?php esc_attr_e('Galleria immagini prodotto', 'sage'); ?>">
      <div class="swiper-wrapper">
        <?php foreach (array_values($all_ids) as $index => $img_id) {
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
                  class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-[1.02] rounded-2xl"
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
          <?php echo \Roots\view('components.icons.arrow-left', ['attributes' => new \Illuminate\View\ComponentAttributeBag(['class' => 'size-5'])])->render(); ?>
        </button>
        <button type="button" class="product-gallery__next" aria-label="<?php esc_attr_e('Immagine successiva', 'sage'); ?>">
          <?php echo \Roots\view('components.icons.arrow-right', ['attributes' => new \Illuminate\View\ComponentAttributeBag(['class' => 'size-5'])])->render(); ?>
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
              $alt = esc_attr(get_post_meta($img_id, '_wp_attachment_image_alt', true) ?: get_the_title($img_id));
              ?>
            <div class="swiper-slide">
              <button type="button" class="product-gallery__thumb overflow-hidden rounded-xl" aria-label="<?php printf(esc_attr__('Immagine %d', 'sage'), $index + 1); ?>">
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
        <?php echo \Roots\view('components.icons.x-mark', ['attributes' => new \Illuminate\View\ComponentAttributeBag(['class' => 'size-5', 'stroke-width' => '1.5'])])->render(); ?>
      </button>

      <!-- Prev -->
      <button
        x-ref="lbPrev"
        @click="prev()"
        class="btn btn-icon btn-light absolute left-4 md:left-6 z-10"
        aria-label="<?php esc_attr_e('Immagine precedente', 'sage'); ?>"
        x-show="images.length > 1"
      >
        <?php echo \Roots\view('components.icons.arrow-left', ['attributes' => new \Illuminate\View\ComponentAttributeBag(['class' => 'size-5'])])->render(); ?>
      </button>

      <!-- Lightbox Swiper -->
      <div class="max-w-5xl max-h-[90vh] w-full px-16 md:px-20 flex items-center justify-center">
        <div x-ref="lbSwiper" class="js-product-lightbox swiper w-full h-full">
          <div class="swiper-wrapper">
            <template x-for="(img, i) in images" :key="i">
              <div class="swiper-slide flex items-center justify-center">
                <img
                  :src="img"
                  :alt="'<?php echo $product_title; ?> — ' + (i + 1)"
                  class="max-h-[85vh] max-w-full object-contain shadow-2xl"
                  width="1200"
                  height="1500"
                >
              </div>
            </template>
          </div>
        </div>
      </div>

      <!-- Next -->
      <button
        x-ref="lbNext"
        @click="next()"
        class="btn btn-icon btn-light absolute right-4 md:right-6 z-10"
        aria-label="<?php esc_attr_e('Immagine successiva', 'sage'); ?>"
        x-show="images.length > 1"
      >
        <?php echo \Roots\view('components.icons.arrow-right', ['attributes' => new \Illuminate\View\ComponentAttributeBag(['class' => 'size-5'])])->render(); ?>
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
