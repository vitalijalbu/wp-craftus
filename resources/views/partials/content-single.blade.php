@php
  $post_id   = get_the_ID();
  $thumb_id  = get_post_thumbnail_id($post_id);
  $thumb_url = $thumb_id ? wp_get_attachment_image_url($thumb_id, 'full') : '';
  $thumb_alt = $thumb_id ? esc_attr(get_post_meta($thumb_id, '_wp_attachment_image_alt', true)) : '';
  $post_url  = esc_url(get_permalink());
  $post_title = esc_attr(get_the_title());
  $tags      = get_the_tags($post_id);
@endphp

<article @php(post_class('h-entry max-w-3xl mx-auto'))>

  {{-- Hero image --}}
  @if($thumb_url)
    <figure class="mb-10 -mx-6 lg:-mx-10 overflow-hidden aspect-[21/9]">
      <img
        src="{{ $thumb_url }}"
        alt="{{ $thumb_alt }}"
        loading="eager"
        decoding="async"
        class="w-full h-full object-cover"
      >
    </figure>
  @endif

  {{-- Header --}}
  <header class="mb-10">

    {{-- Category label --}}
    @php($cats = get_the_category($post_id))
    @if($cats)
      <a href="{{ esc_url(get_category_link($cats[0]->term_id)) }}"
         class="text-[0.65rem] font-semibold tracking-[0.2em] uppercase text-primary hover:text-primary transition-colors mb-4 inline-block">
        {{ esc_html($cats[0]->name) }}
      </a>
    @endif

    <h1 class="p-name text-[clamp(1.75rem,4vw,3rem)] font-light text-ink leading-tight mb-6">
      {!! $title !!}
    </h1>

    @include('partials.entry-meta')

    <div class="w-12 h-px bg-primary mt-8" aria-hidden="true"></div>

  </header>

  {{-- Body --}}
  <div class="e-content prose prose-lg prose-ink max-w-none
    prose-headings:prose-headings:font-light
    prose-a:text-primary prose-a:no-underline hover:prose-a:underline
    prose-blockquote:border-l-primary prose-blockquote:prose-blockquote:font-light prose-blockquote:text-xl
    prose-img:rounded">
    @php(the_content())
  </div>

  {{-- Page nav (multi-page posts) --}}
  @if($pagination)
    <nav class="mt-10 pt-6 border-t border-border" aria-label="{{ __('Pagine articolo', 'sage') }}">
      {!! $pagination !!}
    </nav>
  @endif

  {{-- Tags --}}
  @if($tags)
    <footer class="mt-10 pt-6 border-t border-border flex flex-wrap items-center gap-2">
      <span class="text-xs text-muted uppercase tracking-wider mr-2">{{ __('Tag:', 'sage') }}</span>
      @foreach($tags as $tag)
        <a href="{{ esc_url(get_tag_link($tag->term_id)) }}"
           class="inline-block text-xs border border-border px-3 py-1 hover:border-primary hover:text-primary transition-colors">
          {{ esc_html($tag->name) }}
        </a>
      @endforeach
    </footer>
  @endif

  {{-- Share --}}
  <div class="mt-8 pt-6 border-t border-border flex items-center gap-4 flex-wrap">
    <span class="text-xs font-semibold tracking-widest uppercase text-muted">{{ __('Condividi', 'sage') }}</span>

    <a href="https://www.facebook.com/sharer/sharer.php?u={{ rawurlencode($post_url) }}"
       target="_blank" rel="noopener noreferrer"
       class="text-xs font-medium text-ink hover:text-primary transition-colors"
       aria-label="{{ __('Condividi su Facebook', 'sage') }}">Facebook</a>

    <a href="https://twitter.com/intent/tweet?url={{ rawurlencode($post_url) }}&text={{ rawurlencode($post_title) }}"
       target="_blank" rel="noopener noreferrer"
       class="text-xs font-medium text-ink hover:text-primary transition-colors"
       aria-label="{{ __('Condividi su X (Twitter)', 'sage') }}">X / Twitter</a>

    <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ rawurlencode($post_url) }}&title={{ rawurlencode($post_title) }}"
       target="_blank" rel="noopener noreferrer"
       class="text-xs font-medium text-ink hover:text-primary transition-colors"
       aria-label="{{ __('Condividi su LinkedIn', 'sage') }}">LinkedIn</a>

    <a href="https://api.whatsapp.com/send?text={{ rawurlencode($post_title . ' ' . $post_url) }}"
       target="_blank" rel="noopener noreferrer"
       class="text-xs font-medium text-ink hover:text-primary transition-colors"
       aria-label="{{ __('Condividi su WhatsApp', 'sage') }}">WhatsApp</a>
  </div>

  {{-- Comments --}}
  @php(comments_template())

</article>

{{-- Related posts --}}
@include('partials.related-posts')
