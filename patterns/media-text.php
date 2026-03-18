<?php
/**
 * Title: Media + Testo – Immagine a Sinistra
 * Slug: 4zampe/media-text
 * Categories: 4zampe-sections
 * Keywords: media text, immagine, testo, due colonne, feature
 * Description: Sezione a due colonne con immagine a sinistra e contenuto testuale a destra: titolo grande, testo e pulsante CTA.
 * Viewport Width: 1440
 */
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"backgroundColor":"white","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-white-background-color has-background">

	<!-- wp:media-text {"mediaPosition":"left","mediaType":"image","verticalAlignment":"center","className":"theme-media-text"} -->
	<div class="wp-block-media-text theme-media-text is-stacked-on-mobile" style="grid-template-columns:50% auto">
		<figure class="wp-block-media-text__media">
			<img src="" alt="" class="wp-image-0"/>
		</figure>
		<div class="wp-block-media-text__content">

			<!-- wp:heading {"level":2,"textColor":"dark","fontFamily":"serif","fontSize":"hero","className":"theme-hero-title"} -->
			<h2 class="wp-block-heading has-dark-color has-text-color has-serif-font-family has-hero-font-size theme-hero-title">Un'agenzia che lavora<br>per i tuoi obiettivi</h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"fontSize":"lg"} -->
			<p class="has-lg-font-size">Non offriamo soluzioni standard. Ogni progetto nasce da un'analisi approfondita del tuo mercato, dei tuoi competitor e dei tuoi clienti ideali.</p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"fontSize":"lg"} -->
			<p class="has-lg-font-size">Accompagniamo ogni cliente con un approccio consulenziale continuo, misurando i risultati e ottimizzando ogni azione.</p>
			<!-- /wp:paragraph -->

			<!-- wp:buttons -->
			<div class="wp-block-buttons">
				<!-- wp:button {"backgroundColor":"primary","textColor":"white"} -->
				<div class="wp-block-button"><a class="wp-block-button__link has-primary-background-color has-white-color has-background has-text-color wp-element-button" href="/chi-siamo">Chi siamo →</a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->

		</div>
	</div>
	<!-- /wp:media-text -->

</div>
<!-- /wp:group -->
