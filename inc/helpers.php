<?php
/** テーマ共通ヘルパー・データ提供（sc_field / pickup / エリア） */

// ACFフィールドをエスケープ付きで取得（空時はフォールバック）
function sc_field( string $key, $post_id = null, string $fallback = '' ): string {
	$val = get_field( $key, $post_id );
	return $val ? esc_html( $val ) : esc_html( $fallback );
}

// ACFフィールドをURL用エスケープで取得
function sc_field_url( string $key, $post_id = null ): string {
	$val = get_field( $key, $post_id );
	return $val ? esc_url( $val ) : '';
}

// ACFテキストエリア取得（<br>のみ許可、それ以外のHTMLは除去）
// 前提: ACFフィールド設定で「改行 → 自動的に <br> に変換」を有効にする
function sc_field_textarea( string $key, $post_id = null, string $fallback = '' ): string {
	$val = get_field( $key, $post_id );
	$val = $val !== '' && $val !== null ? $val : $fallback;
	return $val ? wp_kses( $val, [ 'br' => [] ] ) : '';
}

// 雇用形態（複数選択）の日本語ラベル配列。値は schema.org の employmentType
function sc_job_type_labels( int $post_id ): array {
	$values  = (array) get_field( 'job_type', $post_id );
	$choices = acf_get_field( 'job_type' )['choices'] ?? [];
	$labels  = [];
	foreach ( $values as $value ) {
		if ( isset( $choices[ $value ] ) ) $labels[] = $choices[ $value ];
	}
	return $labels;
}

// アイキャッチ画像URLを取得（フォールバック付き）
function sc_thumbnail_url( int $post_id, string $size = 'medium', string $fallback = '' ): string {
	$url = get_the_post_thumbnail_url( $post_id, $size );
	if ( $url ) return esc_url( $url );
	return $fallback ? esc_url( $fallback ) : sc_no_image_url();
}

// テキスト量から読了時間（分）を算出
function sc_reading_time( string $content ): int {
	$word_count = mb_strlen( wp_strip_all_tags( $content ) );
	$minutes    = (int) ceil( $word_count / 400 );
	return max( 1, $minutes );
}

// 電話番号の表示整形（tel: リンク用は数字のみ）
function sc_tel_href( string $phone ): string {
	return 'tel:' . preg_replace( '/[^\d+]/', '', $phone );
}

// 日付を日本語形式で整形
function sc_date_jp( string $date_str ): string {
	$ts = strtotime( $date_str );
	if ( ! $ts ) return esc_html( $date_str );
	return date_i18n( 'Y年n月j日', $ts );
}

// ノーイメージプレースホルダー URL
function sc_no_image_url(): string {
	return esc_url( SC_TPL_URI . '/assets/images/common/no-image.jpg' );
}

// タクソノミースラッグから term_id を取得（$wpdb 直クエリ）
// WP の get_term_by('slug') は内部で sanitize_title() を呼ぶため日本語スラッグが消える問題を回避
function sc_get_term_id_by_slug( string $slug, string $taxonomy ): int {
	global $wpdb;
	return (int) $wpdb->get_var( $wpdb->prepare(
		"SELECT t.term_id FROM {$wpdb->terms} t
		 INNER JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id
		 WHERE t.slug = %s AND tt.taxonomy = %s LIMIT 1",
		$slug, $taxonomy
	) );
}

