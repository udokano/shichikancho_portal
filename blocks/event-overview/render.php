<?php
/**
 * Block: event-overview
 * 開催概要テーブル
 */

$date_text  = get_field( 'overview_date_text' );
$time       = get_field( 'overview_time' );
$venue      = get_field( 'overview_venue' );
$organizer  = get_field( 'overview_organizer' );
$fee        = get_field( 'overview_fee' );
$notes      = get_field( 'overview_notes' );
$contact    = get_field( 'overview_contact' );
$map_url    = get_field( 'overview_map_url' );

$has_any = ( $date_text || $time || $venue || $organizer || $fee || $notes || $contact || $map_url );
if ( ! $has_any ) {
	return;
}

// お問い合わせ先 自動リンク
$contact_html = '';
if ( $contact ) {
	$trimmed = trim( $contact );
	if ( is_email( $trimmed ) ) {
		$contact_html = '<a href="mailto:' . esc_attr( $trimmed ) . '">' . esc_html( $trimmed ) . '</a>';
	} elseif ( preg_match( '/^[\d\-+() ]+$/', $trimmed ) ) {
		$tel = preg_replace( '/[^\d+]/', '', $trimmed );
		$contact_html = '<a href="tel:' . esc_attr( $tel ) . '">' . esc_html( $trimmed ) . '</a>';
	} else {
		$contact_html = wp_kses( $contact, [ 'br' => [], 'a' => [ 'href' => [], 'target' => [], 'rel' => [] ] ] );
	}
}
?>
<div class="p-event-single__overview">
	<table class="p-event-single__overview-table">
		<tbody>
			<?php if ( $date_text ) : ?>
			<tr><th>開催日</th><td><?php echo esc_html( $date_text ); ?></td></tr>
			<?php endif; ?>
			<?php if ( $time ) : ?>
			<tr><th>時間</th><td><?php echo esc_html( $time ); ?></td></tr>
			<?php endif; ?>
			<?php if ( $venue ) : ?>
			<tr><th>場所</th><td><?php echo esc_html( $venue ); ?></td></tr>
			<?php endif; ?>
			<?php if ( $organizer ) : ?>
			<tr><th>主催</th><td><?php echo esc_html( $organizer ); ?></td></tr>
			<?php endif; ?>
			<?php if ( $fee ) : ?>
			<tr><th>参加料金</th><td><?php echo esc_html( $fee ); ?></td></tr>
			<?php endif; ?>
			<?php if ( $notes ) : ?>
			<tr><th>備考</th><td><?php echo wp_kses( $notes, [ 'br' => [] ] ); ?></td></tr>
			<?php endif; ?>
			<?php if ( $contact_html ) : ?>
			<tr><th>お問い合わせ先</th><td><?php echo $contact_html; ?></td></tr>
			<?php endif; ?>
			<?php if ( $map_url ) : ?>
			<tr><th>地図</th><td><a href="<?php echo esc_url( $map_url ); ?>" target="_blank" rel="noopener noreferrer">地図を見る</a></td></tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>
<!-- /.p-event-single__overview -->
