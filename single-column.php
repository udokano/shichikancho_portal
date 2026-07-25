<?php
/** コラム単体記事ページ */
get_header();

if ( ! have_posts() ) {
	get_footer();
	return;
}
the_post();

if ( function_exists( 'schema_article' ) ) schema_article( get_the_ID() );

// アイキャッチ未設定時はヒーロー画像を出さない（No Image は表示しない）
$thumb     = get_the_post_thumbnail_url( get_the_ID(), 'large' ) ?: '';
$cats      = get_the_terms( get_the_ID(), TAX_COLUMN_CAT );
$tags      = get_the_terms( get_the_ID(), 'post_tag' );
$author_id = get_the_author_meta( 'ID' );
$author    = get_the_author();
$avatar    = get_avatar_url( $author_id, [ 'size' => 80 ] );
$bio       = get_the_author_meta( 'description' );
$read_min  = max( 1, round( mb_strlen( strip_tags( get_the_content() ) ) / 400 ) );

// 目次（h2/h3 から自動生成）
$content_html = apply_filters( 'the_content', get_the_content() );
preg_match_all( '/<(h[23])[^>]*>(.+?)<\/\1>/i', $content_html, $headings_matches, PREG_SET_ORDER );
$toc_items = [];
foreach ( $headings_matches as $i => $m ) {
	$level = strtolower( $m[1] );
	$text  = wp_strip_all_tags( $m[2] );
	$id    = 'toc-' . ( $i + 1 );
	$toc_items[] = [ 'level' => $level, 'text' => $text, 'id' => $id ];
}
// 本文に id 属性を付与
$toc_index = 0;
$content_with_ids = preg_replace_callback(
	'/<(h[23])([^>]*)>/i',
	function( $matches ) use ( &$toc_index, $toc_items ) {
		if ( ! isset( $toc_items[ $toc_index ] ) ) return $matches[0];
		$id = $toc_items[ $toc_index ]['id'];
		$toc_index++;
		return '<' . $matches[1] . $matches[2] . ' id="' . esc_attr( $id ) . '">';
	},
	$content_html
);

// 新着情報用クエリ（自分以外の最新5件）
$recent_query = new WP_Query( [
	'post_type'      => CPT_COLUMN,
	'posts_per_page' => 5,
	'post__not_in'   => [ get_the_ID() ],
	'orderby'        => 'date',
	'order'          => 'DESC',
	'no_found_rows'  => true,
] );

// 関連コラム（同カテゴリ最新3件）
$related_args = [
	'post_type'      => CPT_COLUMN,
	'posts_per_page' => 3,
	'post__not_in'   => [ get_the_ID() ],
	'orderby'        => 'date',
	'order'          => 'DESC',
	'no_found_rows'  => true,
];
if ( $cats && ! is_wp_error( $cats ) ) {
	$related_args['tax_query'] = [ [
		'taxonomy' => TAX_COLUMN_CAT,
		'field'    => 'term_id',
		'terms'    => wp_list_pluck( $cats, 'term_id' ),
	] ];
}
$related_query = new WP_Query( $related_args );
?>

<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>

<!-- ─── ページヒーロー ── -->
<div class="p-column-single__hero">
	<div class="p-column-single__hero-inner">
		<h1 class="p-column-single__hero-title">七ぶらコラム</h1>
		<p class="p-column-single__hero-sub">七間町の物語を絵師、住人、訪問者の目線で紹介</p>
	</div>
	<!-- /.p-column-single__hero-inner -->
</div>
<!-- /.p-column-single__hero -->

