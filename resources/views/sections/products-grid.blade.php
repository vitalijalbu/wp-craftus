@php
  // Parameters
  $section_label    = $section_label    ?? '';
  $section_title    = wp_kses_post($section_title ?? __('Tutti i prodotti', 'sage'));
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
          'id'    => (int) $term->term_id,
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

  $initial_products_data = array_values(array_filter(array_map(function ($product) {
    if (! ($product instanceof \WC_Product)) {
      return null;
    }

    $pid = (int) $product->get_id();
    $thumb_id = (int) $product->get_image_id();
    $terms = get_the_terms($pid, 'product_cat');
    $category_name = ($terms && ! is_wp_error($terms)) ? esc_html($terms[0]->name) : '';

    return [
      'id' => $pid,
      'title' => esc_html($product->get_name()),
      'url' => esc_url(get_permalink($pid)),
      'thumb' => esc_url(wp_get_attachment_image_url($thumb_id, 'woocommerce_thumbnail') ?: ''),
      'category' => $category_name,
      'price_html' => wp_strip_all_tags($product->get_price_html()),
      'on_sale' => $product->is_on_sale(),
      'in_stock' => $product->is_in_stock(),
      'add_to_cart_url' => esc_url($product->add_to_cart_url()),
      'add_to_cart_text' => esc_html($product->add_to_cart_text()),
    ];
  }, $initial_products)));

  $initial_active_category = 'all';
  if (! empty($category)) {
    $first_category = is_array($category) ? (string) reset($category) : (string) $category;
    $term = get_term_by('slug', $first_category, 'product_cat');
    if ($term && ! is_wp_error($term)) {
      $initial_active_category = (int) $term->term_id;
    }
  }

  $category_map = [];
  foreach ($cat_tabs as $tab) {
    $category_map[(string) $tab['slug']] = (int) $tab['id'];
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
  x-data="productsGrid({{ \Illuminate\Support\Js::from([
    'activeCategory' => $initial_active_category,
    'products' => $initial_products_data,
    'perPage' => (int) $per_page,
    'hasMore' => count($initial_products) >= $per_page,
    'endpoint' => rest_url('theme/v1/products'),
    'orderby' => 'date',
    'categoryMap' => $category_map,
  ]) }})"
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
          class="px-5 py-2 text-[15px] font-semibold tracking-[0.066em] uppercase transition-all duration-200"
          :class="activeCategory === 'all'
            ? 'bg-white text-primary border border-primary'
            : 'bg-transparent text-muted border border-border hover:border-primary hover:text-primary'"
        >{{ __('Tutti', 'sage') }}</button>

        @foreach($cat_tabs as $tab)
          <button
            type="button"
            :aria-pressed="activeCategory === {{ (int) $tab['id'] }}"
            @click="filterByCategory({{ (int) $tab['id'] }})"
            class="px-5 py-2 text-[15px] font-semibold tracking-[0.066em] uppercase transition-all duration-200"
            :class="activeCategory === {{ (int) $tab['id'] }}
              ? 'bg-white text-primary border border-primary'
              : 'bg-transparent text-muted border border-border hover:border-primary hover:text-primary'"
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
      <template x-for="product in products" :key="product.id">
        <div data-scroll-item role="listitem">
          <article class="product-card" :aria-label="product.title">
            <div class="product-card__image-wrap">
              <a :href="product.url" tabindex="-1" aria-hidden="true">
                <img
                  :src="product.thumb"
                  :alt="product.title"
                  sizes="(max-width: 640px) 50vw, (max-width: 1024px) 33vw, 25vw"
                  class="w-full h-full object-cover"
                  loading="lazy"
                  decoding="async"
                >
              </a>

              <div class="product-card__badge flex flex-col gap-1">
                <span x-show="product.on_sale" class="badge badge-primary">{{ __('Offerta', 'sage') }}</span>
                <span x-show="!product.in_stock" class="badge bg-muted/80">{{ __('Esaurito', 'sage') }}</span>
              </div>

              <button
                type="button"
                class="product-card__wishlist wishlist-btn"
                :data-product-id="product.id"
                aria-label="{{ esc_attr__('Aggiungi alla wishlist', 'sage') }}"
              >
                <x-icons.heart class="size-4 text-ink" />
              </button>

              {{-- Add to cart overlay disattivato sulle card shop: CTA disponibile solo nel single product --}}
              {{--
              <div class="product-card__overlay bg-white/95" x-show="product.in_stock && product.add_to_cart_url">
                <a
                  :href="product.add_to_cart_url"
                  :data-product_id="product.id"
                  class="btn-primary w-full justify-center add_to_cart_button ajax_add_to_cart"
                  rel="nofollow"
                  aria-label="{{ esc_attr__('Aggiungi al carrello', 'sage') }}"
                >
                  <span class="btn-label" x-text="product.add_to_cart_text"></span>
                  <x-icons.spinner class="btn-spinner" width="16" height="16" />
                </a>
              </div>
              --}}
            </div>

            <div class="product-card__body">
              <p class="product-card__category" x-show="product.category" x-text="product.category"></p>

              <a :href="product.url" class="product-card__title" x-text="product.title"></a>

              <div class="product-card__price mt-auto pt-3">
                <span x-text="product.price_html"></span>
              </div>
            </div>
          </article>
        </div>
      </template>
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
      <p class="text-2xl text-ink mb-3">{{ __('Nessun prodotto trovato', 'sage') }}</p>
      <p class="text-sm text-muted">{{ __('Prova a selezionare un\'altra categoria.', 'sage') }}</p>
    </div>

  </div>
</section>
