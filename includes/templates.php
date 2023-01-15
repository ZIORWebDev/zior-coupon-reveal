<?php
function zior_coupon_archive_templates( $template ) {
	if ( is_tax( 'coupon-stores' )  ) {
		$template = locate_template( array( 'taxonomy-coupon-stores.php' ) );
		if ( ! $template ) {
			$template = ZR_COUPON_PLUGIN_DIR . 'templates/taxonomy-coupon-stores.php';
		}
	}

	return $template;
}
add_filter( 'template_include', 'zior_coupon_archive_templates', 99 );