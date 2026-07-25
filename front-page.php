<?php

/** トップページテンプレート */

get_header();

// ─── JSON-LD: LocalBusiness（フロントページのみ） ──────────────
schema_local_business();
?>

<?php
// ─── ヒーロー ─────────────────────────────────────────────
?>
<section class="p-home-hero" aria-labelledby="home-hero-title">

	<!-- 背景動画 -->
	<video
		class="p-home-hero__video"
		src="<?php echo esc_url(SC_TPL_URI . '/assets/videos/hero-bg.mp4'); ?>"
		autoplay
		muted
		loop
		playsinline
		aria-hidden="true"></video>
	<div class="p-home-hero__overlay" aria-hidden="true"></div>

	<div class="p-home-hero__inner">
		<div class="p-home-hero__content">

			<!-- 左カラム: タイトル -->
			<div class="p-home-hero__title-wrap">
				<p class="p-home-hero__title-en js-hero-split" aria-label="SHICHIKANCHO">SHICHIKENCHO</p>
				<h1 id="home-hero-title" class="p-home-hero__title-ja js-hero-fade">七間町</h1>
				<p class="p-home-hero__title-sub js-hero-fade">静岡県の中心、七間町</p>
			</div>
			<!-- /.p-home-hero__title-wrap -->

			<!-- 右カラム: 地図画像 -->
			<div class="p-home-hero__map-wrap">
				<img
					class="p-home-hero__map-img"
					src="<?php echo esc_url(SC_TPL_URI . '/assets/images/top/hero-map-2.png'); ?>"
					alt="静岡県の地図と七間町の位置"
					loading="eager">
			</div>
			<!-- /.p-home-hero__map-wrap -->

		</div>
		<!-- /.p-home-hero__content -->
	</div>
	<!-- /.p-home-hero__inner -->
</section>
<!-- /.p-home-hero -->

<?php
// ─── カテゴリーナビ（SP のみ表示）────────────────────────
$cat_nav = [
	['icon' => 'icon-house',     'label' => '町の紹介',     'slug' => 'about'],
	['icon' => 'icon-film',      'label' => '映画の町',     'slug' => 'cinema'],
	['icon' => 'icon-map-pin',   'label' => '町をめぐる',   'slug' => 'walk'],
	['icon' => 'icon-person',    'label' => '町に住む',     'slug' => 'living'],
	['icon' => 'icon-hat',       'label' => '町でまなぶ',   'slug' => 'learn'],
	['icon' => 'icon-briefcase', 'label' => '町で働く',     'slug' => 'work'],
	['icon' => 'icon-store',     'label' => '町で商い',     'slug' => 'business'],
	['icon' => 'icon-camera',    'label' => '町のギャラリー', 'slug' => 'gallery'],
	['icon' => 'icon-book',      'label' => 'くらしガイド', 'slug' => 'guide'],
	['icon' => 'icon-shield',    'label' => 'いのちを守る', 'slug' => 'safety'],
];
?>
<section class="p-home-category" aria-label="カテゴリーナビゲーション">
	<div class="p-home-category__inner">
		<ul class="p-home-category__list" role="list">
			<?php foreach ($cat_nav as $item) : ?>
				<li>
					<a class="p-home-category__link" href="<?php echo esc_url(home_url('/' . $item['slug'] . '/')); ?>">
						<span class="p-home-category__icon-wrap">
							<svg class="p-home-category__icon" aria-hidden="true" focusable="false">
								<use href="#<?php echo esc_attr($item['icon']); ?>"></use>
							</svg>
						</span>
						<span class="p-home-category__label"><?php echo esc_html($item['label']); ?></span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
<!-- /.p-home-category -->

<?php
// ─── インフォメーション ────────────────────────────────────
// 左: お知らせ（CPT_NEWS 最新3件）
$news_query = new WP_Query([
	'post_type'      => CPT_NEWS,
	'posts_per_page' => 3,
	'orderby'        => 'date',
	'order'          => 'DESC',
]);

