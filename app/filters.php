<?php

/**
 * Theme filters.
 */

namespace App;

// ── Frontend globals: themeData + themeI18n ──────────────────────────────────
// Injected once via wp_add_inline_script so JS modules don't need inline PHP.
add_action('wp_enqueue_scripts', function () {
    $data = [
        'restUrl' => esc_url_raw(rest_url()),
        'nonce' => wp_create_nonce('wp_rest'),
        'homeUrl' => esc_url_raw(home_url('/')),
        'shopUrl' => function_exists('wc_get_page_permalink') ? esc_url_raw(wc_get_page_permalink('shop')) : '',
    ];
    $i18n = [
        'invalidEmail' => __('Inserisci un indirizzo email valido.', 'sage'),
        'genericError' => __('Si è verificato un errore.', 'sage'),
        'networkError' => __('Errore di rete. Riprova.', 'sage'),
    ];
    wp_add_inline_script(
        'theme/js/app',
        'window.themeData = '.wp_json_encode($data).'; window.themeI18n = '.wp_json_encode($i18n).';',
        'before',
    );
}, 20);

// ── WooCommerce: always enable registration on My Account frontend ─────────
// The custom form-login override always shows Login + Register side by side.
// Keep admin settings untouched.
add_filter('option_woocommerce_enable_myaccount_registration', function ($value) {
    if (is_admin()) {
        return $value;
    }

    return 'yes';
});

// ── WooCommerce: move related & upsells outside div.product grid ─────────
// By default WC renders them inside woocommerce_after_single_product_summary
// (which lives inside the 2-column product grid). Moving them to
// woocommerce_after_single_product places them outside, so bg-cream goes full-width.
remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
remove_action('woocommerce_after_single_product', 'woocommerce_upsell_display', 10);
remove_action('woocommerce_after_single_product', 'woocommerce_output_related_products', 20);
add_action('theme_after_woocommerce_container', 'woocommerce_upsell_display', 10);
add_action('theme_after_woocommerce_container', 'woocommerce_output_related_products', 20);

// ── WooCommerce: cart count fragment ─────────────────────────────────────────
// Updates `.cart-count-fragment[data-cart-count]` via WC's AJAX fragment system
// so the cart badge stays accurate after add-to-cart without a page reload.
add_filter('woocommerce_add_to_cart_fragments', function (array $fragments): array {
    if (! function_exists('WC') || ! WC()->cart) {
        return $fragments;
    }

    $count = (int) WC()->cart->get_cart_contents_count();
    $html = sprintf(
        '<span class="icon-badge cart-count-fragment %s" data-cart-count="%d" data-count="%d">%d</span>',
        $count === 0 ? 'opacity-0 pointer-events-none' : 'opacity-100',
        $count,
        $count,
        $count
    );

    // Target ALL .cart-count-fragment spans (desktop + mobile)
    $fragments['span.cart-count-fragment'] = $html;

    // Drawer content fragment — replaces the entire cart items + footer block
    try {
        $fragments['div.wc-cart-drawer-fragment'] = \Roots\view('partials.cart-drawer-content')->render();
    } catch (\Throwable $e) {
        // Silently skip if Blade rendering fails during AJAX
    }

    return $fragments;
});

// ── Newsletter: REST API endpoint ────────────────────────────────────────────
// POST /wp-json/theme/v1/newsletter  { "email": "..." }
// Fire the `theme_newsletter_subscribe` action so any ESP integration
// (Mailchimp, Klaviyo, etc.) can hook in without touching this file.
add_action('rest_api_init', function () {
    register_rest_route('theme/v1', '/newsletter', [
        'methods' => 'POST',
        'callback' => __NAMESPACE__.'\\newsletter_subscribe',
        'permission_callback' => '__return_true',
        'args' => [
            'email' => [
                'required' => true,
                'type' => 'string',
                'sanitize_callback' => 'sanitize_email',
                'validate_callback' => fn ($v) => is_email($v),
            ],
        ],
    ]);
});

/**
 * Handle newsletter subscription.
 *
 * @return \WP_REST_Response|\WP_Error
 */
