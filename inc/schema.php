<?php
/**
 * 構造化データ（JSON-LD）出力関数群
 * 各テンプレートから呼び出す。
 *
 * 共通定数
 */
define( 'SC_SITE_URL',  home_url( '/' ) );
define( 'SC_ORG_NAME',  '七間町商店街振興組合' );
define( 'SC_SITE_NAME', '七間町 ─ しちけんちょう' );
define( 'SC_ADDRESS', [
	'@type'           => 'PostalAddress',
	'streetAddress'   => '七間町',
	'addressLocality' => '静岡市葵区',
	'addressRegion'   => '静岡県',
	'postalCode'      => '420-0035',
	'addressCountry'  => 'JP',
] );
define( 'SC_GEO', [
	'@type'     => 'GeoCoordinates',
	'latitude'  => '34.9715',
	'longitude' => '138.3827',
] );

// JSON-LD を <script> タグで出力するヘルパー
function sc_output_schema( array $data ): void {
	echo '<script type="application/ld+json">' . "\n";
	echo wp_json_encode( $data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES );
	echo "\n</script>\n";
}

// ─────────────────────────────────────────────────────────
// 全ページ共通（header.php から呼び出す）
// ─────────────────────────────────────────────────────────

function schema_website(): void {
	sc_output_schema( [
		'@context'        => 'https://schema.org',
		'@type'           => 'WebSite',
		'name'            => SC_SITE_NAME,
		'url'             => SC_SITE_URL,
		'description'     => '静岡市葵区・七間町商店街の公式サイト。お店、イベント、観光、暮らしの情報をお届けします。',
		'inLanguage'      => 'ja',
		'potentialAction' => [
			'@type'       => 'SearchAction',
			'target'      => SC_SITE_URL . '?s={search_term_string}',
			'query-input' => 'required name=search_term_string',
		],
	] );
}

function schema_organization(): void {
	sc_output_schema( [
		'@context' => 'https://schema.org',
		'@type'    => 'Organization',
		'name'     => SC_ORG_NAME,
		'url'      => SC_SITE_URL,
		'logo'     => [
			'@type' => 'ImageObject',
			'url'   => SC_TPL_URI . '/assets/images/logo/logo.png',
		],
		'address'  => SC_ADDRESS,
		'geo'      => SC_GEO,
		'sameAs'   => [
			'https://www.instagram.com/shichikencho/',
			'https://www.facebook.com/shichikencho/',
		],
		'contactPoint' => [
			'@type'             => 'ContactPoint',
			'contactType'       => 'customer service',
			'availableLanguage' => 'Japanese',
			'url'               => SC_SITE_URL . 'contact/',
		],
	] );
}

function schema_breadcrumb(): void {
	if ( is_front_page() ) return;
	if ( ! function_exists( 'sc_get_breadcrumbs' ) ) return;

	$crumbs = sc_get_breadcrumbs();
	if ( count( $crumbs ) < 2 ) return;

	$items = [];
	foreach ( $crumbs as $i => $crumb ) {
		$item = [
			'@type'    => 'ListItem',
			'position' => $i + 1,
			'name'     => $crumb['label'],
		];
		if ( $crumb['url'] ) {
			$item['item'] = $crumb['url'];
		}
		$items[] = $item;
	}

	sc_output_schema( [
		'@context'        => 'https://schema.org',
		'@type'           => 'BreadcrumbList',
		'itemListElement' => $items,
	] );
}

// ─────────────────────────────────────────────────────────
// トップページ（front-page.php から呼び出す）
// ─────────────────────────────────────────────────────────

function schema_local_business(): void {
	$data = [
		'@context'      => 'https://schema.org',
		'@type'         => [ 'ShoppingCenter', 'LocalBusiness' ],
		'@id'           => SC_SITE_URL . '#localbusiness',
		'name'          => '七間町商店街',
		'alternateName' => 'しちけんちょう商店街',
		'url'           => SC_SITE_URL,
		'description'   => '静岡市葵区に位置する歴史ある商店街。飲食・物販・サービス・文化施設が集まる「文化×日常」の街。',
		'address'       => SC_ADDRESS,
		'geo'           => SC_GEO,
		'hasMap'        => 'https://maps.google.com/?q=' . SC_GEO['latitude'] . ',' . SC_GEO['longitude'],
		// 商店街全体の統一営業時間は存在しない。根拠の無い Mo-Su 10:00-21:00 は出さない
		'priceRange'    => '¥〜¥¥¥',
		'image'         => SC_TPL_URI . '/assets/images/common/ogp.jpg',
		'areaServed'    => [
			'@type'          => 'AdministrativeArea',
			'name'           => '静岡市葵区',
			'addressRegion'  => '静岡県',
			'addressCountry' => 'JP',
		],
		'keywords'      => '七間町,静岡,商店街,観光,グルメ,イベント,映画の町',
	];

	// 振興組合の代表電話（SC_TELEPHONE 未定義時はキー自体を出力しない）
	if ( defined( 'SC_TELEPHONE' ) && SC_TELEPHONE ) {
		$data['telephone'] = SC_TELEPHONE;
	}

	sc_output_schema( $data );
}

