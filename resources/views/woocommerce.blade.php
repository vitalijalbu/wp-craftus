@extends('layouts.app')

@section('content')
  @php
    $page_title = get_the_title(wc_get_page_id('shop'));
    if (is_cart())     { $page_title = __('Carrello', 'sage'); }
    if (is_checkout()) { $page_title = __('Checkout', 'sage'); }
    if (is_account_page()) { $page_title = __('Il mio account', 'sage'); }
    if (is_product_category() || is_product_tag()) { $page_title = single_term_title('', false); }
    if (is_product()) { $page_title = get_the_title(); }
    if (is_shop()) { $page_title = get_the_title(wc_get_page_id('shop')); }
  @endphp

  {{-- Page header
  <div class="bg-cream border-b border-border/80 page-header-offset pb-10 lg:pb-12">
    <div class="container">
      @if(function_exists('woocommerce_breadcrumb'))
        <div class="text-xs text-muted mb-4 [&_a]:text-muted [&_a:hover]:text-primary [&_.breadcrumb-separator]:mx-1">
          @php woocommerce_breadcrumb() @endphp
        </div>
      @endif
      <h1 class="text-[clamp(1.5rem,2.4vw,2.1875rem)] font-medium text-ink leading-[1.28]">
        {!! $page_title !!}
      </h1>
    </div>
  </div> --}}

  {{-- WooCommerce content --}}
  <div class="woocommerce-page bg-surface">
    <div class="container py-14 lg:py-20">
      @if(is_cart() || is_checkout() || is_account_page())
        @while(have_posts()) @php the_post() @endphp
          @php the_content() @endphp
        @endwhile
      @else
        @php woocommerce_content() @endphp
      @endif
    </div>

    @if(is_product())
      @php do_action('theme_after_woocommerce_container') @endphp
    @endif
  </div>
@endsection
