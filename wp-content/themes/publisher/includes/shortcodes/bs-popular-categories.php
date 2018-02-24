<?php
/**
 * bs-popular-categories.php
 *---------------------------
 * [bs-popular-categories] shortcode & widget
 *
 */


/**
 * Publisher Popular Categories Shortcode
 */
class Publisher_Popular_Categories_Shortcode extends BF_Shortcode {

	function __construct( $id, $options ) {

		$id = 'bs-popular-categories';

		$_options = array(
			'defaults'            => array(
				'title'                => publisher_translation_get( 'widget_popular_categories' ),
				'show_title'           => 1,
				'icon'                 => '',
				'heading_style'        => 'default',
				'count'                => 6,
				'exclude'              => '',
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

		publisher_set_prop( 'shortcode-bs-popular-categories-atts', $atts );

		publisher_get_view( 'shortcodes', 'bs-popular-categories' );

		publisher_clear_props();

		return ob_get_clean();

	}


	public function get_fields() {

		return array(
			array(
				'type' => 'tab',
				'name' => __( 'General', 'publisher' ),
				'id'   => 'general',
			),
			array(
				'name'           => __( 'Title', 'publisher' ),
				'type'           => 'text',
				'id'             => 'title',
				//
				'vc_admin_label' => TRUE,
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
				'name'           => __( 'Categories Limit', 'publisher' ),
				'type'           => 'text',
				'id'             => 'count',
				//
				'vc_admin_label' => FALSE,
			),
			array(
				'input-desc'     => __( 'Hold Command in Mac or CTRL in Windows to select multiple categories.', 'publisher' ),
				'name'           => __( 'Excluded Categories', 'publisher' ),
				'id'             => 'exclude',
				//
				'type'           => 'bf_select',
				'multiple'       => TRUE,
				'options'        => array(
					'' => __( '-- No Exclude --', 'publisher' ),
					array(
						'label'   => __( 'Categories', 'publisher' ),
						'options' => array(
							'category_walker' => 'category_walker'
						),
					),
				),
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
			'name'           => __( 'Popular Categories', 'publisher' ),
			"base"           => $this->id,
			"weight"         => 10,
			"wrapper_height" => 'full',

			"category" => publisher_white_label_get_option( 'publisher' ),
			"params"   => $this->vc_map_listing_all(),
		) );

	} // register_vc_add_on


	function tinymce_settings() {

		return array(
			'name' => __( 'Popular Categories', 'publisher' ),
		);
	}
}


/**
 * Publisher Popular Categories Widget
 */
class Publisher_Popular_Categories_Widget extends BF_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {

		parent::__construct(
			'bs-popular-categories',
			sprintf( __( '%s - Popular Categories', 'publisher' ), publisher_white_label_get_option( 'publisher' ) ),
			array(
				'description' => __( 'Show popular categories by post count.', 'publisher' )
			)
		);
	} // __construct


	/**
	 * Adds backend fields
	 */
	function load_fields() {

		// Back end form fields
		$this->fields = array(
			array(
				'name' => __( 'Title', 'publisher' ),
				'id'   => 'title',
				'type' => 'text',
			),
			array(
				'name' => __( 'Categories Limit', 'publisher' ),
				'id'   => 'count',
				'type' => 'text',
			),
			array(
				'type'       => 'select',
				'name'       => __( 'Excluded Categories', 'publisher' ),
				"input-desc" => __( 'Hold Command in Mac or CTRL in Windows to select multiple categories.', 'publisher' ),
				"id"         => 'exclude',
				'multiple'   => TRUE,
				'options'    => array(
					'' => __( '-- No Exclude --', 'publisher' ),
					array(
						'label'   => __( 'Categories', 'publisher' ),
						'options' => array(
							'category_walker' => 'category_walker'
						),
					),
				),
			)
		);

	}
}
