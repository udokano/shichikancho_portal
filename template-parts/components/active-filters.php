<?php
/**
 * 適用中の絞り込みチップバー（共通）
 *
 * 引数:
 *   $args['chips']     ... [['label' => '...', 'remove' => 'URL'], ...]
 *   $args['clear_url'] ... 全解除リンクの遷移先
 *   $args['title']     ... 条件付き見出しテキスト（オプション、見出し表示したい場合）
 *   $args['title_default'] ... フィルタなし時の見出し（オプション）
 *   $args['title_tag'] ... 'h2' 等。デフォルト 'h2'
 *   $args['title_class'] ... 見出しクラス。デフォルト ''
 */

$chips         = $args['chips'] ?? [];
$clear_url     = $args['clear_url'] ?? '';
$is_filtered   = ! empty( $chips );
$title         = $args['title'] ?? '';
$title_default = $args['title_default'] ?? '';
$title_tag     = $args['title_tag'] ?? 'h2';
$title_class   = $args['title_class'] ?? '';

if ( $is_filtered ) :
?>
<div class="c-active-filters" aria-label="適用中の絞り込み">
	<?php foreach ( $chips as $c ) : ?>
	<a class="c-active-filters__chip" href="<?php echo esc_url( $c['remove'] ); ?>" aria-label="<?php echo esc_attr( $c['label'] ); ?> を解除">
		<span><?php echo esc_html( $c['label'] ); ?></span>
		<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
	</a>
	<?php endforeach; ?>
	<a class="c-active-filters__clear" href="<?php echo esc_url( $clear_url ); ?>">すべて解除</a>
</div>
<?php endif; ?>

<?php if ( $title || $title_default ) :
	$out_title = $is_filtered && $title ? $title : $title_default;
	if ( $out_title ) :
?>
<<?php echo esc_attr( $title_tag ); ?> class="<?php echo esc_attr( $title_class ); ?>"><?php echo esc_html( $out_title ); ?></<?php echo esc_attr( $title_tag ); ?>>
<?php endif; endif; ?>
