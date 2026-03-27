<?php
/**
 * Title: Sezione Video – Embed o YouTube
 * Slug: theme/video-section
 * Categories: theme-sections
 * Keywords: video, youtube, embed, media, presentazione, storytelling
 * Description: Sezione con video embed (YouTube/Vimeo) centrato, titolo e descrizione. Sfondo scuro con video in primo piano.
 * Viewport Width: 1440
 */
?>
<!-- wp:group {"backgroundColor":"ink","layout":{"type":"constrained"},"style":{"spacing":{"padding":{"top":"var:preset|spacing|9","bottom":"var:preset|spacing|9"}}}} -->
<div class="wp-block-group has-ink-background-color has-background">

	<!-- Intestazione -->
	<!-- wp:group {"layout":{"type":"constrained","contentSize":"48rem"},"style":{"spacing":{"blockGap":"var:preset|spacing|4"}}} -->
	<div class="wp-block-group">
		<!-- wp:paragraph {"align":"center","textColor":"accent","fontSize":"xs","className":"section-label"} -->
		<p class="has-text-align-center has-accent-color has-text-color has-xs-font-size section-label">GUARDA IL VIDEO</p>
		<!-- /wp:paragraph -->
		<!-- wp:heading {"level":2,"textAlign":"center","textColor":"white","fontFamily":"serif","fontSize":"4xl"} -->
		<h2 class="wp-block-heading has-text-align-center has-white-color has-text-color has-serif-font-family has-4-xl-font-size">La nostra storia in 2 minuti</h2>
		<!-- /wp:heading -->
		<!-- wp:paragraph {"align":"center","fontSize":"base","style":{"color":{"text":"rgba(255,255,255,0.6)"}}} -->
		<p class="has-text-align-center has-text-color has-base-font-size" style="color:rgba(255,255,255,0.6)">Scopri chi siamo, cosa facciamo e perché migliaia di clienti scelgono noi ogni anno.</p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- wp:spacer {"height":"2.5rem"} -->
	<div style="height:2.5rem" aria-hidden="true" class="wp-block-spacer"></div>
	<!-- /wp:spacer -->

	<!-- Video embed — sostituisci l'URL con il tuo video -->
	<!-- wp:embed {"url":"https://www.youtube.com/watch?v=dQw4w9WgXcQ","type":"video","providerNameSlug":"youtube","responsive":true,"className":"wp-embed-aspect-16-9 wp-has-aspect-ratio","align":"wide"} -->
	<figure class="wp-block-embed is-type-video is-provider-youtube wp-block-embed-youtube wp-embed-aspect-16-9 wp-has-aspect-ratio alignwide">
		<div class="wp-block-embed__wrapper">
			https://www.youtube.com/watch?v=dQw4w9WgXcQ
		</div>
	</figure>
	<!-- /wp:embed -->

	<!-- wp:spacer {"height":"2rem"} -->
	<div style="height:2rem" aria-hidden="true" class="wp-block-spacer"></div>
	<!-- /wp:spacer -->

	<!-- CTA sotto il video -->
	<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
	<div class="wp-block-buttons">
		<!-- wp:button {"backgroundColor":"accent","textColor":"white"} -->
		<div class="wp-block-button"><a class="wp-block-button__link has-accent-background-color has-white-color has-background has-text-color wp-element-button" href="/contatti">Lavora con noi</a></div>
		<!-- /wp:button -->
		<!-- wp:button {"className":"is-style-outline"} -->
		<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" style="color:#ffffff;border-color:#ffffff" href="/chi-siamo">Scopri di più</a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->

</div>
<!-- /wp:group -->
