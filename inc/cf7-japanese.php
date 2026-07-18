<?php
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
