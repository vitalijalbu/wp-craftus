<?php
/**
 * Block: theme/icon-box
 */
$icon_id = (int) ($attributes['iconImageId'] ?? 0);
$icon_url = esc_url($attributes['iconImageUrl'] ?? ($icon_id ? wp_get_attachment_image_url($icon_id, 'thumbnail') : '')) ?: esc_url(\App\get_placeholder_url());
$icon_size = (int) ($attributes['iconSize'] ?? 48);
$title = wp_kses_post($attributes['title'] ?? '');
$text = wp_kses_post($attributes['text'] ?? '');
$link_label = esc_html($attributes['linkLabel'] ?? '');
$link_url = esc_url($attributes['linkUrl'] ?? '');
$bg = $attributes['bg'] ?? 'surface';
$layout = $attributes['layout'] ?? 'vertical';
$bordered = (bool) ($attributes['bordered'] ?? false);
$scroll_effect = esc_attr($attributes['scrollEffect'] ?? '');

$title_class = $bg === 'ink' ? 'text-white' : 'text-ink';
$text_class = $bg === 'ink' ? 'text-white/60' : 'text-muted';
$link_class = 'text-primary';
$border_class = $bordered ? ($bg === 'ink' ? 'border border-white/10 p-6' : 'border border-border p-6') : '';

$is_horizontal = $layout === 'horizontal';
$wrap_flex = $is_horizontal
    ? 'flex flex-row gap-4 items-start'
    : 'flex flex-col gap-4';
?>
<div
  class="icon-box <?= $wrap_flex ?> <?= $border_class ?>"
  <?= get_block_wrapper_attributes($scroll_effect ? ['data-scroll' => $scroll_effect] : []) ?>
>
  <img
    src="<?= $icon_url ?>"
    alt=""
    aria-hidden="true"
    width="<?= $icon_size ?>"
    height="<?= $icon_size ?>"
    class="icon-box__icon shrink-0 object-contain"
    style="--icon-sz:<?= $icon_size ?>px"
    loading="lazy"
    decoding="async"
  >

  <div>
    <?php if ($title) { ?>
      <h3 class="text-xl font-light <?= $title_class ?> leading-snug"><?= $title ?></h3>
    <?php } ?>
    <?php if ($text) { ?>
      <p class="text-sm <?= $text_class ?> mt-2 leading-relaxed"><?= $text ?></p>
    <?php } ?>
    <?php if ($link_label && $link_url) { ?>
      <a href="<?= $link_url ?>" class="mt-3 inline-block text-xs font-semibold tracking-[0.12em] uppercase <?= $link_class ?> hover:underline">
        <?= $link_label ?> →
      </a>
    <?php } ?>
  </div>
</div>
