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
		
		add_action( 'init', [ $this, 'register' ] );
		add_filter( 'render_block_data', [ $this, 'pre_render_block' ], 10, 3 );
	}

	/**
	 * Register server side blocks for the editor.
	 */
	public function register() {
		spl_autoload_register( function ( $class ) {
			$allowed_class = [
				'zior_abstract_block',
				'style_attributes_utils',
				'zior_coupon_categories',
			];
		
			if ( ! in_array( strtolower( $class ), $allowed_class ) ) {
				return;
			}
			include strtolower( $class ) . '.php';
		});

		( new Zior_Coupon_Categories() )->initialize();
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
			$terms = get_the_terms( $coupon->ID, 'coupon-stores' );
			if ( $terms ) {
				$thumbnail_id = get_field( 'store_logo', $terms[0]->taxonomy . '_' . $terms[0]->term_id );
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

(new ZIOR_Coupon_Blocks_Loader)->init();