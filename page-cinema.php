<?php

/** 映画の町ページ */
get_header();
?>

<?php get_template_part('template-parts/components/breadcrumbs'); ?>

<article class="p-cinema">

	<?php
	get_template_part('template-parts/components/page-hero', null, [
		'title' => '映画の町',
		'sub'   => '映画が暮らしになっている町。',
	]);
	?>


	<!-- ─── コンセプト ── -->
	<section class="p-cinema__concept" aria-labelledby="cinema-concept-title">
		<div class="p-cinema__concept-inner">
			<div class="p-cinema__concept-left">
				<h2 class="p-cinema__concept-title" id="cinema-concept-title">映画が<br>"暮らし"になっている町</h2>
				<p class="p-cinema__concept-text">長い時間をかけて映画文化を受け入れ、支え、生活の一部として育ててきた町。スクリーンの中の物語と、町の日常がゆるやかにつながり、映画を観ることが特別なイベントではなく、「いつもの週末」として存在する。七間町は、「文化が息づくまちづくり」の象徴です。</p>
				<div class="p-cinema__concept-toc">
					<h3 class="p-cinema__concept-toc-label">ストーリーガイド</h3>
					<ol class="p-cinema__concept-toc-list">
						<li class="p-cinema__concept-toc-item">
							<a class="p-cinema__concept-toc-link" href="#cinema-history-block-01">
								<span class="p-cinema__concept-toc-num">01</span>
								<span class="p-cinema__concept-toc-body">
									<span class="p-cinema__concept-toc-name">この町の映画性</span>
									<span class="p-cinema__concept-toc-sub">江戸時代</span>
								</span>
							</a>
						</li>
						<li class="p-cinema__concept-toc-item">
							<a class="p-cinema__concept-toc-link" href="#cinema-history-block-02">
								<span class="p-cinema__concept-toc-num">02</span>
								<span class="p-cinema__concept-toc-body">
									<span class="p-cinema__concept-toc-name">七間町映画史</span>
									<span class="p-cinema__concept-toc-sub">江戸〜明治</span>
								</span>
							</a>
						</li>
						<li class="p-cinema__concept-toc-item">
							<a class="p-cinema__concept-toc-link" href="#cinema-history-block-03">
								<span class="p-cinema__concept-toc-num">03</span>
								<span class="p-cinema__concept-toc-body">
									<span class="p-cinema__concept-toc-name">昔の映画館</span>
									<span class="p-cinema__concept-toc-sub">大正〜昭和</span>
								</span>
							</a>
						</li>
						<li class="p-cinema__concept-toc-item">
							<a class="p-cinema__concept-toc-link" href="#cinema-history-block-04">
								<span class="p-cinema__concept-toc-num">04</span>
								<span class="p-cinema__concept-toc-body">
									<span class="p-cinema__concept-toc-name">いまの映画館</span>
									<span class="p-cinema__concept-toc-sub">大正〜昭和</span>
								</span>
							</a>
						</li>
						<li class="p-cinema__concept-toc-item">
							<a class="p-cinema__concept-toc-link" href="#cinema-now-title">
								<span class="p-cinema__concept-toc-num">05</span>
								<span class="p-cinema__concept-toc-body">
									<span class="p-cinema__concept-toc-name">映画館の余韻で歩く</span>
								</span>
							</a>
						</li>
					</ol>
				</div>
				<!-- /.p-cinema__concept-toc -->
			</div>
			<!-- /.p-cinema__concept-left -->
			<div class="p-cinema__concept-img" aria-hidden="true">
				<picture class="u-picture-fill">
					<img class="u-img-cover" src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/top/hero-cinema.jpg'); ?>" alt="" aria-hidden="true" loading="eager" width="1200" height="800">
				</picture>
			</div>
			<!-- /.p-cinema__concept-img -->
		</div>
		<!-- /.p-cinema__concept-inner -->
	</section>
	<!-- /.p-cinema__concept -->

	<!-- ─── 映画史 ── -->
	<section class="p-cinema__history" aria-labelledby="cinema-history-heading-01">

		<!-- 01 誕生（テキスト左・画像右） -->
		<div class="p-cinema__history-block" id="cinema-history-block-01">
			<div class="p-cinema__history-block-text">
				<span class="p-cinema__history-block-num" aria-hidden="true">01</span>
				<span class="p-cinema__history-block-label">ヒストリー</span>
				<h2 class="p-cinema__history-block-title" id="cinema-history-heading-01">七間町映画史｜なぜ七間町は「映画の町」なのか</h2>
				<p class="p-cinema__history-block-body">七間町は、もともと「華やかさ」が根づく町でした。商いと人の往来が絶えることのない、芝居小屋や寄席場が並ぶ、日常の中で特別に触れる場所でした。そこに映画という新しい娯楽が入り込んだとき、人々はまるで当然のように受け入れ、観客を続けていったのです。</p>
			</div>
			<!-- /.p-cinema__history-block-text -->
			<div class="p-cinema__history-block-img" aria-hidden="true">
				<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/top/hero-cinema.jpg'); ?>" alt="" aria-hidden="true" loading="lazy" width="800" height="540">
			</div>
			<!-- /.p-cinema__history-block-img -->
		</div>
		<!-- /.p-cinema__history-block -->

		<!-- 02 拡大（テキスト左・画像右） -->
		<div class="p-cinema__history-block p-cinema__history-block--reverse" id="cinema-history-block-02">
			<div class="p-cinema__history-block-text">
				<span class="p-cinema__history-block-num" aria-hidden="true">02</span>
				<span class="p-cinema__history-block-label">拡大</span>
				<h3 class="p-cinema__history-block-title">映画館の街、ここに生まれる</h3>
				<p class="p-cinema__history-block-body">営業に代わり、動く映像が人々を魅了し、七間町は東海道に近い立地を活かし、くつろぎの場所を受け入れます。芝居小屋や寄席場が並ぶ日常の中で、映画という新しい娯楽が入り込んだとき、人々はまるで当然のように受け入れ、観客を続けていったのです。七間町の商人たちは、旅籠屋も兼ねる店を構え、町は常に新しい刺激にさらされていました。</p>
			</div>
			<!-- /.p-cinema__history-block-text -->
			<div class="p-cinema__history-block-img" aria-hidden="true">
				<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/top/hero-main.jpg'); ?>" alt="" aria-hidden="true" loading="lazy" width="800" height="540">
			</div>
			<!-- /.p-cinema__history-block-img -->
		</div>
		<!-- /.p-cinema__history-block -->

		<!-- 03 変化（テキスト左・画像右） -->
		<div class="p-cinema__history-block" id="cinema-history-block-03">
			<div class="p-cinema__history-block-text">
				<span class="p-cinema__history-block-num" aria-hidden="true">03</span>
				<span class="p-cinema__history-block-label">変化</span>
				<h3 class="p-cinema__history-block-title">時代の波と七間町</h3>
				<p class="p-cinema__history-block-body">戦後から高度経済成長期にかけて、七間町には変化の波が押し寄せました。このワクワクの舞台、仕事帰りに一杯、休日に家族と映画…そんな風景が当たり前だったのです。人々は芝居を観に来て、映画を観に来て、そして買い物をし、喫茶店に、洋食屋に立ち寄り、15分があっという間に感じられました。</p>
			</div>
			<!-- /.p-cinema__history-block-text -->
			<div class="p-cinema__history-block-img" aria-hidden="true">
				<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/top/hero-shops.jpg'); ?>" alt="" aria-hidden="true" loading="lazy" width="800" height="540">
			</div>
			<!-- /.p-cinema__history-block-img -->
		</div>
		<!-- /.p-cinema__history-block -->

		<!-- 04 継承（テキスト左・画像右） -->
		<div class="p-cinema__history-block p-cinema__history-block--reverse" id="cinema-history-block-04">
			<div class="p-cinema__history-block-text">
				<span class="p-cinema__history-block-num" aria-hidden="true">04</span>
				<span class="p-cinema__history-block-label">ムービー特集</span>
				<h3 class="p-cinema__history-block-title">映画文化を受け継ぐ町</h3>
				<p class="p-cinema__history-block-body">時代の変化とともに、多くの映画館は姿を消しました。しかし、七間町から映画文化が消えたわけではありません。七間町には、長く続いた店が静かに幕を下ろす一方で、新しい店が生まれ、町の顔ぶれが少しずつ変わっていきました。こうして、七間町は「映画の町」として、映画文化を受け継いできた町なのです。</p>
			</div>
			<!-- /.p-cinema__history-block-text -->
			<div class="p-cinema__history-block-img" aria-hidden="true">
				<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/top/hero-tourism.jpg'); ?>" alt="" aria-hidden="true" loading="lazy" width="800" height="540">
			</div>
			<!-- /.p-cinema__history-block-img -->
		</div>
		<!-- /.p-cinema__history-block -->

	</section>
	<!-- /.p-cinema__history -->

	<!-- ─── 昔の映画館 ── -->
	<section class="p-cinema__old" id="cinema-old-title" aria-labelledby="cinema-old-heading">
		<div class="p-cinema__old-inner">
			<h2 class="p-cinema__old-title" id="cinema-old-heading">昔の映画館｜たくさんの灯りがあった頃</h2>
			<p class="p-cinema__old-text">七間町周辺には、かつて複数の映画館が存在しました。ヒカデン、富竹（七間町電気館跡地）、ここでは「映画の街」として、映画館の名前と軌跡を紹介しています。</p>
			<div class="p-cinema__old-photos">
				<figure class="p-cinema__old-photo">
					<img class="u-img-cover" src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/top/hero-cinema.jpg'); ?>" alt="七間町電気館" loading="lazy" width="400" height="300">
					<figcaption class="p-cinema__old-photo-caption">七間町電気館</figcaption>
				</figure>
				<figure class="p-cinema__old-photo">
					<img class="u-img-cover" src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/top/hero-main.jpg'); ?>" alt="静岡劇場" loading="lazy" width="400" height="300">
					<figcaption class="p-cinema__old-photo-caption">静岡劇場</figcaption>
				</figure>
				<figure class="p-cinema__old-photo">
					<img class="u-img-cover" src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/top/hero-shops.jpg'); ?>" alt="オリオン座" loading="lazy" width="400" height="300">
					<figcaption class="p-cinema__old-photo-caption">オリオン座</figcaption>
				</figure>
				<figure class="p-cinema__old-photo">
					<img class="u-img-cover" src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/top/hero-tourism.jpg'); ?>" alt="シネマパレス" loading="lazy" width="400" height="300">
					<figcaption class="p-cinema__old-photo-caption">シネマパレス</figcaption>
				</figure>
			</div>
			<!-- /.p-cinema__old-photos -->
		</div>
		<!-- /.p-cinema__old-inner -->
	</section>
	<!-- /.p-cinema__old -->

	<!-- ─── 現在の映画館 ── -->
	<section class="p-cinema__now" id="cinema-now-title" aria-labelledby="cinema-now-heading">
		<div class="p-cinema__now-inner">
			<h2 class="p-cinema__now-title" id="cinema-now-heading">映画館のある景色｜いまの七間町</h2>
			<p class="p-cinema__now-lead">七間町・周辺には、現在も3つの映画館が営業中です。映画が終わった後も時間が続く、ここで暮らす幸せのひとつです。</p>
			<ul class="p-cinema__now-list">

				<li class="p-cinema__now-card">
					<div class="p-cinema__now-card-img" aria-hidden="true">
						<img class="u-img-cover" src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/top/hero-cinema.jpg'); ?>" alt="" aria-hidden="true" loading="lazy" width="80" height="80">
					</div>
					<!-- /.p-cinema__now-card-img -->
					<div class="p-cinema__now-card-body">
						<span class="p-cinema__now-card-type">総合</span>
						<h3 class="p-cinema__now-card-name">静岡東宝会館</h3>
						<p class="p-cinema__now-card-area">
							<svg aria-hidden="true" focusable="false" class="p-cinema__now-card-pin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
								<use href="#icon-map-pin"></use>
							</svg>
							七間町○○○○○○○○○○○○○○○○○○○○○○
						</p>
					</div>
					<!-- /.p-cinema__now-card-body -->
					<a class="p-cinema__now-card-btn" href="#" target="_blank" rel="noopener noreferrer">
						公式サイトを見る
						<svg aria-hidden="true" focusable="false" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<use href="#icon-external"></use>
						</svg>
					</a>
				</li>
				<!-- /.p-cinema__now-card -->

				<li class="p-cinema__now-card">
					<div class="p-cinema__now-card-img" aria-hidden="true">
						<img class="u-img-cover" src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/top/hero-main.jpg'); ?>" alt="" aria-hidden="true" loading="lazy" width="80" height="80">
					</div>
					<!-- /.p-cinema__now-card-img -->
					<div class="p-cinema__now-card-body">
						<span class="p-cinema__now-card-type">独立</span>
						<h3 class="p-cinema__now-card-name">シネシティ ザート／ザート2</h3>
						<p class="p-cinema__now-card-area">
							<svg aria-hidden="true" focusable="false" class="p-cinema__now-card-pin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
								<use href="#icon-map-pin"></use>
							</svg>
							アクセス
						</p>
					</div>
					<!-- /.p-cinema__now-card-body -->
					<a class="p-cinema__now-card-btn" href="#" target="_blank" rel="noopener noreferrer">
						公式サイトを見る
						<svg aria-hidden="true" focusable="false" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<use href="#icon-external"></use>
						</svg>
					</a>
				</li>
				<!-- /.p-cinema__now-card -->

				<li class="p-cinema__now-card">
					<div class="p-cinema__now-card-img" aria-hidden="true">
						<img class="u-img-cover" src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/top/hero-shops.jpg'); ?>" alt="" aria-hidden="true" loading="lazy" width="80" height="80">
					</div>
					<!-- /.p-cinema__now-card-img -->
					<div class="p-cinema__now-card-body">
						<span class="p-cinema__now-card-type">独立</span>
						<h3 class="p-cinema__now-card-name">静岡シネ・ギャラリー</h3>
						<p class="p-cinema__now-card-area">
							<svg aria-hidden="true" focusable="false" class="p-cinema__now-card-pin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
								<use href="#icon-map-pin"></use>
							</svg>
							アクセス
						</p>
					</div>
					<!-- /.p-cinema__now-card-body -->
					<a class="p-cinema__now-card-btn" href="#" target="_blank" rel="noopener noreferrer">
						公式サイトを見る
						<svg aria-hidden="true" focusable="false" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<use href="#icon-external"></use>
						</svg>
					</a>
				</li>
				<!-- /.p-cinema__now-card -->

			</ul>
			<!-- /.p-cinema__now-list -->
		</div>
		<!-- /.p-cinema__now-inner -->
	</section>
	<!-- /.p-cinema__now -->

	<!-- ─── 次のページCTA ── -->
	<section class="p-cinema__next" id="cinema-next" aria-label="次のページへ">
		<div class="p-cinema__next-inner">
			<h2 class="p-cinema__next-title">次は、七間町を「歩いて」体験する</h2>
			<p class="p-cinema__next-text">歴史を知ったあとは、実際に七間町を歩いてみましょう。駿府城跡から七間町、そして周辺エリアへ。まちの空気やお店の雰囲気を、ぜひ体感してください。</p>
			<a class="c-btn c-btn--primary" href="<?php echo esc_url(home_url('/walk/')); ?>">
				七間町を歩く
				<svg class="c-btn__icon" aria-hidden="true" focusable="false">
					<use href="#icon-chevron-right"></use>
				</svg>
			</a>
		</div>
		<!-- /.p-cinema__next-inner -->
	</section>
	<!-- /.p-cinema__next -->

</article>
<!-- /.p-cinema -->

<?php get_footer(); ?>