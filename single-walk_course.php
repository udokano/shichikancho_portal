<?php
/** 散策コース個別ページ */
get_header();

if ( have_posts() ) : the_post();
	$pid        = get_the_ID();
	$duration   = (int) get_field( 'walk_duration', $pid );
	$distance   = get_field( 'walk_distance', $pid );
	$desc       = get_field( 'walk_description', $pid );
	$spots      = get_field( 'walk_spots', $pid );
	$thumb      = get_the_post_thumbnail_url( $pid, 'large' );

	$areas = get_the_terms( $pid, TAX_AREA );
	$area  = ( $areas && ! is_wp_error( $areas ) ) ? $areas[0] : null;

	// 散策シーン（体験タイプ）: 食べ歩き / 歴史めぐり / おしゃれ など
	$scene_terms = get_the_terms( $pid, TAX_WALK_SCENE );
	$scenes      = ( $scene_terms && ! is_wp_error( $scene_terms ) ) ? $scene_terms : [];
?>

<article class="p-walk-detail">

	<!-- ─── ヒーロー（タイトル＋サブ／淡色背景） ── -->
	<section class="p-walk-detail__hero" aria-labelledby="walk-title">
		<div class="p-walk-detail__hero-inner">
			<h1 class="p-walk-detail__hero-title" id="walk-title"><?php the_title(); ?></h1>
			<?php if ( $desc ) : ?>
			<p class="p-walk-detail__hero-sub"><?php echo wp_kses( $desc, [ 'br' => [] ] ); ?></p>
			<?php endif; ?>
		</div>
		<!-- /.p-walk-detail__hero-inner -->
	</section>
	<!-- /.p-walk-detail__hero -->

	<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>

	<!-- ─── サマリー（メタ情報バー） ── -->
	<section class="p-walk-detail__summary" aria-label="コース概要">
		<div class="p-walk-detail__summary-inner">
			<ul class="p-walk-detail__meta">
				<?php if ( $duration ) : ?>
				<li class="p-walk-detail__meta-item">
					<svg class="p-walk-detail__meta-icon" aria-hidden="true" focusable="false" width="20" height="20"><use href="#icon-clock"></use></svg>
					<span><?php echo esc_html( $duration ); ?>分</span>
				</li>
				<?php endif; ?>
				<?php if ( $distance ) : ?>
				<li class="p-walk-detail__meta-item">
					<svg class="p-walk-detail__meta-icon" aria-hidden="true" focusable="false" width="20" height="20"><use href="#icon-ruler"></use></svg>
					<span><?php echo esc_html( $distance ); ?></span>
				</li>
				<?php endif; ?>
				<?php if ( $spots ) : ?>
				<li class="p-walk-detail__meta-item">
					<svg class="p-walk-detail__meta-icon" aria-hidden="true" focusable="false" width="20" height="20"><use href="#icon-map-pin"></use></svg>
					<span><?php echo esc_html( count( $spots ) ); ?>スポット</span>
				</li>
				<?php endif; ?>
				<?php foreach ( $scenes as $sc ) : ?>
				<li class="p-walk-detail__meta-item p-walk-detail__meta-item--chip">
					<span class="p-walk-detail__meta-chip"><?php echo esc_html( $sc->name ); ?></span>
				</li>
				<?php endforeach; ?>
			</ul>
			<!-- /.p-walk-detail__meta -->

			<div class="p-walk-detail__actions">
				<button type="button" class="p-walk-detail__share js-copy-url" aria-label="URLをコピー" title="URLをコピー">
					<svg class="p-walk-detail__share-icon" aria-hidden="true" focusable="false" width="20" height="20"><use href="#icon-share"></use></svg>
				</button>
			</div>
		</div>
		<!-- /.p-walk-detail__summary-inner -->
	</section>
	<!-- /.p-walk-detail__summary -->

	<?php if ( $spots ) : ?>
	<!-- ─── ウォーキングルート ── -->
	<section class="p-walk-detail__route" aria-labelledby="walk-route-title">
		<div class="p-walk-detail__route-inner">
			<h2 class="p-walk-detail__route-title" id="walk-route-title">ウォーキングルート</h2>
			<ol class="p-walk-detail__route-list">
				<?php
				$spot_count = count( $spots );
				foreach ( $spots as $i => $spot ) :
					$ref_id = $spot['ref'] ?? 0;
					$s_name = $ref_id ? get_the_title( $ref_id ) : ( $spot['name'] ?? '' );
					$s_note = $ref_id ? ( get_field( 'spot_note', $ref_id ) ?: get_field( 'spot_description', $ref_id ) ) : ( $spot['note'] ?? '' );
					if ( ! $s_name ) continue;
					$next_t = $spot['time_to_next'] ?? '';
					$anchor = 'walk-spot-' . ( $i + 1 );
				?>
				<li class="p-walk-detail__route-item">
					<a class="p-walk-detail__route-link" href="#<?php echo esc_attr( $anchor ); ?>">
						<span class="p-walk-detail__route-num"><?php echo esc_html( $i + 1 ); ?></span>
						<div class="p-walk-detail__route-body">
							<div class="p-walk-detail__route-text">
								<h3 class="p-walk-detail__route-name"><?php echo esc_html( $s_name ); ?></h3>
								<?php if ( $s_note ) : ?>
								<p class="p-walk-detail__route-note"><?php echo esc_html( wp_trim_words( wp_strip_all_tags( $s_note ), 30, '…' ) ); ?></p>
								<?php endif; ?>
							</div>
							<svg class="p-walk-detail__route-arrow" aria-hidden="true" focusable="false" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><use href="#icon-chevron-down"></use></svg>
						</div>
					</a>
					<?php if ( $next_t && $i < $spot_count - 1 ) : ?>
					<span class="p-walk-detail__route-time" aria-hidden="true"><?php echo esc_html( $next_t ); ?></span>
					<?php endif; ?>
				</li>
				<?php endforeach; ?>
			</ol>
			<!-- /.p-walk-detail__route-list -->
		</div>
		<!-- /.p-walk-detail__route-inner -->
	</section>
	<!-- /.p-walk-detail__route -->
	<?php endif; ?>

	<!-- ─── ルートマップ（Leaflet + OpenStreetMap） ── -->
	<?php
	// walk_spots の ref から lat/lng を収集
	$map_points = [];
	if ( $spots ) {
		foreach ( $spots as $i => $spot ) {
			$ref_id = $spot['ref'] ?? 0;
			if ( ! $ref_id ) continue;
			$lat = (float) get_field( 'spot_map_lat', $ref_id );
			$lng = (float) get_field( 'spot_map_lng', $ref_id );
			if ( ! $lat || ! $lng ) continue;
			$map_points[] = [
				'lat'   => $lat,
				'lng'   => $lng,
				'index' => $i + 1,
				'name'  => get_the_title( $ref_id ),
				'url'   => get_permalink( $ref_id ),
			];
		}
	}
	?>
	<?php if ( count( $map_points ) >= 1 ) : ?>
	<section class="p-walk-detail__map" aria-labelledby="walk-map-title">
		<div class="p-walk-detail__map-inner">
			<header class="p-walk-detail__map-head">
				<h2 class="p-walk-detail__map-title" id="walk-map-title">ルートマップ</h2>
				<button type="button" class="p-walk-detail__map-toggle js-walkmap-toggle" aria-expanded="false" aria-controls="walkmap-canvas">
					<svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-map-pin"></use></svg>
					<span class="js-walkmap-toggle-label">地図を表示</span>
				</button>
			</header>
			<div id="walkmap-canvas" class="p-walk-detail__map-canvas js-walkmap" hidden
				data-points="<?php echo esc_attr( wp_json_encode( $map_points ) ); ?>"></div>
		</div>
		<!-- /.p-walk-detail__map-inner -->
	</section>
	<!-- /.p-walk-detail__map -->
	<?php endif; ?>

	<?php if ( $spots ) : ?>
	<!-- ─── スポット詳細 ── -->
	<section class="p-walk-detail__spots" aria-labelledby="walk-spots-title">
		<div class="p-walk-detail__spots-inner">
			<h2 class="p-walk-detail__spots-title" id="walk-spots-title">スポット詳細</h2>
			<div class="p-walk-detail__spots-list">
				<?php foreach ( $spots as $i => $spot ) :
					$ref_id = $spot['ref'] ?? 0;
					if ( ! $ref_id ) continue;

					$s_name    = get_the_title( $ref_id );
					$s_thumb   = sc_thumbnail_url( $ref_id, 'large' );
					$s_desc    = get_field( 'spot_description', $ref_id );
					$s_gallery = get_field( 'spot_gallery', $ref_id );
					$s_types   = get_the_terms( $ref_id, TAX_SPOT_TYPE );
					$s_org     = get_field( 'spot_organization', $ref_id );
					$s_hours   = get_field( 'spot_hours', $ref_id );
					$s_closed  = get_field( 'spot_closed', $ref_id );
					$s_phone   = get_field( 'spot_phone', $ref_id );
					$s_website = get_field( 'spot_website', $ref_id );
				?>
				<article class="p-walk-detail__spot" id="walk-spot-<?php echo esc_attr( $i + 1 ); ?>">
					<header class="p-walk-detail__spot-head">
						<span class="p-walk-detail__spot-num"><?php echo esc_html( $i + 1 ); ?></span>
						<div class="p-walk-detail__spot-head-body">
							<h3 class="p-walk-detail__spot-name">
								<a href="<?php echo esc_url( get_permalink( $ref_id ) ); ?>"><?php echo esc_html( $s_name ); ?></a>
							</h3>
							<?php if ( $s_org ) : ?>
							<p class="p-walk-detail__spot-org"><?php echo esc_html( $s_org ); ?></p>
							<?php endif; ?>
						</div>
					</header>

					<?php if ( $s_thumb ) : ?>
					<div class="p-walk-detail__spot-img">
						<picture class="u-picture-fill">
							<img class="u-img-cover" src="<?php echo esc_url( $s_thumb ); ?>" alt="" aria-hidden="true" loading="lazy" width="900" height="500">
						</picture>
					</div>
					<?php endif; ?>

					<?php if ( $s_desc ) : ?>
					<p class="p-walk-detail__spot-desc"><?php echo wp_kses( $s_desc, [ 'br' => [] ] ); ?></p>
					<?php endif; ?>

					<?php if ( $s_hours || $s_closed || $s_phone ) : ?>
					<dl class="p-walk-detail__spot-info">
						<?php if ( $s_hours ) : ?>
						<div><dt>営業時間:</dt><dd><?php echo esc_html( $s_hours ); ?></dd></div>
						<?php endif; ?>
						<?php if ( $s_closed ) : ?>
						<div><dt>定休日:</dt><dd><?php echo esc_html( $s_closed ); ?></dd></div>
						<?php endif; ?>
						<?php if ( $s_phone ) : ?>
						<div><dt>電話:</dt><dd><?php echo esc_html( $s_phone ); ?></dd></div>
						<?php endif; ?>
					</dl>
					<?php endif; ?>

					<?php if ( $s_types && ! is_wp_error( $s_types ) ) : ?>
					<ul class="p-walk-detail__spot-tags">
						<?php foreach ( $s_types as $t ) : ?>
						<li><span class="c-tag c-tag--sm"><?php echo esc_html( $t->name ); ?></span></li>
						<?php endforeach; ?>
					</ul>
					<?php endif; ?>

					<?php if ( $s_phone || $s_website ) : ?>
					<div class="p-walk-detail__spot-actions">
						<?php if ( $s_phone ) : ?>
						<a class="p-walk-detail__spot-action p-walk-detail__spot-action--phone" href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $s_phone ) ); ?>">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><use href="#icon-phone"></use></svg>電話
						</a>
						<?php endif; ?>
						<?php if ( $s_website ) : ?>
						<a class="p-walk-detail__spot-action p-walk-detail__spot-action--web" href="<?php echo esc_url( $s_website ); ?>" target="_blank" rel="noopener noreferrer">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><use href="#icon-external"></use></svg>ウェブサイト
						</a>
						<?php endif; ?>
					</div>
					<?php endif; ?>

					<?php if ( $s_gallery && is_array( $s_gallery ) ) : ?>
					<div class="p-walk-detail__spot-gallery">
						<h4 class="p-walk-detail__spot-gallery-title">このスポットの写真</h4>
						<ul class="p-walk-detail__spot-gallery-list">
							<?php foreach ( array_slice( $s_gallery, 0, 3 ) as $img ) : ?>
							<li class="p-walk-detail__spot-gallery-item">
								<picture class="u-picture-fill">
									<img class="u-img-cover" src="<?php echo esc_url( $img['sizes']['medium_large'] ?? $img['url'] ); ?>" alt="<?php echo esc_attr( $img['alt'] ?? '' ); ?>" loading="lazy" width="400" height="280">
								</picture>
							</li>
							<?php endforeach; ?>
						</ul>
					</div>
					<?php endif; ?>
				</article>
				<!-- /.p-walk-detail__spot -->
				<?php endforeach; ?>
			</div>
			<!-- /.p-walk-detail__spots-list -->
		</div>
		<!-- /.p-walk-detail__spots-inner -->
	</section>
	<!-- /.p-walk-detail__spots -->
	<?php endif; ?>

	<!-- ─── 関連コース ── -->
	<?php
	$related = new WP_Query( [
		'post_type'      => CPT_WALK,
		'posts_per_page' => 3,
		'post__not_in'   => [ $pid ],
		'orderby'        => 'rand',
		'no_found_rows'  => true,
	] );
	if ( $related->have_posts() ) :
	?>
	<section class="p-walk-detail__related" aria-labelledby="walk-related-title">
		<div class="p-walk-detail__related-inner">
			<h2 class="p-walk-detail__related-title" id="walk-related-title">次に歩くのにおすすめのコース</h2>
			<div class="p-walk-detail__related-grid">
				<?php while ( $related->have_posts() ) : $related->the_post();
					$rid       = get_the_ID();
					$rthumb    = sc_thumbnail_url( $rid, 'medium' );
					$rdur      = (int) get_field( 'walk_duration', $rid );
					$rdist     = get_field( 'walk_distance', $rid );
					$rdesc     = get_field( 'walk_description', $rid );
				?>
				<a class="p-explore__course-card" href="<?php the_permalink(); ?>">
					<div class="p-explore__course-card-img">
						<picture class="u-picture-fill">
							<img class="u-img-cover" src="<?php echo esc_url( $rthumb ); ?>" alt="" aria-hidden="true" loading="lazy" width="400" height="300">
						</picture>
					</div>
					<div class="p-explore__course-card-body">
						<h3 class="p-explore__course-card-name"><?php the_title(); ?></h3>
						<?php if ( $rdesc ) : ?>
						<p class="p-explore__course-card-desc"><?php echo esc_html( wp_trim_words( wp_strip_all_tags( $rdesc ), 20, '…' ) ); ?></p>
						<?php endif; ?>
						<dl class="p-explore__course-card-meta">
							<?php if ( $rdur ) : ?>
							<div><dt class="u-sr-only">所要時間</dt><dd><?php echo esc_html( $rdur ); ?>分</dd></div>
							<?php endif; ?>
							<?php if ( $rdist ) : ?>
							<div><dt class="u-sr-only">距離</dt><dd><?php echo esc_html( $rdist ); ?></dd></div>
							<?php endif; ?>
						</dl>
					</div>
				</a>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
			<!-- /.p-walk-detail__related-grid -->
		</div>
		<!-- /.p-walk-detail__related-inner -->
	</section>
	<!-- /.p-walk-detail__related -->
	<?php endif; ?>

</article>
<!-- /.p-walk-detail -->

<?php endif; get_footer(); ?>
