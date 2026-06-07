<?php
/** 管理画面の左メニューの並び順を業務都合の順に固定する */

// カスタム並び順を有効化
add_filter( 'custom_menu_order', '__return_true' );

// メニュー順を指定（CPTスラッグは constants.php の定数を使用）
add_filter( 'menu_order', function ( $menu_ord ) {
	return [
		'index.php',                                  // ダッシュボード
		'edit.php?post_type=page',                    // 固定ページ
		'upload.php',                                 // メディア
		'edit.php?post_type=' . CPT_SHOP,             // お店
		'edit.php?post_type=' . CPT_SPOT,             // スポット
		'edit.php?post_type=' . CPT_EVENT,            // イベント
		'edit.php?post_type=' . CPT_NEWS,             // インフォメーション
		'edit.php?post_type=' . CPT_COLUMN,           // 七ぶらコラム
		'edit.php?post_type=' . CPT_RESIDENT,         // お隣さんの話
		'edit.php?post_type=' . CPT_WALK,             // 散策コース
		'edit.php?post_type=' . CPT_GALLERY,          // ギャラリー
		'edit.php?post_type=' . CPT_LEARN,            // 学ぶ施設
		'edit.php?post_type=' . CPT_COWORK,           // コアワーキング
		'edit.php?post_type=' . CPT_JOB,              // 求人情報
		'edit.php?post_type=' . CPT_PROPERTY,         // 空き物件
		'edit.php?post_type=' . CPT_PHOTO_AWARD,      // 入賞作品
	];
} );