// ─────────────────────────────────────────────────────────
// お店個別（single-shop.php から呼び出す）
// ─────────────────────────────────────────────────────────

/**
 * shop_category タクソノミー slug → schema.org 型 のマップ
 */
const SC_SHOP_TYPE_MAP = [
	// 英語スラッグ
	'cafe'       => 'CafeOrCoffeeShop',
	'restaurant' => 'Restaurant',
	'bar'        => 'BarOrPub',
	'bakery'     => 'Bakery',
	'retail'     => 'Store',
	'shop'       => 'Store',
	'apparel'    => 'ClothingStore',
	'beauty'     => 'BeautySalon',
	'salon'      => 'HairSalon',
	'health'     => 'HealthAndBeautyBusiness',
	'pharmacy'   => 'Pharmacy',
	'medical'    => 'MedicalBusiness',
	'gym'        => 'SportsActivityLocation',
	'craft'      => 'Store',
	'gallery'    => 'ArtGallery',
	'service'    => 'ProfessionalService',
	// 当サイトの実スラッグ（日本語）対応
	'食べる'      => 'Restaurant',
	'買う'        => 'Store',
	'遊ぶ'        => 'EntertainmentBusiness',
	'サービス'    => 'ProfessionalService',
	'医療・美容'  => 'HealthAndBeautyBusiness',
	'その他'      => 'LocalBusiness',
];

/**
 * shop_category の最初のターム slug を見て schema.org 型を返す。
 * 該当無し or タームなしは LocalBusiness にフォールバック。
 */
function sc_shop_schema_type( int $post_id ): string {
	$terms = wp_get_post_terms( $post_id, 'shop_category' );
	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		return 'LocalBusiness';
	}
	foreach ( $terms as $term ) {
		// 英語スラッグ / URLエンコード済みスラッグをデコードした値 / 日本語名 のいずれでヒットしてもよい
		$candidates = [
			$term->slug,
			urldecode( $term->slug ),
			$term->name,
		];
		foreach ( $candidates as $key ) {
			if ( isset( SC_SHOP_TYPE_MAP[ $key ] ) ) {
				return SC_SHOP_TYPE_MAP[ $key ];
			}
		}
	}
	return 'LocalBusiness';
}

/**
 * shop_features チェックボックスから paymentAccepted 配列を生成。
 * 現金は常に含める。
 */
function sc_shop_payment_accepted( $features ): array {
	$accepted = [ 'Cash' ];
	if ( ! is_array( $features ) ) {
		return $accepted;
	}
	if ( in_array( 'card', $features, true ) ) {
		$accepted[] = 'Credit Card';
	}
	if ( in_array( 'qr', $features, true ) ) {
		$accepted[] = 'QR Code';
	}
	return $accepted;
}

/**
 * shop_closed フリーテキストから dayOfWeek 配列を抽出。
 * 例: "月・火" → [Mo, Tu]、"水曜定休" → [We]
 * 抽出に失敗 (空 / 不定休 / 該当無し) の場合は空配列。
 */
function sc_shop_closed_days( string $closed ): array {
	$map = [
		'月' => 'Monday',
		'火' => 'Tuesday',
		'水' => 'Wednesday',
		'木' => 'Thursday',
		'金' => 'Friday',
		'土' => 'Saturday',
		'日' => 'Sunday',
	];
	$days = [];
	foreach ( $map as $jp => $en ) {
		if ( mb_strpos( $closed, $jp ) !== false ) {
			$days[] = $en;
		}
	}
	return array_values( array_unique( $days ) );
}

/**
 * shop_hours フリーテキストから "HH:MM-HH:MM" 形式に正規化。
 * 抽出失敗時は元文字列を返す（呼び出し側で扱いを切り替え）。
 *
 * @return array{normalized:?string, raw:string}
 */
function sc_shop_normalize_hours( string $hours ): array {
	$raw = trim( $hours );
	if ( $raw === '' ) {
		return [ 'normalized' => null, 'raw' => '' ];
	}
	$ranges = sc_shop_time_ranges( $raw );
	// レンジが複数ある表記はどれが代表か決められないので正規化しない
	if ( count( $ranges ) !== 1 ) {
		return [ 'normalized' => null, 'raw' => $raw ];
	}
	return [ 'normalized' => $ranges[0][0] . '-' . $ranges[0][1], 'raw' => $raw ];
}

/**
 * 時刻表記を HH:MM へ。"11時30分" / "9:00" 両対応。
 */
