<?php
/**
 * Title: Immagine con Lista di Benefici
 * Slug: theme/image-text-list
 * Categories: theme-sections
 * Keywords: immagine, lista, benefici, features, icone, testo
 * Description: Layout split con immagine a sinistra e lista di caratteristiche/benefici a destra. Adatto per presentare i punti di forza di un servizio o prodotto.
 * Viewport Width: 1440
 */
?>
<!-- wp:group {"className":"section bg-surface","layout":{"type":"default"}} -->
<div class="wp-block-group section bg-surface">

	<div class="container">

	<!-- wp:columns {"isStackedOnMobile":true,"style":{"spacing":{"blockGap":{"left":"5rem","top":"3rem"}},"verticalAlignment":"center"}} -->
	<div class="wp-block-columns items-center">

		<!-- wp:column {"width":"50%","verticalAlignment":"center"} -->
		<div class="wp-block-column basis-1/2">
			<!-- wp:image {"aspectRatio":"4/5","scale":"cover","className":"overflow-hidden"} -->
			<figure class="wp-block-image overflow-hidden">
				<img src="" alt="Immagine sezione" class="aspect-square object-cover w-full"/>
			</figure>
			<!-- /wp:image -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"width":"50%","verticalAlignment":"center"} -->
		<div class="wp-block-column basis-1/2">

			<!-- wp:paragraph {"className":"section-label text-muted"} -->
			<p class="section-label text-muted">I NOSTRI VALORI</p>
			<!-- /wp:paragraph -->

			<!-- wp:heading {"level":2,"className":"section-title text-ink"} -->
			<h2 class="section-title text-ink">Perché sceglierci</h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"className":"text-sm text-muted mt-4 leading-relaxed"} -->
			<p class="text-sm text-muted mt-4 leading-relaxed">Una breve introduzione che contestualizza la lista sottostante e invita il lettore a scoprire di più sul brand o sul prodotto.</p>
			<!-- /wp:paragraph -->

			<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","orientation":"vertical"},"style":{"spacing":{"blockGap":"0","marginTop":"2rem"}}} -->
			<div class="wp-block-group mt-8">

				<!-- Item 1 -->
				<!-- wp:group {"className":"itl-item","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"left"},"style":{"spacing":{"blockGap":"1.25rem","padding":{"top":"1.25rem","bottom":"1.25rem"}},"border":{"bottom":{"color":"var(--color-border)","width":"1px"}}}} -->
				<div class="wp-block-group itl-item">
					<!-- wp:image {"width":"48px","height":"48px","className":"shrink-0 object-contain"} -->
					<figure class="wp-block-image"><img src="" alt="Icona" class="itl-icon"/></figure>
					<!-- /wp:image -->
					<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","orientation":"vertical"},"style":{"spacing":{"blockGap":"0.25rem"}}} -->
					<div class="wp-block-group">
						<!-- wp:heading {"level":3,"className":"text-lg font-light text-ink"} -->
						<h3 class="text-lg font-light text-ink">Qualità artigianale</h3>
						<!-- /wp:heading -->
						<!-- wp:paragraph {"className":"text-sm text-muted"} -->
						<p class="text-sm text-muted">Ogni prodotto è realizzato a mano con cura e attenzione al dettaglio.</p>
						<!-- /wp:paragraph -->
					</div>
					<!-- /wp:group -->
				</div>
				<!-- /wp:group -->

				<!-- Item 2 -->
				<!-- wp:group {"className":"itl-item","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"left"},"style":{"spacing":{"blockGap":"1.25rem","padding":{"top":"1.25rem","bottom":"1.25rem"}},"border":{"bottom":{"color":"var(--color-border)","width":"1px"}}}} -->
				<div class="wp-block-group itl-item">
					<!-- wp:image {"width":"48px","height":"48px","className":"shrink-0 object-contain"} -->
					<figure class="wp-block-image"><img src="" alt="Icona" class="itl-icon"/></figure>
					<!-- /wp:image -->
					<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","orientation":"vertical"},"style":{"spacing":{"blockGap":"0.25rem"}}} -->
					<div class="wp-block-group">
						<!-- wp:heading {"level":3,"className":"text-lg font-light text-ink"} -->
						<h3 class="text-lg font-light text-ink">Materiali sostenibili</h3>
						<!-- /wp:heading -->
						<!-- wp:paragraph {"className":"text-sm text-muted"} -->
						<p class="text-sm text-muted">Selezioniamo solo materie prime certificate e a basso impatto ambientale.</p>
						<!-- /wp:paragraph -->
					</div>
					<!-- /wp:group -->
				</div>
				<!-- /wp:group -->

				<!-- Item 3 -->
				<!-- wp:group {"className":"itl-item","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"left"},"style":{"spacing":{"blockGap":"1.25rem","padding":{"top":"1.25rem","bottom":"1.25rem"}}}} -->
				<div class="wp-block-group itl-item">
					<!-- wp:image {"width":"48px","height":"48px","className":"shrink-0 object-contain"} -->
					<figure class="wp-block-image"><img src="" alt="Icona" class="itl-icon"/></figure>
					<!-- /wp:image -->
					<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","orientation":"vertical"},"style":{"spacing":{"blockGap":"0.25rem"}}} -->
					<div class="wp-block-group">
						<!-- wp:heading {"level":3,"className":"text-lg font-light text-ink"} -->
						<h3 class="text-lg font-light text-ink">Consegna garantita</h3>
						<!-- /wp:heading -->
						<!-- wp:paragraph {"className":"text-sm text-muted"} -->
						<p class="text-sm text-muted">Spedizione rapida e tracciata, con reso gratuito entro 30 giorni.</p>
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

</div>
<!-- /wp:group -->
