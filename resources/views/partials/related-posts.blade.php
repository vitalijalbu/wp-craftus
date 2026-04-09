@php
  $post_id = get_the_ID();
  $cats    = get_the_category($post_id);
  $cat_ids = $cats ? array_column($cats, 'term_id') : [];

  $related = $cat_ids ? get_posts([
    'post_type'           => 'post',
    'posts_per_page'      => 3,
    'post__not_in'        => [$post_id],
    'category__in'        => $cat_ids,
    'orderby'             => 'rand',
    'ignore_sticky_posts' => true,
  ]) : [];
@endphp

@if($related)
  <aside class="mt-16 pt-12 border-t border-border" aria-labelledby="related-heading">

    <p class="text-xs font-semibold tracking-[0.2em] uppercase text-muted mb-2" aria-hidden="true">
      {{ __('Continua a leggere', 'sage') }}
    </p>
    <h2 id="related-heading" class="font-serif text-2xl font-light text-ink mb-8">
      {{ __('Articoli correlati', 'sage') }}
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8" role="list" aria-label="{{ __('Articoli correlati', 'sage') }}">
      @foreach($related as $post)
        @include('partials.post-card', [
          'post'         => $post,
          'show_excerpt' => false,
          'image_size'   => 'medium_large',
        ])
      @endforeach
    </div>

  </aside>
@endif
