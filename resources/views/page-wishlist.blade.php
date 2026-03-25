@extends('layouts.app')

@section('content')
  {{-- Page header --}}
  <div class="bg-cream border-b border-border pt-16 pb-10">
    <div class="container">
      <h1 class="font-serif text-4xl font-light text-ink leading-tight">
        {{ __('La mia Wishlist', 'sage') }}
      </h1>
    </div>
  </div>

  {{-- Wishlist products — rendered client-side via wishlist.js --}}
  <div class="bg-surface py-12 lg:py-16">
    <div class="container">
      <wishlist-products
        products-limit="50"
        empty-label="{{ esc_attr(__('La tua wishlist è vuota. Sfoglia i prodotti e aggiungi i tuoi preferiti!', 'sage')) }}"
        class="grid grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-10"
      ></wishlist-products>
    </div>
  </div>
@endsection
