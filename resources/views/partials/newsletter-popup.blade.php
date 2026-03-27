{{--
  Newsletter Popup — exit intent + timer fallback
  - Mostra 1 volta ogni 7 giorni (localStorage)
  - Exit intent: mouseleave dal top della pagina (desktop)
  - Timer fallback: 30 secondi
  - Rispetta il cookie consent (non mostra se non accettato)
  - Richiede: newsletter_active = true nel Customizer
--}}
@if(get_theme_mod('newsletter_active', false))
<div
  x-data="newsletterPopup()"
  x-init="init()"
  x-show="visible"
  x-cloak
  x-trap.inert.noscroll="visible"
  @keydown.escape.window="dismiss()"
  role="dialog"
  aria-modal="true"
  aria-labelledby="nl-popup-title"
  class="fixed inset-0 z-200 flex items-center justify-center px-4"
  style="display:none"
>
  {{-- Backdrop --}}
  <div
    class="absolute inset-0 bg-ink/60 backdrop-blur-sm"
    @click="dismiss()"
    aria-hidden="true"
  ></div>

  {{-- Modal --}}
  <div
    class="relative z-10 w-full max-w-lg bg-white shadow-2xl"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95"
  >
    {{-- Close --}}
    <button
      type="button"
      @click="dismiss()"
      class="absolute top-4 right-4 text-muted hover:text-ink transition-colors z-10"
      aria-label="{{ __('Chiudi', 'sage') }}"
    >
      <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
      </svg>
    </button>

    {{-- Layout: image + form --}}
    <div class="flex flex-col sm:flex-row min-h-[320px]">

      {{-- Decorative side --}}
      <div class="sm:w-2/5 bg-ink flex items-center justify-center p-8 relative overflow-hidden">
        <div class="absolute inset-0 opacity-5">
          <div class="absolute -top-10 -left-10 w-40 h-40 bg-accent rounded-full"></div>
          <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-accent rounded-full"></div>
        </div>
        <div class="relative text-center">
          <p class="font-sans text-xs font-semibold tracking-[0.3em] uppercase text-accent mb-4">{{ __('Esclusivo', 'sage') }}</p>
          <p class="font-serif text-3xl font-light text-white leading-tight">-10%</p>
          <p class="text-white/50 text-sm mt-2">{{ __('sul tuo primo ordine', 'sage') }}</p>
        </div>
      </div>

      {{-- Form --}}
      <div class="flex-1 p-8 flex flex-col justify-center">
        <p class="section-label text-muted mb-2">{{ __('Newsletter', 'sage') }}</p>
        <h2 id="nl-popup-title" class="font-serif text-2xl font-light text-ink leading-tight mb-2">
          {{ esc_html(get_theme_mod('newsletter_heading', __('Offerte esclusive, ogni settimana', 'sage'))) }}
        </h2>
        <p class="text-sm text-muted mb-6">{{ __('Iscriviti e ricevi novità, offerte e contenuti premium in anteprima.', 'sage') }}</p>

        <form
          x-data="{ email: '', state: 'idle', message: '' }"
          @submit.prevent="
            if (!email || state === 'loading') return;
            state = 'loading';
            fetch('{{ esc_url(rest_url('theme/v1/newsletter')) }}', {
              method: 'POST',
              headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': '{{ wp_create_nonce('wp_rest') }}' },
              body: JSON.stringify({ email }),
            })
            .then(r => r.json())
            .then(d => { state = d.success ? 'done' : 'error'; message = d.message || ''; if (d.success) { localStorage.setItem('nl_popup_dismissed', Date.now() + 30*24*60*60*1000); } })
            .catch(() => { state = 'error'; message = '{{ __('Errore. Riprova.', 'sage') }}'; });
          "
          novalidate
        >
          <template x-if="state !== 'done'">
            <div>
              <label for="nl-popup-email" class="sr-only">{{ __('Email', 'sage') }}</label>
              <div class="flex gap-0">
                <input
                  id="nl-popup-email"
                  type="email"
                  x-model="email"
                  placeholder="{{ __('La tua email', 'sage') }}"
                  :disabled="state === 'loading'"
                  required
                  class="flex-1 form-input-luxury border-r-0 py-3 text-sm"
                >
                <button
                  type="submit"
                  :disabled="state === 'loading'"
                  class="btn-primary text-xs px-5 py-3 shrink-0 disabled:opacity-60"
                >
                  <span x-show="state !== 'loading'">{{ __('Iscriviti', 'sage') }}</span>
                  <span x-show="state === 'loading'" class="animate-pulse">…</span>
                </button>
              </div>
              <p x-show="state === 'error'" x-text="message" class="text-xs text-error mt-2" aria-live="assertive"></p>
              <p class="text-xs text-muted/60 mt-3">{{ __('Nessuno spam. Disiscriviti quando vuoi.', 'sage') }}</p>
            </div>
          </template>
          <div x-show="state === 'done'" class="text-center py-4" aria-live="polite">
            <svg class="w-10 h-10 text-success mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
            <p class="font-serif text-lg font-light text-ink" x-text="message || '{{ __('Grazie per l\'iscrizione!', 'sage') }}'"></p>
          </div>
        </form>

        {{-- Skip link --}}
        <button
          type="button"
          @click="dismiss()"
          class="text-xs text-muted/50 hover:text-muted transition-colors mt-4 text-left"
        >
          {{ __('No grazie, continuo senza sconti →', 'sage') }}
        </button>
      </div>
    </div>
  </div>
</div>

<script>
function newsletterPopup() {
  return {
    visible: false,
    _timer: null,

    init() {
      // Skip if already subscribed or dismissed recently
      const stored = localStorage.getItem('nl_popup_dismissed');
      if (stored && Date.now() < parseInt(stored)) return;

      // Skip on checkout/account pages (non-disturbance UX)
      if (document.body.classList.contains('woocommerce-checkout') ||
          document.body.classList.contains('woocommerce-account') ||
          document.body.classList.contains('woocommerce-cart')) return;

      // Exit intent (desktop): show when cursor leaves top of page
      const handleExitIntent = (e) => {
        if (e.clientY <= 0) {
          this.show();
          document.removeEventListener('mouseleave', handleExitIntent);
        }
      };
      document.addEventListener('mouseleave', handleExitIntent);

      // Timer fallback: 30 seconds (mobile-friendly)
      this._timer = setTimeout(() => {
        document.removeEventListener('mouseleave', handleExitIntent);
        this.show();
      }, 30000);
    },

    show() {
      // Respect cookie consent
      const consent = localStorage.getItem('cookie_consent');
      if (!consent) return; // Banner not yet answered — don't stack modals
      this.visible = true;
    },

    dismiss() {
      this.visible = false;
      clearTimeout(this._timer);
      // Dismiss for 7 days
      localStorage.setItem('nl_popup_dismissed', Date.now() + 7 * 24 * 60 * 60 * 1000);
    },
  };
}
</script>
@endif
