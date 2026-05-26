<?php
/**
 * 記事系（ブログ）サイドバー共通コンポーネント
 *
 * @param array $args {
 *   @type string $post_type      表示する CPT スラッグ（recent / category 用）
 *   @type string $taxonomy       カテゴリーtax スラッグ
 *   @type int    $current_id     現在表示している投稿ID（recent から除外）
 *   @type array  $toc_items      目次アイテム配列 [ ['level'=>'h2', 'text'=>'..', 'id'=>'..'], ... ]
 *   @type string $tag_taxonomy   タグtax スラッグ（デフォルト post_tag）
 *   @type string $base_class     BEM ルートクラス（デフォルト c-post-sidebar）
 * }
 */
$post_type         = $args['post_type']         ?? 'post';
$taxonomy          = $args['taxonomy']          ?? 'category';
$current_id        = (int) ( $args['current_id'] ?? get_the_ID() );
$toc_items         = $args['toc_items']         ?? [];
$tag_taxonomy      = $args['tag_taxonomy']      ?? 'post_tag';
$base              = $args['base_class']        ?? 'c-post-sidebar';
$archive_url       = $args['archive_url']       ?? '';
$cat_param         = $args['cat_param']         ?? 'cat';
$tag_param         = $args['tag_param']         ?? 'tag';
$recent_label      = $args['recent_label']      ?? '新着情報';
$cat_label         = $args['cat_label']         ?? 'カテゴリー';
$show_recent_thumb = $args['show_recent_thumb'] ?? true;

$build_term_url = function( $term, $param ) use ( $archive_url ) {
	if ( $archive_url ) return add_query_arg( $param, $term->slug, $archive_url );
	return get_term_link( $term );
};

$recent = new WP_Query( [
	'post_type'           => $post_type,
	'posts_per_page'      => 4,
	'post__not_in'        => [ $current_id ],
	'ignore_sticky_posts' => 1,
	'no_found_rows'       => true,
] );

$cat_terms = $taxonomy     ? get_terms( [ 'taxonomy' => $taxonomy,     'hide_empty' => false ] ) : [];
$tag_terms = $tag_taxonomy ? get_terms( [ 'taxonomy' => $tag_taxonomy, 'hide_empty' => true  ] ) : [];
?>
<aside class="<?php echo esc_attr( $base ); ?>" aria-label="サイドバー">

	<?php if ( $recent->have_posts() ) : ?>
	<section class="<?php echo esc_attr( $base ); ?>__widget">
		<h3 class="<?php echo esc_attr( $base ); ?>__title">
			<svg class="<?php echo esc_attr( $base ); ?>__title-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
			<?php echo esc_html( $recent_label ); ?>
		</h3>
		<ul class="<?php echo esc_attr( $base ); ?>__list">
			<?php while ( $recent->have_posts() ) : $recent->the_post();
				$r_id    = get_the_ID();
				$r_thumb = $show_recent_thumb ? sc_thumbnail_url( $r_id, 'thumbnail' ) : '';
				$r_date  = get_the_date( 'Y/m/d' );
			?>
			<li>
				<a href="<?php the_permalink(); ?>">
					<?php if ( $show_recent_thumb && $r_thumb ) : ?>
					<span class="<?php echo esc_attr( $base ); ?>__thumb">
						<picture class="u-picture-fill">
							<img class="u-img-cover" src="<?php echo esc_url( $r_thumb ); ?>" alt="" aria-hidden="true" loading="lazy" width="80" height="80">
						</picture>
					</span>
					<?php endif; ?>
					<span class="<?php echo esc_attr( $base ); ?>__meta">
						<span class="<?php echo esc_attr( $base ); ?>__date"><?php echo esc_html( $r_date ); ?></span>
						<span class="<?php echo esc_attr( $base ); ?>__name"><?php the_title(); ?></span>
					</span>
				</a>
			</li>
			<?php endwhile; wp_reset_postdata(); ?>
		</ul>
	</section>
	<!-- /.<?php echo esc_html( $base ); ?>__widget -->
	<?php endif; ?>

	<?php if ( $cat_terms && ! is_wp_error( $cat_terms ) ) : ?>
	<section class="<?php echo esc_attr( $base ); ?>__widget">
		<h3 class="<?php echo esc_attr( $base ); ?>__title">
			<svg class="<?php echo esc_attr( $base ); ?>__title-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
			<?php echo esc_html( $cat_label ); ?>
		</h3>
		<ul class="<?php echo esc_attr( $base ); ?>__cats">
			<?php foreach ( $cat_terms as $t ) : ?>
			<li>
				<a href="<?php echo esc_url( $build_term_url( $t, $cat_param ) ); ?>">
					<span><?php echo esc_html( $t->name ); ?></span>
					<span class="<?php echo esc_attr( $base ); ?>__count"><?php echo (int) $t->count; ?></span>
				</a>
			</li>
			<?php endforeach; ?>
		</ul>
	</section>
	<!-- /.<?php echo esc_html( $base ); ?>__widget -->
	<?php endif; ?>

	<?php if ( $tag_terms && ! is_wp_error( $tag_terms ) ) : ?>
	<section class="<?php echo esc_attr( $base ); ?>__widget">
		<h3 class="<?php echo esc_attr( $base ); ?>__title">
			<svg class="<?php echo esc_attr( $base ); ?>__title-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
			タグ
		</h3>
		<ul class="<?php echo esc_attr( $base ); ?>__tags">
			<?php foreach ( $tag_terms as $t ) : ?>
			<li>
				<a href="<?php echo esc_url( $build_term_url( $t, $tag_param ) ); ?>">
					<span><?php echo esc_html( $t->name ); ?></span>
					<span class="<?php echo esc_attr( $base ); ?>__count">(<?php echo (int) $t->count; ?>)</span>
				</a>
			</li>
			<?php endforeach; ?>
		</ul>
	</section>
	<!-- /.<?php echo esc_html( $base ); ?>__widget -->
	<?php endif; ?>

	<?php if ( ! empty( $toc_items ) ) : ?>
	<section class="<?php echo esc_attr( $base ); ?>__widget">
		<h3 class="<?php echo esc_attr( $base ); ?>__title">
			<svg class="<?php echo esc_attr( $base ); ?>__title-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
			目次
		</h3>
		<ol class="<?php echo esc_attr( $base ); ?>__toc">
			<?php foreach ( $toc_items as $item ) : ?>
			<li class="<?php echo esc_attr( $base ); ?>__toc-item <?php echo esc_attr( $base ); ?>__toc-item--<?php echo esc_attr( $item['level'] ); ?>">
				<a href="#<?php echo esc_attr( $item['id'] ); ?>"><?php echo esc_html( $item['text'] ); ?></a>
			</li>
			<?php endforeach; ?>
		</ol>
	</section>
	<!-- /.<?php echo esc_html( $base ); ?>__widget -->
	<?php endif; ?>

</aside>
<!-- /.<?php echo esc_html( $base ); ?> -->
