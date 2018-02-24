<?php
/**
 * init.php
 *---------------------------
 * Publisher injection locations functionality
 */


if ( ! function_exists( 'publisher_can_inject' ) ) {
	/**
	 * Determines the injections location is active or not
	 *
	 * @param string $location_id
	 *
	 * @return bool|mixed|null The page ID
	 */
	function publisher_can_inject( $location_id = '' ) {

		if ( ! $location_id ) {
			return FALSE;
		}

		// Cache it
		{
			static $locations = array();

			if ( isset( $locations[ $location_id ] ) ) {
				return $locations[ $location_id ];
			}
		}

		//
		// todo add level and override for injection locations
		//

		//
		// Detect location form admin panel
		//
		{
			$option = publisher_get_option( "injection_$location_id" );
		}

		return $locations[ $location_id ] = $option;
	}
}


// Setup continue reading
add_action( 'template_redirect', 'publisher_init_injection_locations' );


if ( ! function_exists( 'publisher_init_injection_locations' ) ) {
	/**
	 * Adds appropriate hooks for injection locations
	 */
	function publisher_init_injection_locations() {

		if ( publisher_can_inject( 'before_header' ) ) {
			add_action( 'publisher/main-wrap/before', 'publisher_inject_location_before_header' );
		}

		if ( publisher_can_inject( 'after_header' ) ) {
			add_action( 'publisher/content-wrap/before', 'publisher_inject_location_after_header' );
		}

		if ( publisher_can_inject( 'before_footer' ) ) {
			add_action( 'publisher/main-wrap/end', 'publisher_inject_location_before_footer' );
		}

		if ( publisher_can_inject( 'after_footer' ) ) {
			add_action( 'publisher/main-wrap/after', 'publisher_inject_location_after_footer' );
		}
	}
}


if ( ! function_exists( 'publisher_inject_location_before_header' ) ) {
	/**
	 * Before header
	 */
	function publisher_inject_location_before_header() {

		publisher_inject_location( 'before_header' );
	}
}


if ( ! function_exists( 'publisher_inject_location_after_header' ) ) {
	/**
	 * After header
	 */
	function publisher_inject_location_after_header() {

		publisher_inject_location( 'after_header' );
	}
}


if ( ! function_exists( 'publisher_inject_location_before_footer' ) ) {
	/**
	 * Before footer
	 */
	function publisher_inject_location_before_footer() {

		publisher_inject_location( 'before_footer' );
	}
}


if ( ! function_exists( 'publisher_inject_location_after_footer' ) ) {
	/**
	 * After footer
	 */
	function publisher_inject_location_after_footer() {

		publisher_inject_location( 'after_footer' );
	}
}


if ( ! function_exists( 'publisher_inject_location' ) ) {
	/**
	 * Injects a location from location ID
	 *
	 * @param $location_id
	 */
	function publisher_inject_location( $location_id ) {

		$page        = publisher_can_inject( $location_id );
		$content     = FALSE;
		$page_layout = publisher_get_page_layout_file();

		if ( $page && ( $page_object = get_post( $page ) ) ) {
			$content = bf_the_content_by_id( $page, array( 'echo' => FALSE ) );
		}

		//
		// show injection content
		//
		if ( $content ) {
			echo "<div class='bs-injection bs-injection-$location_id bs-injection-$page_layout bs-vc-content'>$content</div>";
		}

	} // publisher_inject_location
}
