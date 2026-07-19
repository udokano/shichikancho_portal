<?php
/** 管理画面・エディタ挙動の調整 */

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

// ═══════════════════════════════════════════════════════
// コメント無効化
// ═══════════════════════════════════════════════════════
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

// ═══════════════════════════════════════════════════════
// 標準投稿の無効化
// ═══════════════════════════════════════════════════════
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

// ═══════════════════════════════════════════════════════
// ユーザープロフィール拡張
// ═══════════════════════════════════════════════════════
/**
 * ユーザープロフィール拡張
 * - Instagram URL / Facebook URL を追加
 *   表示: 投稿者名 + プロフィール（biographical info）+ アバター + SNSリンク
 */

add_filter( 'user_contactmethods', function ( array $methods ): array {
	$methods['instagram_url'] = 'Instagram URL';
	$methods['facebook_url']  = 'Facebook URL';
	return $methods;
} );

// ═══════════════════════════════════════════════════════
// 固定ページのエディタ切替
// ═══════════════════════════════════════════════════════
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
