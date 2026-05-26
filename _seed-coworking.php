<?php
/** コワーキングサンプル投稿シーダー（ローカル開発用） */
$wp_load = dirname( __DIR__, 3 ) . '/wp-load.php';
require $wp_load;

if ( ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) wp_die( '管理者ログイン要' );
if ( ( $_GET['seed'] ?? '' ) !== 'cw' ) wp_die( 'Use ?seed=cw' );

header( 'Content-Type: text/plain; charset=utf-8' );

$samples = [
	[
		'title'   => '七間町コワーキング NANATSU',
		'desc'    => '七間町商店街の中心に位置するコワーキングスペース。フリーランス、リモートワーカー、スタートアップに最適な環境。法人登記も可能で、起業の第一歩を支援します。',
		'address' => '静岡市葵区七間町1-5 2F',
		'hours'   => '平日 7:00〜22:00 / 土日祝 9:00〜20:00',
		'dropin'  => '1日 1,500円',
		'capacity'=> '40席',
		'rating'  => 4.5,
		'monthly' => '月額 15,000円〜',
		'phone'   => '054-XXX-XXXX',
		'website' => 'https://example.com/nanatsu',
		'tags'    => [ '高速Wi-Fi', '電源完備', '会議室2室', 'フリードリンク', 'ロッカー', '郵便受取', '法人登記可' ],
	],
	[
		'title'   => '静岡シェアオフィス BRIDGE',
		'desc'    => '24時間利用可能な本格的シェアオフィス。個室ブースから大型会議室まで、ビジネスの成長に合わせた空間を提供。定期的にビジネス交流会も開催。',
		'address' => '静岡市葵区七間町2-10 3F',
		'hours'   => '24時間利用可（会員制）',
		'dropin'  => '1日 2,000円',
		'capacity'=> '60席',
		'rating'  => 4.8,
		'monthly' => '月額 25,000円〜',
		'phone'   => '054-XXX-XXXX',
		'website' => 'https://example.com/bridge',
		'tags'    => [ '24時間利用', '個室ブース', '会議室4室', 'イベントスペース', 'シャワー', '法人登記可', '駐輪場' ],
	],
	[
		'title'   => 'カフェワーク 七間茶房',
		'desc'    => '落ち着いた雰囲気のカフェ併設ワークスペース。ちょっとした作業やオンライン会議に最適。静岡茶とスイーツを楽しみながら仕事ができます。',
		'address' => '静岡市葵区七間町3-2',
		'hours'   => '8:00〜19:00（定休日: 水曜）',
		'dropin'  => 'ドリンク代のみ（席料無料）',
		'capacity'=> '15席',
		'rating'  => 4.2,
		'monthly' => '—',
		'phone'   => '054-XXX-XXXX',
		'website' => 'https://example.com/cafework',
		'tags'    => [ 'Wi-Fi', '静かな環境', 'ドリンク付', '予約不要' ],
	],
];

$created = 0;
$skipped = 0;

foreach ( $samples as $s ) {
	$existing = get_posts( [ 'post_type' => 'coworking', 'title' => $s['title'], 'posts_per_page' => 1, 'fields' => 'ids' ] );
	if ( $existing ) {
		echo "SKIP (exists): {$s['title']}\n";
		$skipped++;
		continue;
	}
	$pid = wp_insert_post( [
		'post_title'   => $s['title'],
		'post_excerpt' => $s['desc'],
		'post_content' => $s['desc'],
		'post_status'  => 'publish',
		'post_type'    => 'coworking',
	], true );
	if ( is_wp_error( $pid ) ) {
		echo "ERROR: {$s['title']} → " . $pid->get_error_message() . "\n";
		continue;
	}

	update_field( 'cw_name',       $s['title'], $pid );
	update_field( 'cw_description',$s['desc'],  $pid );
	update_field( 'cw_address',    $s['address'], $pid );
	update_field( 'cw_hours',      $s['hours'],   $pid );
	update_field( 'cw_dropin',     $s['dropin'],  $pid );
	update_field( 'cw_capacity',   $s['capacity'],$pid );
	update_field( 'cw_rating',     $s['rating'],  $pid );
	update_field( 'cw_monthly',    $s['monthly'], $pid );
	update_field( 'cw_phone',      $s['phone'],   $pid );
	update_field( 'cw_website',    $s['website'], $pid );
	update_field( 'cw_tags', array_map( fn( $t ) => [ 'tag' => $t ], $s['tags'] ), $pid );

	echo "CREATED: {$s['title']} (ID: {$pid})\n";
	$created++;
}

echo "\n----\nCreated: {$created}, Skipped: {$skipped}\n";
echo "本ファイル削除: " . __FILE__ . "\n";
