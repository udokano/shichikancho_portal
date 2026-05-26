<?php
/**
 * お知らせ一覧アーカイブ（/info）
 */

$current_page = max( 1, get_query_var( 'paged', 1 ) );

$news_query = new WP_Query( [
	'post_type'      => CPT_NEWS,
	'posts_per_page' => 12,
	'paged'          => $current_page,
	'orderby'        => 'date',
	'order'          => 'DESC',
] );
$base_url = get_post_type_archive_link( CPT_NEWS );

get_header();
?>
<main id="main-content" class="p-info-archive">

	<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>

	<?php
	get_template_part( 'template-parts/components/page-hero', null, [
		'title' => 'インフォメーション',
		'sub'   => '七間町の最新情報をお届けします。',
	] );
	?>

	<section class="p-info-archive__main">
		<div class="p-info-archive__container">

			<!-- 記事リスト -->
			<?php if ( $news_query->have_posts() ) : ?>
			<ul class="p-info-archive__list">
				<?php while ( $news_query->have_posts() ) : $news_query->the_post();
					$pid   = get_the_ID();
					$thumb = sc_thumbnail_url( $pid, 'medium_large' );
					$cats  = get_the_terms( $pid, TAX_NEWS_CAT );
					$cat   = ( $cats && ! is_wp_error( $cats ) ) ? $cats[0] : null;
				?>
				<li class="p-info-archive__item">
					<a class="p-info-archive__row" href="<?php the_permalink(); ?>">
						<?php if ( $thumb ) : ?>
						<div class="p-info-archive__thumb">
							<picture class="u-picture-fill">
								<img class="u-img-cover--transition" src="<?php echo esc_url( $thumb ); ?>" alt="" aria-hidden="true" loading="lazy" width="120" height="80">
							</picture>
						</div>
						<?php endif; ?>
						<div class="p-info-archive__body">
							<div class="p-info-archive__meta">
								<time class="p-info-archive__date" datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>"><?php echo esc_html( get_the_date( 'Y/m/d' ) ); ?></time>
								<?php if ( $cat ) : ?>
								<span class="p-info-archive__cat"><?php echo esc_html( $cat->name ); ?></span>
								<?php endif; ?>
							</div>
							<span class="p-info-archive__title"><?php the_title(); ?></span>
						</div>
					</a>
				</li>
				<!-- /.p-info-archive__item -->
				<?php endwhile; wp_reset_postdata(); ?>
			</ul>
			<!-- /.p-info-archive__list -->

			<?php
			$pagination = paginate_links( [
				'total'     => $news_query->max_num_pages,
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
			<p class="p-info-archive__empty">インフォメーションが見つかりませんでした。</p>
			<?php endif; ?>

		</div>
		<!-- /.p-info-archive__container -->
	</section>
	<!-- /.p-info-archive__main -->

</main>
<!-- /#main-content -->

<?php get_footer(); ?>
