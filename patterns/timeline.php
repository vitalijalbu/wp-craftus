<?php
/**
 * Title: Timeline – Processo Verticale
 * Slug: theme/timeline
 * Categories: theme-sections
 * Keywords: timeline, processo, step, fasi, come lavoriamo, storia, percorso
 * Description: Sezione timeline verticale con 4 step numerati. Sfondo chiaro con linea verticale e icone numerate.
 * Viewport Width: 1440
 */
?>
<!-- wp:group {"backgroundColor":"surface","layout":{"type":"constrained"},"style":{"spacing":{"padding":{"top":"var:preset|spacing|9","bottom":"var:preset|spacing|9"}}}} -->
<div class="wp-block-group has-surface-background-color has-background">

	<!-- Intestazione -->
	<!-- wp:group {"layout":{"type":"constrained","contentSize":"48rem"},"style":{"spacing":{"blockGap":"var:preset|spacing|4"}}} -->
	<div class="wp-block-group">
		<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"xs","className":"section-label"} -->
		<p class="has-text-align-center has-muted-color has-text-color has-xs-font-size section-label">IL NOSTRO PROCESSO</p>
		<!-- /wp:paragraph -->
		<!-- wp:heading {"level":2,"textAlign":"center","fontFamily":"serif","fontSize":"4xl"} -->
		<h2 class="wp-block-heading has-text-align-center has-serif-font-family has-4-xl-font-size">Come lavoriamo insieme</h2>
		<!-- /wp:heading -->
		<!-- wp:paragraph {"align":"center","textColor":"muted","fontSize":"base"} -->
		<p class="has-text-align-center has-muted-color has-text-color has-base-font-size">Un processo chiaro e trasparente, pensato per garantire risultati eccellenti in ogni fase del progetto.</p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- wp:spacer {"height":"3rem"} -->
	<div style="height:3rem" aria-hidden="true" class="wp-block-spacer"></div>
	<!-- /wp:spacer -->

	<!-- Timeline steps -->
	<!-- wp:group {"layout":{"type":"constrained","contentSize":"42rem"}} -->
	<div class="wp-block-group">

		<!-- Step 1 -->
		<!-- wp:group {"style":{"spacing":{"blockGap":"1.5rem","padding":{"bottom":"3rem"}}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
		<div class="wp-block-group" style="padding-bottom:3rem;gap:1.5rem">
			<!-- wp:group {"style":{"spacing":{"padding":{"all":"0"}},"dimensions":{"minWidth":"3rem"}},"layout":{"type":"default"}} -->
			<div class="wp-block-group" style="min-width:3rem">
				<!-- wp:heading {"level":3,"textAlign":"center","textColor":"accent","fontSize":"2xl","fontFamily":"serif","style":{"border":{"width":"2px","style":"solid","color":"#0074C7"},"spacing":{"padding":{"all":"0.75rem"}}},"className":"process-step__num"} -->
				<h3 class="wp-block-heading has-text-align-center has-accent-color has-text-color has-serif-font-family has-2-xl-font-size process-step__num" style="border:2px solid var(--wp--preset--color--accent, #0074C7);padding:0.75rem">01</h3>
				<!-- /wp:heading -->
			</div>
			<!-- /wp:group -->
			<!-- wp:group {"layout":{"type":"default"},"style":{"spacing":{"padding":{"top":"0.5rem"}}}} -->
			<div class="wp-block-group" style="padding-top:0.5rem">
				<!-- wp:heading {"level":4,"fontSize":"xl"} -->
				<h4 class="wp-block-heading has-xl-font-size">Scoperta e analisi</h4>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"textColor":"muted","fontSize":"base"} -->
				<p class="has-muted-color has-text-color has-base-font-size">Ascoltiamo il tuo progetto, analizziamo il mercato e definiamo insieme obiettivi chiari. Questa fase è fondamentale per costruire la strategia giusta.</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->

		<!-- Step 2 -->
		<!-- wp:group {"style":{"spacing":{"blockGap":"1.5rem","padding":{"bottom":"3rem"}}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
		<div class="wp-block-group" style="padding-bottom:3rem;gap:1.5rem">
			<!-- wp:group {"style":{"spacing":{"padding":{"all":"0"}},"dimensions":{"minWidth":"3rem"}},"layout":{"type":"default"}} -->
			<div class="wp-block-group" style="min-width:3rem">
				<!-- wp:heading {"level":3,"textAlign":"center","textColor":"accent","fontSize":"2xl","fontFamily":"serif","style":{"border":{"width":"2px","style":"solid","color":"#0074C7"},"spacing":{"padding":{"all":"0.75rem"}}},"className":"process-step__num"} -->
				<h3 class="wp-block-heading has-text-align-center has-accent-color has-text-color has-serif-font-family has-2-xl-font-size process-step__num" style="border:2px solid var(--wp--preset--color--accent, #0074C7);padding:0.75rem">02</h3>
				<!-- /wp:heading -->
			</div>
			<!-- /wp:group -->
			<!-- wp:group {"layout":{"type":"default"},"style":{"spacing":{"padding":{"top":"0.5rem"}}}} -->
			<div class="wp-block-group" style="padding-top:0.5rem">
				<!-- wp:heading {"level":4,"fontSize":"xl"} -->
				<h4 class="wp-block-heading has-xl-font-size">Progettazione</h4>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"textColor":"muted","fontSize":"base"} -->
				<p class="has-muted-color has-text-color has-base-font-size">Definiamo l'architettura del progetto, creiamo wireframe e prototipi interattivi. Il cliente approva ogni elemento prima che inizi lo sviluppo.</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->

		<!-- Step 3 -->
		<!-- wp:group {"style":{"spacing":{"blockGap":"1.5rem","padding":{"bottom":"3rem"}}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
		<div class="wp-block-group" style="padding-bottom:3rem;gap:1.5rem">
			<!-- wp:group {"style":{"spacing":{"padding":{"all":"0"}},"dimensions":{"minWidth":"3rem"}},"layout":{"type":"default"}} -->
			<div class="wp-block-group" style="min-width:3rem">
				<!-- wp:heading {"level":3,"textAlign":"center","textColor":"accent","fontSize":"2xl","fontFamily":"serif","style":{"border":{"width":"2px","style":"solid","color":"#0074C7"},"spacing":{"padding":{"all":"0.75rem"}}},"className":"process-step__num"} -->
				<h3 class="wp-block-heading has-text-align-center has-accent-color has-text-color has-serif-font-family has-2-xl-font-size process-step__num" style="border:2px solid var(--wp--preset--color--accent, #0074C7);padding:0.75rem">03</h3>
				<!-- /wp:heading -->
			</div>
			<!-- /wp:group -->
			<!-- wp:group {"layout":{"type":"default"},"style":{"spacing":{"padding":{"top":"0.5rem"}}}} -->
			<div class="wp-block-group" style="padding-top:0.5rem">
				<!-- wp:heading {"level":4,"fontSize":"xl"} -->
				<h4 class="wp-block-heading has-xl-font-size">Sviluppo e test</h4>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"textColor":"muted","fontSize":"base"} -->
				<p class="has-muted-color has-text-color has-base-font-size">Sviluppiamo con standard qualitativi elevati, test su tutti i dispositivi e browser. Ogni funzionalità viene verificata prima della consegna.</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->

		<!-- Step 4 -->
		<!-- wp:group {"style":{"spacing":{"blockGap":"1.5rem"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
		<div class="wp-block-group" style="gap:1.5rem">
			<!-- wp:group {"style":{"spacing":{"padding":{"all":"0"}},"dimensions":{"minWidth":"3rem"}},"layout":{"type":"default"}} -->
			<div class="wp-block-group" style="min-width:3rem">
				<!-- wp:heading {"level":3,"textAlign":"center","textColor":"accent","fontSize":"2xl","fontFamily":"serif","style":{"border":{"width":"2px","style":"solid","color":"#0074C7"},"spacing":{"padding":{"all":"0.75rem"}}},"className":"process-step__num"} -->
				<h3 class="wp-block-heading has-text-align-center has-accent-color has-text-color has-serif-font-family has-2-xl-font-size process-step__num" style="border:2px solid var(--wp--preset--color--accent, #0074C7);padding:0.75rem">04</h3>
				<!-- /wp:heading -->
			</div>
			<!-- /wp:group -->
			<!-- wp:group {"layout":{"type":"default"},"style":{"spacing":{"padding":{"top":"0.5rem"}}}} -->
			<div class="wp-block-group" style="padding-top:0.5rem">
				<!-- wp:heading {"level":4,"fontSize":"xl"} -->
				<h4 class="wp-block-heading has-xl-font-size">Lancio e supporto</h4>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"textColor":"muted","fontSize":"base"} -->
				<p class="has-muted-color has-text-color has-base-font-size">Pubblichiamo il progetto con un processo di lancio strutturato. Ti accompagniamo nei primi giorni e forniamo formazione per gestire tutto in autonomia.</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->

	</div>
	<!-- /wp:group -->

</div>
<!-- /wp:group -->
