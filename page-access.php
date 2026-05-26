<?php
/**
 * Template Name: アクセス
 * アクセスページ（交通手段タブ + 地図）
 */
get_header();

// ─── 駐車場データ ──
$parkings = [
	[
		'name'     => '七間町パーキング',
		'badge'    => '最寄り',
		'hours'    => '24時間営業',
		'capacity' => '50台',
		'fee'      => '200円/30分',
		'note'     => '',
		'walk'     => '',
	],
	[
		'name'     => '静岡パルコ駐車場',
		'badge'    => '徒歩5分',
		'hours'    => '7:00〜24:00',
		'capacity' => '200台',
		'fee'      => '300円/30分',
		'note'     => '※パルコでのお買い物で割引あり',
		'walk'     => '',
	],
	[
		'name'     => '市営七間町駐車場',
		'badge'    => '徒歩3分',
		'hours'    => '7:00〜22:00',
		'capacity' => '80台',
		'fee'      => '150円/30分',
		'note'     => '※最大料金 1,200円/日',
		'walk'     => '',
	],
	[
		'name'     => 'タイムズ呉服町',
		'badge'    => '徒歩7分',
		'hours'    => '24時間営業',
		'capacity' => '30台',
		'fee'      => '220円/30分',
		'note'     => '※最大料金 1,500円/日',
		'walk'     => '',
	],
];
?>

<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>

