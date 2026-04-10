<?php
/**
 * Title: CTA Banner
 * Slug: theme/cta-banner
 * Categories: theme-sections
 * Keywords: cta, call to action, blu, prenota, contatti, consulenza
 * Description: Banner call-to-action con sfondo blu primario (#3E80C4), pattern geometrico, titolo, sottotitolo e pulsanti di contatto (booking, telefono, WhatsApp, email).
 * Viewport Width: 1440
 */
?>
<!-- wp:group {"backgroundColor":"primary","className":"theme-cta-banner","style":{"spacing":{"padding":{"top":"var:preset|spacing|9","bottom":"var:preset|spacing|9"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group theme-cta-banner has-primary-background-color has-background">

	<!-- wp:group {"layout":{"type":"constrained","contentSize":"48rem"}} -->
	<div class="wp-block-group">

		<!-- wp:heading {"level":2,"textAlign":"center","textColor":"white","fontSize":"2xl","className":"theme-section-title"} -->
		<h2 class="wp-block-heading has-text-align-center has-white-color has-text-color has-2-xl-font-size theme-section-title">Pronto a far crescere<br>la tua comunicazione?</h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"align":"center","textColor":"white","fontSize":"lg"} -->
		<p class="has-text-align-center has-white-color has-text-color has-lg-font-size">Analizziamo insieme la tua situazione e troviamo le soluzioni giuste per te.</p>
		<!-- /wp:paragraph -->

		<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center","flexWrap":"wrap"}} -->
		<div class="wp-block-buttons">
			<!-- wp:button -->
			<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo function_exists('App\\theme_cta_url') ? \App\theme_cta_url() : esc_url(home_url(apply_filters('theme_cta_fallback_path', '/contatti'))); ?>">📅 Prenota una consulenza</a></div>
			<!-- /wp:button -->
			<!-- wp:button {"className":"is-style-outline"} -->
			<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="tel:<?php echo esc_attr(get_theme_mod('contact_phone', '')); ?>"><?php echo esc_html(get_theme_mod('contact_phone', '')); ?></a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->

		<?php
		$wa_url   = function_exists('App\\theme_whatsapp_url') ? \App\theme_whatsapp_url() : '';
		$contact_email = get_theme_mod('contact_email', '');
		if ($wa_url || $contact_email) :
		?>
		<!-- Link secondari: WhatsApp + email -->
		<!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"center"}} -->
		<div class="wp-block-group">
			<?php if ($wa_url) : ?>
			<!-- wp:paragraph {"textColor":"white","fontSize":"lg"} -->
			<p class="has-white-color has-text-color has-lg-font-size">💬 <a href="<?php echo esc_url($wa_url); ?>" target="_blank" rel="noopener"><?php esc_html_e('WhatsApp', 'sage'); ?></a></p>
			<!-- /wp:paragraph -->
			<?php endif; ?>
			<?php if ($contact_email) : ?>
			<!-- wp:paragraph {"textColor":"white","fontSize":"lg"} -->
			<p class="has-white-color has-text-color has-lg-font-size">✉️ <a href="mailto:<?php echo esc_attr($contact_email); ?>"><?php echo esc_html($contact_email); ?></a></p>
			<!-- /wp:paragraph -->
			<?php endif; ?>
		</div>
		<!-- /wp:group -->
		<?php endif; ?>

	</div>
	<!-- /wp:group -->

</div>
<!-- /wp:group -->