function sc_shop_time( string $t ): ?string {
	if ( preg_match( '/(\d{1,2})\s*[:時]\s*(\d{1,2})/u', $t, $m ) ) {
		return sprintf( '%02d:%02d', (int) $m[1], (int) $m[2] );
	}
	return null;
}

/**
 * 1行分のテキストから時間帯を全部拾う。"翌0:00" の翌は落とす。
 *
 * @return array<array{0:string,1:string}> [opens, closes] のリスト
 */
function sc_shop_time_ranges( string $s ): array {
	$out = [];
	$pattern = '/(\d{1,2}\s*[:時]\s*\d{1,2}\s*分?)\s*[-〜～~–]\s*(?:翌)?\s*(\d{1,2}\s*[:時]\s*\d{1,2}\s*分?)/u';
	if ( preg_match_all( $pattern, $s, $ms, PREG_SET_ORDER ) ) {
		foreach ( $ms as $m ) {
			$opens  = sc_shop_time( $m[1] );
			$closes = sc_shop_time( $m[2] );
			if ( $opens && $closes ) {
				$out[] = [ $opens, $closes ];
			}
		}
	}
	return $out;
}

/**
 * shop_hours から OpeningHoursSpecification 配列を組み立てる。
 * Google 由来の曜日別表記「月曜日: 11時30分～14時00分, 17時00分～22時00分」を優先解析し、
 * 同一時間帯の曜日をまとめる。「火曜日: 定休日」の行は時間帯が取れないので自然に除外される。
 * 曜日が読めず時間帯が複数ある表記は、どの曜日に対応するか確定できないため構造化しない。
 *
 * @return array{specs:array, raw:string}
 */
function sc_shop_hours_specs( string $hours, string $closed = '' ): array {
	$raw = trim( $hours );
	if ( $raw === '' ) {
		return [ 'specs' => [], 'raw' => '' ];
	}

	$map = [
		'月' => 'Monday',
		'火' => 'Tuesday',
		'水' => 'Wednesday',
		'木' => 'Thursday',
		'金' => 'Friday',
		'土' => 'Saturday',
		'日' => 'Sunday',
	];

	// 曜日別表記（2日分以上あれば曜日別とみなす）
	$found = preg_match_all( '/([月火水木金土日])曜日\s*[:：]\s*([^\n]*)/u', $raw, $ms, PREG_SET_ORDER );
	if ( $found && count( $ms ) >= 2 ) {
		$groups = [];
		foreach ( $ms as $m ) {
			foreach ( sc_shop_time_ranges( $m[2] ) as $r ) {
				$key                      = $r[0] . '-' . $r[1];
				$groups[ $key ]['range']  = $r;
				$groups[ $key ]['days'][] = $map[ $m[1] ];
			}
		}
		$specs = [];
		foreach ( $groups as $g ) {
			$specs[] = [
				'@type'     => 'OpeningHoursSpecification',
				'dayOfWeek' => array_values( array_unique( $g['days'] ) ),
				'opens'     => $g['range'][0],
				'closes'    => $g['range'][1],
			];
		}
		return [ 'specs' => $specs, 'raw' => $raw ];
	}

	// フリーテキスト。時間帯が1つのときだけ採用
	$ranges = sc_shop_time_ranges( $raw );
	if ( count( $ranges ) !== 1 ) {
		return [ 'specs' => [], 'raw' => $raw ];
	}
	$spec = [
		'@type'  => 'OpeningHoursSpecification',
		'opens'  => $ranges[0][0],
		'closes' => $ranges[0][1],
	];
	// 定休日未入力の店は「年中無休」と主張しない
	$closed_days = sc_shop_closed_days( $closed );
	if ( $closed_days ) {
		$spec['dayOfWeek'] = array_values( array_diff( array_values( $map ), $closed_days ) );
	}
	return [ 'specs' => [ $spec ], 'raw' => $raw ];
}

/**
 * 住所フリーテキストを PostalAddress に分解。
 * 〒・都道府県・市区町村を抽出し streetAddress との二重表現を防ぐ。
 * 郵便番号が読めない住所に 420-0035 を固定付与しない（七間町外の店舗があるため）。
 *
 * @return array PostalAddress 連想配列
 */
