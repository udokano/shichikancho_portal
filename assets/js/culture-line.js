( function () {
	'use strict';

	// ─── エリアネットワーク（バブルマップ）── hover/click で詳細パネル表示
	const nodes   = document.querySelectorAll( '.js-cl-node' );
	const detail  = document.querySelector( '.p-cl__network-detail' );
	const closeBtn = document.querySelector( '.js-cl-close' );
	const nameEl  = detail ? detail.querySelector( '.js-cl-name' ) : null;
	const descEl  = detail ? detail.querySelector( '.js-cl-desc' ) : null;

	if ( nodes.length && detail && nameEl && descEl ) {
		function showDetail( node ) {
			nodes.forEach( n => n.classList.remove( 'is-active' ) );
			node.classList.add( 'is-active' );
			nameEl.textContent = node.dataset.name || '';
			descEl.textContent = node.dataset.desc || '';
			detail.hidden = false;
		}

		function hideDetail() {
			nodes.forEach( n => n.classList.remove( 'is-active' ) );
			detail.hidden = true;
		}

		function toggle( node ) {
			if ( node.classList.contains( 'is-active' ) ) {
				hideDetail();
			} else {
				showDetail( node );
			}
		}

		nodes.forEach( function ( node ) {
			// hover で表示（PC）
			node.addEventListener( 'mouseenter', function () {
				showDetail( node );
			} );

			// click で toggle（スマホ対応）
			node.addEventListener( 'click', function ( e ) {
				e.stopPropagation();
				toggle( node );
			} );

			// キーボード操作（SVG では .click() が効かない環境があるため直接 toggle）
			node.addEventListener( 'keydown', function ( e ) {
				if ( e.key === 'Enter' || e.key === ' ' ) {
					e.preventDefault();
					e.stopPropagation();
					toggle( node );
				}
			} );
		} );

		if ( closeBtn ) {
			closeBtn.addEventListener( 'click', function ( e ) {
				e.stopPropagation();
				hideDetail();
			} );
		}

		// マップ外クリックで閉じる
		document.addEventListener( 'click', function ( e ) {
			if ( ! e.target.closest( '.p-cl__network-map' ) ) {
				hideDetail();
			}
		} );
	}

	// ─── モデルコースタブ
	const tabs   = document.querySelectorAll( '.p-cl__courses-tab' );
	const panels = document.querySelectorAll( '.p-cl__courses-panel' );

	if ( tabs.length && panels.length ) {
		tabs.forEach( function ( tab, i ) {
			tab.addEventListener( 'click', function () {
				tabs.forEach( t => {
					t.classList.remove( 'is-active' );
					t.setAttribute( 'aria-selected', 'false' );
				} );
				panels.forEach( p => {
					p.classList.remove( 'is-active' );
					p.hidden = true;
				} );

				tab.classList.add( 'is-active' );
				tab.setAttribute( 'aria-selected', 'true' );

				const panel = document.getElementById( 'cl-course-' + i );
				if ( panel ) {
					panel.classList.add( 'is-active' );
					panel.hidden = false;
				}
			} );
		} );
	}
}() );
