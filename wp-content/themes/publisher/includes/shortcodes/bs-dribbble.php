<?php
/**
 * bs-dribbble.php
 *---------------------------
 * [bs-dribbble] shortcode & widget
 *
 */


/**
 * Publisher Dribbble Shortcode
 */
class Publisher_Dribbble_Shortcode extends BF_Shortcode {

	function __construct( $id, $options ) {

		$id = 'bs-dribbble';

		$_options = array(
			'defaults'            => array(
				'title'                => publisher_translation_get( 'widget_dribbble_shots' ),
				'show_title'           => 1,
				'icon'                 => '',
				'user_id'              => '',
				'access_token'         => '',
				'photo_count'          => 6,
				'style'                => 3,
				'bs-show-desktop'      => TRUE,
				'bs-show-tablet'       => TRUE,
				'bs-show-phone'        => TRUE,
				'bs-text-color-scheme' => '',
			),
			'have_widget'         => TRUE,
			'have_vc_add_on'      => TRUE,
			'have_tinymce_add_on' => TRUE,
		);

		if ( isset( $options['shortcode_class'] ) ) {
			$_options['shortcode_class'] = $options['shortcode_class'];
		}

		if ( isset( $options['widget_class'] ) ) {
			$_options['widget_class'] = $options['widget_class'];
		}

		parent::__construct( $id, $_options );

	}


	/**
	 * Filter custom css codes for shortcode widget!
	 *
	 * @param $fields
	 *
	 * @return array
	 */
	function register_custom_css( $fields ) {

		return $fields;
	}


	/**
	 * Handle displaying of shortcode
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function display( array $atts, $content = '' ) {

		ob_start();

		publisher_set_prop( 'shortcode-bs-dribbble-atts', $atts );

		publisher_get_view( 'shortcodes', 'bs-dribbble' );

		publisher_clear_props();

		return ob_get_clean();

	}


	public function get_fields() {

		return array(

			array(
				'type' => 'tab',
				'name' => __( 'Dribbble', 'publisher' ),
				'id'   => 'dribbble',
			),

			array(
				'name'           => __( 'Dribbble ID', 'publisher' ),
				'id'             => 'user_id',
				'type'           => 'text',
				//
				'vc_admin_label' => TRUE,
			),
			array(
				'name'           => __( 'Access Token', 'publisher' ),
				'id'             => 'access_token',
				'type'           => 'text',
				//
				'vc_admin_label' => FALSE,
			),
			array(
				'name'           => __( 'Number of Shots', 'publisher' ),
				'id'             => 'photo_count',
				'type'           => 'text',
				//
				'vc_admin_label' => FALSE,
			),
			array(
				'name'           => __( 'Columns', 'publisher' ),
				'id'             => 'style',
				//
				'type'           => 'select',
				'options'        => array(
					2        => __( '2 Column', 'publisher' ),
					3        => __( '3 Column', 'publisher' ),
					'slider' => __( 'Slider', 'publisher' ),
				),
				//
				'vc_admin_label' => FALSE,
			),
			array(
				'type' => 'tab',
				'name' => __( 'Heading', 'publisher' ),
				'id'   => 'heading',
			),
			array(
				'name'           => __( 'Title', 'publisher' ),
				'id'             => 'title',
				'type'           => 'text',
				//
				'vc_admin_label' => FALSE,
			),
			array(
				'name'           => __( 'Show Title?', 'publisher' ),
				'id'             => 'show_title',
				'type'           => 'switch',
				//
				'vc_admin_label' => FALSE,
			),
			array(
				'desc'           => __( 'Select custom icon for widget.', 'publisher' ),
				'name'           => __( 'Title Icon (Optional)', 'publisher' ),
				'type'           => 'icon_select',
				'id'             => 'icon',
				//
				'vc_admin_label' => FALSE,
			),
			array(
				'type' => 'tab',
				'name' => __( 'Design options', 'publisher' ),
				'id'   => 'design_options',
			),
			array(
				'section_class'  => 'style-floated-left bordered bf-css-edit-switch',
				'name'           => __( 'Show on Desktop', 'publisher' ),
				'id'             => 'bs-show-desktop',
				'type'           => 'switch',
				//
				'vc_admin_label' => FALSE,
			),
			array(
				'section_class'  => 'style-floated-left bordered bf-css-edit-switch',
				'name'           => __( 'Show on Tablet Portrait', 'publisher' ),
				'id'             => 'bs-show-tablet',
				'type'           => 'switch',
				//
				'vc_admin_label' => FALSE,
			),
			array(
				'section_class'  => 'style-floated-left bordered bf-css-edit-switch',
				'name'           => __( 'Show on Phone', 'publisher' ),
				'id'             => 'bs-show-phone',
				'type'           => 'switch',
				//
				'vc_admin_label' => FALSE,
			),
			array(
				'name'           => __( 'Block Text Color Scheme', 'publisher' ),
				'id'             => 'bs-text-color-scheme',
				//
				'type'           => 'select',
				'options'        => array(
					''      => __( '-- Default --', 'publisher' ),
					'light' => __( 'White Color Texts', 'publisher' ),
				),
				//
				'vc_admin_label' => FALSE,
			),
			array(
				'name'           => __( 'Custom CSS Class', 'publisher' ),
				'section_class'  => 'bf-section-two-column',
				'id'             => 'custom-css-class',
				'type'           => 'text',
				//
				'vc_admin_label' => FALSE,
			),
			array(
				'name'           => __( 'Custom ID', 'publisher' ),
				'section_class'  => 'bf-section-two-column',
				'id'             => 'custom-id',
				'type'           => 'text',
				//
				'vc_admin_label' => FALSE,
			),
			array(
				'name' => __( 'CSS box', 'publisher' ),
				'type' => 'css_editor',
				'id'   => 'css',
			),
		);
	}


	/**
	 * Registers Visual Composer Add-on
	 */
	function register_vc_add_on() {

		vc_map( array(
			'name'           => __( 'Dribbble Shots', 'publisher' ),
			"base"           => $this->id,
			"weight"         => 10,
			"wrapper_height" => 'full',

			"category" => publisher_white_label_get_option( 'publisher' ),
			"params"   => $this->vc_map_listing_all(),
		) );
	}


