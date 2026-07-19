<?php
/** ブロックエディタ：カスタムブロック・パターン・CF7日本語化 */

/**
 * カスタムブロック登録（WP ネイティブ register_block_type）
 *
 * 各ブロックは blocks/{block-name}/block.json で定義し、
 * WP コア API でメタデータベースから登録する。
 * ACF フィールドグループは ACF 管理画面 UI で定義し、
 * インポート JSON 経由で同期する（PHP では登録しない）。
 *
 * 関連:
 *   blocks/event-lead/block.json + render.php
 *   blocks/event-overview/block.json + render.php
 *   _acf-import_event-lead-fields.json / _acf-import_event-overview-fields.json
 */

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'init', function () {
	$dirs = [ 'event-lead', 'event-overview' ];
	foreach ( $dirs as $name ) {
		$path = get_template_directory() . '/blocks/' . $name;
		if ( file_exists( $path . '/block.json' ) ) {
			register_block_type( $path );
		}
	}
}, 5 );

// ═══════════════════════════════════════════════════════
// ブロックパターン
// ═══════════════════════════════════════════════════════
/**
 * ブロックパターン登録
 *
 * テンプレート直書きをやめ、編集画面から挿入・編集できる定型ブロックとして提供する。
 * アイコンは SVG スプライト参照のため core/html ブロックで持つ（core ブロックでは <use> を出せない）。
 *
 * 連絡先の運用:
 *   実体は同期パターン「連絡先」(wp_block)。各ページはそれを参照するだけなので、
 *   ページを複製しても住所のコピーは増えず、パターンを1箇所直せば全ページに反映される。
 *   ここの登録は同期パターンを作り直すときの種（git 管理される唯一の定義）。
 *   通常の編集は 管理画面 → 外観 → パターン から行う。
 *
 * NAP 表記ゆれ注意: footer.php / inc/schema.php(SC_ADDRESS) にも住所定義がある。
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// アイコン span 1個分の HTML（スプライト参照）
function sc_pattern_icon( string $id ): string {
	return sprintf(
		'<span class="p-contact__info-icon" aria-hidden="true"><svg class="p-contact__info-icon-svg" aria-hidden="true" focusable="false"><use href="#%s"></use></svg></span>',
		esc_attr( $id )
	);
}

// 連絡先 1項目分（アイコン + 本文）
function sc_pattern_contact_item( string $icon, string $label, array $values, string $note = '' ): string {
	$out  = '<!-- wp:group {"className":"p-contact__info-item"} -->' . "\n";
	$out .= '<div class="wp-block-group p-contact__info-item">' . "\n";

	$out .= '<!-- wp:html -->' . "\n" . sc_pattern_icon( $icon ) . "\n" . '<!-- /wp:html -->' . "\n";

	$out .= '<!-- wp:group {"className":"p-contact__info-body"} -->' . "\n";
	$out .= '<div class="wp-block-group p-contact__info-body">' . "\n";

	$out .= '<!-- wp:paragraph {"className":"p-contact__info-label"} -->' . "\n";
	$out .= '<p class="p-contact__info-label">' . esc_html( $label ) . '</p>' . "\n";
	$out .= '<!-- /wp:paragraph -->' . "\n";

	foreach ( $values as $value ) {
		$out .= '<!-- wp:paragraph {"className":"p-contact__info-value"} -->' . "\n";
		$out .= '<p class="p-contact__info-value">' . $value . '</p>' . "\n";
		$out .= '<!-- /wp:paragraph -->' . "\n";
	}

	if ( $note !== '' ) {
		$out .= '<!-- wp:paragraph {"className":"p-contact__info-note"} -->' . "\n";
		$out .= '<p class="p-contact__info-note">' . esc_html( $note ) . '</p>' . "\n";
		$out .= '<!-- /wp:paragraph -->' . "\n";
	}

	$out .= '</div>' . "\n" . '<!-- /wp:group -->' . "\n";
	$out .= '</div>' . "\n" . '<!-- /wp:group -->' . "\n";

	return $out;
}

// 連絡先パターン本体
function sc_pattern_contact_info(): string {
	$out  = '<!-- wp:group {"className":"p-contact__info"} -->' . "\n";
	$out .= '<div class="wp-block-group p-contact__info">' . "\n";

	$out .= '<!-- wp:heading {"level":2,"className":"p-contact__info-title"} -->' . "\n";
	$out .= '<h2 class="wp-block-heading p-contact__info-title">連絡先</h2>' . "\n";
	$out .= '<!-- /wp:heading -->' . "\n";

	$out .= sc_pattern_contact_item(
		'icon-map-pin-solid',
		'七間町町内会',
		[ '〒420-0035', '静岡県静岡市葵区七間町17-9' ]
	);

	$out .= sc_pattern_contact_item(
		'icon-phone',
		'電話番号',
		[ '054-XXX-XXXX' ],
		'（平日 9:00〜17:00）'
	);

	$out .= sc_pattern_contact_item(
		'icon-mail',
		'メールアドレス',
		[ '<a class="p-contact__info-value-link" href="mailto:info@shichikencho.jp">info@shichikencho.jp</a>' ]
	);

	$out .= '</div>' . "\n" . '<!-- /wp:group -->' . "\n";

	return $out;
}

add_action( 'init', function (): void {
	if ( ! function_exists( 'register_block_pattern' ) ) return;

	register_block_pattern_category( 'sichikenchou', [ 'label' => '七間町' ] );

	register_block_pattern( 'sichikenchou/contact-info', [
		'title'       => '連絡先',
		'description' => '住所・電話番号・メールアドレスの3項目（アイコン付き）',
		'categories'  => [ 'sichikenchou' ],
		'keywords'    => [ 'contact', '連絡先', '住所' ],
		'content'     => sc_pattern_contact_info(),
	] );
}, 20 );

// ═══════════════════════════════════════════════════════
// CF7 バリデーション日本語化
// ═══════════════════════════════════════════════════════
/**
 * Contact Form 7 バリデーションメッセージの日本語デフォルト
 *
 * サイトは ja だが、フォームによってはメッセージが英語で保存されている。
 * wpcf7_messages はフォーム個別設定が未指定のキーに効く（＝新規・複製フォーム向けの保険）。
 * 既存フォームの英語は DB 側で日本語に更新済み。
 */

if ( ! defined( 'ABSPATH' ) ) exit;

add_filter( 'wpcf7_messages', function ( array $messages ): array {
	$ja = [
		'validation_error' => '入力内容に誤りがあります。ご確認のうえ、もう一度お試しください。',
		'spam'             => '送信に失敗しました。時間をおいて再度お試しください。',
		'accept_terms'     => '確認事項に同意のうえ送信してください。',
		'invalid_required' => '必須項目です。入力してください。',
		'invalid_too_long' => '入力された文字数が多すぎます。',
		'invalid_too_short'=> '入力された文字数が少なすぎます。',
		'invalid_date'     => '日付の形式が正しくありません。',
		'invalid_number'   => '数値の形式が正しくありません。',
		'invalid_email'    => 'メールアドレスの形式が正しくありません。',
		'invalid_url'      => 'URL の形式が正しくありません。',
		'invalid_tel'      => '電話番号の形式が正しくありません。',
		'invalid_file'     => '許可されていないファイル形式です。',
		'upload_failed'    => 'ファイルのアップロードに失敗しました。',
	];

	foreach ( $ja as $key => $text ) {
		if ( isset( $messages[ $key ] ) ) {
			$messages[ $key ]['default'] = $text;
		}
	}

	return $messages;
}, 20 );
