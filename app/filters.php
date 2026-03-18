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
// POST /wp-json/theme/v1/newsletter  { "email": "..." }
// Fire the `theme_newsletter_subscribe` action so any ESP integration
// (Mailchimp, Klaviyo, etc.) can hook in without touching this file.
add_action('rest_api_init', function () {
    register_rest_route('theme/v1', '/newsletter', [
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
    do_action('theme_newsletter_subscribe', $email);

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

// ── WooCommerce single product: layout tweaks ────────────────────────────────

// Move breadcrumb above the product div (before single-product summary hooks)
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

// Change default columns: 2-col gallery on single product (WC default = 1/2 split via flex)
add_filter('woocommerce_product_thumbnails_columns', fn() => 4);

// Trust badges below add-to-cart
add_action('woocommerce_after_add_to_cart_button', function () {
    echo '<div class="trust-badges flex flex-wrap gap-4 mt-5 text-muted font-sans text-xs">';
    $badges = [
        ['icon' => 'M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z',
              'label' => __('Pagamento sicuro', 'sage')],
        ['icon' => 'M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12',
              'label' => __('Spedizione rapida', 'sage')],
        ['icon' => 'M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99',
              'label' => __('Resi gratuiti 30gg', 'sage')],
    ];
    foreach ($badges as $b) {
        printf(
            '<span class="flex items-center gap-1.5"><svg class="w-4 h-4 text-gold shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="%s"/></svg>%s</span>',
            esc_attr($b['icon']),
            esc_html($b['label'])
        );
    }
    echo '</div>';
}, 25);

// ── Security hardening ───────────────────────────────────────────────────────

// Remove WordPress version from head and feeds (information disclosure)
remove_action('wp_head', 'wp_generator');
add_filter('the_generator', '__return_empty_string');

// Remove RSD / WLW manifest links (unused, potential attack surface)
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');

// Disable XML-RPC (common brute-force target; re-enable if Jetpack / app bridge needed)
add_filter('xmlrpc_enabled', '__return_false');

// Remove shortlink from head
remove_action('wp_head', 'wp_shortlink_wp_head');

// Rate-limit newsletter REST endpoint: max 5 requests per IP per minute.
// Uses transients as a lightweight counter — no extra plugin needed.
add_filter('rest_pre_dispatch', function ($result, $server, \WP_REST_Request $request) {
    if ($request->get_route() !== '/theme/v1/newsletter') {
        return $result;
    }
    $ip  = sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
    $key = 'nl_rl_' . md5($ip);
    $hits = (int) get_transient($key);
    if ($hits >= 5) {
        return new \WP_Error(
            'rate_limit',
            __('Troppe richieste. Riprova tra un minuto.', 'sage'),
            ['status' => 429]
        );
    }
    set_transient($key, $hits + 1, MINUTE_IN_SECONDS);
    return $result;
}, 10, 3);

// Remove oEmbed discovery links (privacy + minor attack surface)
remove_action('wp_head', 'wp_oembed_add_discovery_links');
remove_action('wp_head', 'wp_oembed_add_host_js');

// Disable REST API user enumeration for unauthenticated requests
add_filter('rest_endpoints', function (array $endpoints): array {
    if (! is_user_logged_in()) {
        unset($endpoints['/wp/v2/users']);
        unset($endpoints['/wp/v2/users/(?P<id>[\d]+)']);
    }
    return $endpoints;
});

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
