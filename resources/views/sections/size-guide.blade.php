@php
  /**
   * Size guide section — styled table + optional intro + accordion.
   *
   * @var string $section_label
   * @var string $heading
   * @var string $subtext
   * @var string $bg              'surface' | 'cream' | 'ink'
   * @var array  $tables          Array of table groups:
   *                              [['title' => '', 'headers' => [], 'rows' => [[]]]]
   * @var string $note            Footer note (e.g. conversion hints)
   * @var bool   $accordion       Wrap multiple tables in an accordion (default true)
   * @var string $measure_url     Optional link to "how to measure" guide
   * @var string $measure_label   CTA label for measure guide
   */
  $section_label = $section_label ?? '';
  $heading       = $heading       ?? __('Guida alle taglie', 'sage');
  $subtext       = $subtext       ?? '';
  $bg            = $bg            ?? 'surface';
  $note          = $note          ?? '';
  $accordion     = $accordion     ?? true;
  $measure_url   = $measure_url   ?? '';
  $measure_label = $measure_label ?? __('Come prendere le misure', 'sage');

  // Default single table example — override via $tables variable
  $tables = $tables ?? [
    [
      'title'   => __('Abbigliamento donna', 'sage'),
      'headers' => ['IT', 'EU', 'UK', 'US', 'Busto (cm)', 'Vita (cm)', 'Fianchi (cm)'],
      'rows'    => [
        ['36', '32', '6',  'XS', '80–83',  '60–63',  '87–90'],
        ['38', '34', '8',  'S',  '84–87',  '64–67',  '91–94'],
        ['40', '36', '10', 'M',  '88–91',  '68–71',  '95–98'],
        ['42', '38', '12', 'M/L','92–96',  '72–76',  '99–103'],
        ['44', '40', '14', 'L',  '97–101', '77–81',  '104–108'],
        ['46', '42', '16', 'XL', '102–107','82–87',  '109–114'],
        ['48', '44', '18', 'XXL','108–113','88–93',  '115–120'],
      ],
    ],
  ];

  $bg_class    = match($bg) { 'cream' => 'bg-cream', 'ink' => 'bg-ink', default => 'bg-surface' };
  $title_class = $bg === 'ink' ? 'text-white'    : 'text-ink';
  $sub_class   = $bg === 'ink' ? 'text-white/60' : 'text-muted';
  $label_class = $bg === 'ink' ? 'text-primary'     : 'text-muted';
@endphp

<section
  id="{{ $section_id ?? 'section-size-guide' }}"
  class="section {{ $bg_class }}"
  aria-label="{{ strip_tags($heading) }}"
  @if($accordion && count($tables) > 1) x-data="{ openTab: 0 }" @endif
>
  <div class="container">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-12">
      <div>
        @if($section_label)
          <span class="section-label {{ $label_class }}" data-scroll="fade">{{ $section_label }}</span>
        @endif
        <h2 class="section-title {{ $title_class }}" data-scroll="text-reveal">{!! $heading !!}</h2>
        @if($subtext)
          <p class="section-subtitle mt-4 max-w-2xl {{ $sub_class }}" data-scroll="slide-up">{{ $subtext }}</p>
        @endif
      </div>
      @if($measure_url)
        <a href="{{ esc_url($measure_url) }}" class="btn-ghost shrink-0 self-start {{ $bg === 'ink' ? 'text-white/50 border-white/20 hover:text-white hover:border-white' : '' }}" data-scroll="fade">
          {{ $measure_label }}
        </a>
      @endif
    </div>

    @if($accordion && count($tables) > 1)
      {{-- Accordion tabs for multiple tables --}}
      <div class="space-y-3" data-scroll="fade">
        @foreach($tables as $ti => $table)
          <div class="size-guide-accordion {{ $bg === 'ink' ? 'border-white/10' : 'border-border' }}">
            <button
              type="button"
              class="size-guide-accordion__trigger {{ $bg === 'ink' ? 'text-white/80 hover:text-white' : 'text-ink' }}"
              @click="openTab = openTab === {{ $ti }} ? -1 : {{ $ti }}"
              :aria-expanded="openTab === {{ $ti }}"
              aria-controls="sg-table-{{ $ti }}"
            >
              <span class="text-base font-light">{{ esc_html($table['title'] ?? '') }}</span>
              <x-icons.chevron-down
                class="size-4 transition-transform duration-300 shrink-0"
                ::class="openTab === {{ $ti }} ? 'rotate-180' : ''"
              />
            </button>
            <div
              id="sg-table-{{ $ti }}"
              x-show="openTab === {{ $ti }}"
              x-collapse
              class="size-guide-accordion__body"
            >
              @include('sections.size-guide--table', ['table' => $table, 'bg' => $bg])
            </div>
          </div>
        @endforeach
      </div>
    @else
      {{-- Single table or non-accordion layout --}}
      @foreach($tables as $table)
        @if(count($tables) > 1 && !empty($table['title']))
          <h3 class="text-xl font-light mb-4 {{ $title_class }}">{{ esc_html($table['title']) }}</h3>
        @endif
        <div class="size-guide-table-wrap mb-8" data-scroll="fade">
          <table class="size-guide-table {{ $bg === 'ink' ? 'size-guide-table--dark' : '' }}">
            @if(!empty($table['headers']))
              <thead>
                <tr>
                  @foreach($table['headers'] as $header)
                    <th scope="col">{{ esc_html($header) }}</th>
                  @endforeach
                </tr>
              </thead>
            @endif
            <tbody>
              @foreach($table['rows'] as $row)
                <tr>
                  @foreach($row as $cell)
                    <td>{{ esc_html($cell) }}</td>
                  @endforeach
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endforeach
    @endif

    @if($note)
      <p class="mt-6 text-xs leading-relaxed {{ $sub_class }}" data-scroll="fade">{!! wp_kses_post($note) !!}</p>
    @endif

  </div>
</section>
