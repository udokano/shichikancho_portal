( function () {
	'use strict';

	const nodes   = document.querySelectorAll( '.js-network-node' );
	const detail  = document.querySelector( '.js-network-detail' );
	const closeBtn = document.querySelector( '.js-network-close' );

	if ( ! nodes.length || ! detail ) return;

	const nameEl = detail.querySelector( '.js-network-detail-name' );
	const descEl = detail.querySelector( '.js-network-detail-desc' );
	const tagEl  = detail.querySelector( '.js-network-detail-tag' );

	function showDetail( node ) {
		nodes.forEach( n => n.classList.remove( 'is-active' ) );
		node.classList.add( 'is-active' );

		nameEl.textContent = node.dataset.name  || '';
		descEl.textContent = node.dataset.desc  || '';
		tagEl.textContent  = node.dataset.tag   || '';

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
		// クリックは外側 click 監視と衝突するため stopPropagation
		node.addEventListener( 'click', function ( e ) {
			e.stopPropagation();
			toggle( node );
		} );

		// キーボード操作（Enter/Space）— SVG では node.click() が効かない環境があるため直接 toggle
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

	// 図の外クリックで閉じる
	document.addEventListener( 'click', function ( e ) {
		if ( ! e.target.closest( '.p-about__network-map' ) ) {
			hideDetail();
		}
	} );
}() );
