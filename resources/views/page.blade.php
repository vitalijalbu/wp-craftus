@extends('layouts.app')

@section('content')
  @while(have_posts())
    @php the_post() @endphp

    @if(function_exists('is_woocommerce') && (is_woocommerce() || is_cart() || is_checkout() || is_account_page()))
      @php woocommerce_content() @endphp
    @else
      @php the_content() @endphp
    @endif

  @endwhile
@endsection
