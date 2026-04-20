<?php

/**
 * Centralized AJAX / REST handlers.
 * Covers: contact form, live search, wishlist toggle.
 */

namespace App;

// ── Contact form (admin-post.php) ─────────────────────────────────────────────

add_action('admin_post_theme_contact', __NAMESPACE__.'\\handle_contact_form');
add_action('admin_post_nopriv_theme_contact', __NAMESPACE__.'\\handle_contact_form');

/**
 * Detect XML HTTP requests for admin-post handlers.
 */
function theme_is_xhr_request(): bool
{
    $header = strtolower((string) ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? ''));

    return wp_doing_ajax() || $header === 'xmlhttprequest';
}

/**
 * Reply as JSON for AJAX calls, otherwise redirect back.
 */
function theme_contact_respond(bool $success, string $message, int $status = 200): void
{
    if (theme_is_xhr_request()) {
        wp_send_json([
            'success' => $success,
            'message' => $message,
        ], $status);
    }

    $redirect = wp_get_referer() ?: home_url('/');
    $redirect = add_query_arg('theme_contact', $success ? 'success' : 'error', $redirect);
    wp_safe_redirect($redirect, $success ? 303 : 302);
    exit;
}

/**
 * Process the contact form submission.
 * Validates nonce, honeypot, required fields, then fires wp_mail().
 */
function handle_contact_form(): void
{
    // Honeypot check
    if (! empty($_POST['honeypot']) || ! empty($_POST['website'])) {
        theme_contact_respond(false, '', 400);
    }

    // Nonce verification
    $nonce = sanitize_text_field(wp_unslash($_POST['_contact_nonce'] ?? $_POST['_wpnonce'] ?? ''));
    if (
        $nonce === ''
        || ! wp_verify_nonce($nonce, 'theme_contact_form')
    ) {
        theme_contact_respond(false, __('Verifica di sicurezza fallita. Ricarica la pagina.', 'sage'), 403);
    }

    $name = sanitize_text_field(wp_unslash($_POST['contact_name'] ?? $_POST['name'] ?? ''));
    $email = sanitize_email(wp_unslash($_POST['contact_email'] ?? $_POST['email'] ?? ''));
    $subject = sanitize_text_field(wp_unslash(
        $_POST['contact_subject'] ?? $_POST['subject'] ?? __('Nuovo messaggio dal sito', 'sage')
    ));
    $message = sanitize_textarea_field(wp_unslash($_POST['contact_message'] ?? $_POST['message'] ?? ''));
    $privacy = ! empty($_POST['contact_privacy']) || ! empty($_POST['privacy']);

    if (! $name || ! is_email($email) || ! $message || ! $privacy) {
        theme_contact_respond(false, __('Compila tutti i campi obbligatori.', 'sage'), 422);
    }

    $to = sanitize_email(get_option('admin_email'));
    $headers = [
        'Content-Type: text/html; charset=UTF-8',
        sprintf('Reply-To: %s <%s>', $name, $email),
    ];

    $body = sprintf(
        '<p><strong>Nome:</strong> %s</p><p><strong>Email:</strong> %s</p><p><strong>Messaggio:</strong></p><p>%s</p>',
        esc_html($name),
        esc_html($email),
        nl2br(esc_html($message))
    );

    /**
     * Allow plugins/integrations to intercept before sending.
     * Return false to skip wp_mail (e.g. send to CRM instead).
     */
    $send = apply_filters('theme_before_contact_mail', true, compact('name', 'email', 'subject', 'message'));

    $sent = $send ? wp_mail($to, '['.get_bloginfo('name').'] '.$subject, $body, $headers) : true;

    if ($sent) {
        do_action('theme_contact_form_sent', compact('name', 'email', 'subject', 'message'));
        theme_contact_respond(true, __('Messaggio inviato. Ti risponderemo al più presto.', 'sage'));
    } else {
        theme_contact_respond(false, __('Invio non riuscito. Riprova o contattaci via email.', 'sage'), 500);
    }
}

