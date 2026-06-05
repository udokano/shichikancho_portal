<?php
/** 駿河カルチャーライン構想ページ */
get_header();
?>

<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>

<article class="p-cl">

	<!-- ─── ヒーロー ── -->
	<section class="p-cl__hero" aria-label="駿河カルチャーライン構想">
		<picture class="p-cl__hero-bg" aria-hidden="true">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/top/hero-main.jpg' ); ?>" alt="" width="1440" height="800" loading="eager">
		</picture>
		<!-- /.p-cl__hero-bg -->
		<div class="p-cl__hero-inner">
			<p class="p-cl__hero-eyebrow">SURUGA CULTURE LINE</p>
			<h1 class="p-cl__hero-title">駿河カルチャーライン構想</h1>
			<p class="p-cl__hero-lead">駿府城を中心に、周辺の町が文化の線でつながる。それぞれの町の個性を活かし、訪れる人が自然と回遊したくなる 持続可能な観光ネットワークを構築する。</p>
		</div>
		<!-- /.p-cl__hero-inner -->
	</section>
	<!-- /.p-cl__hero -->

	<!-- ─── 構想のビジョン ── -->
	<section class="p-cl__vision" aria-label="構想のビジョン">
		<div class="p-cl__vision-inner">
			<div class="p-cl__vision-head">
				<h2 class="p-cl__vision-title">構想のビジョン</h2>
				<p class="p-cl__vision-lead">もし駿府城が再建されたら、静岡の街はどう変わるか。その未来に向けて、今から地域の連携と回遊の仕組みを整える。</p>
			</div>
			<!-- /.p-cl__vision-head -->
			<div class="p-cl__vision-cards">

				<div class="p-cl__vision-card">
					<div class="p-cl__vision-card-icon">
						<svg aria-hidden="true" focusable="false"><use href="#icon-building"></use></svg>
					</div>
					<!-- /.p-cl__vision-card-icon -->
					<h3 class="p-cl__vision-card-title">駿府城再建</h3>
					<p class="p-cl__vision-card-body">再建への機運を高め、静岡の象徴を取り戻す</p>
				</div>
				<!-- /.p-cl__vision-card -->

				<div class="p-cl__vision-card">
					<div class="p-cl__vision-card-icon">
						<svg aria-hidden="true" focusable="false"><use href="#icon-users-solid"></use></svg>
					</div>
					<!-- /.p-cl__vision-card-icon -->
					<h3 class="p-cl__vision-card-title">地域連携</h3>
					<p class="p-cl__vision-card-body">各エリアが独立せず、相互に連携し合う観光基盤</p>
				</div>
				<!-- /.p-cl__vision-card -->

				<div class="p-cl__vision-card">
					<div class="p-cl__vision-card-icon">
						<svg aria-hidden="true" focusable="false"><use href="#icon-map-pin-solid"></use></svg>
					</div>
					<!-- /.p-cl__vision-card-icon -->
					<h3 class="p-cl__vision-card-title">回遊促進</h3>
					<p class="p-cl__vision-card-body">訪問者の滞在時間を延長し、地域全体の魅力を最大化</p>
				</div>
				<!-- /.p-cl__vision-card -->

			</div>
			<!-- /.p-cl__vision-cards -->
		</div>
		<!-- /.p-cl__vision-inner -->
	</section>
	<!-- /.p-cl__vision -->

	<!-- ─── エリアネットワーク ── -->
	<section class="p-cl__network" aria-label="エリアネットワーク">
		<div class="p-cl__network-inner">
			<div class="p-cl__network-head">
				<h2 class="p-cl__network-title">エリアネットワーク</h2>
				<p class="p-cl__network-lead">駿府城を中心に、各エリアが文化の線でつながる</p>
			</div>
			<!-- /.p-cl__network-head -->

			<!-- バブルマップ -->
			<div class="p-cl__network-map">
				<svg class="p-cl__network-svg" viewBox="0 0 700 380" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
					<!-- 接続線 -->
					<line x1="350" y1="80" x2="180" y2="160" stroke="#e5a0b0" stroke-width="2" stroke-dasharray="6,4"/>
					<line x1="350" y1="80" x2="350" y2="160" stroke="#e5a0b0" stroke-width="2" stroke-dasharray="6,4"/>
					<line x1="350" y1="80" x2="520" y2="160" stroke="#e5a0b0" stroke-width="2" stroke-dasharray="6,4"/>
					<line x1="350" y1="80" x2="100" y2="240" stroke="#e5a0b0" stroke-width="2" stroke-dasharray="6,4"/>
					<line x1="350" y1="80" x2="230" y2="280" stroke="#e5a0b0" stroke-width="2" stroke-dasharray="6,4"/>
					<line x1="350" y1="80" x2="350" y2="300" stroke="#e5a0b0" stroke-width="2" stroke-dasharray="6,4"/>
					<line x1="350" y1="80" x2="470" y2="280" stroke="#e5a0b0" stroke-width="2" stroke-dasharray="6,4"/>
					<line x1="350" y1="80" x2="600" y2="240" stroke="#e5a0b0" stroke-width="2" stroke-dasharray="6,4"/>
					<line x1="350" y1="80" x2="130" y2="320" stroke="#e5a0b0" stroke-width="2" stroke-dasharray="6,4"/>
					<line x1="350" y1="80" x2="570" y2="320" stroke="#e5a0b0" stroke-width="2" stroke-dasharray="6,4"/>

					<!-- 駿府城（中心） -->
					<g class="js-cl-node" data-name="駿府城公園" data-desc="再建構想の中心地。徳川家康ゆかりの史跡。" data-color="#e8546a" role="button" tabindex="0" aria-label="駿府城公園">
						<circle cx="350" cy="80" r="42" fill="#e8546a" opacity="0.15"/>
						<circle cx="350" cy="80" r="30" fill="#e8546a"/>
						<text x="350" y="84" text-anchor="middle" fill="#fff" font-size="11" font-weight="700">駿府城</text>
					</g>

					<!-- 七間町 -->
					<g class="js-cl-node" data-name="七間町" data-desc="文化の交差点。映画館・老舗・食が交わる町。" data-color="#e06c9a" role="button" tabindex="0" aria-label="七間町">
						<circle cx="350" cy="190" r="28" fill="#e06c9a"/>
						<text x="350" y="194" text-anchor="middle" fill="#fff" font-size="11" font-weight="700">七間町</text>
					</g>

					<!-- 呉服町 -->
					<g class="js-cl-node" data-name="呉服町" data-desc="老舗が軒を連ねる静岡の中心商業地。" data-color="#9b59b6" role="button" tabindex="0" aria-label="呉服町">
						<circle cx="180" cy="175" r="24" fill="#9b59b6"/>
						<text x="180" y="179" text-anchor="middle" fill="#fff" font-size="10" font-weight="700">呉服町</text>
					</g>

					<!-- 青葉通り -->
					<g class="js-cl-node" data-name="青葉通り" data-desc="街路樹が美しいストリート。カフェや雑貨が並ぶ。" data-color="#4fa8d4" role="button" tabindex="0" aria-label="青葉通り">
						<circle cx="520" cy="175" r="24" fill="#4fa8d4"/>
						<text x="520" y="179" text-anchor="middle" fill="#fff" font-size="10" font-weight="700">青葉通り</text>
					</g>

					<!-- 人宿町 -->
					<g class="js-cl-node" data-name="人宿町" data-desc="クリエイターが集まるアート系の町。" data-color="#3b82b0" role="button" tabindex="0" aria-label="人宿町">
						<circle cx="100" cy="255" r="22" fill="#3b82b0"/>
						<text x="100" y="259" text-anchor="middle" fill="#fff" font-size="10" font-weight="700">人宿町</text>
					</g>

					<!-- 紺屋町 -->
					<g class="js-cl-node" data-name="紺屋町" data-desc="染め物の歴史が残る職人の町。" data-color="#e07040" role="button" tabindex="0" aria-label="紺屋町">
						<circle cx="230" cy="290" r="22" fill="#e07040"/>
						<text x="230" y="294" text-anchor="middle" fill="#fff" font-size="10" font-weight="700">紺屋町</text>
					</g>

					<!-- 両替町 -->
					<g class="js-cl-node" data-name="両替町" data-desc="商業の歴史を持つ活気あるエリア。" data-color="#e06c9a" role="button" tabindex="0" aria-label="両替町">
						<circle cx="470" cy="290" r="22" fill="#e06c9a"/>
						<text x="470" y="294" text-anchor="middle" fill="#fff" font-size="10" font-weight="700">両替町</text>
					</g>

					<!-- 鷹匠町 -->
					<g class="js-cl-node" data-name="鷹匠町" data-desc="静かで落ち着いた住宅と文化施設が混在。" data-color="#c4a44e" role="button" tabindex="0" aria-label="鷹匠町">
						<circle cx="600" cy="255" r="22" fill="#c4a44e"/>
						<text x="600" y="259" text-anchor="middle" fill="#fff" font-size="10" font-weight="700">鷹匠町</text>
					</g>

					<!-- 浅間通り -->
					<g class="js-cl-node" data-name="浅間通り" data-desc="浅間神社への参道。地元の生活感あふれる通り。" data-color="#e07878" role="button" tabindex="0" aria-label="浅間通り">
						<circle cx="130" cy="335" r="22" fill="#e07878"/>
						<text x="130" y="339" text-anchor="middle" fill="#fff" font-size="10" font-weight="700">浅間通り</text>
					</g>

					<!-- 常磐町 -->
					<g class="js-cl-node" data-name="常磐町" data-desc="緑と文化施設が点在する閑静なエリア。" data-color="#48b06a" role="button" tabindex="0" aria-label="常磐町">
						<circle cx="570" cy="335" r="22" fill="#48b06a"/>
						<text x="570" y="339" text-anchor="middle" fill="#fff" font-size="10" font-weight="700">常磐町</text>
					</g>
				</svg>
				<!-- /.p-cl__network-svg -->

				<!-- ホバー詳細パネル -->
				<div class="p-cl__network-detail" hidden>
					<button class="p-cl__network-detail-close js-cl-close" type="button" aria-label="閉じる">
						<svg width="16" height="16" aria-hidden="true" focusable="false"><use href="#icon-close"></use></svg>
					</button>
					<p class="p-cl__network-detail-name js-cl-name"></p>
					<p class="p-cl__network-detail-desc js-cl-desc"></p>
				</div>
				<!-- /.p-cl__network-detail -->

				<p class="p-cl__network-hint">各エリアにカーソルを合わせると詳細を表示</p>
			</div>
			<!-- /.p-cl__network-map -->

			<!-- エリアカード一覧 -->
			<div class="p-cl__network-areas">

				<?php
				$areas = [
					[ 'name' => '駿府城公園', 'color' => '#e8546a' ],
					[ 'name' => '呉服町',     'color' => '#9b59b6' ],
					[ 'name' => '七間町',     'color' => '#e06c9a' ],
					[ 'name' => '青葉通り',   'color' => '#4fa8d4' ],
					[ 'name' => '人宿町',     'color' => '#3b82b0' ],
					[ 'name' => '紺屋町',     'color' => '#e07040' ],
					[ 'name' => '両替町',     'color' => '#e06c9a' ],
					[ 'name' => '鷹匠町',     'color' => '#c4a44e' ],
					[ 'name' => '浅間通り',   'color' => '#e07878' ],
					[ 'name' => '常磐町',     'color' => '#48b06a' ],
				];
				foreach ( $areas as $area ) :
				?>
				<div class="p-cl__network-area">
					<span class="p-cl__network-area-dot" style="background-color: <?php echo esc_attr( $area['color'] ); ?>"></span>
					<span class="p-cl__network-area-name"><?php echo esc_html( $area['name'] ); ?></span>
				</div>
				<!-- /.p-cl__network-area -->
				<?php endforeach; ?>

			</div>
			<!-- /.p-cl__network-areas -->
		</div>
		<!-- /.p-cl__network-inner -->
	</section>
	<!-- /.p-cl__network -->

	<!-- ─── モデルコース ── -->
	<section class="p-cl__courses" aria-label="モデルコース">
		<div class="p-cl__courses-inner">
			<div class="p-cl__courses-head">
				<h2 class="p-cl__courses-title">モデルコース</h2>
				<p class="p-cl__courses-lead">気分やテーマに合わせて選べる、物語性のある回遊ルート</p>
			</div>
			<!-- /.p-cl__courses-head -->

			<!-- タブ -->
			<div class="p-cl__courses-tabs" role="tablist" aria-label="モデルコース選択">
				<button class="p-cl__courses-tab is-active" role="tab" aria-selected="true" aria-controls="cl-course-0" id="cl-tab-0" type="button">文化満喫コース</button>
				<button class="p-cl__courses-tab" role="tab" aria-selected="false" aria-controls="cl-course-1" id="cl-tab-1" type="button">職人体験コース</button>
				<button class="p-cl__courses-tab" role="tab" aria-selected="false" aria-controls="cl-course-2" id="cl-tab-2" type="button">ショートコース</button>
				<button class="p-cl__courses-tab" role="tab" aria-selected="false" aria-controls="cl-course-3" id="cl-tab-3" type="button">ディープ探訪コース</button>
			</div>
			<!-- /.p-cl__courses-tabs -->

			<!-- コース詳細パネル -->
			<div class="p-cl__courses-panel is-active" id="cl-course-0" role="tabpanel" aria-labelledby="cl-tab-0">
				<div class="p-cl__courses-panel-meta">
					<h3 class="p-cl__courses-panel-title">文化満喫コース</h3>
					<p class="p-cl__courses-panel-desc">歴史から現代文化まで、静岡の文化を一日で堪能するコース。</p>
					<span class="p-cl__courses-panel-time">
						<svg width="14" height="14" aria-hidden="true" focusable="false"><use href="#icon-clock"></use></svg>
						4–5時間
					</span>
				</div>
				<!-- /.p-cl__courses-panel-meta -->
				<div class="p-cl__courses-route">
					<div class="p-cl__courses-route-step">
						<span class="p-cl__courses-route-num">1</span>
						<span class="p-cl__courses-route-name">駿府城公園</span>
					</div>
					<span class="p-cl__courses-route-arrow" aria-hidden="true">→</span>
					<div class="p-cl__courses-route-step">
						<span class="p-cl__courses-route-num">2</span>
						<span class="p-cl__courses-route-name">呉服町</span>
					</div>
					<span class="p-cl__courses-route-arrow" aria-hidden="true">→</span>
					<div class="p-cl__courses-route-step">
						<span class="p-cl__courses-route-num">3</span>
						<span class="p-cl__courses-route-name">七間町</span>
					</div>
					<span class="p-cl__courses-route-arrow" aria-hidden="true">→</span>
					<div class="p-cl__courses-route-step">
						<span class="p-cl__courses-route-num">4</span>
						<span class="p-cl__courses-route-name">青葉通り</span>
					</div>
				</div>
				<!-- /.p-cl__courses-route -->
				<div class="p-cl__courses-experiences">
					<p class="p-cl__courses-exp-label">体験内容</p>
					<div class="p-cl__courses-exp-tags">
						<span class="p-cl__courses-exp-tag">歴史散策</span>
						<span class="p-cl__courses-exp-tag">老舗巡り</span>
						<span class="p-cl__courses-exp-tag">カフェ休憩</span>
						<span class="p-cl__courses-exp-tag">映画館</span>
						<span class="p-cl__courses-exp-tag">おでん横丁</span>
					</div>
					<!-- /.p-cl__courses-exp-tags -->
				</div>
				<!-- /.p-cl__courses-experiences -->
			</div>
			<!-- /.p-cl__courses-panel -->

			<div class="p-cl__courses-panel" id="cl-course-1" role="tabpanel" aria-labelledby="cl-tab-1" hidden>
				<div class="p-cl__courses-panel-meta">
					<h3 class="p-cl__courses-panel-title">職人体験コース</h3>
					<p class="p-cl__courses-panel-desc">染め物・陶器・和菓子など、静岡の職人文化を体験するコース。</p>
					<span class="p-cl__courses-panel-time">
						<svg width="14" height="14" aria-hidden="true" focusable="false"><use href="#icon-clock"></use></svg>
						3–4時間
					</span>
				</div>
				<!-- /.p-cl__courses-panel-meta -->
				<div class="p-cl__courses-route">
					<div class="p-cl__courses-route-step">
						<span class="p-cl__courses-route-num">1</span>
						<span class="p-cl__courses-route-name">紺屋町</span>
					</div>
					<span class="p-cl__courses-route-arrow" aria-hidden="true">→</span>
					<div class="p-cl__courses-route-step">
						<span class="p-cl__courses-route-num">2</span>
						<span class="p-cl__courses-route-name">人宿町</span>
					</div>
					<span class="p-cl__courses-route-arrow" aria-hidden="true">→</span>
					<div class="p-cl__courses-route-step">
						<span class="p-cl__courses-route-num">3</span>
						<span class="p-cl__courses-route-name">両替町</span>
					</div>
				</div>
				<!-- /.p-cl__courses-route -->
				<div class="p-cl__courses-experiences">
					<p class="p-cl__courses-exp-label">体験内容</p>
					<div class="p-cl__courses-exp-tags">
						<span class="p-cl__courses-exp-tag">染め物体験</span>
						<span class="p-cl__courses-exp-tag">工房見学</span>
						<span class="p-cl__courses-exp-tag">和菓子作り</span>
					</div>
					<!-- /.p-cl__courses-exp-tags -->
				</div>
				<!-- /.p-cl__courses-experiences -->
			</div>
			<!-- /.p-cl__courses-panel -->

			<div class="p-cl__courses-panel" id="cl-course-2" role="tabpanel" aria-labelledby="cl-tab-2" hidden>
				<div class="p-cl__courses-panel-meta">
					<h3 class="p-cl__courses-panel-title">ショートコース</h3>
					<p class="p-cl__courses-panel-desc">2時間でまわれる、気軽なお散歩コース。</p>
					<span class="p-cl__courses-panel-time">
						<svg width="14" height="14" aria-hidden="true" focusable="false"><use href="#icon-clock"></use></svg>
						1.5–2時間
					</span>
				</div>
				<!-- /.p-cl__courses-panel-meta -->
				<div class="p-cl__courses-route">
					<div class="p-cl__courses-route-step">
						<span class="p-cl__courses-route-num">1</span>
						<span class="p-cl__courses-route-name">七間町</span>
					</div>
					<span class="p-cl__courses-route-arrow" aria-hidden="true">→</span>
					<div class="p-cl__courses-route-step">
						<span class="p-cl__courses-route-num">2</span>
						<span class="p-cl__courses-route-name">青葉通り</span>
					</div>
				</div>
				<!-- /.p-cl__courses-route -->
				<div class="p-cl__courses-experiences">
					<p class="p-cl__courses-exp-label">体験内容</p>
					<div class="p-cl__courses-exp-tags">
						<span class="p-cl__courses-exp-tag">カフェ休憩</span>
						<span class="p-cl__courses-exp-tag">雑貨巡り</span>
					</div>
					<!-- /.p-cl__courses-exp-tags -->
				</div>
				<!-- /.p-cl__courses-experiences -->
			</div>
			<!-- /.p-cl__courses-panel -->

			<div class="p-cl__courses-panel" id="cl-course-3" role="tabpanel" aria-labelledby="cl-tab-3" hidden>
				<div class="p-cl__courses-panel-meta">
					<h3 class="p-cl__courses-panel-title">ディープ探訪コース</h3>
					<p class="p-cl__courses-panel-desc">裏路地・隠れスポット・地元民御用達の店を巡る深掘りコース。</p>
					<span class="p-cl__courses-panel-time">
						<svg width="14" height="14" aria-hidden="true" focusable="false"><use href="#icon-clock"></use></svg>
						6–8時間
					</span>
				</div>
				<!-- /.p-cl__courses-panel-meta -->
				<div class="p-cl__courses-route">
					<div class="p-cl__courses-route-step">
						<span class="p-cl__courses-route-num">1</span>
						<span class="p-cl__courses-route-name">浅間通り</span>
					</div>
					<span class="p-cl__courses-route-arrow" aria-hidden="true">→</span>
					<div class="p-cl__courses-route-step">
						<span class="p-cl__courses-route-num">2</span>
						<span class="p-cl__courses-route-name">常磐町</span>
					</div>
					<span class="p-cl__courses-route-arrow" aria-hidden="true">→</span>
					<div class="p-cl__courses-route-step">
						<span class="p-cl__courses-route-num">3</span>
						<span class="p-cl__courses-route-name">鷹匠町</span>
					</div>
					<span class="p-cl__courses-route-arrow" aria-hidden="true">→</span>
					<div class="p-cl__courses-route-step">
						<span class="p-cl__courses-route-num">4</span>
						<span class="p-cl__courses-route-name">七間町</span>
					</div>
				</div>
				<!-- /.p-cl__courses-route -->
				<div class="p-cl__courses-experiences">
					<p class="p-cl__courses-exp-label">体験内容</p>
					<div class="p-cl__courses-exp-tags">
						<span class="p-cl__courses-exp-tag">神社仏閣</span>
						<span class="p-cl__courses-exp-tag">ギャラリー</span>
						<span class="p-cl__courses-exp-tag">地元居酒屋</span>
						<span class="p-cl__courses-exp-tag">おでん横丁</span>
					</div>
					<!-- /.p-cl__courses-exp-tags -->
				</div>
				<!-- /.p-cl__courses-experiences -->
			</div>
			<!-- /.p-cl__courses-panel -->

		</div>
		<!-- /.p-cl__courses-inner -->
	</section>
	<!-- /.p-cl__courses -->

	<!-- ─── 広域観光連携 ── -->
	<section class="p-cl__wide" aria-label="広域観光連携">
		<div class="p-cl__wide-inner">
			<div class="p-cl__wide-head">
				<h2 class="p-cl__wide-title">広域観光連携</h2>
				<p class="p-cl__wide-lead">市街地（カルチャーライン）を「ハブ」として、静岡の主要観光スポットを「サテライト」に。滞在時間を延ばし、静岡全体を楽しむパッケージ。</p>
			</div>
			<!-- /.p-cl__wide-head -->
			<div class="p-cl__wide-hub">
				<span class="p-cl__wide-hub-badge">
					<svg width="14" height="14" aria-hidden="true" focusable="false"><use href="#icon-map-pin-solid"></use></svg>
					ハブ：静岡市街地（カルチャーライン）
				</span>
			</div>
			<!-- /.p-cl__wide-hub -->
			<div class="p-cl__wide-grid">

				<?php
				$spots = [
					[ 'name' => '静岡浅間神社', 'cat' => '神社仏閣',    'time' => '徒歩15分', 'img' => 'common/hero-about.jpg'   ],
					[ 'name' => '久能山東照宮', 'cat' => '世界遺産',     'time' => 'バス40分', 'img' => 'shop/shop-1.jpg'        ],
					[ 'name' => '日本平',       'cat' => '絶景',         'time' => 'バス30分', 'img' => 'top/hero-tourism.jpg'   ],
					[ 'name' => '三保の松原',   'cat' => '世界文化遺産', 'time' => 'バス45分', 'img' => 'event/event-1.jpg'      ],
					[ 'name' => '日本平動物園', 'cat' => 'レジャー',     'time' => 'バス25分', 'img' => 'gallery/gallery-4.jpg'  ],
					[ 'name' => '登呂遺跡',     'cat' => '歴史',         'time' => 'バス20分', 'img' => 'gallery/gallery-5.jpg'  ],
				];
				foreach ( $spots as $spot ) :
				?>
				<div class="p-cl__wide-card">
					<div class="p-cl__wide-card-photo" aria-hidden="true">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/' . $spot['img'] ); ?>" alt="" width="400" height="267" loading="lazy">
					</div>
					<!-- /.p-cl__wide-card-photo -->
					<div class="p-cl__wide-card-body">
						<p class="p-cl__wide-card-name"><?php echo esc_html( $spot['name'] ); ?></p>
						<div class="p-cl__wide-card-meta">
							<span class="p-cl__wide-card-cat"><?php echo esc_html( $spot['cat'] ); ?></span>
							<span class="p-cl__wide-card-time"><?php echo esc_html( $spot['time'] ); ?></span>
						</div>
						<!-- /.p-cl__wide-card-meta -->
					</div>
					<!-- /.p-cl__wide-card-body -->
				</div>
				<!-- /.p-cl__wide-card -->
				<?php endforeach; ?>

			</div>
			<!-- /.p-cl__wide-grid -->
			<div class="p-cl__wide-desc">
				<div class="p-cl__wide-desc-item">
					<h3 class="p-cl__wide-desc-title">相互プロモーション</h3>
					<p class="p-cl__wide-desc-body">各施設・エリアが互いの魅力を紹介し合い、訪問者の次の目的地を自然に提案。デジタルスタンプラリーやクロスクーポンで回遊を促進。</p>
				</div>
				<!-- /.p-cl__wide-desc-item -->
				<div class="p-cl__wide-desc-item">
					<h3 class="p-cl__wide-desc-title">クロスマーケティング</h3>
					<p class="p-cl__wide-desc-body">市街地で得た情報が郊外スポットへの動機となり、郊外から戻った人が市街地の夜を楽しむ。滞在時間の最大化と地域経済の循環を実現。</p>
				</div>
				<!-- /.p-cl__wide-desc-item -->
			</div>
			<!-- /.p-cl__wide-desc -->
		</div>
		<!-- /.p-cl__wide-inner -->
	</section>
	<!-- /.p-cl__wide -->

	<!-- ─── OUR IMPACT（ロードマップ含む） ── -->
	<section class="p-cl__impact" aria-label="街にもたらす価値">
		<div class="p-cl__impact-inner">
			<div class="p-cl__impact-head">
				<p class="p-cl__impact-eyebrow">OUR IMPACT</p>
				<h2 class="p-cl__impact-title">街にもたらす価値</h2>
				<p class="p-cl__impact-lead">この構想は観光客だけのものではありません。経済、文化、環境、そして市民の誇り──多面的な価値を街にもたらします。</p>
			</div>
			<!-- /.p-cl__impact-head -->

			<!-- 上段3列 -->
			<div class="p-cl__impact-grid">

				<div class="p-cl__impact-card p-cl__impact-card--economy">
					<div class="p-cl__impact-card-icon">
						<svg aria-hidden="true" focusable="false"><use href="#icon-yen"></use></svg>
					</div>
					<!-- /.p-cl__impact-card-icon -->
					<h3 class="p-cl__impact-card-title">経済の循環</h3>
					<p class="p-cl__impact-card-body">日帰り観光から「1泊2日の周遊」へ転換。滞在時間を延ばし、市街地のナイトタイムエコノミー（おでん横丁・バー・居酒屋）を活性化。</p>
					<div class="p-cl__impact-card-tags">
						<span class="p-cl__impact-card-tag">域内消費額UP</span>
						<span class="p-cl__impact-card-tag">税収増</span>
					</div>
					<!-- /.p-cl__impact-card-tags -->
				</div>
				<!-- /.p-cl__impact-card -->

				<div class="p-cl__impact-card p-cl__impact-card--civic">
					<div class="p-cl__impact-card-icon">
						<svg aria-hidden="true" focusable="false"><use href="#icon-heart-solid"></use></svg>
					</div>
					<!-- /.p-cl__impact-card-icon -->
					<h3 class="p-cl__impact-card-title">シビックプライド</h3>
					<p class="p-cl__impact-card-body">地元の子どもたちが歴史を学び、若者が文化を創造し、誇りを持てる街へ。観光客だけでなく、市民が街の歴史と文化を再発見し、次世代へ語り継ぐ舞台に。</p>
					<div class="p-cl__impact-card-tags">
						<span class="p-cl__impact-card-tag">郷土愛</span>
						<span class="p-cl__impact-card-tag">次世代育成</span>
					</div>
					<!-- /.p-cl__impact-card-tags -->
				</div>
				<!-- /.p-cl__impact-card -->

				<div class="p-cl__impact-card p-cl__impact-card--smart">
					<div class="p-cl__impact-card-icon">
						<svg aria-hidden="true" focusable="false"><use href="#icon-bolt-solid"></use></svg>
					</div>
					<!-- /.p-cl__impact-card-icon -->
					<h3 class="p-cl__impact-card-title">スマートな移動</h3>
					<p class="p-cl__impact-card-body">シェアサイクル（PULCLE）や公共交通と連携し、歩いて楽しい・環境に優しい街づくり。「点から線」を実現する移動インフラ。</p>
					<div class="p-cl__impact-card-tags">
						<span class="p-cl__impact-card-tag">MaaS</span>
						<span class="p-cl__impact-card-tag">脱炭素</span>
					</div>
					<!-- /.p-cl__impact-card-tags -->
				</div>
				<!-- /.p-cl__impact-card -->

			</div>
			<!-- /.p-cl__impact-grid -->

			<!-- 下段：官民共創カード + ロードマップ統合カード -->
			<div class="p-cl__impact-row2">

				<div class="p-cl__impact-card p-cl__impact-card--collab">
					<div class="p-cl__impact-card-icon">
						<svg aria-hidden="true" focusable="false"><use href="#icon-users-solid"></use></svg>
					</div>
					<!-- /.p-cl__impact-card-icon -->
					<h3 class="p-cl__impact-card-title">官民共創プラットフォーム</h3>
					<p class="p-cl__impact-card-body">行政・商店街・クリエイター・企業・市民が一体となって進めるプロジェクト。「やってほしい」ではなく「一緒にやる」姿勢で、持続可能な街づくりを実現。</p>
					<div class="p-cl__impact-card-tags">
						<span class="p-cl__impact-card-tag">共創</span>
						<span class="p-cl__impact-card-tag">持続可能</span>
					</div>
					<!-- /.p-cl__impact-card-tags -->
				</div>
				<!-- /.p-cl__impact-card -->

				<!-- ロードマップ統合カード -->
				<div class="p-cl__impact-roadmap">
					<div class="p-cl__impact-roadmap-head">
						<div class="p-cl__impact-roadmap-icon">
							<svg aria-hidden="true" focusable="false"><use href="#icon-desk"></use></svg>
						</div>
						<!-- /.p-cl__impact-roadmap-icon -->
						<div>
							<p class="p-cl__impact-roadmap-title">ロードマップ ── 実現へのステップ</p>
						</div>
					</div>
					<!-- /.p-cl__impact-roadmap-head -->
					<div class="p-cl__impact-roadmap-steps">

						<div class="p-cl__impact-roadmap-step p-cl__impact-roadmap-step--1">
							<div class="p-cl__impact-roadmap-step-badge">
								<span class="p-cl__impact-roadmap-step-num">1</span>
								<span class="p-cl__impact-roadmap-step-phase">現在</span>
							</div>
							<!-- /.p-cl__impact-roadmap-step-badge -->
							<p class="p-cl__impact-roadmap-step-title">エリア連携の基盤づくり</p>
							<p class="p-cl__impact-roadmap-step-desc">カルチャーライン構想サイト公開、エリア間の情報連携開始、デジタルマップ整備</p>
						</div>
						<!-- /.p-cl__impact-roadmap-step -->

						<div class="p-cl__impact-roadmap-step p-cl__impact-roadmap-step--2">
							<div class="p-cl__impact-roadmap-step-badge">
								<span class="p-cl__impact-roadmap-step-num">2</span>
								<span class="p-cl__impact-roadmap-step-phase">中期（2-3年）</span>
							</div>
							<!-- /.p-cl__impact-roadmap-step-badge -->
							<p class="p-cl__impact-roadmap-step-title">街歩きインフラの定着</p>
							<p class="p-cl__impact-roadmap-step-desc">シェアサイクル拡充、デジタルスタンプラリー定着、ナイトタイムコンテンツ充実、回遊人口の増加</p>
						</div>
						<!-- /.p-cl__impact-roadmap-step -->

						<div class="p-cl__impact-roadmap-step p-cl__impact-roadmap-step--3">
							<div class="p-cl__impact-roadmap-step-badge">
								<span class="p-cl__impact-roadmap-step-num">3</span>
								<span class="p-cl__impact-roadmap-step-phase">長期（5年〜）</span>
							</div>
							<!-- /.p-cl__impact-roadmap-step-badge -->
							<p class="p-cl__impact-roadmap-step-title">歴史都市としての飛躍</p>
							<p class="p-cl__impact-roadmap-step-desc">駿府城再建の実現、歴史観光都市としてのブランド確立、国内外からの誘客基盤完成</p>
						</div>
						<!-- /.p-cl__impact-roadmap-step -->

					</div>
					<!-- /.p-cl__impact-roadmap-steps -->
				</div>
				<!-- /.p-cl__impact-roadmap -->

			</div>
			<!-- /.p-cl__impact-row2 -->

		</div>
		<!-- /.p-cl__impact-inner -->
	</section>
	<!-- /.p-cl__impact -->

	<!-- ─── VISION CTA ── -->
	<section class="p-cl__future" aria-label="駿府城再建がもたらす未来">
		<picture class="p-cl__future-bg" aria-hidden="true">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/top/hero-tourism.jpg' ); ?>" alt="" width="1440" height="800" loading="lazy">
		</picture>
		<!-- /.p-cl__future-bg -->
		<div class="p-cl__future-inner">
			<p class="p-cl__future-eyebrow">VISION</p>
			<h2 class="p-cl__future-title">駿府城再建がもたらす未来</h2>
			<p class="p-cl__future-lead">駿府城が再建されれば、静岡市は日本有数の歴史観光都市となる。その日に向けて、今から地域の魅力を磨き、受け入れ態勢を整える。カルチャーラインは、その未来への布石。</p>
			<p class="p-cl__future-sub">この構想は、行政、商店街、民間企業、そして市民が一体となって進める共創プロジェクトです。再建という大きな未来に向けて、今日からできる「歩いて楽しい街づくり」を始めます。</p>
			<div class="p-cl__future-stats">
				<div class="p-cl__future-stat">
					<span class="p-cl__future-stat-num">10<small>+</small></span>
					<span class="p-cl__future-stat-label">連携エリア</span>
				</div>
				<!-- /.p-cl__future-stat -->
				<div class="p-cl__future-stat">
					<span class="p-cl__future-stat-num">4</span>
					<span class="p-cl__future-stat-label">モデルコース</span>
				</div>
				<!-- /.p-cl__future-stat -->
				<div class="p-cl__future-stat">
					<span class="p-cl__future-stat-num">6<small>+</small></span>
					<span class="p-cl__future-stat-label">広域観光スポット</span>
				</div>
				<!-- /.p-cl__future-stat -->
			</div>
			<!-- /.p-cl__future-stats -->
			<a href="<?php echo esc_url( get_permalink( get_page_by_path( 'about' ) ) ); ?>" class="p-cl__future-btn">
				<svg width="18" height="18" aria-hidden="true" focusable="false"><use href="#icon-chevron-left"></use></svg>
				町の紹介に戻る
			</a>
		</div>
		<!-- /.p-cl__future-inner -->
	</section>
	<!-- /.p-cl__future -->

</article>
<!-- /.p-cl -->

<?php get_footer(); ?>
