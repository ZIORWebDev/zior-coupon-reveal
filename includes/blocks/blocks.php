<?php
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Helper methods for running the blocks.
 */
class ZIOR_Coupon_Blocks_Loader {
	public function init() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		add_filter( 'render_block_data', [ $this, 'pre_render_block' ], 10, 3 );
	}

	public function enqueue_scripts() {
		wp_enqueue_style( 'zior-coupons', ZR_COUPON_PLUGIN_URL . 'build/main.min.css', array(), ZR_COUPON_VERSION );
		wp_enqueue_script( 'zior-coupons', ZR_COUPON_PLUGIN_URL . 'build/main.min.js', [
			'wp-blocks',
			'wp-i18n',
			'wp-element',
			'wp-components',
			'wp-editor',
			'wp-hooks',
			'wp-util'
		], ZR_COUPON_VERSION, true );
	}

	public function has_managefeatured_enabled( $parsed_block ) {
		$enabled = $parsed_block['attrs']['pull_featured_image_from_stores'] ?? 0;
		if ( $enabled ) {
			return true;
		}

		return false;
	}

	public function pre_render_block_featured_image( $thumbnail_id, $coupon ) {
		if ( ! $thumbnail_id ) {
			// Get thumbnail ID from store taxonomy
			//echo '$coupon: ' . $coupon->ID;
			$terms = get_the_terms( $coupon->ID, 'coupon-stores' );
			//print_r( $coupon );
			if ( $terms ) {
				$thumbnail_id = get_field( 'store_image', $terms[0]->taxonomy . '_' . $terms[0]->term_id );
			}
		}

		return $thumbnail_id;
	}

	public function pre_render_block( $parsed_block, $source_block, $parent_block ) {
		if ( 'core/post-featured-image' === $parsed_block['blockName'] && $this->has_managefeatured_enabled( $parsed_block ) ) {
			add_filter( 'post_thumbnail_id', [ $this, 'pre_render_block_featured_image' ], 10, 2 );
		}

		return $parsed_block;
	}
}
