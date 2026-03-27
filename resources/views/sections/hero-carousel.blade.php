@php
  // ACF repeater field: slides
  $slides = function_exists('get_field') ? (get_field('hero_slides') ?: []) : [];

  // Static fallback slides when ACF not available or no slides set
  if (empty($slides)) {
    $slides = [
      [
        'image'    => ['url' => '', 'alt' => ''],
        'label'    => __('Collezione', 'sage'),
        'title'    => get_bloginfo('name'),
        'subtitle' => get_bloginfo('description'),
        'cta_label'  => __('Scopri ora', 'sage'),
        'cta_url'    => '/shop',
        'cta2_label' => __('Chi siamo', 'sage'),
        'cta2_url'   => '/about',
      ],
    ];
  }
@endphp

{{-- Remove header spacer — transparent overlay header --}}
<style>.header-spacer{display:none}</style>

<section
  id="{{ $section_id ?? 'section-hero' }}"
  class="relative w-full overflow-hidden"
  aria-roledescription="{{ __('carosello', 'sage') }}"
  aria-label="{{ __('Slideshow hero', 'sage') }}"
  style="min-height: 100svh"
>
  {{-- Swiper container --}}
  <div class="swiper js-hero-swiper w-full h-full absolute inset-0">
    <div class="swiper-wrapper" aria-live="polite">

      @foreach($slides as $slide)
        @php
          $img_url = $slide['image']['url'] ?? '';
          $img_alt = $slide['image']['alt'] ?? '';
          $label   = $slide['label']   ?? '';
          $title   = $slide['title']   ?? '';
          $subtitle = $slide['subtitle'] ?? '';
          $cta_label  = $slide['cta_label']  ?? '';
          $cta_url    = $slide['cta_url']    ?? '';
          $cta2_label = $slide['cta2_label'] ?? '';
          $cta2_url   = $slide['cta2_url']   ?? '';
          $slide_count = count($slides);
        @endphp
        <div
          class="swiper-slide relative"
          role="group"
          aria-roledescription="{{ __('slide', 'sage') }}"
          aria-label="{{ sprintf(__('Slide %d di %d', 'sage'), $loop->iteration, $slide_count) }}"
          style="min-height: 100svh"
        >

          {{-- Slide background --}}
          <div class="absolute inset-0 z-0" aria-hidden="true">
            @if($img_url)
              <img
                src="{{ $img_url }}"
                alt="{{ $img_alt }}"
                class="w-full h-full object-cover"
                loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                fetchpriority="{{ $loop->first ? 'high' : 'low' }}"
                decoding="async"
              >
            @else
              <div class="w-full h-full bg-linear-to-br from-ink via-dark to-dark-900"></div>
            @endif
          </div>

          {{-- Overlay --}}
          <div class="absolute inset-0 z-1 bg-linear-to-t from-ink/70 via-ink/25 to-ink/5" aria-hidden="true"></div>

          {{-- Slide content --}}
          <div class="relative z-10 h-full flex items-end pb-24 lg:pb-32">
            <div class="max-w-360 mx-auto px-6 lg:px-10 w-full">
              <div class="max-w-2xl">

                @if($label)
                  <p class="font-semibold tracking-[0.25em] uppercase text-accent mb-5">
                    {{ $label }}
                  </p>
                @endif

                @if($title)
                  <h2 class="hero-title mb-5">{!! $title !!}</h2>
                @endif

                @if($subtitle)
                  <p class="hero-subtitle mb-8">{{ $subtitle }}</p>
                @endif

                @if($cta_label && $cta_url)
                  <div class="flex flex-wrap gap-4">
                    <a href="{{ $cta_url }}" class="btn-primary bg-white text-ink border-white hover:bg-white/90 hover:border-white/90">
                      {{ $cta_label }}
                    </a>
                    @if($cta2_label && $cta2_url)
                      <a href="{{ $cta2_url }}" class="btn-outline-white">
                        {{ $cta2_label }}
                      </a>
                    @endif
                  </div>
                @endif

              </div>
            </div>
          </div>

        </div>
      @endforeach

    </div>

    {{-- Pagination --}}
    <div
      class="swiper-pagination absolute bottom-8 left-1/2 -translate-x-1/2 z-20 flex gap-2"
      role="group"
      aria-label="{{ __('Scegli slide', 'sage') }}"
    ></div>

    {{-- Navigation arrows --}}
    <button
      class="swiper-button-prev swiper-dark absolute left-6 lg:left-10 top-1/2 -translate-y-1/2 z-20"
      aria-label="{{ __('Slide precedente', 'sage') }}"
    ></button>
    <button
      class="swiper-button-next swiper-dark absolute right-6 lg:right-10 top-1/2 -translate-y-1/2 z-20"
      aria-label="{{ __('Slide successiva', 'sage') }}"
    ></button>
  </div>

  {{-- Scroll indicator --}}
  <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-30 scroll-indicator" aria-hidden="true">
    <div class="scroll-indicator-line"></div>
    <span class="mt-2">scroll</span>
  </div>

</section>
