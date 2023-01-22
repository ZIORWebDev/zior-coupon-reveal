<?php
namespace ZIOR;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);
/**
 * ZIOR Coupon Reveal
 *
 * Plugin Name: ZIOR Coupon Reveal
 * Description: Coupon reveal for Gutenberg blocks page builder.
 * Version: 0.1.0
 * Author:      ZIORWeb.Dev
 * Author URI:  https://github.com/ZIORWebDev
 * License:     GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
 * Text Domain: zior-couponreveal
 * Requires at least: 4.9
 * Tested up to: 6.1
 * Requires PHP: 7.4
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation. You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

class CouponReveal {

	/**
	 * @var CouponReveal
	 */
	protected static $instance;
	protected $loader;
	
	/**
	 * @var string
	 */
	protected $version = '0.1.0';

	/**
	 * Get instance.
	 *
	 * @return static
	 * @since
	 * @access static
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->init();
		}

		return self::$instance;
	}

	public function init() {
		$this->setup_constants();
		$this->includes();
	}

	public function includes() {
		// Include ACF if not installed
		if ( ! class_exists('ACF') ) {
			require_once ZR_COUPON_PLUGIN_DIR . 'includes/acf/acf-loader.php';
		}

		require_once ZR_COUPON_PLUGIN_DIR . 'includes/classes/loader.php';
		require_once ZR_COUPON_PLUGIN_DIR . 'includes/actions.php';
		require_once ZR_COUPON_PLUGIN_DIR . 'includes/filters.php';
		require_once ZR_COUPON_PLUGIN_DIR . 'includes/templates.php';
	}

	/**
	 * Setup plugin constants
	 *
	 */
	private function setup_constants() {
		// Plugin version.
		if ( ! defined( 'ZR_COUPON_VERSION' ) ) {
			define( 'ZR_COUPON_VERSION', $this->version );
		}
		// Plugin Folder Path.
		if ( ! defined( 'ZR_COUPON_PLUGIN_DIR')) {
			define( 'ZR_COUPON_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		}

		// Plugin Folder URL.
		if ( ! defined( 'ZR_COUPON_PLUGIN_URL' ) ) {
			define( 'ZR_COUPON_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		}

		// Plugin Root File.
		if ( ! defined( 'ZR_COUPON_PLUGIN_FILE' ) )
		{
			define( 'ZR_COUPON_PLUGIN_FILE', __FILE__ );
		}
	}
}

/**
 * Start the blocks loader.
 *
 * @return CouponReveal
 */
function coupon_reveal_initialize() {
	return CouponReveal::instance();
}

coupon_reveal_initialize();