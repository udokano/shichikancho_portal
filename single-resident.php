<?php
/** お隣さんの話 個別ページ */
get_header();

if ( ! have_posts() ) { get_footer(); exit; }
the_post();

$pid        = get_the_ID();
$name       = get_field( 'resident_name', $pid ) ?: get_the_title();
$age        = get_field( 'resident_age', $pid );
$occupation = get_field( 'resident_occupation', $pid );
$years      = get_field( 'resident_years', $pid );
$portrait   = get_field( 'resident_portrait', $pid );
$quote      = get_field( 'resident_quote', $pid );
$fav_spots  = get_field( 'resident_favorite_spot', $pid );

// ポートレート URL
if ( $portrait && is_array( $portrait ) ) {
	$portrait_url_lg  = $portrait['sizes']['large']     ?? $portrait['url'];
	$portrait_url_sm  = $portrait['sizes']['thumbnail'] ?? $portrait['url'];
} else {
	$portrait_url_lg  = get_the_post_thumbnail_url( $pid, 'large' )     ?: '';
	$portrait_url_sm  = get_the_post_thumbnail_url( $pid, 'thumbnail' ) ?: '';
}

// 本文のh2/h3を抽出して目次データと id 付き本文を生成
$toc_items   = [];
$content_out = preg_replace_callback(
	'/<(h[23])([^>]*)>(.*?)<\/h[23]>/si',
	function( $m ) use ( &$toc_items ) {
		$level = $m[1];
		$text  = wp_strip_all_tags( $m[3] );
		$id    = 'toc-' . sanitize_title( $text );
		$toc_items[] = [ 'level' => $level, 'text' => $text, 'id' => $id ];
		if ( strpos( $m[2], 'id=' ) === false ) {
			return '<' . $level . $m[2] . ' id="' . esc_attr( $id ) . '">' . $m[3] . '</' . $level . '>';
		}
		return $m[0];
	},
	apply_filters( 'the_content', get_the_content() )
);

// 関連：同 CPT の最新3件（自分を除く）
$related = new WP_Query( [
	'post_type'           => CPT_RESIDENT,
	'posts_per_page'      => 3,
	'post__not_in'        => [ $pid ],
	'orderby'             => 'date',
	'order'               => 'DESC',
	'no_found_rows'       => true,
	'ignore_sticky_posts' => 1,
] );
?>

<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>

