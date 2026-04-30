@php
  /**
   * Shared archive loop — included by archive.blade.php and taxonomy.blade.php.
   * Renders page header, post grid (adaptive to post type), and pagination.
   */
  $archive_title = get_the_archive_title();
  $archive_desc  = get_the_archive_description();
  $post_type     = get_queried_object()?->post_type
    ?? get_query_var('post_type')
    ?: 'post';
@endphp

{{-- Page header --}}
<div class="bg-cream border-b border-border page-header-offset pb-10">
  <div class="container">
    @include('partials.breadcrumb')
    <h1 class="text-[clamp(1.75rem,3.5vw,3rem)] font-light text-ink leading-tight mt-4">
      {!! wp_kses_post($archive_title) !!}
    </h1>
    @if($archive_desc)
      <div class="section-subtitle mt-3 text-muted prose-sm">
        {!! wp_kses_post($archive_desc) !!}
      </div>
    @endif
  </div>
</div>

<div class="bg-surface">
  <div class="container py-14 lg:py-18">

    @if(have_posts())

      <div
        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-10"
        data-scroll="stagger"
      >
        @while(have_posts())
          @php the_post() @endphp
          @php
            $pid       = get_the_ID();
            $thumb_id  = get_post_thumbnail_id($pid);
            $thumb_alt = $thumb_id
              ? esc_attr(get_post_meta($thumb_id, '_wp_attachment_image_alt', true))
              : esc_attr(get_the_title());
            $perma     = esc_url(get_permalink());
            $excerpt   = wp_trim_words(get_the_excerpt(), 22, '…');
          @endphp

          <article
            class="group flex flex-col border border-border hover:border-ink transition-colors duration-250"
            data-scroll-item
          >
            @if($thumb_id)
              <a href="{{ $perma }}" tabindex="-1" aria-hidden="true"
                 class="block overflow-hidden aspect-[4/3]">
                <x-picture
                  :id="(int) $thumb_id"
                  :alt="$thumb_alt"
                  class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                  sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw"
                />
              </a>
            @endif

            <div class="p-6 flex flex-col flex-1">
              <h2 class="text-xl font-light text-ink group-hover:text-primary transition-colors mb-3 leading-snug">
                <a href="{{ $perma }}" class="relative after:absolute after:inset-0">
                  {{ get_the_title() }}
                </a>
              </h2>
              @if($excerpt)
                <p class="text-sm text-muted leading-relaxed line-clamp-3 flex-1 mb-4">
                  {{ $excerpt }}
                </p>
              @endif
              <time
                datetime="{{ get_post_time('c') }}"
                class="text-xs text-muted/60 mt-auto pt-4 border-t border-border"
              >
                {{ get_the_date('j M Y') }}
              </time>
            </div>
          </article>
        @endwhile
      </div>

      {{-- Pagination --}}
      <div class="mt-14 flex justify-center">
        <nav aria-label="{{ __('Navigazione archivio', 'sage') }}">
          @php
            $prev_icon = \Roots\view('components.icons.chevron-left', [
              'attributes' => new \Illuminate\View\ComponentAttributeBag(['class' => 'size-4'])
            ])->render();
            $next_icon = \Roots\view('components.icons.arrow-right', [
              'attributes' => new \Illuminate\View\ComponentAttributeBag(['class' => 'size-4'])
            ])->render();
          @endphp
          {!! get_the_posts_pagination([
            'mid_size'  => 2,
            'prev_text' => $prev_icon . '<span class="sr-only">' . esc_html__('Precedente', 'sage') . '</span>',
            'next_text' => '<span class="sr-only">' . esc_html__('Successivo', 'sage') . '</span>' . $next_icon,
            'class'     => 'pagination flex items-center gap-2',
          ]) !!}
        </nav>
      </div>

    @else
      <p class="text-muted font-sans">{{ __('Nessun contenuto trovato.', 'sage') }}</p>
    @endif

  </div>
</div>
