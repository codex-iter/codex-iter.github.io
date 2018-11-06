<?php
/**
 * Contains various functions that may be potentially used throughout
 * the WPForms plugin.
 *
 * @package    WPForms
 * @author     WPForms
 * @since      1.0.0
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2016, WPForms LLC
 */

/**
 * Helper function to trigger displaying a form.
 *
 * @since 1.0.2
 *
 * @param mixed $form_id
 * @param bool  $title
 * @param bool  $desc
 */
function wpforms_display( $form_id = false, $title = false, $desc = false ) {

	wpforms()->frontend->output( $form_id, $title, $desc );
}

/**
 * Performs json_decode and unslash.
 *
 * @since 1.0.0
 *
 * @param string $data
 *
 * @return array|bool
 */
function wpforms_decode( $data ) {

	if ( ! $data || empty( $data ) ) {
		return false;
	}

	return wp_unslash( json_decode( $data, true ) );
}

/**
 * Performs json_encode and wp_slash.
 *
 * @since 1.3.1.3
 *
 * @param mixed $data
 *
 * @return string
 */
function wpforms_encode( $data = false ) {

	if ( empty( $data ) ) {
		return false;
	}

	return wp_slash( wp_json_encode( $data ) );
}

/**
 * Check if a string is a valid URL.
 *
 * @since 1.0.0
 *
 * @param string $url
 *
 * @return bool
 */
function wpforms_is_url( $url ) {

	if ( preg_match( '_^(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)(?:\.(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)*(?:\.(?:[a-z\x{00a1}-\x{ffff}]{2,})))(?::\d{2,5})?(?:/[^\s]*)?$_iuS', trim( $url ) ) ) {
		return true;
	}

	return false;
}

/**
 * Get current URL.
 *
 * @since 1.0.0
 *
 * @return string
 */
function wpforms_current_url() {

	$url = ( ! empty( $_SERVER['HTTPS'] ) ) ? 'https://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] : 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

	return esc_url_raw( $url );
}

/**
 * Object to array.
 *
 * @since 1.1.7
 *
 * @param object $object
 *
 * @return mixed
 */
function wpforms_object_to_array( $object ) {

	if ( ! is_object( $object ) && ! is_array( $object ) ) {
		return $object;
	}

	if ( is_object( $object ) ) {
		$object = get_object_vars( $object );
	}

	return array_map( 'wpforms_object_to_array', $object );
}

/**
 * Get the value of a specific WPForms setting.
 *
 * @since 1.0.0
 *
 * @param string $key
 * @param mixed  $default
 * @param string $option
 *
 * @return mixed
 */
function wpforms_setting( $key, $default = false, $option = 'wpforms_settings' ) {

	$key     = wpforms_sanitize_key( $key );
	$options = get_option( $option, false );
	$value   = is_array( $options ) && ! empty( $options[ $key ] ) ? $options[ $key ] : $default;

	return $value;
}

/**
 * Sanitize key, primarily used for looking up options.
 *
 * @since 1.3.9
 *
 * @param string $key
 *
 * @return string
 */
function wpforms_sanitize_key( $key = '' ) {

	return preg_replace( '/[^a-zA-Z0-9_\-\.\:\/]/', '', $key );
}

/**
 * Check if form provided contains the specified field type.
 *
 * @since 1.0.5
 *
 * @param array|string $type
 * @param array        $form
 * @param bool         $multiple
 *
 * @return bool
 */
function wpforms_has_field_type( $type, $form, $multiple = false ) {

	$form_data = '';
	$field     = false;
	$type      = (array) $type;

	if ( $multiple ) {
		foreach ( $form as $single_form ) {
			$field = wpforms_has_field_type( $type, $single_form );
			if ( $field ) {
				break;
			}
		}

		return $field;
	} else {

		if ( is_object( $form ) && ! empty( $form->post_content ) ) {
			$form_data = wpforms_decode( $form->post_content );
		} elseif ( is_array( $form ) ) {
			$form_data = $form;
		}

		if ( empty( $form_data['fields'] ) ) {
			return false;
		}

		foreach ( $form_data['fields'] as $single_field ) {
			if ( in_array( $single_field['type'], $type, true ) ) {
				$field = true;
				break;
			}
		}

		return $field;
	}
}

/**
 * Check if form provided contains a field which a specific setting.
 *
 * @since 1.4.5
 *
 * @param string $setting
 * @param array  $form
 * @param bool   $multiple
 *
 * @return bool
 */
function wpforms_has_field_setting( $setting, $form, $multiple = false ) {

	$form_data = '';
	$field     = false;

	if ( $multiple ) {
		foreach ( $form as $single_form ) {
			$field = wpforms_has_field_setting( $setting, $single_form );
			if ( $field ) {
				break;
			}
		}

		return $field;
	} else {

		if ( is_object( $form ) && ! empty( $form->post_content ) ) {
			$form_data = wpforms_decode( $form->post_content );
		} elseif ( is_array( $form ) ) {
			$form_data = $form;
		}

		if ( empty( $form_data['fields'] ) ) {
			return false;
		}

		foreach ( $form_data['fields'] as $single_field ) {

			if ( ! empty( $single_field[ $setting ] ) ) {
				$field = true;
				break;
			}
		}

		return $field;
	}
}

/**
 * Checks if form provided contains page breaks, if so give details.
 *
 * @since 1.0.0
 *
 * @param mixed $form
 *
 * @return mixed
 */
function wpforms_has_pagebreak( $form = false ) {

	$form_data = '';
	$pagebreak = false;
	$pages     = 1;

	if ( is_object( $form ) && ! empty( $form->post_content ) ) {
		$form_data = wpforms_decode( $form->post_content );
	} elseif ( is_array( $form ) ) {
		$form_data = $form;
	}

	if ( empty( $form_data['fields'] ) ) {
		return false;
	}

	$fields = $form_data['fields'];

	foreach ( $fields as $field ) {
		if ( 'pagebreak' === $field['type'] && empty( $field['position'] ) ) {
			$pagebreak = true;
			$pages ++;
		}
	}

	if ( $pagebreak ) {
		return $pages;
	} else {
		return false;
	}
}

/**
 * Tries to find and return an top or bottom pagebreak.
 *
 * @since 1.2.1
 *
 * @param mixed $form
 * @param mixed $type
 *
 * @return array|bool
 */
function wpforms_get_pagebreak( $form = false, $type = false ) {

	$form_data = '';

	if ( is_object( $form ) && ! empty( $form->post_content ) ) {
		$form_data = wpforms_decode( $form->post_content );
	} elseif ( is_array( $form ) ) {
		$form_data = $form;
	}

	if ( empty( $form_data['fields'] ) ) {
		return false;
	}

	$fields = $form_data['fields'];
	$pages  = array();

	foreach ( $fields as $field ) {
		if ( 'pagebreak' === $field['type'] ) {
			$position = ! empty( $field['position'] ) ? $field['position'] : false;
			if ( 'pages' === $type && 'bottom' !== $position ) {
				$pages[] = $field;
			} elseif ( $position === $type ) {
				return $field;
			}
		}
	}

	if ( ! empty( $pages ) ) {
		return $pages;
	}

	return false;
}

/**
 * Returns information about pages if the form has multiple pages.
 *
 * @since 1.3.7
 *
 * @param mixed $form
 *
 * @return mixed false or an array
 */
