<?php
/**
 * Demo Installer Configuration
 */

add_filter( 'better-framework/product-pages/install-demo/config', 'publisher_demos_config' );

if ( ! function_exists( 'publisher_demos_config' ) ) {
	/**
	 * Adds active demos to BS Product Pages with correct config to install
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	function publisher_demos_config( $data = array() ) {

		$data['pure-magazine']   = array(
			'thumbnail'   => PUBLISHER_THEME_URI . 'includes/demos/pure-magazine/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
			'name'        => __( 'Pure Magazine', 'publisher' ),
			'preview_url' => 'http://demo.betterstudio.com/publisher/',
			'options'     => TRUE,
		);
		$data['online-magazine'] = array(
			'thumbnail'   => PUBLISHER_THEME_URI . 'includes/demos/online-magazine/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
			'name'        => __( 'Online Magazine', 'publisher' ),
			'preview_url' => 'http://demo.betterstudio.com/publisher/online-magazine/',
			'options'     => TRUE,
			'badges'      => array(
				'New',
			),
		);
		$data['clean-tech']      = array(
			'thumbnail'   => PUBLISHER_THEME_URI . 'includes/demos/clean-tech/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
			'name'        => __( 'Clean Tech', 'publisher' ),
			'preview_url' => 'http://demo.betterstudio.com/publisher/clean-tech/',
			'options'     => TRUE,
		);
		$data['the-online-post'] = array(
			'thumbnail'   => PUBLISHER_THEME_URI . 'includes/demos/the-online-post/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
			'name'        => __( 'The Online Post', 'publisher' ),
			'preview_url' => 'http://demo.betterstudio.com/publisher/the-online-post/',
			'options'     => TRUE,
			'badges'      => array(
				'New',
			),
		);
		$data['crypto-news']     = array(
			'thumbnail'   => PUBLISHER_THEME_URI . 'includes/demos/crypto-news/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
			'name'        => __( 'Crypto News', 'publisher' ),
			'preview_url' => 'http://demo.betterstudio.com/publisher/crypto-news/',
			'options'     => TRUE,
			'badges'      => array(
				'New',
			),
		);
		$data['newswatch']       = array(
			'thumbnail'   => PUBLISHER_THEME_URI . 'includes/demos/newswatch/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
			'name'        => __( 'Market News', 'publisher' ),
			'preview_url' => 'http://demo.betterstudio.com/publisher/market-news/',
			'options'     => TRUE,
		);
		$data['tech-magazine']   = array(
			'thumbnail'   => PUBLISHER_THEME_URI . 'includes/demos/tech-magazine/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
			'name'        => __( 'Tech Magazine', 'publisher' ),
			'preview_url' => 'http://demo.betterstudio.com/publisher/tech-magazine/',
			'options'     => TRUE,
			'badges'      => array(
				'New',
			),
		);
		$data['dark-magazine']   = array(
			'thumbnail'   => PUBLISHER_THEME_URI . 'includes/demos/dark-magazine/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
			'name'        => __( 'Dark Magazine', 'publisher' ),
			'preview_url' => 'http://demo.betterstudio.com/publisher/dark-mag/',
			'options'     => TRUE,
		);
		$data['top-news']        = array(
			'thumbnail'   => PUBLISHER_THEME_URI . 'includes/demos/top-news/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
			'name'        => __( 'Top News', 'publisher' ),
			'preview_url' => 'http://demo.betterstudio.com/publisher/top-news/',
			'options'     => TRUE,
		);
		$data['business-times']  = array(
			'thumbnail'   => PUBLISHER_THEME_URI . 'includes/demos/business-times/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
			'name'        => __( 'Business Times', 'publisher' ),
			'preview_url' => 'http://demo.betterstudio.com/publisher/business-times/',
			'options'     => TRUE,
			'badges'      => array(
				'New',
			),
		);

		$data['luxury-magazine'] = array(
			'thumbnail'   => PUBLISHER_THEME_URI . 'includes/demos/luxury-magazine/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
			'name'        => __( 'Luxury Magazine', 'publisher' ),
			'preview_url' => 'http://demo.betterstudio.com/publisher/luxury-magazine/',
			'options'     => TRUE,
			'badges'      => array(
				'New',
			),
		);
		$data['brilliance']      = array(
			'thumbnail'   => PUBLISHER_THEME_URI . 'includes/demos/brilliance/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
			'name'        => __( 'Brilliance', 'publisher' ),
			'preview_url' => 'http://demo.betterstudio.com/publisher/brilliance/',
			'options'     => TRUE,
		);
		$data['readmag']         = array(
			'thumbnail'   => PUBLISHER_THEME_URI . 'includes/demos/readmag/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
			'name'        => __( 'Read Mag', 'publisher' ),
			'preview_url' => 'http://demo.betterstudio.com/publisher/read-mag/',
			'options'     => TRUE,
		);
		$data['celebrity-news']  = array(
			'thumbnail'   => PUBLISHER_THEME_URI . 'includes/demos/celebrity-news/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
			'name'        => __( 'Celebrity News', 'publisher' ),
			'preview_url' => 'http://demo.betterstudio.com/publisher/celebrity-news/',
			'options'     => TRUE,
			'badges'      => array(
				'New',
			),
		);
		$data['gamers']          = array(
			'thumbnail'   => PUBLISHER_THEME_URI . 'includes/demos/gamers/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
			'name'        => __( 'Gamers', 'publisher' ),
			'preview_url' => 'http://demo.betterstudio.com/publisher/gamers/',
			'options'     => TRUE,
			'badges'      => array(
				'New',
			),
		);
		$data['newspaper']       = array(
			'thumbnail'   => PUBLISHER_THEME_URI . 'includes/demos/newspaper/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
			'name'        => __( 'Newspaper', 'publisher' ),
			'preview_url' => 'http://demo.betterstudio.com/publisher/newspaper/',
			'options'     => TRUE,
			'badges'      => array(
				'New',
			),
		);
		$data['travel-guides']   = array(
			'thumbnail'   => PUBLISHER_THEME_URI . 'includes/demos/travel-guides/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
			'name'        => __( 'Travel Guides', 'publisher' ),
			'preview_url' => 'http://demo.betterstudio.com/publisher/travel-guides/',
			'options'     => TRUE,
		);

		$data['adventure-blog'] = array(
			'thumbnail'   => PUBLISHER_THEME_URI . 'includes/demos/adventure-blog/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
			'name'        => __( 'Adventure Blog', 'publisher' ),
			'preview_url' => 'http://demo.betterstudio.com/publisher/adventure-blog/',
			'options'     => TRUE,
		);

		$data['the-prime']     = array(
			'thumbnail'   => PUBLISHER_THEME_URI . 'includes/demos/the-prime/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
			'name'        => __( 'The Prime', 'publisher' ),
			'preview_url' => 'http://demo.betterstudio.com/publisher/the-prime/',
			'options'     => TRUE,
		);
		$data['wonderful']     = array(
			'thumbnail'   => PUBLISHER_THEME_URI . 'includes/demos/wonderful/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
			'name'        => __( 'Wonderful', 'publisher' ),
			'preview_url' => 'http://demo.betterstudio.com/publisher/wonderful/',
			'options'     => TRUE,
		);
		$data['designer-blog'] = array(
			'thumbnail'   => PUBLISHER_THEME_URI . 'includes/demos/designer-blog/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
			'name'        => __( 'Designer Blog', 'publisher' ),
			'preview_url' => 'http://demo.betterstudio.com/publisher/designer-blog/',
			'options'     => TRUE,
			'badges'      => array(
				'New',
			),
		);
		$data['better-mag']    = array(
			'thumbnail'   => PUBLISHER_THEME_URI . 'includes/demos/better-mag/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
			'name'        => __( 'Better Mag', 'publisher' ),
			'preview_url' => 'http://demo.betterstudio.com/publisher/better-mag/',
			'options'     => TRUE,
		);

		$data['classic-magazine']         = array(
			'thumbnail'   => PUBLISHER_THEME_URI . 'includes/demos/classic-magazine/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
			'name'        => __( 'Classic Magazine', 'publisher' ),
			'preview_url' => 'http://demo.betterstudio.com/publisher/classic-mag/',
			'options'     => TRUE,
		);
		$data['clean-blog']               = array(
			'thumbnail'   => PUBLISHER_THEME_URI . 'includes/demos/clean-blog/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
			'name'        => __( 'Clean Blog', 'publisher' ),
			'preview_url' => 'http://demo.betterstudio.com/publisher/clean-blog/',
			'options'     => TRUE,
		);
		$data['clean-fashion']            = array(
			'thumbnail'   => PUBLISHER_THEME_URI . 'includes/demos/clean-fashion/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
			'name'        => __( 'Clean Fashion', 'publisher' ),
			'preview_url' => 'http://demo.betterstudio.com/publisher/clean-fashion/',
			'options'     => TRUE,
		);
		$data['travel-blog']              = array(
			'thumbnail'   => PUBLISHER_THEME_URI . 'includes/demos/travel-blog/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
			'name'        => __( 'Traveler Blog', 'publisher' ),
			'preview_url' => 'http://demo.betterstudio.com/publisher/traveler-blog/',
			'options'     => TRUE,
		);
		$data['clean-magazine']           = array(
			'thumbnail'   => PUBLISHER_THEME_URI . 'includes/demos/clean-magazine/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
			'name'        => __( 'Clean Magazine', 'publisher' ),
			'preview_url' => 'http://demo.betterstudio.com/publisher/clean-mag/',
			'options'     => TRUE,
		);
		$data['clean-design']             = array(
			'thumbnail'   => PUBLISHER_THEME_URI . 'includes/demos/clean-design/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
			'name'        => __( 'Clean Design', 'publisher' ),
			'preview_url' => 'http://demo.betterstudio.com/publisher/clean-design/',
			'options'     => TRUE,
		);
		$data['clean-sport']              = array(
			'thumbnail'   => PUBLISHER_THEME_URI . 'includes/demos/clean-sport/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
			'name'        => __( 'Clean Sport', 'publisher' ),
			'preview_url' => 'http://demo.betterstudio.com/publisher/clean-sport/',
			'options'     => TRUE,
		);
		$data['classic-blog']             = array(
			'thumbnail'   => PUBLISHER_THEME_URI . 'includes/demos/classic-blog/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
			'name'        => __( 'Classic Blog', 'publisher' ),
			'preview_url' => 'http://demo.betterstudio.com/publisher/classic-blog/',
			'options'     => TRUE,
		);
		$data['clean-video']              = array(
			'thumbnail'   => PUBLISHER_THEME_URI . 'includes/demos/clean-video/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
			'name'        => __( 'Clean Video', 'publisher' ),
			'preview_url' => 'http://demo.betterstudio.com/publisher/clean-video/',
			'options'     => TRUE,
		);
		$data['life-mag']                 = array(
			'thumbnail'   => PUBLISHER_THEME_URI . 'includes/demos/life-mag/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
			'name'        => __( 'Life Magazine', 'publisher' ),
			'preview_url' => 'http://demo.betterstudio.com/publisher/life-mag/',
			'options'     => TRUE,
			'badges'      => array(
				'New',
			),
		);
		$data['clean-tech-rtl-arabic']    = array(
			'thumbnail'   => PUBLISHER_THEME_URI . 'includes/demos/clean-tech-rtl-arabic/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
			'name'        => __( 'RTL - Clean Tech', 'publisher' ),
			'preview_url' => 'http://demo.betterstudio.com/publisher/rtl/',
			'badges'      => array(
				'RTL',
			),
			'options'     => TRUE,
		);
		$data['pure-magazine-rtl-arabic'] = array(
			'thumbnail'   => PUBLISHER_THEME_URI . 'includes/demos/pure-magazine-rtl-arabic/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
			'name'        => __( 'RTL - Pure Mag', 'publisher' ),
			'preview_url' => 'http://demo.betterstudio.com/publisher/rtl-clean-mag/',
			'badges'      => array(
				'RTL',
			),
			'options'     => TRUE,
		);

		return $data;
	} // publisher_demos_config
}


if ( ! function_exists( 'publisher_get_demo_id' ) ) {
	/**
	 *
	 * @return mixed
	 */
	function publisher_get_demo_id() {

		global $publisher_theme_core_globals_cache;

		// return from cache
		if ( isset( $publisher_theme_core_globals_cache['theme-demo'] ) ) {
			return $publisher_theme_core_globals_cache['theme-demo'];
		}

		$demo = get_option( publisher_get_theme_panel_id() . '_current_demo' );

		// cache it
		$publisher_theme_core_globals_cache['theme-demo'] = $demo;

		return $demo;

	} // publisher_get_demo_id
}


