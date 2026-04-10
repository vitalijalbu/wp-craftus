@php
  // Parameters
  $section_label    = $section_label ?? '';
  $section_title    = $section_title ?? '';
  $bg               = $bg            ?? 'cream'; // 'surface' | 'cream' | 'ink'
  $dividers         = $dividers      ?? true;    // show vertical dividers between stats

  // Default stats — override via $stats parameter
  $stats = $stats ?? [
    [
      'number'  => 5000,
      'suffix'  => '+',
      'prefix'  => '',
      'label'   => __('Clienti soddisfatti', 'sage'),
      'decimals' => 0,
    ],
    [
      'number'  => 1200,
      'suffix'  => '+',
      'prefix'  => '',
      'label'   => __('Prodotti disponibili', 'sage'),
      'decimals' => 0,
    ],
    [
      'number'  => 10,
      'suffix'  => '+',
      'prefix'  => '',
      'label'   => __('Anni di esperienza', 'sage'),
      'decimals' => 0,
    ],
    [
      'number'  => 99,
      'suffix'  => '%',
      'prefix'  => '',
      'label'   => __('Recensioni positive', 'sage'),
      'decimals' => 0,
    ],
  ];

  $bg_class = match($bg) {
    'ink'    => 'bg-ink',
    'surface' => 'bg-surface',
    default  => 'bg-cream',
  };
  $border_class  = $bg === 'ink' ? 'border-white/10' : 'border-border';
  $number_class  = $bg === 'ink' ? 'text-white'       : 'text-ink';
  $label_class   = $bg === 'ink' ? 'text-white/50'    : 'text-muted';
  $section_title_class = $bg === 'ink' ? 'text-white' : 'text-ink';
  $section_label_class = $bg === 'ink' ? 'text-primary'  : 'text-muted';
  $grid_cols           = match(count($stats)) {
    2       => 'grid-cols-2',
    3       => 'grid-cols-2 lg:grid-cols-3',
    default => 'grid-cols-2 lg:grid-cols-4',
  };
@endphp

<section id="{{ $section_id ?? 'section-stats' }}" class="section-luxury {{ $bg_class }}" aria-label="{{ $section_title ?: __('Statistiche', 'sage') }}">
  <div class="container">

    {{-- Optional header --}}
    @if($section_label || $section_title)
      <div class="mb-14">
        @include('partials.section-header', ['bg' => $bg, 'align' => 'center'])
      </div>
    @endif

    {{-- Stats row --}}
    <div
      class="grid {{ $grid_cols }} {{ $dividers ? 'divide-y-2 lg:divide-y-0 lg:divide-x divide-' . ($bg === 'ink' ? 'white/10' : 'border') : '' }}"
      data-scroll="stagger"
      role="list"
    >
      @foreach($stats as $stat)
        <div
          class="stat-item px-6 lg:px-10 py-8 lg:py-0 text-center"
          data-scroll-item
          role="listitem"
        >
          <div
            class="stat-item__number {{ $number_class }}"
            data-counter="{{ $stat['number'] ?? 0 }}"
            data-counter-suffix="{{ $stat['suffix'] ?? '' }}"
            data-counter-prefix="{{ $stat['prefix'] ?? '' }}"
            data-counter-decimals="{{ $stat['decimals'] ?? 0 }}"
            aria-label="{{ ($stat['prefix'] ?? '') . ($stat['number'] ?? '') . ($stat['suffix'] ?? '') }}"
          >
            {{-- Initial render (replaced by GSAP counter) --}}
            {{ ($stat['prefix'] ?? '') }}0{{ ($stat['suffix'] ?? '') }}
          </div>

          {{-- Primary accent line --}}
          <div class="w-6 h-px bg-primary mx-auto my-3" aria-hidden="true"></div>

          <p class="stat-item__label {{ $label_class }}">{{ $stat['label'] ?? '' }}</p>
        </div>
      @endforeach
    </div>

  </div>
</section>