function wpforms_get_pagebreak_details( $form = false ) {

	$form_data = '';
	$details   = array();
	$pages     = 1;

	if ( is_object( $form ) && ! empty( $form->post_content ) ) {
		$form_data = wpforms_decode( $form->post_content );
	} elseif ( is_array( $form ) ) {
		$form_data = $form;
	}

	if ( empty( $form_data['fields'] ) ) {
		return false;
	}

	foreach ( $form_data['fields'] as $field ) {
		if ( 'pagebreak' === $field['type'] ) {
			if ( empty( $field['position'] ) ) {
				$pages ++;
				$details['total']   = $pages;
				$details['pages'][] = $field;
			} elseif ( 'top' === $field['position'] ) {
				$details['top'] = $field;
			} elseif ( 'bottom' === $field['position'] ) {
				$details['bottom'] = $field;
			}
		}
	}

	if ( ! empty( $details ) ) {
		if ( empty( $details['top'] ) ) {
			$details['top'] = array();
		}
		if ( empty( $details['bottom'] ) ) {
			$details['bottom'] = array();
		}
		$details['current'] = 1;

		return $details;
	} else {
		return false;
	}
}

/**
 * Formats, sanitizes, and returns/echos HTML element ID, classes, attributes,
 * and data attributes.
 *
 * @since 1.3.7
 *
 * @param string $id
 * @param array  $class
 * @param array  $datas
 * @param array  $atts
 * @param bool   $echo
 *
 * @return string
 */
function wpforms_html_attributes( $id = '', $class = array(), $datas = array(), $atts = array(), $echo = false ) {

	$id    = trim( $id );
	$parts = array();

	if ( ! empty( $id ) ) {
		$id = sanitize_html_class( $id );
		if ( ! empty( $id ) ) {
			$parts[] = 'id="' . $id . '"';
		}
	}

	if ( ! empty( $class ) ) {
		$class = wpforms_sanitize_classes( $class, true );
		if ( ! empty( $class ) ) {
			$parts[] = 'class="' . $class . '"';
		}
	}

	if ( ! empty( $datas ) ) {
		foreach ( $datas as $data => $val ) {
			$parts[] = 'data-' . sanitize_html_class( $data ) . '="' . esc_attr( $val ) . '"';
		}
	}

	if ( ! empty( $atts ) ) {
		foreach ( $atts as $att => $val ) {
			if ( '0' == $val || ! empty( $val ) ) {
				$parts[] = sanitize_html_class( $att ) . '="' . esc_attr( $val ) . '"';
			}
		}
	}

	$output = implode( ' ', $parts );

	if ( $echo ) {
		echo trim( $output ); // phpcs:ignore
	} else {
		return trim( $output );
	}
}

/**
 * Sanitizes string of CSS classes.
 *
 * @since 1.2.1
 *
 * @param array|string $classes
 * @param bool         $convert True will convert strings to array and vice versa.
 *
 * @return string|array
 */
function wpforms_sanitize_classes( $classes, $convert = false ) {

	$array = is_array( $classes );
	$css   = array();

	if ( ! empty( $classes ) ) {
		if ( ! $array ) {
			$classes = explode( ' ', trim( $classes ) );
		}
		foreach ( $classes as $class ) {
			if ( ! empty( $class ) ) {
				$css[] = sanitize_html_class( $class );
			}
		}
	}
	if ( $array ) {
		return $convert ? implode( ' ', $css ) : $css;
	} else {
		return $convert ? $css : implode( ' ', $css );
	}
}

/**
 * Convert a file size provided, such as "2M", to bytes.
 *
 * @since 1.0.0
 * @link http://stackoverflow.com/a/22500394
 *
 * @param string $size
 *
 * @return int
 */
function wpforms_size_to_bytes( $size ) {

	if ( is_numeric( $size ) ) {
		return $size;
	}

	$suffix = substr( $size, - 1 );
	$value  = substr( $size, 0, - 1 );

	switch ( strtoupper( $suffix ) ) {
		case 'P':
			$value *= 1024;
		case 'T':
			$value *= 1024;
		case 'G':
			$value *= 1024;
		case 'M':
			$value *= 1024;
		case 'K':
			$value *= 1024;
			break;
	}

	return $value;
}

/**
 * Convert bytes to megabytes (or in some cases KB).
 *
 * @since 1.0.0
 *
 * @param int $bytes Bytes to convert to a readable format.
 *
 * @return string
 */
function wpforms_size_to_megabytes( $bytes ) {

	if ( $bytes < 1048676 ) {
		return number_format( $bytes / 1024, 1 ) . ' KB';
	} else {
		return round( number_format( $bytes / 1048576, 1 ) ) . ' MB';
	}
}

/**
 * Convert a file size provided, such as "2M", to bytes.
 *
 * @since 1.0.0
 * @link http://stackoverflow.com/a/22500394
 *
 * @param bool $bytes
 *
 * @return mixed
 */
function wpforms_max_upload( $bytes = false ) {

	$max = wp_max_upload_size();
	if ( $bytes ) {
		return $max;
	} else {
		return wpforms_size_to_megabytes( $max );
	}
}

/**
 * Retrieve actual fields from a form.
 *
 * Non-posting elements such as section divider, page break, and HTML are
 * automatically excluded. Optionally a white list can be provided.
 *
 * @since 1.0.0
 *
 * @param mixed $form
 * @param array $whitelist
 *
 * @return mixed boolean or array
 */
function wpforms_get_form_fields( $form = false, $whitelist = array() ) {

	// Accept form (post) object or form ID.
	if ( is_object( $form ) ) {
		$form = wpforms_decode( $form->post_content );
	} elseif ( is_numeric( $form ) ) {
		$form = wpforms()->form->get(
			$form,
			array(
				'content_only' => true,
			)
		);
	}

	if ( ! is_array( $form ) || empty( $form['fields'] ) ) {
		return false;
	}

	// White list of field types to allow.
	$allowed_form_fields = array(
		'text',
		'textarea',
		'select',
		'radio',
		'checkbox',
		'gdpr-checkbox',
		'email',
		'address',
		'url',
		'name',
		'hidden',
		'date-time',
		'phone',
		'number',
		'file-upload',
		'rating',
		'likert_scale',
		'signature',
		'payment-single',
		'payment-multiple',
		'payment-select',
		'payment-total',
		'net_promoter_score'
	);
	$allowed_form_fields = apply_filters( 'wpforms_get_form_fields_allowed', $allowed_form_fields );

	$whitelist = ! empty( $whitelist ) ? $whitelist : $allowed_form_fields;

	$form_fields = $form['fields'];

	foreach ( $form_fields as $id => $form_field ) {
		if ( ! in_array( $form_field['type'], $whitelist, true ) ) {
			unset( $form_fields[ $id ] );
		}
	}

	return $form_fields;
}

/**
 * Get meta key value for a form field.
 *
 * @since 1.1.9
 *
 * @param int|string $id Field ID.
 * @param string     $key Meta key.
 * @param mixed      $form_data Form data array.
 *
 * @return string
 */
function wpforms_get_form_field_meta( $id = '', $key = '', $form_data = '' ) {

	if ( empty( $id ) || empty( $key ) || empty( $form_data ) ) {
		return '';
	}

	if ( ! empty( $form_data['fields'][ $id ]['meta'][ $key ] ) ) {
		return $form_data['fields'][ $id ]['meta'][ $key ];
	} else {
		return '';
	}
}

/**
 * Get meta key value for a form field.
 *
 * @since 1.3.1
 *
 * @param string $key Meta key.
 * @param string $value
 * @param mixed  $form_data Form data array.
 *
 * @return string
 */
function wpforms_get_form_fields_by_meta( $key = '', $value = '', $form_data = '' ) {

	if ( empty( $key ) || empty( $value ) || empty( $form_data['fields'] ) ) {
		return false;
	}

	$found = array();

	foreach ( $form_data['fields'] as $id => $field ) {

		if ( ! empty( $field['meta'][ $key ] ) && $value == $field['meta'][ $key ] ) {
			$found[ $id ] = $field;
		}
	}

	if ( ! empty( $found ) ) {
		return $found;
	} else {
		return false;
	}
}

/**
 * US States
 *
 * @since 1.0.0
 *
 * @return array
 */
