<?php
/**
 * スポット一覧アーカイブ
 */

$filter_types = isset( $_GET['type'] ) ? array_filter( array_map( 'sanitize_text_field', (array) $_GET['type'] ) ) : [];

$args = [
	'post_type'      => CPT_SPOT,
	'posts_per_page' => -1,
	'orderby'        => 'date',
	'order'          => 'DESC',
];
// 日本語スラッグは WP_Tax_Query で sanitize_title されるため term_id に変換
// sc_get_term_id_by_slug() は $wpdb 直クエリで日本語スラッグも正確に引く
if ( $filter_types ) {
	$tids = [];
	foreach ( $filter_types as $s ) { $tid = sc_get_term_id_by_slug( $s, TAX_SPOT_TYPE ); if ( $tid ) $tids[] = $tid; }
	if ( $tids ) $args['tax_query'] = [ [ 'taxonomy' => TAX_SPOT_TYPE, 'field' => 'term_id', 'terms' => $tids, 'operator' => 'IN' ] ];
}

$spot_query  = new WP_Query( $args );
$spot_types  = get_terms( [ 'taxonomy' => TAX_SPOT_TYPE, 'hide_empty' => false ] );

// spot_type 名 → SVG スプライト ID
$type_icons = [
	'観る'   => 'icon-eye',
	'買う'   => 'icon-shopping-bag',
	'食べる' => 'icon-utensils',
	'泊まる' => 'icon-bed',
	'知る'   => 'icon-graduation-cap',
	'その他' => 'icon-ellipsis',
];

get_header();
?>
<main id="main-content">

	<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>

	<?php
		get_template_part( 'template-parts/components/page-hero', null, [
			'title' => '観光スポット',
			'sub'   => '七間町の魅力的なスポットをカテゴリー別にご紹介します。',
		] );
		?>

	<section class="p-spot-archive">
		<div class="p-spot-archive__inner">
			<h2 class="p-spot-archive__list-title">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
				観光スポット一覧
			</h2>
			<p class="p-spot-archive__list-sub">SPOT</p>

			<!-- タイプ別タブ -->
			<?php if ( ! is_wp_error( $spot_types ) && $spot_types ) : ?>
			<form id="js-spot-filter" method="get" action="<?php echo esc_url( get_post_type_archive_link( CPT_SPOT ) ); ?>">
			<div class="p-spot-type-tabs">
				<button type="button" class="p-spot-type-tab<?php echo ! $filter_types ? ' is-active' : ''; ?> js-chips-clear" data-group="type">
					<svg class="p-spot-type-tab__icon" aria-hidden="true" focusable="false" viewBox="0 0 24 24"><use href="#icon-eye"></use></svg>
					<span class="p-spot-type-tab__label">すべて</span>
				</button>
				<?php foreach ( $spot_types as $type ) :
					$icon_id = $type_icons[ $type->name ] ?? 'icon-map-pin';
				?>
				<label class="p-spot-type-tab<?php echo in_array( $type->slug, $filter_types, true ) ? ' is-active' : ''; ?>">
				<input class="u-sr-only" type="checkbox" name="type[]" value="<?php echo esc_attr( $type->slug ); ?>"<?php echo in_array( $type->slug, $filter_types, true ) ? ' checked' : ''; ?>>
					<svg class="p-spot-type-tab__icon" aria-hidden="true" focusable="false" viewBox="0 0 24 24"><use href="#<?php echo esc_attr( $icon_id ); ?>"></use></svg>
					<span class="p-spot-type-tab__label"><?php echo esc_html( $type->name ); ?></span>
				</label>
				<?php endforeach; ?>
			</div>
			</form>
			<script>
			(function () {
				var form = document.getElementById('js-spot-filter');
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
			<?php endif; ?>

			<!-- 適用中の絞り込みチップ -->
			<?php
			$active_chips = [];
			$base_url     = get_post_type_archive_link( CPT_SPOT );
			$qs_all       = $_GET; unset( $qs_all['paged'] );
			foreach ( $filter_types as $slug ) {
				$tid  = sc_get_term_id_by_slug( $slug, TAX_SPOT_TYPE );
				$term = $tid ? get_term( $tid, TAX_SPOT_TYPE ) : null;
				if ( $term && ! is_wp_error( $term ) ) {
					$remaining = array_values( array_diff( $filter_types, [ $slug ] ) );
					$qs = $qs_all;
					if ( $remaining ) { $qs['type'] = $remaining; } else { unset( $qs['type'] ); }
					$active_chips[] = [ 'label' => $term->name, 'remove' => $qs ? add_query_arg( $qs, $base_url ) : $base_url ];
				}
			}
			get_template_part( 'template-parts/components/active-filters', null, [
				'chips'     => $active_chips,
				'clear_url' => $base_url,
			] );
			?>

			<!-- スポットグリッド -->
			<?php if ( $spot_query->have_posts() ) : ?>
			<p class="p-spot-archive__count">
				全<?php echo esc_html( $spot_query->found_posts ); ?>件
			</p>
			<div class="p-spot-grid">
				<?php while ( $spot_query->have_posts() ) : $spot_query->the_post();
					$thumb      = sc_thumbnail_url( get_the_ID(), 'medium_large' );
					$types      = get_the_terms( get_the_ID(), TAX_SPOT_TYPE );
					$type_name  = ( $types && ! is_wp_error( $types ) ) ? $types[0]->name : '';
				?>
				<a class="p-spot-card" href="<?php the_permalink(); ?>">
					<div class="p-spot-card__img">
						<?php if ( $thumb ) : ?>
						<img class="u-img-cover--transition" src="<?php echo esc_url( $thumb ); ?>" alt="" aria-hidden="true" loading="lazy">
						<?php endif; ?>
					</div>
					<!-- /.p-spot-card__img -->
					<div class="p-spot-card__body">
						<h3 class="p-spot-card__title"><?php the_title(); ?></h3>
						<?php if ( $type_name ) : ?>
						<p class="p-spot-card__type"><?php echo esc_html( $type_name ); ?></p>
						<?php endif; ?>
						<p class="p-spot-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 40 ) ); ?></p>
					</div>
					<!-- /.p-spot-card__body -->
				</a>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
			<?php else : ?>
			<p class="p-spot-archive__empty">スポットが見つかりませんでした。</p>
			<?php endif; ?>

			<div class="p-spot-archive__back">
				<a class="c-btn c-btn--outline" href="<?php echo esc_url( home_url( '/tourism/' ) ); ?>">観光情報ページへ戻る →</a>
			</div>
		</div>
	</section>

</main>
<?php get_footer(); ?>
