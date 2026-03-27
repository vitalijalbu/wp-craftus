@php
  // Parameters — pass via @include or set from ACF
  $image_position = $image_position ?? 'left'; // 'left' | 'right'
  $label          = $label          ?? '';
  $title          = $title          ?? '';
  $text           = $text           ?? '';
  $cta_label      = $cta_label      ?? '';
  $cta_url        = $cta_url        ?? '';
  $cta2_label     = $cta2_label     ?? '';
  $cta2_url       = $cta2_url       ?? '';
  $image          = $image          ?? null;  // WP attachment array ['url', 'alt', 'sizes']
  $image_id       = is_array($image) ? (int) ($image['ID'] ?? $image['id'] ?? 0) : 0;
  $image_url      = is_array($image) ? ($image['url'] ?? '') : (is_string($image) ? $image : '');
  $image_alt      = is_array($image) ? ($image['alt'] ?? '') : '';
  $bg             = $bg             ?? 'surface'; // 'surface' | 'cream' | 'ink'
  $accent         = $accent         ?? false;  // show gold accent bar

  $bg_class   = match($bg) {
    'cream' => 'bg-cream',
    'ink'   => 'bg-ink',
    default => 'bg-surface',
  };
  $text_class  = $bg === 'ink' ? 'text-white'   : 'text-ink';
  $label_class = $bg === 'ink' ? 'text-accent'     : 'text-muted';
  $muted_class = $bg === 'ink' ? 'text-white/60' : 'text-muted';

  // Reverse column order when image is right
  $row_class = $image_position === 'right' ? 'lg:flex-row-reverse' : 'lg:flex-row';
@endphp

<section id="{{ $section_id ?? 'section-media-text' }}" class="section-luxury {{ $bg_class }} overflow-hidden" aria-label="{{ strip_tags($title) ?: __('Sezione media e testo', 'sage') }}">
  <div class="max-w-360 mx-auto px-6 lg:px-10">
    <div class="flex flex-col {{ $row_class }} gap-12 lg:gap-20 items-center">

      {{-- Image column --}}
      <div class="w-full lg:w-1/2" data-scroll="fade">
        <div class="relative">
          @if($image_id)
            <x-picture
              :id="$image_id"
              :alt="$image_alt"
              class="media-text-image"
              size="large"
              sizes="(max-width: 1024px) 100vw, 50vw"
            />
          @elseif($image_url)
            <img
              src="{{ $image_url }}"
              alt="{{ $image_alt }}"
              class="media-text-image"
              loading="lazy"
              decoding="async"
            >
          @else
            <div class="media-text-image bg-linear-to-br from-cream to-border flex items-center justify-center" aria-hidden="true">
              <svg class="w-16 h-16 text-border" fill="none" stroke="currentColor" stroke-width="0.75" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
              </svg>
            </div>
          @endif

          {{-- Decorative corner accent --}}
          @if($accent)
            <div
              class="absolute -bottom-4 {{ $image_position === 'right' ? '-left-4' : '-right-4' }} w-24 h-24 border-2 border-accent z-10 pointer-events-none"
              aria-hidden="true"
            ></div>
          @endif
        </div>
      </div>

      {{-- Text column --}}
      <div class="w-full lg:w-1/2">

        @if($label)
          <span class="section-label {{ $label_class }}" data-scroll="fade">{{ $label }}</span>
        @endif

        {{-- Gold line --}}
        <div class="divider-gold" data-scroll="line-in" aria-hidden="true"></div>

        @if($title)
          <h2
            class="section-title {{ $text_class }} mb-6"
            data-scroll="text-reveal"
          >{!! $title !!}</h2>
        @endif

        @if($text)
          <div
            class="text-base leading-relaxed {{ $muted_class }} mb-8 space-y-4"
            data-scroll="slide-up"
          >{!! wpautop($text) !!}</div>
        @endif

        @if($cta_label && $cta_url)
          <div class="flex flex-wrap gap-4" data-scroll="slide-up">
            @if($bg === 'ink')
              <a href="{{ $cta_url }}" class="btn-primary bg-white text-ink border-white hover:bg-white/90">
                {{ $cta_label }}
              </a>
            @else
              <a href="{{ $cta_url }}" class="btn-primary">
                {{ $cta_label }}
              </a>
            @endif

            @if($cta2_label && $cta2_url)
              @if($bg === 'ink')
                <a href="{{ $cta2_url }}" class="btn-outline-white">{{ $cta2_label }}</a>
              @else
                <a href="{{ $cta2_url }}" class="btn-ghost">{{ $cta2_label }}</a>
              @endif
            @endif
          </div>
        @endif

      </div>

    </div>
  </div>
</section>