function wpforms_us_states() {

	$states = array(
		'AL' => esc_html__( 'Alabama', 'wpforms' ),
		'AK' => esc_html__( 'Alaska', 'wpforms' ),
		'AZ' => esc_html__( 'Arizona', 'wpforms' ),
		'AR' => esc_html__( 'Arkansas', 'wpforms' ),
		'CA' => esc_html__( 'California', 'wpforms' ),
		'CO' => esc_html__( 'Colorado', 'wpforms' ),
		'CT' => esc_html__( 'Connecticut', 'wpforms' ),
		'DE' => esc_html__( 'Delaware', 'wpforms' ),
		'DC' => esc_html__( 'District of Columbia', 'wpforms' ),
		'FL' => esc_html__( 'Florida', 'wpforms' ),
		'GA' => esc_html_x( 'Georgia', 'US State', 'wpforms' ),
		'HI' => esc_html__( 'Hawaii', 'wpforms' ),
		'ID' => esc_html__( 'Idaho', 'wpforms' ),
		'IL' => esc_html__( 'Illinois', 'wpforms' ),
		'IN' => esc_html__( 'Indiana', 'wpforms' ),
		'IA' => esc_html__( 'Iowa', 'wpforms' ),
		'KS' => esc_html__( 'Kansas', 'wpforms' ),
		'KY' => esc_html__( 'Kentucky', 'wpforms' ),
		'LA' => esc_html__( 'Louisiana', 'wpforms' ),
		'ME' => esc_html__( 'Maine', 'wpforms' ),
		'MD' => esc_html__( 'Maryland', 'wpforms' ),
		'MA' => esc_html__( 'Massachusetts', 'wpforms' ),
		'MI' => esc_html__( 'Michigan', 'wpforms' ),
		'MN' => esc_html__( 'Minnesota', 'wpforms' ),
		'MS' => esc_html__( 'Mississippi', 'wpforms' ),
		'MO' => esc_html__( 'Missouri', 'wpforms' ),
		'MT' => esc_html__( 'Montana', 'wpforms' ),
		'NE' => esc_html__( 'Nebraska', 'wpforms' ),
		'NV' => esc_html__( 'Nevada', 'wpforms' ),
		'NH' => esc_html__( 'New Hampshire', 'wpforms' ),
		'NJ' => esc_html__( 'New Jersey', 'wpforms' ),
		'NM' => esc_html__( 'New Mexico', 'wpforms' ),
		'NY' => esc_html__( 'New York', 'wpforms' ),
		'NC' => esc_html__( 'North Carolina', 'wpforms' ),
		'ND' => esc_html__( 'North Dakota', 'wpforms' ),
		'OH' => esc_html__( 'Ohio', 'wpforms' ),
		'OK' => esc_html__( 'Oklahoma', 'wpforms' ),
		'OR' => esc_html__( 'Oregon', 'wpforms' ),
		'PA' => esc_html__( 'Pennsylvania', 'wpforms' ),
		'RI' => esc_html__( 'Rhode Island', 'wpforms' ),
		'SC' => esc_html__( 'South Carolina', 'wpforms' ),
		'SD' => esc_html__( 'South Dakota', 'wpforms' ),
		'TN' => esc_html__( 'Tennessee', 'wpforms' ),
		'TX' => esc_html__( 'Texas', 'wpforms' ),
		'UT' => esc_html__( 'Utah', 'wpforms' ),
		'VT' => esc_html__( 'Vermont', 'wpforms' ),
		'VA' => esc_html__( 'Virginia', 'wpforms' ),
		'WA' => esc_html__( 'Washington', 'wpforms' ),
		'WV' => esc_html__( 'West Virginia', 'wpforms' ),
		'WI' => esc_html__( 'Wisconsin', 'wpforms' ),
		'WY' => esc_html__( 'Wyoming', 'wpforms' ),
	);

	return apply_filters( 'wpforms_us_states', $states );
}

/**
 * Countries.
 *
 * @since 1.0.0
 *
 * @return array
 */
