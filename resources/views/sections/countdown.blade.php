@php
  $section_label = $section_label ?? '';
  $heading       = $heading       ?? __('Offerta a tempo limitato', 'sage');
  $subtext       = $subtext       ?? '';
  $end_date      = $end_date      ?? ''; // ISO 8601 e.g. "2025-12-31T23:59:59"
  $cta_label     = $cta_label     ?? __('Approfitta ora', 'sage');
  $cta_url       = $cta_url       ?? home_url('/');
  $bg            = $bg            ?? 'ink';
  $show_seconds  = $show_seconds  ?? true;
  $expired_text  = $expired_text  ?? __('L\'offerta è scaduta.', 'sage');

  $bg_class    = match($bg) { 'cream' => 'bg-cream', 'surface' => 'bg-surface', default => 'bg-ink' };
  $title_class = $bg === 'ink' ? 'text-white'    : 'text-ink';
  $sub_class   = $bg === 'ink' ? 'text-white/60' : 'text-muted';
  $label_class = $bg === 'ink' ? 'text-gold'     : 'text-muted';
  $unit_class  = $bg === 'ink' ? 'text-white/40' : 'text-muted';
  $num_class   = $bg === 'ink' ? 'text-white'    : 'text-ink';
  $card_class  = $bg === 'ink' ? 'bg-white/5'    : 'bg-cream';
@endphp

<section
  class="section-luxury {{ $bg_class }}"
  aria-label="{{ strip_tags($heading) }}"
  x-data="countdown('{{ esc_js($end_date) }}')"
  x-init="start()"
>
  <div class="max-w-360 mx-auto px-6 lg:px-10 text-center">

    @if($section_label)
      <span class="section-label {{ $label_class }}" data-scroll="fade">{{ $section_label }}</span>
    @endif

    <h2 class="section-title {{ $title_class }}" data-scroll="text-reveal">{!! $heading !!}</h2>

    @if($subtext)
      <p class="section-subtitle mt-4 max-w-2xl mx-auto {{ $sub_class }}" data-scroll="slide-up">{{ $subtext }}</p>
    @endif

    {{-- Countdown blocks --}}
    <div
      x-show="!expired"
      class="flex flex-wrap justify-center gap-4 sm:gap-8 mt-12"
      data-scroll="fade"
    >
      <div class="countdown-unit {{ $card_class }}">
        <span class="countdown-num {{ $num_class }}" x-text="pad(days)">00</span>
        <span class="countdown-label {{ $unit_class }}">{{ __('Giorni', 'sage') }}</span>
      </div>
      <div class="countdown-unit {{ $card_class }}">
        <span class="countdown-num {{ $num_class }}" x-text="pad(hours)">00</span>
        <span class="countdown-label {{ $unit_class }}">{{ __('Ore', 'sage') }}</span>
      </div>
      <div class="countdown-unit {{ $card_class }}">
        <span class="countdown-num {{ $num_class }}" x-text="pad(minutes)">00</span>
        <span class="countdown-label {{ $unit_class }}">{{ __('Minuti', 'sage') }}</span>
      </div>
      @if($show_seconds)
        <div class="countdown-unit {{ $card_class }}">
          <span class="countdown-num {{ $num_class }}" x-text="pad(seconds)">00</span>
          <span class="countdown-label {{ $unit_class }}">{{ __('Secondi', 'sage') }}</span>
        </div>
      @endif
    </div>

    {{-- Expired message --}}
    <p
      x-show="expired"
      class="mt-12 font-sans text-sm {{ $sub_class }}"
      aria-live="polite"
    >{{ $expired_text }}</p>

    @if($cta_label && $cta_url)
      <div class="mt-10" data-scroll="fade">
        <a href="{{ esc_url($cta_url) }}" class="btn-primary" x-show="!expired">
          {{ $cta_label }}
        </a>
      </div>
    @endif

  </div>
</section>

<script>
if (typeof Alpine !== 'undefined') {
  document.addEventListener('alpine:init', () => {
    Alpine.data('countdown', (endDate) => ({
      days:    0,
      hours:   0,
      minutes: 0,
      seconds: 0,
      expired: false,
      _timer:  null,

      start() {
        if (!endDate) { this.expired = true; return }
        this.tick()
        this._timer = setInterval(() => this.tick(), 1000)
      },

      tick() {
        const diff = new Date(endDate) - Date.now()
        if (diff <= 0) {
          this.days = this.hours = this.minutes = this.seconds = 0
          this.expired = true
          clearInterval(this._timer)
          return
        }
        const s = Math.floor(diff / 1000)
        this.days    = Math.floor(s / 86400)
        this.hours   = Math.floor((s % 86400) / 3600)
        this.minutes = Math.floor((s % 3600) / 60)
        this.seconds = s % 60
      },

      pad(n) { return String(n).padStart(2, '0') },
    }))
  })
}
</script>
