<?php

// include it manually earlier to get styles work!
include PUBLISHER_THEME_PATH . 'includes/libs/better-framework/functions/multilingual.php';

// Init style manager
include PUBLISHER_THEME_PATH . 'includes/styles/publisher-theme-style.php';
include PUBLISHER_THEME_PATH . 'includes/styles/publisher-theme-styles-manager.php';

if ( ! function_exists( 'publisher_styles_config' ) ) {
	/**
	 * List of all styles with configuration
	 *
	 * @return array
	 */
	function publisher_styles_config() {

		/*
		 * attrs for styles:
		 * - img
		 * - label
		 * - views
		 * - options
		 * - functions
		 * - css
		 * - js
		 */

		return array(
			'pure-magazine'    => array(
				'img'   => PUBLISHER_THEME_URI . 'includes/demos/pure-magazine/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
				'label' => __( 'Pure Magazine', 'publisher' ),
				'views' => FALSE,
				'info'  => array(
					'cat'  => array(
						__( 'Magazine', 'publisher' ),
					),
					'type' => array(
						__( 'General', 'publisher' ),
					),
				)
			),
			'online-magazine'  => array(
				'img'   => PUBLISHER_THEME_URI . 'includes/demos/online-magazine/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
				'label' => __( 'Online Magazine', 'publisher' ),
				'views' => FALSE,
				'info'  => array(
					'cat'  => array(
						__( 'Magazine', 'publisher' ),
					),
					'type' => array(
						__( 'Technology', 'publisher' ),
					),
				)
			),
			'clean-tech'       => array(
				'img'   => PUBLISHER_THEME_URI . 'includes/demos/clean-tech/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
				'label' => __( 'Clean Tech', 'publisher' ),
				'views' => FALSE,
				'info'  => array(
					'cat'  => array(
						__( 'Magazine', 'publisher' ),
					),
					'type' => array(
						__( 'General', 'publisher' ),
						__( 'Technology', 'publisher' ),
					),
				)
			),
			'the-online-post'  => array(
				'img'   => PUBLISHER_THEME_URI . 'includes/demos/the-online-post/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
				'label' => __( 'The Online Post', 'publisher' ),
				'views' => FALSE,
				'info'  => array(
					'cat'  => array(
						__( 'News', 'publisher' ),
					),
					'type' => array(
						__( 'Newspaper', 'publisher' ),
					),
				)
			),
			'crypto-news'      => array(
				'img'   => PUBLISHER_THEME_URI . 'includes/demos/crypto-news/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
				'label' => __( 'Crypto News', 'publisher' ),
				'views' => FALSE,
				'info'  => array(
					'cat'  => array(
						__( 'Magazine', 'publisher' ),
					),
					'type' => array(
						__( 'Cryptocurrency', 'publisher' ),
					),
				)
			),
			'newswatch'        => array(
				'img'   => PUBLISHER_THEME_URI . 'includes/demos/newswatch/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
				'label' => __( 'Market News', 'publisher' ),
				'views' => FALSE,
				'info'  => array(
					'cat'  => array(
						__( 'News', 'publisher' ),
					),
					'type' => array(
						__( 'Business', 'publisher' ),
					),
				)
			),
			'tech-magazine'    => array(
				'img'   => PUBLISHER_THEME_URI . 'includes/demos/tech-magazine/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
				'label' => __( 'Tech Magazine', 'publisher' ),
				'views' => FALSE,
				'info'  => array(
					'cat'  => array(
						__( 'Magazine', 'publisher' ),
					),
					'type' => array(
						__( 'Technology', 'publisher' ),
					),
				)
			),
			'dark-magazine'    => array(
				'img'   => PUBLISHER_THEME_URI . 'includes/demos/dark-magazine/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
				'label' => __( 'Dark Magazine', 'publisher' ),
				'views' => FALSE,
				'info'  => array(
					'cat'  => array(
						__( 'Magazine', 'publisher' ),
					),
					'type' => array(
						__( 'General', 'publisher' ),
						__( 'Dark', 'publisher' ),
					),
				)
			),
			'top-news'         => array(
				'img'   => PUBLISHER_THEME_URI . 'includes/demos/top-news/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
				'label' => __( 'Top News', 'publisher' ),
				'views' => FALSE,
				'info'  => array(
					'cat'  => array(
						__( 'Magazine', 'publisher' ),
						__( 'News', 'publisher' ),
					),
					'type' => array(
						__( 'General', 'publisher' ),
					),
				)
			),
			'life-mag'         => array(
				'img'   => PUBLISHER_THEME_URI . 'includes/demos/life-mag/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
				'label' => __( 'Life Mag', 'publisher' ),
				'views' => FALSE,
				'info'  => array(
					'cat'  => array(
						__( 'Magazine', 'publisher' ),
					),
					'type' => array(
						__( 'General', 'publisher' ),
					),
				)
			),
			'business-times'   => array(
				'img'   => PUBLISHER_THEME_URI . 'includes/demos/business-times/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
				'label' => __( 'Business Times', 'publisher' ),
				'views' => FALSE,
				'info'  => array(
					'cat'  => array(
						__( 'News', 'publisher' ),
					),
					'type' => array(
						__( 'News', 'publisher' ),
						__( 'Business', 'publisher' ),
					),
				)
			),
			'luxury-magazine'  => array(
				'img'   => PUBLISHER_THEME_URI . 'includes/demos/luxury-magazine/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
				'label' => __( 'Luxury Magazine', 'publisher' ),
				'views' => FALSE,
				'info'  => array(
					'cat'  => array(
						__( 'Magazine', 'publisher' ),
					),
					'type' => array(
						__( 'News', 'publisher' ),
						__( 'Luxury', 'publisher' ),
					),
				)
			),
			'brilliance'       => array(
				'img'   => PUBLISHER_THEME_URI . 'includes/demos/brilliance/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
				'label' => __( 'Brilliance', 'publisher' ),
				'views' => FALSE,
				'info'  => array(
					'cat'  => array(
						__( 'Magazine', 'publisher' ),
					),
					'type' => array(
						__( 'General', 'publisher' ),
					),
				)
			),
			'readmag'          => array(
				'img'   => PUBLISHER_THEME_URI . 'includes/demos/readmag/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
				'label' => __( 'Read Mag', 'publisher' ),
				'views' => FALSE,
				'info'  => array(
					'cat'  => array(
						__( 'Magazine', 'publisher' ),
					),
					'type' => array(
						__( 'General', 'publisher' ),
					),
				)
			),
			'celebrity-news'   => array(
				'img'   => PUBLISHER_THEME_URI . 'includes/demos/celebrity-news/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
				'label' => __( 'Celebrity News', 'publisher' ),
				'views' => FALSE,
				'info'  => array(
					'cat'  => array(
						__( 'Magazine', 'publisher' ),
					),
					'type' => array(
						__( 'Fashion', 'publisher' ),
					),
				)
			),
			'gamers'           => array(
				'img'   => PUBLISHER_THEME_URI . 'includes/demos/gamers/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
				'label' => __( 'Gamers', 'publisher' ),
				'views' => FALSE,
				'info'  => array(
					'cat'  => array(
						__( 'Magazine', 'publisher' ),
					),
					'type' => array(
						__( 'Gaming', 'publisher' ),
					),
				)
			),
			'newspaper'        => array(
				'img'   => PUBLISHER_THEME_URI . 'includes/demos/newspaper/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
				'label' => __( 'Newspaper', 'publisher' ),
				'views' => FALSE,
				'info'  => array(
					'cat'  => array(
						__( 'News', 'publisher' ),
					),
					'type' => array(
						__( 'Newspaper', 'publisher' ),
					),
				)
			),
			'travel-guides'    => array(
				'img'   => PUBLISHER_THEME_URI . 'includes/demos/travel-guides/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
				'label' => __( 'Travel Guides', 'publisher' ),
				'views' => FALSE,
				'info'  => array(
					'cat'  => array(
						__( 'Magazine', 'publisher' ),
					),
					'type' => array(
						__( 'Travel', 'publisher' ),
					),
				)
			),
			'designer-blog'    => array(
				'img'   => PUBLISHER_THEME_URI . 'includes/demos/designer-blog/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
				'label' => __( 'Designer blog', 'publisher' ),
				'views' => FALSE,
				'info'  => array(
					'cat'  => array(
						__( 'Blog', 'publisher' ),
					),
					'type' => array(
						__( 'Lifestyle', 'publisher' ),
					),
				)
			),
			'better-mag'       => array(
				'img'   => PUBLISHER_THEME_URI . 'includes/demos/better-mag/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
				'label' => __( 'Better Mag', 'publisher' ),
				'views' => FALSE,
				'info'  => array(
					'cat'  => array(
						__( 'Magazine', 'publisher' ),
					),
					'type' => array(
						__( 'General', 'publisher' ),
					),
				)
			),
			'adventure-blog'   => array(
				'img'   => PUBLISHER_THEME_URI . 'includes/demos/adventure-blog/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
				'label' => __( 'Adventure Blog', 'publisher' ),
				'views' => FALSE,
				'info'  => array(
					'cat'  => array(
						__( 'Blog', 'publisher' ),
					),
					'type' => array(
						__( 'Travel', 'publisher' ),
					),
				)
			),
			'the-prime'        => array(
				'img'   => PUBLISHER_THEME_URI . 'includes/demos/the-prime/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
				'label' => __( 'The Prime', 'publisher' ),
				'views' => FALSE,
				'info'  => array(
					'cat'  => array(
						__( 'Magazine', 'publisher' ),
						__( 'Blog', 'publisher' ),
					),
					'type' => array(
						__( 'Fashion', 'publisher' ),
					),
				)
			),
			'wonderful'        => array(
				'img'   => PUBLISHER_THEME_URI . 'includes/demos/wonderful/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
				'label' => __( 'Wonderful', 'publisher' ),
				'views' => FALSE,
				'info'  => array(
					'cat'  => array(
						__( 'Magazine', 'publisher' ),
						__( 'Blog', 'publisher' ),
					),
					'type' => array(
						__( 'General', 'publisher' ),
					),
				)
			),
			'travel-blog'      => array(
				'img'   => PUBLISHER_THEME_URI . 'includes/demos/travel-blog/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
				'label' => __( 'Travel Blog', 'publisher' ),
				'views' => FALSE,
				'info'  => array(
					'cat'  => array(
						__( 'Blog', 'publisher' ),
					),
					'type' => array(
						__( 'Travel', 'publisher' ),
					),
				)
			),
			'classic-magazine' => array(
				'img'   => PUBLISHER_THEME_URI . 'includes/demos/classic-magazine/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
				'label' => __( 'Classic Magazine', 'publisher' ),
				'views' => FALSE,
				'info'  => array(
					'cat'  => array(
						__( 'Magazine', 'publisher' ),
					),
					'type' => array(
						__( 'General', 'publisher' ),
					),
				)
			),
			'clean-blog'       => array(
				'img'   => PUBLISHER_THEME_URI . 'includes/demos/clean-blog/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
				'label' => __( 'Clean Blog', 'publisher' ),
				'views' => FALSE,
				'info'  => array(
					'cat'  => array(
						__( 'Blog', 'publisher' ),
					),
					'type' => array(
						__( 'General', 'publisher' ),
					),
				)
			),
			'clean-fashion'    => array(
				'img'   => PUBLISHER_THEME_URI . 'includes/demos/clean-fashion/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
				'label' => __( 'Clean Fashion', 'publisher' ),
				'views' => FALSE,
				'info'  => array(
					'cat'  => array(
						__( 'Magazine', 'publisher' ),
					),
					'type' => array(
						__( 'Fashion', 'publisher' ),
					),
				)
			),
			'clean-magazine'   => array(
				'img'   => PUBLISHER_THEME_URI . 'includes/demos/clean-magazine/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
				'label' => __( 'Clean Magazine', 'publisher' ),
				'views' => FALSE,
				'info'  => array(
					'cat'  => array(
						__( 'Magazine', 'publisher' ),
					),
					'type' => array(
						__( 'General', 'publisher' ),
					),
				)
			),
			'clean-design'     => array(
				'img'   => PUBLISHER_THEME_URI . 'includes/demos/clean-design/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
				'label' => __( 'Clean Design', 'publisher' ),
				'views' => FALSE,
				'info'  => array(
					'cat'  => array(
						__( 'Magazine', 'publisher' ),
					),
					'type' => array(
						__( 'General', 'publisher' ),
						__( 'Interior', 'publisher' ),
					),
				)
			),
			'clean-sport'      => array(
				'img'   => PUBLISHER_THEME_URI . 'includes/demos/clean-sport/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
				'label' => __( 'Clean Sport', 'publisher' ),
				'views' => FALSE,
				'info'  => array(
					'cat'  => array(
						__( 'Magazine', 'publisher' ),
					),
					'type' => array(
						__( 'Sport', 'publisher' ),
					),
				)
			),
			'classic-blog'     => array(
				'img'   => PUBLISHER_THEME_URI . 'includes/demos/classic-blog/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
				'label' => __( 'Classic Blog', 'publisher' ),
				'views' => FALSE,
				'info'  => array(
					'cat'  => array(
						__( 'Blog', 'publisher' ),
					),
					'type' => array(
						__( 'General', 'publisher' ),
					),
				)
			),
			'clean-video'      => array(
				'img'   => PUBLISHER_THEME_URI . 'includes/demos/clean-video/thumbnail.png?v=' . PUBLISHER_THEME_VERSION,
				'label' => __( 'Clean Video', 'publisher' ),
				'views' => FALSE,
				'info'  => array(
					'cat'  => array(
						__( 'Magazine', 'publisher' ),
					),
					'type' => array(
						__( 'Video', 'publisher' ),
					),
				)
			),
		);
	} // publisher_styles_config
}


