<?php
/** 空き物件 個別ページ（Manus準拠） */
get_header();

if ( ! have_posts() ) { get_footer(); return; }
the_post();

$pid       = get_the_ID();
if ( function_exists( 'schema_property' ) ) schema_property( $pid );
$name      = get_field( 'prop_name', $pid ) ?: get_the_title( $pid );
$address   = get_field( 'prop_address', $pid );
$rent      = get_field( 'prop_rent', $pid );
$area      = get_field( 'prop_area', $pid );
$floor     = get_field( 'prop_floor', $pid );
$desc      = get_field( 'prop_description', $pid );
$main_img  = get_field( 'prop_main_image', $pid );
$gallery   = get_field( 'prop_gallery', $pid );
$features  = get_field( 'prop_features', $pid ) ?: [];
$contact   = get_field( 'prop_contact', $pid );
$status    = get_field( 'prop_status', $pid ) ?: 'open';
$category  = get_field( 'prop_category', $pid );
$tags_raw  = get_field( 'prop_tags', $pid );
$tags      = is_array( $tags_raw ) ? array_filter( array_map( fn( $r ) => $r['tag'] ?? '', $tags_raw ) ) : [];

$status_labels = [ 'open' => '募集中', 'negotiating' => '商談中', 'soon' => '近日公開', 'closed' => '成約済み' ];
$feature_labels = [
	'kitchen'  => '厨房あり',
	'restroom' => 'トイレ独立',
	'parking'  => '駐車場あり',
	'ac'       => 'エアコン付き',
	'internet' => 'ネット利用可',
	'shop'     => '店舗利用可',
	'office'   => '事務所利用可',
	'house'    => '住居併用可',
];

$hero = '';
if ( $main_img && is_array( $main_img ) ) {
	$hero = $main_img['sizes']['large'] ?? $main_img['url'];
} else {
	$hero = get_the_post_thumbnail_url( $pid, 'large' ) ?: sc_no_image_url();
}
?>

<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>

<!-- ページヘッダー -->
<header class="p-property__page-head">
	<div class="p-property__page-head-inner">
		<h1 class="p-property__page-title">町で商い</h1>
		<p class="p-property__page-sub">七間町で新しいビジネスを始めよう</p>
	</div>
</header>

