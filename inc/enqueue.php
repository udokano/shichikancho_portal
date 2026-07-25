<?php
// ─── Google Fonts：preconnect + 非同期読み込み（レンダーブロック回避）──
add_action( 'wp_head', function (): void {
	$fonts_url = 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Noto+Sans+JP:wght@400;500;600;700&display=swap';
	?>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link rel="preload" href="<?php echo esc_url( $fonts_url ); ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
	<noscript><link rel="stylesheet" href="<?php echo esc_url( $fonts_url ); ?>"></noscript>
	<?php
}, 1 );

// main.css は同期読み込みを維持（非同期化すると CLS が発生するため）

// ─── CF7 CSS/JS：フォームが無いページでは読み込まない ──
// contact はブロックエディタ管理なので本文のブロック/ショートコードで判定する
add_action( 'wp_enqueue_scripts', function (): void {
	$post    = get_post();
	$has_cf7 = false;

	if ( $post instanceof WP_Post ) {
		$has_cf7 = has_block( 'contact-form-7/contact-form-selector', $post )
			|| has_shortcode( (string) $post->post_content, 'contact-form-7' );
	}

	// フォトコンテストはテンプレート側で do_shortcode するため本文判定に出てこない
	if ( is_page( 'photo-contest' ) ) $has_cf7 = true;

	if ( $has_cf7 ) return;

	wp_dequeue_style( 'contact-form-7' );
	wp_deregister_style( 'contact-form-7' );
	wp_dequeue_script( 'contact-form-7' );
	wp_deregister_script( 'contact-form-7' );
	wp_dequeue_script( 'swv' );
	wp_deregister_script( 'swv' );
}, 100 );

add_action( 'wp_enqueue_scripts', 'sichikenchou_enqueue' );

