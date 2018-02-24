<?php


class Better_Ads_Shortcode extends BF_Shortcode {

	function __construct( $id, $options ) {

		$id = 'better-ads';

		$this->widget_id = 'better ads';

		$this->name = __( 'Ad Box', 'better-studio' );

		$this->description = 'BetterAds ad box';

		$_options = array(
			'defaults'            => array(
				'title'           => '',
				'type'            => '',
				'banner'          => 'none',
				'campaign'        => 'none',
				'count'           => 2,
				'columns'         => 1,
				'align'           => 'center',
				'order'           => 'ASC',
				'orderby'         => 'rand',
				'float'           => 'none',
				'show-caption'    => TRUE,
				'bs-show-desktop' => TRUE,
				'bs-show-tablet'  => TRUE,
				'bs-show-phone'   => TRUE,
			),
			'have_widget'         => TRUE,
			'have_vc_add_on'      => TRUE,
			'have_tinymce_add_on' => apply_filters( 'better-ads/shortcode/live-preview', TRUE ),
		);

		$_options = wp_parse_args( $_options, $options );

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

		$func = '';
		if ( is_singular() ) {
			$func = 'bf_get_post_meta';
		} elseif ( is_archive() ) {

			$queried_object = get_queried_object();

			if ( ! empty( $queried_object->taxonomy ) ) {
				$func = 'bf_get_term_meta';
			}
		}

		if ( ! empty( $func ) ) {
			if ( call_user_func( $func, 'bam_disable_all' ) || call_user_func( $func, 'bam_disable_widgets' ) ) {
				return '';
			}
		}

		// Add float class to shortcodes that are not inside a widget sidebar!
		if ( ! bf_get_current_sidebar() && isset( $atts['align'] ) ) {
			$atts['float'] = $atts['align'];
		}

		ob_start();
		echo Better_Ads_Manager()->show_ads( $atts );

		return ob_get_clean();

	}


	/**
	 * Registers Visual Composer Add-on
	 */
	function register_vc_add_on() {

		vc_map( array(
			"name"           => $this->name,
			"base"           => $this->id,
			"description"    => $this->description,
			"weight"         => 10,
			"wrapper_height" => 'full',

			"category" => __( 'Content', 'better-studio' ),
			"params"   => $this->vc_map_listing_all()
		) );

	} // register_vc_add_on


	/**
	 * Fields for all panels
	 *
	 * @return array
	 */
	public function get_fields() {

		return array(

			array(
				'type' => 'tab',
				'name' => __( 'General', 'better-studio' ),
				'id'   => 'general_tab',
			),
			array(
				"name"           => __( 'Ad Type', 'better-studio' ),
				"type"           => 'select',
				"id"             => 'type',
				'options'        => array(
					''         => __( '-- Select Ad Type', 'better-studio' ),
					'campaign' => __( 'Campaign', 'better-studio' ),
					'banner'   => __( 'Banner', 'better-studio' ),
				),
				//
				"vc_admin_label" => TRUE,
			),
			//
			// Banner
			//
			array(
				"type"             => 'select',
				"name"             => __( 'Banner', 'better-studio' ),
				"id"               => 'banner',
				'deferred-options' => array(
					'callback' => 'better_ads_get_banners_option',
					'args'     => array(
						- 1,
						TRUE
					),
				),
				'show_on'          => array(
					array( 'type=banner' ),
				),
				//
				"vc_admin_label"   => TRUE,
			),
			//
			// Campaign
			//
			array(
				"type"             => 'select',
				"name"             => __( 'Campaign', 'better-studio' ),
				"id"               => 'campaign',
				'deferred-options' => array(
					'callback' => 'better_ads_get_campaigns_option',
					'args'     => array(
						- 1,
						TRUE
					),
				),
				'show_on'          => array(
					array( 'type=campaign' ),
				),
				//
				"vc_admin_label"   => TRUE,
			),
			array(
				"type"           => 'text',
				"name"           => __( 'Max Amount of Allowed Banners', 'better-studio' ),
				"desc"           => __( 'Leave empty to show all banners.', 'better-studio' ),
				"id"             => 'count',
				'show_on'        => array(
					array( 'type=campaign' ),
				),
				//
				"vc_admin_label" => FALSE,
			),
			array(
				"type"           => 'select',
				"name"           => __( 'Columns', 'better-studio' ),
				"id"             => 'columns',
				"options"        => array(
					1 => __( '1 Column', 'better-studio' ),
					2 => __( '2 Column', 'better-studio' ),
					3 => __( '3 Column', 'better-studio' ),
				),
				'show_on'        => array(
					array( 'type=campaign' ),
				),
				//
				"vc_admin_label" => FALSE,
			),
			array(
				"type"           => 'select',
				"name"           => __( 'Order By', 'better-studio' ),
				"id"             => 'orderby',
				"options"        => array(
					'date'  => __( 'Date', 'better-studio' ),
					'title' => __( 'Title', 'better-studio' ),
					'rand'  => __( 'Rand', 'better-studio' ),
				),
				'show_on'        => array(
					array( 'type=campaign' ),
				),
				//
				"vc_admin_label" => FALSE,
			),
			array(
				"type"           => 'select',
				"name"           => __( 'Order', 'better-studio' ),
				"id"             => 'order',
				"options"        => array(
					'ASC'  => __( 'Ascending', 'better-studio' ),
					'DESC' => __( 'Descending', 'better-studio' ),
				),
				'show_on'        => array(
					array( 'type=campaign' ),
				),
				//
				"vc_admin_label" => FALSE,
			),
			array(
				"type"           => 'select',
				"name"           => __( 'Align', 'better-studio' ),
				"id"             => 'align',
				"options"        => array(
					'left'   => __( 'Left', 'better-studio' ),
					'center' => __( 'Center', 'better-studio' ),
					'right'  => __( 'Right', 'better-studio' ),
				),
				'show_on'        => array(
					array( 'type=campaign' ),
					array( 'type=banner' ),
				),
				//
				"vc_admin_label" => FALSE,
			),
			array(
				"type"           => 'select',
				"name"           => __( 'Show Captions', 'better-studio' ),
				"id"             => 'show-caption',
				"options"        => array(
					1 => __( 'Show caption\'s', 'better-studio' ),
					0 => __( 'Hide caption\'s', 'better-studio' ),
				),
				'show_on'        => array(
					array( 'type=campaign' ),
					array( 'type=banner' ),
				),
				//
				"vc_admin_label" => FALSE,
			),
		);
	}


	/**
	 * Registers configuration of tinyMCE views
	 *
	 * @return array
	 */
	function tinymce_settings() {

		$styles = array(
			array(
				'type' => 'custom',
				'url'  => bf_append_suffix( Better_Ads_Manager::dir_url( 'css/bam' ), '.css' ),
			),
		);

		return array(
			'name'   => __( 'Better Ads', 'better-studio' ),
			'styles' => $styles,
		);
	}


}
