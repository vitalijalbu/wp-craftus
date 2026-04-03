<?php
/**
 * Title: Griglia Servizi – 3 Colonne
 * Slug: theme/services-grid
 * Categories: theme-sections, theme-cards
 * Keywords: servizi, griglia, card, 3 colonne, icona, link
 * Description: Griglia di 3 card servizio con icona emoji, titolo, descrizione e link. Include titolo sezione e pulsante "Vedi tutti i servizi".
 * Viewport Width: 1440
 */
?>
<!-- wp:group {"backgroundColor":"white","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|70"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group has-white-background-color has-background">

	<!-- Titolo sezione -->
	<!-- wp:group {"layout":{"type":"constrained","contentSize":"38rem"}} -->
	<div class="wp-block-group">
		<!-- wp:heading {"level":2,"textAlign":"center","textColor":"ink","fontSize":"2xl","className":"theme-section-title"} -->
		<h2 class="wp-block-heading has-text-align-center has-ink-color has-text-color has-2-xl-font-size theme-section-title">I Nostri Servizi</h2>
		<!-- /wp:heading -->
		<!-- wp:paragraph {"align":"center","fontSize":"lg"} -->
		<p class="has-text-align-center has-lg-font-size">Soluzioni su misura per comunicare al meglio la tua azienda, costruire autorevolezza e acquisire nuovi clienti.</p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- 3 colonne di card servizi -->
	<!-- wp:columns {"isStackedOnMobile":true} -->
	<div class="wp-block-columns">

		<!-- Card 1 -->
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"className":"theme-service-card","style":{"spacing":{"padding":{"all":"2rem"}},"border":{"width":"1px","style":"solid","color":"#e5e7eb"}},"layout":{"type":"default"}} -->
			<div class="wp-block-group theme-service-card" style="border:1px solid var(--wp--preset--color--border, #e5e7eb);padding:2rem">
				<!-- wp:paragraph -->
				<p aria-hidden="true">🎯</p>
				<!-- /wp:paragraph -->
				<!-- wp:heading {"level":3,"textColor":"ink","fontSize":"xl"} -->
				<h3 class="wp-block-heading has-ink-color has-text-color has-xl-font-size">Strategia di Comunicazione</h3>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"fontSize":"lg"} -->
				<p class="has-lg-font-size">Analizziamo il tuo mercato e definiamo la strategia comunicativa più efficace per raggiungere e convertire il tuo target.</p>
				<!-- /wp:paragraph -->
				<!-- wp:paragraph -->
				<p><a href="/servizi/strategia-di-comunicazione">Scopri di più →</a></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->

		<!-- Card 2 -->
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"className":"theme-service-card","style":{"spacing":{"padding":{"all":"2rem"}},"border":{"width":"1px","style":"solid","color":"#e5e7eb"}},"layout":{"type":"default"}} -->
			<div class="wp-block-group theme-service-card" style="border:1px solid var(--wp--preset--color--border, #e5e7eb);padding:2rem">
				<!-- wp:paragraph -->
				<p aria-hidden="true">✏️</p>
				<!-- /wp:paragraph -->
				<!-- wp:heading {"level":3,"textColor":"ink","fontSize":"xl"} -->
				<h3 class="wp-block-heading has-ink-color has-text-color has-xl-font-size">Content Marketing</h3>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"fontSize":"lg"} -->
				<p class="has-lg-font-size">Creiamo contenuti che parlano al tuo pubblico: articoli SEO, video, newsletter e social post che generano engagement e fiducia.</p>
				<!-- /wp:paragraph -->
				<!-- wp:paragraph -->
				<p><a href="/servizi/content-marketing">Scopri di più →</a></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->

		<!-- Card 3 -->
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"className":"theme-service-card","style":{"spacing":{"padding":{"all":"2rem"}},"border":{"width":"1px","style":"solid","color":"#e5e7eb"}},"layout":{"type":"default"}} -->
			<div class="wp-block-group theme-service-card" style="border:1px solid var(--wp--preset--color--border, #e5e7eb);padding:2rem">
				<!-- wp:paragraph -->
				<p aria-hidden="true">📱</p>
				<!-- /wp:paragraph -->
				<!-- wp:heading {"level":3,"textColor":"ink","fontSize":"xl"} -->
				<h3 class="wp-block-heading has-ink-color has-text-color has-xl-font-size">Social Media Management</h3>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"fontSize":"lg"} -->
				<p class="has-lg-font-size">Gestiamo i tuoi profili social con approccio strategico: dalla creazione dei contenuti alla community management e advertising.</p>
				<!-- /wp:paragraph -->
				<!-- wp:paragraph -->
				<p><a href="/servizi/social-media-management">Scopri di più →</a></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->

	</div>
	<!-- /wp:columns -->

	<!-- CTA Vedi tutti -->
	<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
	<div class="wp-block-buttons">
		<!-- wp:button {"className":"is-style-outline"} -->
		<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="/servizi">Vedi tutti i servizi →</a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->

</div>
<!-- /wp:group -->
