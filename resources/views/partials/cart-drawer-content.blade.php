{{--
  Cart Drawer inner content — rendered as a WC AJAX fragment.
  Fragment selector: div.wc-cart-drawer-fragment
--}}
@php
  $cart_items = WC()->cart ? WC()->cart->get_cart() : [];
@endphp

<div class="wc-cart-drawer-fragment flex-1 flex flex-col min-h-0">

  {{-- Cart items --}}
  <div class="flex-1 overflow-y-auto px-6 py-4 min-h-0">
    @if(empty($cart_items))
      <div class="flex flex-col items-center justify-center h-full text-center py-12">
        <x-icons.cart class="w-12 h-12 text-border mb-4" stroke-width="1" />
        <p class="font-serif text-lg font-light text-ink mb-1">{{ __('Il carrello è vuoto', 'sage') }}</p>
        <p class="text-sm text-muted mb-6">{{ __('Aggiungi qualcosa di bello!', 'sage') }}</p>
        <a href="{{ esc_url(wc_get_page_permalink('shop')) }}"
           class="btn-primary btn-sm">{{ __('Vai allo shop', 'sage') }}</a>
      </div>
    @else
      <ul class="space-y-5" aria-label="{{ __('Prodotti nel carrello', 'sage') }}">
        @foreach($cart_items as $key => $item)
          @include('partials.cart-item', ['key' => $key, 'item' => $item])
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
            {!! sprintf(__('Aggiungi %s per la spedizione gratuita', 'sage'), '<strong class="text-ink">' . wc_price($remaining) . '</strong>') !!}
          </p>
        @else
          <p class="text-xs text-success font-semibold text-center mb-2">
            ✓ {{ __('Hai ottenuto la spedizione gratuita!', 'sage') }}
          </p>
        @endif
        <div class="h-1 bg-border rounded-full overflow-hidden">
          <div
            class="h-full transition-all duration-500 ease-out {{ $remaining <= 0 ? 'bg-success' : 'bg-primary' }}"
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
