<?php
/** 町で働くページ */
get_header();
?>

<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>

<article class="p-working">

	<?php
		get_template_part( 'template-parts/components/page-hero', null, [
			'title' => '町で働く',
			'sub'   => '七間町で、あなたの居場所を見つけよう。',
		] );
		?>

	<!-- ─── スポンサー法人 ── -->
	<section class="p-working__sponsors" aria-labelledby="working-sponsors-title">
		<div class="p-working__sponsors-inner">
			<div class="p-working__sponsors-header">
				<span class="p-working__sponsors-label">SPONSOR</span>
				<h2 class="p-working__sponsors-title" id="working-sponsors-title">注目の法人</h2>
			</div>
			<!-- /.p-working__sponsors-header -->
			<div class="p-working__sponsors-grid">

				<a class="p-working__sponsor-card" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">
					<span class="p-working__sponsor-pr" aria-label="PR">PR</span>
					<div class="p-working__sponsor-head">
						<div class="p-working__sponsor-thumb">
							<picture class="u-picture-fill">
								<img class="u-img-cover" src="<?php echo esc_url( sc_no_image_url() ); ?>" alt="" aria-hidden="true" loading="lazy" width="120" height="120">
							</picture>
						</div>
						<div class="p-working__sponsor-info">
							<h3 class="p-working__sponsor-name">七間町商店街振興組合</h3>
							<span class="p-working__sponsor-category">まちづくり・イベント</span>
						</div>
					</div>
					<!-- /.p-working__sponsor-head -->
					<p class="p-working__sponsor-desc">七間町商店街の活性化を推進する組合。イベント企画、広報、地域連携など多彩な仕事があります。</p>
					<div class="p-working__sponsor-foot">
						<span class="p-working__sponsor-count">
							<svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-briefcase"></use></svg>
							求人 3件
						</span>
						<span class="p-working__sponsor-more">詳しく見る →</span>
					</div>
					<!-- /.p-working__sponsor-foot -->
				</a>
				<!-- /.p-working__sponsor-card -->

				<a class="p-working__sponsor-card" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">
					<span class="p-working__sponsor-pr" aria-label="PR">PR</span>
					<div class="p-working__sponsor-head">
						<div class="p-working__sponsor-thumb">
							<picture class="u-picture-fill">
								<img class="u-img-cover" src="<?php echo esc_url( sc_no_image_url() ); ?>" alt="" aria-hidden="true" loading="lazy" width="120" height="120">
							</picture>
						</div>
						<div class="p-working__sponsor-info">
							<h3 class="p-working__sponsor-name">七間町デザイン事務所</h3>
							<span class="p-working__sponsor-category">IT・Web制作</span>
						</div>
					</div>
					<!-- /.p-working__sponsor-head -->
					<p class="p-working__sponsor-desc">地域のDX推進を担うクリエイティブカンパニー。リモートワーク可、フレックス制度あり。</p>
					<div class="p-working__sponsor-foot">
						<span class="p-working__sponsor-count">
							<svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-briefcase"></use></svg>
							求人 2件
						</span>
						<span class="p-working__sponsor-more">詳しく見る →</span>
					</div>
					<!-- /.p-working__sponsor-foot -->
				</a>
				<!-- /.p-working__sponsor-card -->

				<a class="p-working__sponsor-card" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">
					<span class="p-working__sponsor-pr" aria-label="PR">PR</span>
					<div class="p-working__sponsor-head">
						<div class="p-working__sponsor-thumb">
							<picture class="u-picture-fill">
								<img class="u-img-cover" src="<?php echo esc_url( sc_no_image_url() ); ?>" alt="" aria-hidden="true" loading="lazy" width="120" height="120">
							</picture>
						</div>
						<div class="p-working__sponsor-info">
							<h3 class="p-working__sponsor-name">七間町建具工房</h3>
							<span class="p-working__sponsor-category">伝統工芸・建築</span>
						</div>
					</div>
					<!-- /.p-working__sponsor-head -->
					<p class="p-working__sponsor-desc">40年の伝統を継承する建具工房。職人技を次世代に伝える仕事。</p>
					<div class="p-working__sponsor-foot">
						<span class="p-working__sponsor-count">
							<svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-briefcase"></use></svg>
							求人 1件
						</span>
						<span class="p-working__sponsor-more">詳しく見る →</span>
					</div>
					<!-- /.p-working__sponsor-foot -->
				</a>
				<!-- /.p-working__sponsor-card -->

			</div>
			<!-- /.p-working__sponsors-grid -->
		</div>
		<!-- /.p-working__sponsors-inner -->
	</section>
	<!-- /.p-working__sponsors -->

	<!-- ─── 求人一覧 ── -->
	<section class="p-working__listings" aria-labelledby="working-listings-title">
		<div class="p-working__listings-inner">

			<!-- フィルターサイドバー -->
			<aside class="p-working__filter" aria-label="求人絞り込み">
				<h2 class="p-working__filter-heading">
					<svg class="p-working__filter-heading-icon" aria-hidden="true" focusable="false" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
					絞り込み条件
				</h2>

				<div class="p-working__filter-group">
					<label class="p-working__filter-label" for="filter-keyword">キーワード検索</label>
					<div class="p-working__filter-search">
						<svg class="p-working__filter-search-icon" aria-hidden="true" focusable="false"><use href="#icon-search"></use></svg>
						<input class="p-working__filter-input" type="search" id="filter-keyword" placeholder="職種、会社名...">
					</div>
				</div>

				<div class="p-working__filter-group">
					<span class="p-working__filter-label">職種カテゴリー</span>
					<div class="c-chips" data-filter-group="category">
						<button class="c-chips__chip c-chips__chip--solid is-active" type="button" data-value="">すべて</button>
						<button class="c-chips__chip" type="button" data-value="企画・営業">企画・営業</button>
						<button class="c-chips__chip" type="button" data-value="飲食">飲食</button>
						<button class="c-chips__chip" type="button" data-value="職人・技術">職人・技術</button>
						<button class="c-chips__chip" type="button" data-value="販売・接客">販売・接客</button>
						<button class="c-chips__chip" type="button" data-value="IT・クリエイティブ">IT・クリエイティブ</button>
						<button class="c-chips__chip" type="button" data-value="医療・福祉">医療・福祉</button>
					</div>
				</div>

				<div class="p-working__filter-group">
					<span class="p-working__filter-label">雇用形態</span>
					<div class="c-chips" data-filter-group="type">
						<button class="c-chips__chip c-chips__chip--solid is-active" type="button" data-value="">すべて</button>
						<button class="c-chips__chip" type="button" data-value="正社員">正社員</button>
						<button class="c-chips__chip" type="button" data-value="パート・アルバイト">パート・アルバイト</button>
						<button class="c-chips__chip" type="button" data-value="契約社員">契約社員</button>
						<button class="c-chips__chip" type="button" data-value="業務委託">業務委託</button>
					</div>
				</div>
			</aside>
			<!-- /.p-working__filter -->

			<div class="p-working__main js-work-filter">
				<p class="p-working__count"><strong class="js-work-count">7</strong>件の求人 <span class="p-working__count-page">（1/2ページ）</span></p>

				<div class="p-working__jobs">

				<a class="p-working__job p-working__job--pickup" href="#job-modal-1" data-modal-target="#job-modal-1">
					<span class="p-working__job-type">正社員</span>
					<span class="p-working__job-pickup">おすすめ</span>
					<h3 class="p-working__job-title">イベント企画・営業スタッフ募集</h3>
					<p class="p-working__job-company">七間町商店街振興組合</p>
					<p class="p-working__job-desc">七間町商店街の活性化を推進するイベント企画・営業スタッフ。年間15〜20件のイベント企画・運営、営業活動、SNS更新などを担当。地域の魅力を発信し、商店街に人を呼び込む仕事です。</p>
					<div class="p-working__job-meta">
						<span class="p-working__job-meta-item"><svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-yen"></use></svg>月給 25〜35万円</span>
						<span class="p-working__job-meta-item"><svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-map-pin"></use></svg>静岡県静岡市葵区七間町</span>
						<span class="p-working__job-meta-item"><svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-tag"></use></svg>企画・営業</span>
					</div>
					<!-- /.p-working__job-meta -->
					<ul class="p-working__job-tags" role="list">
						<li class="p-working__job-tag">企画・営業</li>
						<li class="p-working__job-tag">イベント企画</li>
						<li class="p-working__job-tag">営業</li>
						<li class="p-working__job-tag">正社員</li>
						<li class="p-working__job-tag">商店街</li>
					</ul>
					<!-- /.p-working__job-tags -->
				</a>
				<!-- /.p-working__job -->

				<a class="p-working__job" href="#job-modal-2" data-modal-target="#job-modal-2">
					<span class="p-working__job-type">パート・アルバイト</span>
					
					<h3 class="p-working__job-title">カフェスタッフ募集</h3>
					<p class="p-working__job-company">カフェ・ド・七間</p>
					<p class="p-working__job-desc">朝のモーニング営業から夜のカフェタイムまで。地域のお客様とのコミュニケーションを大切にする職場です。未経験歓迎、丁寧に教えます。</p>
					<div class="p-working__job-meta">
						<span class="p-working__job-meta-item"><svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-yen"></use></svg>時給 1,100〜1,300円</span>
						<span class="p-working__job-meta-item"><svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-map-pin"></use></svg>七間町2丁目</span>
						<span class="p-working__job-meta-item"><svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-tag"></use></svg>飲食</span>
					</div>
					<!-- /.p-working__job-meta -->
					<ul class="p-working__job-tags" role="list">
						<li class="p-working__job-tag">飲食</li>
						<li class="p-working__job-tag">カフェ</li>
						<li class="p-working__job-tag">パート</li>
						<li class="p-working__job-tag">未経験歓迎</li>
					</ul>
					<!-- /.p-working__job-tags -->
				</a>
				<!-- /.p-working__job -->

				<a class="p-working__job p-working__job--pickup" href="#job-modal-3" data-modal-target="#job-modal-3">
					<span class="p-working__job-type">正社員</span>
					<span class="p-working__job-pickup">おすすめ</span>
					<h3 class="p-working__job-title">建具職人 弟子募集</h3>
					<p class="p-working__job-company">七間町建具工房</p>
					<p class="p-working__job-desc">40年の伝統を継承する建具職人。オーダーメイド建具製造から修理・メンテナンスまで、幅広い技術を習得できます。ものづくりが好きな方、伝統工芸に興味がある方を歓迎します。</p>
					<div class="p-working__job-meta">
						<span class="p-working__job-meta-item"><svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-yen"></use></svg>月給 22〜30万円</span>
						<span class="p-working__job-meta-item"><svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-map-pin"></use></svg>七間町1丁目</span>
						<span class="p-working__job-meta-item"><svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-tag"></use></svg>職人・技術</span>
					</div>
					<!-- /.p-working__job-meta -->
					<ul class="p-working__job-tags" role="list">
						<li class="p-working__job-tag">職人・技術</li>
						<li class="p-working__job-tag">建具</li>
						<li class="p-working__job-tag">正社員</li>
						<li class="p-working__job-tag">伝統工芸</li>
					</ul>
					<!-- /.p-working__job-tags -->
				</a>
				<!-- /.p-working__job -->

				<a class="p-working__job" href="#job-modal-4" data-modal-target="#job-modal-4">
					<span class="p-working__job-type">パート・アルバイト</span>
					
					<h3 class="p-working__job-title">書店スタッフ（レジ・品出し）</h3>
					<p class="p-working__job-company">七間町ブックス</p>
					<p class="p-working__job-desc">地域に愛される老舗書店でのスタッフ募集。レジ対応・棚卸し・品出しをお任せします。本が好きな方、地域の文化を支えたい方を歓迎します。</p>
					<div class="p-working__job-meta">
						<span class="p-working__job-meta-item"><svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-yen"></use></svg>時給 1,000〜1,150円</span>
						<span class="p-working__job-meta-item"><svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-map-pin"></use></svg>七間町3丁目</span>
						<span class="p-working__job-meta-item"><svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-tag"></use></svg>販売・接客</span>
					</div>
					<!-- /.p-working__job-meta -->
					<ul class="p-working__job-tags" role="list">
						<li class="p-working__job-tag">販売・接客</li>
						<li class="p-working__job-tag">書店</li>
						<li class="p-working__job-tag">パート</li>
					</ul>
					<!-- /.p-working__job-tags -->
				</a>
				<!-- /.p-working__job -->

				<a class="p-working__job" href="#job-modal-5" data-modal-target="#job-modal-5">
					<span class="p-working__job-type">正社員・業務委託</span>
					
					<h3 class="p-working__job-title">Webデザイナー・コーダー</h3>
					<p class="p-working__job-company">七間町デザイン事務所</p>
					<p class="p-working__job-desc">地域の中小企業・商店街のWebサイト制作・リニューアルを担当。デザインからコーディングまで一貫して携わります。リモートワーク可・フレックス制度あり。</p>
					<div class="p-working__job-meta">
						<span class="p-working__job-meta-item"><svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-yen"></use></svg>月給 25〜40万円</span>
						<span class="p-working__job-meta-item"><svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-map-pin"></use></svg>七間町2丁目（リモート可）</span>
						<span class="p-working__job-meta-item"><svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-tag"></use></svg>IT・クリエイティブ</span>
					</div>
					<!-- /.p-working__job-meta -->
					<ul class="p-working__job-tags" role="list">
						<li class="p-working__job-tag">IT・クリエイティブ</li>
						<li class="p-working__job-tag">Webデザイン</li>
						<li class="p-working__job-tag">正社員</li>
						<li class="p-working__job-tag">リモート可</li>
					</ul>
					<!-- /.p-working__job-tags -->
				</a>
				<!-- /.p-working__job -->

				<a class="p-working__job" href="#job-modal-6" data-modal-target="#job-modal-6">
					<span class="p-working__job-type">パート・アルバイト</span>
					
					<h3 class="p-working__job-title">介護スタッフ（訪問介護）</h3>
					<p class="p-working__job-company">七間町ケアサービス</p>
					<p class="p-working__job-desc">七間町在住の高齢者宅への訪問介護スタッフを募集。地域に根ざした温かなケアを提供します。介護資格取得支援制度あり。</p>
					<div class="p-working__job-meta">
						<span class="p-working__job-meta-item"><svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-yen"></use></svg>時給 1,200〜1,500円</span>
						<span class="p-working__job-meta-item"><svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-map-pin"></use></svg>七間町周辺</span>
						<span class="p-working__job-meta-item"><svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-tag"></use></svg>医療・福祉</span>
					</div>
					<!-- /.p-working__job-meta -->
					<ul class="p-working__job-tags" role="list">
						<li class="p-working__job-tag">医療・福祉</li>
						<li class="p-working__job-tag">介護</li>
						<li class="p-working__job-tag">パート</li>
						<li class="p-working__job-tag">資格支援</li>
					</ul>
					<!-- /.p-working__job-tags -->
				</a>
				<!-- /.p-working__job -->

				<a class="p-working__job" href="#job-modal-7" data-modal-target="#job-modal-7">
					<span class="p-working__job-type">正社員</span>
					
					<h3 class="p-working__job-title">和菓子職人（製造スタッフ）</h3>
					<p class="p-working__job-company">菓子処 七間堂</p>
					<p class="p-working__job-desc">創業80年の老舗和菓子店で職人を募集。季節の上生菓子・餅菓子の製造をお任せします。未経験から職人を目指せる環境です。</p>
					<div class="p-working__job-meta">
						<span class="p-working__job-meta-item"><svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-yen"></use></svg>月給 20〜28万円</span>
						<span class="p-working__job-meta-item"><svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-map-pin"></use></svg>七間町1丁目</span>
						<span class="p-working__job-meta-item"><svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-tag"></use></svg>職人・技術</span>
					</div>
					<!-- /.p-working__job-meta -->
					<ul class="p-working__job-tags" role="list">
						<li class="p-working__job-tag">職人・技術</li>
						<li class="p-working__job-tag">和菓子</li>
						<li class="p-working__job-tag">正社員</li>
						<li class="p-working__job-tag">未経験可</li>
					</ul>
					<!-- /.p-working__job-tags -->
				</a>
				<!-- /.p-working__job -->

			</div>
			<!-- /.p-working__jobs -->

			<!-- ─── ページネーション ── -->
			<nav class="c-pagination" aria-label="ページ送り">
				<a class="c-pagination__link c-pagination__link--prev" href="#" aria-disabled="true">
					<svg aria-hidden="true" focusable="false" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
					前へ
				</a>
				<span class="c-pagination__current">1</span>
				<a class="c-pagination__link" href="?paged=2">2</a>
				<a class="c-pagination__link c-pagination__link--next" href="?paged=2">
					次へ
					<svg aria-hidden="true" focusable="false" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
				</a>
			</nav>
			<!-- /.c-pagination -->

			</div>
			<!-- /.p-working__main -->

		</div>
		<!-- /.p-working__listings-inner -->
	</section>
	<!-- /.p-working__listings -->

