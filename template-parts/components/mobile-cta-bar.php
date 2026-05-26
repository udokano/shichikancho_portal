<?php
// SP 固定フッター CTA バー（お問い合わせ・アクセス等のクイックリンク）
?>
<div class="c-mobile-cta" aria-label="クイックリンク">
	<a class="c-mobile-cta__item" href="<?php echo esc_url( home_url( '/access/' ) ); ?>">
		<svg class="c-mobile-cta__icon" aria-hidden="true" focusable="false">
			<use href="#icon-map-pin"></use>
		</svg>
		<span>アクセス</span>
	</a>
	<a class="c-mobile-cta__item" href="<?php echo esc_url( home_url( '/shops/' ) ); ?>">
		<svg class="c-mobile-cta__icon" aria-hidden="true" focusable="false">
			<use href="#icon-house"></use>
		</svg>
		<span>お店</span>
	</a>
	<a class="c-mobile-cta__item" href="<?php echo esc_url( home_url( '/events/' ) ); ?>">
		<svg class="c-mobile-cta__icon" aria-hidden="true" focusable="false">
			<use href="#icon-map-pin"></use>
		</svg>
		<span>イベント</span>
	</a>
	<a class="c-mobile-cta__item" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">
		<svg class="c-mobile-cta__icon" aria-hidden="true" focusable="false">
			<use href="#icon-mail"></use>
		</svg>
		<span>お問い合わせ</span>
	</a>
</div>
<!-- /.c-mobile-cta -->
