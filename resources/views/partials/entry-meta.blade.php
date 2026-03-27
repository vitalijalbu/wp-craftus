@php
  $post_id   = get_the_ID();
  $author_id = get_post_field('post_author', $post_id);
  $avatar    = get_avatar_url($author_id, ['size' => 48]);
  $words     = str_word_count(wp_strip_all_tags(get_the_content()));
  $read_min  = max(1, (int) ceil($words / 200));
  $cats      = get_the_category($post_id);
@endphp

<div class="flex flex-wrap items-center gap-4 text-sm text-muted">

  {{-- Avatar + author --}}
  <a href="{{ esc_url(get_author_posts_url($author_id)) }}" class="flex items-center gap-2 hover:text-primary transition-colors">
    @if($avatar)
      <img src="{{ $avatar }}" alt="" width="28" height="28" class="rounded-full w-7 h-7 object-cover" aria-hidden="true">
    @endif
    <span class="font-medium text-ink">{{ get_the_author_meta('display_name', $author_id) }}</span>
  </a>

  <span class="text-border" aria-hidden="true">·</span>

  {{-- Date --}}
  <time datetime="{{ get_post_time('c', true) }}">
    {{ get_the_date('j F Y') }}
  </time>

  <span class="text-border" aria-hidden="true">·</span>

  {{-- Reading time --}}
  <span>{{ $read_min }}&nbsp;{{ __('min di lettura', 'sage') }}</span>

  @if($cats)
    <span class="text-border" aria-hidden="true">·</span>
    {{-- Primary category --}}
    <a href="{{ esc_url(get_category_link($cats[0]->term_id)) }}"
       class="inline-block font-semibold text-xs tracking-[0.15em] uppercase text-accent hover:text-primary transition-colors">
      {{ esc_html($cats[0]->name) }}
    </a>
  @endif

</div>
