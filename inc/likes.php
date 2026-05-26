<?php
/**
 * 投稿のいいねカウント REST API
 *
 * - POST /wp-json/sc/v1/like/{id}  → カウント +1 / { count }
 * - GET  /wp-json/sc/v1/like/{id}  → 現在のカウント / { count }
 *
 * 重複防止はクライアント側 localStorage で行う簡易版。
 * 本格的な防止が必要なら IP/トランジェント記録に拡張。
 */

add_action( 'rest_api_init', function (): void {

	register_rest_route( 'sc/v1', '/like/(?P<id>\d+)', [
		[
			'methods'             => 'GET',
			'callback'            => 'sc_like_get',
			'permission_callback' => '__return_true',
			'args' => [
				'id' => [
					'validate_callback' => fn( $v ) => is_numeric( $v ) && (int) $v > 0,
				],
			],
		],
		[
			'methods'             => 'POST',
			'callback'            => 'sc_like_increment',
			'permission_callback' => '__return_true',
			'args' => [
				'id' => [
					'validate_callback' => fn( $v ) => is_numeric( $v ) && (int) $v > 0,
				],
			],
		],
	] );
} );

function sc_like_get( WP_REST_Request $req ): WP_REST_Response {
	$id = (int) $req['id'];
	if ( ! get_post( $id ) ) {
		return new WP_REST_Response( [ 'error' => 'not_found' ], 404 );
	}
	$count = (int) get_post_meta( $id, '_like_count', true );
	return new WP_REST_Response( [ 'count' => $count ], 200 );
}

function sc_like_increment( WP_REST_Request $req ): WP_REST_Response {
	$id = (int) $req['id'];
	if ( ! get_post( $id ) ) {
		return new WP_REST_Response( [ 'error' => 'not_found' ], 404 );
	}
	$count = (int) get_post_meta( $id, '_like_count', true );
	$count++;
	update_post_meta( $id, '_like_count', $count );
	return new WP_REST_Response( [ 'count' => $count ], 200 );
}

/**
 * 取得用ヘルパー（テンプレートから使用）
 */
function sc_get_like_count( int $post_id ): int {
	return (int) get_post_meta( $post_id, '_like_count', true );
}
