/* トップページ お店・スポットマップ（Google Maps JavaScript API） */

// Google Maps の callback として呼ばれるのでグローバル関数として定義
function initScMap() {
	var data = window.scMapData;
	if ( ! data ) return;

	var el = document.getElementById( 'home-map-canvas' );
	if ( ! el ) return;

	var map = new google.maps.Map( el, {
		center:            { lat: parseFloat( data.center[0] ), lng: parseFloat( data.center[1] ) },
		zoom:              parseInt( data.zoom, 10 ),
		mapTypeControl:    false,
		fullscreenControl: false,
		streetViewControl: false,
		styles: [
			{ featureType: 'poi', elementType: 'labels', stylers: [ { visibility: 'off' } ] },
		],
	} );

	// お店=青、スポット=緑
	var PIN_COLOR = { shop: '#1b6b93', spot: '#2e7d32' };

	var infoWindow = new google.maps.InfoWindow();
	var markers    = [];

	data.pins.forEach( function ( pin ) {
		if ( ! pin.lat || ! pin.lng ) return;

		var color  = PIN_COLOR[ pin.type ] || '#666';
		var svgPin = 'data:image/svg+xml;charset=UTF-8,'
			+ encodeURIComponent(
				'<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">'
				+ '<circle cx="16" cy="16" r="10" fill="' + color + '" stroke="#fff" stroke-width="2.5"/>'
				+ '</svg>'
			);

		var marker = new google.maps.Marker( {
			position: { lat: parseFloat( pin.lat ), lng: parseFloat( pin.lng ) },
			map:      map,
			icon: {
				url:        svgPin,
				scaledSize: new google.maps.Size( 32, 32 ),
				anchor:     new google.maps.Point( 16, 16 ),
			},
			title: pin.name,
		} );
		marker._scType = pin.type;
		markers.push( marker );

		marker.addListener( 'click', function () {
			var thumb = pin.thumb
				? '<img class="sc-map-popup__thumb" src="' + pin.thumb + '" alt="" aria-hidden="true">'
				: '';
			var cat = pin.cat
				? '<span class="sc-map-popup__cat">' + pin.cat + '</span>'
				: '';
			infoWindow.setContent(
				'<div class="sc-map-popup">'
				+   thumb
				+   '<div class="sc-map-popup__body">'
				+     cat
				+     '<a class="sc-map-popup__name" href="' + pin.url + '">' + pin.name + '</a>'
				+   '</div>'
				+ '</div>'
			);
			infoWindow.open( map, marker );
		} );
	} );

	// フィルターボタン
	var btns = el.parentElement.querySelectorAll( '[data-map-filter]' );
	btns.forEach( function ( btn ) {
		btn.addEventListener( 'click', function () {
			var filter = this.dataset.mapFilter;
			btns.forEach( function ( b ) { b.classList.remove( 'is-active' ); } );
			this.classList.add( 'is-active' );
			infoWindow.close();
			markers.forEach( function ( m ) {
				m.setVisible( filter === 'all' || m._scType === filter );
			} );
		} );
	} );
}
