<?php
/**
 * Title: Hero Shop – Fullscreen con CTA
 * Slug: theme/shop-hero
 * Categories: theme-sections
 * Keywords: hero, shop, ecommerce, fullscreen, cta
 * Viewport Width: 1440
 */
?>
<!-- wp:cover {"dimRatio":50,"minHeight":95,"minHeightUnit":"vh","contentPosition":"center center","className":"theme-hero","align":"full"} -->
<div class="wp-block-cover alignfull theme-hero min-h-[95vh]">
<span aria-hidden="true" class="wp-block-cover__background has-background-dim has-background-dim-50"></span>
<div class="wp-block-cover__inner-container">
<!-- wp:group {"layout":{"type":"constrained","contentSize":"52rem"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"accent","fontSize":"sm","className":"theme-section-label"} -->
<p class="has-text-align-center has-accent-color has-text-color has-sm-font-size theme-section-label">Benvenuto in Sage</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":1,"textAlign":"center","textColor":"white","fontSize":"hero","className":"theme-hero-title","fontFamily":"serif"} -->
<h1 class="wp-block-heading has-text-align-center has-white-color has-text-color has-hero-font-size has-serif-font-family theme-hero-title">Scopri la nostra<br>collezione esclusiva</h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","textColor":"white","fontSize":"lg"} -->
<p class="has-text-align-center has-white-color has-text-color has-lg-font-size">Prodotti selezionati con cura per il benessere e la felicità dei tuoi animali.</p>
<!-- /wp:paragraph -->
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button {"backgroundColor":"accent","textColor":"ink","className":"theme-btn-primary"} -->
<div class="wp-block-button theme-btn-primary"><a class="wp-block-button__link has-accent-background-color has-ink-color has-background has-text-color" href="/shop">Scopri i prodotti</a></div>
<!-- /wp:button -->
<!-- wp:button {"className":"is-style-outline theme-btn-outline-white"} -->
<div class="wp-block-button is-style-outline theme-btn-outline-white"><a class="wp-block-button__link" href="<?php echo esc_url(home_url(apply_filters('theme_about_path', '/chi-siamo'))); ?>">Chi siamo</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
</div>
</div>
<!-- /wp:cover -->
