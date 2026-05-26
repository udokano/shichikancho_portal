<?php
/**
 * 404 ページ
 */
get_header();
?>
<main id="main-content">

	<?php
	get_template_part( 'template-parts/components/page-hero', null, [
		'title' => 'ページが見つかりません',
		'sub'   => 'お探しのページは存在しないか、移動した可能性があります。',
	] );
	?>

	<section class="p-404">
		<div class="p-404__inner">
			<p class="p-404__code">404</p>
			<p class="p-404__message">URLが間違っているか、ページが削除された可能性があります。</p>
			<div class="p-404__links">
				<a class="c-btn c-btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>">トップページへ戻る</a>
				<a class="c-btn c-btn--outline" href="<?php echo esc_url( get_post_type_archive_link( 'shop' ) ); ?>">お店一覧を見る</a>
			</div>
		</div>
		<!-- /.p-404__inner -->
	</section>
	<!-- /.p-404 -->

</main>
<?php get_footer(); ?>