// ── Live Search — REST endpoint ───────────────────────────────────────────────
// GET /wp-json/theme/v1/search?q=keyword&per_page=6&type=any

add_action('rest_api_init', function () {
    register_rest_route('theme/v1', '/search', [
        'methods' => 'GET',
        'callback' => __NAMESPACE__.'\\live_search',
        'permission_callback' => '__return_true',
        'args' => [
            'q' => [
                'required' => true,
                'type' => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'validate_callback' => fn ($v) => strlen(trim($v)) >= 2,
            ],
            'per_page' => [
                'default' => 6,
                'type' => 'integer',
                'sanitize_callback' => 'absint',
            ],
            'type' => [
                'default' => 'any',
                'type' => 'string',
                'sanitize_callback' => 'sanitize_key',
            ],
        ],
    ]);
});

/**
 * Live search handler — returns posts + products matching query.
 */
function live_search(\WP_REST_Request $request): \WP_REST_Response
{
    $query = sanitize_text_field($request->get_param('q'));
    $per_page = min((int) $request->get_param('per_page'), 12);
    $type = sanitize_key($request->get_param('type'));

    $post_types = ['post', 'page'];
    if (function_exists('WC') && in_array($type, ['any', 'product'], true)) {
        $post_types[] = 'product';
    }
    if ($type !== 'any' && in_array($type, $post_types, true)) {
        $post_types = [$type];
    }

    $wp_query = new \WP_Query([
        's' => $query,
        'post_type' => $post_types,
        'posts_per_page' => $per_page,
        'post_status' => 'publish',
        'no_found_rows' => true,
        // No 'fields' => 'ids' — load full objects so title/permalink/excerpt
        // are available without extra queries. With max 12 results the overhead
        // is negligible; the old approach caused N+1 for every meta/title call.
    ]);

    // Warm thumbnail caches in one batch query instead of one per post.
    update_post_thumbnail_cache($wp_query);

    $results = [];
    foreach ($wp_query->posts as $post) {
        $pid = (int) $post->ID;
        $thumb_id = get_post_thumbnail_id($pid);
        $thumb_url = $thumb_id ? wp_get_attachment_image_url($thumb_id, 'thumbnail') : '';

        $price = '';
        if ($post->post_type === 'product' && function_exists('wc_get_product')) {
            $product = wc_get_product($pid);
            $price = $product ? wp_strip_all_tags($product->get_price_html()) : '';
        }

        $results[] = [
            'id' => $pid,
            'title' => esc_html(get_the_title($post)),
            'url' => esc_url(get_permalink($post)),
            'thumb' => esc_url($thumb_url),
            'type' => $post->post_type,
            'price' => $price,
            'excerpt' => wp_trim_words(get_the_excerpt($post), 12, '…'),
        ];
    }

    return rest_ensure_response([
        'query' => $query,
        'count' => count($results),
        'results' => $results,
        'more_url' => esc_url(home_url('/?s='.urlencode($query))),
    ]);
}

// ── Quick View — REST endpoint ────────────────────────────────────────────────
// GET /wp-json/theme/v1/quick-view/{id}

add_action('rest_api_init', function () {
    register_rest_route('theme/v1', '/quick-view/(?P<id>[\d]+)', [
        'methods' => 'GET',
        'callback' => __NAMESPACE__.'\\quick_view',
        'permission_callback' => '__return_true',
        'args' => [
            'id' => [
                'required' => true,
                'type' => 'integer',
                'sanitize_callback' => 'absint',
            ],
        ],
    ]);
});

/**
 * Quick view product data — gallery, price, attributes, add-to-cart.
 */
