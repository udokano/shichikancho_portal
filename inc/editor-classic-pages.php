<?php
/**
 * 固定ページ（page）はクラシックエディタを使う
 *
 * - Classic Editor プラグイン非依存（コア標準フィルタで切替）
 * - その他の post_type（CPT）はブロックエディタのまま
 * - 例外: sc_block_editor_templates() のページテンプレートはブロックエディタで本文を管理する
 */

// event / column 以外の CPT と固定ページはクラシックエディタ
function sc_classic_editor_types(): array {
	return [
		'page',
		'coworking',
		'gallery_photo',
		'job',
		'learn_facility',
		'news',
		'property',
		'resident',
		'shop',
		'spot',
		'walk_course',
	];
}

// 本文をブロックエディタで管理するページテンプレート
// スラッグではなくテンプレート基準。同じテンプレートを割り当てれば何ページでも増やせる
function sc_block_editor_templates(): array {
	return [ SC_TPL_CONTACT_FORM ];
}

// 編集対象の投稿ID（管理画面 / ブロックエディタの REST 保存 両対応）
function sc_editing_post_id(): int {
	if ( ! empty( $_GET['post'] ) )     return (int) $_GET['post'];
	if ( ! empty( $_POST['post_ID'] ) ) return (int) $_POST['post_ID'];

	// ブロックエディタは /wp-json/wp/v2/pages/123 で保存する
	$uri = (string) ( $_SERVER['REQUEST_URI'] ?? '' );
	if ( preg_match( '#/wp/v2/pages/(\d+)#', $uri, $m ) ) return (int) $m[1];

	return 0;
}

// 指定IDがブロックエディタ管理の固定ページか（割り当てテンプレートで判定）
function sc_is_block_editor_page( int $post_id ): bool {
	if ( ! $post_id ) return false;
	$post = get_post( $post_id );
	if ( ! $post || $post->post_type !== 'page' ) return false;
	return in_array( get_page_template_slug( $post_id ), sc_block_editor_templates(), true );
}

// post_type 単位ではクラシック
add_filter( 'use_block_editor_for_post_type', function ( bool $use, string $post_type ): bool {
	if ( in_array( $post_type, sc_classic_editor_types(), true ) ) return false;
	return $use;
}, 10, 2 );

// 投稿単位で上書き（post_type フィルタより後に適用される）
add_filter( 'use_block_editor_for_post', function ( bool $use, $post ): bool {
	if ( $post instanceof WP_Post && sc_is_block_editor_page( $post->ID ) ) return true;
	return $use;
}, 10, 2 );

// ブロックエディタからクラシックブロック（core/freeform）を除去
add_action( 'enqueue_block_editor_assets', function (): void {
	wp_add_inline_script(
		'wp-blocks',
		"wp.domReady(function(){ wp.blocks.unregisterBlockType('core/freeform'); });"
	);
} );

// 固定ページの本文入力欄を非表示（ブロックエディタ管理ページは残す）
// editor サポートを消すと REST の content フィールドも消えて保存できなくなるため除外必須
add_action( 'init', function (): void {
	if ( sc_is_block_editor_page( sc_editing_post_id() ) ) return;
	remove_post_type_support( 'page', 'editor' );
}, 20 );

add_action( 'admin_enqueue_scripts', function (): void {
	$screen = get_current_screen();
	if ( ! $screen || ! in_array( $screen->post_type, sc_classic_editor_types(), true ) ) return;
	// ブロックエディタ管理ページでは wp-block-library が必要
	if ( sc_is_block_editor_page( sc_editing_post_id() ) ) return;
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'wc-blocks-style' );
}, 100 );
