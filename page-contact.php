<?php
/** お問い合わせページ */
get_header();
<?php if ( function_exists( 'schema_contact_page' ) ) schema_contact_page(); ?>
<?php

<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>

<article class="p-contact">

	<?php
		get_template_part( 'template-parts/components/page-hero', null, [
			'title' => 'お問い合わせ',
			'sub'   => 'ご質問やご意見はこちらからお気軽にお問い合わせください。',
		] );
		?>

	<!-- ─── 本文：連絡先 + フォーム（2カラム） ── -->
	<section class="p-contact__main" aria-label="お問い合わせ">
		<div class="p-contact__main-inner">
			<div class="p-contact__grid">

				<!-- 左：連絡先 -->
				<div class="p-contact__info">
					<h2 class="p-contact__info-title">連絡先</h2>

					<div class="p-contact__info-item">
						<span class="p-contact__info-icon" aria-hidden="true">
							<svg class="p-contact__info-icon-svg" aria-hidden="true" focusable="false"><use href="#icon-map-pin-solid"></use></svg>
						</span>
						<div class="p-contact__info-body">
							<p class="p-contact__info-label">七間町町内会</p>
							<p class="p-contact__info-value">〒420-0035</p>
							<p class="p-contact__info-value">静岡県静岡市葵区七間町17-9</p>
						</div>
					</div>

					<div class="p-contact__info-item">
						<span class="p-contact__info-icon" aria-hidden="true">
							<svg class="p-contact__info-icon-svg" aria-hidden="true" focusable="false"><use href="#icon-phone"></use></svg>
						</span>
						<div class="p-contact__info-body">
							<p class="p-contact__info-label">電話番号</p>
							<p class="p-contact__info-value">054-XXX-XXXX</p>
							<p class="p-contact__info-note">（平日 9:00〜17:00）</p>
						</div>
					</div>

					<div class="p-contact__info-item">
						<span class="p-contact__info-icon" aria-hidden="true">
							<svg class="p-contact__info-icon-svg" aria-hidden="true" focusable="false"><use href="#icon-mail"></use></svg>
						</span>
						<div class="p-contact__info-body">
							<p class="p-contact__info-label">メールアドレス</p>
							<p class="p-contact__info-value"><a class="p-contact__info-value-link" href="mailto:info@shichikencho.jp">info@shichikencho.jp</a></p>
						</div>
					</div>
				</div>
				<!-- /.p-contact__info -->

				<!-- 右：お問い合わせフォーム -->
				<div class="p-contact__form">
					<h2 class="p-contact__form-title">お問い合わせフォーム</h2>
					<div class="p-contact__form-body">
						<?php
						echo do_shortcode( '[contact-form-7 id="9471ce0c05753bf12a259489b32f811af6dce90cbbcc85be31adcc9aee4d998b" title="お問い合わせ"]' );
						?>
					</div>
				</div>
				<!-- /.p-contact__form -->

			</div>
			<!-- /.p-contact__grid -->
		</div>
		<!-- /.p-contact__main-inner -->
	</section>
	<!-- /.p-contact__main -->

</article>
<!-- /.p-contact -->

<?php get_footer(); ?>
