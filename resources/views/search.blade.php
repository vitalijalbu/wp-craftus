@extends('layouts.app')

@section('content')

{{-- Page header --}}
<div class="bg-cream border-b border-border pt-20 pb-10">
  <div class="container">
    @include('partials.breadcrumb')
    @if(get_search_query())
      <h1 class="text-[clamp(1.75rem,3.5vw,3rem)] font-light text-ink leading-tight">
        {{ sprintf(__('Risultati per: "%s"', 'sage'), esc_html(get_search_query())) }}
      </h1>
      @if(have_posts())
        <p class="text-sm text-muted mt-2">
          {{ sprintf(
            _n('%d risultato trovato', '%d risultati trovati', $wp_query->found_posts, 'sage'),
            $wp_query->found_posts
          ) }}
        </p>
      @endif
    @else
      <h1 class="text-[clamp(1.75rem,3.5vw,3rem)] font-light text-ink">
        {{ __('Ricerca', 'sage') }}
      </h1>
    @endif
  </div>
</div>

{{-- Results / empty state --}}
<div class="container py-14 lg:py-20">

  @if(!have_posts())
    <x-empty-state
      icon="icons.search"
      :title="__('Nessun risultato trovato', 'sage')"
      :message="__('Prova con parole chiave diverse o naviga il sito.', 'sage')"
      :showSearch="true"
      :buttons="[
        ['url' => home_url('/'), 'label' => __('Vai alla Home', 'sage'), 'style' => 'primary'],
        ['url' => function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop'), 'label' => __('Sfoglia i prodotti', 'sage'), 'style' => 'outline'],
      ]"
    />
  @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-14" role="list" aria-label="{{ __('Risultati di ricerca', 'sage') }}">
      @while(have_posts()) @php(the_post())
        <div role="listitem">
          @includeFirst(['partials.content-' . get_post_type(), 'partials.content'])
        </div>
      @endwhile
    </div>

    {{-- Pagination --}}
    <nav aria-label="{{ __('Pagine risultati', 'sage') }}">
      {!! get_the_posts_pagination([
        'mid_size'  => 2,
        'prev_text' => '← ' . __('Precedente', 'sage'),
        'next_text' => __('Successiva', 'sage') . ' →',
      ]) !!}
    </nav>
  @endif

</div>
@endsection
