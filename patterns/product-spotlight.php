<?php
/**
 * Title: Prodotto in Evidenza
 * Slug: theme/product-spotlight
 * Categories: theme-sections
 * Keywords: prodotto, spotlight, evidenza, featured, shop, woocommerce
 * Description: Sezione split con immagine grande a sinistra e dettagli prodotto a destra. Ideale per mettere in evidenza un singolo prodotto.
 * Viewport Width: 1440
 */
?>
<!-- wp:group {"className":"bg-cream overflow-hidden","layout":{"type":"default"}} -->
<div class="wp-block-group bg-cream overflow-hidden">

	<!-- wp:columns {"isStackedOnMobile":true,"style":{"spacing":{"blockGap":"0"}}} -->
	<div class="wp-block-columns is-layout-flex">

		<!-- wp:column {"width":"55%","style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"}}}} -->
		<div class="wp-block-column basis-[55%] p-0">
			<!-- wp:image {"aspectRatio":"4/5","scale":"cover","style":{"dimensions":{"minHeight":"100%"}}} -->
			<figure class="wp-block-image min-h-full">
				<img src="" alt="Immagine prodotto in evidenza" class="aspect-square object-cover w-full h-full"/>
			</figure>
			<!-- /wp:image -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"width":"45%","style":{"spacing":{"padding":{"top":"4rem","bottom":"4rem","left":"3rem","right":"3rem"}}}} -->
		<div class="wp-block-column basis-[45%] py-16 px-12">
			<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","orientation":"vertical","justifyContent":"left"},"style":{"spacing":{"blockGap":"1.5rem"}}} -->
			<div class="wp-block-group">

				<!-- wp:paragraph {"className":"section-label text-muted"} -->
				<p class="section-label text-muted">NOVITÀ</p>
				<!-- /wp:paragraph -->

				<!-- wp:heading {"level":2,"className":"font-serif text-4xl font-light text-ink leading-tight"} -->
				<h2 class="font-serif text-4xl font-light text-ink leading-tight">Nome del Prodotto<br>in Evidenza</h2>
				<!-- /wp:heading -->

				<!-- wp:paragraph {"className":"text-sm text-muted leading-relaxed"} -->
				<p class="text-sm text-muted leading-relaxed">Descrizione breve del prodotto. Racconta cosa lo rende speciale, i materiali, l'artigianalità o la storia che lo ispira. Qualità e stile in un unico oggetto.</p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"className":"font-serif text-3xl font-light text-ink"} -->
				<p class="font-serif text-3xl font-light text-ink">€ 199,00</p>
				<!-- /wp:paragraph -->

				<!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"left"},"style":{"spacing":{"blockGap":"1rem"}}} -->
				<div class="wp-block-group">
					<!-- wp:buttons -->
					<div class="wp-block-buttons">
						<!-- wp:button {"className":"btn-primary"} -->
						<div class="wp-block-button btn-primary"><a class="wp-block-button__link" href="#">Aggiungi al carrello</a></div>
						<!-- /wp:button -->
					</div>
					<!-- /wp:buttons -->
					<!-- wp:paragraph {"className":"text-xs text-muted self-center"} -->
					<p class="text-xs text-muted self-center"><a href="#" class="hover:text-ink transition-colors">Scopri tutti i dettagli →</a></p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->

				<!-- wp:separator {"className":"border-border my-4"} -->
				<hr class="wp-block-separator border-border my-4"/>
				<!-- /wp:separator -->

				<!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"left"},"style":{"spacing":{"blockGap":"2rem"}}} -->
				<div class="wp-block-group">
					<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","orientation":"vertical"},"style":{"spacing":{"blockGap":"0.125rem"}}} -->
					<div class="wp-block-group">
						<!-- wp:paragraph {"className":"    font-semibold tracking-widest uppercase text-muted"} -->
						<p class="font-semibold tracking-widest uppercase text-muted">Materiale</p>
						<!-- /wp:paragraph -->
						<!-- wp:paragraph {"className":"text-sm text-ink"} -->
						<p class="text-sm text-ink">100% Cotone biologico</p>
						<!-- /wp:paragraph -->
					</div>
					<!-- /wp:group -->
					<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","orientation":"vertical"},"style":{"spacing":{"blockGap":"0.125rem"}}} -->
					<div class="wp-block-group">
						<!-- wp:paragraph {"className":"    font-semibold tracking-widest uppercase text-muted"} -->
						<p class="    font-semibold tracking-widest uppercase text-muted">Produzione</p>
						<!-- /wp:paragraph -->
						<!-- wp:paragraph {"className":"text-sm text-ink"} -->
						<p class="text-sm text-ink">Made in Italy</p>
						<!-- /wp:paragraph -->
					</div>
					<!-- /wp:group -->
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