<!-- ─── 求人モーダル ── -->
<div class="c-modal" id="job-modal-1" hidden aria-hidden="true">
	<div class="c-modal__overlay" data-close></div>
	<div class="c-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="job-modal-1-title">
		<button type="button" class="c-modal__close" data-close aria-label="閉じる">
			<svg aria-hidden="true" focusable="false"><use href="#icon-close"></use></svg>
		</button>
		<div class="c-modal__head">
			<span class="c-modal__type">正社員</span>
			<h2 class="c-modal__title" id="job-modal-1-title">イベント企画・営業スタッフ募集</h2>
			<p class="c-modal__subtitle">
				<svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-store"></use></svg>
				七間町商店街振興組合
			</p>
		</div>
		<div class="c-modal__content">
			<div class="c-modal__section">
				<h3 class="c-modal__section-title">仕事内容</h3>
				<p class="c-modal__section-text">七間町商店街の活性化を推進するイベント企画・営業スタッフ。年間15〜20件のイベント企画・運営、営業活動、SNS更新などを担当。地域の魅力を発信し、商店街に人を呼び込む仕事です。</p>
			</div>
			<div class="c-modal__section">
				<div class="c-modal__info-grid">
					<div class="c-modal__info-box">
						<p class="c-modal__info-label">給与</p>
						<p class="c-modal__info-value">月給 25〜35万円</p>
					</div>
					<div class="c-modal__info-box">
						<p class="c-modal__info-label">勤務地</p>
						<p class="c-modal__info-value">静岡県静岡市葵区七間町</p>
					</div>
