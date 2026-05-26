<?php
/** 町でまなぶページ */

$pid = get_queried_object_id();

// ─── タブは固定（タブ名・アイコン・パネルIDはテンプレート側で確定） ──
// 各タブの項目だけ ACF リピーターから取得
$learn_tabs = [
	[ 'label' => '文化・歴史体験',    'icon' => 'icon-culture',  'panel_id' => 'learn-panel-culture', 'acf_key' => 'learn_items_culture' ],
	[ 'label' => '塾・学習塾',         'icon' => 'icon-school',   'panel_id' => 'learn-panel-school',  'acf_key' => 'learn_items_school'  ],
	[ 'label' => '習い事・教室',       'icon' => 'icon-sparkles', 'panel_id' => 'learn-panel-lesson',  'acf_key' => 'learn_items_lesson'  ],
	[ 'label' => '資格・スキルアップ', 'icon' => 'icon-desk',     'panel_id' => 'learn-panel-skill',   'acf_key' => 'learn_items_skill'   ],
];

foreach ( $learn_tabs as &$tab ) {
	$rows = function_exists( 'get_field' ) ? get_field( $tab['acf_key'], $pid ) : [];
	$tab['items'] = ( ! empty( $rows ) && is_array( $rows ) ) ? $rows : [];
}
unset( $tab );

get_header();
?>

<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>