// ═══════════════════════════════════════════════════════
// PICK UP ヘルパー
// ═══════════════════════════════════════════════════════
/**
 * PICK UP ヘルパー
 *
 * 各 CPT 用 ACF オプションページ（pickup-{cpt}）の relationship フィールド
 * `pickup_{cpt}` から ID 配列を取得する。
 * オプションページ・フィールド本体は ACF 管理画面 UI で定義し、
 * インポート JSON 経由で ACF に登録する（PHP では登録しない）。
 *
 * 関連: _acf-import_pickup-options.json / _acf-import_pickup-fields.json
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! function_exists( 'sc_get_pickup_ids' ) ) :
	/**
	 * 指定 CPT の PICK UP 投稿 ID 配列を返す。
	 * 表示順はランダム（shuffle）。上限は既定でなし（$limit=0）。
	 *
	 * @param string $key   pickup_xxx の xxx 部分（例: 'shop'）
	 * @param int    $limit 上限件数（0 以下は上限なし）
	 * @return int[]
	 */
	function sc_get_pickup_ids( $key, $limit = 0 ) {
		if ( ! function_exists( 'get_field' ) ) return [];
		$ids = get_field( 'pickup_' . $key, 'option' );
		if ( ! is_array( $ids ) || ! $ids ) return [];
		$ids = array_values( array_filter( array_map( 'intval', $ids ), function ( $id ) {
			return $id > 0 && get_post_status( $id ) === 'publish';
		} ) );
		shuffle( $ids ); // 表示順はランダム
		if ( (int) $limit > 0 ) {
			$ids = array_slice( $ids, 0, (int) $limit );
		}
		return $ids;
	}
endif;

// ═══════════════════════════════════════════════════════
// エリアガイド データ
// ═══════════════════════════════════════════════════════
/**
 * エリアガイド下層ページ — データ提供
 * ルーティングは WP 標準（/area/ 配下の固定ページ + Template Name「エリアページ（下層）」）
 * 旧カスタム rewrite は撤去。ページスラッグでデータを引く
 */

// TAX_AREA が rewrite ベース /area/ を占有しているため、
// 既知のエリアスラッグだけ固定ページへ優先ルーティング（タクソノミーより先に解決）
add_action( 'init', function () {
	$slugs = array_map( function ( $a ) { return $a['slug']; }, sc_get_areas() );
	$pattern = '^area/(' . implode( '|', array_map( 'preg_quote', $slugs ) ) . ')/?$';
	add_rewrite_rule( $pattern, 'index.php?pagename=area/$matches[1]', 'top' );

	// ルール反映のため一度だけ flush（バージョンで管理）
	if ( get_option( 'sc_area_rewrite_v' ) !== '2' ) {
		flush_rewrite_rules( false );
		update_option( 'sc_area_rewrite_v', '2' );
	}
} );

/**
 * エリア基本データ（tourism マップと共通）
 * col/row は tourism ページの 12x12 グリッド座標
 * @return array<int, array<string, mixed>>
 */
function sc_get_areas(): array {
	// area_terms は TAX_AREA（サブ地名）タームの名称。spot/event はこれで大エリアに束ねる
	return [
		[ 'name' => '七間町・駒形通り・人宿町・駿河町エリア', 'slug' => 'shichikancho', 'color' => '#8BA7C5', 'card_title' => '七間町・駒形通り・人宿町・駿河町', 'desc' => '江戸時代から続く商店街の中心地。映画館文化が栄えた歴史ある町並みが残ります。', 'tags' => [ '七間町', '駒形通り', '人宿町', '駿河町' ], 'area_terms' => [ '七間町', '駒形通り', '人宿町', '駿河町' ] ],
		[ 'name' => '常磐町・両替町・昭和町エリア', 'slug' => 'tokiwa',       'color' => '#CBA7A1', 'card_title' => '常磐町・両替町・昭和町', 'desc' => '金融・商業の中心として発展した地域。近代的な街並みと歴史が共存しています。',   'tags' => [ '常磐町', '両替町', '昭和町' ], 'area_terms' => [ '常磐町', '両替町', '昭和町' ] ],
		[ 'name' => '呉服町・紺屋町・御幸町エリア',   'slug' => 'gofuku',       'color' => '#A4BBAE', 'card_title' => '呉服町・紺屋町・御幸町',   'desc' => '染物・呉服の問屋街として栄えた地域。職人の技と伝統が息づく町です。',           'tags' => [ '呉服町', '紺屋町', '御幸町' ], 'area_terms' => [ '呉服町', '紺屋町', '御幸町' ] ],
		[ 'name' => '駿府城公園・駿府町・鷹匠・伝馬町エリア', 'slug' => 'takajo',       'color' => '#D3C4A7', 'card_title' => '駿府城公園・駿府町・鷹匠・伝馬町', 'desc' => 'おしゃれなカフェやブティックが集まるエリア。新旧の文化が融合しています。',     'tags' => [ '駿府城公園', '駿府町', '鷹匠', '伝馬町' ], 'area_terms' => [ '駿府城公園', '駿府町', '鷹匠', '伝馬町' ] ],
		[ 'name' => '馬場町・宮ヶ崎町・大手町・車町・中町エリア', 'slug' => 'baba',         'color' => '#A8A7C4', 'card_title' => '馬場町・宮ヶ崎町・大手町・車町・中町', 'desc' => '駿府城に近い歴史的なエリア。神社仏閣や公園が点在する静かな町です。',           'tags' => [ '馬場町', '宮ヶ崎町', '大手町', '車町', '中町' ], 'area_terms' => [ '馬場町', '宮ヶ崎町', '大手町', '車町', '中町' ] ],
	];
}

