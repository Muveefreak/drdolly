<?php
/*
Plugin Name: Better Google Custom Search
Plugin URI: http://betterstudio.com
Description: Replace the default WordPress search engine with search powered by Google.
Version: 1.2.0
Author: BetterStudio
Author URI: http://betterstudio.com
License: GPL2
*/


/**
 * Better_GCS class wrapper for make changes safe in future
 *
 * @return Better_GCS
 */
function Better_GCS() {

	return Better_GCS::self();
}


// Initialize Better Google Custom Search
Better_GCS();


/**
 * Handy function used for generating search box to page
 *
 * this make usability easy and safe for feature changes
 */
function Better_GCS_Search_Box() {

	Better_GCS()->generate_search_box();
}


/**
 * Class Better_GCS
 */
class Better_GCS {


	/**
	 * Contains Better_GCS version number that used for assets for preventing cache mechanism
	 *
	 * @var string
	 */
	private static $version = '1.2.0';


	/**
	 * Contains Better_GCS option panel id
	 *
	 * @var string
	 */
	public static $panel_id = 'better_google_custom_search';


	/**
	 * Inner array of instances
	 *
	 * @var array
	 */
	protected static $instances = array();


	function __construct() {

		// make sure following code only one time run
		static $initialized;
		if ( $initialized ) {
			return;
		} else {
			$initialized = TRUE;
		}

		// Register included BF to loader
		add_filter( 'better-framework/loader', array( $this, 'better_framework_loader' ) );

		include self::dir_path( 'includes/options/panel.php' );

		load_plugin_textdomain( 'better-studio', FALSE, 'better-google-custom-search/languages' );

		// Enqueue assets
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );

		// Callback for changing search page template
		add_action( 'template_redirect', array( $this, 'show_search_box' ) );

