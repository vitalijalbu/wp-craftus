@extends('layouts.app')

@section('content')

@php
  $page_title = post_type_archive_title('', false) ?: __('Portfolio', 'sage');
  // Collect portfolio categories for filtering
  $port_cats  = get_terms(['taxonomy' => 'portfolio_category', 'hide_empty' => true]);
@endphp

{{-- Page header --}}
<div class="bg-cream border-b border-border pt-20 pb-10">
  <div class="max-w-360 mx-auto px-6 lg:px-10">
    @include('partials.breadcrumb')
    <p class="text-xs font-semibold tracking-[0.2em] uppercase text-accent mb-3">
      {{ __('Lavori', 'sage') }}
    </p>
    <h1 class="font-serif text-[clamp(1.75rem,3.5vw,3rem)] font-light text-ink leading-tight">
      {{ $page_title }}
    </h1>
  </div>
</div>

{{-- Portfolio grid --}}
<div class="max-w-360 mx-auto px-6 lg:px-10 py-14 lg:py-20">

  {{-- Category filters --}}
  @if($port_cats && !is_wp_error($port_cats))
    <div role="group" aria-label="{{ __('Filtra per categoria', 'sage') }}"
         class="flex flex-wrap gap-2 mb-10">
      @php($current_cat = get_queried_object())
      <a href="{{ esc_url(get_post_type_archive_link('portfolio')) }}"
         class="text-xs font-semibold tracking-wider uppercase px-4 py-2 border transition-colors
                {{ !is_tax('portfolio_category') ? 'bg-ink text-white border-ink' : 'border-border text-muted hover:border-primary hover:text-primary' }}">
        {{ __('Tutti', 'sage') }}
      </a>
      @foreach($port_cats as $cat)
        <a href="{{ esc_url(get_term_link($cat)) }}"
           class="text-xs font-semibold tracking-wider uppercase px-4 py-2 border transition-colors
                  {{ (is_tax('portfolio_category') && $current_cat->term_id === $cat->term_id) ? 'bg-ink text-white border-ink' : 'border-border text-muted hover:border-primary hover:text-primary' }}">
          {{ esc_html($cat->name) }}
        </a>
      @endforeach
    </div>
  @endif

  @if(!have_posts())
    <p class="font-serif text-xl text-ink text-center py-12">{{ __('Nessun progetto trovato.', 'sage') }}</p>
  @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" role="list">
      @while(have_posts()) @php(the_post())
        @php
          $proj_id    = get_the_ID();
          $thumb_url  = get_the_post_thumbnail_url($proj_id, 'large');
          $thumb_alt  = esc_attr(get_the_title($proj_id));
          $proj_cats  = get_the_terms($proj_id, 'portfolio_category');
          $cat_label  = ($proj_cats && !is_wp_error($proj_cats)) ? esc_html($proj_cats[0]->name) : '';
        @endphp

        <article class="group" role="listitem">
          <a href="{{ get_permalink() }}" class="block overflow-hidden aspect-[4/3] mb-4 bg-surface">
            @if($thumb_url)
              <img src="{{ $thumb_url }}" alt="{{ $thumb_alt }}"
                   loading="lazy" decoding="async"
                   class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
            @endif
          </a>

          @if($cat_label)
            <p class="text-[0.65rem] font-semibold tracking-[0.2em] uppercase text-accent mb-1">
              {{ $cat_label }}
            </p>
          @endif

          <h2 class="font-serif text-xl font-light text-ink group-hover:text-primary transition-colors">
            <a href="{{ get_permalink() }}">{{ get_the_title() }}</a>
          </h2>

          @if(get_the_excerpt())
            <p class="text-sm text-muted mt-2 line-clamp-2">{{ get_the_excerpt() }}</p>
          @endif
        </article>
      @endwhile
    </div>

    {{-- Pagination --}}
    <nav class="mt-16 flex justify-center" aria-label="{{ __('Pagine portfolio', 'sage') }}">
      {!! get_the_posts_pagination(['mid_size' => 2,
        'prev_text' => '← ' . __('Precedente', 'sage'),
        'next_text' => __('Successiva', 'sage') . ' →']) !!}
    </nav>
  @endif

</div>
@endsection
