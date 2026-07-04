<?php
/**
 * 観光情報ページ（/tourism/）
 * Manus /tourism のレイアウトをミラー
 */
get_header();

if ( function_exists( 'schema_tourist_destination' ) ) schema_tourist_destination();

// Pick up News: 最新 news 投稿1件
$pickup_news = get_posts( [
	'post_type'      => CPT_NEWS,
	'posts_per_page' => 1,
	'orderby'        => 'date',
	'order'          => 'DESC',
	'no_found_rows'  => true,
] );
$pickup = ! empty( $pickup_news ) ? $pickup_news[0] : null;

// イベント取得
$events_q = new WP_Query( [
	'post_type'      => CPT_EVENT,
	'posts_per_page' => 4,
	'orderby'        => 'date',
	'order'          => 'DESC',
	'no_found_rows'  => true,
] );

// スポットタクソノミー（タブ用）
$spot_types = get_terms( [ 'taxonomy' => TAX_SPOT_TYPE, 'hide_empty' => false ] );

// タームごとにスポットを取得（タブ切替用・各最大6件）
$spots_by_type = [];
if ( ! is_wp_error( $spot_types ) && $spot_types ) {
	foreach ( $spot_types as $st ) {
		$q = new WP_Query( [
			'post_type'      => CPT_SPOT,
			'posts_per_page' => 6,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'no_found_rows'  => true,
			'tax_query'      => [ [
				'taxonomy' => TAX_SPOT_TYPE,
				'field'    => 'term_id',
				'terms'    => $st->term_id,
			] ],
		] );
		$spots_by_type[ $st->slug ] = $q;
	}
}

// 人気散策コース（walk_course CPT から最新3件）
$walks_q = new WP_Query( [
	'post_type'      => CPT_WALK,
	'posts_per_page' => 3,
	'orderby'        => 'date',
	'order'          => 'DESC',
	'no_found_rows'  => true,
] );

// 想定タイムライン（人気散策コース全件分を生成 — クリックで切替）
$timelines        = []; // [ walk_id => [ 'title' => ..., 'steps' => [...] ] ]
$featured_walk_id = 0;
if ( $walks_q->have_posts() ) {
	foreach ( $walks_q->posts as $w ) {
		if ( ! $featured_walk_id ) { $featured_walk_id = $w->ID; }
		$walk_steps = get_field( 'walk_spots', $w->ID ) ?: [];
		$steps      = [];
		foreach ( $walk_steps as $i => $step ) {
			$ref_id = $step['ref'] ?? 0;
			if ( ! $ref_id ) continue;
			$note = get_field( 'spot_note', $ref_id ) ?: wp_trim_words( wp_strip_all_tags( get_field( 'spot_description', $ref_id ) ?: '' ), 40, '…' );
			$steps[] = [
				'step' => 'STEP' . ( $i + 1 ),
				'cat'  => get_the_title( $ref_id ),
				'desc' => $note,
				'time' => $step['time_to_next'] ?? '',
			];
		}
		$timelines[ $w->ID ] = [
			'title'     => get_the_title( $w->ID ),
			'permalink' => get_permalink( $w->ID ),
			'steps'     => $steps,
		];
	}
}

// エリアガイド（CSS Grid 12x12 上のホットスポット座標で実装）
// データは inc/area.php に一元化（下層ページと共通）
$areas = sc_get_areas();

// マップ画像（未配置時はフォールバック）
$area_map_path = get_template_directory() . '/assets/images/common/area-map.png';
$area_map_url  = file_exists( $area_map_path ) ? get_template_directory_uri() . '/assets/images/common/area-map.png' : sc_no_image_url();
?>

<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>