<div class="c-modal__info-box">
						<p class="c-modal__info-label">勤務時間</p>
						<p class="c-modal__info-value">9:00〜18:00（実働8時間）</p>
					</div>
					<div class="c-modal__info-box">
						<p class="c-modal__info-label">休日</p>
						<p class="c-modal__info-value">土日祝（イベント時出勤あり・振替休日）</p>
					</div>
					<div class="c-modal__info-box">
						<p class="c-modal__info-label">職種</p>
						<p class="c-modal__info-value">企画・営業</p>
					</div>
				</div>
			</div>

			<div class="c-modal__section">
				<h3 class="c-modal__section-title">応募条件</h3>
				<ul class="c-modal__check-list">
					<li>企画・営業経験2年以上</li>
					<li>普通自動車免許</li>
					<li>PCスキル（Word, Excel, PowerPoint）</li>
					<li>コミュニケーション能力</li>
				</ul>
			</div>
			<div class="c-modal__section">
				<h3 class="c-modal__section-title">待遇・福利厚生</h3>
				<ul class="c-modal__chip-list">
					<li><span class="c-tag c-tag--success">社会保険完備</span></li>
					<li><span class="c-tag c-tag--success">交通費支給</span></li>
					<li><span class="c-tag c-tag--success">賞与年2回</span></li>
					<li><span class="c-tag c-tag--success">有給休暇</span></li>
					<li><span class="c-tag c-tag--success">研修制度あり</span></li>
				</ul>
			</div>
			<div class="c-modal__meta-line">
				<span><svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-tag"></use></svg>掲載: 2025/04/15</span>
				<span><svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-tag"></use></svg>締切: 2025/05/31</span>
			</div>
		</div>
		<div class="c-modal__actions">
			<a class="c-btn c-btn--primary" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">
				<svg class="c-btn__icon" aria-hidden="true" focusable="false"><use href="#icon-phone"></use></svg>
				電話する
			</a>
			<a class="c-btn c-btn--outline" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">
				<svg class="c-btn__icon" aria-hidden="true" focusable="false"><use href="#icon-mail"></use></svg>
				メール
			</a>
			<a class="c-btn c-btn--outline" href="#" target="_blank" rel="noopener noreferrer">
				<svg class="c-btn__icon" aria-hidden="true" focusable="false"><use href="#icon-external"></use></svg>
				企業サイトで詳細を見る
			</a>
		</div>
	</div>
