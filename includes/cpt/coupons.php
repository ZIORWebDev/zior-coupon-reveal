<?php
function zior_coupon_create_posttype() {
	/**
	 * Post Type: Coupons.
	 */
	$coupons = array(
		'labels' => array(
			'name'          => __( 'Coupons' ),
			'singular_name' => __( 'Coupon' ),
		),
		'supports' => array(
			'title',
			'editor',
			'thumbnail',
		),
		'public'       => true,
		'has_archive'  => true,
		'show_in_rest' => true,
	);
	register_post_type( 'coupons', $coupons );

	/**
	 * Taxonomy: Stores.
	 */
	$stores = array(
		'hierarchical' => true,
		'labels'       => array(
			'name'              => __( 'Stores', 'zior-couponreveal' ),
			'singular_name'     => __( 'Store', 'zior-couponreveal' ),
			'search_items'      => __( 'Search Stores', 'zior-couponreveal' ),
			'all_items'         => __( 'All Stores', 'zior-couponreveal' ),
			'view_item'         => __( 'View Store', 'zior-couponreveal' ),
			'parent_item'       => __( 'Parent Store', 'zior-couponreveal' ),
			'parent_item_colon' => __( 'Parent Store:', 'zior-couponreveal' ),
			'edit_item'         => __( 'Edit Store', 'zior-couponreveal' ),
			'update_item'       => __( 'Update Store', 'zior-couponreveal' ),
			'add_new_item'      => __( 'Add New Store', 'zior-couponreveal' ),
			'new_item_name'     => __( 'New Store Name', 'zior-couponreveal' ),
			'not_found'         => __( 'No Stores Found', 'zior-couponreveal' ),
			'back_to_items'     => __( 'Back to Stores', 'zior-couponreveal' ),
			'menu_name'         => __( 'Stores', 'zior-couponreveal' ),
		),
		'show_ui'           => true,
		'show_in_rest'      => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'coupon-stores' ),
	);
	register_taxonomy( 'coupon-stores', array( 'coupons' ), $stores );

	/**
	 * Taxonomy: Categories.
	 */
	$categories = array(
		'hierarchical' => true,
		'labels'       => array(
			'name'              => __( 'Categories', 'zior-couponreveal' ),
			'singular_name'     => __( 'Category', 'zior-couponreveal' ),
			'search_items'      => __( 'Search Categories', 'zior-couponreveal' ),
			'all_items'         => __( 'All Categories', 'zior-couponreveal' ),
			'view_item'         => __( 'View Category', 'zior-couponreveal' ),
			'parent_item'       => __( 'Parent Category', 'zior-couponreveal' ),
			'parent_item_colon' => __( 'Parent Category:', 'zior-couponreveal' ),
			'edit_item'         => __( 'Edit Category', 'zior-couponreveal' ),
			'update_item'       => __( 'Update Category', 'zior-couponreveal' ),
			'add_new_item'      => __( 'Add New Category', 'zior-couponreveal' ),
			'new_item_name'     => __( 'New Category Name', 'zior-couponreveal' ),
			'not_found'         => __( 'No Categories Found', 'zior-couponreveal' ),
			'back_to_items'     => __( 'Back to Categories', 'zior-couponreveal' ),
			'menu_name'         => __( 'Categories', 'zior-couponreveal' ),
		),
		'show_ui'           => true,
		'show_in_rest'      => true,
		'show_admin_column' => true,
		'query_var'         => true,
	);
	register_taxonomy( 'coupon-categories', array( 'coupons' ), $categories );

	/**
	 * Post Type: Templates.
	 */
	$template_labels = [
		'name' => esc_html__( 'Templates', 'zior-couponreveal' ),
		'singular_name' => esc_html__( 'Template', 'zior-couponreveal' ),
		'menu_name' => esc_html__( 'Templates', 'zior-couponreveal' ),
		'all_items' => esc_html__( 'Templates', 'zior-couponreveal' ),
	];

	$template_args = [
		'label'               => esc_html__( 'Templates', 'zior-couponreveal' ),
		'labels'              => $template_labels,
		'public'              => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_menu'        => 'edit.php?post_type=coupons',
		'show_in_nav_menus'   => false,
		'exclude_from_search' => true,
		'capability_type'     => 'post',
		'query_var'           => true,
		'supports'            => [ 'title', 'editor' ],
		'show_in_rest'        => true,
		'hierarchical'        => true,
	];
	
	register_post_type( 'coupon-templates', $template_args );
}
add_action( 'init', 'zior_coupon_create_posttype' );