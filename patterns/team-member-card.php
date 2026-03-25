<?php
/**
 * Title: Scheda Membro del Team
 * Slug: theme/team-member-card
 * Categories: theme-sections
 * Keywords: team, membro, persona, ruolo, linkedin, staff
 * Description: Griglia di tre schede per presentare i membri del team con foto, nome, ruolo e link LinkedIn.
 * Viewport Width: 1440
 */
?>
<!-- wp:group {"className":"section-luxury bg-surface","layout":{"type":"constrained","contentSize":"90rem"}} -->
<div class="wp-block-group section-luxury bg-surface">

	<!-- wp:group {"style":{"spacing":{"blockGap":"0.5rem"}},"layout":{"type":"constrained","contentSize":"48rem","justifyContent":"center"}} -->
	<div class="wp-block-group" style="text-align:center">
		<!-- wp:paragraph {"className":"section-label text-muted"} -->
		<p class="section-label text-muted">IL NOSTRO TEAM</p>
		<!-- /wp:paragraph -->
		<!-- wp:heading {"level":2,"className":"section-title text-ink","style":{"typography":{"fontFamily":"var(--font-serif)","fontWeight":"300"}}} -->
		<h2 class="section-title text-ink">Le persone dietro al progetto</h2>
		<!-- /wp:heading -->
	</div>
	<!-- /wp:group -->

	<!-- wp:columns {"columns":3,"isStackedOnMobile":true,"style":{"spacing":{"blockGap":{"left":"2rem","top":"2rem"}}}} -->
	<div class="wp-block-columns">

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"className":"team-card group","layout":{"type":"flex","flexWrap":"nowrap","orientation":"vertical"}} -->
			<div class="wp-block-group team-card group">
				<!-- wp:image {"aspectRatio":"3/4","scale":"cover","className":"team-card__img overflow-hidden"} -->
				<figure class="wp-block-image team-card__img overflow-hidden">
					<img src="" alt="Foto membro team" style="aspect-ratio:3/4;object-fit:cover"/>
				</figure>
				<!-- /wp:image -->
				<!-- wp:heading {"level":3,"className":"font-serif text-lg font-light text-ink mt-2"} -->
				<h3 class="font-serif text-lg font-light text-ink mt-2">Nome Cognome</h3>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"className":"text-xs tracking-widest uppercase text-muted mt-1"} -->
				<p class="text-xs tracking-widest uppercase text-muted mt-1">Ruolo / Titolo</p>
				<!-- /wp:paragraph -->
				<!-- wp:paragraph {"className":"text-xs text-primary mt-2"} -->
				<p class="text-xs text-primary mt-2"><a href="#">LinkedIn</a></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"className":"team-card group","layout":{"type":"flex","flexWrap":"nowrap","orientation":"vertical"}} -->
			<div class="wp-block-group team-card group">
				<!-- wp:image {"aspectRatio":"3/4","scale":"cover","className":"team-card__img overflow-hidden"} -->
				<figure class="wp-block-image team-card__img overflow-hidden">
					<img src="" alt="Foto membro team" style="aspect-ratio:3/4;object-fit:cover"/>
				</figure>
				<!-- /wp:image -->
				<!-- wp:heading {"level":3,"className":"font-serif text-lg font-light text-ink mt-2"} -->
				<h3 class="font-serif text-lg font-light text-ink mt-2">Nome Cognome</h3>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"className":"text-xs tracking-widest uppercase text-muted mt-1"} -->
				<p class="text-xs tracking-widest uppercase text-muted mt-1">Ruolo / Titolo</p>
				<!-- /wp:paragraph -->
				<!-- wp:paragraph {"className":"text-xs text-primary mt-2"} -->
				<p class="text-xs text-primary mt-2"><a href="#">LinkedIn</a></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"className":"team-card group","layout":{"type":"flex","flexWrap":"nowrap","orientation":"vertical"}} -->
			<div class="wp-block-group team-card group">
				<!-- wp:image {"aspectRatio":"3/4","scale":"cover","className":"team-card__img overflow-hidden"} -->
				<figure class="wp-block-image team-card__img overflow-hidden">
					<img src="" alt="Foto membro team" style="aspect-ratio:3/4;object-fit:cover"/>
				</figure>
				<!-- /wp:image -->
				<!-- wp:heading {"level":3,"className":"font-serif text-lg font-light text-ink mt-2"} -->
				<h3 class="font-serif text-lg font-light text-ink mt-2">Nome Cognome</h3>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"className":"text-xs tracking-widest uppercase text-muted mt-1"} -->
				<p class="text-xs tracking-widest uppercase text-muted mt-1">Ruolo / Titolo</p>
				<!-- /wp:paragraph -->
				<!-- wp:paragraph {"className":"text-xs text-primary mt-2"} -->
				<p class="text-xs text-primary mt-2"><a href="#">LinkedIn</a></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->

	</div>
	<!-- /wp:columns -->

</div>
<!-- /wp:group -->
