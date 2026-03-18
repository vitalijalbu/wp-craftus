<?php
/**
 * Title: Sezione Contatti – Form + Info
 * Slug: theme/contact-section
 * Categories: theme-sections
 * Keywords: contatti, form, email, telefono, indirizzo, messaggio
 * Description: Sezione contatti a due colonne: sinistra con informazioni di contatto (tel, email, WhatsApp, indirizzo), destra con form di contatto via CF7 o HTML nativo.
 * Viewport Width: 1440
 */
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"}}},"backgroundColor":"white","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-white-background-color has-background">

	<!-- Titolo sezione -->
	<!-- wp:group {"layout":{"type":"constrained","contentSize":"36rem"}} -->
	<div class="wp-block-group">
		<!-- wp:heading {"level":2,"textAlign":"center","textColor":"dark","fontSize":"2xl","className":"theme-section-title"} -->
		<h2 class="wp-block-heading has-text-align-center has-dark-color has-text-color has-2-xl-font-size theme-section-title">Parliamo del tuo progetto</h2>
		<!-- /wp:heading -->
		<!-- wp:paragraph {"align":"center","fontSize":"lg"} -->
		<p class="has-text-align-center has-lg-font-size">Compila il form o contattaci direttamente. Rispondiamo entro 24 ore lavorative.</p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- Due colonne: info + form -->
	<!-- wp:columns {"verticalAlignment":"top"} -->
	<div class="wp-block-columns are-vertically-aligned-top">

		<!-- Colonna info contatti -->
		<!-- wp:column {"width":"40%","verticalAlignment":"top"} -->
		<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:40%">

			<!-- wp:group -->
			<div class="wp-block-group">

				<!-- Telefono -->
				<!-- wp:group {"layout":{"type":"flex","verticalAlignment":"center"}} -->
				<div class="wp-block-group">
					<!-- wp:paragraph {"fontSize":"2xl"} -->
					<p class="has-2-xl-font-size">📞</p>
					<!-- /wp:paragraph -->
					<!-- wp:group {"layout":{"type":"default"}} -->
					<div class="wp-block-group">
						<!-- wp:paragraph {"className":"theme-section-label"} -->
						<p class="theme-section-label">Telefono</p>
						<!-- /wp:paragraph -->
						<!-- wp:paragraph {"fontSize":"lg"} -->
						<p class="has-lg-font-size"><a href="tel:+39030000000">030 000 000</a></p>
						<!-- /wp:paragraph -->
					</div>
					<!-- /wp:group -->
				</div>
				<!-- /wp:group -->

				<!-- Email -->
				<!-- wp:group {"layout":{"type":"flex","verticalAlignment":"center"}} -->
				<div class="wp-block-group">
					<!-- wp:paragraph {"fontSize":"2xl"} -->
					<p class="has-2-xl-font-size">✉️</p>
					<!-- /wp:paragraph -->
					<!-- wp:group {"layout":{"type":"default"}} -->
					<div class="wp-block-group">
						<!-- wp:paragraph {"className":"theme-section-label"} -->
						<p class="theme-section-label">Email</p>
						<!-- /wp:paragraph -->
						<!-- wp:paragraph {"fontSize":"lg"} -->
						<p class="has-lg-font-size"><a href="mailto:info@theme.it">info@theme.it</a></p>
						<!-- /wp:paragraph -->
					</div>
					<!-- /wp:group -->
				</div>
				<!-- /wp:group -->

				<!-- WhatsApp -->
				<!-- wp:group {"layout":{"type":"flex","verticalAlignment":"center"}} -->
				<div class="wp-block-group">
					<!-- wp:paragraph {"fontSize":"2xl"} -->
					<p class="has-2-xl-font-size">💬</p>
					<!-- /wp:paragraph -->
					<!-- wp:group {"layout":{"type":"default"}} -->
					<div class="wp-block-group">
						<!-- wp:paragraph {"className":"theme-section-label"} -->
						<p class="theme-section-label">WhatsApp</p>
						<!-- /wp:paragraph -->
						<!-- wp:paragraph {"fontSize":"lg"} -->
						<p class="has-lg-font-size"><a href="https://wa.me/393000000000" target="_blank" rel="noopener">Scrivici su WhatsApp</a></p>
						<!-- /wp:paragraph -->
					</div>
					<!-- /wp:group -->
				</div>
				<!-- /wp:group -->

				<!-- Sede -->
				<!-- wp:group {"layout":{"type":"flex","verticalAlignment":"center"}} -->
				<div class="wp-block-group">
					<!-- wp:paragraph {"fontSize":"2xl"} -->
					<p class="has-2-xl-font-size">📍</p>
					<!-- /wp:paragraph -->
					<!-- wp:group {"layout":{"type":"default"}} -->
					<div class="wp-block-group">
						<!-- wp:paragraph {"className":"theme-section-label"} -->
						<p class="theme-section-label">Sede</p>
						<!-- /wp:paragraph -->
						<!-- wp:paragraph {"fontSize":"lg"} -->
						<p class="has-lg-font-size">Pavia, Lombardia – Italia</p>
						<!-- /wp:paragraph -->
					</div>
					<!-- /wp:group -->
				</div>
				<!-- /wp:group -->

			</div>
			<!-- /wp:group -->

		</div>
		<!-- /wp:column -->

		<!-- Colonna form contatti -->
		<!-- wp:column {"width":"60%","verticalAlignment":"top"} -->
		<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:60%">

			<!-- wp:group {"className":"theme-contact-form","style":{"spacing":{"padding":{"all":"2.5rem"}},"border":{"width":"1px","style":"solid","color":"#e5e7eb"}}} -->
			<div class="wp-block-group theme-contact-form" style="border:1px solid #e5e7eb;padding:2.5rem">

				<!-- wp:html -->
				<form class="theme-form" id="theme-contact-form" novalidate>
					<div class="theme-form__row">
						<label for="theme-name" class="theme-form__label">Nome e Cognome *</label>
						<input type="text" id="theme-name" name="name" class="theme-form__input" required placeholder="Mario Rossi"/>
					</div>
					<div class="theme-form__row">
						<label for="theme-email" class="theme-form__label">Email *</label>
						<input type="email" id="theme-email" name="email" class="theme-form__input" required placeholder="mario@azienda.it"/>
					</div>
					<div class="theme-form__row">
						<label for="theme-phone" class="theme-form__label">Telefono</label>
						<input type="tel" id="theme-phone" name="phone" class="theme-form__input" placeholder="+39 030 000 000"/>
					</div>
					<div class="theme-form__row">
						<label for="theme-subject" class="theme-form__label">Oggetto *</label>
						<input type="text" id="theme-subject" name="subject" class="theme-form__input" required placeholder="Oggetto del messaggio"/>
					</div>
					<div class="theme-form__row">
						<label for="theme-message" class="theme-form__label">Messaggio *</label>
						<textarea id="theme-message" name="message" class="theme-form__textarea" rows="5" required placeholder="Descrivi il tuo progetto o la tua richiesta..."></textarea>
					</div>
					<div class="theme-form__row theme-form__privacy">
						<label class="theme-form__checkbox-label">
							<input type="checkbox" name="privacy" required/>
							Ho letto e accetto la <a href="/privacy-policy" target="_blank">Privacy Policy</a>. *
						</label>
					</div>
					<div class="theme-form__submit">
						<button type="submit" class="theme-btn theme-btn--primary theme-btn--full">
							Invia il messaggio →
						</button>
					</div>
					<div class="theme-form__feedback" aria-live="polite"></div>
				</form>
				<!-- /wp:html -->

			</div>
			<!-- /wp:group -->

		</div>
		<!-- /wp:column -->

	</div>
	<!-- /wp:columns -->

</div>
<!-- /wp:group -->