function wpforms_countries() {

	$countries = array(
		'AF' => esc_html__( 'Afghanistan', 'wpforms' ),
		'AX' => esc_html__( 'Åland Islands', 'wpforms' ),
		'AL' => esc_html__( 'Albania', 'wpforms' ),
		'DZ' => esc_html__( 'Algeria', 'wpforms' ),
		'AS' => esc_html__( 'American Samoa', 'wpforms' ),
		'AD' => esc_html__( 'Andorra', 'wpforms' ),
		'AO' => esc_html__( 'Angola', 'wpforms' ),
		'AI' => esc_html__( 'Anguilla', 'wpforms' ),
		'AQ' => esc_html__( 'Antarctica', 'wpforms' ),
		'AG' => esc_html__( 'Antigua and Barbuda', 'wpforms' ),
		'AR' => esc_html__( 'Argentina', 'wpforms' ),
		'AM' => esc_html__( 'Armenia', 'wpforms' ),
		'AW' => esc_html__( 'Aruba', 'wpforms' ),
		'AU' => esc_html__( 'Australia', 'wpforms' ),
		'AT' => esc_html__( 'Austria', 'wpforms' ),
		'AZ' => esc_html__( 'Azerbaijan', 'wpforms' ),
		'BS' => esc_html__( 'Bahamas', 'wpforms' ),
		'BH' => esc_html__( 'Bahrain', 'wpforms' ),
		'BD' => esc_html__( 'Bangladesh', 'wpforms' ),
		'BB' => esc_html__( 'Barbados', 'wpforms' ),
		'BY' => esc_html__( 'Belarus', 'wpforms' ),
		'BE' => esc_html__( 'Belgium', 'wpforms' ),
		'BZ' => esc_html__( 'Belize', 'wpforms' ),
		'BJ' => esc_html__( 'Benin', 'wpforms' ),
		'BM' => esc_html__( 'Bermuda', 'wpforms' ),
		'BT' => esc_html__( 'Bhutan', 'wpforms' ),
		'BO' => esc_html__( 'Bolivia (Plurinational State of)', 'wpforms' ),
		'BA' => esc_html__( 'Bosnia and Herzegovina', 'wpforms' ),
		'BW' => esc_html__( 'Botswana', 'wpforms' ),
		'BV' => esc_html__( 'Bouvet Island', 'wpforms' ),
		'BR' => esc_html__( 'Brazil', 'wpforms' ),
		'IO' => esc_html__( 'British Indian Ocean Territory', 'wpforms' ),
		'BN' => esc_html__( 'Brunei Darussalam', 'wpforms' ),
		'BG' => esc_html__( 'Bulgaria', 'wpforms' ),
		'BF' => esc_html__( 'Burkina Faso', 'wpforms' ),
		'BI' => esc_html__( 'Burundi', 'wpforms' ),
		'CV' => esc_html__( 'Cabo Verde', 'wpforms' ),
		'KH' => esc_html__( 'Cambodia', 'wpforms' ),
		'CM' => esc_html__( 'Cameroon', 'wpforms' ),
		'CA' => esc_html__( 'Canada', 'wpforms' ),
		'KY' => esc_html__( 'Cayman Islands', 'wpforms' ),
		'CF' => esc_html__( 'Central African Republic', 'wpforms' ),
		'TD' => esc_html__( 'Chad', 'wpforms' ),
		'CL' => esc_html__( 'Chile', 'wpforms' ),
		'CN' => esc_html__( 'China', 'wpforms' ),
		'CX' => esc_html__( 'Christmas Island', 'wpforms' ),
		'CC' => esc_html__( 'Cocos (Keeling) Islands', 'wpforms' ),
		'CO' => esc_html__( 'Colombia', 'wpforms' ),
		'KM' => esc_html__( 'Comoros', 'wpforms' ),
		'CG' => esc_html__( 'Congo', 'wpforms' ),
		'CD' => esc_html__( 'Congo (Democratic Republic of the)', 'wpforms' ),
		'CK' => esc_html__( 'Cook Islands', 'wpforms' ),
		'CR' => esc_html__( 'Costa Rica', 'wpforms' ),
		'CI' => esc_html__( 'Côte d\'Ivoire', 'wpforms' ),
		'HR' => esc_html__( 'Croatia', 'wpforms' ),
		'CU' => esc_html__( 'Cuba', 'wpforms' ),
		'CW' => esc_html__( 'Curaçao', 'wpforms' ),
		'CY' => esc_html__( 'Cyprus', 'wpforms' ),
		'CZ' => esc_html__( 'Czech Republic', 'wpforms' ),
		'DK' => esc_html__( 'Denmark', 'wpforms' ),
		'DJ' => esc_html__( 'Djibouti', 'wpforms' ),
		'DM' => esc_html__( 'Dominica', 'wpforms' ),
		'DO' => esc_html__( 'Dominican Republic', 'wpforms' ),
		'EC' => esc_html__( 'Ecuador', 'wpforms' ),
		'EG' => esc_html__( 'Egypt', 'wpforms' ),
		'SV' => esc_html__( 'El Salvador', 'wpforms' ),
		'GQ' => esc_html__( 'Equatorial Guinea', 'wpforms' ),
		'ER' => esc_html__( 'Eritrea', 'wpforms' ),
		'EE' => esc_html__( 'Estonia', 'wpforms' ),
		'ET' => esc_html__( 'Ethiopia', 'wpforms' ),
		'FK' => esc_html__( 'Falkland Islands (Malvinas)', 'wpforms' ),
		'FO' => esc_html__( 'Faroe Islands', 'wpforms' ),
		'FJ' => esc_html__( 'Fiji', 'wpforms' ),
		'FI' => esc_html__( 'Finland', 'wpforms' ),
		'FR' => esc_html__( 'France', 'wpforms' ),
		'GF' => esc_html__( 'French Guiana', 'wpforms' ),
		'PF' => esc_html__( 'French Polynesia', 'wpforms' ),
		'TF' => esc_html__( 'French Southern Territories', 'wpforms' ),
		'GA' => esc_html__( 'Gabon', 'wpforms' ),
		'GM' => esc_html__( 'Gambia', 'wpforms' ),
		'GE' => esc_html_x( 'Georgia', 'Country', 'wpforms' ),
		'DE' => esc_html__( 'Germany', 'wpforms' ),
		'GH' => esc_html__( 'Ghana', 'wpforms' ),
		'GI' => esc_html__( 'Gibraltar', 'wpforms' ),
		'GR' => esc_html__( 'Greece', 'wpforms' ),
		'GL' => esc_html__( 'Greenland', 'wpforms' ),
		'GD' => esc_html__( 'Grenada', 'wpforms' ),
		'GP' => esc_html__( 'Guadeloupe', 'wpforms' ),
		'GU' => esc_html__( 'Guam', 'wpforms' ),
		'GT' => esc_html__( 'Guatemala', 'wpforms' ),
		'GG' => esc_html__( 'Guernsey', 'wpforms' ),
		'GN' => esc_html__( 'Guinea', 'wpforms' ),
		'GW' => esc_html__( 'Guinea-Bissau', 'wpforms' ),
		'GY' => esc_html__( 'Guyana', 'wpforms' ),
		'HT' => esc_html__( 'Haiti', 'wpforms' ),
		'HM' => esc_html__( 'Heard Island and McDonald Islands', 'wpforms' ),
		'HN' => esc_html__( 'Honduras', 'wpforms' ),
		'HK' => esc_html__( 'Hong Kong', 'wpforms' ),
		'HU' => esc_html__( 'Hungary', 'wpforms' ),
		'IS' => esc_html__( 'Iceland', 'wpforms' ),
		'IN' => esc_html__( 'India', 'wpforms' ),
		'ID' => esc_html__( 'Indonesia', 'wpforms' ),
		'IR' => esc_html__( 'Iran (Islamic Republic of)', 'wpforms' ),
		'IQ' => esc_html__( 'Iraq', 'wpforms' ),
		'IE' => esc_html__( 'Ireland (Republic of)', 'wpforms' ),
		'IM' => esc_html__( 'Isle of Man', 'wpforms' ),
		'IL' => esc_html__( 'Israel', 'wpforms' ),
		'IT' => esc_html__( 'Italy', 'wpforms' ),
		'JM' => esc_html__( 'Jamaica', 'wpforms' ),
		'JP' => esc_html__( 'Japan', 'wpforms' ),
		'JE' => esc_html__( 'Jersey', 'wpforms' ),
		'JO' => esc_html__( 'Jordan', 'wpforms' ),
		'KZ' => esc_html__( 'Kazakhstan', 'wpforms' ),
		'KE' => esc_html__( 'Kenya', 'wpforms' ),
		'KI' => esc_html__( 'Kiribati', 'wpforms' ),
		'KP' => esc_html__( 'Korea (Democratic People\'s Republic of)', 'wpforms' ),
		'KR' => esc_html__( 'Korea (Republic of)', 'wpforms' ),
		'KW' => esc_html__( 'Kuwait', 'wpforms' ),
		'KG' => esc_html__( 'Kyrgyzstan', 'wpforms' ),
		'LA' => esc_html__( 'Lao People\'s Democratic Republic', 'wpforms' ),
		'LV' => esc_html__( 'Latvia', 'wpforms' ),
		'LB' => esc_html__( 'Lebanon', 'wpforms' ),
		'LS' => esc_html__( 'Lesotho', 'wpforms' ),
		'LR' => esc_html__( 'Liberia', 'wpforms' ),
		'LY' => esc_html__( 'Libya', 'wpforms' ),
		'LI' => esc_html__( 'Liechtenstein', 'wpforms' ),
		'LT' => esc_html__( 'Lithuania', 'wpforms' ),
		'LU' => esc_html__( 'Luxembourg', 'wpforms' ),
		'MO' => esc_html__( 'Macao', 'wpforms' ),
		'MK' => esc_html__( 'Macedonia (Republic of)', 'wpforms' ),
		'MG' => esc_html__( 'Madagascar', 'wpforms' ),
		'MW' => esc_html__( 'Malawi', 'wpforms' ),
		'MY' => esc_html__( 'Malaysia', 'wpforms' ),
		'MV' => esc_html__( 'Maldives', 'wpforms' ),
		'ML' => esc_html__( 'Mali', 'wpforms' ),
		'MT' => esc_html__( 'Malta', 'wpforms' ),
		'MH' => esc_html__( 'Marshall Islands', 'wpforms' ),
		'MQ' => esc_html__( 'Martinique', 'wpforms' ),
		'MR' => esc_html__( 'Mauritania', 'wpforms' ),
		'MU' => esc_html__( 'Mauritius', 'wpforms' ),
		'YT' => esc_html__( 'Mayotte', 'wpforms' ),
		'MX' => esc_html__( 'Mexico', 'wpforms' ),
		'FM' => esc_html__( 'Micronesia (Federated States of)', 'wpforms' ),
		'MD' => esc_html__( 'Moldova (Republic of)', 'wpforms' ),
		'MC' => esc_html__( 'Monaco', 'wpforms' ),
		'MN' => esc_html__( 'Mongolia', 'wpforms' ),
		'ME' => esc_html__( 'Montenegro', 'wpforms' ),
		'MS' => esc_html__( 'Montserrat', 'wpforms' ),
		'MA' => esc_html__( 'Morocco', 'wpforms' ),
		'MZ' => esc_html__( 'Mozambique', 'wpforms' ),
		'MM' => esc_html__( 'Myanmar', 'wpforms' ),
		'NA' => esc_html__( 'Namibia', 'wpforms' ),
		'NR' => esc_html__( 'Nauru', 'wpforms' ),
		'NP' => esc_html__( 'Nepal', 'wpforms' ),
		'NL' => esc_html__( 'Netherlands', 'wpforms' ),
		'NC' => esc_html__( 'New Caledonia', 'wpforms' ),
		'NZ' => esc_html__( 'New Zealand', 'wpforms' ),
		'NI' => esc_html__( 'Nicaragua', 'wpforms' ),
		'NE' => esc_html__( 'Niger', 'wpforms' ),
		'NG' => esc_html__( 'Nigeria', 'wpforms' ),
		'NU' => esc_html__( 'Niue', 'wpforms' ),
		'NF' => esc_html__( 'Norfolk Island', 'wpforms' ),
		'MP' => esc_html__( 'Northern Mariana Islands', 'wpforms' ),
		'NO' => esc_html__( 'Norway', 'wpforms' ),
		'OM' => esc_html__( 'Oman', 'wpforms' ),
		'PK' => esc_html__( 'Pakistan', 'wpforms' ),
		'PW' => esc_html__( 'Palau', 'wpforms' ),
		'PS' => esc_html__( 'Palestine (State of)', 'wpforms' ),
		'PA' => esc_html__( 'Panama', 'wpforms' ),
		'PG' => esc_html__( 'Papua New Guinea', 'wpforms' ),
		'PY' => esc_html__( 'Paraguay', 'wpforms' ),
		'PE' => esc_html__( 'Peru', 'wpforms' ),
		'PH' => esc_html__( 'Philippines', 'wpforms' ),
		'PN' => esc_html__( 'Pitcairn', 'wpforms' ),
		'PL' => esc_html__( 'Poland', 'wpforms' ),
		'PT' => esc_html__( 'Portugal', 'wpforms' ),
		'PR' => esc_html__( 'Puerto Rico', 'wpforms' ),
		'QA' => esc_html__( 'Qatar', 'wpforms' ),
		'RE' => esc_html__( 'Réunion', 'wpforms' ),
		'RO' => esc_html__( 'Romania', 'wpforms' ),
		'RU' => esc_html__( 'Russian Federation', 'wpforms' ),
		'RW' => esc_html__( 'Rwanda', 'wpforms' ),
		'BL' => esc_html__( 'Saint Barthélemy', 'wpforms' ),
		'SH' => esc_html__( 'Saint Helena, Ascension and Tristan da Cunha', 'wpforms' ),
		'KN' => esc_html__( 'Saint Kitts and Nevis', 'wpforms' ),
		'LC' => esc_html__( 'Saint Lucia', 'wpforms' ),
		'MF' => esc_html__( 'Saint Martin (French part)', 'wpforms' ),
		'PM' => esc_html__( 'Saint Pierre and Miquelon', 'wpforms' ),
		'VC' => esc_html__( 'Saint Vincent and the Grenadines', 'wpforms' ),
		'WS' => esc_html__( 'Samoa', 'wpforms' ),
		'SM' => esc_html__( 'San Marino', 'wpforms' ),
		'ST' => esc_html__( 'Sao Tome and Principe', 'wpforms' ),
		'SA' => esc_html__( 'Saudi Arabia', 'wpforms' ),
		'SN' => esc_html__( 'Senegal', 'wpforms' ),
		'RS' => esc_html__( 'Serbia', 'wpforms' ),
		'SC' => esc_html__( 'Seychelles', 'wpforms' ),
		'SL' => esc_html__( 'Sierra Leone', 'wpforms' ),
		'SG' => esc_html__( 'Singapore', 'wpforms' ),
		'SX' => esc_html__( 'Sint Maarten (Dutch part)', 'wpforms' ),
		'SK' => esc_html__( 'Slovakia', 'wpforms' ),
		'SI' => esc_html__( 'Slovenia', 'wpforms' ),
		'SB' => esc_html__( 'Solomon Islands', 'wpforms' ),
		'SO' => esc_html__( 'Somalia', 'wpforms' ),
		'ZA' => esc_html__( 'South Africa', 'wpforms' ),
		'GS' => esc_html__( 'South Georgia and the South Sandwich Islands', 'wpforms' ),
		'SS' => esc_html__( 'South Sudan', 'wpforms' ),
		'ES' => esc_html__( 'Spain', 'wpforms' ),
		'LK' => esc_html__( 'Sri Lanka', 'wpforms' ),
		'SD' => esc_html__( 'Sudan', 'wpforms' ),
		'SR' => esc_html__( 'Suriname', 'wpforms' ),
		'SJ' => esc_html__( 'Svalbard and Jan Mayen', 'wpforms' ),
		'SZ' => esc_html__( 'Swaziland', 'wpforms' ),
		'SE' => esc_html__( 'Sweden', 'wpforms' ),
		'CH' => esc_html__( 'Switzerland', 'wpforms' ),
		'SY' => esc_html__( 'Syrian Arab Republic', 'wpforms' ),
		'TW' => esc_html__( 'Taiwan, Province of China', 'wpforms' ),
		'TJ' => esc_html__( 'Tajikistan', 'wpforms' ),
		'TZ' => esc_html__( 'Tanzania (United Republic of)', 'wpforms' ),
		'TH' => esc_html__( 'Thailand', 'wpforms' ),
		'TL' => esc_html__( 'Timor-Leste', 'wpforms' ),
		'TG' => esc_html__( 'Togo', 'wpforms' ),
		'TK' => esc_html__( 'Tokelau', 'wpforms' ),
		'TO' => esc_html__( 'Tonga', 'wpforms' ),
		'TT' => esc_html__( 'Trinidad and Tobago', 'wpforms' ),
		'TN' => esc_html__( 'Tunisia', 'wpforms' ),
		'TR' => esc_html__( 'Turkey', 'wpforms' ),
		'TM' => esc_html__( 'Turkmenistan', 'wpforms' ),
		'TC' => esc_html__( 'Turks and Caicos Islands', 'wpforms' ),
		'TV' => esc_html__( 'Tuvalu', 'wpforms' ),
		'UG' => esc_html__( 'Uganda', 'wpforms' ),
		'UA' => esc_html__( 'Ukraine', 'wpforms' ),
		'AE' => esc_html__( 'United Arab Emirates', 'wpforms' ),
		'GB' => esc_html__( 'United Kingdom of Great Britain and Northern Ireland', 'wpforms' ),
		'US' => esc_html__( 'United States of America', 'wpforms' ),
		'UM' => esc_html__( 'United States Minor Outlying Islands', 'wpforms' ),
		'UY' => esc_html__( 'Uruguay', 'wpforms' ),
		'UZ' => esc_html__( 'Uzbekistan', 'wpforms' ),
		'VU' => esc_html__( 'Vanuatu', 'wpforms' ),
		'VA' => esc_html__( 'Vatican City State', 'wpforms' ),
		'VE' => esc_html__( 'Venezuela (Bolivarian Republic of)', 'wpforms' ),
		'VN' => esc_html__( 'Viet Nam', 'wpforms' ),
		'VG' => esc_html__( 'Virgin Islands (British)', 'wpforms' ),
		'VI' => esc_html__( 'Virgin Islands (U.S.)', 'wpforms' ),
		'WF' => esc_html__( 'Wallis and Futuna', 'wpforms' ),
		'EH' => esc_html__( 'Western Sahara', 'wpforms' ),
		'YE' => esc_html__( 'Yemen', 'wpforms' ),
		'ZM' => esc_html__( 'Zambia', 'wpforms' ),
		'ZW' => esc_html__( 'Zimbabwe', 'wpforms' ),
	);

	return apply_filters( 'wpforms_countries', $countries );
}

