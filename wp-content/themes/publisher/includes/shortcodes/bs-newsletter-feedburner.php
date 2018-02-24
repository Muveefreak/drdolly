<?php


/**
 * bs-newsletter-feedburner.php
 *---------------------------
 * [bs-subscribe-newsletter] short code & widget
 *
 */
class Publisher_Subscribe_Newsletter_Shortcode extends BF_Shortcode {

	function __construct( $id, $options ) {

		$id = 'bs-subscribe-newsletter';

		$_options = array(
			'defaults'            => array(
				'title'                => publisher_translation_get( 'widget_newsletter' ),
				'show_title'           => 1,
				'icon'                 => '',
				'heading_color'        => '',
				'heading_style'        => 'default',
				'feedburner-id'        => '',
				'msg'                  => publisher_translation_get( 'widget_newsletter_msg' ),
				'image'                => PUBLISHER_THEME_URI . 'images/other/email-illustration.png',
				'show-powered'         => TRUE,
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

		publisher_set_prop( 'shortcode-bs-newsletter-feedburner-atts', $atts );

		publisher_get_view( 'shortcodes', 'bs-newsletter-feedburner' );

		publisher_clear_props();

		return ob_get_clean();

	}


	public function get_fields() {

		return array(
			array(
				'type' => 'tab',
				'name' => __( 'Newsletter', 'publisher' ),
				'id'   => 'newsletter',
			),
			array(
				'name'           => __( 'Feedburner ID', 'publisher' ),
				'id'             => 'feedburner-id',
				'type'           => 'text',
				//
				'vc_admin_label' => FALSE,
			),
			array(
				'name'           => __( 'Image', 'publisher' ),
				'id'             => 'image',
				//
				'media_button'   => __( 'Select as Image', 'publisher' ),
				'upload_label'   => __( 'Upload Image', 'publisher' ),
				'remove_label'   => __( 'Remove', 'publisher' ),
				'media_title'    => __( 'Remove', 'publisher' ),
				'type'           => 'media_image',
				//
				'vc_admin_label' => FALSE,
			),
			array(
				'name'           => __( 'Message', 'publisher' ),
				'type'           => 'textarea',
				'id'             => 'msg',
				//
				'vc_admin_label' => FALSE,
			),
			array(
				'section_class'  => 'style-floated-left bordered bf-css-edit-switch',
				'name'           => __( 'Show Powered By?', 'publisher' ),
				'id'             => 'show-powered',
				'type'           => 'switch',
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
				'section_class'  => 'bf-section-two-column',
				'name'           => __( 'Custom ID', 'publisher' ),
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
			'name'           => __( 'Newsletter - FeedBurner', 'publisher' ),
			"base"           => $this->id,
			"weight"         => 10,
			"wrapper_height" => 'full',
			"category"       => publisher_white_label_get_option( 'publisher' ),
			"params"         => $this->vc_map_listing_all(),
		) );

	} // register_vc_add_on


	function tinymce_settings() {

		return array(
			'name' => __( 'Newsletter - FeedBurner', 'publisher' ),
		);
	}
}


class Publisher_Subscribe_Newsletter_Widget extends BF_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {

		parent::__construct(
			'bs-subscribe-newsletter',
			sprintf( __( '%s - Newsletter - FeedBurner', 'publisher' ), publisher_white_label_get_option( 'publisher' ) ),
			array(
				'description' => __( 'Widget display NewsLetter Subscribe form it support Feedburner.', 'publisher' )
			)
		);
	}


	/**
	 * Adds backend fields
	 */
	function load_fields() {

		$this->fields = array(
			array(
				'name' => __( 'Title', 'publisher' ),
				'id'   => 'title',
				'type' => 'text',
			),
			array(
				'name'       => __( 'FeedBurner ID', 'publisher' ),
				'input-desc' => __( 'Enter Feedburner ID.', 'publisher' ),
				'id'         => 'feedburner-id',
				'type'       => 'text',
			),
			array(
				'name'         => __( 'Image', 'publisher' ),
				'id'           => 'image',
				'type'         => 'media_image',
				'upload_label' => __( 'Upload Image', 'publisher' ),
				'remove_label' => __( 'Remove', 'publisher' ),
				'media_title'  => __( 'Remove', 'publisher' ),
				'media_button' => __( 'Select Image', 'publisher' ),
			),
			array(
				'name' => __( 'Message', 'publisher' ),
				'id'   => 'msg',
				'type' => 'textarea',
			),
			array(
				'name' => __( 'Show Powered By?', 'publisher' ),
				'id'   => 'show-powered',
				'type' => 'switch',
			),
		);
	}
}
