<?php
/** 町のギャラリーページ */

$filter_slugs = isset( $_GET['cat'] ) ? array_filter( array_map( 'sanitize_text_field', (array) $_GET['cat'] ) ) : [];

// 1ページあたり表示件数（無限スクロール用）
$ppp = 12;

// メインクエリ（初回ロード）
$query_args = [
	'post_type'      => CPT_GALLERY,
	'posts_per_page' => $ppp,
	'paged'          => 1,
	'orderby'        => 'date',
	'order'          => 'DESC',
	'no_found_rows'  => false,
];
// 日本語スラッグは WP_Tax_Query で sanitize_title されるため term_id に変換
if ( $filter_slugs ) {
	$tids = [];
	foreach ( $filter_slugs as $s ) { $tid = sc_get_term_id_by_slug( $s, TAX_GALLERY_CAT ); if ( $tid ) $tids[] = $tid; }
	if ( $tids ) $query_args['tax_query'] = [ [ 'taxonomy' => TAX_GALLERY_CAT, 'field' => 'term_id', 'terms' => $tids, 'operator' => 'IN' ] ];
}
$gallery_query = new WP_Query( $query_args );
$total_count   = (int) $gallery_query->found_posts;
$max_pages     = (int) $gallery_query->max_num_pages;

$gallery_cats = get_terms( [
	'taxonomy'   => TAX_GALLERY_CAT,
	'hide_empty' => false,
] );


// featured: 今月のベスト（オプションページ選択 3件）
$featured_ids = function_exists( 'sc_get_gallery_best_ids' ) ? sc_get_gallery_best_ids() : [];
$featured     = $featured_ids ? get_posts( [
	'post_type'      => CPT_GALLERY,
	'post__in'       => $featured_ids,
	'orderby'        => 'post__in',
	'posts_per_page' => 3,
] ) : [];

get_header();
?>

<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>