function quick_view(\WP_REST_Request $request): \WP_REST_Response|\WP_Error
{
    if (! function_exists('wc_get_product')) {
        return new \WP_Error('wc_missing', 'WooCommerce required.', ['status' => 503]);
    }

    $product_id = (int) $request->get_param('id');
    $product = wc_get_product($product_id);

    if (! $product || $product->get_status() !== 'publish') {
        return new \WP_Error('not_found', __('Prodotto non trovato.', 'sage'), ['status' => 404]);
    }

    // Gallery images
    $gallery_ids = array_filter(array_merge(
        [$product->get_image_id()],
        $product->get_gallery_image_ids()
    ));
    $gallery = array_values(array_map(
        fn ($id) => esc_url(wp_get_attachment_image_url($id, 'woocommerce_single') ?: ''),
        $gallery_ids
    ));

    // Category
    $terms = get_the_terms($product_id, 'product_cat');
    $category = ($terms && ! is_wp_error($terms)) ? esc_html($terms[0]->name) : '';

    // Attributes for variable products
    $attributes = [];
    if ($product instanceof \WC_Product_Variable) {
        foreach ($product->get_variation_attributes() as $attr_name => $options) {
            $label = wc_attribute_label($attr_name, $product);
            $attributes[] = [
                'name' => esc_attr($attr_name),
                'label' => esc_html($label),
                'options' => array_map('esc_html', $options),
            ];
        }
    }

    return rest_ensure_response([
        'id' => $product_id,
        'title' => esc_html($product->get_name()),
        'url' => esc_url(get_permalink($product_id)),
        'price_html' => wp_strip_all_tags($product->get_price_html()),
        'thumb' => esc_url(wp_get_attachment_image_url($product->get_image_id(), 'woocommerce_thumbnail') ?: ''),
        'gallery' => $gallery,
        'short_desc' => wp_kses_post(apply_filters('woocommerce_short_description', $product->get_short_description())),
        'category' => $category,
        'in_stock' => $product->is_in_stock(),
        'on_sale' => $product->is_on_sale(),
        'rating' => (float) $product->get_average_rating(),
        'rating_count' => (int) $product->get_rating_count(),
        'add_to_cart_url' => esc_url($product->add_to_cart_url()),
        'attributes' => $attributes,
    ]);
}

// ── Products — REST endpoint (faceted filter) ─────────────────────────────────
// GET /wp-json/theme/v1/products?cats[]=1&cats[]=2&min_price=0&max_price=100&orderby=date&page=1

add_action('rest_api_init', function () {
    register_rest_route('theme/v1', '/products', [
        'methods' => 'GET',
        'callback' => __NAMESPACE__.'\\filtered_products',
        'permission_callback' => '__return_true',
        'args' => [
            'cats' => ['default' => [], 'type' => 'array',   'sanitize_callback' => fn ($v) => array_map('absint', (array) $v)],
            'min_price' => ['default' => 0,   'type' => 'number',  'sanitize_callback' => fn ($v) => max(0, (float) $v)],
            'max_price' => ['default' => 0,   'type' => 'number',  'sanitize_callback' => fn ($v) => max(0, (float) $v)],
            'in_stock' => ['default' => false, 'type' => 'boolean'],
            'orderby' => ['default' => 'date', 'type' => 'string', 'sanitize_callback' => 'sanitize_key',
                'validate_callback' => fn ($v) => in_array($v, ['date', 'price', 'price-desc', 'popularity', 'rating', 'title'], true)],
            'per_page' => ['default' => 12,  'type' => 'integer', 'sanitize_callback' => fn ($v) => min(24, max(1, (int) $v))],
            'page' => ['default' => 1,   'type' => 'integer', 'sanitize_callback' => fn ($v) => max(1, (int) $v)],
        ],
    ]);
});

/**
 * Filtered product list for AJAX faceted search.
 */
