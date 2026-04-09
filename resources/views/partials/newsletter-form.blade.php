@php
  $bg          = $bg          ?? 'ink';
  $nl_input_id = 'nl-email-' . wp_unique_id();
  $placeholder = $placeholder ?? __('La tua email', 'sage');
  $btn_label   = $btn_label   ?? __('Iscriviti', 'sage');
  $rest_url    = $rest_url    ?? esc_url(rest_url('theme/v1/newsletter'));
  $nonce       = $nonce       ?? wp_create_nonce('wp_rest');
  $input_class = $bg === 'ink'
    ? 'bg-transparent border-white/20 text-white placeholder-white/25 focus:border-white/60'
    : 'bg-transparent border-border text-ink placeholder-muted focus:border-ink';
  $btn_class   = $bg === 'ink'
    ? 'btn-light'
    : 'btn-secondary';
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
      <label for="{{ $nl_input_id }}" class="sr-only">{{ __('Indirizzo email', 'sage') }}</label>
      <input
        id="{{ $nl_input_id }}"
        type="email"
        x-model="email"
        placeholder="{{ $placeholder }}"
        autocomplete="email"
        required
        class="flex-1 py-3 pr-4 bg-transparent text-sm {{ $input_class }} focus:outline-none"
        :class="error ? 'text-error' : ''"
        :aria-describedby="error ? 'nl-error' : undefined"
      >
      <button
        type="submit"
        class="shrink-0 btn-sm {{ $btn_class }}"
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
      class="mt-2 text-xs text-error"
      x-text="error"
      role="alert"
      aria-live="assertive"
    ></p>
  </form>

  {{-- Success state --}}
  <div
    x-show="success"
    x-transition
    class="py-3 text-sm {{ $bg === 'ink' ? 'text-white/70' : 'text-muted' }}"
    role="status"
    aria-live="polite"
  >
    ✓ {{ __('Iscritto! Controlla la tua email.', 'sage') }}
  </div>

  {{-- Privacy note --}}
  <p class="mt-3 leading-relaxed {{ $meta_class }}">
    {!! $privacy_label ?? '' !!}
  </p>
</div>

{{-- Alpine component 'newsletterForm' registrato in resources/js/app.js --}}
