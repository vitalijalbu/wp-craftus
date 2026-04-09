@extends('layouts.app')

@section('content')

{{-- Page header --}}
<div class="bg-cream border-b border-border pt-20 pb-10">
  <div class="container">
    @include('partials.breadcrumb')
    @if(get_search_query())
      <h1 class="font-serif text-[clamp(1.75rem,3.5vw,3rem)] font-light text-ink leading-tight">
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
      <h1 class="font-serif text-[clamp(1.75rem,3.5vw,3rem)] font-light text-ink">
        {{ __('Ricerca', 'sage') }}
      </h1>
    @endif
  </div>
</div>

{{-- Results / empty state --}}
<div class="container py-14 lg:py-20">

  @if(!have_posts())
    <div class="max-w-lg mx-auto text-center py-12">
      <p class="font-serif text-2xl text-ink mb-3">{{ __('Nessun risultato trovato.', 'sage') }}</p>
      <p class="text-sm text-muted mb-8">
        {{ __('Prova con parole chiave diverse o naviga il sito.', 'sage') }}
      </p>
      <form role="search" method="get" action="{{ home_url('/') }}" class="flex max-w-sm mx-auto gap-0">
        <label for="search-empty" class="sr-only">{{ __('Nuova ricerca', 'sage') }}</label>
        <input
          id="search-empty"
          type="search"
          name="s"
          placeholder="{{ __('Nuova ricerca…', 'sage') }}"
          class="flex-1 border border-border border-r-0 px-4 py-3 text-sm text-ink focus:outline-none focus:border-primary transition-colors"
        >
        <button type="submit" class="btn-secondary btn-sm">
          {{ __('Cerca', 'sage') }}
        </button>
      </form>
    </div>
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