</div>

<div class="c-modal" id="job-modal-2" hidden aria-hidden="true">
	<div class="c-modal__overlay" data-close></div>
	<div class="c-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="job-modal-2-title">
		<button type="button" class="c-modal__close" data-close aria-label="閉じる">
			<svg aria-hidden="true" focusable="false"><use href="#icon-close"></use></svg>
		</button>
		<div class="c-modal__head">
			<span class="c-modal__type">パート・アルバイト</span>
			<h2 class="c-modal__title" id="job-modal-2-title">カフェスタッフ募集</h2>
			<p class="c-modal__subtitle">
				<svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-store"></use></svg>
				カフェ・ド・七間
			</p>
		</div>
		<div class="c-modal__content">
			<div class="c-modal__section">
				<h3 class="c-modal__section-title">仕事内容</h3>
				<p class="c-modal__section-text">朝のモーニング営業から夜のカフェタイムまで。地域のお客様とのコミュニケーションを大切にする職場です。未経験歓迎、丁寧に教えます。</p>
			</div>
			<div class="c-modal__section">
				<div class="c-modal__info-grid">
					<div class="c-modal__info-box">
						<p class="c-modal__info-label">給与</p>
						<p class="c-modal__info-value">時給 1,100〜1,300円</p>
					</div>
					<div class="c-modal__info-box">
						<p class="c-modal__info-label">勤務地</p>
						<p class="c-modal__info-value">七間町2丁目</p>
					</div>