/**
 * Calendar Months
 *
 * @since 1.3.7
 * @return array
 */
function wpforms_months() {

	$months = array(
		'Jan' => esc_html__( 'January', 'wpforms' ),
		'Feb' => esc_html__( 'February', 'wpforms' ),
		'Mar' => esc_html__( 'March', 'wpforms' ),
		'Apr' => esc_html__( 'April', 'wpforms' ),
		'May' => esc_html__( 'May', 'wpforms' ),
		'Jun' => esc_html__( 'June', 'wpforms' ),
		'Jul' => esc_html__( 'July', 'wpforms' ),
		'Aug' => esc_html__( 'August', 'wpforms' ),
		'Sep' => esc_html__( 'September', 'wpforms' ),
		'Oct' => esc_html__( 'October', 'wpforms' ),
		'Nov' => esc_html__( 'November', 'wpforms' ),
		'Dec' => esc_html__( 'December', 'wpforms' ),
	);

	return apply_filters( 'wpforms_months', $months );
}

/**
 * Calendar Days
 *
 * @since 1.3.7
 * @return array
 */
function wpforms_days() {

	$days = array(
		'Sun' => esc_html__( 'Sunday', 'wpforms' ),
		'Mon' => esc_html__( 'Monday', 'wpforms' ),
		'Tue' => esc_html__( 'Tuesday', 'wpforms' ),
		'Wed' => esc_html__( 'Wednesday', 'wpforms' ),
		'Thu' => esc_html__( 'Thursday', 'wpforms' ),
		'Fri' => esc_html__( 'Friday', 'wpforms' ),
		'Sat' => esc_html__( 'Saturday', 'wpforms' ),
	);

	return apply_filters( 'wpforms_days', $days );
}

