<?php
/**
 * パンくずデータを配列で返す
 * @return array [ ['label' => string, 'url' => string|null], ... ]
 */
function sc_get_breadcrumbs(): array {
	$crumbs = [
		[ 'label' => 'ホーム', 'url' => home_url( '/' ) ],
	];

	if ( is_singular() ) {
		$post_type = get_post_type();
		$obj       = get_post_type_object( $post_type );

		// CPT アーカイブ
		if ( $obj && $obj->has_archive ) {
			$archive_url = get_post_type_archive_link( $post_type );
			if ( $archive_url ) {
				$crumbs[] = [
					'label' => esc_html( $obj->labels->name ),
					'url'   => esc_url( $archive_url ),
				];
			}
		}

		// カテゴリーをアーカイブ絞り込みURLでリンク（CPT ごとにパラメータを切り替え）
		$tax_filter_map = [
			CPT_EVENT  => [ 'tax' => TAX_EVENT_CAT,  'param' => 'ev_cat' ],
			CPT_SHOP   => [ 'tax' => TAX_SHOP_CAT,   'param' => 'cat'    ],
			CPT_COLUMN => [ 'tax' => TAX_COLUMN_CAT, 'param' => 'cat'    ],
			CPT_SPOT   => [ 'tax' => TAX_SPOT_TYPE,  'param' => 'type'   ],
		];
		if ( isset( $tax_filter_map[ $post_type ] ) ) {
			$map   = $tax_filter_map[ $post_type ];
			$terms = get_the_terms( get_the_ID(), $map['tax'] );
			if ( $terms && ! is_wp_error( $terms ) ) {
				$term     = $terms[0];
				$cat_url  = $archive_url ? add_query_arg( $map['param'], $term->slug, $archive_url ) : '';
				$crumbs[] = [ 'label' => esc_html( $term->name ), 'url' => esc_url( $cat_url ) ];
			}
		}

		// 投稿タイトル（現在地 = リンクなし）
		$crumbs[] = [ 'label' => get_the_title(), 'url' => null ];

	} elseif ( is_post_type_archive() ) {
		$obj         = get_queried_object();
		$archive_url = get_post_type_archive_link( $obj->name );

		// ev_cat フィルターが付いている場合はアーカイブをリンクにしてカテゴリー名を追加
		$ev_cat_slugs = isset( $_GET['ev_cat'] ) ? array_filter( array_map( 'sanitize_text_field', (array) $_GET['ev_cat'] ) ) : [];
		if ( $ev_cat_slugs && defined( 'TAX_EVENT_CAT' ) ) {
			$crumbs[] = [ 'label' => esc_html( $obj->labels->name ), 'url' => esc_url( $archive_url ) ];
			$term = get_term_by( 'slug', reset( $ev_cat_slugs ), TAX_EVENT_CAT );
			if ( $term && ! is_wp_error( $term ) ) {
				$crumbs[] = [ 'label' => esc_html( $term->name ), 'url' => null ];
			}
		} else {
			$crumbs[] = [ 'label' => esc_html( $obj->labels->name ), 'url' => null ];
		}

	} elseif ( is_tax() || is_category() || is_tag() ) {
		$term     = get_queried_object();
		$crumbs[] = [ 'label' => esc_html( $term->name ), 'url' => null ];

	} elseif ( is_page() ) {
		// 固定ページ：親ページがあれば挿入
		$ancestors = array_reverse( get_post_ancestors( get_the_ID() ) );
		foreach ( $ancestors as $ancestor_id ) {
			$crumbs[] = [
				'label' => esc_html( get_the_title( $ancestor_id ) ),
				'url'   => esc_url( get_permalink( $ancestor_id ) ),
			];
		}
		$crumbs[] = [ 'label' => get_the_title(), 'url' => null ];

	} elseif ( is_search() ) {
		$crumbs[] = [
			'label' => '「' . esc_html( get_search_query() ) . '」の検索結果',
			'url'   => null,
		];

	} elseif ( is_404() ) {
		$crumbs[] = [ 'label' => 'ページが見つかりません', 'url' => null ];
	}

	return $crumbs;
}

// パンくず HTML を出力（template-parts から呼び出す）
function sc_breadcrumbs_html(): void {
	if ( is_front_page() ) return;

	$crumbs = sc_get_breadcrumbs();
	$last   = count( $crumbs ) - 1;

	echo '<nav class="c-breadcrumbs" aria-label="パンくずリスト">';
	echo '<ol class="c-breadcrumbs__list" itemscope itemtype="https://schema.org/BreadcrumbList">';

	foreach ( $crumbs as $i => $crumb ) {
		$position = $i + 1;
		echo '<li class="c-breadcrumbs__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';

		// 最初のアイテム（ホーム）にアイコンを付与
		$icon = '';
		if ( $i === 0 ) {
			$icon = '<svg class="c-breadcrumbs__icon" aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-house"></use></svg>';
		}

		if ( $crumb['url'] && $i < $last ) {
			echo '<a class="c-breadcrumbs__link" href="' . esc_url( $crumb['url'] ) . '" itemprop="item">';
			echo $icon;
			echo '<span itemprop="name">' . esc_html( $crumb['label'] ) . '</span>';
			echo '</a>';
		} else {
			echo '<span class="c-breadcrumbs__current" itemprop="name">' . $icon . esc_html( $crumb['label'] ) . '</span>';
		}

		echo '<meta itemprop="position" content="' . $position . '">';
		echo '</li>';
	}

	echo '</ol>';
	echo '</nav>';
}
