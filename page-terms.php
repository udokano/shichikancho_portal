<?php
/** 当サイトのご利用にあたって（利用規約）ページ */
get_header();
?>

<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>

<article class="p-terms">

	<?php
	get_template_part( 'template-parts/components/page-hero', null, [
		'title' => '当サイトのご利用にあたって',
		'sub'   => '利用規約',
	] );
	?>

	<div class="p-terms__body">
		<div class="p-terms__inner">

			<section class="p-terms__section" aria-labelledby="terms-copyright">
				<h2 class="p-terms__section-title" id="terms-copyright">1. 著作権について</h2>
				<p class="p-terms__section-text">当サイトに掲載されている文章、画像、動画などのコンテンツの著作権は、七間町町内会または各権利者に帰属します。無断での複製、転載、改変は禁止されています。</p>
			</section>

			<section class="p-terms__section" aria-labelledby="terms-disclaimer">
				<h2 class="p-terms__section-title" id="terms-disclaimer">2. 免責事項</h2>
				<p class="p-terms__section-text">当サイトの情報は、正確性を期していますが、その完全性・正確性を保証するものではありません。当サイトの利用により生じた損害について、当サイト運営者は一切の責任を負いません。</p>
			</section>

			<section class="p-terms__section" aria-labelledby="terms-link">
				<h2 class="p-terms__section-title" id="terms-link">3. リンクについて</h2>
				<p class="p-terms__section-text">当サイトへのリンクは、原則として自由です。ただし、以下の場合はリンクをお断りします。</p>
				<ul class="p-terms__section-list">
					<li>当サイトの内容を誹謗中傷するサイトからのリンク</li>
					<li>違法なコンテンツを含むサイトからのリンク</li>
					<li>フレーム内に当サイトを表示するリンク</li>
				</ul>
			</section>

			<section class="p-terms__section" aria-labelledby="terms-env">
				<h2 class="p-terms__section-title" id="terms-env">4. 推奨環境</h2>
				<p class="p-terms__section-text">当サイトは、以下のブラウザでの閲覧を推奨しています。</p>
				<ul class="p-terms__section-list">
					<li>Google Chrome（最新版）</li>
					<li>Mozilla Firefox（最新版）</li>
					<li>Safari（最新版）</li>
					<li>Microsoft Edge（最新版）</li>
				</ul>
			</section>

			<footer class="p-terms__meta">
				<p>制定日：2025年1月1日</p>
				<p>最終更新日：2025年1月1日</p>
			</footer>

		</div>
		<!-- /.p-terms__inner -->
	</div>
	<!-- /.p-terms__body -->

</article>
<!-- /.p-terms -->

<?php get_footer(); ?>
