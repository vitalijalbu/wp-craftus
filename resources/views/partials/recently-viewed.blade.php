{{--
  Recently Viewed Products — localStorage-based
  Usage: @include('partials.recently-viewed', ['exclude_id' => $product_id])
  - Mostra gli ultimi 6 prodotti visti (escludendo quello corrente)
  - I dati (title, url, price, thumb) vengono salvati via JS nel product-card.blade.php
    e sulle pagine singolo prodotto (single-product).
--}}
<section
  class="recently-viewed-section border-t border-border mt-16 pt-12 pb-12"
  x-data="recentlyViewed({{ $exclude_id ?? 0 }})"
  x-init="load()"
  x-show="items.length > 0"
  x-cloak
  aria-label="{{ __('Visti di recente', 'sage') }}"
>
  <div class="container">
    <div class="flex items-center justify-between mb-8">
      <h2 class="font-serif text-2xl font-light text-ink">{{ __('Visti di recente', 'sage') }}</h2>
      <button
        type="button"
        @click="clear()"
        class="text-xs font-semibold tracking-wider uppercase text-muted hover:text-ink transition-colors"
      >
        {{ __('Cancella', 'sage') }}
      </button>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
      <template x-for="item in items" :key="item.id">
        <div class="group">
          <a :href="item.url" class="block aspect-square bg-surface-alt overflow-hidden mb-3">
            <img
              :src="item.thumb"
              :alt="item.title"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
              loading="lazy"
              x-show="item.thumb"
            >
            <div x-show="!item.thumb" class="w-full h-full flex items-center justify-center text-border">
              <x-icons.image-placeholder x-show="!item.thumb" class="w-8 h-8" stroke-width="1" />
            </div>
          </a>
          <a :href="item.url" class="block text-xs font-medium text-ink hover:text-accent transition-colors leading-snug line-clamp-2 mb-1" x-text="item.title"></a>
          <p class="text-xs text-muted" x-html="item.price"></p>
        </div>
      </template>
    </div>
  </div>
</section>
