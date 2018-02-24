<?php
/**
 * bs-accordions.php
 *---------------------------
 * [bs-accordion] shortcode
 *
 */

$GLOBALS['publisher_sh_accordion_panes'] = NULL;


/**
 * Publisher Accordion Panel shortcode
 */
class Publisher_Accordion_Panel_Shortcode extends BF_Shortcode {

	function __construct( $id, $options ) {

		$id = 'accordion';

		$_options = array(
			'defaults'            => array(
				'title' => '',
				'load'  => FALSE,
			),
			'have_widget'         => FALSE,
			'have_vc_add_on'      => FALSE,
			'have_tinymce_add_on' => FALSE,
		);

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

		global $publisher_sh_accordion_panes;

		$publisher_sh_accordion_panes[] = array(
			'title'   => $atts['title'],
			'load'    => $atts['load'],
			'content' => wpautop( do_shortcode( $content ) ),
		);

	}

}


/**
 * Publisher Heading shortcode
 */
class Publisher_Accordions_Shortcode extends BF_Shortcode {

	function __construct( $id, $options ) {

		$id = 'accordions';

		$_options = array(
			'defaults'            => array(
				'title'        => '',
				'tab_settings' => array(
					array(
						'title'   => '',
						'content' => '',
					)
				),
			),
			'have_widget'         => FALSE,
			'have_vc_add_on'      => FALSE,
			'have_tinymce_add_on' => TRUE,
		);

		parent::__construct( $id, $_options );
	}


	/**
	 * Handle displaying of shortcode
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * TODO: need some refactor
	 *
	 * @return string
	 */
	function display( array $atts, $content = '' ) {

		global $publisher_sh_accordion_panes;

		$publisher_sh_accordion_panes = array();


		// parse nested shortcodes and collect data
		do_shortcode( $content );

		$id = mt_rand();

		$output = '<div class="panel-group bs-accordion-shortcode" id="accordion-' . $id . '">';

		$count = 0;


		foreach ( $publisher_sh_accordion_panes as $pane ) {

			$count ++;

			$active = $pane['load'] == 'show' ? ' in' : '';

			$output .= '<div class="panel panel-default ' . ( $active == ' in' ? 'open' : '' ) . '">
                            <div class="panel-heading ' . ( $active == ' in' ? 'active' : '' ) . '">
                              <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion-' . $id . '" href="#accordion-' . $id . '-pane-' . $count . '">';
			$output .= ! empty( $pane['title'] ) ? $pane['title'] : __( 'Accordion', 'publisher' ) . ' ' . $count;
			$output .= '</a>
                              </h4>
                            </div>
                            <div id="accordion-' . $id . '-pane-' . $count . '" class="panel-collapse collapse ' . $active . '">
                              <div class="panel-body">';
			$output .= $pane['content'];
			$output .= '
                                </div>
                            </div>
                        </div>';

		}

		unset( $publisher_sh_accordion_panes );

		return $output . '</div>';
	}


	/**
	 * Shortcode Helper: Part of Tabs
	 */
	public static function publisher_sh_tab( $atts, $content = NULL ) {

		global $publisher_sh_tabs_count, $publisher_sh_tabs;

		if ( is_null( $publisher_sh_tabs_count ) ) {
			$publisher_sh_tabs_count = 0;
		}

		extract( shortcode_atts( array( 'title' => 'Tab %d' ), $atts ), EXTR_SKIP );

		$publisher_sh_tabs[ $publisher_sh_tabs_count ] = array(
			'title'   => sprintf( $title, $publisher_sh_tabs_count ),
			'content' => wpautop( do_shortcode( $content ) ),
			'id'      => mt_rand(),
		);

		$publisher_sh_tabs_count ++;
	}


	/**
	 * Custom Fields
	 *
	 * @return array
	 */
	public function get_fields() {

		return array(
			array(
				'type' => 'tab',
				'name' => __( 'General', 'publisher' ),
				'id'   => 'general',
			),
			array(
				'name' => __( 'Title', 'publisher' ),
				'type' => 'text',
				'id'   => 'title',
			),
			array(
				'name'          => '',
				'id'            => 'accordion_settings',
				'type'          => 'repeater',
				'add_label'     => '<i class="fa fa-plus"></i> ' . __( 'Add New Accordion', 'publisher' ),
				'delete_label'  => __( 'Delete Accordion', 'publisher' ),
				'item_title'    => __( 'Accordion', 'publisher' ),
				'section_class' => 'full-with-both',
				'options'       => array(
					'title'   => array(
						'name' => __( 'Accordion Title', 'publisher' ),
						'id'   => 'title',
						'type' => 'text',
					),
					'content' => array(
						'name'              => __( 'Tab Title', 'publisher' ),
						'id'                => 'content',
						'type'              => 'textarea',
						'shortcode_content' => TRUE,
					),
					'load'    => array(
						'name'    => __( 'Default Status', 'publisher' ),
						'type'    => 'select',
						'id'      => 'load',
						'options' => array(
							'hide' => __( 'Hidden', 'publisher' ),
							'show' => __( 'Visible', 'publisher' ),
						),
					),
				),
			),
		);
	}


	/**
	 * TinyMCE view  settings
	 *
	 * @return array
	 */
	function tinymce_settings() {

		return array(
			'name'           => __( 'Accordion', 'publisher' ),
			'sub_shortcodes' => array( 'accordion_settings' => 'accordion' ),

			'scripts' => array(
				array(
					'type'    => 'registered',
					'handles' => array( 'theme-libs' ),
				)
			),
		);
	}
}
