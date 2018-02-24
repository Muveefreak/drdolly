<?php


/**
 * Class BS_Newsletter_Pack_Shortcode
 *
 * @since 1.0
 */
class BS_Newsletter_Pack_Shortcode extends BF_Shortcode {

	/**
	 * BS_Newsletter_Pack_Shortcode constructor.
	 *
	 * @param string $id
	 * @param array  $options
	 */
	function __construct( $id, $options ) {

		$id = 'newsletter-pack';

		$this->name = __( 'Newsletter Pack', 'better-studio' );

		$this->description = 'Show pre-defined newsletter in sidebar.';

		$_options = array(
			'defaults'            => array(
				'title'           => '',
				'newsletter'      => '',
				'style'           => 'default',
				'si_style'        => 'default',
				'bs-show-desktop' => TRUE,
				'bs-show-tablet'  => TRUE,
				'bs-show-phone'   => TRUE,
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

		if ( ! empty( $atts['newsletter'] ) || $atts['newsletter'] !== 'none' ) {

			$atts['newsletter-data'] = bsnp_get_newsletter_data( $atts['newsletter'] );

			// newsletter is not active
			if ( ! empty( $atts['newsletter-data']['active-newsletter'] ) ) {
				$atts['active_location'] = TRUE;
			}

			$atts['newsletter-data']['style-type'] = 'widget';

			$_check = array(
				'default' => '',
				''        => '',
			);

			// update custom style
			if ( ! isset( $_check[ $atts['style'] ] ) ) {
				$atts['newsletter-data']['style'] = $atts['style'];
			}

			// update custom social icon
			if ( ! isset( $_check[ $atts['si_style'] ] ) ) {

				if ( $atts['si_style'] === 'hidden' ) {
					$atts['newsletter-data']['social_icons'] = FALSE;
				} else {
					$atts['newsletter-data']['social_icons_style'] = $atts['si_style'];
				}
			}


		}

		echo BS_Newsletter_Pack_Pro()->show_newsletter( $atts );

		return ob_get_clean();

	}


	/**
	 * @return array
	 */
	public function get_fields() {

		return array(

			array(
				'type' => 'tab',
				'name' => __( 'Newsletter', 'better-studio' ),
				'id'   => 'newsletter_tab',
			),

			array(
				'name'             => __( 'Newsletter', 'better-studio' ),
				'id'               => 'newsletter',
				'type'             => 'select',
				'deferred-options' => array(
					'callback' => 'bsnp_get_newsletters_list_option',
					'args'     => array(
						- 1,
						TRUE
					),
				),
				//
				'vc_admin_label'   => TRUE,
			),
			array(
				'name'             => __( 'Override Style', 'better-studio' ),
				'id'               => 'style',
				'desc'             => __( 'Custom newsletter style for this location.', 'better-studio' ),
				'type'             => 'select_popup',
				'std'              => '',
				'deferred-options' => array(
					'callback' => 'bsnp_get_newsletters_style_option',
					'args'     => array(
						TRUE,
					),
				),
				'texts'            => array(
					'modal_title'   => __( 'Choose Newsletter Style', 'better-studio' ),
					'box_pre_title' => __( 'Active style', 'better-studio' ),
					'box_button'    => __( 'Change Style', 'better-studio' ),
				),
				'section_class'    => 'newsletter-pack-newsletter-field',
				'show_on'          => array(
					array(
						'newsletter!=none'
					)
				),
				//
				'vc_admin_label'   => FALSE,
			),
			array(
				'name'             => __( 'Override Social Icons Style', 'better-studio' ),
				'id'               => 'si_style',
				'desc'             => __( 'Custom newsletter style for this location.', 'better-studio' ),
				'type'             => 'select_popup',
				'std'              => '',
				'deferred-options' => array(
					'callback' => 'bsnp_get_newsletters_si_style_option',
					'args'     => array(
						TRUE,
						TRUE,
					),
				),
				'texts'            => array(
					'modal_title'   => __( 'Choose Social Icons Style', 'better-studio' ),
					'box_pre_title' => __( 'Active style', 'better-studio' ),
					'box_button'    => __( 'Change Style', 'better-studio' ),
				),
				'section_class'    => 'newsletter-pack-newsletter-field',
				'show_on'          => array(
					array(
						'newsletter!=none'
					)
				),
			),
			array(
				'type' => 'tab',
				'name' => __( 'Heading', 'better-studio' ),
				'id'   => 'heading',
			),
			array(
				'name'           => __( 'Title', 'better-studio' ),
				'id'             => 'title',
				'type'           => 'text',
				//
				'vc_admin_label' => FALSE,
			),
			array(
				'name'           => __( 'Show Title?', 'better-studio' ),
				'id'             => 'show_title',
				'type'           => 'switch',
				//
				'vc_admin_label' => FALSE,
			),
			array(
				'desc'           => __( 'Select custom icon for widget.', 'better-studio' ),
				'name'           => __( 'Title Icon (Optional)', 'better-studio' ),
				'type'           => 'icon_select',
				'id'             => 'icon',
				//
				'vc_admin_label' => FALSE,
			),
			array(
				'type' => 'tab',
				'name' => __( 'Design options', 'better-studio' ),
				'id'   => 'design_options',
			),
			array(
				'name' => __( 'CSS box', 'better-studio' ),
				'type' => 'css_editor',
				'id'   => 'css',
			),
			array(
				'name'           => __( 'Custom CSS Class', 'better-studio' ),
				'section_class'  => 'bf-section-two-column',
				'id'             => 'css-class',
				'type'           => 'text',
				//
				'vc_admin_label' => FALSE,
			),
			array(
				'name'           => __( 'Custom ID', 'better-studio' ),
				'section_class'  => 'bf-section-two-column',
				'id'             => 'custom-id',
				'type'           => 'text',
				//
				'vc_admin_label' => FALSE,
			),
		);
	}


	/**
	 * Registers Visual Composer Add-on
	 */
	function register_vc_add_on() {

		vc_map(
			array(
				'name'           => $this->name,
				'base'           => $this->id,
				'icon'           => $this->icon,
				'desc'           => $this->description,
				'weight'         => 10,
				'wrapper_height' => 'full',
				'category'       => __( 'Content', 'better-studio' ),
				'params'         => $this->vc_map_listing_all(),
			)
		);
	}


	/**
	 * TinyMCE view  settings
	 *
	 * @return array
	 */
	function tinymce_settings() {

		return array(
			'name'   => __( 'Newsletter Pack', 'better-studio' ),
			'styles' => array(
				array(
					'type' => 'custom',
					'url'  => bf_append_suffix( BS_Newsletter_Pack_Pro::dir_url() . 'css/newsletter-pack', '.css' ),
				)
			),
		);
	}

}