<article class="p-visit">

	<?php
	get_template_part( 'template-parts/components/page-hero', null, [
		'title' => '観光情報',
		'sub'   => '七間町の観光スポット、イベント、散策コースをご紹介。',
	] );
	?>

	<!-- ─── Pick up News ── -->
	<?php if ( $pickup ) : ?>
	<section class="p-visit__pickup" aria-label="ピックアップニュース">
		<a class="p-visit__pickup-inner" href="<?php echo esc_url( get_permalink( $pickup ) ); ?>">
			<span class="p-visit__pickup-label">Pick up News</span>
			<time class="p-visit__pickup-date" datetime="<?php echo esc_attr( get_the_date( 'Y-m-d', $pickup ) ); ?>">
				<?php echo esc_html( get_the_date( 'Y/n/j', $pickup ) ); ?>
			</time>
			<p class="p-visit__pickup-text"><?php echo esc_html( get_the_title( $pickup ) ); ?></p>
		</a>
		<!-- /.p-visit__pickup-inner -->
	</section>
	<!-- /.p-visit__pickup -->
	<?php endif; ?>

	<!-- ─── 人気の散策コース ── -->
	<section class="p-visit__walks" aria-labelledby="visit-walks-title">
		<div class="p-visit__walks-inner">
			<h2 class="p-visit__walks-title" id="visit-walks-title">人気の散策コース</h2>
			<p class="p-visit__walks-lead">おすすめの観光スポット、七間町の魅力を深掘り探索ルートをご紹介</p>

			<?php if ( $walks_q->have_posts() ) : ?>
			<div class="p-visit__walks-grid" role="tablist" aria-label="散策コース選択">
				<?php while ( $walks_q->have_posts() ) : $walks_q->the_post();
					$wid     = get_the_ID();
					$wthumb  = sc_thumbnail_url( $wid, 'medium_large' );
					$is_active = ( $wid === $featured_walk_id );
				?>
				<button type="button"
					class="p-visit__walk-card js-walk-tab<?php echo $is_active ? ' is-active' : ''; ?>"
					role="tab"
					aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
					aria-controls="visit-timeline-panel"
					data-walk-id="<?php echo esc_attr( $wid ); ?>">
					<div class="p-visit__walk-card-img">
						<picture class="u-picture-fill">
							<img class="u-img-cover" src="<?php echo esc_url( $wthumb ); ?>" alt="" aria-hidden="true" loading="lazy" width="400" height="300">
						</picture>
					</div>
					<div class="p-visit__walk-card-body">
						<h3 class="p-visit__walk-card-title"><?php the_title(); ?></h3>
						<p class="p-visit__walk-card-desc"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 40, '…' ) ); ?></p>
					</div>
				</button>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
			<!-- /.p-visit__walks-grid -->
			<?php endif; ?>

			<!-- 想定タイムライン（散策コースカードクリックで切替） -->
			<?php if ( $timelines && $featured_walk_id ) :
				$initial = $timelines[ $featured_walk_id ];
			?>
			<div class="p-visit__timeline" id="visit-timeline-panel" role="tabpanel" aria-live="polite">
				<header class="p-visit__timeline-head">
					<h3 class="p-visit__timeline-title">【本誌注目】想定タイムライン</h3>
					<a class="p-visit__timeline-link js-walk-detail-link" href="<?php echo esc_url( $initial['permalink'] ); ?>">
						詳しくはコチラ→
					</a>
				</header>
				<div class="p-visit__timeline-table-wrap">
					<table class="p-visit__timeline-table">
						<thead>
							<tr>
								<th scope="col">ステップ</th>
								<th scope="col">観光情報</th>
								<th scope="col">概要</th>
								<th scope="col">所要時間</th>
							</tr>
						</thead>
						<tbody class="js-walk-timeline-body">
							<?php foreach ( $initial['steps'] as $t ) : ?>
							<tr>
								<td class="p-visit__timeline-step"><?php echo esc_html( $t['step'] ?? '' ); ?></td>
								<td><?php echo esc_html( $t['cat'] ?? '' ); ?></td>
								<td><?php echo esc_html( $t['desc'] ?? '' ); ?></td>
								<td><?php echo esc_html( $t['time'] ?? '' ); ?></td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
				<!-- /.p-visit__timeline-table-wrap -->
			</div>
			<!-- /.p-visit__timeline -->

			<script id="js-walk-timelines-data" type="application/json"><?php
				// 全コース分のタイムラインデータ
				echo wp_json_encode( $timelines, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
			?></script>
			<script>
			(function () {
				var dataEl = document.getElementById('js-walk-timelines-data');
				if (!dataEl) return;
				var data = {};
				try { data = JSON.parse(dataEl.textContent || '{}'); } catch (e) { return; }
				var tabs = document.querySelectorAll('.js-walk-tab');
				var tbody = document.querySelector('.js-walk-timeline-body');
				var link = document.querySelector('.js-walk-detail-link');
				if (!tabs.length || !tbody) return;

				tabs.forEach(function (btn) {
					btn.addEventListener('click', function () {
						var id = btn.dataset.walkId;
						var d = data[id];
						if (!d) return;

						tabs.forEach(function (b) {
							b.classList.remove('is-active');
							b.setAttribute('aria-selected', 'false');
						});
						btn.classList.add('is-active');
						btn.setAttribute('aria-selected', 'true');

						var html = '';
						(d.steps || []).forEach(function (t) {
							html += '<tr>' +
								'<td class="p-visit__timeline-step">' + (t.step || '') + '</td>' +
								'<td>' + (t.cat || '') + '</td>' +
								'<td>' + (t.desc || '') + '</td>' +
								'<td>' + (t.time || '') + '</td>' +
							'</tr>';
						});
						tbody.innerHTML = html;
						if (link && d.permalink) { link.setAttribute('href', d.permalink); }
					});
				});
			}());
			</script>
			<?php endif; ?>
		</div>
		<!-- /.p-visit__walks-inner -->
	</section>
	<!-- /.p-visit__walks -->

	<!-- ─── イベント ── -->
	<section class="p-visit__events" aria-labelledby="visit-events-title">
		<div class="p-visit__events-inner">
			<h2 class="p-visit__events-title" id="visit-events-title">イベント</h2>

			<?php if ( $events_q->have_posts() ) : ?>
			<div class="p-visit__events-grid">
				<?php while ( $events_q->have_posts() ) : $events_q->the_post();
					$eid    = get_the_ID();
					$ethumb = sc_thumbnail_url( $eid, 'medium' );
					$edate  = get_post_meta( $eid, 'event_start_date', true );
					if ( ! $edate ) { $edate = get_the_date( 'Y/n/j', $eid ); }
				?>
				<a class="p-visit__event-card" href="<?php the_permalink(); ?>">
					<div class="p-visit__event-card-img">
						<picture class="u-picture-fill">
							<img class="u-img-cover--transition" src="<?php echo esc_url( $ethumb ); ?>" alt="" aria-hidden="true" loading="lazy" width="200" height="150">
						</picture>
					</div>
					<div class="p-visit__event-card-body">
						<p class="p-visit__event-card-date"><?php echo esc_html( $edate ); ?></p>
						<h3 class="p-visit__event-card-title"><?php the_title(); ?></h3>
						<p class="p-visit__event-card-desc"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 30, '…' ) ); ?></p>
					</div>
				</a>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
			<!-- /.p-visit__events-grid -->
			<?php endif; ?>

			<div class="p-visit__events-more">
				<a class="c-btn p-visit__more-btn" href="<?php echo esc_url( get_post_type_archive_link( CPT_EVENT ) ); ?>">
					もっと見る
					<svg class="c-btn__icon" aria-hidden="true" focusable="false" width="16" height="16"><use href="#icon-chevron-right"></use></svg>
				</a>
			</div>
		</div>
		<!-- /.p-visit__events-inner -->
	</section>
	<!-- /.p-visit__events -->

	<!-- ─── 観光スポット ── -->
	<section class="p-visit__spots" aria-labelledby="visit-spots-title">
		<div class="p-visit__spots-inner">
			<header class="p-visit__spots-head">
				<div class="p-visit__spots-head-title">
					<svg class="p-visit__spots-head-icon" aria-hidden="true" focusable="false" width="24" height="24"><use href="#icon-map-pin"></use></svg>
					<h2 class="p-visit__spots-title" id="visit-spots-title">観光スポット</h2>
				</div>
				<p class="p-visit__spots-en">SPOT</p>
			</header>

			<?php
			// spot_type 名 → アイコンID マッピング（Manus準拠）
			$spot_type_icons = [
				'観る'   => 'icon-eye',
				'買う'   => 'icon-shopping-bag',
				'食べる' => 'icon-utensils',
				'泊まる' => 'icon-bed',
				'知る'   => 'icon-graduation-cap',
				'その他' => 'icon-ellipsis',
			];
			?>
			<?php if ( ! is_wp_error( $spot_types ) && $spot_types ) : ?>
			<div class="p-visit__spots-tabs" role="tablist" aria-label="スポット種別">
				<?php foreach ( $spot_types as $i => $t ) :
					$icon = $spot_type_icons[ $t->name ] ?? 'icon-map-pin';
				?>
				<button type="button"
					class="p-visit__spots-tab<?php echo $i === 0 ? ' is-active' : ''; ?>"
					role="tab"
					aria-selected="<?php echo $i === 0 ? 'true' : 'false'; ?>"
					aria-controls="spot-panel-<?php echo esc_attr( $t->slug ); ?>"
					data-spot-type="<?php echo esc_attr( $t->slug ); ?>">
					<span class="p-visit__spots-tab-icon">
						<svg aria-hidden="true" focusable="false" width="20" height="20"><use href="#<?php echo esc_attr( $icon ); ?>"></use></svg>
					</span>
					<span class="p-visit__spots-tab-label"><?php echo esc_html( $t->name ); ?></span>
				</button>
				<?php endforeach; ?>
			</div>
			<!-- /.p-visit__spots-tabs -->

			<?php foreach ( $spot_types as $i => $t ) :
				$sq = $spots_by_type[ $t->slug ] ?? null;
			?>
			<div class="p-visit__spots-panel<?php echo $i === 0 ? ' is-active' : ''; ?>"
				id="spot-panel-<?php echo esc_attr( $t->slug ); ?>"
				role="tabpanel"
				<?php echo $i !== 0 ? 'hidden' : ''; ?>>
				<?php if ( $sq && $sq->have_posts() ) : ?>
				<div class="p-visit__spots-grid">
					<?php while ( $sq->have_posts() ) : $sq->the_post();
						$sid    = get_the_ID();
						$sthumb = sc_thumbnail_url( $sid, 'medium_large' );
						$sorg   = get_post_meta( $sid, 'spot_organization', true );
					?>
					<a class="p-visit__spot-card" href="<?php the_permalink(); ?>">
						<div class="p-visit__spot-card-img">
							<picture class="u-picture-fill">
								<img class="u-img-cover" src="<?php echo esc_url( $sthumb ); ?>" alt="" aria-hidden="true" loading="lazy" width="400" height="300">
							</picture>
						</div>
						<div class="p-visit__spot-card-body">
							<h3 class="p-visit__spot-card-title"><?php the_title(); ?></h3>
							<?php if ( $sorg ) : ?>
							<p class="p-visit__spot-card-org"><?php echo esc_html( $sorg ); ?></p>
							<?php endif; ?>
							<p class="p-visit__spot-card-desc"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 50, '…' ) ); ?></p>
						</div>
					</a>
					<?php endwhile; wp_reset_postdata(); ?>
				</div>
				<!-- /.p-visit__spots-grid -->
				<?php else : ?>
				<p class="p-visit__spots-empty">このカテゴリのスポットはまだありません。</p>
				<?php endif; ?>
				<div class="p-visit__spots-more">
					<a class="c-btn p-visit__more-btn"
						href="<?php echo esc_url( add_query_arg( 'type', $t->slug, home_url( '/spots/' ) ) ); ?>">
						もっと見る
						<svg class="c-btn__icon" aria-hidden="true" focusable="false" width="16" height="16"><use href="#icon-chevron-right"></use></svg>
					</a>
				</div>
			</div>
			<!-- /.p-visit__spots-panel -->
			<?php endforeach; ?>
			<?php endif; ?>
		</div>
		<!-- /.p-visit__spots-inner -->
	</section>
	<!-- /.p-visit__spots -->

	<!-- ─── エリアガイド ── -->
	<section class="p-visit__area" aria-labelledby="visit-area-title">
		<div class="p-visit__area-inner">
			<header class="p-visit__area-head">
				<div class="p-visit__area-head-title">
					<svg class="p-visit__area-head-icon" aria-hidden="true" focusable="false" width="24" height="24"><use href="#icon-map-pin"></use></svg>
					<h2 class="p-visit__area-title" id="visit-area-title">エリアガイド</h2>
				</div>
				<p class="p-visit__area-en">Area Guide</p>
				<p class="p-visit__area-lead">七間町は静岡市中心部に位置し、駿府城や浅間神社へのアクセスも良好です。徒歩圏内に多くの観光スポットが集まっており、5つのエリアでそれぞれ異なる魅力をお楽しみいただけます。</p>
			</header>

			<!-- レスポンシブ画像マップ（CSS Grid 12x12 でホットスポット配置） -->
			<div class="p-visit__area-map">
				<div class="p-visit__area-map-wrap">
					<img class="p-visit__area-map-img" src="<?php echo esc_url( $area_map_url ); ?>" alt="七間町エリアマップ" width="1024" height="768">
					<div class="p-visit__area-map-grid" aria-hidden="false">
						<?php foreach ( $areas as $a ) : ?>
						<a class="p-visit__area-hotspot"
							href="<?php echo esc_url( home_url( '/area/' . $a['slug'] ) ); ?>"
							aria-label="<?php echo esc_attr( $a['name'] ); ?>"
							style="grid-column: <?php echo esc_attr( $a['col'] ); ?>; grid-row: <?php echo esc_attr( $a['row'] ); ?>; --hot: <?php echo esc_attr( $a['color'] ); ?>;"></a>
						<?php endforeach; ?>
					</div>
					<!-- /.p-visit__area-map-grid -->
				</div>
				<!-- /.p-visit__area-map-wrap -->

				<p class="p-visit__area-note">※地名をクリックすると詳細ページへ飛びます</p>

				<!-- モバイル用フォールバック（地図上ホットスポットが押しづらいSP用に名称リスト） -->
				<ul class="p-visit__area-list">
					<?php foreach ( $areas as $a ) : ?>
					<li class="p-visit__area-item">
						<a class="p-visit__area-link" href="<?php echo esc_url( home_url( '/area/' . $a['slug'] ) ); ?>">
							<span class="p-visit__area-dot" style="background-color: <?php echo esc_attr( $a['color'] ); ?>;" aria-hidden="true"></span>
							<span class="p-visit__area-name"><?php echo esc_html( $a['name'] ); ?></span>
							<svg class="p-visit__area-arrow" aria-hidden="true" focusable="false" width="16" height="16"><use href="#icon-chevron-right"></use></svg>
						</a>
					</li>
					<?php endforeach; ?>
				</ul>
			</div>
			<!-- /.p-visit__area-map -->
		</div>
		<!-- /.p-visit__area-inner -->
	</section>
	<!-- /.p-visit__area -->

	<!-- ─── エリアを選んで詳しく見る ── -->
	<section class="p-visit__explore" aria-labelledby="visit-explore-title">
		<div class="p-visit__explore-inner">
			<header class="p-visit__explore-head">
				<p class="p-visit__explore-en">Explore Each Area</p>
				<h2 class="p-visit__explore-title" id="visit-explore-title">エリアを選んで詳しく見る</h2>
				<p class="p-visit__explore-lead">各エリアの歴史・グルメ・観光スポット・モデルコースをご紹介します</p>
			</header>

			<div class="p-visit__explore-grid">
				<?php foreach ( $areas as $a ) : ?>
				<a class="p-visit__explore-card p-visit__explore-card--<?php echo esc_attr( $a['slug'] ); ?>" href="<?php echo esc_url( home_url( '/area/' . $a['slug'] ) ); ?>">
					<div class="p-visit__explore-card-body">
						<div class="p-visit__explore-card-icon">
							<svg aria-hidden="true" focusable="false" width="20" height="20"><use href="#icon-map-pin"></use></svg>
						</div>
						<!-- /.p-visit__explore-card-icon -->
						<div class="p-visit__explore-card-main">
							<h3 class="p-visit__explore-card-title"><?php echo esc_html( $a['card_title'] ); ?></h3>
							<p class="p-visit__explore-card-desc"><?php echo esc_html( $a['desc'] ); ?></p>
						</div>
						<!-- /.p-visit__explore-card-main -->
					</div>
					<!-- /.p-visit__explore-card-body -->
					<ul class="p-visit__explore-card-tags">
						<?php foreach ( $a['tags'] as $tag ) : ?>
						<li class="p-visit__explore-card-tag"><?php echo esc_html( $tag ); ?></li>
						<?php endforeach; ?>
					</ul>
					<span class="p-visit__explore-card-more">
						詳しく見る
						<svg aria-hidden="true" focusable="false" width="16" height="16"><use href="#icon-chevron-right"></use></svg>
					</span>
				</a>
				<!-- /.p-visit__explore-card -->
				<?php endforeach; ?>
			</div>
			<!-- /.p-visit__explore-grid -->
		</div>
		<!-- /.p-visit__explore-inner -->
	</section>
	<!-- /.p-visit__explore -->

	<!-- ─── 初めて七間町を訪れる方へ ── -->
	<?php
	$tourism_first_items = [
		[
			'thumb' => get_template_directory_uri() . '/assets/images/top/hero-tourism.jpg',
			'title' => 'アクセス方法',
			'list'  => [
				'新幹線「静岡駅」より徒歩15分',
				'JR「静岡駅」より徒歩15分',
				'静岡鉄道「新静岡駅」より徒歩11分',
			],
			'url'   => home_url( '/access/' ),
		],
		[
			'thumb' => get_template_directory_uri() . '/assets/images/top/hero-main.jpg',
			'title' => '特徴的なこと',
			'list'  => [
				'江戸時代から続く歴史ある商店街',
				'映画館・芝居小屋の文化',
				'職人の工房が点在',
			],
			'url'   => home_url( '/about/' ),
		],
		[
			'thumb' => get_template_directory_uri() . '/assets/images/top/hero-shops.jpg',
			'title' => 'おすすめルート',
			'list'  => [
				'駿府城〜七間町コース (2時間)',
				'映画館めぐりコース (1.5時間)',
				'カフェ&雑貨コース (2時間)',
			],
			'url'   => home_url( '/walk/' ),
		],
	];
	?>
	<section class="p-visit__first" aria-labelledby="visit-first-title">
		<div class="p-visit__first-inner">
			<h2 class="p-visit__first-title" id="visit-first-title">初めて七間町を訪れる方へ</h2>

			<div class="p-visit__first-grid">
				<?php foreach ( $tourism_first_items as $item ) : ?>
				<a class="p-visit__first-card" href="<?php echo esc_url( $item['url'] ); ?>">
					<div class="p-visit__first-card-thumb">
						<img class="u-img-cover" src="<?php echo esc_url( $item['thumb'] ); ?>" alt="" aria-hidden="true" loading="lazy" width="600" height="400">
					</div>
					<!-- /.p-visit__first-card-thumb -->
					<div class="p-visit__first-card-body">
						<h3 class="p-visit__first-card-title"><?php echo esc_html( $item['title'] ); ?></h3>
						<ul class="p-visit__first-card-list">
							<?php foreach ( $item['list'] as $li ) : ?>
							<li><?php echo esc_html( $li ); ?></li>
							<?php endforeach; ?>
						</ul>
						<span class="p-visit__first-card-link">
							詳しく見る
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="9 18 15 12 9 6"/></svg>
						</span>
					</div>
					<!-- /.p-visit__first-card-body -->
				</a>
				<!-- /.p-visit__first-card -->
				<?php endforeach; ?>
			</div>
			<!-- /.p-visit__first-grid -->
		</div>
		<!-- /.p-visit__first-inner -->
	</section>
	<!-- /.p-visit__first -->

</article>
<!-- /.p-visit -->

<?php get_footer(); ?>
