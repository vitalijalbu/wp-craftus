@php
  // Parameters
  $items    = $items    ?? []; // array of strings
  $bg       = $bg       ?? 'surface'; // 'surface' | 'cream' | 'ink'
  $size     = $size     ?? 'md'; // 'sm' | 'md' | 'lg'
  $speed    = $speed    ?? 20;   // GSAP duration in seconds (set via CSS var)

  // Default items if none provided
  if (empty($items)) {
    $items = [
      get_bloginfo('name'),
      __('Qualità Premium', 'sage'),
      __('Spedizione Rapida', 'sage'),
      __('Prodotti Selezionati', 'sage'),
      __('Cura & Benessere', 'sage'),
      __('Dal 2014', 'sage'),
    ];
  }

  $bg_class = match($bg) {
    'ink'    => 'bg-ink border-white/10',
    'cream'  => 'bg-cream border-border',
    default  => 'bg-surface border-border',
  };
  $text_class = $bg === 'ink' ? 'text-white' : 'text-ink';
  $dot_class  = 'text-accent';

  $font_size = match($size) {
    'sm' => 'text-sm',
    'lg' => 'text-3xl md:text-5xl',
    default => 'text-lg md:text-2xl',
  };
  $py_class = match($size) {
    'sm' => 'py-4',
    'lg' => 'py-8',
    default => 'py-5',
  };
@endphp

<section
  id="{{ $section_id ?? 'section-marquee' }}"
  class="marquee-outer {{ $bg_class }} {{ $py_class }} overflow-hidden"
  aria-label="{{ __('Vantaggi', 'sage') }}"
>
  <div class="marquee-track-wrapper">
    <div class="js-marquee-track flex items-center gap-10 whitespace-nowrap" style="--marquee-speed: {{ $speed }}s">
      @foreach($items as $item)
        <span class="js-marquee-item flex items-center gap-10">
          <span class="font-serif font-light tracking-widest uppercase {{ $font_size }} {{ $text_class }}">
            {{ $item }}
          </span>
          <svg class="w-2 h-2 fill-accent shrink-0" viewBox="0 0 8 8"><circle cx="4" cy="4" r="4"/></svg>
        </span>
      @endforeach
    </div>
  </div>
</section>