function sc_parse_postal_address( string $raw ): array {
	$s = trim( $raw );
	if ( $s === '' ) {
		return SC_ADDRESS;
	}

	$out = [
		'@type'          => 'PostalAddress',
		'addressCountry' => 'JP',
	];

	// 郵便番号
	if ( preg_match( '/〒?\s*(\d{3})[-ー‐]?(\d{4})/u', $s, $m ) ) {
		$out['postalCode'] = $m[1] . '-' . $m[2];
		$s = trim( str_replace( $m[0], '', $s ) );
	}

	// 都道府県
	if ( preg_match( '/^(.{1,3}?[都道府県])/u', $s, $m ) ) {
		$out['addressRegion'] = $m[1];
		$s = trim( mb_substr( $s, mb_strlen( $m[1] ) ) );
	} else {
		$out['addressRegion'] = SC_ADDRESS['addressRegion'];
	}

	// 市区町村（政令市の「市＋区」を優先マッチ）
	if ( preg_match( '/^(.+?市.+?区|.+?[市区町村])/u', $s, $m ) ) {
		$out['addressLocality'] = $m[1];
		$s = trim( mb_substr( $s, mb_strlen( $m[1] ) ) );
	}

	if ( $s !== '' ) {
		$out['streetAddress'] = $s;
	}

	// 〒未記載でも七間町内なら郵便番号を確定できる
	if ( ! isset( $out['postalCode'] )
		&& ( $out['addressLocality'] ?? '' ) === SC_ADDRESS['addressLocality']
		&& mb_strpos( $s, '七間町' ) === 0 ) {
		$out['postalCode'] = SC_ADDRESS['postalCode'];
	}

	return $out;
}

function schema_shop( int $post_id ): void {
	$name        = get_the_title( $post_id );
	$address_str = get_field( 'shop_address', $post_id ) ?? '';
	$phone       = get_field( 'shop_phone', $post_id ) ?? '';
	$hours       = get_field( 'shop_hours', $post_id ) ?? '';
	$closed      = get_field( 'shop_closed', $post_id ) ?? '';
	$website     = get_field( 'shop_website', $post_id ) ?? '';
	$instagram   = get_field( 'shop_instagram', $post_id ) ?? '';
	$lat         = get_field( 'shop_map_lat', $post_id ) ?? '';
	$lng         = get_field( 'shop_map_lng', $post_id ) ?? '';
	$catch       = get_field( 'shop_catchphrase', $post_id ) ?? '';
	$desc_long   = get_field( 'shop_description', $post_id ) ?? '';
	$price_range = get_field( 'shop_price_range', $post_id ) ?? '';
	$features    = get_field( 'shop_features', $post_id );
	$main_image  = get_field( 'shop_main_image', $post_id );
	$gallery     = get_field( 'shop_gallery', $post_id );

	// description: shop_description（長文）優先 → catchphrase → excerpt
	$description = '';
	if ( is_string( $desc_long ) && trim( wp_strip_all_tags( $desc_long ) ) !== '' ) {
		$description = wp_strip_all_tags( $desc_long );
	} elseif ( $catch ) {
		$description = $catch;
	} else {
		$description = get_the_excerpt( $post_id );
	}

	// 画像配列化（最大3枚）: メイン画像 + ギャラリー、フォールバックでアイキャッチ
	$images = [];
	if ( is_array( $main_image ) && ! empty( $main_image['url'] ) ) {
		$images[] = $main_image['url'];
	}
	if ( is_array( $gallery ) ) {
		foreach ( $gallery as $g ) {
			if ( is_array( $g ) && ! empty( $g['url'] ) ) {
				$images[] = $g['url'];
			}
		}
	}
	if ( empty( $images ) ) {
		$thumb = get_the_post_thumbnail_url( $post_id, 'large' );
		if ( $thumb ) {
			$images[] = $thumb;
		}
	}
	$images = array_values( array_unique( array_slice( $images, 0, 3 ) ) );

	// address: フリーテキストを分解して二重表現・誤った郵便番号の固定付与を防ぐ
	$address = sc_parse_postal_address( (string) $address_str );

	$data = [
		'@context'           => 'https://schema.org',
		'@type'              => sc_shop_schema_type( $post_id ),
		// WebPage の mainEntity から参照するアンカー（seo.php の AIOSEO 拡張と対）
		'@id'                => get_permalink( $post_id ) . '#shop',
		'name'               => $name,
		'description'        => $description,
		'url'                => get_permalink( $post_id ),
		'address'            => $address,
		'parentOrganization' => [
			'@type' => 'Organization',
			'name'  => SC_ORG_NAME,
		],
	];

	// 電話は空値を出力しない
	if ( $phone ) {
		$data['telephone'] = $phone;
	}

	if ( ! empty( $images ) ) {
		// 単一画像なら文字列、複数なら配列で出力
		$data['image'] = count( $images ) === 1 ? $images[0] : $images;
	}

	// geo + hasMap
	if ( $lat && $lng ) {
		$data['geo'] = [
			'@type'     => 'GeoCoordinates',
			'latitude'  => (float) $lat,
			'longitude' => (float) $lng,
		];
		$data['hasMap'] = sprintf( 'https://www.google.com/maps?q=%s,%s', $lat, $lng );
	}

	// openingHours / openingHoursSpecification
	$h = sc_shop_hours_specs( (string) $hours, (string) $closed );
	if ( $h['specs'] ) {
		$data['openingHoursSpecification'] = count( $h['specs'] ) === 1 ? $h['specs'][0] : $h['specs'];
	} elseif ( $h['raw'] !== '' ) {
		// 曜日対応が確定できない表記は構造化せず生テキストのまま
		$data['openingHours'] = $h['raw'];
	}

	// priceRange
	if ( $price_range ) {
		$data['priceRange'] = $price_range;
	}

	// paymentAccepted
	$data['paymentAccepted'] = sc_shop_payment_accepted( $features );

	// acceptsReservations
	if ( is_array( $features ) && in_array( 'reserve', $features, true ) ) {
		$data['acceptsReservations'] = true;
	}

	// sameAs
	$same_as = [];
	if ( $website )   { $same_as[] = $website; }
	if ( $instagram ) { $same_as[] = $instagram; }
	if ( $same_as ) {
		$data['sameAs'] = count( $same_as ) === 1 ? $same_as[0] : $same_as;
	}

	sc_output_schema( $data );
}

