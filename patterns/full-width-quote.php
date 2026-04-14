<?php
/**
 * Title: Citazione Full Width
 * Slug: theme/full-width-quote
 * Categories: theme-sections
 * Keywords: citazione, quote, testimonianza, pull quote, editorial
 * Description: Sezione editoriale full-width con grande citazione in stile serif, autore e ruolo. Varianti sfondo chiaro e scuro.
 * Viewport Width: 1440
 */
?>
<!-- wp:group {"className":"section bg-ink","layout":{"type":"constrained","contentSize":"72rem"}} -->
<div class="wp-block-group section bg-ink">

	<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","orientation":"vertical","justifyContent":"center"},"style":{"spacing":{"blockGap":"2rem"}},"textAlign":"center"} -->
	<div class="wp-block-group text-center">

		<!-- wp:paragraph {"className":"font-serif text-primary text-[3rem] leading-none select-none","style":{"typography":{"fontSize":"3rem"}}} -->
		<p class="font-serif text-primary leading-none select-none text-[3rem]">&ldquo;</p>
		<!-- /wp:paragraph -->

		<!-- wp:heading {"level":2,"className":"font-serif text-white font-light leading-snug","style":{"typography":{"fontSize":"clamp(1.75rem, 4vw, 3rem)","fontWeight":"300","lineHeight":"1.3"}}} -->
		<h2 class="font-serif text-white font-light leading-snug text-quote-display">La qualità non è mai un caso; è sempre il risultato<br>di uno sforzo intelligente.</h2>
		<!-- /wp:heading -->

		<!-- wp:group {"layout":{"type":"flex","justifyContent":"center","flexWrap":"nowrap"},"style":{"spacing":{"blockGap":"1rem"}}} -->
		<div class="wp-block-group">
			<!-- wp:separator {"className":"border-white/20","style":{"layout":{"selfStretch":"fixed","flexSize":"2rem"}}} -->
			<hr class="wp-block-separator border-white/20 w-8"/>
			<!-- /wp:separator -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","orientation":"vertical","justifyContent":"center"},"style":{"spacing":{"blockGap":"0.25rem"}}} -->
		<div class="wp-block-group">
			<!-- wp:paragraph {"className":"text-sm font-semibold tracking-[0.15em] uppercase text-white"} -->
			<p class="text-sm font-semibold tracking-[0.15em] uppercase text-white">Nome Autore</p>
			<!-- /wp:paragraph -->
			<!-- wp:paragraph {"className":"text-xs text-white/40"} -->
			<p class="text-xs text-white/40">Fondatore &amp; CEO</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

	</div>
	<!-- /wp:group -->

</div>
<!-- /wp:group -->
