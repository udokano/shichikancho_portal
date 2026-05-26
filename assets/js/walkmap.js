// 散策コース詳細ページのルートマップ（Leaflet + OpenStreetMap）
( function () {
	const toggle = document.querySelector( '.js-walkmap-toggle' );
	const canvas = document.querySelector( '.js-walkmap' );
	if ( ! toggle || ! canvas || typeof L === 'undefined' ) return;

	let mapInstance = null;

	function buildIcon( index ) {
		// 番号付きピン（Manus のピンクサークル風）
		return L.divIcon( {
			className: 'p-walk-detail__map-pin',
			html: '<span>' + index + '</span>',
			iconSize: [ 32, 32 ],
			iconAnchor: [ 16, 16 ],
		} );
	}

	function init() {
		if ( mapInstance ) return;
		const points = JSON.parse( canvas.dataset.points || '[]' );
		if ( ! points.length ) return;

		mapInstance = L.map( canvas, { scrollWheelZoom: false } );
		L.tileLayer( 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			maxZoom: 19,
			attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
		} ).addTo( mapInstance );

		const latlngs = points.map( p => [ p.lat, p.lng ] );

		// マーカー
		points.forEach( p => {
			const m = L.marker( [ p.lat, p.lng ], { icon: buildIcon( p.index ) } ).addTo( mapInstance );
			m.bindPopup( '<strong>' + p.index + '. ' + p.name + '</strong><br><a href="' + p.url + '">詳細を見る →</a>' );
		} );

		// ルートライン
		if ( latlngs.length >= 2 ) {
			L.polyline( latlngs, {
				color: '#E91E63',
				weight: 4,
				opacity: 0.85,
			} ).addTo( mapInstance );
		}

		// 全マーカーを画面に収める
		const bounds = L.latLngBounds( latlngs );
		mapInstance.fitBounds( bounds, { padding: [ 32, 32 ], maxZoom: 16 } );

		// クリックでスクロールズーム解放
		mapInstance.on( 'click', () => mapInstance.scrollWheelZoom.enable() );
		mapInstance.on( 'mouseout', () => mapInstance.scrollWheelZoom.disable() );
	}

	toggle.addEventListener( 'click', () => {
		const expanded = toggle.getAttribute( 'aria-expanded' ) === 'true';
		const next = ! expanded;
		toggle.setAttribute( 'aria-expanded', next ? 'true' : 'false' );
		canvas.hidden = ! next;
		const label = toggle.querySelector( '.js-walkmap-toggle-label' );
		if ( label ) label.textContent = next ? '地図を閉じる' : '地図を表示';
		if ( next ) {
			init();
			// 表示直後に Leaflet が再計測できるよう invalidate
			setTimeout( () => mapInstance && mapInstance.invalidateSize(), 100 );
		}
	} );
} )();
