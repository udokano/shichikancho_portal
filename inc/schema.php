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
			'url'   => get_template_directory_uri() . '/assets/images/logo/logo.png',
		],
		'address'  => SC_ADDRESS,
		'geo'      => SC_GEO,
		'sameAs'   => [
			'https://www.instagram.com/shichikancho/',
			'https://www.facebook.com/shichikancho/',
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
		'openingHours'  => 'Mo-Su 10:00-21:00',
		'priceRange'    => '¥〜¥¥¥',
		'image'         => get_template_directory_uri() . '/assets/images/common/ogp.jpg',
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
	if ( preg_match( '/(\d{1,2}):(\d{2})\s*[-〜~–]\s*(\d{1,2}):(\d{2})/u', $raw, $m ) ) {
		$normalized = sprintf( '%02d:%02d-%02d:%02d', $m[1], $m[2], $m[3], $m[4] );
		return [ 'normalized' => $normalized, 'raw' => $raw ];
	}
	return [ 'normalized' => null, 'raw' => $raw ];
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

	// address: PostalAddress 維持、shop_address は streetAddress に明示代入
	$address = [
		'@type'           => 'PostalAddress',
		'streetAddress'   => $address_str ?: SC_ADDRESS['streetAddress'],
		'addressLocality' => SC_ADDRESS['addressLocality'],
		'addressRegion'   => SC_ADDRESS['addressRegion'],
		'postalCode'      => SC_ADDRESS['postalCode'],
		'addressCountry'  => SC_ADDRESS['addressCountry'],
	];

	$data = [
		'@context'           => 'https://schema.org',
		'@type'              => sc_shop_schema_type( $post_id ),
		'name'               => $name,
		'description'        => $description,
		'url'                => get_permalink( $post_id ),
		'address'            => $address,
		'telephone'          => $phone,
		'parentOrganization' => [
			'@type' => 'Organization',
			'name'  => SC_ORG_NAME,
		],
	];

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
	$h = sc_shop_normalize_hours( (string) $hours );
	if ( $h['normalized'] !== null ) {
		$closed_days = sc_shop_closed_days( (string) $closed );
		$all_days    = [ 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday' ];
		$open_days   = array_values( array_diff( $all_days, $closed_days ) );

		[ $open_t, $close_t ] = explode( '-', $h['normalized'] );
		$data['openingHoursSpecification'] = [
			'@type'     => 'OpeningHoursSpecification',
			'dayOfWeek' => $open_days,
			'opens'     => $open_t,
			'closes'    => $close_t,
		];
	} elseif ( $h['raw'] !== '' ) {
		// パース失敗時はフリーテキストを openingHours に流す（仕様外形式の許容）
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
	$desc    = get_field( 'spot_description', $post_id ) ?? '';
	$address = get_field( 'spot_address', $post_id ) ?? '静岡市葵区七間町';
	$lat     = get_field( 'spot_map_lat', $post_id ) ?? '';
	$lng     = get_field( 'spot_map_lng', $post_id ) ?? '';
	$hours   = get_field( 'spot_hours', $post_id ) ?? '';
	$thumb   = get_the_post_thumbnail_url( $post_id, 'large' ) ?: '';

	$data = [
		'@context'    => 'https://schema.org',
		'@type'       => 'TouristAttraction',
		'name'        => $name,
		'description' => $desc,
		'url'         => get_permalink( $post_id ),
		'image'       => $thumb,
		'address'     => $address,
		'touristType'  => '観光客・地域住民',
	];

	if ( $lat && $lng ) {
		$data['geo'] = [
			'@type'     => 'GeoCoordinates',
			'latitude'  => (float) $lat,
			'longitude' => (float) $lng,
		];
	}

	if ( $hours ) {
		$data['openingHours'] = $hours;
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
		'url'              => get_permalink( $post_id ),
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
	$desc     = get_the_excerpt( $post_id );
	$type_val = get_field( 'job_type', $post_id ) ?? '';

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
			'address' => $location,
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
