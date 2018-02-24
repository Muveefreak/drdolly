<?php


/**
 * BetterNewsTicker Shortcode
 */
class Better_Newsticker_Shortcode extends BF_Shortcode {

	function __construct( $id, $options ) {

		$id = 'better-newsticker';

		$options = array_merge( array(
			'defaults'            => array(
				'title'           => publisher_translation_get( 'newsticker_trending' ),
				'show_title'      => 0,
				'ticker_text'     => publisher_translation_get( 'newsticker_trending' ),
				'speed'           => 12,
				'count'           => 10,
				'cat'             => '',
				'tag'             => '',
				'class'           => '',
				'bg_color'        => '',
				'bs-show-desktop' => TRUE,
				'bs-show-tablet'  => TRUE,
				'bs-show-phone'   => TRUE,
			),
			'have_widget'         => FALSE,
			'have_vc_add_on'      => TRUE,
			'have_tinymce_add_on' => TRUE,
		), $options );

		if ( isset( $options['shortcode_class'] ) ) {
			$_options['shortcode_class'] = $options['shortcode_class'];
		}

		parent::__construct( $id, $options );
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


	public function get_fields() {

		return array(
			array(
				'type' => 'tab',
				'name' => __( 'Newsticker', 'publisher' ),
				'id'   => 'newsticker',
			),
			array(
				'name'           => __( 'News Ticker Text', 'publisher' ),
				'id'             => 'ticker_text',
				'type'           => 'text',
				//
				'vc_admin_label' => TRUE,
			),
			array(
				'name'           => __( 'Speed', 'publisher' ),
				'id'             => 'speed',
				//
				'type'           => 'slider',
				'dimension'      => 'second',
				'min'            => '3',
				'max'            => '60',
				'step'           => '1',
				//
				'std'            => '15',
				'desc'           => __( 'Set the speed of the ticker cycling, in second.', 'publisher' ),
				//
				'vc_admin_label' => TRUE,
			),
			array(
				'name'           => __( 'Category', 'publisher' ),
				'id'             => 'cat',
				//
				'type'           => 'select',
				'options'        => array(
					'' => __( 'All Posts', 'publisher' ),
					array(
						'label'   => __( 'Categories', 'publisher' ),
						'options' => array(
							'category_walker' => 'category_walker'
						),
					),
				),
				//
				'vc_admin_label' => TRUE,
			),
			array(
				'name'           => __( 'Number of Posts', 'publisher' ),
				'id'             => 'count',
				'type'           => 'text',
				//
				'vc_admin_label' => TRUE,
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
				'vc_admin_label' => TRUE,
			),
			array(
				'name' => __( 'Show Title?', 'publisher' ),
				'id'   => 'show_title',
				'type' => 'switch',
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
				'name' => __( 'CSS box', 'publisher' ),
				'type' => 'css_editor',
				'id'   => 'css',
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
		);
	}


	/**
	 * Registers Visual Composer Add-on
	 */
	function register_vc_add_on() {

		vc_map( array(
			'name'           => __( 'News Ticker', 'publisher' ),
			"base"           => $this->id,
			"weight"         => 10,
			"wrapper_height" => 'full',

			"category" => publisher_white_label_get_option( 'publisher' ),
			"params"   => $this->vc_map_listing_all(),
		) );

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

		publisher_set_prop( 'shortcode-better-newsticker', $atts );

		publisher_get_view( 'shortcodes', 'better-newsticker' );

		publisher_clear_props();
		publisher_clear_query();

		return ob_get_clean();
	}


	function tinymce_settings() {

		return array(
			'name' => __( 'News Ticker', 'publisher' ),

			'scripts' => array(
				array(
					'type'    => 'registered',
					'handles' => array( 'theme-libs' )
				),

				array(
					'type' => 'inline',
					'data' => 'jQuery(function($){$(\'.better-newsticker\').betterNewsticker({control_nav: true});})',
				),
			),
		);
	}
}
