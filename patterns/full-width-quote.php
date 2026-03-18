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
<!-- wp:group {"className":"section-luxury bg-ink","layout":{"type":"constrained","contentSize":"72rem"}} -->
<div class="wp-block-group section-luxury bg-ink">

	<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","orientation":"vertical","justifyContent":"center"},"style":{"spacing":{"blockGap":"2rem"}},"textAlign":"center"} -->
	<div class="wp-block-group" style="text-align:center">

		<!-- wp:paragraph {"className":"font-serif text-gold text-[3rem] leading-none select-none","style":{"typography":{"fontSize":"3rem"}}} -->
		<p class="font-serif text-gold leading-none select-none" style="font-size:3rem">&ldquo;</p>
		<!-- /wp:paragraph -->

		<!-- wp:heading {"level":2,"className":"font-serif text-white font-light leading-snug","style":{"typography":{"fontSize":"clamp(1.75rem, 4vw, 3rem)","fontWeight":"300","lineHeight":"1.3"}}} -->
		<h2 class="font-serif text-white font-light leading-snug" style="font-size:clamp(1.75rem, 4vw, 3rem);font-weight:300;line-height:1.3">La qualità non è mai un caso; è sempre il risultato<br>di uno sforzo intelligente.</h2>
		<!-- /wp:heading -->

		<!-- wp:group {"layout":{"type":"flex","justifyContent":"center","flexWrap":"nowrap"},"style":{"spacing":{"blockGap":"1rem"}}} -->
		<div class="wp-block-group">
			<!-- wp:separator {"className":"border-white/20","style":{"layout":{"selfStretch":"fixed","flexSize":"2rem"}}} -->
			<hr class="wp-block-separator border-white/20" style="width:2rem"/>
			<!-- /wp:separator -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","orientation":"vertical","justifyContent":"center"},"style":{"spacing":{"blockGap":"0.25rem"}}} -->
		<div class="wp-block-group">
			<!-- wp:paragraph {"className":"font-sans text-sm font-semibold tracking-[0.15em] uppercase text-white"} -->
			<p class="font-sans text-sm font-semibold tracking-[0.15em] uppercase text-white">Nome Autore</p>
			<!-- /wp:paragraph -->
			<!-- wp:paragraph {"className":"font-sans text-xs text-white/40"} -->
			<p class="font-sans text-xs text-white/40">Fondatore &amp; CEO</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

	</div>
	<!-- /wp:group -->

</div>
<!-- /wp:group -->
