@php
  /**
   * Post Card partial — unifica blog-posts e related-posts.
   *
   * @param WP_Post $post
   * @param string  $bg            'surface' | 'cream' | 'ink'  (default: 'surface')
   * @param bool    $show_excerpt  (default: true)
   * @param bool    $show_read_time (default: true)
   * @param bool    $show_date     (default: true)
   * @param string  $aspect        Tailwind aspect class (default: '16/9')
   * @param string  $image_size    WP image size (default: 'large')
   */
  $pid        = $post->ID;
  $thumb_id   = get_post_thumbnail_id($pid);
  $thumb_alt  = $thumb_id ? esc_attr(get_post_meta($thumb_id, '_wp_attachment_image_alt', true)) : '';
  $cats       = get_the_category($pid);
  $cat_name   = $cats ? esc_html($cats[0]->name)                   : '';
  $cat_url    = $cats ? esc_url(get_category_link($cats[0]->term_id)) : '';
  $words      = str_word_count(wp_strip_all_tags($post->post_content));
  $read_min   = max(1, (int) ceil($words / 200));
  $perma      = esc_url(get_permalink($pid));

  $bg             = $bg             ?? 'surface';
  $show_excerpt   = $show_excerpt   ?? true;
  $show_read_time = $show_read_time ?? true;
  $show_date      = $show_date      ?? true;
  $aspect         = $aspect         ?? '16/9';
  $image_size     = $image_size     ?? 'large';

  $excerpt = $show_excerpt
    ? wp_trim_words(get_the_excerpt($post), 22, '…')
    : '';

  $title_class   = $bg === 'ink' ? 'text-white group-hover:text-accent'   : 'text-ink group-hover:text-primary';
  $meta_class    = $bg === 'ink' ? 'text-white/40'   : 'text-muted';
  $excerpt_class = $bg === 'ink' ? 'text-white/50'   : 'text-muted';
  $border_class  = $bg === 'ink' ? 'border-white/10' : 'border-border';
  $cat_class     = $bg === 'ink' ? 'text-accent'     : 'text-primary';
  $cta_class     = $bg === 'ink' ? 'text-white/40'   : 'text-primary';
@endphp

<article class="post-card group flex flex-col" data-scroll-item>

  {{-- Thumbnail --}}
  @if($thumb_id)
    <a href="{{ $perma }}" tabindex="-1" aria-hidden="true"
       class="block overflow-hidden aspect-[{{ $aspect }}] mb-5">
      <x-picture
        :id="(int) $thumb_id"
        :alt="$thumb_alt"
        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
        :size="$image_size"
        sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw"
      />
    </a>
  @endif

  {{-- Meta: categoria + tempo lettura --}}
  @if($cat_name || $show_read_time)
    <div class="flex items-center gap-3 mb-3">
      @if($cat_name)
        <a href="{{ $cat_url }}"
           class="text-[0.65rem] font-semibold tracking-[0.2em] uppercase {{ $cat_class }} hover:opacity-75 transition-opacity">
          {{ $cat_name }}
        </a>
        @if($show_read_time)
          <span class="w-px h-3 bg-border" aria-hidden="true"></span>
        @endif
      @endif
      @if($show_read_time)
        <span class="text-[0.65rem] {{ $meta_class }}">{{ $read_min }}&nbsp;min</span>
      @endif
    </div>
  @endif

  {{-- Titolo --}}
  <h3 class="font-serif text-xl font-light leading-snug mb-3 {{ $title_class }} transition-colors">
    <a href="{{ $perma }}" class="relative after:absolute after:inset-0">
      {!! esc_html(get_the_title($pid)) !!}
    </a>
  </h3>

  {{-- Excerpt --}}
  @if($excerpt)
    <p class="text-sm leading-relaxed line-clamp-3 mb-5 flex-1 {{ $excerpt_class }}">{{ $excerpt }}</p>
  @endif

  {{-- Footer: data + "Leggi →" --}}
  @if($show_date)
    <div class="flex items-center justify-between mt-auto pt-4 border-t {{ $border_class }}">
      <time datetime="{{ get_post_time('c', true, $post) }}" class="text-xs {{ $meta_class }}">
        {{ get_the_date('j M Y', $post) }}
      </time>
      <span class="text-xs font-semibold tracking-wider uppercase {{ $cta_class }}" aria-hidden="true">
        {{ __('Leggi →', 'sage') }}
      </span>
    </div>
  @endif

</article>
