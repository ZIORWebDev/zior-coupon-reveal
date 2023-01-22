<?php
namespace ZIOR\CouponReveal;
use ZIOR\CouponReveal\Blocks\StyleAttributesUtils;
use ZIOR\CouponReveal\PostTypes;
use ZIOR\CouponReveal\Settings;
use ZIOR\CouponReveal\Blocks\ImageAddon;
use ZIOR\CouponReveal\Blocks\CouponsCategoriesList;
use ZIOR\CouponReveal\Blocks\CouponsStoresList;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Helper methods for running the blocks.
 */
class Loader {
	public function __construct() {
		add_action( 'init', [ $this, 'register' ] );
	}

	/**
	 * Register server side blocks for the editor.
	 */
	public function register() {
		spl_autoload_register( function ( $class ) {
			if ( ! preg_match( '/^ZIOR.+$/', $class ) ) {
				return;
			}

			$classes = [
				'ZIOR\CouponReveal\Blocks\StyleAttributesUtils'  => 'blocks/StyleAttributesUtils',
				'ZIOR\CouponReveal\PostTypes'                    => 'PostTypes',
				'ZIOR\CouponReveal\Settings'                     => 'Settings',
				'ZIOR\CouponReveal\Blocks\ImageAddon'            => 'blocks/ImageAddon',
				'ZIOR\CouponReveal\Blocks\AbstractBlock'         => 'blocks/AbstractBlock',
				'ZIOR\CouponReveal\Blocks\CouponsCategoriesList' => 'blocks/coupons-categories-list/CouponsCategoriesList',
				'ZIOR\CouponReveal\Blocks\CouponsStoresList'     => 'blocks/coupons-stores-list/CouponsStoresList',
			];

			include $classes[ $class ] . '.php';
		} );

		( new StyleAttributesUtils );
		( new PostTypes );
		( new Settings );
		( new ImageAddon );
		( new CouponsCategoriesList )->initialize();
		( new CouponsStoresList )->initialize();
	}
}

new Loader;