<?php
function zior_couponreveal_archive_templates( $template ) {
	if ( is_tax( 'coupon-stores' )  ) {
		$template = locate_template( array( 'taxonomy-coupon-stores.php' ) );
		if ( ! $template ) {
			$template = ZR_COUPON_PLUGIN_DIR . 'templates/taxonomy-coupon-stores.php';
		}
	}

	if ( is_tax( 'coupon-categories' )  ) {
		$template = locate_template( array( 'taxonomy-coupon-categories.php' ) );
		if ( ! $template ) {
			$template = ZR_COUPON_PLUGIN_DIR . 'templates/taxonomy-coupon-categories.php';
		}
	}

	if ( is_post_type_archive( 'coupons' )  ) {
		$template = locate_template( array( 'archive-coupons.php' ) );
		if ( ! $template ) {
			$template = ZR_COUPON_PLUGIN_DIR . 'templates/archive-coupons.php';
		}
	}

	return $template;
}
add_filter( 'template_include', 'zior_couponreveal_archive_templates', 99 );