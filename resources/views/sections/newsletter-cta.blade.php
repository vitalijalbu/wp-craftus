@php
  $section_label = $section_label ?? '';
  $section_title = $section_title ?? get_theme_mod('newsletter_heading', __('Resta aggiornato', 'sage'));
  $section_sub   = $section_sub   ?? __('Iscriviti e ricevi offerte esclusive, novità e contenuti riservati agli abbonati.', 'sage');
  $bg            = $bg            ?? 'ink';   // 'surface' | 'cream' | 'ink'
  $layout        = $layout        ?? 'split'; // 'split' | 'centered'
  $placeholder   = $placeholder   ?? __('La tua email', 'sage');
  $btn_label     = $btn_label     ?? __('Iscriviti', 'sage');
  $privacy_label = $privacy_label ?? __('Accetto la <a href="{url}" class="underline">Privacy Policy</a>.', 'sage');
  $privacy_url   = esc_url(get_privacy_policy_url() ?: home_url('/privacy'));
  $privacy_label = str_replace('{url}', $privacy_url, $privacy_label);

  $bg_class    = match($bg) { 'cream' => 'bg-cream', 'surface' => 'bg-surface', default => 'bg-ink' };
  $title_class = $bg === 'ink' ? 'text-white'   : 'text-ink';
  $sub_class   = $bg === 'ink' ? 'text-white/50' : 'text-muted';
  $label_class = $bg === 'ink' ? 'text-accent'    : 'text-muted';
  $rest_url    = esc_url(rest_url('theme/v1/newsletter'));
  $nonce       = wp_create_nonce('wp_rest');
@endphp

<section
  id="{{ $section_id ?? 'section-newsletter' }}"
  class="section-luxury {{ $bg_class }}"
  aria-label="{{ strip_tags($section_title) }}"
>
  <div class="max-w-360 mx-auto px-6 lg:px-10">

    @if($layout === 'split')
      <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">

        {{-- Left: copy --}}
        <div>
          @if($section_label)
            <span class="section-label {{ $label_class }}" data-scroll="fade">{{ $section_label }}</span>
          @endif
          <h2 class="section-title {{ $title_class }}" data-scroll="text-reveal">{!! $section_title !!}</h2>
          <p class="section-subtitle mt-4 {{ $sub_class }}" data-scroll="slide-up">{{ $section_sub }}</p>
        </div>

        {{-- Right: form --}}
        <div data-scroll="fade">
          @include('partials.newsletter-form', compact('placeholder', 'btn_label', 'privacy_label', 'bg', 'rest_url', 'nonce'))
        </div>

      </div>

    @else
      {{-- Centered --}}
      <div class="text-center max-w-xl mx-auto">
        @if($section_label)
          <span class="section-label {{ $label_class }}" data-scroll="fade">{{ $section_label }}</span>
        @endif
        <h2 class="section-title {{ $title_class }}" data-scroll="text-reveal">{!! $section_title !!}</h2>
        <p class="section-subtitle mx-auto mt-4 mb-10 {{ $sub_class }}" data-scroll="slide-up">{{ $section_sub }}</p>
        <div data-scroll="fade">
          @include('partials.newsletter-form', compact('placeholder', 'btn_label', 'privacy_label', 'bg', 'rest_url', 'nonce'))
        </div>
      </div>
    @endif

  </div>
</section>