<div class="c-modal__info-box">
						<p class="c-modal__info-label">勤務時間</p>
						<p class="c-modal__info-value">7:00〜22:00の間でシフト制（1日4h〜OK）</p>
					</div>
					<div class="c-modal__info-box">
						<p class="c-modal__info-label">休日</p>
						<p class="c-modal__info-value">シフト制</p>
					</div>
					<div class="c-modal__info-box">
						<p class="c-modal__info-label">職種</p>
						<p class="c-modal__info-value">飲食</p>
					</div>
				</div>
			</div>

			<div class="c-modal__section">
				<h3 class="c-modal__section-title">応募条件</h3>
				<ul class="c-modal__check-list">
					<li>未経験歓迎</li>
					<li>高校生不可</li>
					<li>土日勤務できる方歓迎</li>
				</ul>
			</div>
			<div class="c-modal__section">
				<h3 class="c-modal__section-title">待遇・福利厚生</h3>
				<ul class="c-modal__chip-list">
					<li><span class="c-tag c-tag--success">交通費支給</span></li>
					<li><span class="c-tag c-tag--success">まかない付き</span></li>
					<li><span class="c-tag c-tag--success">スタッフ割引</span></li>
				</ul>
			</div>
			<div class="c-modal__meta-line">
				<span><svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-tag"></use></svg>掲載: 2025/04/10</span>
				<span><svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-tag"></use></svg>締切: 2025/05/15</span>
			</div>
		</div>
		<div class="c-modal__actions">
			<a class="c-btn c-btn--primary" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">
				<svg class="c-btn__icon" aria-hidden="true" focusable="false"><use href="#icon-phone"></use></svg>
				電話する
			</a>
			<a class="c-btn c-btn--outline" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">
				<svg class="c-btn__icon" aria-hidden="true" focusable="false"><use href="#icon-mail"></use></svg>
				メール
			</a>
			<a class="c-btn c-btn--outline" href="#" target="_blank" rel="noopener noreferrer">
				<svg class="c-btn__icon" aria-hidden="true" focusable="false"><use href="#icon-external"></use></svg>
				企業サイトで詳細を見る
			</a>
		</div>
	</div>
</div>

<div class="c-modal" id="job-modal-3" hidden aria-hidden="true">
	<div class="c-modal__overlay" data-close></div>
	<div class="c-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="job-modal-3-title">
		<button type="button" class="c-modal__close" data-close aria-label="閉じる">
			<svg aria-hidden="true" focusable="false"><use href="#icon-close"></use></svg>
		</button>
		<div class="c-modal__head">
			<span class="c-modal__type">正社員</span>
			<h2 class="c-modal__title" id="job-modal-3-title">建具職人 弟子募集</h2>
			<p class="c-modal__subtitle">
				<svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-store"></use></svg>
				七間町建具工房
			</p>
		</div>
		<div class="c-modal__content">
			<div class="c-modal__section">
				<h3 class="c-modal__section-title">仕事内容</h3>
				<p class="c-modal__section-text">40年の伝統を継承する建具職人。オーダーメイド建具製造から修理・メンテナンスまで、幅広い技術を習得できます。ものづくりが好きな方、伝統工芸に興味がある方を歓迎します。</p>
			</div>
			<div class="c-modal__section">
				<div class="c-modal__info-grid">
					<div class="c-modal__info-box">
						<p class="c-modal__info-label">給与</p>
						<p class="c-modal__info-value">月給 22〜30万円</p>
					</div>
					<div class="c-modal__info-box">
						<p class="c-modal__info-label">勤務地</p>
						<p class="c-modal__info-value">七間町1丁目</p>
					</div>
