@php
  // Parameters
  $section_label    = $section_label    ?? '';
  $section_title    = $section_title    ?? __('Perché sceglierci', 'sage');
  $section_subtitle = $section_subtitle ?? '';
  $cols             = $cols             ?? 3; // 2 | 3 | 4
  $bg               = $bg               ?? 'surface'; // 'surface' | 'cream' | 'ink'
  $style            = $style            ?? 'boxed'; // 'boxed' | 'minimal'

  // Static default features — override via $features parameter
  $features = $features ?? [
    [
      'icon' => 'check-badge',
      'title' => __('Qualità garantita', 'sage'),
      'desc'  => __('Selezioniamo solo i migliori prodotti, testati e approvati da veterinari esperti.', 'sage'),
    ],
    [
      'icon' => 'truck',
      'title' => __('Consegna rapida', 'sage'),
      'desc'  => __('Spedizioni in tutta Italia in 24/48 ore. Ricezione garantita entro 2 giorni lavorativi.', 'sage'),
    ],
    [
      'icon' => 'heart',
      'title' => __('Amore per gli animali', 'sage'),
      'desc'  => __('Siamo appassionati di animali domestici. Ogni prodotto è scelto con il cuore.', 'sage'),
    ],
    [
      'icon' => 'message-chat',
      'title' => __('Supporto dedicato', 'sage'),
      'desc'  => __('Il nostro team è disponibile per consigliarti sui prodotti più adatti al tuo animale.', 'sage'),
    ],
  ];

  $bg_class = match($bg) {
    'cream' => 'bg-cream',
    'ink'   => 'bg-ink',
    default => 'bg-surface',
  };
  $text_class  = $bg === 'ink' ? 'text-white'   : 'text-ink';
  $label_class = $bg === 'ink' ? 'text-primary'    : 'text-muted';
  $desc_class  = $bg === 'ink' ? 'text-white/60' : 'text-muted';

  $cols_class = match((int) $cols) {
    2 => 'grid-cols-1 sm:grid-cols-2',
    4 => 'grid-cols-1 sm:grid-cols-2 xl:grid-cols-4',
    default => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3',
  };
@endphp

<section id="{{ $section_id ?? 'section-features' }}" class="section {{ $bg_class }}" aria-label="{{ $section_title }}">
  <div class="container">

    {{-- Header --}}
    @if($section_label || $section_title)
      <div class="text-center mb-14">
        @if($section_label)
          <span class="section-label {{ $label_class }}" data-scroll="fade">{{ $section_label }}</span>
        @endif
        <h2 class="section-title {{ $text_class }}" data-scroll="text-reveal">{!! $section_title !!}</h2>
        @if($section_subtitle)
          <p class="section-subtitle mx-auto mt-4 {{ $desc_class }}" data-scroll="slide-up">{{ $section_subtitle }}</p>
        @endif
      </div>
    @endif

    {{-- Features grid --}}
    <div
      class="grid {{ $cols_class }} gap-6"
      data-scroll="stagger"
    >
      @foreach($features as $feature)
        <div
          class="{{ $style === 'boxed' ? 'feature-item' : 'py-8' }} {{ $bg === 'ink' ? 'border-white/10' : '' }}"
          data-scroll-item
        >
          {{-- Icon --}}
          <div class="feature-item__icon" aria-hidden="true">
            <x-dynamic-component :component="'icons.' . ($feature['icon'] ?? 'check-badge')" />
          </div>

          {{-- Title --}}
          <h3 class="feature-item__title {{ $text_class }}">{{ $feature['title'] ?? '' }}</h3>

          {{-- Description --}}
          <p class="feature-item__desc {{ $desc_class }}">{{ $feature['desc'] ?? '' }}</p>

          {{-- Optional CTA --}}
          @if(!empty($feature['cta_label']) && !empty($feature['cta_url']))
            <a
              href="{{ $feature['cta_url'] }}"
              class="btn-ghost mt-4 {{ $bg === 'ink' ? 'text-white/60 border-white/40 hover:text-white hover:border-white' : '' }}"
            >
              {{ $feature['cta_label'] }}
              <x-icons.arrow-right class="size-4" />
            </a>
          @endif
        </div>
      @endforeach
    </div>

  </div>
</section>