// ─────────────────────────────────────────────────────────
// イベント個別（single-event.php から呼び出す）
// ─────────────────────────────────────────────────────────

function schema_event( int $post_id ): void {
	$name       = get_the_title( $post_id );
	$start      = get_field( 'event_date_start', $post_id ) ?? '';
	$end        = get_field( 'event_date_end', $post_id ) ?? '';
	$venue      = get_field( 'event_venue', $post_id ) ?? '七間町商店街';
	$address    = get_field( 'event_address', $post_id ) ?? '静岡市葵区七間町';
	$fee        = get_field( 'event_fee', $post_id ) ?? '';
	$organizer  = get_field( 'event_organizer', $post_id ) ?? SC_ORG_NAME;
	$ext_url    = get_field( 'event_external_url', $post_id ) ?? '';
	$thumb      = get_the_post_thumbnail_url( $post_id, 'large' ) ?: '';

	$data = [
		'@context'   => 'https://schema.org',
		'@type'      => 'Event',
		'name'       => $name,
		'url'        => get_permalink( $post_id ),
		'image'      => $thumb,
		'startDate'  => $start,
		'endDate'    => $end ?: $start,
		'eventStatus' => 'https://schema.org/EventScheduled',
		'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
		'location'   => [
			'@type'   => 'Place',
			'name'    => $venue,
			'address' => $address,
		],
		'organizer'  => [
			'@type' => 'Organization',
			'name'  => $organizer,
			'url'   => SC_SITE_URL,
		],
	];

	if ( $fee !== '' && $fee !== '無料' ) {
		$data['offers'] = [
			'@type'       => 'Offer',
			'price'       => $fee,
			'priceCurrency' => 'JPY',
			'url'         => $ext_url ?: get_permalink( $post_id ),
		];
	} elseif ( $fee === '無料' ) {
		$data['offers'] = [
			'@type'       => 'Offer',
			'price'       => '0',
			'priceCurrency' => 'JPY',
		];
	}

	sc_output_schema( $data );
}

// ─────────────────────────────────────────────────────────
// スポット個別（single-spot.php から呼び出す）
// ─────────────────────────────────────────────────────────

function schema_spot( int $post_id ): void {
	$name    = get_the_title( $post_id );
	$desc    = (string) ( get_field( 'spot_description', $post_id ) ?: '' );
	$address = (string) ( get_field( 'spot_address', $post_id ) ?: '' );
	$lat     = get_field( 'spot_map_lat', $post_id ) ?? '';
	$lng     = get_field( 'spot_map_lng', $post_id ) ?? '';
	$hours   = (string) ( get_field( 'spot_hours', $post_id ) ?: '' );
	$thumb   = get_the_post_thumbnail_url( $post_id, 'large' ) ?: '';

	// フィールド未入力時は抜粋にフォールバック
	if ( $desc === '' ) {
		$desc = wp_strip_all_tags( get_the_excerpt( $post_id ) );
	}

	$data = [
		'@context'    => 'https://schema.org',
		'@type'       => 'TouristAttraction',
		// WebPage の mainEntity から参照するアンカー（seo.php の AIOSEO 拡張と対）
		'@id'         => get_permalink( $post_id ) . '#spot',
		'name'        => $name,
		'url'         => get_permalink( $post_id ),
		'touristType' => '観光客・地域住民',
	];

	if ( $desc !== '' ) {
		$data['description'] = $desc;
	}
	if ( $thumb ) {
		$data['image'] = $thumb;
	}

	// 住所未入力時は出力しない（七間町外のスポットに誤住所を主張しないため）
	if ( $address !== '' ) {
		$data['address'] = sc_parse_postal_address( $address );
	}

	if ( $lat && $lng ) {
		$data['geo'] = [
			'@type'     => 'GeoCoordinates',
			'latitude'  => (float) $lat,
			'longitude' => (float) $lng,
		];
	}

	// "9:00〜17:00" 等を正規化。曜日情報は無いため dayOfWeek は主張しない
	$h = sc_shop_normalize_hours( $hours );
	if ( $h['normalized'] !== null ) {
		[ $open_t, $close_t ] = explode( '-', $h['normalized'] );
		$data['openingHoursSpecification'] = [
			'@type' => 'OpeningHoursSpecification',
			'opens'  => $open_t,
			'closes' => $close_t,
		];
	}

	sc_output_schema( $data );
}

