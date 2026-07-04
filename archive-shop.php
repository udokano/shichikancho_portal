<?php
/**
 * お店一覧アーカイブ
 */

// カテゴリー・エリアで絞り込み（複数選択対応）
$filter_cats  = isset( $_GET['cat'] )     ? array_filter( array_map( 'sanitize_text_field', (array) $_GET['cat'] ) )     : [];
// 'area' は taxonomy query_var と衝突するため sh_area を使用
$filter_areas = isset( $_GET['sh_area'] ) ? array_filter( array_map( 'sanitize_text_field', (array) $_GET['sh_area'] ) ) : [];
$filter_kw    = isset( $_GET['kw'] )      ? sanitize_text_field( $_GET['kw'] ) : '';

// 日本語スラッグは WP_Tax_Query 内で sanitize_title されると空になるため term_id に変換
// sc_get_term_id_by_slug() は $wpdb 直クエリで日本語スラッグも正確に引く
$tax_query = [];
if ( $filter_cats ) {
	$tids = [];
	foreach ( $filter_cats as $s ) {
		$tid = sc_get_term_id_by_slug( $s, TAX_SHOP_CAT );
		if ( $tid ) $tids[] = $tid;
	}
	if ( $tids ) $tax_query[] = [ 'taxonomy' => TAX_SHOP_CAT, 'field' => 'term_id', 'terms' => $tids, 'operator' => 'IN' ];
}
if ( $filter_areas ) {
	$tids = [];
	foreach ( $filter_areas as $s ) {
		$tid = sc_get_term_id_by_slug( $s, TAX_AREA );
		if ( $tid ) $tids[] = $tid;
	}
	if ( $tids ) $tax_query[] = [ 'taxonomy' => TAX_AREA, 'field' => 'term_id', 'terms' => $tids, 'operator' => 'IN' ];
}
if ( count( $tax_query ) > 1 ) {
	$tax_query['relation'] = 'AND';
}

$args = [
	'post_type'      => CPT_SHOP,
	'posts_per_page' => 9,
	'paged'          => get_query_var( 'paged', 1 ),
	'tax_query'      => $tax_query ?: null,
];
if ( $filter_kw ) {
	$args['s'] = $filter_kw;
}

$shop_query = new WP_Query( $args );
if ( function_exists( 'schema_item_list' ) && $shop_query->posts ) schema_item_list( $shop_query->posts );

// カテゴリー・エリア一覧取得
$shop_cats = get_terms( [ 'taxonomy' => TAX_SHOP_CAT, 'hide_empty' => false ] );
$areas     = get_terms( [ 'taxonomy' => TAX_AREA,     'hide_empty' => false ] );
$total     = $shop_query->found_posts;

