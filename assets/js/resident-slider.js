'use strict';

// お隣さん PICK UP slick スライダー
jQuery(function ($) {
	const $list = $('.js-resident-pickup');
	if (!$list.length) return;
	const count    = $list.children().length;
	const isSlider = $list.closest('.p-resident-archive__featured').hasClass('is-slider');
	const isSP     = window.matchMedia('(max-width: 768px)').matches;

	// 4以上 or SP の時のみ slick 化（3以下 PC は CSS グリッドのまま）
	if (!isSlider && !isSP) return;

	const prevHtml = '<button type="button" class="slick-prev p-resident-archive__slider-arrow p-resident-archive__slider-arrow--prev" aria-label="前へ"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg></button>';
	const nextHtml = '<button type="button" class="slick-next p-resident-archive__slider-arrow p-resident-archive__slider-arrow--next" aria-label="次へ"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg></button>';

	$list.slick({
		slidesToShow: 3,
		slidesToScroll: 1,
		arrows: true,
		dots: true,
		infinite: count > 3,
		autoplay: true,
		autoplaySpeed: 5000,
		pauseOnHover: true,
		prevArrow: prevHtml,
		nextArrow: nextHtml,
		responsive: [
			{ breakpoint: 900, settings: { slidesToShow: 2, slidesToScroll: 1 } },
			{ breakpoint: 600, settings: { slidesToShow: 1, slidesToScroll: 1 } },
		],
	});
});
