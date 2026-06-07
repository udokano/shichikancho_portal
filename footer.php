</main>
<!-- /.l-main -->

<?php get_template_part( 'template-parts/components/mobile-cta-bar' ); ?>

<footer class="l-footer" role="contentinfo">
	<div class="l-footer__inner">

		<div class="l-footer__grid">

			<!-- 左2カラム: ロゴ＋住所＋SNS -->
			<div class="l-footer__col l-footer__col--wide">
				<div class="l-footer__logo-wrap">
					<a class="l-footer__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="SHICHIKANCHO トップページへ">
						<img class="l-footer__logo-img" src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo/logo-white.png' ); ?>" alt="SHICHIKANCHO" width="84" height="56" loading="lazy">
					</a>
				</div>

				<div class="l-footer__sns" aria-label="SNSリンク">
					<a class="l-footer__sns-link" href="https://youtube.com/" target="_blank" rel="noopener noreferrer" aria-label="YouTube（新しいタブで開く）">
						<svg class="l-footer__sns-icon" aria-hidden="true" focusable="false"><use href="#icon-youtube"></use></svg>
					</a>
					<a class="l-footer__sns-link" href="https://facebook.com/" target="_blank" rel="noopener noreferrer" aria-label="Facebook（新しいタブで開く）">
						<svg class="l-footer__sns-icon" aria-hidden="true" focusable="false"><use href="#icon-facebook"></use></svg>
					</a>
					<a class="l-footer__sns-link" href="https://www.instagram.com/shichikencho/" target="_blank" rel="noopener noreferrer" aria-label="Instagram（新しいタブで開く）">
						<svg class="l-footer__sns-icon" aria-hidden="true" focusable="false"><use href="#icon-instagram"></use></svg>
					</a>
					<a class="l-footer__sns-link" href="https://pinterest.com/" target="_blank" rel="noopener noreferrer" aria-label="Pinterest（新しいタブで開く）">
						<svg class="l-footer__sns-icon" aria-hidden="true" focusable="false"><use href="#icon-pinterest"></use></svg>
					</a>
				</div>
				<!-- /.l-footer__sns -->

				<!-- NAP（MEO対応） -->
				<address class="l-footer__nap" itemscope itemtype="https://schema.org/Organization">
					<meta itemprop="name" content="七間町 町内会">

					<div class="l-footer__stats-list">
						<div class="l-footer__stats-row">
							<svg class="l-footer__stats-icon" aria-hidden="true" focusable="false"><use href="#icon-users-solid"></use></svg>
							<span>人口 <strong class="l-footer__stats-value">7,000</strong>人</span>
						</div>
						<div class="l-footer__stats-row">
							<svg class="l-footer__stats-icon" aria-hidden="true" focusable="false"><use href="#icon-home-solid"></use></svg>
							<span>世帯数 <strong class="l-footer__stats-value">7,000</strong>世帯</span>
						</div>
						<p class="l-footer__stats-note">（更新年月：<?php echo esc_html( date( 'Y' ) ); ?>年●月）</p>
					</div>

					<span class="l-footer__nap-item" itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
						<meta itemprop="postalCode" content="420-0035">
						<meta itemprop="addressRegion" content="静岡県">
						<meta itemprop="addressLocality" content="静岡市葵区">
						<meta itemprop="streetAddress" content="七間町17-9">
						七間町 町内会　〒420-0035<br>
						静岡県静岡市葵区七間町17-9
					</span>
				</address>
			</div>
			<!-- /.l-footer__col--wide -->

			<div class="l-footer__col">
				<nav class="l-footer__col-nav" aria-label="メインナビゲーション">
					<a class="l-footer__col-nav-link" href="<?php echo esc_url( home_url( '/tourism/' ) ); ?>">観光情報</a>
					<a class="l-footer__col-nav-link" href="<?php echo esc_url( home_url( '/shops/' ) ); ?>">商店街のお店</a>
					<a class="l-footer__col-nav-link" href="<?php echo esc_url( home_url( '/access/' ) ); ?>">アクセス</a>
					<a class="l-footer__col-nav-link" href="<?php echo esc_url( home_url( '/about/' ) ); ?>">町の紹介</a>
					<a class="l-footer__col-nav-link" href="<?php echo esc_url( home_url( '/cinema/' ) ); ?>">映画の町</a>
					<a class="l-footer__col-nav-link" href="<?php echo esc_url( home_url( '/walk/' ) ); ?>">町をめぐる</a>
					<a class="l-footer__col-nav-link" href="<?php echo esc_url( home_url( '/living/' ) ); ?>">町に住む</a>
					<a class="l-footer__col-nav-link" href="<?php echo esc_url( home_url( '/learn/' ) ); ?>">町でまなぶ</a>
					<a class="l-footer__col-nav-link" href="<?php echo esc_url( home_url( '/work/' ) ); ?>">町で働く</a>
					<a class="l-footer__col-nav-link" href="<?php echo esc_url( home_url( '/business/' ) ); ?>">町で商い</a>
					<a class="l-footer__col-nav-link" href="<?php echo esc_url( home_url( '/gallery/' ) ); ?>">町のギャラリー</a>
					<a class="l-footer__col-nav-link" href="<?php echo esc_url( home_url( '/guide/' ) ); ?>">くらしガイド</a>
					<a class="l-footer__col-nav-link" href="<?php echo esc_url( home_url( '/safety/' ) ); ?>">いのちを守る</a>
				</nav>
			</div>
			<!-- /.l-footer__col -->

			<div class="l-footer__col l-footer__col--sub">
				<nav class="l-footer__col-nav" aria-label="サブナビゲーション1">
					<a class="l-footer__col-nav-link" href="<?php echo esc_url( home_url( '/info/' ) ); ?>">インフォメーション</a>
					<a class="l-footer__col-nav-link" href="<?php echo esc_url( home_url( '/residents/' ) ); ?>">お隣さんの話</a>
					<a class="l-footer__col-nav-link" href="<?php echo esc_url( home_url( '/sponsor/' ) ); ?>">スポンサー募集</a>
					<a class="l-footer__col-nav-link" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">お問い合わせ</a>
					<a class="l-footer__col-nav-link" href="<?php echo esc_url( home_url( '/terms/' ) ); ?>">当サイトのご利用にあたって</a>
				</nav>
			</div>
			<!-- /.l-footer__col -->

			<div class="l-footer__col l-footer__col--sub">
				<nav class="l-footer__col-nav" aria-label="サブナビゲーション2">
					<a class="l-footer__col-nav-link" href="<?php echo esc_url( home_url( '/events/' ) ); ?>">イベント</a>
					<a class="l-footer__col-nav-link" href="<?php echo esc_url( home_url( '/column/' ) ); ?>">七ぶらコラム</a>
					<a class="l-footer__col-nav-link" href="<?php echo esc_url( home_url( '/links/' ) ); ?>">関連リンク</a>
					<a class="l-footer__col-nav-link" href="<?php echo esc_url( home_url( '/privacy/' ) ); ?>">プライバシーポリシー</a>
					<a class="l-footer__col-nav-link" href="<?php echo esc_url( home_url( '/company/' ) ); ?>">運営会社</a>
				</nav>
			</div>
			<!-- /.l-footer__col -->

		</div>
		<!-- /.l-footer__grid -->

	</div>
	<!-- /.l-footer__inner -->

	<div class="l-footer__bottom">
		<p class="l-footer__copy">
			<small>&copy; <?php echo esc_html( date( 'Y' ) ); ?> 七間町町内会. All rights reserved.</small>
		</p>
	</div>
	<!-- /.l-footer__bottom -->
</footer>
<!-- /.l-footer -->

<!-- Google Translate 非表示ウィジェット（クッキー制御で翻訳を作動させるため必要） -->
<div id="google_translate_element" style="display:none" aria-hidden="true"></div>
<script>
function googleTranslateElementInit() {
	new google.translate.TranslateElement({
		pageLanguage: 'ja',
		includedLanguages: 'en',
		autoDisplay: false
	}, 'google_translate_element');
}
</script>
<script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" async></script>
<?php wp_footer(); ?>
</body>
</html>
