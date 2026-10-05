<?php
/** 町で働くページ */
get_header();

// 求人一覧（掲載中のみ・新しい順）。おすすめは PICK UP 設定から
$job_pickup_ids = array_map(
	fn( $pick ) => is_object( $pick ) ? (int) $pick->ID : (int) $pick,
	(array) get_field( 'pickup_job', 'option' )
);
$job_query = new WP_Query( [
	'post_type'      => CPT_JOB,
	'posts_per_page' => -1,
	'orderby'        => 'date',
	'order'          => 'DESC',
	'meta_query'     => [
		'relation' => 'OR',
		[ 'key' => 'job_is_active', 'value' => '1', 'compare' => '=' ],
		[ 'key' => 'job_is_active', 'compare' => 'NOT EXISTS' ],
	],
] );

// 絞り込みチップの選択肢（職種は実データ、雇用形態は ACF の選択肢から）
$job_positions = [];
foreach ( $job_query->posts as $job_post ) {
	$job_position_value = (string) get_field( 'job_position', $job_post->ID );
	if ( '' !== $job_position_value && ! in_array( $job_position_value, $job_positions, true ) ) {
		$job_positions[] = $job_position_value;
	}
}
$job_type_choices = acf_get_field( 'job_type' )['choices'] ?? [];
?>

<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>

