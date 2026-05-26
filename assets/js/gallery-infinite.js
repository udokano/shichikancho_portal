'use strict';

// 町のギャラリー Ajax 無限スクロール
(function () {
	const grid     = document.querySelector('.js-gallery-grid');
	const sentinel = document.querySelector('.js-gallery-sentinel');
	if (!grid || !sentinel || !window.SC_AJAX) return;

	const state = {
		page:     parseInt(grid.dataset.initialPage || '1', 10),
		maxPages: parseInt(grid.dataset.maxPages || '1', 10),
		cats:     JSON.parse(grid.dataset.cats || '[]'),
		ppp:      parseInt(grid.dataset.ppp || '12', 10),
		loading:  false,
	};

	if (state.page >= state.maxPages) {
		sentinel.style.display = 'none';
		return;
	}

	async function loadNext () {
		if (state.loading) return;
		if (state.page >= state.maxPages) {
			sentinel.style.display = 'none';
			io.disconnect();
			return;
		}
		state.loading = true;
		grid.classList.add('is-loading');

		const params = new URLSearchParams();
		params.set('action', 'sc_load_gallery');
		params.set('paged', String(state.page + 1));
		params.set('ppp', String(state.ppp));
		state.cats.forEach(c => params.append('cat[]', c));

		try {
			const res = await fetch(SC_AJAX.url + '?' + params.toString(), { credentials: 'same-origin' });
			const json = await res.json();
			if (json && json.success && json.data) {
				const wrap = document.createElement('div');
				wrap.innerHTML = json.data.html;
				const items = wrap.querySelectorAll('.p-gallery__item');
				items.forEach(el => grid.appendChild(el));
				state.page     = json.data.page;
				state.maxPages = json.data.max_pages;
				if (!json.data.has_more) {
					sentinel.style.display = 'none';
					io.disconnect();
				}
			}
		} catch (e) {
			console.error('[gallery] load failed', e);
		} finally {
			state.loading = false;
			grid.classList.remove('is-loading');
		}
	}

	const io = new IntersectionObserver((entries) => {
		entries.forEach(e => { if (e.isIntersecting) loadNext(); });
	}, { rootMargin: '300px 0px' });

	io.observe(sentinel);
}());
