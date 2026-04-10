{{--
  Template Name: Landing Page
  Template Post Type: page
--}}

@extends('layouts.app')

@section('content')
  @while(have_posts()) @php(the_post())

    @php
      // ACF flexible content sections
      $sections = function_exists('get_field') ? (get_field('page_sections') ?: []) : [];
      $has_sections = !empty($sections);
    @endphp

    @if($has_sections)
      {{-- ── ACF Flexible Content builder ── --}}
      @foreach($sections as $section)
        @php $layout = $section['acf_fc_layout'] ?? ''; @endphp

        @if($layout === 'hero')
          @include('sections.hero', [
            'hero_label'    => $section['label']    ?? '',
            'hero_title'    => $section['title']    ?? get_the_title(),
            'hero_subtitle' => $section['subtitle'] ?? '',
            'hero_image'    => $section['image']    ?? null,
            'hero_cta_1_label' => $section['cta_1_label'] ?? __('Scopri', 'sage'),
            'hero_cta_1_url'   => $section['cta_1_url']   ?? '/shop',
            'hero_cta_2_label' => $section['cta_2_label'] ?? '',
            'hero_cta_2_url'   => $section['cta_2_url']   ?? '',
            'hero_align'    => $section['align']    ?? 'left',
          ])

        @elseif($layout === 'hero_carousel')
          @include('sections.hero-carousel', [
            'slides' => $section['slides'] ?? [],
          ])

        @elseif($layout === 'products_carousel')
          @include('sections.products-carousel', [
            'section_label'    => $section['label']    ?? '',
            'section_title'    => $section['title']    ?? __('Prodotti', 'sage'),
            'section_subtitle' => $section['subtitle'] ?? '',
            'category'         => $section['category'] ?? '',
            'limit'            => $section['limit']    ?? 12,
            'featured'         => $section['featured'] ?? false,
            'view_all_url'     => $section['view_all_url']   ?? '/shop',
            'view_all_label'   => $section['view_all_label'] ?? __('Vedi tutti', 'sage'),
          ])

        @elseif($layout === 'products_grid')
          @include('sections.products-grid', [
            'section_label'    => $section['label']    ?? '',
            'section_title'    => $section['title']    ?? __('Prodotti', 'sage'),
            'section_subtitle' => $section['subtitle'] ?? '',
            'category'         => $section['category'] ?? '',
            'per_page'         => $section['per_page'] ?? 12,
            'cols'             => $section['cols']     ?? 3,
            'bg'               => $section['bg']       ?? 'surface',
            'show_filters'     => $section['show_filters'] ?? true,
          ])

        @elseif($layout === 'categories_grid')
          @include('sections.categories-grid', [
            'section_label'    => $section['label']    ?? '',
            'section_title'    => $section['title']    ?? __('Categorie', 'sage'),
            'section_subtitle' => $section['subtitle'] ?? '',
            'cols'             => $section['cols']     ?? 4,
            'number'           => $section['number']   ?? 8,
            'bg'               => $section['bg']       ?? 'surface',
          ])

        @elseif($layout === 'media_text')
          @include('sections.media-text', [
            'label'          => $section['label']          ?? '',
            'title'          => $section['title']          ?? '',
            'text'           => $section['text']           ?? '',
            'image'          => $section['image']          ?? null,
            'image_position' => $section['image_position'] ?? 'left',
            'cta_label'      => $section['cta_label']      ?? '',
            'cta_url'        => $section['cta_url']        ?? '',
            'cta2_label'     => $section['cta2_label']     ?? '',
            'cta2_url'       => $section['cta2_url']       ?? '',
            'bg'             => $section['bg']             ?? 'surface',
            'accent'         => $section['accent']         ?? false,
          ])

        @elseif($layout === 'features')
          @include('sections.features', [
            'section_label'    => $section['label']    ?? '',
            'section_title'    => $section['title']    ?? '',
            'section_subtitle' => $section['subtitle'] ?? '',
            'features'         => $section['items']    ?? [],
            'cols'             => $section['cols']     ?? 3,
            'bg'               => $section['bg']       ?? 'surface',
            'style'            => $section['style']    ?? 'boxed',
          ])

        @elseif($layout === 'stats')
          @include('sections.stats', [
            'section_label'  => $section['label'] ?? '',
            'section_title'  => $section['title'] ?? '',
            'stats'          => $section['items'] ?? [],
            'bg'             => $section['bg']    ?? 'cream',
          ])

        @elseif($layout === 'cta_banner')
          @include('sections.cta-banner', [
            'label'      => $section['label']      ?? '',
            'title'      => $section['title']      ?? '',
            'subtitle'   => $section['subtitle']   ?? '',
            'cta_label'  => $section['cta_label']  ?? __('Scopri', 'sage'),
            'cta_url'    => $section['cta_url']    ?? '/shop',
            'cta2_label' => $section['cta2_label'] ?? '',
            'cta2_url'   => $section['cta2_url']   ?? '',
            'image'      => $section['image']      ?? null,
            'style'      => $section['style']      ?? 'dark',
            'align'      => $section['align']      ?? 'center',
          ])

        @elseif($layout === 'testimonials')
          @include('sections.testimonials', [
            'section_label'    => $section['label']        ?? '',
            'section_title'    => $section['title']        ?? __('Testimonianze', 'sage'),
            'section_subtitle' => $section['subtitle']     ?? '',
            'testimonials'     => $section['items']        ?? [],
            'bg'               => $section['bg']           ?? 'cream',
          ])

        @elseif($layout === 'marquee')
          @include('sections.marquee', [
            'items' => $section['items'] ?? [],
            'bg'    => $section['bg']    ?? 'surface',
            'size'  => $section['size']  ?? 'md',
          ])

        @elseif($layout === 'wysiwyg')
          <section class="section-luxury bg-surface">
            <div class="container">
              <div class="prose max-w-3xl mx-auto">
                {!! $section['content'] ?? '' !!}
              </div>
            </div>
          </section>

        @endif
      @endforeach

    @else
      {{-- ── Fallback: standard page content ── --}}
      <div class="section-luxury bg-surface">
        <div class="container">
          <div class="max-w-4xl mx-auto">

            <h1
              class="font-serif text-[clamp(2.5rem,5vw,4rem)] font-light text-ink leading-tight mb-8"
              data-scroll="text-reveal"
            >{{ get_the_title() }}</h1>

            <div class="w-10 h-px bg-primary mb-10" data-scroll="line-in" aria-hidden="true"></div>

            <div class="prose prose-lg max-w-none text-muted leading-relaxed" data-scroll="slide-up">
              {!! apply_filters('the_content', get_the_content()) !!}
            </div>

          </div>
        </div>
      </div>
    @endif

  @endwhile
@endsection
