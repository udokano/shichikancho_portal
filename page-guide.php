<?php
/** くらしガイドページ */
get_header();

// ACFリピーターをpostmetaから直読みするヘルパー
function guide_get_repeater( int $pid, string $key ): array {
	$count = (int) get_post_meta( $pid, $key, true );
	if ( $count <= 0 ) return [];
	$all  = get_post_meta( $pid );
	$rows = [];
	for ( $i = 0; $i < $count; $i++ ) {
		$prefix = $key . '_' . $i . '_';
		$row    = [];
		foreach ( $all as $mk => $mv ) {
			if ( str_starts_with( $mk, $prefix ) && ! str_starts_with( $mk, '_' ) ) {
				$row[ substr( $mk, strlen( $prefix ) ) ] = $mv[0];
			}
		}
		$rows[] = $row;
	}
	return $rows;
}

$pid          = get_queried_object_id();
$medical_lead = get_post_meta( $pid, 'guide_medical_lead',  true ) ?: '';
$garbage_notes_raw = get_post_meta( $pid, 'guide_garbage_notes', true ) ?: '';

$facilities = guide_get_repeater( $pid, 'guide_facilities' );
$medical    = guide_get_repeater( $pid, 'guide_medical' );
$garbage    = guide_get_repeater( $pid, 'guide_garbage' );
$rules      = guide_get_repeater( $pid, 'guide_rules' );
$links      = guide_get_repeater( $pid, 'guide_links' );
?>

<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>

