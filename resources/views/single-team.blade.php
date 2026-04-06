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
  <div class="container">
    @include('partials.breadcrumb')
    @if($dept_name)
      <p class="text-xs font-semibold tracking-[0.2em] uppercase text-accent mb-3">
        {{ $dept_name }}
      </p>
    @endif
    <h1 class="font-serif text-[clamp(1.75rem,3.5vw,3rem)] font-light text-ink leading-tight">
      {{ get_the_title() }}
    </h1>
    @if($role_label)
      <p class="text-sm text-muted mt-2">{{ esc_html($role_label) }}</p>
    @endif
  </div>
</div>

{{-- Content --}}
<div class="container py-14 lg:py-20">
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
             class="flex items-center gap-2 text-sm text-muted hover:text-primary transition-colors">
            <x-icons.envelope-rect class="w-[15px] h-[15px]" />
            {{ esc_html($email) }}
          </a>
        @endif
        @if($linkedin)
          <a href="{{ $linkedin }}" target="_blank" rel="noopener noreferrer"
             class="flex items-center gap-2 text-sm text-muted hover:text-primary transition-colors">
            <x-icons.linkedin class="w-[15px] h-[15px]" />
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
