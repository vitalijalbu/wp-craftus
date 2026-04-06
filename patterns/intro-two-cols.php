<?php
/**
 * Title: Intro – Due Colonne (Testo + Titolo)
 * Slug: theme/intro-two-cols
 * Categories: theme-sections
 * Keywords: intro, due colonne, testo, titolo, presentazione, agenzia
 * Description: Sezione introduttiva a due colonne. Sinistra: rich text + pulsante CTA. Destra: titolo evidenziato in blu con riga decorativa.
 * Viewport Width: 1440
 */
?>
<!-- wp:group {"backgroundColor":"white","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group has-white-background-color has-background">

	<!-- wp:columns {"verticalAlignment":"center","isStackedOnMobile":true} -->
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
				<div class="wp-block-button"><a class="wp-block-button__link has-primary-background-color has-white-color has-background has-text-color wp-element-button" href="<?php echo esc_url(home_url(apply_filters('theme_about_path', '/chi-siamo'))); ?>">Scopri di più →</a></div>
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
			<div class="wp-block-spacer h-6" aria-hidden="true"></div>
			<!-- /wp:spacer -->

			<!-- Riga decorativa accent -->
			<!-- wp:group {"style":{"dimensions":{"minHeight":"4px"}},"backgroundColor":"accent","layout":{"type":"constrained","contentSize":"6rem"}} -->
			<div class="wp-block-group has-accent-background-color has-background min-h-1"></div>
			<!-- /wp:group -->

		</div>
		<!-- /wp:column -->

	</div>
	<!-- /wp:columns -->

</div>
<!-- /wp:group -->