<article class="p-working">

	<?php
		get_template_part( 'template-parts/components/page-hero', null, [
			'title' => '町で働く',
			'sub'   => '七間町で、あなたの居場所を見つけよう。',
		] );
		?>

	<!-- ─── スポンサー法人 ── -->
	<section class="p-working__sponsors" aria-labelledby="working-sponsors-title">
		<div class="p-working__sponsors-inner">
			<div class="p-working__sponsors-header">
				<span class="p-working__sponsors-label">SPONSOR</span>
				<h2 class="p-working__sponsors-title" id="working-sponsors-title">注目の法人</h2>
			</div>
			<!-- /.p-working__sponsors-header -->
			<div class="p-working__sponsors-grid">

				<a class="p-working__sponsor-card" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">
					<span class="p-working__sponsor-pr" aria-label="PR">PR</span>
					<div class="p-working__sponsor-head">
						<div class="p-working__sponsor-thumb">
							<picture class="u-picture-fill">
								<img class="u-img-cover" src="<?php echo esc_url( sc_no_image_url() ); ?>" alt="" aria-hidden="true" loading="lazy" width="120" height="120">
							</picture>
						</div>
						<div class="p-working__sponsor-info">
							<h3 class="p-working__sponsor-name">七間町商店街振興組合</h3>
							<span class="p-working__sponsor-category">まちづくり・イベント</span>
						</div>
					</div>
					<!-- /.p-working__sponsor-head -->
					<p class="p-working__sponsor-desc">七間町商店街の活性化を推進する組合。イベント企画、広報、地域連携など多彩な仕事があります。</p>
					<div class="p-working__sponsor-foot">
						<span class="p-working__sponsor-count">
							<svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-briefcase"></use></svg>
							求人 3件
						</span>
						<span class="p-working__sponsor-more">詳しく見る →</span>
					</div>
					<!-- /.p-working__sponsor-foot -->
				</a>
				<!-- /.p-working__sponsor-card -->

				<a class="p-working__sponsor-card" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">
					<span class="p-working__sponsor-pr" aria-label="PR">PR</span>
					<div class="p-working__sponsor-head">
						<div class="p-working__sponsor-thumb">
							<picture class="u-picture-fill">
								<img class="u-img-cover" src="<?php echo esc_url( sc_no_image_url() ); ?>" alt="" aria-hidden="true" loading="lazy" width="120" height="120">
							</picture>
						</div>
						<div class="p-working__sponsor-info">
							<h3 class="p-working__sponsor-name">七間町デザイン事務所</h3>
							<span class="p-working__sponsor-category">IT・Web制作</span>
						</div>
					</div>
					<!-- /.p-working__sponsor-head -->
					<p class="p-working__sponsor-desc">地域のDX推進を担うクリエイティブカンパニー。リモートワーク可、フレックス制度あり。</p>
					<div class="p-working__sponsor-foot">
						<span class="p-working__sponsor-count">
							<svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-briefcase"></use></svg>
							求人 2件
						</span>
						<span class="p-working__sponsor-more">詳しく見る →</span>
					</div>
					<!-- /.p-working__sponsor-foot -->
				</a>
				<!-- /.p-working__sponsor-card -->

				<a class="p-working__sponsor-card" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">
					<span class="p-working__sponsor-pr" aria-label="PR">PR</span>
					<div class="p-working__sponsor-head">
						<div class="p-working__sponsor-thumb">
							<picture class="u-picture-fill">
								<img class="u-img-cover" src="<?php echo esc_url( sc_no_image_url() ); ?>" alt="" aria-hidden="true" loading="lazy" width="120" height="120">
							</picture>
						</div>
						<div class="p-working__sponsor-info">
							<h3 class="p-working__sponsor-name">七間町建具工房</h3>
							<span class="p-working__sponsor-category">伝統工芸・建築</span>
						</div>
					</div>
					<!-- /.p-working__sponsor-head -->
					<p class="p-working__sponsor-desc">40年の伝統を継承する建具工房。職人技を次世代に伝える仕事。</p>
					<div class="p-working__sponsor-foot">
						<span class="p-working__sponsor-count">
							<svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-briefcase"></use></svg>
							求人 1件
						</span>
						<span class="p-working__sponsor-more">詳しく見る →</span>
					</div>
					<!-- /.p-working__sponsor-foot -->
				</a>
				<!-- /.p-working__sponsor-card -->

			</div>
			<!-- /.p-working__sponsors-grid -->
		</div>
		<!-- /.p-working__sponsors-inner -->
	</section>
	<!-- /.p-working__sponsors -->

	<!-- ─── 求人一覧 ── -->
	<section class="p-working__listings" aria-labelledby="working-listings-title">
		<div class="p-working__listings-inner">

			<!-- フィルターサイドバー -->
			<aside class="p-working__filter" aria-label="求人絞り込み">
				<h2 class="p-working__filter-heading">
					<svg class="p-working__filter-heading-icon" aria-hidden="true" focusable="false" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
					絞り込み条件
				</h2>

				<div class="p-working__filter-group">
					<label class="p-working__filter-label" for="filter-keyword">キーワード検索</label>
					<div class="p-working__filter-search">
						<svg class="p-working__filter-search-icon" aria-hidden="true" focusable="false"><use href="#icon-search"></use></svg>
						<input class="p-working__filter-input" type="search" id="filter-keyword" placeholder="職種、会社名...">
					</div>
				</div>

				<div class="p-working__filter-group">
					<span class="p-working__filter-label">職種カテゴリー</span>
					<div class="c-chips" data-filter-group="category">
						<button class="c-chips__chip c-chips__chip--solid is-active" type="button" data-value="">すべて</button>
						<?php foreach ( $job_positions as $job_position ) : ?>
							<button class="c-chips__chip" type="button" data-value="<?php echo esc_attr( $job_position ); ?>"><?php echo esc_html( $job_position ); ?></button>
						<?php endforeach; ?>
					</div>
				</div>

				<div class="p-working__filter-group">
					<span class="p-working__filter-label">雇用形態</span>
					<div class="c-chips" data-filter-group="type">
						<button class="c-chips__chip c-chips__chip--solid is-active" type="button" data-value="">すべて</button>
						<?php foreach ( $job_type_choices as $job_type_label ) : ?>
							<button class="c-chips__chip" type="button" data-value="<?php echo esc_attr( $job_type_label ); ?>"><?php echo esc_html( $job_type_label ); ?></button>
						<?php endforeach; ?>
					</div>
				</div>
			</aside>
			<!-- /.p-working__filter -->

			<div class="p-working__main js-work-filter">
				<p class="p-working__count"><strong class="js-work-count"><?php echo esc_html( $job_query->found_posts ); ?></strong>件の求人</p>

				<div class="p-working__jobs">
				<?php if ( $job_query->have_posts() ) : ?>
					<?php while ( $job_query->have_posts() ) : $job_query->the_post(); ?>
						<?php get_template_part( 'template-parts/components/job-card', null, [ 'pickup' => in_array( get_the_ID(), $job_pickup_ids, true ) ] ); ?>
					<?php endwhile; ?>
				<?php else : ?>
					<p class="p-working__empty">現在募集中の求人はありません。</p>
				<?php endif; ?>
			</div>
			<!-- /.p-working__jobs -->


			</div>
			<!-- /.p-working__main -->

		</div>
		<!-- /.p-working__listings-inner -->
	</section>
	<!-- /.p-working__listings -->

<!-- ─── 求人モーダル ── -->
<?php if ( $job_query->have_posts() ) : ?>
	<?php while ( $job_query->have_posts() ) : $job_query->the_post(); ?>
		<?php get_template_part( 'template-parts/components/job-modal' ); ?>
		<?php schema_job( get_the_ID() ); // 求人の構造化データ（JobPosting） ?>
	<?php endwhile; ?>
<?php endif; ?>
<?php wp_reset_postdata(); ?>

</article>
<!-- /.p-working -->

<?php get_footer(); ?>
