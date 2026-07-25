<?php
/** お知らせ単体記事ページ（c-article レイアウト / コラムに準拠） */
get_header();

if ( ! have_posts() ) {
	get_footer();
	return;
}
the_post();

// アイキャッチ未設定時はヒーロー画像を出さない（No Image は表示しない）
$thumb    = get_the_post_thumbnail_url( get_the_ID(), 'large' ) ?: '';
$author   = get_the_author();

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

// 新着お知らせ（自分以外の最新5件）
$recent_query = new WP_Query( [
	'post_type'      => CPT_NEWS,
	'posts_per_page' => 5,
	'post__not_in'   => [ get_the_ID() ],
	'orderby'        => 'date',
	'order'          => 'DESC',
	'no_found_rows'  => true,
] );
?>

<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>

<!-- ─── ページヒーロー ── -->
<div class="p-info-single__hero">
	<div class="p-info-single__hero-inner">
		<h1 class="p-info-single__hero-title">インフォメーション</h1>
		<p class="p-info-single__hero-sub">七間町商店街からの最新情報</p>
	</div>
	<!-- /.p-info-single__hero-inner -->
</div>
<!-- /.p-info-single__hero -->

<!-- ─── 本文（2カラム） ── -->
<section class="p-info-single c-article">
	<div class="p-info-single__inner">
		<div class="c-article__layout">

			<!-- 左：記事本文 -->
			<article class="p-info-single__article c-article__main">

				<?php if ( $thumb ) : ?>
				<div class="c-article__hero">
					<img class="u-img-cover" src="<?php echo esc_url( $thumb ); ?>" alt="<?php the_title_attribute(); ?>" loading="eager">
				</div>
				<?php endif; ?>

				<div class="c-article__title-row">
					<h2 class="c-article__title"><?php the_title(); ?></h2>
				</div>

				<div class="p-info-single__meta">
					<span class="p-info-single__meta-item">
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
						<time datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>"><?php echo esc_html( get_the_date( 'Y年n月j日' ) ); ?></time>
					</span>
					<span class="p-info-single__meta-item">
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
						<?php echo esc_html( $author ); ?>
					</span>
				</div>

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

			</article>
			<!-- /.p-info-single__article -->

			<!-- 右：サイドバー -->
			<?php get_template_part( 'template-parts/components/post-sidebar', null, [
				'post_type'    => CPT_NEWS,
				'taxonomy'     => TAX_NEWS_CAT,
				'tag_taxonomy' => '',
				'current_id'   => get_the_ID(),
				'toc_items'    => $toc_items,
				'archive_url'  => get_post_type_archive_link( CPT_NEWS ),
				'recent_label' => '最新のインフォメーション',
				'cat_label'    => 'インフォメーションのカテゴリ',
			] ); ?>

		</div>
		<!-- /.c-article__layout -->
	</div>
	<!-- /.p-info-single__inner -->
</section>
<!-- /.p-info-single -->

<?php get_footer(); ?>
