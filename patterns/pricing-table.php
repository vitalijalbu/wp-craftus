<?php
/**
 * Title: Tabella Prezzi – 3 Piani
 * Slug: theme/pricing-table
 * Categories: theme-sections
 * Keywords: prezzi, pricing, piani, abbonamento, tariffe, costi
 * Description: Sezione prezzi con 3 colonne — piano Base, Pro e Premium. Piano centrale evidenziato con sfondo accent.
 * Viewport Width: 1440
 */
?>
<!-- wp:group {"backgroundColor":"surface-alt","layout":{"type":"constrained"},"style":{"spacing":{"padding":{"top":"var:preset|spacing|9","bottom":"var:preset|spacing|9"}}}} -->
<div class="wp-block-group has-surface-alt-background-color has-background">

	<!-- Intestazione -->
	<!-- wp:group {"layout":{"type":"constrained","contentSize":"48rem"},"style":{"spacing":{"blockGap":"var:preset|spacing|4"}}} -->
	<div class="wp-block-group">
		<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"xs","className":"section-label"} -->
		<p class="has-text-align-center has-muted-color has-text-color has-xs-font-size section-label">PIANI E PREZZI</p>
		<!-- /wp:paragraph -->
		<!-- wp:heading {"level":2,"textAlign":"center","fontFamily":"serif","fontSize":"4xl"} -->
		<h2 class="wp-block-heading has-text-align-center has-serif-font-family has-4-xl-font-size">Scegli il piano giusto per te</h2>
		<!-- /wp:heading -->
		<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"base"} -->
		<p class="has-text-align-center has-muted-color has-text-color has-base-font-size">Tutti i piani includono assistenza e aggiornamenti. Passa a un piano superiore in qualsiasi momento.</p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- wp:spacer {"height":"2.5rem"} -->
	<div style="height:2.5rem" aria-hidden="true" class="wp-block-spacer"></div>
	<!-- /wp:spacer -->

	<!-- 3 colonne pricing -->
	<!-- wp:columns {"isStackedOnMobile":true,"verticalAlignment":"stretch","style":{"spacing":{"blockGap":{"left":"1.5rem","top":"1.5rem"}}}} -->
	<div class="wp-block-columns are-vertically-aligned-stretch">

		<!-- Piano Base -->
		<!-- wp:column {"verticalAlignment":"stretch"} -->
		<div class="wp-block-column is-vertically-aligned-stretch">
			<!-- wp:group {"style":{"border":{"width":"1px","style":"solid","color":"#e0e0e0"},"spacing":{"padding":{"all":"2.5rem"}}},"backgroundColor":"surface","layout":{"type":"default"}} -->
			<div class="wp-block-group has-surface-background-color has-background" style="border:1px solid #e0e0e0;padding:2.5rem">
				<!-- wp:paragraph {"textColor":"muted","fontSize":"xs","className":"section-label"} -->
				<p class="has-muted-color has-text-color has-xs-font-size section-label">BASE</p>
				<!-- /wp:paragraph -->
				<!-- wp:heading {"level":3,"fontSize":"2xl"} -->
				<h3 class="wp-block-heading has-2-xl-font-size">Starter</h3>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"textColor":"muted","fontSize":"sm"} -->
				<p class="has-muted-color has-text-color has-sm-font-size">Ideale per chi inizia e ha bisogno di una presenza online professionale.</p>
				<!-- /wp:paragraph -->
				<!-- wp:separator {"className":"is-style-accent","style":{"spacing":{"margin":{"top":"1.5rem","bottom":"1.5rem"}}}} -->
				<hr class="wp-block-separator has-alpha-channel-opacity is-style-accent" style="margin-top:1.5rem;margin-bottom:1.5rem"/>
				<!-- /wp:separator -->
				<!-- wp:heading {"level":2,"fontSize":"5xl","fontFamily":"serif"} -->
				<h2 class="wp-block-heading has-serif-font-family has-5-xl-font-size">€29<span style="font-size:1rem;font-weight:400">/mese</span></h2>
				<!-- /wp:heading -->
				<!-- wp:list {"textColor":"muted","fontSize":"sm","style":{"spacing":{"padding":{"left":"1rem"},"blockGap":"0.5rem"}}} -->
				<ul class="wp-block-list has-muted-color has-text-color has-sm-font-size" style="padding-left:1rem;gap:0.5rem">
					<li>5 pagine incluse</li>
					<li>Form di contatto</li>
					<li>SSL + hosting ottimizzato</li>
					<li>Supporto via email</li>
				</ul>
				<!-- /wp:list -->
				<!-- wp:spacer {"height":"1.5rem"} -->
				<div style="height:1.5rem" aria-hidden="true" class="wp-block-spacer"></div>
				<!-- /wp:spacer -->
				<!-- wp:buttons -->
				<div class="wp-block-buttons">
					<!-- wp:button {"className":"is-style-outline","width":100} -->
					<div class="wp-block-button is-style-outline has-custom-width wp-block-button__width-100"><a class="wp-block-button__link wp-element-button" href="/contatti">Inizia ora</a></div>
					<!-- /wp:button -->
				</div>
				<!-- /wp:buttons -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->

		<!-- Piano Pro — evidenziato -->
		<!-- wp:column {"verticalAlignment":"stretch"} -->
		<div class="wp-block-column is-vertically-aligned-stretch">
			<!-- wp:group {"backgroundColor":"ink","textColor":"white","style":{"border":{"width":"0"},"spacing":{"padding":{"all":"2.5rem"}}},"layout":{"type":"default"}} -->
			<div class="wp-block-group has-ink-background-color has-white-color has-background has-text-color" style="padding:2.5rem">
				<!-- wp:paragraph {"textColor":"accent","fontSize":"xs","className":"section-label"} -->
				<p class="has-accent-color has-text-color has-xs-font-size section-label">PIÙ POPOLARE</p>
				<!-- /wp:paragraph -->
				<!-- wp:heading {"level":3,"textColor":"white","fontSize":"2xl"} -->
				<h3 class="wp-block-heading has-white-color has-text-color has-2-xl-font-size">Pro</h3>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"textColor":"white","fontSize":"sm","style":{"color":{"text":"rgba(255,255,255,0.6)"}}} -->
				<p class="has-text-color has-sm-font-size" style="color:rgba(255,255,255,0.6)">La scelta giusta per aziende in crescita che vogliono risultati concreti.</p>
				<!-- /wp:paragraph -->
				<!-- wp:separator {"style":{"color":{"background":"rgba(255,255,255,0.15)"},"spacing":{"margin":{"top":"1.5rem","bottom":"1.5rem"}}}} -->
				<hr class="wp-block-separator has-alpha-channel-opacity" style="background-color:rgba(255,255,255,0.15);margin-top:1.5rem;margin-bottom:1.5rem"/>
				<!-- /wp:separator -->
				<!-- wp:heading {"level":2,"textColor":"white","fontSize":"5xl","fontFamily":"serif"} -->
				<h2 class="wp-block-heading has-white-color has-text-color has-serif-font-family has-5-xl-font-size">€79<span style="font-size:1rem;font-weight:400">/mese</span></h2>
				<!-- /wp:heading -->
				<!-- wp:list {"textColor":"white","fontSize":"sm","style":{"color":{"text":"rgba(255,255,255,0.8)"},"spacing":{"padding":{"left":"1rem"},"blockGap":"0.5rem"}}} -->
				<ul class="wp-block-list has-text-color has-sm-font-size" style="color:rgba(255,255,255,0.8);padding-left:1rem;gap:0.5rem">
					<li>Pagine illimitate</li>
					<li>E-commerce (fino a 500 prodotti)</li>
					<li>SEO avanzato</li>
					<li>Supporto prioritario</li>
					<li>Report mensili</li>
				</ul>
				<!-- /wp:list -->
				<!-- wp:spacer {"height":"1.5rem"} -->
				<div style="height:1.5rem" aria-hidden="true" class="wp-block-spacer"></div>
				<!-- /wp:spacer -->
				<!-- wp:buttons -->
				<div class="wp-block-buttons">
					<!-- wp:button {"backgroundColor":"accent","textColor":"white","width":100} -->
					<div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link has-accent-background-color has-white-color has-background has-text-color wp-element-button" href="/contatti">Inizia ora</a></div>
					<!-- /wp:button -->
				</div>
				<!-- /wp:buttons -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->

		<!-- Piano Premium -->
		<!-- wp:column {"verticalAlignment":"stretch"} -->
		<div class="wp-block-column is-vertically-aligned-stretch">
			<!-- wp:group {"style":{"border":{"width":"1px","style":"solid","color":"#e0e0e0"},"spacing":{"padding":{"all":"2.5rem"}}},"backgroundColor":"surface","layout":{"type":"default"}} -->
			<div class="wp-block-group has-surface-background-color has-background" style="border:1px solid #e0e0e0;padding:2.5rem">
				<!-- wp:paragraph {"textColor":"muted","fontSize":"xs","className":"section-label"} -->
				<p class="has-muted-color has-text-color has-xs-font-size section-label">PREMIUM</p>
				<!-- /wp:paragraph -->
				<!-- wp:heading {"level":3,"fontSize":"2xl"} -->
				<h3 class="wp-block-heading has-2-xl-font-size">Enterprise</h3>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"textColor":"muted","fontSize":"sm"} -->
				<p class="has-muted-color has-text-color has-sm-font-size">Soluzione completa per realtà strutturate con esigenze personalizzate.</p>
				<!-- /wp:paragraph -->
				<!-- wp:separator {"className":"is-style-accent","style":{"spacing":{"margin":{"top":"1.5rem","bottom":"1.5rem"}}}} -->
				<hr class="wp-block-separator has-alpha-channel-opacity is-style-accent" style="margin-top:1.5rem;margin-bottom:1.5rem"/>
				<!-- /wp:separator -->
				<!-- wp:heading {"level":2,"fontSize":"5xl","fontFamily":"serif"} -->
				<h2 class="wp-block-heading has-serif-font-family has-5-xl-font-size">Su misura</h2>
				<!-- /wp:heading -->
				<!-- wp:list {"textColor":"muted","fontSize":"sm","style":{"spacing":{"padding":{"left":"1rem"},"blockGap":"0.5rem"}}} -->
				<ul class="wp-block-list has-muted-color has-text-color has-sm-font-size" style="padding-left:1rem;gap:0.5rem">
					<li>Tutto il piano Pro</li>
					<li>Sviluppo custom</li>
					<li>Integrazioni CRM/ERP</li>
					<li>Account manager dedicato</li>
				</ul>
				<!-- /wp:list -->
				<!-- wp:spacer {"height":"1.5rem"} -->
				<div style="height:1.5rem" aria-hidden="true" class="wp-block-spacer"></div>
				<!-- /wp:spacer -->
				<!-- wp:buttons -->
				<div class="wp-block-buttons">
					<!-- wp:button {"className":"is-style-outline","width":100} -->
					<div class="wp-block-button is-style-outline has-custom-width wp-block-button__width-100"><a class="wp-block-button__link wp-element-button" href="/contatti">Contattaci</a></div>
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
