@php
  $bg          = $bg          ?? 'ink';
  $placeholder = $placeholder ?? __('La tua email', 'sage');
  $btn_label   = $btn_label   ?? __('Iscriviti', 'sage');
  $rest_url    = $rest_url    ?? esc_url(rest_url('theme/v1/newsletter'));
  $nonce       = $nonce       ?? wp_create_nonce('wp_rest');
  $input_class = $bg === 'ink'
    ? 'bg-transparent border-white/20 text-white placeholder-white/25 focus:border-white/60'
    : 'bg-transparent border-border text-ink placeholder-muted focus:border-ink';
  $btn_class   = $bg === 'ink'
    ? 'bg-white text-ink hover:bg-white/90'
    : 'bg-ink text-white hover:bg-ink/90';
  $meta_class  = $bg === 'ink' ? 'text-white/30' : 'text-muted/60';
@endphp

<div
  x-data="newsletterForm('{{ $rest_url }}', '{{ $nonce }}')"
  class="newsletter-form"
>
  <form
    @submit.prevent="submit()"
    novalidate
    role="search"
    aria-label="{{ __('Iscrizione newsletter', 'sage') }}"
  >
    <div class="flex border-b {{ $bg === 'ink' ? 'border-white/20 focus-within:border-white/60' : 'border-border focus-within:border-ink' }} transition-colors">
      <label for="nl-email-{{ uniqid() }}" class="sr-only">{{ __('Indirizzo email', 'sage') }}</label>
      <input
        id="nl-email-{{ uniqid() }}"
        type="email"
        x-model="email"
        placeholder="{{ $placeholder }}"
        autocomplete="email"
        required
        class="flex-1 py-3 pr-4 bg-transparent font-sans text-sm {{ $input_class }} focus:outline-none"
        :class="error ? 'text-red-400' : ''"
        :aria-describedby="error ? 'nl-error' : undefined"
      >
      <button
        type="submit"
        class="shrink-0 px-5 py-2 font-sans text-[10px] font-semibold tracking-[0.18em] uppercase transition-colors {{ $btn_class }}"
        :disabled="loading"
        :class="loading ? 'opacity-60 cursor-wait' : ''"
      >
        <span x-show="!loading">{{ $btn_label }}</span>
        <span x-show="loading" aria-live="polite">…</span>
      </button>
    </div>

    <p
      x-show="error"
      id="nl-error"
      class="mt-2 font-sans text-xs text-red-400"
      x-text="error"
      role="alert"
      aria-live="assertive"
    ></p>
  </form>

  {{-- Success state --}}
  <div
    x-show="success"
    x-transition
    class="py-3 font-sans text-sm {{ $bg === 'ink' ? 'text-white/70' : 'text-muted' }}"
    role="status"
    aria-live="polite"
  >
    ✓ {{ __('Iscritto! Controlla la tua email.', 'sage') }}
  </div>

  {{-- Privacy note --}}
  <p class="mt-3 font-sans text-[11px] leading-relaxed {{ $meta_class }}">
    {!! $privacy_label ?? '' !!}
  </p>
</div>

<script>
if (typeof Alpine !== 'undefined' && !Alpine._data?.newsletterForm) {
  document.addEventListener('alpine:init', () => {
    Alpine.data('newsletterForm', (restUrl, nonce) => ({
      email:   '',
      loading: false,
      success: false,
      error:   '',

      async submit() {
        this.error = ''
        if (!this.email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.email)) {
          this.error = '<?php echo esc_js(__("Inserisci un indirizzo email valido.", "sage")); ?>'
          return
        }
        this.loading = true
        try {
          const res  = await fetch(restUrl, {
            method:  'POST',
            headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': nonce },
            body:    JSON.stringify({ email: this.email }),
          })
          const data = await res.json()
          if (res.ok && data.success) {
            this.success = true
            this.email   = ''
          } else {
            this.error = data.message || '<?php echo esc_js(__("Si è verificato un errore.", "sage")); ?>'
          }
        } catch {
          this.error = '<?php echo esc_js(__("Errore di rete. Riprova.", "sage")); ?>'
        } finally {
          this.loading = false
        }
      },
    }))
  })
}
</script>
