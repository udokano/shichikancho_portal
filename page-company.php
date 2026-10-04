<?php
/** 運営についてページ */
get_header();
?>

<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>

<article class="p-company">

	<?php
	get_template_part( 'template-parts/components/page-hero', null, [
		'title' => '運営について',
		'sub'   => '当サイトの運営情報',
	] );
	?>

	<div class="p-company__body">
		<div class="p-company__inner">

			<section class="p-company__card" aria-labelledby="company-info-title">
				<h2 class="p-company__card-title" id="company-info-title">運営団体情報</h2>

				<dl class="p-company__dl">

					<div class="p-company__row">
						<dt class="p-company__dt">団体名</dt>
						<dd class="p-company__dd">七間町町内会</dd>
					</div>

					<div class="p-company__row">
						<dt class="p-company__dt">所在地</dt>
						<dd class="p-company__dd">〒420-0035<br>静岡県静岡市葵区七間町17-9</dd>
					</div>

					<div class="p-company__row">
						<dt class="p-company__dt">設立</dt>
						<dd class="p-company__dd">昭和XX年</dd>
					</div>

					<div class="p-company__row">
						<dt class="p-company__dt">代表者</dt>
						<dd class="p-company__dd">会長 ○○ ○○</dd>
					</div>

					<div class="p-company__row">
						<dt class="p-company__dt">電話番号</dt>
						<dd class="p-company__dd"><a class="p-company__tel" href="tel:054XXXXXXX">054-XXX-XXXX</a></dd>
					</div>

					<div class="p-company__row">
						<dt class="p-company__dt">メール</dt>
						<dd class="p-company__dd"><a class="p-company__mail" href="mailto:info@shichikancho.jp">info@shichikancho.jp</a></dd>
					</div>

					<div class="p-company__row">
						<dt class="p-company__dt">活動内容</dt>
						<dd class="p-company__dd">
							<ul class="p-company__activities">
								<li>商店街の活性化事業</li>
								<li>イベント企画・運営</li>
								<li>観光情報の発信</li>
								<li>地域コミュニティの形成</li>
							</ul>
						</dd>
					</div>

				</dl>
			</section>
			<!-- /.p-company__card -->

			<div class="p-company__cta">
				<a class="c-btn c-btn--primary" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">
					<svg class="c-btn__icon" aria-hidden="true" focusable="false"><use href="#icon-mail"></use></svg>
					お問い合わせはこちら
				</a>
			</div>
			<!-- /.p-company__cta -->

		</div>
		<!-- /.p-company__inner -->
	</div>
	<!-- /.p-company__body -->

</article>
<!-- /.p-company -->

<?php get_footer(); ?>
