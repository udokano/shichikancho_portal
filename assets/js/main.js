'use strict';

document.addEventListener('DOMContentLoaded', () => {

	// ─── ハンバーガーメニュー ─────────────────────────────────
	const hamburger = document.querySelector('.js-hamburger');
	const drawer    = document.getElementById('drawer');
	const overlay   = document.querySelector('.js-overlay');

	if (hamburger && drawer) {
		const openDrawer = () => {
			hamburger.setAttribute('aria-expanded', 'true');
			hamburger.classList.add('is-open');
			drawer.hidden = false;
			drawer.classList.add('is-open');
			document.body.classList.add('is-nav-open');
			overlay?.classList.add('is-visible');
			// フォーカスをドロワー内最初のリンクへ移動
			const firstLink = drawer.querySelector('a, button');
			if (firstLink) firstLink.focus();
		};

		const closeDrawer = () => {
			hamburger.setAttribute('aria-expanded', 'false');
			hamburger.classList.remove('is-open');
			drawer.classList.remove('is-open');
			document.body.classList.remove('is-nav-open');
			overlay?.classList.remove('is-visible');
			// hidden はアニメーション終了後に付与
			setTimeout(() => { drawer.hidden = true; }, 300);
			hamburger.focus();
		};

		hamburger.addEventListener('click', () => {
			const isOpen = hamburger.getAttribute('aria-expanded') === 'true';
			isOpen ? closeDrawer() : openDrawer();
		});

		// ドロワー閉じるボタン
		const drawerClose = document.querySelector('.js-drawer-close');
		if (drawerClose) drawerClose.addEventListener('click', closeDrawer);

		// ドロワー内リンクをクリックで閉じる
		drawer.addEventListener('click', (e) => {
			if (e.target.closest('a')) closeDrawer();
		});

		// オーバーレイクリックで閉じる
		overlay?.addEventListener('click', closeDrawer);

		// ESC キーで閉じる
		document.addEventListener('keydown', (e) => {
			if (e.key === 'Escape' && hamburger.getAttribute('aria-expanded') === 'true') {
				closeDrawer();
			}
		});

		// フォーカストラップ（ドロワー内のみ Tab が回るように）
		drawer.addEventListener('keydown', (e) => {
			if (e.key !== 'Tab') return;
			const focusables = drawer.querySelectorAll('a, button, [tabindex]:not([tabindex="-1"])');
			const first = focusables[0];
			const last  = focusables[focusables.length - 1];

			if (e.shiftKey && document.activeElement === first) {
				e.preventDefault();
				last.focus();
			} else if (!e.shiftKey && document.activeElement === last) {
				e.preventDefault();
				first.focus();
			}
		});
	}

	// ─── スクロールでヘッダーに影を付ける ────────────────────
	const header = document.querySelector('.l-header');
	if (header) {
		const onScroll = () => {
			header.classList.toggle('is-scrolled', window.scrollY > 40);
		};
		window.addEventListener('scroll', onScroll, { passive: true });
		onScroll();
	}

	// ─── タブ切り替えUI（汎用） ──────────────────────────────
	// .js-tab-nav 内の [role="tab"] と .js-tab-panel を対応付ける
	document.querySelectorAll('.js-tab-nav').forEach((nav) => {
		const tabs   = nav.querySelectorAll('[role="tab"]');
		const parent = nav.closest('[data-tab-container]') ?? document;

		const activateTab = (tab) => {
			tabs.forEach((t) => {
				t.setAttribute('aria-selected', 'false');
				t.setAttribute('tabindex', '-1');
			});
			tab.setAttribute('aria-selected', 'true');
			tab.setAttribute('tabindex', '0');

			const targetId = tab.getAttribute('data-tab');
			parent.querySelectorAll('.js-tab-panel').forEach((panel) => {
				const isActive = panel.id === targetId;
				panel.hidden = !isActive;
				if (isActive) panel.removeAttribute('hidden');
			});
		};

		tabs.forEach((tab) => {
			tab.addEventListener('click', () => activateTab(tab));
			// 矢印キーでタブ移動
			tab.addEventListener('keydown', (e) => {
				const index = [...tabs].indexOf(tab);
				if (e.key === 'ArrowRight') {
					e.preventDefault();
					tabs[(index + 1) % tabs.length].focus();
				} else if (e.key === 'ArrowLeft') {
					e.preventDefault();
					tabs[(index - 1 + tabs.length) % tabs.length].focus();
				}
			});
		});

		// URL ハッシュでタブ初期化
		const hash = location.hash.replace('#', '');
		const hashTab = hash ? nav.querySelector(`[data-tab="${hash}"]`) : null;
		if (hashTab) {
			activateTab(hashTab);
		} else if (tabs.length) {
			activateTab(tabs[0]);
		}
	});

	// ─── アコーディオン FAQ ──────────────────────────────────
	document.querySelectorAll('.js-accordion').forEach((accordion) => {
		accordion.addEventListener('click', (e) => {
			const button = e.target.closest('.js-accordion-trigger');
			if (!button) return;

			const isExpanded = button.getAttribute('aria-expanded') === 'true';
			const panel = document.getElementById(button.getAttribute('aria-controls'));

			button.setAttribute('aria-expanded', String(!isExpanded));
			if (panel) panel.hidden = isExpanded;
		});
	});

	// ─── エリアガイド 名所リスト（SP のみアコーディオン・PC は CSS で常時表示）──
	document.querySelectorAll('.js-area-acc').forEach((btn) => {
		btn.addEventListener('click', () => {
			const expanded = btn.getAttribute('aria-expanded') === 'true';
			const panel = document.getElementById(btn.getAttribute('aria-controls'));

			btn.setAttribute('aria-expanded', String(!expanded));
			if (panel) panel.classList.toggle('is-open', !expanded);
		});
	});

	// ─── 画像遅延読み込み（ネイティブ loading="lazy" 補完）──
	if ('IntersectionObserver' in window) {
		const lazyImages = document.querySelectorAll('img[data-src]');
		const observer = new IntersectionObserver((entries) => {
			entries.forEach((entry) => {
				if (!entry.isIntersecting) return;
				const img = entry.target;
				img.src = img.dataset.src;
				if (img.dataset.srcset) img.srcset = img.dataset.srcset;
				img.removeAttribute('data-src');
				observer.unobserve(img);
			});
		}, { rootMargin: '200px' });

		lazyImages.forEach((img) => observer.observe(img));
	}

	// ─── アンカーリンクの固定ヘッダーオフセット ────────────────
	// このサイトはネイティブの fragment スクロールも scrollIntoView も不安定なため、
	// window.scrollTo で目標位置を実測計算して飛ぶ（最も確実）。
	// ヘッダー高さは is-scrolled / SP で可変なので都度実測して引く。
	function scrollToHash(hash) {
		if (!hash || hash === '#') return false;
		let target;
		try { target = document.querySelector(hash); } catch (e) { return false; }
		if (!target) return false;
		const headerH = header ? header.offsetHeight : 0;
		const top = target.getBoundingClientRect().top + window.scrollY - headerH - 8;
		// CSS の scroll-behavior:smooth が残っていても確実に instant で飛ぶよう一時上書き
		// （smooth スクロールは GT の html{height:100%} 等の影響で不発になることがある）
		const de = document.documentElement;
		const prev = de.style.scrollBehavior;
		de.style.scrollBehavior = 'auto';
		window.scrollTo(0, top);
		de.style.scrollBehavior = prev;
		return true;
	}

	document.querySelectorAll('a[href^="#"]').forEach((link) => {
		link.addEventListener('click', (e) => {
			const href = link.getAttribute('href');
			if (href.length < 2) return; // "#" 単体は無視
			if (scrollToHash(href)) {
				e.preventDefault();
				history.pushState(null, '', href); // URL は更新（ネイティブジャンプは使わない）
			}
		});
	});

	// 別ページから #hash 付きで着地した場合の補正（ネイティブが効かないため）
	if (location.hash) {
		requestAnimationFrame(() => scrollToHash(location.hash));
	}
	window.addEventListener('hashchange', () => scrollToHash(location.hash));

	// ─── CF7 バリデーションエラー時：最初のエラー箇所へスクロール ──
	// wpcf7invalid は Ajax バリデーション失敗時に document で発火する
	document.addEventListener('wpcf7invalid', (e) => {
		const form = e.target;
		const firstError = form.querySelector('.wpcf7-not-valid');
		if (!firstError) return;
		const headerH = header ? header.offsetHeight : 0;
		const top = firstError.getBoundingClientRect().top + window.scrollY - headerH - 16;
		const de = document.documentElement;
		const prev = de.style.scrollBehavior;
		de.style.scrollBehavior = 'auto';
		window.scrollTo(0, top);
		de.style.scrollBehavior = prev;
		if (typeof firstError.focus === 'function') firstError.focus({ preventScroll: true });
	});

	// ─── 目次（TOC）開閉トグル ──────────────────────────────
	document.querySelectorAll('.js-toc-toggle').forEach((btn) => {
		btn.addEventListener('click', () => {
			const expanded = btn.getAttribute('aria-expanded') === 'true';
			btn.setAttribute('aria-expanded', String(!expanded));
			const state = btn.querySelector('.p-column-single__toc-state');
			if (state) state.textContent = expanded ? '開く' : '閉じる';
		});
	});

	// ─── タブ切り替え（汎用 c-tabs） ─────────────────────────
	// data-tabs 属性で対象パネルセレクタを指定可能
	// 例: <nav class="c-tabs js-tabs" data-panels=".p-learning__panel">
	document.querySelectorAll('.js-tabs').forEach((tabsRoot) => {
		const panelSelector = tabsRoot.dataset.panels || '.c-tabs-panel';
		const buttons = tabsRoot.querySelectorAll('[role="tab"]');
		const panels  = document.querySelectorAll(panelSelector);

		buttons.forEach((btn) => {
			btn.addEventListener('click', () => {
				const targetId = btn.getAttribute('aria-controls');

				buttons.forEach((b) => {
					const isActive = b === btn;
					b.classList.toggle('is-active', isActive);
					b.setAttribute('aria-selected', String(isActive));
				});

				panels.forEach((panel) => {
					const isActive = panel.id === targetId;
					panel.classList.toggle('is-active', isActive);
					panel.hidden = !isActive;
				});
			});
		});
	});

	// ─── shop ギャラリースライダー（複数スライド + サムネ + 前後ナビ + カウンター）
	document.querySelectorAll('.js-shop-gallery').forEach((root) => {
		const slides = root.querySelectorAll('.p-shop__hero-slide');
		const thumbs = root.querySelectorAll('.p-shop__hero-thumb');
		const counter = root.querySelector('.js-shop-gallery-current');
		const prev = root.querySelector('.js-shop-gallery-prev');
		const next = root.querySelector('.js-shop-gallery-next');
		if (slides.length <= 1) return;
		let idx = 0;
		const total = slides.length;

		const go = (n) => {
			idx = (n + total) % total;
			slides.forEach((s, i) => s.classList.toggle('is-active', i === idx));
			thumbs.forEach((t, i) => t.classList.toggle('is-active', i === idx));
			if (counter) counter.textContent = String(idx + 1);
		};

		thumbs.forEach((thumb, i) => {
			thumb.addEventListener('click', () => go(i));
		});
		prev?.addEventListener('click', () => go(idx - 1));
		next?.addEventListener('click', () => go(idx + 1));
	});

	// ─── 共通モーダル ─────────────────────────────────────────
	const openModal = (modal) => {
		if (!modal) return;
		modal.hidden = false;
		modal.setAttribute('aria-hidden', 'false');
		// hidden を外した直後に is-open を付けると transition が効く
		requestAnimationFrame(() => modal.classList.add('is-open'));
		document.body.classList.add('is-modal-open');
		const focusable = modal.querySelector('[data-close], button, a, input');
		focusable?.focus();
	};

	const closeModal = (modal) => {
		if (!modal) return;
		modal.classList.remove('is-open');
		modal.setAttribute('aria-hidden', 'true');
		document.body.classList.remove('is-modal-open');
		// transition 完了後に hidden を戻す
		modal.addEventListener('transitionend', () => { modal.hidden = true; }, { once: true });
	};

	// data-modal-target を持つ要素のクリックでモーダル起動
	document.querySelectorAll('[data-modal-target]').forEach((trigger) => {
		trigger.addEventListener('click', (e) => {
			e.preventDefault();
			const target = trigger.getAttribute('data-modal-target');
			openModal(document.querySelector(target));
		});
	});

	// data-close 内クリックで閉じる
	document.querySelectorAll('.c-modal').forEach((modal) => {
		modal.addEventListener('click', (e) => {
			if (e.target.closest('[data-close]')) closeModal(modal);
		});
	});

	// Escキーで閉じる
	document.addEventListener('keydown', (e) => {
		if (e.key !== 'Escape') return;
		const open = document.querySelector('.c-modal:not([hidden])');
		closeModal(open);
	});

	// ─── /work 求人フィルター（カテゴリー/雇用形態/キーワード） ──
	document.querySelectorAll('.js-work-filter').forEach((root) => {
		const filterAside = document.querySelector('.p-working__filter');
		const cards = root.querySelectorAll('.p-working__job');
		const countEl = document.querySelector('.js-work-count');
		const keywordInput = document.querySelector('#filter-keyword');

		if (!cards.length) return;

		const state = { keyword: '', category: '', type: '' };

		const apply = () => {
			let visible = 0;
			cards.forEach((card) => {
				const type = card.querySelector('.p-working__job-type')?.textContent.trim() || '';
				const metaItems = card.querySelectorAll('.p-working__job-meta-item');
				// 3番目の meta-item (#icon-tag) がカテゴリー
				const category = (metaItems[2]?.textContent || '').trim();
				const text = card.textContent.toLowerCase();

				const matchKw = !state.keyword || text.includes(state.keyword);
				const matchCat = !state.category || category === state.category;
				const matchType = !state.type || type === state.type;

				const show = matchKw && matchCat && matchType;
				card.style.display = show ? '' : 'none';
				if (show) visible++;
			});
			if (countEl) countEl.textContent = String(visible);
		};

		// chips クリック
		filterAside?.querySelectorAll('[data-filter-group]').forEach((group) => {
			const key = group.dataset.filterGroup;
			group.querySelectorAll('.c-chips__chip').forEach((chip) => {
				chip.addEventListener('click', () => {
					group.querySelectorAll('.c-chips__chip').forEach((c) => c.classList.remove('is-active'));
					chip.classList.add('is-active');
					state[key] = chip.dataset.value || '';
					apply();
				});
			});
		});

		// キーワード入力（debounce 200ms）
		if (keywordInput) {
			let timer;
			keywordInput.addEventListener('input', (e) => {
				clearTimeout(timer);
				timer = setTimeout(() => {
					state.keyword = e.target.value.toLowerCase().trim();
					apply();
				}, 200);
			});
		}
	});

	// ─── /commerce 物件フィルター（カテゴリー / キーワード） ──
	document.querySelectorAll('.js-commerce-filter').forEach((root) => {
		const cards = document.querySelectorAll('.p-commerce__property');
		const countEl = document.querySelector('.js-commerce-count');
		const searchInput = root.querySelector('#commerce-search');
		const chipsGroup = root.querySelector('[data-filter-group="category"]');
		if (!cards.length) return;

		const state = { keyword: '', category: '' };

		const apply = () => {
			let visible = 0;
			cards.forEach((card) => {
				const category = card.querySelector('.p-commerce__property-category')?.textContent.trim() || '';
				const text = card.textContent.toLowerCase();
				const matchKw = !state.keyword || text.includes(state.keyword);
				const matchCat = !state.category || category === state.category;
				const show = matchKw && matchCat;
				card.style.display = show ? '' : 'none';
				if (show) visible++;
			});
			if (countEl) countEl.textContent = String(visible);
		};

		chipsGroup?.querySelectorAll('.c-chips__chip').forEach((chip) => {
			chip.addEventListener('click', () => {
				chipsGroup.querySelectorAll('.c-chips__chip').forEach((c) => c.classList.remove('is-active'));
				chip.classList.add('is-active');
				state.category = chip.dataset.value || '';
				apply();
			});
		});

		if (searchInput) {
			let timer;
			searchInput.addEventListener('input', (e) => {
				clearTimeout(timer);
				timer = setTimeout(() => {
					state.keyword = e.target.value.toLowerCase().trim();
					apply();
				}, 200);
			});
		}
	});

	// ─── /walk (explore) コースフィルター ────────────────────
	document.querySelectorAll('.js-explore-filter').forEach((root) => {
		const cards = document.querySelectorAll('.p-explore__course-card');
		if (!cards.length) return;

		const state = { area: '', duration: 0, scene: '' };

		const apply = () => {
			cards.forEach((card) => {
				const area = card.querySelector('.p-explore__course-card-area')?.textContent.trim() || '';
				const durTxt = card.querySelector('.p-explore__course-card-meta dd')?.textContent.trim() || '';
				const dur = parseInt(durTxt, 10) || 0;
				const text = card.textContent;
				const matchArea = !state.area || area.includes(state.area);
				const matchDur = !state.duration || (dur > 0 && dur <= state.duration);
				const matchScene = !state.scene || text.includes(state.scene);
				card.style.display = matchArea && matchDur && matchScene ? '' : 'none';
			});
		};

		root.querySelectorAll('[data-filter-group]').forEach((group) => {
			const key = group.dataset.filterGroup;
			group.querySelectorAll('.c-chips__chip, button').forEach((btn) => {
				btn.addEventListener('click', (e) => {
					if (btn.type === 'submit' && btn.name) return; // server submit is fine
					e.preventDefault();
					group.querySelectorAll('.c-chips__chip, button').forEach((b) => b.classList.remove('is-active'));
					btn.classList.add('is-active');
					const val = btn.dataset.value || btn.value || '';
					state[key] = key === 'duration' ? parseInt(val, 10) || 0 : val;
					apply();
				});
			});
		});
	});

	// ─── ギャラリー画像クリック→拡大モーダル + 前後送り ─────
	(() => {
		const modal = document.querySelector('#gallery-zoom-modal');
		if (!modal) return;
		const imgEl     = modal.querySelector('#gallery-zoom-img');
		const titleEl   = modal.querySelector('#gallery-zoom-title');
		const authorEl  = modal.querySelector('.p-gallery__modal-author');
		const counterEl = modal.querySelector('.js-gallery-counter');
		const prevBtn   = modal.querySelector('.js-gallery-prev');
		const nextBtn   = modal.querySelector('.js-gallery-next');

		const triggers = [...document.querySelectorAll('.js-gallery-zoom')];
		if (!triggers.length) return;

		// 表示中のアイテム（hidden の filter 後 のみ）
		let visibleList = triggers;
		let currentIdx  = 0;

		const refreshVisible = () => {
			visibleList = triggers.filter((t) => {
				const item = t.closest('.p-gallery__item');
				return item && getComputedStyle(item).display !== 'none';
			});
		};

		const render = (idx) => {
			refreshVisible();
			if (!visibleList.length) return;
			currentIdx = (idx + visibleList.length) % visibleList.length;
			const trigger = visibleList[currentIdx];
			const src    = trigger.dataset.img || trigger.querySelector('img')?.src || '';
			const title  = trigger.dataset.title || '';
			const author = trigger.dataset.author || '';

			// フェードトランジション
			imgEl?.classList.add('is-fading');
			setTimeout(() => {
				if (imgEl) { imgEl.src = src; imgEl.alt = title; }
				imgEl?.classList.remove('is-fading');
			}, 150);

			if (titleEl) titleEl.textContent = title;
			if (authorEl) authorEl.textContent = author ? `撮影: ${author}` : '';
			if (counterEl) counterEl.textContent = `${currentIdx + 1} / ${visibleList.length}`;
		};

		const open = (idx) => {
			render(idx);
			modal.hidden = false;
			modal.setAttribute('aria-hidden', 'false');
			// アニメーション起動
			requestAnimationFrame(() => modal.classList.add('is-open'));
			document.body.classList.add('is-modal-open');
		};

		const close = () => {
			modal.classList.remove('is-open');
			setTimeout(() => {
				modal.hidden = true;
				modal.setAttribute('aria-hidden', 'true');
				document.body.classList.remove('is-modal-open');
			}, 200);
		};

		triggers.forEach((trigger, i) => {
			trigger.addEventListener('click', () => {
				refreshVisible();
				const visIdx = visibleList.indexOf(trigger);
				open(visIdx >= 0 ? visIdx : 0);
			});
		});

		prevBtn?.addEventListener('click', () => render(currentIdx - 1));
		nextBtn?.addEventListener('click', () => render(currentIdx + 1));

		// オーバーレイ・×ボタン → close
		modal.addEventListener('click', (e) => {
			if (e.target.closest('[data-close]')) close();
		});

		// キーボード操作
		document.addEventListener('keydown', (e) => {
			if (modal.hidden) return;
			if (e.key === 'ArrowLeft')  { e.preventDefault(); render(currentIdx - 1); }
			if (e.key === 'ArrowRight') { e.preventDefault(); render(currentIdx + 1); }
			if (e.key === 'Escape')     { close(); }
		});
	})();

	// ─── いいねボタン（REST API + localStorage 重複防止） ───
	document.querySelectorAll('.js-like').forEach((btn) => {
		const postId = btn.dataset.postId;
		if (!postId) return;
		const key = `liked:${postId}`;
		const countEl = btn.querySelector('.js-like-count');

		// 押済み状態を復元
		if (localStorage.getItem(key) === '1') {
			btn.classList.add('is-liked');
			btn.setAttribute('aria-pressed', 'true');
		}

		btn.addEventListener('click', async (e) => {
			e.preventDefault();
			e.stopPropagation();
			if (localStorage.getItem(key) === '1') return; // 連打防止

			// 楽観的UI: 即時+1
			const current = parseInt(countEl?.textContent || '0', 10);
			if (countEl) countEl.textContent = String(current + 1);
			btn.classList.add('is-liked');
			btn.setAttribute('aria-pressed', 'true');
			localStorage.setItem(key, '1');

			try {
				const res = await fetch(`/wp-json/sc/v1/like/${postId}`, { method: 'POST' });
				if (!res.ok) throw new Error('rest failed');
				const data = await res.json();
				if (countEl && typeof data.count === 'number') {
					countEl.textContent = String(data.count);
				}
			} catch (err) {
				// 失敗したらUI戻す
				if (countEl) countEl.textContent = String(current);
				btn.classList.remove('is-liked');
				btn.setAttribute('aria-pressed', 'false');
				localStorage.removeItem(key);
			}
		});
	});

	// ─── イベントカルーセル ─────────────────────────────────
	document.querySelectorAll('.js-event-carousel, .js-column-carousel, .js-home-event-carousel').forEach((carousel) => {
		const slides = carousel.querySelectorAll('[data-slide]');
		const dots = carousel.querySelectorAll('[data-target]');
		const prev = carousel.querySelector('.js-carousel-prev');
		const next = carousel.querySelector('.js-carousel-next');
		if (slides.length <= 1) return;
		let idx = 0;
		let timer = null;
		const go = (i) => {
			idx = (i + slides.length) % slides.length;
			slides.forEach((s, n) => s.classList.toggle('is-active', n === idx));
			dots.forEach((d, n) => d.classList.toggle('is-active', n === idx));
		};
		const stop = () => { if (timer) { clearInterval(timer); timer = null; } };
		const start = () => { stop(); timer = setInterval(() => go(idx + 1), 5000); };
		prev && prev.addEventListener('click', () => { go(idx - 1); start(); });
		next && next.addEventListener('click', () => { go(idx + 1); start(); });
		dots.forEach((d, n) => d.addEventListener('click', () => { go(n); start(); }));
		carousel.addEventListener('mouseenter', stop);
		carousel.addEventListener('mouseleave', start);
		start();
	});

	// ─── スポット詳細 メインビジュアル スライダー ──────────────
	document.querySelectorAll('.js-spot-mv').forEach((mv) => {
		const slides = mv.querySelectorAll('.p-spot-detail__mv-slide');
		const thumbs = mv.querySelectorAll('.p-spot-detail__mv-thumb');
		const prev   = mv.querySelector('.js-spot-mv-prev');
		const next   = mv.querySelector('.js-spot-mv-next');
		const counter = mv.querySelector('.js-spot-mv-current');
		const total  = slides.length;
		let current  = 0;

		const show = (i) => {
			current = (i + total) % total;
			slides.forEach((s, idx) => {
				const on = idx === current;
				s.classList.toggle('is-active', on);
				s.setAttribute('aria-hidden', on ? 'false' : 'true');
				s.setAttribute('tabindex', on ? '0' : '-1');
			});
			thumbs.forEach((t, idx) => {
				const on = idx === current;
				t.classList.toggle('is-active', on);
				t.setAttribute('aria-selected', on ? 'true' : 'false');
			});
			if (counter) counter.textContent = String(current + 1);
		};

		if (prev) prev.addEventListener('click', () => show(current - 1));
		if (next) next.addEventListener('click', () => show(current + 1));
		thumbs.forEach((t) => t.addEventListener('click', () => show(parseInt(t.dataset.index, 10) || 0)));

		mv.addEventListener('keydown', (e) => {
			if (e.key === 'ArrowLeft')  { e.preventDefault(); show(current - 1); }
			if (e.key === 'ArrowRight') { e.preventDefault(); show(current + 1); }
		});
	});

	// ─── 星評価入力（レビューフォーム） ───────────────────────
	document.querySelectorAll('.js-rating-input').forEach((wrap) => {
		const stars = wrap.querySelectorAll('.p-spot-detail__rating-star');
		const input = wrap.querySelector('input[name="sc_rating"]');
		const setStars = (val, hover) => {
			stars.forEach((s, idx) => {
				s.classList.toggle('is-on', idx < val);
				s.classList.toggle('is-hover', hover && idx < val);
			});
		};
		stars.forEach((s) => {
			const v = parseInt(s.dataset.value, 10) || 0;
			s.addEventListener('mouseenter', () => setStars(v, true));
			s.addEventListener('mouseleave', () => setStars(parseInt(input.value, 10) || 0, false));
			s.addEventListener('click', () => {
				input.value = String(v);
				setStars(v, false);
			});
		});
	});

	// ─── Google Translate バーを強制非表示（ウィジェット注入後に上書きされるため JS で監視）─────────────
	const hideGTBar = () => {
		const bar = document.querySelector('.goog-te-banner-frame');
		if (bar) bar.style.setProperty('display', 'none', 'important');
		// GT がボディに top を付与するのをリセット
		if (document.body.style.top && document.body.style.top !== '0px') {
			document.body.style.setProperty('top', '0', 'important');
		}
	};
	const gtObserver = new MutationObserver(hideGTBar);
	gtObserver.observe(document.body, { childList: true, subtree: false });
	hideGTBar();

	// ─── 言語ドロップダウン（Google Translate クッキー制御）─────────────
	const langWrap   = document.querySelector('.js-lang-wrap');
	const langToggle = document.querySelector('.js-lang-toggle');
	const langDropdown = langWrap?.querySelector('.l-header__lang-dropdown');

	const closeLangDropdown = () => {
		langToggle?.setAttribute('aria-expanded', 'false');
		langDropdown?.classList.remove('is-open');
	};

	langToggle?.addEventListener('click', (e) => {
		e.stopPropagation();
		const isOpen = langToggle.getAttribute('aria-expanded') === 'true';
		langToggle.setAttribute('aria-expanded', String(!isOpen));
		langDropdown?.classList.toggle('is-open', !isOpen);
	});

	document.querySelectorAll('.js-translate-option').forEach((btn) => {
		btn.addEventListener('click', () => {
			const lang    = btn.dataset.lang;
			const name    = 'googtrans';
			const expires = 'expires=Thu, 01 Jan 1970 00:00:00 UTC';

			if (lang === 'ja') {
				// 日本語：クッキー削除
				document.cookie = `${name}=; ${expires}; path=/`;
				document.cookie = `${name}=; ${expires}; path=/; domain=${location.hostname}`;
			} else {
				// 英語：クッキーをセット
				document.cookie = `${name}=/ja/${lang}; path=/`;
				document.cookie = `${name}=/ja/${lang}; path=/; domain=${location.hostname}`;
			}
			location.reload();
		});
	});

	// ドロップダウン外クリックで閉じる
	document.addEventListener('click', closeLangDropdown);

	// ESC で閉じる
	document.addEventListener('keydown', (e) => {
		if (e.key === 'Escape') closeLangDropdown();
	});

	// ─── SP 追従 CTA：上スクロール時に表示、下スクロール時に隠す ──
	const mobileCta = document.querySelector('.c-mobile-cta');
	if ( mobileCta ) {
		let lastY = window.scrollY;
		window.addEventListener( 'scroll', () => {
			const currentY = window.scrollY;
			if ( currentY < lastY ) {
				mobileCta.classList.add( 'is-visible' );
			} else {
				mobileCta.classList.remove( 'is-visible' );
			}
			lastY = currentY;
		}, { passive: true } );
	}

	// ─── URLコピー＋トースト ──────────────────────────────
	const showToast = (msg) => {
		const existing = document.querySelector('.c-toast');
		if (existing) existing.remove();
		const toast = document.createElement('div');
		toast.className = 'c-toast';
		toast.textContent = msg;
		document.body.appendChild(toast);
		requestAnimationFrame(() => toast.classList.add('is-visible'));
		setTimeout(() => {
			toast.classList.remove('is-visible');
			toast.addEventListener('transitionend', () => toast.remove(), { once: true });
		}, 2000);
	};

	document.querySelectorAll('.js-copy-url').forEach((btn) => {
		btn.addEventListener('click', () => {
			const url = btn.dataset.url || location.href;
			if (navigator.clipboard) {
				navigator.clipboard.writeText(url).then(() => showToast('URLをコピーしました'));
			} else {
				// フォールバック（古いブラウザ）
				const ta = document.createElement('textarea');
				ta.value = url;
				ta.style.position = 'fixed';
				ta.style.opacity = '0';
				document.body.appendChild(ta);
				ta.select();
				document.execCommand('copy');
				document.body.removeChild(ta);
				showToast('URLをコピーしました');
			}
		});
	});

	// ─── ヒーローアニメ: タイトル文字ばらし出現 + 地図ピン表示 ──
	(function () {
		const hero = document.querySelector('.p-home-hero');
		if (!hero) return;

		// 文字分割（aria-label に元テキストを保持済）
		document.querySelectorAll('.js-hero-split').forEach((el) => {
			const text = el.textContent;
			el.textContent = '';
			[...text].forEach((ch, i) => {
				const span = document.createElement('span');
				span.className = 'p-home-hero__char';
				span.textContent = ch === ' ' ? ' ' : ch;
				// 文字ごとの遅延（70ms ずつ）
				span.style.animationDelay = (0.15 + i * 0.07) + 's';
				el.appendChild(span);
			});
		});

		// h1 / リードの遅延設定（順次フェードイン）
		const fades = document.querySelectorAll('.p-home-hero .js-hero-fade');
		fades.forEach((el, i) => {
			el.style.setProperty('--hero-delay', (1.0 + i * 0.25) + 's');
		});

		// 強制リフロー（CSSの初期スタイルを確定させる）
		void hero.offsetHeight;

		// 次フレームで一括 is-ready 付与（CSS transition 発火）
		requestAnimationFrame(() => {
			hero.classList.add('is-ready');
			document.querySelectorAll('.p-home-hero .js-hero-split, .p-home-hero .js-hero-fade').forEach((el) => {
				el.classList.add('is-ready');
			});
		});
	}());

	// ─── 観光スポット タブ切替 ─────────────────────────────
	(function () {
		const tabs   = document.querySelectorAll('.p-visit__spots-tab');
		const panels = document.querySelectorAll('.p-visit__spots-panel');
		if ( ! tabs.length ) return;

		tabs.forEach((tab) => {
			tab.addEventListener('click', () => {
				const type = tab.dataset.spotType;

				tabs.forEach((t) => {
					t.classList.remove('is-active');
					t.setAttribute('aria-selected', 'false');
				});
				panels.forEach((p) => {
					p.classList.remove('is-active');
					p.setAttribute('hidden', 'hidden');
				});

				tab.classList.add('is-active');
				tab.setAttribute('aria-selected', 'true');
				const panel = document.getElementById('spot-panel-' + type);
				if (panel) {
					panel.classList.add('is-active');
					panel.removeAttribute('hidden');
				}
			});
		});
	}());

	// ─── フィルターアコーディオン（SP のみ）────────────────────
	document.querySelectorAll('.c-filter-sidebar__head[aria-controls]').forEach((filterToggle) => {
		const filterBody = document.getElementById(filterToggle.getAttribute('aria-controls'));
		const filterMq   = window.matchMedia('(max-width: 768px)');
		if (!filterBody) return;

		const setFilterOpen = (open) => {
			filterToggle.setAttribute('aria-expanded', String(open));
			filterBody.classList.toggle('is-open', open);
		};

		const syncFilter = () => {
			setFilterOpen(!filterMq.matches);
		};

		filterToggle.addEventListener('click', () => {
			if (!filterMq.matches) return; // PC では常時表示
			setFilterOpen(filterToggle.getAttribute('aria-expanded') !== 'true');
		});

		syncFilter();
		filterMq.addEventListener('change', syncFilter);
	});

	// ─── お気に入りボタン（localStorage で記憶）─────────────
	document.querySelectorAll('.js-favorite').forEach((btn) => {
		const key = 'fav:' + (btn.dataset.postId || location.pathname);
		const useEl = btn.querySelector('use');
		const setState = (on) => {
			btn.setAttribute('aria-pressed', String(on));
			btn.setAttribute('aria-label', on ? 'お気に入りから削除' : 'お気に入りに追加');
			if (useEl) useEl.setAttribute('href', on ? '#icon-heart-solid' : '#icon-heart-outline');
		};
		setState(localStorage.getItem(key) === '1');
		btn.addEventListener('click', () => {
			const on = btn.getAttribute('aria-pressed') !== 'true';
			localStorage.setItem(key, on ? '1' : '0');
			setState(on);
		});
	});

	// ─── エリア詳細: 歴史・文化タブ + ページネーション ──────
	(function () {
		const tabsWrap = document.querySelector('.js-history-tabs');
		const panelsWrap = document.querySelector('.js-history-panels');
		if (!tabsWrap || !panelsWrap) return;

		const tabs = [...tabsWrap.querySelectorAll('.p-area__history-tab')];
		const panels = [...panelsWrap.querySelectorAll('.p-area__history-panel')];
		const total = tabs.length;

		const activate = (idx) => {
			if (idx < 0 || idx >= total) return;
			tabs.forEach((t, i) => {
				const on = i === idx;
				t.classList.toggle('is-active', on);
				t.setAttribute('aria-selected', String(on));
			});
			panels.forEach((p, i) => {
				const on = i === idx;
				p.classList.toggle('is-active', on);
				p.hidden = !on;
			});
		};

		tabs.forEach((t, i) => t.addEventListener('click', () => activate(i)));

		// 各パネル内の 前へ / 次へ
		panels.forEach((p, i) => {
			const prev = p.querySelector('.js-history-prev');
			const next = p.querySelector('.js-history-next');
			if (prev) prev.addEventListener('click', () => activate(i - 1));
			if (next) next.addEventListener('click', () => activate(i + 1));
		});
	}());

});
