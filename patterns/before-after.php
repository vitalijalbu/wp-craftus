<?php
/**
 * Title: Before/After – Confronto Risultati
 * Slug: theme/before-after
 * Categories: theme-sections
 * Keywords: before, after, prima, dopo, confronto, trasformazione, risultati
 * Description: Sezione confronto prima/dopo con due immagini affiancate e didascalie. Ideale per ristrutturazioni, beauty, design.
 * Viewport Width: 1440
 */
?>
<!-- wp:group {"backgroundColor":"surface-alt","layout":{"type":"constrained"},"style":{"spacing":{"padding":{"top":"var:preset|spacing|9","bottom":"var:preset|spacing|9"}}}} -->
<div class="wp-block-group has-surface-alt-background-color has-background">

	<!-- Intestazione -->
	<!-- wp:group {"layout":{"type":"constrained","contentSize":"48rem"},"style":{"spacing":{"blockGap":"var:preset|spacing|4"}}} -->
	<div class="wp-block-group">
		<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"xs","className":"section-label"} -->
		<p class="has-text-align-center has-muted-color has-text-color has-xs-font-size section-label">I RISULTATI</p>
		<!-- /wp:paragraph -->
		<!-- wp:heading {"level":2,"textAlign":"center","fontFamily":"serif","fontSize":"4xl"} -->
		<h2 class="wp-block-heading has-text-align-center has-serif-font-family has-4-xl-font-size">Prima e Dopo</h2>
		<!-- /wp:heading -->
		<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"base"} -->
		<p class="has-text-align-center has-muted-color has-text-color has-base-font-size">La differenza è evidente. Guarda come trasformiamo ogni progetto in un risultato straordinario.</p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- wp:spacer {"height":"2.5rem"} -->
	<div style="height:2.5rem" aria-hidden="true" class="wp-block-spacer"></div>
	<!-- /wp:spacer -->

	<!-- Confronto immagini -->
	<!-- wp:columns {"isStackedOnMobile":true,"style":{"spacing":{"blockGap":{"left":"2rem","top":"2rem"}}}} -->
	<div class="wp-block-columns">

		<!-- PRIMA -->
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"style":{"border":{"width":"1px","style":"solid","color":"#e0e0e0"},"spacing":{"padding":{"all":"0"}}},"backgroundColor":"surface","layout":{"type":"default"}} -->
			<div class="wp-block-group has-surface-background-color has-background" style="border:1px solid var(--wp--preset--color--border, #e0e0e0)">
				<!-- wp:image {"aspectRatio":"4/3","scale":"cover","sizeSlug":"large"} -->
				<figure class="wp-block-image size-large">
					<img src="" alt="Immagine Prima" style="aspect-ratio:4/3;object-fit:cover"/>
				</figure>
				<!-- /wp:image -->
				<!-- wp:group {"style":{"spacing":{"padding":{"top":"1.25rem","bottom":"1.25rem","left":"1.5rem","right":"1.5rem"}}}} -->
				<div class="wp-block-group" style="padding:1.25rem 1.5rem">
					<!-- wp:paragraph {"textColor":"muted","fontSize":"xs","className":"section-label","style":{"spacing":{"margin":{"bottom":"0.25rem"}}}} -->
					<p class="has-muted-color has-text-color has-xs-font-size section-label" style="margin-bottom:0.25rem">PRIMA</p>
					<!-- /wp:paragraph -->
					<!-- wp:heading {"level":4,"fontSize":"lg"} -->
					<h4 class="wp-block-heading has-lg-font-size">Situazione di partenza</h4>
					<!-- /wp:heading -->
					<!-- wp:paragraph {"textColor":"muted","fontSize":"sm"} -->
					<p class="has-muted-color has-text-color has-sm-font-size">Descrivi brevemente la situazione iniziale del cliente o del progetto prima dell'intervento.</p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->

		<!-- DOPO -->
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"style":{"border":{"width":"2px","style":"solid","color":"#0074C7"},"spacing":{"padding":{"all":"0"}}},"backgroundColor":"surface","layout":{"type":"default"}} -->
			<div class="wp-block-group has-surface-background-color has-background" style="border:2px solid var(--wp--preset--color--accent, #0074C7)">
				<!-- wp:image {"aspectRatio":"4/3","scale":"cover","sizeSlug":"large"} -->
				<figure class="wp-block-image size-large">
					<img src="" alt="Immagine Dopo" style="aspect-ratio:4/3;object-fit:cover"/>
				</figure>
				<!-- /wp:image -->
				<!-- wp:group {"style":{"spacing":{"padding":{"top":"1.25rem","bottom":"1.25rem","left":"1.5rem","right":"1.5rem"}}}} -->
				<div class="wp-block-group" style="padding:1.25rem 1.5rem">
					<!-- wp:paragraph {"textColor":"accent","fontSize":"xs","className":"section-label","style":{"spacing":{"margin":{"bottom":"0.25rem"}}}} -->
					<p class="has-accent-color has-text-color has-xs-font-size section-label" style="margin-bottom:0.25rem">DOPO</p>
					<!-- /wp:paragraph -->
					<!-- wp:heading {"level":4,"fontSize":"lg"} -->
					<h4 class="wp-block-heading has-lg-font-size">Il risultato ottenuto</h4>
					<!-- /wp:heading -->
					<!-- wp:paragraph {"textColor":"muted","fontSize":"sm"} -->
					<p class="has-muted-color has-text-color has-sm-font-size">Descrivi il risultato raggiunto, i benefici concreti e il cambiamento ottenuto grazie al tuo lavoro.</p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->

	</div>
	<!-- /wp:columns -->

	<!-- wp:spacer {"height":"2.5rem"} -->
	<div style="height:2.5rem" aria-hidden="true" class="wp-block-spacer"></div>
	<!-- /wp:spacer -->

	<!-- CTA -->
	<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
	<div class="wp-block-buttons">
		<!-- wp:button {"backgroundColor":"accent","textColor":"white"} -->
		<div class="wp-block-button"><a class="wp-block-button__link has-accent-background-color has-white-color has-background has-text-color wp-element-button" href="/portfolio">Vedi altri progetti</a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->

</div>
<!-- /wp:group -->
