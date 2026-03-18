<?php
/**
 * Title: Testo con Colonna Laterale
 * Slug: theme/text-with-aside
 * Categories: theme-sections
 * Keywords: testo, aside, colonna laterale, sidebar, contenuto, articolo
 * Description: Layout editoriale con colonna principale di testo a sinistra e una colonna laterale destra con info aggiuntive, link o callout box.
 * Viewport Width: 1440
 */
?>
<!-- wp:group {"className":"section-luxury bg-surface","layout":{"type":"constrained","contentSize":"90rem"}} -->
<div class="wp-block-group section-luxury bg-surface">

	<!-- wp:columns {"isStackedOnMobile":true,"style":{"spacing":{"blockGap":{"left":"4rem","top":"3rem"}}}} -->
	<div class="wp-block-columns">

		{{-- Main content column (2/3) --}}
		<!-- wp:column {"width":"65%"} -->
		<div class="wp-block-column" style="width:65%">

			<!-- wp:paragraph {"className":"section-label text-muted"} -->
			<p class="section-label text-muted">IL NOSTRO APPROCCIO</p>
			<!-- /wp:paragraph -->

			<!-- wp:heading {"level":2,"className":"section-title text-ink"} -->
			<h2 class="section-title text-ink">Un'artigianalità tramandata<br>di generazione in generazione</h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"className":"font-sans text-base text-muted leading-relaxed mt-6"} -->
			<p class="font-sans text-base text-muted leading-relaxed mt-6">Ogni pezzo nasce dall'incontro tra tradizione e contemporaneità. I nostri artigiani utilizzano tecniche tramandate per secoli, affinando ogni dettaglio con cura e dedizione. Il risultato è un oggetto che racconta una storia, che porta con sé il peso della memoria e la leggerezza del bello.</p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"className":"font-sans text-base text-muted leading-relaxed"} -->
			<p class="font-sans text-base text-muted leading-relaxed">Utilizziamo solo materie prime di prima scelta, selezionate con attenzione e rispetto per l'ambiente. Ogni scelta è consapevole, ogni gesto è intenzionale. Dalla filatura alla rifinitura, dall'imbottitura alla cucitura: ogni fase del processo produttivo è curata nei minimi dettagli.</p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"className":"font-sans text-base text-muted leading-relaxed"} -->
			<p class="font-sans text-base text-muted leading-relaxed">Crediamo che la qualità sia un atto di rispetto verso chi riceve l'oggetto, verso chi lo crea e verso il pianeta che ci ospita. Per questo non accettiamo compromessi e non smettere mai di cercare il meglio.</p>
			<!-- /wp:paragraph -->

		</div>
		<!-- /wp:column -->

		{{-- Aside column (1/3) --}}
		<!-- wp:column {"width":"35%"} -->
		<div class="wp-block-column" style="width:35%">

			<!-- wp:group {"className":"bg-cream p-8 sticky top-8","layout":{"type":"flex","flexWrap":"nowrap","orientation":"vertical"},"style":{"spacing":{"blockGap":"1.5rem"}}} -->
			<div class="wp-block-group bg-cream p-8 sticky top-8">

				<!-- wp:heading {"level":3,"className":"font-serif text-xl font-light text-ink"} -->
				<h3 class="font-serif text-xl font-light text-ink">In breve</h3>
				<!-- /wp:heading -->

				<!-- wp:list {"className":"font-sans text-sm text-muted space-y-2","style":{"typography":{"lineHeight":"1.8"}}} -->
				<ul class="font-sans text-sm text-muted space-y-2" style="line-height:1.8">
					<!-- wp:list-item -->
					<li>Produzione artigianale italiana</li>
					<!-- /wp:list-item -->
					<!-- wp:list-item -->
					<li>Materie prime certificate</li>
					<!-- /wp:list-item -->
					<!-- wp:list-item -->
					<li>Spedizione in 48–72 ore</li>
					<!-- /wp:list-item -->
					<!-- wp:list-item -->
					<li>Garanzia soddisfatti o rimborsati</li>
					<!-- /wp:list-item -->
				</ul>
				<!-- /wp:list -->

				<!-- wp:separator {"className":"border-border"} -->
				<hr class="wp-block-separator border-border"/>
				<!-- /wp:separator -->

				<!-- wp:paragraph {"className":"font-sans text-xs text-muted leading-relaxed"} -->
				<p class="font-sans text-xs text-muted leading-relaxed">Hai domande? Il nostro team è disponibile dal lunedì al venerdì, dalle 9 alle 18.</p>
				<!-- /wp:paragraph -->

				<!-- wp:buttons -->
				<div class="wp-block-buttons">
					<!-- wp:button {"className":"btn-ghost w-full text-center"} -->
					<div class="wp-block-button btn-ghost w-full"><a class="wp-block-button__link" href="#">Contattaci →</a></div>
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
