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

    <p class="font-sans text-xs font-semibold tracking-[0.2em] uppercase text-gold mb-2" aria-hidden="true">
      {{ __('Continua a leggere', 'sage') }}
    </p>
    <h2 id="related-heading" class="font-serif text-2xl font-light text-ink mb-8">
      {{ __('Articoli correlati', 'sage') }}
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8" role="list" aria-label="{{ __('Articoli correlati', 'sage') }}">
      @foreach($related as $post)
        @php
          setup_postdata($post);
          $rid       = $post->ID;
          $rthumb_id = get_post_thumbnail_id($rid);
          $rthumb    = $rthumb_id ? wp_get_attachment_image_url($rthumb_id, 'medium_large') : '';
          $ralt      = $rthumb_id ? esc_attr(get_post_meta($rthumb_id, '_wp_attachment_image_alt', true)) : '';
          $rcats     = get_the_category($rid);
          $rperma    = esc_url(get_permalink($rid));
          $rwords    = str_word_count(wp_strip_all_tags($post->post_content));
          $rmin      = max(1, (int) ceil($rwords / 200));
        @endphp

        <article class="group flex flex-col" role="listitem">

          @if($rthumb)
            <a href="{{ $rperma }}" tabindex="-1" aria-hidden="true"
               class="block overflow-hidden aspect-[16/9] mb-4">
              <img src="{{ $rthumb }}" alt="{{ $ralt }}" loading="lazy" decoding="async"
                   class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
            </a>
          @endif

          @if($rcats)
            <span class="font-sans text-[0.65rem] font-semibold tracking-[0.2em] uppercase text-gold mb-2 block">
              {{ esc_html($rcats[0]->name) }}
            </span>
          @endif

          <h3 class="font-serif text-base font-light text-ink leading-snug mb-2 group-hover:text-primary transition-colors">
            <a href="{{ $rperma }}" class="relative after:absolute after:inset-0">
              {{ esc_html(get_the_title($rid)) }}
            </a>
          </h3>

          <div class="flex items-center gap-2 font-sans text-xs text-muted mt-auto pt-3 border-t border-border">
            <time datetime="{{ get_post_time('c', true, $post) }}">{{ get_the_date('j M Y', $post) }}</time>
            <span aria-hidden="true">·</span>
            <span>{{ $rmin }}&nbsp;min</span>
          </div>

        </article>

        @php(wp_reset_postdata())
      @endforeach
    </div>

  </aside>
@endif
