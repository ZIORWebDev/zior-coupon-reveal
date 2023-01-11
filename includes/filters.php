<?php
function zior_coupon_acf_json_save_point( $path ) {
	$path = ZR_COUPON_PLUGIN_DIR . 'includes/acf/acf-json';
	return $path;
}
add_filter( 'acf/settings/save_json', 'zior_coupon_acf_json_save_point' );

// Load ACF fields from JSON File
function zior_coupon_acf_json_load_point( $paths ) {
	$paths[] = ZR_COUPON_PLUGIN_DIR . 'includes/acf/acf-json';
	return $paths;
}
add_filter( 'acf/settings/load_json', 'zior_coupon_acf_json_load_point' );