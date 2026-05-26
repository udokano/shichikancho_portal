<?php
// acf-json/ への保存パス
add_filter( 'acf/settings/save_json', function() {
	return get_template_directory() . '/acf-json';
} );

// acf-json/ からの読み込みパス
add_filter( 'acf/settings/load_json', function( $paths ) {
	$paths[] = get_template_directory() . '/acf-json';
	return $paths;
} );
