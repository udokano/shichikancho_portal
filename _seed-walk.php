<?php
/**
 * 散策コースのサンプル投稿シーダー（ローカル開発用）
 *
 * 使い方:
 *   ログイン済み管理者で次のURLにアクセス
 *   /wp-content/themes/sichikenchou/_seed-walk.php?seed=walk
 *
 * 投入後に本ファイルは削除してください。
 */

// WordPress を読み込む（テーマ内から）
$wp_load = dirname( __DIR__, 3 ) . '/wp-load.php';
if ( ! file_exists( $wp_load ) ) {
	die( 'wp-load.php not found' );
}
require $wp_load;

// 安全策: 管理者かつ ?seed=walk のみ実行
if ( ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) {
	wp_die( '管理者でログインしてください。' );
}
if ( ( $_GET['seed'] ?? '' ) !== 'walk' ) {
	wp_die( 'Use ?seed=walk' );
}

header( 'Content-Type: text/plain; charset=utf-8' );

$courses = [
	[
		'title'    => '駿府城〜七間町コース',
		'excerpt'  => '駿府城公園から七間町まで、歴史を感じながら歩くコース。',
		'duration' => 120,
		'distance' => '約3km',
		'difficulty' => 'normal',
		'description' => "静岡の歴史を象徴する駿府城公園から、昭和の面影を残す七間町商店街までを歩く約3kmのコース。\n途中、地域に愛されるカフェや工房に立ち寄りながら、町の時間を味わえます。",
		'area'     => '中心部',
		'scenes'   => [ '歴史', '散策' ],
	],
	[
		'title'    => '映画館めぐりコース',
		'excerpt'  => 'かつての映画館の面影を訪ねる懐かしいコース。',
		'duration' => 90,
		'distance' => '約2km',
		'difficulty' => 'easy',
		'description' => "「映画の町」七間町ならではのコース。現存する3つの映画館と、かつて栄えた映画街の跡を辿りながら、町の文化を体感します。",
		'area'     => '七間町',
		'scenes'   => [ '文化', '歴史' ],
	],
	[
		'title'    => 'カフェ＆雑貨コース',
		'excerpt'  => 'おしゃれなカフェと雑貨店を巡るゆったりコース。',
		'duration' => 120,
		'distance' => '約2.5km',
		'difficulty' => 'easy',
		'description' => "七間町周辺のおしゃれなカフェと地元作家の雑貨店を巡るコース。お気に入りの一杯と、心ときめく一品に出会える2時間。",
		'area'     => '七間町',
		'scenes'   => [ 'グルメ', 'ショッピング', '休憩' ],
	],
	[
		'title'    => '歴史探訪コース',
		'excerpt'  => '静岡の歴史を深く知る本格的な探訪コース。',
		'duration' => 180,
		'distance' => '約4km',
		'difficulty' => 'hard',
		'description' => "駿府城跡・浅間神社・七間町商店街・呉服町を巡る本格コース。ボランティアガイドの解説付きで歩けば、静岡の400年の歩みが手に取るように分かります。",
		'area'     => '中心部',
		'scenes'   => [ '歴史', '文化' ],
	],
	[
		'title'    => '朝活ウォーキング',
		'excerpt'  => '朝の静けさを楽しむ短時間コース。',
		'duration' => 45,
		'distance' => '約1.5km',
		'difficulty' => 'easy',
		'description' => "町が動き出す前の静かな時間を歩く45分のコース。駿府城公園の朝の空気と、開店準備に向かう商店街の活気を味わえます。",
		'area'     => '七間町',
		'scenes'   => [ '散策' ],
	],
];

// area / walk_scene タームを必要に応じて作成
function seed_walk_term( string $taxonomy, string $name ): int {
	$term = get_term_by( 'name', $name, $taxonomy );
	if ( $term ) return (int) $term->term_id;
	$slug = sanitize_title( $name );
	$res  = wp_insert_term( $name, $taxonomy, [ 'slug' => $slug ] );
	if ( is_wp_error( $res ) ) return 0;
	return (int) $res['term_id'];
}

$created = 0;
$skipped = 0;

foreach ( $courses as $c ) {
	// 同タイトル既存チェック
	$existing = get_posts( [
		'post_type'      => 'walk_course',
		'title'          => $c['title'],
		'posts_per_page' => 1,
		'fields'         => 'ids',
	] );
	if ( $existing ) {
		echo "SKIP (exists): {$c['title']}\n";
		$skipped++;
		continue;
	}

	$pid = wp_insert_post( [
		'post_title'   => $c['title'],
		'post_excerpt' => $c['excerpt'],
		'post_content' => $c['description'],
		'post_status'  => 'publish',
		'post_type'    => 'walk_course',
	], true );

	if ( is_wp_error( $pid ) ) {
		echo "ERROR: {$c['title']} → " . $pid->get_error_message() . "\n";
		continue;
	}

	// ACFフィールド
	update_field( 'walk_duration', $c['duration'], $pid );
	update_field( 'walk_distance', $c['distance'], $pid );
	update_field( 'walk_difficulty', $c['difficulty'], $pid );
	update_field( 'walk_description', $c['description'], $pid );

	// ターム
	$area_id = seed_walk_term( 'area', $c['area'] );
	if ( $area_id ) wp_set_object_terms( $pid, [ $area_id ], 'area' );

	$scene_ids = [];
	foreach ( $c['scenes'] as $s ) {
		$tid = seed_walk_term( 'walk_scene', $s );
		if ( $tid ) $scene_ids[] = $tid;
	}
	if ( $scene_ids ) wp_set_object_terms( $pid, $scene_ids, 'walk_scene' );

	echo "CREATED: {$c['title']} (ID: {$pid})\n";
	$created++;
}

echo "\n----\n";
echo "Created: {$created}, Skipped: {$skipped}\n";
echo "ファイルは削除してください: " . __FILE__ . "\n";