// 右: イベント（最新4件、2件以上でカルーセル）
$event_query = new WP_Query([
	'post_type'      => CPT_EVENT,
	'posts_per_page' => 4,
	'orderby'        => 'date',
	'order'          => 'DESC',
]);
?>
<section class="p-home-section p-home-section--washi" aria-labelledby="home-info-title">
	<div class="p-home-section__inner">
		<div class="p-home-info__grid">

			<!-- 左: お知らせ一覧 -->
			<div class="p-home-info__news">
				<h2 class="p-home-info__col-h2" id="home-info-title">インフォメーション</h2>
				<div class="p-home-info__card">
					<p class="p-home-info__intro">七間町の最新情報をお届けします！</p>
					<?php if ($news_query->have_posts()) : ?>
						<?php while ($news_query->have_posts()) : $news_query->the_post(); ?>
							<a class="c-news-item" href="<?php the_permalink(); ?>">
								<span class="c-news-item__meta">
									<span class="c-news-item__date"><?php echo esc_html(get_the_date('Y/m')); ?></span>
									<span class="c-news-item__tag">インフォメーション</span>
								</span>
								<span class="c-news-item__title"><?php the_title(); ?></span>
							</a>
						<?php endwhile;
						wp_reset_postdata(); ?>
					<?php else : ?>
						<p class="p-home-info__empty">インフォメーションはまだありません。</p>
					<?php endif; ?>
				</div>
			</div>
			<!-- /.p-home-info__news -->

			<!-- 右: 近日開催のイベント -->
			<div class="p-home-info__events">
				<h2 class="p-home-info__col-h2">近日開催のイベント</h2>
				<?php if ($event_query->have_posts()) :
					$event_posts  = $event_query->posts;
					$event_count  = count($event_posts);
					$use_carousel = $event_count >= 2;
				?>
					<div class="p-home-event-carousel<?php echo $use_carousel ? ' js-home-event-carousel' : ''; ?>">
						<?php foreach ($event_posts as $i => $ev) :
							$GLOBALS['post'] = $ev; // カルーセルは index が必要なため while ではなく foreach + setup_postdata
							setup_postdata($ev);
							$thumb = sc_thumbnail_url(get_the_ID(), 'medium_large');
							$place = sc_field('event_place');
							$time  = sc_field('event_time');
							$date  = get_the_date('Y/n/j');
							$dow   = sc_date_jp(get_the_date('D'));
						?>
							<div class="p-home-event-carousel__slide<?php echo $i === 0 ? ' is-active' : ''; ?>" data-slide="<?php echo $i; ?>" aria-hidden="<?php echo $i === 0 ? 'false' : 'true'; ?>">
								<a class="p-home-event-card" href="<?php the_permalink(); ?>">
									<div class="p-home-event-card__thumb">
										<img class="u-img-cover" src="<?php echo esc_url($thumb); ?>" alt="" aria-hidden="true" loading="lazy">
										<div class="p-home-event-card__date-badge">
											<span class="p-home-event-card__date"><?php echo esc_html($date); ?></span>
											<span class="p-home-event-card__dow"><?php echo esc_html($dow); ?></span>
										</div>
									</div>
									<div class="p-home-event-card__body">
										<?php if ($time || $place) : ?>
											<div class="p-home-event-card__meta">
												<?php if ($time) : ?><span class="p-home-event-card__time"><?php echo esc_html($time); ?></span><?php endif; ?>
												<?php if ($time && $place) : ?><span class="p-home-event-card__sep" aria-hidden="true">|</span><?php endif; ?>
												<?php if ($place) : ?><span class="p-home-event-card__place"><?php echo esc_html($place); ?></span><?php endif; ?>
											</div>
										<?php endif; ?>
										<h3 class="p-home-event-card__title"><?php the_title(); ?></h3>
										<?php if (has_excerpt()) : ?>
											<p class="p-home-event-card__excerpt"><?php echo esc_html(get_the_excerpt()); ?></p>
										<?php endif; ?>
									</div>
								</a>
							</div>
							<!-- /.p-home-event-carousel__slide -->
						<?php endforeach;
						wp_reset_postdata(); ?>

						<?php if ($use_carousel) : ?>
							<div class="p-home-event-carousel__controls">
								<button type="button" class="p-home-event-carousel__arrow p-home-event-carousel__arrow--prev js-carousel-prev" aria-label="前へ">
									<svg width="16" height="16" aria-hidden="true" focusable="false">
										<use href="#icon-chevron-right"></use>
									</svg>
								</button>
								<div class="p-home-event-carousel__dots">
									<?php for ($i = 0; $i < $event_count; $i++) : ?>
										<button type="button" class="p-home-event-carousel__dot<?php echo $i === 0 ? ' is-active' : ''; ?>" data-target="<?php echo $i; ?>" aria-label="<?php echo $i + 1; ?>枚目"></button>
									<?php endfor; ?>
								</div>
								<button type="button" class="p-home-event-carousel__arrow p-home-event-carousel__arrow--next js-carousel-next" aria-label="次へ">
									<svg width="16" height="16" aria-hidden="true" focusable="false">
										<use href="#icon-chevron-right"></use>
									</svg>
								</button>
							</div>
							<!-- /.p-home-event-carousel__controls -->
						<?php endif; ?>
					</div>
					<!-- /.p-home-event-carousel -->
				<?php else : ?>
					<p class="p-home-info__empty">近日のイベントはありません。</p>
				<?php endif; ?>
			</div>
			<!-- /.p-home-info__events -->

		</div>
		<!-- /.p-home-info__grid -->
	</div>
	<!-- /.p-home-section__inner -->
