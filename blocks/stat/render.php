<?php
/**
 * Block: theme/stat
 */
$value = esc_html($attributes['value'] ?? '100+');
$label = esc_html($attributes['label'] ?? '');
$description = esc_html($attributes['description'] ?? '');
$bg = $attributes['bg'] ?? 'surface';
$prefix = esc_html($attributes['prefix'] ?? '');
$suffix = esc_html($attributes['suffix'] ?? '');
$align = $attributes['align'] ?? 'left';

$value_class = $bg === 'ink' ? 'text-white' : 'text-ink';
$label_class = $bg === 'ink' ? 'text-white/40' : 'text-muted';
$desc_class = $bg === 'ink' ? 'text-white/30' : 'text-muted/70';
$align_class = $align === 'center' ? 'text-center' : '';
$line_class = $bg === 'ink' ? 'bg-white/10' : 'bg-border';
?>
<div
  class="stat-block <?= $align_class ?> py-8 <?= $align !== 'center' ? "border-l-2 pl-6 {$line_class}" : '' ?>"
  <?= get_block_wrapper_attributes() ?>
>
  <?php if ($prefix) { ?>
    <span class="font-serif text-2xl font-light <?= $value_class ?> opacity-60"><?= $prefix ?></span>
  <?php } ?>
  <span class="font-serif font-light <?= $value_class ?> leading-none" style="font-size: clamp(3rem, 6vw, 5rem)">
    <?= $value ?>
  </span>
  <?php if ($suffix) { ?>
    <span class="font-serif text-2xl font-light <?= $value_class ?>"><?= $suffix ?></span>
  <?php } ?>

  <?php if ($label) { ?>
    <p class="text-xs font-semibold tracking-[0.15em] uppercase mt-3 <?= $label_class ?>"><?= $label ?></p>
  <?php } ?>
  <?php if ($description) { ?>
    <p class="text-xs mt-1 <?= $desc_class ?>"><?= $description ?></p>
  <?php } ?>
</div>