<article class="p-property">
	<div class="p-property__inner">

		<a class="p-property__back" href="<?php echo esc_url( home_url( '/business/' ) ); ?>">← 物件一覧に戻る</a>

		<div class="p-property__layout">

			<!-- メイン -->
			<div class="p-property__main">
				<div class="p-property__hero">
					<img class="u-img-cover" src="<?php echo esc_url( $hero ); ?>" alt="<?php echo esc_attr( $name ); ?>" loading="eager">
					<span class="p-property__hero-status p-property__hero-status--<?php echo esc_attr( $status ); ?>"><?php echo esc_html( $status_labels[ $status ] ?? '募集中' ); ?></span>
				</div>

				<?php if ( $category ) : ?>
				<span class="p-property__category"><?php echo esc_html( $category ); ?></span>
				<?php endif; ?>
				<h2 class="p-property__title"><?php echo esc_html( $name ); ?></h2>
				<?php if ( $desc ) : ?>
				<p class="p-property__desc"><?php echo wp_kses( $desc, [ 'br' => [] ] ); ?></p>
				<?php endif; ?>

				<!-- 物件詳細テーブル -->
				<table class="p-property__table">
					<tbody>
						<?php if ( $address ) : ?>
						<tr><th>所在地</th><td><?php echo esc_html( $address ); ?></td></tr>
						<?php endif; ?>
						<?php if ( $area ) : ?>
						<tr><th>面積</th><td><?php echo esc_html( $area ); ?></td></tr>
						<?php endif; ?>
						<?php if ( $floor ) : ?>
						<tr><th>階数</th><td><?php echo esc_html( $floor ); ?></td></tr>
						<?php endif; ?>
						<?php if ( $rent ) : ?>
						<tr><th>賃料</th><td class="p-property__table-rent"><?php echo esc_html( $rent ); ?></td></tr>
						<?php endif; ?>
						<tr><th>敷金・礼金</th><td>敷金3ヶ月・礼金1ヶ月</td></tr>
						<tr><th>最寄り駅</th><td>静岡駅から徒歩12分</td></tr>
					</tbody>
				</table>

				<?php if ( $tags || $features ) : ?>
				<section class="p-property__features">
					<h3 class="p-property__features-title">物件の特徴</h3>
					<ul class="p-property__features-list">
						<?php foreach ( $tags as $t ) : ?>
						<li class="p-property__feature-chip">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><use href="#icon-check"></use></svg>
							<?php echo esc_html( $t ); ?>
						</li>
						<?php endforeach; ?>
						<?php foreach ( (array) $features as $f_key ) :
							$label = $feature_labels[ $f_key ] ?? $f_key;
						?>
						<li class="p-property__feature-chip">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><use href="#icon-check"></use></svg>
							<?php echo esc_html( $label ); ?>
						</li>
						<?php endforeach; ?>
					</ul>
				</section>
				<?php endif; ?>
			</div>
			<!-- /.p-property__main -->

			<!-- サイドバー -->
			<aside class="p-property__sidebar">

				<!-- お問い合わせカード -->
				<div class="p-property__contact-card">
					<h3 class="p-property__contact-title">お問い合わせ</h3>
					<a class="p-property__contact-btn p-property__contact-btn--phone" href="tel:054-XXX-XXXX">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><use href="#icon-phone"></use></svg>
						<span>
							<span class="p-property__contact-btn-label">電話でのお問い合わせ</span>
							<span class="p-property__contact-btn-main">054-XXX-XXXX</span>
						</span>
					</a>
					<a class="p-property__contact-btn p-property__contact-btn--mail" href="mailto:info@shichikencho-estate.jp">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><use href="#icon-mail"></use></svg>
						<span>
							<span class="p-property__contact-btn-label">メールでのお問い合わせ</span>
							<span class="p-property__contact-btn-main">info@shichikencho-estate.jp</span>
						</span>
					</a>
				</div>

				<!-- 七間町で起業するメリット -->
				<div class="p-property__merit-card">
					<h3 class="p-property__merit-title">七間町で起業するメリット</h3>
					<ul class="p-property__merit-list">
						<li><svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><use href="#icon-building-library"></use></svg>静岡市中心部の好立地・高い集客力</li>
						<li><svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><use href="#icon-store"></use></svg>商店街の活気とコミュニティの支援</li>
						<li><svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><use href="#icon-users-solid"></use></svg>地域住民との密接なつながり</li>
						<li><svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><use href="#icon-yen"></use></svg>静岡市の創業支援補助金制度</li>
					</ul>
				</div>

				<!-- 関連リンク -->
				<div class="p-property__links-card">
					<h3 class="p-property__links-title">関連リンク</h3>
					<ul class="p-property__links-list">
						<li><a href="#" target="_blank" rel="noopener noreferrer"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><use href="#icon-external"></use></svg>静岡市 創業支援情報</a></li>
						<li><a href="#" target="_blank" rel="noopener noreferrer"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><use href="#icon-external"></use></svg>静岡商工会議所</a></li>
						<li><a href="#" target="_blank" rel="noopener noreferrer"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><use href="#icon-external"></use></svg>日本政策金融公庫 静岡支店</a></li>
					</ul>
				</div>

			</aside>
			<!-- /.p-property__sidebar -->

		</div>
		<!-- /.p-property__layout -->
	</div>
	<!-- /.p-property__inner -->

	<!-- 下部CTA -->
	<section class="p-property__cta">
		<div class="p-property__cta-inner">
			<h2 class="p-property__cta-title">七間町での開業をお考えの方へ</h2>
			<p class="p-property__cta-text">物件のご相談、開業に関するご質問など、お気軽にお問い合わせください。<br>七間町商店街振興組合が、あなたの新しいビジネスの第一歩をサポートします。</p>
			<div class="p-property__cta-buttons">
				<a class="p-property__cta-btn p-property__cta-btn--primary" href="tel:054-XXX-XXXX">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><use href="#icon-phone"></use></svg>
					電話で相談する
				</a>
				<a class="p-property__cta-btn p-property__cta-btn--secondary" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><use href="#icon-mail"></use></svg>
					メールで問い合わせ
				</a>
			</div>
		</div>
	</section>

</article>
<!-- /.p-property -->

<?php get_footer(); ?>
