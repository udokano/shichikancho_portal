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
// 所要時間フィルター選択肢
$durations = [
	[ 'label' => '30分以内',  'max_minutes' => 30  ],
	[ 'label' => '60分以内',  'max_minutes' => 60  ],
	[ 'label' => '90分以内',  'max_minutes' => 90  ],
	[ 'label' => '120分以内', 'max_minutes' => 120 ],
];
?>

<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>

<article class="p-explore">

	<?php
	get_template_part( 'template-parts/components/page-hero', null, [
		'title' => '町をめぐる',
		'sub'   => '七間町とその周辺を歩いて楽しむおすすめルート。',
	] );
	?>


	<!-- ─── コース検索 + 一覧 ── -->
	<section class="p-explore__main" aria-labelledby="explore-courses-title">
		<div class="p-explore__main-inner">

			<!-- フィルターサイドバー -->
			<aside class="p-explore__filter" aria-label="コース絞り込み">
				<form class="p-explore__filter-form js-explore-filter" method="get" action="<?php echo esc_url( get_permalink() ); ?>">
					<h2 class="p-explore__filter-title">条件で絞り込む</h2>

					<?php if ( ! is_wp_error( $areas ) && $areas ) : ?>
					<fieldset class="p-explore__filter-block">
						<legend class="p-explore__filter-label">エリア</legend>
						<div class="c-chips">
							<?php foreach ( $areas as $a ) : ?>
							<label class="c-chips__chip<?php echo in_array( $a->slug, $filter_areas, true ) ? ' is-active' : ''; ?>">
							<input class="u-sr-only" type="checkbox" name="sh_area[]" value="<?php echo esc_attr( $a->slug ); ?>"<?php echo in_array( $a->slug, $filter_areas, true ) ? ' checked' : ''; ?>>
								<?php echo esc_html( $a->name ); ?>
							</label>
							<?php endforeach; ?>
						</div>
					</fieldset>
					<?php endif; ?>

					<?php if ( $durations ) : ?>
					<fieldset class="p-explore__filter-block">
						<legend class="p-explore__filter-label">所要時間</legend>
						<div class="c-chips">
							<?php foreach ( $durations as $d ) : ?>
							<button type="submit" name="dur" value="<?php echo esc_attr( (int) ( $d['max_minutes'] ?? 0 ) ); ?>"
								class="c-chips__chip<?php echo $filter_dur === (int) ( $d['max_minutes'] ?? 0 ) ? ' is-active' : ''; ?>">
								<?php echo esc_html( $d['label'] ?? '' ); ?>
							</button>
							<?php endforeach; ?>
						</div>
					</fieldset>
					<?php endif; ?>

					<?php if ( ! is_wp_error( $scenes ) && $scenes ) : ?>
					<fieldset class="p-explore__filter-block">
						<legend class="p-explore__filter-label">シーン</legend>
						<div class="c-chips">
							<?php foreach ( $scenes as $s ) : ?>
							<label class="c-chips__chip<?php echo in_array( $s->slug, $filter_scenes, true ) ? ' is-active' : ''; ?>">
							<input class="u-sr-only" type="checkbox" name="scene[]" value="<?php echo esc_attr( $s->slug ); ?>"<?php echo in_array( $s->slug, $filter_scenes, true ) ? ' checked' : ''; ?>>
								<?php echo esc_html( $s->name ); ?>
							</label>
							<?php endforeach; ?>
						</div>
					</fieldset>
					<?php endif; ?>

					<?php if ( $filter_areas || $filter_scenes || $filter_dur ) : ?>
					<a class="p-explore__filter-reset" href="<?php echo esc_url( get_permalink() ); ?>">条件をリセット</a>
					<?php endif; ?>
				</form>
			<script>
			(function () {
				var form = document.querySelector('.js-explore-filter');
				if (!form) return;
				form.querySelectorAll('input[type="checkbox"]').forEach(function (cb) {
					cb.addEventListener('change', function () {
						cb.closest('label').classList.toggle('is-active', cb.checked);
						form.submit();
					});
				});
			}());
			</script>
			</aside>
			<!-- /.p-explore__filter -->

			<!-- コース一覧 -->
			<div class="p-explore__courses">
				<header class="p-explore__courses-head">
					<h2 class="p-explore__courses-title" id="explore-courses-title">コース一覧</h2>
					<p class="p-explore__courses-count">登録コース <strong class="p-explore__courses-count-value"><?php echo esc_html( $walks->found_posts ); ?></strong> 件</p>
				</header>

				<?php if ( $walks->have_posts() ) : ?>
				<div class="p-explore__courses-grid">
					<?php while ( $walks->have_posts() ) : $walks->the_post();
						$wid       = get_the_ID();
						$wthumb    = sc_thumbnail_url( $wid, 'medium_large' );
						$wdur      = (int) get_post_meta( $wid, 'walk_duration', true );
						$wdist     = get_post_meta( $wid, 'walk_distance', true );
						$wdesc     = get_post_meta( $wid, 'walk_description', true );
						$warea_arr = get_the_terms( $wid, TAX_AREA );
						$warea     = ( $warea_arr && ! is_wp_error( $warea_arr ) ) ? $warea_arr[0] : null;
					?>
					<a class="p-explore__course-card" href="<?php the_permalink(); ?>">
						<div class="p-explore__course-card-img">
							<picture class="u-picture-fill">
								<img class="u-img-cover" src="<?php echo esc_url( $wthumb ); ?>" alt="" aria-hidden="true" loading="lazy" width="400" height="300">
							</picture>
							<?php if ( $warea ) : ?>
							<span class="c-tag c-tag--sm p-explore__course-card-area"><?php echo esc_html( $warea->name ); ?></span>
							<?php endif; ?>
						</div>
						<div class="p-explore__course-card-body">
							<h3 class="p-explore__course-card-name"><?php the_title(); ?></h3>
							<?php if ( $wdesc ) : ?>
							<p class="p-explore__course-card-desc"><?php echo wp_kses( $wdesc, [ 'br' => [] ] ); ?></p>
							<?php endif; ?>
							<dl class="p-explore__course-card-meta">
								<?php if ( $wdur ) : ?>
								<div><dt class="u-sr-only">所要時間</dt><dd><?php echo esc_html( $wdur ); ?>分</dd></div>
								<?php endif; ?>
								<?php if ( $wdist ) : ?>
								<div><dt class="u-sr-only">距離</dt><dd><?php echo esc_html( $wdist ); ?></dd></div>
								<?php endif; ?>
							</dl>
						</div>
					</a>
					<?php endwhile; wp_reset_postdata(); ?>
				</div>
				<!-- /.p-explore__courses-grid -->

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
				<p class="p-explore__courses-empty">条件に合うコースが見つかりませんでした。</p>
				<?php endif; ?>
			</div>
			<!-- /.p-explore__courses -->

		</div>
		<!-- /.p-explore__main-inner -->
	</section>
	<!-- /.p-explore__main -->

	<!-- ─── 月間人気ランキング ── -->
	<?php
	$rank_q = new WP_Query( [
		'post_type'      => CPT_WALK,
		'posts_per_page' => 5,
		'orderby'        => 'date',
		'order'          => 'DESC',
		'no_found_rows'  => true,
	] );
	$ranking_ids = wp_list_pluck( $rank_q->posts, 'ID' );
	if ( $ranking_ids ) :
	?>
	<section class="p-explore__ranking" aria-labelledby="explore-ranking-title">
		<div class="p-explore__ranking-inner">
			<h2 class="p-explore__ranking-title" id="explore-ranking-title">月間人気コースランキング</h2>
			<ol class="p-explore__ranking-list">
				<?php foreach ( $ranking_ids as $idx => $rid ) :
					$rdur = (int) get_post_meta( $rid, 'walk_duration', true );
				?>
				<li class="p-explore__ranking-item">
					<span class="p-explore__ranking-num"><?php echo esc_html( $idx + 1 ); ?></span>
					<a class="p-explore__ranking-link" href="<?php echo esc_url( get_permalink( $rid ) ); ?>">
						<span class="p-explore__ranking-name"><?php echo esc_html( get_the_title( $rid ) ); ?></span>
						<?php if ( $rdur ) : ?>
						<span class="p-explore__ranking-meta"><?php echo esc_html( $rdur ); ?>分</span>
						<?php endif; ?>
					</a>
				</li>
				<?php endforeach; ?>
			</ol>
		</div>
		<!-- /.p-explore__ranking-inner -->
	</section>
	<!-- /.p-explore__ranking -->
	<?php endif; ?>

</article>
<!-- /.p-explore -->

<?php get_footer(); ?>
