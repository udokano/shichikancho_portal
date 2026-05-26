<?php
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
