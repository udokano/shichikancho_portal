<?php
/** いのちを守るページ */
get_header();
?>

<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>

<article class="p-safety">

	<?php
		get_template_part( 'template-parts/components/page-hero', null, [
			'title' => 'いのちを守る',
			'sub'   => '備えあれば、憂いなし。',
		] );
		?>

	<!-- ─── 重要告知バナー ── -->
	<div class="p-safety__alert" role="alert">
		<div class="p-safety__alert-inner">
			<svg class="p-safety__alert-icon" aria-hidden="true" focusable="false" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><use href="#icon-shield"></use></svg>
			<div class="p-safety__alert-body">
				<p class="p-safety__alert-heading">静岡県は南海トラフ巨大地震の想定地域です</p>
				<p class="p-safety__alert-text">日頃からの備えが、あなたと家族のいのちを守ります。このページの情報を必ずご確認ください。</p>
			</div>
		</div>
		<!-- /.p-safety__alert-inner -->
	</div>
	<!-- /.p-safety__alert -->

	<!-- ─── タブナビ ── -->
	<nav class="c-tabs js-tabs" data-panels=".p-safety__section" aria-label="防災カテゴリー">
		<div class="c-tabs__inner">
			<ul class="c-tabs__list" role="tablist">
				<li class="c-tabs__item" role="presentation"><button type="button" class="c-tabs__btn is-active" role="tab" aria-selected="true"  aria-controls="safety-emergency">緊急連絡先</button></li>
				<li class="c-tabs__item" role="presentation"><button type="button" class="c-tabs__btn" role="tab" aria-selected="false" aria-controls="safety-shelter">避難場所</button></li>
				<li class="c-tabs__item" role="presentation"><button type="button" class="c-tabs__btn" role="tab" aria-selected="false" aria-controls="safety-earthquake">地震・津波</button></li>
				<li class="c-tabs__item" role="presentation"><button type="button" class="c-tabs__btn" role="tab" aria-selected="false" aria-controls="safety-fuji">富士山噴火</button></li>
				<li class="c-tabs__item" role="presentation"><button type="button" class="c-tabs__btn" role="tab" aria-selected="false" aria-controls="safety-daily">日頃の備え</button></li>
				<li class="c-tabs__item" role="presentation"><button type="button" class="c-tabs__btn" role="tab" aria-selected="false" aria-controls="safety-links">公式情報</button></li>
			</ul>
		</div>
		<!-- /.c-tabs__inner -->
	</nav>
	<!-- /.c-tabs -->

	<!-- ─── 緊急連絡先 ── -->
	<section class="p-safety__section" id="safety-emergency" role="tabpanel" aria-labelledby="safety-emergency-title">
		<div class="p-safety__section-inner">
			<h2 class="p-safety__section-title" id="safety-emergency-title">緊急連絡先</h2>
			<div class="p-safety__contacts">

				<a class="p-safety__contact p-safety__contact--police" href="tel:110">
					<div class="p-safety__contact-circle">
						<span class="p-safety__contact-circle-num">110</span>
					</div>
					<h3 class="p-safety__contact-label">警察（事件・事故）</h3>
					<p class="p-safety__contact-num">110</p>
				</a>

				<a class="p-safety__contact p-safety__contact--fire" href="tel:119">
					<div class="p-safety__contact-circle">
						<span class="p-safety__contact-circle-num">119</span>
					</div>
					<h3 class="p-safety__contact-label">消防・救急</h3>
					<p class="p-safety__contact-num">119</p>
				</a>

				<a class="p-safety__contact p-safety__contact--coast" href="tel:118">
					<div class="p-safety__contact-circle">
						<span class="p-safety__contact-circle-num">118</span>
					</div>
					<h3 class="p-safety__contact-label">海上保安庁</h3>
					<p class="p-safety__contact-num">118</p>
				</a>

				<a class="p-safety__contact p-safety__contact--city" href="tel:0542542111">
					<div class="p-safety__contact-circle">
						<svg aria-hidden="true" focusable="false" class="p-safety__contact-circle-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><use href="#icon-phone"></use></svg>
					</div>
					<h3 class="p-safety__contact-label">静岡市役所（代表）</h3>
					<p class="p-safety__contact-num">054-254-2111</p>
				</a>

				<a class="p-safety__contact p-safety__contact--bousai" href="tel:0542211241">
					<div class="p-safety__contact-circle">
						<svg aria-hidden="true" focusable="false" class="p-safety__contact-circle-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><use href="#icon-phone"></use></svg>
					</div>
					<h3 class="p-safety__contact-label">静岡市防災対策課</h3>
					<p class="p-safety__contact-num">054-221-1241</p>
				</a>

				<a class="p-safety__contact p-safety__contact--pref-police" href="tel:0542710110">
					<div class="p-safety__contact-circle">
						<svg aria-hidden="true" focusable="false" class="p-safety__contact-circle-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><use href="#icon-phone"></use></svg>
					</div>
					<h3 class="p-safety__contact-label">静岡県警察本部</h3>
					<p class="p-safety__contact-num">054-271-0110</p>
				</a>

				<a class="p-safety__contact p-safety__contact--hospital" href="tel:0542611111">
					<div class="p-safety__contact-circle">
						<svg aria-hidden="true" focusable="false" class="p-safety__contact-circle-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><use href="#icon-phone"></use></svg>
					</div>
					<h3 class="p-safety__contact-label">静岡市急病センター</h3>
					<p class="p-safety__contact-num">054-261-1111</p>
				</a>

				<a class="p-safety__contact p-safety__contact--dengon" href="tel:171">
					<div class="p-safety__contact-circle">
						<span class="p-safety__contact-circle-num">171</span>
					</div>
					<h3 class="p-safety__contact-label">災害用伝言ダイヤル</h3>
					<p class="p-safety__contact-num">171</p>
				</a>

				<a class="p-safety__contact p-safety__contact--kyukyu" href="tel:#7119">
					<div class="p-safety__contact-circle">
						<svg aria-hidden="true" focusable="false" class="p-safety__contact-circle-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><use href="#icon-phone"></use></svg>
					</div>
					<h3 class="p-safety__contact-label">救急安心センター</h3>
					<p class="p-safety__contact-num">#7119</p>
				</a>

			</div>
			<!-- /.p-safety__contacts -->

			<div class="p-safety__dengon-guide">
				<h3 class="p-safety__dengon-title">災害用伝言ダイヤルの使い方</h3>
				<ol class="p-safety__dengon-steps">
					<li class="p-safety__step"><strong class="p-safety__step-highlight">171</strong> に電話する</li>
					<li class="p-safety__step"><strong class="p-safety__step-highlight">1</strong>（録音）または <strong class="p-safety__step-highlight">2</strong>（再生）を選択</li>
					<li class="p-safety__step">自宅の電話番号を入力</li>
				</ol>
				<p class="p-safety__dengon-note">家族で事前に「どの電話番号で登録するか」を決めておきましょう。</p>
			</div>
			<!-- /.p-safety__dengon-guide -->
		</div>
		<!-- /.p-safety__section-inner -->
	</section>
	<!-- /.p-safety__section -->

	<!-- ─── 避難場所 ── -->
	<section class="p-safety__section p-safety__section--washi" id="safety-shelter" role="tabpanel" hidden aria-labelledby="safety-shelter-title">
		<div class="p-safety__section-inner">
			<h2 class="p-safety__section-title" id="safety-shelter-title">避難場所一覧</h2>
			<p class="p-safety__section-lead">七間町周辺の指定避難所・広域避難場所・一時避難場所をご確認ください。事前に場所と経路を把握しておきましょう。</p>
			<div class="p-safety__shelter-grid">

				<div class="p-safety__shelter-card">
					<div class="p-safety__shelter-head">
						<span class="p-safety__shelter-type p-safety__shelter-type--primary">指定避難所</span>
						<h3 class="p-safety__shelter-name">静岡市立葵小学校</h3>
					</div>
					<!-- /.p-safety__shelter-head -->
					<dl class="p-safety__shelter-info">
						<dt class="p-safety__shelter-term">住所</dt><dd class="p-safety__shelter-desc">静岡市葵区鷹匠町6-1</dd>
						<dt class="p-safety__shelter-term">収容</dt><dd class="p-safety__shelter-desc">約500人</dd>
						<dt class="p-safety__shelter-term">備考</dt><dd class="p-safety__shelter-desc">耐震構造・給水タンクあり</dd>
					</dl>
					<!-- /.p-safety__shelter-info -->
				</div>
				<!-- /.p-safety__shelter-card -->

				<div class="p-safety__shelter-card">
					<div class="p-safety__shelter-head">
						<span class="p-safety__shelter-type p-safety__shelter-type--primary">広域避難場所</span>
						<h3 class="p-safety__shelter-name">駿府城公園</h3>
					</div>
					<!-- /.p-safety__shelter-head -->
					<dl class="p-safety__shelter-info">
						<dt class="p-safety__shelter-term">住所</dt><dd class="p-safety__shelter-desc">静岡市葵区駿府城公園</dd>
						<dt class="p-safety__shelter-term">収容</dt><dd class="p-safety__shelter-desc">大規模</dd>
						<dt class="p-safety__shelter-term">備考</dt><dd class="p-safety__shelter-desc">広域避難場所・ヘリポートあり</dd>
					</dl>
					<!-- /.p-safety__shelter-info -->
				</div>
				<!-- /.p-safety__shelter-card -->

				<div class="p-safety__shelter-card">
					<div class="p-safety__shelter-head">
						<span class="p-safety__shelter-type p-safety__shelter-type--primary">指定避難所</span>
						<h3 class="p-safety__shelter-name">静岡市役所 葵区役所</h3>
					</div>
					<!-- /.p-safety__shelter-head -->
					<dl class="p-safety__shelter-info">
						<dt class="p-safety__shelter-term">住所</dt><dd class="p-safety__shelter-desc">静岡市葵区追手町5-1</dd>
						<dt class="p-safety__shelter-term">収容</dt><dd class="p-safety__shelter-desc">約300人</dd>
						<dt class="p-safety__shelter-term">備考</dt><dd class="p-safety__shelter-desc">防災倉庫併設</dd>
					</dl>
					<!-- /.p-safety__shelter-info -->
				</div>
				<!-- /.p-safety__shelter-card -->

				<div class="p-safety__shelter-card">
					<div class="p-safety__shelter-head">
						<span class="p-safety__shelter-type">一時避難場所</span>
						<h3 class="p-safety__shelter-name">静岡市民文化会館</h3>
					</div>
					<!-- /.p-safety__shelter-head -->
					<dl class="p-safety__shelter-info">
						<dt class="p-safety__shelter-term">住所</dt><dd class="p-safety__shelter-desc">静岡市葵区駿府町2-90</dd>
						<dt class="p-safety__shelter-term">収容</dt><dd class="p-safety__shelter-desc">約800人</dd>
						<dt class="p-safety__shelter-term">備考</dt><dd class="p-safety__shelter-desc">屋内避難可</dd>
					</dl>
					<!-- /.p-safety__shelter-info -->
				</div>
				<!-- /.p-safety__shelter-card -->

				<div class="p-safety__shelter-card">
					<div class="p-safety__shelter-head">
						<span class="p-safety__shelter-type p-safety__shelter-type--primary">指定避難所</span>
						<h3 class="p-safety__shelter-name">静岡市立中央体育館</h3>
					</div>
					<!-- /.p-safety__shelter-head -->
					<dl class="p-safety__shelter-info">
						<dt class="p-safety__shelter-term">住所</dt><dd class="p-safety__shelter-desc">静岡市葵区駿府町2-80</dd>
						<dt class="p-safety__shelter-term">収容</dt><dd class="p-safety__shelter-desc">約600人</dd>
						<dt class="p-safety__shelter-term">備考</dt><dd class="p-safety__shelter-desc">毛布・食料備蓄あり</dd>
					</dl>
					<!-- /.p-safety__shelter-info -->
				</div>
				<!-- /.p-safety__shelter-card -->

			</div>
			<!-- /.p-safety__shelter-grid -->
			<p class="p-safety__shelter-note">※ 最新の避難場所情報は、静岡市公式サイトでご確認ください。</p>
		</div>
		<!-- /.p-safety__section-inner -->
	</section>
	<!-- /.p-safety__section -->

	<!-- ─── 地震・津波 ── -->
	<section class="p-safety__section" id="safety-earthquake" role="tabpanel" hidden aria-labelledby="safety-earthquake-title">
		<div class="p-safety__section-inner">
			<h2 class="p-safety__section-title" id="safety-earthquake-title">地震・津波への備え</h2>

			<!-- 南海トラフコールアウト -->
			<div class="p-safety__callout p-safety__callout--quake">
				<h3 class="p-safety__callout-title">南海トラフ巨大地震について</h3>
				<p class="p-safety__callout-text">静岡県は南海トラフ巨大地震の想定震源域に位置しています。マグニチュード9クラスの巨大地震が今後30年以内に70〜80％の確率で発生すると予測されています。静岡市葵区では最大震度7が想定されています。</p>
			</div>
			<!-- /.p-safety__callout -->

			<div class="p-safety__fuji-cards">
			<!-- 地震が起きたら -->
			<div class="p-safety__fuji-card">
				<h3 class="p-safety__fuji-card-title">地震が起きたら</h3>
				<ol class="p-safety__actions p-safety__actions--quake">
					<li class="p-safety__action">
						<span class="p-safety__action-num" aria-hidden="true">1</span>
						<span class="p-safety__action-text"><strong>まず身を守る：</strong>テーブルの下などに隠れ、頭を守ります。揺れが収まるまで動かないでください。</span>
					</li>
					<li class="p-safety__action">
						<span class="p-safety__action-num" aria-hidden="true">2</span>
						<span class="p-safety__action-text"><strong>火の始末：</strong>揺れが収まったら、ガスの元栓を閉め、ブレーカーを落とします。</span>
					</li>
					<li class="p-safety__action">
						<span class="p-safety__action-num" aria-hidden="true">3</span>
						<span class="p-safety__action-text"><strong>避難経路確保：</strong>ドアを開けて出口を確保します。エレベーターは使わないでください。</span>
					</li>
					<li class="p-safety__action">
						<span class="p-safety__action-num" aria-hidden="true">4</span>
						<span class="p-safety__action-text"><strong>津波警報確認：</strong>海岸部にいる場合は、すぐに高台へ避難してください。</span>
					</li>
					<li class="p-safety__action">
						<span class="p-safety__action-num" aria-hidden="true">5</span>
						<span class="p-safety__action-text"><strong>情報収集：</strong>ラジオ、テレビ、防災無線で正確な情報を得ましょう。SNSのデマ情報に注意。</span>
					</li>
				</ol>
			</div>
			<!-- /.p-safety__fuji-card -->

			<!-- 津波について -->
			<div class="p-safety__fuji-card">
				<h3 class="p-safety__fuji-card-title">津波について</h3>
				<p class="p-safety__tsunami-text">静岡市葵区の七間町周辺は海岸から約15km内陸に位置していますが、南海トラフ巨大地震では安倍川を通じて津波が遡上する可能性があります。河川沿いにいる場合は特に注意が必要です。</p>
				<a class="p-safety__fuji-link p-safety__fuji-link--quake" href="https://www.city.shizuoka.lg.jp/kurashi_tetsuzuki/bosai/tsunami.html" target="_blank" rel="noopener noreferrer">
					<span class="p-safety__fuji-link-label">静岡市津波ハザードマップを確認</span>
					<svg class="p-safety__fuji-link-icon" aria-hidden="true" focusable="false" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6M15 3h6v6M10 14L21 3"/></svg>
				</a>
			</div>
			<!-- /.p-safety__fuji-card -->
			</div>
			<!-- /.p-safety__fuji-cards -->
		</div>
		<!-- /.p-safety__section-inner -->
	</section>
	<!-- /.p-safety__section -->

	<!-- ─── 富士山噴火 ── -->
	<section class="p-safety__section" id="safety-fuji" role="tabpanel" hidden aria-labelledby="safety-fuji-title">
		<div class="p-safety__section-inner">
			<h2 class="p-safety__section-title" id="safety-fuji-title">富士山噴火への備え</h2>

			<!-- 活火山コールアウト -->
			<div class="p-safety__callout">
				<h3 class="p-safety__callout-title">富士山は活火山です</h3>
				<p class="p-safety__callout-text">富士山の最後の噴火は1707年（宝永噴火）。科学的にはいつ噴火してもおかしくないとされています。静岡市は富士山から約60kmの位置にあり、降灰の影響を受ける可能性があります。</p>
			</div>
			<!-- /.p-safety__callout -->

			<div class="p-safety__fuji-cards">

				<!-- 想定される影響 -->
				<div class="p-safety__fuji-card">
					<h3 class="p-safety__fuji-card-title">想定される影響</h3>
					<ul class="p-safety__impacts">
						<li class="p-safety__impact">
							<span class="p-safety__impact-badge">降灰</span>
							<span class="p-safety__impact-text">風向きによっては数cm〜数十cmの火山灰が降り積もる可能性。交通麻痺、停電、水道汚染の恐れ。</span>
						</li>
						<li class="p-safety__impact">
							<span class="p-safety__impact-badge">空振</span>
							<span class="p-safety__impact-text">噴火に伴う空振（空気の振動）により、窓ガラスが割れる可能性。</span>
						</li>
						<li class="p-safety__impact">
							<span class="p-safety__impact-badge">泥流</span>
							<span class="p-safety__impact-text">大雨時に火山灰が泥流となって流れ下る可能性（富士川流域）。</span>
						</li>
					</ul>
				</div>
				<!-- /.p-safety__fuji-card -->

				<!-- 噴火時の行動 -->
				<div class="p-safety__fuji-card">
					<h3 class="p-safety__fuji-card-title">噴火時の行動</h3>
					<ol class="p-safety__actions">
						<li class="p-safety__action">
							<span class="p-safety__action-num" aria-hidden="true">1</span>
							<span class="p-safety__action-text">テレビ・ラジオで正確な情報を収集する</span>
						</li>
						<li class="p-safety__action">
							<span class="p-safety__action-num" aria-hidden="true">2</span>
							<span class="p-safety__action-text">屋内にいる場合は窓を閉め、換気を止める</span>
						</li>
						<li class="p-safety__action">
							<span class="p-safety__action-num" aria-hidden="true">3</span>
							<span class="p-safety__action-text">外出時はマスク・ゴーグルを着用し、灰を吸い込まない</span>
						</li>
						<li class="p-safety__action">
							<span class="p-safety__action-num" aria-hidden="true">4</span>
							<span class="p-safety__action-text">降灰中の車の運転は極力避ける（スリップ危険）</span>
						</li>
						<li class="p-safety__action">
							<span class="p-safety__action-num" aria-hidden="true">5</span>
							<span class="p-safety__action-text">灰の掃除は水で流さず、乾いた状態で集める</span>
						</li>
					</ol>
				</div>
				<!-- /.p-safety__fuji-card -->

			</div>
			<!-- /.p-safety__fuji-cards -->

			<!-- 内閣府リンク -->
			<a class="p-safety__fuji-link" href="https://www.bousai.go.jp/kazan/fujisan-kyougikai/index.html" target="_blank" rel="noopener noreferrer">
				<span class="p-safety__fuji-link-label">内閣府 富士山噴火広域避難計画</span>
				<span class="p-safety__fuji-link-desc">詳細な避難計画・ハザードマップを確認</span>
				<svg class="p-safety__fuji-link-icon" aria-hidden="true" focusable="false" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6M15 3h6v6M10 14L21 3"/></svg>
			</a>
			<!-- /.p-safety__fuji-link -->

		</div>
		<!-- /.p-safety__section-inner -->
	</section>
	<!-- /.p-safety__section -->

	<!-- ─── 日頃の備え ── -->
	<section class="p-safety__section p-safety__section--washi" id="safety-daily" role="tabpanel" hidden aria-labelledby="safety-daily-title">
		<div class="p-safety__section-inner">
			<h2 class="p-safety__section-title" id="safety-daily-title">日頃の備え</h2>

			<!-- 非常用持ち出し袋の準備 -->
			<div class="p-safety__daily-card">
				<h3 class="p-safety__daily-card-title">非常用持ち出し袋の準備</h3>
				<div class="p-safety__daily-grid">
					<div class="p-safety__daily-col">
						<h4 class="p-safety__daily-sub">必須アイテム</h4>
						<ul class="p-safety__bullet-list">
							<li class="p-safety__bullet-item">飲料水（3日分以上）</li>
							<li class="p-safety__bullet-item">非常食（缶詰・乾パン・レトルト食品）</li>
							<li class="p-safety__bullet-item">懐中電灯・モバイルバッテリー</li>
							<li class="p-safety__bullet-item">救急箱・常備薬</li>
							<li class="p-safety__bullet-item">現金・身分証明書のコピー</li>
						</ul>
					</div>
					<div class="p-safety__daily-col">
						<h4 class="p-safety__daily-sub">あると便利</h4>
						<ul class="p-safety__bullet-list">
							<li class="p-safety__bullet-item">ラジオ（手回し充電式）</li>
							<li class="p-safety__bullet-item">ウェットティッシュ・トイレットペーパー</li>
							<li class="p-safety__bullet-item">軍手・マスク・ゴーグル</li>
							<li class="p-safety__bullet-item">ブルーシート・毛布</li>
							<li class="p-safety__bullet-item">筆記用具・ホイッスル</li>
						</ul>
					</div>
				</div>
			</div>
			<!-- /.p-safety__daily-card -->

			<!-- 家族で決めておくこと -->
			<div class="p-safety__daily-card">
				<h3 class="p-safety__daily-card-title">家族で決めておくこと</h3>
				<ul class="p-safety__family-list">
					<li class="p-safety__family-item">
						<svg class="p-safety__family-icon" aria-hidden="true" focusable="false" width="14" height="14" viewBox="0 0 14 14"><rect x="0" y="0" width="14" height="14" rx="2" fill="#dc2626"/></svg>
						<span class="p-safety__family-text">集合場所（避難場所と自宅以外の待ち合わせ場所）</span>
					</li>
					<li class="p-safety__family-item">
						<svg class="p-safety__family-icon" aria-hidden="true" focusable="false" width="14" height="14" viewBox="0 0 14 14"><rect x="0" y="0" width="14" height="14" rx="2" fill="#dc2626"/></svg>
						<span class="p-safety__family-text">連絡方法（災害用伝言ダイヤル171の使い方）</span>
					</li>
					<li class="p-safety__family-item">
						<svg class="p-safety__family-icon" aria-hidden="true" focusable="false" width="14" height="14" viewBox="0 0 14 14"><rect x="0" y="0" width="14" height="14" rx="2" fill="#dc2626"/></svg>
						<span class="p-safety__family-text">避難ルート（自宅から避難場所までの道順）</span>
					</li>
					<li class="p-safety__family-item">
						<svg class="p-safety__family-icon" aria-hidden="true" focusable="false" width="14" height="14" viewBox="0 0 14 14"><rect x="0" y="0" width="14" height="14" rx="2" fill="#dc2626"/></svg>
						<span class="p-safety__family-text">役割分担（誰が何を持ち出すか）</span>
					</li>
					<li class="p-safety__family-item">
						<svg class="p-safety__family-icon" aria-hidden="true" focusable="false" width="14" height="14" viewBox="0 0 14 14"><rect x="0" y="0" width="14" height="14" rx="2" fill="#dc2626"/></svg>
						<span class="p-safety__family-text">親戚・知人の連絡先リスト</span>
					</li>
				</ul>
			</div>
			<!-- /.p-safety__daily-card -->

			<!-- 家の安全対策 -->
			<div class="p-safety__daily-card">
				<h3 class="p-safety__daily-card-title">家の安全対策</h3>
				<ul class="p-safety__bullet-list">
					<li class="p-safety__bullet-item">家具の転倒防止（L字金具・突っ張り棒）</li>
					<li class="p-safety__bullet-item">ガラスの飛散防止フィルム</li>
					<li class="p-safety__bullet-item">感震ブレーカーの設置</li>
					<li class="p-safety__bullet-item">食器棚の上に重い物を置かない</li>
					<li class="p-safety__bullet-item">避難経路の確保（廊下・玄関に物を置かない）</li>
				</ul>
			</div>
			<!-- /.p-safety__daily-card -->
		</div>
		<!-- /.p-safety__section-inner -->
	</section>
	<!-- /.p-safety__section -->

	<!-- ─── 公式情報 ── -->
	<section class="p-safety__section p-safety__section--washi" id="safety-links" role="tabpanel" hidden aria-labelledby="safety-links-title">
		<div class="p-safety__section-inner">
			<h2 class="p-safety__section-title" id="safety-links-title">公式情報・外部リンク</h2>
			<p class="p-safety__section-lead">防災に関する最新・正確な情報は、以下の公式サイトでご確認ください。</p>
			<div class="p-safety__official-grid">

				<a class="p-safety__official-card" href="https://www.city.shizuoka.lg.jp/kurashi_tetsuzuki/bosai/index.html" target="_blank" rel="noopener noreferrer">
					<div class="p-safety__official-body">
						<h3 class="p-safety__official-title">静岡市 防災情報</h3>
						<p class="p-safety__official-desc">避難場所・ハザードマップ・防災計画</p>
					</div>
					<svg class="p-safety__official-icon" aria-hidden="true" focusable="false" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6M15 3h6v6M10 14L21 3"/></svg>
				</a>

				<a class="p-safety__official-card" href="https://www.pref.shizuoka.jp/bosaikinkyu/index.html" target="_blank" rel="noopener noreferrer">
					<div class="p-safety__official-body">
						<h3 class="p-safety__official-title">静岡県 地震防災センター</h3>
						<p class="p-safety__official-desc">地震想定・被害予測・津波情報</p>
					</div>
					<svg class="p-safety__official-icon" aria-hidden="true" focusable="false" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6M15 3h6v6M10 14L21 3"/></svg>
				</a>

				<a class="p-safety__official-card" href="https://www.jma.go.jp/jma/index.html" target="_blank" rel="noopener noreferrer">
					<div class="p-safety__official-body">
						<h3 class="p-safety__official-title">気象庁 静岡県の防災情報</h3>
						<p class="p-safety__official-desc">天気予報・警報・地震情報</p>
					</div>
					<svg class="p-safety__official-icon" aria-hidden="true" focusable="false" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6M15 3h6v6M10 14L21 3"/></svg>
				</a>

				<a class="p-safety__official-card" href="https://www.bousai.go.jp/kazan/fujisan-kyougikai/" target="_blank" rel="noopener noreferrer">
					<div class="p-safety__official-body">
						<h3 class="p-safety__official-title">内閣府 富士山の大規模噴火対策</h3>
						<p class="p-safety__official-desc">富士山噴火広域避難計画</p>
					</div>
					<svg class="p-safety__official-icon" aria-hidden="true" focusable="false" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6M15 3h6v6M10 14L21 3"/></svg>
				</a>

				<a class="p-safety__official-card" href="https://www.city.shizuoka.lg.jp/kurashi_tetsuzuki/bosai/tsunami.html" target="_blank" rel="noopener noreferrer">
					<div class="p-safety__official-body">
						<h3 class="p-safety__official-title">静岡市 津波ハザードマップ</h3>
						<p class="p-safety__official-desc">津波浸水予測・避難ルート</p>
					</div>
					<svg class="p-safety__official-icon" aria-hidden="true" focusable="false" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6M15 3h6v6M10 14L21 3"/></svg>
				</a>

				<a class="p-safety__official-card" href="https://www.pref.shizuoka.jp/bosaikinkyu/sonae/fujisan.html" target="_blank" rel="noopener noreferrer">
					<div class="p-safety__official-body">
						<h3 class="p-safety__official-title">静岡県 富士山噴火対策</h3>
						<p class="p-safety__official-desc">降灰予測・避難計画・対策ガイド</p>
					</div>
					<svg class="p-safety__official-icon" aria-hidden="true" focusable="false" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6M15 3h6v6M10 14L21 3"/></svg>
				</a>

				<a class="p-safety__official-card" href="https://www.city.shizuoka.lg.jp/kurashi_tetsuzuki/bosai/mail.html" target="_blank" rel="noopener noreferrer">
					<div class="p-safety__official-body">
						<h3 class="p-safety__official-title">静岡市 防災アプリ「静岡市防災メール」</h3>
						<p class="p-safety__official-desc">緊急通報・避難情報のメール配信</p>
					</div>
					<svg class="p-safety__official-icon" aria-hidden="true" focusable="false" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6M15 3h6v6M10 14L21 3"/></svg>
				</a>

				<a class="p-safety__official-card" href="https://www.nhk.or.jp/kishou-saigai/" target="_blank" rel="noopener noreferrer">
					<div class="p-safety__official-body">
						<h3 class="p-safety__official-title">NHK 防災情報</h3>
						<p class="p-safety__official-desc">リアルタイム災害情報</p>
					</div>
					<svg class="p-safety__official-icon" aria-hidden="true" focusable="false" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6M15 3h6v6M10 14L21 3"/></svg>
				</a>

			</div>
			<!-- /.p-safety__official-grid -->
		</div>
		<!-- /.p-safety__section-inner -->
	</section>
	<!-- /.p-safety__section -->

</article>
<!-- /.p-safety -->

<?php get_footer(); ?>