<div class="c-modal__info-box">
						<p class="c-modal__info-label">勤務時間</p>
						<p class="c-modal__info-value">8:00〜17:00（実働8時間）</p>
					</div>
					<div class="c-modal__info-box">
						<p class="c-modal__info-label">休日</p>
						<p class="c-modal__info-value">日祝・第2土曜</p>
					</div>
					<div class="c-modal__info-box">
						<p class="c-modal__info-label">職種</p>
						<p class="c-modal__info-value">職人・技術</p>
					</div>
				</div>
			</div>

			<div class="c-modal__section">
				<h3 class="c-modal__section-title">応募条件</h3>
				<ul class="c-modal__check-list">
					<li>未経験OK</li>
					<li>ものづくりが好きな方</li>
					<li>体力に自信のある方</li>
				</ul>
			</div>
			<div class="c-modal__section">
				<h3 class="c-modal__section-title">待遇・福利厚生</h3>
				<ul class="c-modal__chip-list">
					<li><span class="c-tag c-tag--success">社会保険完備</span></li>
					<li><span class="c-tag c-tag--success">交通費支給</span></li>
					<li><span class="c-tag c-tag--success">工具貸与</span></li>
					<li><span class="c-tag c-tag--success">住宅手当あり</span></li>
				</ul>
			</div>
			<div class="c-modal__meta-line">
				<span><svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-tag"></use></svg>掲載: 2025/04/01</span>
				<span><svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-tag"></use></svg>締切: 2025/06/30</span>
			</div>
		</div>
		<div class="c-modal__actions">
			<a class="c-btn c-btn--primary" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">
				<svg class="c-btn__icon" aria-hidden="true" focusable="false"><use href="#icon-phone"></use></svg>
				電話する
			</a>
			<a class="c-btn c-btn--outline" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">
				<svg class="c-btn__icon" aria-hidden="true" focusable="false"><use href="#icon-mail"></use></svg>
				メール
			</a>
			<a class="c-btn c-btn--outline" href="#" target="_blank" rel="noopener noreferrer">
				<svg class="c-btn__icon" aria-hidden="true" focusable="false"><use href="#icon-external"></use></svg>
				企業サイトで詳細を見る
			</a>
		</div>
	</div>
</div>

<div class="c-modal" id="job-modal-4" hidden aria-hidden="true">
	<div class="c-modal__overlay" data-close></div>
	<div class="c-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="job-modal-4-title">
		<button type="button" class="c-modal__close" data-close aria-label="閉じる">
			<svg aria-hidden="true" focusable="false"><use href="#icon-close"></use></svg>
		</button>
		<div class="c-modal__head">
			<span class="c-modal__type">パート・アルバイト</span>
			<h2 class="c-modal__title" id="job-modal-4-title">書店スタッフ（レジ・品出し）</h2>
			<p class="c-modal__subtitle">
				<svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-store"></use></svg>
				七間町ブックス
			</p>
		</div>
		<div class="c-modal__content">
			<div class="c-modal__section">
				<h3 class="c-modal__section-title">仕事内容</h3>
				<p class="c-modal__section-text">地域に愛される老舗書店でのスタッフ募集。レジ対応・棚卸し・品出しをお任せします。本が好きな方、地域の文化を支えたい方を歓迎します。</p>
			</div>
			<div class="c-modal__section">
				<div class="c-modal__info-grid">
					<div class="c-modal__info-box">
						<p class="c-modal__info-label">給与</p>
						<p class="c-modal__info-value">時給 1,000〜1,150円</p>
					</div>
					<div class="c-modal__info-box">
						<p class="c-modal__info-label">勤務地</p>
						<p class="c-modal__info-value">七間町3丁目</p>
					</div>
<div class="c-modal__info-box">
						<p class="c-modal__info-label">勤務時間</p>
						<p class="c-modal__info-value">10:00〜20:00の間でシフト制</p>
					</div>
					<div class="c-modal__info-box">
						<p class="c-modal__info-label">休日</p>
						<p class="c-modal__info-value">シフト制</p>
					</div>
					<div class="c-modal__info-box">
						<p class="c-modal__info-label">職種</p>
						<p class="c-modal__info-value">販売・接客</p>
					</div>
				</div>
			</div>

			<div class="c-modal__section">
				<h3 class="c-modal__section-title">応募条件</h3>
				<ul class="c-modal__check-list">
					<li>本が好きな方</li>
					<li>高校生可</li>
				</ul>
			</div>
			<div class="c-modal__section">
				<h3 class="c-modal__section-title">待遇・福利厚生</h3>
				<ul class="c-modal__chip-list">
					<li><span class="c-tag c-tag--success">交通費支給</span></li>
					<li><span class="c-tag c-tag--success">書籍購入割引</span></li>
				</ul>
			</div>
			<div class="c-modal__meta-line">
				<span><svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-tag"></use></svg>掲載: 2025/04/12</span>
				<span><svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-tag"></use></svg>締切: 2025/05/20</span>
			</div>
		</div>
		<div class="c-modal__actions">
			<a class="c-btn c-btn--primary" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">
				<svg class="c-btn__icon" aria-hidden="true" focusable="false"><use href="#icon-phone"></use></svg>
				電話する
			</a>
			<a class="c-btn c-btn--outline" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">
				<svg class="c-btn__icon" aria-hidden="true" focusable="false"><use href="#icon-mail"></use></svg>
				メール
			</a>
			<a class="c-btn c-btn--outline" href="#" target="_blank" rel="noopener noreferrer">
				<svg class="c-btn__icon" aria-hidden="true" focusable="false"><use href="#icon-external"></use></svg>
				企業サイトで詳細を見る
			</a>
		</div>
	</div>
</div>

