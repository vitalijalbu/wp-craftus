<?php
/**
 * Title: Portfolio – Griglia Case Study
 * Slug: theme/portfolio-grid
 * Categories: theme-sections, theme-cards
 * Keywords: portfolio, case study, lavori, progetti, griglia
 * Description: Sezione portfolio con titolo, griglia 3 colonne (query loop CPT portfolio) e pulsante "Vedi tutti i progetti".
 * Viewport Width: 1440
 */
?>
<!-- wp:group {"backgroundColor":"surface-alt","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group has-surface-alt-background-color has-background">

	<!-- Titolo sezione -->
	<!-- wp:group {"layout":{"type":"constrained","contentSize":"40rem"}} -->
	<div class="wp-block-group">
		<!-- wp:heading {"level":2,"textAlign":"center","textColor":"ink","fontSize":"2xl","className":"theme-section-title"} -->
		<h2 class="wp-block-heading has-text-align-center has-ink-color has-text-color has-2-xl-font-size theme-section-title">I Nostri Progetti</h2>
		<!-- /wp:heading -->
		<!-- wp:paragraph {"align":"center","fontSize":"lg"} -->
		<p class="has-text-align-center has-lg-font-size">Case study e risultati concreti per i nostri clienti.</p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- Query loop CPT portfolio -->
	<!-- wp:query {"queryId":3,"query":{"perPage":6,"pages":0,"offset":0,"postType":"portfolio","order":"desc","orderBy":"date","inherit":false},"layout":{"type":"default"}} -->
	<div class="wp-block-query">

		<!-- wp:post-template {"layout":{"type":"grid","columnCount":3}} -->
			<!-- wp:group {"className":"theme-portfolio-card","backgroundColor":"surface","layout":{"type":"default"}} -->
			<div class="wp-block-group theme-portfolio-card has-surface-background-color has-background">
				<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"4/3"} /-->
				<!-- wp:group {"className":"theme-portfolio-card-body","layout":{"type":"default"}} -->
				<div class="wp-block-group theme-portfolio-card-body">
					<!-- wp:post-terms {"term":"portfolio-settore"} /-->
					<!-- wp:post-title {"isLink":true} /-->
					<!-- wp:post-excerpt {"excerptLength":12} /-->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->
		<!-- /wp:post-template -->

		<!-- wp:query-no-results -->
			<!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">Nessun progetto trovato.</p><!-- /wp:paragraph -->
		<!-- /wp:query-no-results -->

	</div>
	<!-- /wp:query -->

	<!-- CTA -->
	<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
	<div class="wp-block-buttons">
		<!-- wp:button {"backgroundColor":"ink","textColor":"primary"} -->
		<div class="wp-block-button"><a class="wp-block-button__link has-ink-background-color has-primary-color has-background has-text-color wp-element-button" href="/portfolio">Vedi tutti i progetti →</a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->

</div>
<!-- /wp:group -->