/**
 * スラッグ一致のエリア基本データを返す（無ければ null）
 * @return array<string, mixed>|null
 */
function sc_get_area( string $slug ): ?array {
	foreach ( sc_get_areas() as $a ) {
		if ( $a['slug'] === $slug ) {
			return $a;
		}
	}
	return null;
}

/**
 * 町名ターム（TAX_AREA）→ 大エリアページ の対応表を作る
 *
 * 正は各エリアページの ACF「エリア連動ターム」(area_linked_terms)。
 * 未設定のページのみ sc_get_areas() の area_terms 名称でフォールバックする
 * （page-area.php の解決順と揃える）。
 *
 * @return array<int, array{slug:string, name:string, card_title:string, color:string, page_id:int}> term_id をキーにした対応表
 */
function sc_get_area_term_map(): array {
	static $map = null;
	if ( $map !== null ) return $map;

	$map = [];

	foreach ( sc_get_areas() as $area ) {
		$page = get_page_by_path( 'area/' . $area['slug'] ) ?: get_page_by_path( $area['slug'] );
		if ( ! $page ) continue;

		$term_ids = array_map( 'intval', (array) get_field( 'area_linked_terms', $page->ID ) );

		// ACF 未設定ならターム名で引き当て
		if ( ! $term_ids ) {
			foreach ( ( $area['area_terms'] ?? [] ) as $term_name ) {
				$t = get_term_by( 'name', $term_name, TAX_AREA );
				if ( $t && ! is_wp_error( $t ) ) $term_ids[] = (int) $t->term_id;
			}
		}

		foreach ( $term_ids as $tid ) {
			if ( ! $tid ) continue;
			// 先に登録された大エリアを優先（1タームが複数エリアに属する想定はしない）
			if ( isset( $map[ $tid ] ) ) continue;
			$map[ $tid ] = [
				'slug'       => $area['slug'],
				'name'       => $area['name'],
				'card_title' => $area['card_title'],
				'color'      => $area['color'],
				'page_id'    => (int) $page->ID,
			];
		}
	}

	return $map;
}

/**
 * 投稿が属する大エリアを返す（spot / shop / event など TAX_AREA を持つ投稿共通）
 * 複数の町名タームが付く投稿もあるため、重複を除いた配列で返す
 *
 * @return array<int, array{slug:string, name:string, card_title:string, color:string, page_id:int, url:string}>
 */
function sc_get_post_areas( int $post_id ): array {
	$terms = get_the_terms( $post_id, TAX_AREA );
	if ( ! $terms || is_wp_error( $terms ) ) return [];

	$map   = sc_get_area_term_map();
	$found = [];

	foreach ( $terms as $t ) {
		if ( ! isset( $map[ $t->term_id ] ) ) continue;
		$a = $map[ $t->term_id ];
		if ( isset( $found[ $a['slug'] ] ) ) continue;
		$a['url'] = (string) get_permalink( $a['page_id'] );
		$found[ $a['slug'] ] = $a;
	}

	return array_values( $found );
}

