'use strict';

// PICK UP 等のスライダー。対象: .js-center-slider（1ページに複数あっても可）
// 通常: SP のみ slick センターモード（PC は CSS グリッド）
// .is-pc-slider 付き: 4件以上想定。PC は複数枚スライダー、SP はセンターモード（slick responsive で切替）
jQuery(function ($) {
	const $sliders = $('.js-center-slider');
	if (!$sliders.length) return;

	const mqSp = window.matchMedia('(max-width: 768px)');

	// SP センターモード（従来）
	const SP_CONF = {
		centerMode: true,
		centerPadding: '32px',
		slidesToShow: 1,
		slidesToScroll: 1,
		arrows: false,
		dots: true,
		infinite: true,
		speed: 350,
	};

	// 円形矢印（SVG スプライトの chevron を使用）
	const PREV = '<button type="button" class="slick-prev" aria-label="前のスライド"><svg aria-hidden="true" focusable="false"><use href="#icon-chevron-left"></use></svg></button>';
	const NEXT = '<button type="button" class="slick-next" aria-label="次のスライド"><svg aria-hidden="true" focusable="false"><use href="#icon-chevron-right"></use></svg></button>';

	// PC 複数枚 + SP センターモード。矢印・ドットは操作バーへ流し込む
	// PC の表示枚数は data-pc-slides で調整可（未指定は3枚）
	function pcConf($el) {
		const pcSlides = parseInt($el.attr('data-pc-slides'), 10) || 3;
		const conf = {
			slidesToShow: pcSlides,
			slidesToScroll: 1,
			arrows: true,
			dots: true,
			infinite: true,
			speed: 350,
			prevArrow: PREV,
			nextArrow: NEXT,
			responsive: [
				{
					breakpoint: 768,
					settings: {
						centerMode: true,
						centerPadding: '32px',
						slidesToShow: 1,
					},
				},
			],
		};
		// グリッド直後の操作バー（矢印＋ドットを一列に）
		const $nav = $el.next('.js-center-slider-nav');
		if ($nav.length) {
			conf.appendArrows = $nav;
			conf.appendDots = $nav;
		}
		return conf;
	}

	function build($el, conf) {
		if (!$el.hasClass('slick-initialized')) $el.slick(conf);
	}

	function teardown($el) {
		if ($el.hasClass('slick-initialized')) $el.slick('unslick');
	}

	// 初期化＋ブレイクポイント跨ぎで slick を出し入れ
	function sync() {
		$sliders.each(function () {
			const $el = $(this);
			if ($el.hasClass('is-pc-slider')) {
				// PC/SP とも slick（切替は slick responsive 任せ）。teardown しない
				build($el, pcConf($el));
			} else if (mqSp.matches) {
				build($el, SP_CONF);
			} else {
				teardown($el);
			}
		});
	}

	sync();
	mqSp.addEventListener('change', sync);
});
