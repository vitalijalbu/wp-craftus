<?php
/**
 * Title: CTA Newsletter – Dark
 * Slug: theme/newsletter-cta
 * Categories: theme-sections
 * Keywords: newsletter, email, iscrizione, cta
 * Viewport Width: 1440
 */
?>
<!-- wp:group {"backgroundColor":"ink","layout":{"type":"constrained"},"style":{"spacing":{"padding":{"top":"var:preset|spacing|9","bottom":"var:preset|spacing|9"}}}} -->
<div class="wp-block-group has-ink-background-color has-background">
<!-- wp:group {"layout":{"type":"constrained","contentSize":"40rem"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"align":"center","textColor":"primary","fontSize":"sm","className":"theme-section-label"} -->
<p class="has-text-align-center has-primary-color has-text-color has-sm-font-size theme-section-label">Newsletter</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":2,"textAlign":"center","textColor":"white","fontSize":"4xl","fontFamily":"serif","className":"theme-section-title"} -->
<h2 class="wp-block-heading has-text-align-center has-white-color has-text-color has-4-xl-font-size has-serif-font-family theme-section-title">Offerte esclusive per<br>chi ama i propri animali</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","fontSize":"base"} -->
<p class="has-text-align-center has-base-font-size text-white/50">Iscriviti e ricevi il 10% di sconto sul primo ordine, guide e consigli veterinari.</p>
<!-- /wp:paragraph -->
<!-- wp:html -->
<form
  class="flex flex-col sm:flex-row gap-0 max-w-md mx-auto mt-6"
  x-data="{ email: '', state: 'idle', message: '' }"
  @submit.prevent="
    if (!email) return;
    state = 'loading';
    fetch('/wp-json/theme/v1/newsletter', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email }),
    })
    .then(r => r.json())
    .then(d => { state = d.success ? 'done' : 'error'; message = d.message || ''; })
    .catch(() => { state = 'error'; message = 'Errore. Riprova.'; });
  "
  novalidate
>
  <template x-if="state !== 'done'">
    <div class="flex w-full">
      <label for="nl-cta-email" class="sr-only">Email</label>
      <input
        id="nl-cta-email"
        type="email"
        x-model="email"
        placeholder="La tua email"
        :disabled="state === 'loading'"
        class="flex-1 bg-white/5 border border-white/15 border-r-0 px-4 py-3.5 text-sm text-white placeholder-white/30 focus:outline-none focus:border-primary/50 transition-colors disabled:opacity-50"
        required
      >
      <button
        type="submit"
        :disabled="state === 'loading'"
        class="bg-primary text-white text-xs font-semibold tracking-widest uppercase px-6 py-3.5 hover:bg-primary-dark transition-colors whitespace-nowrap disabled:opacity-60"
      >
        <span x-show="state !== 'loading'">Iscriviti</span>
        <span x-show="state === 'loading'" aria-live="polite">…</span>
      </button>
    </div>
  </template>
  <p x-show="state === 'done'" class="text-sm text-primary py-3" aria-live="polite" x-text="message"></p>
  <p x-show="state === 'error'" class="text-sm text-error py-3" aria-live="assertive" x-text="message"></p>
</form>
<!-- /wp:html -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
