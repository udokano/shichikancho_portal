<?php
/**
 * 標準「投稿（post）」機能の無効化
 *
 * 当テーマは独自CPT（shop / event / spot / column / etc.）で運用するため、
 * 標準の post post_type は管理画面・フロント両方から非表示にする。
 */

// 管理画面メニューから「投稿」を削除
add_action( 'admin_menu', function (): void {
	remove_menu_page( 'edit.php' );
} );

// 管理バー (+新規) の「投稿」を非表示
add_action( 'admin_bar_menu', function ( WP_Admin_Bar $bar ): void {
	$bar->remove_node( 'new-post' );
}, 999 );

// REST API でも露出しない（不要な公開を防ぐ）
add_filter( 'register_post_type_args', function ( array $args, string $post_type ): array {
	if ( $post_type === 'post' ) {
		$args['show_in_rest']     = false;
		$args['show_ui']          = false;
		$args['public']           = false;
		$args['publicly_queryable'] = false;
		$args['has_archive']      = false;
		$args['rewrite']          = false;
	}
	return $args;
}, 10, 2 );

// 既存 post 個別URL・blog アーカイブへのアクセスを 404 化
add_action( 'template_redirect', function (): void {
	if ( is_singular( 'post' ) || is_post_type_archive( 'post' ) || is_home() ) {
		// is_home() = posts ページ。フロントが固定ページなら影響なし
		if ( ! is_front_page() ) {
			global $wp_query;
			$wp_query->set_404();
			status_header( 404 );
			nocache_headers();
		}
	}
} );

// 投稿フィードを止める
add_action( 'do_feed_rss2', function (): void {
	wp_die( 'Feed disabled.', '', [ 'response' => 404 ] );
}, 0 );
