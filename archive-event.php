<?php
/**
 * イベント一覧アーカイブ（Manus デザイン準拠）
 * レイアウト: 左サイドバー(フィルター/タグ/CTA) + 右メイン(特集→ランキング→近日開催)
 */

// 'cat'/'tag' は WP 予約済みクエリ変数と衝突するため ev_cat/ev_tag を使用（複数選択対応）
$filter_cats = isset( $_GET['ev_cat'] ) ? array_filter( array_map( 'sanitize_text_field', (array) $_GET['ev_cat'] ) ) : [];
$filter_tags = isset( $_GET['ev_tag'] ) ? array_filter( array_map( 'sanitize_text_field', (array) $_GET['ev_tag'] ) ) : [];

// 追加の絞り込み（UI 上の選択肢。実データ未保持のため見出し/チップ表示用に保持）
$extra_filters = [
	'audience' => [
		'label'   => '対象者',
		'choices' => [
			'family'   => '子育て世帯',
			'senior'   => 'シニア',
			'youth'    => '若者・学生',
			'business' => 'ビジネスパーソン',
			'couple'   => 'カップル',
			'solo'     => 'おひとりさまOK',
		],
	],
	'price'    => [
		'label'   => '料金',
		'choices' => [
			'free'   => '無料',
			'low'    => '〜1,000円',
			'mid'    => '〜3,000円',
			'high'   => '3,000円以上',
		],
	],
	'day'      => [
		'label'   => '開催曜日',
		'choices' => [
			'weekday' => '平日',
			'weekend' => '土日祝',
			'morning' => '午前開催',
			'evening' => '夕方以降',
		],
	],
	// 'area' は taxonomy query_var と衝突するため ev_area を使用
	'ev_area'  => [
		'label'   => 'エリア',
		'choices' => [
			'central' => '中央',
			'east'    => '東部',
			'west'    => '西部',
		],
	],
];

// 現在の追加絞り込み値（複数選択対応）
$active_extras = [];
foreach ( $extra_filters as $key => $cfg ) {
	$vals = isset( $_GET[ $key ] ) ? array_filter( array_map( 'sanitize_text_field', (array) $_GET[ $key ] ) ) : [];
	$vals = array_values( array_filter( $vals, function( $v ) use ( $cfg ) { return isset( $cfg['choices'][ $v ] ); } ) );
	if ( $vals ) $active_extras[ $key ] = $vals;
}

// メインクエリ（イベント一覧、件数表示用）
$args = [
	'post_type'      => CPT_EVENT,
	'posts_per_page' => 8,
	'paged'          => max( 1, get_query_var( 'paged', 1 ) ),
	'meta_key'       => 'event_date_start',
	'orderby'        => 'meta_value',
	'order'          => 'DESC',
];
// 日本語スラッグは WP_Tax_Query で sanitize_title されるため term_id に変換
// sc_get_term_id_by_slug() は $wpdb 直クエリで日本語スラッグも正確に引く
$tax_query = [];
if ( $filter_cats ) {
	$tids = [];
	foreach ( $filter_cats as $s ) { $tid = sc_get_term_id_by_slug( $s, TAX_EVENT_CAT ); if ( $tid ) $tids[] = $tid; }
	if ( $tids ) $tax_query[] = [ 'taxonomy' => TAX_EVENT_CAT, 'field' => 'term_id', 'terms' => $tids, 'operator' => 'IN' ];
}
if ( $filter_tags ) {
	$tids = [];
	foreach ( $filter_tags as $s ) { $tid = sc_get_term_id_by_slug( $s, 'post_tag' ); if ( $tid ) $tids[] = $tid; }
	if ( $tids ) $tax_query[] = [ 'taxonomy' => 'post_tag', 'field' => 'term_id', 'terms' => $tids, 'operator' => 'IN' ];
}
if ( count( $tax_query ) > 1 ) $tax_query['relation'] = 'AND';
if ( $tax_query ) $args['tax_query'] = $tax_query;
$event_query = new WP_Query( $args );

