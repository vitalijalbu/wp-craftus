<?php
/**
 * Title: Logo Carousel – Brand / Partner in movimento
 * Slug: theme/logo-carousel
 * Categories: theme-sections
 * Keywords: loghi, brand, partner, clienti, carousel, slider, trust
 * Description: Carosello infinito dei loghi brand/partner. Usa Swiper FreeMode con autoplay continuo. Sostituisci le immagini con i loghi reali.
 * Viewport Width: 1440
 */
?>
<!-- wp:group {"backgroundColor":"surface-alt","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group has-surface-alt-background-color has-background">

	<!-- Titolo sezione (opzionale) -->
	<!-- wp:paragraph {"align":"center","textColor":"muted","className":"theme-section-label","style":{"typography":{"letterSpacing":"0.2em","textTransform":"uppercase","fontSize":"0.6875rem"}}} -->
	<p class="has-text-align-center has-muted-color has-text-color theme-section-label" style="letter-spacing:0.2em;text-transform:uppercase;font-size:0.6875rem"><?php esc_html_e('Aziende che ci hanno scelto', 'sage'); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:spacer {"height":"2rem"} -->
	<div style="height:2rem" aria-hidden="true" class="wp-block-spacer"></div>
	<!-- /wp:spacer -->

	<!-- Carosello loghi: usa classe .js-logos-swiper — inizializzato da carousel.js -->
	<!-- wp:html -->
	<div
	  class="swiper js-logos-swiper overflow-hidden"
	  aria-label="<?php esc_attr_e('Loghi brand partner', 'sage'); ?>"
	  role="region"
	>
	  <div class="swiper-wrapper items-center">

	    <!-- Slide 1 — sostituisci src con il logo reale -->
	    <div class="swiper-slide flex items-center justify-center opacity-50 grayscale hover:opacity-100 hover:grayscale-0 transition-all duration-300">
	      <img
	        src="<?php echo esc_url(get_template_directory_uri()); ?>/public/placeholder.png"
	        alt="Brand 1"
	        width="120"
	        height="40"
	        loading="lazy"
	        class="max-h-10 w-auto object-contain"
	      >
	    </div>

	    <!-- Slide 2 -->
	    <div class="swiper-slide flex items-center justify-center opacity-50 grayscale hover:opacity-100 hover:grayscale-0 transition-all duration-300">
	      <img
	        src="<?php echo esc_url(get_template_directory_uri()); ?>/public/placeholder.png"
	        alt="Brand 2"
	        width="120"
	        height="40"
	        loading="lazy"
	        class="max-h-10 w-auto object-contain"
	      >
	    </div>

	    <!-- Slide 3 -->
	    <div class="swiper-slide flex items-center justify-center opacity-50 grayscale hover:opacity-100 hover:grayscale-0 transition-all duration-300">
	      <img
	        src="<?php echo esc_url(get_template_directory_uri()); ?>/public/placeholder.png"
	        alt="Brand 3"
	        width="120"
	        height="40"
	        loading="lazy"
	        class="max-h-10 w-auto object-contain"
	      >
	    </div>

	    <!-- Slide 4 -->
	    <div class="swiper-slide flex items-center justify-center opacity-50 grayscale hover:opacity-100 hover:grayscale-0 transition-all duration-300">
	      <img
	        src="<?php echo esc_url(get_template_directory_uri()); ?>/public/placeholder.png"
	        alt="Brand 4"
	        width="120"
	        height="40"
	        loading="lazy"
	        class="max-h-10 w-auto object-contain"
	      >
	    </div>

	    <!-- Slide 5 -->
	    <div class="swiper-slide flex items-center justify-center opacity-50 grayscale hover:opacity-100 hover:grayscale-0 transition-all duration-300">
	      <img
	        src="<?php echo esc_url(get_template_directory_uri()); ?>/public/placeholder.png"
	        alt="Brand 5"
	        width="120"
	        height="40"
	        loading="lazy"
	        class="max-h-10 w-auto object-contain"
	      >
	    </div>

	    <!-- Slide 6 -->
	    <div class="swiper-slide flex items-center justify-center opacity-50 grayscale hover:opacity-100 hover:grayscale-0 transition-all duration-300">
	      <img
	        src="<?php echo esc_url(get_template_directory_uri()); ?>/public/placeholder.png"
	        alt="Brand 6"
	        width="120"
	        height="40"
	        loading="lazy"
	        class="max-h-10 w-auto object-contain"
	      >
	    </div>

	  </div>
	</div>
	<!-- /wp:html -->

</div>
<!-- /wp:group -->