// ─────────────────────────────────────────────────────────
// コラム・お隣さんの話（single-column.php, single-resident.php）
// ─────────────────────────────────────────────────────────

function schema_article( int $post_id ): void {
	$type       = get_post_type( $post_id ) === CPT_COLUMN ? 'BlogPosting' : 'Article';
	$title      = get_the_title( $post_id );
	$thumb      = get_the_post_thumbnail_url( $post_id, 'large' ) ?: '';
	$published  = get_the_date( 'c', $post_id );
	$modified   = get_the_modified_date( 'c', $post_id );
	$author_name = get_field( 'column_author_name', $post_id )
		?: get_field( 'resident_name', $post_id )
		?: SC_ORG_NAME;
	$excerpt    = get_the_excerpt( $post_id );

	sc_output_schema( [
		'@context'         => 'https://schema.org',
		'@type'            => $type,
		'headline'         => $title,
		'description'      => $excerpt,
		'image'            => $thumb,
		'url'              => $job_url,
		'datePublished'    => $published,
		'dateModified'     => $modified,
		'author'           => [
			'@type' => 'Person',
			'name'  => $author_name,
		],
		'publisher'        => [
			'@type' => 'Organization',
			'name'  => SC_ORG_NAME,
			'url'   => SC_SITE_URL,
		],
		'inLanguage'       => 'ja',
	] );
}

// ─────────────────────────────────────────────────────────
// 求人（schema_job）
// ─────────────────────────────────────────────────────────

function schema_job( int $post_id ): void {
	$title    = get_the_title( $post_id );
	$company  = get_field( 'job_company', $post_id ) ?? SC_ORG_NAME;
	$position = get_field( 'job_position', $post_id ) ?? $title;
	$salary   = get_field( 'job_salary', $post_id ) ?? '';
	$location = get_field( 'job_location', $post_id ) ?? '静岡市葵区七間町';
	$deadline = get_field( 'job_deadline', $post_id ) ?? '';
	// 本文は使わず ACF の仕事内容から。求人の個別テンプレートは無く、働くページのモーダルで見せる
	$desc     = wp_strip_all_tags( (string) get_field( 'job_description', $post_id ) );
	$type_val = (array) ( get_field( 'job_type', $post_id ) ?: [] );
	$job_url  = home_url( '/work/#job-modal-' . $post_id );

	$data = [
		'@context'         => 'https://schema.org',
		'@type'            => 'JobPosting',
		'title'            => $position,
		'description'      => $desc,
		'url'              => get_permalink( $post_id ),
		'datePosted'       => get_the_date( 'c', $post_id ),
		'hiringOrganization' => [
			'@type' => 'Organization',
			'name'  => $company,
		],
		'jobLocation'      => [
			'@type'   => 'Place',
			'address' => [
				'@type'           => 'PostalAddress',
				'streetAddress'   => $location,
				'addressLocality' => '静岡市葵区',
				'addressRegion'   => '静岡県',
				'addressCountry'  => 'JP',
			],
		],
		'employmentType'   => $type_val,
	];

	if ( $deadline ) {
		$data['validThrough'] = $deadline;
	}

	if ( $salary ) {
		$data['baseSalary'] = [
			'@type'    => 'MonetaryAmount',
			'currency' => 'JPY',
			'value'    => $salary,
		];
	}

	sc_output_schema( $data );
}

// ─────────────────────────────────────────────────────────
// 空き物件（schema_property）
// ─────────────────────────────────────────────────────────

function schema_property( int $post_id ): void {
	$name    = get_the_title( $post_id );
	$address = get_field( 'prop_address', $post_id ) ?? '静岡市葵区七間町';
	$rent    = get_field( 'prop_rent', $post_id ) ?? '';
	$desc    = get_field( 'prop_description', $post_id ) ?? '';
	$thumb   = get_the_post_thumbnail_url( $post_id, 'large' ) ?: '';

	sc_output_schema( [
		'@context'    => 'https://schema.org',
		'@type'       => 'RealEstateListing',
		'name'        => $name,
		'description' => $desc,
		'url'         => get_permalink( $post_id ),
		'image'       => $thumb,
		'address'     => $address,
		'offers'      => $rent ? [
			'@type'       => 'Offer',
			'price'       => $rent,
			'priceCurrency' => 'JPY',
		] : null,
	] );
}

// ─────────────────────────────────────────────────────────
// 学ぶ施設（schema_learn_facility）
// ─────────────────────────────────────────────────────────