</section>
<!-- /.p-home-section インフォメーション -->

<?php
// ─── 商店街のお店 ──────────────────────────────────────────
// 丸型スクロール帯用の画像（仮アセット）
$shop_scroll_base = [
	['src' => SC_TPL_URI . '/assets/images/top/hero-shops.jpg',  'alt' => 'お店'],
	['src' => SC_TPL_URI . '/assets/images/top/hero-cinema.jpg', 'alt' => 'お店'],
	['src' => SC_TPL_URI . '/assets/images/top/hero-main.jpg',   'alt' => 'お店'],
	['src' => SC_TPL_URI . '/assets/images/top/hero-tourism.jpg', 'alt' => 'お店'],
];
// 1グループ幅をワイド画面幅以上にして -50% ループ時の右余白を防ぐ（4枚×5=20枚）
$shop_scroll_imgs = array_merge(...array_fill(0, 5, $shop_scroll_base));
// カテゴリ別件数（shop_category 実タームの件数を表示）
$shop_cat_terms = get_terms(['taxonomy' => TAX_SHOP_CAT, 'hide_empty' => false]);
$shop_cat_terms = is_wp_error($shop_cat_terms) ? [] : $shop_cat_terms;
?>
<section class="p-home-section p-home-section--sakura p-home-shops" aria-labelledby="home-shops-title">
	<div class="p-home-section__inner">
		<div class="p-home-section__head">
			<h2 class="p-home-section__title p-home-section__title--center" id="home-shops-title">七間町商店街のお店</h2>
			<p class="p-home-section__sub">静岡の真ん中に、人と店の物語がある。<br class="u-br-sp">カフェ、雑貨店、映画館、職人の工房まで。</p>
		</div>
	</div>

	<!-- 丸型画像 無限スクロール帯（-50% ループ用に同一グループを2周出力） -->
	<div class="p-home-shops__scroll-wrap" aria-hidden="true">
		<div class="p-home-shops__scroll-track">
			<?php for ($loop = 0; $loop < 2; $loop++) : ?>
				<?php foreach ($shop_scroll_imgs as $img) : ?>
					<div class="p-home-shops__circle">
						<img class="u-img-cover" src="<?php echo esc_url($img['src']); ?>" alt="<?php echo esc_attr($img['alt']); ?>" loading="lazy">
					</div>
				<?php endforeach; ?>
			<?php endfor; ?>
		</div>
	</div>
	<!-- /.p-home-shops__scroll-wrap -->

	<div class="p-home-section__inner">
		<div class="p-home-shops__cats">
			<?php foreach ($shop_cat_terms as $t) : ?>
				<a class="p-home-shops__cat" href="<?php echo esc_url(home_url('/shops/?cat[]=' . rawurlencode($t->slug))); ?>"><?php echo esc_html($t->name); ?><span class="p-home-shops__cat-count"><?php echo (int) $t->count; ?></span></a>
			<?php endforeach; ?>
		</div>

		<div class="p-home-shops__more">
			<a class="c-btn c-btn--outline" href="<?php echo esc_url(home_url('/shops/')); ?>">お店一覧をみる <svg class="c-btn__icon" aria-hidden="true" focusable="false">
					<use href="#icon-chevron-right"></use>
				</svg></a>
		</div>
	</div>
	<!-- /.p-home-section__inner -->
</section>
<!-- /.p-home-section 商店街のお店 -->

