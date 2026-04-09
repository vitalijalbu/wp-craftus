{{--
  Product Filters Sidebar — AJAX faceted search
  Emette evento Alpine "product-filter-changed" con i filtri attivi.
  Il component Alpine productGrid() in woocommerce.blade.php ascolta e ricarica i prodotti.

  @var array $current_cats  — categorie attive (da query string)
  @var float $min_price     — prezzo minimo
  @var float $max_price     — prezzo massimo
  @var array $all_cats      — tutte le categorie prodotto
--}}
@php
  $all_cats    = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => true, 'parent' => 0, 'number' => 30]);
  $all_cats    = is_array($all_cats) ? $all_cats : [];
  $current_cats = array_map('absint', (array) ($_GET['cats'] ?? []));
  $min_price   = (float) ($_GET['min_price'] ?? 0);
  $max_price   = (float) ($_GET['max_price'] ?? 0);

  // Max price from WC — get highest product price
  global $wpdb;
  $max_product_price = (float) $wpdb->get_var(
    "SELECT MAX(meta_value+0) FROM {$wpdb->postmeta}
     WHERE meta_key = '_price'
     AND post_id IN (SELECT ID FROM {$wpdb->posts} WHERE post_status = 'publish' AND post_type = 'product')"
  );
  $max_product_price = $max_product_price ?: 1000;
@endphp

<aside
  class="product-filters"
  x-data="productFilters()"
  x-init="init()"
  aria-label="{{ __('Filtri prodotto', 'sage') }}"
>
  {{-- Mobile toggle --}}
  <div class="lg:hidden mb-4">
    <button
      type="button"
      @click="mobileOpen = !mobileOpen"
      class="btn-outline btn-sm w-full justify-between"
      :aria-expanded="mobileOpen"
    >
      <span class="flex items-center gap-2">
        <span
          class="burger-icon"
          :class="mobileOpen ? 'is-active' : ''"
          aria-hidden="true"
        >
          <span class="burger-icon__line"></span>
          <span class="burger-icon__line"></span>
          <span class="burger-icon__line"></span>
        </span>
        {{ __('Filtri', 'sage') }}
      </span>
      <span x-show="activeCount > 0" class="text-xs bg-accent text-white px-2 py-0.5" x-text="activeCount"></span>
    </button>
  </div>

  {{-- Filters panel --}}
  <div :class="mobileOpen || !isMobile ? '' : 'hidden'" class="lg:block space-y-6">

    {{-- Reset --}}
    <div class="flex items-center justify-between" x-show="activeCount > 0">
      <p class="text-xs font-semibold tracking-wider uppercase text-ink">
        {{ __('Filtri attivi', 'sage') }} (<span x-text="activeCount"></span>)
      </p>
      <button
        type="button"
        @click="reset()"
        class="text-xs text-accent hover:underline"
      >
        {{ __('Rimuovi tutti', 'sage') }}
      </button>
    </div>

    {{-- Categories --}}
    @if(!empty($all_cats))
      <div>
        <h3 class="text-xs font-semibold tracking-[0.2em] uppercase text-ink mb-3">{{ __('Categoria', 'sage') }}</h3>
        <ul class="space-y-1.5">
          @foreach($all_cats as $cat)
            <li>
              <label class="flex items-center gap-2.5 cursor-pointer group">
                <input
                  type="checkbox"
                  class="rounded-none border-border text-accent focus:ring-accent/30 cursor-pointer"
                  :checked="cats.includes({{ $cat->term_id }})"
                  @change="toggleCat({{ $cat->term_id }})"
                >
                <span class="text-sm text-ink group-hover:text-accent transition-colors">
                  {{ esc_html($cat->name) }}
                  <span class="text-muted">({{ $cat->count }})</span>
                </span>
              </label>
            </li>
          @endforeach
        </ul>
      </div>
    @endif

    {{-- Price range --}}
    <div>
      <h3 class="text-xs font-semibold tracking-[0.2em] uppercase text-ink mb-3">{{ __('Prezzo', 'sage') }}</h3>
      <div class="space-y-3">
        <div class="flex items-center gap-2">
          <div class="flex-1">
            <label class="text-xs text-muted block mb-1">{{ __('Min', 'sage') }}</label>
            <div class="flex items-center border border-border bg-white">
              <span class="px-2 text-muted text-sm">{{ get_woocommerce_currency_symbol() }}</span>
              <input
                type="number"
                x-model.number="priceMin"
                @change="applyFilters()"
                min="0"
                :max="priceMax"
                class="flex-1 py-2 pr-2 text-sm text-ink bg-transparent focus:outline-none"
              >
            </div>
          </div>
          <span class="text-muted text-sm mt-4">—</span>
          <div class="flex-1">
            <label class="text-xs text-muted block mb-1">{{ __('Max', 'sage') }}</label>
            <div class="flex items-center border border-border bg-white">
              <span class="px-2 text-muted text-sm">{{ get_woocommerce_currency_symbol() }}</span>
              <input
                type="number"
                x-model.number="priceMax"
                @change="applyFilters()"
                :min="priceMin"
                max="{{ (int) $max_product_price }}"
                class="flex-1 py-2 pr-2 text-sm text-ink bg-transparent focus:outline-none"
              >
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- In stock only --}}
    <div>
      <label class="flex items-center gap-2.5 cursor-pointer group">
        <input
          type="checkbox"
          class="rounded-none border-border text-accent focus:ring-accent/30 cursor-pointer"
          x-model="inStockOnly"
          @change="applyFilters()"
        >
        <span class="text-sm text-ink group-hover:text-accent transition-colors">{{ __('Solo disponibili', 'sage') }}</span>
      </label>
    </div>

    {{-- Apply (mobile) --}}
    <div class="lg:hidden">
      <button type="button" @click="applyFilters(); mobileOpen = false" class="btn-primary w-full justify-center text-xs">
        {{ __('Applica filtri', 'sage') }}
      </button>
    </div>

  </div>
</aside>

<script>
function productFilters() {
  const params = new URLSearchParams(window.location.search);
  return {
    cats:        (params.getAll('cats[]') || []).map(Number).filter(Boolean),
    priceMin:    parseFloat(params.get('min_price') || '0'),
    priceMax:    parseFloat(params.get('max_price') || '{{ (int) $max_product_price }}'),
    inStockOnly: params.get('in_stock') === '1',
    mobileOpen:  false,

    get isMobile() { return window.innerWidth < 1024; },
    get activeCount() {
      let c = 0;
      if (this.cats.length) c += this.cats.length;
      if (this.priceMin > 0) c++;
      if (this.priceMax < {{ (int) $max_product_price }}) c++;
      if (this.inStockOnly) c++;
      return c;
    },

    init() {
      // If URL has filters, trigger initial load
      if (this.activeCount > 0) {
        this.$nextTick(() => this.applyFilters());
      }
    },

    toggleCat(id) {
      const idx = this.cats.indexOf(id);
      if (idx >= 0) { this.cats.splice(idx, 1); } else { this.cats.push(id); }
      this.applyFilters();
    },

    applyFilters() {
      window.dispatchEvent(new CustomEvent('product-filter-changed', {
        detail: {
          cats:       this.cats,
          min_price:  this.priceMin,
          max_price:  this.priceMax < {{ (int) $max_product_price }} ? this.priceMax : null,
          in_stock:   this.inStockOnly,
        }
      }));
    },

    reset() {
      this.cats       = [];
      this.priceMin   = 0;
      this.priceMax   = {{ (int) $max_product_price }};
      this.inStockOnly = false;
      this.applyFilters();
    },
  };
}
</script>
