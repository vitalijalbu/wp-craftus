{{--
  Empty State component — reusable across 404, wishlist, search, etc.

  @param string       $icon       Icon component name (e.g. 'icons.heart') — optional
  @param string       $code       Large decorative text (e.g. '404') — optional
  @param string       $title      Heading text
  @param string       $message    Description text — optional
  @param array        $buttons    Array of ['url' => '...', 'label' => '...', 'style' => 'primary|outline|ghost'] — optional
  @param bool         $showSearch Show search form — optional (default: false)
--}}

@props([
  'icon'       => '',
  'code'       => '',
  'title'      => __('Nessun risultato', 'sage'),
  'message'    => '',
  'buttons'    => [],
  'showSearch' => false,
])

<div {{ $attributes->merge(['class' => 'empty-state flex flex-col items-center justify-center text-center py-16 lg:py-24']) }}>

  {{-- Decorative code (e.g. 404) --}}
  @if($code)
    <p class="text-[clamp(6rem,20vw,14rem)] font-light leading-none text-border select-none" aria-hidden="true">
      {{ $code }}
    </p>
    <div class="w-12 h-px bg-primary mx-auto my-8" aria-hidden="true"></div>
  @endif

  {{-- Icon --}}
  @if($icon)
    <div class="mb-6 text-border">
      <x-dynamic-component :component="$icon" class="w-16 h-16 mx-auto" stroke-width="1" />
    </div>
  @endif

  {{-- Title --}}
  <h2 class="text-[clamp(1.5rem,3.5vw,2.5rem)] font-light text-ink mb-3 leading-tight">
    {{ $title }}
  </h2>

  {{-- Message --}}
  @if($message)
    <p class="text-base text-muted max-w-md mx-auto mb-8 leading-relaxed">
      {{ $message }}
    </p>
  @endif

  {{-- Search form --}}
  @if($showSearch)
    <form role="search" method="get" action="{{ home_url('/') }}" class="flex max-w-sm mx-auto mb-8 gap-0 w-full">
      <label for="search-empty-state" class="sr-only">{{ __('Cerca nel sito', 'sage') }}</label>
      <input
        id="search-empty-state"
        type="search"
        name="s"
        value="{{ get_search_query() }}"
        placeholder="{{ __('Cerca nel sito…', 'sage') }}"
        class="flex-1 border border-border border-r-0 px-4 py-3 text-sm text-ink bg-white placeholder-muted focus:outline-none focus:border-primary transition-colors"
      >
      <button type="submit" class="btn-secondary">{{ __('Cerca', 'sage') }}</button>
    </form>
  @endif

  {{-- Buttons --}}
  @if(!empty($buttons))
    <nav aria-label="{{ __('Link utili', 'sage') }}" class="flex flex-wrap justify-center gap-4">
      @foreach($buttons as $btn)
        @php
          $style = $btn['style'] ?? 'primary';
          $class = match($style) {
            'outline' => 'btn-outline',
            'ghost'   => 'btn-ghost',
            default   => 'btn-primary',
          };
        @endphp
        <a href="{{ esc_url($btn['url']) }}" class="{{ $class }}">
          {{ $btn['label'] }}
        </a>
      @endforeach
    </nav>
  @endif

  {{-- Slot for custom content --}}
  {{ $slot }}
</div>
