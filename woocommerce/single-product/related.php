@php
/** @var WC_Product $product */
$image_id = $product->get_image_id();
$image_url = $image_id ? wp_get_attachment_image_url($image_id, 'medium') : wc_placeholder_img_src();
@endphp

<div class="h-full flex flex-col bg-white rounded-2xl overflow-hidden shadow-sm">

  {{-- Image --}}
  <a href="{{ get_permalink($product->get_id()) }}" class="block">
    <div class="aspect-square bg-gray-100 overflow-hidden">
      <img
        src="{{ $image_url }}"
        alt="{{ $product->get_name() }}"
        class="w-full h-full object-cover"
        loading="lazy">
    </div>
  </a>

  {{-- Content --}}
  <div class="flex flex-col flex-1 p-4">

    {{-- Title --}}
    <h3 class="text-sm font-medium text-ink mb-2 line-clamp-2">
      <a href="{{ get_permalink($product->get_id()) }}">
        {{ $product->get_name() }}
      </a>
    </h3>

    {{-- Price --}}
    <div class="text-base font-semibold text-ink mb-4">
      {!! $product->get_price_html() !!}
    </div>

    {{-- Spacer + CTA --}}
    <div class="mt-auto">
      <a
        href="{{ get_permalink($product->get_id()) }}"
        class="inline-block w-full text-center bg-black text-white text-sm py-2 rounded-lg hover:bg-gray-800 transition">
        Vedi prodotto
      </a>
    </div>

  </div>
</div>