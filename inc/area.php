<?php
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
		[ 'name' => '七間町・駒形通り・人宿町エリア', 'slug' => 'shichikancho', 'color' => '#F8B4C4', 'col' => '1 / span 5',  'row' => '1 / span 4', 'card_title' => '七間町・駒形通り・人宿町', 'desc' => '江戸時代から続く商店街の中心地。映画館文化が栄えた歴史ある町並みが残ります。', 'tags' => [ '七間町', '駒形通り', '人宿町' ], 'area_terms' => [ '七間町通り', '人宿町' ] ],
		[ 'name' => '常磐町・両替町エリア',           'slug' => 'tokiwa',       'color' => '#F9E076', 'col' => '7 / span 6',  'row' => '1 / span 4', 'card_title' => '常磐町・両替町',           'desc' => '金融・商業の中心として発展した地域。近代的な街並みと歴史が共存しています。',   'tags' => [ '常磐町', '両替町' ], 'area_terms' => [] ],
		[ 'name' => '呉服町・紺屋町エリア',           'slug' => 'gofuku',       'color' => '#F5A962', 'col' => '1 / span 5',  'row' => '5 / span 4', 'card_title' => '呉服町・紺屋町',           'desc' => '染物・呉服の問屋街として栄えた地域。職人の技と伝統が息づく町です。',           'tags' => [ '呉服町', '紺屋町' ], 'area_terms' => [ '呉服町' ] ],
		[ 'name' => '鷹匠・伝馬町エリア',             'slug' => 'takajo',       'color' => '#7EC8E3', 'col' => '7 / span 6',  'row' => '5 / span 4', 'card_title' => '鷹匠・伝馬町',             'desc' => 'おしゃれなカフェやブティックが集まるエリア。新旧の文化が融合しています。',     'tags' => [ '鷹匠', '伝馬町' ], 'area_terms' => [ '鷹匠' ] ],
		[ 'name' => '馬場町・宮ヶ崎町・大手町エリア', 'slug' => 'baba',         'color' => '#90C695', 'col' => '1 / span 12', 'row' => '9 / span 4', 'card_title' => '馬場町・宮ヶ崎町・大手町', 'desc' => '駿府城に近い歴史的なエリア。神社仏閣や公園が点在する静かな町です。',           'tags' => [ '馬場町', '宮ヶ崎町', '大手町' ], 'area_terms' => [ '駿府城周辺' ] ],
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
