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
      'icon' => '<svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.746 3.746 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z"/></svg>',
      'title' => __('Qualità garantita', 'sage'),
      'desc'  => __('Selezioniamo solo i migliori prodotti, testati e approvati da veterinari esperti.', 'sage'),
    ],
    [
      'icon' => '<svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>',
      'title' => __('Consegna rapida', 'sage'),
      'desc'  => __('Spedizioni in tutta Italia in 24/48 ore. Ricezione garantita entro 2 giorni lavorativi.', 'sage'),
    ],
    [
      'icon' => '<svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/></svg>',
      'title' => __('Amore per gli animali', 'sage'),
      'desc'  => __('Siamo appassionati di animali domestici. Ogni prodotto è scelto con il cuore.', 'sage'),
    ],
    [
      'icon' => '<svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155"/></svg>',
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
  $label_class = 'text-gold';
  $desc_class  = $bg === 'ink' ? 'text-white/60' : 'text-muted';

  $cols_class = match((int) $cols) {
    2 => 'grid-cols-1 sm:grid-cols-2',
    4 => 'grid-cols-1 sm:grid-cols-2 xl:grid-cols-4',
    default => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3',
  };
@endphp

<section class="section-luxury {{ $bg_class }}" aria-label="{{ $section_title }}">
  <div class="max-w-360 mx-auto px-6 lg:px-10">

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
            {!! $feature['icon'] ?? '' !!}
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
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
              </svg>
            </a>
          @endif
        </div>
      @endforeach
    </div>

  </div>
</section>
