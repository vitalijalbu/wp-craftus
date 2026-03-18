@extends('layouts.app')

@section('content')

@php
  $member_id  = get_the_ID();
  $thumb_url  = get_the_post_thumbnail_url($member_id, 'large');
  $thumb_alt  = esc_attr(get_the_title($member_id));
  $role_label = get_post_meta($member_id, '_team_role', true);
  $email      = get_post_meta($member_id, '_team_email', true);
  $linkedin   = esc_url(get_post_meta($member_id, '_team_linkedin', true));
  $depts      = get_the_terms($member_id, 'team_department');
  $dept_name  = ($depts && !is_wp_error($depts)) ? esc_html($depts[0]->name) : '';
@endphp

{{-- Page header --}}
<div class="bg-cream border-b border-border pt-20 pb-10">
  <div class="max-w-360 mx-auto px-6 lg:px-10">
    @include('partials.breadcrumb')
    @if($dept_name)
      <p class="font-sans text-xs font-semibold tracking-[0.2em] uppercase text-gold mb-3">
        {{ $dept_name }}
      </p>
    @endif
    <h1 class="font-serif text-[clamp(1.75rem,3.5vw,3rem)] font-light text-ink leading-tight">
      {{ get_the_title() }}
    </h1>
    @if($role_label)
      <p class="font-sans text-sm text-muted mt-2">{{ esc_html($role_label) }}</p>
    @endif
  </div>
</div>

{{-- Content --}}
<div class="max-w-360 mx-auto px-6 lg:px-10 py-14 lg:py-20">
  <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-12 lg:gap-16 items-start">

    {{-- Portrait --}}
    <aside>
      @if($thumb_url)
        <figure class="overflow-hidden aspect-[3/4] mb-6">
          <img src="{{ $thumb_url }}" alt="{{ $thumb_alt }}"
               loading="eager" decoding="async"
               class="w-full h-full object-cover">
        </figure>
      @endif

      {{-- Contact links --}}
      <div class="space-y-2">
        @if($email)
          <a href="mailto:{{ esc_attr($email) }}"
             class="flex items-center gap-2 font-sans text-sm text-muted hover:text-primary transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
            </svg>
            {{ esc_html($email) }}
          </a>
        @endif
        @if($linkedin)
          <a href="{{ $linkedin }}" target="_blank" rel="noopener noreferrer"
             class="flex items-center gap-2 font-sans text-sm text-muted hover:text-primary transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
              <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6zM2 9h4v12H2z"/><circle cx="4" cy="4" r="2"/>
            </svg>
            LinkedIn
          </a>
        @endif
      </div>
    </aside>

    {{-- Bio --}}
    <article class="prose prose-lg prose-headings:font-serif prose-headings:font-light prose-a:text-primary max-w-none">
      @php(the_content())
    </article>

  </div>
</div>

@endsection
