<?php
/**
 * Block: event-lead
 * リード文 + シェアバー
 */

$lead_text  = get_field( 'event_lead_text' );
$show_share = get_field( 'event_lead_show_share' );
if ( $show_share === null || $show_share === '' ) {
	$show_share = true;
}

$pid         = get_the_ID();
$share_url   = $pid ? get_permalink( $pid ) : home_url( '/' );
$share_title = $pid ? get_the_title( $pid ) : get_bloginfo( 'name' );
?>
<?php if ( $lead_text || $show_share ) : ?>
<div class="c-article__lead">
	<?php if ( $lead_text ) : ?>
	<p class="c-article__lead-text"><?php echo nl2br( esc_html( $lead_text ) ); ?></p>
	<?php endif; ?>
	<?php if ( $show_share ) : ?>
	<div class="c-article__share">
		<span class="c-article__share-label">シェア:</span>
		<a class="c-article__share-btn is-line" href="https://social-plugins.line.me/lineit/share?url=<?php echo rawurlencode( $share_url ); ?>" target="_blank" rel="noopener noreferrer" aria-label="LINEでシェア">
			<svg width="16" height="16" fill="currentColor" aria-hidden="true"><use href="#icon-line"></use></svg>
		</a>
		<a class="c-article__share-btn is-x" href="https://twitter.com/intent/tweet?url=<?php echo rawurlencode( $share_url ); ?>&text=<?php echo rawurlencode( $share_title ); ?>" target="_blank" rel="noopener noreferrer" aria-label="Xでシェア">
			<svg width="14" height="14" fill="currentColor" aria-hidden="true"><use href="#icon-x"></use></svg>
		</a>
		<a class="c-article__share-btn is-fb" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo rawurlencode( $share_url ); ?>" target="_blank" rel="noopener noreferrer" aria-label="Facebookでシェア">
			<svg width="16" height="16" fill="currentColor" aria-hidden="true"><use href="#icon-facebook"></use></svg>
		</a>
	</div>
	<!-- /.c-article__share -->
	<?php endif; ?>
</div>
<!-- /.c-article__lead -->
<?php endif; ?>
