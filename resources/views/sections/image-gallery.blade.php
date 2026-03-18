@php
  $section_label = $section_label ?? '';
  $section_title = $section_title ?? '';
  $bg            = $bg            ?? 'surface';
  $layout        = $layout        ?? 'grid';    // 'grid' | 'masonry' | 'editorial'
  $cols          = $cols          ?? 3;
  $lightbox      = $lightbox      ?? true;
  /**
   * $images — array of attachment IDs or ['id', 'caption'] arrays.
   * If null, tries to pull from current post's ACF gallery field 'gallery_images'.
   */
  $images        = $images        ?? null;

  if (empty($images) && function_exists('get_field')) {
    $acf = get_field('gallery_images');
    $images = is_array($acf) ? array_map(fn($img) => ['id' => (int)($img['ID'] ?? $img), 'caption' => $img['caption'] ?? ''], $acf) : [];
  }
  $images = array_filter((array) $images);

  $bg_class   = match($bg) { 'cream' => 'bg-cream', 'ink' => 'bg-ink', default => 'bg-surface' };
  $label_class = $bg === 'ink' ? 'text-gold' : 'text-muted';
  $title_class = $bg === 'ink' ? 'text-white' : 'text-ink';

  $cols_class = match((int) $cols) {
    2 => 'grid-cols-1 sm:grid-cols-2',
    4 => 'grid-cols-2 lg:grid-cols-4',
    default => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3',
  };
@endphp

@if(!empty($images))
<section
  class="section-luxury {{ $bg_class }}"
  aria-label="{{ $section_title ?: __('Galleria immagini', 'sage') }}"
  @if($lightbox) x-data="imageLightbox()" @endif
>
  <div class="max-w-360 mx-auto px-6 lg:px-10">

    @if($section_label || $section_title)
      <div class="mb-12">
        @if($section_label)
          <span class="section-label {{ $label_class }}" data-scroll="fade">{{ $section_label }}</span>
        @endif
        @if($section_title)
          <h2 class="section-title {{ $title_class }}" data-scroll="text-reveal">{!! $section_title !!}</h2>
        @endif
      </div>
    @endif

    @if($layout === 'editorial')
      {{-- NAP editorial: large left + 2 right stacked --}}
      @php $editorial_chunks = array_chunk($images, 3); @endphp
      @foreach($editorial_chunks as $chunk)
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3" data-scroll="stagger">
          @php $main = $chunk[0]; $rest = array_slice($chunk, 1); @endphp
          @php $main_id = (int)($main['id'] ?? $main); @endphp
          <div
            class="gallery-item overflow-hidden aspect-[3/4] cursor-zoom-in"
            @if($lightbox) @click="open({{ $loop->index * 3 }})" @endif
          >
            <x-picture :id="$main_id" alt="" class="w-full h-full object-cover transition-transform duration-700 hover:scale-105" sizes="(max-width: 640px) 100vw, 50vw" />
          </div>
          <div class="flex flex-col gap-3">
            @foreach($rest as $ri => $img)
              @php $img_id = (int)($img['id'] ?? $img); @endphp
              <div
                class="gallery-item overflow-hidden flex-1 cursor-zoom-in"
                style="min-height: 0"
                @if($lightbox) @click="open({{ $loop->parent->index * 3 + $ri + 1 }})" @endif
              >
                <x-picture :id="$img_id" alt="" class="w-full h-full object-cover transition-transform duration-700 hover:scale-105" sizes="(max-width: 640px) 100vw, 50vw" />
              </div>
            @endforeach
          </div>
        </div>
      @endforeach

    @else
      {{-- Standard grid --}}
      <div class="grid {{ $cols_class }} gap-3" data-scroll="stagger">
        @foreach($images as $idx => $img)
          @php
            $img_id  = (int)($img['id'] ?? $img);
            $caption = $img['caption'] ?? '';
          @endphp
          <figure
            class="gallery-item group overflow-hidden {{ $lightbox ? 'cursor-zoom-in' : '' }}"
            @if($lightbox) @click="open({{ $idx }})" @endif
            data-scroll-item
          >
            <div class="aspect-square overflow-hidden">
              <x-picture
                :id="$img_id"
                :alt="$caption ?: ''"
                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                sizes="(max-width: 640px) 50vw, (max-width: 1024px) 33vw, 25vw"
              />
            </div>
            @if($caption)
              <figcaption class="mt-2 font-sans text-xs {{ $bg === 'ink' ? 'text-white/40' : 'text-muted' }}">
                {{ esc_html($caption) }}
              </figcaption>
            @endif
          </figure>
        @endforeach
      </div>
    @endif

    {{-- Lightbox overlay --}}
    @if($lightbox)
      <div
        x-show="isOpen"
        x-cloak
        x-trap.inert.noscroll="isOpen"
        @keydown.escape.window="close()"
        @keydown.arrow-right.window="next({{ count($images) }})"
        @keydown.arrow-left.window="prev({{ count($images) }})"
        class="fixed inset-0 z-200 bg-black/95 flex items-center justify-center"
        role="dialog"
        aria-modal="true"
        aria-label="{{ __('Lightbox galleria', 'sage') }}"
        style="display:none"
      >
        @php $lb_images = array_map(fn($img) => wp_get_attachment_image_url((int)($img['id'] ?? $img), 'full'), $images); @endphp

        <template x-for="(src, i) in {{ json_encode($lb_images) }}" :key="i">
          <img
            :src="src"
            :alt="'Immagine ' + (i + 1)"
            class="max-h-screen max-w-screen object-contain p-4 lg:p-8"
            x-show="current === i"
            loading="lazy"
            decoding="async"
          >
        </template>

        <button @click="close()" class="absolute top-4 right-5 text-white/50 hover:text-white transition-colors" aria-label="{{ __('Chiudi', 'sage') }}">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
        </button>
        <button @click="prev({{ count($images) }})" class="absolute left-4 top-1/2 -translate-y-1/2 text-white/50 hover:text-white transition-colors" aria-label="{{ __('Precedente', 'sage') }}">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
        </button>
        <button @click="next({{ count($images) }})" class="absolute right-4 top-1/2 -translate-y-1/2 text-white/50 hover:text-white transition-colors" aria-label="{{ __('Successivo', 'sage') }}">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
        </button>

        <span class="absolute bottom-4 left-1/2 -translate-x-1/2 font-sans text-xs text-white/30" aria-live="polite">
          <span x-text="current + 1"></span> / {{ count($images) }}
        </span>
      </div>
    @endif

  </div>
</section>

@if($lightbox)
<script>
document.addEventListener('alpine:init', () => {
  Alpine.data('imageLightbox', () => ({
    isOpen: false, current: 0,
    open(i)  { this.current = i; this.isOpen = true },
    close()  { this.isOpen = false },
    next(n)  { this.current = (this.current + 1) % n },
    prev(n)  { this.current = (this.current - 1 + n) % n },
  }))
})
</script>
@endif
@endif
