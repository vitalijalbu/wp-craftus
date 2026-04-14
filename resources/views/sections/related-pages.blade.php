@php
  // Parameters
  $section_label    = $section_label    ?? '';
  $section_title    = $section_title    ?? __('Scopri anche', 'sage');
  $section_subtitle = $section_subtitle ?? '';
  $bg               = $bg               ?? 'cream'; // 'surface' | 'cream' | 'ink'
  $number           = $number           ?? 3;
  $auto             = $auto             ?? true;    // auto-fetch sibling pages
  $parent_id        = $parent_id        ?? null;    // override parent page ID
  $pages            = $pages            ?? null;    // manual: [['id' => X], ...]

  // Resolve pages list
  if (empty($pages)) {
    $current_id  = get_the_ID();
    $current_pid = wp_get_post_parent_id($current_id);

    // If the current page has a parent, fetch siblings; otherwise fetch children
    if ($current_pid) {
      $lookup_id = $parent_id ?? $current_pid;
    } else {
      $lookup_id = $parent_id ?? $current_id;
    }

    $page_args = [
      'post_type'      => 'page',
      'posts_per_page' => (int) $number,
      'post_status'    => 'publish',
      'post__not_in'   => [$current_id],
      'orderby'        => 'menu_order',
      'order'          => 'ASC',
    ];

    if ($lookup_id) {
      $page_args['post_parent'] = (int) $lookup_id;
    }

    $pages_query = new WP_Query($page_args);
    $pages_raw   = $pages_query->posts ?? [];
    wp_reset_postdata();

    $pages = array_map(fn($p) => ['id' => $p->ID], $pages_raw);
  }

  $bg_class    = match($bg) {
    'ink'   => 'bg-ink',
    'surface' => 'bg-surface',
    default => 'bg-cream',
  };
  $title_class = $bg === 'ink' ? 'text-white'   : 'text-ink';
  $sub_class   = $bg === 'ink' ? 'text-white/60' : 'text-muted';
  $label_class = $bg === 'ink' ? 'text-primary'    : 'text-muted';

  $cols_class = match(count($pages)) {
    1       => 'grid-cols-1 max-w-lg mx-auto',
    2       => 'grid-cols-1 sm:grid-cols-2',
    default => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3',
  };
@endphp

@if(!empty($pages))
<section
  id="{{ $section_id ?? 'section-related' }}"
  class="section {{ $bg_class }}"
  aria-label="{{ strip_tags($section_title) }}"
>
  <div class="container">

    {{-- Header --}}
    @if($section_label || $section_title)
      <div class="mb-14">
        @include('partials.section-header', ['bg' => $bg])
      </div>
    @endif

    {{-- Pages grid --}}
    <div
      class="grid {{ $cols_class }} gap-8 lg:gap-10"
      data-scroll="stagger"
    >
      @foreach($pages as $page_item)
        @php
          $pid       = (int) ($page_item['id'] ?? 0);
          if (!$pid) continue;
          $page_obj  = get_post($pid);
          if (!$page_obj || $page_obj->post_status !== 'publish') continue;

          $thumb_id  = get_post_thumbnail_id($pid);
          $thumb_alt = $thumb_id
            ? esc_attr(get_post_meta($thumb_id, '_wp_attachment_image_alt', true))
            : esc_attr(get_the_title($pid));
          $perma     = esc_url(get_permalink($pid));
          $excerpt   = wp_trim_words(
            get_the_excerpt($page_obj) ?: wp_strip_all_tags($page_obj->post_content),
            18,
            '…'
          );
          // Optional manual overrides per item
          $label     = $page_item['label']   ?? '';
          $cta_text  = $page_item['cta']     ?? __('Scopri', 'sage');
        @endphp

        <article
          class="related-page-card group flex flex-col {{ $bg === 'ink' ? 'related-page-card--dark' : '' }}"
          data-scroll-item
        >
          {{-- Thumbnail --}}
          @if($thumb_id)
            <a href="{{ $perma }}" tabindex="-1" aria-hidden="true"
               class="block overflow-hidden aspect-4/3 mb-0">
              <x-picture
                :id="(int) $thumb_id"
                :alt="$thumb_alt"
                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                size="large"
                sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw"
              />
            </a>
          @endif

          {{-- Body --}}
          <div class="related-page-card__body flex flex-col flex-1">
            @if($label)
              <span class="text-[0.65rem] font-semibold tracking-[0.2em] uppercase mb-2 {{ $bg === 'ink' ? 'text-primary' : 'text-muted' }}">
                {{ $label }}
              </span>
            @endif

            <h3 class="font-serif text-xl font-light leading-snug mb-3 {{ $bg === 'ink' ? 'text-white group-hover:text-primary' : 'text-ink group-hover:text-primary' }} transition-colors">
              <a href="{{ $perma }}" class="relative after:absolute after:inset-0">
                {!! esc_html(get_the_title($pid)) !!}
              </a>
            </h3>

            @if($excerpt)
              <p class="text-sm leading-relaxed line-clamp-3 mb-6 flex-1 {{ $bg === 'ink' ? 'text-white/50' : 'text-muted' }}">
                {{ $excerpt }}
              </p>
            @endif

            <a
              href="{{ $perma }}"
              class="related-page-card__cta self-start text-xs font-semibold tracking-[0.15em] uppercase pb-0.5 border-b transition-colors
                {{ $bg === 'ink'
                  ? 'text-white/60 border-white/20 hover:text-white hover:border-white'
                  : 'text-primary border-primary/40 hover:border-primary' }}"
              aria-hidden="true"
              tabindex="-1"
            >
              {{ $cta_text }}
            </a>
          </div>

        </article>
      @endforeach
    </div>

  </div>
</section>
@endif
