<?php
/**
 * theme/products-carousel — server-side render.
 *
 * @var array    $attributes  Block attributes.
 * @var string   $content     Inner blocks HTML (unused — dynamic block).
 * @var WP_Block $block       Block instance.
 */
defined('ABSPATH') || exit;

if (! function_exists('wc_get_product')) {
    return;
}

$title      = esc_html($attributes['title']    ?? '');
$subtitle   = esc_html($attributes['subtitle'] ?? '');
$limit      = max(1, min(24, (int) ($attributes['limit'] ?? 8)));
$categories = array_filter(array_map('sanitize_title', (array) ($attributes['categories'] ?? [])));
$orderby    = sanitize_key($attributes['orderby'] ?? 'date');
$order      = in_array(strtoupper((string) ($attributes['order'] ?? 'DESC')), ['ASC', 'DESC'], true)
    ? strtoupper((string) ($attributes['order'] ?? 'DESC'))
    : 'DESC';
$bg         = $attributes['bg'] ?? 'surface';

$bg_class = match ($bg) {
    'cream' => 'bg-cream',
    'ink'   => 'bg-ink',
    default => 'bg-surface',
};

// ── WP_Query ──────────────────────────────────────────────────────────────────
$query_args = [
    'post_type'           => 'product',
    'post_status'         => 'publish',
    'posts_per_page'      => $limit,
    'order'               => $order,
    'ignore_sticky_posts' => true,
];

// Orderby mapping (WooCommerce conventions → WP_Query)
switch ($orderby) {
    case 'popularity':
        $query_args['meta_key'] = 'total_sales';
        $query_args['orderby']  = 'meta_value_num';
        break;
    case 'rating':
        $query_args['meta_key'] = '_wc_average_rating';
        $query_args['orderby']  = 'meta_value_num';
        break;
    case 'price':
        $query_args['meta_key'] = '_price';
        $query_args['orderby']  = 'meta_value_num';
        $query_args['order']    = 'ASC';
        break;
    case 'price-desc':
        $query_args['meta_key'] = '_price';
        $query_args['orderby']  = 'meta_value_num';
        $query_args['order']    = 'DESC';
        break;
    case 'title':
        $query_args['orderby'] = 'title';
        break;
    case 'rand':
        $query_args['orderby'] = 'rand';
        break;
    default: // date
        $query_args['orderby'] = 'date';
        break;
}

if (! empty($categories)) {
    $query_args['tax_query'] = [[
        'taxonomy' => 'product_cat',
        'field'    => 'slug',
        'terms'    => $categories,
    ]];
}

$query    = new WP_Query($query_args);
$products = [];

if ($query->have_posts()) {
    while ($query->have_posts()) {
        $query->the_post();
        $product = wc_get_product(get_the_ID());
        if ($product && $product->is_visible()) {
            $products[] = $product;
        }
    }
    wp_reset_postdata();
}

if (empty($products)) {
    if (defined('REST_REQUEST') && REST_REQUEST) {
        echo '<p style="padding:2rem;color:#999;font-style:italic;">'
            . esc_html__('Nessun prodotto trovato con questi filtri.', 'sage')
            . '</p>';
    }
    return;
}

$has_many     = count($products) > 1;
$wrapper_atts = get_block_wrapper_attributes([
    'class'       => "products-carousel-section {$bg_class}",
    'data-products-carousel' => '',
    'aria-label'  => $title ?: __('Carousel prodotti', 'sage'),
]);
?>
<section <?php echo $wrapper_atts; ?>>

    <?php if ($title || $subtitle) { ?>
        <div class="container mb-8 md:mb-10">
            <div class="flex items-end justify-between gap-6">
                <div>
                    <?php if ($subtitle) { ?>
                        <p class="section-label text-muted mb-2"><?php echo $subtitle; ?></p>
                    <?php } ?>
                    <h2 class="text-2xl md:text-3xl lg:text-4xl font-serif font-light text-ink leading-tight m-0">
                        <?php echo $title; ?>
                    </h2>
                </div>
                <?php if ($has_many) { ?>
                    <div class="flex items-center gap-2 shrink-0">
                        <button type="button"
                            class="swiper-button-prev products-carousel__btn"
                            aria-label="<?php esc_attr_e('Prodotto precedente', 'sage'); ?>">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
                            </svg>
                        </button>
                        <button type="button"
                            class="swiper-button-next products-carousel__btn"
                            aria-label="<?php esc_attr_e('Prodotto successivo', 'sage'); ?>">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                            </svg>
                        </button>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php } ?>

    <div class="products-carousel__outer">
        <div class="products-carousel__track">
            <div class="js-products-swiper swiper">
                <div class="swiper-wrapper items-stretch">
                    <?php foreach ($products as $product) { ?>
                        <div class="swiper-slide h-auto">
                            <?php echo \Roots\view('partials.product-card', ['product' => $product])->render(); ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="swiper-scrollbar products-carousel__scrollbar"></div>
    </div>

</section>
