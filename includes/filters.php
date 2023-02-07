<?php
// Load ACF fields from JSON File
function zior_coupon_acf_json_load_point( $paths ) {
	$paths[] = ZR_COUPON_PLUGIN_DIR . 'includes/acf/acf-json';
	return $paths;
}
add_filter( 'acf/settings/load_json', 'zior_coupon_acf_json_load_point' );

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Filter wp:query blocks on taxonomy archive.
 */
function zior_couponreveal_query_loop_block_query_vars( $query, $block, $page ) {
	
	if ( ! is_tax( 'coupon-stores' ) && ! is_tax( 'coupon-categories' ) ) {
		return $query;
	}
	
	$term = get_queried_object();
	if ( ! $term ) {
		return query;
	}

	$query['tax_query'][] = array(
		'taxonomy'         => $term->taxonomy,
		'terms'            => $term->term_id,
		'include_children' => true,
	);

	return $query;
}
add_filter( 'query_loop_block_query_vars', 'zior_couponreveal_query_loop_block_query_vars', 999, 3 );