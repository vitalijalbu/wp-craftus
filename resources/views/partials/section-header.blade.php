@php
  /**
   * Section header partial — label + titolo + sottotitolo.
   *
   * Usato da tutte le sezioni blade per evitare duplicazione del markup.
   *
   * @param string $section_label    Eyebrow label (opzionale)
   * @param string $section_title    Titolo H2 (HTML trusted — viene da PHP/editor)
   * @param string $section_subtitle Sottotitolo (opzionale)
   * @param string $bg               'surface' | 'cream' | 'ink' (default: 'surface')
   * @param string $align            'left' | 'center' (default: 'left')
   */
  $section_label    = $section_label    ?? '';
  $section_title    = $section_title    ?? '';
  $section_subtitle = $section_subtitle ?? '';
  $bg               = $bg               ?? 'surface';
  $align            = $align            ?? 'left';

  $label_class = $bg === 'ink' ? 'text-primary'    : 'text-muted';
  $title_class = $bg === 'ink' ? 'text-white'     : 'text-ink';
  $sub_class   = $bg === 'ink' ? 'text-white/60'  : 'text-muted';

  $wrap_class  = $align === 'center' ? 'text-center' : '';
@endphp

@if($section_label || $section_title || $section_subtitle)
  <div class="{{ $wrap_class }}">
    @if($section_label)
      <span class="section-label {{ $label_class }}" data-scroll="fade">{{ $section_label }}</span>
    @endif

    @if($section_title)
      <h2 class="section-title {{ $title_class }}" data-scroll="text-reveal">{!! $section_title !!}</h2>
    @endif

    @if($section_subtitle)
      <p class="section-subtitle mt-4 {{ $sub_class }} {{ $align === 'center' ? 'mx-auto' : '' }}" data-scroll="slide-up">
        {{ $section_subtitle }}
      </p>
    @endif
  </div>
@endif
