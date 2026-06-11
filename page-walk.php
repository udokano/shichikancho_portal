<?php
/**
 * Template Name: 町をめぐる
 * 町をめぐるページ（walk_course CPT 駆動）
 */
get_header();

// クエリパラメータ（area/scene は複数選択対応）
// 'area' は taxonomy query_var と衝突するため sh_area を使用
$filter_areas  = isset( $_GET['sh_area'] ) ? array_filter( array_map( 'sanitize_text_field', (array) $_GET['sh_area'] ) ) : [];
$filter_scenes = isset( $_GET['scene'] )   ? array_filter( array_map( 'sanitize_text_field', (array) $_GET['scene'] ) )   : [];
$filter_dur    = isset( $_GET['dur'] )     ? (int) $_GET['dur'] : 0;

// コース取得
$args = [
	'post_type'      => CPT_WALK,
	'posts_per_page' => 12,
	'paged'          => get_query_var( 'paged', 1 ),
	'orderby'        => 'date',
	'order'          => 'DESC',
];
// 日本語スラッグは WP_Tax_Query で sanitize_title されるため term_id に変換
// sc_get_term_id_by_slug() は $wpdb 直クエリで日本語スラッグも正確に引く
$tax_query = [];
if ( $filter_areas ) {
	$tids = [];
	foreach ( $filter_areas as $s ) { $tid = sc_get_term_id_by_slug( $s, TAX_AREA ); if ( $tid ) $tids[] = $tid; }
	if ( $tids ) $tax_query[] = [ 'taxonomy' => TAX_AREA, 'field' => 'term_id', 'terms' => $tids, 'operator' => 'IN' ];
}
if ( $filter_scenes ) {
	$tids = [];
	foreach ( $filter_scenes as $s ) { $tid = sc_get_term_id_by_slug( $s, TAX_WALK_SCENE ); if ( $tid ) $tids[] = $tid; }
	if ( $tids ) $tax_query[] = [ 'taxonomy' => TAX_WALK_SCENE, 'field' => 'term_id', 'terms' => $tids, 'operator' => 'IN' ];
}
if ( $tax_query ) {
	$args['tax_query'] = count( $tax_query ) > 1 ? array_merge( [ 'relation' => 'AND' ], $tax_query ) : $tax_query;
}
if ( $filter_dur > 0 ) {
	$args['meta_query'] = [
		[ 'key' => 'walk_duration', 'value' => $filter_dur, 'compare' => '<=', 'type' => 'NUMERIC' ],
	];
}
$walks = new WP_Query( $args );

$areas  = get_terms( [ 'taxonomy' => TAX_AREA, 'hide_empty' => false ] );
$scenes = get_terms( [ 'taxonomy' => TAX_WALK_SCENE, 'hide_empty' => false ] );
$durations = [
	[ 'label' => '〜1時間',  'max_minutes' => 60  ],
	[ 'label' => '1〜2時間', 'max_minutes' => 120 ],
	[ 'label' => '2時間以上', 'max_minutes' => 999 ],
];
?>

<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>