<?php
// ─── 観光マップ ────────────────────────────────────────────
// Leaflet で shop / spot のピンを表示。座標未入力のデータは enqueue.php で除外済み
?>
<section class="p-home-section p-home-section--white" aria-labelledby="home-map-title">
	<div class="p-home-section__inner">
		<div class="p-home-section__head">
			<h2 class="p-home-section__title p-home-section__title--bar" id="home-map-title">観光マップ</h2>
			<p class="p-home-section__sub u-text-center">七間町とその周辺のお店・スポットを<br class="u-br-sp">マップで確認できます</p>
		</div>

		<div class="p-home-map">

			<!-- フィルターボタン -->
			<div class="p-home-map__filters" role="group" aria-label="表示フィルター">
				<button class="p-home-map__filter-btn is-active" type="button" data-map-filter="all">すべて</button>
				<button class="p-home-map__filter-btn" type="button" data-map-filter="shop">お店</button>
				<button class="p-home-map__filter-btn" type="button" data-map-filter="spot">スポット</button>
			</div>
			<!-- /.p-home-map__filters -->

			<!-- Leaflet マップ本体 -->
			<div id="home-map-canvas" class="p-home-map__canvas" aria-label="七間町お店・スポットマップ"></div>

		</div>
		<!-- /.p-home-map -->

		<div class="p-home-map__more">
			<a class="c-btn c-btn--outline" href="<?php echo esc_url(home_url('/shops/')); ?>">お店一覧をみる <svg class="c-btn__icon" aria-hidden="true" focusable="false">
					<use href="#icon-chevron-right"></use>
				</svg></a>
			<a class="c-btn c-btn--outline" href="<?php echo esc_url(home_url('/spots/')); ?>">スポット一覧をみる <svg class="c-btn__icon" aria-hidden="true" focusable="false">
					<use href="#icon-chevron-right"></use>
				</svg></a>
		</div>
		<!-- /.p-home-map__more -->

	</div>
	<!-- /.p-home-section__inner -->
</section>
<!-- /.p-home-section 観光マップ -->

<?php
// ─── 七間町について ────────────────────────────────────────
$about_img = SC_TPL_URI . '/assets/images/top/hero-main.jpg';
?>
<section class="p-home-section p-home-section--washi" aria-labelledby="home-about-title">
	<div class="p-home-section__inner">
		<div class="p-home-about__grid">
			<div>
				<span class="p-home-section__label">七間町について</span>
				<h2 class="p-home-section__title" id="home-about-title">静岡市中心部にある<br>「文化×日常」が共存する街。</h2>
				<p class="p-home-about__desc">七間町は、江戸時代から続く歴史ある商店街。映画館や芝居小屋が立ち並び、文化の発信地として栄えてきました。今も残る昭和の面影と、新しいカフェやギャラリーが共存する、静岡の顔ともいえる場所です。</p>
				<a class="c-btn c-btn--primary" href="<?php echo esc_url(home_url('/about/')); ?>">もっと見る <svg class="c-btn__icon" aria-hidden="true" focusable="false">
						<use href="#icon-chevron-right"></use>
					</svg></a>
			</div>
			<div class="p-home-about__img">
				<img class="u-img-cover" src="<?php echo esc_url($about_img); ?>" alt="夜の七間町商店街" loading="lazy">
			</div>
		</div>
		<!-- /.p-home-about__grid -->
	</div>
	<!-- /.p-home-section__inner -->
</section>
<!-- /.p-home-section 七間町について -->

<?php
// ─── お隣さんの話（コラム特集）──────────────────────────
$neighbors_img = SC_TPL_URI . '/assets/images/top/hero-tourism.jpg';
?>
<section class="p-home-section p-home-section--sakura" aria-labelledby="home-neighbors-title">
	<div class="p-home-section__inner">
		<div class="p-home-neighbors__grid">

			<!-- 左: 画像 -->
			<div class="p-home-neighbors__img">
				<img class="u-img-cover" src="<?php echo esc_url($neighbors_img); ?>" alt="桜と富士山と商店街の風景" loading="lazy">
			</div>
			<!-- /.p-home-neighbors__img -->

			<!-- 右: テキスト -->
			<div class="p-home-neighbors__body">
				<span class="p-home-section__label">お隣さんの話</span>
				<h2 class="p-home-section__title" id="home-neighbors-title">あなたのすぐ近くにある、<br>誰かの物語。</h2>
				<p class="p-home-neighbors__desc">七間町で暮らし、働く人々のインタビュー。映画館スタッフ、建具職人、カフェオーナー… それぞれの視点から見た町の姿をお届けします。</p>
				<a class="c-btn c-btn--outline" href="<?php echo esc_url(home_url('/column/')); ?>">お隣さんの話へ <svg class="c-btn__icon" aria-hidden="true" focusable="false">
						<use href="#icon-chevron-right"></use>
					</svg></a>
			</div>
			<!-- /.p-home-neighbors__body -->

		</div>
		<!-- /.p-home-neighbors__grid -->
	</div>
	<!-- /.p-home-section__inner -->
