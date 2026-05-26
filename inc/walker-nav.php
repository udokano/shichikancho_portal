<?php
/**
 * サブナビ用カスタムウォーカー
 * メニュー項目のCSS class に icon-xxx を付与すると
 * SVG スプライトの対応 symbol を自動出力する
 */
class SC_Sub_Nav_Walker extends Walker_Nav_Menu {

	// アイコンクラス → SVG symbol ID マップ
	private const ICON_MAP = [
		'icon-house'      => 'icon-house',
		'icon-film'       => 'icon-film',
		'icon-map-pin'    => 'icon-map-pin',
		'icon-person'     => 'icon-person',
		'icon-hat'        => 'icon-hat',
		'icon-briefcase'  => 'icon-briefcase',
		'icon-camera'     => 'icon-camera',
		'icon-book'       => 'icon-book',
		'icon-shield'     => 'icon-shield',
	];

	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ): void {
		$classes   = empty( $item->classes ) ? [] : (array) $item->classes;
		$icon_id   = '';

		foreach ( self::ICON_MAP as $class => $symbol ) {
			if ( in_array( $class, $classes, true ) ) {
				$icon_id = $symbol;
				break;
			}
		}

		$is_current = in_array( 'current-menu-item', $classes, true )
			? ' is-current'
			: '';

		$url   = esc_url( $item->url );
		$title = esc_html( $item->title );

		$output .= '<li class="l-header__nav-sub-item">';
		$output .= '<a href="' . $url . '" class="l-header__nav-sub-link' . $is_current . '">';

		if ( $icon_id ) {
			$output .= '<svg class="l-header__nav-sub-icon" aria-hidden="true" focusable="false">';
			$output .= '<use href="#' . esc_attr( $icon_id ) . '"></use>';
			$output .= '</svg>';
		}

		$output .= '<span>' . $title . '</span>';
		$output .= '</a>';
		$output .= '</li>';
	}

	public function end_el( &$output, $item, $depth = 0, $args = null ): void {}
}
