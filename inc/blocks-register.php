<?php
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
