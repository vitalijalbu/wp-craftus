<?php

/**
 * WordPress Customizer options for 4 Zampe theme.
 *
 * Provides admin-configurable settings for:
 *  - Social media profile URLs
 *  - Contact/CTA link override
 *  - Footer tagline
 */

namespace App;

add_action('customize_register', function (\WP_Customize_Manager $wp_customize): void {

    // ── Section: Social Media ─────────────────────────────────────────────────
    $wp_customize->add_section('4zampe_social', [
        'title'       => __('Social Media', 'sage'),
        'description' => __('URL dei profili social. Lascia vuoto per nascondere l\'icona.', 'sage'),
        'priority'    => 120,
    ]);

    $social_networks = [
        'instagram' => ['label' => 'Instagram', 'priority' => 10],
        'facebook'  => ['label' => 'Facebook',  'priority' => 20],
        'tiktok'    => ['label' => 'TikTok',    'priority' => 30],
        'youtube'   => ['label' => 'YouTube',   'priority' => 40],
    ];

    foreach ($social_networks as $slug => $config) {
        $wp_customize->add_setting("social_{$slug}", [
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        ]);
        $wp_customize->add_control("social_{$slug}", [
            'label'       => $config['label'],
            'section'     => '4zampe_social',
            'type'        => 'url',
            'priority'    => $config['priority'],
            'input_attrs' => ['placeholder' => "https://www.{$slug}.com/nomepagina"],
        ]);
    }

    // ── Section: Theme Options ────────────────────────────────────────────────
    $wp_customize->add_section('4zampe_theme', [
        'title'    => __('Opzioni Tema', 'sage'),
        'priority' => 125,
    ]);

    // CTA button URL override
    $wp_customize->add_setting('cta_url', [
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('cta_url', [
        'label'       => __('URL pulsante "Contattaci"', 'sage'),
        'description' => __('Lascia vuoto per usare /contatti.', 'sage'),
        'section'     => '4zampe_theme',
        'type'        => 'url',
        'priority'    => 10,
    ]);

    // Footer tagline
    $wp_customize->add_setting('footer_tagline', [
        'default'           => __('Il tuo punto di riferimento per la cura e il benessere del tuo animale domestico.', 'sage'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ]);
    $wp_customize->add_control('footer_tagline', [
        'label'    => __('Tagline footer', 'sage'),
        'section'  => '4zampe_theme',
        'type'     => 'textarea',
        'priority' => 20,
    ]);

    // Newsletter heading
    $wp_customize->add_setting('newsletter_heading', [
        'default'           => __('Offerte esclusive, novità e consigli per il tuo animale.', 'sage'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ]);
    $wp_customize->add_control('newsletter_heading', [
        'label'    => __('Titolo newsletter footer', 'sage'),
        'section'  => '4zampe_theme',
        'type'     => 'text',
        'priority' => 30,
    ]);
});

/**
 * Helper: return the CTA URL, falling back to /contatti.
 */
function theme_cta_url(): string {
    $override = get_theme_mod('cta_url', '');
    return $override ? esc_url($override) : esc_url(home_url('/contatti'));
}
