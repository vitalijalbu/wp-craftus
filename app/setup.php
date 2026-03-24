<?php

/**
 * Theme setup.
 */

namespace App;

use Illuminate\Support\Facades\Vite;

/**
 * Return the URL of the static placeholder image.
 */
function get_placeholder_url(): string
{
    return get_template_directory_uri().'/public/placeholder.png';
}

/**
 * Inject styles into the block editor.
 *
 * @return array
 */
add_filter('block_editor_settings_all', function ($settings) {
    $style = Vite::asset('resources/css/editor.css');

    $settings['styles'][] = [
        'css' => "@import url('{$style}')",
    ];

    return $settings;
});

/**
 * Inject scripts into the block editor.
 *
 * @return void
 */
add_action('admin_head', function () {
    if (! get_current_screen()?->is_block_editor()) {
        return;
    }

    // Load Google Fonts for the block editor via <link> (avoids CSS @import order warnings).
    $font_url = esc_url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap');
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">'."\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>'."\n";
    echo '<link rel="stylesheet" href="'.$font_url.'">'."\n";

    if (! Vite::isRunningHot()) {
        $dependencies = json_decode(Vite::content('editor.deps.json'));

        foreach ($dependencies as $dependency) {
            if (! wp_script_is($dependency)) {
                wp_enqueue_script($dependency);
            }
        }
    }
    echo Vite::withEntryPoints([
        'resources/js/editor.js',
    ])->toHtml();
});

/**
 * Use the generated theme.json file.
 *
 * @return string
 */
add_filter('theme_file_path', function ($path, $file) {
    return $file === 'theme.json'
        ? public_path('build/assets/theme.json')
        : $path;
}, 10, 2);

/**
 * Enable on-demand block asset loading (per-block CSS).
 * WordPress loads only the CSS for blocks actually rendered on each page,
 * instead of dumping all block styles in one file.
 * Bug in WP <6.7 (ticket/61965) is fixed — safe to enable.
 *
 * @link https://core.trac.wordpress.org/ticket/61965
 */
add_filter('should_load_separate_core_block_assets', '__return_true');

/**
 * Register the initial theme setup.
 *
 * @return void
 */
add_action('after_setup_theme', function () {
    /**
     * Enable Global Styles panel (Site Editor → paintbrush icon).
     * Allows the client to change colors, fonts, and spacing from the WP admin
     * without touching code. block-templates support is intentionally kept active
     * so the Site Editor and Global Styles are fully available.
     * Blade templates still take precedence over block templates when no matching
     * HTML template exists in /templates/.
     *
     * @link https://developer.wordpress.org/news/2024/12/bridging-the-gap-hybrid-themes/
     */

    /**
     * Apply WordPress's built-in block CSS defaults (gaps, margins, etc.).
     * Required for consistent styling of core blocks without custom overrides.
     *
     * @link https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#default-block-styles
     */
    add_theme_support('wp-block-styles');

    /**
     * Register the navigation menus.
     *
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus([
        'primary_navigation' => __('Menu Principale', 'sage'),
        'footer_navigation' => __('Menu Footer — Esplora', 'sage'),
        'footer_info_navigation' => __('Menu Footer — Informazioni', 'sage'),
        'footer_legal_navigation' => __('Menu Footer — Legal (Privacy, Cookie, Termini)', 'sage'),
    ]);

    /**
     * WooCommerce support.
     */
    add_theme_support('woocommerce', [
        'thumbnail_image_width' => 600,
        'single_image_width' => 800,
        'product_grid' => [
            'default_rows' => 3,
            'min_rows' => 1,
            'default_columns' => 3,
            'min_columns' => 1,
            'max_columns' => 4,
        ],
    ]);
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');

    /**
     * Disable the default block patterns.
     *
     * @link https://developer.wordpress.org/block-editor/developers/themes/theme-support/#disabling-the-default-block-patterns
     */
    remove_theme_support('core-block-patterns');

    /**
     * Enable plugins to manage the document title.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support('title-tag');

    /**
     * Enable post thumbnail support.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    /**
     * Enable wide and full-width block alignment.
     *
     * @link https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#wide-alignment
     */
    add_theme_support('align-wide');

    /**
     * Enable responsive embed support.
     *
     * @link https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#responsive-embedded-content
     */
    add_theme_support('responsive-embeds');

    /**
     * Enable editor stylesheet support (hybrid theme pattern).
     * Lets WordPress know this theme has an editor stylesheet
     * so it can apply it to the block editor properly.
     *
     * @link https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#editor-styles
     */
    add_theme_support('editor-styles');

    /**
     * Enable HTML5 markup support.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support('html5', [
        'caption',
        'comment-form',
        'comment-list',
        'gallery',
        'search-form',
        'script',
        'style',
    ]);

    /**
     * Enable selective refresh for widgets in customizer.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#customize-selective-refresh-widgets
     */
    add_theme_support('customize-selective-refresh-widgets');

    /**
     * Register block pattern categories.
     */
    register_block_pattern_category('theme-sections', [
        'label' => __('Theme – Sezioni', 'sage'),
        'description' => __('Sezioni hero, CTA, intro e layout.', 'sage'),
    ]);
    register_block_pattern_category('theme-cards', [
        'label' => __('Theme – Card', 'sage'),
        'description' => __('Card per prodotti, servizi e blog.', 'sage'),
    ]);
    register_block_pattern_category('theme-carousel', [
        'label' => __('Theme – Carousel', 'sage'),
        'description' => __('Sezioni con slider e caroselli.', 'sage'),
    ]);
}, 20);

