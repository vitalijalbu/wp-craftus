<?php
/**
 * Title: Mappa + Contatti – Split
 * Slug: theme/map-contact
 * Categories: theme-sections
 * Keywords: mappa, contatti, indirizzo, dove siamo, google maps, sede, ufficio
 * Description: Sezione split con mappa embed a sinistra e informazioni di contatto a destra. Sfondo chiaro.
 * Viewport Width: 1440
 */
?>
<!-- wp:group {"align":"full","backgroundColor":"surface-alt","layout":{"type":"constrained"},"style":{"spacing":{"padding":{"top":"var:preset|spacing|9","bottom":"var:preset|spacing|9"}}}} -->
<div class="wp-block-group alignfull has-surface-alt-background-color has-background">

	<!-- Intestazione -->
	<!-- wp:group {"layout":{"type":"constrained","contentSize":"48rem"},"style":{"spacing":{"blockGap":"var:preset|spacing|4"}}} -->
	<div class="wp-block-group">
		<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"xs","className":"section-label"} -->
		<p class="has-text-align-center has-muted-color has-text-color has-xs-font-size section-label">DOVE SIAMO</p>
		<!-- /wp:paragraph -->
		<!-- wp:heading {"level":2,"textAlign":"center","fontFamily":"serif","fontSize":"4xl"} -->
		<h2 class="wp-block-heading has-text-align-center has-serif-font-family has-4-xl-font-size">Vieni a trovarci</h2>
		<!-- /wp:heading -->
	</div>
	<!-- /wp:group -->

	<!-- wp:spacer {"height":"2.5rem"} -->
	<div style="height:2.5rem" aria-hidden="true" class="wp-block-spacer"></div>
	<!-- /wp:spacer -->

	<!-- Split: mappa + info -->
	<!-- wp:columns {"isStackedOnMobile":true,"verticalAlignment":"stretch","style":{"spacing":{"blockGap":{"left":"0","top":"2rem"}}}} -->
	<div class="wp-block-columns are-vertically-aligned-stretch">

		<!-- Mappa embed -->
		<!-- wp:column {"width":"55%"} -->
		<div class="wp-block-column" style="flex-basis:55%">
			<!-- wp:html -->
			<div style="width:100%;height:420px;overflow:hidden">
				<!-- Sostituisci con il tuo Google Maps embed URL -->
				<iframe
					src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2796.2!2d9.18!3d45.46!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjA!5e0!3m2!1sit!2sit!4v1700000000000"
					width="100%"
					height="420"
					style="border:0;display:block"
					allowfullscreen=""
					loading="lazy"
					referrerpolicy="no-referrer-when-downgrade"
					title="La nostra sede"
				></iframe>
			</div>
			<!-- /wp:html -->
		</div>
		<!-- /wp:column -->

		<!-- Info contatti -->
		<!-- wp:column {"width":"45%","verticalAlignment":"center"} -->
		<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:45%">
			<!-- wp:group {"style":{"spacing":{"padding":{"all":"2.5rem"},"blockGap":"2rem"}},"backgroundColor":"surface","layout":{"type":"default"}} -->
			<div class="wp-block-group has-surface-background-color has-background" style="padding:2.5rem;gap:2rem">

				<!-- Indirizzo -->
				<!-- wp:group {"style":{"spacing":{"blockGap":"0.5rem"}},"layout":{"type":"default"}} -->
				<div class="wp-block-group" style="gap:0.5rem">
					<!-- wp:paragraph {"textColor":"accent","fontSize":"xs","className":"section-label"} -->
					<p class="has-accent-color has-text-color has-xs-font-size section-label">INDIRIZZO</p>
					<!-- /wp:paragraph -->
					<!-- wp:paragraph {"fontSize":"base"} -->
					<p class="has-base-font-size">Via Esempio 123, 20121 Milano (MI)</p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->

				<!-- wp:separator {"style":{"color":{"background":"#e0e0e0"}}} -->
				<hr class="wp-block-separator has-alpha-channel-opacity" style="background-color:#e0e0e0"/>
				<!-- /wp:separator -->

				<!-- Telefono -->
				<!-- wp:group {"style":{"spacing":{"blockGap":"0.5rem"}},"layout":{"type":"default"}} -->
				<div class="wp-block-group" style="gap:0.5rem">
					<!-- wp:paragraph {"textColor":"accent","fontSize":"xs","className":"section-label"} -->
					<p class="has-accent-color has-text-color has-xs-font-size section-label">TELEFONO</p>
					<!-- /wp:paragraph -->
					<!-- wp:paragraph {"fontSize":"base"} -->
					<p class="has-base-font-size"><a href="tel:+390200000000">+39 02 0000 0000</a></p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->

				<!-- wp:separator {"style":{"color":{"background":"#e0e0e0"}}} -->
				<hr class="wp-block-separator has-alpha-channel-opacity" style="background-color:#e0e0e0"/>
				<!-- /wp:separator -->

				<!-- Email -->
				<!-- wp:group {"style":{"spacing":{"blockGap":"0.5rem"}},"layout":{"type":"default"}} -->
				<div class="wp-block-group" style="gap:0.5rem">
					<!-- wp:paragraph {"textColor":"accent","fontSize":"xs","className":"section-label"} -->
					<p class="has-accent-color has-text-color has-xs-font-size section-label">EMAIL</p>
					<!-- /wp:paragraph -->
					<!-- wp:paragraph {"fontSize":"base"} -->
					<p class="has-base-font-size"><a href="mailto:info@esempio.it">info@esempio.it</a></p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->

				<!-- wp:separator {"style":{"color":{"background":"#e0e0e0"}}} -->
				<hr class="wp-block-separator has-alpha-channel-opacity" style="background-color:#e0e0e0"/>
				<!-- /wp:separator -->

				<!-- Orari -->
				<!-- wp:group {"style":{"spacing":{"blockGap":"0.5rem"}},"layout":{"type":"default"}} -->
				<div class="wp-block-group" style="gap:0.5rem">
					<!-- wp:paragraph {"textColor":"accent","fontSize":"xs","className":"section-label"} -->
					<p class="has-accent-color has-text-color has-xs-font-size section-label">ORARI</p>
					<!-- /wp:paragraph -->
					<!-- wp:paragraph {"textColor":"muted","fontSize":"sm"} -->
					<p class="has-muted-color has-text-color has-sm-font-size">Lun – Ven: 9:00 – 18:00<br>Sab – Dom: chiuso</p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->

				<!-- CTA -->
				<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"0.5rem"}}}} -->
				<div class="wp-block-buttons" style="margin-top:0.5rem">
					<!-- wp:button {"backgroundColor":"accent","textColor":"white"} -->
					<div class="wp-block-button"><a class="wp-block-button__link has-accent-background-color has-white-color has-background has-text-color wp-element-button" href="/contatti">Scrivici ora</a></div>
					<!-- /wp:button -->
				</div>
				<!-- /wp:buttons -->

			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->

	</div>
	<!-- /wp:columns -->

</div>
<!-- /wp:group -->
