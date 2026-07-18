<?php
/**
 * お隣さんの話 一覧アーカイブ
 */

$paged       = max( 1, get_query_var( 'paged', 1 ) );
$filter_kw   = isset( $_GET['kw'] )  ? sanitize_text_field( $_GET['kw'] ) : '';
$filter_ages = isset( $_GET['age'] ) ? array_filter( array_map( 'sanitize_text_field', (array) $_GET['age'] ) ) : [];

$age_choices = [
	'20' => '20代',
	'30' => '30代',
	'40' => '40代',
	'50' => '50代',
	'60' => '60代以上',
];

$query_args = [
	'post_type'      => CPT_RESIDENT,
	'posts_per_page' => 12,
	'paged'          => $paged,
	'orderby'        => 'date',
	'order'          => 'DESC',
];

if ( $filter_kw ) { $query_args['s'] = $filter_kw; }

if ( $filter_ages ) {
	$age_meta = [ 'relation' => 'OR' ];
	foreach ( $filter_ages as $a ) {
		// 「30」「30代」「30代前半」など曖昧マッチ
		$age_meta[] = [ 'key' => 'resident_age', 'value' => $a, 'compare' => 'LIKE' ];
	}
	$query_args['meta_query'] = $age_meta;
}

$resident_query = new WP_Query( $query_args );

// PICK UP: オプションページで選択した投稿 → なければ最新3件
$pickup_ids = function_exists( 'sc_get_pickup_ids' ) ? sc_get_pickup_ids( 'resident' ) : [];
if ( $pickup_ids ) {
	$pickup_query = new WP_Query( [
		'post_type'      => CPT_RESIDENT,
		'post__in'       => $pickup_ids,
		'orderby'        => 'post__in',
		'posts_per_page' => -1,
		'no_found_rows'  => true,
	] );
} else {
	$pickup_query = new WP_Query( [
		'post_type'      => CPT_RESIDENT,
		'posts_per_page' => 3,
		'orderby'        => 'date',
		'order'          => 'DESC',
		'no_found_rows'  => true,
	] );
}

// サイドバー: 新着5件
$sidebar_latest = new WP_Query( [
	'post_type'      => CPT_RESIDENT,
	'posts_per_page' => 5,
	'orderby'        => 'date',
	'order'          => 'DESC',
	'no_found_rows'  => true,
] );