</section>
<!-- /.p-home-section お隣さんの話 -->

<?php
// ─── 七間町の風景（ギャラリー）────────────────────────────
$gallery_imgs = [
	['src' => 'top/hero-main.jpg',    'alt' => '七間町の風景1'],
	['src' => 'top/hero-shops.jpg',   'alt' => '七間町の風景2'],
	['src' => 'top/hero-cinema.jpg',  'alt' => '七間町の風景3'],
	['src' => 'top/hero-tourism.jpg', 'alt' => '七間町の風景4'],
];
?>
<section class="p-home-section p-home-section--white" aria-labelledby="home-gallery-title">
	<div class="p-home-section__inner">
		<div class="p-home-section__head">
			<h2 class="p-home-section__title p-home-section__title--bar" id="home-gallery-title">七間町の風景</h2>
		</div>

		<div class="p-home-gallery__grid">
			<?php foreach ($gallery_imgs as $img) : ?>
				<div class="p-home-gallery__item">
					<img class="u-img-cover--transition" src="<?php echo esc_url(SC_TPL_URI . '/assets/images/' . $img['src']); ?>" alt="<?php echo esc_attr($img['alt']); ?>" loading="lazy">
				</div>
			<?php endforeach; ?>
		</div>
		<!-- /.p-home-gallery__grid -->

		<div class="p-home-gallery__more">
			<a class="c-btn c-btn--outline" href="<?php echo esc_url(home_url('/gallery/')); ?>">ギャラリーをみる <svg class="c-btn__icon" aria-hidden="true" focusable="false">
					<use href="#icon-chevron-right"></use>
				</svg></a>
		</div>
	</div>
	<!-- /.p-home-section__inner -->
</section>
<!-- /.p-home-section ギャラリー -->

<?php
// ─── アクセス ─────────────────────────────────────────────
$access_map_img = SC_TPL_URI . '/assets/images/common/access.jpg';
$access_routes  = [
	['icon' => 'icon-bolt-solid',    'from' => '新幹線「静岡駅」より',    'label' => '徒歩約15分'],
	['icon' => 'icon-map-pin-solid', 'from' => '静岡鉄道「新静岡駅」より', 'label' => '徒歩約11分'],
];
?>
<section class="p-home-section p-home-section--washi" aria-labelledby="home-access-title">
	<div class="p-home-section__inner">
		<div class="p-home-access__grid">

			<!-- 左: 地図画像 -->
			<div class="p-home-access__media">
				<img class="u-img-cover" src="<?php echo esc_url($access_map_img); ?>" alt="七間町周辺の地図" loading="lazy">
			</div>
			<!-- /.p-home-access__media -->

			<!-- 右: アクセス情報 -->
			<div class="p-home-access__body">
				<h2 class="p-home-section__title" id="home-access-title">七間町へのアクセス</h2>

				<div class="p-home-access__routes">
					<?php foreach ($access_routes as $route) : ?>
						<div class="p-home-access__route">
							<span class="p-home-access__icon" aria-hidden="true">
								<svg class="p-home-access__icon-svg" aria-hidden="true" focusable="false">
									<use href="#<?php echo esc_attr($route['icon']); ?>"></use>
								</svg>
							</span>
							<div>
								<p class="p-home-access__from"><?php echo esc_html($route['from']); ?></p>
								<p class="p-home-access__time"><?php echo esc_html($route['label']); ?></p>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<!-- /.p-home-access__routes -->

				<a class="c-btn c-btn--primary" href="<?php echo esc_url(home_url('/access/')); ?>">詳しいアクセス方法 <svg class="c-btn__icon" aria-hidden="true" focusable="false">
						<use href="#icon-chevron-right"></use>
					</svg></a>
			</div>
			<!-- /.p-home-access__body -->

		</div>
		<!-- /.p-home-access__grid -->
	</div>
	<!-- /.p-home-section__inner -->
</section>
<!-- /.p-home-section アクセス -->

