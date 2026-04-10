@extends('layouts.app')

@section('content')
<section class="min-h-[70vh] flex items-center bg-surface" aria-labelledby="error-heading">
  <div class="container py-24 text-center">

    {{-- 404 number --}}
    <p class="font-serif text-[clamp(6rem,20vw,14rem)] font-light leading-none text-border select-none" aria-hidden="true">
      404
    </p>

    <div class="w-12 h-px bg-primary mx-auto my-8" aria-hidden="true"></div>

    <h1 id="error-heading" class="font-serif text-[clamp(1.75rem,4vw,3rem)] font-light text-ink mb-4">
      {{ __('Pagina non trovata', 'sage') }}
    </h1>
    <p class="text-base text-muted max-w-md mx-auto mb-10">
      {{ __('La pagina che stai cercando potrebbe essere stata spostata, rinominata o non esiste più.', 'sage') }}
    </p>

    {{-- Search --}}
    <form role="search" method="get" action="{{ home_url('/') }}" class="flex max-w-sm mx-auto mb-10 gap-0">
      <label for="search-404" class="sr-only">{{ __('Cerca nel sito', 'sage') }}</label>
      <input
        id="search-404"
        type="search"
        name="s"
        value="{{ get_search_query() }}"
        placeholder="{{ __('Cerca nel sito…', 'sage') }}"
        class="flex-1 border border-border border-r-0 px-4 py-3 text-sm text-ink bg-white placeholder-muted focus:outline-none focus:border-primary transition-colors"
      >
      <button
        type="submit"
        class="btn-secondary btn-sm"
      >{{ __('Cerca', 'sage') }}</button>
    </form>

    {{-- Quick links --}}
    <nav aria-label="{{ __('Link utili', 'sage') }}" class="flex flex-wrap justify-center gap-4">
      <a href="{{ home_url('/') }}" class="btn-primary btn-sm">
        {{ __('← Torna in Homepage', 'sage') }}
      </a>
      @if(function_exists('wc_get_page_permalink'))
        <a href="{{ esc_url(wc_get_page_permalink('shop')) }}" class="btn-ghost btn-sm">
          {{ __('Vai allo Shop', 'sage') }}
        </a>
      @endif
      <a href="{{ home_url('/contatti') }}" class="btn-ghost btn-sm">
        {{ __('Contattaci', 'sage') }}
      </a>
    </nav>

  </div>
</section>
@endsection
