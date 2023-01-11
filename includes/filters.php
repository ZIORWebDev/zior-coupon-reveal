<?php
// Load ACF fields from JSON File
function zior_coupon_acf_json_load_point( $paths ) {
	$paths[] = ZR_COUPON_PLUGIN_DIR . 'includes/acf/acf-json';
	return $paths;
}
add_filter( 'acf/settings/load_json', 'zior_coupon_acf_json_load_point' );