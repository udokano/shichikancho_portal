<?php
/**
 * 共通ページヒーロー
 *
 * 使い方:
 *   get_template_part( 'template-parts/components/page-hero', null, [
 *       'title'    => '町でまなぶ',
 *       'sub'      => '七間町で学び、成長する。',
 *       'modifier' => '',  // '', 'washi', 'dark', 'alert'
 *   ] );
 */

$args     = wp_parse_args( $args ?? [], [
	'title'    => '',
	'sub'      => '',
	'modifier' => '',
] );
$title    = (string) $args['title'];
$sub      = (string) $args['sub'];
$modifier = (string) $args['modifier'];

if ( $title === '' ) return;

$class = 'c-page-hero';
if ( $modifier ) {
	$class .= ' c-page-hero--' . sanitize_html_class( $modifier );
}
?>
<div class="<?php echo esc_attr( $class ); ?>">
	<div class="c-page-hero__inner">
		<h1 class="c-page-hero__title"><?php echo esc_html( $title ); ?></h1>
		<?php if ( $sub ) : ?>
		<p class="c-page-hero__sub"><?php echo esc_html( $sub ); ?></p>
		<?php endif; ?>
	</div>
	<!-- /.c-page-hero__inner -->
</div>
<!-- /.c-page-hero -->
