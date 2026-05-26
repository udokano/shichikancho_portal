<?php
/** イベント個別ページ */
get_header();

if ( have_posts() ) : the_post();
	$pid       = get_the_ID();
	$start     = get_field( 'event_date_start', $pid );
	$end       = get_field( 'event_date_end', $pid );
	$time      = get_field( 'event_time', $pid );
	$venue     = get_field( 'event_venue', $pid );
	$address   = get_field( 'event_address', $pid );
	$fee       = get_field( 'event_fee', $pid );
	$capacity  = get_field( 'event_capacity', $pid );
	$organizer = get_field( 'event_organizer', $pid );
	$contact   = get_field( 'event_contact', $pid );
	$ext_url   = get_field( 'event_external_url', $pid );

	// 投稿者（WPユーザー）情報
	$author_id     = (int) get_post_field( 'post_author', $pid );
	$author_name   = get_the_author_meta( 'display_name', $author_id );
	$author_bio    = get_the_author_meta( 'description', $author_id );
	$author_avatar = get_avatar_url( $author_id, [ 'size' => 144 ] );
	$author_ig     = get_the_author_meta( 'instagram_url', $author_id );
	$author_fb     = get_the_author_meta( 'facebook_url', $author_id );

	$hero  = sc_thumbnail_url( $pid, 'large' );
	$cats  = get_the_terms( $pid, TAX_EVENT_CAT );
	$cats  = ( $cats && ! is_wp_error( $cats ) ) ? $cats : [];

	// 終了判定
	$today    = current_time( 'Y-m-d' );
	$end_cmp  = $end ?: $start;
	$is_ended = ( $end_cmp && $end_cmp < $today );

	// 開催日表示
	$date_display = '';
	if ( $start ) {
		$date_display = mysql2date( 'Y年n月j日', $start );
		if ( $end && $end !== $start ) {
			$date_display .= ' 〜 ' . mysql2date( 'n月j日', $end );
		}
	}

	// シェアURL
	$share_url   = get_permalink( $pid );
	$share_title = get_the_title( $pid );

	if ( function_exists( 'schema_event' ) ) schema_event( $pid );

	// 目次生成（h2/h3）
	$content_html = apply_filters( 'the_content', get_the_content() );
	$toc_items = [];
	if ( preg_match_all( '/<(h[23])[^>]*>(.+?)<\/\1>/i', $content_html, $matches, PREG_SET_ORDER ) ) {
		foreach ( $matches as $i => $m ) {
			$toc_items[] = [
				'level' => strtolower( $m[1] ),
				'text'  => wp_strip_all_tags( $m[2] ),
				'id'    => 'toc-' . ( $i + 1 ),
			];
		}
	}
	$toc_index = 0;
	$content_with_ids = preg_replace_callback(
		'/<(h[23])([^>]*)>/i',
		function( $m ) use ( &$toc_index, $toc_items ) {
			if ( ! isset( $toc_items[ $toc_index ] ) ) return $m[0];
			$id = $toc_items[ $toc_index ]['id'];
			$toc_index++;
			return '<' . $m[1] . $m[2] . ' id="' . esc_attr( $id ) . '">';
		},
		$content_html
	);
?>

<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>

