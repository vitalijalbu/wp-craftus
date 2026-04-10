<?php
/**
 * Title: Testimonial – Citazione Singola
 * Slug: theme/testimonials
 * Categories: theme-sections
 * Keywords: testimonial, recensione, citazione, cliente, review, stelle
 * Description: Sezione testimonial con sfondo chiaro, stelle di valutazione, citazione in evidenza, avatar e nome cliente.
 * Viewport Width: 1440
 */
?>
<!-- wp:group {"backgroundColor":"cream","style":{"spacing":{"padding":{"top":"var:preset|spacing|9","bottom":"var:preset|spacing|9"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group has-cream-background-color has-background">

	<!-- wp:group {"layout":{"type":"constrained","contentSize":"44rem"}} -->
	<div class="wp-block-group">

		<!-- Stelle ⭐⭐⭐⭐⭐ -->
		<!-- wp:html -->
		<p class="has-text-align-center has-2-xl-font-size" role="img" aria-label="<?php echo esc_attr__('5 stelle su 5', 'sage'); ?>">⭐⭐⭐⭐⭐</p>
		<!-- /wp:html -->

		<!-- Citazione -->
		<!-- wp:quote {"className":"theme-testimonial__quote"} -->
		<blockquote class="wp-block-quote theme-testimonial__quote">
			<p>Craft ha trasformato completamente il nostro modo di comunicare online. In sei mesi abbiamo raddoppiato i lead qualificati e il brand è finalmente riconoscibile nel nostro settore. Professionalità e risultati concreti.</p>
		</blockquote>
		<!-- /wp:quote -->

		<!-- Avatar + nome -->
		<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center","verticalAlignment":"center"}} -->
		<div class="wp-block-group">
			<!-- wp:image {"width":64,"height":64,"style":{"border":{"radius":"50%"}}} -->
			<figure class="wp-block-image is-resized rounded-full"><img src="" alt="Avatar cliente" width="64" height="64"/></figure>
			<!-- /wp:image -->
			<!-- wp:group {"layout":{"type":"default"}} -->
			<div class="wp-block-group">
				<!-- wp:paragraph {"fontSize":"base"} -->
				<p class="has-base-font-size">Marco Rossi</p>
				<!-- /wp:paragraph -->
				<!-- wp:paragraph {"fontSize":"sm"} -->
				<p class="has-sm-font-size">CEO, Azienda Esempio Srl</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->

	</div>
	<!-- /wp:group -->

</div>
<!-- /wp:group -->
