<?php
/**
 * Class for registering a new settings page.
 */
class ZIOR_CouponReveal_Options {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'admin_menu' ] );
		add_action( 'admin_init', [ $this, 'save_settings' ] );
	}

	/**
	 * Registers a new settings page.
	 */
	public function admin_menu() {
		add_submenu_page( 'edit.php?post_type=coupons', 'Coupon Reveal Options', 'Settings', 'manage_options', 'couponreveal-settings',
		[ $this, 'couponreveal_settings' ] );
	}

	/**
	 * Get default settings.
	 *
	 * @return array
	 */
	protected function get_settings_default() {
		$settings =
			array(
				array(
					'title'     => __( 'Store Archive Template', 'zior-couponreveal' ),
					'desc'      => sprintf( __( 'Block page template for coupon store archive.' ) ),
					'id'        => 'zior_couponreveal_store_page_id',
					'type'      => 'single_select_page',
					'post_type' => 'coupon-templates',
					'class'     => 'wc-enhanced-select-nostd',
					'css'       => 'min-width:300px;',
				),
				array(
					'title'    => __( 'Category Archive Template', 'zior-couponreveal' ),
					'desc'     => sprintf( __( 'Block page template for coupon categories archive.' ) ),
					'id'       => 'zior_couponreveal_category_page_id',
					'type'     => 'single_select_page',
					'post_type' => 'coupon-templates',
					'class'    => 'wc-enhanced-select-nostd',
					'css'      => 'min-width:300px;',
				),
			);

		return apply_filters( 'zior_couponreveal_settings', $settings );
	}

	/**
	 * Get a setting from the settings API.
	 *
	 * @param string $option_name Option name.
	 * @param mixed  $default     Default value.
	 * @return mixed
	 */
	public static function get_option( $option_name, $default = '' ) {
		if ( ! $option_name ) {
			return $default;
		}

		// Array value.
		if ( strstr( $option_name, '[' ) ) {

			parse_str( $option_name, $option_array );

			// Option name is first key.
			$option_name = current( array_keys( $option_array ) );

			// Get value.
			$option_values = get_option( $option_name, '' );

			$key = key( $option_array[ $option_name ] );

			if ( isset( $option_values[ $key ] ) ) {
				$option_value = $option_values[ $key ];
			} else {
				$option_value = null;
			}
		} else {
			// Single value.
			$option_value = get_option( $option_name, null );
		}

		if ( is_array( $option_value ) ) {
			$option_value = wp_unslash( $option_value );
		} elseif ( ! is_null( $option_value ) ) {
			$option_value = stripslashes( $option_value );
		}

		return ( null === $option_value ) ? $default : $option_value;
	}

	/**
	 * Save admin fields.
	 *
	 * Loops through the options array and outputs each field.
	 *
	 * @param array $options Options array to output.
	 * @param array $data    Optional. Data to use for saving. Defaults to $_POST.
	 * @return bool
	 */
	public function save_settings() {

		if ( sanitize_text_field( $_POST['action']) != 'save_zior_couponreveal_settings' ) {
			return false;
		}

		$options = $this->get_settings_default();
		$data    = $_POST;

		if ( empty( $data ) ) {
			return false;
		}

		// Options to update will be stored here and saved later.
		$update_options   = array();
		$autoload_options = array();

		// Loop options and get values to save.
		foreach ( $options as $option ) {
			if ( ! isset( $option['id'] ) || ! isset( $option['type'] ) || ( isset( $option['is_option'] ) && false === $option['is_option'] ) ) {
				continue;
			}

			$option_name = $option['field_name'] ?? $option['id'];

			// Get posted value.
			if ( strstr( $option_name, '[' ) ) {
				parse_str( $option_name, $option_name_array );
				$option_name  = current( array_keys( $option_name_array ) );
				$setting_name = key( $option_name_array[ $option_name ] );
				$raw_value    = isset( $data[ $option_name ][ $setting_name ] ) ? wp_unslash( $data[ $option_name ][ $setting_name ] ) : null;
			} else {
				$setting_name = '';
				$raw_value    = isset( $data[ $option_name ] ) ? wp_unslash( $data[ $option_name ] ) : null;
			}

			// Format the value based on option type.
			switch ( $option['type'] ) {
				case 'checkbox':
					$value = '1' === $raw_value || 'yes' === $raw_value ? 'yes' : 'no';
					break;
				case 'textarea':
					$value = wp_kses_post( trim( $raw_value ) );
					break;
				case 'multiselect':
					$value = array_filter( array_map( 'wc_clean', (array) $raw_value ) );
					break;
				case 'select':
					$allowed_values = empty( $option['options'] ) ? array() : array_map( 'strval', array_keys( $option['options'] ) );
					if ( empty( $option['default'] ) && empty( $allowed_values ) ) {
						$value = null;
						break;
					}
					$default = ( empty( $option['default'] ) ? $allowed_values[0] : $option['default'] );
					$value   = in_array( $raw_value, $allowed_values, true ) ? $raw_value : $default;
					break;
				default:
					$value = wc_clean( $raw_value );
					break;
			}

			// Check if option is an array and handle that differently to single values.
			if ( $option_name && $setting_name ) {
				if ( ! isset( $update_options[ $option_name ] ) ) {
					$update_options[ $option_name ] = get_option( $option_name, array() );
				}
				if ( ! is_array( $update_options[ $option_name ] ) ) {
					$update_options[ $option_name ] = array();
				}
				$update_options[ $option_name ][ $setting_name ] = $value;
			} else {
				$update_options[ $option_name ] = $value;
			}

			$autoload_options[ $option_name ] = isset( $option['autoload'] ) ? (bool) $option['autoload'] : true;
		}

		// Save all options in our array.
		foreach ( $update_options as $name => $value ) {
			update_option( $name, $value, $autoload_options[ $name ] ? 'yes' : 'no' );
		}

		do_action( 'zior_couponreveal_saved', $update_options );

		return true;
	}

	public static function render_common_fields( $option ) {
		$html = '
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="'. esc_attr( $option['id'] ) .'">'. esc_html( $option['title'] ) .'</label>
			</th>
			<td class="forminp forminp-'. esc_attr( $type ) . '">
				<input
					name="'. esc_attr( $option['field_name'] ) .'"
					id="'. esc_attr( $option['id'] ) .'"
					type="'. esc_attr( $option['type'] ) .'"
					style="'. esc_attr( $option['css'] ) .'"
					value="'. esc_attr( $option['value'] ) .'"
					class="'. esc_attr( $option['class'] ) .'"
					placeholder="'. esc_attr( $option['placeholder'] ) .'"
					'. $option['custom_attributes']. ' />
					'. $option['description'] .'
			</td>
		</tr>';

		return $html;
	}

	public static function render_textarea_fields( $option ) {
		$html = '
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="'. esc_attr( $option['id'] ) .'">'. esc_html( $option['title'] ) .'</label>
			</th>
			<td class="forminp forminp-'. esc_attr( $type ) . '">
				'. $option['description'] .'
				<textarea
					name="'. esc_attr( $option['field_name'] ) .'"
					id="'. esc_attr( $option['id'] ) .'"
					type="'. esc_attr( $option['type'] ) .'"
					style="'. esc_attr( $option['css'] ) .'"
					class="'. esc_attr( $option['class'] ) .'"
					placeholder="'. esc_attr( $option['placeholder'] ) .'"
					'. $option['custom_attributes']. '>'. esc_attr( $option['value'] ) .'</textarea>
			</td>
		</tr>';

		return $html;
	}

	public static function render_select_fields( $option ) {
		$html = '
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="'. esc_attr( $option['id'] ) .'">'. esc_html( $option['title'] ) .'</label>
			</th>
			<td class="forminp forminp-'. esc_attr( $type ) . '">
				<select
					name="'. esc_attr( $value['field_name'] ) . ( 'multiselect' === $value['type'] ) ? '[]' : '' . '"
					id="'. esc_attr( $value['id'] ) .'"
					style="'. esc_attr( $value['css'] ). '>"
					class="'. esc_attr( $value['class'] ) .'"
					'. implode( ' ', $custom_attributes ) .'
					'. ( 'multiselect' === $value['type'] ? 'multiple="multiple"' : '' ) .'>';

					foreach ( $value['options'] as $key => $val ) {
						$html .= '<option value="'. esc_attr( $key ) .'"';

							if ( is_array( $option_value ) ) {
								selected( in_array( (string) $key, $option_value, true ), true );
							} else {
								selected( $option_value, (string) $key );
							}
						$html .= '>'. esc_html( $val ) . '</option>';
					}
			$html .= '</select>
			</td>
		</tr>';

		return $html;
	}

	public static function render_checkbox_fields( $option ) {
		$html = '';
		$visibility_class = array();

		if ( ! isset( $value['hide_if_checked'] ) ) {
			$value['hide_if_checked'] = false;
		}
		if ( ! isset( $value['show_if_checked'] ) ) {
			$value['show_if_checked'] = false;
		}
		if ( 'yes' === $value['hide_if_checked'] || 'yes' === $value['show_if_checked'] ) {
			$visibility_class[] = 'hidden_option';
		}
		if ( 'option' === $value['hide_if_checked'] ) {
			$visibility_class[] = 'hide_options_if_checked';
		}
		if ( 'option' === $value['show_if_checked'] ) {
			$visibility_class[] = 'show_options_if_checked';
		}

		if ( ! isset( $value['checkboxgroup'] ) || 'start' === $value['checkboxgroup'] ) {
			$html .= '<tr valign="top" class="'. esc_attr( implode( ' ', $visibility_class ) ) .'">
				<th scope="row" class="titledesc">'. esc_html( $value['title'] ) .'</th>
				<td class="forminp forminp-checkbox">
				<fieldset>';
		} else {
			$html .= '<fieldset class="'. esc_attr( implode( ' ', $visibility_class ) ). '">';
		}
	
		if ( ! empty( $value['title'] ) ) {
			$html .= '<legend class="screen-reader-text"><span>'. esc_html( $value['title'] ) .'</span></legend>';
		}

		$html .= '<label for="'. esc_attr( $value['id'] ) .'">
			<input
				name="'. esc_attr( $value['field_name'] ) .'"
				id="'. esc_attr( $value['id'] ) .'"
				type="checkbox"
				class="'. esc_attr( isset( $value['class'] ) ? $value['class'] : '' ) .'"
				value="1" '. 
				disabled( $value['disabled'] ?? false ) .'
				'. checked( $option_value, 'yes' ) .'
				'. implode( ' ', $custom_attributes ) .'
			/>'. $description .'
			</label>';

			if ( ! isset( $value['checkboxgroup'] ) || 'end' === $value['checkboxgroup'] ) {
				$html .= '</fieldset>
						</td>
					</tr>';
			} else {
				$html .= '</fieldset>';
			}

		return $html;
	}

	public static function render_singlepage_select_fields( $option ) {
		$args = array(
			'name'             => $option['id'],
			'id'               => $option['id'],
			'sort_column'      => 'menu_order',
			'sort_order'       => 'ASC',
			'show_option_none' => ' ',
			'class'            => $option['class'],
			'echo'             => false,
			'selected'         => absint( $option['value'] ),
			'post_status'      => 'publish,private,draft',
			'post_type'      => $option['post_type'],
		);
		
		if ( isset( $option['args'] ) ) {
			$args = wp_parse_args( $option['args'], $args );
		}

		$html = '<tr valign="top" class="single_select_page">
			<th scope="row" class="titledesc">
				<label>'. esc_html( $option['title'] ) .'</label>
			</th>
			<td class="forminp">
				'. str_replace( ' id=', " data-placeholder='" . esc_attr__( 'Select a page&hellip;', 'ziorcouponreveal' ) . "' style='" . $option['css'] . "' class='" . $option['class'] . "' id=", wp_dropdown_pages( $args ) ) . '<p>' . $option['desc'] .'</p>
			</td>
		</tr>';

		return $html;
	}

	/**
	 * Output admin fields.
	 *
	 * Loops through the options array and outputs each field.
	 *
	 * @param array[] $options Opens array to output.
	 */
	public static function output_fields( $options ) {
		$html = '';
		foreach ( $options as $value ) {
			if ( ! isset( $value['type'] ) ) {
				continue;
			}

			if ( ! isset( $value['value'] ) ) {
				$value['value'] = self::get_option( $value['id'], $value['default'] ) ?? '';
			}

			// Custom attribute handling.
			$custom_attributes = array();

			if ( ! empty( $value['custom_attributes'] ) && is_array( $value['custom_attributes'] ) ) {
				foreach ( $value['custom_attributes'] as $attribute => $attribute_value ) {
					$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
				}
			}

			// Switch based on type.
			switch ( $value['type'] ) {
				case 'text':
				case 'password':
				case 'datetime':
				case 'datetime-local':
				case 'date':
				case 'month':
				case 'time':
				case 'week':
				case 'number':
				case 'email':
				case 'url':
				case 'tel':
					$html .= self::render_common_fields( $value );
					break;

				// Textarea.
				case 'textarea':
					$html .= self::render_textarea_fields( $value );
					break;

				// Select boxes.
				case 'select':
				case 'multiselect':
					$html .= self::render_select_fields( $value );
					break;

				// Checkbox input.
				case 'checkbox':
					$html .= self::render_checkbox_fields( $value );
					break;
				// Single page selects.
				case 'single_select_page':
					$html .= self::render_singlepage_select_fields( $value );
					break;

				// Default: run an action.
				default:
					do_action( 'zior_couponreveal_admin_field_' . $value['type'], $value );
					break;
			}
		}

		return $html;
	}

	public function render_fields() {
		$fields = $this->get_settings_default();
		$html = $this->output_fields( $fields );
		return $html;
	}
	/**
	 * Settings page display callback.
	 */
	public function couponreveal_settings() {
		$html = '<div class="wrap" id="couponreveal_settings">
				<h1>Settings</h1>
				<form method="post">
					<input type="hidden" name="action" value="save_zior_couponreveal_settings" />
					<table class="form-table">
					'. $this->render_fields() .'
					</table>
					' . get_submit_button( esc_html__('Save Changes', 'zior-couponreveal') ) . '
				</form>
			</div>';
		echo $html;
	}
}

new ZIOR_CouponReveal_Options;