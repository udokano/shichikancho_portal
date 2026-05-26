<?php
/**
 * ユーザープロフィール拡張
 * - Instagram URL / Facebook URL を追加
 *   表示: 投稿者名 + プロフィール（biographical info）+ アバター + SNSリンク
 */

add_filter( 'user_contactmethods', function ( array $methods ): array {
	$methods['instagram_url'] = 'Instagram URL';
	$methods['facebook_url']  = 'Facebook URL';
	return $methods;
} );
