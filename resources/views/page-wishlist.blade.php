@php
  $shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop');
@endphp

@extends('layouts.app')

@section('content')
  {{-- Page header --}}
  <div class="bg-cream border-b border-border page-header-offset pb-10">
    <div class="container">
      @include('partials.breadcrumb')
      <h1 class="text-[clamp(1.75rem,3.5vw,3rem)] font-light text-ink leading-tight">
        {{ __('La mia Wishlist', 'sage') }}
      </h1>
    </div>
  </div>

  {{-- Wishlist content --}}
  <div class="container py-12 lg:py-16"
       x-data="{ hasItems: (JSON.parse(localStorage.getItem('theme:wishlist') || '[]')).length > 0 }">

    {{-- No-JS fallback --}}
    <noscript>
      <x-empty-state
        icon="icons.heart"
        :title="__('La tua wishlist è vuota', 'sage')"
        :message="__('Sfoglia i prodotti e aggiungi i tuoi preferiti!', 'sage')"
        :buttons="[
          ['url' => home_url('/'), 'label' => __('Vai alla Home', 'sage'), 'style' => 'primary'],
          ['url' => $shop_url, 'label' => __('Sfoglia i prodotti', 'sage'), 'style' => 'outline'],
        ]"
      />
    </noscript>

    {{-- Product grid (rendered by JS custom element) --}}
    <div x-show="hasItems" x-cloak>
      <wishlist-products
        products-limit="50"
        empty-label="{{ esc_attr(__('La tua wishlist è vuota', 'sage')) }}"
        class="grid grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-10"
      ></wishlist-products>

      {{-- Actions bar --}}
      <div class="mt-10 flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-border pt-6">
        <a href="{{ esc_url($shop_url) }}" class="btn-outline">
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
    <div x-show="!hasItems" x-cloak>
      <x-empty-state
        icon="icons.heart"
        :title="__('La tua wishlist è vuota', 'sage')"
        :message="__('Sfoglia i prodotti e aggiungi i tuoi preferiti!', 'sage')"
        :buttons="[
          ['url' => home_url('/'), 'label' => __('Vai alla Home', 'sage'), 'style' => 'primary'],
          ['url' => $shop_url, 'label' => __('Sfoglia i prodotti', 'sage'), 'style' => 'outline'],
        ]"
      />
    </div>

  </div>
@endsection
