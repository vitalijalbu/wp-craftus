<?php
/**
 * Title: Recensioni Aggregate – Rating + Badge
 * Slug: theme/review-aggregate
 * Categories: theme-sections
 * Keywords: recensioni, rating, stelle, review, trustpilot, google, badge, testimonianze
 * Description: Sezione rating aggregato con punteggio medio, numero recensioni, badge piattaforme e 3 recensioni in evidenza.
 * Viewport Width: 1440
 */
?>
<!-- wp:group {"backgroundColor":"ink","layout":{"type":"constrained"},"style":{"spacing":{"padding":{"top":"var:preset|spacing|9","bottom":"var:preset|spacing|9"}}}} -->
<div class="wp-block-group has-ink-background-color has-background">

	<!-- Punteggio aggregato -->
	<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center","verticalAlignment":"center"},"style":{"spacing":{"blockGap":"3rem","padding":{"bottom":"3rem"},"margin":{"bottom":"3rem"}},"border":{"bottom":{"color":"rgba(255,255,255,0.1)","width":"1px","style":"solid"}}}} -->
	<div class="wp-block-group" style="padding-bottom:3rem;margin-bottom:3rem;border-bottom:1px solid rgba(255,255,255,0.1);gap:3rem;flex-wrap:wrap;justify-content:center;align-items:center">

		<!-- Rating numerico -->
		<!-- wp:group {"style":{"spacing":{"blockGap":"0.25rem"}},"layout":{"type":"default"}} -->
		<div class="wp-block-group" style="gap:0.25rem;text-align:center">
			<!-- wp:heading {"level":2,"textColor":"white","fontSize":"hero","fontFamily":"serif","style":{"spacing":{"margin":{"bottom":"0"}}}} -->
			<h2 class="wp-block-heading has-white-color has-text-color has-serif-font-family has-hero-font-size" style="margin-bottom:0">4.9</h2>
			<!-- /wp:heading -->
			<!-- wp:paragraph {"textColor":"accent","fontSize":"xs","className":"section-label"} -->
			<p class="has-accent-color has-text-color has-xs-font-size section-label">SU 5 STELLE</p>
			<!-- /wp:paragraph -->
			<!-- wp:paragraph {"fontSize":"sm","style":{"color":{"text":"rgba(255,255,255,0.5)"}}} -->
			<p class="has-text-color has-sm-font-size" style="color:rgba(255,255,255,0.5)">Basato su 328 recensioni</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

		<!-- Separatore verticale (visibile solo desktop) -->
		<!-- wp:group {"style":{"dimensions":{"minHeight":"80px"},"border":{"left":{"color":"rgba(255,255,255,0.1)","width":"1px","style":"solid"}}},"layout":{"type":"default"}} -->
		<div class="wp-block-group" style="min-height:80px;border-left:1px solid rgba(255,255,255,0.1)"></div>
		<!-- /wp:group -->

		<!-- Badge piattaforme -->
		<!-- wp:group {"style":{"spacing":{"blockGap":"1rem"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"center"}} -->
		<div class="wp-block-group" style="gap:1rem">

			<!-- Google -->
			<!-- wp:group {"style":{"border":{"width":"1px","style":"solid","color":"rgba(255,255,255,0.15)"},"spacing":{"padding":{"top":"0.75rem","bottom":"0.75rem","left":"1.25rem","right":"1.25rem"},"blockGap":"0.5rem"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
			<div class="wp-block-group" style="border:1px solid rgba(255,255,255,0.15);padding:0.75rem 1.25rem;gap:0.5rem;flex-wrap:nowrap;align-items:center">
				<!-- wp:paragraph {"textColor":"white","fontSize":"sm","style":{"spacing":{"margin":{"bottom":"0"}}}} -->
				<p class="has-white-color has-text-color has-sm-font-size" style="margin-bottom:0">⭐ <strong>4.9</strong> Google</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->

			<!-- Trustpilot -->
			<!-- wp:group {"style":{"border":{"width":"1px","style":"solid","color":"rgba(255,255,255,0.15)"},"spacing":{"padding":{"top":"0.75rem","bottom":"0.75rem","left":"1.25rem","right":"1.25rem"},"blockGap":"0.5rem"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
			<div class="wp-block-group" style="border:1px solid rgba(255,255,255,0.15);padding:0.75rem 1.25rem;gap:0.5rem;flex-wrap:nowrap;align-items:center">
				<!-- wp:paragraph {"textColor":"white","fontSize":"sm","style":{"spacing":{"margin":{"bottom":"0"}}}} -->
				<p class="has-white-color has-text-color has-sm-font-size" style="margin-bottom:0">⭐ <strong>4.8</strong> Trustpilot</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->

		</div>
		<!-- /wp:group -->

	</div>
	<!-- /wp:group -->

	<!-- 3 recensioni -->
	<!-- wp:columns {"isStackedOnMobile":true,"style":{"spacing":{"blockGap":{"left":"1.5rem","top":"1.5rem"}}}} -->
	<div class="wp-block-columns">

		<!-- Recensione 1 -->
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"style":{"border":{"width":"1px","style":"solid","color":"rgba(255,255,255,0.1)"},"spacing":{"padding":{"all":"1.75rem"},"blockGap":"1rem"}},"layout":{"type":"default"}} -->
			<div class="wp-block-group" style="border:1px solid rgba(255,255,255,0.1);padding:1.75rem;gap:1rem">
				<!-- Stelle -->
				<!-- wp:paragraph {"textColor":"accent","fontSize":"sm","style":{"spacing":{"margin":{"bottom":"0"}}}} -->
				<p class="has-accent-color has-text-color has-sm-font-size" style="margin-bottom:0">★★★★★</p>
				<!-- /wp:paragraph -->
				<!-- wp:paragraph {"fontSize":"base","style":{"color":{"text":"rgba(255,255,255,0.85)"}}} -->
				<p class="has-text-color has-base-font-size" style="color:rgba(255,255,255,0.85)">"Professionalità e competenza fuori dal comune. Il sito realizzato ha superato ogni nostra aspettativa, sia per il design che per le performance."</p>
				<!-- /wp:paragraph -->
				<!-- wp:group {"style":{"spacing":{"blockGap":"0.25rem"}},"layout":{"type":"default"}} -->
				<div class="wp-block-group" style="gap:0.25rem">
					<!-- wp:paragraph {"textColor":"white","fontSize":"sm","style":{"spacing":{"margin":{"bottom":"0"}}}} -->
					<p class="has-white-color has-text-color has-sm-font-size" style="margin-bottom:0"><strong>Marco Bianchi</strong></p>
					<!-- /wp:paragraph -->
					<!-- wp:paragraph {"fontSize":"xs","style":{"color":{"text":"rgba(255,255,255,0.4)"},"spacing":{"margin":{"top":"0"}}}} -->
					<p class="has-text-color has-xs-font-size" style="color:rgba(255,255,255,0.4);margin-top:0">CEO, Azienda Srl</p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->

		<!-- Recensione 2 -->
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"style":{"border":{"width":"1px","style":"solid","color":"rgba(255,255,255,0.1)"},"spacing":{"padding":{"all":"1.75rem"},"blockGap":"1rem"}},"layout":{"type":"default"}} -->
			<div class="wp-block-group" style="border:1px solid rgba(255,255,255,0.1);padding:1.75rem;gap:1rem">
				<!-- Stelle -->
				<!-- wp:paragraph {"textColor":"accent","fontSize":"sm","style":{"spacing":{"margin":{"bottom":"0"}}}} -->
				<p class="has-accent-color has-text-color has-sm-font-size" style="margin-bottom:0">★★★★★</p>
				<!-- /wp:paragraph -->
				<!-- wp:paragraph {"fontSize":"base","style":{"color":{"text":"rgba(255,255,255,0.85)"}}} -->
				<p class="has-text-color has-base-font-size" style="color:rgba(255,255,255,0.85)">"Tempi rispettati, comunicazione impeccabile e risultato finale eccellente. Consigliatissimi a chiunque voglia un progetto digitale di qualità."</p>
				<!-- /wp:paragraph -->
				<!-- wp:group {"style":{"spacing":{"blockGap":"0.25rem"}},"layout":{"type":"default"}} -->
				<div class="wp-block-group" style="gap:0.25rem">
					<!-- wp:paragraph {"textColor":"white","fontSize":"sm","style":{"spacing":{"margin":{"bottom":"0"}}}} -->
					<p class="has-white-color has-text-color has-sm-font-size" style="margin-bottom:0"><strong>Laura Rossi</strong></p>
					<!-- /wp:paragraph -->
					<!-- wp:paragraph {"fontSize":"xs","style":{"color":{"text":"rgba(255,255,255,0.4)"},"spacing":{"margin":{"top":"0"}}}} -->
					<p class="has-text-color has-xs-font-size" style="color:rgba(255,255,255,0.4);margin-top:0">Marketing Manager, Studio XY</p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->

		<!-- Recensione 3 -->
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"style":{"border":{"width":"1px","style":"solid","color":"rgba(255,255,255,0.1)"},"spacing":{"padding":{"all":"1.75rem"},"blockGap":"1rem"}},"layout":{"type":"default"}} -->
			<div class="wp-block-group" style="border:1px solid rgba(255,255,255,0.1);padding:1.75rem;gap:1rem">
				<!-- Stelle -->
				<!-- wp:paragraph {"textColor":"accent","fontSize":"sm","style":{"spacing":{"margin":{"bottom":"0"}}}} -->
				<p class="has-accent-color has-text-color has-sm-font-size" style="margin-bottom:0">★★★★★</p>
				<!-- /wp:paragraph -->
				<!-- wp:paragraph {"fontSize":"base","style":{"color":{"text":"rgba(255,255,255,0.85)"}}} -->
				<p class="has-text-color has-base-font-size" style="color:rgba(255,255,255,0.85)">"Lavoro impeccabile dalla fase di analisi al lancio. Hanno capito subito le nostre esigenze e tradotto tutto in un prodotto digitale che funziona davvero."</p>
				<!-- /wp:paragraph -->
				<!-- wp:group {"style":{"spacing":{"blockGap":"0.25rem"}},"layout":{"type":"default"}} -->
				<div class="wp-block-group" style="gap:0.25rem">
					<!-- wp:paragraph {"textColor":"white","fontSize":"sm","style":{"spacing":{"margin":{"bottom":"0"}}}} -->
					<p class="has-white-color has-text-color has-sm-font-size" style="margin-bottom:0"><strong>Andrea Ferrari</strong></p>
					<!-- /wp:paragraph -->
					<!-- wp:paragraph {"fontSize":"xs","style":{"color":{"text":"rgba(255,255,255,0.4)"},"spacing":{"margin":{"top":"0"}}}} -->
					<p class="has-text-color has-xs-font-size" style="color:rgba(255,255,255,0.4);margin-top:0">Founder, Startup Innovativa</p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->

	</div>
	<!-- /wp:columns -->

</div>
<!-- /wp:group -->