<article class="p-resident-single" itemscope itemtype="https://schema.org/Person">

	<!-- ─── ページヘッダー ── -->
	<div class="p-resident-single__header">
		<div class="p-resident-single__header-inner">
			<span class="p-resident-single__header-label">お隣さんの話</span>
			<h1 class="p-resident-single__header-title"><?php the_title(); ?></h1>
		</div>
		<!-- /.p-resident-single__header-inner -->
	</div>
	<!-- /.p-resident-single__header -->

	<!-- ─── 本文（2カラム） ── -->
	<section class="p-resident-single__body c-article">
		<div class="p-resident-single__body-inner">
			<div class="c-article__layout">

				<!-- メイン -->
				<div class="p-resident-single__main c-article__main">

					<!-- ポートレート画像 -->
					<?php if ( $portrait_url_lg ) : ?>
					<div class="p-resident-single__eyecatch">
						<img src="<?php echo esc_url( $portrait_url_lg ); ?>" alt="<?php echo esc_attr( $name ); ?>" loading="eager" width="1200" height="630">
					</div>
					<!-- /.p-resident-single__eyecatch -->
					<?php endif; ?>

					<!-- プロフィール帯 -->
					<div class="p-resident-single__profile">
						<div class="p-resident-single__profile-avatar" aria-hidden="true">
							<?php if ( $portrait_url_sm ) : ?>
							<img src="<?php echo esc_url( $portrait_url_sm ); ?>" alt="" aria-hidden="true" loading="lazy" width="48" height="48">
							<?php endif; ?>
						</div>
						<!-- /.p-resident-single__profile-avatar -->
						<div class="p-resident-single__profile-body">
							<span class="p-resident-single__profile-label">プロフィール</span>
							<span class="p-resident-single__profile-name" itemprop="name"><?php echo esc_html( $name ); ?></span>
							<?php
							$meta_parts = array_filter( [ $age, $occupation, $years ] );
							if ( $meta_parts ) : ?>
							<span class="p-resident-single__profile-meta"><?php echo esc_html( implode( '　', $meta_parts ) ); ?></span>
							<?php endif; ?>
						</div>
						<!-- /.p-resident-single__profile-body -->
						<div class="p-resident-single__profile-date">
							<time datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>"><?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?></time>
						</div>
						<!-- /.p-resident-single__profile-date -->
					</div>
					<!-- /.p-resident-single__profile -->

					<!-- 一言 -->
					<?php if ( $quote ) : ?>
					<blockquote class="p-resident-single__quote">「<?php echo esc_html( $quote ); ?>」</blockquote>
					<?php endif; ?>

					<!-- ブロックエディタ本文 -->
					<div class="c-article__content">
						<?php echo $content_out; // id付きh2/h3を含む本文 ?>
					</div>
					<!-- /.c-article__content -->

					<!-- お気に入りスポット -->
					<?php if ( $fav_spots && is_array( $fav_spots ) ) : ?>
					<section class="p-resident-single__spots" aria-labelledby="resident-spots-title">
						<h2 class="p-resident-single__spots-title" id="resident-spots-title"><?php echo esc_html( $name ); ?>さんのお気に入り</h2>
						<div class="p-resident-single__spots-grid">
							<?php foreach ( $fav_spots as $rid ) :
								$r_thumb = sc_thumbnail_url( $rid, 'medium' );
								$r_type  = get_post_type( $rid );
							?>
							<a class="p-resident-single__spot-card" href="<?php echo esc_url( get_permalink( $rid ) ); ?>">
								<div class="p-resident-single__spot-img">
									<?php if ( $r_thumb ) : ?>
									<img class="u-img-cover" src="<?php echo esc_url( $r_thumb ); ?>" alt="" aria-hidden="true" loading="lazy" width="400" height="300">
									<?php endif; ?>
								</div>
								<!-- /.p-resident-single__spot-img -->
								<div class="p-resident-single__spot-body">
									<span class="c-tag c-tag--sm c-tag--outline"><?php echo $r_type === 'spot' ? 'スポット' : 'お店'; ?></span>
									<h3 class="p-resident-single__spot-name"><?php echo esc_html( get_the_title( $rid ) ); ?></h3>
								</div>
								<!-- /.p-resident-single__spot-body -->
							</a>
							<?php endforeach; ?>
						</div>
						<!-- /.p-resident-single__spots-grid -->
					</section>
					<!-- /.p-resident-single__spots -->
					<?php endif; ?>

					<!-- 関連：他のお隣さん -->
					<?php if ( $related->have_posts() ) : ?>
					<section class="p-resident-single__related" aria-labelledby="resident-related-title">
						<h2 class="p-resident-single__related-title" id="resident-related-title">他のお隣さん</h2>
						<div class="p-resident-single__related-grid">
							<?php while ( $related->have_posts() ) : $related->the_post();
								$r_id      = get_the_ID();
								$r_name    = get_field( 'resident_name', $r_id ) ?: get_the_title();
								$r_occ     = get_field( 'resident_occupation', $r_id );
								$r_portrait = get_field( 'resident_portrait', $r_id );
								if ( $r_portrait && is_array( $r_portrait ) ) {
									$r_thumb = $r_portrait['sizes']['medium_large'] ?? $r_portrait['url'];
								} else {
									$r_thumb = get_the_post_thumbnail_url( $r_id, 'medium_large' ) ?: '';
								}
							?>
							<a class="p-resident-card" href="<?php the_permalink(); ?>">
								<div class="p-resident-card__img">
									<?php if ( $r_thumb ) : ?>
									<picture class="u-picture-fill">
										<img class="u-img-cover p-resident-card__img-inner" src="<?php echo esc_url( $r_thumb ); ?>" alt="" aria-hidden="true" loading="lazy" width="400" height="400">
									</picture>
									<?php else : ?>
									<div class="p-resident-card__img-placeholder" aria-hidden="true"></div>
									<?php endif; ?>
								</div>
								<!-- /.p-resident-card__img -->
								<div class="p-resident-card__body">
									<?php if ( $r_occ ) : ?>
									<p class="p-resident-card__meta"><?php echo esc_html( $r_occ ); ?></p>
									<?php endif; ?>
									<h3 class="p-resident-card__name"><?php echo esc_html( $r_name ); ?></h3>
								</div>
								<!-- /.p-resident-card__body -->
							</a>
							<?php endwhile; wp_reset_postdata(); ?>
						</div>
						<!-- /.p-resident-single__related-grid -->
					</section>
					<!-- /.p-resident-single__related -->
					<?php endif; ?>

				</div>
				<!-- /.p-resident-single__main -->

				<!-- サイドバー -->
				<?php get_template_part( 'template-parts/components/post-sidebar', null, [
					'post_type'     => CPT_RESIDENT,
					'taxonomy'      => '',
					'tag_taxonomy'  => '',
					'current_id'    => $pid,
					'recent_label'  => '新着のお隣さん',
					'archive_url'   => get_post_type_archive_link( CPT_RESIDENT ),
					'toc_items'     => $toc_items,
				] ); ?>

			</div>
			<!-- /.c-article__layout -->
		</div>
		<!-- /.p-resident-single__body-inner -->
	</section>
	<!-- /.p-resident-single__body -->

	<!-- ─── Join us CTA ── -->
	<section class="p-resident-single__join">
		<div class="p-resident-single__join-inner">
			<p class="p-resident-single__join-sub">Join us</p>
			<h2 class="p-resident-single__join-title">この企画に参加しませんか？</h2>
			<p class="p-resident-single__join-text">七間町に暮らすあなたの物語を聞かせてください。あなた自身の経験や想いを、同じ町に生きる人々と分かち合いましょう。</p>
			<a class="c-btn c-btn--outline-white" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">掲載依頼はこちら</a>
		</div>
		<!-- /.p-resident-single__join-inner -->
	</section>
	<!-- /.p-resident-single__join -->

</article>
<!-- /.p-resident-single -->

<?php get_footer(); ?>