<article class="p-gallery">

	<?php
		get_template_part( 'template-parts/components/page-hero', null, [
			'title' => '町のギャラリー',
			'sub'   => '七間町の風景を写真で楽しむ。',
		] );
		?>

	<!-- ─── イントロ ── -->
	<section class="p-gallery__intro">
		<div class="p-gallery__intro-inner">
			<p class="p-gallery__intro-text">七間町の四季折々の表情、商店街の日常、イベントの賑わい。地域の皆さんから寄せられた写真で、町の魅力をお届けします。</p>
		</div>
		<!-- /.p-gallery__intro-inner -->
	</section>
	<!-- /.p-gallery__intro -->

	<!-- ─── 注目フィーチャー（BEST + #2 + #3） ── -->
	<?php if ( $featured ) : ?>
	<section class="p-gallery__featured" aria-labelledby="gallery-featured-title">
		<h2 class="u-sr-only" id="gallery-featured-title">注目の写真</h2>
		<div class="p-gallery__featured-inner">
			<?php foreach ( $featured as $i => $post ) :
				$pid     = $post->ID;
				$img     = sc_thumbnail_url( $pid, 'large' ) ?: sc_no_image_url();
				$loc     = get_field( 'photo_location', $pid );
				$seasons      = get_the_terms( $pid, TAX_PHOTO_SEASON );
				$season       = ( $seasons && ! is_wp_error( $seasons ) ) ? $seasons[0]->name : '';
				$season_icon  = ( $seasons && ! is_wp_error( $seasons ) && function_exists( 'sc_get_term_icon' ) ) ? sc_get_term_icon( (int) $seasons[0]->term_id, TAX_PHOTO_SEASON ) : '';
				$rank    = $i === 0 ? 'BEST' : '#' . ( $i + 1 );
				$mod     = $i === 0 ? 'main' : 'sub';
			?>
			<a class="p-gallery__featured-card p-gallery__featured-card--<?php echo esc_attr( $mod ); ?>" href="<?php echo esc_url( get_permalink( $pid ) ); ?>">
				<div class="p-gallery__featured-img">
					<img class="p-gallery__featured-img-inner" src="<?php echo esc_url( $img ); ?>" alt="" aria-hidden="true" loading="lazy">
				</div>
				<!-- /.p-gallery__featured-img -->
				<div class="p-gallery__featured-body">
					<div class="p-gallery__featured-meta">
						<span class="p-gallery__featured-rank<?php echo $i === 0 ? ' is-best' : ''; ?>"><?php echo esc_html( $rank ); ?></span>
						<?php if ( $season ) : ?>
						<span class="p-gallery__featured-season">
							<?php if ( $season_icon ) : ?>
								<?php if ( strpos( $season_icon, 'icon-' ) === 0 ) : ?>
								<svg class="p-gallery__featured-season-icon" aria-hidden="true" focusable="false"><use href="#<?php echo esc_attr( $season_icon ); ?>"></use></svg>
								<?php else : ?>
								<span class="p-gallery__featured-season-icon" aria-hidden="true"><?php echo esc_html( $season_icon ); ?></span>
								<?php endif; ?>
							<?php endif; ?>
							<?php echo esc_html( $season ); ?>
						</span>
						<?php endif; ?>
					</div>
					<!-- /.p-gallery__featured-meta -->
					<h3 class="p-gallery__featured-title"><?php echo esc_html( get_the_title( $pid ) ); ?></h3>
					<?php if ( $loc ) : ?>
					<p class="p-gallery__featured-loc"><?php echo esc_html( $loc ); ?></p>
					<?php endif; ?>
				</div>
				<!-- /.p-gallery__featured-body -->
			</a>
			<!-- /.p-gallery__featured-card -->
			<?php endforeach; ?>
		</div>
		<!-- /.p-gallery__featured-inner -->
	</section>
	<!-- /.p-gallery__featured -->
	<?php endif; ?>

	<!-- ─── フィルターナビ + 件数・ビュー切替 ── -->
	<nav class="p-gallery__filter" aria-label="写真カテゴリー">
		<div class="p-gallery__filter-inner">
			<form id="js-gallery-filter" method="get" action="<?php echo esc_url( get_permalink() ); ?>">
			<div class="p-gallery__chips c-chips">
				<button type="button" class="c-chips__chip c-chips__chip--solid<?php echo ! $filter_slugs ? ' is-active' : ''; ?> js-chips-clear" data-group="cat">
					<svg class="p-gallery__chip-icon" aria-hidden="true" focusable="false"><use href="#icon-heart-solid"></use></svg>すべて
				</button>
				<?php if ( ! is_wp_error( $gallery_cats ) ) : foreach ( $gallery_cats as $cat ) :
					$icon = function_exists( 'sc_get_term_icon' ) ? sc_get_term_icon( (int) $cat->term_id, TAX_GALLERY_CAT ) : '';
					$icon = $icon ?: '📷';
				?>
				<label class="c-chips__chip c-chips__chip--solid<?php echo in_array( $cat->slug, $filter_slugs, true ) ? ' is-active' : ''; ?>"
				   data-term-id="<?php echo (int) $cat->term_id; ?>">
					<input type="checkbox" name="cat[]" value="<?php echo esc_attr( $cat->slug ); ?>"<?php echo in_array( $cat->slug, $filter_slugs, true ) ? ' checked' : ''; ?> style="position:absolute;opacity:0;width:1px;height:1px;">
					<?php if ( strpos( $icon, 'icon-' ) === 0 ) : ?>
					<svg class="p-gallery__chip-icon" aria-hidden="true" focusable="false"><use href="#<?php echo esc_attr( $icon ); ?>"></use></svg>
					<?php else : ?>
					<span class="p-gallery__chip-icon" aria-hidden="true"><?php echo esc_html( $icon ); ?></span>
					<?php endif; ?>
					<?php echo esc_html( $cat->name ); ?>
				</label>
				<?php endforeach; endif; ?>
			</div>
			<!-- /.p-gallery__chips -->
			</form>
			<script>
			(function () {
				var form = document.getElementById('js-gallery-filter');
				if (!form) return;
				form.querySelectorAll('input[type="checkbox"]').forEach(function (cb) {
					cb.addEventListener('change', function () {
						cb.closest('label').classList.toggle('is-active', cb.checked);
						form.submit();
					});
				});
				form.querySelectorAll('.js-chips-clear').forEach(function (btn) {
					btn.addEventListener('click', function () {
						var group = btn.dataset.group;
						form.querySelectorAll('input[name="' + group + '[]"]').forEach(function (cb) {
							cb.checked = false;
							cb.closest('label').classList.remove('is-active');
						});
						form.querySelectorAll('.js-chips-clear[data-group="' + group + '"]').forEach(function (b) { b.classList.add('is-active'); });
						form.submit();
					});
				});
			}());
			</script>
			<span class="p-gallery__count"><?php echo esc_html( $total_count ); ?>枚</span>
		</div>
		<!-- /.p-gallery__filter-inner -->
	</nav>
	<!-- /.p-gallery__filter -->

	<!-- ─── フォトグリッド ── -->
	<section class="p-gallery__grid-section" aria-labelledby="gallery-photos-title">
		<h2 class="u-sr-only" id="gallery-photos-title">写真一覧</h2>
		<div class="p-gallery__grid-inner">
			<?php if ( $gallery_query->have_posts() ) : ?>
			<div class="p-gallery__grid js-gallery-grid"
				data-initial-page="1"
				data-max-pages="<?php echo esc_attr( $max_pages ); ?>"
				data-ppp="<?php echo esc_attr( $ppp ); ?>"
				data-cats="<?php echo esc_attr( wp_json_encode( array_values( $filter_slugs ) ) ); ?>">
				<?php while ( $gallery_query->have_posts() ) : $gallery_query->the_post();
					get_template_part( 'template-parts/components/gallery-item', null, [ 'post_id' => get_the_ID() ] );
				endwhile; wp_reset_postdata(); ?>
			</div>
			<!-- /.p-gallery__grid -->
			<div class="p-gallery__sentinel js-gallery-sentinel" aria-hidden="true">
				<span class="p-gallery__sentinel-loader"></span>
			</div>
			<?php else : ?>
			<p class="p-gallery__empty">写真がまだありません。</p>
			<?php endif; ?>
		</div>
		<!-- /.p-gallery__grid-inner -->
	</section>
	<!-- /.p-gallery__grid-section -->

	<!-- ─── 写真投稿CTA ── -->
	<section class="p-gallery__submit" aria-labelledby="gallery-submit-title">
		<div class="p-gallery__submit-inner">
			<svg class="p-gallery__submit-icon" aria-hidden="true" focusable="false" width="40" height="40"><use href="#icon-camera"></use></svg>
			<h2 class="p-gallery__submit-title" id="gallery-submit-title">皆さんの写真を掲載しませんか？</h2>
			<p class="p-gallery__submit-text">七間町で撮影したお気に入りの一枚を、ギャラリーに掲載しませんか？商店街の日常、季節の風景、イベントの様子など、どんな写真でも大歓迎です。</p>
			<div class="p-gallery__submit-steps">
				<h3 class="p-gallery__submit-steps-title">応募方法</h3>
				<ol class="p-gallery__submit-steps-list">
					<li class="p-gallery__submit-step">
						<span class="p-gallery__submit-step-num">1</span>
						<span class="p-gallery__submit-step-text">下記のお問い合わせフォームから、写真データをお送りください</span>
					</li>
					<li class="p-gallery__submit-step">
						<span class="p-gallery__submit-step-num">2</span>
						<span class="p-gallery__submit-step-text">撮影者名（ニックネーム可）と写真のタイトルを添えてください</span>
					</li>
					<li class="p-gallery__submit-step">
						<span class="p-gallery__submit-step-num">3</span>
						<span class="p-gallery__submit-step-text">掲載は無料です。掲載時には事前にご連絡いたします</span>
					</li>
				</ol>
			</div>
			<!-- /.p-gallery__submit-steps -->
			<a class="c-btn c-btn--primary" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">
				写真を送る
				<svg class="c-btn__icon" aria-hidden="true" focusable="false"><use href="#icon-chevron-right"></use></svg>
			</a>
		</div>
		<!-- /.p-gallery__submit-inner -->
	</section>
	<!-- /.p-gallery__submit -->