<article class="p-walk">

	<?php
	get_template_part( 'template-parts/components/page-hero', null, [
		'title' => '町をめぐる',
		'sub'   => '七間町とその周辺を歩いて楽しむおすすめルート。',
	] );
	?>

	<!-- ─── フォトコンテストバナー ── -->
	<section class="p-walk__contest" aria-label="フォトコンテスト" style="background-image:url('<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/common/hero-walk.jpg')">
		<div class="p-walk__contest-inner">
			<span class="p-walk__contest-badge">開催中</span>
			<h2 class="p-walk__contest-title">七間町フォトコンテスト</h2>
			<p class="p-walk__contest-text">あなたが見つけた七間町の魅力を写真で教えてください</p>
			<a class="p-walk__contest-link" href="<?php echo esc_url( home_url( '/photo-contest/' ) ); ?>">
				詳細を見る
				<svg aria-hidden="true" focusable="false" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><use href="#icon-chevron-right"></use></svg>
			</a>
		</div>
		<!-- /.p-walk__contest-inner -->
	</section>
	<!-- /.p-walk__contest -->

	<!-- ─── 入賞作品（CPT: photo_award / ACF: award_rank, award_area）── -->
	<?php
	// 受賞順ソート用の優先度マップ
	$rank_order = [ '最優秀賞' => 1, '優秀賞' => 2, '特別賞' => 3, '佳作' => 4 ];
	$rank_mod   = [ '最優秀賞' => 'gold', '優秀賞' => 'silver', '特別賞' => 'bronze' ];

	$award_q = new WP_Query( [
		'post_type'      => CPT_PHOTO_AWARD,
		'posts_per_page' => 8,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'no_found_rows'  => true,
	] );
	if ( $award_q->have_posts() ) :
	?>
	<section class="p-walk__award" aria-labelledby="walk-award-title">
		<div class="p-walk__award-inner">
			<h2 class="p-walk__award-title" id="walk-award-title">
				<svg class="p-walk__award-title-icon" aria-hidden="true" focusable="false" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><use href="#icon-camera"></use></svg>
				入賞作品
			</h2>
			<div class="p-walk__award-grid">
				<?php while ( $award_q->have_posts() ) : $award_q->the_post();
					$aid      = get_the_ID();
					$a_rank   = get_field( 'award_rank', $aid ) ?: '';
					$a_area   = get_field( 'award_area', $aid ) ?: '';
					$a_thumb  = get_the_post_thumbnail_url( $aid, 'large' )
					            ?: get_template_directory_uri() . '/assets/images/common/no-image.jpg';
					$a_mod    = isset( $rank_mod[ $a_rank ] ) ? ' p-walk__award-card-rank--' . $rank_mod[ $a_rank ] : '';
				?>
				<article class="p-walk__award-card">
					<div class="p-walk__award-card-img" aria-hidden="true">
						<img class="u-img-cover" src="<?php echo esc_url( $a_thumb ); ?>" alt="" aria-hidden="true" loading="lazy" width="400" height="500">
					</div>
					<!-- /.p-walk__award-card-img -->
					<?php if ( $a_rank ) : ?>
					<span class="p-walk__award-card-rank<?php echo esc_attr( $a_mod ); ?>"><?php echo esc_html( $a_rank ); ?></span>
					<?php endif; ?>
					<div class="p-walk__award-card-caption">
						<p class="p-walk__award-card-name">
							<?php if ( $a_rank ) : ?><span class="p-walk__award-card-name-rank"><?php echo esc_html( $a_rank ); ?>：</span><?php endif; ?>
							<?php the_title(); ?>
						</p>
					</div>
				</article>
				<!-- /.p-walk__award-card -->
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
			<!-- /.p-walk__award-grid -->
		</div>
		<!-- /.p-walk__award-inner -->
	</section>
	<!-- /.p-walk__award -->
	<?php endif; ?>

	<!-- ─── コース検索 + 一覧 ── -->
	<section class="p-walk__main" aria-labelledby="walk-courses-title">
		<div class="p-walk__main-inner">

			<!-- フィルターサイドバー（c-filter-sidebar 共通） -->
			<aside class="c-filter-sidebar" aria-label="コース絞り込み">
				<form id="js-walk-filter" class="c-filter-sidebar__filter" method="get" action="<?php echo esc_url( get_permalink() ); ?>">

					<div class="c-filter-sidebar__head">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
						<h2 class="c-filter-sidebar__title">絞り込み検索</h2>
					</div>

					<?php if ( ! is_wp_error( $areas ) && $areas ) : ?>
					<div class="c-filter-sidebar__group">
						<p class="c-filter-sidebar__label">エリア</p>
						<div class="c-filter-sidebar__chips">
							<button type="button" class="c-filter-sidebar__chip js-chips-clear<?php echo ! $filter_areas ? ' is-active' : ''; ?>" data-group="sh_area">すべて</button>
							<?php foreach ( $areas as $a ) : ?>
							<label class="c-filter-sidebar__chip<?php echo in_array( $a->slug, $filter_areas, true ) ? ' is-active' : ''; ?>">
								<input type="checkbox" name="sh_area[]" value="<?php echo esc_attr( $a->slug ); ?>"<?php echo in_array( $a->slug, $filter_areas, true ) ? ' checked' : ''; ?> style="position:absolute;opacity:0;width:1px;height:1px;">
								<?php echo esc_html( $a->name ); ?>
							</label>
							<?php endforeach; ?>
						</div>
					</div>
					<?php endif; ?>

					<?php if ( $durations ) : ?>
					<div class="c-filter-sidebar__group">
						<p class="c-filter-sidebar__label">所要時間</p>
						<div class="c-filter-sidebar__chips">
							<button type="submit" name="dur" value="0" class="c-filter-sidebar__chip<?php echo ! $filter_dur ? ' is-active' : ''; ?>">すべて</button>
							<?php foreach ( $durations as $d ) : $val = (int) ( $d['max_minutes'] ?? 0 ); ?>
							<button type="submit" name="dur" value="<?php echo esc_attr( $val ); ?>" class="c-filter-sidebar__chip<?php echo $filter_dur === $val ? ' is-active' : ''; ?>">
								<?php echo esc_html( $d['label'] ?? '' ); ?>
							</button>
							<?php endforeach; ?>
						</div>
					</div>
					<?php endif; ?>

					<?php if ( ! is_wp_error( $scenes ) && $scenes ) : ?>
					<div class="c-filter-sidebar__group">
						<p class="c-filter-sidebar__label">シーン</p>
						<div class="c-filter-sidebar__chips">
							<button type="button" class="c-filter-sidebar__chip js-chips-clear<?php echo ! $filter_scenes ? ' is-active' : ''; ?>" data-group="scene">すべて</button>
							<?php foreach ( $scenes as $s ) : ?>
							<label class="c-filter-sidebar__chip<?php echo in_array( $s->slug, $filter_scenes, true ) ? ' is-active' : ''; ?>">
								<input type="checkbox" name="scene[]" value="<?php echo esc_attr( $s->slug ); ?>"<?php echo in_array( $s->slug, $filter_scenes, true ) ? ' checked' : ''; ?> style="position:absolute;opacity:0;width:1px;height:1px;">
								<?php echo esc_html( $s->name ); ?>
							</label>
							<?php endforeach; ?>
						</div>
					</div>
					<?php endif; ?>

					<div class="c-filter-sidebar__result">
						<span class="c-filter-sidebar__result-label">登録コース</span>
						<p class="c-filter-sidebar__result-value"><?php echo (int) $walks->found_posts; ?><span class="c-filter-sidebar__result-unit">件</span></p>
					</div>
				</form>
				<script>
				(function () {
					var form = document.getElementById('js-walk-filter');
					if (!form) return;
					form.querySelectorAll('input[type="checkbox"]').forEach(function (cb) {
						cb.addEventListener('change', function () {
							cb.closest('label').classList.toggle('is-active', cb.checked);
							form.submit();
						});
					});
					form.querySelectorAll('.js-chips-clear').forEach(function (btn) {
						btn.addEventListener('click', function () {
							var group = btn.dataset.group;
							form.querySelectorAll('input[name="' + group + '[]"]').forEach(function (cb) {
								cb.checked = false;
								cb.closest('label').classList.remove('is-active');
							});
							form.querySelectorAll('.js-chips-clear[data-group="' + group + '"]').forEach(function (b) { b.classList.add('is-active'); });
							form.submit();
						});
					});
				}());
				</script>
			</aside>
			<!-- /.c-filter-sidebar -->

			<!-- コース一覧 -->
			<div class="p-walk__courses">
				<?php if ( $walks->have_posts() ) : ?>
				<div class="p-walk__courses-grid">
					<?php while ( $walks->have_posts() ) : $walks->the_post();
						$wid       = get_the_ID();
						$wthumb    = sc_thumbnail_url( $wid, 'medium_large' );
						$wdur      = (int) get_post_meta( $wid, 'walk_duration', true );
						$wdist     = get_post_meta( $wid, 'walk_distance', true );
						$wdesc     = get_post_meta( $wid, 'walk_description', true );
						$warea_arr = get_the_terms( $wid, TAX_AREA );
						$warea     = ( $warea_arr && ! is_wp_error( $warea_arr ) ) ? $warea_arr[0] : null;
					?>
					<a class="p-walk__course-card" href="<?php the_permalink(); ?>">
						<div class="p-walk__course-card-img">
							<picture class="u-picture-fill">
								<img class="u-img-cover" src="<?php echo esc_url( $wthumb ); ?>" alt="" aria-hidden="true" loading="lazy" width="400" height="300">
							</picture>
							<?php if ( $warea ) : ?>
							<span class="c-tag c-tag--sm p-walk__course-card-area"><?php echo esc_html( $warea->name ); ?></span>
							<?php endif; ?>
						</div>
						<!-- /.p-walk__course-card-img -->
						<div class="p-walk__course-card-body">
							<h3 class="p-walk__course-card-name"><?php the_title(); ?></h3>
							<?php if ( $wdesc ) : ?>
							<p class="p-walk__course-card-desc"><?php echo wp_kses( $wdesc, [ 'br' => [] ] ); ?></p>
							<?php endif; ?>
							<dl class="p-walk__course-card-meta">
								<?php if ( $wdur ) : ?>
								<div>
									<dt>
										<svg aria-hidden="true" focusable="false" class="p-walk__course-card-meta-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><use href="#icon-clock"></use></svg>
										<span class="u-sr-only">所要時間</span>
									</dt>
									<dd><?php echo esc_html( $wdur ); ?>分</dd>
								</div>
								<?php endif; ?>
								<?php if ( $wdist ) : ?>
								<div>
									<dt>
										<svg aria-hidden="true" focusable="false" class="p-walk__course-card-meta-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><use href="#icon-map-pin"></use></svg>
										<span class="u-sr-only">距離</span>
									</dt>
									<dd><?php echo esc_html( $wdist ); ?></dd>
								</div>
								<?php endif; ?>
							</dl>
							<!-- /.p-walk__course-card-meta -->
						</div>
						<!-- /.p-walk__course-card-body -->
					</a>
					<!-- /.p-walk__course-card -->
					<?php endwhile; wp_reset_postdata(); ?>
				</div>
				<!-- /.p-walk__courses-grid -->

				<?php if ( $walks->max_num_pages > 1 ) : ?>
				<nav class="c-pagination" aria-label="ページ送り">
					<?php
					echo paginate_links( [
						'total'      => $walks->max_num_pages,
						'mid_size'   => 1,
						'end_size'   => 1,
						'prev_text'  => '‹',
						'next_text'  => '›',
						'before_page_number' => '<span class="u-sr-only">ページ </span>',
					] );
					?>
				</nav>
				<?php endif; ?>

				<?php else : ?>
				<p class="p-walk__courses-empty">条件に合うコースが見つかりませんでした。</p>
				<?php endif; ?>
			</div>
			<!-- /.p-walk__courses -->

		</div>
		<!-- /.p-walk__main-inner -->
	</section>
	<!-- /.p-walk__main -->

	<!-- ─── 月間人気コースランキング ── -->
	<?php
	// WP Statistics の月間PVで上位5件を取得
	// データ未蓄積時は新着順にフォールバック
	$ranking_posts = [];
	if ( function_exists( 'wp_statistics_get_top_pages' ) ) {
		$month_start = date( 'Y-m-01' );
		$month_end   = date( 'Y-m-t' );
		[ , $top_pages ] = wp_statistics_get_top_pages( $month_start, $month_end, 5, CPT_WALK );
		if ( $top_pages ) {
			// page_id（インデックス2）を元に投稿順序を保ったまま取得
			$rank_ids = array_filter( array_column( $top_pages, 2 ) );
			if ( $rank_ids ) {
				$rank_q = new WP_Query( [
					'post_type'      => CPT_WALK,
					'post__in'       => $rank_ids,
					'posts_per_page' => 5,
					'orderby'        => 'post__in', // PV降順を維持
					'no_found_rows'  => true,
				] );
				$ranking_posts = $rank_q->posts;
				wp_reset_postdata();
			}
		}
	}
	// フォールバック: データ未蓄積時は新着順
	if ( ! $ranking_posts ) {
		$rank_q = new WP_Query( [
			'post_type'      => CPT_WALK,
			'posts_per_page' => 5,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'no_found_rows'  => true,
		] );
		$ranking_posts = $rank_q->posts;
		wp_reset_postdata();
	}
	if ( $ranking_posts ) :
	?>
	<section class="p-walk__ranking" aria-labelledby="walk-ranking-title">
		<div class="p-walk__ranking-inner">
			<h2 class="p-walk__ranking-title" id="walk-ranking-title">月間人気コースランキング</h2>
			<div class="p-walk__ranking-grid">
				<?php foreach ( $ranking_posts as $idx => $rpost ) :
					$rid    = $rpost->ID;
					$rthumb = sc_thumbnail_url( $rid, 'medium_large' );
					$rdur   = (int) get_post_meta( $rid, 'walk_duration', true );
				?>
				<a class="p-walk__ranking-card" href="<?php echo esc_url( get_permalink( $rid ) ); ?>">
					<div class="p-walk__ranking-card-img">
						<picture class="u-picture-fill">
							<img class="u-img-cover" src="<?php echo esc_url( $rthumb ); ?>" alt="" aria-hidden="true" loading="lazy" width="300" height="220">
						</picture>
						<span class="p-walk__ranking-card-num"><?php echo esc_html( $idx + 1 ); ?></span>
					</div>
					<!-- /.p-walk__ranking-card-img -->
					<div class="p-walk__ranking-card-body">
						<p class="p-walk__ranking-card-name"><?php echo esc_html( get_the_title( $rid ) ); ?></p>
						<?php if ( $rdur ) : ?>
						<p class="p-walk__ranking-card-meta">
							<svg aria-hidden="true" focusable="false" class="p-walk__ranking-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><use href="#icon-clock"></use></svg>
							<?php echo esc_html( $rdur ); ?>分
						</p>
						<?php endif; ?>
					</div>
					<!-- /.p-walk__ranking-card-body -->
				</a>
				<!-- /.p-walk__ranking-card -->
				<?php endforeach; ?>
			</div>
			<!-- /.p-walk__ranking-grid -->
		</div>
		<!-- /.p-walk__ranking-inner -->
	</section>
	<!-- /.p-walk__ranking -->
	<?php endif; ?>

	<!-- ─── 町のギャラリー（ハードコード）── -->
	<section class="p-walk__gallery" aria-labelledby="walk-gallery-title">
		<div class="p-walk__gallery-inner">
			<h2 class="p-walk__gallery-title" id="walk-gallery-title">町のギャラリー</h2>
			<div class="p-walk__gallery-grid">
				<?php
				$gallery_items = [
					[ 'name' => '駿府城公園',      'area' => '七間町', 'likes' => 24 ],
					[ 'name' => '静岡市美術館',    'area' => '中心部', 'likes' => 18 ],
					[ 'name' => 'カフェ・ド・七間', 'area' => '七間町', 'likes' => 32 ],
					[ 'name' => '旧映画館通り',    'area' => '中心部', 'likes' => 15 ],
					[ 'name' => '商店街アーケード', 'area' => '七間町', 'likes' => 27 ],
					[ 'name' => '静岡駅前',        'area' => '中心部', 'likes' => 21 ],
					[ 'name' => '歴史資料館',      'area' => '七間町', 'likes' => 19 ],
					[ 'name' => '桜並木通り',      'area' => '中心部', 'likes' => 29 ],
				];
				foreach ( $gallery_items as $item ) :
				?>
				<div class="p-walk__gallery-card">
					<div class="p-walk__gallery-card-img" aria-hidden="true">
						<picture class="u-picture-fill">
							<img class="u-img-cover" src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/top/hero-cinema.jpg' ); ?>" alt="" aria-hidden="true" loading="lazy" width="300" height="300">
						</picture>
					</div>
					<!-- /.p-walk__gallery-card-img -->
					<div class="p-walk__gallery-card-body">
						<span class="c-tag c-tag--sm p-walk__gallery-card-area"><?php echo esc_html( $item['area'] ); ?></span>
						<p class="p-walk__gallery-card-name"><?php echo esc_html( $item['name'] ); ?></p>
					</div>
					<!-- /.p-walk__gallery-card-body -->
				</div>
				<!-- /.p-walk__gallery-card -->
				<?php endforeach; ?>
			</div>
			<!-- /.p-walk__gallery-grid -->
			<div class="p-walk__gallery-more">
				<a class="c-btn c-btn--outline" href="<?php echo esc_url( home_url( '/gallery/' ) ); ?>">
					町のギャラリーをもっと見る
					<svg class="c-btn__icon" aria-hidden="true" focusable="false"><use href="#icon-chevron-right"></use></svg>
				</a>
			</div>
			<!-- /.p-walk__gallery-more -->
		</div>
		<!-- /.p-walk__gallery-inner -->
	</section>
	<!-- /.p-walk__gallery -->

</article>
<!-- /.p-walk -->

<?php get_footer(); ?>
