{{--
  archive-faq.blade.php — FAQ CPT archive
  Groups FAQs by faq_category taxonomy and renders as accordions.
--}}
@extends('layouts.app')

@section('content')
@php
  // Group all published FAQs by their primary faq_category
  $terms = get_terms([
    'taxonomy'   => 'faq_category',
    'hide_empty' => true,
    'orderby'    => 'menu_order',
    'order'      => 'ASC',
  ]);
  $terms     = is_array($terms) ? $terms : [];
  $has_terms = !empty($terms);
@endphp

  {{-- Page header --}}
  <div class="bg-cream border-b border-border page-header-offset pb-10">
    <div class="container">
      @include('partials.breadcrumb')
      <h1 class="text-[clamp(1.75rem,3.5vw,3rem)] font-light text-ink leading-tight mt-4">
        {{ __('Domande frequenti', 'sage') }}
      </h1>
      <p class="section-subtitle mt-3 text-muted">
        {{ __('Trova le risposte alle domande più comuni.', 'sage') }}
      </p>
    </div>
  </div>

  @if($has_terms)
    {{-- Category navigation --}}
    <nav
      class="sticky top-[var(--header-height)] z-30 bg-surface border-b border-border"
      aria-label="{{ __('Categorie FAQ', 'sage') }}"
    >
      <div class="container flex items-center gap-6 overflow-x-auto scrollbar-hide py-4">
        @foreach($terms as $term)
          <a
            href="#faq-cat-{{ $term->term_id }}"
            class="whitespace-nowrap text-xs font-semibold tracking-[0.15em] uppercase text-muted hover:text-ink transition-colors pb-1 border-b-2 border-transparent hover:border-ink"
          >
            {{ esc_html($term->name) }}
            <span class="ml-1     text-border">({{ $term->count }})</span>
          </a>
        @endforeach
      </div>
    </nav>

    {{-- FAQ accordions per category --}}
    <div class="bg-surface">
      <div class="max-w-3xl mx-auto px-6 lg:px-10 py-16 lg:py-20 space-y-16">
        @foreach($terms as $term)
          @include('sections.faq-accordion', [
            'section_label' => '',
            'section_title' => esc_html($term->name),
            'category'      => $term->slug,
            'number'        => -1,
            'bg'            => 'surface',
            'layout'        => 'single',
          ])
        @endforeach
      </div>
    </div>

  @else
    {{-- No categories — render all FAQs flat --}}
    <div class="max-w-3xl mx-auto px-6 lg:px-10 py-16 lg:py-20">
      @include('sections.faq-accordion', [
        'section_title' => '',
        'number'        => -1,
        'bg'            => 'surface',
        'layout'        => 'single',
      ])
    </div>
  @endif

@endsection
