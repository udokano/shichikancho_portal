<?php
/**
 * SEO補完：AIOSEO が出力しない部分を担当
 * - preconnect / dns-prefetch
 * - theme-color meta
 * - OpenGraph 補完（AIOSEO 無効時フォールバック）
 * - canonical
 * - hreflang（将来多言語対応用）
 */

add_action( 'wp_head', 'sc_seo_meta_head', 1 );

function sc_seo_meta_head(): void {
	// preconnect は enqueue.php で出力済み（Google Fonts 非同期読み込みと一元化）
	?>
	<link rel="dns-prefetch" href="//maps.googleapis.com">
	<meta name="theme-color" content="#2D5A27">
	<?php

	// AIOSEO 有効時は description / OGP / canonical を完全に任せる（重複防止）
	// 既定 description は AIOSEO 管理画面 → ページ毎の Meta Description で設定すること
	if ( defined( 'AIOSEO_VERSION' ) || defined( 'AIOSEO_FILE' ) ) {
		return;
	}

	$site_name   = get_bloginfo( 'name' );
	$site_url    = home_url( '/' );
	$title       = wp_get_document_title();
	$description = '';
	$og_image    = get_template_directory_uri() . '/assets/images/common/ogp.jpg';
	$og_type     = 'website';
	$canonical   = '';

	if ( is_singular() ) {
		$og_type     = 'article';
		$canonical   = get_permalink();
		$description = get_the_excerpt();
		$thumb_url   = get_the_post_thumbnail_url( get_the_ID(), 'large' );
		if ( $thumb_url ) $og_image = $thumb_url;

	} elseif ( is_post_type_archive() ) {
		$canonical = get_post_type_archive_link( get_post_type() );
		$obj       = get_queried_object();
		if ( $obj ) $description = esc_html( $obj->description );

	} elseif ( is_front_page() ) {
		$canonical   = $site_url;
		$description = get_bloginfo( 'description' );
	}

	$description = $description ?: get_bloginfo( 'description' );
	$canonical   = $canonical ?: home_url( $_SERVER['REQUEST_URI'] );

	?>
	<link rel="canonical" href="<?php echo esc_url( $canonical ); ?>">
	<meta name="description" content="<?php echo esc_attr( mb_strimwidth( $description, 0, 160, '…' ) ); ?>">

	<!-- OGP -->
	<meta property="og:type"        content="<?php echo esc_attr( $og_type ); ?>">
	<meta property="og:title"       content="<?php echo esc_attr( $title ); ?>">
	<meta property="og:description" content="<?php echo esc_attr( mb_strimwidth( $description, 0, 160, '…' ) ); ?>">
	<meta property="og:url"         content="<?php echo esc_url( $canonical ); ?>">
	<meta property="og:site_name"   content="<?php echo esc_attr( $site_name ); ?>">
	<meta property="og:image"       content="<?php echo esc_url( $og_image ); ?>">
	<meta property="og:image:width"  content="1200">
	<meta property="og:image:height" content="630">
	<meta property="og:locale"      content="ja_JP">

	<!-- Twitter Card -->
	<meta name="twitter:card"        content="summary_large_image">
	<meta name="twitter:title"       content="<?php echo esc_attr( $title ); ?>">
	<meta name="twitter:description" content="<?php echo esc_attr( mb_strimwidth( $description, 0, 160, '…' ) ); ?>">
	<meta name="twitter:image"       content="<?php echo esc_url( $og_image ); ?>">
	<?php
}

/**
 * AIOSEO が description を出力していない固定ページに meta description を補完する
 * AIOSEO 管理画面で description を設定すれば自動的に不要になる
 */
/**
 * AIOSEO が description を出力していないページに meta description を補完する
 * AIOSEO 管理画面で description を設定すれば自動的に不要になる
 */
function sc_seo_fill_missing_description(): void {
	// 固定ページ
	if ( is_page() ) {
		$post_id     = get_queried_object_id();
		$aioseo_desc = get_post_meta( $post_id, '_aioseo_description', true );
		if ( $aioseo_desc ) return;

		$defaults = [
			'about'  => '七間町商店街の歴史・文化・まちづくりへの想いをご紹介します。静岡市葵区の映画の町・七間町の魅力を発信しています。',
			'guide'  => '七間町エリアのお店・グルメ・観光・アクセスなどの案内情報をまとめています。静岡市葵区・七間町商店街の公式ガイドです。',
		];
		$slug = get_post_field( 'post_name', $post_id );
		$desc = $defaults[ $slug ] ?? wp_trim_words( get_the_excerpt( $post_id ) ?: get_bloginfo( 'description' ), 50 );
		echo '<meta name="description" content="' . esc_attr( mb_strimwidth( $desc, 0, 160, '…' ) ) . '">' . "\n";
		return;
	}

	// CPTアーカイブ
	if ( is_post_type_archive() ) {
		$defaults = [
			'event'  => '七間町商店街のイベント情報一覧。季節の祭り・マルシェ・映画上映など、まちを盛り上げるイベントをカレンダーで確認できます。',
			'shop'   => '七間町商店街のお店一覧。飲食・物販・サービスなど多彩なお店が揃う静岡市葵区のにぎわいある商店街です。',
			'column' => '七間町商店街のコラム一覧。まちの人・文化・出来事をテーマにした読みもの記事を発信しています。',
			'spot'   => '七間町エリアの観光スポット一覧。映画の町・七間町ならではの見どころをご紹介します。',
		];
		$post_type = get_post_type();
		$desc      = $defaults[ $post_type ] ?? '';
		if ( ! $desc ) return;
		echo '<meta name="description" content="' . esc_attr( mb_strimwidth( $desc, 0, 160, '…' ) ) . '">' . "\n";
	}
}

// wp_title フィルター（AIOSEO がいない場合のフォールバック）
add_filter( 'document_title_parts', 'sc_document_title_parts' );

function sc_document_title_parts( array $title ): array {
	if ( defined( 'AIOSEO_VERSION' ) ) return $title;
	$title['tagline'] = '七間町 ─ 静岡市葵区の商店街';
	return $title;
}
