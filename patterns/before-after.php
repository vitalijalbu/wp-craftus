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
	<div class="wp-block-spacer h-10" aria-hidden="true"></div>
	<!-- /wp:spacer -->

	<!-- Confronto immagini -->
	<!-- wp:columns {"isStackedOnMobile":true,"style":{"spacing":{"blockGap":{"left":"2rem","top":"2rem"}}}} -->
	<div class="wp-block-columns">

		<!-- PRIMA -->
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"className":"ba-card","backgroundColor":"surface","layout":{"type":"default"}} -->
			<div class="wp-block-group has-surface-background-color has-background ba-card">
				<!-- wp:image {"aspectRatio":"4/3","scale":"cover","sizeSlug":"large"} -->
				<figure class="wp-block-image size-large">
					<img src="" alt="Immagine Prima" class="aspect-4/3 object-cover"/>
				</figure>
				<!-- /wp:image -->
				<!-- wp:group {"className":"ba-card-body","layout":{"type":"default"}} -->
				<div class="wp-block-group ba-card-body">
					<!-- wp:paragraph {"textColor":"muted","fontSize":"xs","className":"section-label","style":{"spacing":{"margin":{"bottom":"0.25rem"}}}} -->
					<p class="has-muted-color has-text-color has-xs-font-size section-label mb-1">PRIMA</p>
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
			<!-- wp:group {"className":"ba-card-primary","backgroundColor":"surface","layout":{"type":"default"}} -->
			<div class="wp-block-group has-surface-background-color has-background ba-card-primary">
				<!-- wp:image {"aspectRatio":"4/3","scale":"cover","sizeSlug":"large"} -->
				<figure class="wp-block-image size-large">
					<img src="" alt="Immagine Dopo" class="aspect-4/3 object-cover"/>
				</figure>
				<!-- /wp:image -->
				<!-- wp:group {"className":"ba-card-body","layout":{"type":"default"}} -->
				<div class="wp-block-group ba-card-body">
					<!-- wp:paragraph {"textColor":"primary","fontSize":"xs","className":"section-label","style":{"spacing":{"margin":{"bottom":"0.25rem"}}}} -->
					<p class="has-primary-color has-text-color has-xs-font-size section-label mb-1">DOPO</p>
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
	<div class="wp-block-spacer h-10" aria-hidden="true"></div>
	<!-- /wp:spacer -->

	<!-- CTA -->
	<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
	<div class="wp-block-buttons">
		<!-- wp:button {"backgroundColor":"primary","textColor":"white"} -->
		<div class="wp-block-button"><a class="wp-block-button__link has-primary-background-color has-white-color has-background has-text-color wp-element-button" href="<?php echo esc_url(home_url(apply_filters('theme_portfolio_path', '/portfolio'))); ?>">Vedi altri progetti</a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->

</div>
<!-- /wp:group -->
