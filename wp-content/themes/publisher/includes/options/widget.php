<?php
/**
 * widget.php
 *---------------------------
 * Registers options for widgets
 *
 */


// Define general widget fields and values
add_filter( 'better-framework/widgets/options/general', 'publisher_widgets_general_fields', 100 );
add_filter( 'better-framework/widgets/options/general/bf-widget-title-bg-color/default', 'publisher_general_widget_title_bg_color_field_default', 100 );
add_filter( 'better-framework/widgets/options/general/bf-widget-title-color/default', 'publisher_general_widget_title_color_field_default', 100 );
add_filter( 'better-framework/widgets/options/general/bf-widget-bg-color/default', 'publisher_general_widget_bg_color_field_default', 100 );
add_filter( 'better-framework/widgets/options/general/bf-widget-title-style/default', 'publisher_general_widget_title_style_field_default', 100 );

// Define custom css for widgets
add_filter( 'better-framework/css/widgets', 'publisher_widgets_custom_css', 100 );

if ( ! function_exists( 'publisher_widgets_general_fields' ) ) {
	/**
	 * Filter widgets general fields
	 *
	 * @param $fields
	 *
	 * @return array
	 */
	function publisher_widgets_general_fields( $fields ) {

		$fields[] = 'bf-widget-title-bg-color';
		$fields[] = 'bf-widget-title-color';
		$fields[] = 'bf-widget-bg-color';

		$fields[] = 'bf-widget-title-icon';
		$fields[] = 'bf-widget-title-link';
		$fields[] = 'bf-widget-title-style';
		$fields[] = 'bs-text-color-scheme';

		$fields[] = 'bf-widget-show-desktop';
		$fields[] = 'bf-widget-show-tablet';
		$fields[] = 'bf-widget-show-mobile';

		$fields[] = 'bf-widget-custom-class';
		$fields[] = 'bf-widget-custom-id';

		$fields[] = 'bf-widget-listing-settings';

		return $fields;

	} // publisher_widgets_general_fields
}


if ( ! function_exists( 'publisher_general_widget_title_bg_color_field_default' ) ) {
	/**
	 * Default value for widget title heading color
	 *
	 * @param $value
	 *
	 * @return string
	 */
	function publisher_general_widget_title_bg_color_field_default( $value ) {

		return publisher_get_option( 'widget_title_bg_color' );
	}
}


if ( ! function_exists( 'publisher_general_widget_title_color_field_default' ) ) {
	/**
	 * Default value for widget title text color
	 *
	 * @param $value
	 *
	 * @return string
	 */
	function publisher_general_widget_title_color_field_default( $value ) {

		return '';
	}
}


if ( ! function_exists( 'publisher_general_widget_bg_color_field_default' ) ) {
	/**
	 * Default value for widget title heading color
	 *
	 * @param $value
	 *
	 * @return string
	 */
	function publisher_general_widget_bg_color_field_default( $value ) {

		return publisher_get_option( 'widget_bg_color' );
	}
}


if ( ! function_exists( 'publisher_general_widget_title_style_field_default' ) ) {
	/**
	 * Default value for widget heading style
	 *
	 * @param $value
	 *
	 * @return string
	 */
	function publisher_general_widget_title_style_field_default( $value ) {

		return 'publisher_cb_heading_option_list';
	}
}


if ( ! function_exists( 'publisher_widgets_custom_css' ) ) {
	/**
	 * Widgets Custom css parameters
	 *
	 * @param $fields
	 *
	 * @return array
	 */
	function publisher_widgets_custom_css( $fields ) {

		$fields['bf-widget-title-bg-color'] = array(
			'field'    => 'bf-widget-title-bg-color',
			'callback' => array(
				'fun'                   => 'publisher_cb_css_generator_section_heading',
				'args'                  => array(
					'type' => 'widget_color',
				),
				'_NEEDED_WIDGET_FIELDS' => array(
					'bf-widget-title-style'
				)
			),
		);

		$fields['bf-widget-title-color'] = array(
			'field'    => 'bf-widget-title-color',
			'callback' => array(
				'fun'                   => 'publisher_cb_css_generator_section_heading',
				'args'                  => array(
					'type'    => 'widget_color',
					'section' => 'color',
				),
				'_NEEDED_WIDGET_FIELDS' => array(
					'bf-widget-title-style'
				)
			),
		);

		$fields['bf-widget-bg-color'] = array(
			'field'    => 'bf-widget-bg-color',
			array(
				'selector' => array(
					'%%widget-id%%',
				),
				'prop'     => array(
					'background-color' => '%%value%%; padding: 20px;',
				),
			),
			'callback' => array(
				'fun'                   => 'publisher_cb_css_generator_section_heading',
				'args'                  => array(
					'type'    => 'widget_color',
					'section' => 'bg_fix',
				),
				'_NEEDED_WIDGET_FIELDS' => array(
					'bf-widget-title-style'
				)
			),
		);


		return $fields;
	}
}


// Customizes heading style per widget
if ( ! is_admin() ) {

	add_filter( 'dynamic_sidebar_params', 'publisher_cb_customize_widget_heading', 99, 2 );

	/**
	 * Adds heading class to widget title!
	 *
	 * @param $params
	 *
	 * @return mixed
	 */
	function publisher_cb_customize_widget_heading( $params ) {

		global $wp_registered_widgets;

		$id = $params[0]['widget_id']; // Current widget ID

		if ( isset( $wp_registered_widgets[ $id ]['callback'][0] ) && is_object( $wp_registered_widgets[ $id ]['callback'][0] ) ) {

			// Get settings for all widgets of this type
			$settings = $wp_registered_widgets[ $id ]['callback'][0]->get_settings();

			// Get settings for this instance of the widget
			$setting_key = substr( $id, strrpos( $id, '-' ) + 1 );
			$instance    = isset( $settings[ $setting_key ] ) ? $settings[ $setting_key ] : array();

			// Current customized heading style or read from panel!
			{
				$heading_style = 'default';

				$_check = array(
					''        => '',
					'default' => '',
				);

				if ( isset( $instance['bf-widget-title-style'] ) ) {
					$heading_style = $instance['bf-widget-title-style'];
				}

				if ( isset( $_check[ $heading_style ] ) ) {

					$_check_2 = array(
						'footer-1' => '',
						'footer-2' => '',
						'footer-3' => '',
						'footer-4' => '',
					);

					if ( isset( $_check_2[ bf_get_current_sidebar() ] ) ) {
						$heading_style = publisher_get_option( 'footer_widgets_heading_style' );
					}

					if ( isset( $_check[ $heading_style ] ) ) {
						$heading_style = publisher_get_option( 'widgets_heading_style' );
					}
				}

				if ( isset( $_check[ $heading_style ] ) ) {
					$heading_style = publisher_get_option( 'section_heading_style' );
				}
			}

			$params[0]['before_title'] = str_replace(
				'class="section-heading',
				'class="section-heading ' . publisher_get_block_heading_class( $heading_style ),
				$params[0]['before_title']
			);

		}

		return $params;
	}
}
