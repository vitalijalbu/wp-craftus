<?php
/**
 * Title: Mappa + Contatti – Split
 * Slug: theme/map-contact
 * Categories: theme-sections
 * Keywords: mappa, contatti, indirizzo, dove siamo, google maps, sede, ufficio
 * Description: Sezione split con mappa embed a sinistra e informazioni di contatto a destra. Sfondo chiaro.
 * Viewport Width: 1440
 */
$contact_phone = get_theme_mod('contact_phone', '');
$contact_email = get_theme_mod('contact_email', '');
$contact_address = get_theme_mod('contact_address', 'Via Esempio 123, 20121 Milano (MI)');
$contact_hours = get_theme_mod('contact_hours', "Lun – Ven: 9:00 – 18:00\nSab – Dom: chiuso");
$contact_map_url = get_theme_mod('contact_map_embed_url', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2796.2!2d9.18!3d45.46!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjA!5e0!3m2!1sit!2sit!4v1700000000000');
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
	<div class="wp-block-spacer h-10" aria-hidden="true"></div>
	<!-- /wp:spacer -->

	<!-- Split: mappa + info -->
	<!-- wp:columns {"isStackedOnMobile":true,"verticalAlignment":"stretch","style":{"spacing":{"blockGap":{"left":"0","top":"2rem"}}}} -->
	<div class="wp-block-columns are-vertically-aligned-stretch">

		<!-- Mappa embed -->
		<!-- wp:column {"width":"55%"} -->
		<div class="wp-block-column basis-[55%]">
			<!-- wp:html -->
			<div class="w-full h-105 overflow-hidden">
				<iframe
					src="<?php echo esc_url($contact_map_url); ?>"
					width="100%"
					height="420"
					class="map-iframe"
					allowfullscreen=""
					loading="lazy"
					referrerpolicy="no-referrer-when-downgrade"
					title="<?php esc_attr_e('La nostra sede', 'sage'); ?>"
				></iframe>
			</div>
			<!-- /wp:html -->
		</div>
		<!-- /wp:column -->

		<!-- Info contatti -->
		<!-- wp:column {"width":"45%","verticalAlignment":"center"} -->
		<div class="wp-block-column is-vertically-aligned-center basis-[45%]">
			<!-- wp:group {"className":"theme-contact-info","backgroundColor":"surface","layout":{"type":"default"}} -->
			<div class="wp-block-group theme-contact-info has-surface-background-color has-background">

				<!-- Indirizzo -->
				<!-- wp:group {"style":{"spacing":{"blockGap":"0.5rem"}},"layout":{"type":"default"}} -->
				<div class="wp-block-group gap-2">
					<!-- wp:paragraph {"textColor":"muted","fontSize":"xs","className":"section-label"} -->
					<p class="has-muted-color has-text-color has-xs-font-size section-label">INDIRIZZO</p>
					<!-- /wp:paragraph -->
					<!-- wp:paragraph {"fontSize":"base"} -->
					<p class="has-base-font-size"><?php echo nl2br(esc_html($contact_address)); ?></p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->

				<!-- wp:separator {"className":"bg-border"} -->
				<hr class="wp-block-separator has-alpha-channel-opacity bg-border"/>
				<!-- /wp:separator -->

				<?php if ($contact_phone) { ?>
				<!-- Telefono -->
				<!-- wp:group {"style":{"spacing":{"blockGap":"0.5rem"}},"layout":{"type":"default"}} -->
				<div class="wp-block-group gap-2">
					<!-- wp:paragraph {"textColor":"muted","fontSize":"xs","className":"section-label"} -->
					<p class="has-muted-color has-text-color has-xs-font-size section-label">TELEFONO</p>
					<!-- /wp:paragraph -->
					<!-- wp:paragraph {"fontSize":"base"} -->
					<p class="has-base-font-size"><a href="tel:<?php echo esc_attr(preg_replace('/[^+\d]/', '', $contact_phone)); ?>"><?php echo esc_html($contact_phone); ?></a></p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->

				<!-- wp:separator {"className":"bg-border"} -->
				<hr class="wp-block-separator has-alpha-channel-opacity bg-border"/>
				<!-- /wp:separator -->
				<?php } ?>

				<?php if ($contact_email) { ?>
				<!-- Email -->
				<!-- wp:group {"style":{"spacing":{"blockGap":"0.5rem"}},"layout":{"type":"default"}} -->
				<div class="wp-block-group gap-2">
					<!-- wp:paragraph {"textColor":"muted","fontSize":"xs","className":"section-label"} -->
					<p class="has-muted-color has-text-color has-xs-font-size section-label">EMAIL</p>
					<!-- /wp:paragraph -->
					<!-- wp:paragraph {"fontSize":"base"} -->
					<p class="has-base-font-size"><a href="mailto:<?php echo esc_attr($contact_email); ?>"><?php echo esc_html($contact_email); ?></a></p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->

				<!-- wp:separator {"className":"bg-border"} -->
				<hr class="wp-block-separator has-alpha-channel-opacity bg-border"/>
				<!-- /wp:separator -->
				<?php } ?>

				<!-- Orari -->
				<!-- wp:group {"style":{"spacing":{"blockGap":"0.5rem"}},"layout":{"type":"default"}} -->
				<div class="wp-block-group gap-2">
					<!-- wp:paragraph {"textColor":"muted","fontSize":"xs","className":"section-label"} -->
					<p class="has-muted-color has-text-color has-xs-font-size section-label">ORARI</p>
					<!-- /wp:paragraph -->
					<!-- wp:paragraph {"textColor":"muted","fontSize":"sm"} -->
					<p class="has-muted-color has-text-color has-sm-font-size"><?php echo nl2br(esc_html($contact_hours)); ?></p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->

				<!-- CTA -->
				<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"0.5rem"}}}} -->
				<div class="wp-block-buttons mt-2">
					<!-- wp:button {"backgroundColor":"primary","textColor":"white"} -->
					<div class="wp-block-button"><a class="wp-block-button__link has-primary-background-color has-white-color has-background has-text-color wp-element-button" href="/contatti">Scrivici ora</a></div>
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
