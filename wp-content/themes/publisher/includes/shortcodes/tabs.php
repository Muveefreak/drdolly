<?php
/**
 * bs-tabs.php
 *---------------------------
 * [bs-tabs] shortcode
 *
 */

$GLOBALS['publisher_sh_tabs_count'] = NULL;
$GLOBALS['publisher_sh_tabs']       = NULL;


/**
 * Inner Tab shortcode
 */
class Publisher_Tab_Shortcode extends BF_Shortcode {

	function __construct( $id, $options ) {

		$id = 'tab';

		$_options = array(
			'defaults'            => array(
				'title'   => '',
				'content' => '',
				'id'      => '',
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

		global $publisher_sh_tabs_count, $publisher_sh_tabs;

		if ( is_null( $publisher_sh_tabs_count ) ) {
			$publisher_sh_tabs_count = 0;
		}

		$publisher_sh_tabs[ $publisher_sh_tabs_count ] = array(
			'title'   => sprintf( $atts['title'], $publisher_sh_tabs_count ),
			'content' => wpautop( do_shortcode( $content ) ),
			'id'      => ! empty( $atts['id'] ) ? $atts['id'] : mt_rand(),
		);

		$publisher_sh_tabs_count ++;
	}
}


/**
 * Publisher Heading shortcode
 */
class Publisher_Tabs_Shortcode extends BF_Shortcode {

	function __construct( $id, $options ) {

		$id = 'tabs';

		$_options = array(
			'defaults'            => array(
				'title'        => '',
				'tab_settings' => array(
					array(
						'title'   => '',
						'content' => '',
						'id'      => '',
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

		$panes  = $tabs = array();
		$output = '';

		global $publisher_sh_tabs_count, $publisher_sh_tabs;

		// parse nested shortcodes and collect data
		do_shortcode( $content );

		if ( isset( $publisher_sh_tabs_count ) && is_array( $publisher_sh_tabs ) ) {

			$count = 0;

			foreach ( $publisher_sh_tabs as $tab ) {
				$count ++;

				$tab_class = ( $count == 1 ? ' class="active"' : '' );

				$tab_pane_class = ( $count == 1 ? ' class="active tab-pane"' : ' class="tab-pane"' );

				$tabs[]  = '<li' . $tab_class . '><a href="#tab-' . $tab['id'] . '" data-toggle="tab">' . $tab['title'] . '</a></li>';
				$panes[] = '<div id="tab-' . $tab['id'] . '"' . $tab_pane_class . '>' . $tab['content'] . '</div>';
			}

			$output =
				'<div class="bs-tab-shortcode">
                    <ul class="nav nav-tabs" role="tablist">' . implode( '', $tabs ) . '</ul>
                    <div class="tab-content">' . implode( "\n", $panes ) . '</div>
                </div>';
		}

		$publisher_sh_tabs_count = $publisher_sh_tabs = NULL;

		return $output;
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
				'name'          => '',
				'id'            => 'tab_settings',
				'type'          => 'repeater',
				'add_label'     => '<i class="fa fa-plus"></i> ' . __( 'Add New Tab', 'publisher' ),
				'delete_label'  => __( 'Delete Tab', 'publisher' ),
				'item_title'    => __( 'Tab', 'publisher' ),
				'section_class' => 'full-with-both',
				'options'       => array(
					'title'   => array(
						'name' => __( 'Tab Title', 'publisher' ),
						'id'   => 'title',
						'type' => 'text',
					),
					'content' => array(
						'name'              => __( 'Tab Content', 'publisher' ),
						'id'                => 'content',
						'type'              => 'textarea',
						'shortcode_content' => TRUE,
					),
					'id'      => array(
						'name' => __( 'Custom Tab ID (Optional)', 'publisher' ),
						'id'   => 'id',
						'type' => 'text',
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
			'name'           => __( 'Tabs', 'publisher' ),
			'sub_shortcodes' => array( 'tab_settings' => 'tab' ),

			'scripts' => array(
				array(
					'type'    => 'registered',
					'handles' => array( 'theme-libs' ),
				)
			),
		);
	}
}

