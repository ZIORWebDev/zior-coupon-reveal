<?php
use WP_Block;

/**
 * Abstract_Block class.
 */
abstract class Zior_Abstract_Block {

	/**
	 * Block namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'zior';

	/**
	 * Block name within this namespace.
	 *
	 * @var string
	 */
	protected $block_name = '';

	/**
	 * Constructor.
	 *
	 * @param string              $block_name Optionally set block name during construct.
	 */
	public function __construct() {
		$this->initialize();
	}

	/**
	 * The default render_callback for all blocks. This will ensure assets are enqueued just in time, then render
	 * the block (if applicable).
	 *
	 * @param array|WP_Block $attributes Block attributes, or an instance of a WP_Block. Defaults to an empty array.
	 * @param string         $content    Block content. Default empty string.
	 * @param WP_Block|null  $block      Block instance.
	 * @return string Rendered block type output.
	 */
	public function render_callback( $attributes = [], $content = '', $block = null ) {
		$render_callback_attributes = $this->parse_render_callback_attributes( $attributes );
		return $this->render( $render_callback_attributes, $content, $block );
	}

	/**
	 * Enqueue assets used for rendering the block in editor context.
	 *
	 * This is needed if a block is not yet within the post content--`render` and `enqueue_assets` may not have ran.
	 */
	public function enqueue_editor_assets() {
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

	/**
	 * Initialize this block type.
	 *
	 * - Register the block with WordPress.
	 */
	public function initialize() {
		$this->register_block_type();
		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_editor_assets' ] );
	}

	/**
	 * Registers the block type with WordPress.
	 *
	 * @return string[] Chunks paths.
	 */
	protected function register_block_type() {
		$block_settings = [
			'render_callback' => $this->get_block_type_render_callback(),
			'editor_style'    => $this->get_block_type_editor_style(),
			'style'           => $this->get_block_type_style(),
		];

		$metadata_path = $this->get_block_metadata_path( $this->block_name );
		// Prefer to register with metadata if the path is set in the block's class.
		if ( ! empty( $metadata_path ) ) {
			register_block_type_from_metadata(
				$metadata_path,
				$block_settings
			);
			return;
		}
	}

	/**
	 * Get the path to a block's metadata
	 *
	 * @param string $block_name The block to get metadata for.
	 * @param string $path Optional. The path to the metadata file inside the 'build' folder.
	 *
	 * @return string|boolean False if metadata file is not found for the block.
	 */
	public function get_block_metadata_path( $block_name ) {
		$metadata_file = ZR_COUPON_PLUGIN_DIR . 'build/blocks/' . $block_name . '/block.json';

		
		if ( ! file_exists( $metadata_file ) ) {
			return false;
		}

		return $metadata_file;
	}

	/**
	 * Get the block type.
	 *
	 * @return string
	 */
	protected function get_block_type() {
		return $this->namespace . '/' . $this->block_name;
	}

	/**
	 * Get the render callback for this block type.
	 *
	 * Dynamic blocks should return a callback, for example, `return [ $this, 'render' ];`
	 *
	 * @see $this->register_block_type()
	 * @return callable|null;
	 */
	protected function get_block_type_render_callback() {
		return [ $this, 'render_callback' ];
	}

	/**
	 * Get the editor style handle for this block type.
	 *
	 * @see $this->register_block_type()
	 * @return string|null
	 */
	protected function get_block_type_editor_style() {
		return 'zior-blocks-editor-style';
	}

	/**
	 * Get the frontend style handle for this block type.
	 *
	 * @see $this->register_block_type()
	 * @return string|null
	 */
	protected function get_block_type_style() {
		return 'zior-blocks-style';
	}

	/**
	 * Get the supports array for this block type.
	 *
	 * @see $this->register_block_type()
	 * @return string;
	 */
	protected function get_block_type_supports() {
		return [];
	}

	/**
	 * Get block attributes.
	 *
	 * @return array;
	 */
	protected function get_block_type_attributes() {
		return [];
	}

	/**
	 * Get block usesContext.
	 *
	 * @return array;
	 */
	protected function get_block_type_uses_context() {
		return [];
	}

	/**
	 * Parses block attributes from the render_callback.
	 *
	 * @param array|WP_Block $attributes Block attributes, or an instance of a WP_Block. Defaults to an empty array.
	 * @return array
	 */
	protected function parse_render_callback_attributes( $attributes ) {
		return is_a( $attributes, 'WP_Block' ) ? $attributes->attributes : $attributes;
	}

	/**
	 * Render the block. Extended by children.
	 *
	 * @param array    $attributes Block attributes.
	 * @param string   $content    Block content.
	 * @param WP_Block $block      Block instance.
	 * @return string Rendered block type output.
	 */
	protected function render( $attributes, $content, $block ) {
		return $content;
	}

	public static function get_classes_and_styles_by_attributes( $attributes, $properties = array() ) {
		$classes_and_styles = array(
			'line_height'      => self::get_line_height_class_and_style( $attributes ),
			'text_color'       => self::get_text_color_class_and_style( $attributes ),
			'font_size'        => self::get_font_size_class_and_style( $attributes ),
			'font_family'      => self::get_font_family_class_and_style( $attributes ),
			'font_weight'      => self::get_font_weight_class_and_style( $attributes ),
			'font_style'       => self::get_font_style_class_and_style( $attributes ),
			'link_color'       => self::get_link_color_class_and_style( $attributes ),
			'background_color' => self::get_background_color_class_and_style( $attributes ),
			'border_color'     => self::get_border_color_class_and_style( $attributes ),
			'border_radius'    => self::get_border_radius_class_and_style( $attributes ),
			'border_width'     => self::get_border_width_class_and_style( $attributes ),
			'padding'          => self::get_padding_class_and_style( $attributes ),
			'margin'           => self::get_margin_class_and_style( $attributes ),
		);

		if ( ! empty( $properties ) ) {
			foreach ( $classes_and_styles as $key => $value ) {
				if ( ! in_array( $key, $properties, true ) ) {
					unset( $classes_and_styles[ $key ] );
				}
			}
		}

		$classes_and_styles = array_filter( $classes_and_styles );

		$classes = array_map(
			function( $item ) {
				return $item['class'];
			},
			$classes_and_styles
		);

		$styles = array_map(
			function( $item ) {
				return $item['style'];
			},
			$classes_and_styles
		);

		$classes = array_filter( $classes );
		$styles  = array_filter( $styles );

		return array(
			'classes' => implode( ' ', $classes ),
			'styles'  => implode( ' ', $styles ),
		);
	}
}
