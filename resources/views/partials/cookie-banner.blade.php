{{--
  GDPR Cookie Banner
  - Stores consent in localStorage under key "cookie_consent"
  - Values: "all" | "essential" | null (not yet decided)
  - Alpine component; no page reload required.
  - Fires `cookieConsent` custom event so analytics scripts can self-init.
  - Usage: @include('partials.cookie-banner') in layouts/app.blade.php
--}}
<div
  x-data="cookieBanner()"
  x-init="init()"
  x-show="visible"
  x-cloak
  x-transition:enter="transition ease-out duration-300"
  x-transition:enter-start="opacity-0 translate-y-4"
  x-transition:enter-end="opacity-100 translate-y-0"
  x-transition:leave="transition ease-in duration-200"
  x-transition:leave-start="opacity-100 translate-y-0"
  x-transition:leave-end="opacity-0 translate-y-4"
  role="dialog"
  aria-modal="false"
  aria-labelledby="cookie-banner-title"
  aria-describedby="cookie-banner-desc"
  class="fixed bottom-0 inset-x-0 z-50 bg-white border-t border-border shadow-xl"
  x-cloak
>
  <div class="container py-5 flex flex-col sm:flex-row items-start sm:items-center gap-4">

    {{-- Text --}}
    <div class="flex-1 min-w-0">
      <p id="cookie-banner-title" class="text-sm font-semibold text-ink mb-1">
        {{ __('Questo sito utilizza i cookie', 'sage') }}
      </p>
      <p id="cookie-banner-desc" class="text-xs text-muted leading-relaxed">
        {{ __('Usiamo cookie tecnici necessari e, con il tuo consenso, cookie analitici e di marketing per migliorare l\'esperienza. Puoi accettare tutto o solo i cookie essenziali.', 'sage') }}
        <a href="{{ esc_url(get_privacy_policy_url() ?: home_url('/privacy-policy')) }}"
           class="underline hover:text-primary transition-colors ml-1">
          {{ __('Privacy policy', 'sage') }}
        </a>
      </p>
    </div>

    {{-- Actions --}}
    <div class="flex flex-wrap gap-2 shrink-0">
      <button
        @click="accept('essential')"
        type="button"
        class="btn-outline"
      >
        {{ __('Solo essenziali', 'sage') }}
      </button>
      <button
        @click="accept('all')"
        type="button"
        class="btn-secondary"
      >
        {{ __('Accetta tutto', 'sage') }}
      </button>
    </div>

  </div>
</div>

<script>
  function cookieBanner() {
    return {
      visible: false,

      init() {
        const stored = localStorage.getItem('cookie_consent');
        if (!stored) {
          // Small delay so banner doesn't flash during page paint
          setTimeout(() => { this.visible = true; }, 800);
        }
      },

      accept(level) {
        localStorage.setItem('cookie_consent', level);
        this.visible = false;
        window.dispatchEvent(new CustomEvent('cookieConsent', { detail: { level } }));
      },
    };
  }
</script>