<article class="p-event-single c-article">
	<div class="p-event-single__inner">

		<div class="c-article__layout">

			<div class="c-article__main">

				<!-- ヒーロー画像 -->
				<div class="c-article__hero">
					<picture class="u-picture-fill">
						<img class="u-img-cover" src="<?php echo esc_url( $hero ); ?>" alt="<?php echo esc_attr( get_the_title( $pid ) ); ?>" loading="eager" width="1200" height="675">
					</picture>
					<?php if ( $is_ended ) : ?>
					<span class="p-event-single__status c-article__status is-ended">終了</span>
					<?php endif; ?>
				</div>
				<!-- /.c-article__hero -->

				<?php
				$post_tags = get_the_terms( $pid, 'post_tag' );
				$post_tags = ( $post_tags && ! is_wp_error( $post_tags ) ) ? $post_tags : [];
				if ( $cats || $post_tags ) : ?>
				<?php $event_archive = get_post_type_archive_link( CPT_EVENT ); ?>
				<ul class="c-article__cats">
					<?php foreach ( $cats as $c ) : ?>
					<li><a class="c-tag c-tag--cat" href="<?php echo esc_url( add_query_arg( 'cat', $c->slug, $event_archive ) ); ?>"><?php echo esc_html( $c->name ); ?></a></li>
					<?php endforeach; ?>
					<?php foreach ( $post_tags as $t ) : ?>
					<li><a class="c-tag c-tag--tag" href="<?php echo esc_url( add_query_arg( 'tag', $t->slug, $event_archive ) ); ?>"><?php echo esc_html( $t->name ); ?></a></li>
					<?php endforeach; ?>
				</ul>
				<!-- /.c-article__cats -->
				<?php endif; ?>

				<div class="c-article__title-row">
					<h1 class="c-article__title"><?php the_title(); ?></h1>
				</div>
				<!-- /.c-article__title-row -->

				<dl class="p-event-single__keyinfo">
					<?php if ( $date_display ) : ?>
					<div class="p-event-single__keyinfo-item">
						<span class="p-event-single__keyinfo-icon" aria-hidden="true">
							<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
						</span>
						<div class="p-event-single__keyinfo-body">
							<dt>開催日</dt>
							<dd><?php echo esc_html( $date_display ); ?></dd>
						</div>
					</div>
					<?php endif; ?>

					<?php if ( $time ) : ?>
					<div class="p-event-single__keyinfo-item">
						<span class="p-event-single__keyinfo-icon" aria-hidden="true">
							<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
						</span>
						<div class="p-event-single__keyinfo-body">
							<dt>時間</dt>
							<dd><?php echo esc_html( $time ); ?></dd>
						</div>
					</div>
					<?php endif; ?>

					<?php if ( $venue ) : ?>
					<div class="p-event-single__keyinfo-item">
						<span class="p-event-single__keyinfo-icon" aria-hidden="true">
							<svg width="20" height="20"><use href="#icon-map-pin"></use></svg>
						</span>
						<div class="p-event-single__keyinfo-body">
							<dt>場所</dt>
							<dd><?php echo esc_html( $venue ); ?></dd>
						</div>
					</div>
					<?php endif; ?>

					<?php if ( $organizer ) : ?>
					<div class="p-event-single__keyinfo-item">
						<span class="p-event-single__keyinfo-icon" aria-hidden="true">
							<svg width="20" height="20"><use href="#icon-users-solid"></use></svg>
						</span>
						<div class="p-event-single__keyinfo-body">
							<dt>主催</dt>
							<dd><?php echo esc_html( $organizer ); ?></dd>
						</div>
					</div>
					<?php endif; ?>
				</dl>
				<!-- /.p-event-single__keyinfo -->

				<?php if ( ! empty( $toc_items ) ) : ?>
				<nav class="c-article__toc js-toc" aria-label="目次">
					<button class="c-article__toc-toggle js-toc-toggle" type="button" aria-expanded="true">
						<span class="c-article__toc-label">目次</span>
						<span class="c-article__toc-state">閉じる</span>
					</button>
					<ol class="c-article__toc-list">
						<?php $h2_count = 0; foreach ( $toc_items as $item ) : if ( $item['level'] === 'h2' ) $h2_count++; ?>
						<li class="c-article__toc-item c-article__toc-item--<?php echo esc_attr( $item['level'] ); ?>">
							<a href="#<?php echo esc_attr( $item['id'] ); ?>">
								<?php if ( $item['level'] === 'h2' ) : ?>
								<span class="c-article__toc-num"><?php echo esc_html( $h2_count ); ?>.</span>
								<?php else : ?>
								<span class="c-article__toc-bullet" aria-hidden="true">─</span>
								<?php endif; ?>
								<span class="c-article__toc-text"><?php echo esc_html( $item['text'] ); ?></span>
							</a>
						</li>
						<?php endforeach; ?>
					</ol>
				</nav>
				<!-- /.c-article__toc -->
				<?php endif; ?>

				<?php if ( $content_with_ids ) : ?>
				<div class="c-article__body">
					<?php echo $content_with_ids; ?>
				</div>
				<!-- /.c-article__body -->
				<?php endif; ?>

				<?php if ( $ext_url ) : ?>
				<div class="p-event-single__cta">
					<a class="c-btn c-btn--primary" href="<?php echo esc_url( $ext_url ); ?>" target="_blank" rel="noopener noreferrer">
						お申し込みはこちら
						<svg class="c-btn__icon" aria-hidden="true" focusable="false"><use href="#icon-external"></use></svg>
					</a>
				</div>
				<!-- /.p-event-single__cta -->
				<?php endif; ?>

				<?php if ( $author_name || $author_bio ) : ?>
				<aside class="c-article__author" aria-label="この記事を書いた人">
					<p class="c-article__author-label">この記事を書いた人</p>
					<div class="c-article__author-body">
						<span class="c-article__author-avatar">
							<img class="u-img-cover" src="<?php echo esc_url( $author_avatar ); ?>" alt="" aria-hidden="true" loading="lazy" width="72" height="72">
						</span>
						<div class="c-article__author-info">
							<?php if ( $author_name ) : ?>
							<p class="c-article__author-name"><?php echo esc_html( $author_name ); ?></p>
							<?php endif; ?>
							<?php if ( $author_bio ) : ?>
							<p class="c-article__author-bio"><?php echo nl2br( esc_html( $author_bio ) ); ?></p>
							<?php endif; ?>
							<?php if ( $author_ig || $author_fb ) : ?>
							<div class="c-article__author-sns">
								<?php if ( $author_ig ) : ?>
								<a class="c-article__author-sns-btn" href="<?php echo esc_url( $author_ig ); ?>" target="_blank" rel="noopener noreferrer">Instagram</a>
								<?php endif; ?>
								<?php if ( $author_fb ) : ?>
								<a class="c-article__author-sns-btn" href="<?php echo esc_url( $author_fb ); ?>" target="_blank" rel="noopener noreferrer">Facebook</a>
								<?php endif; ?>
							</div>
							<?php endif; ?>
						</div>
					</div>
				</aside>
				<!-- /.c-article__author -->
				<?php endif; ?>

				<div class="c-article__share c-article__share--bottom">
					<p class="c-article__share-text">このイベント情報をシェアする</p>
					<div class="c-article__share-row">
						<a class="c-btn c-btn--share is-line" href="https://social-plugins.line.me/lineit/share?url=<?php echo rawurlencode( $share_url ); ?>" target="_blank" rel="noopener noreferrer">LINEでシェア</a>
						<a class="c-btn c-btn--share is-x" href="https://twitter.com/intent/tweet?url=<?php echo rawurlencode( $share_url ); ?>&text=<?php echo rawurlencode( $share_title ); ?>" target="_blank" rel="noopener noreferrer">Xでシェア</a>
						<a class="c-btn c-btn--share is-fb" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo rawurlencode( $share_url ); ?>" target="_blank" rel="noopener noreferrer">Facebookでシェア</a>
						<button type="button" class="c-btn c-btn--share is-copy js-copy-url" data-url="<?php echo esc_attr( $share_url ); ?>">URLをコピー</button>
					</div>
				</div>
				<!-- /.c-article__share -->

			</div>
			<!-- /.c-article__main -->

				<?php get_template_part( 'template-parts/components/post-sidebar', null, [
					'post_type'   => CPT_EVENT,
					'taxonomy'    => TAX_EVENT_CAT,
					'current_id'  => $pid,
					'toc_items'   => $toc_items,
					'archive_url' => get_post_type_archive_link( CPT_EVENT ),
				] ); ?>

		</div>
		<!-- /.c-article__layout -->

		<?php
		$tax_query = [];
		if ( $cats ) {
			$tax_query = [ [
				'taxonomy' => TAX_EVENT_CAT,
				'field'    => 'term_id',
				'terms'    => wp_list_pluck( $cats, 'term_id' ),
			] ];
		}
		$related = new WP_Query( [
			'post_type'           => CPT_EVENT,
			'posts_per_page'      => 3,
			'post__not_in'        => [ $pid ],
			'ignore_sticky_posts' => 1,
			'no_found_rows'       => true,
			'tax_query'           => $tax_query,
		] );
		if ( $related->have_posts() ) : ?>
		<section class="c-article__related" aria-label="関連イベント">
			<h2 class="c-article__related-title">同エリアのおすすめイベント</h2>
			<div class="c-article__related-grid">
				<?php while ( $related->have_posts() ) : $related->the_post();
					$rid     = get_the_ID();
					$r_start = get_field( 'event_date_start', $rid );
					$r_thumb = sc_thumbnail_url( $rid, 'medium' );
				?>
				<a class="c-article__related-card" href="<?php the_permalink(); ?>">
					<span class="c-article__related-thumb">
						<picture class="u-picture-fill">
							<img class="u-img-cover" src="<?php echo esc_url( $r_thumb ); ?>" alt="" aria-hidden="true" loading="lazy" width="400" height="260">
						</picture>
					</span>
					<span class="c-article__related-body">
						<?php if ( $r_start ) : ?>
						<span class="c-article__related-date"><?php echo esc_html( mysql2date( 'Y/m/d', $r_start ) ); ?></span>
						<?php endif; ?>
						<span class="c-article__related-name"><?php the_title(); ?></span>
					</span>
				</a>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
			<!-- /.c-article__related-grid -->
			<div class="c-article__related-more">
				<a class="c-btn c-btn--primary" href="<?php echo esc_url( get_post_type_archive_link( CPT_EVENT ) ); ?>">すべてのイベントを見る</a>
			</div>
		</section>
		<!-- /.c-article__related -->
		<?php endif; ?>

		<!-- イベント掲載CTA -->
		<section class="c-article__cta-banner" aria-labelledby="event-cta-title">
			<h2 class="c-article__cta-banner-title" id="event-cta-title">イベントを掲載しませんか？</h2>
			<p class="c-article__cta-banner-text">七間町のイベント情報は、地域の活性化を応援するため、どなたでも掲載できます。町内のイベント、ワークショップ、マルシェなど、ぜひ情報をお寄せください。</p>
			<a class="c-btn c-btn--primary" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">イベント情報を投稿する</a>
		</section>
		<!-- /.c-article__cta-banner -->

	</div>
	<!-- /.p-event-single__inner -->
</article>
<!-- /.p-event-single -->

<?php endif; get_footer(); ?>
