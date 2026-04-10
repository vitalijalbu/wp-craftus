<?php
/**
 * Block: theme/accordion
 * Accessible accordion — uses native <details>/<summary> for zero-JS open/close.
 * Alpine.js adds smooth animation when available; native browser fallback works without JS.
 *
 * @var array    $attributes  Block attributes.
 * @var string   $content     Inner blocks HTML (unused).
 * @var WP_Block $block       Block instance.
 */

$items     = $attributes['items']    ?? [];
$style     = $attributes['style']    ?? 'lines';
$open_first = (bool) ($attributes['openFirst'] ?? true);

if (empty($items)) {
    return;
}

$wrapper_class = match ($style) {
    'cards'  => 'theme-accordion theme-accordion--cards divide-y-0 space-y-2',
    'filled' => 'theme-accordion theme-accordion--filled divide-y-0 space-y-1',
    default  => 'theme-accordion theme-accordion--lines divide-y divide-border',
};
?>
<div <?= get_block_wrapper_attributes(['class' => $wrapper_class]) ?>>
  <?php foreach ($items as $index => $item) :
    $question = wp_kses_post($item['question'] ?? '');
    $answer   = wp_kses_post($item['answer']   ?? '');
    if (! $question && ! $answer) {
        continue;
    }
    $is_open = ($open_first && $index === 0);
  ?>
  <details
    class="group"
    <?= $is_open ? 'open' : '' ?>
    x-data="{ open: <?= $is_open ? 'true' : 'false' ?> }"
    :open="open"
  >
    <summary
      class="flex items-center justify-between gap-4 py-5 cursor-pointer list-none select-none
             font-sans font-medium text-ink hover:text-primary transition-colors duration-200
             <?= $style === 'cards' ? 'px-5 bg-surface-alt' : '' ?>
             <?= $style === 'filled' ? 'px-5 bg-ink text-white hover:text-white/80' : '' ?>"
      @click.prevent="open = !open"
      role="button"
      :aria-expanded="open.toString()"
    >
      <span><?= $question ?></span>
      <svg
        class="w-4 h-4 shrink-0 transition-transform duration-300"
        :class="open ? 'rotate-45' : 'rotate-0'"
        fill="none" stroke="currentColor" stroke-width="2"
        viewBox="0 0 24 24" aria-hidden="true"
      >
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
      </svg>
    </summary>

    <div
      x-show="open"
      x-collapse
      class="<?= $style === 'cards' ? 'px-5 pb-5 bg-surface-alt' : '' ?>
             <?= $style === 'filled' ? 'px-5 pb-5 bg-surface-alt' : 'pb-5' ?>"
    >
      <div class="text-muted leading-relaxed prose prose-sm max-w-none">
        <?= $answer ?>
      </div>
    </div>
  </details>
  <?php endforeach; ?>
</div>
