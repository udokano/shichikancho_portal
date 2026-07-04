<?php
/** エリアガイド下層ページ — /area/{slug} のルーティングとデータ提供 */

// ─── リライトルール: /area/{slug} ──
add_action( 'init', function () {
	add_rewrite_rule( '^area/([^/]+)/?$', 'index.php?sc_area=$matches[1]', 'top' );

	// 初回のみ flush（ルール反映のため）
	if ( get_option( 'sc_area_rewrite_v' ) !== '1' ) {
		flush_rewrite_rules( false );
		update_option( 'sc_area_rewrite_v', '1' );
	}
} );

// query_var 登録
add_filter( 'query_vars', function ( array $vars ): array {
	$vars[] = 'sc_area';
	return $vars;
} );

// エリアページは page-area.php を使用。未定義スラッグは 404
add_filter( 'template_include', function ( string $template ): string {
	$slug = get_query_var( 'sc_area' );
	if ( '' === $slug ) {
		return $template;
	}
	if ( ! sc_get_area( $slug ) ) {
		global $wp_query;
		$wp_query->set_404();
		status_header( 404 );
		return get_query_template( '404' );
	}
	$found = locate_template( 'page-area.php' );
	return $found ?: $template;
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
 * エリア詳細データ（下層ページ用）
 * shichikancho は Manus 準拠で完全実装。他エリアは順次データ追加
 * 存在しないセクションは page-area.php 側で出し分け
 * @return array<string, mixed>
 */
function sc_get_area_detail( string $slug ): array {
	$data = [
		'shichikancho' => [
			'intro_en'    => 'A Timeless Shopping Street Where Cinema History Meets Artisan Craft',
			'intro_title' => '映画の記憶と職人の技が息づく、時代を超えた商店街',
			'features'    => [
				[ 'icon' => 'icon-film',  'title' => '映画の町',       'text' => 'かつて10館以上の映画館が立ち並んだ「映画の町」。昭和の映画文化が今も息づいています。' ],
				[ 'icon' => 'icon-ruler', 'title' => '職人の工房',     'text' => '建具・表具・漆器など、伝統工芸の職人が今も現役で活躍。工房見学も可能です。' ],
				[ 'icon' => 'icon-cafe',  'title' => '昭和レトロカフェ', 'text' => '創業数十年の老舗喫茶店が点在。昭和の雰囲気を残すインテリアと手作りスイーツが魅力。' ],
			],
			'towns'       => [
				[ 'name' => '七間町',   'text' => '江戸時代から続く商店街の中心地。かつては映画館が立ち並び「映画の町」として栄えました。現在も老舗店舗と新しいショップが共存し、独特の雰囲気を醸し出しています。建具職人の工房や、昭和レトロなカフェなど、時代を超えた魅力が詰まった町です。' ],
				[ 'name' => '駒形通り', 'text' => '七間町商店街から続く通りで、地元の人々に愛される飲食店や専門店が軒を連ねています。昔ながらの商店と現代的なカフェが混在し、散策が楽しいエリアです。' ],
				[ 'name' => '人宿町',   'text' => 'かつて旅人が宿泊した宿場町の名残を残すエリア。静かな住宅街の中に、隠れ家的なお店や工房が点在しています。地元の人との交流が楽しめる温かい雰囲気の町です。' ],
			],
			'history'     => [
				[ 'era' => '江戸時代',   'title' => '七間町の誕生',   'text' => '駿府城下町として整備された七間町は、江戸時代から商業の中心地として栄えました。「七間」とは間口が七間（約12.7m）の区画を意味し、当時の都市計画の名残を今に伝えています。' ],
				[ 'era' => '明治〜大正', 'title' => '映画文化の隆盛', 'text' => '明治末期から大正にかけて、七間町には次々と映画館が開業。最盛期には10館以上が立ち並び、静岡市の娯楽の中心として多くの人々が集いました。「七間町に行く」ことは映画を観ることと同義語でした。' ],
				[ 'era' => '昭和',       'title' => '商店街の黄金期', 'text' => '戦後復興とともに商店街は活気を取り戻し、衣料品店・食料品店・専門店が軒を連ねました。七間町商店街は静岡市民の生活に欠かせない場所として、地域コミュニティの核となっていました。' ],
				[ 'era' => '現在',       'title' => '新旧融合の町',   'text' => '老舗店舗と新しいカフェ・セレクトショップが共存する現在の七間町。職人の工房では伝統技術の継承が続き、若い世代のクリエイターも集まる、新旧融合の文化発信地として注目されています。' ],
			],
			'gourmet'     => [
				[ 'cat' => 'カフェ',   'name' => '七間町珈琲',        'text' => '自家焙煎の深煎りコーヒーと手作りケーキが自慢。昭和レトロな内装が人気。', 'price' => '〜800円' ],
				[ 'cat' => '和菓子',   'name' => '老舗和菓子 松月堂', 'text' => '創業100年以上の老舗。駿河名物の安倍川餅と季節の生菓子が絶品。',       'price' => '〜1,500円' ],
				[ 'cat' => '食堂',     'name' => '駒形食堂',          'text' => '地元の常連客に愛される昔ながらの食堂。日替わり定食が安くて美味しい。', 'price' => '〜900円' ],
				[ 'cat' => 'バル',     'name' => '人宿町バル',        'text' => '夜は地元ワインと静岡おでんが楽しめる隠れ家的バル。予約必須の人気店。', 'price' => '〜3,000円' ],
			],
			'course'      => [
				[ 'time' => '10:00', 'title' => '七間町商店街スタート',   'text' => '老舗和菓子店で安倍川餅を購入。商店街の歴史を感じながら散策開始。' ],
				[ 'time' => '10:30', 'title' => '建具職人工房',           'text' => '伝統建具の制作現場を見学。職人の技を間近で体験できます（要予約）。' ],
				[ 'time' => '11:30', 'title' => '七間町珈琲でランチ',     'text' => '昭和レトロな雰囲気の中、自家焙煎コーヒーとサンドイッチで一息。' ],
				[ 'time' => '13:00', 'title' => '駒形通り散策',           'text' => '個性的なセレクトショップや雑貨店を巡る。掘り出し物が見つかるかも。' ],
				[ 'time' => '14:30', 'title' => '人宿町の隠れ家スポット', 'text' => '路地裏に佇む小さなギャラリーや工房を探索。地元アーティストの作品に出会えます。' ],
				[ 'time' => '16:00', 'title' => '夕暮れの商店街',         'text' => '夕日に染まる商店街を歩きながら、お土産を購入してゴール。' ],
			],
		],
	];

	// アクセスは全エリア共通（七間町中心部基準）
	$common_access = [
		[ 'icon' => 'icon-train',    'label' => 'JR・新幹線', 'time' => '約15分',    'text' => '静岡駅北口から徒歩約15分' ],
		[ 'icon' => 'icon-train',    'label' => '静岡鉄道',   'time' => '約11分',    'text' => '新静岡駅から徒歩約11分' ],
		[ 'icon' => 'icon-bus',      'label' => 'バス',       'time' => '約10分',    'text' => '静岡駅前バスターミナルから「七間町」停留所下車すぐ' ],
		[ 'icon' => 'icon-bicycle',  'label' => '自転車',     'time' => '約5〜10分', 'text' => 'PULCLE（静岡市シェアサイクル）の利用が便利。市内各所にポートあり' ],
	];

	$detail = $data[ $slug ] ?? [];
	$detail['access'] = $common_access;
	return $detail;
}
