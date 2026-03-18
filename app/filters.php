<?php

/**
 * Theme filters.
 */

namespace App;

// ── WooCommerce: cart count fragment ─────────────────────────────────────────
// Updates `.cart-count-fragment[data-cart-count]` via WC's AJAX fragment system
// so the cart badge stays accurate after add-to-cart without a page reload.
add_filter('woocommerce_add_to_cart_fragments', function (array $fragments): array {
    if (! function_exists('WC') || ! WC()->cart) {
        return $fragments;
    }

    $count = (int) WC()->cart->get_cart_contents_count();
    $html  = sprintf(
        '<span class="cart-count-fragment absolute -top-1 -right-1 min-w-4 h-4 bg-gold text-ink text-[9px] font-bold rounded-full flex items-center justify-center px-0.5 leading-none transition-opacity %s" data-cart-count="%d">%d</span>',
        $count === 0 ? 'opacity-0 pointer-events-none' : 'opacity-100',
        $count,
        $count
    );

    // Target ALL .cart-count-fragment spans (desktop + mobile)
    $fragments['span.cart-count-fragment'] = $html;

    return $fragments;
});

// ── Newsletter: REST API endpoint ────────────────────────────────────────────
// POST /wp-json/4zampe/v1/newsletter  { "email": "..." }
// Fire the `4zampe_newsletter_subscribe` action so any ESP integration
// (Mailchimp, Klaviyo, etc.) can hook in without touching this file.
add_action('rest_api_init', function () {
    register_rest_route('4zampe/v1', '/newsletter', [
        'methods'             => 'POST',
        'callback'            => __NAMESPACE__ . '\\newsletter_subscribe',
        'permission_callback' => '__return_true',
        'args'                => [
            'email' => [
                'required'          => true,
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_email',
                'validate_callback' => fn($v) => is_email($v),
            ],
        ],
    ]);
});

/**
 * Handle newsletter subscription.
 *
 * @param \WP_REST_Request $request
 * @return \WP_REST_Response|\WP_Error
 */
function newsletter_subscribe(\WP_REST_Request $request) {
    $email = sanitize_email($request->get_param('email'));

    if (! is_email($email)) {
        return new \WP_Error('invalid_email', __('Indirizzo email non valido.', 'sage'), ['status' => 422]);
    }

    /**
     * Fires when a user subscribes to the newsletter.
     * Hook here to integrate with Mailchimp, Klaviyo, or any other ESP.
     *
     * @param string $email The subscriber email address.
     */
    do_action('4zampe_newsletter_subscribe', $email);

    return rest_ensure_response([
        'success' => true,
        'message' => __('Iscrizione effettuata. Grazie!', 'sage'),
    ]);
}

/**
 * Add "… Continued" to the excerpt.
 *
 * @return string
 */
/**
 * Add `has-hero` body class when the first block of the page is a full-width
 * cover/group so the header can stay transparent over a dark hero.
 */
add_filter('body_class', function (array $classes): array {
    if (! is_singular()) {
        return $classes;
    }
    $post   = get_post();
    $blocks = $post ? parse_blocks($post->post_content) : [];
    $first  = $blocks[0]['blockName'] ?? '';
    $hero_blocks = ['core/cover', 'core/group', 'theme/hero'];
    if (in_array($first, $hero_blocks, true)) {
        $classes[] = 'has-hero';
    }
    return $classes;
});

add_filter('excerpt_more', function () {
    return sprintf(' &hellip; <a href="%s">%s</a>', get_permalink(), __('Continued', 'sage'));
});

/**
 * Cap WooCommerce product queries so blocks/shortcodes never load all products at once.
 * Without this, an "All Products" block with 10k products causes memory exhaustion.
 */
add_filter('woocommerce_shortcode_products_query', function (array $query): array {
    if (empty($query['posts_per_page']) || (int) $query['posts_per_page'] < 0) {
        $query['posts_per_page'] = 12;
    }
    return $query;
});

add_filter('pre_render_block', function ($pre_render, array $block) {
    $wc_product_blocks = [
        'woocommerce/all-products',
        'woocommerce/product-query',
        'woocommerce/handpicked-products',
        'woocommerce/product-best-sellers',
        'woocommerce/product-new',
        'woocommerce/product-on-sale',
        'woocommerce/product-top-rated',
        'woocommerce/products-by-attribute',
        'woocommerce/product-category',
    ];
    if (in_array($block['blockName'] ?? '', $wc_product_blocks, true)) {
        if (empty($block['attrs']['perPage']) || $block['attrs']['perPage'] > 24) {
            add_filter('query_vars', function ($vars) { return $vars; }); // noop to force re-read
            add_filter('posts_per_page', function () { return 12; });
        }
    }
    return $pre_render;
}, 5, 2);

/**
 * Map WooCommerce templates to the woocommerce Blade view.
 *
 * Sage resolves templates via the `sage/template/hierarchy` filter.
 * By prepending 'woocommerce' for any WC page we force Acorn to load
 * resources/views/woocommerce.blade.php instead of page.blade.php.
 */
add_filter('sage/template/hierarchy', function (array $templates): array {
    if (function_exists('is_woocommerce') && is_woocommerce()) {
        array_unshift($templates, 'woocommerce');
    }
    if (function_exists('is_cart') && is_cart()) {
        array_unshift($templates, 'woocommerce');
    }
    if (function_exists('is_checkout') && is_checkout()) {
        array_unshift($templates, 'woocommerce');
    }
    if (function_exists('is_account_page') && is_account_page()) {
        array_unshift($templates, 'woocommerce');
    }
    return $templates;
});
