<?php
// Define path and URL to the ACF plugin.
define( 'ZR_COUPON_ACF_PATH', ZR_COUPON_PLUGIN_DIR . 'vendor/acf/' );
define( 'ZR_COUPON_ACF_URL', ZR_COUPON_PLUGIN_URL . 'vendor/acf/' );

// Include the ACF plugin if not installed.
include_once( ZR_COUPON_ACF_PATH . 'acf.php' );

// Customize the url setting to fix incorrect asset URLs.
function zior_coupon_acf_settings_url( $url ) {
	return ZR_COUPON_ACF_URL;
}
add_filter( 'acf/settings/url', 'zior_coupon_acf_settings_url');

// (Optional) Hide the ACF admin menu item.
add_filter( 'acf/settings/show_admin', '__return_false' );
