@php
  // Parameters (pass via @include(['sections.products-carousel', ['category' => 'dogs', 'limit' => 12]]))
  $section_label   = $section_label   ?? __('I nostri prodotti', 'sage');
  $section_title   = $section_title   ?? __('Scelti con cura', 'sage');
  $section_subtitle = $section_subtitle ?? '';
  $category        = $category        ?? '';
  $limit           = $limit           ?? 12;
  $tag             = $tag             ?? '';
  $featured        = $featured        ?? false;
  $view_all_label  = $view_all_label  ?? __('Vedi tutti', 'sage');
  $view_all_url    = $view_all_url    ?? '/shop';

  $products = [];
  if (function_exists('wc_get_products')) {
    $args = [
      'status'  => 'publish',
      'limit'   => (int) $limit,
      'orderby' => 'date',
      'order'   => 'DESC',
    ];
    if ($featured) {
      $args['featured'] = true;
    }
    if ($category) {
      $args['category'] = is_array($category) ? $category : [$category];
    }
    if ($tag) {
      $args['tag'] = is_array($tag) ? $tag : [$tag];
    }
    $products = wc_get_products($args);
  }
@endphp

@if(!empty($products))
<section
  id="{{ $section_id ?? 'section-products-carousel' }}"
  class="section-luxury bg-surface overflow-hidden"
  data-products-carousel
  aria-roledescription="{{ __('carosello', 'sage') }}"
  aria-label="{{ strip_tags($section_title) }}"
>
  <div class="container">

    {{-- Section header --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
      <div>
        @if($section_label)
          <span class="section-label" data-scroll="fade">{{ $section_label }}</span>
        @endif
        <h2 class="section-title" data-scroll="text-reveal">{!! $section_title !!}</h2>
        @if($section_subtitle)
          <p class="section-subtitle mt-3" data-scroll="slide-up">{{ $section_subtitle }}</p>
        @endif
      </div>

      <div class="flex items-center gap-4 shrink-0" data-scroll="fade">
        {{-- Nav arrows --}}
        <div class="flex gap-2">
          <button
            class="swiper-button-prev static w-11 h-11 border border-border flex items-center justify-center text-ink hover:bg-ink hover:text-surface hover:border-ink transition-all duration-200"
            aria-label="{{ __('Precedente', 'sage') }}"
            type="button"
          >
            <x-icons.chevron-left class="w-4 h-4" stroke-width="2" />
          </button>
          <button
            class="swiper-button-next static w-11 h-11 border border-border flex items-center justify-center text-ink hover:bg-ink hover:text-surface hover:border-ink transition-all duration-200"
            aria-label="{{ __('Successivo', 'sage') }}"
            type="button"
          >
            <x-icons.chevron-right class="w-4 h-4" stroke-width="2" />
          </button>
        </div>

        <a href="{{ $view_all_url }}" class="btn-ghost">
          {{ $view_all_label }}
          <x-icons.arrow-right class="w-4 h-4" />
        </a>
      </div>
    </div>

  </div>

  {{-- Swiper carousel --}}
  <div class="pl-6 lg:pl-10 max-w-360 mx-auto">
    <div class="swiper js-products-swiper overflow-visible">
      <div class="swiper-wrapper" aria-live="polite">
        @foreach($products as $product)
          <div
            class="swiper-slide"
            role="group"
            aria-roledescription="{{ __('slide', 'sage') }}"
            aria-label="{{ sprintf(__('%d di %d', 'sage'), $loop->iteration, count($products)) }}"
          >
            @include('partials.product-card', ['product' => $product])
          </div>
        @endforeach
      </div>
    </div>
  </div>

</section>
@endif