// 特集イベント: PICK UPオプション or 最新。上限なし・ランダム順（ヘルパー側で shuffle）
$pickup_event_ids = function_exists( 'sc_get_pickup_ids' ) ? sc_get_pickup_ids( 'event' ) : [];
$featured_query = $pickup_event_ids
	? new WP_Query( [
		'post_type'      => CPT_EVENT,
		'post__in'       => $pickup_event_ids,
		'orderby'        => 'post__in',
		'posts_per_page' => -1,
		'no_found_rows'  => true,
	] )
	: new WP_Query( [
		'post_type'      => CPT_EVENT,
		'posts_per_page' => 2,
		'no_found_rows'  => true,
		'meta_key'       => 'event_date_start',
		'orderby'        => 'meta_value',
		'order'          => 'DESC',
	] );

// 近日開催（4件、event_date_start ≥ 今日。なければ最新）
$today = date_i18n( 'Y-m-d' );
$upcoming_query = new WP_Query( [
	'post_type'      => CPT_EVENT,
	'posts_per_page' => 4,
	'no_found_rows'  => true,
	'meta_key'       => 'event_date_start',
	'orderby'        => 'meta_value',
	'order'          => 'ASC',
	'meta_query'     => [
		[ 'key' => 'event_date_start', 'value' => $today, 'compare' => '>=', 'type' => 'DATE' ],
	],
] );
if ( ! $upcoming_query->have_posts() ) {
	$upcoming_query = new WP_Query( [
		'post_type'      => CPT_EVENT,
		'posts_per_page' => 4,
		'no_found_rows'  => true,
	] );
}

// 今週の人気ランキング（views meta があれば降順、なければ最新5件）
$ranking_args = [
	'post_type'      => CPT_EVENT,
	'posts_per_page' => 5,
	'no_found_rows'  => true,
];
if ( get_posts( [ 'post_type' => CPT_EVENT, 'meta_key' => 'views', 'posts_per_page' => 1, 'fields' => 'ids' ] ) ) {
	$ranking_args['meta_key'] = 'views';
	$ranking_args['orderby']  = 'meta_value_num';
	$ranking_args['order']    = 'DESC';
}
$ranking_query = new WP_Query( $ranking_args );

// カテゴリー一覧
$event_cats = get_terms( [ 'taxonomy' => TAX_EVENT_CAT, 'hide_empty' => false ] );

// タグ一覧（投稿件数つき、上位10）
$event_tags = get_terms( [ 'taxonomy' => 'post_tag', 'hide_empty' => true, 'orderby' => 'count', 'order' => 'DESC', 'number' => 10 ] );

// 終了判定
$sc_event_is_ended = function( $pid ) {
	$end = get_field( 'event_date_end', $pid );
	if ( ! $end ) $end = get_field( 'event_date_start', $pid );
	if ( ! $end ) return false;
	return strtotime( $end ) < strtotime( date_i18n( 'Y-m-d' ) );
};

$base_url = get_post_type_archive_link( CPT_EVENT );
// タグクラウドのリンク: 現在の ev_cat を保持しつつ ev_tag を単独で切り替え
$keep_cat_qs = $filter_cats ? [ 'ev_cat' => $filter_cats ] : [];
$keep_tag = $filter_tags ? add_query_arg( array_merge( $keep_cat_qs, [ 'ev_tag' => $filter_tags ] ), $base_url ) : ( $keep_cat_qs ? add_query_arg( $keep_cat_qs, $base_url ) : $base_url );

