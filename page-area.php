<?php
/**
 * エリアガイド下層ページ（/area/{slug}）
 * ルーティングは inc/area.php の template_include 経由
 * データは sc_get_area() / sc_get_area_detail()
 */

$slug = get_query_var( 'sc_area' );
$area = sc_get_area( $slug );
if ( ! $area ) {
	// 通常ここには来ない（template_include で 404 済み）
	get_header();
	get_footer();
	return;
}
$detail = sc_get_area_detail( $slug );

// 大エリアに属する TAX_AREA（サブ地名）ターム ID に解決
$area_term_ids = [];
foreach ( ( $area['area_terms'] ?? [] ) as $term_name ) {
	$t = get_term_by( 'name', $term_name, TAX_AREA );
	if ( $t && ! is_wp_error( $t ) ) {
		$area_term_ids[] = $t->term_id;
	}
}

// スポット・イベントをサブ地名タームで取得（無ければ各セクション非表示）
$spot_archive = get_post_type_archive_link( CPT_SPOT );
$spot_tax_query = $area_term_ids ? [ [ 'taxonomy' => TAX_AREA, 'field' => 'term_id', 'terms' => $area_term_ids ] ] : [ [ 'taxonomy' => TAX_AREA, 'field' => 'term_id', 'terms' => [ 0 ] ] ];
$area_spots = new WP_Query( [
	'post_type'      => CPT_SPOT,
	'posts_per_page' => 4,
	'no_found_rows'  => true,
	'tax_query'      => $spot_tax_query,
] );
$area_events = new WP_Query( [
	'post_type'      => CPT_EVENT,
	'posts_per_page' => 3,
	'no_found_rows'  => true,
	'tax_query'      => $spot_tax_query,
] );

// 他エリア（自分を除く）
$other_areas = array_values( array_filter( sc_get_areas(), function ( $a ) use ( $slug ) {
	return $a['slug'] !== $slug;
} ) );

// イベント終了判定（終了日 or 開始日 < 今日）
$sc_area_event_ended = function ( $pid ) {
	$end = get_field( 'event_date_end', $pid ) ?: get_field( 'event_date_start', $pid );
	return $end && strtotime( $end ) < strtotime( date_i18n( 'Y-m-d' ) );
};

