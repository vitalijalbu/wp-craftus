@php
  // ACF fields with static fallbacks
  $hero_image    = function_exists('get_field') ? get_field('hero_image')    : null;
  $hero_video    = function_exists('get_field') ? get_field('hero_video')    : null;
  $hero_label    = function_exists('get_field') ? get_field('hero_label')    : __('Benvenuti', 'sage');
  $hero_title    = function_exists('get_field') ? get_field('hero_title')    : get_bloginfo('name');
  $hero_subtitle = function_exists('get_field') ? get_field('hero_subtitle') : get_bloginfo('description');
  $hero_cta_1_label = function_exists('get_field') ? get_field('hero_cta_1_label') : __('Scopri i prodotti', 'sage');
  $hero_cta_1_url   = function_exists('get_field') ? get_field('hero_cta_1_url')   : '/shop';
  $hero_cta_2_label = function_exists('get_field') ? get_field('hero_cta_2_label') : __('Chi siamo', 'sage');
  $hero_cta_2_url   = function_exists('get_field') ? get_field('hero_cta_2_url')   : '/about';
  $hero_align   = function_exists('get_field') ? get_field('hero_align')   : 'left'; // left | center
  $image_url    = $hero_image['url'] ?? '';
  $image_id     = (int) ($hero_image['ID'] ?? $hero_image['id'] ?? 0);
  $align_classes = $hero_align === 'center' ? 'text-center items-center mx-auto' : 'text-left';
@endphp

{{-- Remove header spacer on hero pages (header is transparent overlay) --}}
<style>.header-spacer{display:none}</style>

<section id="{{ $section_id ?? 'section-hero' }}" class="hero-section" aria-label="{{ strip_tags($hero_title) ?: get_bloginfo('name') }}">

  {{-- Background: video > image > gradient fallback --}}
  <div class="hero-bg" aria-hidden="true">
    @if($hero_video)
      <video
        autoplay
        muted
        loop
        playsinline
        poster="{{ $image_url ?: '' }}"
        class="w-full h-full object-cover"
      >
        <source src="{{ is_array($hero_video) ? $hero_video['url'] : $hero_video }}" type="video/mp4">
      </video>
    @elseif($image_id)
      <x-picture
        :id="$image_id"
        alt=""
        class="w-full h-full object-cover"
        size="full"
        sizes="100vw"
        loading="eager"
        fetchpriority="high"
        :data="['scroll' => 'hero-parallax']"
      />
    @elseif($image_url)
      <img
        src="{{ $image_url }}"
        alt=""
        class="w-full h-full object-cover"
        data-scroll="hero-parallax"
        fetchpriority="high"
        decoding="async"
      >
    @else
      <div class="w-full h-full bg-linear-to-br from-ink via-dark to-dark-900"></div>
    @endif
  </div>

  {{-- Overlay --}}
  <div class="hero-overlay" aria-hidden="true"></div>

  {{-- Content --}}
  <div class="hero-content pb-20 lg:pb-28">
    <div class="container">
      <div class="max-w-3xl {{ $align_classes }}">

        {{-- Label --}}
        @if($hero_label)
          <p
            class="font-semibold tracking-[0.25em] uppercase text-accent mb-6 opacity-0 animate-fade-in-up animate-delay-100"
            data-scroll="fade"
          >{{ $hero_label }}</p>
        @endif

        {{-- Divider line --}}
        <div
          class="w-10 h-px bg-white/40 mb-8 {{ $hero_align === 'center' ? 'mx-auto' : '' }}"
          data-scroll="line-in"
          aria-hidden="true"
        ></div>

        {{-- Title --}}
        <h1
          class="hero-title mb-6"
          data-scroll="text-reveal"
        >{!! $hero_title !!}</h1>

        {{-- Subtitle --}}
        @if($hero_subtitle)
          <p
            class="hero-subtitle max-w-xl mb-10 {{ $hero_align === 'center' ? 'mx-auto' : '' }}"
            data-scroll="slide-up"
          >{{ $hero_subtitle }}</p>
        @endif

        {{-- CTA buttons --}}
        <div
          class="flex flex-wrap gap-4 {{ $hero_align === 'center' ? 'justify-center' : '' }}"
          data-scroll="slide-up"
        >
          @if($hero_cta_1_label && $hero_cta_1_url)
            <a href="{{ $hero_cta_1_url }}" class="btn-light">
              {{ $hero_cta_1_label }}
            </a>
          @endif
          @if($hero_cta_2_label && $hero_cta_2_url)
            <a href="{{ $hero_cta_2_url }}" class="btn-outline-white">
              {{ $hero_cta_2_label }}
            </a>
          @endif
        </div>

      </div>
    </div>
  </div>

  {{-- Scroll indicator --}}
  <div
    class="absolute bottom-8 left-1/2 -translate-x-1/2 z-10 scroll-indicator"
    aria-hidden="true"
  >
    <div class="scroll-indicator-line"></div>
    <span class="mt-2">scroll</span>
  </div>

</section>
