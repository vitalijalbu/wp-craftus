{{--
  Breadcrumb partial
  Supports: Yoast SEO, Rank Math, plain WP fallback.
  Usage: @include('partials.breadcrumb')
--}}
@php
  $bc_html = '';

  if (function_exists('yoast_breadcrumb')) {
      ob_start();
      yoast_breadcrumb('<nav aria-label="' . esc_attr(__('Breadcrumb', 'sage')) . '" class="breadcrumb">', '</nav>');
      $bc_html = ob_get_clean();
  } elseif (function_exists('rank_math_the_breadcrumbs')) {
      ob_start();
      rank_math_the_breadcrumbs();
      $bc_html = ob_get_clean();
  } else {
      // Minimal WP fallback
      if (!is_front_page()) {
          $sep   = '<span class="mx-1 text-border" aria-hidden="true">/</span>';
          $items = '<a href="' . esc_url(home_url('/')) . '" class="hover:text-primary transition-colors">'
                 . __('Home', 'sage') . '</a>';

          if (is_singular()) {
              $cats = get_the_category();
              if ($cats) {
                  $items .= $sep . '<a href="' . esc_url(get_category_link($cats[0]->term_id))
                          . '" class="hover:text-primary transition-colors">'
                          . esc_html($cats[0]->name) . '</a>';
              }
              $items .= $sep . '<span aria-current="page">' . esc_html(get_the_title()) . '</span>';
          } elseif (is_category()) {
              $items .= $sep . '<span aria-current="page">' . single_cat_title('', false) . '</span>';
          } elseif (is_tag()) {
              $items .= $sep . '<span aria-current="page">' . single_tag_title('', false) . '</span>';
          } elseif (is_archive()) {
              $items .= $sep . '<span aria-current="page">' . get_the_archive_title() . '</span>';
          } elseif (is_search()) {
              $items .= $sep . '<span aria-current="page">' . __('Ricerca', 'sage') . '</span>';
          } elseif (is_404()) {
              $items .= $sep . '<span aria-current="page">404</span>';
          }

          $bc_html = '<nav aria-label="' . esc_attr(__('Breadcrumb', 'sage')) . '" class="breadcrumb">'
                   . '<ol class="flex flex-wrap items-center font-sans text-xs text-muted gap-0">'
                   . '<li>' . $items . '</li>'
                   . '</ol></nav>';
      }
  }
@endphp

@if($bc_html)
  <div class="mb-4 font-sans text-xs text-muted [&_a]:hover:text-primary [&_a]:transition-colors [&_ol]:flex [&_ol]:flex-wrap [&_ol]:items-center [&_ol]:gap-1">
    {!! $bc_html !!}
  </div>
@endif
