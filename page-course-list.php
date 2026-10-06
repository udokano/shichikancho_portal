<?php
/**
 * Template Name: 散策コース一覧
 * 散策コース（walk_course CPT）の全件一覧
 *
 * walk_course はアーカイブを持たないため、この固定ページが一覧の役割を担う。
 * /walk/ の一覧は絞り込み UI 付きの企画ページなので、こちらは全件を素直に並べる。
 */
get_header();

$courses = new WP_Query( [
	'post_type'      => CPT_WALK,
	'post_status'    => 'publish',
	'posts_per_page' => 12,
	'paged'          => max( 1, (int) get_query_var( 'paged' ) ),
	'orderby'        => 'menu_order date',
	'order'          => 'ASC',
] );
?>

<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>

<article class="p-course-list">

	<?php
	get_template_part( 'template-parts/components/page-hero', null, [
		'title' => '散策コース一覧',
		'sub'   => '七間町とその周辺をめぐる、おすすめの散策コース。',
	] );
	?>

	<section class="p-course-list__main" aria-labelledby="course-list-title">
		<div class="p-course-list__inner">
			<h2 class="p-course-list__title" id="course-list-title">
				すべてのコース
				<?php if ( $courses->found_posts ) : ?>
				<span class="p-course-list__count"><?php echo esc_html( (string) $courses->found_posts ); ?>件</span>
				<?php endif; ?>
			</h2>

			<?php if ( $courses->have_posts() ) : ?>
			<ul class="p-course-list__grid">
				<?php while ( $courses->have_posts() ) : $courses->the_post();
					$cid      = get_the_ID();
					$cthumb   = sc_thumbnail_url( $cid, 'medium_large' );
					$cdur     = (int) get_field( 'walk_duration', $cid );
					$cdist    = get_field( 'walk_distance', $cid );
					$cdesc    = get_field( 'walk_description', $cid );
					$cspots   = get_field( 'walk_spots', $cid ) ?: [];
					$area_arr = get_the_terms( $cid, TAX_AREA );
					$carea    = ( $area_arr && ! is_wp_error( $area_arr ) ) ? $area_arr[0] : null;
				?>
				<li class="p-course-list__item">
					<a class="p-course-list__card" href="<?php the_permalink(); ?>">
						<div class="p-course-list__card-img">
							<picture class="u-picture-fill">
								<img class="u-img-cover" src="<?php echo esc_url( $cthumb ); ?>" alt="" aria-hidden="true" loading="lazy" width="400" height="300">
							</picture>
							<?php if ( $carea ) : ?>
							<span class="c-tag c-tag--sm p-course-list__card-area"><?php echo esc_html( $carea->name ); ?></span>
							<?php endif; ?>
						</div>
						<!-- /.p-course-list__card-img -->

						<div class="p-course-list__card-body">
							<h3 class="p-course-list__card-name"><?php the_title(); ?></h3>
							<?php if ( $cdesc ) : ?>
							<p class="p-course-list__card-desc"><?php echo esc_html( wp_trim_words( wp_strip_all_tags( $cdesc ), 60, '…' ) ); ?></p>
							<?php endif; ?>

							<dl class="p-course-list__card-meta">
								<?php if ( $cdur ) : ?>
								<div class="p-course-list__card-meta-row">
									<dt class="p-course-list__card-meta-term">
										<svg class="p-course-list__card-meta-icon" aria-hidden="true" focusable="false" width="18" height="18"><use href="#icon-clock"></use></svg>
										<span class="u-sr-only">所要時間</span>
									</dt>
									<dd class="p-course-list__card-meta-desc"><?php echo esc_html( sc_walk_duration_label( $cid, $cdur ) ); ?></dd>
								</div>
								<?php endif; ?>
								<?php if ( $cdist ) : ?>
								<div class="p-course-list__card-meta-row">
									<dt class="p-course-list__card-meta-term">
										<svg class="p-course-list__card-meta-icon" aria-hidden="true" focusable="false" width="18" height="18"><use href="#icon-ruler"></use></svg>
										<span class="u-sr-only">距離</span>
									</dt>
									<dd class="p-course-list__card-meta-desc"><?php echo esc_html( $cdist ); ?></dd>
								</div>
								<?php endif; ?>
								<?php if ( $cspots ) : ?>
								<div class="p-course-list__card-meta-row">
									<dt class="p-course-list__card-meta-term">
										<svg class="p-course-list__card-meta-icon" aria-hidden="true" focusable="false" width="18" height="18"><use href="#icon-map-pin"></use></svg>
										<span class="u-sr-only">スポット数</span>
									</dt>
									<dd class="p-course-list__card-meta-desc"><?php echo esc_html( (string) count( $cspots ) ); ?>スポット</dd>
								</div>
								<?php endif; ?>
							</dl>
							<!-- /.p-course-list__card-meta -->
						</div>
						<!-- /.p-course-list__card-body -->
					</a>
				</li>
				<?php endwhile; ?>
			</ul>
			<!-- /.p-course-list__grid -->

			<?php if ( $courses->max_num_pages > 1 ) : ?>
			<nav class="c-pagination" aria-label="ページ送り">
				<?php
				echo paginate_links( [
					'total'              => $courses->max_num_pages,
					'current'            => max( 1, (int) get_query_var( 'paged' ) ),
					'mid_size'           => 1,
					'end_size'           => 1,
					'prev_text'          => '‹',
					'next_text'          => '›',
					'before_page_number' => '<span class="u-sr-only">ページ </span>',
				] );
				?>
			</nav>
			<?php endif; ?>

			<?php wp_reset_postdata(); ?>

			<?php else : ?>
			<p class="p-course-list__empty">現在公開中の散策コースはありません。</p>
			<?php endif; ?>
		</div>
		<!-- /.p-course-list__inner -->
	</section>
	<!-- /.p-course-list__main -->

</article>
<!-- /.p-course-list -->

<?php get_footer(); ?>
