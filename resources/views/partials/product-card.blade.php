@php
  /**
   * Product Card partial
   *
   * @param WC_Product $product  WooCommerce product object
   * @param bool       $show_cta Whether to show the add-to-cart overlay (default: true)
   */
  if (!isset($product) || !($product instanceof WC_Product)) {
    return;
  }

  $product_id    = $product->get_id();
  $product_link  = get_permalink($product_id);
  $product_name  = $product->get_name();
  $product_price = $product->get_price_html();
  $product_sku   = $product->get_sku();
  $is_on_sale    = $product->is_on_sale();
  $is_featured   = $product->is_featured();
  $is_virtual    = $product->is_virtual();
  $stock_status  = $product->get_stock_status(); // 'instock' | 'outofstock' | 'onbackorder'

  // Thumbnail
  $thumb_id  = $product->get_image_id();
  $thumb_url = $thumb_id
    ? wp_get_attachment_image_url($thumb_id, 'woocommerce_thumbnail')
    : wc_placeholder_img_src('woocommerce_thumbnail');
  $thumb_srcset = $thumb_id
    ? wp_get_attachment_image_srcset($thumb_id, 'woocommerce_thumbnail')
    : '';
  $thumb_alt = $thumb_id
    ? (get_post_meta($thumb_id, '_wp_attachment_image_alt', true) ?: $product_name)
    : $product_name;

  // Category
  $categories = wc_get_product_category_list($product_id, ', ');
  $first_cat  = '';
  $terms = get_the_terms($product_id, 'product_cat');
  if ($terms && !is_wp_error($terms)) {
    $first_cat = $terms[0]->name ?? '';
  }

  // Add to cart
  $add_to_cart_url  = $product->add_to_cart_url();
  $add_to_cart_text = $product->add_to_cart_text();
  $is_purchasable   = $product->is_purchasable() && $product->is_in_stock();
@endphp

<article
  class="product-card"
  aria-label="{{ esc_attr($product_name) }}"
  itemscope
  itemtype="https://schema.org/Product"
>
  {{-- Image wrap --}}
  <div class="product-card__image-wrap">
    <a
      href="{{ $product_link }}"
      tabindex="-1"
      aria-hidden="true"
    >
      <img
        src="{{ $thumb_url }}"
        alt="{{ esc_attr($thumb_alt) }}"
        @if($thumb_srcset) srcset="{{ $thumb_srcset }}" @endif
        sizes="(max-width: 640px) 50vw, (max-width: 1024px) 33vw, 25vw"
        class="w-full h-full object-cover"
        loading="lazy"
        decoding="async"
        itemprop="image"
      >
    </a>

    {{-- Badges --}}
    <div class="product-card__badge flex flex-col gap-1">
      @if($is_on_sale)
        <span class="badge badge-primary">{{ __('Offerta', 'sage') }}</span>
      @endif
      @if($is_featured && !$is_on_sale)
        <span class="badge">{{ __('In evidenza', 'sage') }}</span>
      @endif
      @if($stock_status === 'outofstock')
        <span class="badge bg-muted/80">{{ __('Esaurito', 'sage') }}</span>
      @endif
    </div>

    {{-- Wishlist button (placeholder — activate with YITH Wishlist) --}}
    <button
      type="button"
      class="product-card__wishlist"
      aria-label="{{ sprintf(__('Aggiungi %s alla wishlist', 'sage'), esc_attr($product_name)) }}"
      data-product-id="{{ $product_id }}"
    >
      <x-icons.heart class="size-4 text-ink" />
    </button>

    {{-- Add to cart overlay --}}
    @if($is_purchasable)
      <div class="product-card__overlay bg-white/95">
        <a
          href="{{ $add_to_cart_url }}"
          data-product_id="{{ $product_id }}"
          data-product_sku="{{ $product_sku }}"
          class="btn-primary btn-sm w-full justify-center add_to_cart_button ajax_add_to_cart"
          rel="nofollow"
          aria-label="{{ sprintf(__('Aggiungi %s al carrello', 'sage'), esc_attr($product_name)) }}"
        >
          <span class="btn-label">{{ $add_to_cart_text }}</span>
          <svg class="btn-spinner" fill="none" viewBox="0 0 24 24" aria-hidden="true" width="16" height="16">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
          </svg>
        </a>
      </div>
    @endif
  </div>

  {{-- Card body --}}
  <div class="product-card__body">

    @if($first_cat)
      <p class="product-card__category">{{ $first_cat }}</p>
    @endif

    <a
      href="{{ $product_link }}"
      class="product-card__title"
      itemprop="name"
    >{{ $product_name }}</a>

    {{-- Rating --}}
    @if($product->get_rating_count() > 0)
      <div
        class="flex items-center gap-1.5 mb-2"
        aria-label="{{ sprintf(__('Valutazione: %.1f su 5', 'sage'), $product->get_average_rating()) }}"
      >
        @php $rating = round($product->get_average_rating()); @endphp
        @for($i = 0; $i < 5; $i++)
          <x-icons.star class="size-3 {{ $i < $rating ? 'fill-primary text-primary' : 'fill-border text-border' }}" />
        @endfor
        <span class="text-muted">({{ $product->get_rating_count() }})</span>
      </div>
    @endif

    {{-- Price --}}
    <div
      class="product-card__price mt-auto pt-3"
      itemprop="offers"
      itemscope
      itemtype="https://schema.org/Offer"
    >
      {!! $product_price !!}
      <meta itemprop="price" content="{{ $product->get_price() }}">
      <meta itemprop="priceCurrency" content="{{ get_woocommerce_currency() }}">
      <link itemprop="availability" href="{{ $stock_status === 'instock' ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}">
    </div>

  </div>
</article>
