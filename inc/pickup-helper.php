<?php
/**
 * PICK UP ヘルパー
 *
 * 各 CPT 用 ACF オプションページ（pickup-{cpt}）の relationship フィールド
 * `pickup_{cpt}` から ID 配列を取得する。
 * オプションページ・フィールド本体は ACF 管理画面 UI で定義し、
 * インポート JSON 経由で ACF に登録する（PHP では登録しない）。
 *
 * 関連: _acf-import_pickup-options.json / _acf-import_pickup-fields.json
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! function_exists( 'sc_get_pickup_ids' ) ) :
	/**
	 * 指定 CPT の PICK UP 投稿 ID 配列を返す。
	 *
	 * @param string $key   pickup_xxx の xxx 部分（例: 'shop'）
	 * @param int    $limit 上限件数
	 * @return int[]
	 */
	function sc_get_pickup_ids( $key, $limit = 3 ) {
		if ( ! function_exists( 'get_field' ) ) return [];
		$ids = get_field( 'pickup_' . $key, 'option' );
		if ( ! is_array( $ids ) || ! $ids ) return [];
		$ids = array_values( array_filter( array_map( 'intval', $ids ), function ( $id ) {
			return $id > 0 && get_post_status( $id ) === 'publish';
		} ) );
		return array_slice( $ids, 0, max( 1, (int) $limit ) );
	}
endif;
