<?php
/** スポット個別ページ（c-article 2カラム構成） */
get_header();

if ( have_posts() ) : the_post();
	$pid       = get_the_ID();
	$desc      = get_field( 'spot_description', $pid );
	$gallery   = get_field( 'spot_gallery', $pid );
	$address   = get_field( 'spot_address', $pid );
	$hours     = get_field( 'spot_hours', $pid );
	$fee       = get_field( 'spot_fee', $pid );
	$lat       = get_field( 'spot_map_lat', $pid );
	$lng       = get_field( 'spot_map_lng', $pid );
	$duration  = get_field( 'spot_duration', $pid );
	$season    = get_field( 'spot_season', $pid );
	$related   = get_field( 'spot_related_spots', $pid );

	// アイキャッチ未設定時は MV 自体を出さない（No Image は表示しない）
	$hero  = get_the_post_thumbnail_url( $pid, 'large' ) ?: '';
	$types = get_the_terms( $pid, TAX_SPOT_TYPE );
	$types = ( $types && ! is_wp_error( $types ) ) ? $types : [];
	$areas = get_the_terms( $pid, TAX_AREA );
	$areas = ( $areas && ! is_wp_error( $areas ) ) ? $areas : [];

	$share_url   = get_permalink( $pid );
	$share_title = get_the_title( $pid );
	$published   = get_the_date( 'Y年n月j日', $pid );
	$modified    = get_the_modified_date( 'Y年n月j日', $pid );

	if ( function_exists( 'schema_spot' ) ) schema_spot( $pid );
?>

<?php
get_template_part( 'template-parts/components/page-hero', null, [
	'title' => '観光スポット',
	'sub'   => '静岡市のまんなかにある城下町、七間町。',
] );
?>

<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>

