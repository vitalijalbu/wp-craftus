@extends('layouts.app')

@section('content')

@php
  $page_title = post_type_archive_title('', false) ?: __('Il Nostro Team', 'sage');
@endphp

{{-- Page header --}}
<div class="bg-cream border-b border-border pt-20 pb-10">
  <div class="max-w-360 mx-auto px-6 lg:px-10">
    @include('partials.breadcrumb')
    <p class="font-sans text-xs font-semibold tracking-[0.2em] uppercase text-gold mb-3">
      {{ __('Chi siamo', 'sage') }}
    </p>
    <h1 class="font-serif text-[clamp(1.75rem,3.5vw,3rem)] font-light text-ink leading-tight">
      {{ $page_title }}
    </h1>
  </div>
</div>

{{-- Team grid --}}
<div class="max-w-360 mx-auto px-6 lg:px-10 py-14 lg:py-20">

  @if(!have_posts())
    <p class="font-serif text-xl text-ink text-center py-12">{{ __('Nessun membro trovato.', 'sage') }}</p>
  @else
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8 lg:gap-10" role="list">
      @while(have_posts()) @php(the_post())
        @php
          $member_id   = get_the_ID();
          $thumb_url   = get_the_post_thumbnail_url($member_id, 'large');
          $thumb_alt   = esc_attr(get_the_title($member_id));
          $role_label  = get_post_meta($member_id, '_team_role', true);
          $departments = get_the_terms($member_id, 'team_department');
          $dept_name   = ($departments && !is_wp_error($departments)) ? esc_html($departments[0]->name) : '';
        @endphp

        <article class="group text-center" role="listitem">

          {{-- Portrait --}}
          <a href="{{ get_permalink() }}" tabindex="-1" aria-hidden="true"
             class="block overflow-hidden aspect-[3/4] mb-4 bg-surface">
            @if($thumb_url)
              <img src="{{ $thumb_url }}" alt="{{ $thumb_alt }}"
                   loading="lazy" decoding="async"
                   class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
            @endif
          </a>

          @if($dept_name)
            <p class="font-sans text-[0.6rem] font-semibold tracking-[0.2em] uppercase text-gold mb-1">
              {{ $dept_name }}
            </p>
          @endif

          <h2 class="font-serif text-base font-light text-ink group-hover:text-primary transition-colors">
            <a href="{{ get_permalink() }}" class="after:absolute after:inset-0 relative">
              {{ get_the_title() }}
            </a>
          </h2>

          @if($role_label)
            <p class="font-sans text-xs text-muted mt-0.5">{{ esc_html($role_label) }}</p>
          @endif

        </article>
      @endwhile
    </div>
  @endif

</div>
@endsection
