/**
 * Wishlist — localStorage-based wishlist for guests.
 * Fixes applied: aria-pressed sync, AbortController fetch timeout, dev-only logging.
 */

const KEY = 'theme:wishlist';
const IS_DEV = import.meta.env.DEV;

function getWishlist() {
	return JSON.parse(localStorage.getItem(KEY) || '[]');
}

function saveWishlist(wishlist) {
	localStorage.setItem(KEY, JSON.stringify(wishlist));
}

function updateButtonStates() {
	const wishlist = getWishlist();
	const wishlistDot = document.querySelector('.wishlist-dot');

	document
		.querySelectorAll('.wishlist-btn, .product-card__wishlist')
		.forEach((btn) => {
			const productId = btn.getAttribute('data-product-id');
			const inWishlist = wishlist.includes(productId);
			btn.classList.toggle('active', inWishlist);
			btn.setAttribute('aria-pressed', inWishlist ? 'true' : 'false');
		});

	if (wishlistDot) {
		wishlistDot.classList.toggle('is-visible', wishlist.length > 0);
	}

	document.querySelectorAll('.wishlist-count-bubble').forEach((bubble) => {
		if (wishlist.length > 0) {
			bubble.textContent = wishlist.length;
			bubble.classList.add('is-visible');
		} else {
			bubble.textContent = '';
			bubble.classList.remove('is-visible');
		}
	});
}

function initWishlistButtons() {
	document
		.querySelectorAll('.wishlist-btn, .product-card__wishlist')
		.forEach((btn) => {
			if (btn.dataset.wishlistInit) {
				return;
			}
			btn.dataset.wishlistInit = 'true';

			btn.addEventListener('click', function () {
				let wishlist = getWishlist();
				const productId = this.getAttribute('data-product-id');

				if (wishlist.includes(productId)) {
					wishlist = wishlist.filter((item) => item !== productId);
					this.classList.remove('active');
					this.setAttribute('aria-pressed', 'false');
				} else {
					wishlist.unshift(productId);
					this.classList.add('active');
					this.setAttribute('aria-pressed', 'true');
				}

				saveWishlist(wishlist);
				updateButtonStates();

				// Reload wishlist page element if present
				const wishlistEl = document.querySelector('wishlist-products');
				if (wishlistEl) {
					wishlistEl.loadProducts();
				}
			});
		});

	updateButtonStates();
}

// ── Wishlist page custom element ──────────────────────────────────────────────
// Usage: <wishlist-products products-limit="12"></wishlist-products>
// Fetches products via GET /wp-json/theme/v1/wishlist-products?ids=1,2,3 (URL from window.themeRestUrl)
if (!window.customElements.get('wishlist-products')) {
	class WishlistProducts extends HTMLElement {
		connectedCallback() {
			this.loadProducts();
		}

		async loadProducts() {
			const ids = getWishlist().slice(0, this.productLimit);

			if (ids.length === 0) {
				const homeUrl = window.themeData?.homeUrl || '/';
				const shopUrl = window.themeData?.shopUrl || '/shop';
				this.innerHTML =
					'<div class="col-span-full flex flex-col items-center justify-center text-center py-16">' +
					'<svg class="w-16 h-16 text-border mb-6 mx-auto" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/></svg>' +
					'<p class="font-serif text-2xl font-light text-ink mb-2">' +
					(this.getAttribute('empty-label') || 'La tua wishlist è vuota') +
					'</p>' +
					'<p class="text-base text-muted mb-8">Sfoglia i prodotti e aggiungi i tuoi preferiti!</p>' +
					'<div class="flex flex-wrap justify-center gap-4">' +
					'<a href="' +
					homeUrl +
					'" class="btn-primary">Vai alla Home</a>' +
					'<a href="' +
					shopUrl +
					'" class="btn-outline">Sfoglia i prodotti</a>' +
					'</div>' +
					'</div>';
				return;
			}

			this.innerHTML =
				'<div class="col-span-full py-10 text-center text-muted">Caricamento…</div>';

			const ctrl = new AbortController();
			const timer = setTimeout(() => ctrl.abort(), 8000);

			try {
				const base = window.themeRestUrl || '/wp-json/theme/v1';
				const res = await fetch(
					base + '/wishlist-products?ids=' + ids.join(','),
					{
						signal: ctrl.signal,
					},
				);
				clearTimeout(timer);
				if (!res.ok) {
					throw new Error('fetch failed');
				}
				const data = await res.json();
				const products = data.products || [];

				if (!products.length) {
					const homeUrl = window.themeData?.homeUrl || '/';
					const shopUrl = window.themeData?.shopUrl || '/shop';
					this.innerHTML =
						'<div class="col-span-full flex flex-col items-center justify-center text-center py-16">' +
						'<svg class="w-16 h-16 text-border mb-6 mx-auto" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/></svg>' +
						'<p class="font-serif text-2xl font-light text-ink mb-2">' +
						(this.getAttribute('empty-label') || 'La tua wishlist è vuota') +
						'</p>' +
						'<p class="text-base text-muted mb-8">Sfoglia i prodotti e aggiungi i tuoi preferiti!</p>' +
						'<div class="flex flex-wrap justify-center gap-4">' +
						'<a href="' +
						homeUrl +
						'" class="btn-primary">Vai alla Home</a>' +
						'<a href="' +
						shopUrl +
						'" class="btn-outline">Sfoglia i prodotti</a>' +
						'</div>' +
						'</div>';
					return;
				}

				this.innerHTML = products
					.map(
						(p) => `
					<div class="product-card group" data-product-id="${p.id}">
						<a href="${p.url}" class="block overflow-hidden aspect-square bg-surface-alt mb-4">
							${p.thumb ? `<img src="${p.thumb}" alt="${p.title}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">` : ''}
						</a>
						<div class="p-2 flex items-start justify-between gap-2">
							<div>
								<a href="${p.url}" class="font-sans text-sm font-medium text-ink hover:text-primary transition-colors">${p.title}</a>
								${p.price_html ? `<p class="text-sm text-muted mt-0.5">${p.price_html}</p>` : ''}
							</div>
							<button
								class="product-card__wishlist wishlist-btn active shrink-0 size-8 flex items-center justify-center text-primary"
								data-product-id="${p.id}"
								aria-label="Rimuovi dalla wishlist"
								aria-pressed="true"
							>
								<svg class="size-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
									<path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z"/>
								</svg>
							</button>
						</div>
					</div>
				`,
					)
					.join('');

				initWishlistButtons();
			} catch (error) {
				clearTimeout(timer);
				this.innerHTML =
					'<p class="text-error col-span-full py-10 text-center">Errore nel caricamento. Riprova.</p>';
				if (IS_DEV) {
					console.error('Wishlist load error:', error);
				}
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

// Cross-tab sync — update buttons and wishlist page when localStorage changes
window.addEventListener('storage', (e) => {
	if (e.key !== KEY) {
		return;
	}
	updateButtonStates();
	const wishlistEl = document.querySelector('wishlist-products');
	if (wishlistEl) {
		wishlistEl.loadProducts();
	}
});

// Expose for dynamic content (e.g. after AJAX add-to-cart)
window.initWishlistButtons = initWishlistButtons;
