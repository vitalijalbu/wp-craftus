@php
  // Parameters
  $section_label    = $section_label    ?? '';
  $section_title    = $section_title    ?? __('Tutti i prodotti', 'sage');
  $section_subtitle = $section_subtitle ?? '';
  $category         = $category         ?? '';   // slug or array of slugs
  $per_page         = $per_page         ?? 12;
  $show_filters     = $show_filters     ?? true;
  $cols             = $cols             ?? 3;    // 2 | 3 | 4
  $bg               = $bg               ?? 'surface'; // surface | cream

  // Build initial category tabs from WooCommerce
  $cat_tabs = [];
  if ($show_filters && function_exists('get_terms')) {
    $raw_terms = get_terms([
      'taxonomy'   => 'product_cat',
      'hide_empty' => true,
      'number'     => 12,
      'parent'     => 0,
    ]);
    if (!is_wp_error($raw_terms)) {
      foreach ($raw_terms as $term) {
        $cat_tabs[] = [
          'slug'  => $term->slug,
          'name'  => $term->name,
          'count' => $term->count,
        ];
      }
    }
  }

  // Initial product query
  $initial_products = [];
  if (function_exists('wc_get_products')) {
    $q_args = [
      'status'  => 'publish',
      'limit'   => (int) $per_page,
      'orderby' => 'date',
      'order'   => 'DESC',
      'paginate' => false,
    ];
    if ($category) {
      $q_args['category'] = is_array($category) ? $category : [$category];
    }
    $initial_products = wc_get_products($q_args);
  }

  $cols_class = match((int) $cols) {
    2 => 'grid-cols-1 sm:grid-cols-2',
    4 => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4',
    default => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3',
  };

  $bg_class = $bg === 'cream' ? 'bg-cream' : 'bg-surface';
@endphp

<section
  id="{{ $section_id ?? 'section-products' }}"
  class="section {{ $bg_class }}"
  aria-label="{{ strip_tags($section_title) }}"
  x-data="{
    activeCategory: '{{ $category ?: 'all' }}',
    products: {{ json_encode(array_map(fn($p) => $p->get_id(), $initial_products)) }},
    page: 1,
    perPage: {{ (int) $per_page }},
    loading: false,
    hasMore: {{ count($initial_products) >= $per_page ? 'true' : 'false' }},
    statusMsg: '',

    async filterByCategory(slug) {
      this.activeCategory = slug;
      this.page = 1;
      this.loading = true;
      await this.fetchProducts(true);
      this.loading = false;
    },

    async loadMore() {
      if (this.loading || !this.hasMore) return;
      this.page++;
      this.loading = true;
      await this.fetchProducts(false);
      this.loading = false;
    },

    async fetchProducts(reset) {
      const params = new URLSearchParams({
        per_page: this.perPage,
        page: this.page,
        status: 'publish',
        orderby: 'date',
        order: 'desc',
      });
      if (this.activeCategory && this.activeCategory !== 'all') {
        // Resolve category ID from slug via REST
        const catResp = await fetch(
          `{{ rest_url('wc/v3/products/categories') }}?slug=${this.activeCategory}&consumer_key=&consumer_secret=`,
          { credentials: 'same-origin', headers: { 'X-WP-Nonce': '{{ wp_create_nonce('wp_rest') }}' } }
        );
        if (catResp.ok) {
          const cats = await catResp.json();
          if (cats.length) params.append('category', cats[0].id);
        }
      }
      const resp = await fetch(
        `{{ rest_url('wc/v3/products') }}?${params.toString()}`,
        { credentials: 'same-origin', headers: { 'X-WP-Nonce': '{{ wp_create_nonce('wp_rest') }}' } }
      );
      if (resp.ok) {
        const data = await resp.json();
        const ids = data.map(p => p.id);
        if (reset) {
          this.products = ids;
        } else {
          this.products = [...this.products, ...ids];
        }
        this.hasMore = ids.length >= this.perPage;
        this.statusMsg = ids.length === 0 ? 'Nessun prodotto trovato' : ids.length + ' prodotti caricati';
        // Trigger GSAP refresh
        if (window.ScrollTrigger) window.ScrollTrigger.refresh();
      }
    }
  }"
>
  <div class="container">

    {{-- Section header --}}
    @if($section_label || $section_title)
      <div class="mb-12">
        @include('partials.section-header', ['align' => 'center'])
      </div>
    @endif

    {{-- Category filter tabs --}}
    @if($show_filters && !empty($cat_tabs))
      <div
        class="flex flex-wrap items-center gap-1 justify-center mb-10 pb-8 border-b border-border"
        role="group"
        aria-label="{{ __('Filtra per categoria', 'sage') }}"
        data-scroll="fade"
      >
        <button
          type="button"
          :aria-pressed="activeCategory === 'all'"
          @click="filterByCategory('all')"
          class="px-5 py-2 text-xs font-500 tracking-wider uppercase transition-all duration-200"
          :class="activeCategory === 'all'
            ? 'bg-ink text-surface border border-ink'
            : 'bg-transparent text-muted border border-border hover:border-ink hover:text-ink'"
        >{{ __('Tutti', 'sage') }}</button>

        @foreach($cat_tabs as $tab)
          <button
            type="button"
            :aria-pressed="activeCategory === '{{ $tab['slug'] }}'"
            @click="filterByCategory('{{ $tab['slug'] }}')"
            class="px-5 py-2 text-xs font-500 tracking-wider uppercase transition-all duration-200"
            :class="activeCategory === '{{ $tab['slug'] }}'
              ? 'bg-ink text-surface border border-ink'
              : 'bg-transparent text-muted border border-border hover:border-ink hover:text-ink'"
          >{{ $tab['name'] }}</button>
        @endforeach
      </div>
    @endif

    {{-- SR-only live region: annuncia caricamento e risultati agli screen reader --}}
    <p
      class="sr-only"
      role="status"
      aria-live="polite"
      aria-atomic="true"
      x-text="loading ? '{{ __('Caricamento prodotti in corso…', 'sage') }}' : statusMsg"
    ></p>

    {{-- Products grid --}}
    <div
      class="grid {{ $cols_class }} gap-x-6 gap-y-10 transition-opacity duration-300"
      data-scroll="stagger"
      :class="loading ? 'opacity-50 pointer-events-none' : 'opacity-100'"
      role="list"
    >
      @foreach($initial_products as $product)
        <div data-scroll-item role="listitem">
          @include('partials.product-card', ['product' => $product])
        </div>
      @endforeach
    </div>

    {{-- Loading spinner (visuale; l'annuncio AT è nella live region sr-only sopra) --}}
    <div
      class="flex justify-center py-8"
      x-show="loading"
      x-cloak
      aria-hidden="true"
    >
      <div class="size-8 border-2 border-border border-t-primary rounded-full animate-spin"></div>
    </div>

    {{-- Load more --}}
    <div
      class="flex justify-center mt-14"
      x-show="hasMore && !loading"
      x-cloak
    >
      <button
        type="button"
        @click="loadMore()"
        class="btn-outline px-10"
      >
        {{ __('Carica altri prodotti', 'sage') }}
      </button>
    </div>

    {{-- No results --}}
    <div
      class="text-center py-16"
      x-show="!loading && products.length === 0"
      x-cloak
    >
      <p class="font-serif text-2xl text-ink mb-3">{{ __('Nessun prodotto trovato', 'sage') }}</p>
      <p class="text-sm text-muted">{{ __('Prova a selezionare un\'altra categoria.', 'sage') }}</p>
    </div>

  </div>
</section>