<article class="p-spot-detail c-article">
	<div class="p-spot-detail__inner">

		<div class="c-article__layout">

			<div class="c-article__main">

				<!-- 日付メタ -->
				<div class="c-article__meta">
					<span class="c-article__meta-item">
						<svg width="14" height="14" aria-hidden="true" focusable="false" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
						公開日: <?php echo esc_html( $published ); ?>
					</span>
					<?php if ( $modified !== $published ) : ?>
					<span class="c-article__meta-item">
						<svg width="14" height="14" aria-hidden="true" focusable="false" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
						更新日: <?php echo esc_html( $modified ); ?>
					</span>
					<?php endif; ?>
				</div>
				<!-- /.c-article__meta -->

				<!-- タイトル -->
				<div class="c-article__title-row">
					<h1 class="c-article__title"><?php the_title(); ?></h1>
				</div>

				<!-- カテゴリ（スポットタイプ + エリア） -->
				<?php if ( $types || $areas ) : ?>
				<?php $spot_archive = get_post_type_archive_link( CPT_SPOT ); ?>
				<ul class="c-article__cats">
					<?php foreach ( $types as $t ) : ?>
					<li><a class="c-tag c-tag--cat" href="<?php echo esc_url( add_query_arg( 'type', $t->slug, $spot_archive ) ); ?>"><?php echo esc_html( $t->name ); ?></a></li>
					<?php endforeach; ?>
					<?php foreach ( $areas as $a ) : ?>
					<li><a class="c-tag c-tag--tag" href="<?php echo esc_url( add_query_arg( 'area', $a->slug, $spot_archive ) ); ?>"><?php echo esc_html( $a->name ); ?></a></li>
					<?php endforeach; ?>
				</ul>
				<?php endif; ?>

				<!-- メインビジュアル: gallery あり = スライダー / なし = アイキャッチ単一 -->
				<?php
				$mv_images = [];
				if ( $gallery && is_array( $gallery ) ) {
					foreach ( $gallery as $g ) {
						$mv_images[] = [
							'url'   => $g['sizes']['large'] ?? $g['url'],
							'thumb' => $g['sizes']['thumbnail'] ?? $g['url'],
							'alt'   => $g['alt'] ?? '',
						];
					}
				} elseif ( $hero ) {
					$mv_images[] = [ 'url' => $hero, 'thumb' => $hero, 'alt' => $share_title ];
				}
				$mv_count = count( $mv_images );
				?>
				<?php if ( $mv_count === 1 ) : ?>
				<div class="p-spot-detail__mv p-spot-detail__mv--single">
					<picture class="u-picture-fill">
						<img class="u-img-cover" src="<?php echo esc_url( $mv_images[0]['url'] ); ?>" alt="<?php echo esc_attr( $mv_images[0]['alt'] ); ?>" loading="eager" width="1200" height="675">
					</picture>
				</div>
				<!-- /.p-spot-detail__mv -->
				<?php elseif ( $mv_count > 1 ) : ?>
				<div class="p-spot-detail__mv p-spot-detail__mv--slider js-spot-mv" data-count="<?php echo (int) $mv_count; ?>">
					<div class="p-spot-detail__mv-stage">
						<?php foreach ( $mv_images as $i => $img ) : ?>
						<button type="button" class="p-spot-detail__mv-slide<?php echo $i === 0 ? ' is-active' : ''; ?>" data-index="<?php echo (int) $i; ?>" aria-hidden="<?php echo $i === 0 ? 'false' : 'true'; ?>" tabindex="-1">
							<img class="u-img-cover" src="<?php echo esc_url( $img['url'] ); ?>" alt="<?php echo esc_attr( $img['alt'] ); ?>" loading="<?php echo $i === 0 ? 'eager' : 'lazy'; ?>" width="1200" height="675">
						</button>
						<?php endforeach; ?>
						<button type="button" class="p-spot-detail__mv-nav p-spot-detail__mv-nav--prev js-spot-mv-prev" aria-label="前の画像">
							<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="15 18 9 12 15 6"/></svg>
						</button>
						<button type="button" class="p-spot-detail__mv-nav p-spot-detail__mv-nav--next js-spot-mv-next" aria-label="次の画像">
							<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="9 18 15 12 9 6"/></svg>
						</button>
						<span class="p-spot-detail__mv-counter" aria-live="polite"><span class="js-spot-mv-current">1</span>/<?php echo (int) $mv_count; ?></span>
					</div>
					<ul class="p-spot-detail__mv-thumbs" role="tablist">
						<?php foreach ( $mv_images as $i => $img ) : ?>
						<li>
							<button type="button" class="p-spot-detail__mv-thumb<?php echo $i === 0 ? ' is-active' : ''; ?>" data-index="<?php echo (int) $i; ?>" aria-label="画像 <?php echo (int) ( $i + 1 ); ?>" aria-selected="<?php echo $i === 0 ? 'true' : 'false'; ?>" role="tab">
								<img class="u-img-cover" src="<?php echo esc_url( $img['thumb'] ); ?>" alt="" aria-hidden="true" loading="lazy" width="120" height="84">
							</button>
						</li>
						<?php endforeach; ?>
					</ul>
				</div>
				<!-- /.p-spot-detail__mv -->
				<?php endif; ?>

				<!-- 紹介文 -->
				<?php if ( $desc ) : ?>
				<h2 class="c-heading-line">スポット紹介</h2>
				<div class="c-article__body">
					<p><?php echo wp_kses( $desc, [ 'br' => [] ] ); ?></p>
				</div>
				<?php endif; ?>

				<!-- 基本情報 -->
				<dl class="p-spot-detail__info">
					<?php if ( $address ) : ?>
					<div class="p-spot-detail__info-row">
						<dt>住所</dt><dd><?php echo esc_html( $address ); ?></dd>
					</div>
					<?php endif; ?>
					<?php if ( $hours ) : ?>
					<div class="p-spot-detail__info-row">
						<dt>営業時間</dt><dd><?php echo esc_html( $hours ); ?></dd>
					</div>
					<?php endif; ?>
					<?php if ( $fee ) : ?>
					<div class="p-spot-detail__info-row">
						<dt>料金</dt><dd><?php echo esc_html( $fee ); ?></dd>
					</div>
					<?php endif; ?>
					<?php if ( $duration ) : ?>
					<div class="p-spot-detail__info-row">
						<dt>所要時間</dt><dd><?php echo esc_html( $duration ); ?></dd>
					</div>
					<?php endif; ?>
					<?php if ( $season ) :
						$season_labels = [ 'spring' => '春', 'summer' => '夏', 'autumn' => '秋', 'winter' => '冬', 'all' => '通年' ];
						$labels        = array_filter( array_map( fn( $s ) => $season_labels[ $s ] ?? null, (array) $season ) );
						if ( $labels ) : ?>
					<div class="p-spot-detail__info-row">
						<dt>おすすめ</dt><dd><?php echo esc_html( implode( ' / ', $labels ) ); ?></dd>
					</div>
					<?php endif; endif; ?>
				</dl>

