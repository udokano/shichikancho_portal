<?php
/** 町で商いページ */
get_header();
?>

<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>

<article class="p-commerce">

	<?php
		get_template_part( 'template-parts/components/page-hero', null, [
			'title' => '町で商い',
			'sub'   => '七間町で新しいビジネスを始めよう。',
		] );
		?>

	<!-- ─── タブナビ ── -->
	<nav class="c-tabs js-tabs" data-panels=".p-commerce__panel" aria-label="物件カテゴリー">
		<div class="c-tabs__inner">
			<ul class="c-tabs__list" role="tablist">
				<li class="c-tabs__item" role="presentation"><button type="button" class="c-tabs__btn is-active" role="tab" aria-selected="true" aria-controls="commerce-properties">
					<svg class="c-tabs__icon" aria-hidden="true" focusable="false"><use href="#icon-building"></use></svg>
					<span>空き物件・土地</span>
				</button></li>
				<li class="c-tabs__item" role="presentation"><button type="button" class="c-tabs__btn" role="tab" aria-selected="false" aria-controls="commerce-coworking">
					<svg class="c-tabs__icon" aria-hidden="true" focusable="false"><use href="#icon-wifi"></use></svg>
					<span>コワーキングスペース</span>
				</button></li>
			</ul>
		</div>
		<!-- /.c-tabs__inner -->
	</nav>
	<!-- /.c-tabs -->

	<!-- ─── 物件パネル ── -->
	<div class="p-commerce__panel" id="commerce-properties" role="tabpanel">


	<!-- ─── 物件一覧 ── -->
	<section class="p-commerce__listings" aria-labelledby="commerce-listings-title">
		<div class="p-commerce__listings-inner">

			<!-- サイドバー（c-filter-sidebar 共通） -->
			<aside class="c-filter-sidebar js-commerce-filter" aria-label="絞り込み">
				<div class="c-filter-sidebar__filter">
					<div class="c-filter-sidebar__head">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
						<h2 class="c-filter-sidebar__title">絞り込み検索</h2>
					</div>

					<div class="c-filter-sidebar__group">
						<label class="c-filter-sidebar__label" for="commerce-search">キーワード検索</label>
						<input id="commerce-search" class="c-filter-sidebar__search" type="search" placeholder="例: 飲食可、角地、SOHO">
					</div>

					<div class="c-filter-sidebar__group" data-filter-group="category">
						<p class="c-filter-sidebar__label">カテゴリー</p>
						<div class="c-filter-sidebar__chips">
							<button class="c-filter-sidebar__chip is-active" type="button" data-value="">すべて</button>
							<button class="c-filter-sidebar__chip" type="button" data-value="店舗">店舗</button>
							<button class="c-filter-sidebar__chip" type="button" data-value="オフィス">オフィス</button>
							<button class="c-filter-sidebar__chip" type="button" data-value="土地">土地</button>
							<button class="c-filter-sidebar__chip" type="button" data-value="空き部屋">空き部屋</button>
						</div>
					</div>

					<div class="c-filter-sidebar__result">
						<span class="c-filter-sidebar__result-label">検索結果</span>
						<p class="c-filter-sidebar__result-value"><strong class="js-commerce-count"><?php echo (int) wp_count_posts( CPT_PROPERTY )->publish; ?></strong><span class="c-filter-sidebar__result-unit">件</span></p>
					</div>
				</div>
			</aside>
			<!-- /.c-filter-sidebar -->

			<div class="p-commerce__listings-main">
			<h2 class="u-sr-only" id="commerce-listings-title">物件一覧</h2>

			<div class="p-commerce__properties">
				<?php
				$prop_query = new WP_Query( [
					'post_type'      => CPT_PROPERTY,
					'posts_per_page' => -1,
					'no_found_rows'  => true,
				] );
				$status_labels = [ 'open' => '募集中', 'negotiating' => '商談中', 'soon' => '近日公開', 'closed' => '成約' ];
				if ( $prop_query->have_posts() ) :
					while ( $prop_query->have_posts() ) : $prop_query->the_post();
						$pid       = get_the_ID();
						$pname     = get_post_meta( $pid, 'prop_name', true ) ?: get_the_title( $pid );
						$paddr     = get_post_meta( $pid, 'prop_address', true );
						$prent     = get_post_meta( $pid, 'prop_rent', true );
						$parea     = get_post_meta( $pid, 'prop_area', true );
						$pfloor    = get_post_meta( $pid, 'prop_floor', true );
						$pstatus   = get_post_meta( $pid, 'prop_status', true ) ?: 'open';
						// 画像: attachment IDなのでURLを解決、なければサムネイルへフォールバック
						$pmain_img_id = (int) get_post_meta( $pid, 'prop_main_image', true );
						$pthumb       = $pmain_img_id ? wp_get_attachment_image_url( $pmain_img_id, 'medium' ) : sc_thumbnail_url( $pid, 'medium' );
						$ptags_raw = get_post_meta( $pid, 'prop_tags', true );
						$ptags     = is_array( $ptags_raw ) ? array_filter( array_map( fn( $r ) => $r['tag'] ?? '', $ptags_raw ) ) : [];
						$pcat      = get_post_meta( $pid, 'prop_category', true ) ?: '';
				?>
					<a class="p-commerce__property" href="<?php the_permalink(); ?>">
						<div class="p-commerce__property-img">
							<picture class="u-picture-fill">
								<img class="u-img-cover" src="<?php echo esc_url( $pthumb ); ?>" alt="" aria-hidden="true" loading="lazy" width="600" height="338">
							</picture>
							<div class="p-commerce__property-badges">
								<span class="p-commerce__property-status p-commerce__property-status--<?php echo esc_attr( $pstatus ); ?>"><?php echo esc_html( $status_labels[ $pstatus ] ?? '募集中' ); ?></span>
								<?php if ( $pcat ) : ?>
								<span class="p-commerce__property-category"><?php echo esc_html( $pcat ); ?></span>
								<?php endif; ?>
							</div>
						</div>
						<!-- /.p-commerce__property-img -->
						<div class="p-commerce__property-body">
							<h3 class="p-commerce__property-name"><?php echo esc_html( $pname ); ?></h3>
							<?php if ( $paddr ) : ?>
							<p class="p-commerce__property-address"><?php echo esc_html( $paddr ); ?></p>
							<?php endif; ?>
							<div class="p-commerce__property-meta">
								<?php if ( $parea || $pfloor ) : ?>
								<span class="p-commerce__property-size"><?php echo esc_html( trim( ( $parea ?: '' ) . ( $pfloor ? ' / ' . $pfloor : '' ) ) ); ?></span>
								<?php endif; ?>
								<?php if ( $prent ) : ?>
								<span class="p-commerce__property-price"><?php echo esc_html( $prent ); ?></span>
								<?php endif; ?>
							</div>
							<?php if ( $ptags ) : ?>
							<ul class="p-commerce__property-tags" role="list">
								<?php foreach ( $ptags as $t ) : ?>
								<li class="p-commerce__property-tag"><?php echo esc_html( $t ); ?></li>
								<?php endforeach; ?>
							</ul>
							<?php endif; ?>
							<span class="p-commerce__property-link c-btn c-btn--outline c-btn--sm">詳細を見る</span>
						</div>
						<!-- /.p-commerce__property-body -->
					</a>
					<!-- /.p-commerce__property -->
				<?php endwhile; wp_reset_postdata(); else : ?>
					<p class="p-commerce__properties-empty">物件が登録されていません。</p>
				<?php endif; ?>
				</div>
				<!-- /.p-commerce__properties -->
			</div>
			<!-- /.p-commerce__listings-main -->
		</div>
		<!-- /.p-commerce__listings-inner -->
	</section>
	<!-- /.p-commerce__listings -->

	</div>
	<!-- /.p-commerce__panel#commerce-properties -->

	<!-- ─── コワーキングパネル ── -->
	<div class="p-commerce__panel" id="commerce-coworking" role="tabpanel" hidden>
		<section class="p-commerce__coworking" aria-labelledby="commerce-coworking-title">
			<div class="p-commerce__coworking-inner">
				<h2 class="u-sr-only" id="commerce-coworking-title">コワーキングスペース</h2>
				<p class="p-commerce__coworking-lead">七間町エリアには、フリーランス・リモートワーカー・スタートアップに最適なコワーキングスペースがあります。ドロップイン利用から月額会員まで、働き方に合わせた選択が可能です。</p>

				<?php
				// CPT_COWORK 投稿があれば動的、なければサンプル fallback
				$cw_query = new WP_Query( [
					'post_type'      => CPT_COWORK,
					'posts_per_page' => -1,
					'no_found_rows'  => true,
				] );
				$use_cpt = $cw_query->have_posts();

				$cw_sample = [
					[
						'name'    => '七間町コワーキング NANATSU',
						'rating'  => '4.5',
						'desc'    => '七間町商店街の中心に位置するコワーキングスペース。フリーランス、リモートワーカー、スタートアップに最適な環境。法人登記も可能で、起業の第一歩を支援します。',
						'address' => '静岡市葵区七間町1-5 2F',
						'hours'   => '平日 7:00〜22:00 / 土日祝 9:00〜20:00',
						'dropin'  => '1日 1,500円',
						'seats'   => '40席',
						'tags'    => [ '高速Wi-Fi', '電源完備', '会議室2室', 'フリードリンク', 'ロッカー', '郵便受取', '法人登記可' ],
						'monthly' => '月額 15,000円〜',
						'phone'   => '054-XXX-XXXX',
					],
					[
						'name'    => '静岡シェアオフィス BRIDGE',
						'rating'  => '4.8',
						'desc'    => '24時間利用可能な本格的シェアオフィス。個室ブースから大型会議室まで、ビジネスの成長に合わせた空間を提供。定期的にビジネス交流会も開催。',
						'address' => '静岡市葵区七間町2-10 3F',
						'hours'   => '24時間利用可（会員制）',
						'dropin'  => '1日 2,000円',
						'seats'   => '60席',
						'tags'    => [ '24時間利用', '個室ブース', '会議室4室', 'イベントスペース', 'シャワー', '法人登記可', '駐輪場' ],
						'monthly' => '月額 25,000円〜',
						'phone'   => '054-XXX-XXXX',
					],
					[
						'name'    => 'カフェワーク 七間茶房',
						'rating'  => '4.2',
						'desc'    => '落ち着いた雰囲気のカフェ併設ワークスペース。ちょっとした作業やオンライン会議に最適。静岡茶とスイーツを楽しみながら仕事ができます。',
						'address' => '静岡市葵区七間町3-2',
						'hours'   => '8:00〜19:00（定休日: 水曜）',
						'dropin'  => 'ドリンク代のみ（席料無料）',
						'seats'   => '15席',
						'tags'    => [ 'Wi-Fi', '静かな環境', 'ドリンク付', '予約不要' ],
						'monthly' => '—',
						'phone'   => '054-XXX-XXXX',
					],
				];
				?>
				<div class="p-commerce__coworking-list">
				<?php
				if ( $use_cpt ) :
					while ( $cw_query->have_posts() ) : $cw_query->the_post();
						$cwid     = get_the_ID();
						$cwname   = get_post_meta( $cwid, 'cw_name', true ) ?: get_the_title( $cwid );
						$cwdesc   = get_post_meta( $cwid, 'cw_description', true );
						$cwaddr   = get_post_meta( $cwid, 'cw_address', true );
						$cwhours  = get_post_meta( $cwid, 'cw_hours', true );
						$cwdropin = get_post_meta( $cwid, 'cw_dropin', true );
						$cwseats  = get_post_meta( $cwid, 'cw_capacity', true );
						$cwrating = get_post_meta( $cwid, 'cw_rating', true );
						$cwmonthly = get_post_meta( $cwid, 'cw_monthly', true );
						$cwphone  = get_post_meta( $cwid, 'cw_phone', true );
						$cwurl    = get_post_meta( $cwid, 'cw_website', true );
						$cwmain_id  = (int) get_post_meta( $cwid, 'cw_main_image', true );
						$cwthumb    = $cwmain_id ? wp_get_attachment_image_url( $cwmain_id, 'medium' ) : sc_thumbnail_url( $cwid, 'medium' );
						$cwtags_raw = get_post_meta( $cwid, 'cw_tags', true );
						$cwtags = is_array( $cwtags_raw ) ? array_filter( array_map( fn( $r ) => $r['tag'] ?? '', $cwtags_raw ) ) : [];
				?>
					<article class="p-commerce__coworking-card">
						<div class="p-commerce__coworking-card-img">
							<picture class="u-picture-fill">
								<img class="u-img-cover" src="<?php echo esc_url( $cwthumb ); ?>" alt="" aria-hidden="true" loading="lazy" width="400" height="300">
							</picture>
						</div>
						<div class="p-commerce__coworking-card-body">
							<header class="p-commerce__coworking-card-head">
								<h3 class="p-commerce__coworking-card-name"><?php echo esc_html( $cwname ); ?></h3>
								<?php if ( $cwrating ) : ?>
								<span class="p-commerce__coworking-card-rating">
									<svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-star"></use></svg>
									<?php echo esc_html( number_format( (float) $cwrating, 1 ) ); ?>
								</span>
								<?php endif; ?>
							</header>
							<?php if ( $cwdesc ) : ?>
							<p class="p-commerce__coworking-card-desc"><?php echo wp_kses( $cwdesc, [ 'br' => [] ] ); ?></p>
							<?php endif; ?>
							<dl class="p-commerce__coworking-card-info">
								<?php if ( $cwaddr ) : ?>
								<div class="p-commerce__coworking-card-info-row"><dt><svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-map-pin"></use></svg></dt><dd><?php echo esc_html( $cwaddr ); ?></dd></div>
								<?php endif; ?>
								<?php if ( $cwhours ) : ?>
								<div class="p-commerce__coworking-card-info-row"><dt><svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-tag"></use></svg></dt><dd><?php echo esc_html( $cwhours ); ?></dd></div>
								<?php endif; ?>
								<?php if ( $cwdropin ) : ?>
								<div class="p-commerce__coworking-card-info-row"><dt><svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-yen"></use></svg></dt><dd>ドロップイン: <?php echo esc_html( $cwdropin ); ?></dd></div>
								<?php endif; ?>
								<?php if ( $cwseats ) : ?>
								<div class="p-commerce__coworking-card-info-row"><dt><svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-users-solid"></use></svg></dt><dd>座席数: <?php echo esc_html( $cwseats ); ?></dd></div>
								<?php endif; ?>
							</dl>
							<?php if ( $cwtags ) : ?>
							<ul class="p-commerce__coworking-card-tags" role="list">
								<?php foreach ( $cwtags as $tag ) : ?>
								<li><span class="c-tag c-tag--sm"><?php echo esc_html( $tag ); ?></span></li>
								<?php endforeach; ?>
							</ul>
							<?php endif; ?>
							<footer class="p-commerce__coworking-card-foot">
								<?php if ( $cwmonthly ) : ?>
								<span class="p-commerce__coworking-card-monthly">月額: <strong><?php echo esc_html( $cwmonthly ); ?></strong></span>
								<?php endif; ?>
								<?php if ( $cwphone ) : ?>
								<a class="p-commerce__coworking-card-action" href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $cwphone ) ); ?>">
									<svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-phone"></use></svg>
									電話する
								</a>
								<?php endif; ?>
								<?php if ( $cwurl ) : ?>
								<a class="p-commerce__coworking-card-action" href="<?php echo esc_url( $cwurl ); ?>" target="_blank" rel="noopener noreferrer">
									<svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-external"></use></svg>
									公式サイト
								</a>
								<?php endif; ?>
							</footer>
						</div>
					</article>
				<?php
					endwhile;
					wp_reset_postdata();
				else :
					foreach ( $cw_sample as $cw ) :
				?>
					<article class="p-commerce__coworking-card">
						<div class="p-commerce__coworking-card-img">
							<picture class="u-picture-fill">
								<img class="u-img-cover" src="<?php echo esc_url( sc_no_image_url() ); ?>" alt="" aria-hidden="true" loading="lazy" width="400" height="300">
							</picture>
						</div>
						<div class="p-commerce__coworking-card-body">
							<header class="p-commerce__coworking-card-head">
								<h3 class="p-commerce__coworking-card-name"><?php echo esc_html( $cw['name'] ); ?></h3>
								<span class="p-commerce__coworking-card-rating">
									<svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-star"></use></svg>
									<?php echo esc_html( $cw['rating'] ); ?>
								</span>
							</header>
							<p class="p-commerce__coworking-card-desc"><?php echo esc_html( $cw['desc'] ); ?></p>
							<dl class="p-commerce__coworking-card-info">
								<div class="p-commerce__coworking-card-info-row"><dt><svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-map-pin"></use></svg></dt><dd><?php echo esc_html( $cw['address'] ); ?></dd></div>
								<div class="p-commerce__coworking-card-info-row"><dt><svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-tag"></use></svg></dt><dd><?php echo esc_html( $cw['hours'] ); ?></dd></div>
								<div class="p-commerce__coworking-card-info-row"><dt><svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-yen"></use></svg></dt><dd>ドロップイン: <?php echo esc_html( $cw['dropin'] ); ?></dd></div>
								<div class="p-commerce__coworking-card-info-row"><dt><svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-users-solid"></use></svg></dt><dd>座席数: <?php echo esc_html( $cw['seats'] ); ?></dd></div>
							</dl>
							<ul class="p-commerce__coworking-card-tags" role="list">
								<?php foreach ( $cw['tags'] as $tag ) : ?>
								<li><span class="c-tag c-tag--sm"><?php echo esc_html( $tag ); ?></span></li>
								<?php endforeach; ?>
							</ul>
							<footer class="p-commerce__coworking-card-foot">
								<span class="p-commerce__coworking-card-monthly">月額: <strong><?php echo esc_html( $cw['monthly'] ); ?></strong></span>
								<a class="p-commerce__coworking-card-action" href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $cw['phone'] ) ); ?>">
									<svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-phone"></use></svg>
									電話する
								</a>
								<a class="p-commerce__coworking-card-action" href="#" target="_blank" rel="noopener noreferrer">
									<svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-external"></use></svg>
									公式サイト
								</a>
							</footer>
						</div>
					</article>
				<?php
					endforeach;
				endif;
				?>
				</div>
				<!-- /.p-commerce__coworking-list -->
			</div>
			<!-- /.p-commerce__coworking-inner -->
		</section>
		<!-- /.p-commerce__coworking -->
	</div>
	<!-- /.p-commerce__panel#commerce-coworking -->

	<!-- ─── 開業相談CTA ── -->
	<section class="p-commerce__cta" aria-labelledby="commerce-cta-title">
		<div class="p-commerce__cta-inner">
			<h2 class="p-commerce__cta-title" id="commerce-cta-title">七間町での開業をお考えの方へ</h2>
			<p class="p-commerce__cta-text">物件のご相談、開業に関するご質問など、お気軽にお問い合わせください。七間町商店街振興組合が、あなたの新しいビジネスの第一歩をサポートします。</p>
			<div class="p-commerce__cta-buttons">
				<a class="c-btn c-btn--primary" href="tel:054251XXXX">
					<svg class="c-btn__icon" aria-hidden="true" focusable="false"><use href="#icon-phone"></use></svg>
					電話で相談する
				</a>
				<a class="c-btn c-btn--outline" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">
					<svg class="c-btn__icon" aria-hidden="true" focusable="false"><use href="#icon-mail"></use></svg>
					メールで問い合わせ
				</a>
			</div>
			<!-- /.p-commerce__cta-buttons -->
		</div>
		<!-- /.p-commerce__cta-inner -->
	</section>
	<!-- /.p-commerce__cta -->

</article>
<!-- /.p-commerce -->

<?php get_footer(); ?>
