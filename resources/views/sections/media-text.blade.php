@php
  // Parameters — pass via @include or set from ACF
  $image_position = $image_position ?? 'left'; // 'left' | 'right'
  $label          = sanitize_text_field($label ?? '');
  $title          = wp_kses_post($title ?? '');
  $text           = wp_kses_post($text ?? '');
  $cta_label      = sanitize_text_field($cta_label ?? '');
  $cta_url        = esc_url($cta_url ?? '');
  $cta2_label     = sanitize_text_field($cta2_label ?? '');
  $cta2_url       = esc_url($cta2_url ?? '');
  $image          = $image          ?? null;  // WP attachment array ['url', 'alt', 'sizes']
  $image_id       = is_array($image) ? (int) ($image['ID'] ?? $image['id'] ?? 0) : 0;
  $image_url      = is_array($image) ? esc_url($image['url'] ?? '') : (is_string($image) ? esc_url($image) : '');
  $image_alt      = is_array($image) ? esc_attr($image['alt'] ?? '') : '';
  $bg             = $bg             ?? 'surface'; // 'surface' | 'cream' | 'ink'
  $accent         = $accent         ?? false;  // show gold accent bar

  $bg_class   = match($bg) {
    'cream' => 'bg-cream',
    'ink'   => 'bg-ink',
    default => 'bg-surface',
  };
  $text_class  = $bg === 'ink' ? 'text-white'   : 'text-ink';
  $label_class = $bg === 'ink' ? 'text-primary'     : 'text-muted';
  $muted_class = $bg === 'ink' ? 'text-white/60' : 'text-muted';

  // Reverse column order when image is right
  $row_class = $image_position === 'right' ? 'lg:flex-row-reverse' : 'lg:flex-row';
@endphp

<section id="{{ $section_id ?? 'section-media-text' }}" class="section {{ $bg_class }} overflow-hidden" aria-label="{{ strip_tags($title) ?: __('Sezione media e testo', 'sage') }}">
  <div class="container">
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
              <x-icons.image-placeholder class="w-16 h-16 text-border" stroke-width="0.75" />
            </div>
          @endif

          {{-- Decorative corner --}}
          @if($accent)
            <div
              class="absolute -bottom-4 {{ $image_position === 'right' ? '-left-4' : '-right-4' }} w-24 h-24 border border-primary z-10 pointer-events-none"
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
        <div class="divider-primary" data-scroll="line-in" aria-hidden="true"></div>

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
              <a href="{{ esc_url($cta_url) }}" class="btn-light">
                {{ $cta_label }}
              </a>
            @else
              <a href="{{ esc_url($cta_url) }}" class="btn-primary">
                {{ $cta_label }}
              </a>
            @endif

            @if($cta2_label && $cta2_url)
              @if($bg === 'ink')
                <a href="{{ esc_url($cta2_url) }}" class="btn-outline-white">{{ $cta2_label }}</a>
              @else
                <a href="{{ esc_url($cta2_url) }}" class="btn-ghost">{{ $cta2_label }}</a>
              @endif
            @endif
          </div>
        @endif

      </div>

    </div>
  </div>
</section>
