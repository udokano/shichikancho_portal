<?php
// ACFフィールドをエスケープ付きで取得（空時はフォールバック）
function sc_field( string $key, $post_id = null, string $fallback = '' ): string {
	$val = get_field( $key, $post_id );
	return $val ? esc_html( $val ) : esc_html( $fallback );
}

// ACFフィールドをURL用エスケープで取得
function sc_field_url( string $key, $post_id = null ): string {
	$val = get_field( $key, $post_id );
	return $val ? esc_url( $val ) : '';
}

// ACFテキストエリア取得（<br>のみ許可、それ以外のHTMLは除去）
// 前提: ACFフィールド設定で「改行 → 自動的に <br> に変換」を有効にする
function sc_field_textarea( string $key, $post_id = null, string $fallback = '' ): string {
	$val = get_field( $key, $post_id );
	$val = $val !== '' && $val !== null ? $val : $fallback;
	return $val ? wp_kses( $val, [ 'br' => [] ] ) : '';
}

// アイキャッチ画像URLを取得（フォールバック付き）
function sc_thumbnail_url( int $post_id, string $size = 'medium', string $fallback = '' ): string {
	$url = get_the_post_thumbnail_url( $post_id, $size );
	if ( $url ) return esc_url( $url );
	return $fallback ? esc_url( $fallback ) : sc_no_image_url();
}

// テキスト量から読了時間（分）を算出
function sc_reading_time( string $content ): int {
	$word_count = mb_strlen( wp_strip_all_tags( $content ) );
	$minutes    = (int) ceil( $word_count / 400 );
	return max( 1, $minutes );
}

// 電話番号の表示整形（tel: リンク用は数字のみ）
function sc_tel_href( string $phone ): string {
	return 'tel:' . preg_replace( '/[^\d+]/', '', $phone );
}

// 日付を日本語形式で整形
function sc_date_jp( string $date_str ): string {
	$ts = strtotime( $date_str );
	if ( ! $ts ) return esc_html( $date_str );
	return date_i18n( 'Y年n月j日', $ts );
}

// ノーイメージプレースホルダー URL
function sc_no_image_url(): string {
	return esc_url( get_template_directory_uri() . '/assets/images/common/no-image.svg' );
}

// タクソノミースラッグから term_id を取得（$wpdb 直クエリ）
// WP の get_term_by('slug') は内部で sanitize_title() を呼ぶため日本語スラッグが消える問題を回避
function sc_get_term_id_by_slug( string $slug, string $taxonomy ): int {
	global $wpdb;
	return (int) $wpdb->get_var( $wpdb->prepare(
		"SELECT t.term_id FROM {$wpdb->terms} t
		 INNER JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id
		 WHERE t.slug = %s AND tt.taxonomy = %s LIMIT 1",
		$slug, $taxonomy
	) );
}