/**
 * Lookup user IP.
 *
 * There are many ways to do this, but we prefer the way EDD does it.
 * https://github.com/easydigitaldownloads/easy-digital-downloads/blob/master/includes/misc-functions.php#L163
 *
 * @since 1.2.5
 * @return string
 */
function wpforms_get_ip() {

	$ip = '127.0.0.1';

	if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} elseif ( ! empty( $_SERVER['REMOTE_ADDR'] ) ) {
		$ip = $_SERVER['REMOTE_ADDR'];
	}

	// Fix potential CSV returned from $_SERVER variables
	$ip_array = array_map( 'trim', explode( ',', $ip ) );

	return $ip_array[0];
}

/**
 * Sanitizes hex color.
 *
 * @since 1.2.1
 *
 * @param string $color
 *
 * @return string
 */
function wpforms_sanitize_hex_color( $color ) {

	if ( empty( $color ) ) {
		return '';
	}

	// 3 or 6 hex digits, or the empty string.
	if ( preg_match( '|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ) {
		return $color;
	}

	return '';
}

/**
 * Sanitizes error message, primarily used during form frontend output.
 *
 * @since 1.3.7
 *
 * @param string $error
 *
 * @return string
 */
function wpforms_sanitize_error( $error = '' ) {

	$allow = array(
		'a'      => array(
			'href'  => array(),
			'title' => array(),
		),
		'br'     => array(),
		'em'     => array(),
		'strong' => array(),
		'p'      => array(),
	);

	return wp_kses( $error, $allow );
}

/**
 * Sanitizes a string, that can be a multiline.
 * If WP core `sanitize_textarea_field()` exists (after 4.7.0) - use it.
 * Otherwise - split onto separate lines, sanitize each one, merge again.
 *
 * @since 1.4.1
 *
 * @param string $string
 *
 * @return string If empty var is passed, or not a string - return unmodified. Otherwise - sanitize.
 */
function wpforms_sanitize_textarea_field( $string ) {

	if ( empty( $string ) || ! is_string( $string ) ) {
		return $string;
	}

	if ( function_exists( 'sanitize_textarea_field' ) ) {
		$string = sanitize_textarea_field( $string );
	} else {
		$string = implode( "\n", array_map( 'sanitize_text_field', explode( "\n", $string ) ) );
	}

	return $string;
}

/**
 * Sanitizes an array, that consists of values as strings.
 * After that - merge all array values into multiline string.
 *
 * @since 1.4.1
 *
 * @param array $array
 *
 * @return mixed If not an array is passed (or empty var) - return unmodified var. Otherwise - a merged array into multiline string.
 */
function wpforms_sanitize_array_combine( $array ) {

	if ( empty( $array ) || ! is_array( $array ) ) {
		return $array;
	}

	return implode( "\n", array_map( 'sanitize_text_field', $array ) );
}

/**
 * Detect if we should use a light or dark color based on the color given.
 *
 * @since 1.2.5
 * @link https://docs.woocommerce.com/wc-apidocs/source-function-wc_light_or_dark.html#608-627
 *
 * @param mixed $color
 * @param string $dark (default: '#000000').
 * @param string $light (default: '#FFFFFF').
 *
 * @return string
 */
function wpforms_light_or_dark( $color, $dark = '#000000', $light = '#FFFFFF' ) {

	$hex = str_replace( '#', '', $color );

	$c_r = hexdec( substr( $hex, 0, 2 ) );
	$c_g = hexdec( substr( $hex, 2, 2 ) );
	$c_b = hexdec( substr( $hex, 4, 2 ) );

	$brightness = ( ( $c_r * 299 ) + ( $c_g * 587 ) + ( $c_b * 114 ) ) / 1000;

	return $brightness > 155 ? $dark : $light;
}

/**
 * Builds and returns either a taxonomy or post type object that is
 * nests to accommodate any hierarchy.
 *
 * @since 1.3.9
 *
 * @param array $args
 * @param bool $flat
 *
 * @return bool|array
 */
function wpforms_get_hierarchical_object( $args = array(), $flat = false ) {

	if ( empty( $args['taxonomy'] ) && empty( $args['post_type'] ) ) {
		return false;
	}

	$children   = array();
	$parents    = array();
	$ref_parent = '';
	$ref_name   = '';

	if ( ! empty( $args['post_type'] ) ) {

		$defaults   = array(
			'posts_per_page' => - 1,
			'orderby'        => 'title',
			'order'          => 'ASC',
		);
		$args       = wp_parse_args( $args, $defaults );
		$items      = get_posts( $args );
		$ref_parent = 'post_parent';
		$ref_id     = 'ID';
		$ref_name   = 'post_title';

	} elseif ( ! empty( $args['taxonomy'] ) ) {

		$defaults   = array(
			'hide_empty' => false,
		);
		$args       = wp_parse_args( $args, $defaults );
		$items      = get_terms( $args );
		$ref_parent = 'parent';
		$ref_id     = 'term_id';
		$ref_name   = 'name';
	}

	if ( empty( $items ) || is_wp_error( $items ) ) {
		return false;
	}

	foreach ( $items as $item ) {
		if ( $item->{$ref_parent} ) {
			$children[ $item->{$ref_id} ]     = $item;
			$children[ $item->{$ref_id} ]->ID = (int) $item->{$ref_id};
		} else {
			$parents[ $item->{$ref_id} ]     = $item;
			$parents[ $item->{$ref_id} ]->ID = (int) $item->{$ref_id};
		}
	}

	$children_count = count( $children );
	while ( $children_count >= 1 ) {
		foreach ( $children as $child ) {
			_wpforms_get_hierarchical_object_search( $child, $parents, $children, $ref_parent );
			// $children is modified by reference, so we need to recount to make sure we met the limits.
			$children_count = count( $children );
		}
	}

	if ( $flat ) {
		$parents_flat = array();
		_wpforms_get_hierarchical_object_flatten( $parents, $parents_flat, $ref_name );

		return $parents_flat;
	}

	return $parents;
}

/**
 * Searches a given array and finds the parent of the provided object.
 *
 * @since 1.3.9
 *
 * @param object $child
 * @param array $parents
 * @param array $children
 * @param string $ref_parent
 */
function _wpforms_get_hierarchical_object_search( $child, &$parents, &$children, $ref_parent ) {

	foreach ( $parents as $id => $parent ) {

		if ( $parent->ID === $child->{$ref_parent} ) {

			if ( empty( $parent->children ) ) {
				$parents[ $id ]->children = array(
					$child->ID => $child,
				);
			} else {
				$parents[ $id ]->children[ $child->ID ] = $child;
			}

			unset( $children[ $child->ID ] );

		} elseif ( ! empty( $parent->children ) && is_array( $parent->children ) ) {

			_wpforms_get_hierarchical_object_search( $child, $parent->children, $children, $ref_parent );
		}
	}
}

/**
 * Flattens a hierarchical object.
 *
 * @since 1.3.9
 *
 * @param array $array
 * @param array $output
 * @param string $ref_name
 * @param int $level
 */
function _wpforms_get_hierarchical_object_flatten( $array, &$output, $ref_name = 'name', $level = 0 ) {

	foreach ( $array as $key => $item ) {

		$indicator           = apply_filters( 'wpforms_hierarchical_object_indicator', '&mdash;' );
		$item->{$ref_name}   = str_repeat( $indicator, $level ) . ' ' . $item->{$ref_name};
		$item->depth         = $level + 1;
		$output[ $item->ID ] = $item;

		if ( ! empty( $item->children ) ) {

			_wpforms_get_hierarchical_object_flatten( $item->children, $output, $ref_name, $level + 1 );
			unset( $output[ $item->ID ]->children );
		}
	}
}

/**
 * Returns field choice properties for field configured with dynamic choices.
 *
 * @since 1.4.5
 *
 * @param array $field     Field settings.
 * @param int   $form_id   Form ID.
 * @param array $form_data Form data.
 *
 * @return false|array
 */
function wpforms_get_field_dynamic_choices( $field, $form_id, $form_data = array() ) {

	if ( empty( $field['dynamic_choices'] ) ) {
		return false;
	}

	$choices = array();

	if ( 'post_type' === $field['dynamic_choices'] ) {

		if ( empty( $field['dynamic_post_type'] ) ) {
			return false;
		}

		$posts = wpforms_get_hierarchical_object(
			apply_filters(
				'wpforms_dynamic_choice_post_type_args',
				array(
					'post_type'      => $field['dynamic_post_type'],
					'posts_per_page' => -1,
					'orderby'        => 'title',
					'order'          => 'ASC',
				),
				$field,
				$form_id
			),
			true
		);

		foreach ( $posts as $post ) {
			$choices[] = array(
				'value' => $post->ID,
				'label' => $post->post_title,
				'depth' => isset( $post->depth ) ? absint( $post->depth ) : 1,
			);
		}
	} elseif ( 'taxonomy' === $field['dynamic_choices'] ) {

		if ( empty( $field['dynamic_taxonomy'] ) ) {
			return false;
		}

		$terms = wpforms_get_hierarchical_object(
			apply_filters(
				'wpforms_dynamic_choice_taxonomy_args',
				array(
					'taxonomy'   => $field['dynamic_taxonomy'],
					'hide_empty' => false,
				),
				$field,
				$form_data
			),
			true
		);

		foreach ( $terms as $term ) {
			$choices[] = array(
				'value' => $term->term_id,
				'label' => $term->name,
				'depth' => isset( $term->depth ) ? absint( $term->depth ) : 1,
			);
		}
	}

	return $choices;
}

/**
 * Insert an array into another array before/after a certain key.
 *
 * @since 1.3.9
 * @link https://gist.github.com/scribu/588429
 *
 * @param array $array The initial array.
 * @param array $pairs The array to insert.
 * @param string $key The certain key.
 * @param string $position Where to insert the array - before or after the key.
 *
 * @return array
 */
function wpforms_array_insert( $array, $pairs, $key, $position = 'after' ) {

	$key_pos = array_search( $key, array_keys( $array ), true );
	if ( 'after' === $position ) {
		$key_pos ++;
	}

	if ( false !== $key_pos ) {
		$result = array_slice( $array, 0, $key_pos );
		$result = array_merge( $result, $pairs );
		$result = array_merge( $result, array_slice( $array, $key_pos ) );
	} else {
		$result = array_merge( $array, $pairs );
	}

	return $result;
}

/**
 * Recursively remove empty strings from an array.
 *
 * @since 1.3.9.1
 *
 * @param array $data
 *
 * @return array
 */
function wpforms_array_remove_empty_strings( $data ) {

	foreach ( $data as $key => $value ) {
		if ( is_array( $value ) ) {
			$data[ $key ] = wpforms_array_remove_empty_strings( $data[ $key ] );
		}

		if ( '' === $data[ $key ] ) {
			unset( $data[ $key ] );
		}
	}

	return $data;
}

/**
 * Whether plugin works in a debug mode.
 *
 * @since 1.2.3
 *
 * @return bool
 */
function wpforms_debug() {

	$debug = false;

	if ( ( defined( 'WPFORMS_DEBUG' ) && true === WPFORMS_DEBUG ) && is_super_admin() ) {
		$debug = true;
	}

	$debug_option = get_option( 'wpforms_debug' );

	if ( $debug_option ) {
		$current_user = wp_get_current_user();
		if ( $current_user->user_login === $debug_option ) {
			$debug = true;
		}
	}

	return apply_filters( 'wpforms_debug', $debug );
}

/**
 * Helper function to display debug data.
 *
 * @since 1.0.0
 *
 * @param mixed $data What to dump, can be any type.
 * @param bool  $echo Whether to print or return. Default is to print.
 *
 * @return string
 */
function wpforms_debug_data( $data, $echo = true ) {

	if ( wpforms_debug() ) {

		$output = '<textarea style="background:#fff;margin: 20px 0;width:100%;height:500px;font-size:12px;font-family: Consolas,Monaco,monospace;direction: ltr;unicode-bidi: embed;line-height: 1.4;padding: 4px 6px 1px;" readonly>';

		$output .= "=================== WPFORMS DEBUG ===================\n\n";

		if ( is_array( $data ) || is_object( $data ) ) {
			$output .= ( print_r( $data, true ) ); // phpcs:ignore
		} else {
			$output .= $data;
		}

		$output .= '</textarea>';

		if ( $echo ) {
			echo $output; // phpcs:ignore
		} else {
			return $output;
		}
	}
}

/**
 * Log helper.
 *
 * @since 1.0.0
 *
 * @param string $title Title of a log message.
 * @param string $message Content of a log message.
 * @param array  $args Expected keys: form_id, meta, parent.
 */
function wpforms_log( $title = '', $message = '', $args = array() ) {

	// Require log title.
	if ( empty( $title ) ) {
		return;
	}

	// Force logging everything when in debug mode.
	if ( ! wpforms_debug() ) {

		/**
		 * Compare error levels to determine if we should log.
		 * Current supported levels:
		 * - Errors (error)
		 * - Spam (spam)
		 * - Entries (entry)
		 * - Payments (payment)
		 * - Providers (provider)
		 * - Conditional Logic (conditional_logic)
		 */
		$type   = ! empty( $args['type'] ) ? (array) $args['type'] : array( 'error' );
		$levels = array_intersect( $type, get_option( 'wpforms_logging', array() ) );
		if ( empty( $levels ) ) {
			return;
		}
	}

	// Meta.
	if ( ! empty( $args['form_id'] ) ) {
		$meta = array(
			'form' => absint( $args['form_id'] ),
		);
	} elseif ( ! empty( $args['meta'] ) ) {
		$meta = $args['meta'];
	} else {
		$meta = '';
	}

	// Parent element.
	$parent = ! empty( $args['parent'] ) ? $args['parent'] : 0;

	// Make arrays and objects look nice.
	if ( is_array( $message ) || is_object( $message ) ) {
		$message = '<pre>' . print_r( $message, true ) . '</pre>'; // phpcs:ignore
	}

	// Create log entry.
	wpforms()->logs->add( $title, $message, $parent, $parent, $meta );
}

/**
 * Array replace recursive, for PHP 5.2.
 */
if ( ! function_exists( 'array_replace_recursive' ) ) :
	/**
	 * PHP-agnostic version of {@link array_replace_recursive()}.
	 *
	 * The array_replace_recursive() function is a PHP 5.3 function. WordPress
	 * currently supports down to PHP 5.2, so this method is a workaround
	 * for PHP 5.2.
	 *
	 * Note: array_replace_recursive() supports infinite arguments, but for our use-
	 * case, we only need to support two arguments.
	 *
	 * Subject to removal once WordPress makes PHP 5.3.0 the minimum requirement.
	 *
	 * @since 1.2.3
	 * @see http://php.net/manual/en/function.array-replace-recursive.php#109390
	 *
	 * @param  array $base Array with keys needing to be replaced.
	 * @param  array $replacements Array with the replaced keys.
	 *
	 * @return array
	 */
	function array_replace_recursive( $base = array(), $replacements = array() ) {
		// PHP 5.2-compatible version
		// http://php.net/manual/en/function.array-replace-recursive.php#109390.
		foreach ( array_slice( func_get_args(), 1 ) as $replacements ) {
			$bref_stack = array( &$base );
			$head_stack = array( $replacements );
			$counter    = count( $head_stack );
			do {
				end( $bref_stack );
				$bref = &$bref_stack[ key( $bref_stack ) ];
				$head = array_pop( $head_stack );
				unset( $bref_stack[ key( $bref_stack ) ] );
				foreach ( array_keys( $head ) as $key ) {
					if ( isset( $key, $bref ) && is_array( $bref[ $key ] ) && is_array( $head[ $key ] ) ) {
						$bref_stack[] = &$bref[ $key ];
						$head_stack[] = $head[ $key ];
					} else {
						$bref[ $key ] = $head[ $key ];
					}
				}
			} while ( $counter );
		}

		return $base;
	}
endif;

/**
 * Check whether the current page is in AMP mode or not.
 * We need to check for specific functions, as there is no special AMP header.
 *
 * @since 1.4.1
 *
 * @return bool
 */
function wpforms_is_amp() {

	$is_amp = false;

	if (
		// AMP by Automattic; ampforwp.
		( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) ||
		// Better AMP.
		( function_exists( 'is_better_amp' ) && is_better_amp() )
	) {
		$is_amp = true;
	}

	return apply_filters( 'wpforms_is_amp', $is_amp );
}

/**
 * Decode special characters, both alpha- (<) and numeric-based (').
 *
 * @since 1.4.1
 *
 * @param string $string Raw string to decode.
 *
 * @return string
 */
function wpforms_decode_string( $string ) {

	if ( ! is_string( $string ) ) {
		return $string;
	}

	return wp_kses_decode_entities( html_entity_decode( $string, ENT_QUOTES ) );
}

add_filter( 'wpforms_email_message', 'wpforms_decode_string' );

/**
 * Get a suffix for assets, `.min` if debug is disabled.
 *
 * @since 1.4.1
 *
 * @return string
 */
function wpforms_get_min_suffix() {
	return wpforms_debug() ? '' : '.min';
}

/**
 * Get the required label text, with a filter.
 *
 * @since 1.4.4
 *
 * @return string
 */
function wpforms_get_required_label() {
	return apply_filters( 'wpforms_required_label', esc_html__( 'This field is required.', 'wpforms' ) );
}

/**
 * Get the required field label HTML, with a filter.
 *
 * @since 1.4.8
 *
 * @return string
 */
function wpforms_get_field_required_label() {

	$label_html = apply_filters_deprecated(
		'wpforms_field_required_label',
		array( ' <span class="wpforms-required-label">*</span>' ),
		'1.4.8 of WPForms plugin',
		'wpforms_get_field_required_label'
	);

	return apply_filters( 'wpforms_get_field_required_label', $label_html );
}

/**
 * Get the default capability to manage everything for WPForms.
 *
 * @since 1.4.4
 *
 * @return string
 */
function wpforms_get_capability_manage_options() {
	return apply_filters( 'wpforms_manage_cap', 'manage_options' );
}

/**
 * Check permissions for currently logged in user.
 *
 * @since 1.4.4
 *
 * @return bool
 */
function wpforms_current_user_can() {

	$capability = wpforms_get_capability_manage_options();

	return apply_filters( 'wpforms_current_user_can', current_user_can( $capability ), $capability );
}

/**
 * Get the certain date of a specified day in a specified format.
 *
 * @since 1.4.4
 *
 * @param string $period Supported values: start, end.
 * @param string $timestamp Default is the current timestamp, if left empty.
 * @param string $format Default is a MySQL format.
 *
 * @return string
 */
function wpforms_get_day_period_date( $period, $timestamp = '', $format = 'Y-m-d H:i:s' ) {

	$date = '';

	if ( empty( $timestamp ) ) {
		$timestamp = time();
	}

	switch ( $period ) {
		case 'start_of_day':
			$date = date( $format, strtotime( 'today', $timestamp ) );
			break;

		case 'end_of_day':
			$date = date( $format, strtotime( 'tomorrow', $timestamp ) - 1 );
			break;

	}

	return $date;
}

/**
 * Get an array of all the active provider addons.
 *
 * @since 1.4.7
 *
 * @return array
 */
function wpforms_get_providers_available() {
	return (array) apply_filters( 'wpforms_providers_available', array() );
}

/**
 * Get options for all providers.
 *
 * @since 1.4.7
 *
 * @param string $provider Define a single provider to get options for this one only.
 *
 * @return array
 */
function wpforms_get_providers_options( $provider = '' ) {

	$options  = get_option( 'wpforms_providers', array() );
	$provider = sanitize_key( $provider );
	$data     = $options;

	if ( ! empty( $provider ) && isset( $options[ $provider ] ) ) {
		$data = $options[ $provider ];
	}

	return (array) apply_filters( 'wpforms_get_providers_options', $data, $provider );
}

/**
 * Update options for all providers.
 *
 * @since 1.4.7
 *
 * @param string      $provider Provider slug.
 * @param array|false $options If false is passed - provider will be removed. Otherwise saved.
 * @param string      $key Optional key to identify which connection to update. If empty - generate a new one.
 */
function wpforms_update_providers_options( $provider, $options, $key = '' ) {

	$providers = wpforms_get_providers_options();
	$id        = ! empty( $key ) ? $key : uniqid();
	$provider  = sanitize_key( $provider );

	if ( $options ) {
		$providers[ $provider ][ $id ] = (array) $options;
	} else {
		unset( $providers[ $provider ] );
	}

	update_option( 'wpforms_providers', $providers );
}

/**
 * Helper function to determine if loading an WPForms related admin page.
 *
 * Here we determine if the current administration page is owned/created by
 * WPForms. This is done in compliance with WordPress best practices for
 * development, so that we only load required WPForms CSS and JS files on pages
 * we create. As a result we do not load our assets admin wide, where they might
 * conflict with other plugins needlessly, also leading to a better, faster user
 * experience for our users.
 *
 * @since 1.3.9
 *
 * @param string $slug Slug identifier for a specifc WPForms admin page.
 *
 * @return boolean
 */
function wpforms_is_admin_page( $slug = '', $view = '' ) {

	// Check against basic requirements.
	if (
		! is_admin() ||
		empty( $_REQUEST['page'] ) ||
		strpos( $_REQUEST['page'], 'wpforms' ) === false
	) {
		return false;
	}

	// Check against page slug identifier.
	if (
		( ! empty( $slug ) && 'wpforms-' . $slug !== $_REQUEST['page'] )
		|| ( empty( $slug ) && 'wpforms-builder' === $_REQUEST['page'] )
	) {
		return false;
	}

	// Check against sub-level page view.
	if (
		! empty( $view )
		&& ( empty( $_REQUEST['view'] ) || $view !== $_REQUEST['view'] )
	) {
		return false;
	}

	return true;
}