<div class="c-modal" id="job-modal-5" hidden aria-hidden="true">
	<div class="c-modal__overlay" data-close></div>
	<div class="c-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="job-modal-5-title">
		<button type="button" class="c-modal__close" data-close aria-label="閉じる">
			<svg aria-hidden="true" focusable="false"><use href="#icon-close"></use></svg>
		</button>
		<div class="c-modal__head">
			<span class="c-modal__type">正社員・業務委託</span>
			<h2 class="c-modal__title" id="job-modal-5-title">Webデザイナー・コーダー</h2>
			<p class="c-modal__subtitle">
				<svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-store"></use></svg>
				七間町デザイン事務所
			</p>
		</div>
		<div class="c-modal__content">
			<div class="c-modal__section">
				<h3 class="c-modal__section-title">仕事内容</h3>
				<p class="c-modal__section-text">地域の中小企業・商店街のWebサイト制作・リニューアルを担当。デザインからコーディングまで一貫して携わります。リモートワーク可・フレックス制度あり。</p>
			</div>
			<div class="c-modal__section">
				<div class="c-modal__info-grid">
					<div class="c-modal__info-box">
						<p class="c-modal__info-label">給与</p>
						<p class="c-modal__info-value">月給 25〜40万円</p>
					</div>
					<div class="c-modal__info-box">
						<p class="c-modal__info-label">勤務地</p>
						<p class="c-modal__info-value">七間町2丁目（リモート可）</p>
					</div>
<div class="c-modal__info-box">
						<p class="c-modal__info-label">勤務時間</p>
						<p class="c-modal__info-value">9:00〜18:00（フレックス制）</p>
					</div>
					<div class="c-modal__info-box">
						<p class="c-modal__info-label">休日</p>
						<p class="c-modal__info-value">土日祝</p>
					</div>
					<div class="c-modal__info-box">
						<p class="c-modal__info-label">職種</p>
						<p class="c-modal__info-value">IT・クリエイティブ</p>
					</div>
				</div>
			</div>

			<div class="c-modal__section">
				<h3 class="c-modal__section-title">応募条件</h3>
				<ul class="c-modal__check-list">
					<li>Web制作実務経験2年以上</li>
					<li>Photoshop/Illustrator実務経験</li>
					<li>HTML/CSS/JS基礎</li>
				</ul>
			</div>
			<div class="c-modal__section">
				<h3 class="c-modal__section-title">待遇・福利厚生</h3>
				<ul class="c-modal__chip-list">
					<li><span class="c-tag c-tag--success">社会保険完備</span></li>
					<li><span class="c-tag c-tag--success">リモートワーク可</span></li>
					<li><span class="c-tag c-tag--success">交通費支給</span></li>
					<li><span class="c-tag c-tag--success">機材支給</span></li>
					<li><span class="c-tag c-tag--success">書籍購入補助</span></li>
				</ul>
			</div>
			<div class="c-modal__meta-line">
				<span><svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-tag"></use></svg>掲載: 2025/04/05</span>
				<span><svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-tag"></use></svg>締切: 2025/05/31</span>
			</div>
		</div>
		<div class="c-modal__actions">
			<a class="c-btn c-btn--primary" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">
				<svg class="c-btn__icon" aria-hidden="true" focusable="false"><use href="#icon-phone"></use></svg>
				電話する
			</a>
			<a class="c-btn c-btn--outline" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">
				<svg class="c-btn__icon" aria-hidden="true" focusable="false"><use href="#icon-mail"></use></svg>
				メール
			</a>
			<a class="c-btn c-btn--outline" href="#" target="_blank" rel="noopener noreferrer">
				<svg class="c-btn__icon" aria-hidden="true" focusable="false"><use href="#icon-external"></use></svg>
				企業サイトで詳細を見る
			</a>
		</div>
	</div>
</div>

<div class="c-modal" id="job-modal-6" hidden aria-hidden="true">
	<div class="c-modal__overlay" data-close></div>
	<div class="c-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="job-modal-6-title">
		<button type="button" class="c-modal__close" data-close aria-label="閉じる">
			<svg aria-hidden="true" focusable="false"><use href="#icon-close"></use></svg>
		</button>
		<div class="c-modal__head">
			<span class="c-modal__type">パート・アルバイト</span>
			<h2 class="c-modal__title" id="job-modal-6-title">介護スタッフ（訪問介護）</h2>
			<p class="c-modal__subtitle">
				<svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-store"></use></svg>
				七間町ケアサービス
			</p>
		</div>
		<div class="c-modal__content">
			<div class="c-modal__section">
				<h3 class="c-modal__section-title">仕事内容</h3>
				<p class="c-modal__section-text">七間町在住の高齢者宅への訪問介護スタッフを募集。地域に根ざした温かなケアを提供します。介護資格取得支援制度あり。</p>
			</div>
			<div class="c-modal__section">
				<div class="c-modal__info-grid">
					<div class="c-modal__info-box">
						<p class="c-modal__info-label">給与</p>
						<p class="c-modal__info-value">時給 1,200〜1,500円</p>
					</div>
					<div class="c-modal__info-box">
						<p class="c-modal__info-label">勤務地</p>
						<p class="c-modal__info-value">七間町周辺</p>
					</div>
