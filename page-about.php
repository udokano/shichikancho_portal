<?php

/** 町の紹介ページ */
get_header();
?>

<?php get_template_part('template-parts/components/breadcrumbs'); ?>

<article class="p-about">

	<?php
	get_template_part('template-parts/components/page-hero', null, [
		'title' => '町の紹介',
		'sub'   => '静岡市中心部にある文化と日常が共存する街。',
	]);
	?>



	<!-- ─── ストーリーガイド ── -->
	<section class="p-about__story" aria-labelledby="story-guide-title">
		<div class="p-about__story-inner">
			<div class="p-about__story-grid">

				<!-- 目次（PC では sticky） -->
				<div class="p-about__story-toc-wrap">
					<div class="p-about__story-toc-card">
						<h2 class="p-about__story-toc-title" id="story-guide-title">ストーリーガイド</h2>
						<nav aria-label="ストーリーガイド目次">
							<ul class="p-about__story-toc-list" role="list">
								<li>
									<a class="p-about__story-toc-link" href="#section-01">
										<span class="p-about__story-toc-num">01</span>
										<span class="p-about__story-toc-sub">町の誕生以前</span>
									</a>
								</li>
								<li>
									<a class="p-about__story-toc-link" href="#section-02">
										<span class="p-about__story-toc-num">02</span>
										<span class="p-about__story-toc-sub">江戸時代初期</span>
									</a>
								</li>
								<li>
									<a class="p-about__story-toc-link" href="#section-03">
										<span class="p-about__story-toc-num">03</span>
										<span class="p-about__story-toc-sub">江戸中期〜</span>
									</a>
								</li>
								<li>
									<a class="p-about__story-toc-link" href="#section-04">
										<span class="p-about__story-toc-num">04</span>
										<span class="p-about__story-toc-sub">大正〜昭和</span>
									</a>
								</li>
								<li>
									<a class="p-about__story-toc-link" href="#section-05">
										<span class="p-about__story-toc-num">05</span>
										<span class="p-about__story-toc-sub">戦後</span>
									</a>
								</li>
								<li>
									<a class="p-about__story-toc-link" href="#section-06">
										<span class="p-about__story-toc-num">06</span>
										<span class="p-about__story-toc-sub">現代</span>
									</a>
								</li>
								<li>
									<a class="p-about__story-toc-link" href="#section-07">
										<span class="p-about__story-toc-num">07</span>
										<span class="p-about__story-toc-sub">駿河カルチャーライン</span>
									</a>
								</li>
							</ul>
						</nav>
					</div>
					<!-- /.p-about__story-toc-card -->
				</div>
				<!-- /.p-about__story-toc-wrap -->

				<!-- 本文 -->
				<div class="p-about__story-content">

					<section class="p-about__story-section" id="section-01" aria-labelledby="section-01-title">
						<div class="p-about__story-section-text">
							<div class="p-about__story-section-num" aria-hidden="true">01</div>
							<h3 class="p-about__story-section-title" id="section-01-title">背景</h3>
							<p class="p-about__story-section-body">徳川家康の時代、駿府は単なる隠居の地ではなく、政治・文化の中心として機能していました。駿府城を中心に、町人文化が花開き、その中心地として七間町は、商いと文化の交差点として誕生しました。今、その七間町は、駿府城再建（2026年以降予定）を起点とした「駿河カルチャーライン構想」の中心地として、新たな役割を担おうとしています。</p>
						</div>
						<!-- /.p-about__story-section-text -->
						<div class="p-about__story-section-img" aria-hidden="true">
							<img src="<?php echo esc_url(SC_TPL_URI . '/assets/images/top/hero-tourism.jpg'); ?>" alt="" aria-hidden="true" loading="lazy" width="600" height="400">
						</div>
						<!-- /.p-about__story-section-img -->
					</section>
					<!-- /.p-about__story-section -->

					<section class="p-about__story-section" id="section-02" aria-labelledby="section-02-title">
						<div class="p-about__story-section-text">
							<div class="p-about__story-section-num" aria-hidden="true">02</div>
							<h3 class="p-about__story-section-title" id="section-02-title">起源</h3>
							<p class="p-about__story-section-body">「七間町」という名は、江戸時代の町割りに由来します。七間（約十二・七メートル）という間口を持つ商家が軒を連ね、その規模と賑わいがそのまま地名になりました。商人たちは通りに面した店を構え、駿府城下の人々、旅人、武士たちを相手に商売を営みました。ここには単なる金の貸し借りではなく、人と人との信頼と、時代をつなぐ記憶が積み重ねられていきました。</p>
						</div>
						<!-- /.p-about__story-section-text -->
						<div class="p-about__story-section-img" aria-hidden="true">
							<img src="<?php echo esc_url(SC_TPL_URI . '/assets/images/top/hero-main.jpg'); ?>" alt="" aria-hidden="true" loading="lazy" width="600" height="400">
						</div>
						<!-- /.p-about__story-section-img -->
					</section>
					<!-- /.p-about__story-section -->

					<section class="p-about__story-section" id="section-03" aria-labelledby="section-03-title">
						<div class="p-about__story-section-text">
							<div class="p-about__story-section-num" aria-hidden="true">03</div>
							<h3 class="p-about__story-section-title" id="section-03-title">発展</h3>
							<p class="p-about__story-section-body">江戸中期から明治にかけて、七間町は東海道に近い立地を活かし、人と文化と情報が集まる交差点として発展していきます。旅人、商人、職人、芸人、知識人…七間町の商人たちは、旅籠屋も兼ねる店を構え、町は常に新しい刺激にさらされていました。七間町は、駿府城再建を起点とした「文化を受け入れる余白」があったのです。</p>
						</div>
						<!-- /.p-about__story-section-text -->
						<div class="p-about__story-section-img" aria-hidden="true">
							<img src="<?php echo esc_url(SC_TPL_URI . '/assets/images/top/hero-shops.jpg'); ?>" alt="" aria-hidden="true" loading="lazy" width="600" height="400">
						</div>
						<!-- /.p-about__story-section-img -->
					</section>
					<!-- /.p-about__story-section -->

					<section class="p-about__story-section" id="section-04" aria-labelledby="section-04-title">
						<div class="p-about__story-section-text">
							<div class="p-about__story-section-num" aria-hidden="true">04</div>
							<h3 class="p-about__story-section-title" id="section-04-title">黄金期</h3>
							<p class="p-about__story-section-body">大正から昭和初期にかけて、七間町は静岡随一の繁華街として名を馳せます。芝居小屋、映画館、飲食店、服飾店が軒を連ね、昼も夜も人の流れが絶えることはありませんでした。人々は芝居を観に来て、映画を観に来て、そして買い物をし、喫茶店に、洋食屋に立ち寄り、15分があっという間に感じられました。</p>
						</div>
						<!-- /.p-about__story-section-text -->
						<div class="p-about__story-section-img" aria-hidden="true">
							<img src="<?php echo esc_url(SC_TPL_URI . '/assets/images/top/hero-cinema.jpg'); ?>" alt="" aria-hidden="true" loading="lazy" width="600" height="400">
						</div>
						<!-- /.p-about__story-section-img -->
					</section>
					<!-- /.p-about__story-section -->

					<section class="p-about__story-section" id="section-05" aria-labelledby="section-05-title">
						<div class="p-about__story-section-text">
							<div class="p-about__story-section-num" aria-hidden="true">05</div>
							<h3 class="p-about__story-section-title" id="section-05-title">転換</h3>
							<p class="p-about__story-section-body">戦争から高度経済成長期にかけて、七間町には変化の波が押し寄せました。映画館が次々と閉館し、人の流れは郊外や駅前に移り、商店街は静かになっていきました。七間町には、長く続いた店が静かに幕を下ろす一方で、新しい店が生まれ、町の顔ぶれが少しずつ変わっていきました。</p>
						</div>
						<!-- /.p-about__story-section-text -->
						<div class="p-about__story-section-img" aria-hidden="true">
							<img src="<?php echo esc_url(SC_TPL_URI . '/assets/images/top/hero-tourism.jpg'); ?>" alt="" aria-hidden="true" loading="lazy" width="600" height="400">
						</div>
						<!-- /.p-about__story-section-img -->
					</section>
					<!-- /.p-about__story-section -->

					<section class="p-about__story-section" id="section-06" aria-labelledby="section-06-title">
						<div class="p-about__story-section-text">
							<div class="p-about__story-section-num" aria-hidden="true">06</div>
							<h3 class="p-about__story-section-title" id="section-06-title">再生</h3>
							<p class="p-about__story-section-body">現在の七間町では、古い建物をリノベーションし、新しい価値を生み出す動きが活発化しています。カフェ、ギャラリー、コワーキングスペースなど、事業者が続々とまちを盛り上げ、通りに賑わいの兆しが戻り、ここで大切なのは、「再び賑わう場所」ではなく、「待ち続ける場所」へと変わろうとしています。</p>
						</div>
						<!-- /.p-about__story-section-text -->
						<div class="p-about__story-section-img" aria-hidden="true">
							<img src="<?php echo esc_url(SC_TPL_URI . '/assets/images/top/hero-shops.jpg'); ?>" alt="" aria-hidden="true" loading="lazy" width="600" height="400">
						</div>
						<!-- /.p-about__story-section-img -->
					</section>
					<!-- /.p-about__story-section -->

					<section class="p-about__story-section" id="section-07" aria-labelledby="section-07-title">
						<div class="p-about__story-section-text">
							<div class="p-about__story-section-num" aria-hidden="true">07</div>
							<h3 class="p-about__story-section-title" id="section-07-title">構想</h3>
							<p class="p-about__story-section-body">駿府城の再建と整備が進めば、国内外から多くの人が静岡を訪れるようになります。その人の流れを七間町へ、そして周辺エリアへと広げていくことで、七間町は静岡県、静岡市、商店街エリアを結ぶ「文化の交差点」として、人が立ち寄り、回遊し、また来たいと思える場所になっていきます。</p>
						</div>
						<!-- /.p-about__story-section-text -->
						<div class="p-about__story-section-img" aria-hidden="true">
							<img src="<?php echo esc_url(SC_TPL_URI . '/assets/images/top/hero-tourism.jpg'); ?>" alt="" aria-hidden="true" loading="lazy" width="600" height="400">
						</div>
						<!-- /.p-about__story-section-img -->
					</section>
					<!-- /.p-about__story-section -->

				</div>
				<!-- /.p-about__story-content -->

			</div>
			<!-- /.p-about__story-grid -->
		</div>
		<!-- /.p-about__story-inner -->
	</section>
	<!-- /.p-about__story -->

	<!-- ─── 回遊ネットワーク ── -->
	<section class="p-about__network" aria-labelledby="about-network-title">
		<div class="p-about__network-inner">
			<h2 class="p-about__network-title" id="about-network-title">七間町を起点に、町を回遊する</h2>
			<p class="p-about__network-lead">駿府城を中心に、周辺の町が文化の線でつながる。それぞれの町の特徴を生かした回遊体験を。</p>

			<!-- ネットワーク図 -->
			<div class="p-about__network-map" role="img" aria-label="七間町周辺エリアネットワーク図">
				<svg class="p-about__network-svg" viewBox="0 0 700 520" xmlns="http://www.w3.org/2000/svg" aria-label="七間町エリアネットワーク図" role="img">
					<!-- 破線接続（駿府城→各エリア） -->
					<line x1="350" y1="145" x2="200" y2="270" stroke="#e5a0b0" stroke-width="1.5" stroke-dasharray="6,4" />
					<line x1="350" y1="145" x2="578" y2="270" stroke="#e5a0b0" stroke-width="1.5" stroke-dasharray="6,4" />
					<line x1="350" y1="145" x2="350" y2="290" stroke="#e5a0b0" stroke-width="1.5" stroke-dasharray="6,4" />
					<!-- 七間町→各エリア -->
					<line x1="350" y1="335" x2="258" y2="365" stroke="#e5a0b0" stroke-width="1.5" stroke-dasharray="6,4" />
					<line x1="350" y1="335" x2="490" y2="365" stroke="#e5a0b0" stroke-width="1.5" stroke-dasharray="6,4" />
					<line x1="350" y1="355" x2="175" y2="445" stroke="#e5a0b0" stroke-width="1.5" stroke-dasharray="6,4" />
					<line x1="350" y1="355" x2="315" y2="445" stroke="#e5a0b0" stroke-width="1.5" stroke-dasharray="6,4" />
					<line x1="350" y1="355" x2="455" y2="445" stroke="#e5a0b0" stroke-width="1.5" stroke-dasharray="6,4" />
					<line x1="350" y1="355" x2="578" y2="445" stroke="#e5a0b0" stroke-width="1.5" stroke-dasharray="6,4" />

					<!-- 駿府城（中央上・大） -->
					<g class="js-network-node" data-name="駿府城" data-desc="駿河カルチャーライン構想の中心。2026年以降の再建整備により、静岡観光の核となる予定。" data-tag="再建構想の中心" tabindex="0" role="button" aria-label="駿府城">
						<circle cx="350" cy="100" r="52" fill="#f472b6" opacity="0.2" />
						<circle cx="350" cy="100" r="38" fill="#ec4899" />
						<text x="350" y="105" text-anchor="middle" fill="#fff" font-size="13" font-weight="700" font-family="serif">駿府城</text>
						<text x="350" y="162" text-anchor="middle" fill="#9ca3af" font-size="10">再建構想の中心</text>
					</g>

					<!-- 七間町（中央・中） -->
					<g class="js-network-node" data-name="七間町" data-desc="文化と商いが交差する七間町。映画・食・伝統工芸が共存する静岡の文化的中心地。" data-tag="文化の交差点" tabindex="0" role="button" aria-label="七間町">
						<circle cx="350" cy="100" r="0" fill="none" />
						<circle cx="350" cy="315" r="44" fill="#f472b6" opacity="0.2" />
						<circle cx="350" cy="315" r="32" fill="#f43f5e" />
						<text x="350" y="320" text-anchor="middle" fill="#fff" font-size="13" font-weight="700" font-family="serif">七間町</text>
						<text x="350" y="374" text-anchor="middle" fill="#9ca3af" font-size="10">文化の交差点</text>
					</g>

					<!-- 浅間通り（左中） -->
					<g class="js-network-node" data-name="浅間通り" data-desc="静岡浅間神社の参道沿いに広がるエリア。神社参拝と老舗が共存する歴史的な通り。" data-tag="神社参拝" tabindex="0" role="button" aria-label="浅間通り">
						<circle cx="200" cy="278" r="28" fill="#fb923c" />
						<text x="200" y="283" text-anchor="middle" fill="#fff" font-size="11" font-weight="700">浅間通り</text>
						<text x="200" y="316" text-anchor="middle" fill="#9ca3af" font-size="10">神社参拝</text>
					</g>

					<!-- 常磐町（右中） -->
					<g class="js-network-node" data-name="常磐町" data-desc="静岡市内でも個性的な店が集まるディープエリア。地元民に愛される隠れた名店が点在。" data-tag="ディープ" tabindex="0" role="button" aria-label="常磐町">
						<circle cx="578" cy="278" r="28" fill="#2dd4bf" />
						<text x="578" y="283" text-anchor="middle" fill="#fff" font-size="11" font-weight="700">常磐町</text>
						<text x="578" y="316" text-anchor="middle" fill="#9ca3af" font-size="10">ディープ</text>
					</g>

					<!-- 呉服町（左下中） -->
					<g class="js-network-node" data-name="呉服町" data-desc="かつて呉服問屋が並んでいた通り。現在も老舗と新しい店が混在する賑やかなエリア。" data-tag="老舗巡り" tabindex="0" role="button" aria-label="呉服町">
						<circle cx="258" cy="375" r="28" fill="#a78bfa" />
						<text x="258" y="380" text-anchor="middle" fill="#fff" font-size="11" font-weight="700">呉服町</text>
						<text x="258" y="413" text-anchor="middle" fill="#9ca3af" font-size="10">老舗巡り</text>
					</g>

					<!-- 青葉通り（右下中） -->
					<g class="js-network-node" data-name="青葉通り" data-desc="緑豊かなケヤキ並木が続く静岡のメインストリート。おしゃれなカフェや雑貨店が並ぶ。" data-tag="ストリート" tabindex="0" role="button" aria-label="青葉通り">
						<circle cx="490" cy="375" r="28" fill="#4ade80" />
						<text x="490" y="380" text-anchor="middle" fill="#fff" font-size="11" font-weight="700">青葉通り</text>
						<text x="490" y="413" text-anchor="middle" fill="#9ca3af" font-size="10">ストリート</text>
					</g>

					<!-- 人宿町（左下） -->
					<g class="js-network-node" data-name="人宿町" data-desc="江戸時代に旅人宿が集まった町。現在はアートギャラリーや個性的な店舗が立ち並ぶ。" data-tag="" tabindex="0" role="button" aria-label="人宿町">
						<circle cx="175" cy="455" r="22" fill="#f9a8d4" />
						<text x="175" y="460" text-anchor="middle" fill="#fff" font-size="10" font-weight="700">人宿町</text>
					</g>

					<!-- 紺屋町（中下左） -->
					<g class="js-network-node" data-name="紺屋町" data-desc="藍染め職人が多く住んでいた歴史ある町。現在は若手クリエイターが集まるエリアとして注目。" data-tag="" tabindex="0" role="button" aria-label="紺屋町">
						<circle cx="315" cy="455" r="22" fill="#f472b6" />
						<text x="315" y="460" text-anchor="middle" fill="#fff" font-size="10" font-weight="700">紺屋町</text>
					</g>

					<!-- 両替町（中下右） -->
					<g class="js-network-node" data-name="両替町" data-desc="かつて両替商が集まった金融の中心地。現在は文化施設や飲食店が集まるエリア。" data-tag="" tabindex="0" role="button" aria-label="両替町">
						<circle cx="455" cy="455" r="22" fill="#86efac" />
						<text x="455" y="460" text-anchor="middle" fill="#fff" font-size="10" font-weight="700">両替町</text>
					</g>

					<!-- 鷹匠町（右下） -->
					<g class="js-network-node" data-name="鷹匠町" data-desc="鷹匠が住んでいた歴史的な町。伝統工芸の工房や職人の店が残るディープなエリア。" data-tag="" tabindex="0" role="button" aria-label="鷹匠町">
						<circle cx="578" cy="455" r="22" fill="#fdba74" />
						<text x="578" y="460" text-anchor="middle" fill="#fff" font-size="10" font-weight="700">鷹匠町</text>
					</g>
				</svg>

				<!-- クリック詳細パネル -->
				<div class="p-about__network-detail js-network-detail" hidden>
					<button class="p-about__network-detail-close js-network-close" type="button" aria-label="閉じる">
						<svg aria-hidden="true" focusable="false" width="16" height="16">
							<use href="#icon-x-mark"></use>
						</svg>
					</button>
					<p class="p-about__network-detail-tag js-network-detail-tag"></p>
					<h3 class="p-about__network-detail-name js-network-detail-name"></h3>
					<p class="p-about__network-detail-desc js-network-detail-desc"></p>
				</div>
				<!-- /.p-about__network-detail -->

				<p class="p-about__network-hint" aria-hidden="true">▲ エリアをクリックすると詳細を表示</p>
			</div>
			<!-- /.p-about__network-map -->

			<!-- コースカード -->
			<div class="p-about__network-courses">
				<div class="p-about__network-course">
					<div class="p-about__network-course-head">
						<span class="p-about__network-course-num">1</span>
						<h3 class="p-about__network-course-title">文化満喫コース</h3>
					</div>
					<!-- /.p-about__network-course-head -->
					<p class="p-about__network-course-route">駿府城 → 呉服町 → 七間町 → 青葉通り</p>
					<div class="p-about__network-course-tags">
						<span class="p-about__network-course-tag">歴史</span>
						<span class="p-about__network-course-tag">老舗巡り</span>
						<span class="p-about__network-course-tag">カフェ</span>
						<span class="p-about__network-course-tag">映画館</span>
					</div>
					<!-- /.p-about__network-course-tags -->
				</div>
				<!-- /.p-about__network-course -->
				<div class="p-about__network-course">
					<div class="p-about__network-course-head">
						<span class="p-about__network-course-num">2</span>
						<h3 class="p-about__network-course-title">職人体験コース</h3>
					</div>
					<!-- /.p-about__network-course-head -->
					<p class="p-about__network-course-route">駿府城 → 鷹匠町 → 両替町 → 紺屋町</p>
					<div class="p-about__network-course-tags">
						<span class="p-about__network-course-tag">伝統工芸</span>
						<span class="p-about__network-course-tag">文学</span>
						<span class="p-about__network-course-tag">工房見学</span>
					</div>
					<!-- /.p-about__network-course-tags -->
				</div>
				<!-- /.p-about__network-course -->
				<div class="p-about__network-course">
					<div class="p-about__network-course-head">
						<span class="p-about__network-course-num">3</span>
						<h3 class="p-about__network-course-title">ショートコース</h3>
					</div>
					<!-- /.p-about__network-course-head -->
					<p class="p-about__network-course-route">駿府城 → 七間町 → 常磐町</p>
					<div class="p-about__network-course-tags">
						<span class="p-about__network-course-tag">散歩</span>
						<span class="p-about__network-course-tag">グルメ</span>
						<span class="p-about__network-course-tag">ディープ</span>
					</div>
					<!-- /.p-about__network-course-tags -->
				</div>
				<!-- /.p-about__network-course -->
			</div>
			<!-- /.p-about__network-courses -->

			<div class="p-about__network-cta">
				<a class="p-about__network-cta-btn" href="<?php echo esc_url(home_url('/culture-line/')); ?>">
					駿河カルチャーライン構想を見る
					<svg aria-hidden="true" focusable="false" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<use href="#icon-chevron-right"></use>
					</svg>
				</a>
			</div>
			<!-- /.p-about__network-cta -->
		</div>
		<!-- /.p-about__network-inner -->
	</section>
	<!-- /.p-about__network -->

	<!-- ─── CTAカード ── -->
	<?php
	$about_cta_items = [
		[
			'url'   => home_url( '/tourism/' ),
			'thumb' => SC_TPL_URI . '/assets/images/top/hero-tourism.jpg',
			'title' => 'カフェ・スポット',
			'text'  => '七間町のカフェや観光スポットを探す。お気に入りの一軒をみつけよう。',
		],
		[
			'url'   => home_url( '/events/' ),
			'thumb' => SC_TPL_URI . '/assets/images/top/hero-shops.jpg',
			'title' => 'イベント情報',
			'text'  => '近日開催のイベントをチェック。季節の催しや商店街のお祭り情報。',
		],
		[
			'url'   => home_url( '/culture-line/' ),
			'thumb' => SC_TPL_URI . '/assets/images/top/hero-main.jpg',
			'title' => '駿河カルチャーライン',
			'text'  => '七間町を起点に町を回遊する構想。文化と暮らしをつなぐ取り組み。',
		],
	];
	?>
	<section class="p-about__cta" aria-label="関連リンク">
		<div class="p-about__cta-inner">
			<div class="p-about__cta-grid">
				<?php foreach ( $about_cta_items as $item ) : ?>
				<a class="p-about__cta-card" href="<?php echo esc_url( $item['url'] ); ?>">
					<div class="p-about__cta-card-thumb">
						<img class="u-img-cover" src="<?php echo esc_url( $item['thumb'] ); ?>" alt="" aria-hidden="true" loading="lazy" width="600" height="400">
					</div>
					<!-- /.p-about__cta-card-thumb -->
					<div class="p-about__cta-card-body">
						<h3 class="p-about__cta-card-title"><?php echo esc_html( $item['title'] ); ?></h3>
						<p class="p-about__cta-card-text"><?php echo esc_html( $item['text'] ); ?></p>
						<span class="p-about__cta-card-link">
							詳しく見る
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="9 18 15 12 9 6"/></svg>
						</span>
					</div>
					<!-- /.p-about__cta-card-body -->
				</a>
				<!-- /.p-about__cta-card -->
				<?php endforeach; ?>
			</div>
			<!-- /.p-about__cta-grid -->
		</div>
		<!-- /.p-about__cta-inner -->
	</section>
	<!-- /.p-about__cta -->

</article>
<!-- /.p-about -->

<?php get_footer(); ?>