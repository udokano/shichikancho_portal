<?php
/**
 * ギャラリー1件分の表示パーツ
 * 引数: $args['post_id']
 */
$pid = $args['post_id'] ?? get_the_ID();
if ( ! $pid ) return;

$img_url = sc_thumbnail_url( $pid, 'medium_large' );
$author  = get_field( 'photo_photographer', $pid ) ?: get_the_excerpt( $pid ) ?: get_the_author_meta( 'display_name', get_post_field( 'post_author', $pid ) );
$loc     = get_field( 'photo_location', $pid );
$seasons = get_the_terms( $pid, TAX_PHOTO_SEASON );
$season  = ( $seasons && ! is_wp_error( $seasons ) ) ? $seasons[0]->name : '';
$noimg   = sc_no_image_url();
$src     = $img_url ?: $noimg;
?>
<div class="p-gallery__item">
	<button type="button"
		class="p-gallery__item-img js-gallery-zoom"
		data-img="<?php echo esc_url( $img_url ); ?>"
		data-title="<?php echo esc_attr( get_the_title( $pid ) ); ?>"
		data-author="<?php echo esc_attr( $author ); ?>"
		aria-label="<?php echo esc_attr( get_the_title( $pid ) ); ?> を拡大表示">
		<img class="u-img-cover--transition" src="<?php echo esc_url( $src ); ?>" alt="" aria-hidden="true" loading="lazy" onerror="this.onerror=null;this.src='<?php echo esc_url( $noimg ); ?>';">
		<?php if ( $season ) : ?>
		<span class="p-gallery__item-season"><?php echo esc_html( $season ); ?></span>
		<?php endif; ?>
	</button>
	<!-- /.p-gallery__item-img -->
	<div class="p-gallery__item-body">
		<h3 class="p-gallery__item-title"><?php echo esc_html( get_the_title( $pid ) ); ?></h3>
		<?php if ( $loc ) : ?>
		<p class="p-gallery__item-loc"><?php echo esc_html( $loc ); ?></p>
		<?php endif; ?>
	</div>
	<!-- /.p-gallery__item-body -->
</div>
<!-- /.p-gallery__item -->