<article class="p-learn">

	<?php
	get_template_part( 'template-parts/components/page-hero', null, [
		'title' => '町で学ぶ',
		'sub'   => '七間町で学び、成長する。',
	] );
	?>

	<!-- ─── イントロ（ハードコード） ── -->
	<section class="p-learn__intro" aria-labelledby="learn-intro-title">
		<div class="p-learn__intro-inner">
			<h2 class="p-learn__intro-title" id="learn-intro-title">学びの町、七間町</h2>
			<p class="p-learn__intro-text">歴史と文化が息づく七間町には、伝統工芸の体験教室から学習塾、習い事、資格取得スクールまで、<br>あらゆる世代の「学びたい」に応える場所が揃っています。</p>
		</div>
		<!-- /.p-learn__intro-inner -->
	</section>
	<!-- /.p-learn__intro -->

	<!-- ─── カテゴリータブ ── -->
	<nav class="c-tabs js-tabs" data-panels=".p-learn__panel" aria-label="学びカテゴリー">
		<div class="c-tabs__inner">
			<ul class="c-tabs__list" role="tablist">
				<?php foreach ( $learn_tabs as $i => $tab ) : ?>
				<li class="c-tabs__item" role="presentation">
					<button
						type="button"
						class="c-tabs__btn<?php echo $i === 0 ? ' is-active' : ''; ?>"
						role="tab"
						aria-selected="<?php echo $i === 0 ? 'true' : 'false'; ?>"
						aria-controls="<?php echo esc_attr( $tab['panel_id'] ); ?>">
						<svg class="c-tabs__icon" aria-hidden="true" focusable="false" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><use href="#<?php echo esc_attr( $tab['icon'] ); ?>"></use></svg>
						<?php echo esc_html( $tab['label'] ); ?>
					</button>
				</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<!-- /.c-tabs__inner -->
	</nav>
	<!-- /.c-tabs -->

	<!-- ─── カテゴリーパネル ── -->
	<?php foreach ( $learn_tabs as $i => $tab ) :
		$items = $tab['items'];
	?>
	<section
		class="p-learn__panel<?php echo $i === 0 ? ' is-active' : ''; ?>"
		id="<?php echo esc_attr( $tab['panel_id'] ); ?>"
		role="tabpanel"
		<?php echo $i !== 0 ? 'hidden' : ''; ?>>
		<div class="p-learn__panel-inner">
			<h2 class="p-learn__panel-title">
				<?php echo esc_html( $tab['label'] ); ?>
				<span class="p-learn__panel-count"><?php echo esc_html( count( $items ) ); ?>件</span>
			</h2>

			<?php if ( $items ) : ?>
			<div class="p-learn__cards">
				<?php foreach ( $items as $item ) :
					$name   = $item['name']     ?? '';
					$badge  = $item['badge']    ?? '';
					$desc   = $item['desc']     ?? '';
					$sched  = $item['schedule'] ?? '';
					$fee    = $item['fee']      ?? '';
					$target = $item['target']   ?? '';
					$venue  = $item['venue']    ?? '';
					$phone  = $item['phone']    ?? '';
					$url    = $item['url']      ?? '';
					if ( ! $name ) continue;
				?>
				<article class="p-learn__card">
					<header class="p-learn__card-head">
						<h3 class="p-learn__card-title"><?php echo esc_html( $name ); ?></h3>
						<?php if ( $badge ) : ?>
						<span class="p-learn__card-badge"><?php echo esc_html( $badge ); ?></span>
						<?php endif; ?>
					</header>
					<!-- /.p-learn__card-head -->

					<?php if ( $desc ) : ?>
					<p class="p-learn__card-desc"><?php echo wp_kses( $desc, [ 'br' => [] ] ); ?></p>
					<?php endif; ?>

					<dl class="p-learn__card-info">
						<?php if ( $sched ) : ?>
						<div class="p-learn__card-info-row">
							<dt>
								<svg aria-hidden="true" focusable="false" class="p-learn__card-info-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><use href="#icon-clock"></use></svg>
								<span class="u-sr-only">開催日時</span>
							</dt>
							<dd><?php echo esc_html( $sched ); ?></dd>
						</div>
						<?php endif; ?>
						<?php if ( $fee ) : ?>
						<div class="p-learn__card-info-row">
							<dt>
								<svg aria-hidden="true" focusable="false" class="p-learn__card-info-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><use href="#icon-tag"></use></svg>
								<span class="u-sr-only">料金</span>
							</dt>
							<dd><?php echo esc_html( $fee ); ?></dd>
						</div>
						<?php endif; ?>
						<?php if ( $target ) : ?>
						<div class="p-learn__card-info-row">
							<dt>
								<svg aria-hidden="true" focusable="false" class="p-learn__card-info-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><use href="#icon-person"></use></svg>
								<span class="u-sr-only">対象</span>
							</dt>
							<dd><?php echo esc_html( $target ); ?></dd>
						</div>
						<?php endif; ?>
						<?php if ( $venue ) : ?>
						<div class="p-learn__card-info-row">
							<dt>
								<svg aria-hidden="true" focusable="false" class="p-learn__card-info-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><use href="#icon-map-pin"></use></svg>
								<span class="u-sr-only">会場</span>
							</dt>
							<dd><?php echo esc_html( $venue ); ?></dd>
						</div>
						<?php endif; ?>
					</dl>
					<!-- /.p-learn__card-info -->

					<footer class="p-learn__card-foot">
						<?php if ( $phone ) : ?>
						<a class="p-learn__card-phone" href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>">
							<svg aria-hidden="true" focusable="false" class="p-learn__card-info-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><use href="#icon-phone"></use></svg>
							<?php echo esc_html( $phone ); ?>
						</a>
						<?php endif; ?>
						<a class="p-learn__card-link" href="<?php echo esc_url( $url ?: home_url( '/contact/' ) ); ?>"<?php echo $url ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
							お問い合わせ
							<svg aria-hidden="true" focusable="false" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><use href="#icon-chevron-right"></use></svg>
						</a>
					</footer>
					<!-- /.p-learn__card-foot -->
				</article>
				<!-- /.p-learn__card -->
				<?php endforeach; ?>
			</div>
			<!-- /.p-learn__cards -->
			<?php else : ?>
			<p class="p-learn__panel-empty">このカテゴリーには現在登録がありません。</p>
			<?php endif; ?>

		</div>
		<!-- /.p-learn__panel-inner -->
	</section>
	<!-- /.p-learn__panel -->
	<?php endforeach; ?>

	<!-- ─── 掲載募集CTA（ハードコード） ── -->
	<section class="l-cta-hero" aria-labelledby="learn-cta-title">
		<div class="l-cta-hero__inner">
			<h2 class="l-cta-hero__title" id="learn-cta-title">教室・スクールを掲載しませんか？</h2>
			<p class="l-cta-hero__text">七間町で教室やスクールを運営されている方、掲載をご希望の方はお気軽にお問い合わせください。掲載は無料です。</p>
			<div class="l-cta-hero__buttons">
				<a class="l-cta-hero__btn l-cta-hero__btn--primary" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">
					掲載のお問い合わせ
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><use href="#icon-chevron-right"></use></svg>
				</a>
			</div>
		</div>
	</section>
	<!-- /.l-cta-hero -->

</article>
<!-- /.p-learn -->

<?php get_footer(); ?>
