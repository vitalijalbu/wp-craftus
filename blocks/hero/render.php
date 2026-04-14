<?php
/**
 * Block: theme/hero
 * Render template.
 *
 * @var array $attributes  Block attributes.
 * @var string $content     Inner blocks HTML (unused).
 * @var WP_Block $block       Block instance.
 */
$image_id = (int) ($attributes['imageId'] ?? 0);
$image_url = esc_url($attributes['imageUrl'] ?? ($image_id ? wp_get_attachment_image_url($image_id, 'full') : '')) ?: esc_url(\App\get_placeholder_url());
$image_alt = esc_attr($attributes['imageAlt'] ?? ($image_id ? get_post_meta($image_id, '_wp_attachment_image_alt', true) : ''));
$label = esc_html($attributes['label'] ?? '');
$heading = wp_kses_post($attributes['heading'] ?? '');
$subtext = esc_html($attributes['subtext'] ?? '');
$cta_label = esc_html($attributes['ctaLabel'] ?? __('Scopri di più', 'sage'));
$cta_url = esc_url($attributes['ctaUrl'] ?? home_url('/'));
$cta2_label = esc_html($attributes['cta2Label'] ?? '');
$cta2_url = esc_url($attributes['cta2Url'] ?? '');
$opacity = (int) ($attributes['overlayOpacity'] ?? 40);
$min_height = esc_attr($attributes['minHeight'] ?? '100svh');
$align = $attributes['contentAlign'] ?? 'center';
$scroll_effect = esc_attr($attributes['scrollEffect'] ?? '');

$align_class = match ($align) {
    'left' => 'items-start text-left',
    'right' => 'items-end text-right',
    default => 'items-center text-center',
};
?>
<section
  class="theme-hero relative flex flex-col justify-center overflow-hidden"
  style="--hero-min-h:<?= $min_height ?>"
  aria-label="<?= esc_attr(wp_strip_all_tags($heading)) ?>"
  <?= get_block_wrapper_attributes($scroll_effect ? ['data-scroll' => $scroll_effect] : []) ?>
>
  {{-- Background image --}}
  <?php if ($image_id) { ?>
    <?= wp_get_attachment_image($image_id, 'full', false, [
        'class' => 'absolute inset-0 w-full h-full object-cover',
        'loading' => 'eager',
        'fetchpriority' => 'high',
        'aria-hidden' => 'true',
        'decoding' => 'async',
    ]) ?>
  <?php } else { ?>
    <img
      src="<?= $image_url ?>"
      alt=""
      aria-hidden="true"
      class="absolute inset-0 w-full h-full object-cover"
      loading="eager"
      fetchpriority="high"
      decoding="async"
    >
  <?php } ?>

  {{-- Overlay --}}
  <div
    class="theme-hero__overlay absolute inset-0 bg-ink"
    style="--hero-overlay:<?= $opacity / 100 ?>"
    aria-hidden="true"
  ></div>

  {{-- Content --}}
  <div class="relative z-10 flex flex-col <?= $align_class ?> px-6 lg:px-16 py-24 max-w-4xl <?= $align === 'center' ? 'mx-auto' : '' ?>">

    <?php if ($label) { ?>
      <span class="section-label text-white/60 mb-4"><?= $label ?></span>
    <?php } ?>

    <h1 class="text-white font-light leading-tight text-hero-display">
      <?= $heading ?>
    </h1>

    <?php if ($subtext) { ?>
      <p class="mt-6 text-white/70 text-lg leading-relaxed max-w-2xl <?= $align === 'center' ? 'mx-auto' : '' ?>">
        <?= $subtext ?>
      </p>
    <?php } ?>

    <div class="flex flex-wrap gap-4 mt-10 <?= $align === 'center' ? 'justify-center' : '' ?>">
      <a href="<?= $cta_url ?>" class="btn-primary"><?= $cta_label ?></a>
      <?php if ($cta2_label && $cta2_url) { ?>
        <a href="<?= $cta2_url ?>" class="text-xs font-semibold tracking-[0.15em] uppercase text-white/60 border-b border-white/20 pb-0.5 hover:text-white hover:border-white transition-colors">
          <?= $cta2_label ?>
        </a>
      <?php } ?>
    </div>

  </div>
</section>
