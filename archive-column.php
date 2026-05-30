<?php
/**
 * コラム一覧アーカイブ（Manus デザイン準拠）
 */

$filter_cats = isset( $_GET['cat'] ) ? array_filter( array_map( 'sanitize_text_field', (array) $_GET['cat'] ) ) : [];
$filter_tags = isset( $_GET['tag'] ) ? array_filter( array_map( 'sanitize_text_field', (array) $_GET['tag'] ) ) : [];
$keyword     = isset( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';

// メインクエリ（一覧、新着順）
$args = [
	'post_type'      => CPT_COLUMN,
	'posts_per_page' => 4,
	'paged'          => max( 1, get_query_var( 'paged', 1 ) ),
	'orderby'        => 'date',
	'order'          => 'DESC',
];
// 日本語スラッグは WP_Tax_Query で sanitize_title されるため term_id に変換
// sc_get_term_id_by_slug() は $wpdb 直クエリで日本語スラッグも正確に引く
$tax_query = [];
if ( $filter_cats ) {
	$tids = [];
	foreach ( $filter_cats as $s ) { $tid = sc_get_term_id_by_slug( $s, TAX_COLUMN_CAT ); if ( $tid ) $tids[] = $tid; }
	if ( $tids ) $tax_query[] = [ 'taxonomy' => TAX_COLUMN_CAT, 'field' => 'term_id', 'terms' => $tids, 'operator' => 'IN' ];
}
if ( $filter_tags ) {
	$tids = [];
	foreach ( $filter_tags as $s ) { $tid = sc_get_term_id_by_slug( $s, 'post_tag' ); if ( $tid ) $tids[] = $tid; }
	if ( $tids ) $tax_query[] = [ 'taxonomy' => 'post_tag', 'field' => 'term_id', 'terms' => $tids, 'operator' => 'IN' ];
}
if ( count( $tax_query ) > 1 ) $tax_query['relation'] = 'AND';
if ( $tax_query ) $args['tax_query'] = $tax_query;
if ( $keyword )   $args['s'] = $keyword;

$col_query = new WP_Query( $args );

// 特集コラム（カルーセル）: オプションページ優先、無ければ最新3件
$pickup_column_ids = function_exists( 'sc_get_pickup_ids' ) ? sc_get_pickup_ids( 'column', 3 ) : [];
if ( $pickup_column_ids ) {
	$featured_query = new WP_Query( [
		'post_type'      => CPT_COLUMN,
		'post__in'       => $pickup_column_ids,
		'orderby'        => 'post__in',
		'posts_per_page' => 3,
		'no_found_rows'  => true,
	] );
} else {
	$featured_query = new WP_Query( [
		'post_type'      => CPT_COLUMN,
		'posts_per_page' => 3,
		'orderby'        => 'date',
		'order'          => 'DESC',
		'no_found_rows'  => true,
	] );
}

// 人気ランキング（views meta が存在すればそれで降順、無ければ最新）
$ranking_args = [
	'post_type'      => CPT_COLUMN,
	'posts_per_page' => 5,
	'no_found_rows'  => true,
];
if ( get_posts( [ 'post_type' => CPT_COLUMN, 'meta_key' => 'views', 'posts_per_page' => 1, 'fields' => 'ids' ] ) ) {
	$ranking_args['meta_key'] = 'views';
	$ranking_args['orderby']  = 'meta_value_num';
	$ranking_args['order']    = 'DESC';
}
$ranking_query = new WP_Query( $ranking_args );

// カテゴリー
$col_cats   = get_terms( [ 'taxonomy' => TAX_COLUMN_CAT, 'hide_empty' => false ] );
$total_cnt  = (int) wp_count_posts( CPT_COLUMN )->publish;

// 人気タグ（コラム投稿に紐づく post_tag のみ）
$col_tags = [];
$tag_post_ids = get_posts( [ 'post_type' => CPT_COLUMN, 'posts_per_page' => -1, 'fields' => 'ids' ] );
if ( $tag_post_ids ) {
	$tags = wp_get_object_terms( $tag_post_ids, 'post_tag', [ 'orderby' => 'count', 'order' => 'DESC' ] );
	if ( ! is_wp_error( $tags ) ) {
		$col_tags = $tags;
	}
}

$base_url = get_post_type_archive_link( CPT_COLUMN );

// 表示中ページ
$current_page = max( 1, get_query_var( 'paged', 1 ) );

get_header();
if ( function_exists( 'schema_item_list' ) && $col_query->posts ) schema_item_list( $col_query->posts );
?>
<main id="main-content" class="p-column-archive">

	<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>

	<?php
	get_template_part( 'template-parts/components/page-hero', null, [
		'title' => '七ぶらコラム',
		'sub'   => '七間町の魅力を深掘りするコラムをお届けします。',
	] );
	?>

	<!-- ─── 特集コラム カルーセル ── -->
	<?php if ( $featured_query->have_posts() ) : ?>
	<section class="p-column-archive__featured" aria-label="特集コラム">
		<div class="p-column-archive__container">
			<h2 class="c-pickup-title p-column-archive__featured-title"><span class="c-pickup-title__badge">PICK UP</span> 特集コラム</h2>
			<div class="p-column-archive__carousel js-column-carousel">
				<?php $f_index = 0; while ( $featured_query->have_posts() ) : $featured_query->the_post();
					$pid     = get_the_ID();
					$thumb   = sc_thumbnail_url( $pid, 'large' );
					$cats    = get_the_terms( $pid, TAX_COLUMN_CAT );
					$cat     = ( $cats && ! is_wp_error( $cats ) ) ? $cats[0] : null;
					$author_name = get_field( 'column_author_name', $pid );
					if ( ! $author_name ) {
						$author_name = get_the_author_meta( 'display_name', get_post_field( 'post_author', $pid ) );
					}
					$excerpt = get_field( 'column_excerpt', $pid );
					if ( ! $excerpt ) $excerpt = wp_trim_words( get_the_excerpt(), 24 );
					$read_time = (int) get_field( 'column_read_time', $pid );
					if ( ! $read_time ) $read_time = sc_reading_time( get_the_content() );
					$is_active = $f_index === 0;
				?>
				<div class="p-column-archive__slide<?php echo $is_active ? ' is-active' : ''; ?>" data-slide="<?php echo (int) $f_index; ?>">
					<a class="p-column-archive__slide-link" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>"></a>
					<div class="p-column-archive__slide-media">
						<picture class="u-picture-fill">
							<img class="u-img-cover" src="<?php echo esc_url( $thumb ); ?>" alt="" aria-hidden="true" loading="<?php echo $is_active ? 'eager' : 'lazy'; ?>" width="1200" height="514">
						</picture>
					</div>
					<div class="p-column-archive__slide-body">
						<?php if ( $cat ) : ?>
						<span class="p-column-archive__slide-cat"><?php echo esc_html( $cat->name ); ?></span>
						<?php endif; ?>
						<?php if ( $excerpt ) : ?>
						<p class="p-column-archive__slide-lead"><?php echo esc_html( wp_strip_all_tags( $excerpt ) ); ?></p>
						<?php endif; ?>
						<h3 class="p-column-archive__slide-title"><?php the_title(); ?></h3>
						<div class="p-column-archive__slide-meta">
							<?php if ( $author_name ) : ?>
							<span class="p-column-archive__slide-meta-item">
								<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
								<?php echo esc_html( $author_name ); ?>
							</span>
							<?php endif; ?>
							<span class="p-column-archive__slide-meta-item">
								<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
								<?php echo esc_html( get_the_date( 'Y年n月j日' ) ); ?>
							</span>
							<span class="p-column-archive__slide-meta-item">
								<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
								<?php echo (int) $read_time; ?>分
							</span>
						</div>
						<a class="p-column-archive__slide-btn" href="<?php the_permalink(); ?>">
							読む
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
						</a>
					</div>
				</div>
				<!-- /.p-column-archive__slide -->
				<?php $f_index++; endwhile; wp_reset_postdata(); ?>

				<?php if ( $featured_query->post_count > 1 ) : ?>
				<button type="button" class="p-column-archive__carousel-prev js-carousel-prev" aria-label="前のスライド">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
				</button>
				<button type="button" class="p-column-archive__carousel-next js-carousel-next" aria-label="次のスライド">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
				</button>
				<div class="p-column-archive__carousel-dots">
					<?php for ( $i = 0; $i < $featured_query->post_count; $i++ ) : ?>
					<button type="button" class="p-column-archive__carousel-dot<?php echo $i === 0 ? ' is-active' : ''; ?>" data-target="<?php echo (int) $i; ?>" aria-label="スライド<?php echo (int) ( $i + 1 ); ?>"></button>
					<?php endfor; ?>
				</div>
				<?php endif; ?>
			</div>
			<!-- /.p-column-archive__carousel -->
		</div>
	</section>
	<!-- /.p-column-archive__featured -->
	<?php endif; ?>

	<!-- ─── メインレイアウト（左：サイドバー ／ 右：本文） ── -->
	<section class="p-column-archive__main">
		<div class="p-column-archive__container">
			<div class="p-column-archive__layout">

				<!-- ─── サイドバー ── -->
				<aside class="c-filter-sidebar" aria-label="絞り込み">

					<?php if ( ( ! is_wp_error( $col_cats ) && $col_cats ) || $col_tags ) : ?>
					<form id="js-column-filter" class="c-filter-sidebar__filter" method="get" action="<?php echo esc_url( $base_url ); ?>">

						<div class="c-filter-sidebar__head">
							<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
							<h2 class="c-filter-sidebar__title">絞り込み検索</h2>
						</div>

						<div class="c-filter-sidebar__group">
							<label class="c-filter-sidebar__label" for="column-search">キーワード検索</label>
							<input id="column-search" class="c-filter-sidebar__search" type="search" name="s" placeholder="タイトル、タグ..." value="<?php echo esc_attr( $keyword ); ?>">
						</div>

						<?php if ( ! is_wp_error( $col_cats ) && $col_cats ) : ?>
						<div class="c-filter-sidebar__group">
							<p class="c-filter-sidebar__label">カテゴリー</p>
							<div class="c-filter-sidebar__chips">
								<button type="button" class="c-filter-sidebar__chip js-chips-clear<?php echo ! $filter_cats ? ' is-active' : ''; ?>" data-group="cat">すべて</button>
								<?php foreach ( $col_cats as $cat ) : ?>
								<label class="c-filter-sidebar__chip<?php echo in_array( $cat->slug, $filter_cats, true ) ? ' is-active' : ''; ?>">
									<input type="checkbox" name="cat[]" value="<?php echo esc_attr( $cat->slug ); ?>"<?php echo in_array( $cat->slug, $filter_cats, true ) ? ' checked' : ''; ?> style="position:absolute;opacity:0;width:1px;height:1px;">
									<?php echo esc_html( $cat->name ); ?>
								</label>
								<?php endforeach; ?>
							</div>
						</div>
						<?php endif; ?>

						<?php if ( $col_tags ) : ?>
						<div class="c-filter-sidebar__group">
							<p class="c-filter-sidebar__label">人気タグ</p>
							<div class="c-filter-sidebar__chips">
								<button type="button" class="c-filter-sidebar__chip js-chips-clear<?php echo ! $filter_tags ? ' is-active' : ''; ?>" data-group="tag">すべて</button>
								<?php foreach ( $col_tags as $t ) : ?>
								<label class="c-filter-sidebar__chip<?php echo in_array( $t->slug, $filter_tags, true ) ? ' is-active' : ''; ?>">
									<input type="checkbox" name="tag[]" value="<?php echo esc_attr( $t->slug ); ?>"<?php echo in_array( $t->slug, $filter_tags, true ) ? ' checked' : ''; ?> style="position:absolute;opacity:0;width:1px;height:1px;">
									<?php echo esc_html( $t->name ); ?>
								</label>
								<?php endforeach; ?>
							</div>
						</div>
						<?php endif; ?>

						<div class="c-filter-sidebar__result">
							<span class="c-filter-sidebar__result-label">検索結果</span>
							<p class="c-filter-sidebar__result-value"><?php echo (int) $col_query->found_posts; ?><span class="c-filter-sidebar__result-unit">件</span></p>
						</div>
					</form>
					<script>
					(function () {
						var form = document.getElementById('js-column-filter');
						if (!form) return;
						var colTimer;
						form.querySelectorAll('input[type="search"], input[type="text"]').forEach(function (inp) {
							inp.addEventListener('input', function () {
								clearTimeout(colTimer);
								colTimer = setTimeout(function () { form.submit(); }, 600);
							});
						});
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
					<?php endif; ?>

					<!-- 人気コラムランキング -->
					<?php if ( $ranking_query->have_posts() ) : ?>
					<div class="p-column-archive__sidebar-section">
						<p class="p-column-archive__sidebar-label">人気コラム</p>
						<ol class="p-column-archive__ranking">
							<?php $r = 0; while ( $ranking_query->have_posts() ) : $ranking_query->the_post(); $r++;
								$views = (int) get_post_meta( get_the_ID(), 'views', true );
							?>
							<li class="p-column-archive__rank-item">
								<a class="p-column-archive__rank-link" href="<?php the_permalink(); ?>">
									<span class="p-column-archive__rank-num<?php echo $r > 3 ? ' p-column-archive__rank-num--low' : ''; ?>"><?php echo (int) $r; ?></span>
									<span class="p-column-archive__rank-body">
										<span class="p-column-archive__rank-title"><?php the_title(); ?></span>
										<?php if ( $views ) : ?>
										<span class="p-column-archive__rank-views"><?php echo number_format( $views ); ?> views</span>
										<?php endif; ?>
									</span>
								</a>
							</li>
							<?php endwhile; wp_reset_postdata(); ?>
						</ol>
					</div>
					<?php endif; ?>

					<!-- 投稿募集ミニ CTA -->
					<div class="p-column-archive__sidebar-cta">
						<h4 class="p-column-archive__sidebar-cta-title">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 1 1 3 3L7 19l-4 1 1-4z"/></svg>
							コラムを書いてみませんか？
						</h4>
						<p class="p-column-archive__sidebar-cta-text">七間町の魅力を伝えるコラムを募集しています。あなたの視点で町の物語を紡いでみませんか。</p>
						<a class="p-column-archive__sidebar-cta-btn" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">投稿について</a>
					</div>
					<!-- /.p-column-archive__sidebar-cta -->
				</aside>
				<!-- /.c-filter-sidebar -->

				<!-- ─── 本文 ── -->
				<div class="p-column-archive__content">

					<!-- 件数カウント -->
					<div class="p-column-archive__count">
						<p class="p-column-archive__count-text">
							<span class="p-column-archive__count-num"><?php echo (int) $col_query->found_posts; ?></span>
							件のコラム
							<?php if ( $col_query->max_num_pages > 1 ) : ?>
							<span class="p-column-archive__count-page">（<?php echo (int) $current_page; ?>/<?php echo (int) $col_query->max_num_pages; ?>ページ）</span>
							<?php endif; ?>
						</p>
					</div>

					<?php
					// 適用中の絞り込みチップ
					$active_chips = [];
					$base_url     = get_post_type_archive_link( CPT_COLUMN );
					$qs_all       = $_GET; unset( $qs_all['paged'] );
					foreach ( $filter_cats as $slug ) {
						$tid  = sc_get_term_id_by_slug( $slug, TAX_COLUMN_CAT );
						$term = $tid ? get_term( $tid, TAX_COLUMN_CAT ) : null;
						if ( $term && ! is_wp_error( $term ) ) {
							$remaining = array_values( array_diff( $filter_cats, [ $slug ] ) );
							$qs = $qs_all;
							if ( $remaining ) { $qs['cat'] = $remaining; } else { unset( $qs['cat'] ); }
							$active_chips[] = [ 'label' => $term->name, 'remove' => $qs ? add_query_arg( $qs, $base_url ) : $base_url ];
						}
					}
					foreach ( $filter_tags as $slug ) {
						$tid  = sc_get_term_id_by_slug( $slug, 'post_tag' );
						$term = $tid ? get_term( $tid, 'post_tag' ) : null;
						if ( $term && ! is_wp_error( $term ) ) {
							$remaining = array_values( array_diff( $filter_tags, [ $slug ] ) );
							$qs = $qs_all;
							if ( $remaining ) { $qs['tag'] = $remaining; } else { unset( $qs['tag'] ); }
							$active_chips[] = [ 'label' => '#' . $term->name, 'remove' => $qs ? add_query_arg( $qs, $base_url ) : $base_url ];
						}
					}
					get_template_part( 'template-parts/components/active-filters', null, [
						'chips'     => $active_chips,
						'clear_url' => $base_url,
					] );
					?>
					<?php if ( $col_query->have_posts() ) : ?>
					<div class="p-column-archive__list">
						<?php while ( $col_query->have_posts() ) : $col_query->the_post();
							$pid    = get_the_ID();
							$thumb  = sc_thumbnail_url( $pid, 'medium_large' );
							$cats   = get_the_terms( $pid, TAX_COLUMN_CAT );
							$cat    = ( $cats && ! is_wp_error( $cats ) ) ? $cats[0] : null;
							$tags   = get_the_terms( $pid, 'post_tag' );
							$author_name = get_field( 'column_author_name', $pid );
							if ( ! $author_name ) {
								$author_name = get_the_author_meta( 'display_name', get_post_field( 'post_author', $pid ) );
							}
							$excerpt = get_field( 'column_excerpt', $pid );
							if ( ! $excerpt ) $excerpt = wp_trim_words( get_the_excerpt(), 60 );
							$read_time = (int) get_field( 'column_read_time', $pid );
							if ( ! $read_time ) $read_time = sc_reading_time( get_the_content() );
							$views = (int) get_post_meta( $pid, 'views', true );
						?>
						<a class="p-column-archive__card" href="<?php the_permalink(); ?>">
							<div class="p-column-archive__card-media">
								<picture class="u-picture-fill">
									<img class="u-img-cover--transition" src="<?php echo esc_url( $thumb ); ?>" alt="" aria-hidden="true" loading="lazy" width="240" height="200">
								</picture>
							</div>
							<div class="p-column-archive__card-body">
								<?php if ( $cat ) : ?>
								<span class="p-column-archive__card-cat"><?php echo esc_html( $cat->name ); ?></span>
								<?php endif; ?>
								<h3 class="p-column-archive__card-title"><?php the_title(); ?></h3>
								<p class="p-column-archive__card-excerpt"><?php echo esc_html( wp_strip_all_tags( $excerpt ) ); ?></p>
								<div class="p-column-archive__card-meta">
									<?php if ( $author_name ) : ?>
									<span class="p-column-archive__card-meta-item">
										<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
										<?php echo esc_html( $author_name ); ?>
									</span>
									<?php endif; ?>
									<span class="p-column-archive__card-meta-item">
										<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
										<?php echo esc_html( get_the_date( 'Y年n月j日' ) ); ?>
									</span>
									<span class="p-column-archive__card-meta-item">
										<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
										<?php echo (int) $read_time; ?>分
									</span>
									<?php if ( $views ) : ?>
									<span class="p-column-archive__card-meta-item">
										<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
										<?php echo number_format( $views ); ?>
									</span>
									<?php endif; ?>
								</div>
								<?php if ( $tags && ! is_wp_error( $tags ) ) : ?>
								<div class="p-column-archive__card-tags">
									<?php foreach ( array_slice( $tags, 0, 4 ) as $t ) : ?>
									<span class="p-column-archive__card-tag"><?php echo esc_html( $t->name ); ?></span>
									<?php endforeach; ?>
								</div>
								<?php endif; ?>
							</div>
						</a>
						<!-- /.p-column-archive__card -->
						<?php endwhile; wp_reset_postdata(); ?>
					</div>
					<!-- /.p-column-archive__list -->

					<?php
					// 数字付きページネーション
					$pagination = paginate_links( [
						'total'     => $col_query->max_num_pages,
						'current'   => $current_page,
						'prev_text' => '< 前へ',
						'next_text' => '次へ >',
						'type'      => 'array',
						'end_size'  => 1,
						'mid_size'  => 2,
					] );
					if ( $pagination ) :
					?>
					<nav class="p-column-archive__pagination" aria-label="ページネーション">
						<ul class="p-column-archive__page-list">
							<?php foreach ( $pagination as $link ) : ?>
							<li class="p-column-archive__page-item"><?php echo wp_kses_post( $link ); ?></li>
							<?php endforeach; ?>
						</ul>
					</nav>
					<?php endif; ?>

					<?php else : ?>
					<p class="p-column-archive__empty">コラムが見つかりませんでした。</p>
					<?php endif; ?>

				</div>
				<!-- /.p-column-archive__content -->

			</div>
			<!-- /.p-column-archive__layout -->
		</div>
	</section>
	<!-- /.p-column-archive__main -->

</main>
<!-- /#main-content -->

<?php get_footer(); ?>
