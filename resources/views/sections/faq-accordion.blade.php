@php
  // Parameters
  $section_label    = $section_label    ?? '';
  $section_title    = $section_title    ?? __('Domande frequenti', 'sage');
  $section_subtitle = $section_subtitle ?? '';
  $bg               = $bg               ?? 'surface'; // 'surface' | 'cream' | 'ink'
  $layout           = $layout           ?? 'single';  // 'single' | 'two-col'
  $category         = $category         ?? '';        // faq_category slug
  $number           = $number           ?? -1;        // -1 = all

  // Query FAQs from CPT
  $faqs = [];
  $query_args = [
    'post_type'      => 'faq',
    'posts_per_page' => (int) $number,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
    'post_status'    => 'publish',
  ];
  if ($category) {
    $query_args['tax_query'] = [[
      'taxonomy' => 'faq_category',
      'field'    => 'slug',
      'terms'    => (array) $category,
    ]];
  }
  $faq_query = new WP_Query($query_args);
  if ($faq_query->have_posts()) {
    while ($faq_query->have_posts()) {
      $faq_query->the_post();
      $faqs[] = [
        'id'       => get_the_ID(),
        'question' => esc_html(get_the_title()),
        'answer'   => wp_kses_post(apply_filters('the_content', get_the_content())),
      ];
    }
    wp_reset_postdata();
  }

  $bg_class     = match($bg) {
    'cream' => 'bg-cream',
    'ink'   => 'bg-ink',
    default => 'bg-surface',
  };
  $title_class  = $bg === 'ink' ? 'text-white'   : 'text-ink';
  $sub_class    = $bg === 'ink' ? 'text-white/60' : 'text-muted';
  $label_class  = $bg === 'ink' ? 'text-primary'    : 'text-muted';
  $item_class   = $bg === 'ink' ? 'faq-item faq-item--dark' : 'faq-item';
@endphp

@if(!empty($faqs))
<section
  id="{{ $section_id ?? 'section-faq' }}"
  class="section {{ $bg_class }}"
  aria-label="{{ strip_tags($section_title) }}"
  itemscope
  itemtype="https://schema.org/FAQPage"
>
  <div class="container">

    {{-- Header --}}
    @if($section_label || $section_title)
      <div class="text-center mb-14">
        @if($section_label)
          <span class="section-label {{ $label_class }}" data-scroll="fade">{{ $section_label }}</span>
        @endif
        <h2 class="section-title {{ $title_class }}" data-scroll="text-reveal">{!! $section_title !!}</h2>
        @if($section_subtitle)
          <p class="section-subtitle mx-auto mt-4 {{ $sub_class }}" data-scroll="slide-up">{{ $section_subtitle }}</p>
        @endif
      </div>
    @endif

    {{-- Accordion --}}
    <div
      class="{{ $layout === 'two-col' ? 'grid md:grid-cols-2 gap-x-10 lg:gap-x-20' : 'max-w-3xl mx-auto' }}"
      x-data="{ open: null }"
    >
      @foreach($faqs as $index => $faq)
        <div
          class="{{ $item_class }}"
          data-scroll-item
          itemscope
          itemtype="https://schema.org/Question"
        >
          {{-- Question button --}}
          <button
            type="button"
            class="faq-item__trigger"
            :aria-expanded="open === {{ $index }} ? 'true' : 'false'"
            aria-controls="faq-answer-{{ $faq['id'] }}"
            @click="open = open === {{ $index }} ? null : {{ $index }}"
            itemprop="name"
          >
            <span>{{ $faq['question'] }}</span>
            <x-icons.chevron-down
              class="faq-item__icon shrink-0"
              ::class="{ 'rotate-180': open === {{ $index }} }"
              stroke-width="2"
            />
          </button>

          {{-- Answer --}}
          <div
            id="faq-answer-{{ $faq['id'] }}"
            role="region"
            :aria-hidden="open === {{ $index }} ? 'false' : 'true'"
            x-show="open === {{ $index }}"
            x-collapse
            itemscope
            itemtype="https://schema.org/Answer"
          >
            <div
              class="faq-item__answer"
              itemprop="text"
            >{!! $faq['answer'] !!}</div>
          </div>
        </div>
      @endforeach
    </div>

  </div>
</section>
@endif
