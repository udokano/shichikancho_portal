<?php
/** 登録処理：CPT・タクソノミー・ナビメニュー */

/**
 * CPT・タクソノミーは ACF PRO 管理画面UI で定義し、acf-json/ で管理する。
 * ここで PHP 直書きは行わない（CLAUDE.local.md ルール）。
 *
 * 例外として、コア post_tag を CPT へ追加するなど、ACF UI で扱えない調整のみ記述。
 */

// post_tag を event / column CPT へ追加
add_action( 'init', function (): void {
	if ( post_type_exists( CPT_EVENT ) ) {
		register_taxonomy_for_object_type( 'post_tag', CPT_EVENT );
	}
	if ( post_type_exists( CPT_COLUMN ) ) {
		register_taxonomy_for_object_type( 'post_tag', CPT_COLUMN );
	}
}, 11 );

// CPT rewrite slug を Manus プロトタイプ準拠に揃える（ACF UI 設定の保険）
// Manus: /walk, /shops/{id}, /events/{id}, /column/{id}, /residents/{id}, /spots
add_filter( 'register_post_type_args', function ( array $args, string $post_type ): array {
	if ( $post_type === 'walk_course' ) {
		// /walk/ は「町をめぐる」固定ページに使うため、個別記事は /walk-course/{slug}/ に
		$args['rewrite']     = [ 'slug' => 'walk-course', 'with_front' => true, 'feeds' => false, 'pages' => true ];
		$args['has_archive'] = false;
	}
	if ( $post_type === 'spot' ) {
		$args['rewrite']     = [ 'slug' => 'spots', 'with_front' => true, 'feeds' => false, 'pages' => true ];
		$args['has_archive'] = 'spots';
	}
	if ( $post_type === 'shop' ) {
		$args['rewrite']     = [ 'slug' => 'shops', 'with_front' => true, 'feeds' => false, 'pages' => true ];
		$args['has_archive'] = 'shops';
	}
	if ( $post_type === 'event' ) {
		$args['rewrite']     = [ 'slug' => 'events', 'with_front' => true, 'feeds' => false, 'pages' => true ];
		$args['has_archive'] = 'events';
	}
	if ( $post_type === 'resident' ) {
		$args['rewrite']     = [ 'slug' => 'residents', 'with_front' => true, 'feeds' => false, 'pages' => true ];
		$args['has_archive'] = 'residents';
	}
	if ( $post_type === 'news' ) {
		$args['rewrite']     = [ 'slug' => 'info', 'with_front' => true, 'feeds' => false, 'pages' => true ];
		$args['has_archive'] = 'info';
		// ラベルを「インフォメーション」に統一（URL slug=info / フッターと整合）
		$args['label']                       = 'インフォメーション';
		$args['labels']['name']              = 'インフォメーション';
		$args['labels']['singular_name']     = 'インフォメーション';
		$args['labels']['menu_name']         = 'インフォメーション';
		$args['labels']['all_items']         = 'インフォメーション一覧';
		$args['labels']['archives']          = 'インフォメーション';
	}
	return $args;
}, 99, 2 );

// 階層タクソノミーへ文字列で来た tax_input を配列化（古いフォーム / ACF 同期直後の不整合対策）
add_action( 'admin_init', function (): void {
	if ( empty( $_POST['tax_input'] ) || ! is_array( $_POST['tax_input'] ) ) return;
	foreach ( $_POST['tax_input'] as $tax_slug => $value ) {
		if ( is_array( $value ) ) continue;
		$tax = get_taxonomy( $tax_slug );
		if ( ! $tax || ! $tax->hierarchical ) continue;
		// 階層タクソノミーは ID 配列を期待。文字列なら term 名として lookup → ID に変換
		$names = array_filter( array_map( 'trim', explode( ',', $value ) ) );
		$ids   = [];
		foreach ( $names as $n ) {
			if ( ctype_digit( $n ) ) { $ids[] = (int) $n; continue; }
			$term = get_term_by( 'name', $n, $tax_slug );
			if ( $term ) $ids[] = (int) $term->term_id;
		}
		$_POST['tax_input'][ $tax_slug ] = $ids;
	}
} );

// ═══════════════════════════════════════════════════════
// ナビゲーションメニュー登録
// ═══════════════════════════════════════════════════════
add_action( 'after_setup_theme', 'sichikenchou_setup' );

function sichikenchou_setup() {
	// ナビゲーションメニュー登録
	register_nav_menus( [
		'primary' => 'トップナビゲーション（観光情報・お店・イベント・コラム・アクセス）',
		'sub'     => 'サブナビゲーション（町の紹介・映画・まちあるき等）',
		'footer'  => 'フッターナビゲーション',
	] );

	// アイキャッチ画像有効化
	add_theme_support( 'post-thumbnails' );

	// HTML5マークアップ
	add_theme_support( 'html5', [
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
	] );

	// タイトルタグ
	add_theme_support( 'title-tag' );
}