<!-- ─── 本文（2カラム） ── -->
<section class="p-column-single c-article">
	<div class="p-column-single__inner">
		<div class="c-article__layout">

			<!-- 左：記事本文 -->
			<article class="p-column-single__article c-article__main">

				<?php if ( $thumb ) : ?>
				<div class="c-article__hero">
					<img class="u-img-cover" src="<?php echo esc_url( $thumb ); ?>" alt="<?php the_title_attribute(); ?>" loading="eager">
				</div>
				<?php endif; ?>

				<?php if ( ( $cats && ! is_wp_error( $cats ) ) || ( $tags && ! is_wp_error( $tags ) ) ) : ?>
				<ul class="c-article__cats">
					<?php if ( $cats && ! is_wp_error( $cats ) ) : foreach ( $cats as $c ) : ?>
					<li><span class="c-tag c-tag--cat"><?php echo esc_html( $c->name ); ?></span></li>
					<?php endforeach; endif; ?>
					<?php if ( $tags && ! is_wp_error( $tags ) ) : foreach ( $tags as $t ) : ?>
					<li><span class="c-tag c-tag--tag"><?php echo esc_html( $t->name ); ?></span></li>
					<?php endforeach; endif; ?>
				</ul>
				<?php endif; ?>

				<div class="c-article__title-row">
					<h2 class="c-article__title"><?php the_title(); ?></h2>
				</div>

				<?php if ( has_excerpt() ) : ?>
				<p class="p-column-single__subtitle"><?php echo esc_html( get_the_excerpt() ); ?></p>
				<?php endif; ?>

				<div class="p-column-single__meta">
					<span class="p-column-single__author">
						<svg class="p-column-single__meta-icon" aria-hidden="true" focusable="false"><use href="#icon-person"></use></svg>
						<?php echo esc_html( $author ); ?>
					</span>
					<span class="p-column-single__date">
						<?php echo esc_html( get_the_date( 'Y年n月j日' ) ); ?>
					</span>
					<span class="p-column-single__read">
						<?php echo esc_html( $read_min ); ?>分で読める
					</span>
				</div>

				<?php
				// リードボックス（本文の最初の段落をリードとして表示）
				$first_p = '';
				if ( preg_match( '/<p>(.+?)<\/p>/s', $content_with_ids, $fp ) ) {
					$first_p = $fp[1];
				}
				?>
				<?php if ( $first_p ) : ?>
				<div class="c-article__lead p-column-single__lead">
					<p><?php echo wp_kses_post( $first_p ); ?></p>
				</div>
				<?php endif; ?>

				<?php if ( ! empty( $toc_items ) ) : ?>
				<nav class="c-article__toc js-toc" aria-label="目次">
					<button class="c-article__toc-toggle js-toc-toggle" type="button" aria-expanded="true">
						<span class="c-article__toc-label">目次</span>
						<span class="c-article__toc-state">閉じる</span>
					</button>
					<ol class="c-article__toc-list">
						<?php $h2_count = 0; foreach ( $toc_items as $item ) : if ( $item['level'] === 'h2' ) $h2_count++; ?>
						<li class="c-article__toc-item c-article__toc-item--<?php echo esc_attr( $item['level'] ); ?>">
							<a href="#<?php echo esc_attr( $item['id'] ); ?>">
								<?php if ( $item['level'] === 'h2' ) : ?>
								<span class="c-article__toc-num"><?php echo esc_html( $h2_count ); ?>.</span>
								<?php else : ?>
								<span class="c-article__toc-bullet" aria-hidden="true">─</span>
								<?php endif; ?>
								<span class="c-article__toc-text"><?php echo esc_html( $item['text'] ); ?></span>
							</a>
						</li>
						<?php endforeach; ?>
					</ol>
				</nav>
				<?php endif; ?>

				<div class="c-article__body">
					<?php echo $content_with_ids; ?>
				</div>

				<!-- 著者プロフィール -->
				<aside class="c-article__author p-column-single__bio" aria-label="この記事を書いた人">
					<p class="c-article__author-label p-column-single__bio-label">この記事を書いた人</p>
					<div class="c-article__author-body p-column-single__bio-body">
						<span class="c-article__author-avatar">
							<img class="u-img-cover" src="<?php echo esc_url( $avatar ); ?>" alt="" aria-hidden="true" width="80" height="80" loading="lazy">
						</span>
						<div class="c-article__author-info p-column-single__bio-text">
							<p class="c-article__author-name p-column-single__bio-name"><?php echo esc_html( $author ); ?></p>
							<?php if ( $bio ) : ?>
							<p class="c-article__author-bio p-column-single__bio-desc"><?php echo esc_html( $bio ); ?></p>
							<?php endif; ?>
						</div>
					</div>
				</aside>
				<!-- /.c-article__author -->

				<!-- 関連コラム -->
				<?php if ( $related_query->have_posts() ) : ?>
				<section class="p-column-single__related" aria-labelledby="column-related-title">
					<h3 class="p-column-single__related-title" id="column-related-title">関連コラム</h3>
					<div class="p-column-single__related-list">
						<?php while ( $related_query->have_posts() ) : $related_query->the_post();
							$rthumb = sc_thumbnail_url( get_the_ID(), 'medium' );
							$rcats  = get_the_terms( get_the_ID(), TAX_COLUMN_CAT );
							$rcat   = ( $rcats && ! is_wp_error( $rcats ) ) ? $rcats[0] : null;
						?>
						<a class="p-column-single__related-card" href="<?php the_permalink(); ?>">
							<div class="p-column-single__related-thumb">
								<?php if ( $rthumb ) : ?>
								<img class="u-img-cover" src="<?php echo esc_url( $rthumb ); ?>" alt="" aria-hidden="true" loading="lazy">
								<?php endif; ?>
							</div>
							<div class="p-column-single__related-body">
								<?php if ( $rcat ) : ?>
								<span class="p-column-single__related-cat"><?php echo esc_html( $rcat->name ); ?></span>
								<?php endif; ?>
								<p class="p-column-single__related-title-card"><?php the_title(); ?></p>
								<p class="p-column-single__related-date"><?php echo esc_html( get_the_date( 'Y年n月j日' ) ); ?></p>
							</div>
						</a>
						<?php endwhile; wp_reset_postdata(); ?>
					</div>
				</section>
				<?php endif; ?>

			</article>
			<!-- /.p-column-single__article -->

			<!-- 右：サイドバー -->
			<?php get_template_part( 'template-parts/components/post-sidebar', null, [
				'post_type'  => CPT_COLUMN,
				'taxonomy'   => TAX_COLUMN_CAT,
				'current_id' => get_the_ID(),
				'toc_items'  => $toc_items,
			] ); ?>

		</div>
		<!-- /.c-article__layout -->
	</div>
	<!-- /.p-column-single__inner -->
</section>
<!-- /.p-column-single -->

<?php get_footer(); ?>
