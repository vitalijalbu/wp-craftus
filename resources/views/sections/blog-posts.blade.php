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
  $label_class = $bg === 'ink' ? 'text-primary'    : 'text-muted';

  $cols_class = match((int) $cols) {
    2 => 'grid-cols-1 sm:grid-cols-2',
    default => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3',
  };
@endphp

@if(!empty($posts_list))
<section
  id="{{ $section_id ?? 'section-blog' }}"
  class="section {{ $bg_class }}"
  aria-label="{{ strip_tags($section_title) }}"
>
  <div class="container">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-14">
      @include('partials.section-header', ['bg' => $bg])
      @if($cta_label && $cta_url)
        <a
          href="{{ $cta_url }}"
          class="btn-ghost shrink-0 self-start md:self-auto {{ $bg === 'ink' ? 'text-white/60 border-white/40 hover:text-white hover:border-white' : '' }}"
          data-scroll="fade"
        >
          {{ $cta_label }}
          <x-icons.arrow-right class="size-4" />
        </a>
      @endif
    </div>

    {{-- Posts grid --}}
    <div
      class="grid {{ $cols_class }} gap-8 lg:gap-10"
      data-scroll="stagger"
    >
      @foreach($posts_list as $post)
        @include('partials.post-card', ['post' => $post, 'bg' => $bg])
      @endforeach
    </div>

  </div>
</section>
@php wp_reset_postdata(); @endphp
@endif