function sichikenchou_enqueue() {
	$v = wp_get_theme()->get( 'Version' );

	// ファイル更新時刻でキャッシュバスト
	$css_ver = filemtime( get_template_directory() . '/assets/css/main.css' ) ?: $v;
	$js_ver  = filemtime( get_template_directory() . '/assets/js/main.js' ) ?: $v;

	wp_enqueue_style(
		'sichikenchou-main',
		SC_TPL_URI . '/assets/css/main.css',
		[],
		$css_ver
	);

	wp_enqueue_script(
		'sichikenchou-main',
		SC_TPL_URI . '/assets/js/main.js',
		[],
		$js_ver,
		true
	);

	// Leaflet（散策コース詳細ページのみ）
	if ( is_singular( CPT_WALK ) ) {
		wp_enqueue_style(
			'leaflet',
			'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css',
			[],
			'1.9.4'
		);
		wp_enqueue_script(
			'leaflet',
			'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js',
			[],
			'1.9.4',
			true
		);
		$walkmap_js = get_template_directory() . '/assets/js/walkmap.js';
		if ( file_exists( $walkmap_js ) ) {
			wp_enqueue_script(
				'sichikenchou-walkmap',
				SC_TPL_URI . '/assets/js/walkmap.js',
				[ 'leaflet' ],
				filemtime( $walkmap_js ),
				true
			);
		}
	}

	// about ページのネットワーク図インタラクション
	if ( is_page( 'about' ) ) {
		$about_js = get_template_directory() . '/assets/js/about-network.js';
		wp_enqueue_script(
			'sichikenchou-about-network',
			SC_TPL_URI . '/assets/js/about-network.js',
			[],
			filemtime( $about_js ),
			true
		);
	}

	// お隣さんの話 PICK UP slick スライダー
	if ( is_post_type_archive( CPT_RESIDENT ) ) {
		wp_enqueue_style( 'slick',       'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css',       [], '1.8.1' );
		wp_enqueue_style( 'slick-theme', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css', [], '1.8.1' );
		wp_enqueue_script( 'slick', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', [ 'jquery' ], '1.8.1', true );

		$r_js = get_template_directory() . '/assets/js/resident-slider.js';
		wp_enqueue_script(
			'sichikenchou-resident-slider',
			SC_TPL_URI . '/assets/js/resident-slider.js',
			[ 'jquery', 'slick' ],
			filemtime( $r_js ),
			true
		);
	}

	// SP センタースライダー（暮らし「住んでいる人の声」/ フォトコン「これまでの投稿作品」/ お店・イベントアーカイブ PICK UP）
	if ( is_page( 'living' ) || is_page( 'photo-contest' ) || is_post_type_archive( CPT_SHOP ) || is_post_type_archive( CPT_EVENT ) ) {
		wp_enqueue_style( 'slick',       'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css',       [], '1.8.1' );
		wp_enqueue_style( 'slick-theme', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css', [], '1.8.1' );
		wp_enqueue_script( 'slick', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', [ 'jquery' ], '1.8.1', true );

		$cs_js = get_template_directory() . '/assets/js/center-slider.js';
		wp_enqueue_script(
			'sichikenchou-center-slider',
			SC_TPL_URI . '/assets/js/center-slider.js',
			[ 'jquery', 'slick' ],
			filemtime( $cs_js ),
			true
		);
	}

	// 町のギャラリー 無限スクロール
	if ( is_page( 'gallery' ) ) {
		$g_js = get_template_directory() . '/assets/js/gallery-infinite.js';
		wp_enqueue_script(
			'sichikenchou-gallery-infinite',
			SC_TPL_URI . '/assets/js/gallery-infinite.js',
			[],
			filemtime( $g_js ),
			true
		);
		wp_localize_script( 'sichikenchou-gallery-infinite', 'SC_AJAX', [
			'url' => admin_url( 'admin-ajax.php' ),
		] );
	}

	// カルチャーラインページのタブ・ネットワークインタラクション
	if ( is_page( 'culture-line' ) ) {
		$cl_js = get_template_directory() . '/assets/js/culture-line.js';
		wp_enqueue_script(
			'sichikenchou-culture-line',
			SC_TPL_URI . '/assets/js/culture-line.js',
			[],
			filemtime( $cl_js ),
			true
		);
	}

	// フロントページ Google Maps お店・スポットマップ
	$sc_gmaps_key = defined( 'SC_GOOGLE_MAPS_KEY' ) ? SC_GOOGLE_MAPS_KEY : '';
	if ( is_front_page() && $sc_gmaps_key ) {
		$pins = [];

		// お店
		$shop_q = new WP_Query( [
			'post_type'      => CPT_SHOP,
			'posts_per_page' => -1,
			'meta_query'     => [
				'relation' => 'AND',
				[ 'key' => 'shop_map_lat', 'value' => '', 'compare' => '!=' ],
				[ 'key' => 'shop_map_lng', 'value' => '', 'compare' => '!=' ],
			],
		] );
		while ( $shop_q->have_posts() ) {
			$shop_q->the_post();
			$id  = get_the_ID();
			$lat = (float) get_field( 'shop_map_lat', $id );
			$lng = (float) get_field( 'shop_map_lng', $id );
			if ( ! $lat || ! $lng ) continue;
			$cats = get_the_terms( $id, TAX_SHOP_CAT );
			$cat  = ( $cats && ! is_wp_error( $cats ) ) ? $cats[0]->name : '';
			$pins[] = [
				'type'  => 'shop',
				'lat'   => $lat,
				'lng'   => $lng,
				'name'  => get_the_title(),
				'cat'   => $cat,
				'url'   => get_permalink(),
				'thumb' => get_the_post_thumbnail_url( $id, 'thumbnail' ) ?: '',
			];
		}
		wp_reset_postdata();

		// スポット
		$spot_q = new WP_Query( [
			'post_type'      => CPT_SPOT,
			'posts_per_page' => -1,
			'meta_query'     => [
				'relation' => 'AND',
				[ 'key' => 'spot_map_lat', 'value' => '', 'compare' => '!=' ],
				[ 'key' => 'spot_map_lng', 'value' => '', 'compare' => '!=' ],
			],
		] );
		while ( $spot_q->have_posts() ) {
			$spot_q->the_post();
			$id  = get_the_ID();
			$lat = (float) get_field( 'spot_map_lat', $id );
			$lng = (float) get_field( 'spot_map_lng', $id );
			if ( ! $lat || ! $lng ) continue;
			$types = get_the_terms( $id, TAX_SPOT_TYPE );
			$type  = ( $types && ! is_wp_error( $types ) ) ? $types[0]->name : '';
			$pins[] = [
				'type'  => 'spot',
				'lat'   => $lat,
				'lng'   => $lng,
				'name'  => get_the_title(),
				'cat'   => $type,
				'url'   => get_permalink(),
				'thumb' => get_the_post_thumbnail_url( $id, 'thumbnail' ) ?: '',
			];
		}
		wp_reset_postdata();

		// frontpage-map.js に先行して scMapData を出力し、Google Maps の callback で参照させる
		$frontmap_js = get_template_directory() . '/assets/js/frontpage-map.js';
		wp_enqueue_script(
			'sichikenchou-frontpage-map',
			SC_TPL_URI . '/assets/js/frontpage-map.js',
			[],
			file_exists( $frontmap_js ) ? filemtime( $frontmap_js ) : '1',
			true
		);
		wp_localize_script( 'sichikenchou-frontpage-map', 'scMapData', [
			'pins'   => $pins,
			'center' => [ 34.9756, 138.3828 ],
			'zoom'   => 16,
		] );

		// Google Maps API は frontpage-map.js の後ろに動的挿入（callback=initScMap）
		$api_key = esc_attr( $sc_gmaps_key );
		wp_add_inline_script(
			'sichikenchou-frontpage-map',
			'(function(){var s=document.createElement("script");'
			. 's.src="https://maps.googleapis.com/maps/api/js?key=' . $api_key . '&callback=initScMap&loading=async";'
			. 's.async=true;document.head.appendChild(s);})();',
			'after'
		);
	}
}

// ─── ブロックエディタ用スタイル ──
add_action( 'after_setup_theme', function (): void {
	add_theme_support( 'editor-styles' );
	// Google Fonts のみ add_editor_style 経由
	add_editor_style( 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Noto+Sans+JP:wght@400;500;600;700&display=swap' );
} );

// JS 側の未翻訳 UI 文字列を補完（WP 6.9 AI Assistant 関連等）
add_action( 'enqueue_block_editor_assets', function (): void {
	$map = [
		'Type / to choose a block or // to use AI Assistant' => '/ でブロック選択、// で AI アシスタント',
		'Type / to choose a block'                            => '/ でブロックを選択',
	];

	// 1) wp.i18n.setLocaleData で通常の翻訳パスを上書き
	$messages = [];
	foreach ( $map as $en => $ja ) {
		$messages[ $en ] = [ $ja ];
	}
	$data = [ '' => [ 'domain' => 'default', 'lang' => 'ja' ] ] + $messages;
	$json_data = wp_json_encode( $data );
	wp_add_inline_script( 'wp-i18n', "wp.i18n.setLocaleData( {$json_data}, 'default' );" );

	// 2) フォールバック: DOM を MutationObserver で監視して placeholder 属性／テキストを置換
	$json_map = wp_json_encode( $map );
	$dom_js = <<<JS
( function() {
	var MAP = {$json_map};
	function tryReplace( el ) {
		if ( ! el || ! el.textContent ) return;
		var raw = el.textContent.replace( /\s+/g, ' ' ).trim();
		for ( var en in MAP ) {
			if ( raw === en && el.dataset.scI18n !== '1' ) {
				el.textContent = MAP[ en ];
				el.dataset.scI18n = '1';
				return;
			}
		}
	}
	function replaceIn( doc ) {
		if ( ! doc || ! doc.body ) return;
		// 通常の placeholder 属性
		doc.querySelectorAll( '[placeholder]' ).forEach( function( el ) {
			var v = el.getAttribute( 'placeholder' );
			if ( MAP[ v ] ) el.setAttribute( 'placeholder', MAP[ v ] );
		} );
		// Gutenberg の rich-text placeholder（CSS擬似要素で表示される）
		doc.querySelectorAll( '[data-rich-text-placeholder]' ).forEach( function( el ) {
			var v = el.getAttribute( 'data-rich-text-placeholder' );
			if ( MAP[ v ] ) el.setAttribute( 'data-rich-text-placeholder', MAP[ v ] );
		} );
		// aria-label 経由の placeholder
		doc.querySelectorAll( '[aria-label]' ).forEach( function( el ) {
			var v = el.getAttribute( 'aria-label' );
			if ( MAP[ v ] ) el.setAttribute( 'aria-label', MAP[ v ] );
		} );
		// 子に <kbd> 等を含む可能性があるので要素単位で textContent 照合
		doc.querySelectorAll( 'p, span, div' ).forEach( tryReplace );
	}
	function watch( doc ) {
		replaceIn( doc );
		new MutationObserver( function() { replaceIn( doc ); } )
			.observe( doc.body, { childList: true, subtree: true, characterData: true, attributes: true, attributeFilter: [ 'placeholder', 'data-rich-text-placeholder', 'aria-label' ] } );
	}
	function start() {
		watch( document );
		setInterval( function() {
			document.querySelectorAll( 'iframe' ).forEach( function( ifr ) {
				try {
					if ( ifr._scWatched || ! ifr.contentDocument || ! ifr.contentDocument.body ) return;
					ifr._scWatched = true;
					watch( ifr.contentDocument );
				} catch ( e ) {}
			} );
		}, 500 );
	}
	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', start );
	} else {
		start();
	}
} )();
JS;
	wp_add_inline_script( 'wp-i18n', $dom_js );
} );

// SVG スプライトをエディタ iframe に注入（ACF ブロック内の <use href="#icon-*"> を解決するため）
add_action( 'enqueue_block_editor_assets', function (): void {
	ob_start();
	get_template_part( 'template-parts/components/svg-sprite' );
	$sprite = ob_get_clean();
	if ( ! $sprite ) return;
	$json = wp_json_encode( $sprite );
	wp_add_inline_script(
		'wp-edit-post',
		"(function(){function inject(){var ifr=document.querySelector('iframe[name=\"editor-canvas\"]');if(!ifr||!ifr.contentDocument||!ifr.contentDocument.body){return setTimeout(inject,300);}if(ifr.contentDocument.getElementById('sc-editor-sprite'))return;var w=document.createElement('div');w.id='sc-editor-sprite';w.style.cssText='position:absolute;width:0;height:0;overflow:hidden';w.innerHTML={$json};ifr.contentDocument.body.appendChild(w);}setTimeout(inject,500);})();"
	);
} );

// editor-style.css をインラインで iframe に注入（キャッシュ無関係・常に最新）
add_filter( 'block_editor_settings_all', function ( array $settings ): array {
	$path = get_template_directory() . '/assets/css/editor-style.css';
	if ( file_exists( $path ) ) {
		$settings['styles'][] = [ 'css' => file_get_contents( $path ) ];
	}
	return $settings;
} );