function filtered_products(\WP_REST_Request $request): \WP_REST_Response
{
    if (! function_exists('wc_get_product')) {
        return rest_ensure_response(['products' => [], 'total' => 0, 'pages' => 0]);
    }

    $cats = $request->get_param('cats') ?: [];
    $min_price = (float) $request->get_param('min_price');
    $max_price = (float) $request->get_param('max_price');
    $in_stock = (bool) $request->get_param('in_stock');
    $orderby = sanitize_key($request->get_param('orderby'));
    $per_page = (int) $request->get_param('per_page');
    $page = (int) $request->get_param('page');

    $cats = array_values(array_filter(array_map('absint', (array) $cats)));
    sort($cats);

    $cache_key = 'theme_rest_products_'.md5(wp_json_encode([
        'cats' => $cats,
        'min_price' => $min_price,
        'max_price' => $max_price,
        'in_stock' => $in_stock,
        'orderby' => $orderby,
        'per_page' => $per_page,
        'page' => $page,
    ]));

    $cached = get_transient($cache_key);
    if (is_array($cached)) {
        return rest_ensure_response($cached);
    }

    $args = [
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => $per_page,
        'paged' => $page,
        'fields' => 'ids',
        'meta_query' => [],
        'tax_query' => [],
    ];

    // Category filter
    if (! empty($cats)) {
        $args['tax_query'][] = [
            'taxonomy' => 'product_cat',
            'field' => 'term_id',
            'terms' => $cats,
            'operator' => 'IN',
        ];
    }

    // Price filter
    if ($min_price > 0 || $max_price > 0) {
        $args['meta_query'][] = [
            'key' => '_price',
            'value' => [$min_price ?: 0, $max_price ?: PHP_INT_MAX],
            'compare' => 'BETWEEN',
            'type' => 'NUMERIC',
        ];
    }

    // Stock filter
    if ($in_stock) {
        $args['meta_query'][] = ['key' => '_stock_status', 'value' => 'instock'];
    }

    // Ordering
    match ($orderby) {
        'price' => ($args += ['meta_key' => '_price', 'orderby' => 'meta_value_num', 'order' => 'ASC']),
        'price-desc' => ($args += ['meta_key' => '_price', 'orderby' => 'meta_value_num', 'order' => 'DESC']),
        'popularity' => ($args += ['meta_key' => 'total_sales', 'orderby' => 'meta_value_num', 'order' => 'DESC']),
        'rating' => ($args += ['meta_key' => '_wc_average_rating', 'orderby' => 'meta_value_num', 'order' => 'DESC']),
        'title' => ($args += ['orderby' => 'title', 'order' => 'ASC']),
        default => ($args += ['orderby' => 'date',  'order' => 'DESC']),
    };

    $query = new \WP_Query($args);
    $products = [];

    $product_ids = array_map('intval', (array) $query->posts);
    if (! empty($product_ids)) {
        _prime_post_caches($product_ids, false, true);
    }

    foreach ($product_ids as $product_id) {
        $product = wc_get_product($product_id);
        if (! $product) {
            continue;
        }

        $thumb_id = $product->get_image_id();
        $terms = get_the_terms($product_id, 'product_cat');
        $category = ($terms && ! is_wp_error($terms)) ? esc_html($terms[0]->name) : '';
        $products[] = [
            'id' => $product_id,
            'title' => esc_html($product->get_name()),
            'url' => esc_url(get_permalink($product_id)),
            'thumb' => esc_url(wp_get_attachment_image_url($thumb_id, 'woocommerce_thumbnail') ?: ''),
            'category' => $category,
            'price_html' => wp_strip_all_tags($product->get_price_html()),
            'price' => (float) $product->get_price(),
            'on_sale' => $product->is_on_sale(),
            'in_stock' => $product->is_in_stock(),
            'rating' => (float) $product->get_average_rating(),
            'add_to_cart_url' => esc_url($product->add_to_cart_url()),
            'add_to_cart_text' => esc_html($product->add_to_cart_text()),
        ];
    }

    $response = [
        'products' => $products,
        'total' => (int) $query->found_posts,
        'pages' => (int) $query->max_num_pages,
        'page' => $page,
    ];

    set_transient($cache_key, $response, MINUTE_IN_SECONDS);

    return rest_ensure_response($response);
}

// ── Wishlist Products — REST endpoint ────────────────────────────────────────
// GET /wp-json/theme/v1/wishlist-products?ids=1,2,3

