@php
  // Parameters
  $section_label    = $section_label    ?? '';
  $section_title    = $section_title    ?? __('Dal blog', 'sage');
  $section_subtitle = $section_subtitle ?? '';
  $bg               = $bg               ?? 'surface'; // 'surface' | 'cream' | 'ink'
  $number           = $number           ?? 3;
  $cols             = $cols             ?? 3;   // 2 | 3
  $category         = $category         ?? '';  // category slug
  $cta_label        = $cta_label        ?? __('Tutti gli articoli', 'sage');
  $cta_url          = $cta_url          ?? get_permalink(get_option('page_for_posts')) ?: '/blog';

  // Query posts
  $query_args = [
    'post_type'      => 'post',
    'posts_per_page' => (int) $number,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
    'ignore_sticky_posts' => true,
  ];
  if ($category) {
    $query_args['category_name'] = $category;
  }
  $posts_query = new WP_Query($query_args);
  $posts_list  = $posts_query->posts ?? [];

  $bg_class    = match($bg) {
    'cream' => 'bg-cream',
    'ink'   => 'bg-ink',
    default => 'bg-surface',
  };
  $title_class = $bg === 'ink' ? 'text-white'   : 'text-ink';
  $sub_class   = $bg === 'ink' ? 'text-white/60' : 'text-muted';
  $label_class = $bg === 'ink' ? 'text-accent'    : 'text-muted';

  $cols_class = match((int) $cols) {
    2 => 'grid-cols-1 sm:grid-cols-2',
    default => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3',
  };
@endphp

@if(!empty($posts_list))
<section
  id="{{ $section_id ?? 'section-blog' }}"
  class="section-luxury {{ $bg_class }}"
  aria-label="{{ strip_tags($section_title) }}"
>
  <div class="max-w-360 mx-auto px-6 lg:px-10">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-14">
      <div>
        @if($section_label)
          <span class="section-label {{ $label_class }}" data-scroll="fade">{{ $section_label }}</span>
        @endif
        <h2 class="section-title {{ $title_class }}" data-scroll="text-reveal">{!! $section_title !!}</h2>
        @if($section_subtitle)
          <p class="section-subtitle mt-4 {{ $sub_class }}" data-scroll="slide-up">{{ $section_subtitle }}</p>
        @endif
      </div>
      @if($cta_label && $cta_url)
        <a
          href="{{ $cta_url }}"
          class="btn-ghost shrink-0 self-start md:self-auto {{ $bg === 'ink' ? 'text-white/60 border-white/40 hover:text-white hover:border-white' : '' }}"
          data-scroll="fade"
        >
          {{ $cta_label }}
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
          </svg>
        </a>
      @endif
    </div>

    {{-- Posts grid --}}
    <div
      class="grid {{ $cols_class }} gap-8 lg:gap-10"
      data-scroll="stagger"
    >
      @foreach($posts_list as $post)
        @php
          $pid       = $post->ID;
          $thumb_id  = get_post_thumbnail_id($pid);
          $thumb_alt = $thumb_id ? esc_attr(get_post_meta($thumb_id, '_wp_attachment_image_alt', true)) : '';
          $cats      = get_the_category($pid);
          $cat_name  = $cats ? esc_html($cats[0]->name) : '';
          $cat_url   = $cats ? esc_url(get_category_link($cats[0]->term_id)) : '';
          $words     = str_word_count(wp_strip_all_tags($post->post_content));
          $read_min  = max(1, (int) ceil($words / 200));
          $perma     = esc_url(get_permalink($pid));
          $excerpt   = wp_trim_words(get_the_excerpt($post), 22, '…');
        @endphp

        <article
          class="blog-card group flex flex-col"
          data-scroll-item
        >
          {{-- Thumbnail --}}
          @if($thumb_id)
            <a href="{{ $perma }}" tabindex="-1" aria-hidden="true"
               class="block overflow-hidden aspect-[16/9] mb-5">
              <x-picture
                :id="(int) $thumb_id"
                :alt="$thumb_alt"
                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                size="large"
                sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw"
              />
            </a>
          @endif

          {{-- Meta --}}
          <div class="flex items-center gap-3 mb-3">
            @if($cat_name)
              <a href="{{ $cat_url }}" class="text-[0.65rem] font-semibold tracking-[0.2em] uppercase text-primary hover:text-ink transition-colors">
                {{ $cat_name }}
              </a>
              <span class="w-px h-3 bg-border" aria-hidden="true"></span>
            @endif
            <span class="text-[0.65rem] {{ $bg === 'ink' ? 'text-white/40' : 'text-muted' }}">
              {{ $read_min }}&nbsp;min
            </span>
          </div>

          {{-- Title --}}
          <h3 class="font-serif text-xl font-light leading-snug mb-3 {{ $bg === 'ink' ? 'text-white group-hover:text-accent' : 'text-ink group-hover:text-primary' }} transition-colors">
            <a href="{{ $perma }}" class="relative after:absolute after:inset-0">
              {!! esc_html(get_the_title($pid)) !!}
            </a>
          </h3>

          {{-- Excerpt --}}
          @if($excerpt)
            <p class="text-sm leading-relaxed line-clamp-3 mb-5 flex-1 {{ $bg === 'ink' ? 'text-white/50' : 'text-muted' }}">
              {{ $excerpt }}
            </p>
          @endif

          {{-- Footer --}}
          <div class="flex items-center justify-between mt-auto pt-4 border-t {{ $bg === 'ink' ? 'border-white/10' : 'border-border' }}">
            <time
              datetime="{{ get_post_time('c', true, $post) }}"
              class="text-xs {{ $bg === 'ink' ? 'text-white/40' : 'text-muted' }}"
            >
              {{ get_the_date('j M Y', $post) }}
            </time>
            <span class="text-xs font-semibold tracking-wider uppercase {{ $bg === 'ink' ? 'text-white/40' : 'text-primary' }}" aria-hidden="true">
              {{ __('Leggi →', 'sage') }}
            </span>
          </div>

        </article>
      @endforeach
    </div>

  </div>
</section>
@php wp_reset_postdata(); @endphp
@endif
