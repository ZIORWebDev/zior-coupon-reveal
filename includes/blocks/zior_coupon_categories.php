<?php
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class Zior_Coupon_Categories extends Zior_Abstract_Block {

	protected $block_name = 'coupon-categories';

	/**
	 * Default attribute values, should match what's set in JS `registerBlockType`.
	 *
	 * @var array
	 */
	protected $defaults = array(
		'hasCount'       => true,
		'hasImage'       => false,
		'isHierarchical' => true,
	);

	/**
	 * Get block attributes.
	 *
	 * @return array
	 */
	protected function get_block_type_attributes() {
		return array_merge(
			parent::get_block_type_attributes(),
			array(
				'align'          => $this->get_schema_align(),
				'className'      => $this->get_schema_string(),
				'hasCount'       => $this->get_schema_boolean( true ),
				'hasImage'       => $this->get_schema_boolean( false ),
				'isHierarchical' => $this->get_schema_boolean( true ),
				'textColor'      => $this->get_schema_string(),
				'fontSize'       => $this->get_schema_string(),
				'lineHeight'     => $this->get_schema_string(),
				'style'          => array( 'type' => 'object' ),
			)
		);
	}
	
	/**
	 * Get the schema for the alignment property.
	 *
	 * @return array Property definition for align.
	 */
	protected function get_schema_align() {
		return array(
			'type' => 'string',
			'enum' => array( 'left', 'center', 'right', 'wide', 'full' ),
		);
	}

	/**
	 * Get the schema for a string value.
	 *
	 * @param  string $default  The default value.
	 * @return array Property definition.
	 */
	protected function get_schema_string( $default = '' ) {
		return array(
			'type'    => 'string',
			'default' => $default,
		);
	}

	/**
	 * Get the schema for a boolean value.
	 *
	 * @param  string $default  The default value.
	 * @return array Property definition.
	 */
	protected function get_schema_boolean( $default = true ) {
		return array(
			'type'    => 'boolean',
			'default' => $default,
		);
	}

	/**
	 * Render the Coupon Categories List block.
	 *
	 * @param array    $attributes Block attributes.
	 * @param string   $content    Block content.
	 * @param WP_Block $block Block instance.
	 * @return string Rendered block type output.
	 */
	public function render( $attributes, $content, $block ) {
		$uid        = uniqid( 'coupon-categories-' );
		$categories = $this->get_categories( $attributes );

		if ( empty( $categories ) ) {
			return '';
		}

		if ( ! empty( $content ) ) {
			// Deal with legacy attributes (before this was an SSR block) that differ from defaults.
			if ( strstr( $content, 'data-has-count="false"' ) ) {
				$attributes['hasCount'] = false;
			}

			if ( strstr( $content, 'data-is-hierarchical="false"' ) ) {
				$attributes['isHierarchical'] = false;
			}
		}

		$classes_and_styles = Style_Attributes_Utils::get_classes_and_styles_by_attributes(
			$attributes,
			array( 'line_height', 'text_color', 'font_size' )
		);

		$classes = $this->get_container_classes( $attributes ) . ' ' . $classes_and_styles['classes'];
		$styles  = $classes_and_styles['styles'];

		$output  = '<div class="wp-block-coupon-categories ' . esc_attr( $classes ) . '" style="' . esc_attr( $styles ) . '">';
		$output .= $this->renderList( $categories, $attributes, $uid );
		$output .= '</div>';

		return $output;
	}

	/**
	 * Get the list of classes to apply to this block.
	 *
	 * @param array $attributes Block attributes. Default empty array.
	 * @return string space-separated list of classes.
	 */
	protected function get_container_classes( $attributes = array() ) {

		$classes = array( 'zior-block-coupon-categories' );

		if ( isset( $attributes['align'] ) ) {
			$classes[] = "align{$attributes['align']}";
		}

		if ( ! empty( $attributes['className'] ) ) {
			$classes[] = $attributes['className'];
		}

		$classes[] = 'is-list';

		return implode( ' ', $classes );
	}

	/**
	 * Get categories (terms) from the db.
	 *
	 * @param array $attributes Block attributes. Default empty array.
	 * @return array
	 */
	protected function get_categories( $attributes ) {
		$hierarchical = wc_string_to_bool( $attributes['isHierarchical'] );
		$categories   = get_terms(
			[
				'taxonomy'     => 'coupon-categories',
				'pad_counts'   => true,
				'hierarchical' => true,
			]
		);

		if ( ! is_array( $categories ) || empty( $categories ) ) {
			return [];
		}

		// This ensures that no categories with a coupon count of 0 is rendered.
		$categories = array_filter(
			$categories,
			function( $category ) {
				return 0 !== $category->count;
			}
		);

		return $hierarchical ? $this->build_category_tree( $categories ) : $categories;
	}

	/**
	 * Build hierarchical tree of categories.
	 *
	 * @param array $categories List of terms.
	 * @return array
	 */
	protected function build_category_tree( $categories ) {
		$categories_by_parent = [];

		foreach ( $categories as $category ) {
			if ( ! isset( $categories_by_parent[ 'cat-' . $category->parent ] ) ) {
				$categories_by_parent[ 'cat-' . $category->parent ] = [];
			}
			$categories_by_parent[ 'cat-' . $category->parent ][] = $category;
		}

		$tree = $categories_by_parent['cat-0'];
		unset( $categories_by_parent['cat-0'] );

		foreach ( $tree as $category ) {
			if ( ! empty( $categories_by_parent[ 'cat-' . $category->term_id ] ) ) {
				$category->children = $this->fill_category_children( $categories_by_parent[ 'cat-' . $category->term_id ], $categories_by_parent );
			}
		}

		return $tree;
	}

	/**
	 * Build hierarchical tree of categories by appending children in the tree.
	 *
	 * @param array $categories List of terms.
	 * @param array $categories_by_parent List of terms grouped by parent.
	 * @return array
	 */
	protected function fill_category_children( $categories, $categories_by_parent ) {
		foreach ( $categories as $category ) {
			if ( ! empty( $categories_by_parent[ 'cat-' . $category->term_id ] ) ) {
				$category->children = $this->fill_category_children( $categories_by_parent[ 'cat-' . $category->term_id ], $categories_by_parent );
			}
		}
		return $categories;
	}

	/**
	 * Render the category list as a list.
	 *
	 * @param array $categories List of terms.
	 * @param array $attributes Block attributes. Default empty array.
	 * @param int   $uid Unique ID for the rendered block, used for HTML IDs.
	 * @param int   $depth Current depth.
	 * @return string Rendered output.
	 */
	protected function renderList( $categories, $attributes, $uid, $depth = 0 ) {
		$classes = [
			'zior-block-coupon-categories-list',
			'zior-block-coupon-categories-list--depth-' . absint( $depth ),
		];
		if ( ! empty( $attributes['hasImage'] ) ) {
			$classes[] = 'zior-block-coupon-categories-list--has-images';
		}
		$output = '<ul class="' . esc_attr( implode( ' ', $classes ) ) . '">' . $this->renderListItems( $categories, $attributes, $uid, $depth ) . '</ul>';

		return $output;
	}

	/**
	 * Render a list of terms.
	 *
	 * @param array $categories List of terms.
	 * @param array $attributes Block attributes. Default empty array.
	 * @param int   $uid Unique ID for the rendered block, used for HTML IDs.
	 * @param int   $depth Current depth.
	 * @return string Rendered output.
	 */
	protected function renderListItems( $categories, $attributes, $uid, $depth = 0 ) {
		$output = '';

		$link_color_class_and_style = Style_Attributes_Utils::get_link_color_class_and_style( $attributes );

		$link_color_style = isset( $link_color_class_and_style['style'] ) ? $link_color_class_and_style['style'] : '';

		foreach ( $categories as $category ) {
			$output .= '
				<li class="zior-block-coupon-categories-list-item">
					<a style="' . esc_attr( $link_color_style ) . '" href="' . esc_attr( get_term_link( $category->term_id, 'coupon-categories' ) ) . '">'
						. $this->get_image_html( $category, $attributes )
						. '<span class="zior-block-coupon-categories-list-item__name">' . esc_html( $category->name ) . '</span>'
					. '</a>'
					. $this->getCount( $category, $attributes )
					. ( ! empty( $category->children ) ? $this->renderList( $category->children, $attributes, $uid, $depth + 1 ) : '' ) . '
				</li>
			';
		}

		return preg_replace( '/\r|\n/', '', $output );
	}

	/**
	 * Returns the category image html
	 *
	 * @param \WP_Term $category Term object.
	 * @param array    $attributes Block attributes. Default empty array.
	 * @param string   $size Image size, defaults to 'thumbnail'.
	 * @return string
	 */
	public function get_image_html( $category, $attributes, $size = 'thumbnail' ) {
		if ( empty( $attributes['hasImage'] ) ) {
			return '';
		}

		$image_id = get_term_meta( $category->term_id, 'thumbnail_id', true );

		if ( ! $image_id ) {
			return '<span class="zior-block-coupon-categories-list-item__image zior-block-coupon-categories-list-item__image--placeholder">' . wc_placeholder_img( 'thumbnail' ) . '</span>';
		}

		return '<span class="zior-block-coupon-categories-list-item__image">' . wp_get_attachment_image( $image_id, 'thumbnail' ) . '</span>';
	}

	/**
	 * Get the count, if displaying.
	 *
	 * @param object $category Term object.
	 * @param array  $attributes Block attributes. Default empty array.
	 * @return string
	 */
	protected function getCount( $category, $attributes ) {
		if ( empty( $attributes['hasCount'] ) ) {
			return '';
		}

		if ( $attributes['isDropdown'] ) {
			return '(' . absint( $category->count ) . ')';
		}

		$screen_reader_text = sprintf(
			/* translators: %s number of products in cart. */
			_n( '%d product', '%d products', absint( $category->count ), 'woocommerce' ),
			absint( $category->count )
		);

		return '<span class="wc-block-product-categories-list-item-count">'
			. '<span aria-hidden="true">' . absint( $category->count ) . '</span>'
			. '<span class="screen-reader-text">' . esc_html( $screen_reader_text ) . '</span>'
		. '</span>';
	}
}