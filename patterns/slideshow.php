<?php
/**
 * Title: Slideshow – Hero con Swiper
 * Slug: theme/slideshow
 * Categories: theme-sections
 * Keywords: slideshow, hero, swiper, slider, banner, cta
 * Description: Hero slideshow multi-slide con Swiper, overlay scuro, titolo serif e CTA in stile tema.
 * Block Types: core/group
 * Viewport Width: 1440
 */
?>
<!-- wp:group {"align":"full","className":"theme-slideshow-wrapper","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull theme-slideshow-wrapper">
  <!-- wp:html -->
  <style>.header-spacer{display:none}</style>
  <section
    class="relative w-full overflow-hidden min-h-svh"
    aria-roledescription="carosello"
    aria-label="Slideshow hero"
  >
    <div class="swiper js-hero-swiper w-full h-full absolute inset-0">
      <div class="swiper-wrapper" aria-live="polite">

        <div class="swiper-slide relative min-h-svh" role="group" aria-roledescription="slide" aria-label="Slide 1 di 3">
          <div class="absolute inset-0 z-0" aria-hidden="true">
            <img class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1548199973-03cce0bbc87b?q=80&w=1920&auto=format&fit=crop" alt="Cane felice nel prato" loading="eager" fetchpriority="high" decoding="async" />
          </div>
          <div class="absolute inset-0 z-1 bg-linear-to-t from-ink/70 via-ink/25 to-ink/5" aria-hidden="true"></div>
          <div class="relative z-10 h-full flex items-end pb-24 lg:pb-32">
            <div class="container w-full">
              <div class="max-w-2xl">
                <p class="font-semibold tracking-[0.25em] uppercase text-primary mb-5">Collezione Premium</p>
                <h2 class="hero-title mb-5">Cura quotidiana<br>per il tuo cane</h2>
                <p class="hero-subtitle mb-8">Prodotti selezionati con ingredienti di qualita e spedizione rapida in 24/48h.</p>
                <div class="flex flex-wrap gap-4">
                  <a href="<?php echo esc_url(home_url('/negozio/')); ?>" class="btn-light">Scopri i prodotti</a>
                  <a href="<?php echo esc_url(home_url('/contatti/')); ?>" class="btn-outline-white">Contattaci</a>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="swiper-slide relative min-h-svh" role="group" aria-roledescription="slide" aria-label="Slide 2 di 3">
          <div class="absolute inset-0 z-0" aria-hidden="true">
            <img class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1548767797-d8c844163c4c?q=80&w=1920&auto=format&fit=crop" alt="Gatto su coperta" loading="lazy" decoding="async" />
          </div>
          <div class="absolute inset-0 z-1 bg-linear-to-t from-ink/70 via-ink/25 to-ink/5" aria-hidden="true"></div>
          <div class="relative z-10 h-full flex items-end pb-24 lg:pb-32">
            <div class="container w-full">
              <div class="max-w-2xl">
                <p class="font-semibold tracking-[0.25em] uppercase text-primary mb-5">Benessere Felino</p>
                <h2 class="hero-title mb-5">Comfort, gioco<br>e nutrizione bilanciata</h2>
                <p class="hero-subtitle mb-8">Una selezione pensata per la salute del tuo gatto, dalla pappa agli accessori.</p>
                <div class="flex flex-wrap gap-4">
                  <a href="<?php echo esc_url(home_url('/categoria-prodotto/gatti/')); ?>" class="btn-light">Esplora gatti</a>
                  <a href="<?php echo esc_url(home_url('/wishlist/')); ?>" class="btn-outline-white">Vai alla wishlist</a>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="swiper-slide relative min-h-svh" role="group" aria-roledescription="slide" aria-label="Slide 3 di 3">
          <div class="absolute inset-0 z-0" aria-hidden="true">
            <img class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1450778869180-41d0601e046e?q=80&w=1920&auto=format&fit=crop" alt="Animali domestici insieme" loading="lazy" decoding="async" />
          </div>
          <div class="absolute inset-0 z-1 bg-linear-to-t from-ink/70 via-ink/25 to-ink/5" aria-hidden="true"></div>
          <div class="relative z-10 h-full flex items-end pb-24 lg:pb-32">
            <div class="container w-full">
              <div class="max-w-2xl">
                <p class="font-semibold tracking-[0.25em] uppercase text-primary mb-5">Offerte del mese</p>
                <h2 class="hero-title mb-5">Qualita professionale,<br>prezzi accessibili</h2>
                <p class="hero-subtitle mb-8">Scopri promozioni e bundle pensati per semplificare la tua routine quotidiana.</p>
                <div class="flex flex-wrap gap-4">
                  <a href="<?php echo esc_url(home_url('/negozio/')); ?>" class="btn-light">Vedi le offerte</a>
                  <a href="<?php echo esc_url(home_url('/chi-siamo/')); ?>" class="btn-outline-white">Chi siamo</a>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

      <div class="swiper-pagination absolute bottom-8 left-1/2 -translate-x-1/2 z-20 flex gap-2" role="group" aria-label="Scegli slide"></div>

      <button class="swiper-button-prev swiper-dark absolute left-6 lg:left-10 top-1/2 -translate-y-1/2 z-20" aria-label="Slide precedente"></button>
      <button class="swiper-button-next swiper-dark absolute right-6 lg:right-10 top-1/2 -translate-y-1/2 z-20" aria-label="Slide successiva"></button>
    </div>

    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-30 scroll-indicator" aria-hidden="true">
      <div class="scroll-indicator-line"></div>
      <span class="mt-2">scroll</span>
    </div>
  </section>
  <!-- /wp:html -->
</div>
<!-- /wp:group -->
