{{--
  Off-canvas Cart Drawer
  - Opens on "open-cart" Alpine event (dispatched by header cart button).
  - Refreshed via WooCommerce `wc_fragment_refresh` AJAX on add-to-cart.
  - The inner .wc-cart-drawer-inner fragment is replaced by WC AJAX.
  - Place: @include('partials.cart-drawer') in layouts/app.blade.php
--}}
@if(function_exists('WC'))
<div
  x-data="cartDrawer"
  x-init="init()"
  @open-cart.window="open()"
  @keydown.escape.window="close()"
>

  {{-- Backdrop --}}
  <div
    x-show="isOpen"
    x-cloak
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @click="close()"
    class="fixed inset-0 z-60 bg-ink/40 backdrop-blur-sm"
    aria-hidden="true"
    x-cloak
  ></div>

  {{-- Drawer panel --}}
  <div
    x-show="isOpen"
    x-cloak
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-x-full"
    x-transition:enter-end="opacity-100 translate-x-0"
    x-transition:leave="transition ease-in duration-250"
    x-transition:leave-start="opacity-100 translate-x-0"
    x-transition:leave-end="opacity-0 translate-x-full"
    x-trap.inert.noscroll="isOpen"
    role="dialog"
    aria-modal="true"
    aria-label="{{ __('Carrello', 'sage') }}"
    class="fixed inset-y-0 right-0 z-70 w-full max-w-md bg-white shadow-2xl flex flex-col"
  >

    {{-- Header --}}
    <div class="flex items-center justify-between px-6 py-5 border-b border-border shrink-0">
      <h2 class="font-serif text-lg font-light text-ink">
        {{ __('Carrello', 'sage') }}
        <span x-text="'(' + count + ')'" class="text-sm text-muted ml-1"></span>
      </h2>
      <button
        @click="close()"
        type="button"
        aria-label="{{ __('Chiudi carrello', 'sage') }}"
        class="text-muted hover:text-ink transition-colors p-1"
      >
        <x-icons.x-mark class="w-[18px] h-[18px]" />
      </button>
    </div>

    {{-- Loading overlay --}}
    <div
      x-show="loading"
      class="absolute inset-0 z-10 bg-white/70 flex items-center justify-center"
      aria-live="polite"
      aria-label="{{ __('Aggiornamento carrello…', 'sage') }}"
    >
      <x-icons.spinner class="animate-spin w-7 h-7 text-primary" />
    </div>

    {{-- Cart items + footer — WC fragment --}}
    @include('partials.cart-drawer-content')

  </div>
</div>

@endif