<?php
// ─── 七ぶらコラム ──────────────────────────────────────────
$column_query = new WP_Query([
	'post_type'      => CPT_COLUMN,
	'posts_per_page' => 3,
	'orderby'        => 'date',
	'order'          => 'DESC',
]);
?>
<section class="p-home-section p-home-section--white" aria-labelledby="home-column-title">
	<div class="p-home-section__inner">
		<div class="p-home-section__head">
			<h2 class="p-home-section__title p-home-section__title--bar" id="home-column-title">七ぶらコラム</h2>
			<p class="p-home-section__sub u-text-center">七間町をぶらぶら歩きながら見つけた、小さな発見と物語。</p>
		</div>

		<?php if ($column_query->have_posts()) : ?>
			<div class="p-home-column__grid">
				<?php while ($column_query->have_posts()) : $column_query->the_post();
					$thumb = sc_thumbnail_url(get_the_ID(), 'medium_large');
				?>
					<article class="c-card">
						<a href="<?php the_permalink(); ?>">
							<div class="c-card__thumb">
								<img class="u-img-cover" src="<?php echo esc_url($thumb); ?>" alt="" aria-hidden="true" loading="lazy">
							</div>
						</a>
						<div class="c-card__body">
							<div class="c-card__meta">
								<span class="c-card__date"><?php echo esc_html(get_the_date('Y.m.d')); ?></span>
							</div>
							<h3 class="c-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<?php if (has_excerpt()) : ?>
								<p class="c-card__excerpt"><?php echo esc_html(get_the_excerpt()); ?></p>
							<?php endif; ?>
						</div>
					</article>
				<?php endwhile;
				wp_reset_postdata(); ?>
			</div>
			<!-- /.p-home-column__grid -->
		<?php endif; ?>

		<div class="p-home-column__more">
			<a class="c-btn c-btn--outline" href="<?php echo esc_url(home_url('/column/')); ?>">コラム一覧をみる <svg class="c-btn__icon" aria-hidden="true" focusable="false">
					<use href="#icon-chevron-right"></use>
				</svg></a>
		</div>
	</div>
	<!-- /.p-home-section__inner -->
</section>
<!-- /.p-home-section コラム -->

<?php
// ─── スポンサー ────────────────────────────────────────────
// 仮データ（現状ロゴ画像は no-image 表示・src は差し替え予定のファイル名）
$sponsor_base = [
	['src' => 'sponsor-1.png', 'label' => 'スポンサーロゴ 1'],
	['src' => 'sponsor-2.png', 'label' => 'スポンサーロゴ 2'],
	['src' => 'sponsor-3.png', 'label' => 'スポンサーロゴ 3'],
	['src' => 'sponsor-4.png', 'label' => 'スポンサーロゴ 4'],
];
$sponsors = array_merge($sponsor_base, $sponsor_base);
?>
<section class="p-home-section p-home-section--washi" aria-labelledby="home-sponsor-title">
	<div class="p-home-section__inner">
		<h2 class="p-home-section__title p-home-section__title--bar" id="home-sponsor-title">スポンサー</h2>
		<div class="p-home-sponsor__grid">
			<?php foreach ($sponsors as $s) : ?>
				<div class="p-home-sponsor__item">
					<img class="u-img-contain" src="<?php echo esc_url(sc_no_image_url()); ?>" alt="<?php echo esc_attr($s['label']); ?>" loading="lazy">
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<!-- /.p-home-sponsor -->

<?php
// ─── メディアパートナー ────────────────────────────────────
// 仮データ（スポンサー同様、ロゴ差し替え待ち）
$media_base = [
	['src' => 'media-1.png', 'label' => 'メディアロゴ 1'],
	['src' => 'media-2.png', 'label' => 'メディアロゴ 2'],
	['src' => 'media-3.png', 'label' => 'メディアロゴ 3'],
	['src' => 'media-4.png', 'label' => 'メディアロゴ 4'],
];
$media_partners = array_merge($media_base, $media_base);
?>
<section class="p-home-section p-home-section--white" aria-labelledby="home-media-title">
	<div class="p-home-section__inner">
		<h2 class="p-home-section__title p-home-section__title--bar" id="home-media-title">メディアパートナー</h2>
		<div class="p-home-sponsor__grid">
			<?php foreach ($media_partners as $m) : ?>
				<div class="p-home-sponsor__item p-home-sponsor__item--media">
					<img class="u-img-contain" src="<?php echo esc_url(sc_no_image_url()); ?>" alt="<?php echo esc_attr($m['label']); ?>" loading="lazy">
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<!-- /.p-home-media -->

<?php get_footer(); ?>