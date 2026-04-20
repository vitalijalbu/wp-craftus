@php
  $image_id    = $image_id    ?? 0;
  $image_url   = esc_url($image_url ?? ($image_id ? wp_get_attachment_image_url($image_id, 'full') : ''));
  $image_alt   = esc_attr($image_alt ?? ($image_id ? get_post_meta($image_id, '_wp_attachment_image_alt', true) : ''));
  $label       = sanitize_text_field($label ?? '');
  $heading     = wp_kses_post($heading ?? get_bloginfo('name'));
  $subtext     = sanitize_text_field($subtext ?? '');
  $cta_label   = sanitize_text_field($cta_label ?? __('Scopri di più', 'sage'));
  $cta_url     = esc_url($cta_url ?? home_url('/'));
  $cta2_label  = sanitize_text_field($cta2_label ?? '');
  $cta2_url    = esc_url($cta2_url ?? '');
  $image_right = $image_right ?? true; // true = image on right, false = image on left
  $bg          = $bg          ?? 'surface';
  $full_height = $full_height ?? true;

  $bg_class    = match($bg) { 'cream' => 'bg-cream', 'ink' => 'bg-ink', default => 'bg-surface' };
  $title_class = $bg === 'ink' ? 'text-white'   : 'text-ink';
  $sub_class   = $bg === 'ink' ? 'text-white/60' : 'text-muted';
  $label_class = $bg === 'ink' ? 'text-primary'    : 'text-muted';
  $order_text  = $image_right ? 'lg:order-first' : 'lg:order-last';
  $order_img   = $image_right ? 'lg:order-last'  : 'lg:order-first';
@endphp

<section
  id="{{ $section_id ?? 'section-hero' }}"
  class="split-hero {{ $bg_class }} {{ $full_height ? 'min-h-svh' : '' }} flex items-center"
  aria-label="{{ strip_tags($heading) }}"
>
  <div class="w-full grid lg:grid-cols-2 {{ $full_height ? 'min-h-svh' : '' }}">

    {{-- Text column --}}
    <div class="flex flex-col justify-center px-8 py-20 lg:px-16 xl:px-24 {{ $order_text }}">
      @if($label)
        <span class="section-label {{ $label_class }}" data-scroll="fade">{{ $label }}</span>
      @endif

      <h1 class="split-hero__heading {{ $title_class }}" data-scroll="text-reveal">
        {!! $heading !!}
      </h1>

      @if($subtext)
        <p class="section-subtitle mt-6 {{ $sub_class }}" data-scroll="slide-up">{{ $subtext }}</p>
      @endif

      <div class="flex flex-wrap items-center gap-4 mt-10" data-scroll="fade">
        <a href="{{ esc_url($cta_url) }}" class="btn-primary">{{ $cta_label }}</a>
        @if($cta2_label && $cta2_url)
          <a href="{{ esc_url($cta2_url) }}"
             class="text-xs font-semibold tracking-[0.15em] uppercase pb-0.5 border-b transition-colors {{ $bg === 'ink' ? 'text-white/60 border-white/20 hover:text-white hover:border-white' : 'text-muted border-muted/40 hover:text-ink hover:border-ink' }}">
            {{ $cta2_label }}
          </a>
        @endif
      </div>
    </div>

    {{-- Image column --}}
    <div class="relative {{ $full_height ? 'min-h-[50vh] lg:min-h-svh' : 'aspect-[4/3] lg:aspect-auto' }} overflow-hidden {{ $order_img }}">
      @if($image_id)
        <x-picture
          :id="(int) $image_id"
          :alt="$image_alt"
          class="absolute inset-0 w-full h-full object-cover"
          :loading="'eager'"
          :fetchpriority="true"
          sizes="(max-width: 1024px) 100vw, 50vw"
        />
      @elseif($image_url)
        <img
          src="{{ $image_url }}"
          alt="{{ $image_alt }}"
          class="absolute inset-0 w-full h-full object-cover"
          loading="eager"
          fetchpriority="high"
          decoding="async"
        >
      @endif
    </div>

  </div>
</section>
