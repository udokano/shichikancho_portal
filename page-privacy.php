<?php
/** プライバシーポリシーページ */
get_header();
?>

<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>

<article class="p-privacy">

	<?php
	get_template_part( 'template-parts/components/page-hero', null, [
		'title' => 'プライバシーポリシー',
		'sub'   => '個人情報の取り扱いについて',
	] );
	?>

	<div class="p-privacy__body">
		<div class="p-privacy__inner">

			<section class="p-privacy__section">
				<div class="p-privacy__section-num">1</div>
				<div class="p-privacy__section-content">
					<h2 class="p-privacy__section-title">個人情報の収集について</h2>
					<p class="p-privacy__section-text">当サイトでは、お問い合わせやイベント申し込みの際に、お名前、メールアドレス、電話番号などの個人情報をご提供いただく場合があります。これらの情報は、お問い合わせへの回答、サービスの提供、イベントのご案内などの目的でのみ使用いたします。</p>
				</div>
			</section>
			<!-- /.p-privacy__section -->

			<section class="p-privacy__section">
				<div class="p-privacy__section-num">2</div>
				<div class="p-privacy__section-content">
					<h2 class="p-privacy__section-title">個人情報の利用目的</h2>
					<ul class="p-privacy__section-list">
						<li>お問い合わせへの回答</li>
						<li>イベント・キャンペーンのご案内</li>
						<li>サービスの改善・向上</li>
						<li>統計データの作成（個人を特定できない形式）</li>
					</ul>
				</div>
			</section>
			<!-- /.p-privacy__section -->

			<section class="p-privacy__section">
				<div class="p-privacy__section-num">3</div>
				<div class="p-privacy__section-content">
					<h2 class="p-privacy__section-title">個人情報の第三者提供</h2>
					<p class="p-privacy__section-text">当サイトでは、法令に基づく場合を除き、ご本人の同意なく個人情報を第三者に提供することはありません。</p>
				</div>
			</section>
			<!-- /.p-privacy__section -->

			<section class="p-privacy__section">
				<div class="p-privacy__section-num">4</div>
				<div class="p-privacy__section-content">
					<h2 class="p-privacy__section-title">Cookieの使用について</h2>
					<p class="p-privacy__section-text">当サイトでは、ユーザー体験の向上やアクセス解析のためにCookieを使用しています。ブラウザの設定により、Cookieの受け入れを拒否することも可能ですが、一部のサービスが正常に動作しない場合があります。</p>
				</div>
			</section>
			<!-- /.p-privacy__section -->

			<section class="p-privacy__section">
				<div class="p-privacy__section-num">5</div>
				<div class="p-privacy__section-content">
					<h2 class="p-privacy__section-title">お問い合わせ</h2>
					<p class="p-privacy__section-text">個人情報の取り扱いに関するお問い合わせは、<a class="p-privacy__link" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">お問い合わせページ</a>よりご連絡ください。</p>
				</div>
			</section>
			<!-- /.p-privacy__section -->

			<div class="p-privacy__dates">
				<p>制定日：2025年1月1日</p>
				<p>最終更新日：2025年1月1日</p>
			</div>
			<!-- /.p-privacy__dates -->

		</div>
		<!-- /.p-privacy__inner -->
	</div>
	<!-- /.p-privacy__body -->

</article>

<?php get_footer(); ?>
