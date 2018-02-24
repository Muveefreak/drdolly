<?php


/**
 * bs-newsletter-mailchimp.php
 *---------------------------
 * [bs-newsletter-mailchimp] short code & widget
 *
 */
class Publisher_Newsletter_MailChimp_Shortcode extends BF_Shortcode {

	function __construct( $id, $options ) {

		$id = 'bs-newsletter-mailchimp';

		$_options = array(
			'defaults'            => array(
				'title'                => publisher_translation_get( 'widget_newsletter' ),
				'show_title'           => 1,
				'icon'                 => '',
				'heading_color'        => '',
				'heading_style'        => 'default',
				'mailchimp-code'       => '',
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

		publisher_set_prop( 'shortcode-bs-newsletter-mailchimp-atts', $atts );

		publisher_get_view( 'shortcodes', 'bs-newsletter-mailchimp' );

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
				'std'       => wp_kses(
					sprintf(
						__( '<p>To integrate MailChimp with your Publisher site, you will need MailChimp signup form code that you can find it with following steps:</p>
<ol>
    <li><a href="%s" target="_blank">Log in</a> to your MailChimp account.</li>
    <li>From your account Dashboard, click <strong>Lists</strong> in the navigation menu.</li>
    <li>Find the list you want to connect to your site, click on it.</li>
    <li>Find the "<strong>Sign up forms</strong>" from the list navigation, click on it.</li>
    <li>Click "<strong>Select</strong>" on the "<strong>Embedded</strong>" forms option.</li>
    <li>Find the "<strong>Copy/paste onto your site</strong>" section.</li>
    <li>Click anywhere in the box to select the code.</li>
    <li>Press "<strong>ctrl + C</strong>" on a PC or "<strong>command + C</strong>" on a Mac to copy the code.</li>
    <li>Paste it in following "<strong>MailChimp Form Code</strong>" field.</li>
</ol>
			                ', 'publisher' )
						,
						'https://goo.gl/MU6UWn'
					), bf_trans_allowed_html()
				),
				'name'      => __( 'Instructions', 'publisher' ),
				'id'        => 'help',
				//
				'type'      => 'bf_info',
				'state'     => 'open',
				'info-type' => 'help',
			),
			array(
				'name'           => __( 'MailChimp Form Code', 'publisher' ),
				'type'           => 'textarea_raw_html',
				'id'             => 'mailchimp-code',
				//
				'vc_admin_label' => FALSE,
			),
			array(
				'name'           => __( 'MailChimp List URL', 'publisher' ),
				'id'             => 'mailchimp-url',
				'type'           => 'textarea',
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
				'type'           => 'bf_media_image',
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
				'desc'           => __( 'Select custom icon for newsletter.', 'publisher' ),
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
			'name'           => __( 'Newsletter - MailChimp', 'publisher' ),
			"base"           => $this->id,
			"weight"         => 10,
			"wrapper_height" => 'full',
			"category"       => publisher_white_label_get_option( 'publisher' ),
			"params"         => $this->vc_map_listing_all(),
		) );

	} // register_vc_add_on


	function tinymce_settings() {

		return array(
			'name' => __( 'Newsletter - MailChimp', 'publisher' ),
		);
	}
}


class Publisher_Newsletter_MailChimp_Widget extends BF_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {

		parent::__construct(
			'bs-newsletter-mailchimp',
			sprintf( __( '%s - Newsletter - MailChimp', 'publisher' ), publisher_white_label_get_option( 'publisher' ) ),
			array(
				'description' => __( 'MailChimp newsletter signup form widget.', 'publisher' )
			)
		);
	}


	/**
	 * Adds backend fields
	 */
	function load_fields() {

		$this->fields = array(
			array(
				'name'      => __( 'Instructions', 'publisher' ),
				'id'        => 'help',
				'type'      => 'info',
				'std'       => wp_kses(
					sprintf(
						__( '<p>To integrate MailChimp with your Publisher site, you will need MailChimp signup form code that you can find it with following steps:</p>
<ol>
    <li><a href="%s" target="_blank">Log in</a> to your MailChimp account.</li>
    <li>From your account Dashboard, click <strong>Lists</strong> in the navigation menu.</li>
    <li>Find the list you want to connect to your site, click on it.</li>
    <li>Find the "<strong>Sign up forms</strong>" from the list navigation, click on it.</li>
    <li>Click "<strong>Select</strong>" on the "<strong>Embedded</strong>" forms option.</li>
    <li>Find the "<strong>Copy/paste onto your site</strong>" section.</li>
    <li>Click anywhere in the box to select the code.</li>
    <li>Press "<strong>ctrl + C</strong>" on a PC or "<strong>command + C</strong>" on a Mac to copy the code.</li>
    <li>Paste it in following "<strong>MailChimp Form Code</strong>" field.</li>
</ol>
			                ', 'publisher' )
						,
						'https://goo.gl/MU6UWn'
					), bf_trans_allowed_html()
				),
				'state'     => 'open',
				'info-type' => 'help',
			),
			array(
				'name' => __( 'Title', 'publisher' ),
				'id'   => 'title',
				'type' => 'text',
			),
			array(
				'name'            => __( 'MailChimp Form Code', 'publisher' ),
				'id'              => 'mailchimp-code',
				'type'            => 'textarea',
				'container_class' => 'widget-mailchimp-code-field',
			),
			array(
				'name'            => __( 'MailChimp URL', 'publisher' ),
				'id'              => 'mailchimp-url',
				'type'            => 'text',
				'container_class' => 'widget-mailchimp-url-field',
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
