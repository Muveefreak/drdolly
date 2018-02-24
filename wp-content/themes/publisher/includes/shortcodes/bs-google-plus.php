<?php
/**
 * bs-google-plus.php
 *---------------------------
 * [bs-google-plus] short code & widget
 *
 */


/**
 * Publisher Google+ Shortcode
 */
class Publisher_Google_Plus_Shortcode extends BF_Shortcode {

	function __construct( $id, $options ) {

		$id = 'bs-google-plus';

		$_options = array(
			'defaults'            => array(
				'title'                => publisher_translation_get( 'widget_google_plus' ),
				'show_title'           => TRUE,
				'icon'                 => '',
				'heading_color'        => '',
				'heading_style'        => 'default',
				'type'                 => 'profile', // or page, community
				'url'                  => '',
				'width'                => '326',
				'scheme'               => 'light', // or dark
				'layout'               => 'portrait', // or Landscape
				'cover'                => 'show',
				'tagline'              => 'show',
				'lang'                 => 'en-US',
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
	 * Handle displaying of shortcode
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function display( array $atts, $content = '' ) {

		ob_start();

		publisher_set_prop( 'shortcode-bs-google-plus-atts', $atts );

		publisher_get_view( 'shortcodes', 'bs-google-plus' );

		publisher_clear_props();

		return ob_get_clean();

	}


	public function get_fields() {

		return array(
			array(
				'type' => 'tab',
				'name' => __( 'Google+', 'publisher' ),
				'id'   => 'google_plus',
			),
			array(
				'name'           => __( 'Type', 'publisher' ),
				'id'             => 'type',
				//
				'type'           => 'select',
				'options'        => array(
					'profile'   => __( 'Profile', 'publisher' ),
					'page'      => __( 'Page', 'publisher' ),
					'community' => __( 'Community', 'publisher' ),
				),
				//
				'vc_admin_label' => FALSE,
			),
			array(
				'name'           => __( 'Google+ Page URL', 'publisher' ),
				'type'           => 'text',
				'id'             => 'url',
				//
				'vc_admin_label' => TRUE,
			),
			array(
				'type' => 'tab',
				'name' => __( 'Style', 'publisher' ),
				'id'   => 'style',
			),
			array(
				'name'           => __( 'Color Scheme', 'publisher' ),
				'id'             => 'scheme',
				//
				'type'           => 'select',
				'options'        => array(
					'light' => __( 'Light', 'publisher' ),
					'dark'  => __( 'Dark', 'publisher' ),
				),
				//
				'vc_admin_label' => FALSE,
			),
			array(
				'name'           => __( 'Width', 'publisher' ),
				'type'           => 'text',
				'id'             => 'width',
				//
				'vc_admin_label' => FALSE,
			),
			array(
				'name'           => __( 'Layout', 'publisher' ),
				'id'             => 'layout',
				//
				'type'           => 'select',
				'options'        => array(
					'portrait'  => __( 'Portrait', 'publisher' ),
					'landscape' => __( 'Landscape', 'publisher' ),
				),
				//
				'vc_admin_label' => FALSE,
			),
			array(
				'name'           => __( 'Cover', 'publisher' ),
				'id'             => 'cover',
				//
				'type'           => 'select',
				'options'        => array(
					'show' => __( 'Show', 'publisher' ),
					'hide' => __( 'Hide', 'publisher' ),
				),
				//
				'vc_admin_label' => FALSE,
			),
			array(
				'name'           => __( 'Tagline', 'publisher' ),
				'id'             => 'tagline',
				//
				'type'           => 'select',
				'options'        => array(
					'show' => __( 'Show', 'publisher' ),
					'hide' => __( 'Hide', 'publisher' ),
				),
				//
				'vc_admin_label' => FALSE,
			),
			array(
				'name'           => __( 'Language', 'publisher' ),
				'id'             => 'lang',
				//
				'type'           => 'select',
				'options'        => array(
					'af'     => __( 'Afrikaans', 'publisher' ),
					'am'     => __( 'Amharic', 'publisher' ),
					'ar'     => __( 'Arabic', 'publisher' ),
					'eu'     => __( 'Basque', 'publisher' ),
					'bn'     => __( 'Bengali', 'publisher' ),
					'bg'     => __( 'Bulgarian', 'publisher' ),
					'ca'     => __( 'Catalan', 'publisher' ),
					'zh-HK'  => __( 'Chinese (Hong Kong)', 'publisher' ),
					'zh-CN'  => __( 'Chinese (Simplified)', 'publisher' ),
					'zh-TW'  => __( 'Chinese (Traditional)', 'publisher' ),
					'hr'     => __( 'Croatian', 'publisher' ),
					'cs'     => __( 'Czech', 'publisher' ),
					'da'     => __( 'Danish', 'publisher' ),
					'nl'     => __( 'Dutch', 'publisher' ),
					'en-GB'  => __( 'English (UK)', 'publisher' ),
					'en-US'  => __( 'English (US)', 'publisher' ),
					'et'     => __( 'Estonian', 'publisher' ),
					'fil'    => __( 'Filipino', 'publisher' ),
					'fi'     => __( 'Finnish', 'publisher' ),
					'fr'     => __( 'French', 'publisher' ),
					'fr-CA'  => __( 'French (Canadian)', 'publisher' ),
					'gl'     => __( 'Galician', 'publisher' ),
					'de'     => __( 'German', 'publisher' ),
					'el'     => __( 'Greek', 'publisher' ),
					'gu'     => __( 'Gujarati', 'publisher' ),
					'iw'     => __( 'Hebrew', 'publisher' ),
					'hi'     => __( 'Hindi', 'publisher' ),
					'hu'     => __( 'Hungarian', 'publisher' ),
					'is'     => __( 'Icelandic', 'publisher' ),
					'id'     => __( 'Indonesian', 'publisher' ),
					'it'     => __( 'Italian', 'publisher' ),
					'ja'     => __( 'Japanese', 'publisher' ),
					'kn'     => __( 'Kannada', 'publisher' ),
					'ko'     => __( 'Korean', 'publisher' ),
					'lv'     => __( 'Latvian', 'publisher' ),
					'lt'     => __( 'Lithuanian', 'publisher' ),
					'ms'     => __( 'Malay', 'publisher' ),
					'ml'     => __( 'Malayalam', 'publisher' ),
					'mr'     => __( 'Marathi', 'publisher' ),
					'no'     => __( 'Norwegian', 'publisher' ),
					'fa'     => __( 'Persian', 'publisher' ),
					'pl'     => __( 'Polish', 'publisher' ),
					'pt-BR'  => __( 'Portuguese (Brazil)', 'publisher' ),
					'pt-PT'  => __( 'Portuguese (Portugal)', 'publisher' ),
					'ro'     => __( 'Romanian', 'publisher' ),
					'ru'     => __( 'Russian', 'publisher' ),
					'sr'     => __( 'Serbian', 'publisher' ),
					'sk'     => __( 'Slovak', 'publisher' ),
					'sl'     => __( 'Slovenian', 'publisher' ),
					'es'     => __( 'Spanish', 'publisher' ),
					'es-419' => __( 'Spanish (Latin America)', 'publisher' ),
					'sw'     => __( 'Swahili', 'publisher' ),
					'sv'     => __( 'Swedish', 'publisher' ),
					'ta'     => __( 'Tamil', 'publisher' ),
					'te'     => __( 'Telugu', 'publisher' ),
					'th'     => __( 'Thai', 'publisher' ),
					'tr'     => __( 'Turkish', 'publisher' ),
					'uk'     => __( 'Ukrainian', 'publisher' ),
					'ur'     => __( 'Urdu', 'publisher' ),
					'vi'     => __( 'Vietnamese', 'publisher' ),
					'zu'     => __( 'Zulu', 'publisher' ),
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
				'type'           => 'text',
				'id'             => 'title',
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
				'name'           => __( 'Heading Custom Color', 'publisher' ),
				'desc'           => __( 'Change block heading color.', 'publisher' ),
				'id'             => 'heading_color',
				'type'           => 'color',
				//
				'vc_admin_label' => FALSE,
			),
			array(
				'name'             => __( 'Custom Heading Style', 'publisher' ),
				'desc'             => __( 'Specialize this block with custom heading.', 'publisher' ),
				'id'               => 'heading_style',
				'type'             => 'select_popup',
				'deferred-options' => array(
					'callback' => 'publisher_cb_heading_option_list',
					'args'     => array(
						TRUE
					),
				),
				//
				'vc_admin_label'   => FALSE,
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
			'name'           => __( 'Google+', 'publisher' ),
			"base"           => $this->id,
			"weight"         => 10,
			"wrapper_height" => 'full',

			"category" => publisher_white_label_get_option( 'publisher' ),
			"params"   => $this->vc_map_listing_all(),
		) );

	} // register_vc_add_on


	function tinymce_settings() {

		return array(
			'name'    => __( 'Google+', 'publisher' ),
			'scripts' => array(
				array(
					'type' => 'inline',
					'data' => '(function () {var po = document.createElement(\'script\');po.type = \'text/javascript\';po.async = true;po.src = \'https://apis.google.com/js/plusone.js\';var s = document.getElementsByTagName(\'script\')[0];s.parentNode.insertBefore(po, s);})();',
				)
			),
		);
	}
} // Publisher_Google_Plus_Shortcode


/**
 * Publisher Google+ Widget
 */
class Publisher_Google_Plus_Widget extends BF_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {

		parent::__construct(
			'bs-google-plus',
			sprintf( __( '%s - Google+ Badge Box', 'publisher' ), publisher_white_label_get_option( 'publisher' ) ),
			array(
				'desc' => __( 'Adds a beautiful Google Plus badge widget.', 'publisher' )
			)
		);
	}


	/**
	 * Adds backend fields
	 */
	function load_fields() {

		// Back end form fields
		$this->fields = array(
			array(
				'name' => __( 'Title:', 'publisher' ),
				'id'   => 'title',
				'type' => 'text',
			),
			array(
				'name'    => __( 'Type:', 'publisher' ),
				'id'      => 'type',
				'type'    => 'select',
				'options' => array(
					'profile'   => __( 'Profile', 'publisher' ),
					'page'      => __( 'Page', 'publisher' ),
					'community' => __( 'Community', 'publisher' ),
				),
			),
			array(
				'name' => __( 'Google+ Page URL:', 'publisher' ),
				'id'   => 'url',
				'type' => 'text',
			),
			array(
				'name' => __( 'Width:', 'publisher' ),
				'id'   => 'width',
				'type' => 'text',
			),
			array(
				'name'    => __( 'Color Scheme:', 'publisher' ),
				'id'      => 'scheme',
				'type'    => 'select',
				'options' => array(
					'light' => __( 'Light', 'publisher' ),
					'dark'  => __( 'Dark', 'publisher' ),
				),
			),
			array(
				'name'    => __( 'Layout:', 'publisher' ),
				'id'      => 'layout',
				'type'    => 'select',
				'options' => array(
					'portrait'  => __( 'Portrait', 'publisher' ),
					'landscape' => __( 'Landscape', 'publisher' ),
				),
			),
			array(
				'name'    => __( 'Cover:', 'publisher' ),
				'id'      => 'cover',
				'type'    => 'select',
				'options' => array(
					'show' => __( 'Show', 'publisher' ),
					'hide' => __( 'Hide', 'publisher' ),
				),
			),
			array(
				'name'    => __( 'Tagline:', 'publisher' ),
				'id'      => 'tagline',
				'type'    => 'select',
				'options' => array(
					'show' => __( 'Show', 'publisher' ),
					'hide' => __( 'Hide', 'publisher' ),
				),
			),
			array(
				'name'    => __( 'Language:', 'publisher' ),
				'id'      => 'lang',
				'type'    => 'select',
				'options' => array(
					'af'     => __( 'Afrikaans', 'publisher' ),
					'am'     => __( 'Amharic', 'publisher' ),
					'ar'     => __( 'Arabic', 'publisher' ),
					'eu'     => __( 'Basque', 'publisher' ),
					'bn'     => __( 'Bengali', 'publisher' ),
					'bg'     => __( 'Bulgarian', 'publisher' ),
					'ca'     => __( 'Catalan', 'publisher' ),
					'zh-HK'  => __( 'Chinese (Hong Kong)', 'publisher' ),
					'zh-CN'  => __( 'Chinese (Simplified)', 'publisher' ),
					'zh-TW'  => __( 'Chinese (Traditional)', 'publisher' ),
					'hr'     => __( 'Croatian', 'publisher' ),
					'cs'     => __( 'Czech', 'publisher' ),
					'da'     => __( 'Danish', 'publisher' ),
					'nl'     => __( 'Dutch', 'publisher' ),
					'en-GB'  => __( 'English (UK)', 'publisher' ),
					'en-US'  => __( 'English (US)', 'publisher' ),
					'et'     => __( 'Estonian', 'publisher' ),
					'fil'    => __( 'Filipino', 'publisher' ),
					'fi'     => __( 'Finnish', 'publisher' ),
					'fr'     => __( 'French', 'publisher' ),
					'fr-CA'  => __( 'French (Canadian)', 'publisher' ),
					'gl'     => __( 'Galician', 'publisher' ),
					'de'     => __( 'German', 'publisher' ),
					'el'     => __( 'Greek', 'publisher' ),
					'gu'     => __( 'Gujarati', 'publisher' ),
					'iw'     => __( 'Hebrew', 'publisher' ),
					'hi'     => __( 'Hindi', 'publisher' ),
					'hu'     => __( 'Hungarian', 'publisher' ),
					'is'     => __( 'Icelandic', 'publisher' ),
					'id'     => __( 'Indonesian', 'publisher' ),
					'it'     => __( 'Italian', 'publisher' ),
					'ja'     => __( 'Japanese', 'publisher' ),
					'kn'     => __( 'Kannada', 'publisher' ),
					'ko'     => __( 'Korean', 'publisher' ),
					'lv'     => __( 'Latvian', 'publisher' ),
					'lt'     => __( 'Lithuanian', 'publisher' ),
					'ms'     => __( 'Malay', 'publisher' ),
					'ml'     => __( 'Malayalam', 'publisher' ),
					'mr'     => __( 'Marathi', 'publisher' ),
					'no'     => __( 'Norwegian', 'publisher' ),
					'fa'     => __( 'Persian', 'publisher' ),
					'pl'     => __( 'Polish', 'publisher' ),
					'pt-BR'  => __( 'Portuguese (Brazil)', 'publisher' ),
					'pt-PT'  => __( 'Portuguese (Portugal)', 'publisher' ),
					'ro'     => __( 'Romanian', 'publisher' ),
					'ru'     => __( 'Russian', 'publisher' ),
					'sr'     => __( 'Serbian', 'publisher' ),
					'sk'     => __( 'Slovak', 'publisher' ),
					'sl'     => __( 'Slovenian', 'publisher' ),
					'es'     => __( 'Spanish', 'publisher' ),
					'es-419' => __( 'Spanish (Latin America)', 'publisher' ),
					'sw'     => __( 'Swahili', 'publisher' ),
					'sv'     => __( 'Swedish', 'publisher' ),
					'ta'     => __( 'Tamil', 'publisher' ),
					'te'     => __( 'Telugu', 'publisher' ),
					'th'     => __( 'Thai', 'publisher' ),
					'tr'     => __( 'Turkish', 'publisher' ),
					'uk'     => __( 'Ukrainian', 'publisher' ),
					'ur'     => __( 'Urdu', 'publisher' ),
					'vi'     => __( 'Vietnamese', 'publisher' ),
					'zu'     => __( 'Zulu', 'publisher' ),
				),
			),
		);
	}
}