function newsletter_subscribe(\WP_REST_Request $request)
{
    $email = sanitize_email($request->get_param('email'));

    if (! is_email($email)) {
        return new \WP_Error('invalid_email', __('Indirizzo email non valido.', 'sage'), ['status' => 422]);
    }

    /**
     * Fires when a user subscribes to the newsletter.
     * Hook here to integrate with Mailchimp, Klaviyo, or any other ESP.
     *
     * @param  string  $email  The subscriber email address.
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
    // Front page always has a hero section (assembled in front-page.blade.php)
    if (is_front_page() && ! is_home()) {
        $classes[] = 'has-hero';

        return $classes;
    }

    if (! is_singular()) {
        return $classes;
    }

    // Singular: detect hero via first Gutenberg block
    $post = get_post();
    $blocks = $post ? parse_blocks($post->post_content) : [];
    $first = $blocks[0]['blockName'] ?? '';
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
 * Cap query — numero massimo di prodotti per query/blocco/shortcode.
 * Valore unico, cambiare qui si applica ovunque.
 */
const THEME_PRODUCT_QUERY_CAP = 24;

/**
 * Cap WooCommerce product queries so blocks/shortcodes never load all products at once.
 * Without this, an "All Products" block with 10k products causes memory exhaustion.
 */
add_filter('woocommerce_shortcode_products_query', function (array $query): array {
    if (empty($query['posts_per_page']) || (int) $query['posts_per_page'] < 0) {
        $query['posts_per_page'] = THEME_PRODUCT_QUERY_CAP;
    }

    return $query;
});

// Safe cap for product queries: avoids leaking a global posts_per_page override.
add_filter('posts_per_page', function ($posts_per_page, $query) {
    if (is_admin() || ! $query instanceof \WP_Query) {
        return $posts_per_page;
    }

    $post_type = $query->get('post_type');
    $is_product_query = $post_type === 'product'
        || (is_array($post_type) && in_array('product', $post_type, true));

    if (! $is_product_query) {
        return $posts_per_page;
    }

    $pp = (int) $posts_per_page;
    if ($pp <= 0 || $pp > THEME_PRODUCT_QUERY_CAP) {
        return THEME_PRODUCT_QUERY_CAP;
    }

    return $pp;
}, 20, 2);

// ── WooCommerce single product: layout tweaks ────────────────────────────────

// Rimuove il tab recensioni dalla pagina prodotto
add_filter('woocommerce_product_tabs', function (array $tabs): array {
    unset($tabs['reviews']);

    return $tabs;
});

// Move breadcrumb above the product div (before single-product summary hooks)
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

// Normalize breadcrumb markup so separators and current item can be styled reliably.
add_filter('woocommerce_breadcrumb_defaults', function (array $defaults): array {
    $defaults['delimiter'] = '<span class="breadcrumb-separator" aria-hidden="true">/</span>';

    return $defaults;
});

add_filter('woocommerce_get_breadcrumb', function (array $crumbs): array {
    if (empty($crumbs)) {
        return $crumbs;
    }

    // Remove link from last crumb (current page).
    $last = count($crumbs) - 1;
    $crumbs[$last][1] = '';

    return $crumbs;
});

// Wrap result-count + ordering in a flex row
add_action('woocommerce_before_shop_loop', function () {
    echo '<div class="shop-sort-bar">';
}, 19);
add_action('woocommerce_before_shop_loop', function () {
    echo '</div>';
}, 31);

// Change default columns: 2-col gallery on single product (WC default = 1/2 split via flex)
add_filter('woocommerce_product_thumbnails_columns', fn () => 4);

// Trust badges below add-to-cart form (outside <form class="cart">)
add_action('woocommerce_after_add_to_cart_form', function () {
    global $product;

    $is_physical = $product instanceof WC_Product
        && ! $product->is_virtual()
        && ! $product->is_downloadable();

    $trust_title = sanitize_text_field(get_theme_mod('single_trust_title', __('Perché scegliere noi', 'sage')));

    $badges = [
        ['icon' => 'M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z',
            'label' => sanitize_text_field(get_theme_mod('single_trust_secure', __('Pagamento sicuro', 'sage'))),
            'physical' => false,
        ],
        ['icon' => 'M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12',
            'label' => sanitize_text_field(get_theme_mod('single_trust_shipping', __('Spedizione rapida', 'sage'))),
            'physical' => true,
        ],
        ['icon' => 'M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99',
            'label' => sanitize_text_field(get_theme_mod('single_trust_returns', __('Resi gratuiti 30gg', 'sage'))),
            'physical' => true,
        ],
        ['icon' => 'M13.5 4.5c-1.44 0-2.773.62-3.694 1.629A5.003 5.003 0 0 0 6.112 4.5C3.795 4.5 1.875 6.358 1.875 8.7c0 5.25 7.93 9.75 7.93 9.75s7.945-4.5 7.945-9.75c0-2.342-1.92-4.2-4.25-4.2Z',
            'label' => sanitize_text_field(get_theme_mod('single_trust_happy_pet', __('Il tuo cane sarà felice', 'sage'))),
            'physical' => false,
        ],
    ];

    $visible = array_values(array_filter(
        $badges,
        fn ($b) => !empty($b['label']) && (! $b['physical'] || $is_physical)
    ));

    if (empty($visible)) {
        return;
    }

    echo '<section class="trust-badges mt-6 pt-5 border-t border-border" aria-label="'.esc_attr__('Vantaggi acquisto', 'sage').'">';

    if (! empty($trust_title)) {
        echo '<p class="text-[10px] font-semibold tracking-wider uppercase text-muted/60 mb-3">'.esc_html($trust_title).'</p>';
    }

    echo '<ul class="list-none m-0 p-0 flex flex-col gap-2.5">';

    // Badge items
    foreach ($visible as $b) {
        $icon_html = \Roots\view('components.icons.path', [
            'path' => (string) $b['icon'],
            'attributes' => new \Illuminate\View\ComponentAttributeBag([
                'class' => 'size-4 text-primary shrink-0',
                'stroke-width' => '1.5',
            ]),
        ])->render();

        printf(
            '<li class="flex items-center gap-2 text-muted text-xs">%s%s</li>',
            $icon_html,
            esc_html($b['label'])
        );
    }

    echo '</ul>';

    // Payment methods
    $payment_methods_raw = sanitize_text_field(get_theme_mod('single_payment_methods', 'Visa, Mastercard, PayPal, Apple Pay'));
    $payment_methods = array_values(array_filter(array_map('trim', explode(',', $payment_methods_raw))));

    echo '<div class="flex items-center gap-3 mt-1">';
    echo '<span class="text-[10px] font-semibold tracking-wider uppercase text-muted/60">'.esc_html__('Accettiamo', 'sage').'</span>';
    echo '<div class="flex items-center gap-2 text-muted/50 text-[10px] tracking-wide">';
    foreach ($payment_methods as $method) {
        echo '<span>'.esc_html($method).'</span>';
    }
    echo '</div></div>';

    echo '</section>';
}, 25);

// ── Performance: rimuovi asset inutili di WordPress ──────────────────────────

/**
 * Rimuovi emoji script/style di WordPress.
 * Risparmia ~15 KB e una DNS lookup a s.w.org per ogni pagina.
 */
add_action('init', function () {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    add_filter('tiny_mce_plugins', fn ($p) => array_diff($p ?? [], ['wpemoji']));
    add_filter('wp_resource_hints', fn ($hints, $relation_type) => $relation_type === 'dns-prefetch'
            ? array_filter($hints, fn ($h) => ! str_contains($h['href'] ?? '', 's.w.org'))
            : $hints,
        10, 2);
});

/**
 * Rimuovi jQuery Migrate dal frontend.
 * Il tema usa Alpine.js e non ha codice legacy che richieda jQuery Migrate.
 * Risparmia ~10 KB minificati.
 */
add_action('wp_default_scripts', function (\WP_Scripts $scripts) {
    if (! is_admin() && isset($scripts->registered['jquery'])) {
        $script = $scripts->registered['jquery'];
        if ($script->deps) {
            $script->deps = array_diff($script->deps, ['jquery-migrate']);
        }
    }
});

/**
 * Aggiungi fetchpriority="high" al logo/hero image (LCP hint).
 * WordPress 6.3+ già gestisce fetchpriority automaticamente per le immagini
 * al primo posto nel DOM, ma questo assicura il logo nel header.
 */
add_filter('wp_get_attachment_image_attributes', function (array $attr, \WP_Post $attachment): array {
    if (
        isset($attr['class']) &&
        str_contains($attr['class'], 'custom-logo')
    ) {
        $attr['fetchpriority'] = 'high';
        $attr['decoding'] = 'async';
    }

    return $attr;
}, 10, 2);

/**
 * Aggiungi defer/async ai script di terze parti non critici.
 * Gli script del tema (Vite) sono già gestiti con type="module" (implicitamente defer).
 */
add_filter('script_loader_tag', function (string $tag, string $handle): string {
    $defer_handles = [
        'wc-add-to-cart',
        'wc-cart-fragments',
        'jquery-blockui',
    ];
    if (in_array($handle, $defer_handles, true) && ! str_contains($tag, 'defer')) {
        $tag = str_replace(' src=', ' defer src=', $tag);
    }

    return $tag;
}, 10, 2);

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

/**
 * Detect the real client IP, honouring Cloudflare and common reverse-proxy headers.
 * REMOTE_ADDR is always the last hop (trusted), used as final fallback.
 *
 * @return string Sanitized IP address.
 */
function theme_get_client_ip(): string
{
    // Cloudflare passes the original IP in CF-Connecting-IP
    if (! empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        $ip = sanitize_text_field(wp_unslash($_SERVER['HTTP_CF_CONNECTING_IP']));
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            return $ip;
        }
    }

    // Standard reverse-proxy header (first entry is the client)
    if (! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $first = trim(explode(',', wp_unslash($_SERVER['HTTP_X_FORWARDED_FOR']))[0]);
        $ip = sanitize_text_field($first);
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return $ip;
        }
    }

    return sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
}