if ( ! function_exists( 'bf_get_panel_default_style' ) ) {
	/**
	 * Handy function to get panels default style field id
	 *
	 * @param string $panel_id
	 *
	 * @return string
	 */
	function bf_get_panel_default_style( $panel_id = '' ) {

		if ( $panel_id == publisher_get_theme_panel_id() ) {
			return publisher_get_style() === 'default' ? 'clean-magazine' : publisher_get_style();
		}

		return 'default';
	}
}


if ( ! function_exists( 'publisher_get_style' ) ) {
	/**
	 * Used to get current active style.
	 *
	 * Default style: general
	 *
	 * @return  string
	 */
	function publisher_get_style() {

		static $style;

		if ( $style ) {
			return $style;
		}

		$lang = bf_get_current_language_option_code();

		// current lang style or default none lang
		$style = get_option( publisher_get_theme_panel_id() . $lang . '_current_style' );

		// check
		if ( $style === FALSE && ! empty( $lang ) ) {
			$style = get_option( publisher_get_theme_panel_id() . '_current_style' );
		}

		if ( $style === FALSE || empty( $style ) ) {
			$style = 'clean-magazine';
		}

		return $style;
	}
}

// Init styles
if ( class_exists( 'Publisher_Theme_Styles_Manager' ) ) {
	new Publisher_Theme_Styles_Manager();
}