/**
 * Disable WooCommerce default styles (we use our own in app.css).
 */
add_filter('woocommerce_enqueue_styles', '__return_empty_array');

/**
 * WooCommerce: products per page and columns.
 */
add_filter('loop_shop_per_page', fn () => 12);
add_filter('loop_shop_columns', fn () => 3);

/**
 * Load Google Fonts asynchronously (non-render-blocking).
 * Uses the print/onload trick: browser downloads as print, swaps to all on load.
 * A <noscript> fallback ensures fonts load without JS.
 * preconnect hints reduce DNS + TLS handshake latency.
 */
add_action('wp_head', function () {
    $font_url = 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap';
    $url = esc_url($font_url);
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">'."\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>'."\n";
    echo '<link rel="preload" as="style" href="'.$url.'">'."\n";
    echo '<link rel="stylesheet" media="print" onload="this.media=\'all\'" href="'.$url.'">'."\n";
    echo '<noscript><link rel="stylesheet" href="'.$url.'"></noscript>'."\n";
}, 1);

/**
 * JSON-LD structured data for singular posts/pages.
 * Outputs Article or WebPage schema when no SEO plugin is active.
 */
add_action('wp_head', function () {
    if (
        defined('WPSEO_VERSION')
        || defined('RANK_MATH_VERSION')
        || defined('AIOSEO_VERSION')
    ) {
        return;
    }

    if (! is_singular()) {
        return;
    }

    global $post;
    if (! $post) {
        return;
    }

    $post_type = get_post_type($post);
    $schema_type = match ($post_type) {
        'post' => 'Article',
        'portfolio' => 'CreativeWork',
        'team' => 'Person',
        default => 'WebPage',
    };

    $data = [
        '@context' => 'https://schema.org',
        '@type' => $schema_type,
        'headline' => get_the_title($post),
        'url' => get_permalink($post),
    ];

    if (in_array($schema_type, ['Article', 'CreativeWork'], true)) {
        $data['datePublished'] = get_the_date('c', $post);
        $data['dateModified'] = get_the_modified_date('c', $post);
        $author_id = (int) get_post_field('post_author', $post);
        $data['author'] = [
            '@type' => 'Person',
            'name' => get_the_author_meta('display_name', $author_id),
        ];
    }

    if (has_excerpt($post)) {
        $data['description'] = wp_strip_all_tags(get_the_excerpt($post));
    }

    $thumb_id = get_post_thumbnail_id($post);
    if ($thumb_id) {
        $img = wp_get_attachment_image_src($thumb_id, 'large');
        if ($img) {
            $data['image'] = $img[0];
        }
    }

    $site = [
        '@type' => 'Organization',
        'name' => get_bloginfo('name'),
        'url' => home_url('/'),
    ];
    $logo_id = get_theme_mod('custom_logo');
    if ($logo_id) {
        $logo = wp_get_attachment_image_src($logo_id, 'full');
        if ($logo) {
            $site['logo'] = $logo[0];
        }
    }
    $data['publisher'] = $site;

    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    echo '<script type="application/ld+json">'.wp_json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE).'</script>'."\n";
}, 5);

/**
 * OG / Twitter Card meta tags fallback (active only when no SEO plugin is detected).
 * Yoast SEO, Rank Math, and All in One SEO each define their own OG output —
 * this hook fires at priority 5 and bails early if any of them is active.
 */
