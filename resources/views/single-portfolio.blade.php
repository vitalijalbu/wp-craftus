@extends('layouts.app')

@section('content')

@php
  $proj_id    = get_the_ID();
  $thumb_url  = get_the_post_thumbnail_url($proj_id, 'full');
  $thumb_alt  = esc_attr(get_the_title($proj_id));
  $proj_cats  = get_the_terms($proj_id, 'portfolio_category');
  $cat_name   = ($proj_cats && !is_wp_error($proj_cats)) ? esc_html($proj_cats[0]->name) : '';
  $cat_link   = ($proj_cats && !is_wp_error($proj_cats)) ? esc_url(get_term_link($proj_cats[0])) : '';
  $client     = get_post_meta($proj_id, '_portfolio_client', true);
  $year       = get_post_meta($proj_id, '_portfolio_year', true) ?: get_the_date('Y');
  $services   = get_post_meta($proj_id, '_portfolio_services', true);
  $project_url= esc_url(get_post_meta($proj_id, '_portfolio_url', true));
@endphp

{{-- Page header --}}
<div class="bg-cream border-b border-border pt-20 pb-10">
  <div class="max-w-360 mx-auto px-6 lg:px-10">
    @include('partials.breadcrumb')
    @if($cat_name)
      <a href="{{ $cat_link }}" class="font-sans text-xs font-semibold tracking-[0.2em] uppercase text-gold mb-3 inline-block hover:text-primary transition-colors">
        {{ $cat_name }}
      </a>
    @endif
    <h1 class="font-serif text-[clamp(1.75rem,3.5vw,3rem)] font-light text-ink leading-tight">
      {{ get_the_title() }}
    </h1>
  </div>
</div>

{{-- Content --}}
<div class="max-w-360 mx-auto px-6 lg:px-10 py-14 lg:py-20">

  {{-- Hero image --}}
  @if($thumb_url)
    <figure class="mb-14 overflow-hidden aspect-[21/9]">
      <img src="{{ $thumb_url }}" alt="{{ $thumb_alt }}"
           loading="eager" decoding="async"
           class="w-full h-full object-cover">
    </figure>
  @endif

  <div class="grid grid-cols-1 lg:grid-cols-[1fr_280px] gap-12 lg:gap-16 items-start">

    {{-- Body --}}
    <article class="prose prose-lg prose-headings:font-serif prose-headings:font-light prose-a:text-primary max-w-none">
      @php(the_content())
    </article>

    {{-- Sidebar: project details --}}
    <aside class="space-y-6 font-sans text-sm lg:sticky lg:top-28">

      <div class="w-10 h-px bg-gold"></div>

      @if($client)
        <div>
          <p class="text-[0.65rem] font-semibold tracking-[0.2em] uppercase text-muted mb-1">{{ __('Cliente', 'sage') }}</p>
          <p class="text-ink font-medium">{{ esc_html($client) }}</p>
        </div>
      @endif

      @if($year)
        <div>
          <p class="text-[0.65rem] font-semibold tracking-[0.2em] uppercase text-muted mb-1">{{ __('Anno', 'sage') }}</p>
          <p class="text-ink font-medium">{{ esc_html($year) }}</p>
        </div>
      @endif

      @if($services)
        <div>
          <p class="text-[0.65rem] font-semibold tracking-[0.2em] uppercase text-muted mb-1">{{ __('Servizi', 'sage') }}</p>
          <p class="text-ink">{{ esc_html($services) }}</p>
        </div>
      @endif

      @if($project_url)
        <a href="{{ $project_url }}" target="_blank" rel="noopener noreferrer"
           class="btn-primary inline-block text-xs">
          {{ __('Visita il sito →', 'sage') }}
        </a>
      @endif

      {{-- Back to archive --}}
      <a href="{{ esc_url(get_post_type_archive_link('portfolio')) }}"
         class="block font-sans text-xs text-muted hover:text-primary transition-colors underline underline-offset-2">
        ← {{ __('Tutti i progetti', 'sage') }}
      </a>

    </aside>

  </div>

</div>
@endsection
