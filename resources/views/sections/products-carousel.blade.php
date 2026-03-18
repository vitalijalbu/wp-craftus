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
  class="section-luxury bg-surface overflow-hidden"
  data-products-carousel
  aria-roledescription="{{ __('carosello', 'sage') }}"
  aria-label="{{ strip_tags($section_title) }}"
>
  <div class="max-w-360 mx-auto px-6 lg:px-10">

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
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
            </svg>
          </button>
          <button
            class="swiper-button-next static w-11 h-11 border border-border flex items-center justify-center text-ink hover:bg-ink hover:text-surface hover:border-ink transition-all duration-200"
            aria-label="{{ __('Successivo', 'sage') }}"
            type="button"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
            </svg>
          </button>
        </div>

        <a href="{{ $view_all_url }}" class="btn-ghost">
          {{ $view_all_label }}
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
          </svg>
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