add_action('wp_head', function () {
    // Bail if a full-featured SEO plugin is handling OG tags.
    if (
        defined('WPSEO_VERSION')        // Yoast SEO
        || defined('RANK_MATH_VERSION') // Rank Math
        || defined('AIOSEO_VERSION')    // All in One SEO
    ) {
        return;
    }

    global $post;

    $site_name = esc_attr(get_bloginfo('name'));
    $site_url = esc_url(home_url('/'));
    $og_type = is_singular() ? 'article' : 'website';
    $og_url = esc_url(is_singular() ? get_permalink() : $site_url);
    $og_title = esc_attr(wp_get_document_title());
    $og_desc = '';
    $og_image = '';

    if (is_singular() && isset($post)) {
        $og_desc = has_excerpt($post) ? esc_attr(get_the_excerpt($post)) : esc_attr(wp_trim_words(strip_shortcodes($post->post_content), 30, ''));
        $thumb_id = get_post_thumbnail_id($post);
        if ($thumb_id) {
            $og_image = esc_url(wp_get_attachment_image_url($thumb_id, 'large'));
        }
    } elseif (is_archive() || is_home()) {
        $og_desc = esc_attr(get_bloginfo('description'));
    }

    if (! $og_image) {
        // Fallback: use custom logo if set
        $logo_id = get_theme_mod('custom_logo');
        $og_image = $logo_id ? esc_url(wp_get_attachment_image_url($logo_id, 'large')) : '';
    }

    echo '<meta property="og:site_name" content="'.$site_name.'">'."\n";
    echo '<meta property="og:type"      content="'.$og_type.'">'."\n";
    echo '<meta property="og:url"       content="'.$og_url.'">'."\n";
    echo '<meta property="og:title"     content="'.$og_title.'">'."\n";
    if ($og_desc) {
        echo '<meta property="og:description" content="'.$og_desc.'">'."\n";
        echo '<meta name="description"         content="'.$og_desc.'">'."\n";
    }
    if ($og_image) {
        echo '<meta property="og:image" content="'.$og_image.'">'."\n";
    }
    echo '<meta name="twitter:card"  content="summary_large_image">'."\n";
    echo '<meta name="twitter:title" content="'.$og_title.'">'."\n";
    if ($og_desc) {
        echo '<meta name="twitter:description" content="'.$og_desc.'">'."\n";
    }
    if ($og_image) {
        echo '<meta name="twitter:image" content="'.$og_image.'">'."\n";
    }
}, 5);

/**
 * Register a custom block category so theme blocks appear at the top
 * of the inserter under their own labelled group.
 */
add_filter('block_categories_all', function (array $categories): array {
    return array_merge(
        [[
            'slug' => 'theme',
            'title' => __('Theme', 'sage'),
            'icon' => null,
        ]],
        $categories
    );
}, 10, 1);

/**
 * Register custom blocks (server-side rendered via render.php).
 * Each block lives in blocks/{name}/ with a block.json + render.php.
 */
add_action('init', function () {
    $blocks = ['hero', 'testimonial', 'stat', 'icon-box'];
    foreach ($blocks as $name) {
        $dir = get_template_directory()."/blocks/{$name}";
        if (is_dir($dir)) {
            register_block_type($dir);
        }
    }
});

/**
 * Register the theme sidebars.
 *
 * @return void
 */
add_action('widgets_init', function () {
    $config = [
        'before_widget' => '<section class="widget %1$s %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ];

    register_sidebar([
        'name' => __('Primary', 'sage'),
        'id' => 'sidebar-primary',
    ] + $config);

    register_sidebar([
        'name' => __('Footer', 'sage'),
        'id' => 'sidebar-footer',
    ] + $config);
});

// ── Mega-menu checkbox on nav menu items ──────────────────────────────────────
// Adds a "Dropdown (mega menu)" checkbox to each item in Appearance > Menus.
// When checked, the item renders as a dropdown button (not a link) in the header,
// showing its child menu items in a panel.

add_action('wp_nav_menu_item_custom_fields', function (int $item_id, \WP_Post $item): void {
    $checked = get_post_meta($item_id, '_menu_item_megamenu', true) === '1';
    printf(
        '<p class="field-megamenu description description-wide" style="margin-top:8px">
            <label for="edit-menu-item-megamenu-%1$d">
                <input type="checkbox" id="edit-menu-item-megamenu-%1$d"
                       name="menu-item-megamenu[%1$d]" value="1" %2$s style="margin-right:5px">
                %3$s
            </label>
        </p>',
        $item_id,
        checked($checked, true, false),
        esc_html__('Mostra come dropdown (mega menu)', 'sage')
    );
}, 10, 2);

add_action('wp_update_nav_menu_item', function (int $menu_id, int $item_id): void {
    // phpcs:ignore WordPress.Security.NonceVerification
    $value = isset($_POST['menu-item-megamenu'][$item_id]) ? '1' : '';
    update_post_meta($item_id, '_menu_item_megamenu', $value);
}, 10, 2);
