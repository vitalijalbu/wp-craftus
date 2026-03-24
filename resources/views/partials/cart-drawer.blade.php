{{--
  Off-canvas Cart Drawer
  - Opens on "open-cart" Alpine event (dispatched by header cart button).
  - Refreshed via WooCommerce `wc_fragment_refresh` AJAX on add-to-cart.
  - The inner .wc-cart-drawer-inner fragment is replaced by WC AJAX.
  - Place: @include('partials.cart-drawer') in layouts/app.blade.php
--}}
@if(function_exists('WC'))
<div
  x-data="cartDrawer()"
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
    style="display:none"
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
    style="display:none"
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
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
             aria-hidden="true" focusable="false">
          <path d="M18 6 6 18M6 6l12 12"/>
        </svg>
      </button>
    </div>

    {{-- Loading overlay --}}
    <div
      x-show="loading"
      class="absolute inset-0 z-10 bg-white/70 flex items-center justify-center"
      aria-live="polite"
      aria-label="{{ __('Aggiornamento carrello…', 'sage') }}"
    >
      <svg class="animate-spin w-7 h-7 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
      </svg>
    </div>

    {{-- Cart items — WC fragment --}}
    <div class="wc-cart-drawer-inner flex-1 overflow-y-auto px-6 py-4 min-h-0">
      @php
        $cart_items = WC()->cart ? WC()->cart->get_cart() : [];
      @endphp

      @if(empty($cart_items))
        <div class="flex flex-col items-center justify-center h-full text-center py-12">
          <svg class="w-12 h-12 text-border mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/></svg>
          <p class="font-serif text-lg font-light text-ink mb-1">{{ __('Il carrello è vuoto', 'sage') }}</p>
          <p class="text-sm text-muted mb-6">{{ __('Aggiungi qualcosa di bello!', 'sage') }}</p>
          <a href="{{ esc_url(wc_get_page_permalink('shop')) }}" @click="close()"
             class="btn-primary text-sm">{{ __('Vai allo shop', 'sage') }}</a>
        </div>
      @else
        <ul class="space-y-5" aria-label="{{ __('Prodotti nel carrello', 'sage') }}">
          @foreach($cart_items as $key => $item)
            @php
              $product  = $item['data'];
              $img      = $product->get_image('thumbnail', ['class' => 'w-full h-full object-cover']);
              $qty      = $item['quantity'];
              $subtotal = WC()->cart->get_product_subtotal($product, $qty);
            @endphp
            <li class="flex gap-4 items-start">
              <a href="{{ esc_url(get_permalink($item['product_id'])) }}" class="shrink-0 w-16 h-16 overflow-hidden bg-surface block">
                {!! $img !!}
              </a>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-ink leading-snug line-clamp-2">
                  <a href="{{ esc_url(get_permalink($item['product_id'])) }}" class="hover:text-primary transition-colors">
                    {{ $product->get_name() }}
                  </a>
                </p>
                <p class="text-xs text-muted mt-0.5">{{ __('Qta:', 'sage') }} {{ $qty }}</p>
                <p class="text-sm font-semibold text-ink mt-1">{!! $subtotal !!}</p>
              </div>
              <a href="{{ esc_url(wc_get_cart_remove_url($key)) }}"
                 aria-label="{{ __('Rimuovi', 'sage') . ' ' . esc_attr($product->get_name()) }}"
                 class="text-muted hover:text-red-500 transition-colors shrink-0 mt-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
              </a>
            </li>
          @endforeach
        </ul>
      @endif
    </div>

    {{-- Footer: totals + CTA --}}
    @if(!empty($cart_items))
      @php
        $threshold  = (float) get_theme_mod('free_shipping_threshold', 0);
        $cart_total = (float) (WC()->cart ? WC()->cart->get_cart_contents_total() : 0);
        $remaining  = max(0, $threshold - $cart_total);
        $progress   = $threshold > 0 ? min(100, round(($cart_total / $threshold) * 100)) : 0;
      @endphp

      {{-- Free shipping progress bar --}}
      @if($threshold > 0)
        <div class="free-shipping-bar shrink-0 bg-cream border-t border-border px-6 py-3">
          @if($remaining > 0)
            <p class="text-xs text-muted text-center mb-2">
              {{ sprintf(__('Aggiungi %s per la spedizione gratuita', 'sage'), '<strong class="text-ink">' . wc_price($remaining) . '</strong>') }}
            </p>
          @else
            <p class="text-xs text-success font-semibold text-center mb-2">
              ✓ {{ __('Hai ottenuto la spedizione gratuita!', 'sage') }}
            </p>
          @endif
          <div class="h-1 bg-border rounded-full overflow-hidden">
            <div
              class="h-full transition-all duration-500 ease-out {{ $remaining <= 0 ? 'bg-success' : 'bg-accent' }}"
              style="width: {{ $progress }}%"
              role="progressbar"
              aria-valuenow="{{ $progress }}"
              aria-valuemin="0"
              aria-valuemax="100"
            ></div>
          </div>
        </div>
      @endif

      <div class="shrink-0 border-t border-border px-6 py-5 space-y-3 bg-white">
        <div class="flex justify-between text-sm">
          <span class="text-muted">{{ __('Subtotale', 'sage') }}</span>
          <span class="font-semibold text-ink">{!! WC()->cart->get_cart_subtotal() !!}</span>
        </div>
        @if($threshold <= 0)
          <p class="text-xs text-muted">{{ __('Spese di spedizione calcolate al checkout.', 'sage') }}</p>
        @endif
        <a href="{{ esc_url(wc_get_checkout_url()) }}"
           class="btn-primary w-full text-center block">
          {{ __('Vai al checkout', 'sage') }}
        </a>
        <a href="{{ esc_url(wc_get_cart_url()) }}"
           class="block text-center text-xs text-muted hover:text-primary transition-colors underline underline-offset-2">
          {{ __('Vedi carrello completo', 'sage') }}
        </a>
      </div>
    @endif

  </div>
</div>

<script>
function cartDrawer() {
  return {
    isOpen: false,
    count:  {{ (function_exists('WC') && WC()->cart) ? (int) WC()->cart->get_cart_contents_count() : 0 }},
    loading: false,

    init() {
      // WooCommerce fires added_to_cart as a jQuery custom event, not a native DOM event
      jQuery(document.body).on('added_to_cart', (e, fragments, cart_hash) => {
        if (fragments) {
          jQuery.each(fragments, (selector, html) => jQuery(selector).replaceWith(html));
          const countEl = document.querySelector('[data-cart-count]');
          if (countEl) this.count = parseInt(countEl.dataset.cartCount || '0', 10);
          this.loading = false;
        } else {
          this.refreshFragment();
        }
        this.open();
      });

      // WC fragment refresh
      jQuery(document.body).on('wc_fragment_refresh', () => this.refreshFragment());
    },

    open()  { this.isOpen = true; },
    close() { this.isOpen = false; },

    refreshFragment() {
      if (typeof jQuery === 'undefined') return;
      this.loading = true;
      jQuery.post(
        wc_cart_fragments_params?.ajax_url ?? '/wp-admin/admin-ajax.php',
        { action: 'woocommerce_get_refreshed_fragments' },
        (res) => {
          if (res?.fragments) {
            jQuery.each(res.fragments, (key, val) => {
              jQuery(key).replaceWith(val);
            });
          }
          this.count   = res?.cart_hash ? (parseInt(jQuery('.cart-count-fragment').first().text()) || 0) : this.count;
          this.loading = false;
        }
      );
    },
  };
}
</script>
@endif
