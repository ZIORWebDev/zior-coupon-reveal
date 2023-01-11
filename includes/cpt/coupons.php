<?php
function zior_coupon_create_posttype() {
	$coupons = array(
		'labels' => array(
			'name'          => __( 'Coupons' ),
			'singular_name' => __( 'Coupon' ),
		),
		'rewrite' => array(
			'slug' => 'zr-coupons',
		),
		'supports' => array(
			'title',
			'editor',
		),
		'public'       => true,
		'has_archive'  => true,
		'show_in_rest' => true,
	);
	register_post_type( 'zr-coupons', $coupons );

	$stores = array(
		'hierarchical' => true,
		'labels'       => array(
			'name'              => __( 'Stores', 'zior-coupon-reveal' ),
			'singular_name'     => __( 'Store', 'zior-coupon-reveal' ),
			'search_items'      => __( 'Search Stores', 'zior-coupon-reveal' ),
			'all_items'         => __( 'All Stores', 'zior-coupon-reveal' ),
			'view_item'         => __( 'View Store', 'zior-coupon-reveal' ),
			'parent_item'       => __( 'Parent Store', 'zior-coupon-reveal' ),
			'parent_item_colon' => __( 'Parent Store:', 'zior-coupon-reveal' ),
			'edit_item'         => __( 'Edit Store', 'zior-coupon-reveal' ),
			'update_item'       => __( 'Update Store', 'zior-coupon-reveal' ),
			'add_new_item'      => __( 'Add New Store', 'zior-coupon-reveal' ),
			'new_item_name'     => __( 'New Store Name', 'zior-coupon-reveal' ),
			'not_found'         => __( 'No Stores Found', 'zior-coupon-reveal' ),
			'back_to_items'     => __( 'Back to Stores', 'zior-coupon-reveal' ),
			'menu_name'         => __( 'Stores', 'zior-coupon-reveal' ),
		),
		'show_ui'           => true,
		'show_in_rest'      => true,
		'show_admin_column' => true,
		'query_var'         => true,
	);
	register_taxonomy( 'stores', array( 'zr-coupons' ), $stores );

	$categories = array(
		'hierarchical' => true,
		'labels'       => array(
			'name'              => __( 'Categories', 'zior-coupon-reveal' ),
			'singular_name'     => __( 'Category', 'zior-coupon-reveal' ),
			'search_items'      => __( 'Search Categories', 'zior-coupon-reveal' ),
			'all_items'         => __( 'All Categories', 'zior-coupon-reveal' ),
			'view_item'         => __( 'View Category', 'zior-coupon-reveal' ),
			'parent_item'       => __( 'Parent Category', 'zior-coupon-reveal' ),
			'parent_item_colon' => __( 'Parent Category:', 'zior-coupon-reveal' ),
			'edit_item'         => __( 'Edit Category', 'zior-coupon-reveal' ),
			'update_item'       => __( 'Update Category', 'zior-coupon-reveal' ),
			'add_new_item'      => __( 'Add New Category', 'zior-coupon-reveal' ),
			'new_item_name'     => __( 'New Category Name', 'zior-coupon-reveal' ),
			'not_found'         => __( 'No Categories Found', 'zior-coupon-reveal' ),
			'back_to_items'     => __( 'Back to Categories', 'zior-coupon-reveal' ),
			'menu_name'         => __( 'Categories', 'zior-coupon-reveal' ),
		),
		'show_ui'           => true,
		'show_in_rest'      => true,
		'show_admin_column' => true,
		'query_var'         => true,
	);
	register_taxonomy( 'coupon_categories', array( 'zr-coupons' ), $categories );
}

add_action( 'init', 'zior_coupon_create_posttype' );