get_header();
?>
<main id="main-content">

	<?php
		get_template_part( 'template-parts/components/page-hero', null, [
			'title' => '商店街のお店',
			'sub'   => '静岡の真ん中に、人と店の物語がある。',
		] );
		?>

	<div class="p-shop-archive">
		<div class="l-sidebar-layout">

			<!-- サイドバー -->
			<aside class="c-filter-sidebar" aria-label="絞り込み">
				<form id="js-shop-filter" class="c-filter-sidebar__filter" method="get" action="<?php echo esc_url( get_post_type_archive_link( CPT_SHOP ) ); ?>">

					<button type="button" class="c-filter-sidebar__head" id="js-filter-toggle" aria-expanded="false" aria-controls="js-filter-body">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
						<span class="c-filter-sidebar__title">絞り込み検索</span>
						<svg class="c-filter-sidebar__toggle-icon" width="20" height="20" aria-hidden="true" focusable="false"><use href="#icon-plus"></use></svg>
					</button>

					<div id="js-filter-body" class="c-filter-sidebar__body">

					<div class="c-filter-sidebar__group">
						<label class="c-filter-sidebar__label" for="shop-kw">店名で検索</label>
						<input id="shop-kw" class="c-filter-sidebar__search" type="search" name="kw" placeholder="店名で検索" value="<?php echo esc_attr( $filter_kw ); ?>">
					</div>

					<?php if ( ! is_wp_error( $shop_cats ) && $shop_cats ) : ?>
					<div class="c-filter-sidebar__group">
						<p class="c-filter-sidebar__label">カテゴリー</p>
						<div class="c-filter-sidebar__chips">
							<button type="button" class="c-filter-sidebar__chip js-chips-clear<?php echo ! $filter_cats ? ' is-active' : ''; ?>" data-group="cat">すべて</button>
							<?php foreach ( $shop_cats as $cat ) : ?>
							<label class="c-filter-sidebar__chip<?php echo in_array( $cat->slug, $filter_cats, true ) ? ' is-active' : ''; ?>">
								<input class="u-sr-only" type="checkbox" name="cat[]" value="<?php echo esc_attr( $cat->slug ); ?>"<?php echo in_array( $cat->slug, $filter_cats, true ) ? ' checked' : ''; ?>>
								<?php echo esc_html( $cat->name ); ?>
							</label>
							<?php endforeach; ?>
						</div>
					</div>
					<?php endif; ?>

					<?php if ( ! is_wp_error( $areas ) && $areas ) : ?>
					<div class="c-filter-sidebar__group">
						<p class="c-filter-sidebar__label">エリア</p>
						<div class="c-filter-sidebar__chips">
							<button type="button" class="c-filter-sidebar__chip js-chips-clear<?php echo ! $filter_areas ? ' is-active' : ''; ?>" data-group="sh_area">すべて</button>
							<?php foreach ( $areas as $area ) : ?>
							<label class="c-filter-sidebar__chip<?php echo in_array( $area->slug, $filter_areas, true ) ? ' is-active' : ''; ?>">
								<input class="u-sr-only" type="checkbox" name="sh_area[]" value="<?php echo esc_attr( $area->slug ); ?>"<?php echo in_array( $area->slug, $filter_areas, true ) ? ' checked' : ''; ?>>
								<?php echo esc_html( $area->name ); ?>
							</label>
							<?php endforeach; ?>
						</div>
					</div>
					<?php endif; ?>

					<div class="c-filter-sidebar__result">
						<span class="c-filter-sidebar__result-label">検索結果</span>
						<p class="c-filter-sidebar__result-value"><?php echo (int) $total; ?><span class="c-filter-sidebar__result-unit">件</span></p>
					</div>

					</div><!-- /.c-filter-sidebar__body -->
				</form>
				<!-- /.c-filter-sidebar__filter -->
				<script>
				(function () {
					var form = document.getElementById('js-shop-filter');
					if (!form) return;
					// テキスト入力: 600ms デバウンス後にサブミット
					var kwTimer;
					form.querySelectorAll('input[type="search"], input[type="text"]').forEach(function (inp) {
						inp.addEventListener('input', function () {
							clearTimeout(kwTimer);
							kwTimer = setTimeout(function () { form.submit(); }, 600);
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

			</aside>

			<!-- メインコンテンツ -->
			<div class="p-shop-archive__main">

				<!-- PICKUP -->
				<?php
				// 1) オプションページ（PICK UP 設定）優先
				$pickup_ids   = function_exists( 'sc_get_pickup_ids' ) ? sc_get_pickup_ids( 'shop', 3 ) : [];
				$pickup_query = false;
				if ( $pickup_ids ) {
					$pickup_query = new WP_Query( [
						'post_type'      => CPT_SHOP,
						'post__in'       => $pickup_ids,
						'orderby'        => 'post__in',
						'posts_per_page' => 3,
						'no_found_rows'  => true,
					] );
				}
				// 2) フォールバック: 旧 _is_pickup メタ → ランダム
				if ( ! $pickup_query || ! $pickup_query->have_posts() ) {
					$pickup_query = new WP_Query( [
						'post_type'      => CPT_SHOP,
						'posts_per_page' => 3,
						'meta_key'       => '_is_pickup',
						'meta_value'     => '1',
						'no_found_rows'  => true,
					] );
				}
				if ( ! $pickup_query->have_posts() ) {
					$pickup_query = new WP_Query( [
						'post_type'      => CPT_SHOP,
						'posts_per_page' => 3,
						'orderby'        => 'rand',
						'no_found_rows'  => true,
					] );
				}
				?>
				<?php
				$is_filtering = $filter_cats || $filter_areas || $filter_kw;
				$current_page = get_query_var( 'paged', 1 );
				if ( $pickup_query->have_posts() && ! ( $is_filtering || $current_page >= 2 ) ) : ?>
				<div class="p-shop-pickup">
					<h2 class="c-pickup-title"><span class="c-pickup-title__badge">PICK UP</span> おすすめ店舗</h2>
					<div class="p-shop-pickup__grid js-center-slider">
						<?php while ( $pickup_query->have_posts() ) : $pickup_query->the_post();
							$thumb   = sc_thumbnail_url( get_the_ID(), 'medium_large' );
							$cats    = get_the_terms( get_the_ID(), TAX_SHOP_CAT );
							$cat_name = ( $cats && ! is_wp_error( $cats ) ) ? $cats[0]->name : '';
							$area_terms = get_the_terms( get_the_ID(), TAX_AREA );
							$area_name  = ( $area_terms && ! is_wp_error( $area_terms ) ) ? $area_terms[0]->name : '七間町';
						?>
						<article class="p-shop-pickup__card">
							<a class="p-shop-pickup__card-link" href="<?php the_permalink(); ?>">
								<div class="p-shop-pickup__card-img">
									<?php if ( $thumb ) : ?>
									<img class="u-img-cover" src="<?php echo esc_url( $thumb ); ?>" alt="" aria-hidden="true" loading="lazy">
									<?php endif; ?>
									<span class="p-shop-pickup__pr-badge">PR</span>
								</div>
								<div class="p-shop-pickup__card-body">
									<p class="p-shop-pickup__card-meta"><?php echo esc_html( $area_name ); ?> / <?php echo esc_html( $cat_name ); ?></p>
									<h3 class="p-shop-pickup__card-title"><?php the_title(); ?></h3>
									<p class="p-shop-pickup__card-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 30 ) ); ?></p>
								</div>
							</a>
						</article>
						<?php endwhile; wp_reset_postdata(); ?>
					</div>
				</div>
				<?php endif; ?>

				<!-- お店一覧 -->
				<div class="p-shop-list">
					<h2 class="p-shop-list__title">お店 一覧</h2>

					<?php
					// 番号バッジ用: shop_category 全タームを term_id 昇順で取得し丸数字対応表を作る
					$all_shop_cats = get_terms( [
						'taxonomy'   => TAX_SHOP_CAT,
						'hide_empty' => false,
						'orderby'    => 'term_id',
						'order'      => 'ASC',
					] );
					$cat_number_map = [];
					$circled_nums = [ '①','②','③','④','⑤','⑥','⑦','⑧','⑨','⑩','⑪','⑫' ];
					if ( ! is_wp_error( $all_shop_cats ) && $all_shop_cats ) {
						foreach ( $all_shop_cats as $i => $term ) {
							$cat_number_map[ $term->term_id ] = $circled_nums[ $i ] ?? '';
						}
					}

					// 特徴タグの表示分類: 決済とWi-Fiはフッターに分離
					$feature_labels = [
						'takeout'    => 'テイクアウト',
						'delivery'   => 'デリバリー',
						'parking'    => '駐車場あり',
						'reserve'    => '予約OK',
						'kids_ok'    => 'お子様歓迎',
						'pet_ok'     => 'ペット可',
						'english_ok' => '英語対応',
						'smoking_no' => '完全禁煙',
					];
					?>


					<?php
					$active_chips = [];
					$base_url     = get_post_type_archive_link( CPT_SHOP );
					$qs_all       = $_GET; unset( $qs_all['paged'] );
					foreach ( $filter_cats as $slug ) {
						$tid  = sc_get_term_id_by_slug( $slug, TAX_SHOP_CAT );
						$term = $tid ? get_term( $tid, TAX_SHOP_CAT ) : null;
						if ( $term && ! is_wp_error( $term ) ) {
							$remaining = array_values( array_diff( $filter_cats, [ $slug ] ) );
							$qs = $qs_all;
							if ( $remaining ) { $qs['cat'] = $remaining; } else { unset( $qs['cat'] ); }
							$active_chips[] = [ 'label' => $term->name, 'remove' => $qs ? add_query_arg( $qs, $base_url ) : $base_url ];
						}
					}
					foreach ( $filter_areas as $slug ) {
						$tid  = sc_get_term_id_by_slug( $slug, TAX_AREA );
						$term = $tid ? get_term( $tid, TAX_AREA ) : null;
						if ( $term && ! is_wp_error( $term ) ) {
							$remaining = array_values( array_diff( $filter_areas, [ $slug ] ) );
							$qs = $qs_all;
							if ( $remaining ) { $qs['sh_area'] = $remaining; } else { unset( $qs['sh_area'] ); }
							$active_chips[] = [ 'label' => $term->name, 'remove' => $qs ? add_query_arg( $qs, $base_url ) : $base_url ];
						}
					}
					if ( $filter_kw ) {
						$qs = $qs_all; unset( $qs['kw'] );
						$active_chips[] = [ 'label' => '"' . $filter_kw . '"', 'remove' => $qs ? add_query_arg( $qs, $base_url ) : $base_url ];
					}
					get_template_part( 'template-parts/components/active-filters', null, [
						'chips'     => $active_chips,
						'clear_url' => $base_url,
					] );
					?>

					<?php if ( $shop_query->have_posts() ) : ?>
					<div class="p-shop-list__grid">
						<?php while ( $shop_query->have_posts() ) : $shop_query->the_post();
							$thumb    = sc_thumbnail_url( get_the_ID(), 'medium_large' );
							$cats     = get_the_terms( get_the_ID(), TAX_SHOP_CAT );
							$cat_main = ( $cats && ! is_wp_error( $cats ) ) ? $cats[0] : null;
							$cat_name = $cat_main ? $cat_main->name : '';
							$cat_num  = ( $cat_main && isset( $cat_number_map[ $cat_main->term_id ] ) ) ? $cat_number_map[ $cat_main->term_id ] : '';
							// サブカテゴリ: 2つ目のタームがあれば表示
							$cat_sub  = ( $cats && ! is_wp_error( $cats ) && count( $cats ) > 1 ) ? $cats[1]->name : '';

							// 特徴タグ: 決済/Wi-Fi はフッター、その他はチップ行
							$features = get_field( 'shop_features' );
							$features = is_array( $features ) ? $features : [];
							$has_card = in_array( 'card', $features, true );
							$has_qr   = in_array( 'qr',   $features, true );
							$has_wifi = in_array( 'wifi', $features, true );
							$tag_features = array_intersect_key( $feature_labels, array_flip( $features ) );

							// 営業時間サマリ: 複数行の先頭行のみカード表示
							$hours_raw     = get_field( 'shop_hours' );
							$hours_summary = '';
							if ( $hours_raw ) {
								$lines = preg_split( '/\r?\n/', trim( wp_strip_all_tags( $hours_raw ) ) );
								$hours_summary = $lines[0] ?? '';
							}
						?>
						<article class="p-shop-card">
							<a class="p-shop-card__img-link" href="<?php the_permalink(); ?>">
								<div class="p-shop-card__img">
									<?php if ( $thumb ) : ?>
									<img class="u-img-cover--transition" src="<?php echo esc_url( $thumb ); ?>" alt="" aria-hidden="true" loading="lazy">
									<?php endif; ?>
									<div class="p-shop-card__badges">
										<?php if ( $cat_name ) : ?>
										<span class="p-shop-card__num-badge"><?php echo esc_html( $cat_name ); ?></span>
										<?php endif; ?>
										<?php if ( $cat_sub ) : ?>
										<span class="p-shop-card__sub-badge"><?php echo esc_html( $cat_sub ); ?></span>
										<?php endif; ?>
									</div>
									<!-- /.p-shop-card__badges -->
								</div>
								<!-- /.p-shop-card__img -->
							</a>
							<div class="p-shop-card__body">
								<h3 class="p-shop-card__title"><a class="p-shop-card__title-link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
								<p class="p-shop-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 30 ) ); ?></p>

								<?php
								$price_range = get_field( 'shop_price_range' );
								$busy_hours  = get_field( 'shop_busy_hours' );
								$seats       = get_field( 'shop_seats' );
								$parking_note = get_field( 'shop_parking_note' );
								if ( $price_range || $busy_hours || $seats || $parking_note ) : ?>
								<dl class="p-shop-card__info">
									<?php if ( $price_range ) : ?>
									<div class="p-shop-card__info-cell"><dt>価格</dt><dd><?php echo esc_html( $price_range ); ?></dd></div>
									<?php endif; ?>
									<?php if ( $busy_hours ) : ?>
									<div class="p-shop-card__info-cell"><dt>混雑</dt><dd><?php echo esc_html( $busy_hours ); ?></dd></div>
									<?php endif; ?>
									<?php if ( $seats ) : ?>
									<div class="p-shop-card__info-cell"><dt>席</dt><dd><?php echo esc_html( $seats ); ?></dd></div>
									<?php endif; ?>
									<?php if ( $parking_note ) : ?>
									<div class="p-shop-card__info-cell"><dt>駐車場</dt><dd><?php echo esc_html( $parking_note ); ?></dd></div>
									<?php endif; ?>
								</dl>
								<!-- /.p-shop-card__info -->
								<?php endif; ?>

								<?php if ( $tag_features ) : ?>
								<ul class="p-shop-card__tags">
									<?php foreach ( $tag_features as $label ) : ?>
									<li class="p-shop-card__tag"><?php echo esc_html( $label ); ?></li>
									<?php endforeach; ?>
								</ul>
								<?php endif; ?>

								<?php
								// 決済 / 営業時間 のいずれかがある場合のみフッターを表示
								$has_pay_info = $has_card || $has_qr || $has_wifi;
								if ( $has_pay_info || $hours_summary ) :
								?>
								<div class="p-shop-card__footer">
									<div class="p-shop-card__pay">
										<?php if ( $has_card ) : ?>
										<span class="p-shop-card__pay-badge">カード</span>
										<?php endif; ?>
										<?php if ( $has_qr ) : ?>
										<span class="p-shop-card__pay-badge">QR</span>
										<?php endif; ?>
										<?php if ( $has_wifi ) : ?>
										<svg class="p-shop-card__pay-icon" aria-label="Wi-Fiあり" focusable="false" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><use href="#icon-wifi"></use></svg>
										<?php endif; ?>
									</div>
									<!-- /.p-shop-card__pay -->
									<?php if ( $hours_summary ) : ?>
									<span class="p-shop-card__hours">
										<svg class="p-shop-card__hours-icon" aria-hidden="true" focusable="false" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><use href="#icon-clock"></use></svg>
										<?php echo esc_html( $hours_summary ); ?>
									</span>
									<?php endif; ?>
								</div>
								<!-- /.p-shop-card__footer -->
								<?php endif; ?>

								<a class="p-shop-card__more" href="<?php the_permalink(); ?>">詳細へ →</a>
							</div>
							<!-- /.p-shop-card__body -->
						</article>
						<?php endwhile; wp_reset_postdata(); ?>
					</div>

					<!-- ページネーション -->
					<?php if ( $shop_query->max_num_pages > 1 ) : ?>
					<div class="p-shop-list__pagination">
						<?php
						echo paginate_links( [
							'total'   => $shop_query->max_num_pages,
							'current' => max( 1, get_query_var( 'paged' ) ),
							'prev_text' => '‹',
							'next_text' => '›',
						] );
						?>
					</div>
					<?php endif; ?>

					<?php else : ?>
					<p class="p-shop-list__empty">該当するお店が見つかりませんでした。</p>
					<?php endif; ?>
				</div>

			</div>
			<!-- /.p-shop-archive__main -->

		</div>
		<!-- /.l-sidebar-layout -->
	</div>

</main>
<?php get_footer(); ?>
