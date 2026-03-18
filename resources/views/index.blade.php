@extends('layouts.app')

@section('content')

@php
  $is_archive    = is_archive();
  $is_category   = is_category();
  $is_tag        = is_tag();
  $is_author     = is_author();
  $is_date       = is_date();
  $archive_title = get_the_archive_title();
  $archive_desc  = get_the_archive_description();
@endphp

{{-- Archive / Blog header --}}
<div class="bg-cream border-b border-border pt-20 pb-10">
  <div class="max-w-360 mx-auto px-6 lg:px-10">
    @include('partials.breadcrumb')

    @if($is_archive)
      @if($is_category || $is_tag)
        <p class="font-sans text-xs font-semibold tracking-[0.2em] uppercase text-gold mb-3">
          {{ $is_category ? __('Categoria', 'sage') : __('Tag', 'sage') }}
        </p>
      @endif
      <h1 class="font-serif text-[clamp(1.75rem,3.5vw,3rem)] font-light text-ink leading-tight">
        {!! $archive_title !!}
      </h1>
      @if($archive_desc)
        <div class="font-sans text-sm text-muted mt-3 max-w-2xl">{!! $archive_desc !!}</div>
      @endif
    @else
      {{-- Blog index --}}
      <p class="font-sans text-xs font-semibold tracking-[0.2em] uppercase text-gold mb-3">
        {{ __('Blog', 'sage') }}
      </p>
      <h1 class="font-serif text-[clamp(1.75rem,3.5vw,3rem)] font-light text-ink">
        {{ get_bloginfo('name') }}
      </h1>
    @endif
  </div>
</div>

{{-- Posts grid --}}
<div class="max-w-360 mx-auto px-6 lg:px-10 py-14 lg:py-20">

  @if(!have_posts())
    <div class="text-center py-12">
      <p class="font-serif text-2xl text-ink mb-3">{{ __('Nessun articolo trovato.', 'sage') }}</p>
    </div>
  @else
    <div
      class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-14"
      role="list"
      aria-label="{{ $is_archive ? strip_tags($archive_title) : __('Articoli del blog', 'sage') }}"
    >
      @while(have_posts()) @php(the_post())
        <div role="listitem">
          @includeFirst(['partials.content-' . get_post_type(), 'partials.content'])
        </div>
      @endwhile
    </div>

    {{-- Pagination --}}
    <nav class="mt-16 flex justify-center" aria-label="{{ __('Pagine archivio', 'sage') }}">
      {!! get_the_posts_pagination([
        'mid_size'  => 2,
        'prev_text' => '← ' . __('Precedente', 'sage'),
        'next_text' => __('Successiva', 'sage') . ' →',
      ]) !!}
    </nav>
  @endif

</div>
@endsection