add_action('rest_api_init', function () {
    register_rest_route('theme/v1', '/wishlist-products', [
        'methods' => 'GET',
        'callback' => __NAMESPACE__.'\\get_wishlist_products',
        'permission_callback' => '__return_true',
        'args' => [
            'ids' => [
                'required' => true,
                'type' => 'string',
                'sanitize_callback' => 'sanitize_text_field',
            ],
        ],
    ]);
});

/**
 * Return product data for a comma-separated list of IDs (max 50).
 * Used by the wishlist-products custom element on the frontend.
 */
function get_wishlist_products(\WP_REST_Request $request): \WP_REST_Response
{
    if (! function_exists('wc_get_product')) {
        return rest_ensure_response(['products' => []]);
    }

    $raw_ids = sanitize_text_field($request->get_param('ids'));
    $ids = array_filter(array_map('absint', explode(',', $raw_ids)));
    $ids = array_slice(array_values($ids), 0, 50);

    // Prime post + meta caches in one batch before the loop.
    _prime_post_caches($ids, false, true);

    // Warm thumbnail caches via a single query.
    $thumb_q = new \WP_Query([
        'post__in' => $ids,
        'posts_per_page' => count($ids),
        'post_status' => 'any',
        'no_found_rows' => true,
        'fields' => 'ids',
    ]);
    update_post_thumbnail_cache($thumb_q);

    $products = [];
    foreach ($ids as $id) {
        $product = wc_get_product($id);
        if (! $product || $product->get_status() !== 'publish') {
            continue;
        }

        $thumb_id = $product->get_image_id();
        $products[] = [
            'id' => $id,
            'title' => esc_html($product->get_name()),
            'url' => esc_url(get_permalink($id)),
            'thumb' => esc_url(wp_get_attachment_image_url($thumb_id, 'woocommerce_thumbnail') ?: ''),
            'price_html' => wp_strip_all_tags($product->get_price_html()),
            'in_stock' => $product->is_in_stock(),
            'on_sale' => $product->is_on_sale(),
        ];
    }

    return rest_ensure_response(['products' => $products]);
}

// ── Wishlist — REST endpoint ──────────────────────────────────────────────────
// POST /wp-json/theme/v1/wishlist  { "product_id": 123, "action": "add"|"remove" }

add_action('rest_api_init', function () {
    register_rest_route('theme/v1', '/wishlist', [
        'methods' => 'POST',
        'callback' => __NAMESPACE__.'\\wishlist_toggle',
        'permission_callback' => '__return_true',
        'args' => [
            'product_id' => [
                'required' => true,
                'type' => 'integer',
                'sanitize_callback' => 'absint',
            ],
            'action' => [
                'default' => 'toggle',
                'type' => 'string',
                'sanitize_callback' => 'sanitize_key',
            ],
        ],
    ]);
});

/**
 * Server-side wishlist — stores in user meta for logged-in users.
 * Guests use client-side localStorage; this syncs on login.
 */
function wishlist_toggle(\WP_REST_Request $request): \WP_REST_Response|\WP_Error
{
    if (! is_user_logged_in()) {
        // For guests, client-side localStorage is the primary store.
        return rest_ensure_response(['success' => true, 'guest' => true]);
    }

    $user_id = get_current_user_id();
    $product_id = (int) $request->get_param('product_id');
    $action = sanitize_key($request->get_param('action'));

    if (! $product_id || ! get_post($product_id)) {
        return new \WP_Error('invalid_product', __('Prodotto non trovato.', 'sage'), ['status' => 404]);
    }

    $wishlist = (array) get_user_meta($user_id, '_theme_wishlist', true);

    if ($action === 'add' || ($action === 'toggle' && ! in_array($product_id, $wishlist, true))) {
        $wishlist[] = $product_id;
        $added = true;
    } else {
        $wishlist = array_values(array_filter($wishlist, fn ($id) => $id !== $product_id));
        $added = false;
    }

    update_user_meta($user_id, '_theme_wishlist', array_unique($wishlist));
    do_action('theme_wishlist_updated', $user_id, $product_id, $added);

    return rest_ensure_response([
        'success' => true,
        'added' => $added,
        'wishlist' => array_values(array_unique($wishlist)),
    ]);
}
