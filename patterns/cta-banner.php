<?php
/**
 * Title: 4 Zampe – CTA Banner – Sfondo Blu
 * Slug: theme/cta-banner
 * Categories: theme-sections
 * Keywords: cta, call to action, blu, prenota, contatti, consulenza
 * Description: Banner call-to-action con sfondo blu primario (#3E80C4), pattern geometrico, titolo, sottotitolo e pulsanti di contatto (booking, telefono, WhatsApp, email).
 * Viewport Width: 1440
 */
?>
<!-- wp:group {"backgroundColor":"primary","className":"theme-cta-banner","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group theme-cta-banner has-primary-background-color has-background">

	<!-- wp:group {"layout":{"type":"constrained","contentSize":"48rem"}} -->
	<div class="wp-block-group">

		<!-- wp:heading {"level":2,"textAlign":"center","textColor":"white","fontSize":"2xl","className":"theme-section-title"} -->
		<h2 class="wp-block-heading has-text-align-center has-white-color has-text-color has-2-xl-font-size theme-section-title">Pronto a far crescere<br>la tua comunicazione?</h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"align":"center","textColor":"white","fontSize":"lg"} -->
		<p class="has-text-align-center has-white-color has-text-color has-lg-font-size">Analizziamo insieme la tua situazione e troviamo le soluzioni giuste per te.</p>
		<!-- /wp:paragraph -->

		<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center","flexWrap":"wrap"}} -->
		<div class="wp-block-buttons">
			<!-- wp:button {"backgroundColor":"dark","textColor":"white"} -->
			<div class="wp-block-button"><a class="wp-block-button__link has-dark-background-color has-white-color has-background has-text-color wp-element-button" href="/contatti">📅 Prenota una consulenza</a></div>
			<!-- /wp:button -->
			<!-- wp:button {"className":"is-style-outline"} -->
			<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="tel:+39030000000">📞 030 000 000</a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->

		<!-- Link secondari: WhatsApp + email -->
		<!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"center"}} -->
		<div class="wp-block-group">
			<!-- wp:paragraph {"textColor":"white","fontSize":"lg"} -->
			<p class="has-white-color has-text-color has-lg-font-size">💬 <a href="https://wa.me/393000000000" target="_blank" rel="noopener">WhatsApp</a></p>
			<!-- /wp:paragraph -->
			<!-- wp:paragraph {"textColor":"white","fontSize":"lg"} -->
			<p class="has-white-color has-text-color has-lg-font-size">✉️ <a href="mailto:info@theme.it">info@theme.it</a></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

	</div>
	<!-- /wp:group -->

</div>
<!-- /wp:group -->
