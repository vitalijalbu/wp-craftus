// Wishlist functionality
(function () {
	function getWishlist() {
		return JSON.parse(localStorage.getItem('theme:wishlist') || '[]');
	}

	function saveWishlist(wishlist) {
		localStorage.setItem('theme:wishlist', JSON.stringify(wishlist));
	}

	function updateButtonStates() {
		const wishlist = getWishlist();
		const wishlistButtons = document.querySelectorAll('.wishlist-btn, .product-card__wishlist');
		const wishlistDot = document.querySelector('.wishlist-dot');

		wishlistButtons.forEach(function (btn) {
			const productId = btn.getAttribute('data-product-id');
			btn.classList.toggle('active', wishlist.includes(productId));
		});

		if (wishlistDot) {
			wishlistDot.classList.toggle('is-visible', wishlist.length > 0);
		}
		
		// FIX Bug 1: Gestisci correttamente la visibilità dei bubble
		document.querySelectorAll('.wishlist-count-bubble').forEach(function(bubble) {
			if (wishlist.length > 0) {
				bubble.textContent = wishlist.length;
				bubble.style.display = '';
			} else {
				bubble.style.display = 'none';
			}
		});
	}

	function initWishlistButtons() {
		document.querySelectorAll('.wishlist-btn, .product-card__wishlist').forEach(function (btn) {
			if (btn.dataset.wishlistInit) return;
			btn.dataset.wishlistInit = 'true';

			btn.addEventListener('click', function () {
				let wishlist = getWishlist();
				const productId = this.getAttribute('data-product-id');

				if (wishlist.includes(productId)) {
					wishlist = wishlist.filter((item) => item !== productId);
					this.classList.remove('active');
				} else {
					wishlist.unshift(productId);
					this.classList.add('active');
				}

				saveWishlist(wishlist);
				updateButtonStates();
				
				// FIX Bug 2: Ricarica i prodotti nella pagina wishlist
				const wishlistProductsElement = document.querySelector('wishlist-products');
				if (wishlistProductsElement) {
					wishlistProductsElement.loadProducts();
				}
			});
		});

		updateButtonStates();
	}

	// Wishlist page custom element
	// Usage: <wishlist-products products-limit="12"></wishlist-products>
	// Fetches products from the WP REST search endpoint by ID.
	if (!window.customElements.get('wishlist-products')) {
		class WishlistProducts extends HTMLElement {
			connectedCallback() {
				this.loadProducts();
			}

			async loadProducts() {
				const ids = getWishlist().slice(0, this.productLimit);

				if (ids.length === 0) {
					this.innerHTML = '<p class="text-muted col-span-full py-10 text-center">' +
						(this.getAttribute('empty-label') || 'La tua wishlist è vuota.') +
						'</p>';
					return;
				}

				this.innerHTML = '<div class="col-span-full py-10 text-center text-muted">Caricamento…</div>';

				try {
					// Fetch each product ID via the existing search endpoint.
					// We search by exact ID by filtering type=product.
					const params = new URLSearchParams({
						per_page: ids.length,
						type: 'product',
						q: ids.join(' '),
					});
					const apiUrl = (window.themeData?.restUrl || '/wp-json/') + 'theme/v1/search?' + params;
					const res = await fetch(apiUrl);
					if (!res.ok) throw new Error('fetch failed');
					const data = await res.json();

					const results = (data.results || []).filter((r) => ids.includes(String(r.id)));

					if (!results.length) {
						this.innerHTML = '<p class="text-muted col-span-full py-10 text-center">' +
							(this.getAttribute('empty-label') || 'La tua wishlist è vuota.') +
							'</p>';
						return;
					}

					this.innerHTML = results.map((p) => `
						<div class="product-card group" data-product-id="${p.id}">
							<a href="${p.url}" class="block overflow-hidden aspect-square bg-surface-alt mb-4">
								${p.thumb ? `<img src="${p.thumb}" alt="${p.title}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">` : ''}
							</a>
							<div class="flex items-start justify-between gap-2">
								<div>
									<a href="${p.url}" class="font-sans text-sm font-medium text-ink hover:text-accent transition-colors">${p.title}</a>
									${p.price ? `<p class="text-sm text-muted mt-0.5">${p.price}</p>` : ''}
								</div>
								<button
									class="product-card__wishlist wishlist-btn active shrink-0 w-8 h-8 flex items-center justify-center text-accent"
									data-product-id="${p.id}"
									aria-label="Rimuovi dalla wishlist"
									aria-pressed="true"
								>
									<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
										<path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z"/>
									</svg>
								</button>
							</div>
						</div>
					`).join('');

					initWishlistButtons();
				} catch (error) {
					this.innerHTML = '<p class="text-error col-span-full py-10 text-center">Errore nel caricamento. Riprova.</p>';
					console.error('Wishlist load error:', error);
				}
			}

			get productLimit() {
				return parseInt(this.getAttribute('products-limit')) || 12;
			}
		}

		window.customElements.define('wishlist-products', WishlistProducts);
	}

	// Initialize on DOM ready
	document.addEventListener('DOMContentLoaded', initWishlistButtons);

	// Expose for dynamic content
	window.initWishlistButtons = initWishlistButtons;
})();