<!-- ─── 拡大モーダル ── -->
<div class="c-modal p-gallery__modal" id="gallery-zoom-modal" hidden aria-hidden="true">
	<div class="c-modal__overlay" data-close></div>
	<button type="button" class="p-gallery__modal-nav p-gallery__modal-nav--prev js-gallery-prev" aria-label="前の写真">
		<svg aria-hidden="true" focusable="false" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
	</button>
	<div class="c-modal__dialog c-modal__dialog--lightbox" role="dialog" aria-modal="true" aria-labelledby="gallery-zoom-title">
		<button type="button" class="c-modal__close" data-close aria-label="閉じる">
			<svg aria-hidden="true" focusable="false"><use href="#icon-close"></use></svg>
		</button>
		<div class="p-gallery__modal-img">
			<img class="p-gallery__modal-image" src="" alt="" id="gallery-zoom-img">
		</div>
		<div class="p-gallery__modal-caption">
			<h2 class="p-gallery__modal-title" id="gallery-zoom-title"></h2>
			<p class="p-gallery__modal-author"></p>
			<p class="p-gallery__modal-counter"><span class="js-gallery-counter"></span></p>
		</div>
	</div>
	<button type="button" class="p-gallery__modal-nav p-gallery__modal-nav--next js-gallery-next" aria-label="次の写真">
		<svg aria-hidden="true" focusable="false" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
	</button>
</div>

</article>
<!-- /.p-gallery -->

<?php get_footer(); ?>
