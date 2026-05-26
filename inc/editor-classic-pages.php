<?php
/**
 * 固定ページ（page）はクラシックエディタを使う
 *
 * - Classic Editor プラグイン非依存（コア標準フィルタで切替）
 * - その他の post_type（CPT）はブロックエディタのまま
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

add_filter( 'use_block_editor_for_post_type', function ( bool $use, string $post_type ): bool {
	if ( in_array( $post_type, sc_classic_editor_types(), true ) ) return false;
	return $use;
}, 10, 2 );

// ブロックエディタからクラシックブロック（core/freeform）を除去
add_action( 'enqueue_block_editor_assets', function (): void {
	wp_add_inline_script(
		'wp-blocks',
		"wp.domReady(function(){ wp.blocks.unregisterBlockType('core/freeform'); });"
	);
} );

// 固定ページの本文入力欄を非表示
add_action( 'init', function (): void {
	remove_post_type_support( 'page', 'editor' );
} );

add_action( 'admin_enqueue_scripts', function (): void {
	$screen = get_current_screen();
	if ( ! $screen || ! in_array( $screen->post_type, sc_classic_editor_types(), true ) ) return;
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'wc-blocks-style' );
}, 100 );