<article class="p-guide">

	<?php
	get_template_part( 'template-parts/components/page-hero', null, [
		'title' => 'くらしガイド',
		'sub'   => '七間町での暮らしに役立つ情報。',
	] );
	?>

	<!-- ─── イントロ ── -->
	<section class="p-guide__intro" aria-labelledby="guide-intro-title">
		<div class="p-guide__intro-inner">
			<h2 class="p-guide__intro-title" id="guide-intro-title">七間町での生活に役立つ情報</h2>
		</div>
		<!-- /.p-guide__intro-inner -->
	</section>
	<!-- /.p-guide__intro -->

	<!-- ─── タブナビ ── -->
	<nav class="c-tabs js-tabs" data-panels=".p-guide__section" aria-label="くらしガイドカテゴリー">
		<div class="c-tabs__inner">
			<ul class="c-tabs__list" role="tablist">
				<li class="c-tabs__item" role="presentation">
					<button type="button" class="c-tabs__btn is-active" role="tab" aria-selected="true" aria-controls="guide-facilities">
						<svg class="c-tabs__icon" aria-hidden="true" focusable="false"><use href="#icon-building"></use></svg>
						公共施設
					</button>
				</li>
				<li class="c-tabs__item" role="presentation">
					<button type="button" class="c-tabs__btn" role="tab" aria-selected="false" aria-controls="guide-medical">
						<svg class="c-tabs__icon" aria-hidden="true" focusable="false"><use href="#icon-medical"></use></svg>
						医療機関
					</button>
				</li>
				<li class="c-tabs__item" role="presentation">
					<button type="button" class="c-tabs__btn" role="tab" aria-selected="false" aria-controls="guide-garbage">
						<svg class="c-tabs__icon" aria-hidden="true" focusable="false"><use href="#icon-tag"></use></svg>
						ごみの出し方
					</button>
				</li>
				<li class="c-tabs__item" role="presentation">
					<button type="button" class="c-tabs__btn" role="tab" aria-selected="false" aria-controls="guide-rules">
						<svg class="c-tabs__icon" aria-hidden="true" focusable="false"><use href="#icon-book"></use></svg>
						生活ルール
					</button>
				</li>
				<li class="c-tabs__item" role="presentation">
					<button type="button" class="c-tabs__btn" role="tab" aria-selected="false" aria-controls="guide-links">
						<svg class="c-tabs__icon" aria-hidden="true" focusable="false"><use href="#icon-external"></use></svg>
						外部リンク
					</button>
				</li>
			</ul>
		</div>
		<!-- /.c-tabs__inner -->
	</nav>
	<!-- /.c-tabs -->

	<!-- ─── 公共施設 ── -->
	<section class="p-guide__section" id="guide-facilities" role="tabpanel" aria-labelledby="guide-facilities-title">
		<div class="p-guide__section-inner">
			<h2 class="p-guide__section-title" id="guide-facilities-title">公共施設の案内</h2>
			<?php if ( $facilities ) : ?>
				<ul class="p-guide__facility-list">
					<?php foreach ( $facilities as $f ) : ?>
						<li class="p-guide__facility-card">
							<div class="p-guide__facility-head">
								<h3 class="p-guide__facility-name"><?php echo esc_html( $f['gf_name'] ); ?></h3>
								<?php if ( $f['gf_type'] ) : ?>
									<span class="p-guide__facility-type"><?php echo esc_html( $f['gf_type'] ); ?></span>
								<?php endif; ?>
							</div>
							<!-- /.p-guide__facility-head -->
							<div class="p-guide__facility-meta">
								<?php if ( $f['gf_address'] ) : ?>
									<span class="p-guide__facility-address">
										<svg aria-hidden="true" focusable="false"><use href="#icon-map-pin"></use></svg>
										<?php echo esc_html( $f['gf_address'] ); ?>
									</span>
								<?php endif; ?>
								<?php if ( $f['gf_hours'] ) : ?>
									<span class="p-guide__facility-hours">
										<svg aria-hidden="true" focusable="false"><use href="#icon-clock"></use></svg>
										<?php echo esc_html( $f['gf_hours'] ); ?>
									</span>
								<?php endif; ?>
							</div>
							<!-- /.p-guide__facility-meta -->
							<?php if ( $f['gf_desc'] ) : ?>
								<p class="p-guide__facility-desc"><?php echo esc_html( $f['gf_desc'] ); ?></p>
							<?php endif; ?>
							<?php if ( $f['gf_phone'] ) : ?>
								<div class="p-guide__facility-footer">
									<span class="p-guide__facility-phone">
										<svg aria-hidden="true" focusable="false"><use href="#icon-phone"></use></svg>
										<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9]/', '', $f['gf_phone'] ) ); ?>"><?php echo esc_html( $f['gf_phone'] ); ?></a>
									</span>
								</div>
							<?php endif; ?>
						</li>
						<!-- /.p-guide__facility-card -->
					<?php endforeach; ?>
				</ul>
				<!-- /.p-guide__facility-list -->
			<?php endif; ?>
		</div>
		<!-- /.p-guide__section-inner -->
	</section>
	<!-- /.p-guide__section -->

	<!-- ─── 医療機関 ── -->
	<section class="p-guide__section" id="guide-medical" role="tabpanel" hidden aria-labelledby="guide-medical-title">
		<div class="p-guide__section-inner">
			<h2 class="p-guide__section-title" id="guide-medical-title">医療機関</h2>
			<?php if ( $medical_lead ) : ?>
				<p class="p-guide__section-lead"><?php echo esc_html( $medical_lead ); ?></p>
			<?php endif; ?>
			<?php if ( $medical ) : ?>
				<ul class="p-guide__facility-list">
					<?php foreach ( $medical as $m ) : ?>
						<li class="p-guide__facility-card">
							<div class="p-guide__facility-head">
								<h3 class="p-guide__facility-name"><?php echo esc_html( $m['gm_name'] ); ?></h3>
								<?php if ( $m['gm_type'] ) : ?>
									<span class="p-guide__facility-type"><?php echo esc_html( $m['gm_type'] ); ?></span>
								<?php endif; ?>
							</div>
							<!-- /.p-guide__facility-head -->
							<div class="p-guide__facility-meta">
								<?php if ( $m['gm_address'] ) : ?>
									<span class="p-guide__facility-address">
										<svg aria-hidden="true" focusable="false"><use href="#icon-map-pin"></use></svg>
										<?php echo esc_html( $m['gm_address'] ); ?>
									</span>
								<?php endif; ?>
								<?php if ( $m['gm_hours'] ) : ?>
									<span class="p-guide__facility-hours">
										<svg aria-hidden="true" focusable="false"><use href="#icon-clock"></use></svg>
										<?php echo esc_html( $m['gm_hours'] ); ?>
									</span>
								<?php endif; ?>
							</div>
							<!-- /.p-guide__facility-meta -->
							<?php if ( $m['gm_desc'] ) : ?>
								<p class="p-guide__facility-desc"><?php echo esc_html( $m['gm_desc'] ); ?></p>
							<?php endif; ?>
							<?php if ( $m['gm_phone'] ) : ?>
								<div class="p-guide__facility-footer">
									<span class="p-guide__facility-phone">
										<svg aria-hidden="true" focusable="false"><use href="#icon-phone"></use></svg>
										<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9]/', '', $m['gm_phone'] ) ); ?>"><?php echo esc_html( $m['gm_phone'] ); ?></a>
									</span>
								</div>
							<?php endif; ?>
						</li>
						<!-- /.p-guide__facility-card -->
					<?php endforeach; ?>
				</ul>
				<!-- /.p-guide__facility-list -->
			<?php endif; ?>
		</div>
		<!-- /.p-guide__section-inner -->
	</section>
	<!-- /.p-guide__section -->

	<!-- ─── ごみの出し方 ── -->
	<section class="p-guide__section p-guide__section--washi" id="guide-garbage" role="tabpanel" hidden aria-labelledby="guide-garbage-title">
		<div class="p-guide__section-inner">
			<h2 class="p-guide__section-title" id="guide-garbage-title">ごみの分け方・出し方</h2>
			<?php if ( $garbage ) : ?>
				<div class="p-guide__garbage-grid">
					<?php foreach ( $garbage as $g ) : ?>
						<div class="p-guide__garbage-card">
							<h3 class="p-guide__garbage-name"><?php echo esc_html( $g['gg_name'] ); ?></h3>
							<?php if ( $g['gg_schedule'] ) : ?>
								<p class="p-guide__garbage-schedule"><?php echo esc_html( $g['gg_schedule'] ); ?></p>
							<?php endif; ?>
							<?php if ( $g['gg_text'] ) : ?>
								<p class="p-guide__garbage-text"><?php echo esc_html( $g['gg_text'] ); ?></p>
							<?php endif; ?>
						</div>
						<!-- /.p-guide__garbage-card -->
					<?php endforeach; ?>
				</div>
				<!-- /.p-guide__garbage-grid -->
			<?php endif; ?>
			<?php if ( $garbage_notes_raw ) : ?>
				<ul class="p-guide__garbage-notes">
					<?php foreach ( explode( "\n", $garbage_notes_raw ) as $note ) : ?>
						<?php $note = trim( $note ); if ( ! $note ) continue; ?>
						<li><?php echo esc_html( $note ); ?></li>
					<?php endforeach; ?>
				</ul>
				<!-- /.p-guide__garbage-notes -->
			<?php endif; ?>
		</div>
		<!-- /.p-guide__section-inner -->
	</section>
	<!-- /.p-guide__section -->

	<!-- ─── 生活ルール ── -->
	<section class="p-guide__section" id="guide-rules" role="tabpanel" hidden aria-labelledby="guide-rules-title">
		<div class="p-guide__section-inner">
			<h2 class="p-guide__section-title" id="guide-rules-title">生活ルール・マナー</h2>
			<?php if ( $rules ) : ?>
				<ul class="p-guide__rules-list">
					<?php foreach ( $rules as $r ) : ?>
						<li class="p-guide__rule-item">
							<h3 class="p-guide__rule-title"><?php echo esc_html( $r['gr_title'] ); ?></h3>
							<?php if ( $r['gr_text'] ) : ?>
								<p class="p-guide__rule-text"><?php echo esc_html( $r['gr_text'] ); ?></p>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
				<!-- /.p-guide__rules-list -->
			<?php endif; ?>
		</div>
		<!-- /.p-guide__section-inner -->
	</section>
	<!-- /.p-guide__section -->

	<!-- ─── 外部リンク ── -->
	<section class="p-guide__section p-guide__section--washi" id="guide-links" role="tabpanel" hidden aria-labelledby="guide-links-title">
		<div class="p-guide__section-inner">
			<h2 class="p-guide__section-title" id="guide-links-title">お役立ちリンク集</h2>
			<?php if ( $links ) : ?>
				<ul class="p-guide__links-list">
					<?php foreach ( $links as $l ) : ?>
						<?php if ( ! $l['gl_url'] ) continue; ?>
						<li class="p-guide__links-item">
							<a class="p-guide__links-link" href="<?php echo esc_url( $l['gl_url'] ); ?>" target="_blank" rel="noopener noreferrer">
								<span class="p-guide__links-info">
									<span class="p-guide__links-name"><?php echo esc_html( $l['gl_name'] ); ?></span>
									<?php if ( $l['gl_desc'] ) : ?>
										<span class="p-guide__links-desc"><?php echo esc_html( $l['gl_desc'] ); ?></span>
									<?php endif; ?>
								</span>
								<svg class="p-guide__links-icon" aria-hidden="true" focusable="false"><use href="#icon-external"></use></svg>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
				<!-- /.p-guide__links-list -->
			<?php endif; ?>
		</div>
		<!-- /.p-guide__section-inner -->
	</section>
	<!-- /.p-guide__section -->

</article>
<!-- /.p-guide -->

<?php get_footer(); ?>