get_header();
?>
<main id="main-content">

	<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>

	<!-- ページヘッダー -->
	<div class="p-resident-archive__header">
		<div class="p-resident-archive__header-inner">
			<h1 class="p-resident-archive__title">お隣さんの話</h1>
			<p class="p-resident-archive__sub">七間町に暮らす人たちのストーリー。</p>
		</div>
		<!-- /.p-resident-archive__header-inner -->
	</div>
	<!-- /.p-resident-archive__header -->

	<div class="p-resident-archive">
		<div class="p-resident-archive__inner">

			<!-- メインコンテンツ -->
			<div class="p-resident-archive__main">

				<!-- PICK UP セクション（アーカイブカードと同じスタイル） -->
				<?php if ( $pickup_query->have_posts() ) :
					$pickup_count = $pickup_query->post_count;
					$is_slider    = $pickup_count >= 4; // 4以上でスライダー化、SP は CSS で常時スライダー
				?>
				<section class="p-resident-archive__featured<?php echo $is_slider ? ' is-slider' : ''; ?>" aria-label="PICK UP">
					<header class="p-resident-archive__featured-head">
						<span class="c-pickup-title"><span class="c-pickup-title__badge">PICK UP</span> 注目のお話</span>
					</header>
					<div class="p-resident-archive__featured-list js-resident-pickup">
						<?php while ( $pickup_query->have_posts() ) : $pickup_query->the_post();
							$pid        = get_the_ID();
							$name       = get_field( 'resident_name', $pid ) ?: get_the_title( $pid );
							$age        = get_field( 'resident_age', $pid );
							$occupation = get_field( 'resident_occupation', $pid );
							$quote      = get_field( 'resident_quote', $pid );
							$portrait   = get_field( 'resident_portrait', $pid );
							if ( $portrait && is_array( $portrait ) ) {
								$thumb_url = $portrait['sizes']['large'] ?? $portrait['url'];
							} else {
								$thumb_url = get_the_post_thumbnail_url( $pid, 'large' ) ?: '';
							}
							$role = trim( implode( '　', array_filter( [ $occupation, $age ] ) ) );
						?>
						<a class="p-resident-card" href="<?php the_permalink(); ?>">
							<article class="p-resident-card__hero">
								<?php if ( $quote || $name ) : ?>
								<div class="p-resident-card__caption">
									<?php if ( $quote ) : ?>
									<p class="p-resident-card__quote">「<?php echo esc_html( $quote ); ?>」</p>
									<?php endif; ?>
									<p class="p-resident-card__title"><?php echo esc_html( $name ); ?></p>
								</div>
								<?php endif; ?>
								<div class="p-resident-card__img">
									<?php if ( $thumb_url ) : ?>
									<img class="u-img-cover p-resident-card__img-inner" src="<?php echo esc_url( $thumb_url ); ?>" alt="" aria-hidden="true" loading="lazy">
									<?php else : ?>
									<div class="p-resident-card__img-placeholder" aria-hidden="true"></div>
									<?php endif; ?>
								</div>
								<time class="p-resident-card__date" datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>"><?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?></time>
							</article>
							<footer class="p-resident-card__foot">
								<?php if ( $thumb_url ) : ?>
								<span class="p-resident-card__foot-avatar"><img class="p-resident-card__foot-avatar-img" src="<?php echo esc_url( $thumb_url ); ?>" alt="" aria-hidden="true" loading="lazy"></span>
								<?php endif; ?>
								<span class="p-resident-card__foot-body">
									<?php if ( $role ) : ?>
									<span class="p-resident-card__foot-role"><?php echo esc_html( $role ); ?></span>
									<?php endif; ?>
									<span class="p-resident-card__foot-name"><?php echo esc_html( $name ); ?></span>
								</span>
							</footer>
						</a>
						<?php endwhile; wp_reset_postdata(); ?>
					</div>
				</section>
				<?php endif; ?>

				<!-- 一覧グリッド（昭和村風） -->
				<?php if ( $resident_query->have_posts() ) : ?>
				<p class="p-resident-archive__count">全<?php echo esc_html( $resident_query->found_posts ); ?>件</p>
				<div class="p-resident-archive__grid">
					<?php $card_no = $resident_query->found_posts - ( ( $paged - 1 ) * 12 );
					while ( $resident_query->have_posts() ) : $resident_query->the_post();
						$pid        = get_the_ID();
						$name       = get_field( 'resident_name', $pid ) ?: get_the_title( $pid );
						$age        = get_field( 'resident_age', $pid );
						$occupation = get_field( 'resident_occupation', $pid );
						$years      = get_field( 'resident_years', $pid );
						$quote      = get_field( 'resident_quote', $pid );
						$portrait   = get_field( 'resident_portrait', $pid );
						if ( $portrait && is_array( $portrait ) ) {
							$thumb_url = $portrait['sizes']['large'] ?? $portrait['url'];
						} else {
							$thumb_url = get_the_post_thumbnail_url( $pid, 'large' ) ?: '';
						}
						$role = trim( implode( '　', array_filter( [ $occupation, $age ] ) ) );
					?>
					<a class="p-resident-card" href="<?php the_permalink(); ?>">
						<article class="p-resident-card__hero">
							<span class="p-resident-card__no" aria-hidden="true"><?php echo (int) $card_no; ?></span>
							<?php if ( $quote || $name ) : ?>
							<div class="p-resident-card__caption">
								<?php if ( $quote ) : ?>
								<p class="p-resident-card__quote">「<?php echo esc_html( $quote ); ?>」</p>
								<?php endif; ?>
								<p class="p-resident-card__title"><?php echo esc_html( $name ); ?></p>
							</div>
							<?php endif; ?>
							<div class="p-resident-card__img">
								<?php if ( $thumb_url ) : ?>
								<img class="u-img-cover p-resident-card__img-inner" src="<?php echo esc_url( $thumb_url ); ?>" alt="" aria-hidden="true" loading="lazy">
								<?php else : ?>
								<div class="p-resident-card__img-placeholder" aria-hidden="true"></div>
								<?php endif; ?>
							</div>
							<time class="p-resident-card__date" datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>"><?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?></time>
						</article>
						<footer class="p-resident-card__foot">
							<?php if ( $thumb_url ) : ?>
							<span class="p-resident-card__foot-avatar"><img class="p-resident-card__foot-avatar-img" src="<?php echo esc_url( $thumb_url ); ?>" alt="" aria-hidden="true" loading="lazy"></span>
							<?php endif; ?>
							<span class="p-resident-card__foot-body">
								<?php if ( $role ) : ?>
								<span class="p-resident-card__foot-role"><?php echo esc_html( $role ); ?></span>
								<?php endif; ?>
								<span class="p-resident-card__foot-name"><?php echo esc_html( $name ); ?></span>
							</span>
						</footer>
					</a>
					<?php $card_no--; endwhile; wp_reset_postdata(); ?>
				</div>
				<!-- /.p-resident-archive__grid -->

				<?php if ( $resident_query->max_num_pages > 1 ) : ?>
				<div class="p-resident-archive__pagination">
					<?php
					echo paginate_links( [
						'total'     => $resident_query->max_num_pages,
						'current'   => $paged,
						'prev_text' => '‹',
						'next_text' => '›',
					] );
					?>
				</div>
				<!-- /.p-resident-archive__pagination -->
				<?php endif; ?>

				<?php else : ?>
				<p class="p-resident-archive__empty">登録されているインタビューはまだありません。</p>
				<?php endif; ?>

			</div>
			<!-- /.p-resident-archive__main -->

			<!-- サイドバー：お隣さんを探す -->
			<aside class="c-filter-sidebar" aria-label="絞り込み">
				<form id="js-resident-filter" class="c-filter-sidebar__filter" method="get" action="<?php echo esc_url( get_post_type_archive_link( CPT_RESIDENT ) ); ?>">

					<div class="c-filter-sidebar__head">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
						<h2 class="c-filter-sidebar__title">お隣さんを探す</h2>
					</div>

					<div class="c-filter-sidebar__group">
						<label class="c-filter-sidebar__label" for="resident-kw">キーワード検索</label>
						<input id="resident-kw" class="c-filter-sidebar__search" type="search" name="kw" placeholder="名前、職業..." value="<?php echo esc_attr( $filter_kw ); ?>">
					</div>

					<div class="c-filter-sidebar__group">
						<p class="c-filter-sidebar__label">年代</p>
						<div class="c-filter-sidebar__chips">
							<button type="button" class="c-filter-sidebar__chip js-chips-clear<?php echo ! $filter_ages ? ' is-active' : ''; ?>" data-group="age">すべて</button>
							<?php foreach ( $age_choices as $val => $lbl ) : ?>
							<label class="c-filter-sidebar__chip<?php echo in_array( $val, $filter_ages, true ) ? ' is-active' : ''; ?>">
								<input class="u-sr-only" type="checkbox" name="age[]" value="<?php echo esc_attr( $val ); ?>"<?php echo in_array( $val, $filter_ages, true ) ? ' checked' : ''; ?>>
								<?php echo esc_html( $lbl ); ?>
							</label>
							<?php endforeach; ?>
						</div>
					</div>

					<div class="c-filter-sidebar__result">
						<span class="c-filter-sidebar__result-label">検索結果</span>
						<p class="c-filter-sidebar__result-value"><?php echo (int) $resident_query->found_posts; ?><span class="c-filter-sidebar__result-unit">件</span></p>
					</div>
				</form>
				<script>
				(function () {
					var form = document.getElementById('js-resident-filter');
					if (!form) return;
					var timer;
					form.querySelectorAll('input[type="search"]').forEach(function (inp) {
						inp.addEventListener('input', function () {
							clearTimeout(timer);
							timer = setTimeout(function () { form.submit(); }, 600);
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

				<!-- 新着情報 -->
				<?php if ( $sidebar_latest->have_posts() ) : ?>
				<div class="p-resident-sidebar__block">
					<h2 class="p-resident-sidebar__block-title">
						<svg class="p-resident-sidebar__block-title-icon" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><use href="#icon-clock-solid"></use></svg>
						新着情報
					</h2>
					<ul class="p-resident-sidebar__list">
						<?php while ( $sidebar_latest->have_posts() ) : $sidebar_latest->the_post();
							$s_pid      = get_the_ID();
							$s_name     = get_field( 'resident_name', $s_pid ) ?: get_the_title();
							$s_portrait = get_field( 'resident_portrait', $s_pid );
							if ( $s_portrait && is_array( $s_portrait ) ) {
								$s_thumb = $s_portrait['sizes']['thumbnail'] ?? $s_portrait['url'];
							} else {
								$s_thumb = get_the_post_thumbnail_url( $s_pid, 'thumbnail' ) ?: '';
							}
						?>
						<li class="p-resident-sidebar__list-item">
							<a class="p-resident-sidebar__list-link" href="<?php the_permalink(); ?>">
								<div class="p-resident-sidebar__list-thumb" aria-hidden="true">
									<?php if ( $s_thumb ) : ?>
									<img class="p-resident-sidebar__list-thumb-img" src="<?php echo esc_url( $s_thumb ); ?>" alt="" aria-hidden="true" loading="lazy" width="48" height="48">
									<?php endif; ?>
								</div>
								<div class="p-resident-sidebar__list-body">
									<span class="p-resident-sidebar__list-name"><?php echo esc_html( $s_name ); ?></span>
									<span class="p-resident-sidebar__list-date"><?php echo get_the_date( 'y.m.d' ); ?></span>
								</div>
							</a>
						</li>
						<?php endwhile; wp_reset_postdata(); ?>
					</ul>
				</div>
				<?php endif; ?>

				<!-- 掲載依頼 CTA -->
				<div class="c-sidebar-cta">
					<h3 class="c-sidebar-cta__title">あなたのストーリーを聞かせてください</h3>
					<p class="c-sidebar-cta__text">七間町で暮らす皆さんの「お隣さんの話」を募集しています。</p>
					<a class="c-sidebar-cta__btn" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">掲載依頼</a>
				</div>

			</aside>
			<!-- /.c-filter-sidebar -->

		</div>
		<!-- /.p-resident-archive__inner -->
	</div>
	<!-- /.p-resident-archive -->

</main>
<?php get_footer(); ?>
