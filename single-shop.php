<?php
/** お店個別ページ */
get_header();

if ( have_posts() ) : the_post();
	$pid         = get_the_ID();
	$catch       = get_field( 'shop_catchphrase', $pid );
	$desc        = get_field( 'shop_description', $pid );
	$main_image  = get_field( 'shop_main_image', $pid );
	$gallery     = get_field( 'shop_gallery', $pid );
	$address     = get_field( 'shop_address', $pid );
	$phone       = get_field( 'shop_phone', $pid );
	$hours       = get_field( 'shop_hours', $pid );
	$closed      = get_field( 'shop_closed', $pid );
	$website     = get_field( 'shop_website', $pid );
	$instagram   = get_field( 'shop_instagram', $pid );
	$lat         = get_field( 'shop_map_lat', $pid );
	$lng         = get_field( 'shop_map_lng', $pid );
	$features    = (array) get_field( 'shop_features', $pid );
	$menu_items  = get_field( 'shop_menu', $pid );
	$faq         = get_field( 'shop_faq', $pid );

	// ヒーロー画像配列を構築（メイン画像 → ギャラリー → アイキャッチ）
	$hero_images = [];
	if ( is_array( $main_image ) && ! empty( $main_image['url'] ) ) {
		$hero_images[] = $main_image;
	}
	if ( is_array( $gallery ) ) {
		foreach ( $gallery as $g ) {
			if ( ! empty( $g['url'] ) ) $hero_images[] = $g;
		}
	}
	if ( ! $hero_images ) {
		$thumb_id = get_post_thumbnail_id( $pid );
		if ( $thumb_id ) {
			$hero_images[] = [
				'url' => wp_get_attachment_image_url( $thumb_id, 'large' ),
				'alt' => get_the_title(),
			];
		}
	}

	$cats  = get_the_terms( $pid, TAX_SHOP_CAT );
	$areas = get_the_terms( $pid, TAX_AREA );
	$cat   = ( $cats && ! is_wp_error( $cats ) ) ? $cats[0] : null;
	$area  = ( $areas && ! is_wp_error( $areas ) ) ? $areas[0] : null;

	// 特徴タグを「支払い / 予約 / 設備 / 対応」にグルーピング（サイドバー用）
	$feature_labels = [
		'takeout'    => 'テイクアウト可',
		'delivery'   => 'デリバリー可',
		'parking'    => '駐車場あり',
		'card'       => 'カード決済可',
		'qr'         => 'QR決済可',
		'wifi'       => 'Wi-Fiあり',
		'smoking_no' => '完全禁煙',
		'kids_ok'    => 'お子様歓迎',
		'pet_ok'     => 'ペット可',
		'english_ok' => '英語対応',
		'reserve'    => '予約可',
		'rainy_ok'   => '雨の日OK',
		'luggage_ok' => '手荷物OK',
	];
	$payment_keys  = [ 'card', 'qr' ];
	$facility_keys = [ 'wifi', 'parking', 'smoking_no' ];
	$support_keys  = [ 'kids_ok', 'pet_ok', 'english_ok', 'takeout', 'delivery' ];
	$extra_keys    = [ 'rainy_ok', 'luggage_ok' ];

	$payments   = array_values( array_intersect( $features, $payment_keys ) );
	$facilities = array_values( array_intersect( $features, $facility_keys ) );
	$supports   = array_values( array_intersect( $features, $support_keys ) );
	$has_reserve = in_array( 'reserve', $features, true );

	// 構造化データ
	if ( function_exists( 'schema_shop' ) ) schema_shop( $pid );
?>

<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>