/**
 * このスポットを含む散策コースを返す（walk_spots リピーターの逆引き）
 *
 * ACF リピーターは walk_spots_0_ref / walk_spots_1_ref … の postmeta として保存されるため、
 * meta_query ではキーのワイルドカード指定ができない。$wpdb で直接引く。
 *
 * @return array<int, WP_Post> 掲載順（menu_order → 日付）
 */
function sc_get_courses_by_spot( int $spot_id ): array {
	global $wpdb;
	if ( ! $spot_id ) return [];

	$ids = $wpdb->get_col( $wpdb->prepare(
		"SELECT DISTINCT pm.post_id
		 FROM {$wpdb->postmeta} pm
		 INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
		 WHERE pm.meta_key LIKE %s
		   AND pm.meta_value = %s
		   AND p.post_type = %s
		   AND p.post_status = 'publish'",
		$wpdb->esc_like( 'walk_spots_' ) . '%' . $wpdb->esc_like( '_ref' ),
		(string) $spot_id,
		CPT_WALK
	) );

	if ( ! $ids ) return [];

	return get_posts( [
		'post_type'      => CPT_WALK,
		'post__in'       => array_map( 'intval', $ids ),
		'posts_per_page' => -1,
		'orderby'        => [ 'menu_order' => 'ASC', 'date' => 'DESC' ],
		'post_status'    => 'publish',
	] );
}

/**
 * 散策コースの移動手段を区間データから導出する
 *
 * walk_spots の time_to_next は「徒歩 15分」「自転車 5分」形式。
 * 所要時間だけを出すと全て徒歩と読まれるため、手段を明示するために使う。
 * 複数手段が混在するコースは「徒歩・バス」のように連結。
 *
 * @return string 手段ラベル（判定不能なら空文字）
 */
function sc_walk_transport( int $post_id ): string {
	$spots = get_field( 'walk_spots', $post_id );
	if ( ! is_array( $spots ) ) return '';

	$known = [ '徒歩', '自転車', 'バス', '電車', 'タクシー', 'ロープウェイ', '車' ];
	$found = [];

	foreach ( $spots as $spot ) {
		$seg = trim( (string) ( $spot['time_to_next'] ?? '' ) );
		if ( $seg === '' ) continue;
		foreach ( $known as $mode ) {
			if ( mb_strpos( $seg, $mode ) === 0 ) {
				$found[ $mode ] = true;
				break;
			}
		}
	}

	if ( ! $found ) return '';

	// $known の順で並べて表記を安定させる
	$ordered = array_values( array_filter( $known, fn( $m ) => isset( $found[ $m ] ) ) );
	return implode( '・', $ordered );
}

/**
 * 「自転車 24分」形式の所要時間ラベルを返す
 * 手段が取れないコースは従来どおり「24分」のみ
 */
function sc_walk_duration_label( int $post_id, int $duration ): string {
	if ( ! $duration ) return '';
	$transport = sc_walk_transport( $post_id );
	return $transport ? $transport . ' ' . $duration . '分' : $duration . '分';
}

/**
 * エリア共通アクセス情報（七間町中心部基準・全エリア共通）
 * コンテンツ（intro/features/towns/history/gourmet/course）は ACF フィールドで管理
 * @return array<int, array<string, string>>
 */
function sc_get_area_access(): array {
	return [
		[ 'icon' => 'icon-train',    'label' => 'JR・新幹線', 'time' => '約15分',    'text' => '静岡駅北口から徒歩約15分' ],
		[ 'icon' => 'icon-train',    'label' => '静岡鉄道',   'time' => '約11分',    'text' => '新静岡駅から徒歩約11分' ],
		[ 'icon' => 'icon-bus',      'label' => 'バス',       'time' => '約10分',    'text' => '静岡駅前バスターミナルから「七間町」停留所下車すぐ' ],
		[ 'icon' => 'icon-bicycle',  'label' => '自転車',     'time' => '約5〜10分', 'text' => 'PULCLE（静岡市シェアサイクル）の利用が便利。市内各所にポートあり' ],
	];
}
