<?php
/**
 * Custom email styles.
 * Overrides WooCommerce's default email CSS.
 */
defined('ABSPATH') || exit;

$bg = '#f5f5f5';
$body_bg = '#ffffff';
$ink = '#0a0a0a';
$muted = '#6b6b6b';
$accent = '#0074C7';
$border = '#e0e0e0';
$heading_font = 'Inter, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif';
$body_font = 'Poppins, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif';
?>

body {
    background-color: <?= esc_attr($bg) ?>;
    margin: 0;
    padding: 0;
}

#wrapper {
    background-color: <?= esc_attr($bg) ?>;
    margin: 0;
    padding: 40px 0;
    width: 100%;
    -webkit-text-size-adjust: none !important;
}

#template_container {
    background-color: <?= esc_attr($body_bg) ?>;
    border: 1px solid <?= esc_attr($border) ?>;
    border-radius: 0 !important;
    max-width: 600px;
    margin: 0 auto;
}

#template_header {
    background-color: <?= esc_attr($ink) ?>;
    border-radius: 0 !important;
    color: #ffffff;
    padding: 32px 40px;
    text-align: left;
}

#template_header h1,
#template_header h1 a {
    color: #ffffff !important;
    font-family: <?= esc_attr($heading_font) ?>;
    font-size: 22px;
    font-weight: 300;
    letter-spacing: 0.05em;
    margin: 0;
    text-decoration: none;
}

#template_header_image img {
    max-height: 48px;
    width: auto;
}

#template_body {
    background-color: <?= esc_attr($body_bg) ?>;
}

#body_content {
    padding: 40px;
}

#body_content table td {
    padding: 0;
}

#body_content p,
#body_content ul,
#body_content ol,
#body_content td,
#body_content th,
#body_content div {
    font-family: <?= esc_attr($body_font) ?>;
    font-size: 14px;
    line-height: 1.7;
    color: <?= esc_attr($muted) ?>;
}

#body_content_inner h1,
#body_content_inner h2,
#body_content_inner h3 {
    color: <?= esc_attr($ink) ?> !important;
    font-family: <?= esc_attr($heading_font) ?>;
    font-weight: 300;
    margin: 0 0 16px;
    line-height: 1.2;
}

#body_content_inner h1 { font-size: 28px; }
#body_content_inner h2 { font-size: 22px; }
#body_content_inner h3 { font-size: 16px; font-weight: 500; }

#body_content a {
    color: <?= esc_attr($accent) ?>;
    text-decoration: none;
}
#body_content a:hover {
    text-decoration: underline;
}

/* Order table */
.td {
    border: 1px solid <?= esc_attr($border) ?> !important;
}

table.td td,
table.td th {
    font-family: <?= esc_attr($body_font) ?>;
    font-size: 14px;
    padding: 12px 16px !important;
    vertical-align: middle;
    text-align: left;
    border: none !important;
    border-bottom: 1px solid <?= esc_attr($border) ?> !important;
}

table.td th {
    font-weight: 600;
    font-size: 11px;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: <?= esc_attr($ink) ?>;
    background: #f9f9f9;
}

.woocommerce-order-items__product-name {
    font-weight: 500;
    color: <?= esc_attr($ink) ?> !important;
}

/* Totals row */
.woocommerce-email-order-details tfoot tr th,
.woocommerce-email-order-details tfoot tr td {
    border-top: 2px solid <?= esc_attr($ink) ?> !important;
}

/* Button */
.button,
.button a {
    background-color: <?= esc_attr($accent) ?> !important;
    border-radius: 0 !important;
    color: #ffffff !important;
    display: inline-block;
    font-family: <?= esc_attr($body_font) ?>;
    font-size: 11px !important;
    font-weight: 600 !important;
    letter-spacing: 0.12em !important;
    padding: 14px 28px !important;
    text-decoration: none !important;
    text-transform: uppercase !important;
}

/* Address boxes */
.address {
    background: #f9f9f9;
    border: 1px solid <?= esc_attr($border) ?>;
    padding: 20px;
    font-size: 14px;
    color: <?= esc_attr($muted) ?>;
    line-height: 1.7;
}

/* Footer */
#template_footer {
    background-color: <?= esc_attr($ink) ?>;
    padding: 24px 40px;
}

#template_footer p,
#template_footer a {
    font-family: <?= esc_attr($body_font) ?>;
    font-size: 12px;
    color: rgba(255,255,255,0.5) !important;
    text-decoration: none;
    line-height: 1.6;
    margin: 0;
}
#template_footer a:hover {
    color: #ffffff !important;
    text-decoration: underline;
}
