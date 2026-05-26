<?php
/** 物件サンプル投稿シーダー */
$wp_load = dirname( __DIR__, 3 ) . '/wp-load.php';
require $wp_load;
if ( ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) wp_die( '管理者ログイン要' );
if ( ( $_GET['seed'] ?? '' ) !== 'prop' ) wp_die( 'Use ?seed=prop' );
header( 'Content-Type: text/plain; charset=utf-8' );

$samples = [
	[ 'title'=>'七間町1丁目 路面店舗', 'category'=>'店舗', 'status'=>'open', 'address'=>'静岡市葵区七間町1-12', 'area'=>'45㎡', 'floor'=>'1階（路面）', 'rent'=>'月額 18万円', 'tags'=>['角地','駐車場1台付','飲食可','即入居可'] ],
	[ 'title'=>'七間町ビル 2階オフィス', 'category'=>'オフィス', 'status'=>'open', 'address'=>'静岡市葵区七間町2-5 七間町ビル2F', 'area'=>'60㎡', 'floor'=>'2階', 'rent'=>'月額 12万円', 'tags'=>['エレベーター有','光回線対応','24時間利用可','分割可'] ],
	[ 'title'=>'七間町3丁目 空き地', 'category'=>'土地', 'status'=>'open', 'address'=>'静岡市葵区七間町3-8', 'area'=>'120㎡', 'floor'=>'—', 'rent'=>'要相談', 'tags'=>['建築自由','商業地域','前面道路6m','更地渡し'] ],
	[ 'title'=>'七間町レジデンス 空き部屋', 'category'=>'空き部屋', 'status'=>'negotiating', 'address'=>'静岡市葵区七間町1-20 七間町レジデンス3F', 'area'=>'25㎡', 'floor'=>'3階', 'rent'=>'月額 6万円', 'tags'=>['SOHO可','ペット不可','バストイレ別','南向き'] ],
	[ 'title'=>'旧映画館跡地 大型店舗', 'category'=>'店舗', 'status'=>'soon', 'address'=>'静岡市葵区七間町2-15', 'area'=>'200㎡', 'floor'=>'1-2階', 'rent'=>'月額 45万円', 'tags'=>['大型店舗','天井高4m','搬入口有','改装自由'] ],
	[ 'title'=>'七間町4丁目 小規模オフィス', 'category'=>'オフィス', 'status'=>'open', 'address'=>'静岡市葵区七間町4-3 2F', 'area'=>'30㎡', 'floor'=>'2階', 'rent'=>'月額 7万円', 'tags'=>['個人事業主向け','光回線','共用トイレ','エアコン完備'] ],
];

$created = $skipped = 0;
foreach ( $samples as $s ) {
	$existing = get_posts( [ 'post_type'=>'property', 'title'=>$s['title'], 'posts_per_page'=>1, 'fields'=>'ids' ] );
	if ( $existing ) { echo "SKIP: {$s['title']}\n"; $skipped++; continue; }
	$pid = wp_insert_post( [
		'post_title'  => $s['title'],
		'post_status' => 'publish',
		'post_type'   => 'property',
	], true );
	if ( is_wp_error( $pid ) ) { echo "ERR: {$s['title']}\n"; continue; }

	update_field( 'prop_name',    $s['title'],   $pid );
	update_field( 'prop_address', $s['address'], $pid );
	update_field( 'prop_area',    $s['area'],    $pid );
	update_field( 'prop_floor',   $s['floor'],   $pid );
	update_field( 'prop_rent',    $s['rent'],    $pid );
	update_field( 'prop_status',  $s['status'],  $pid );
	update_field( 'prop_is_available', $s['status'] !== 'closed', $pid );
	update_field( 'prop_tags', array_map( fn( $t ) => [ 'tag' => $t ], $s['tags'] ), $pid );

	// カテゴリーは TAX_PROP_TYPE
	$term = get_term_by( 'name', $s['category'], 'property_type' );
	if ( ! $term ) {
		$res = wp_insert_term( $s['category'], 'property_type', [ 'slug' => sanitize_title( $s['category'] ) ] );
		if ( ! is_wp_error( $res ) ) $term_id = $res['term_id'];
	} else {
		$term_id = $term->term_id;
	}
	if ( ! empty( $term_id ) ) wp_set_object_terms( $pid, [ (int) $term_id ], 'property_type' );

	echo "CREATED: {$s['title']} (ID: {$pid})\n";
	$created++;
}
echo "\n----\nCreated: {$created}, Skipped: {$skipped}\n";
echo "本ファイル削除: " . __FILE__ . "\n";
