<?php

/**
 * Custom Post Types & Taxonomies
 *
 * Registered CPTs:
 *  - faq → FAQ
 *
 * Taxonomies:
 *  - faq_category → assigned to faq
 *
 * Flush rewrite rules: Settings → Permalinks → Save (once after activation).
 */

// namespace App;

// add_action('init', function (): void {

//     // ── FAQ ───────────────────────────────────────────────────────────────────
//     register_post_type('faq', [
//         'labels' => [
//             'name' => __('FAQ', 'sage'),
//             'singular_name' => __('Domanda FAQ', 'sage'),
//             'add_new_item' => __('Aggiungi domanda', 'sage'),
//             'edit_item' => __('Modifica domanda', 'sage'),
//             'view_item' => __('Vedi domanda', 'sage'),
//             'search_items' => __('Cerca nelle FAQ', 'sage'),
//             'not_found' => __('Nessuna FAQ.', 'sage'),
//             'not_found_in_trash' => __('Nessuna FAQ nel cestino.', 'sage'),
//         ],
//         'public' => true,
//         'has_archive' => false,
//         'rewrite' => ['slug' => 'faq', 'with_front' => false],
//         'menu_icon' => 'dashicons-editor-help',
//         'menu_position' => 24,
//         'supports' => ['title', 'editor', 'custom-fields', 'page-attributes'],
//         'show_in_rest' => true,
//     ]);

//     register_taxonomy('faq_category', 'faq', [
//         'labels' => [
//             'name' => __('Categorie FAQ', 'sage'),
//             'singular_name' => __('Categoria FAQ', 'sage'),
//         ],
//         'public' => true,
//         'hierarchical' => true,
//         'rewrite' => ['slug' => 'faq-categoria'],
//         'show_in_rest' => true,
//     ]);
// });
