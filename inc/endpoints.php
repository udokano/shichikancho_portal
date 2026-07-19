<?php
/** REST / AJAX エンドポイント */

/**
 * 投稿（イベント／コラム）の閲覧数カウント
 * single 表示時に post_meta `views` をインクリメント
 * ボット・プレビュー・ログイン中の編集者は除外
 */

add_action( 'wp', function (): void {
	if ( ! is_singular( [ CPT_EVENT, CPT_COLUMN ] ) ) return;
	if ( is_preview() || is_admin() ) return;
	if ( ! empty( $_SERVER['HTTP_USER_AGENT'] ) && preg_match( '/bot|crawler|spider|slurp|facebookexternalhit/i', $_SERVER['HTTP_USER_AGENT'] ) ) return;

	$post_id = get_queried_object_id();
	if ( ! $post_id ) return;

	$views = (int) get_post_meta( $post_id, 'views', true );
	update_post_meta( $post_id, 'views', $views + 1 );
}, 20 );

// ═══════════════════════════════════════════════════════
// ギャラリー無限スクロール AJAX
// ═══════════════════════════════════════════════════════
/** ギャラリー無限スクロール用 AJAX エンドポイント */

add_action( 'wp_ajax_sc_load_gallery',        'sc_ajax_load_gallery' );
add_action( 'wp_ajax_nopriv_sc_load_gallery', 'sc_ajax_load_gallery' );

function sc_ajax_load_gallery(): void {
	$paged = max( 1, (int) ( $_GET['paged'] ?? 1 ) );
	$cats  = isset( $_GET['cat'] ) ? array_filter( array_map( 'sanitize_text_field', (array) $_GET['cat'] ) ) : [];
	$ppp   = max( 1, (int) ( $_GET['ppp'] ?? 12 ) );

	$args = [
		'post_type'      => CPT_GALLERY,
		'posts_per_page' => $ppp,
		'paged'          => $paged,
		'orderby'        => 'date',
		'order'          => 'DESC',
		'no_found_rows'  => false,
	];

	if ( $cats ) {
		$tids = [];
		foreach ( $cats as $s ) { $tid = sc_get_term_id_by_slug( $s, TAX_GALLERY_CAT ); if ( $tid ) $tids[] = $tid; }
		if ( $tids ) {
			$args['tax_query'] = [ [ 'taxonomy' => TAX_GALLERY_CAT, 'field' => 'term_id', 'terms' => $tids, 'operator' => 'IN' ] ];
		}
	}

	$q = new WP_Query( $args );

	ob_start();
	if ( $q->have_posts() ) {
		while ( $q->have_posts() ) {
			$q->the_post();
			get_template_part( 'template-parts/components/gallery-item', null, [ 'post_id' => get_the_ID() ] );
		}
	}
	wp_reset_postdata();
	$html = ob_get_clean();

	wp_send_json_success( [
		'html'      => $html,
		'has_more'  => $paged < (int) $q->max_num_pages,
		'page'      => $paged,
		'max_pages' => (int) $q->max_num_pages,
	] );
}
