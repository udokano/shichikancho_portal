'use strict';

// SP のみ slick センターモード（PC は CSS グリッドのまま）
// 対象: .js-center-slider（1ページに複数あっても可）
jQuery(function ($) {
	const $sliders = $('.js-center-slider');
	if (!$sliders.length) return;

	const mq = window.matchMedia('(max-width: 768px)');

	function build($el) {
		if ($el.hasClass('slick-initialized')) return;
		$el.slick({
			centerMode: true,
			centerPadding: '32px',
			slidesToShow: 1,
			slidesToScroll: 1,
			arrows: false,
			dots: true,
			infinite: true,
			speed: 350,
		});
	}

	function teardown($el) {
		if ($el.hasClass('slick-initialized')) {
			$el.slick('unslick');
		}
	}

	// 初期化＋ブレイクポイント跨ぎで slick を出し入れ
	function sync() {
		$sliders.each(function () {
			const $el = $(this);
			if (mq.matches) {
				build($el);
			} else {
				teardown($el);
			}
		});
	}

	sync();
	mq.addEventListener('change', sync);
});
