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

  {{-- Page header --}}
  <div class="bg-cream border-b border-border page-header-offset pb-10">
    <div class="container">
      @if(function_exists('woocommerce_breadcrumb'))
        <div class="text-xs text-muted mb-4 [&_a]:text-muted [&_a:hover]:text-primary [&_.breadcrumb-separator]:mx-1">
          @php woocommerce_breadcrumb() @endphp
        </div>
      @endif
      <h1 class="font-serif text-[clamp(1.75rem,3.5vw,3rem)] font-light text-ink leading-tight">
        {!! $page_title !!}
      </h1>
    </div>
  </div>

  {{-- WooCommerce content --}}
  <div class="woocommerce-page bg-surface">
    <div class="container py-12 lg:py-16">
      @if(is_cart() || is_checkout() || is_account_page())
        @while(have_posts()) @php the_post() @endphp
          @php the_content() @endphp
        @endwhile
      @else
        @php woocommerce_content() @endphp
      @endif
    </div>
  </div>
@endsection
