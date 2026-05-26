<?php
// コメント機能は spot CPT（口コミ・レビュー用）以外で無効化
add_action( 'init', function() {
	remove_post_type_support( 'post', 'comments' );
	remove_post_type_support( 'page', 'comments' );
} );

// spot は許可、それ以外は閉じる
add_filter( 'comments_open', function ( $open, $post_id ) {
	$pt = get_post_type( $post_id );
	if ( $pt === CPT_SPOT ) return true;
	return false;
}, 20, 2 );

add_filter( 'pings_open', '__return_false', 20, 2 );

// spot のコメント配列はそのまま、他は空に
add_filter( 'comments_array', function ( $comments, $post_id ) {
	$pt = get_post_type( $post_id );
	if ( $pt === CPT_SPOT ) return $comments;
	return [];
}, 10, 2 );

// X-Pingback ヘッダー削除
add_filter( 'wp_headers', function( array $headers ): array {
	unset( $headers['X-Pingback'] );
	return $headers;
} );

// コメント投稿時、星評価を comment_meta に保存
add_action( 'comment_post', function ( $comment_id, $approved, $data ) {
	$post_id = (int) ( $data['comment_post_ID'] ?? 0 );
	if ( get_post_type( $post_id ) !== CPT_SPOT ) return;
	$rating = isset( $_POST['sc_rating'] ) ? (int) $_POST['sc_rating'] : 0;
	if ( $rating >= 1 && $rating <= 5 ) {
		update_comment_meta( $comment_id, 'sc_rating', $rating );
	}
}, 10, 3 );

// 管理画面でレビュー一覧の列に星表示
add_filter( 'manage_edit-comments_columns', function ( $cols ) {
	$cols['sc_rating'] = '評価';
	return $cols;
} );
add_action( 'manage_comments_custom_column', function ( $col, $comment_id ) {
	if ( $col !== 'sc_rating' ) return;
	$r = (int) get_comment_meta( $comment_id, 'sc_rating', true );
	echo $r ? str_repeat( '★', $r ) . str_repeat( '☆', 5 - $r ) : '—';
}, 10, 2 );