<article class="p-access">

	<?php
	get_template_part( 'template-parts/components/page-hero', null, [
		'title' => 'アクセス',
		'sub'   => '七間町への道順を紹介します。',
	] );
	?>

	<!-- ─── CTAボタン + タブ ── -->
	<section class="p-access__main">
		<div class="p-access__main-inner">

			<!-- ナビCTAボタン -->
			<a
				class="p-access__navi-btn"
				href="https://www.google.com/maps/dir/?api=1&destination=静岡県静岡市葵区七間町"
				target="_blank"
				rel="noopener noreferrer"
			>
				<svg aria-hidden="true" focusable="false" width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
					<path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
				</svg>
				今すぐ現在地から七間町へナビを開始
			</a>
		</div>
		<!-- /.p-access__main-inner -->

		<!-- タブナビ（フル幅） -->
		<nav class="c-tabs js-tabs" aria-label="交通手段">
				<div class="c-tabs__inner">
					<ul class="c-tabs__list" role="tablist">
						<li class="c-tabs__item" role="presentation">
							<button class="c-tabs__btn is-active" role="tab" id="tab-train" aria-controls="panel-train" aria-selected="true" type="button">
								<svg class="c-tabs__icon" aria-hidden="true" focusable="false"><use href="#icon-train"></use></svg>電車・新幹線
							</button>
						</li>
						<li class="c-tabs__item" role="presentation">
							<button class="c-tabs__btn" role="tab" id="tab-walk" aria-controls="panel-walk" aria-selected="false" type="button">
								<svg class="c-tabs__icon" aria-hidden="true" focusable="false"><use href="#icon-person"></use></svg>徒歩ルート
							</button>
						</li>
						<li class="c-tabs__item" role="presentation">
							<button class="c-tabs__btn" role="tab" id="tab-bus" aria-controls="panel-bus" aria-selected="false" type="button">
								<svg class="c-tabs__icon" aria-hidden="true" focusable="false"><use href="#icon-bus"></use></svg>バス
							</button>
						</li>
						<li class="c-tabs__item" role="presentation">
							<button class="c-tabs__btn" role="tab" id="tab-taxi" aria-controls="panel-taxi" aria-selected="false" type="button">
								<svg class="c-tabs__icon" aria-hidden="true" focusable="false"><use href="#icon-car"></use></svg>車・タクシー
							</button>
						</li>
						<li class="c-tabs__item" role="presentation">
							<button class="c-tabs__btn" role="tab" id="tab-bicycle" aria-controls="panel-bicycle" aria-selected="false" type="button">
								<svg class="c-tabs__icon" aria-hidden="true" focusable="false"><use href="#icon-bicycle"></use></svg>自転車
							</button>
						</li>
						<li class="c-tabs__item" role="presentation">
							<button class="c-tabs__btn" role="tab" id="tab-parking" aria-controls="panel-parking" aria-selected="false" type="button">
								<svg class="c-tabs__icon" aria-hidden="true" focusable="false"><use href="#icon-parking"></use></svg>駐車場
							</button>
						</li>
					</ul>
				</div>
			</nav>

			<div class="p-access__main-inner">

			<!-- ─── パネル: 電車・新幹線 ── -->
			<div class="c-tabs-panel p-access__panel" id="panel-train" role="tabpanel" aria-labelledby="tab-train">
				<div class="p-access__panel-hero">
					<h2 class="p-access__panel-title">東京から、わずか69分。</h2>
					<p class="p-access__panel-en">Short Trip from Tokyo</p>
					<p class="p-access__panel-lead">新幹線に飛び乗れば、一息つく間に静岡へ。<br>日常を少し離れて、歴史と文化が息づく七間町へ。</p>
				</div>

				<!-- 主要駅からの所要時間 -->
				<div class="p-access__card">
					<h3 class="p-access__card-heading">主要駅からの所要時間</h3>
					<ul class="p-access__train-list">
						<li class="p-access__train-row">
							<span class="p-access__train-left">
								<svg class="p-access__train-icon" aria-hidden="true" focusable="false"><use href="#icon-train"></use></svg>
								東京駅
							</span>
							<span class="p-access__train-right">
								<strong class="p-access__train-time">約69分</strong>
								<span class="p-access__train-line">ひかり・こだま</span>
							</span>
						</li>
						<li class="p-access__train-row">
							<span class="p-access__train-left">
								<svg class="p-access__train-icon" aria-hidden="true" focusable="false"><use href="#icon-train"></use></svg>
								名古屋駅
							</span>
							<span class="p-access__train-right">
								<strong class="p-access__train-time">約60分</strong>
								<span class="p-access__train-line">ひかり・こだま</span>
							</span>
						</li>
						<li class="p-access__train-row">
							<span class="p-access__train-left">
								<svg class="p-access__train-icon" aria-hidden="true" focusable="false"><use href="#icon-train"></use></svg>
								新大阪駅
							</span>
							<span class="p-access__train-right">
								<strong class="p-access__train-time">約120分</strong>
								<span class="p-access__train-line">ひかり</span>
							</span>
						</li>
						<li class="p-access__train-row">
							<span class="p-access__train-left">
								<svg class="p-access__train-icon" aria-hidden="true" focusable="false"><use href="#icon-train"></use></svg>
								浜松駅
							</span>
							<span class="p-access__train-right">
								<strong class="p-access__train-time">約30分</strong>
								<span class="p-access__train-line">JR東海道本線</span>
							</span>
						</li>
					</ul>
				</div>
				<!-- /.p-access__card -->

				<!-- 静岡駅到着案内 -->
				<div class="p-access__card">
					<h3 class="p-access__card-heading">静岡駅に到着したら</h3>
					<div class="p-access__highlight-box">
						<p class="p-access__highlight-text">新幹線、JR各線ともに「北口」を目指してください。</p>
						<ul class="p-access__highlight-list">
							<li>改札を出て右手が「北口（松坂屋・パルコ方面）」です。</li>
							<li>地下道を利用すると雨に濡れず移動できます。</li>
						</ul>
					</div>
					<p class="p-access__note-line">
						<svg aria-hidden="true" focusable="false" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="flex-shrink:0"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
						静岡駅から七間町まで徒歩約15分。バスで約5分。
					</p>
				</div>
				<!-- /.p-access__card -->
			</div>
			<!-- /.p-access__panel #panel-train -->

			<!-- ─── パネル: 徒歩ルート ── -->
			<div class="c-tabs-panel p-access__panel" id="panel-walk" role="tabpanel" aria-labelledby="tab-walk" hidden>
				<div class="p-access__panel-hero">
					<h2 class="p-access__panel-title">静岡駅から七間町へ（徒歩15分）</h2>
					<p class="p-access__panel-sub">2つのルートをご紹介します</p>
				</div>

				<!-- ルートA -->
				<div class="p-access__route-card">
					<div class="p-access__route-header">
						<div class="p-access__route-header-left">
							<span class="p-access__route-badge p-access__route-badge--a">ルートA</span>
							<span class="p-access__route-name">最短ルート</span>
						</div>
						<span class="p-access__route-time">
							<svg aria-hidden="true" focusable="false" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
							約12分
						</span>
					</div>
					<p class="p-access__route-via">地下道・呉服町スクランブル経由</p>
					<p class="p-access__route-desc">人混みを避け、最短距離で七間町へ。日差しや雨を避けたい場合は地下道出口「KB」を目指すと便利です。</p>
					<div class="p-access__route-steps-wrap">
						<p class="p-access__route-steps-label">ルート詳細</p>
						<ol class="p-access__route-steps">
							<li>静岡駅北口を出て地下道へ</li>
							<li>地下道出口「KB」から地上へ</li>
							<li>呉服町スクランブル交差点を直進</li>
							<li>七間町通りに到着</li>
						</ol>
					</div>
				</div>
				<!-- /.p-access__route-card -->

				<!-- ルートB -->
				<div class="p-access__route-card">
					<div class="p-access__route-header">
						<div class="p-access__route-header-left">
							<span class="p-access__route-badge p-access__route-badge--b">ルートB</span>
							<span class="p-access__route-name">お散歩ルート</span>
						</div>
						<span class="p-access__route-time">
							<svg aria-hidden="true" focusable="false" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
							約15分
						</span>
					</div>
					<p class="p-access__route-via">呉服町通り・伊勢丹前経由</p>
					<p class="p-access__route-desc">静岡のメインストリートを楽しみながら。百貨店やカフェ、雑貨屋が並び、15分があっという間に感じられます。</p>
					<div class="p-access__route-steps-wrap">
						<p class="p-access__route-steps-label">ルート詳細</p>
						<ol class="p-access__route-steps">
							<li>静岡駅北口を出て正面の通りを直進</li>
							<li>呉服町通りのアーケードを歩く</li>
							<li>伊勢丹前を通過</li>
							<li>七間町交差点を左折して到着</li>
						</ol>
					</div>
				</div>
				<!-- /.p-access__route-card -->
			</div>
			<!-- /.p-access__panel #panel-walk -->

			<!-- ─── パネル: バス ── -->
			<div class="c-tabs-panel p-access__panel" id="panel-bus" role="tabpanel" aria-labelledby="tab-bus" hidden>
				<div class="p-access__panel-hero">
					<h2 class="p-access__panel-title">バスで七間町へ</h2>
					<p class="p-access__panel-sub">歩くのが大変な方、荷物が多い方におすすめ</p>
				</div>

				<div class="p-access__card">
					<h3 class="p-access__card-heading">路線バス情報</h3>
					<dl class="p-access__bus-dl">
						<div class="p-access__bus-row">
							<dt><span class="p-access__label">乗り場</span></dt>
							<dd>静岡駅北口 10番乗り場</dd>
						</div>
						<div class="p-access__bus-row">
							<dt><span class="p-access__label">路線名</span></dt>
							<dd>駿府浪漫バス または 安倍線</dd>
						</div>
						<div class="p-access__bus-row">
							<dt><span class="p-access__label">下車停</span></dt>
							<dd>「七間町」バス停</dd>
						</div>
						<div class="p-access__bus-row">
							<dt><span class="p-access__label">所要時間</span></dt>
							<dd>約5分</dd>
						</div>
						<div class="p-access__bus-row">
							<dt><span class="p-access__label">運賃</span></dt>
							<dd>大人 100〜170円</dd>
						</div>
					</dl>
				</div>
				<!-- /.p-access__card -->

				<div class="p-access__info-note">
					<svg aria-hidden="true" focusable="false" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" style="flex-shrink:0"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
					<div>
						<strong>駿府浪漫バスについて</strong><br>
						静岡市内の観光スポットを巡回するバスです。1日乗車券（大人300円）もあり、七間町周辺の散策に便利です。
					</div>
				</div>
			</div>
			<!-- /.p-access__panel #panel-bus -->

			<!-- ─── パネル: 車・タクシー ── -->
			<div class="c-tabs-panel p-access__panel" id="panel-taxi" role="tabpanel" aria-labelledby="tab-taxi" hidden>
				<div class="p-access__panel-hero">
					<h2 class="p-access__panel-title">車・タクシーで七間町へ</h2>
				</div>

				<!-- タクシー -->
				<div class="p-access__card">
					<h3 class="p-access__card-heading--icon">
						<svg class="p-access__card-icon" aria-hidden="true" focusable="false"><use href="#icon-car"></use></svg>
						タクシー
					</h3>
					<div class="p-access__stats-grid">
						<div class="p-access__stat">
							<dt>所要時間</dt>
							<dd>約5分</dd>
						</div>
						<div class="p-access__stat">
							<dt>料金目安</dt>
							<dd>約700円〜</dd>
						</div>
						<div class="p-access__stat">
							<dt>乗り場</dt>
							<dd>北口</dd>
						</div>
					</div>
					<div class="p-access__yellow-note">
						運転手さんには「七間町（しちけんちょう）まで」とお伝えください。
					</div>
				</div>
				<!-- /.p-access__card -->

				<!-- 車（高速道路） -->
				<div class="p-access__card">
					<h3 class="p-access__card-heading--icon">
						<svg class="p-access__card-icon" aria-hidden="true" focusable="false"><use href="#icon-car"></use></svg>
						車（高速道路）
					</h3>
					<dl class="p-access__bus-dl">
						<div class="p-access__bus-row">
							<dt><span class="p-access__label p-access__label--blue">東名高速</span></dt>
							<dd>静岡ICから約15分</dd>
						</div>
						<div class="p-access__bus-row">
							<dt><span class="p-access__label p-access__label--green">新東名</span></dt>
							<dd>新静岡ICから約10分</dd>
						</div>
					</dl>
					<div class="p-access__blue-note">
						カーナビには「静岡市葵区七間町」と入力してください。駐車場情報は「駐車場」タブをご確認ください。
					</div>
				</div>
				<!-- /.p-access__card -->

				<!-- レンタカー -->
				<div class="p-access__card">
					<h3 class="p-access__card-heading">レンタカー</h3>
					<p style="font-size:<?php echo esc_attr( 'var(--fs-sm)' ); ?>;color:var(--color-text-muted);margin-bottom:1rem;">静岡駅周辺には複数のレンタカー店舗があります。</p>
					<ul class="p-access__rental-list">
						<li>トヨタレンタカー 静岡駅前店</li>
						<li>ニッポンレンタカー 静岡駅北口店</li>
						<li>タイムズカーレンタル 静岡駅北口店</li>
						<li>オリックスレンタカー 静岡駅前店</li>
					</ul>
				</div>
				<!-- /.p-access__card -->
			</div>
			<!-- /.p-access__panel #panel-taxi -->

			<!-- ─── パネル: 自転車 ── -->
			<div class="c-tabs-panel p-access__panel" id="panel-bicycle" role="tabpanel" aria-labelledby="tab-bicycle" hidden>
				<div class="p-access__panel-hero">
					<h2 class="p-access__panel-title">自転車で七間町へ</h2>
					<p class="p-access__panel-sub">シェアサイクル「PULCLE」が便利です</p>
				</div>

				<div class="p-access__card">
					<h3 class="p-access__card-heading--icon">
						<svg class="p-access__card-icon p-access__card-icon--green" aria-hidden="true" focusable="false"><use href="#icon-bicycle"></use></svg>
						PULCLE（パルクル）
					</h3>
					<p class="p-access__card-desc">静岡市のシェアサイクルサービス。スマホアプリで簡単に利用できます。</p>
					<div class="p-access__stats-grid">
						<div class="p-access__stat">
							<dt>料金</dt>
							<dd>30分 130円</dd>
						</div>
						<div class="p-access__stat">
							<dt>1日パス</dt>
							<dd>1,000円</dd>
						</div>
						<div class="p-access__stat">
							<dt>ポート数</dt>
							<dd>100+</dd>
						</div>
					</div>

					<h4 class="p-access__sub-list-heading">七間町周辺のポート</h4>
					<ul class="p-access__port-list">
						<li>
							<svg aria-hidden="true" focusable="false" width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
							七間町通り（七間町公園前）
						</li>
						<li>
							<svg aria-hidden="true" focusable="false" width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
							呉服町通り（伊勢丹前）
						</li>
						<li>
							<svg aria-hidden="true" focusable="false" width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
							静岡駅北口（駅前広場）
						</li>
					</ul>
				</div>
				<!-- /.p-access__card -->
			</div>
			<!-- /.p-access__panel #panel-bicycle -->

			<!-- ─── パネル: 駐車場 ── -->
			<div class="c-tabs-panel p-access__panel" id="panel-parking" role="tabpanel" aria-labelledby="tab-parking" hidden>
				<div class="p-access__panel-hero">
					<h2 class="p-access__panel-title">七間町周辺の駐車場</h2>
					<p class="p-access__panel-sub">お車でお越しの方はこちらをご利用ください</p>
				</div>

				<?php foreach ( $parkings as $p ) : ?>
				<div class="p-access__parking-card">
					<div class="p-access__parking-head">
						<span class="p-access__parking-name"><?php echo esc_html( $p['name'] ); ?></span>
						<?php if ( $p['badge'] ) : ?>
						<span class="p-access__parking-badge"><?php echo esc_html( $p['badge'] ); ?></span>
						<?php endif; ?>
					</div>
					<div class="p-access__parking-body">
						<span class="p-access__parking-meta-item">
							<svg aria-hidden="true" focusable="false" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
							<?php echo esc_html( $p['hours'] ); ?>
						</span>
						<span class="p-access__parking-meta-item">
							<svg aria-hidden="true" focusable="false" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
							<?php echo esc_html( $p['capacity'] ); ?>
						</span>
						<span class="p-access__parking-fee"><?php echo esc_html( $p['fee'] ); ?></span>
					</div>
					<?php if ( $p['note'] ) : ?>
					<p class="p-access__parking-note"><?php echo esc_html( $p['note'] ); ?></p>
					<?php endif; ?>
				</div>
				<?php endforeach; ?>

				<div class="p-access__info-note">
					<svg aria-hidden="true" focusable="false" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" style="flex-shrink:0"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
					<div>
						<strong>駐車場のご利用について</strong><br>
						週末やイベント開催時は混雑が予想されます。公共交通機関のご利用もご検討ください。
					</div>
				</div>
			</div>
			<!-- /.p-access__panel #panel-parking -->

		</div>
		<!-- /.p-access__main-inner -->
	</section>
	<!-- /.p-access__main -->

	<!-- ─── 地図 ── -->
	<section class="p-access__map">
		<div class="p-access__map-inner">
			<h2 class="p-access__map-title">七間町の場所</h2>
			<div class="p-access__map-wrap">
				<a
					class="p-access__map-open-btn"
					href="https://maps.google.com/?q=静岡県静岡市葵区七間町"
					target="_blank"
					rel="noopener noreferrer"
				>
					マップで開く
					<svg aria-hidden="true" focusable="false" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
				</a>
				<iframe
					src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3283.5347!2d138.3827!3d34.9756!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x601a0a0e2beb9045%3A0x4b18bd3fad72b1a4!2z5LZ15ZCM55S677yR5LZ15ZCM!5e0!3m2!1sja!2sjp!4v1683500000000!5m2!1sja!2sjp"
					width="800"
					height="450"
					style="border:0;"
					allowfullscreen=""
					loading="lazy"
					referrerpolicy="no-referrer-when-downgrade"
					title="七間町商店街の地図"
				></iframe>
			</div>
			<!-- /.p-access__map-wrap -->
			<address class="p-access__address">〒420-0035 静岡県静岡市葵区七間町</address>
		</div>
		<!-- /.p-access__map-inner -->
	</section>
	<!-- /.p-access__map -->

</article>
<!-- /.p-access -->

<?php get_footer(); ?>