// Adds filter for all demos content
foreach ( publisher_demos_config() as $demo_id => $demo_config ) {
	add_filter( 'better-framework/product-pages/install-demo/' . $demo_id . '/content', 'publisher_init_demo_content', 10, 2 );
	add_filter( 'better-framework/product-pages/install-demo/' . $demo_id . '/setting', 'publisher_init_demo_setting', 10, 2 );
}

if ( ! function_exists( 'publisher_init_demo_content' ) ) {
	/**
	 * Pulls selected demo data from its directory and send it to BS Product Pages demo installer
	 *
	 * @param array  $content
	 * @param string $demo_id
	 *
	 * @return array
	 */
	function publisher_init_demo_content( $content = array(), $demo_id = '' ) {

		$demos_list = publisher_demos_config();

		$theme_dir = get_template_directory() . '/';

		// check if its valid, get value from its directory
		if ( ! empty( $demos_list[ $demo_id ] ) ) {

			include $theme_dir . 'includes/demos/' . $demo_id . '/content.php';

			$content = call_user_func( 'publisher_demo_raw_content' );

		}

		return $content;
	} // publisher_init_demo_content
}

if ( ! function_exists( 'publisher_init_demo_setting' ) ) {
	/**
	 * Pulls selected demo data from its directory and send it to BS Product Pages demo installer
	 *
	 * @param array  $content
	 * @param string $demo_id
	 *
	 * @return array
	 */
	function publisher_init_demo_setting( $content = array(), $demo_id = '' ) {

		$demos_list = publisher_demos_config();

		$theme_dir = get_template_directory() . '/';

		// check if its valid, get value from its directory
		if ( ! empty( $demos_list[ $demo_id ] ) ) {

			include $theme_dir . 'includes/demos/' . $demo_id . '/options.php';

			$content = call_user_func( 'publisher_demo_raw_options' );

		}

		return $content;
	} // publisher_init_demo_setting
}


if ( ! function_exists( 'publisher_get_demo_images_url' ) ) {
	/**
	 * Used to get demo images url
	 *
	 * @param string $style_id
	 * @param string $demo_id
	 *
	 * @return array
	 */
	function publisher_get_demo_images_url( $style_id = '', $demo_id = '' ) {

		$demo_image_url = 'http://demo-contents.betterstudio.com/themes/publisher/v1/' . $style_id . '/';

		if ( bf_is( 'demo-dev' ) ) {
			$demo_image_url = home_url( 'demo-images/v1/' . $style_id . '/' );
		}

		return $demo_image_url;
	} // publisher_get_demo_images_url
}
