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
	return esc_url( get_template_directory_uri() . '/assets/images/common/no-image.jpg' );
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
