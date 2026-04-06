@php
  // Parameters
  $section_label    = $section_label    ?? __('Esplora', 'sage');
  $section_title    = $section_title    ?? __('Le nostre categorie', 'sage');
  $section_subtitle = $section_subtitle ?? '';
  $parent           = $parent           ?? 0;    // 0 = top-level, or parent term ID
  $number           = $number           ?? 8;
  $cols             = $cols             ?? 4;    // 2 | 3 | 4
  $bg               = $bg               ?? 'surface';

  $categories = [];
  if (function_exists('get_terms')) {
    $raw = get_terms([
      'taxonomy'   => 'product_cat',
      'hide_empty' => true,
      'number'     => (int) $number,
      'parent'     => (int) $parent,
      'orderby'    => 'count',
      'order'      => 'DESC',
    ]);
    if (!is_wp_error($raw)) {
      $categories = $raw;
    }
  }

  $cols_class = match((int) $cols) {
    2 => 'grid-cols-2',
    3 => 'grid-cols-2 md:grid-cols-3',
    default => 'grid-cols-2 md:grid-cols-3 lg:grid-cols-4',
  };

  $bg_class = $bg === 'cream' ? 'bg-cream' : ($bg === 'ink' ? 'bg-ink' : 'bg-surface');
@endphp

@if(!empty($categories))
<section
  id="{{ $section_id ?? 'section-categories' }}"
  class="section-luxury {{ $bg_class }}"
  aria-label="{{ $section_title }}"
>
  <div class="container">

    {{-- Header --}}
    @if($section_label || $section_title)
      <div class="text-center mb-14">
        @if($section_label)
          <span class="section-label {{ $bg === 'ink' ? 'text-accent' : 'text-muted' }}" data-scroll="fade">
            {{ $section_label }}
          </span>
        @endif
        <h2
          class="section-title {{ $bg === 'ink' ? 'text-white' : '' }}"
          data-scroll="text-reveal"
        >{!! $section_title !!}</h2>
        @if($section_subtitle)
          <p
            class="section-subtitle mx-auto mt-4 {{ $bg === 'ink' ? 'text-white/60' : '' }}"
            data-scroll="slide-up"
          >{{ $section_subtitle }}</p>
        @endif
      </div>
    @endif

    {{-- Categories grid --}}
    <div
      class="grid {{ $cols_class }} gap-4 lg:gap-6"
      data-scroll="stagger"
    >
      @foreach($categories as $cat)
        @php
          $cat_link  = get_term_link($cat);
          $cat_img   = '';
          $cat_thumb = '';
          // WooCommerce category thumbnail
          if (function_exists('get_woocommerce_term_meta')) {
            $thumbnail_id = get_woocommerce_term_meta($cat->term_id, 'thumbnail_id', true);
          } elseif (function_exists('get_term_meta')) {
            $thumbnail_id = get_term_meta($cat->term_id, 'thumbnail_id', true);
          } else {
            $thumbnail_id = 0;
          }
          if ($thumbnail_id) {
            $cat_img = wp_get_attachment_image_url($thumbnail_id, 'large');
          }
        @endphp
        <a
          href="{{ is_wp_error($cat_link) ? '#' : $cat_link }}"
          class="category-card group"
          data-scroll-item
        >
          {{-- Image: alt="" because <h3> below provides the visible name --}}
          @if($thumbnail_id)
            <x-picture
              :id="(int) $thumbnail_id"
              alt=""
              class="category-card__image"
              size="large"
              sizes="(max-width: 640px) 50vw, (max-width: 1024px) 33vw, 25vw"
            />
          @elseif($cat_img)
            <img
              src="{{ $cat_img }}"
              alt=""
              class="category-card__image"
              loading="lazy"
              decoding="async"
            >
          @else
            <div
              class="category-card__image bg-linear-to-br from-cream via-accent-light to-border flex items-center justify-center"
              aria-hidden="true"
            >
              <x-icons.image-placeholder class="w-12 h-12 text-accent/40" stroke-width="1" />
            </div>
          @endif

          <div class="category-card__overlay" aria-hidden="true"></div>

          <div class="category-card__body">
            <h3 class="category-card__name">{{ $cat->name }}</h3>
            <p class="category-card__count">
              {{ sprintf(_n('%d prodotto', '%d prodotti', $cat->count, 'sage'), $cat->count) }}
            </p>
          </div>
        </a>
      @endforeach
    </div>

  </div>
</section>
@endif
