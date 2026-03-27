<?php
/**
 * Title: Page Hero – Intestazione Pagina
 * Slug: theme/page-hero
 * Categories: theme-sections
 * Keywords: page hero, intestazione, pagina, titolo, breadcrumb
 * Description: Intestazione pagina con sfondo scuro, titolo centrato e sottotitolo opzionale. Altezza 40vh. Ideale per pagine interne (Servizi, Chi Siamo, Portfolio, Blog).
 * Viewport Width: 1440
 */
?>
<!-- wp:cover {"dimRatio":60,"style":{"dimensions":{"minHeight":"42vh"}},"contentPosition":"center center","className":"theme-page-hero","overlayColor":"ink"} -->
<div class="wp-block-cover theme-page-hero" style="min-height:42vh">
	<span aria-hidden="true" class="wp-block-cover__background has-ink-background-color has-background-dim" style="opacity:0.6"></span>
	<img class="wp-block-cover__image-background" src="" alt="" style="object-position:50% 40%" data-object-fit="cover"/>
	<div class="wp-block-cover__inner-container">

		<!-- wp:group {"layout":{"type":"constrained","contentSize":"48rem"}} -->
		<div class="wp-block-group">

			<!-- wp:heading {"level":1,"textAlign":"center","textColor":"white","fontFamily":"serif","fontSize":"hero","className":"theme-section-title"} -->
			<h1 class="wp-block-heading has-text-align-center has-white-color has-text-color has-serif-font-family has-hero-font-size theme-section-title">Titolo della Pagina</h1>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"align":"center","textColor":"white","fontSize":"lg"} -->
			<p class="has-text-align-center has-white-color has-text-color has-lg-font-size">Breve descrizione della pagina o del servizio offerto.</p>
			<!-- /wp:paragraph -->

		</div>
		<!-- /wp:group -->

	</div>
</div>
<!-- /wp:cover -->