<!-- 地図 -->
				<?php if ( $lat && $lng ) : ?>
				<section class="p-spot-detail__map">
					<h2 class="c-heading-line">地図</h2>
					<div class="p-spot-detail__map-frame">
						<iframe src="https://www.google.com/maps?q=<?php echo esc_attr( $lat ); ?>,<?php echo esc_attr( $lng ); ?>&output=embed" loading="lazy" title="<?php echo esc_attr( $share_title ); ?> 地図" referrerpolicy="no-referrer-when-downgrade"></iframe>
					</div>
				</section>
				<?php endif; ?>

				<!-- シェア -->
				<div class="c-article__share c-article__share--bottom">
					<p class="c-article__share-text">このスポット情報をシェアする</p>
					<div class="c-article__share-row">
						<a class="c-btn c-btn--share is-line" href="https://social-plugins.line.me/lineit/share?url=<?php echo rawurlencode( $share_url ); ?>" target="_blank" rel="noopener noreferrer">LINEでシェア</a>
						<a class="c-btn c-btn--share is-x" href="https://twitter.com/intent/tweet?url=<?php echo rawurlencode( $share_url ); ?>&text=<?php echo rawurlencode( $share_title ); ?>" target="_blank" rel="noopener noreferrer">Xでシェア</a>
						<a class="c-btn c-btn--share is-fb" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo rawurlencode( $share_url ); ?>" target="_blank" rel="noopener noreferrer">Facebookでシェア</a>
						<button type="button" class="c-btn c-btn--share is-copy js-copy-url" data-url="<?php echo esc_attr( $share_url ); ?>">URLをコピー</button>
					</div>
				</div>

				<!-- 同じカテゴリーのスポット（spot_type 一致で自動取得） -->
				<?php
				$same_q = null;
				$type_terms = get_the_terms( $pid, TAX_SPOT_TYPE );
				if ( $type_terms && ! is_wp_error( $type_terms ) ) {
					$same_q = new WP_Query( [
						'post_type'           => CPT_SPOT,
						'posts_per_page'      => 3,
						'post__not_in'        => [ $pid ],
						'tax_query'           => [ [
							'taxonomy' => TAX_SPOT_TYPE,
							'field'    => 'term_id',
							'terms'    => wp_list_pluck( $type_terms, 'term_id' ),
						] ],
						'ignore_sticky_posts' => 1,
						'no_found_rows'       => true,
					] );
				}
				?>
				<?php if ( $same_q && $same_q->have_posts() ) : ?>
				<section class="c-article__related" aria-label="同じカテゴリーのスポット">
					<h2 class="c-heading-line">同じカテゴリーのスポット</h2>
					<div class="c-article__related-grid">
						<?php while ( $same_q->have_posts() ) : $same_q->the_post();
							$rid    = get_the_ID();
							$rthumb = sc_thumbnail_url( $rid, 'medium' );
							$rdesc  = get_field( 'spot_description', $rid );
							$rorg   = get_field( 'spot_organization', $rid );
						?>
						<a class="c-article__related-card" href="<?php the_permalink(); ?>">
							<span class="c-article__related-thumb">
								<picture class="u-picture-fill">
									<img class="u-img-cover" src="<?php echo esc_url( $rthumb ); ?>" alt="" aria-hidden="true" loading="lazy" width="400" height="260">
								</picture>
							</span>
							<span class="c-article__related-body">
								<span class="c-article__related-name"><?php the_title(); ?></span>
								<?php if ( $rorg ) : ?>
								<span class="c-article__related-meta"><?php echo esc_html( $rorg ); ?></span>
								<?php elseif ( $rdesc ) : ?>
								<span class="c-article__related-meta"><?php echo esc_html( wp_trim_words( wp_strip_all_tags( $rdesc ), 28, '…' ) ); ?></span>
								<?php endif; ?>
							</span>
						</a>
						<?php endwhile; wp_reset_postdata(); ?>
					</div>
				</section>
				<?php endif; ?>

				<!-- 口コミ・レビュー -->
				<section class="p-spot-detail__reviews" aria-label="口コミ・レビュー">
					<?php
					$comments_data = get_comments( [ 'post_id' => $pid, 'status' => 'approve', 'type' => 'comment' ] );
					$ratings  = array_filter( array_map( function( $c ) { return (int) get_comment_meta( $c->comment_ID, 'sc_rating', true ); }, $comments_data ) );
					$avg      = $ratings ? array_sum( $ratings ) / count( $ratings ) : 0;
					$rcount   = count( $ratings );

					if ( ! function_exists( 'sc_render_stars' ) ) {
						function sc_render_stars( $rating, $size = 16 ) {
							$rating = max( 0, min( 5, (float) $rating ) );
							$out    = '<span class="c-stars" aria-hidden="true">';
							for ( $i = 1; $i <= 5; $i++ ) {
								$cls = $i <= $rating ? 'is-on' : '';
								$out .= '<svg class="c-stars__star ' . $cls . '" width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>';
							}
							$out .= '</span>';
							return $out;
						}
					}
					?>
					<h2 class="c-heading-line">口コミ・レビュー</h2>
					<div class="p-spot-detail__reviews-summary">
						<?php echo sc_render_stars( $avg, 18 ); ?>
						<span class="p-spot-detail__reviews-avg"><?php echo esc_html( number_format( $avg, 1 ) ); ?></span>
						<span class="p-spot-detail__reviews-count">（<?php echo (int) $rcount; ?>件のレビュー）</span>
					</div>

					<?php if ( comments_open( $pid ) ) : ?>
					<div class="p-spot-detail__review-form">
						<h3 class="p-spot-detail__review-form-title">レビューを投稿する</h3>
						<?php
						comment_form( [
							'title_reply'         => '',
							'title_reply_before'  => '',
							'title_reply_after'   => '',
							'class_form'          => 'p-spot-detail__review-form-body',
							'label_submit'        => 'レビューを投稿',
							'submit_button'       => '<button type="submit" class="c-btn c-btn--primary p-spot-detail__review-submit"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>%4$s</button>',
							'comment_field'       => '<p class="comment-form-comment"><label for="comment">コメント</label><textarea id="comment" name="comment" rows="4" placeholder="' . esc_attr( $share_title ) . 'の感想をお聞かせください..." required></textarea></p>',
							'fields' => [
								'author' => '<p class="comment-form-author"><label for="author">お名前</label><input id="author" name="author" type="text" placeholder="山田太郎" required></p>',
								'rating' => '<p class="comment-form-rating"><label>評価</label><span class="p-spot-detail__rating-input js-rating-input">' .
									implode( '', array_map( function( $i ) {
										return '<button type="button" class="p-spot-detail__rating-star" data-value="' . $i . '" aria-label="' . $i . 'つ星"><svg width="22" height="22" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg></button>';
									}, range( 1, 5 ) ) ) .
									'<input type="hidden" name="sc_rating" value="0" required></span></p>',
							],
						] );
						?>
					</div>
					<?php endif; ?>

					<h2 class="c-heading-line">すべてのレビュー</h2>
					<?php if ( $comments_data ) : ?>
					<ol class="p-spot-detail__review-list">
						<?php foreach ( $comments_data as $c ) :
							$crating = (int) get_comment_meta( $c->comment_ID, 'sc_rating', true );
						?>
						<li class="p-spot-detail__review-item">
							<header class="p-spot-detail__review-head">
								<span class="p-spot-detail__review-author"><?php echo esc_html( $c->comment_author ); ?></span>
								<?php if ( $crating ) echo sc_render_stars( $crating, 14 ); ?>
								<time class="p-spot-detail__review-date" datetime="<?php echo esc_attr( get_comment_date( 'c', $c ) ); ?>"><?php echo esc_html( get_comment_date( 'Y/m/d', $c ) ); ?></time>
							</header>
							<div class="p-spot-detail__review-body"><?php echo wp_kses_post( wpautop( $c->comment_content ) ); ?></div>
						</li>
						<?php endforeach; ?>
					</ol>
					<?php else : ?>
					<p class="p-spot-detail__review-empty">まだレビューがありません。最初のレビューを投稿してみませんか？</p>
					<?php endif; ?>
				</section>
				<!-- /.p-spot-detail__reviews -->

				<!-- スポット一覧へ戻る -->
				<div class="p-spot-detail__back">
					<a class="p-spot-detail__back-btn" href="<?php echo esc_url( get_post_type_archive_link( CPT_SPOT ) ); ?>">
						<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="15 18 9 12 15 6"/></svg>
						スポット一覧へ戻る
					</a>
				</div>

			</div>
			<!-- /.c-article__main -->

			<?php
			// 人気スポット（閲覧数があれば閲覧順、無ければ日付降順）
			$popular_q = new WP_Query( [
				'post_type'           => CPT_SPOT,
				'posts_per_page'      => 5,
				'post__not_in'        => [ $pid ],
				'meta_query'          => [
					'relation' => 'OR',
					[ 'key' => 'sc_post_views', 'compare' => 'EXISTS' ],
					[ 'key' => 'sc_post_views', 'compare' => 'NOT EXISTS' ],
				],
				'orderby'             => [ 'meta_value_num' => 'DESC', 'date' => 'DESC' ],
				'meta_key'            => 'sc_post_views',
				'ignore_sticky_posts' => 1,
				'no_found_rows'       => true,
			] );
			// フォールバック: それでも0件なら meta 抜きで取得
			if ( ! $popular_q->have_posts() ) {
				$popular_q = new WP_Query( [
					'post_type'           => CPT_SPOT,
					'posts_per_page'      => 5,
					'post__not_in'        => [ $pid ],
					'orderby'             => 'date',
					'order'               => 'DESC',
					'ignore_sticky_posts' => 1,
					'no_found_rows'       => true,
				] );
			}
			?>
			<?php
			$sidebar_types = get_terms( [ 'taxonomy' => TAX_SPOT_TYPE, 'hide_empty' => false ] );
			$sidebar_areas = get_terms( [ 'taxonomy' => TAX_AREA, 'hide_empty' => false ] );
			$spot_archive  = get_post_type_archive_link( CPT_SPOT );
			?>
			<aside class="c-post-sidebar" aria-label="サイドバー">

				<?php if ( $popular_q->have_posts() ) : ?>
				<section class="c-post-sidebar__widget">
					<h3 class="c-post-sidebar__title">
						<svg class="c-post-sidebar__title-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11.562 3.266a.5.5 0 0 1 .876 0L15.39 8.87a1 1 0 0 0 1.516.294L21.183 5.5a.5.5 0 0 1 .798.519l-2.834 10.246a1 1 0 0 1-.956.734H5.81a1 1 0 0 1-.957-.734L2.02 6.02a.5.5 0 0 1 .798-.519l4.276 3.664a1 1 0 0 0 1.516-.294z"/><path d="M5 21h14"/></svg>
						人気スポット
					</h3>
					<ol class="c-post-sidebar__rank">
						<?php $rank = 0; while ( $popular_q->have_posts() ) : $popular_q->the_post(); $rank++;
							$rid     = get_the_ID();
							$rtypes  = get_the_terms( $rid, TAX_SPOT_TYPE );
							$rtype   = ( $rtypes && ! is_wp_error( $rtypes ) ) ? $rtypes[0]->name : '';
							$rviews  = (int) get_post_meta( $rid, 'sc_post_views', true );
						?>
						<li class="c-post-sidebar__rank-item">
							<a class="c-post-sidebar__rank-link" href="<?php the_permalink(); ?>">
								<span class="c-post-sidebar__rank-num c-post-sidebar__rank-num--<?php echo esc_attr( $rank ); ?>"><?php echo (int) $rank; ?></span>
								<span class="c-post-sidebar__rank-body">
									<span class="c-post-sidebar__rank-title"><?php the_title(); ?></span>
									<span class="c-post-sidebar__rank-meta">
										<?php if ( $rtype ) echo esc_html( $rtype ) . ' ・ '; ?><?php echo number_format( $rviews ); ?>回閲覧
									</span>
								</span>
							</a>
						</li>
						<?php endwhile; wp_reset_postdata(); ?>
					</ol>
				</section>
				<?php endif; ?>

				<?php if ( ! is_wp_error( $sidebar_types ) && $sidebar_types ) : ?>
				<section class="c-post-sidebar__widget">
					<h3 class="c-post-sidebar__title">
						<svg class="c-post-sidebar__title-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
						種別から探す
					</h3>
					<ul class="c-post-sidebar__taglist">
						<?php foreach ( $sidebar_types as $t ) : ?>
						<li><a class="c-tag c-tag--cat" href="<?php echo esc_url( add_query_arg( 'type', $t->slug, $spot_archive ) ); ?>"><?php echo esc_html( $t->name ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				</section>
				<?php endif; ?>

				<?php if ( ! is_wp_error( $sidebar_areas ) && $sidebar_areas ) : ?>
				<section class="c-post-sidebar__widget">
					<h3 class="c-post-sidebar__title">
						<svg class="c-post-sidebar__title-icon" width="18" height="18" aria-hidden="true"><use href="#icon-map-pin"></use></svg>
						エリアから探す
					</h3>
					<ul class="c-post-sidebar__taglist">
						<?php foreach ( $sidebar_areas as $a ) : ?>
						<li><a class="c-tag c-tag--tag" href="<?php echo esc_url( add_query_arg( 'area', $a->slug, $spot_archive ) ); ?>"><?php echo esc_html( $a->name ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				</section>
				<?php endif; ?>

			</aside>
			<!-- /.c-post-sidebar -->

		</div>
		<!-- /.c-article__layout -->


	</div>
	<!-- /.p-spot-detail__inner -->
</article>
<!-- /.p-spot-detail -->

<?php endif; get_footer(); ?>
