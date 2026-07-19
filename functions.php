<?php
// 読み込み順に依存あり：constants → 登録 → ヘルパー → 各機能
require get_template_directory() . '/inc/constants.php';   // スラッグ定数（最優先）
require get_template_directory() . '/inc/register.php';    // CPT・タクソノミー・ナビメニュー登録
require get_template_directory() . '/inc/helpers.php';     // 共通ヘルパー・PICK UP・エリアデータ
require get_template_directory() . '/inc/acf.php';         // ACF 設定・オプションページ
require get_template_directory() . '/inc/admin.php';       // 管理画面・エディタ挙動
require get_template_directory() . '/inc/enqueue.php';     // CSS/JS・フォント・ファビコン
require get_template_directory() . '/inc/breadcrumbs.php'; // パンくずデータ
require get_template_directory() . '/inc/schema.php';      // 構造化データ（SC_ADDRESS 等の定数含む）
require get_template_directory() . '/inc/seo.php';         // SEO 補完・LLMO・geo（schema の定数を参照）
require get_template_directory() . '/inc/blocks.php';      // ブロック登録・パターン・CF7日本語化
require get_template_directory() . '/inc/endpoints.php';   // REST / AJAX エンドポイント
require get_template_directory() . '/inc/likes.php';       // いいねカウント（現在フロント未使用）

// Google Maps APIキー（フロントページのお店・スポットマップ用）
define( 'SC_GOOGLE_MAPS_KEY', 'AIzaSyCaBn8CakCqCFVC_Q6HCNNVuDyKT1dscQk' );
