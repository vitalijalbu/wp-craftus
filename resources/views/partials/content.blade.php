@php
  $post_id    = get_the_ID();
  $cat        = get_the_category($post_id);
  $cat_name   = $cat ? esc_html($cat[0]->name) : '';
  $cat_url    = $cat ? esc_url(get_category_link($cat[0]->term_id)) : '';
  $thumb_id   = get_post_thumbnail_id($post_id);
  $thumb_url  = $thumb_id ? wp_get_attachment_image_url($thumb_id, 'large') : '';
  $thumb_alt  = $thumb_id ? esc_attr(get_post_meta($thumb_id, '_wp_attachment_image_alt', true)) : '';
  $words      = str_word_count(wp_strip_all_tags(get_the_content()));
  $read_min   = max(1, (int) ceil($words / 200));
@endphp

<article @php(post_class('group flex flex-col h-full'))>

  {{-- Thumbnail --}}
  @if($thumb_id)
    <a href="{{ get_permalink() }}" tabindex="-1" aria-hidden="true" class="block overflow-hidden aspect-[16/9] mb-5">
      <x-picture
        :id="$thumb_id"
        :alt="$thumb_alt"
        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
        size="large"
        sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw"
      />
    </a>
  @endif

  <div class="flex flex-col flex-1">

    {{-- Category + reading time --}}
    <div class="flex items-center gap-3 mb-3">
      @if($cat_name)
        <a href="{{ $cat_url }}" class="text-[0.65rem] font-semibold tracking-[0.2em] uppercase text-primary hover:text-primary transition-colors">
          {{ $cat_name }}
        </a>
        <span class="w-px h-3 bg-border" aria-hidden="true"></span>
      @endif
      <span class="text-[0.65rem] text-muted">
        {{ $read_min }}&nbsp;{{ __('min', 'sage') }}
      </span>
    </div>

    {{-- Title --}}
    <h2 class="font-serif text-xl font-light text-ink leading-snug mb-3 group-hover:text-primary transition-colors">
      <a href="{{ get_permalink() }}" class="after:absolute after:inset-0 relative">
        {!! $title !!}
      </a>
    </h2>

    {{-- Excerpt --}}
    <p class="text-sm text-muted leading-relaxed line-clamp-3 mb-5 flex-1">
      {!! wp_trim_words(get_the_excerpt(), 22, '…') !!}
    </p>

    {{-- Footer: date + read more --}}
    <div class="flex items-center justify-between mt-auto pt-4 border-t border-border">
      <time datetime="{{ get_post_time('c', true) }}" class="text-xs text-muted">
        {{ get_the_date('j M Y') }}
      </time>
      <span class="text-xs font-semibold tracking-wider uppercase text-primary" aria-hidden="true">
        {{ __('Leggi →', 'sage') }}
      </span>
    </div>

  </div>

</article>