	function tinymce_settings() {

		return array(
			'name' => __( 'Dribbble Shots', 'publisher' ),
			/*
						'scripts' => array(
							array(
								'type'    => 'registered',
								'handles' => array( 'theme-libs' )
							),
						),
			*/
		);
	}

}


if ( ! function_exists( 'publisher_shortcode_dribbble_get_data' ) ) {

	/**
	 * Wrapper ro getting Dribbble data with cache mechanism
	 *
	 * @param $atts
	 *
	 * @return array|bool|mixed|void
	 */
	function publisher_shortcode_dribbble_get_data( $atts ) {

		$data_store = 'bs-drb-' . $atts['user_id'];
		$back_store = 'bs-drb-bk-' . $atts['user_id'];

		if ( ( $data = get_transient( $data_store ) ) === FALSE ) {

			$data = publisher_shortcode_dribbble_fetch_data( $atts );

			if ( $data ) {

				$cache_time = HOUR_IN_SECONDS * 6;

				// save a transient to expire in $cache_time and a permanent backup option ( fallback )
				set_transient( $data_store, $data, $cache_time );
				update_option( $back_store, $data, 'no' );

			} // fallback to permanent backup store
			else {
				$data = get_option( $back_store );
			}
		}

		return $data;
	}
}


if ( ! function_exists( 'publisher_shortcode_dribbble_fetch_data' ) ) {
	/**
	 * Retrieve Dribbble fresh data
	 *
	 * @param $atts
	 *
	 * @return array|bool
	 */
	function publisher_shortcode_dribbble_fetch_data( $atts ) {

		if ( ! class_exists( 'Publisher_Dribbble_Client_v1' ) ) {
			require_once bf_get_theme_dir( 'includes/libs/bs-theme-api/class-publisher-dribbble-api.php' );
		}

		$client = new Publisher_Dribbble_Client_v1( $atts['access_token'] );

		try {
			$shots = $client->getUserShots( $atts['user_id'] );
		} catch( Exception $e ) {
			$shots = array();
		}

		return $shots;
	}
}


/**
 * Publisher Dribbble Widget
 */
class Publisher_Dribbble_Widget extends BF_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {

		parent::__construct(
			'bs-dribbble',
			sprintf( __( '%s - Dribbble', 'publisher' ), publisher_white_label_get_option( 'publisher' ) ),
			array(
				'desc' => __( 'Display latest shots from Dribbble.', 'publisher' )
			)
		);
	}


	/**
	 * Loads fields
	 */
	function load_fields() {

		// Back end form fields
		$this->fields = array(
			array(
				'name'      => __( 'Instructions', 'publisher' ),
				'id'        => 'help',
				'type'      => 'info',
				'std'       => wp_kses( sprintf( __( '<p>You need to get the access token from your Dribbble account.</p>
                <ol>
                    <li>Go to <a href="%s" target="_blank">Applications</a> page.</li>
                    <li>Click on <strong>Register a new application</strong> button.</li>
                    <li>Fill all fields in next page and click on "<strong>Register application</strong>" button.</li>
                    <li>Copy "<strong>Client Access Token</strong>" in next page and paste in following Access Token field.</li>
                </ol>
                ', 'publisher' ), 'https://goo.gl/Xtidw3' ), bf_trans_allowed_html() ),
				'state'     => 'open',
				'info-type' => 'help',
			),
			array(
				'name' => __( 'Title', 'publisher' ),
				'id'   => 'title',
				'type' => 'text',
			),
			array(
				'name' => __( 'Dribbble ID', 'publisher' ),
				'id'   => 'user_id',
				'type' => 'text',
			),
			array(
				'name' => __( 'Access Token', 'publisher' ),
				'id'   => 'access_token',
				'type' => 'text',
			),
			array(
				'name' => __( 'Number of Shots', 'publisher' ),
				'id'   => 'photo_count',
				'type' => 'text',
			),
			array(
				'name'    => __( 'Columns', 'publisher' ),
				'id'      => 'style',
				'type'    => 'select',
				'options' => array(
					2        => __( '2 Column', 'publisher' ),
					3        => __( '3 Column', 'publisher' ),
					'slider' => __( 'Slider', 'publisher' ),
				),
			),
		);

	}


}