function schema_learn_facility( int $post_id ): void {
	$name    = get_the_title( $post_id );
	$address = get_field( 'facility_address', $post_id ) ?? '静岡市葵区七間町';
	$phone   = get_field( 'facility_phone', $post_id ) ?? '';
	$url     = get_field( 'facility_website', $post_id ) ?? '';
	$target  = get_field( 'facility_target', $post_id ) ?? '';
	$desc    = get_field( 'facility_description', $post_id ) ?? '';

	sc_output_schema( [
		'@context'         => 'https://schema.org',
		'@type'            => 'EducationalOrganization',
		'name'             => $name,
		'description'      => $desc,
		'url'              => $url ?: get_permalink( $post_id ),
		'address'          => $address,
		'telephone'        => $phone,
		'audience'         => $target,
	] );
}

// ─────────────────────────────────────────────────────────
// 医療機関（schema_medical）
// ─────────────────────────────────────────────────────────

function schema_medical( array $data ): void {
	$type_map = [
		'内科' => 'Physician', '外科' => 'Physician',
		'歯科' => 'Dentist',   '薬局' => 'Pharmacy',
		'病院' => 'Hospital',
	];
	$type = $type_map[ $data['medical_type'] ?? '' ] ?? 'MedicalOrganization';

	sc_output_schema( [
		'@context'  => 'https://schema.org',
		'@type'     => $type,
		'name'      => $data['medical_name'] ?? '',
		'address'   => $data['medical_address'] ?? '',
		'telephone' => $data['medical_phone'] ?? '',
		'openingHours' => $data['medical_hours'] ?? '',
	] );
}

// ─────────────────────────────────────────────────────────
// 緊急連絡先・避難場所・公共施設
// ─────────────────────────────────────────────────────────

function schema_emergency( array $data ): void {
	sc_output_schema( [
		'@context'  => 'https://schema.org',
		'@type'     => 'EmergencyService',
		'name'      => $data['contact_name'] ?? '',
		'telephone' => $data['contact_phone'] ?? '',
	] );
}

function schema_shelter( array $data ): void {
	sc_output_schema( [
		'@context'    => 'https://schema.org',
		'@type'       => 'CivicStructure',
		'name'        => $data['shelter_name'] ?? '',
		'address'     => $data['shelter_address'] ?? '',
		'description' => ( $data['shelter_type'] ?? '' ) . ' 収容人数: ' . ( $data['shelter_capacity'] ?? '' ),
	] );
}

function schema_civic( array $data ): void {
	sc_output_schema( [
		'@context'  => 'https://schema.org',
		'@type'     => 'CivicStructure',
		'name'      => $data['facility_name'] ?? '',
		'address'   => $data['facility_address'] ?? '',
		'telephone' => $data['facility_phone'] ?? '',
		'openingHours' => $data['facility_hours'] ?? '',
		'url'       => $data['facility_url'] ?? '',
	] );
}

// ─────────────────────────────────────────────────────────
// FAQ（schema_faq）
// ─────────────────────────────────────────────────────────

function schema_faq( string $field_name, $post_id = null ): void {
	$rows = get_field( $field_name, $post_id );
	if ( ! $rows ) return;

	$entities = [];
	foreach ( $rows as $row ) {
		$q = $row['question'] ?? '';
		$a = $row['answer'] ?? '';
		if ( ! $q || ! $a ) continue;

		$entities[] = [
			'@type'          => 'Question',
			'name'           => $q,
			'acceptedAnswer' => [
				'@type' => 'Answer',
				'text'  => $a,
			],
		];
	}

	if ( ! $entities ) return;

	sc_output_schema( [
		'@context'   => 'https://schema.org',
		'@type'      => 'FAQPage',
		'mainEntity' => $entities,
	] );
}

// ─────────────────────────────────────────────────────────
// HowTo（ごみの出し方等）
// ─────────────────────────────────────────────────────────

function schema_howto( string $field_name, $post_id = null ): void {
	$rows = get_field( $field_name, $post_id );
	if ( ! $rows ) return;

	$steps = [];
	foreach ( $rows as $i => $row ) {
		$steps[] = [
			'@type' => 'HowToStep',
			'position' => $i + 1,
			'name'  => $row['garbage_type'] ?? '',
			'text'  => ( $row['garbage_day'] ?? '' ) . ' ' . ( $row['garbage_note'] ?? '' ),
		];
	}

	sc_output_schema( [
		'@context' => 'https://schema.org',
		'@type'    => 'HowTo',
		'name'     => 'ごみの分別・収集日ガイド',
		'step'     => $steps,
	] );
}

// ─────────────────────────────────────────────────────────
// ItemList（アーカイブ一覧）
// ─────────────────────────────────────────────────────────

