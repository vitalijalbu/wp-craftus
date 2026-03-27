@php
  $section_label = $section_label ?? '';
  $section_title = $section_title ?? __('Portfolio', 'sage');
  $section_sub   = $section_sub   ?? '';
  $bg            = $bg            ?? 'surface';
  $number        = $number        ?? 6;
  $category      = $category      ?? ''; // portfolio_category slug
  $cols          = $cols          ?? 3;
  $show_filters  = $show_filters  ?? false;
  $cta_label     = $cta_label     ?? '';
  $cta_url       = $cta_url       ?? '';

  $args = [
    'post_type'      => 'portfolio',
    'posts_per_page' => (int) $number,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
    'post_status'    => 'publish',
  ];
  if ($category) {
    $args['tax_query'] = [['taxonomy' => 'portfolio_category', 'field' => 'slug', 'terms' => (array) $category]];
  }
  $portfolio_query = new WP_Query($args);
  $items           = $portfolio_query->posts ?? [];
  wp_reset_postdata();

  // Filter terms for the filter bar
  $filter_terms = [];
  if ($show_filters) {
    $filter_terms = get_terms(['taxonomy' => 'portfolio_category', 'hide_empty' => true]) ?: [];
  }

  $bg_class    = match($bg) { 'cream' => 'bg-cream', 'ink' => 'bg-ink', default => 'bg-surface' };
  $title_class = $bg === 'ink' ? 'text-white'   : 'text-ink';
  $sub_class   = $bg === 'ink' ? 'text-white/50' : 'text-muted';
  $label_class = $bg === 'ink' ? 'text-accent'    : 'text-muted';
  $cols_class  = match((int) $cols) {
    2 => 'grid-cols-1 sm:grid-cols-2',
    default => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3',
  };
@endphp

@if(!empty($items))
<section
  id="{{ $section_id ?? 'section-portfolio' }}"
  class="section-luxury {{ $bg_class }}"
  aria-label="{{ strip_tags($section_title) }}"
  @if($show_filters) x-data="{ active: 'all' }" @endif
>
  <div class="max-w-360 mx-auto px-6 lg:px-10">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-14">
      <div>
        @if($section_label)
          <span class="section-label {{ $label_class }}" data-scroll="fade">{{ $section_label }}</span>
        @endif
        <h2 class="section-title {{ $title_class }}" data-scroll="text-reveal">{!! $section_title !!}</h2>
        @if($section_sub)
          <p class="section-subtitle mt-4 {{ $sub_class }}" data-scroll="slide-up">{{ $section_sub }}</p>
        @endif
      </div>
      @if($cta_label && $cta_url)
        <a href="{{ esc_url($cta_url) }}" class="btn-ghost shrink-0 self-start {{ $bg === 'ink' ? 'text-white/50 border-white/20 hover:text-white hover:border-white' : '' }}">
          {{ $cta_label }} →
        </a>
      @endif
    </div>

    {{-- Category filters --}}
    @if($show_filters && !empty($filter_terms))
      <div
        class="flex flex-wrap gap-2 mb-10"
        role="group"
        aria-label="{{ __('Filtra per categoria', 'sage') }}"
      >
        <button
          type="button"
          @click="active = 'all'"
          :aria-pressed="(active === 'all').toString()"
          class="filter-chip"
          :class="active === 'all' ? 'filter-chip--active' : ''"
        >{{ __('Tutti', 'sage') }}</button>
        @foreach($filter_terms as $term)
          <button
            type="button"
            @click="active = '{{ esc_js($term->slug) }}'"
            :aria-pressed="(active === '{{ esc_js($term->slug) }}').toString()"
            class="filter-chip"
            :class="active === '{{ esc_js($term->slug) }}' ? 'filter-chip--active' : ''"
          >{{ esc_html($term->name) }}</button>
        @endforeach
      </div>
    @endif

    {{-- Grid --}}
    <div class="grid {{ $cols_class }} gap-6 lg:gap-8" data-scroll="stagger">
      @foreach($items as $item)
        @php
          $pid      = $item->ID;
          $thumb_id = get_post_thumbnail_id($pid);
          $thumb_alt = $thumb_id ? esc_attr(get_post_meta($thumb_id, '_wp_attachment_image_alt', true)) : '';
          $client   = get_post_meta($pid, '_portfolio_client', true);
          $cats     = get_the_terms($pid, 'portfolio_category');
          $cat_slug = $cats && !is_wp_error($cats) ? $cats[0]->slug : '';
          $cat_name = $cats && !is_wp_error($cats) ? $cats[0]->name : '';
          $perma    = esc_url(get_permalink($pid));
        @endphp

        <article
          class="portfolio-card group"
          data-scroll-item
          @if($show_filters) x-show="active === 'all' || active === '{{ esc_js($cat_slug) }}'" @endif
        >
          <a href="{{ $perma }}" class="block overflow-hidden aspect-[4/3] mb-4">
            @if($thumb_id)
              <x-picture
                :id="(int) $thumb_id"
                :alt="$thumb_alt"
                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw"
              />
            @else
              <div class="w-full h-full {{ $bg === 'ink' ? 'bg-white/5' : 'bg-cream' }}"></div>
            @endif
          </a>
          <div>
            @if($cat_name)
              <span class="text-[0.625rem] font-semibold tracking-[0.2em] uppercase {{ $bg === 'ink' ? 'text-accent' : 'text-muted' }}">{{ $cat_name }}</span>
            @endif
            <h3 class="font-serif text-xl font-light mt-1 {{ $bg === 'ink' ? 'text-white group-hover:text-accent' : 'text-ink group-hover:text-primary' }} transition-colors leading-snug">
              <a href="{{ $perma }}" class="relative after:absolute after:inset-0">{{ esc_html(get_the_title($pid)) }}</a>
            </h3>
            @if($client)
              <p class="text-xs mt-1 {{ $bg === 'ink' ? 'text-white/30' : 'text-muted' }}">{{ esc_html($client) }}</p>
            @endif
          </div>
        </article>
      @endforeach
    </div>

  </div>
</section>
@endif
