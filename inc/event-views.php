<?php
/**
 * 投稿（イベント／コラム）の閲覧数カウント
 * single 表示時に post_meta `views` をインクリメント
 * ボット・プレビュー・ログイン中の編集者は除外
 */

add_action( 'wp', function (): void {
	if ( ! is_singular( [ CPT_EVENT, CPT_COLUMN ] ) ) return;
	if ( is_preview() || is_admin() ) return;
	if ( ! empty( $_SERVER['HTTP_USER_AGENT'] ) && preg_match( '/bot|crawler|spider|slurp|facebookexternalhit/i', $_SERVER['HTTP_USER_AGENT'] ) ) return;

	$post_id = get_queried_object_id();
	if ( ! $post_id ) return;

	$views = (int) get_post_meta( $post_id, 'views', true );
	update_post_meta( $post_id, 'views', $views + 1 );
}, 20 );
