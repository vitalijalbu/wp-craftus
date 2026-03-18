@php
  // Parameters
  $style        = $style        ?? 'dark'; // 'dark' | 'cream' | 'image' | 'primary'
  $label        = $label        ?? '';
  $title        = $title        ?? __('Cosa possiamo fare per te?', 'sage');
  $subtitle     = $subtitle     ?? __('Scopri la nostra selezione di prodotti e servizi premium.', 'sage');
  $cta_label    = $cta_label    ?? __('Scopri i prodotti', 'sage');
  $cta_url      = $cta_url      ?? '/shop';
  $cta2_label   = $cta2_label   ?? __('Contattaci', 'sage');
  $cta2_url     = $cta2_url     ?? '/contatti';
  $image        = $image        ?? null;
  $image_url    = is_array($image) ? ($image['url'] ?? '') : (is_string($image) ? $image : '');
  $image_alt    = is_array($image) ? ($image['alt'] ?? '') : '';
  $align        = $align        ?? 'center'; // 'center' | 'left'

  // Style classes
  $section_classes = match($style) {
    'cream'   => 'bg-cream',
    'primary' => 'bg-primary',
    'image'   => 'relative overflow-hidden bg-ink',
    default   => 'bg-ink',
  };
  $title_class  = in_array($style, ['dark', 'image', 'primary']) ? 'text-white'   : 'text-ink';
  $sub_class    = in_array($style, ['dark', 'image', 'primary']) ? 'text-white/65' : 'text-muted';
  $label_class  = 'text-gold';

  $align_class = $align === 'center' ? 'text-center items-center mx-auto' : 'text-left';
  $btn_class   = match($style) {
    'cream'   => 'btn-primary',
    'primary' => 'btn-primary bg-white text-primary border-white hover:bg-white/90',
    default   => 'btn-primary bg-white text-ink border-white hover:bg-white/90',
  };
  $btn2_class  = match($style) {
    'cream'   => 'btn-ghost',
    'primary' => 'btn-outline-white',
    default   => 'btn-outline-white',
  };
@endphp

<section
  class="section-luxury {{ $section_classes }}"
  aria-label="{{ strip_tags($title) }}"
>
  {{-- Background image (style='image') --}}
  @if($style === 'image' && $image_url)
    <div class="absolute inset-0 z-0" aria-hidden="true">
      <img
        src="{{ $image_url }}"
        alt="{{ $image_alt }}"
        class="w-full h-full object-cover"
        loading="lazy"
        decoding="async"
      >
      <div class="absolute inset-0 bg-ink/65"></div>
    </div>
  @endif

  <div class="max-w-360 mx-auto px-6 lg:px-10 relative z-10">
    <div class="max-w-2xl {{ $align_class }} flex flex-col gap-6">

      @if($label)
        <span class="section-label {{ $label_class }}" data-scroll="fade">{{ $label }}</span>
      @endif

      <div class="divider-gold {{ $align === 'center' ? 'mx-auto' : '' }}" data-scroll="line-in" aria-hidden="true"></div>

      <h2
        class="section-title-lg {{ $title_class }}"
        data-scroll="text-reveal"
      >{!! $title !!}</h2>

      @if($subtitle)
        <p
          class="section-subtitle {{ $sub_class }} {{ $align === 'center' ? 'mx-auto' : '' }}"
          data-scroll="slide-up"
        >{{ $subtitle }}</p>
      @endif

      @if($cta_label && $cta_url)
        <div
          class="flex flex-wrap gap-4 {{ $align === 'center' ? 'justify-center' : '' }}"
          data-scroll="slide-up"
        >
          <a href="{{ $cta_url }}" class="{{ $btn_class }}">
            {{ $cta_label }}
          </a>
          @if($cta2_label && $cta2_url)
            <a href="{{ $cta2_url }}" class="{{ $btn2_class }}">
              {{ $cta2_label }}
            </a>
          @endif
        </div>
      @endif

    </div>
  </div>
</section>