<div class="c-modal__info-box">
						<p class="c-modal__info-label">勤務時間</p>
						<p class="c-modal__info-value">8:00〜17:00（実働8時間）</p>
					</div>
					<div class="c-modal__info-box">
						<p class="c-modal__info-label">休日</p>
						<p class="c-modal__info-value">日祝・第2土曜</p>
					</div>
					<div class="c-modal__info-box">
						<p class="c-modal__info-label">職種</p>
						<p class="c-modal__info-value">医療・福祉</p>
					</div>
				</div>
			</div>

			<div class="c-modal__section">
				<h3 class="c-modal__section-title">応募条件</h3>
				<ul class="c-modal__check-list">
					<li>経験者優遇</li>
					<li>ものづくりが好きな方</li>
				</ul>
			</div>
			<div class="c-modal__section">
				<h3 class="c-modal__section-title">待遇・福利厚生</h3>
				<ul class="c-modal__chip-list">
					<li><span class="c-tag c-tag--success">社会保険完備</span></li>
					<li><span class="c-tag c-tag--success">交通費支給</span></li>
					<li><span class="c-tag c-tag--success">退職金制度あり</span></li>
				</ul>
			</div>
			<div class="c-modal__meta-line">
				<span><svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-tag"></use></svg>掲載: 2025/03/20</span>
				<span><svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-tag"></use></svg>締切: 2025/06/15</span>
			</div>
		</div>
		<div class="c-modal__actions">
			<a class="c-btn c-btn--primary" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">
				<svg class="c-btn__icon" aria-hidden="true" focusable="false"><use href="#icon-phone"></use></svg>
				電話する
			</a>
			<a class="c-btn c-btn--outline" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">
				<svg class="c-btn__icon" aria-hidden="true" focusable="false"><use href="#icon-mail"></use></svg>
				メール
			</a>
			<a class="c-btn c-btn--outline" href="#" target="_blank" rel="noopener noreferrer">
				<svg class="c-btn__icon" aria-hidden="true" focusable="false"><use href="#icon-external"></use></svg>
				企業サイトで詳細を見る
			</a>
		</div>
	</div>
</div>

<div class="c-modal" id="job-modal-7" hidden aria-hidden="true">
	<div class="c-modal__overlay" data-close></div>
	<div class="c-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="job-modal-7-title">
		<button type="button" class="c-modal__close" data-close aria-label="閉じる">
			<svg aria-hidden="true" focusable="false"><use href="#icon-close"></use></svg>
		</button>
		<div class="c-modal__head">
			<span class="c-modal__type">正社員</span>
			<h2 class="c-modal__title" id="job-modal-7-title">和菓子職人（製造スタッフ）</h2>
			<p class="c-modal__subtitle">
				<svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-store"></use></svg>
				菓子処 七間堂
			</p>
		</div>
		<div class="c-modal__content">
			<div class="c-modal__section">
				<h3 class="c-modal__section-title">仕事内容</h3>
				<p class="c-modal__section-text">創業80年の老舗和菓子店で職人を募集。季節の上生菓子・餅菓子の製造をお任せします。未経験から職人を目指せる環境です。</p>
			</div>
			<div class="c-modal__section">
				<div class="c-modal__info-grid">
					<div class="c-modal__info-box">
						<p class="c-modal__info-label">給与</p>
						<p class="c-modal__info-value">月給 20〜28万円</p>
					</div>
					<div class="c-modal__info-box">
						<p class="c-modal__info-label">勤務地</p>
						<p class="c-modal__info-value">七間町1丁目</p>
					</div>
<div class="c-modal__info-box">
						<p class="c-modal__info-label">勤務時間</p>
						<p class="c-modal__info-value">9:00〜18:00</p>
					</div>
					<div class="c-modal__info-box">
						<p class="c-modal__info-label">休日</p>
						<p class="c-modal__info-value">土日祝</p>
					</div>
					<div class="c-modal__info-box">
						<p class="c-modal__info-label">職種</p>
						<p class="c-modal__info-value">職人・技術</p>
					</div>
				</div>
			</div>

			<div class="c-modal__section">
				<h3 class="c-modal__section-title">応募条件</h3>
				<ul class="c-modal__check-list">
					<li>未経験歓迎</li>
					<li>元気な方</li>
				</ul>
			</div>
			<div class="c-modal__section">
				<h3 class="c-modal__section-title">待遇・福利厚生</h3>
				<ul class="c-modal__chip-list">
					<li><span class="c-tag c-tag--success">社会保険完備</span></li>
					<li><span class="c-tag c-tag--success">交通費支給</span></li>
					<li><span class="c-tag c-tag--success">有給休暇</span></li>
				</ul>
			</div>
			<div class="c-modal__meta-line">
				<span><svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-tag"></use></svg>掲載: 2025/04/01</span>
				<span><svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-tag"></use></svg>締切: 2025/05/31</span>
			</div>
		</div>
		<div class="c-modal__actions">
			<a class="c-btn c-btn--primary" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">
				<svg class="c-btn__icon" aria-hidden="true" focusable="false"><use href="#icon-phone"></use></svg>
				電話する
			</a>
			<a class="c-btn c-btn--outline" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">
				<svg class="c-btn__icon" aria-hidden="true" focusable="false"><use href="#icon-mail"></use></svg>
				メール
			</a>
			<a class="c-btn c-btn--outline" href="#" target="_blank" rel="noopener noreferrer">
				<svg class="c-btn__icon" aria-hidden="true" focusable="false"><use href="#icon-external"></use></svg>
				企業サイトで詳細を見る
			</a>
		</div>
	</div>
</div>

</article>
<!-- /.p-working -->

<?php get_footer(); ?>
