<?php

/**
 * Theme setup.
 */

namespace App;

use Illuminate\Support\Facades\Vite;

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
 * Disable on-demand block asset loading.
 *
 * @link https://core.trac.wordpress.org/ticket/61965
 */
add_filter('should_load_separate_core_block_assets', '__return_false');

/**
 * Register the initial theme setup.
 *
 * @return void
 */
add_action('after_setup_theme', function () {
    /**
     * Disable full-site editing support.
     *
     * @link https://wptavern.com/gutenberg-10-5-embeds-pdfs-adds-verse-block-color-options-and-introduces-new-patterns
     */
    remove_theme_support('block-templates');

    /**
     * Register the navigation menus.
     *
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus([
        'primary_navigation' => __('Menu Principale', 'sage'),
        'footer_navigation'  => __('Menu Footer', 'sage'),
    ]);

    /**
     * WooCommerce support.
     */
    add_theme_support('woocommerce', [
        'thumbnail_image_width' => 600,
        'single_image_width'    => 800,
        'product_grid'          => [
            'default_rows'    => 3,
            'min_rows'        => 1,
            'default_columns' => 3,
            'min_columns'     => 1,
            'max_columns'     => 4,
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
     * Enable responsive embed support.
     *
     * @link https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#responsive-embedded-content
     */
    add_theme_support('responsive-embeds');

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
        'label'       => __('Theme – Sezioni', 'sage'),
        'description' => __('Sezioni hero, CTA, intro e layout.', 'sage'),
    ]);
    register_block_pattern_category('theme-cards', [
        'label'       => __('Theme – Card', 'sage'),
        'description' => __('Card per prodotti, servizi e blog.', 'sage'),
    ]);
    register_block_pattern_category('theme-carousel', [
        'label'       => __('Theme – Carousel', 'sage'),
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
add_filter('loop_shop_per_page', fn() => 12);
add_filter('loop_shop_columns', fn() => 3);

/**
 * Load Google Fonts asynchronously (non-render-blocking).
 * Uses the print/onload trick: browser downloads as print, swaps to all on load.
 * A <noscript> fallback ensures fonts load without JS.
 * preconnect hints reduce DNS + TLS handshake latency.
 */
add_action('wp_head', function () {
    $font_url = 'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap';
    $url      = esc_url($font_url);
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
    echo '<link rel="preload" as="style" href="' . $url . '">' . "\n";
    echo '<link rel="stylesheet" media="print" onload="this.media=\'all\'" href="' . $url . '">' . "\n";
    echo '<noscript><link rel="stylesheet" href="' . $url . '"></noscript>' . "\n";
}, 1);

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

    $site_name   = esc_attr(get_bloginfo('name'));
    $site_url    = esc_url(home_url('/'));
    $og_type     = is_singular() ? 'article' : 'website';
    $og_url      = esc_url(get_canonical_url() ?: (is_singular() ? get_permalink() : $site_url));
    $og_title    = esc_attr(wp_get_document_title());
    $og_desc     = '';
    $og_image    = '';

    if (is_singular() && isset($post)) {
        $og_desc  = has_excerpt($post) ? esc_attr(get_the_excerpt($post)) : esc_attr(wp_trim_words(strip_shortcodes($post->post_content), 30, ''));
        $thumb_id = get_post_thumbnail_id($post);
        if ($thumb_id) {
            $og_image = esc_url(wp_get_attachment_image_url($thumb_id, 'large'));
        }
    } elseif (is_archive() || is_home()) {
        $og_desc = esc_attr(get_bloginfo('description'));
    }

    if (! $og_image) {
        // Fallback: use custom logo if set
        $logo_id  = get_theme_mod('custom_logo');
        $og_image = $logo_id ? esc_url(wp_get_attachment_image_url($logo_id, 'large')) : '';
    }

    echo '<meta property="og:site_name" content="' . $site_name . '">' . "\n";
    echo '<meta property="og:type"      content="' . $og_type . '">' . "\n";
    echo '<meta property="og:url"       content="' . $og_url . '">' . "\n";
    echo '<meta property="og:title"     content="' . $og_title . '">' . "\n";
    if ($og_desc) {
        echo '<meta property="og:description" content="' . $og_desc . '">' . "\n";
        echo '<meta name="description"         content="' . $og_desc . '">' . "\n";
    }
    if ($og_image) {
        echo '<meta property="og:image" content="' . $og_image . '">' . "\n";
    }
    echo '<meta name="twitter:card"  content="summary_large_image">' . "\n";
    echo '<meta name="twitter:title" content="' . $og_title . '">' . "\n";
    if ($og_desc) {
        echo '<meta name="twitter:description" content="' . $og_desc . '">' . "\n";
    }
    if ($og_image) {
        echo '<meta name="twitter:image" content="' . $og_image . '">' . "\n";
    }
}, 5);

/**
 * Register custom blocks (server-side rendered via render.php).
 * Each block lives in blocks/{name}/ with a block.json + render.php.
 */
add_action('init', function () {
    $blocks = ['hero', 'testimonial', 'stat', 'icon-box'];
    foreach ($blocks as $name) {
        $dir = get_template_directory() . "/blocks/{$name}";
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
