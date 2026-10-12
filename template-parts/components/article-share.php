<?php
/**
 * 記事末尾のシェア（LINE / X / Facebook / URL コピー）
 *
 * single-event.php / single-spot.php と同じ見た目。外部 SDK は読まずリンクだけで構成
 *
 * @param array $args {
 *     @type string $label 見出し文言
 *     @type string $url   共有する URL（既定: 現在の投稿）
 *     @type string $title 共有するタイトル（既定: 現在の投稿）
 * }
 */
$share_url   = $args['url'] ?? get_permalink();
$share_title = $args['title'] ?? get_the_title();
$share_label = $args['label'] ?? 'この記事をシェアする';
?>
<div class="c-article__share c-article__share--bottom">
	<p class="c-article__share-text"><?php echo esc_html( $share_label ); ?></p>
	<div class="c-article__share-row">
		<a class="c-btn c-btn--share is-line" href="<?php echo esc_url( 'https://social-plugins.line.me/lineit/share?url=' . rawurlencode( $share_url ) ); ?>" target="_blank" rel="noopener noreferrer">
			LINEでシェア
		</a>
		<a class="c-btn c-btn--share is-x" href="<?php echo esc_url( 'https://x.com/intent/post?url=' . rawurlencode( $share_url ) . '&text=' . rawurlencode( $share_title ) ); ?>" target="_blank" rel="noopener noreferrer">
			Xでシェア
		</a>
		<a class="c-btn c-btn--share is-fb" href="<?php echo esc_url( 'https://www.facebook.com/sharer/sharer.php?u=' . rawurlencode( $share_url ) ); ?>" target="_blank" rel="noopener noreferrer">
			Facebookでシェア
		</a>
		<button type="button" class="c-btn c-btn--share is-copy js-copy-url" data-url="<?php echo esc_url( $share_url ); ?>">
			URLをコピー
		</button>
	</div>
	<!-- /.c-article__share-row -->
</div>
<!-- /.c-article__share -->
