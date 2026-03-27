@php
  /**
   * Before/After image slider section.
   *
   * @var int    $before_id      Attachment ID for "before" image
   * @var string $before_label   Label shown on before side
   * @var int    $after_id       Attachment ID for "after" image
   * @var string $after_label    Label shown on after side
   * @var string $section_label  Small eyebrow label
   * @var string $heading        Section heading
   * @var string $subtext        Short description
   * @var string $bg             'surface' | 'cream' | 'ink'
   * @var string $aspect         Tailwind aspect ratio class, e.g. 'aspect-[16/9]'
   */
  $before_id    = $before_id    ?? 0;
  $before_url   = $before_url   ?? ($before_id ? wp_get_attachment_image_url($before_id, 'full') : '');
  $before_alt   = $before_alt   ?? ($before_id ? esc_attr(get_post_meta($before_id, '_wp_attachment_image_alt', true)) : '');
  $before_label = $before_label ?? __('Prima', 'sage');
  $after_id     = $after_id     ?? 0;
  $after_url    = $after_url    ?? ($after_id ? wp_get_attachment_image_url($after_id, 'full') : '');
  $after_alt    = $after_alt    ?? ($after_id ? esc_attr(get_post_meta($after_id, '_wp_attachment_image_alt', true)) : '');
  $after_label  = $after_label  ?? __('Dopo', 'sage');
  $section_label = $section_label ?? '';
  $heading      = $heading      ?? '';
  $subtext      = $subtext      ?? '';
  $bg           = $bg           ?? 'surface';
  $aspect       = $aspect       ?? 'aspect-[4/3]';

  $bg_class    = match($bg) { 'cream' => 'bg-cream', 'ink' => 'bg-ink', default => 'bg-surface' };
  $title_class = $bg === 'ink' ? 'text-white'    : 'text-ink';
  $sub_class   = $bg === 'ink' ? 'text-white/60' : 'text-muted';
  $label_class = $bg === 'ink' ? 'text-accent'     : 'text-muted';
@endphp

@if($before_url && $after_url)
<section
  id="{{ $section_id ?? 'section-before-after' }}"
  class="section-luxury {{ $bg_class }}"
  aria-label="{{ $heading ?: __('Prima e dopo', 'sage') }}"
>
  <div class="max-w-360 mx-auto px-6 lg:px-10">

    @if($section_label || $heading)
      <div class="mb-12 text-center">
        @if($section_label)
          <span class="section-label {{ $label_class }}" data-scroll="fade">{{ $section_label }}</span>
        @endif
        @if($heading)
          <h2 class="section-title {{ $title_class }}" data-scroll="text-reveal">{!! $heading !!}</h2>
        @endif
        @if($subtext)
          <p class="section-subtitle mt-4 max-w-2xl mx-auto {{ $sub_class }}" data-scroll="slide-up">{{ $subtext }}</p>
        @endif
      </div>
    @endif

    {{-- Slider --}}
    <div
      class="before-after {{ $aspect }} relative overflow-hidden select-none"
      x-data="beforeAfter()"
      @mousedown="startDrag($event)"
      @touchstart.prevent="startDrag($event)"
      @mousemove.window="drag($event)"
      @touchmove.window="drag($event)"
      @mouseup.window="stopDrag()"
      @touchend.window="stopDrag()"
      role="group"
      aria-label="{{ __('Slider prima/dopo', 'sage') }}"
    >
      {{-- After image (full width, bottom layer) --}}
      <img
        src="{{ esc_url($after_url) }}"
        alt="{{ esc_attr($after_alt) }}"
        class="absolute inset-0 w-full h-full object-cover"
        draggable="false"
        loading="lazy"
        decoding="async"
      >

      {{-- Before image (clipped, top layer) --}}
      <div
        class="before-after__before absolute inset-0 overflow-hidden"
        :style="`width: ${pos}%`"
      >
        <img
          src="{{ esc_url($before_url) }}"
          alt="{{ esc_attr($before_alt) }}"
          class="absolute inset-0 w-full h-full object-cover"
          draggable="false"
          loading="lazy"
          decoding="async"
          :style="`width: ${100 / (pos / 100)}%`"
        >
        {{-- Before label --}}
        <span class="before-after__label before-after__label--before" aria-hidden="true">
          {{ $before_label }}
        </span>
      </div>

      {{-- After label --}}
      <span class="before-after__label before-after__label--after" aria-hidden="true">
        {{ $after_label }}
      </span>

      {{-- Handle --}}
      <div
        class="before-after__handle"
        :style="`left: ${pos}%`"
        role="slider"
        aria-label="{{ __('Trascina per confrontare', 'sage') }}"
        aria-valuemin="0"
        aria-valuemax="100"
        :aria-valuenow="Math.round(pos)"
        tabindex="0"
        @keydown.arrow-right.prevent="pos = Math.min(100, pos + 1)"
        @keydown.arrow-left.prevent="pos  = Math.max(0,   pos - 1)"
      >
        <div class="before-after__handle-line"></div>
        <div class="before-after__handle-btn">
          {{-- Chevrons --}}
          <svg class="w-4 h-4 rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m15 19-7-7 7-7"/></svg>
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m9 5 7 7-7 7"/></svg>
        </div>
      </div>
    </div>

  </div>
</section>

<script>
if (typeof Alpine !== 'undefined') {
  document.addEventListener('alpine:init', () => {
    Alpine.data('beforeAfter', () => ({
      pos:      50,
      dragging: false,
      _el:      null,

      startDrag(e) {
        this.dragging = true
        this._el = e.currentTarget
        this.updatePos(e)
      },

      drag(e) {
        if (!this.dragging) return
        this.updatePos(e)
      },

      stopDrag() { this.dragging = false },

      updatePos(e) {
        if (!this._el) return
        const rect  = this._el.getBoundingClientRect()
        const clientX = e.touches ? e.touches[0].clientX : e.clientX
        const pct   = ((clientX - rect.left) / rect.width) * 100
        this.pos    = Math.min(100, Math.max(0, pct))
      },
    }))
  })
}
</script>
@endif
