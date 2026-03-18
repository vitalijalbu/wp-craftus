<?php
/**
 * Title: Stats – Numeri in Evidenza
 * Slug: theme/stats
 * Categories: theme-sections
 * Keywords: stats, numeri, statistiche, risultati, contatori, metriche
 * Description: Sezione con 4 colonne di statistiche/numeri. Sfondo scuro (#0f0f0f) con numeri grandi in blu primario e etichette in bianco. Ideale per evidenziare risultati aziendali.
 * Viewport Width: 1440
 */
?>
<!-- wp:group {"backgroundColor":"dark","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group has-dark-background-color has-background">

	<!-- Titolo -->
	<!-- wp:heading {"level":2,"textAlign":"center","textColor":"white","fontSize":"2xl","className":"theme-section-title"} -->
	<h2 class="wp-block-heading has-text-align-center has-white-color has-text-color has-2-xl-font-size theme-section-title">I numeri che ci rappresentano</h2>
	<!-- /wp:heading -->

	<!-- 4 colonne statistiche -->
	<!-- wp:columns -->
	<div class="wp-block-columns">

		<!-- Stat 1 -->
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"layout":{"type":"default"},"style":{"spacing":{"padding":{"all":"2rem"}},"border":{"color":"rgba(255,255,255,0.1)","width":"1px","style":"solid"}},"className":"theme-stat-card"} -->
			<div class="wp-block-group theme-stat-card" style="padding:2rem;border:1px solid rgba(255,255,255,0.1)">
				<!-- wp:heading {"level":3,"textAlign":"center","textColor":"primary","fontFamily":"serif","fontSize":"hero","className":"theme-stat-number"} -->
				<h3 class="wp-block-heading has-text-align-center has-primary-color has-text-color has-serif-font-family has-hero-font-size theme-stat-number">+150</h3>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"align":"center","textColor":"white","fontSize":"base"} -->
				<p class="has-text-align-center has-white-color has-text-color has-base-font-size">Clienti Soddisfatti</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->

		<!-- Stat 2 -->
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"layout":{"type":"default"},"style":{"spacing":{"padding":{"all":"2rem"}},"border":{"color":"rgba(255,255,255,0.1)","width":"1px","style":"solid"}},"className":"theme-stat-card"} -->
			<div class="wp-block-group theme-stat-card" style="padding:2rem;border:1px solid rgba(255,255,255,0.1)">
				<!-- wp:heading {"level":3,"textAlign":"center","textColor":"primary","fontFamily":"serif","fontSize":"hero","className":"theme-stat-number"} -->
				<h3 class="wp-block-heading has-text-align-center has-primary-color has-text-color has-serif-font-family has-hero-font-size theme-stat-number">+8</h3>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"align":"center","textColor":"white","fontSize":"base"} -->
				<p class="has-text-align-center has-white-color has-text-color has-base-font-size">Anni di Esperienza</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->

		<!-- Stat 3 -->
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"layout":{"type":"default"},"style":{"spacing":{"padding":{"all":"2rem"}},"border":{"color":"rgba(255,255,255,0.1)","width":"1px","style":"solid"}},"className":"theme-stat-card"} -->
			<div class="wp-block-group theme-stat-card" style="padding:2rem;border:1px solid rgba(255,255,255,0.1)">
				<!-- wp:heading {"level":3,"textAlign":"center","textColor":"primary","fontFamily":"serif","fontSize":"hero","className":"theme-stat-number"} -->
				<h3 class="wp-block-heading has-text-align-center has-primary-color has-text-color has-serif-font-family has-hero-font-size theme-stat-number">+300</h3>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"align":"center","textColor":"white","fontSize":"base"} -->
				<p class="has-text-align-center has-white-color has-text-color has-base-font-size">Progetti Realizzati</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->

		<!-- Stat 4 -->
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"layout":{"type":"default"},"style":{"spacing":{"padding":{"all":"2rem"}},"border":{"color":"rgba(255,255,255,0.1)","width":"1px","style":"solid"}},"className":"theme-stat-card"} -->
			<div class="wp-block-group theme-stat-card" style="padding:2rem;border:1px solid rgba(255,255,255,0.1)">
				<!-- wp:heading {"level":3,"textAlign":"center","textColor":"primary","fontFamily":"serif","fontSize":"hero","className":"theme-stat-number"} -->
				<h3 class="wp-block-heading has-text-align-center has-primary-color has-text-color has-serif-font-family has-hero-font-size theme-stat-number">98%</h3>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"align":"center","textColor":"white","fontSize":"base"} -->
				<p class="has-text-align-center has-white-color has-text-color has-base-font-size">Clienti Confermati</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->

	</div>
	<!-- /wp:columns -->

</div>
<!-- /wp:group -->
