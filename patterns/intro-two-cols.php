<?php
/**
 * Title: Intro – Due Colonne (Testo + Titolo)
 * Slug: 4zampe/intro-two-cols
 * Categories: 4zampe-sections
 * Keywords: intro, due colonne, testo, titolo, presentazione, agenzia
 * Description: Sezione introduttiva a due colonne. Sinistra: rich text + pulsante CTA. Destra: titolo evidenziato in blu con riga decorativa.
 * Viewport Width: 1440
 */
?>
<!-- wp:group {"backgroundColor":"white","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group has-white-background-color has-background">

	<!-- wp:columns {"verticalAlignment":"center"} -->
	<div class="wp-block-columns are-vertically-aligned-center">

		<!-- Colonna sinistra: Testo + pulsante -->
		<!-- wp:column {"verticalAlignment":"center"} -->
		<div class="wp-block-column is-vertically-aligned-center">

			<!-- wp:paragraph {"fontSize":"lg"} -->
			<p class="has-lg-font-size">Craft è un'agenzia di comunicazione con sede a Pavia che supporta aziende e professionisti nella costruzione di una presenza efficace, autentica e riconoscibile.</p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"fontSize":"lg"} -->
			<p class="has-lg-font-size">Dal branding alla comunicazione digitale, dalla strategia ai contenuti: ogni progetto è pensato su misura per i tuoi obiettivi di business.</p>
			<!-- /wp:paragraph -->

			<!-- wp:buttons -->
			<div class="wp-block-buttons">
				<!-- wp:button {"backgroundColor":"primary","textColor":"white"} -->
				<div class="wp-block-button"><a class="wp-block-button__link has-primary-background-color has-white-color has-background has-text-color wp-element-button" href="/chi-siamo">Scopri di più →</a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->

		</div>
		<!-- /wp:column -->

		<!-- Colonna destra: Titolo + decorazione -->
		<!-- wp:column {"verticalAlignment":"center"} -->
		<div class="wp-block-column is-vertically-aligned-center">

			<!-- wp:heading {"level":2,"textColor":"primary","fontSize":"2xl","className":"theme-section-title"} -->
			<h2 class="wp-block-heading has-primary-color has-text-color has-2-xl-font-size theme-section-title">Comunicazione strategica che porta risultati concreti.</h2>
			<!-- /wp:heading -->

			<!-- wp:spacer {"height":"1.5rem"} -->
			<div style="height:1.5rem" aria-hidden="true" class="wp-block-spacer"></div>
			<!-- /wp:spacer -->

			<!-- Riga decorativa blu -->
			<!-- wp:group {"style":{"dimensions":{"minHeight":"4px"},"color":{"background":"#3E80C4"}},"layout":{"type":"constrained","contentSize":"6rem"}} -->
			<div class="wp-block-group has-background" style="background-color:#3E80C4;min-height:4px"></div>
			<!-- /wp:group -->

		</div>
		<!-- /wp:column -->

	</div>
	<!-- /wp:columns -->

</div>
<!-- /wp:group -->
