@php
  // Parameters
  $section_label    = sanitize_text_field($section_label ?? '');
  $section_title    = wp_kses_post($section_title ?? __('Come funziona', 'sage'));
  $section_subtitle = sanitize_text_field($section_subtitle ?? '');
  $bg               = $bg               ?? 'cream'; // 'surface' | 'cream' | 'ink'
  $layout           = $layout           ?? 'horizontal'; // 'horizontal' | 'vertical'
  $numbered         = $numbered         ?? true;   // show step numbers

  // Default steps — override by passing $steps from the template
  $steps = $steps ?? [
    [
      'title' => __('Scopri', 'sage'),
      'desc'  => __('Sfoglia la nostra selezione curata di prodotti e servizi premium.', 'sage'),
      'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>',
    ],
    [
      'title' => __('Scegli', 'sage'),
      'desc'  => __('Seleziona le opzioni più adatte alle tue esigenze e aggiungile al carrello.', 'sage'),
      'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>',
    ],
    [
      'title' => __('Ordina', 'sage'),
      'desc'  => __('Completa l\'acquisto in modo sicuro. Pagamento protetto e confermato via email.', 'sage'),
      'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z"/>',
    ],
    [
      'title' => __('Ricevi', 'sage'),
      'desc'  => __('Consegna rapida e tracciabile direttamente a casa tua, con cura e discrezione.', 'sage'),
      'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>',
    ],
  ];

  $bg_class    = match($bg) {
    'ink'   => 'bg-ink',
    'surface' => 'bg-surface',
    default => 'bg-cream',
  };
  $title_class = $bg === 'ink' ? 'text-white'   : 'text-ink';
  $sub_class   = $bg === 'ink' ? 'text-white/60' : 'text-muted';
  $label_class = $bg === 'ink' ? 'text-primary'    : 'text-muted';
  $step_count  = count($steps);

  $grid_class  = match(true) {
    $layout === 'vertical'  => 'grid-cols-1 max-w-2xl mx-auto',
    $step_count <= 2        => 'grid-cols-1 sm:grid-cols-2',
    $step_count === 3       => 'grid-cols-1 sm:grid-cols-3',
    default                 => 'grid-cols-2 lg:grid-cols-4',
  };
@endphp

<section
  id="{{ $section_id ?? 'section-how-it-works' }}"
  class="section {{ $bg_class }}"
  aria-label="{{ strip_tags($section_title) }}"
>
  <div class="container">

    {{-- Header --}}
    @if($section_label || $section_title)
      <div class="text-center mb-16">
        @if($section_label)
          <span class="section-label {{ $label_class }}" data-scroll="fade">{{ $section_label }}</span>
        @endif
        <h2 class="section-title {{ $title_class }}" data-scroll="text-reveal">{!! $section_title !!}</h2>
        @if($section_subtitle)
          <p class="section-subtitle mx-auto mt-4 {{ $sub_class }}" data-scroll="slide-up">{{ $section_subtitle }}</p>
        @endif
      </div>
    @endif

    {{-- Steps --}}
    <ol
      class="grid {{ $grid_class }} {{ $layout === 'horizontal' ? 'gap-0' : 'gap-12' }}"
      role="list"
      data-scroll="stagger"
    >
      @foreach($steps as $index => $step)
        @php
          $num       = $index + 1;
          $is_last   = $index === $step_count - 1;
          $icon_path = $step['icon'] ?? null;
          $title     = $step['title'] ?? '';
          $desc      = $step['desc']  ?? '';
        @endphp

        <li
          class="process-step {{ $layout === 'horizontal' ? 'process-step--h' : 'process-step--v' }} {{ $bg === 'ink' ? 'process-step--dark' : '' }}"
          data-scroll-item
        >
          {{-- Connector line (horizontal layout only, not on last item) --}}
          @if($layout === 'horizontal' && !$is_last)
            <span class="process-step__line {{ $bg === 'ink' ? 'bg-white/10' : 'bg-border' }}" aria-hidden="true"></span>
          @endif

          {{-- Icon / number --}}
          <div class="process-step__icon-wrap {{ $bg === 'ink' ? 'border-white/20' : 'border-border' }}">
            @if($icon_path)
              <x-icons.path
                :path="$icon_path"
                class="size-5 text-primary"
                stroke-width="1.5"
              />
            @else
              <span class="process-step__num text-primary"
                    aria-hidden="{{ $numbered ? 'false' : 'true' }}">
                {{ $num }}
              </span>
            @endif
          </div>

          {{-- Text --}}
          <div class="process-step__body">
            @if($numbered && $icon_path)
              <span class="process-step__counter {{ $bg === 'ink' ? 'text-white/20' : 'text-border' }}" aria-hidden="true">
                {{ str_pad($num, 2, '0', STR_PAD_LEFT) }}
              </span>
            @endif
            <h3 class="process-step__title {{ $bg === 'ink' ? 'text-white' : 'text-ink' }}">{{ $title }}</h3>
            @if($desc)
              <p class="process-step__desc {{ $bg === 'ink' ? 'text-white/50' : 'text-muted' }}">{{ $desc }}</p>
            @endif
          </div>

        </li>
      @endforeach
    </ol>

  </div>
</section>
