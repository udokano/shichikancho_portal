<?php
/**
 * Template Name: お問い合わせフォームベース
 *
 * ヒーロー = ACF / 本文 = ブロックエディタ（連絡先パターン + CF7 ブロック）
 * フォームを増やしたい場合は、このテンプレートを割り当てた固定ページを量産する
 *
 * ファイル名を page-contact.php にしないこと。
 * スラッグ contact のページがテンプレート選択を無視して自動適用され、
 * _wp_page_template が default のままになって ACF の page_template 判定が外れる
 */
get_header();

// お問い合わせページの構造化データ
if ( function_exists( 'schema_contact_page' ) ) {
	schema_contact_page();
}
?>

<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>

<article class="p-contact">

	<?php while ( have_posts() ) : the_post(); ?>

		<?php
		// ヒーローは ACF で管理。未入力ならページタイトルにフォールバック
		// page-hero 側で esc_html するため、ここでは生値を渡す
		$hero_title = function_exists( 'get_field' ) ? (string) get_field( 'page_hero_title' ) : '';
		$hero_sub   = function_exists( 'get_field' ) ? (string) get_field( 'page_hero_sub' ) : '';
		if ( $hero_title === '' ) $hero_title = get_the_title();

		get_template_part( 'template-parts/components/page-hero', null, [
			'title' => $hero_title,
			'sub'   => $hero_sub,
		] );
		?>

		<!-- ─── 本文：ブロックエディタ（連絡先パターン + CF7 ブロック）── -->
		<section class="p-contact__main" aria-label="お問い合わせ">
			<div class="p-contact__main-inner">
				<div class="p-contact__content">
					<?php the_content(); ?>
				</div>
				<!-- /.p-contact__content -->
			</div>
			<!-- /.p-contact__main-inner -->
		</section>
		<!-- /.p-contact__main -->

	<?php endwhile; ?>

</article>
<!-- /.p-contact -->

<?php get_footer(); ?>
