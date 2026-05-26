<?php
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