<article class="p-shop">

	<!-- ─── メインレイアウト：本文 + サイドバー ── -->
	<section class="p-shop__layout" aria-labelledby="shop-title">
		<div class="p-shop__layout-inner">
			<div class="p-shop__layout-grid">

				<!-- ─── 左カラム：本文 ── -->
				<div class="p-shop__main">

					<!-- ヒーロー / ギャラリースライダー -->
					<?php if ( $hero_images ) : ?>
					<div class="p-shop__hero js-shop-gallery" data-total="<?php echo (int) count( $hero_images ); ?>">
						<div class="p-shop__hero-stage">
							<?php foreach ( $hero_images as $i => $img ) : ?>
							<div class="p-shop__hero-slide<?php echo $i === 0 ? ' is-active' : ''; ?>" data-index="<?php echo (int) $i; ?>">
								<picture class="u-picture-fill">
									<img class="u-img-cover" src="<?php echo esc_url( $img['url'] ); ?>" alt="<?php echo esc_attr( $i === 0 ? get_the_title() : '' ); ?>"<?php echo $i === 0 ? '' : ' aria-hidden="true"'; ?> loading="<?php echo $i === 0 ? 'eager' : 'lazy'; ?>" width="1600" height="900">
								</picture>
							</div>
							<!-- /.p-shop__hero-slide -->
							<?php endforeach; ?>
							<?php if ( count( $hero_images ) > 1 ) : ?>
							<button type="button" class="p-shop__hero-nav p-shop__hero-nav--prev js-shop-gallery-prev" aria-label="前の画像">
								<svg class="p-shop__hero-nav-icon" aria-hidden="true" focusable="false"><use href="#icon-chevron-left"></use></svg>
							</button>
							<button type="button" class="p-shop__hero-nav p-shop__hero-nav--next js-shop-gallery-next" aria-label="次の画像">
								<svg class="p-shop__hero-nav-icon" aria-hidden="true" focusable="false"><use href="#icon-chevron-right"></use></svg>
							</button>
							<span class="p-shop__hero-counter" aria-live="polite"><span class="js-shop-gallery-current">1</span>/<?php echo (int) count( $hero_images ); ?></span>
							<?php endif; ?>
						</div>
						<!-- /.p-shop__hero-stage -->
						<?php if ( count( $hero_images ) > 1 ) : ?>
						<div class="p-shop__hero-thumbs">
							<?php foreach ( $hero_images as $i => $img ) : ?>
							<button type="button" class="p-shop__hero-thumb<?php echo $i === 0 ? ' is-active' : ''; ?>" data-index="<?php echo (int) $i; ?>" aria-label="画像<?php echo (int) $i + 1; ?>を表示">
								<img class="u-img-cover" src="<?php echo esc_url( $img['sizes']['thumbnail'] ?? $img['url'] ); ?>" alt="" aria-hidden="true" loading="lazy" width="120" height="80">
							</button>
							<?php endforeach; ?>
						</div>
						<!-- /.p-shop__hero-thumbs -->
						<?php endif; ?>
					</div>
					<!-- /.p-shop__hero -->
					<?php endif; ?>

					<!-- 見出し + ファボボタン -->
					<header class="p-shop__head">
						<?php if ( $cat || $area ) : ?>
						<p class="p-shop__crumb">
							<a class="p-shop__crumb-link" href="<?php echo esc_url( get_post_type_archive_link( CPT_SHOP ) ); ?>">商店街のお店</a>
							<?php if ( $cat ) : ?>
							<span class="p-shop__crumb-sep" aria-hidden="true">&rsaquo;</span>
							<a class="p-shop__crumb-link" href="<?php echo esc_url( get_term_link( $cat ) ); ?>"><?php echo esc_html( $cat->name ); ?></a>
							<?php endif; ?>
							<?php if ( $area ) : ?>
							<span class="p-shop__crumb-sep" aria-hidden="true">&rsaquo;</span>
							<a class="p-shop__crumb-link" href="<?php echo esc_url( get_term_link( $area ) ); ?>"><?php echo esc_html( $area->name ); ?></a>
							<?php endif; ?>
						</p>
						<?php endif; ?>
						<div class="p-shop__head-row">
							<h1 class="p-shop__title" id="shop-title"><?php the_title(); ?></h1>
						</div>
						<!-- /.p-shop__head-row -->
						<?php if ( $catch ) : ?>
						<p class="p-shop__catch"><?php echo esc_html( $catch ); ?></p>
						<?php endif; ?>
					</header>
					<!-- /.p-shop__head -->

					<!-- リード文 -->
					<?php if ( $desc ) : ?>
					<p class="p-shop__lead"><?php echo wp_kses( $desc, [ 'br' => [] ] ); ?></p>
					<?php endif; ?>

					<?php
					$target_audience = get_field( 'shop_target_audience' );
					$popular_menu    = get_field( 'shop_popular_menu' );
					$busy_hours      = get_field( 'shop_busy_hours' );
					if ( $target_audience || $popular_menu || $busy_hours ) : ?>
					<dl class="p-shop__info-grid">
						<?php if ( $target_audience ) : ?>
						<div class="p-shop__info-cell">
							<dt class="p-shop__info-cell-term">こんな人におすすめ</dt>
							<dd class="p-shop__info-cell-desc"><?php echo esc_html( $target_audience ); ?></dd>
						</div>
						<?php endif; ?>
						<?php if ( $popular_menu ) : ?>
						<div class="p-shop__info-cell">
							<dt class="p-shop__info-cell-term">人気メニュー</dt>
							<dd class="p-shop__info-cell-desc"><?php echo esc_html( $popular_menu ); ?></dd>
						</div>
						<?php endif; ?>
						<?php if ( $busy_hours ) : ?>
						<div class="p-shop__info-cell">
							<dt class="p-shop__info-cell-term">混雑目安</dt>
							<dd class="p-shop__info-cell-desc"><?php echo esc_html( $busy_hours ); ?></dd>
						</div>
						<?php endif; ?>
					</dl>
					<!-- /.p-shop__info-grid -->
					<?php endif; ?>

					<!-- タブ -->
					<nav class="c-tabs p-shop__tabs js-tabs" data-panels=".p-shop__panel" aria-label="お店情報">
						<div class="c-tabs__inner">
							<ul class="c-tabs__list" role="tablist">
								<li class="c-tabs__item" role="presentation"><button type="button" class="c-tabs__btn is-active" role="tab" aria-selected="true" aria-controls="shop-panel-overview">概要</button></li>
								<?php if ( $menu_items ) : ?>
								<li class="c-tabs__item" role="presentation"><button type="button" class="c-tabs__btn" role="tab" aria-selected="false" aria-controls="shop-panel-menu">メニュー / 商品</button></li>
								<?php endif; ?>
								<?php $tips = get_field( 'shop_tips' ); if ( $tips ) : ?>
								<li class="c-tabs__item" role="presentation"><button type="button" class="c-tabs__btn" role="tab" aria-selected="false" aria-controls="shop-panel-tips">旅行者向けTips</button></li>
								<?php endif; ?>
								<li class="c-tabs__item" role="presentation"><button type="button" class="c-tabs__btn" role="tab" aria-selected="false" aria-controls="shop-panel-access">アクセス</button></li>
							</ul>
						</div>
					</nav>

					<!-- パネル: 概要 -->
					<section class="p-shop__panel is-active" id="shop-panel-overview" role="tabpanel">
						<div class="p-shop__overview">
							<?php if ( $desc ) : ?>
							<p class="p-shop__overview-text"><?php echo wp_kses( $desc, [ 'br' => [] ] ); ?></p>
							<?php endif; ?>

							<?php if ( $features ) : ?>
							<div class="p-shop__features">
								<h2 class="p-shop__features-title">特徴</h2>
								<ul class="p-shop__features-list">
									<?php foreach ( $features as $f ) : if ( isset( $feature_labels[ $f ] ) ) : ?>
									<li class="p-shop__features-item"><span class="c-tag"><?php echo esc_html( $feature_labels[ $f ] ); ?></span></li>
									<?php endif; endforeach; ?>
								</ul>
								<!-- /.p-shop__features-list -->
							</div>
							<!-- /.p-shop__features -->
							<?php endif; ?>
						</div>
						<!-- /.p-shop__overview -->
					</section>
					<!-- /.p-shop__panel#shop-panel-overview -->

					<!-- パネル: メニュー -->
					<?php if ( $menu_items ) : ?>
					<section class="p-shop__panel" id="shop-panel-menu" role="tabpanel" hidden>
						<div class="p-shop__menu-grid">
							<?php foreach ( $menu_items as $item ) :
								$item_image = $item['item_image'] ?? null;
							?>
							<article class="p-shop__menu-item">
								<?php if ( $item_image && is_array( $item_image ) ) : ?>
								<div class="p-shop__menu-img">
									<picture class="u-picture-fill">
										<img class="u-img-cover" src="<?php echo esc_url( $item_image['sizes']['medium'] ?? $item_image['url'] ); ?>" alt="" aria-hidden="true" loading="lazy" width="400" height="300">
									</picture>
								</div>
								<!-- /.p-shop__menu-img -->
								<?php endif; ?>
								<div class="p-shop__menu-body">
									<h3 class="p-shop__menu-name"><?php echo esc_html( $item['item_name'] ?? '' ); ?></h3>
									<?php if ( ! empty( $item['item_price'] ) ) : ?>
									<p class="p-shop__menu-price"><?php echo esc_html( $item['item_price'] ); ?></p>
									<?php endif; ?>
								</div>
								<!-- /.p-shop__menu-body -->
							</article>
							<?php endforeach; ?>
						</div>
						<!-- /.p-shop__menu-grid -->
					</section>
					<!-- /.p-shop__panel#shop-panel-menu -->
					<?php endif; ?>

					<!-- パネル: 旅行者向けTips -->
					<?php if ( $tips ) : ?>
					<section class="p-shop__panel" id="shop-panel-tips" role="tabpanel" hidden>
						<div class="p-shop__tips">
							<?php echo wp_kses_post( wpautop( $tips ) ); ?>
						</div>
						<!-- /.p-shop__tips -->
					</section>
					<!-- /.p-shop__panel#shop-panel-tips -->
					<?php endif; ?>

					<!-- パネル: アクセス -->
					<section class="p-shop__panel" id="shop-panel-access" role="tabpanel" hidden>
						<?php if ( $lat && $lng ) : ?>
						<div class="p-shop__map">
							<iframe
								class="p-shop__map-frame"
								src="https://www.google.com/maps?q=<?php echo esc_attr( $lat ); ?>,<?php echo esc_attr( $lng ); ?>&output=embed"
								loading="lazy"
								title="<?php echo esc_attr( get_the_title() ); ?> 地図"
								referrerpolicy="no-referrer-when-downgrade"></iframe>
						</div>
						<!-- /.p-shop__map -->
						<?php endif; ?>
						<?php if ( $address ) : ?>
						<p class="p-shop__access-address"><?php echo esc_html( $address ); ?></p>
						<?php endif; ?>
					</section>
					<!-- /.p-shop__panel#shop-panel-access -->

				</div>
				<!-- /.p-shop__main -->

				<!-- ─── 右カラム：詳細情報サイドバー ── -->
				<aside class="p-shop__sidebar" aria-label="詳細情報">
					<div class="p-shop__detail">
						<h2 class="p-shop__detail-title">詳細情報</h2>

						<!-- アクションボタン -->
						<div class="p-shop__detail-actions">
							<?php if ( $lat && $lng ) : ?>
							<a class="c-btn c-btn--primary" href="https://maps.google.com/?q=<?php echo esc_attr( $lat ); ?>,<?php echo esc_attr( $lng ); ?>" target="_blank" rel="noopener noreferrer">
								<svg class="c-btn__icon" aria-hidden="true" focusable="false"><use href="#icon-map-pin"></use></svg>
								地図を開く
							</a>
							<?php endif; ?>
							<?php if ( $phone ) : ?>
							<a class="c-btn c-btn--outline" href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>">
								<svg class="c-btn__icon" aria-hidden="true" focusable="false"><use href="#icon-phone"></use></svg>
								電話する
							</a>
							<?php endif; ?>
							<?php if ( $instagram ) : ?>
							<a class="c-btn c-btn--outline" href="<?php echo esc_url( $instagram ); ?>" target="_blank" rel="noopener noreferrer">Instagram</a>
							<?php endif; ?>
							<?php if ( $website ) : ?>
							<a class="c-btn c-btn--outline" href="<?php echo esc_url( $website ); ?>" target="_blank" rel="noopener noreferrer">公式サイト</a>
							<?php endif; ?>
						</div>
						<!-- /.p-shop__detail-actions -->

						<!-- 詳細情報リスト -->
						<dl class="p-shop__detail-list">
							<?php if ( $hours || $closed ) : ?>
							<div class="p-shop__detail-row">
								<dt class="p-shop__detail-term">営業時間</dt>
								<dd class="p-shop__detail-desc">
									<?php if ( $hours ) : ?><?php echo wp_kses( $hours, [ 'br' => [] ] ); ?><?php endif; ?>
									<?php if ( $closed ) : ?><span class="p-shop__detail-sub">定休：<?php echo esc_html( $closed ); ?></span><?php endif; ?>
								</dd>
							</div>
							<!-- /.p-shop__detail-row -->
							<?php endif; ?>

							<?php if ( $payments ) : ?>
							<div class="p-shop__detail-row">
								<dt class="p-shop__detail-term">支払い</dt>
								<dd class="p-shop__detail-desc">
									<ul class="p-shop__detail-tags">
										<?php foreach ( $payments as $f ) : ?>
										<li class="p-shop__detail-tag"><span class="c-tag c-tag--sm"><?php echo esc_html( str_replace( [ '可', '決済' ], '', $feature_labels[ $f ] ) ); ?></span></li>
										<?php endforeach; ?>
										<li class="p-shop__detail-tag"><span class="c-tag c-tag--sm">現金</span></li>
									</ul>
								</dd>
							</div>
							<!-- /.p-shop__detail-row -->
							<?php endif; ?>

							<div class="p-shop__detail-row">
								<dt class="p-shop__detail-term">予約</dt>
								<dd class="p-shop__detail-desc"><?php echo $has_reserve ? '可' : '不要'; ?></dd>
							</div>
							<!-- /.p-shop__detail-row -->

							<?php if ( $facilities ) : ?>
							<div class="p-shop__detail-row">
								<dt class="p-shop__detail-term">設備</dt>
								<dd class="p-shop__detail-desc">
									<?php
									$labels = array_map( function( $f ) use ( $feature_labels ) { return $feature_labels[ $f ]; }, $facilities );
									echo esc_html( implode( ' / ', $labels ) );
									?>
								</dd>
							</div>
							<!-- /.p-shop__detail-row -->
							<?php endif; ?>

							<?php if ( $supports ) : ?>
							<div class="p-shop__detail-row">
								<dt class="p-shop__detail-term">対応</dt>
								<dd class="p-shop__detail-desc">
									<ul class="p-shop__detail-tags">
										<?php foreach ( $supports as $f ) : ?>
										<li class="p-shop__detail-tag"><span class="c-tag c-tag--sm"><?php echo esc_html( $feature_labels[ $f ] ); ?></span></li>
										<?php endforeach; ?>
									</ul>
								</dd>
							</div>
							<!-- /.p-shop__detail-row -->
							<?php endif; ?>

							<?php
							$price_range  = get_field( 'shop_price_range' );
							$seats        = get_field( 'shop_seats' );
							$parking_note = get_field( 'shop_parking_note' );
							?>

							<?php if ( $price_range ) : ?>
							<div class="p-shop__detail-row">
								<dt class="p-shop__detail-term">価格帯</dt>
								<dd class="p-shop__detail-desc"><?php echo esc_html( $price_range ); ?></dd>
							</div>
							<!-- /.p-shop__detail-row -->
							<?php endif; ?>

							<?php if ( $seats ) : ?>
							<div class="p-shop__detail-row">
								<dt class="p-shop__detail-term">席数</dt>
								<dd class="p-shop__detail-desc"><?php echo esc_html( $seats ); ?></dd>
							</div>
							<!-- /.p-shop__detail-row -->
							<?php endif; ?>

							<?php if ( $parking_note ) : ?>
							<div class="p-shop__detail-row">
								<dt class="p-shop__detail-term">駐車場</dt>
								<dd class="p-shop__detail-desc"><?php echo esc_html( $parking_note ); ?></dd>
							</div>
							<!-- /.p-shop__detail-row -->
							<?php endif; ?>

							<?php if ( $address ) : ?>
							<div class="p-shop__detail-row">
								<dt class="p-shop__detail-term">住所</dt>
								<dd class="p-shop__detail-desc"><?php echo esc_html( $address ); ?></dd>
							</div>
							<!-- /.p-shop__detail-row -->
							<?php endif; ?>

							<?php if ( $phone ) : ?>
							<div class="p-shop__detail-row">
								<dt class="p-shop__detail-term">電話</dt>
								<dd class="p-shop__detail-desc"><a class="p-shop__detail-link" href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></dd>
							</div>
							<!-- /.p-shop__detail-row -->
							<?php endif; ?>

							<?php
							$extras    = array_values( array_intersect( $features, $extra_keys ) );
							$crowd_sum = get_field( 'shop_busy_hours' );
							if ( $extras || $crowd_sum ) : ?>
							<div class="p-shop__detail-row p-shop__detail-row--extras">
								<?php if ( $extras ) : ?>
								<ul class="p-shop__detail-extras">
									<?php foreach ( $extras as $f ) : ?>
									<li class="p-shop__detail-tag"><span class="c-tag c-tag--sm"><?php echo esc_html( $feature_labels[ $f ] ); ?></span></li>
									<?php endforeach; ?>
								</ul>
								<?php endif; ?>
								<?php if ( $crowd_sum ) : ?>
								<p class="p-shop__detail-crowd">混雑：<?php echo esc_html( $crowd_sum ); ?></p>
								<?php endif; ?>
							</div>
							<?php endif; ?>
						</dl>
						<!-- /.p-shop__detail-list -->
					</div>
					<!-- /.p-shop__detail -->
				</aside>
				<!-- /.p-shop__sidebar -->

			</div>
			<!-- /.p-shop__layout-grid -->
		</div>
		<!-- /.p-shop__layout-inner -->
	</section>
	<!-- /.p-shop__layout -->

	<!-- ─── 近隣のお店 ── -->
	<?php
	// 近隣のお店：同じエリアタクソノミーを優先、足りなければランダム補完
	$nearby_args = [
		'post_type'      => CPT_SHOP,
		'posts_per_page' => 3,
		'post__not_in'   => [ $pid ],
		'orderby'        => 'rand',
		'no_found_rows'  => true,
	];
	if ( $area ) {
		$nearby_args['tax_query'] = [ [
			'taxonomy' => TAX_AREA,
			'field'    => 'term_id',
			'terms'    => $area->term_id,
		] ];
	}
	$nearby = new WP_Query( $nearby_args );
	if ( ! $nearby->have_posts() && $area ) {
		// エリア絞り込みでヒットゼロならフォールバック
		unset( $nearby_args['tax_query'] );
		$nearby = new WP_Query( $nearby_args );
	}
	if ( $nearby->have_posts() ) :
	?>
	<section class="p-shop__related" aria-labelledby="shop-related-title">
		<div class="p-shop__related-inner">
			<h2 class="p-shop__related-title" id="shop-related-title">近隣のお店</h2>
			<div class="p-shop__related-grid">
				<?php while ( $nearby->have_posts() ) : $nearby->the_post();
					$rid       = get_the_ID();
					$rthumb    = sc_thumbnail_url( $rid, 'medium' );
					$rcat_arr  = get_the_terms( $rid, TAX_SHOP_CAT );
					$rcat      = ( $rcat_arr && ! is_wp_error( $rcat_arr ) ) ? $rcat_arr[0] : null;
					$rarea_arr = get_the_terms( $rid, TAX_AREA );
					$rarea     = ( $rarea_arr && ! is_wp_error( $rarea_arr ) ) ? $rarea_arr[0] : null;
					$rcatch    = get_field( 'shop_catchphrase', $rid );

					// 徒歩距離バッジ：両店舗に lat/lng があるときだけ算出
					$walk_label = '';
					$rlat = get_field( 'shop_map_lat', $rid );
					$rlng = get_field( 'shop_map_lng', $rid );
					if ( $lat && $lng && $rlat && $rlng ) {
						// 簡易距離計算（球面三角法・hubeny 不要、単純な近似でよい）
						$dlat = deg2rad( (float) $rlat - (float) $lat );
						$dlng = deg2rad( (float) $rlng - (float) $lng );
						$a = sin( $dlat / 2 ) ** 2 + cos( deg2rad( (float) $lat ) ) * cos( deg2rad( (float) $rlat ) ) * sin( $dlng / 2 ) ** 2;
						$c = 2 * atan2( sqrt( $a ), sqrt( 1 - $a ) );
						$meters = 6371000 * $c;
						$minutes = max( 1, (int) round( $meters / 80 ) ); // 80m/分換算
						$walk_label = sprintf( '徒歩%d分（約%dm）', $minutes, (int) round( $meters / 10 ) * 10 );
					}
				?>
				<article class="p-shop__related-card">
					<a class="p-shop__related-link" href="<?php the_permalink(); ?>">
						<div class="p-shop__related-img">
							<picture class="u-picture-fill">
								<img class="u-img-cover" src="<?php echo esc_url( $rthumb ); ?>" alt="" aria-hidden="true" loading="lazy" width="400" height="300">
							</picture>
							<?php if ( $walk_label ) : ?>
							<span class="p-shop__related-badge"><?php echo esc_html( $walk_label ); ?></span>
							<?php endif; ?>
						</div>
						<!-- /.p-shop__related-img -->
						<div class="p-shop__related-body">
							<div class="p-shop__related-tags">
								<?php if ( $rarea ) : ?><span class="c-tag c-tag--sm"><?php echo esc_html( $rarea->name ); ?></span><?php endif; ?>
								<?php if ( $rcat ) : ?><span class="c-tag c-tag--sm"><?php echo esc_html( $rcat->name ); ?></span><?php endif; ?>
							</div>
							<!-- /.p-shop__related-tags -->
							<h3 class="p-shop__related-name"><?php the_title(); ?></h3>
							<?php if ( $rcatch ) : ?>
							<p class="p-shop__related-catch"><?php echo esc_html( $rcatch ); ?></p>
							<?php endif; ?>
						</div>
						<!-- /.p-shop__related-body -->
					</a>
				</article>
				<!-- /.p-shop__related-card -->
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
			<!-- /.p-shop__related-grid -->
		</div>
		<!-- /.p-shop__related-inner -->
	</section>
	<!-- /.p-shop__related -->
	<?php endif; ?>

</article>
<!-- /.p-shop -->

<?php endif; get_footer(); ?>
