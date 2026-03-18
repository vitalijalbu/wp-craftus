<?php

/**
 * Custom Post Types & Taxonomies
 *
 * Registered CPTs:
 *  - portfolio  → Portfolio / Progetti
 *  - team       → Team / Staff
 *  - faq        → FAQ
 *
 * Taxonomies:
 *  - portfolio_category  → assigned to portfolio
 *  - team_department     → assigned to team
 *  - faq_category        → assigned to faq
 *
 * Flush rewrite rules: Settings → Permalinks → Save (once after activation).
 */

namespace App;

add_action('init', function (): void {

    // ── Portfolio ─────────────────────────────────────────────────────────────
    register_post_type('portfolio', [
        'labels' => [
            'name'               => __('Portfolio',            'sage'),
            'singular_name'      => __('Progetto',             'sage'),
            'add_new_item'       => __('Aggiungi progetto',    'sage'),
            'edit_item'          => __('Modifica progetto',    'sage'),
            'view_item'          => __('Vedi progetto',        'sage'),
            'search_items'       => __('Cerca progetti',       'sage'),
            'not_found'          => __('Nessun progetto.',     'sage'),
            'not_found_in_trash' => __('Nessun progetto nel cestino.', 'sage'),
        ],
        'public'       => true,
        'has_archive'  => true,
        'rewrite'      => ['slug' => 'portfolio', 'with_front' => false],
        'menu_icon'    => 'dashicons-portfolio',
        'menu_position'=> 22,
        'supports'     => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
        'show_in_rest' => true,
    ]);

    register_taxonomy('portfolio_category', 'portfolio', [
        'labels' => [
            'name'          => __('Categorie Portfolio', 'sage'),
            'singular_name' => __('Categoria Portfolio', 'sage'),
        ],
        'public'       => true,
        'hierarchical' => true,
        'rewrite'      => ['slug' => 'portfolio-categoria'],
        'show_in_rest' => true,
    ]);

    // ── Team ──────────────────────────────────────────────────────────────────
    register_post_type('team', [
        'labels' => [
            'name'               => __('Team',                  'sage'),
            'singular_name'      => __('Membro del team',       'sage'),
            'add_new_item'       => __('Aggiungi membro',        'sage'),
            'edit_item'          => __('Modifica membro',        'sage'),
            'view_item'          => __('Vedi membro',            'sage'),
            'search_items'       => __('Cerca nel team',         'sage'),
            'not_found'          => __('Nessun membro trovato.', 'sage'),
            'not_found_in_trash' => __('Nessun membro nel cestino.', 'sage'),
        ],
        'public'        => true,
        'has_archive'   => false,
        'rewrite'       => ['slug' => 'team', 'with_front' => false],
        'menu_icon'     => 'dashicons-groups',
        'menu_position' => 23,
        'supports'      => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes'],
        'show_in_rest'  => true,
    ]);

    register_taxonomy('team_department', 'team', [
        'labels' => [
            'name'          => __('Reparti',  'sage'),
            'singular_name' => __('Reparto',  'sage'),
        ],
        'public'       => true,
        'hierarchical' => true,
        'rewrite'      => ['slug' => 'reparto'],
        'show_in_rest' => true,
    ]);

    // ── FAQ ───────────────────────────────────────────────────────────────────
    register_post_type('faq', [
        'labels' => [
            'name'               => __('FAQ',               'sage'),
            'singular_name'      => __('Domanda FAQ',       'sage'),
            'add_new_item'       => __('Aggiungi domanda',  'sage'),
            'edit_item'          => __('Modifica domanda',  'sage'),
            'view_item'          => __('Vedi domanda',      'sage'),
            'search_items'       => __('Cerca nelle FAQ',   'sage'),
            'not_found'          => __('Nessuna FAQ.',      'sage'),
            'not_found_in_trash' => __('Nessuna FAQ nel cestino.', 'sage'),
        ],
        'public'        => true,
        'has_archive'   => false,
        'rewrite'       => ['slug' => 'faq', 'with_front' => false],
        'menu_icon'     => 'dashicons-editor-help',
        'menu_position' => 24,
        'supports'      => ['title', 'editor', 'custom-fields', 'page-attributes'],
        'show_in_rest'  => true,
    ]);

    register_taxonomy('faq_category', 'faq', [
        'labels' => [
            'name'          => __('Categorie FAQ', 'sage'),
            'singular_name' => __('Categoria FAQ', 'sage'),
        ],
        'public'       => true,
        'hierarchical' => true,
        'rewrite'      => ['slug' => 'faq-categoria'],
        'show_in_rest' => true,
    ]);
});