		// Includes BF loader if not included before
		include self::dir_path( 'includes/libs/better-framework/init.php' );
	}


	/**
	 * Adds included BetterFramework to loader
	 *
	 * @param $frameworks
	 *
	 * @return array
	 */
	function better_framework_loader( $frameworks ) {

		$frameworks[] = array(
			'version' => '3.5.1',
			'path'    => self::dir_path( 'includes/libs/better-framework/' ),
			'uri'     => self::dir_url( 'includes/libs/better-framework/' ),
		);

		return $frameworks;
	}


	/**
	 * Used for accessing plugin directory URL
	 *
	 * @param string $address
	 *
	 * @return string
	 */
	public static function dir_url( $address = '' ) {

		static $url;

		if ( is_null( $url ) ) {
			$url = plugin_dir_url( __FILE__ );
		}

		return $url . $address;
	}


	/**
	 * Used for accessing plugin directory path
	 *
	 * @param string $address
	 *
	 * @return string
	 */
	public static function dir_path( $address = '' ) {

		static $path;

		if ( is_null( $path ) ) {
			$path = plugin_dir_path( __FILE__ );
		}

		return $path . $address;
	}


	/**
	 * Returns BSC current Version
	 *
	 * @return string
	 */
	public static function get_version() {

		return self::$version;
	}


	/**
	 * Build the required object instance
	 *
	 * @param string $object
	 * @param bool   $fresh
	 * @param bool   $just_include
	 *
	 * @return null
	 */
	public static function factory( $object = 'self', $fresh = FALSE, $just_include = FALSE ) {

		if ( isset( self::$instances[ $object ] ) && ! $fresh ) {
			return self::$instances[ $object ];
		}

		switch ( $object ) {

			/**
			 * Main Better_GCS Class
			 */
			case 'self':
				$class = 'Better_GCS';
				break;

			default:
				return NULL;
		}


		// Just prepare/includes files
		if ( $just_include ) {
			return;
		}

		// don't cache fresh objects
		if ( $fresh ) {
			return new $class;
		}

		self::$instances[ $object ] = new $class;

		return self::$instances[ $object ];
	}


	/**
	 * Used for accessing alive instance of Better_GCS
	 *
	 * static
	 *
	 * @since 1.0
	 * @return Better_GCS
	 */
	public static function self() {

		return self::factory();
	}


	/**
	 * Used for retrieving options simply and safely for next versions
	 *
	 * @param $option_key
	 *
	 * @return mixed|null
	 */
	public static function get_option( $option_key ) {

		return bf_get_option( $option_key, self::$panel_id );
	}


	/**
	 * Enqueue css and js files
	 */
	function enqueue_assets() {

		if ( ! $this->get_engine_id() ) {
			return;
		}

		// Enqueue scripts only if this is search page and engine id is valid
		if ( bf_is_search_page() ) {
			wp_enqueue_script( 'google-json-api', '//www.google.com/jsapi', array(), self::$version, TRUE );
		}

		bf_enqueue_style(
			'better-google-custom-search',
			bf_append_suffix( self::dir_url( 'css/better-google-custom-search' ), '.css' ),
			array(),
			bf_append_suffix( self::dir_path( 'css/better-google-custom-search' ), '.css' ),
			self::$version
		);

	}


	/**
	 * Callback: Used to change search page with Google Custom Search
	 *
	 * Action: template_redirect
	 */
	function show_search_box() {

		// don't do anything when it's not search page
		if ( ! bf_is_search_page() ) {
			return;
		}

		// If engine id isn't defined
		if ( ! $this->get_engine_id() ) {
			return;
		}

		include $this->get_template();

		exit;
	}


	/**
	 * Finds appropriate template file and return path
	 * This make option to change template in themes
	 *
	 * @param string $template
	 *
	 * @return string
	 */
	function get_template( $template = 'better-gcs.php' ) {

		// Use theme specified template for search page
		if ( file_exists( get_template_directory() . "/$template" ) ) {
			return get_template_directory() . "/$template";
		}

		return $this->dir_path( "templates/$template" );
	}


	/**
	 * Generate search result box
	 */
	function generate_search_box() {

		// Run google search by query
		$search_query = isset( $_REQUEST['s'] ) && '' != $_REQUEST['s'] ? $_REQUEST['s'] : '';


		//
		// Search script and template
		//
		{
			ob_start();
			include $this->get_template( 'better-gcs-result.php' );
			$code = ob_get_clean();
		}


		//
		// Prepare search template
		//
		{
			$clases = array();

			// Show search button
			if ( $this->get_option( 'show_ads' ) ) {
				$clases[] = 'show-adsin';
			} else {
				$clases[] = 'hide-adsin';
			}

			$code = str_replace(
				array(
					'{{SEARCH-ENGINE-ID}}',
					'{{SEARCH-QUERY}}',
					'{{SEARCH-URL}}',
					'{{SEARCH-LOADING}}',
					'{{SEARCH-CLASS}}',
				),
				array(
					$this->get_engine_id(),
					$search_query,
					home_url( '/' ),
					$this->get_option( 'loading_text' ),
					implode( ' ', $clases )
				),
				$code
			);
		}

		echo $code;
	}


	/**
	 * Return google search engine id
	 *
	 * @return mixed|string
	 */
	function get_engine_id() {

		static $engine_id;

		if ( ! is_null( $engine_id ) ) {
			return $engine_id;
		}

		// Remove extra characters
		$embed_code = preg_replace( "/[ \n\r\t\v\'\"]/m", '', stripslashes( $this->get_option( 'engine_id' ) ) );

		// Start position of engine ID
		$start = strpos( $embed_code, 'varcx=' );

		// End position of engine ID
		$end = strpos( $embed_code, ';', $start );

		// It's a valid Code
		if ( $start && $end ) {

			// cut code and get id from that
			$engine_id = substr( $embed_code, $start + 6, $end - ( $start + 6 ) );

		} // It's a ID
		else {
			$engine_id = $embed_code;
		}

		return $engine_id;
	}
}
