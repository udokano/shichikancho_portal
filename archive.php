<?php
/**
 * 汎用アーカイブ（専用テンプレートの無いターム一覧・投稿タイプ一覧）
 *
 * 以前は index.php が記事ごとに h1 を出していた。見出しを1つにし、インフォメーション一覧の行レイアウトを流用する
 */

global $wp_query;

$current_page = max( 1, get_query_var( 'paged', 1 ) );
$queried      = get_queried_object();

// 見出しと補足文
if ( $queried instanceof WP_Term ) {
	$archive_title = $queried->name;
	$archive_sub   = trim( wp_strip_all_tags( term_description( $queried ) ) );
} elseif ( is_post_type_archive() ) {
	$archive_title = post_type_archive_title( '', false );
	$archive_sub   = '';
} else {
	$archive_title = wp_strip_all_tags( get_the_archive_title() );
	$archive_sub   = '';
}
if ( '' === $archive_sub ) {
	$archive_sub = sprintf( '「%s」の一覧です。', $archive_title );
}

get_header();
?>
<div class="p-info-archive">

	<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>

	<?php
	get_template_part( 'template-parts/components/page-hero', null, [
		'title' => $archive_title,
		'sub'   => $archive_sub,
	] );
	?>

	<section class="p-info-archive__main">
		<div class="p-info-archive__container">

			<?php if ( have_posts() ) : ?>
			<ul class="p-info-archive__list">
				<?php while ( have_posts() ) : the_post();
					$pid   = get_the_ID();
					$thumb = sc_thumbnail_url( $pid, 'medium_large' );
					$type  = get_post_type_object( get_post_type( $pid ) );
				?>
				<li class="p-info-archive__item">
					<a class="p-info-archive__row" href="<?php the_permalink(); ?>">
						<?php if ( $thumb ) : ?>
						<div class="p-info-archive__thumb">
							<picture class="u-picture-fill">
								<img class="u-img-cover--transition" src="<?php echo esc_url( $thumb ); ?>" alt="" aria-hidden="true" loading="lazy" width="120" height="80">
							</picture>
						</div>
						<!-- /.p-info-archive__thumb -->
						<?php endif; ?>
						<div class="p-info-archive__body">
							<div class="p-info-archive__meta">
								<time class="p-info-archive__date" datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>"><?php echo esc_html( get_the_date( 'Y/m/d' ) ); ?></time>
								<?php if ( $type ) : ?>
								<span class="p-info-archive__cat"><?php echo esc_html( $type->labels->singular_name ); ?></span>
								<?php endif; ?>
							</div>
							<!-- /.p-info-archive__meta -->
							<span class="p-info-archive__title"><?php the_title(); ?></span>
						</div>
						<!-- /.p-info-archive__body -->
					</a>
				</li>
				<?php endwhile; ?>
			</ul>

			<?php
			$pagination = paginate_links( [
				'total'     => $wp_query->max_num_pages,
				'current'   => $current_page,
				'prev_text' => '< 前へ',
				'next_text' => '次へ >',
				'type'      => 'array',
				'end_size'  => 1,
				'mid_size'  => 2,
			] );
			if ( $pagination ) :
			?>
			<nav class="p-info-archive__pagination" aria-label="ページネーション">
				<ul class="p-info-archive__page-list">
					<?php foreach ( $pagination as $link ) : ?>
					<li class="p-info-archive__page-item"><?php echo wp_kses_post( $link ); ?></li>
					<?php endforeach; ?>
				</ul>
			</nav>
			<?php endif; ?>

			<?php else : ?>
			<p class="p-info-archive__empty">該当する記事が見つかりませんでした。</p>
			<?php endif; ?>

		</div>
		<!-- /.p-info-archive__container -->
	</section>

</div>
<!-- /.p-info-archive -->
<?php
get_footer();
