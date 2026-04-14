<?php
/**
 * Product quantity input — stile coerente con il selettore quantità dei blocchi carrello.
 * Override di woocommerce/templates/global/quantity-input.php
 *
 * @version 10.1.0 (WC reference version)
 */
defined('ABSPATH') || exit;

/* translators: %s: product name */
$label = ! empty($args['product_name'])
    ? sprintf(esc_html__('%s quantity', 'woocommerce'), wp_strip_all_tags($args['product_name']))
    : esc_html__('Quantity', 'woocommerce');

$min = ($min_value !== '') ? (int) $min_value : 1;
$max = ($max_value !== '' && $max_value > 0) ? (int) $max_value : '';
?>
<div class="quantity qty-selector">
    <?php do_action('woocommerce_before_quantity_input_field'); ?>

    <label class="screen-reader-text" for="<?php echo esc_attr($input_id); ?>">
        <?php echo esc_html($label); ?>
    </label>

    <button
        type="button"
        class="qty-btn qty-btn--minus"
        aria-label="<?php esc_attr_e('Riduci quantità', 'sage'); ?>"
        <?php echo ($min !== '' && (int) $input_value <= $min) ? 'disabled' : ''; ?>
    >−</button>

    <input
        type="<?php echo esc_attr($type); ?>"
        <?php echo $readonly ? 'readonly="readonly"' : ''; ?>
        id="<?php echo esc_attr($input_id); ?>"
        class="<?php echo esc_attr(implode(' ', (array) $classes)); ?>"
        name="<?php echo esc_attr($input_name); ?>"
        value="<?php echo esc_attr($input_value); ?>"
        aria-label="<?php echo esc_attr($label); ?>"
        min="<?php echo esc_attr($min_value); ?>"
        <?php if ($max_value > 0) { ?>
            max="<?php echo esc_attr($max_value); ?>"
        <?php } ?>
        <?php if (! $readonly) { ?>
            step="<?php echo esc_attr($step); ?>"
            placeholder="<?php echo esc_attr($placeholder); ?>"
            inputmode="<?php echo esc_attr($inputmode); ?>"
            autocomplete="<?php echo esc_attr($autocomplete ?? 'on'); ?>"
        <?php } ?>
    />

    <button
        type="button"
        class="qty-btn qty-btn--plus"
        aria-label="<?php esc_attr_e('Aumenta quantità', 'sage'); ?>"
        <?php echo ($max !== '' && (int) $input_value >= $max) ? 'disabled' : ''; ?>
    >＋</button>

    <?php do_action('woocommerce_after_quantity_input_field'); ?>
</div>
