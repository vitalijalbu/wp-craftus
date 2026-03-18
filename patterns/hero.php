<?php
/**
 * Title: Hero – Immagine di Sfondo con CTA
 * Slug: theme/hero
 * Categories: theme-sections
 * Keywords: hero, banner, sfondo, immagine, titolo, cta, principale
 * Description: Sezione hero con immagine di sfondo, overlay scuro, titolo in Cormorant Garamond, sottotitolo e due pulsanti CTA. Altezza 76vh.
 * Block Types: core/cover
 * Viewport Width: 1440
 */
?>
<!-- wp:cover {"url":"","id":0,"dimRatio":45,"style":{"dimensions":{"minHeight":"76vh"}},"contentPosition":"center center","className":"theme-hero","isUserOverlayColor":true} -->
<div class="wp-block-cover theme-hero" style="min-height:76vh">
	<span aria-hidden="true" class="wp-block-cover__background has-background-dim" style="background-color:#0f0f0f;opacity:0.45"></span>
	<img class="wp-block-cover__image-background" src="" alt="" style="object-position:50% 50%" data-object-fit="cover"/>
	<div class="wp-block-cover__inner-container">

		<!-- wp:group {"layout":{"type":"constrained","contentSize":"56rem"}} -->
		<div class="wp-block-group">

			<!-- wp:heading {"level":1,"textAlign":"center","textColor":"white","fontFamily":"serif","fontSize":"hero","className":"theme-hero-title"} -->
			<h1 class="wp-block-heading has-text-align-center has-white-color has-text-color has-serif-font-family has-hero-font-size theme-hero-title">Comunichiamo<br>il Tuo Valore</h1>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"align":"center","textColor":"white","fontSize":"lg"} -->
			<p class="has-text-align-center has-white-color has-text-color has-lg-font-size">Strategia, creatività e risultati misurabili per la tua azienda.</p>
			<!-- /wp:paragraph -->

			<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
			<div class="wp-block-buttons">
				<!-- wp:button {"backgroundColor":"primary","textColor":"white"} -->
				<div class="wp-block-button"><a class="wp-block-button__link has-primary-background-color has-white-color has-background has-text-color wp-element-button" href="/servizi">Scopri i Servizi</a></div>
				<!-- /wp:button -->
				<!-- wp:button {"className":"is-style-outline"} -->
				<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="/contatti">Contattaci</a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->

		</div>
		<!-- /wp:group -->

		<!-- wp:paragraph {"align":"center","textColor":"white","className":"theme-hero__scroll"} -->
		<p class="has-text-align-center has-white-color has-text-color theme-hero__scroll">Scroll</p>
		<!-- /wp:paragraph -->

	</div>
</div>
<!-- /wp:cover -->
