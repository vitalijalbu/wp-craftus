/**
 * Quantity selector — gestisce i pulsanti +/- nel selettore quantità prodotto.
 */
export function initQuantitySelectors() {
	document.addEventListener('click', (e) => {
		const btn = e.target.closest('.qty-btn');
		if (!btn) {
			return;
		}

		const wrapper = btn.closest('.qty-selector');
		if (!wrapper) {
			return;
		}

		const input = wrapper.querySelector('input.qty');
		if (!input) {
			return;
		}

		const step = parseFloat(input.step) || 1;
		const min = input.min !== '' ? parseFloat(input.min) : 1;
		const max = input.max !== '' ? parseFloat(input.max) : Infinity;
		let val = parseFloat(input.value) || min;

		if (btn.classList.contains('qty-btn--minus')) {
			val = Math.max(min, val - step);
		} else if (btn.classList.contains('qty-btn--plus')) {
			val = Math.min(max, val + step);
		}

		input.value = val;
		input.dispatchEvent(new Event('change', { bubbles: true }));

		// aggiorna stato disabled
		wrapper
			.querySelector('.qty-btn--minus')
			?.toggleAttribute('disabled', val <= min);
		wrapper
			.querySelector('.qty-btn--plus')
			?.toggleAttribute('disabled', max !== Infinity && val >= max);
	});
}
