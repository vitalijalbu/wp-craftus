<?php

/**
 * WordPress Customizer options for Sage theme.
 *
 * Provides admin-configurable settings for:
 *  - Social media profile URLs
 *  - Contact/CTA link override
 *  - Footer tagline
 */

namespace App;

add_action('customize_register', function (\WP_Customize_Manager $wp_customize): void {

    // ── Section: Social Media ─────────────────────────────────────────────────
    $wp_customize->add_section('theme_social', [
        'title' => __('Social Media', 'sage'),
        'description' => __('URL dei profili social. Lascia vuoto per nascondere l\'icona.', 'sage'),
        'priority' => 120,
    ]);

    $social_networks = [
        'instagram' => ['label' => 'Instagram', 'priority' => 10],
        'facebook' => ['label' => 'Facebook',  'priority' => 20],
        'tiktok' => ['label' => 'TikTok',    'priority' => 30],
        'youtube' => ['label' => 'YouTube',   'priority' => 40],
        'whatsapp' => ['label' => 'WhatsApp (numero con prefisso, es. +393401234567)', 'priority' => 50],
    ];

    foreach ($social_networks as $slug => $config) {
        $wp_customize->add_setting("social_{$slug}", [
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport' => 'refresh',
        ]);
        $wp_customize->add_control("social_{$slug}", [
            'label' => $config['label'],
            'section' => 'theme_social',
            'type' => 'url',
            'priority' => $config['priority'],
            'input_attrs' => ['placeholder' => "https://www.{$slug}.com/nomepagina"],
        ]);
    }

    // ── Section: Theme Options ────────────────────────────────────────────────
    $wp_customize->add_section('theme_theme', [
        'title' => __('Opzioni Tema', 'sage'),
        'priority' => 125,
    ]);

    // CTA button label + URL
    $wp_customize->add_setting('header_cta_label', [
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('header_cta_label', [
        'label' => __('Testo pulsante CTA header', 'sage'),
        'description' => __('Lascia vuoto per nascondere il pulsante.', 'sage'),
        'section' => 'theme_theme',
        'type' => 'text',
        'priority' => 10,
    ]);

    $wp_customize->add_setting('cta_url', [
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport' => 'refresh',
    ]);
    $wp_customize->add_control('cta_url', [
        'label' => __('URL pulsante CTA header', 'sage'),
        'description' => __('Lascia vuoto per usare /contatti.', 'sage'),
        'section' => 'theme_theme',
        'type' => 'url',
        'priority' => 11,
    ]);

    // Footer tagline
    $wp_customize->add_setting('footer_tagline', [
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('footer_tagline', [
        'label' => __('Tagline footer', 'sage'),
        'description' => __('Lascia vuoto per nascondere.', 'sage'),
        'section' => 'theme_theme',
        'type' => 'textarea',
        'priority' => 20,
    ]);

    // Newsletter heading
    $wp_customize->add_setting('newsletter_heading', [
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('newsletter_heading', [
        'label' => __('Titolo newsletter footer', 'sage'),
        'description' => __('Visibile solo se newsletter attiva.', 'sage'),
        'section' => 'theme_theme',
        'type' => 'text',
        'priority' => 30,
    ]);

    // Free shipping threshold
    $wp_customize->add_setting('free_shipping_threshold', [
        'default' => 0,
        'sanitize_callback' => 'absint',
        'transport' => 'refresh',
    ]);
    $wp_customize->add_control('free_shipping_threshold', [
        'label' => __('Soglia spedizione gratuita (€)', 'sage'),
        'description' => __('0 = barra nascosta. Mostra una progress bar nel carrello verso la spedizione gratuita.', 'sage'),
        'section' => 'theme_theme',
        'type' => 'number',
        'priority' => 22,
        'input_attrs' => ['min' => 0, 'step' => 1],
    ]);

    // Newsletter active toggle
    $wp_customize->add_setting('newsletter_active', [
        'default' => false,
        'sanitize_callback' => 'rest_sanitize_boolean',
    ]);
    $wp_customize->add_control('newsletter_active', [
        'label' => __('Mostra newsletter nel footer', 'sage'),
        'section' => 'theme_theme',
        'type' => 'checkbox',
        'priority' => 25,
    ]);

    // ── Section: Contatti ────────────────────────────────────────────────────
    $wp_customize->add_section('theme_contact', [
        'title' => __('Informazioni di contatto', 'sage'),
        'priority' => 128,
    ]);

    foreach ([
        ['contact_address',        __('Indirizzo', 'sage'), 'textarea', 'sanitize_textarea_field'],
        ['contact_phone',          __('Telefono', 'sage'), 'text',     'sanitize_text_field'],
        ['contact_email',          __('Email', 'sage'), 'email',    'sanitize_email'],
        ['contact_hours',          __('Orari apertura', 'sage'), 'textarea', 'sanitize_textarea_field'],
        ['contact_map_embed_url',  __('URL embed mappa', 'sage'), 'url',      'esc_url_raw'],
    ] as [$key, $label, $type, $sanitize]) {
        $wp_customize->add_setting($key, [
            'default' => '',
            'sanitize_callback' => $sanitize,
            'transport' => 'refresh',
        ]);
        $wp_customize->add_control($key, [
            'label' => $label,
            'section' => 'theme_contact',
            'type' => $type,
        ]);
    }

    // ── Section: Announcement Bar ────────────────────────────────────────────
    $wp_customize->add_section('theme_announcement', [
        'title' => __('Barra Annunci', 'sage'),
        'priority' => 115,
    ]);

    $wp_customize->add_setting('announcement_bar_active', [
        'default' => false,
        'sanitize_callback' => 'rest_sanitize_boolean',
    ]);
    $wp_customize->add_control('announcement_bar_active', [
        'label' => __('Mostra barra annunci', 'sage'),
        'section' => 'theme_announcement',
        'type' => 'checkbox',
    ]);

    $wp_customize->add_setting('announcement_bar_text', [
        'default' => '',
        'sanitize_callback' => 'wp_kses_post',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('announcement_bar_text', [
        'label' => __('Testo annuncio', 'sage'),
        'description' => __('Puoi usare <strong> e <em>.', 'sage'),
        'section' => 'theme_announcement',
        'type' => 'textarea',
    ]);

    $wp_customize->add_setting('announcement_bar_cta_text', [
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('announcement_bar_cta_text', [
        'label' => __('Testo CTA', 'sage'),
        'section' => 'theme_announcement',
        'type' => 'text',
    ]);

    $wp_customize->add_setting('announcement_bar_cta_url', [
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    $wp_customize->add_control('announcement_bar_cta_url', [
        'label' => __('URL CTA', 'sage'),
        'section' => 'theme_announcement',
        'type' => 'url',
    ]);
});

/**
 * Helper: return the CTA URL, falling back to a filterable slug.
 * Override the slug per-project: add_filter('theme_cta_fallback_path', fn() => '/contact');
 */
function theme_cta_url(): string
{
    $override = get_theme_mod('cta_url', '');
    if ($override) {
        return esc_url($override);
    }

    $fallback_path = apply_filters('theme_cta_fallback_path', '/contatti');

    return esc_url(home_url($fallback_path));
}

/**
 * Helper: return the WhatsApp URL from the customizer social_whatsapp field.
 * Returns empty string if not set.
 */
function theme_whatsapp_url(): string
{
    $number = get_theme_mod('social_whatsapp', '');
    if (! $number) {
        return '';
    }
    $number = preg_replace('/[^+\d]/', '', $number);

    return esc_url('https://wa.me/'.ltrim($number, '+'));
}

/**
 * Helper: return the CTA button label.
 */
function theme_cta_label(): string
{
    return sanitize_text_field(get_theme_mod('header_cta_label', __('Contattaci', 'sage')));
}