get_header();
if ( function_exists( 'schema_item_list' ) && $event_query->posts ) schema_item_list( $event_query->posts );
?>
<main id="main-content" class="p-event-archive">

	<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>

	<section class="p-event-archive__main">
		<div class="p-event-archive__container">

			<!-- ─── ヘッダー ── -->
			<div class="p-event-archive__head">
				<h1 class="p-event-archive__title">イベント</h1>
			</div>

			<div class="l-sidebar-layout">

				<!-- ─── 左サイドバー: 絞り込み ── -->
				<aside class="c-filter-sidebar" aria-label="絞り込み">

					<form id="js-event-filter" class="c-filter-sidebar__filter" method="get" action="<?php echo esc_url( $base_url ); ?>">
						<?php foreach ( $filter_tags as $t_slug ) : ?>
						<input type="hidden" name="ev_tag[]" value="<?php echo esc_attr( $t_slug ); ?>">
						<?php endforeach; ?>

						<button type="button" class="c-filter-sidebar__head" id="js-filter-toggle" aria-expanded="false" aria-controls="js-filter-body">
							<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
							<span class="c-filter-sidebar__title">絞り込み検索</span>
							<svg class="c-filter-sidebar__toggle-icon" width="20" height="20" aria-hidden="true" focusable="false"><use href="#icon-plus"></use></svg>
						</button>

						<div id="js-filter-body" class="c-filter-sidebar__body">

						<?php if ( ! is_wp_error( $event_cats ) && $event_cats ) : ?>
						<div class="c-filter-sidebar__group">
							<p class="c-filter-sidebar__label">カテゴリー</p>
							<div class="c-filter-sidebar__chips">
								<button type="button" class="c-filter-sidebar__chip js-chips-clear<?php echo ! $filter_cats ? ' is-active' : ''; ?>" data-group="ev_cat">すべて</button>
								<?php foreach ( $event_cats as $cat ) : ?>
								<label class="c-filter-sidebar__chip<?php echo in_array( $cat->slug, $filter_cats, true ) ? ' is-active' : ''; ?>">
									<input class="u-sr-only" type="checkbox" name="ev_cat[]" value="<?php echo esc_attr( $cat->slug ); ?>"<?php echo in_array( $cat->slug, $filter_cats, true ) ? ' checked' : ''; ?>>
									<?php echo esc_html( $cat->name ); ?>
								</label>
								<?php endforeach; ?>
							</div>
						</div>
						<?php endif; ?>

						<?php
						foreach ( $extra_filters as $key => $cfg ) :
							$current_vals = $active_extras[ $key ] ?? [];
						?>
						<div class="c-filter-sidebar__group">
							<p class="c-filter-sidebar__label"><?php echo esc_html( $cfg['label'] ); ?></p>
							<div class="c-filter-sidebar__chips">
								<button type="button" class="c-filter-sidebar__chip js-chips-clear<?php echo ! $current_vals ? ' is-active' : ''; ?>" data-group="<?php echo esc_attr( $key ); ?>">すべて</button>
								<?php foreach ( $cfg['choices'] as $val => $lbl ) : ?>
								<label class="c-filter-sidebar__chip<?php echo in_array( $val, $current_vals, true ) ? ' is-active' : ''; ?>">
									<input class="u-sr-only" type="checkbox" name="<?php echo esc_attr( $key ); ?>[]" value="<?php echo esc_attr( $val ); ?>"<?php echo in_array( $val, $current_vals, true ) ? ' checked' : ''; ?>>
									<?php echo esc_html( $lbl ); ?>
								</label>
								<?php endforeach; ?>
							</div>
						</div>
						<?php endforeach; ?>

						<div class="c-filter-sidebar__result">
							<span class="c-filter-sidebar__result-label">検索結果</span>
							<p class="c-filter-sidebar__result-value"><?php echo (int) $event_query->found_posts; ?><span class="c-filter-sidebar__result-unit">件</span></p>
						</div>

						</div><!-- /.c-filter-sidebar__body -->
					</form>
					<!-- /.c-filter-sidebar__filter -->
					<script>
					(function () {
						var form = document.getElementById('js-event-filter');
						if (!form) return;
						form.querySelectorAll('input[type="checkbox"]').forEach(function (cb) {
							cb.addEventListener('change', function () {
								// is-active をサブミット前に即反映
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
								// すべてボタン自身を active に
								form.querySelectorAll('.js-chips-clear[data-group="' + group + '"]').forEach(function (b) {
									b.classList.add('is-active');
								});
								form.submit();
							});
						});
					}());
					</script>

				</aside>
				<!-- /.c-filter-sidebar -->

				<!-- ─── 右メイン ── -->
				<div class="p-event-archive__content">

					<!-- 特集イベント（絞り込みなし時のみ） -->
					<?php $is_filtered = ( $filter_cats || $filter_tags || $active_extras ); ?>
					<?php if ( ! $is_filtered && $featured_query->have_posts() ) : ?>
					<section class="p-event-archive__section">
						<h2 class="c-pickup-title"><span class="c-pickup-title__badge">PICK UP</span> 特集イベント</h2>
						<?php // 3件以上は PC もスライダー化（2枚見せ）。is-pc-slider を JS/CSS フックに
						$feat_slider_cls = $featured_query->post_count >= 3 ? ' is-pc-slider' : ''; ?>
						<div class="p-event-archive__featured-grid js-center-slider<?php echo esc_attr( $feat_slider_cls ); ?>" data-pc-slides="2">
							<?php while ( $featured_query->have_posts() ) : $featured_query->the_post();
								$pid   = get_the_ID();
								$thumb = sc_thumbnail_url( $pid, 'large' );
								$cats  = get_the_terms( $pid, TAX_EVENT_CAT );
								$cat   = ( $cats && ! is_wp_error( $cats ) ) ? $cats[0] : null;
								$ended = $sc_event_is_ended( $pid );
								$start = get_field( 'event_date_start', $pid );
								$time  = get_field( 'event_time', $pid );
							?>
							<a class="p-event-archive__feat-card" href="<?php the_permalink(); ?>">
								<div class="p-event-archive__feat-media">
									<picture class="u-picture-fill">
										<img class="u-img-cover--transition" src="<?php echo esc_url( $thumb ); ?>" alt="" aria-hidden="true" loading="lazy" width="800" height="500">
									</picture>
									<?php if ( $ended ) : ?>
									<span class="p-event-archive__status-badge">終了</span>
									<?php endif; ?>
									<span class="p-event-archive__feat-flag">特集</span>
								</div>
								<div class="p-event-archive__feat-body">
									<?php if ( $cat ) : ?>
									<span class="p-event-archive__tag"><?php echo esc_html( $cat->name ); ?></span>
									<?php endif; ?>
									<h3 class="p-event-archive__feat-title"><?php the_title(); ?></h3>
									<p class="p-event-archive__feat-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 50 ) ); ?></p>
									<div class="p-event-archive__meta">
										<?php if ( $start ) : ?>
										<p class="p-event-archive__meta-row">
											<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
											<?php echo esc_html( mysql2date( 'Y年n月j日', $start ) ); ?>
										</p>
										<?php endif; ?>
										<?php if ( $time ) : ?>
										<p class="p-event-archive__meta-row">
											<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
											<?php echo esc_html( $time ); ?>
										</p>
										<?php endif; ?>
									</div>
								</div>
							</a>
							<!-- /.p-event-archive__feat-card -->
							<?php endwhile; wp_reset_postdata(); ?>
						</div>
						<!-- /.p-event-archive__featured-grid -->
						<?php if ( $featured_query->post_count >= 3 ) : // 3件以上は操作バー（矢印＋ドット） ?>
						<div class="c-slider-nav js-center-slider-nav"></div>
						<?php endif; ?>
					</section>
					<?php endif; ?>

					<!-- 今週の人気イベントランキング（絞り込みなし時のみ） -->
					<?php if ( ! $is_filtered && $ranking_query->have_posts() ) : ?>
					<section class="p-event-archive__section">
						<h2 class="p-event-archive__section-title p-event-archive__section-title--icon">
							<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
							今週の人気イベントランキング
						</h2>
						<div class="p-event-archive__ranking">
							<?php $rank = 0; while ( $ranking_query->have_posts() ) : $ranking_query->the_post();
								$rank++;
								$pid   = get_the_ID();
								$ended = $sc_event_is_ended( $pid );
								$start = get_field( 'event_date_start', $pid );
								$views = (int) get_post_meta( $pid, 'views', true );
							?>
							<a class="p-event-archive__rank-item" href="<?php the_permalink(); ?>">
								<span class="p-event-archive__rank-num"><?php echo (int) $rank; ?></span>
								<div class="p-event-archive__rank-body">
									<div class="p-event-archive__rank-head">
										<h3 class="p-event-archive__rank-title"><?php the_title(); ?></h3>
										<?php if ( $ended ) : ?>
										<span class="p-event-archive__status-badge p-event-archive__status-badge--small">終了</span>
										<?php endif; ?>
									</div>
									<div class="p-event-archive__rank-meta">
										<?php if ( $start ) : ?>
										<span class="p-event-archive__rank-date">
											<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
											<?php echo esc_html( mysql2date( 'Y年n月j日', $start ) ); ?>
										</span>
										<?php endif; ?>
										<span class="p-event-archive__rank-views"><?php echo number_format( $views ); ?> views</span>
									</div>
								</div>
							</a>
							<?php endwhile; wp_reset_postdata(); ?>
						</div>
					</section>
					<?php endif; ?>

					<!-- 適用中の絞り込みチップ -->
					<?php if ( $is_filtered ) :
						$active_chips = [];
						$qs_all = $_GET;
						unset( $qs_all['paged'] );

						foreach ( $filter_cats as $slug ) {
							$tid  = sc_get_term_id_by_slug( $slug, TAX_EVENT_CAT );
							$term = $tid ? get_term( $tid, TAX_EVENT_CAT ) : null;
							if ( $term && ! is_wp_error( $term ) ) {
								$remaining = array_values( array_diff( $filter_cats, [ $slug ] ) );
								$qs = $qs_all;
								if ( $remaining ) { $qs['ev_cat'] = $remaining; } else { unset( $qs['ev_cat'] ); }
								$active_chips[] = [ 'label' => $term->name, 'remove' => $qs ? add_query_arg( $qs, $base_url ) : $base_url ];
							}
						}
						foreach ( $filter_tags as $slug ) {
							$tid  = sc_get_term_id_by_slug( $slug, 'post_tag' );
							$term = $tid ? get_term( $tid, 'post_tag' ) : null;
							if ( $term && ! is_wp_error( $term ) ) {
								$remaining = array_values( array_diff( $filter_tags, [ $slug ] ) );
								$qs = $qs_all;
								if ( $remaining ) { $qs['ev_tag'] = $remaining; } else { unset( $qs['ev_tag'] ); }
								$active_chips[] = [ 'label' => '#' . $term->name, 'remove' => $qs ? add_query_arg( $qs, $base_url ) : $base_url ];
							}
						}
						// extra_filters は配列（複数選択）
						foreach ( $active_extras as $k => $vals ) {
							foreach ( $vals as $v ) {
								$remaining = array_values( array_diff( $vals, [ $v ] ) );
								$qs = $qs_all;
								if ( $remaining ) { $qs[ $k ] = $remaining; } else { unset( $qs[ $k ] ); }
								$active_chips[] = [
									'label'  => $extra_filters[ $k ]['choices'][ $v ] ?? $v,
									'remove' => $qs ? add_query_arg( $qs, $base_url ) : $base_url,
								];
							}
						}
					?>
					<?php get_template_part( 'template-parts/components/active-filters', null, [
						'chips'     => $active_chips,
						'clear_url' => $base_url,
					] ); ?>
					<?php endif; ?>

					<!-- 近日開催のイベント / 絞り込み中は条件名 -->
					<?php
					$section_title = '近日開催のイベント';
					if ( $is_filtered ) {
						$labels = [];
						foreach ( $filter_cats as $slug ) {
							$tid = sc_get_term_id_by_slug( $slug, TAX_EVENT_CAT );
							$t   = $tid ? get_term( $tid, TAX_EVENT_CAT ) : null;
							if ( $t && ! is_wp_error( $t ) ) $labels[] = $t->name;
						}
						foreach ( $filter_tags as $slug ) {
							$tid = sc_get_term_id_by_slug( $slug, 'post_tag' );
							$t   = $tid ? get_term( $tid, 'post_tag' ) : null;
							if ( $t && ! is_wp_error( $t ) ) $labels[] = '#' . $t->name;
						}
						foreach ( $active_extras as $k => $vals ) {
							foreach ( $vals as $v ) {
								if ( isset( $extra_filters[ $k ]['choices'][ $v ] ) ) $labels[] = $extra_filters[ $k ]['choices'][ $v ];
							}
						}
						if ( $labels ) $section_title = implode( ' × ', $labels ) . ' のイベント';
					}
					?>
					<?php if ( $event_query->have_posts() ) : ?>
					<section class="p-event-archive__section">
						<h2 class="p-event-archive__section-title p-event-archive__section-title--icon">
							<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
							<?php echo esc_html( $section_title ); ?>
						</h2>
						<div class="p-event-archive__upcoming">
							<?php while ( $event_query->have_posts() ) : $event_query->the_post();
								$pid   = get_the_ID();
								$thumb = sc_thumbnail_url( $pid, 'medium_large' );
								$cats  = get_the_terms( $pid, TAX_EVENT_CAT );
								$cat   = ( $cats && ! is_wp_error( $cats ) ) ? $cats[0] : null;
								$ended = $sc_event_is_ended( $pid );
								$start = get_field( 'event_date_start', $pid );
								$venue = get_field( 'event_venue', $pid );
							?>
							<a class="p-event-archive__upcoming-card" href="<?php the_permalink(); ?>">
								<div class="p-event-archive__upcoming-media">
									<picture class="u-picture-fill">
										<img class="u-img-cover--transition" src="<?php echo esc_url( $thumb ); ?>" alt="" aria-hidden="true" loading="lazy" width="600" height="375">
									</picture>
									<?php if ( $ended ) : ?>
									<span class="p-event-archive__status-badge p-event-archive__status-badge--small">終了</span>
									<?php endif; ?>
								</div>
								<div class="p-event-archive__upcoming-body">
									<?php if ( $cat ) : ?>
									<span class="p-event-archive__tag"><?php echo esc_html( $cat->name ); ?></span>
									<?php endif; ?>
									<h3 class="p-event-archive__upcoming-title"><?php the_title(); ?></h3>
									<p class="p-event-archive__upcoming-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 30 ) ); ?></p>
									<div class="p-event-archive__meta">
										<?php if ( $start ) : ?>
										<p class="p-event-archive__meta-row">
											<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
											<?php echo esc_html( mysql2date( 'Y年n月j日', $start ) ); ?>
										</p>
										<?php endif; ?>
										<?php if ( $venue ) : ?>
										<p class="p-event-archive__meta-row">
											<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/></svg>
											<?php echo esc_html( $venue ); ?>
										</p>
										<?php endif; ?>
									</div>
								</div>
							</a>
							<!-- /.p-event-archive__upcoming-card -->
							<?php endwhile; wp_reset_postdata(); ?>
						</div>

						<?php if ( $event_query->max_num_pages > 1 ) : ?>
						<nav class="p-event-archive__pagination" aria-label="ページネーション">
							<?php
							echo paginate_links( [
								'total'     => $event_query->max_num_pages,
								'current'   => max( 1, get_query_var( 'paged', 1 ) ),
								'prev_text' => '‹',
								'next_text' => '›',
								'type'      => 'list',
							] );
							?>
						</nav>
						<?php endif; ?>
					</section>
					<?php else : ?>
					<p class="p-event-archive__empty">イベントが見つかりませんでした。</p>
					<?php endif; ?>

				</div>
				<!-- /.p-event-archive__content -->

				<!-- SP では main の下に来る。PC では grid 自動配置で左列2行目 -->
				<div class="p-event-archive__sidebar-extra">
					<?php if ( ! is_wp_error( $event_tags ) && $event_tags ) : ?>
					<div class="p-event-archive__widget">
						<h3 class="p-event-archive__widget-title">タグ</h3>
						<div class="p-event-archive__tag-cloud">
							<?php foreach ( $event_tags as $t ) : ?>
							<a class="p-event-archive__tag-link<?php echo in_array( $t->slug, $filter_tags, true ) ? ' is-active' : ''; ?>" href="<?php echo esc_url( add_query_arg( [ 'ev_tag' => [ $t->slug ] ], $keep_tag ) ); ?>">
								<?php echo esc_html( $t->name ); ?>
								<span class="p-event-archive__tag-count">(<?php echo (int) $t->count; ?>)</span>
							</a>
							<?php endforeach; ?>
						</div>
					</div>
					<?php endif; ?>

					<div class="c-sidebar-cta">
						<h3 class="c-sidebar-cta__title">イベントを掲載しませんか？</h3>
						<p class="c-sidebar-cta__text">七間町で開催するイベント情報を募集しています。</p>
						<a class="c-sidebar-cta__btn" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">掲載依頼</a>
					</div>
				</div>
				<!-- /.p-event-archive__sidebar-extra -->

			</div>
			<!-- /.l-sidebar-layout -->
		</div>
	</section>
	<!-- /.p-event-archive__main -->

</main>
<!-- /#main-content -->
<?php get_footer(); ?>
