<?php
/**
 * Block: theme/testimonial
 */
$quote = wp_kses_post($attributes['quote'] ?? '');
$author_name = esc_html($attributes['authorName'] ?? '');
$author_role = esc_html($attributes['authorRole'] ?? '');
$author_img_id = (int) ($attributes['authorImageId'] ?? 0);
$author_img_url = esc_url($attributes['authorImageUrl'] ?? ($author_img_id ? wp_get_attachment_image_url($author_img_id, 'thumbnail') : '')) ?: esc_url(\App\get_placeholder_url());
$rating = min(5, max(0, (int) ($attributes['rating'] ?? 5)));
$bg = $attributes['bg'] ?? 'surface';
$style = $attributes['style'] ?? 'card';
$scroll_effect = esc_attr($attributes['scrollEffect'] ?? '');

$bg_class = match ($bg) {
    'cream' => 'bg-cream', 'ink' => 'bg-ink', default => 'bg-surface'
};
$card_bg = $bg === 'ink' ? 'bg-white/5' : 'bg-cream';
$quote_class = $bg === 'ink' ? 'text-white/80' : 'text-ink/80';
$meta_class = $bg === 'ink' ? 'text-white' : 'text-ink';
$sub_class = $bg === 'ink' ? 'text-white/40' : 'text-muted';

if ($style === 'large') {
    $wrap_class = 'max-w-2xl mx-auto text-center py-16 px-6';
    $quote_size = 'font-serif text-2xl md:text-3xl font-light leading-snug';
} elseif ($style === 'minimal') {
    $wrap_class = 'py-6';
    $quote_size = 'font-serif text-lg font-light leading-snug';
} else {
    // card
    $wrap_class = "p-8 {$card_bg}";
    $quote_size = 'text-sm leading-relaxed';
}
?>
<figure
  class="testimonial-block <?= $wrap_class ?>"
  <?= get_block_wrapper_attributes($scroll_effect ? ['data-scroll' => $scroll_effect] : []) ?>
>
  <?php if ($rating > 0) { ?>
    <div class="flex gap-0.5 mb-4 <?= $style === 'large' ? 'justify-center' : '' ?>" aria-label="<?= esc_attr(sprintf(__('%d stelle su 5', 'sage'), $rating)) ?>" role="img">
      <?php for ($i = 1; $i <= 5; $i++) { ?>
        <svg class="w-3.5 h-3.5 <?= $i <= $rating ? 'text-gold' : 'text-border' ?>" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
        </svg>
      <?php } ?>
    </div>
  <?php } ?>

  <blockquote class="<?= $quote_class ?> <?= $quote_size ?>">
    <p><?= $quote ?></p>
  </blockquote>

  <figcaption class="mt-6 flex items-center gap-3 <?= $style === 'large' ? 'justify-center' : '' ?>">
    <?php if ($author_img_id) { ?>
      <?= wp_get_attachment_image($author_img_id, [80, 80], false, [
          'class' => 'w-10 h-10 rounded-full object-cover shrink-0',
          'loading' => 'lazy',
          'alt' => esc_attr($author_name),
      ]) ?>
    <?php } else { ?>
      <img
        src="<?= $author_img_url ?>"
        alt="<?= esc_attr($author_name) ?>"
        class="w-10 h-10 rounded-full object-cover shrink-0"
        loading="lazy"
        decoding="async"
        width="40"
        height="40"
      >
    <?php } ?>
    <div>
      <p class="text-sm font-semibold <?= $meta_class ?>"><?= $author_name ?></p>
      <?php if ($author_role) { ?>
        <p class="text-xs <?= $sub_class ?>"><?= $author_role ?></p>
      <?php } ?>
    </div>
  </figcaption>
</figure>
