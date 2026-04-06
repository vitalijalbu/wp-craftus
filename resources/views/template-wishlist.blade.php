@php
/**
 * Template Name: Wishlist
 * Template Post Type: page
 */

$shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop');
@endphp

@extends('layouts.app')

@section('content')

  {{-- Page header --}}
  <div class="bg-cream border-b border-border pt-16 pb-10">
    <div class="container">
      @include('partials.breadcrumb')
      <h1 class="font-serif text-[clamp(1.75rem,3.5vw,3rem)] font-light text-ink leading-tight">
        {{ __('La mia wishlist', 'sage') }}
      </h1>
    </div>
  </div>

  {{-- Wishlist content --}}
  <div class="container py-12 lg:py-16"
       x-data="{ hasItems: (JSON.parse(localStorage.getItem('theme:wishlist') || '[]')).length > 0 }">

    {{-- Empty state (no JS / truly empty) --}}
    <noscript>
      <div class="text-center py-20">
        <p class="font-serif text-2xl font-light text-ink mb-3">{{ __('La tua wishlist è vuota', 'sage') }}</p>
        <a href="{{ esc_url($shop_url) }}" class="btn-primary">{{ __('Sfoglia i prodotti', 'sage') }}</a>
      </div>
    </noscript>

    {{-- Product grid (rendered by JS custom element) --}}
    <div x-show="hasItems" x-cloak>
      <wishlist-products
        products-limit="24"
        empty-label="{{ esc_attr(__('La tua wishlist è vuota.', 'sage')) }}"
        class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6"
      ></wishlist-products>

      {{-- Actions bar --}}
      <div class="mt-10 flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-border pt-6">
        <a href="{{ esc_url($shop_url) }}" class="btn-outline text-sm">
          {{ __('← Continua lo shopping', 'sage') }}
        </a>
        <button
          type="button"
          onclick="localStorage.removeItem('theme:wishlist'); window.location.reload();"
          class="text-xs font-semibold tracking-wider uppercase text-muted hover:text-error transition-colors"
        >
          {{ __('Svuota wishlist', 'sage') }}
        </button>
      </div>
    </div>

    {{-- Alpine empty state --}}
    <div x-show="!hasItems" class="text-center py-20" x-cloak>
      <x-icons.heart class="w-16 h-16 text-border mx-auto mb-5" stroke-width="1" />
      <p class="font-serif text-2xl font-light text-ink mb-2">{{ __('La tua wishlist è vuota', 'sage') }}</p>
      <p class="text-muted mb-8">{{ __('Salva i prodotti che ami per ritrovarli facilmente.', 'sage') }}</p>
      <a href="{{ esc_url($shop_url) }}" class="btn-primary">{{ __('Sfoglia i prodotti', 'sage') }}</a>
    </div>

  </div>

@endsection