function schema_item_list( array $posts ): void {
	if ( ! $posts ) return;

	$items = [];
	foreach ( $posts as $i => $post ) {
		$id  = is_object( $post ) ? $post->ID : (int) $post;
		$items[] = [
			'@type'    => 'ListItem',
			'position' => $i + 1,
			'url'      => get_permalink( $id ),
			'name'     => get_the_title( $id ),
		];
	}

	sc_output_schema( [
		'@context'        => 'https://schema.org',
		'@type'           => 'ItemList',
		'itemListElement' => $items,
	] );
}

// ─────────────────────────────────────────────────────────
// ギャラリー（schema_image_gallery）
// ─────────────────────────────────────────────────────────

function schema_image_gallery( $post_id = null ): void {
	$post_id = $post_id ?? get_the_ID();
	$thumb   = get_the_post_thumbnail_url( $post_id, 'large' ) ?: '';

	sc_output_schema( [
		'@context'    => 'https://schema.org',
		'@type'       => 'ImageGallery',
		'name'        => get_the_title( $post_id ),
		'url'         => get_permalink( $post_id ),
		'thumbnailUrl' => $thumb,
	] );
}

// ─────────────────────────────────────────────────────────
// 観光トリップ（schema_tourist_trip）
// ─────────────────────────────────────────────────────────

function schema_tourist_trip( array $data ): void {
	sc_output_schema( [
		'@context'    => 'https://schema.org',
		'@type'       => 'TouristTrip',
		'name'        => $data['title'] ?? '',
		'description' => $data['description'] ?? '',
		'itinerary'   => [
			'@type' => 'ItemList',
			'itemListElement' => $data['spots'] ?? [],
		],
		'touristType'  => '観光客・旅行者',
	] );
}

// ─────────────────────────────────────────────────────────
// 観光地（page-tourism.php）
// ─────────────────────────────────────────────────────────

function schema_tourist_destination(): void {
	sc_output_schema( [
		'@context'    => 'https://schema.org',
		'@type'       => 'TouristDestination',
		'name'        => '七間町 ─ 静岡市葵区',
		'description' => '静岡市葵区に位置する歴史ある商店街エリア。映画の町・食・文化が融合する観光スポット。',
		'url'         => SC_SITE_URL . 'visit/',
		'address'     => SC_ADDRESS,
		'geo'         => SC_GEO,
		'includesAttraction' => [
			'@type' => 'TouristAttraction',
			'name'  => '七間町商店街',
		],
	] );
}

// ─────────────────────────────────────────────────────────
// お問い合わせページ
// ─────────────────────────────────────────────────────────

function schema_contact_page(): void {
	sc_output_schema( [
		'@context' => 'https://schema.org',
		'@type'    => 'ContactPage',
		'name'     => 'お問い合わせ | ' . SC_SITE_NAME,
		'url'      => SC_SITE_URL . 'contact/',
		'about'    => [
			'@type' => 'Organization',
			'name'  => SC_ORG_NAME,
		],
	] );
}

// ─────────────────────────────────────────────────────────
// コワーキングスペース（page-business.php の各カードから呼ぶ）
// ─────────────────────────────────────────────────────────

function schema_cowork_item( array $data ): void {
	$schema = [
		'@context'           => 'https://schema.org',
		'@type'              => 'LocalBusiness',
		'name'               => $data['name'] ?? '',
		'description'        => $data['description'] ?? '',
		'address'            => [
			'@type'           => 'PostalAddress',
			'streetAddress'   => $data['address'] ?? SC_ADDRESS['streetAddress'],
			'addressLocality' => SC_ADDRESS['addressLocality'],
			'addressRegion'   => SC_ADDRESS['addressRegion'],
			'postalCode'      => SC_ADDRESS['postalCode'],
			'addressCountry'  => SC_ADDRESS['addressCountry'],
		],
		'parentOrganization' => [
			'@type' => 'Organization',
			'name'  => SC_ORG_NAME,
		],
	];

	if ( ! empty( $data['phone'] ) )  { $schema['telephone']    = $data['phone']; }
	if ( ! empty( $data['hours'] ) )  { $schema['openingHours'] = $data['hours']; }
	if ( ! empty( $data['url'] ) )    { $schema['url']          = $data['url']; }
	if ( ! empty( $data['image'] ) )  { $schema['image']        = $data['image']; }

	sc_output_schema( $schema );
}

// ─────────────────────────────────────────────────────────
// スポンサーオファー
// ─────────────────────────────────────────────────────────

function schema_offer( $plans ): void {
	if ( ! $plans ) return;

	$offers = [];
	foreach ( (array) $plans as $plan ) {
		$offers[] = [
			'@type'       => 'Offer',
			'name'        => $plan['plan_name'] ?? '',
			'price'       => $plan['plan_price'] ?? '',
			'priceCurrency' => 'JPY',
			'description' => $plan['plan_features'] ?? '',
		];
	}

	sc_output_schema( [
		'@context' => 'https://schema.org',
		'@type'    => 'Organization',
		'name'     => SC_ORG_NAME,
		'offers'   => $offers,
	] );
}
