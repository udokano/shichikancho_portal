<?php
/** スポンサー・寄付募集ページ */
get_header();
?>

<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>

<article class="p-sponsor">

	<?php
		get_template_part( 'template-parts/components/page-hero', null, [
			'title' => 'スポンサー・寄付募集',
			'sub'   => '七間町のまちづくりを、一緒に支えてくださるパートナーを募集しています。',
		] );
		?>

	<!-- ─── スポンサーになるメリット ── -->
	<section class="p-sponsor__benefits" aria-labelledby="sponsor-benefits-title">
		<div class="p-sponsor__benefits-inner">
			<header class="p-sponsor__section-head">
				<h2 class="p-sponsor__section-title" id="sponsor-benefits-title">スポンサーになるメリット</h2>
				<p class="p-sponsor__section-lead">七間町公式サイトは地域住民・移住検討者・観光客に広くリーチしています。スポンサーとしてご協力いただくことで、以下のメリットがあります。</p>
			</header>

			<div class="p-sponsor__benefits-grid">

				<div class="p-sponsor__benefit">
					<span class="p-sponsor__benefit-icon" aria-hidden="true">
						<svg class="p-sponsor__benefit-icon-svg" aria-hidden="true" focusable="false"><use href="#icon-heart"></use></svg>
					</span>
					<h3 class="p-sponsor__benefit-title">地域への貢献</h3>
					<p class="p-sponsor__benefit-text">七間町の活性化・まちづくりに直接貢献できます</p>
				</div>
				<!-- /.p-sponsor__benefit -->

				<div class="p-sponsor__benefit">
					<span class="p-sponsor__benefit-icon" aria-hidden="true">
						<svg class="p-sponsor__benefit-icon-svg" aria-hidden="true" focusable="false"><use href="#icon-star"></use></svg>
					</span>
					<h3 class="p-sponsor__benefit-title">ブランド認知向上</h3>
					<p class="p-sponsor__benefit-text">地域住民・観光客・移住検討者への認知度が向上します</p>
				</div>
				<!-- /.p-sponsor__benefit -->

				<div class="p-sponsor__benefit">
					<span class="p-sponsor__benefit-icon" aria-hidden="true">
						<svg class="p-sponsor__benefit-icon-svg" aria-hidden="true" focusable="false"><use href="#icon-users-solid"></use></svg>
					</span>
					<h3 class="p-sponsor__benefit-title">ネットワーク</h3>
					<p class="p-sponsor__benefit-text">地元企業・店舗・行政とのつながりが生まれます</p>
				</div>
				<!-- /.p-sponsor__benefit -->

			</div>
			<!-- /.p-sponsor__benefits-grid -->
		</div>
		<!-- /.p-sponsor__benefits-inner -->
	</section>
	<!-- /.p-sponsor__benefits -->

	<!-- ─── スポンサープラン ── -->
	<section class="p-sponsor__plans" aria-labelledby="sponsor-plans-title">
		<div class="p-sponsor__plans-inner">
			<header class="p-sponsor__section-head">
				<h2 class="p-sponsor__section-title" id="sponsor-plans-title">スポンサープラン</h2>
				<p class="p-sponsor__section-lead">貴社の目的・予算に合わせてお選びいただけます。全プラン共通で専用ページを作成し、貴社の魅力を丁寧に紹介します。</p>
			</header>

			<div class="p-sponsor__plans-grid">

				<!-- プラチナ -->
				<div class="p-sponsor__plan p-sponsor__plan--featured">
					<span class="p-sponsor__plan-badge">おすすめ</span>
					<h3 class="p-sponsor__plan-name">プラチナ</h3>
					<p class="p-sponsor__plan-price">120,000<span class="p-sponsor__plan-price-unit">円/年</span></p>
					<p class="p-sponsor__plan-monthly">月あたり約10,000円</p>
					<ul class="p-sponsor__plan-features" role="list">
						<li class="p-sponsor__plan-feature"><svg class="p-sponsor__plan-feature-icon" aria-hidden="true" focusable="false"><use href="#icon-check"></use></svg>サイトトップにロゴ掲載（通年）</li>
						<li class="p-sponsor__plan-feature"><svg class="p-sponsor__plan-feature-icon" aria-hidden="true" focusable="false"><use href="#icon-check"></use></svg>専用紹介ページ作成</li>
						<li class="p-sponsor__plan-feature"><svg class="p-sponsor__plan-feature-icon" aria-hidden="true" focusable="false"><use href="#icon-check"></use></svg>「おすすめ店舗」掲載権（通年）</li>
						<li class="p-sponsor__plan-feature"><svg class="p-sponsor__plan-feature-icon" aria-hidden="true" focusable="false"><use href="#icon-check"></use></svg>「おすすめ求人」掲載権（通年）</li>
						<li class="p-sponsor__plan-feature"><svg class="p-sponsor__plan-feature-icon" aria-hidden="true" focusable="false"><use href="#icon-check"></use></svg>「おすすめ物件」掲載権（通年）</li>
					</ul>
					<a class="c-btn c-btn--primary p-sponsor__plan-cta" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">お問い合わせ</a>
				</div>
				<!-- /.p-sponsor__plan -->

				<!-- ゴールド -->
				<div class="p-sponsor__plan">
					<h3 class="p-sponsor__plan-name">ゴールド</h3>
					<p class="p-sponsor__plan-price">72,000<span class="p-sponsor__plan-price-unit">円/年</span></p>
					<p class="p-sponsor__plan-monthly">月あたり約6,000円</p>
					<ul class="p-sponsor__plan-features" role="list">
						<li class="p-sponsor__plan-feature"><svg class="p-sponsor__plan-feature-icon" aria-hidden="true" focusable="false"><use href="#icon-check"></use></svg>サイトトップにロゴ掲載（通年）</li>
						<li class="p-sponsor__plan-feature"><svg class="p-sponsor__plan-feature-icon" aria-hidden="true" focusable="false"><use href="#icon-check"></use></svg>専用紹介ページ作成</li>
						<li class="p-sponsor__plan-feature"><svg class="p-sponsor__plan-feature-icon" aria-hidden="true" focusable="false"><use href="#icon-check"></use></svg>「おすすめ」掲載権 × 2枚分<br><span class="p-sponsor__plan-feature-note">（店舗・求人・物件から選択 / 6ヶ月）</span></li>
					</ul>
					<a class="c-btn c-btn--outline p-sponsor__plan-cta" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">お問い合わせ</a>
				</div>
				<!-- /.p-sponsor__plan -->

				<!-- シルバー -->
				<div class="p-sponsor__plan">
					<h3 class="p-sponsor__plan-name">シルバー</h3>
					<p class="p-sponsor__plan-price">36,000<span class="p-sponsor__plan-price-unit">円/年</span></p>
					<p class="p-sponsor__plan-monthly">月あたり約3,000円</p>
					<ul class="p-sponsor__plan-features" role="list">
						<li class="p-sponsor__plan-feature"><svg class="p-sponsor__plan-feature-icon" aria-hidden="true" focusable="false"><use href="#icon-check"></use></svg>専用紹介ページ作成</li>
						<li class="p-sponsor__plan-feature"><svg class="p-sponsor__plan-feature-icon" aria-hidden="true" focusable="false"><use href="#icon-check"></use></svg>「おすすめ」掲載権 × 1枚分<br><span class="p-sponsor__plan-feature-note">（店舗・求人・物件から選択 / 3ヶ月）</span></li>
					</ul>
					<a class="c-btn c-btn--outline p-sponsor__plan-cta" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">お問い合わせ</a>
				</div>
				<!-- /.p-sponsor__plan -->

			</div>
			<!-- /.p-sponsor__plans-grid -->

			<!-- ─── 単品オプション ── -->
			<div class="p-sponsor__addons">
				<h3 class="p-sponsor__addons-title">単品オプション（プラン外）</h3>
				<div class="p-sponsor__addons-table-wrap">
					<table class="p-sponsor__addons-table">
						<thead>
							<tr>
								<th scope="col">メニュー</th>
								<th scope="col">内容</th>
								<th scope="col">料金</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>おすすめ店舗掲載</td>
								<td>商店街のお店ページのPICK UP枠に掲載</td>
								<td>5,000円/月</td>
							</tr>
							<tr>
								<td>おすすめ求人掲載</td>
								<td>町で働くページの注目求人枠に掲載</td>
								<td>5,000円/月</td>
							</tr>
							<tr>
								<td>おすすめ物件掲載</td>
								<td>町で商いページのPICK UP枠に掲載</td>
								<td>5,000円/月</td>
							</tr>
							<tr>
								<td>トップロゴ掲載</td>
								<td>サイトトップページにロゴを掲載</td>
								<td>10,000円/月</td>
							</tr>
						</tbody>
					</table>
				</div>
				<!-- /.p-sponsor__addons-table-wrap -->
				<p class="p-sponsor__addons-note">※ 最低契約期間: 1ヶ月から。年間契約の場合は10%割引が適用されます。</p>
			</div>
			<!-- /.p-sponsor__addons -->
		</div>
		<!-- /.p-sponsor__plans-inner -->
	</section>
	<!-- /.p-sponsor__plans -->

	<!-- ─── 寄付で応援する ── -->
	<section class="p-sponsor__donate" aria-labelledby="sponsor-donate-title">
		<div class="p-sponsor__donate-inner">
			<header class="p-sponsor__section-head">
				<h2 class="p-sponsor__section-title" id="sponsor-donate-title">寄付で応援する</h2>
				<p class="p-sponsor__section-lead">スポンサープラン以外にも、七間町のまちづくりを応援する方法があります。<br>いただいた寄付は、イベント運営・商店街美化・地域情報発信に活用させていただきます。</p>
			</header>

			<div class="p-sponsor__donate-grid">
				<div class="p-sponsor__donate-card">
					<p class="p-sponsor__donate-amount">1,000<span class="p-sponsor__donate-amount-unit">円</span></p>
					<p class="p-sponsor__donate-label">お気持ち応援</p>
				</div>
				<div class="p-sponsor__donate-card">
					<p class="p-sponsor__donate-amount">5,000<span class="p-sponsor__donate-amount-unit">円</span></p>
					<p class="p-sponsor__donate-label">サポーター応援</p>
				</div>
				<div class="p-sponsor__donate-card">
					<p class="p-sponsor__donate-amount">10,000<span class="p-sponsor__donate-amount-unit">円〜</span></p>
					<p class="p-sponsor__donate-label">パトロン応援</p>
				</div>
			</div>
			<!-- /.p-sponsor__donate-grid -->

			<div class="p-sponsor__donate-usage">
				<h3 class="p-sponsor__donate-usage-title">寄付の使いみち</h3>
				<ul class="p-sponsor__donate-usage-list" role="list">
					<li>商店街イベントの運営費（朝市・季節イベント等）</li>
					<li>商店街の美化・緑化活動</li>
					<li>地域情報サイトの運営・コンテンツ制作</li>
					<li>地域交流・コミュニティ活動の支援</li>
				</ul>
			</div>
			<!-- /.p-sponsor__donate-usage -->

			<div class="p-sponsor__donate-cta">
				<a class="c-btn c-btn--primary" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">
					<svg class="c-btn__icon" aria-hidden="true" focusable="false"><use href="#icon-mail"></use></svg>
					寄付・スポンサーのお問い合わせ
				</a>
			</div>
			<!-- /.p-sponsor__donate-cta -->
		</div>
		<!-- /.p-sponsor__donate-inner -->
	</section>
	<!-- /.p-sponsor__donate -->

	<!-- ─── よくあるご質問 ── -->
	<?php
	$sponsor_faqs = [
		[
			'q' => '契約期間はどのくらいですか？',
			'a' => 'プラン契約は年単位、単品オプションは最低1ヶ月からご利用いただけます。年間契約の場合は10%割引が適用されます。',
		],
		[
			'q' => 'ロゴや原稿の入稿はどうすればいいですか？',
			'a' => 'お申し込み後、担当者より入稿フォーマットをご案内します。ロゴデータ（PNG/SVG推奨）と紹介文をご用意ください。',
		],
		[
			'q' => '個人でもスポンサーになれますか？',
			'a' => 'はい、個人・法人問わずご利用いただけます。寄付は個人の方にもおすすめです。',
		],
		[
			'q' => '途中解約はできますか？',
			'a' => 'はい、いつでも解約可能です。月割りでの返金はありませんが、契約月末までは掲載が継続されます。',
		],
	];
	?>
	<section class="p-sponsor__faq" aria-labelledby="sponsor-faq-title" itemscope itemtype="https://schema.org/FAQPage">
		<div class="p-sponsor__faq-inner">
			<header class="p-sponsor__section-head">
				<h2 class="p-sponsor__section-title" id="sponsor-faq-title">よくあるご質問</h2>
			</header>

			<div class="p-sponsor__faq-list">
				<?php foreach ( $sponsor_faqs as $faq ) : ?>
				<div class="p-sponsor__faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
					<h3 class="p-sponsor__faq-q" itemprop="name">Q. <?php echo esc_html( $faq['q'] ); ?></h3>
					<div itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
						<p class="p-sponsor__faq-a" itemprop="text"><?php echo esc_html( $faq['a'] ); ?></p>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
			<!-- /.p-sponsor__faq-list -->
		</div>
		<!-- /.p-sponsor__faq-inner -->
	</section>
	<!-- /.p-sponsor__faq -->

	<?php
	// FAQPage JSON-LD
	$faq_jsonld = [
		'@context'   => 'https://schema.org',
		'@type'      => 'FAQPage',
		'mainEntity' => array_map( function ( $f ) {
			return [
				'@type'          => 'Question',
				'name'           => $f['q'],
				'acceptedAnswer' => [
					'@type' => 'Answer',
					'text'  => $f['a'],
				],
			];
		}, $sponsor_faqs ),
	];
	?>
	<script type="application/ld+json"><?php echo wp_json_encode( $faq_jsonld, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ); ?></script>

</article>
<!-- /.p-sponsor -->

<?php get_footer(); ?>