get_header();
?>
<main id="main-content" class="p-area p-area--<?php echo esc_attr( $slug ); ?>">

	<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>

	<!-- ─── ヒーロー ── -->
	<section class="p-area__hero">
		<div class="p-area__hero-inner">
			<h1 class="p-area__hero-title"><?php echo esc_html( $area['card_title'] ); ?></h1>
			<p class="p-area__hero-desc"><?php echo esc_html( $area['desc'] ); ?></p>
		</div>
	</section>
	<!-- /.p-area__hero -->

	<?php if ( ! empty( $detail['features'] ) ) : ?>
	<!-- ─── 特徴（キャッチ + 3カード） ── -->
	<section class="p-area__intro">
		<div class="p-area__intro-inner">
			<?php if ( ! empty( $detail['intro_en'] ) ) : ?>
			<p class="p-area__intro-en"><?php echo esc_html( $detail['intro_en'] ); ?></p>
			<?php endif; ?>
			<?php if ( ! empty( $detail['intro_title'] ) ) : ?>
			<h2 class="p-area__intro-title"><?php echo esc_html( $detail['intro_title'] ); ?></h2>
			<?php endif; ?>

			<ul class="p-area__feature-grid">
				<?php foreach ( $detail['features'] as $f ) : ?>
				<li class="p-area__feature">
					<span class="p-area__feature-icon">
						<svg aria-hidden="true" focusable="false" width="24" height="24"><use href="#<?php echo esc_attr( $f['icon'] ); ?>"></use></svg>
					</span>
					<h3 class="p-area__feature-title"><?php echo esc_html( $f['title'] ); ?></h3>
					<p class="p-area__feature-text"><?php echo esc_html( $f['text'] ); ?></p>
				</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>
	<!-- /.p-area__intro -->
	<?php endif; ?>

	<?php if ( ! empty( $detail['towns'] ) ) : ?>
	<!-- ─── エリア内の町 ── -->
	<section class="p-area__towns">
		<div class="p-area__towns-inner">
			<header class="p-area__section-head">
				<p class="p-area__section-en">Town Introduction</p>
				<h2 class="p-area__section-title">エリア内の町</h2>
			</header>
			<div class="p-area__town-list">
				<?php foreach ( $detail['towns'] as $t ) : ?>
				<article class="p-area__town">
					<div class="p-area__town-media">
						<img src="<?php echo esc_url( $t['img'] ?? sc_no_image_url() ); ?>" alt="" aria-hidden="true" loading="lazy" width="600" height="400">
					</div>
					<!-- /.p-area__town-media -->
					<div class="p-area__town-body">
						<h3 class="p-area__town-name"><?php echo esc_html( $t['name'] ); ?></h3>
						<p class="p-area__town-text"><?php echo esc_html( $t['text'] ); ?></p>
					</div>
					<!-- /.p-area__town-body -->
				</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<!-- /.p-area__towns -->
	<?php endif; ?>

	<?php if ( ! empty( $detail['history'] ) ) : ?>
	<!-- ─── 歴史・文化 ── -->
	<section class="p-area__history">
		<div class="p-area__history-inner">
			<header class="p-area__section-head">
				<p class="p-area__section-en">History &amp; Culture</p>
				<h2 class="p-area__section-title p-area__section-title--icon">
					<svg aria-hidden="true" focusable="false" width="24" height="24"><use href="#icon-clock"></use></svg>
					歴史・文化
				</h2>
			</header>

			<?php $history_total = count( $detail['history'] ); ?>
			<div class="p-area__history-tabs js-history-tabs" role="tablist" aria-label="歴史・文化の時代">
				<?php foreach ( $detail['history'] as $i => $h ) : ?>
				<button type="button" class="p-area__history-tab<?php echo 0 === $i ? ' is-active' : ''; ?>" role="tab" aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>" aria-controls="history-panel-<?php echo (int) $i; ?>" id="history-tab-<?php echo (int) $i; ?>" data-index="<?php echo (int) $i; ?>">
					<?php echo esc_html( $h['era'] ); ?>
				</button>
				<?php endforeach; ?>
			</div>

			<div class="p-area__history-panels js-history-panels">
				<?php foreach ( $detail['history'] as $i => $h ) : ?>
				<div class="p-area__history-panel<?php echo 0 === $i ? ' is-active' : ''; ?>" role="tabpanel" id="history-panel-<?php echo (int) $i; ?>" aria-labelledby="history-tab-<?php echo (int) $i; ?>" data-index="<?php echo (int) $i; ?>"<?php echo 0 === $i ? '' : ' hidden'; ?>>
					<div class="p-area__history-card">
						<span class="p-area__history-icon">
							<svg aria-hidden="true" focusable="false" width="24" height="24"><use href="#icon-book"></use></svg>
						</span>
						<div class="p-area__history-body">
							<span class="p-area__history-badge"><?php echo esc_html( $h['era'] ); ?></span>
							<h3 class="p-area__history-title"><?php echo esc_html( $h['title'] ); ?></h3>
							<p class="p-area__history-text"><?php echo esc_html( $h['text'] ); ?></p>
						</div>
						<!-- /.p-area__history-body -->
						<nav class="p-area__history-nav" aria-label="時代の切り替え">
							<button type="button" class="p-area__history-prev js-history-prev"<?php echo 0 === $i ? ' disabled' : ''; ?>>
								<svg aria-hidden="true" focusable="false" width="16" height="16"><use href="#icon-chevron-left"></use></svg>
								前へ
							</button>
							<span class="p-area__history-count"><?php echo (int) $i + 1; ?> / <?php echo (int) $history_total; ?></span>
							<button type="button" class="p-area__history-next js-history-next"<?php echo ( $i === $history_total - 1 ) ? ' disabled' : ''; ?>>
								次へ
								<svg aria-hidden="true" focusable="false" width="16" height="16"><use href="#icon-chevron-right"></use></svg>
							</button>
						</nav>
					</div>
					<!-- /.p-area__history-card -->
				</div>
				<?php endforeach; ?>
			</div>
			<!-- /.p-area__history-panels -->
		</div>
	</section>
	<!-- /.p-area__history -->
	<?php endif; ?>

	<?php if ( $area_spots->have_posts() ) : ?>
	<!-- ─── おすすめスポット ── -->
	<section class="p-area__spots">
		<div class="p-area__spots-inner">
			<header class="p-area__section-head">
				<p class="p-area__section-en">Recommended Spots</p>
				<h2 class="p-area__section-title">観光地や人気をピックアップ！</h2>
				<p class="p-area__section-lead"><?php echo esc_html( $area['card_title'] ); ?>エリアのおすすめスポット</p>
			</header>
			<div class="p-area__spot-grid">
				<?php while ( $area_spots->have_posts() ) : $area_spots->the_post();
					$pid   = get_the_ID();
					$thumb = sc_thumbnail_url( $pid, 'medium_large' );
					$terms = get_the_terms( $pid, TAX_AREA );
					$term  = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0] : null;
				?>
				<a class="p-area__spot-card" href="<?php the_permalink(); ?>">
					<div class="p-area__spot-media">
						<img class="u-img-cover--transition" src="<?php echo esc_url( $thumb ); ?>" alt="" aria-hidden="true" loading="lazy" width="600" height="400">
					</div>
					<!-- /.p-area__spot-media -->
					<div class="p-area__spot-body">
						<h3 class="p-area__spot-name"><?php the_title(); ?></h3>
						<p class="p-area__spot-text"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 40 ) ); ?></p>
						<?php if ( $term ) : ?>
						<span class="p-area__spot-tag"><?php echo esc_html( $term->name ); ?></span>
						<?php endif; ?>
					</div>
					<!-- /.p-area__spot-body -->
				</a>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
			<?php if ( $spot_archive ) : ?>
			<div class="p-area__spots-more">
				<a class="p-area__more-btn" href="<?php echo esc_url( add_query_arg( 'area', $slug, $spot_archive ) ); ?>">もっと見る
					<svg aria-hidden="true" focusable="false" width="16" height="16"><use href="#icon-chevron-right"></use></svg>
				</a>
			</div>
			<?php endif; ?>
		</div>
	</section>
	<!-- /.p-area__spots -->
	<?php endif; ?>

	<?php if ( ! empty( $detail['gourmet'] ) ) : ?>
	<!-- ─── おすすめグルメ ── -->
	<section class="p-area__gourmet">
		<div class="p-area__gourmet-inner">
			<header class="p-area__section-head">
				<p class="p-area__section-en">Gourmet Guide</p>
				<h2 class="p-area__section-title p-area__section-title--icon">
					<svg aria-hidden="true" focusable="false" width="24" height="24"><use href="#icon-cafe"></use></svg>
					おすすめグルメ
				</h2>
			</header>
			<div class="p-area__gourmet-grid">
				<?php foreach ( $detail['gourmet'] as $g ) : ?>
				<article class="p-area__gourmet-card">
					<span class="p-area__gourmet-icon">
						<svg aria-hidden="true" focusable="false" width="20" height="20"><use href="#icon-utensils"></use></svg>
					</span>
					<span class="p-area__gourmet-cat"><?php echo esc_html( $g['cat'] ); ?></span>
					<h3 class="p-area__gourmet-name"><?php echo esc_html( $g['name'] ); ?></h3>
					<p class="p-area__gourmet-text"><?php echo esc_html( $g['text'] ); ?></p>
					<p class="p-area__gourmet-price">目安: <?php echo esc_html( $g['price'] ); ?></p>
				</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<!-- /.p-area__gourmet -->
	<?php endif; ?>

	<?php if ( ! empty( $detail['course'] ) ) : ?>
	<!-- ─── おすすめモデルコース ── -->
	<section class="p-area__course">
		<div class="p-area__course-inner">
			<header class="p-area__section-head">
				<p class="p-area__section-en">Model Course</p>
				<h2 class="p-area__section-title p-area__section-title--icon">
					<svg aria-hidden="true" focusable="false" width="24" height="24"><use href="#icon-map-pin"></use></svg>
					おすすめモデルコース
				</h2>
			</header>
			<ol class="p-area__course-list">
				<?php foreach ( $detail['course'] as $c ) : ?>
				<li class="p-area__course-step">
					<span class="p-area__course-time"><?php echo esc_html( $c['time'] ); ?></span>
					<div class="p-area__course-body">
						<h3 class="p-area__course-title"><?php echo esc_html( $c['title'] ); ?></h3>
						<p class="p-area__course-text"><?php echo esc_html( $c['text'] ); ?></p>
					</div>
					<!-- /.p-area__course-body -->
				</li>
				<?php endforeach; ?>
			</ol>
		</div>
	</section>
	<!-- /.p-area__course -->
	<?php endif; ?>

	<?php if ( ! empty( $detail['access'] ) ) : ?>
	<!-- ─── アクセス ── -->
	<section class="p-area__access">
		<div class="p-area__access-inner">
			<header class="p-area__section-head">
				<p class="p-area__section-en">Access</p>
				<h2 class="p-area__section-title">アクセス</h2>
			</header>
			<div class="p-area__access-grid">
				<?php foreach ( $detail['access'] as $ac ) : ?>
				<div class="p-area__access-item">
					<span class="p-area__access-icon">
						<svg aria-hidden="true" focusable="false" width="24" height="24"><use href="#<?php echo esc_attr( $ac['icon'] ); ?>"></use></svg>
					</span>
					<div class="p-area__access-body">
						<h3 class="p-area__access-label"><?php echo esc_html( $ac['label'] ); ?><span class="p-area__access-time"><?php echo esc_html( $ac['time'] ); ?></span></h3>
						<p class="p-area__access-text"><?php echo esc_html( $ac['text'] ); ?></p>
					</div>
					<!-- /.p-area__access-body -->
				</div>
				<!-- /.p-area__access-item -->
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<!-- /.p-area__access -->
	<?php endif; ?>

	<?php if ( $area_events->have_posts() ) : ?>
	<!-- ─── このエリアのイベント ── -->
	<section class="p-area__events">
		<div class="p-area__events-inner">
			<header class="p-area__section-head">
				<p class="p-area__section-en">Events</p>
				<h2 class="p-area__section-title p-area__section-title--icon">
					<svg aria-hidden="true" focusable="false" width="24" height="24"><use href="#icon-calendar"></use></svg>
					このエリアのイベント
				</h2>
			</header>
			<div class="p-area__event-grid">
				<?php while ( $area_events->have_posts() ) : $area_events->the_post();
					$pid    = get_the_ID();
					$thumb  = sc_thumbnail_url( $pid, 'medium_large' );
					$start  = get_field( 'event_date_start', $pid );
					$time   = get_field( 'event_time', $pid );
					$venue  = get_field( 'event_venue', $pid );
					$terms  = get_the_terms( $pid, TAX_AREA );
					$term   = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0] : null;
					$ended  = $sc_area_event_ended( $pid );
				?>
				<a class="p-area__event-card" href="<?php the_permalink(); ?>">
					<div class="p-area__event-media">
						<img class="u-img-cover--transition" src="<?php echo esc_url( $thumb ); ?>" alt="" aria-hidden="true" loading="lazy" width="400" height="300">
						<div class="p-area__event-flags">
							<?php if ( $term ) : ?>
							<span class="p-area__event-area"><?php echo esc_html( $term->name ); ?></span>
							<?php endif; ?>
							<?php if ( $ended ) : ?>
							<span class="p-area__event-ended">終了</span>
							<?php endif; ?>
						</div>
						<!-- /.p-area__event-flags -->
					</div>
					<!-- /.p-area__event-media -->
					<div class="p-area__event-body">
						<h3 class="p-area__event-name"><?php the_title(); ?></h3>
						<div class="p-area__event-meta-list">
							<?php if ( $start ) : ?>
							<p class="p-area__event-meta">
								<svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-calendar"></use></svg>
								<?php echo esc_html( mysql2date( 'Y年n月j日', $start ) ); ?>
							</p>
							<?php endif; ?>
							<?php if ( $time ) : ?>
							<p class="p-area__event-meta">
								<svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-clock"></use></svg>
								<?php echo esc_html( $time ); ?>
							</p>
							<?php endif; ?>
							<?php if ( $venue ) : ?>
							<p class="p-area__event-meta">
								<svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-map-pin"></use></svg>
								<?php echo esc_html( $venue ); ?>
							</p>
							<?php endif; ?>
						</div>
						<!-- /.p-area__event-meta-list -->
						<p class="p-area__event-text"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 30 ) ); ?></p>
					</div>
					<!-- /.p-area__event-body -->
				</a>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
			<div class="p-area__events-more">
				<a class="p-area__more-btn" href="<?php echo esc_url( get_post_type_archive_link( CPT_EVENT ) ); ?>">イベント一覧を見る
					<svg aria-hidden="true" focusable="false" width="16" height="16"><use href="#icon-chevron-right"></use></svg>
				</a>
			</div>
		</div>
	</section>
	<!-- /.p-area__events -->
	<?php endif; ?>

	<!-- ─── 他のエリア ── -->
	<section class="p-area__others">
		<div class="p-area__others-inner">
			<header class="p-area__section-head">
				<p class="p-area__section-en">Other Areas</p>
				<h2 class="p-area__section-title">他のエリアも見てみよう</h2>
				<p class="p-area__section-lead">七間町周辺には魅力的なエリアがたくさんあります</p>
			</header>
			<div class="p-area__other-grid">
				<?php foreach ( $other_areas as $o ) : ?>
				<a class="p-area__other-card" href="<?php echo esc_url( home_url( '/area/' . $o['slug'] ) ); ?>">
					<h3 class="p-area__other-name"><?php echo esc_html( $o['card_title'] ); ?></h3>
					<p class="p-area__other-desc"><?php echo esc_html( $o['desc'] ); ?></p>
					<span class="p-area__other-more">詳しく見る
						<svg aria-hidden="true" focusable="false" width="16" height="16"><use href="#icon-chevron-right"></use></svg>
					</span>
				</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<!-- /.p-area__others -->

	<!-- ─── 観光情報に戻る ── -->
	<div class="p-area__back">
		<a class="p-area__back-btn" href="<?php echo esc_url( home_url( '/tourism/' ) ); ?>">観光情報に戻る</a>
	</div>
	<!-- /.p-area__back -->

</main>
<!-- /#main-content -->
<?php get_footer();