// Rate-limit REST endpoints: newsletter (5/min) and search (30/min per IP).
// Uses transients as a lightweight counter — no extra plugin needed.
add_filter('rest_pre_dispatch', function ($result, $server, \WP_REST_Request $request) {
    $route = $request->get_route();

    $limits = [
        '/theme/v1/newsletter' => ['max' => 5,  'prefix' => 'nl_rl_',     'window' => MINUTE_IN_SECONDS],
        '/theme/v1/search' => ['max' => 30, 'prefix' => 'srch_rl_',   'window' => MINUTE_IN_SECONDS],
    ];

    if (! isset($limits[$route])) {
        return $result;
    }

    $cfg = $limits[$route];
    $ip = theme_get_client_ip();
    $key = $cfg['prefix'].md5($ip);
    $hits = (int) get_transient($key);

    if ($hits >= $cfg['max']) {
        return new \WP_Error(
            'rate_limit',
            __('Troppe richieste. Riprova tra un minuto.', 'sage'),
            ['status' => 429]
        );
    }
    set_transient($key, $hits + 1, $cfg['window']);

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
 * Force WooCommerce pages to use resources/views/woocommerce.blade.php.
 *
 * WooCommerce hooks into `template_include` at priority 99 and overrides the
 * template to a file inside the WC plugin directory. Sage hooks at priority 100
 * but cannot match that path to any Blade view, so it falls back to the raw PHP
 * file — losing the theme layout entirely.
 *
 * We hook at priority 101 (after both WC and Sage) and force `sage.view` to
 * 'woocommerce' so index.php renders woocommerce.blade.php with the full layout.
 */
add_filter('template_include', function (string $template): string {
    if (
        function_exists('is_woocommerce') && (
            is_woocommerce() ||
            is_product_category() ||
            is_product_tag() ||
            is_product_taxonomy() ||
            is_cart() ||
            is_checkout() ||
            is_account_page()
        )
    ) {
        \Roots\app()->instance('sage.view', 'woocommerce');
    }

    return $template;
}, 101);

// ── [products_carousel] shortcode ─────────────────────────────────────────────
// Usage: [products_carousel title="Titolo" subtitle="Label" limit="8" category="" orderby="date" order="DESC" ids=""]
add_shortcode('products_carousel', function (array $atts): string {
    if (! function_exists('wc_get_product')) {
        return '';
    }

    $atts = shortcode_atts([
        'title' => __('I nostri prodotti', 'sage'),
        'subtitle' => '',
        'limit' => 8,
        'category' => '',
        'orderby' => 'date',
        'order' => 'DESC',
        'ids' => '',
    ], $atts, 'products_carousel');

    $limit = max(1, min(24, (int) $atts['limit']));
    $title = sanitize_text_field($atts['title']);
    $subtitle = sanitize_text_field($atts['subtitle']);
    $orderby = sanitize_key($atts['orderby']);
    $order = in_array(strtoupper((string) $atts['order']), ['ASC', 'DESC'], true)
        ? strtoupper((string) $atts['order'])
        : 'DESC';

    $query_args = [
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => $limit,
        'orderby' => $orderby,
        'order' => $order,
        'ignore_sticky_posts' => true,
    ];

    if (! empty($atts['category'])) {
        $query_args['tax_query'] = [[
            'taxonomy' => 'product_cat',
            'field' => 'slug',
            'terms' => array_map('sanitize_title', explode(',', (string) $atts['category'])),
        ]];
    }

    if (! empty($atts['ids'])) {
        $ids = array_values(array_filter(array_map('absint', explode(',', (string) $atts['ids']))));
        if (! empty($ids)) {
            $query_args['post__in'] = $ids;
            $query_args['orderby'] = 'post__in';
        }
    }

    $query = new \WP_Query($query_args);
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
        return '';
    }

    $has_many = count($products) > 1;

    ob_start(); ?>
    <section
      class="products-carousel-section py-16 md:py-20"
      data-products-carousel
      aria-label="<?php echo esc_attr($title); ?>"
    >
        <?php if ($title || $subtitle) { ?>
            <div class="container mb-8 md:mb-10">
                <div class="flex items-end justify-between gap-6">
                    <div>
                        <?php if ($subtitle) { ?>
                            <p class="section-label text-muted mb-2"><?php echo esc_html($subtitle); ?></p>
                        <?php } ?>
                        <h2 class="text-2xl md:text-3xl lg:text-4xl font-serif font-light text-ink leading-tight m-0">
                            <?php echo esc_html($title); ?>
                        </h2>
                    </div>
                    <?php if ($has_many) { ?>
                        <div class="flex items-center gap-2 shrink-0">
                            <button type="button" class="swiper-button-prev products-carousel__btn" aria-label="<?php esc_attr_e('Prodotto precedente', 'sage'); ?>">
                                <?php echo \Roots\view('components.icons.chevron-left', ['attributes' => new \Illuminate\View\ComponentAttributeBag(['class' => 'size-4', 'stroke-width' => '1.5'])])->render(); ?>
                            </button>
                            <button type="button" class="swiper-button-next products-carousel__btn" aria-label="<?php esc_attr_e('Prodotto successivo', 'sage'); ?>">
                                <?php echo \Roots\view('components.icons.chevron-right', ['attributes' => new \Illuminate\View\ComponentAttributeBag(['class' => 'size-4', 'stroke-width' => '1.5'])])->render(); ?>
                            </button>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>

        <div class="products-carousel__outer container">

                <div class="js-products-swiper swiper">
                    <div class="swiper-wrapper items-stretch">
                        <?php foreach ($products as $product) { ?>
                            <div class="swiper-slide h-auto">
                                <?php echo \Roots\view('partials.product-card', ['product' => $product])->render(); ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            
            <div class="swiper-scrollbar products-carousel__scrollbar"></div>
        </div>

    </section>
    <?php
    return ob_get_clean();
});

/**
 * WCAG fix: when a wp:button block uses has-primary-color on a dark background,
 * remove the inline color style so our CSS !important rule can set white text.
 * The editor preserves the class; only the inline style (which would override
 * our CSS) is stripped from the output.
 */
add_filter('render_block_core/button', function (string $block_content, array $block): string {
    // Only target buttons with primary-color text AND a dark/ink background
    if (
        str_contains($block_content, 'has-primary-color')
        && (str_contains($block_content, 'has-dark-background-color') || str_contains($block_content, 'has-ink-background-color'))
    ) {
        // Strip standalone 'color:' property from inline style attributes.
        // Negative lookbehind (?<![a-z-]) ensures we don't remove background-color,
        // border-color, text-decoration-color, etc. — only the bare 'color:' property.
        $block_content = preg_replace(
            '/(?<![a-z-])color:\s*[^;}"]+;?/i',
            '',
            $block_content,
        );
        // Clean up any empty or whitespace-only style attributes left behind
        $block_content = preg_replace('/\s+style="\s*;?\s*"/', '', $block_content);
        $block_content = str_replace(' style=""', '', $block_content);
    }

    return $block_content;
}, 10, 2);

/**
 * Recently Viewed — track current product on single product pages.
 * Outputs an inline <script> at wp_footer that calls window.trackProductView()
 * with the current product's data so the recently-viewed component can read it.
 */
add_action('wp_footer', function () {
    if (! is_product() || ! function_exists('wc_get_product')) {
        return;
    }

    $product = wc_get_product(get_the_ID());
    if (! $product) {
        return;
    }

    $thumb_url = get_the_post_thumbnail_url($product->get_id(), 'woocommerce_thumbnail') ?: '';
    $price = html_entity_decode(wp_strip_all_tags($product->get_price_html()), ENT_QUOTES, 'UTF-8');

    $data = wp_json_encode([
        'id' => $product->get_id(),
        'url' => get_permalink($product->get_id()),
        'title' => $product->get_name(),
        'thumb' => $thumb_url,
        'price' => $price,
    ]);

    echo '<script>window.trackProductView && window.trackProductView('.$data.');</script>'."\n";
}, 20);
