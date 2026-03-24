{{--
  Quick View Modal
  - Si apre con evento Alpine "open-quick-view" con payload { id: productId }
  - Fetch via REST /wp-json/theme/v1/quick-view/{id}
  - Include: gallery, prezzo, varianti select, add-to-cart
  - Include in layouts/app.blade.php DOPO cart-drawer
--}}
@if(function_exists('WC'))
<div
  x-data="quickView()"
  @open-quick-view.window="open($event.detail.id)"
  @keydown.escape.window="close()"
  x-show="visible"
  x-cloak
  x-trap.inert.noscroll="visible"
  role="dialog"
  aria-modal="true"
  :aria-label="product ? product.title : '{{ __('Vista rapida prodotto', 'sage') }}'"
  class="fixed inset-0 z-150 flex items-center justify-center px-4 py-8"
  style="display:none"
>
  {{-- Backdrop --}}
  <div
    class="absolute inset-0 bg-ink/50 backdrop-blur-sm"
    @click="close()"
    aria-hidden="true"
    x-transition:enter="transition ease-out duration-250"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
  ></div>

  {{-- Panel --}}
  <div
    class="relative z-10 w-full max-w-3xl bg-white shadow-2xl max-h-[90vh] overflow-y-auto"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-95 translate-y-4"
    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100 translate-y-0"
    x-transition:leave-end="opacity-0 scale-95 translate-y-4"
  >
    {{-- Close --}}
    <button
      type="button"
      @click="close()"
      class="absolute top-4 right-4 z-20 text-muted hover:text-ink transition-colors"
      aria-label="{{ __('Chiudi', 'sage') }}"
    >
      <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
      </svg>
    </button>

    {{-- Loading state --}}
    <div x-show="loading" class="flex items-center justify-center py-20">
      <svg class="w-8 h-8 text-accent animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"/>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
      </svg>
    </div>

    {{-- Product content --}}
    <div x-show="!loading && product" class="grid grid-cols-1 sm:grid-cols-2">

      {{-- Gallery --}}
      <div class="bg-surface-alt aspect-square overflow-hidden">
        <img
          :src="product?.gallery?.[activeImg] || product?.thumb || ''"
          :alt="product?.title || ''"
          class="w-full h-full object-cover"
          loading="eager"
        >
        {{-- Thumbs --}}
        <template x-if="product?.gallery?.length > 1">
          <div class="flex gap-2 p-3 bg-white border-t border-border">
            <template x-for="(img, i) in product.gallery" :key="i">
              <button
                type="button"
                @click="activeImg = i"
                :class="activeImg === i ? 'border-ink' : 'border-border'"
                class="w-12 h-12 overflow-hidden border shrink-0 transition-colors"
              >
                <img :src="img" :alt="product.title + ' ' + (i+1)" class="w-full h-full object-cover">
              </button>
            </template>
          </div>
        </template>
      </div>

      {{-- Info --}}
      <div class="p-6 sm:p-8 flex flex-col">

        {{-- Category --}}
        <p class="text-xs font-semibold tracking-[0.2em] uppercase text-muted mb-2" x-text="product?.category || ''"></p>

        {{-- Title --}}
        <h2 class="font-serif text-xl font-light text-ink leading-snug mb-3" x-text="product?.title"></h2>

        {{-- Rating --}}
        <div x-show="product?.rating_count > 0" class="flex items-center gap-1.5 mb-3">
          <template x-for="i in 5" :key="i">
            <svg
              :class="i <= Math.round(product?.rating || 0) ? 'fill-gold text-gold' : 'fill-border text-border'"
              class="w-3 h-3" viewBox="0 0 24 24" aria-hidden="true"
            >
              <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>
          </template>
          <span class="text-xs text-muted" x-text="'(' + (product?.rating_count || 0) + ')'"></span>
        </div>

        {{-- Price --}}
        <div class="mb-4">
          <span class="font-serif text-2xl font-light text-ink" x-html="product?.price_html || ''"></span>
        </div>

        {{-- Short description --}}
        <div class="text-sm text-muted leading-relaxed mb-5 prose prose-sm max-w-none"
             x-html="product?.short_desc || ''"></div>

        {{-- Variants (select-based) --}}
        <template x-if="product?.attributes?.length">
          <div class="space-y-3 mb-5">
            <template x-for="attr in product.attributes" :key="attr.name">
              <div>
                <label class="block text-xs font-semibold tracking-wider uppercase text-ink mb-1.5" x-text="attr.label"></label>
                <select
                  x-model="selectedVariants[attr.name]"
                  class="form-input-luxury text-sm py-2.5"
                >
                  <option value="">{{ __('Seleziona', 'sage') }} <span x-text="attr.label"></span></option>
                  <template x-for="opt in attr.options" :key="opt">
                    <option :value="opt" x-text="opt"></option>
                  </template>
                </select>
              </div>
            </template>
          </div>
        </template>

        {{-- Stock --}}
        <div class="mb-5 text-xs font-semibold tracking-wider uppercase"
             :class="product?.in_stock ? 'text-success' : 'text-error'">
          <span x-text="product?.in_stock ? '{{ __('Disponibile', 'sage') }}' : '{{ __('Esaurito', 'sage') }}'"></span>
        </div>

        {{-- Add to cart --}}
        <div class="mt-auto space-y-2">
          <a
            :href="product?.add_to_cart_url || '#'"
            :class="product?.in_stock ? '' : 'opacity-50 pointer-events-none'"
            class="btn-primary w-full justify-center add_to_cart_button ajax_add_to_cart"
            :data-product_id="product?.id"
            rel="nofollow"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/>
            </svg>
            {{ __('Aggiungi al carrello', 'sage') }}
          </a>
          <a
            :href="product?.url || '#'"
            class="block text-center text-xs font-semibold tracking-wider uppercase text-muted hover:text-ink transition-colors py-2"
          >
            {{ __('Vedi prodotto completo →', 'sage') }}
          </a>
        </div>
      </div>
    </div>

    {{-- Error state --}}
    <div x-show="!loading && error" class="p-8 text-center">
      <p class="text-muted">{{ __('Impossibile caricare il prodotto. Riprova.', 'sage') }}</p>
    </div>

  </div>
</div>

<script>
function quickView() {
  return {
    visible: false,
    loading: false,
    product: null,
    error: false,
    activeImg: 0,
    selectedVariants: {},

    async open(productId) {
      if (!productId) return;
      this.visible  = true;
      this.loading  = true;
      this.product  = null;
      this.error    = false;
      this.activeImg = 0;
      this.selectedVariants = {};

      try {
        const url = (window.themeData?.restUrl || '/wp-json/') + 'theme/v1/quick-view/' + productId;
        const res = await fetch(url);
        if (!res.ok) throw new Error('fetch');
        this.product = await res.json();
      } catch {
        this.error = true;
      } finally {
        this.loading = false;
      }
    },

    close() {
      this.visible = false;
    },
  };
}
</script>
@endif
