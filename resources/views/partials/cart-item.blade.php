@php
  /**
   * Cart Item partial — usato in cart-drawer-content.
   *
   * @param string $key   Chiave item WC cart
   * @param array  $item  Item array da WC()->cart->get_cart()
   */
  $product  = $item['data'];
  $img      = $product->get_image('thumbnail', ['class' => 'w-full h-full object-cover']);
  $qty      = $item['quantity'];
  $subtotal = WC()->cart->get_product_subtotal($product, $qty);
  $link     = esc_url(get_permalink($item['product_id']));
@endphp

<li class="cart-item flex gap-4 items-start">

  {{-- Immagine --}}
  <a href="{{ $link }}" class="shrink-0 w-16 h-16 overflow-hidden bg-surface-alt block aspect-square" tabindex="-1" aria-hidden="true">
    {!! $img !!}
  </a>

  {{-- Info --}}
  <div class="flex-1 min-w-0">
    <p class="text-sm font-medium text-ink leading-snug line-clamp-2">
      <a href="{{ $link }}" class="hover:text-primary transition-colors">
        {{ $product->get_name() }}
      </a>
    </p>
    <p class="text-xs text-muted mt-0.5">{{ __('Qta:', 'sage') }} {{ $qty }}</p>
    <p class="cart-item__price">{!! $subtotal !!}</p>
  </div>

  {{-- Rimuovi --}}
  <a href="{{ esc_url(wc_get_cart_remove_url($key)) }}"
     aria-label="{{ __('Rimuovi', 'sage') . ' ' . esc_attr($product->get_name()) }}"
     class="text-muted hover:text-error transition-colors shrink-0 mt-0.5">
    <x-icons.x-mark class="w-[14px] h-[14px]" />
  </a>

</li>
