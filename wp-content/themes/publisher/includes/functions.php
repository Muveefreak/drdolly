<?php

$template_directory = trailingslashit( get_template_directory() );
$template_uri       = trailingslashit( get_template_directory_uri() );


// Initialize White Label Feature
include $template_directory . 'includes/white-label/init.php';


if ( ! defined( 'PUBLISHER_THEME_ADMIN_ASSETS_URI' ) ) {
	define( 'PUBLISHER_THEME_ADMIN_ASSETS_URI', $template_uri . 'includes/admin-assets/' );
}

if ( ! defined( 'PUBLISHER_THEME_PATH' ) ) {
	define( 'PUBLISHER_THEME_PATH', $template_directory );
}

if ( ! defined( 'PUBLISHER_THEME_URI' ) ) {
	define( 'PUBLISHER_THEME_URI', $template_uri );
}

if ( ! defined( 'PUBLISHER_THEME_VERSION' ) ) {
	define( 'PUBLISHER_THEME_VERSION', '4.0.0' );
}

add_filter( 'publisher-theme-core/config', 'publisher_config_theme_core', 22 );

if ( ! function_exists( 'publisher_config_theme_core' ) ) {
	/**
	 * Callback: Config "Publisher Theme Core" library needle sections.
	 * Filter: publisher-theme-core/config
	 *
	 * @param array $config
	 *
	 * @return array
	 */
	function publisher_config_theme_core( $config = array() ) {

		$config['dir-path']   = PUBLISHER_THEME_PATH . 'includes/libs/bs-theme-core/';
		$config['dir-url']    = PUBLISHER_THEME_URI . 'includes/libs/bs-theme-core/';
		$config['theme-slug'] = 'publisher';
		$config['theme-name'] = publisher_white_label_get_option( 'publisher' );

		$config['sections']['attr']                   = TRUE;
		$config['sections']['meta-tags']              = TRUE;
		$config['sections']['listing-pagin']          = TRUE;
		$config['sections']['translation']            = TRUE;
		$config['sections']['social-meta-tags']       = TRUE;
		$config['sections']['chat-format']            = TRUE;
		$config['sections']['duplicate-posts']        = TRUE;
		$config['sections']['gallery-slider']         = TRUE;
		$config['sections']['shortcodes-placeholder'] = is_user_logged_in();
		$config['sections']['theme-helpers']          = TRUE;
		$config['sections']['vc-helpers']             = TRUE;
		$config['sections']['rebuild-thumbnails']     = TRUE;
		$config['sections']['page-templates']         = TRUE;
		$config['sections']['post-fields']            = TRUE;
		$config['sections']['lazy-load']              = TRUE;

		$config['vc-widgets-atts'] = array(
			'before_title'  => '<h5 class="section-heading"><span class="h-text">',
			'after_title'   => '</span></h5>',
			'before_widget' => '<div id="%1$s" class="widget vc-widget %2$s">',
			'after_widget'  => '</div>',
		);

		return $config;
	}
}


// Init BetterTranslation for theme
add_filter( 'publisher-theme-core/translation/config', 'publisher_translations_config' );

if ( ! function_exists( 'publisher_translations_config' ) ) {
	/**
	 * Callback: Publisher Translation configurations
	 *
	 * Filter: better-translation/config
	 *
	 * @param $config
	 *
	 * @return mixed
	 */
	function publisher_translations_config( $config ) {

		$config['theme-id']      = 'publisher';
		$config['theme-name']    = publisher_white_label_get_option( 'publisher' );
		$config['notice-icon']   = PUBLISHER_THEME_URI . 'images/admin/notice-logo.png';
		$config['menu-parent']   = 'bs-product-pages-welcome';
		$config['menu-position'] = 55;

		return $config;
	} // publisher_translations_config
}


/**
 * functions.php
 *---------------------------
 * This file contains general functions that used inside theme to
 * do important sections.
 *
 * We create them in a way that you can override them in child them simply!
 * Simply copy the function into child theme and remove the "if( ! function_exists( '*****' ) ){".
 */

/**
 * Callback: Enable oculus error logging system for theme
 * Filter  : better-framework/oculus/logger/filter
 *
 * @access private
 *
 * @param boolean $bool previous value
 * @param string  $product_dir
 * @param string  $type_dir
 *
 * @return bool true if error belongs to theme, previous value otherwise.
 */
function publisher_enable_error_collector( $bool, $product_dir, $type_dir ) {

	if ( $type_dir === 'themes' ) {
		return $product_dir !== get_template();
	}

	return $bool;
}


add_filter( 'better-framework/oculus/logger/turn-off', 'publisher_enable_error_collector', 22, 3 );

if ( ! function_exists( 'publisher_get_theme_panel_id' ) ) {
	/**
	 * Used to get theme panel id
	 *
	 * @return string
	 */
	function publisher_get_theme_panel_id() {

		return 'bs_' . 'publisher_theme_options';
	}
}

// Config demos
include $template_directory . 'includes/demos/init.php';


// Initialize styles
include $template_directory . 'includes/styles/init.php';


if ( ! function_exists( 'publisher_cat_main_slider_config' ) ) {
	/**
	 * Deprecated function.
	 * Use publisher_main_slider_config
	 *
	 * @param null $term_id
	 *
	 * @return array|mixed
	 */
	function publisher_cat_main_slider_config( $term_id = NULL ) {

		return publisher_main_slider_config( array(
			'type'    => 'term',
			'term_id' => is_null( $term_id ) ? '' : $term_id,
		) );
	}
}


if ( ! function_exists( 'publisher_main_slider_config' ) ) {
	/**
	 * Prepare main slider config
	 *
	 * @param array $args
	 *
	 * @return array|mixed
	 */
	function publisher_main_slider_config( $args = array() ) {

		$args = bf_merge_args( $args, array(
			'type'    => 'term',
			'term_id' => '',
		) );

		// return from cache
		if ( publisher_get_global( $args['type'] . '-slider-config' ) != NULL ) {
			return publisher_get_global( $args['type'] . '-slider-config' );
		}

		//
		// Base config
		//
		$config = array(
			'type'      => 'default',
			'style'     => 'default',
			'overlay'   => 'default',
			'show'      => FALSE,
			'in-column' => FALSE,
			'bg_color'  => '',
		);

		//
		// Term Type
		//
		if ( $args['type'] === 'term' ) {

			if ( empty( $args['term_id'] ) ) {
				$queried_object = get_queried_object();

				if ( isset( $queried_object->term_id ) ) {
					$args['term_id'] = $queried_object->term_id;
				}
			}

			// get from current term
			if ( publisher_is_valid_tax() ) {
				$config['type'] = bf_get_term_meta( 'slider_type', $args['term_id'] );
			}

			// slider Type
			if ( $config['type'] === 'default' ) {
				$config['type'] = publisher_get_option( 'cat_slider' );
			}
		} elseif ( $args['type'] === 'home' ) {

			// slider Type
			$config['type'] = publisher_get_option( 'home_slider' );
		}

		if ( ! publisher_is_valid_slider_type( $config['type'] ) ) {
			$config['type'] = 'disable';
		}

		switch ( $config['type'] ) {

			case 'disable':
				$config['style']     = 'disable';
				$config['directory'] = '';
				$config['file']      = '';
				$config['show']      = FALSE;
				$config['posts']     = 0;
				break;

			case 'custom-blocks':

				//
				// Term type
				//
				if ( $args['type'] === 'term' ) {

					// get from current term
					if ( publisher_is_valid_tax() ) {
						$config['style']   = bf_get_term_meta( 'better_slider_style', $args['term_id'] );
						$config['overlay'] = bf_get_term_meta( 'better_slider_gradient', $args['term_id'] );
					}

					// Slider style
					if ( $config['style'] === 'default' ) {
						$config['style'] = publisher_get_option( 'cat_top_posts' );
					}

					// overlay
					if ( $config['overlay'] === 'default' ) {
						$config['overlay'] = publisher_get_option( 'cat_top_posts_gradient' );
					}
				}
				//
				// Home type
				//
				elseif ( $args['type'] === 'home' ) {

					// Slider style
					$config['style'] = publisher_get_option( 'home_top_posts' );

					// overlay
					$config['overlay'] = publisher_get_option( 'home_top_posts_gradient' );
				}

				// Validate it
				if ( ! publisher_is_valid_topposts_style( $config['style'] ) ) {
					$config['style'] = 'disable';
				}

				// Posts config
				switch ( $config['style'] ) {

					case 'style-1':
						$config['directory'] = 'loop';
						$config['file']      = 'listing-modern-grid-1';
						$config['show']      = TRUE;
						$config['posts']     = 4;
						$config['in-column'] = FALSE;
						$config['class']     = '';
						break;

					case 'style-2':
						$config['directory'] = 'loop';
						$config['file']      = 'listing-modern-grid-1';
						$config['show']      = TRUE;
						$config['posts']     = 4;
						$config['in-column'] = TRUE;
						$atts                = publisher_improve_block_atts_for_size( array(), 'mg-1' );

						if ( isset( $atts['mg-layout'] ) ) {
							$config['class'] = $atts['mg-layout'];
						}
						break;

					case 'style-3':
						$config['directory'] = 'loop';
						$config['file']      = 'listing-modern-grid-2';
						$config['show']      = TRUE;
						$config['posts']     = 5;
						$config['in-column'] = FALSE;
						break;

					case 'style-4':
						$config['directory'] = 'loop';
						$config['file']      = 'listing-modern-grid-2';
						$config['show']      = TRUE;
						$config['posts']     = 5;
						$config['in-column'] = TRUE;
						$atts                = publisher_improve_block_atts_for_size( array(), 'mg-2' );

						if ( isset( $atts['mg-layout'] ) ) {
							$config['class'] = $atts['mg-layout'];
						}

						break;

					case 'style-5':
						$config['directory'] = 'loop';
						$config['file']      = 'listing-modern-grid-3';
						$config['show']      = TRUE;
						$config['posts']     = 3;
						$config['columns']   = 3;
						$config['in-column'] = FALSE;
						break;

					case 'style-6':
						$config['directory'] = 'loop';
						$config['file']      = 'listing-modern-grid-3';
						$config['show']      = TRUE;
						$config['posts']     = 2;
						$config['columns']   = 2;
						$config['in-column'] = TRUE;

						// Set columns
						if ( isset( $atts['columns'] ) ) {
							$atts = publisher_improve_block_atts_for_size( array( 'columns' => $config['columns'] ), 'mg-3' );

							if ( isset( $atts['mg-layout'] ) ) {
								$config['class'] = $atts['mg-layout'];
							}
						}

						break;

					case 'style-7':
						$config['directory'] = 'loop';
						$config['file']      = 'listing-modern-grid-4';
						$config['show']      = TRUE;
						$config['posts']     = 4;
						$config['columns']   = 4;
						$config['in-column'] = FALSE;
						break;

					case 'style-8':
						$config['directory'] = 'loop';
						$config['file']      = 'listing-modern-grid-4';
						$config['show']      = TRUE;
						$config['posts']     = 3;
						$config['columns']   = 3;
						$config['in-column'] = TRUE;

						// set smart responsive layout
						if ( isset( $atts['columns'] ) ) {
							$atts = publisher_improve_block_atts_for_size( array( 'columns' => $config['columns'] ), 'mg-4' );

							if ( isset( $atts['mg-layout'] ) ) {
								$config['class'] = $atts['mg-layout'];
							}
						}

						break;

					case 'style-9':
						$config['directory'] = 'shortcodes';
						$config['file']      = 'bs-slider-1';
						$config['show']      = TRUE;
						$config['posts']     = 3;
						$config['columns']   = '';
						$config['in-column'] = FALSE;
						break;

					case 'style-10':
						$config['directory'] = 'shortcodes';
						$config['file']      = 'bs-slider-1';
						$config['show']      = TRUE;
						$config['posts']     = 3;
						$config['columns']   = '';
						$config['in-column'] = TRUE;

						$atts = publisher_improve_block_atts_for_size( array(), 'slider-1' );

						if ( isset( $atts['mg-layout'] ) ) {
							$config['class'] = $atts['mg-layout'];
						}

						break;

					case 'style-11':
						$config['directory'] = 'shortcodes';
						$config['file']      = 'bs-slider-2';
						$config['show']      = TRUE;
						$config['posts']     = 3;
						$config['columns']   = '';
						$config['in-column'] = FALSE;
						break;

					case 'style-12':
						$config['directory'] = 'shortcodes';
						$config['file']      = 'bs-slider-2';
						$config['show']      = TRUE;
						$config['posts']     = 3;
						$config['columns']   = '';
						$config['in-column'] = TRUE;

						$atts = publisher_improve_block_atts_for_size( array(), 'slider-2' );

						if ( isset( $atts['mg-layout'] ) ) {
							$config['class'] = $atts['mg-layout'];
						}

						break;

					case 'style-13':
						$config['directory'] = 'shortcodes';
						$config['file']      = 'bs-slider-3';
						$config['show']      = TRUE;
						$config['posts']     = 3;
						$config['columns']   = '';
						$config['in-column'] = FALSE;
						break;

					case 'style-14':
						$config['directory'] = 'shortcodes';
						$config['file']      = 'bs-slider-3';
						$config['show']      = TRUE;
						$config['posts']     = 3;
						$config['columns']   = '';
						$config['in-column'] = TRUE;

						$atts = publisher_improve_block_atts_for_size( array(), 'slider-3' );

						if ( isset( $atts['mg-layout'] ) ) {
							$config['class'] = $atts['mg-layout'];
						}

						break;

					case 'style-15':
						$config['directory'] = 'loop';
						$config['file']      = 'listing-modern-grid-5';
						$config['show']      = TRUE;
						$config['posts']     = 5;
						$config['columns']   = '';
						$config['in-column'] = FALSE;
						break;

					case 'style-16':
						$config['directory'] = 'loop';
						$config['file']      = 'listing-modern-grid-5';
						$config['show']      = TRUE;
						$config['posts']     = 3;
						$config['columns']   = '';
						$config['in-column'] = TRUE;

						// Set columns
						$atts = publisher_improve_block_atts_for_size( array(), 'mg-5' );

						if ( isset( $atts['mg-layout'] ) ) {
							$config['class'] = $atts['mg-layout'];
						}

						break;

					case 'style-17':
						$config['directory'] = 'loop';
						$config['file']      = 'listing-modern-grid-7';
						$config['show']      = TRUE;
						$config['posts']     = 5;
						$config['columns']   = '';
						$config['in-column'] = FALSE;
						break;

					case 'style-18':
						$config['directory'] = 'loop';
						$config['file']      = 'listing-modern-grid-7';
						$config['show']      = TRUE;
						$config['posts']     = 3;
						$config['columns']   = '';
						$config['in-column'] = TRUE;
						$config['class']     = 'bsw-' . publisher_get_block_size();

						break;

					case 'style-19':
						$config['directory'] = 'loop';
						$config['file']      = 'listing-modern-grid-8';
						$config['show']      = TRUE;
						$config['posts']     = 5;
						$config['columns']   = '';
						$config['in-column'] = FALSE;
						break;

					case 'style-20':
						$config['directory'] = 'loop';
						$config['file']      = 'listing-modern-grid-9';
						$config['show']      = TRUE;
						$config['posts']     = 7;
						$config['columns']   = '';
						$config['in-column'] = FALSE;
						break;

					case 'style-21':
						$config['directory'] = 'loop';
						$config['file']      = 'listing-modern-grid-10';
						$config['show']      = TRUE;
						$config['posts']     = 6;
						$config['columns']   = '';
						$config['in-column'] = FALSE;
						break;

					case 'style-22':
						$config['directory'] = 'loop';
						$config['file']      = 'listing-modern-grid-6';
						$config['show']      = TRUE;
						$config['posts']     = 2;
						$config['columns']   = '';
						$config['in-column'] = FALSE;
						break;

					case 'style-23':
						$config['directory'] = 'loop';
						$config['file']      = 'listing-modern-grid-6';
						$config['show']      = TRUE;
						$config['posts']     = 2;
						$config['columns']   = '';
						$config['in-column'] = TRUE;
						$config['class']     = 'bsw-' . publisher_get_block_size();
						break;

					default:
						$config['type']      = 'disable';
						$config['style']     = 'disable';
						$config['directory'] = '';
						$config['file']      = '';
						$config['show']      = FALSE;
						$config['posts']     = 0;

				}

				break;

			case 'rev_slider':

				//
				// Term type
				//
				if ( $args['type'] === 'term' ) {

					// get from current term
					if ( publisher_is_valid_tax() ) {
						$config['style'] = bf_get_term_meta( 'rev_slider_item', $args['term_id'], 'default' );
					}

					// Slider style
					if ( $config['style'] === 'default' || empty( $config['style'] ) ) {
						$config['style'] = publisher_get_option( 'cat_rev_slider_item' );
					}
				}
				//
				// Home type
				//
				elseif ( $args['type'] === 'home' ) {
					$config['style'] = publisher_get_option( 'home_rev_slider_item' );
				}


				// determine show
				if ( ! empty( $config['style'] ) && function_exists( 'putRevSlider' ) ) {
					$config['show'] = TRUE;
				}

				$config['in-column'] = FALSE;

				break;
		}

		// Have bg color
		if ( ! $config['in-column'] ) {
			$config['bg_color'] = publisher_get_option( 'cat_topposts_bg_color' );
		}

		// Save it to cache
		publisher_set_global( $args['type'] . '-slider-config', $config );

		return $config;

	} // publisher_main_slider_config

} // if


if ( ! function_exists( 'publisher_listing_social_share' ) ) {
	/**
	 * Prints listing share buttons
	 *
	 * @param array $args
	 */
	function publisher_listing_social_share( $args = array() ) {

		if ( ! isset( $args['type'] ) ) {
			$args['type'] = 'listing';
		}

		if ( ! isset( $args['class'] ) ) {
			$args['class'] = '';
		}

		if ( ! isset( $args['show_count'] ) ) {
			$args['show_count'] = FALSE;
		}

		if ( ! isset( $args['show_title'] ) ) {
			$args['show_title'] = FALSE;
		}

		if ( ! isset( $args['show_views'] ) ) {
			$args['show_views'] = FALSE;
		}

		if ( ! isset( $args['show_comments'] ) ) {
			$args['show_comments'] = FALSE;
		}

		if ( ! isset( $args['style'] ) ) {
			$args['style'] = publisher_get_option( 'social_share_style' );
		}

		$styles_fix = array(
			'style-1'  => array(
				'show_title' => FALSE,
			),
			'style-2'  => array(
				'show_title' => TRUE,
				'style'      => 'style-1',
			),
			'style-3'  => array(
				'show_title' => FALSE,
				'style'      => 'style-2',
			),
			'style-4'  => array(
				'show_title' => TRUE,
				'style'      => 'style-2',
			),
			'style-5'  => array(
				'show_title' => FALSE,
				'style'      => 'style-3',
			),
			'style-6'  => array(
				'show_title' => TRUE,
				'style'      => 'style-3',
			),
			'style-7'  => array(
				'show_title' => FALSE,
				'style'      => 'style-4',
			),
			'style-8'  => array(
				'show_title' => TRUE,
				'style'      => 'style-4',
			),
			'style-9'  => array(
				'show_title' => FALSE,
				'style'      => 'style-5',
			),
			'style-10' => array(
				'show_title' => TRUE,
				'style'      => 'style-5',
			),
			'style-11' => array(
				'show_title' => FALSE,
				'style'      => 'style-6',
			),
			'style-12' => array(
				'show_title' => TRUE,
				'style'      => 'style-6',
			),
			'style-13' => array(
				'show_title' => FALSE,
				'style'      => 'style-7',
			),
			'style-14' => array(
				'show_title' => TRUE,
				'style'      => 'style-7',
			),
			'style-15' => array(
				'show_title' => FALSE,
				'style'      => 'style-8',
			),
			'style-16' => array(
				'show_title' => TRUE,
				'style'      => 'style-8',
			),
			'style-17' => array(
				'show_title' => FALSE,
				'style'      => 'style-9',
			),
			'style-18' => array(
				'show_title' => TRUE,
				'style'      => 'style-9',
			),
			'style-19' => array(
				'show_title' => FALSE,
				'style'      => 'style-10',
			),
			'style-20' => array(
				'show_title' => TRUE,
				'style'      => 'style-10',
			),
			'style-21' => array(
				'show_title' => FALSE,
				'style'      => 'style-11',
			),
			'style-22' => array(
				'show_title' => TRUE,
				'style'      => 'style-11',
			),
		);

		if ( isset( $styles_fix[ $args['style'] ] ) ) {
			$args = array_merge( $args, $styles_fix[ $args['style'] ] );
		}

		$args['class'] .= ' ' . $args['style'];

		$sites = publisher_get_option( 'social_share_sites' );

		if ( $args['type'] === 'single' && publisher_get_option( 'social_share_count' ) === 'total-and-site' ) {
			$site_share_count = TRUE;
		} else {
			$site_share_count = FALSE;
		}

		?>
		<div class="post-share <?php echo esc_attr( $args['class'] ); ?>">
			<div class="post-share-btn-group">
				<?php


				if ( $args['type'] === 'single' && $args['show_comments'] && ( ! empty( $args['show_comments_force'] ) || comments_open() ) ) {

					$title  = apply_filters( 'better-studio/theme/meta/comments/title', publisher_get_the_title() );
					$link   = apply_filters( 'better-studio/theme/meta/comments/link', publisher_get_comments_link() );
					$number = apply_filters( 'better-studio/theme/meta/comments/number', publisher_get_comments_number() );
					$text   = apply_filters( 'better-studio/themes/meta/comments/text', $number );

					printf( '<a href="%1$s" class="post-share-btn post-share-btn-comments comments" title="%2$s"><i class="bf-icon fa fa-comments" aria-hidden="true"></i> <b class="number">%3$s</b></a>',
						esc_url( $link ),
						esc_attr( sprintf( publisher_translation_get( 'leave_comment_on' ), $title ) ),
						$text
					);

				}


				if ( $args['type'] === 'single' && $args['show_views'] && function_exists( 'The_Better_Views_Count' ) ) {

					$rank = publisher_get_ranking_icon( The_Better_Views_Count(), 'views_ranking', 'fa-eye', TRUE );

					if ( isset( $rank['show'] ) && $rank['show'] ) {
						The_Better_Views(
							TRUE,
							'<span class="views post-share-btn post-share-btn-views ' . $rank['id'] . '" data-bpv-post="' . get_the_ID() . '">' . $rank['icon'] . ' <b class="number">',
							'</b></span>',
							'show',
							'%VIEW_COUNT%'
						);
					}

				}

				?>
			</div>
			<?php
			if ( $args['type'] === 'single' ) {

			if ( $args['show_count'] ) {
				$count_labels = bf_social_shares_count( $sites );
			} else {
				$count_labels = array();
			}

			$total_count = array_sum( $count_labels );

			$rank = publisher_get_ranking_icon( $total_count, 'shares_ranking', 'fa-eye', TRUE );

			if ( empty( $rank['icon'] ) ) {
				$rank['icon'] = '<i class="fa fa-share-alt" aria-hidden="true"></i>';
			}

			if ( empty( $rank['id'] ) ) {
				$rank['id'] = 'rank-default';
			}

			?>
			<div class="share-handler-wrap <?php echo publisher_get_option( 'social_share_more' ) !== 'yes' ? 'bs-pretty-tabs-initialized' : ''; ?>">
				<span class="share-handler post-share-btn <?php echo $rank['id']; ?>">
					<?php

					echo $rank['icon'];

					if ( $total_count ) { ?>
						<b class="number"><?php echo bf_human_number_format( $total_count ) ?></b>
					<?php } else {
						?>
						<b class="text"><?php publisher_translation_echo( 'post_share' ); ?></b>
						<?php
					} ?>
				</span>
				<?php
				} elseif ( $args['type'] === 'listing' ) {
					//echo $label;
				}

				foreach ( (array) $sites as $site_key => $site ) {
					if ( $site ) {
						$count_label = $site_share_count && isset( $count_labels[ $site_key ] ) ? $count_labels[ $site_key ] : 0;
						echo publisher_shortcode_social_share_get_li( $site_key, $args['show_title'], $count_label );
					}
				}

				if ( $args['type'] === 'single' ) {
				?></div><?php
		}
		?>
		</div>
		<?php

	} // publisher_listing_social_share
}


if ( ! function_exists( 'publisher_layout_option_list' ) ) {
	/**
	 * Panels layout field options
	 *
	 * @param bool $default
	 *
	 * @return array
	 */
	function publisher_layout_option_list( $default = FALSE ) {

		$option = array();

		if ( $default ) {
			$option['default'] = array(
				'img'           => PUBLISHER_THEME_URI . 'images/options/layout-default.png?v=' . PUBLISHER_THEME_VERSION,
				'label'         => __( 'Default', 'publisher' ),
				'current_label' => __( 'Default Layout', 'publisher' ),
			);
		}

		$option['1-col']   = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/layout-1-col.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( 'No Sidebar (1)', 'publisher' ),
			'info'  => array(
				'cat' => array(
					__( '1 Column', 'publisher' ),
				),
			),
		);
		$option['3-col-0'] = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/layout-3-col-0.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( 'No Sidebar (2)', 'publisher' ),
			'info'  => array(
				'cat' => array(
					__( '1 Column', 'publisher' ),
				),
			),
		);

		$option['2-col-right'] = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/layout-2-col-right.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( '2 Column (1)', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
			'info'  => array(
				'cat' => array(
					__( '2 Column', 'publisher' ),
				),
			),
		);
		$option['2-col-left']  = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/layout-2-col-left.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( '2 Column (2)', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
			'info'  => array(
				'cat' => array(
					__( '2 Column', 'publisher' ),
				),
			),
		);

		$option['3-col-1'] = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/layout-3-col-1.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( '3 Column (1)', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
			'info'  => array(
				'cat' => array(
					__( '3 Column', 'publisher' ),
				),
			),
		);
		$option['3-col-2'] = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/layout-3-col-2.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( '3 Column (2)', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
			'info'  => array(
				'cat' => array(
					__( '3 Column', 'publisher' ),
				),
			),
		);
		$option['3-col-3'] = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/layout-3-col-3.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( '3 Column (3)', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
			'info'  => array(
				'cat' => array(
					__( '3 Column', 'publisher' ),
				),
			),
		);
		$option['3-col-4'] = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/layout-3-col-4.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( '3 Column (4)', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
			'info'  => array(
				'cat' => array(
					__( '3 Column', 'publisher' ),
				),
			),
		);
		$option['3-col-5'] = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/layout-3-col-5.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( '3 Column (5)', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
			'info'  => array(
				'cat' => array(
					__( '3 Column', 'publisher' ),
				),
			),
		);
		$option['3-col-6'] = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/layout-3-col-6.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( '3 Column (6)', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
			'info'  => array(
				'cat' => array(
					__( '3 Column', 'publisher' ),
				),
			),
		);

		return $option;
	} // publisher_layout_option_list
} // if


if ( ! function_exists( 'publisher_is_valid_layout' ) ) {
	/**
	 * Check the parameter is theme valid layout or not!
	 *
	 * This is because of multiple theme that have same page_layout id for page layout
	 *
	 * @param $layout
	 *
	 * @return bool
	 */
	function publisher_is_valid_layout( $layout ) {

		$valid = array(
			'1-col'       => '',
			'2-col-left'  => '',
			'2-col-right' => '',
			'3-col-0'     => '',
			'3-col-1'     => '',
			'3-col-2'     => '',
			'3-col-3'     => '',
			'3-col-4'     => '',
			'3-col-5'     => '',
			'3-col-6'     => '',
		);

		return isset( $valid[ $layout ] );
	} // publisher_is_valid_layout
} // if


if ( ! function_exists( 'publisher_get_page_boxed_layout' ) ) {
	/**
	 * Used to get current page boxed layout
	 *
	 * @return bool|mixed|null|string|void
	 */
	function publisher_get_page_boxed_layout() {

		$layout = '';


		// Retrieve post "page layout" from parent category
		if ( is_singular( 'post' ) ) {

			$main_term = publisher_get_post_primary_cat();

			if ( ! is_wp_error( $main_term ) && is_object( $main_term ) && bf_get_term_meta( 'override_in_posts', $main_term ) ) {
				$layout = bf_get_term_meta( 'layout_style', $main_term );
			}
		} elseif ( publisher_is_valid_tax() ) {
			$layout = bf_get_term_meta( 'layout_style' );

			$bg_img = bf_get_term_meta( 'bg_image' );
			if ( ! empty( $bg_img['img'] ) ) {
				$layout = 'boxed';
			}
		}

		if ( empty( $layout ) || $layout == FALSE || $layout === 'default' ) {
			$layout = publisher_get_option( 'layout_style' );

			if ( $layout === 'full-width' ) {
				$bg_img = publisher_get_option( 'site_bg_image' );
				if ( ! empty( $bg_img['img'] ) ) {
					$layout = 'boxed';
				}
			}
		}

		return $layout;
	}
}


if ( ! function_exists( 'publisher_get_page_layout' ) ) {
	/**
	 * Used to get current page layout
	 *
	 * @return string
	 */
	function publisher_get_page_layout() {

		// Return from cache
		if ( publisher_get_global( 'page-layout' ) ) {
			return publisher_get_global( 'page-layout' );
		}

		$layout = 'default';

		// Homepage layout
		if ( is_home() ||
		     ( ( 'page' === get_option( 'show_on_front' ) ) && is_front_page() && bf_get_query_var_paged( 1 ) > 1 )
		) {
			$layout = publisher_get_option( 'home_layout' );
		} // Post, page and custom post types layout
		elseif ( publisher_is_valid_cpt() ) {

			$layout = bf_get_post_meta( 'page_layout' );

			if ( $layout === 'default' ) {
				if ( is_page() ) {
					$layout = publisher_get_option( 'page_layout' );
				} else {
					$layout = publisher_get_option( 'post_layout' );

					// default -> Retrieve from parent category
					if ( $layout === 'default' || ! publisher_is_valid_layout( $layout ) ) {

						$main_term = publisher_get_post_primary_cat();

						if ( ! is_wp_error( $main_term ) && is_object( $main_term ) && bf_get_term_meta( 'override_in_posts', $main_term ) ) {
							$layout = bf_get_term_meta( 'page_layout', $main_term );
						}
					}
				}
			}

		}  // Category Layout
		elseif ( publisher_is_valid_tax() ) {

			$layout = bf_get_term_meta( 'page_layout' );

			if ( $layout === 'default' ) {
				$layout = publisher_get_option( 'cat_layout' );
			}
		} // Tag Layout
		elseif ( publisher_is_valid_tax( 'tag' ) ) {

			$layout = bf_get_term_meta( 'page_layout' );

			if ( $layout === 'default' ) {
				$layout = publisher_get_option( 'tag_layout' );
			}
		} // Author Layout
		elseif ( is_author() ) {
			$layout = bf_get_user_meta( 'page_layout' );

			if ( $layout === 'default' ) {
				$layout = publisher_get_option( 'author_layout' );
			}
		} // Search Layout
		elseif ( is_search() ) {
			$layout = publisher_get_option( 'search_layout' );
		} // bbPress Layout
		elseif ( is_post_type_archive( 'forum' ) || is_singular( 'forum' ) || is_singular( 'topic' ) ) {

			$layout = bf_get_post_meta( 'page_layout', NULL, $layout );

			if ( $layout == 'default' ) {
				$layout = publisher_get_option( 'bbpress_layout' );
			}

		} // Attachments
		elseif ( is_attachment() ) {
			$layout = publisher_get_option( 'attachment_layout' );
		} // WooCommerce
		elseif ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {

			if ( is_shop() ) {
				$layout = bf_get_post_meta( 'page_layout', wc_get_page_id( 'shop' ) );
			} elseif ( is_product() ) {
				$layout = bf_get_post_meta( 'page_layout', get_the_ID() );
			} elseif ( is_cart() ) {
				$layout = bf_get_post_meta( 'page_layout', wc_get_page_id( 'cart' ) );
			} elseif ( is_checkout() ) {
				$layout = bf_get_post_meta( 'page_layout', wc_get_page_id( 'checkout' ) );
			} elseif ( is_account_page() ) {
				$layout = bf_get_post_meta( 'page_layout', wc_get_page_id( 'myaccount' ) );
			} elseif ( is_product_category() || is_product_tag() ) {
				$layout = bf_get_term_meta( 'page_layout', get_queried_object()->term_id );
			}

			if ( $layout === 'default' ) {
				$layout = publisher_get_option( 'shop_layout' );
			}

		}

		// Return default
		if ( $layout === 'default' || ! publisher_is_valid_layout( $layout ) ) {
			$layout = publisher_get_option( 'general_layout' );
		}

		// Cache
		publisher_set_global( 'page-layout', $layout );

		return $layout;

	} // publisher_get_page_layout
}// if


if ( ! function_exists( 'publisher_get_page_layout_file' ) ) {
	/**
	 * Used to get current page layout file
	 *
	 * @return string
	 */
	function publisher_get_page_layout_file() {

		static $layout_file;

		if ( $layout_file ) {
			return $layout_file;
		}

		$layout_file = publisher_get_page_layout();
		$layout_file = $layout_file[0];

		if ( $layout_file == '2' ) {
			$layout_file = '2-col';
		} elseif ( $layout_file == '1' ) {
			$layout_file = '1-col';
		} elseif ( $layout_file == '3' ) {
			$layout_file = '3-col';
		} else {
			$layout_file = '2-col';
		}

		return $layout_file;

	} // publisher_get_page_layout
}// if


if ( ! function_exists( 'publisher_get_page_layout_setting' ) ) {
	/**
	 * Used to get current page layout columns setting
	 *
	 * @return array
	 */
	function publisher_get_page_layout_setting() {

		static $layout;

		if ( $layout ) {
			return $layout;
		}

		$layout['layout']    = publisher_get_page_layout();
		$layout['container'] = '';
		$layout['columns']   = array();

		switch ( $layout['layout'][0] ) {

			//
			// 2 Column layouts
			//
			case '2':

				if ( $layout['layout'] === '2-col-right' ) {
					$layout['container'] = 'layout-2-col layout-2-col-1 layout-right-sidebar';
					$layout['columns']   = array(
						array(
							'id'    => 'content',
							'class' => 'col-sm-8 content-column',
						),
						array(
							'id'    => 'primary',
							'class' => 'col-sm-4 sidebar-column sidebar-column-primary',
						),
					);
				} else {
					$layout['container'] = 'layout-2-col layout-2-col-2 layout-left-sidebar';
					$layout['columns']   = array(
						array(
							'id'    => 'content',
							'class' => 'col-sm-8 col-sm-push-4 content-column',
						),
						array(
							'id'    => 'primary',
							'class' => 'col-sm-4 col-sm-pull-8 sidebar-column sidebar-column-primary',
						),
					);
				}

				break;

			//
			// 3 Column layouts
			//
			case '3':

				$layout['container'] = 'layout-3-col layout-' . $layout['layout'];

				if ( $layout['layout'][6] == 0 ) {
					$layout['columns'] = array(
						array(
							'id'    => 'content',
							'class' => 'col-sm-12 content-column',
						),
					);

					// Page that was not built with WP Bakery Page Builder
					if ( ! is_singular() || ! publisher_is_pagebuilder_used( get_the_ID() ) ) {
						$layout['container'] .= ' container';
					}

				} else {
					$layout['container'] .= ' container';
					$layout['columns']   = array(
						array(
							'id'    => 'content',
							'class' => 'col-sm-7 content-column',
						),
						array(
							'id'    => 'primary',
							'class' => 'col-sm-3 sidebar-column sidebar-column-primary',
						),
						array(
							'id'    => 'secondary',
							'class' => 'col-sm-2 sidebar-column sidebar-column-secondary',
						),
					);
				}

				break;

			//
			// 1 Column layouts
			//
			case '1':
				$layout['container'] = 'layout-1-col layout-no-sidebar';
				$layout['columns']   = array(
					array(
						'id'    => 'content',
						'class' => 'col-sm-12 content-column',
					),
				);
				break;

		}

		return $layout;

	} // publisher_get_page_layout
}// if


if ( ! function_exists( 'publisher_listing_option_list' ) ) {
	/**
	 * Panels posts listing field option
	 *
	 * @param bool $default
	 *
	 * @return array
	 */
	function publisher_listing_option_list( $default = FALSE ) {

		$option = array();

		if ( $default ) {
			$option['default'] = array(
				'img'           => PUBLISHER_THEME_URI . 'images/options/listing-default.png?v=' . PUBLISHER_THEME_VERSION,
				'label'         => __( 'Default', 'publisher' ),
				'current_label' => __( 'Default Listing', 'publisher' ),
			);
		}

		$option['grid-1']    = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/listing-grid-1.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Grid 1', 'publisher' ),
			'current_label' => __( 'Grid Listing 1', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat' => array(
					__( 'Grid Listing', 'publisher' ),
				),
			),
		);
		$option['grid-1-3']  = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/listing-grid-1-3.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Grid 1', 'publisher' ),
			'current_label' => __( 'Grid Listing 1 (3 column)', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat' => array(
					__( 'Grid Listing', 'publisher' ),
				),
			),
			'badges'        => array(
				'3 Column',
			),
		);
		$option['grid-2']    = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/listing-grid-2.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Grid 2', 'publisher' ),
			'current_label' => __( 'Grid Listing 2', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat' => array(
					__( 'Grid Listing', 'publisher' ),
				),
			),
		);
		$option['grid-2-3']  = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/listing-grid-2-3.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Grid 2', 'publisher' ),
			'current_label' => __( 'Grid Listing 2 (3 column)', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat' => array(
					__( 'Grid Listing', 'publisher' ),
				),
			),
			'badges'        => array(
				'3 Column',
			),
		);
		$option['blog-1']    = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/listing-blog-1.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Blog 1', 'publisher' ),
			'current_label' => __( 'Blog Listing 1', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat' => array(
					__( 'Blog Listing', 'publisher' ),
				),
			),
		);
		$option['blog-2']    = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/listing-blog-2.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Blog 2', 'publisher' ),
			'current_label' => __( 'Blog Listing 2', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat' => array(
					__( 'Blog Listing', 'publisher' ),
				),
			),
		);
		$option['blog-3']    = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/listing-blog-3.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Blog 3', 'publisher' ),
			'current_label' => __( 'Blog Listing 3', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat' => array(
					__( 'Blog Listing', 'publisher' ),
				),
			),
		);
		$option['blog-4']    = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/listing-blog-4.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Blog 4', 'publisher' ),
			'current_label' => __( 'Blog Listing 4', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat' => array(
					__( 'Blog Listing', 'publisher' ),
				),
			),
		);
		$option['blog-5']    = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/listing-blog-5.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Blog 5', 'publisher' ),
			'current_label' => __( 'Blog Listing 5', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat' => array(
					__( 'Blog Listing', 'publisher' ),
				),
			),
		);
		$option['classic-1'] = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/listing-classic-1.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Classic 1', 'publisher' ),
			'current_label' => __( 'Classic Listing 1', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat' => array(
					__( 'Classic Listing', 'publisher' ),
				),
			),
		);
		$option['classic-2'] = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/listing-classic-2.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Classic 2', 'publisher' ),
			'current_label' => __( 'Classic Listing 2', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat' => array(
					__( 'Classic Listing', 'publisher' ),
				),
			),
		);
		$option['classic-3'] = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/listing-classic-3.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Classic 3', 'publisher' ),
			'current_label' => __( 'Classic Listing 3', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat' => array(
					__( 'Classic Listing', 'publisher' ),
				),
			),
		);
		$option['tall-1']    = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/listing-tall-1.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Tall 1', 'publisher' ),
			'current_label' => __( 'Tall Listing 1', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat' => array(
					__( 'Tall Listing', 'publisher' ),
				),
			),
		);
		$option['tall-1-4']  = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/listing-tall-1-4.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Tall 1', 'publisher' ),
			'current_label' => __( 'Tall Listing 1 (4 column)', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat' => array(
					__( 'Tall Listing', 'publisher' ),
				),
			),
			'badges'        => array(
				'4 Column',
			),
		);
		$option['tall-2']    = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/listing-tall-2.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Tall 2', 'publisher' ),
			'current_label' => __( 'Tall Listing 2', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat' => array(
					__( 'Tall Listing', 'publisher' ),
				),
			),
		);
		$option['tall-2-4']  = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/listing-tall-2-4.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Tall 2', 'publisher' ),
			'current_label' => __( 'Tall Listing 2 (4 column)', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat' => array(
					__( 'Tall Listing', 'publisher' ),
				),
			),
			'badges'        => array(
				'4 Column',
			),
		);
		$option['mix-4-1']   = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/listing-mix-4-1.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Mix 11', 'publisher' ),
			'current_label' => __( 'Mix Listing 11', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat' => array(
					__( 'Tall Listing', 'publisher' ),
				),
			),
		);
		$option['mix-4-2']   = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/listing-mix-4-2.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Mix 12', 'publisher' ),
			'current_label' => __( 'Mix Listing 12', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat' => array(
					__( 'Mix Listing', 'publisher' ),
				),
			),
		);
		$option['mix-4-3']   = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/listing-mix-4-3.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Mix 13', 'publisher' ),
			'current_label' => __( 'Mix Listing 13', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat' => array(
					__( 'Mix Listing', 'publisher' ),
				),
			),
		);
		$option['mix-4-4']   = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/listing-mix-4-4.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Mix 14', 'publisher' ),
			'current_label' => __( 'Mix Listing 14', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat' => array(
					__( 'Mix Listing', 'publisher' ),
				),
			),
		);
		$option['mix-4-5']   = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/listing-mix-4-5.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Mix 15', 'publisher' ),
			'current_label' => __( 'Mix Listing 15', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat' => array(
					__( 'Mix Listing', 'publisher' ),
				),
			),
		);
		$option['mix-4-6']   = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/listing-mix-4-6.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Mix 16', 'publisher' ),
			'current_label' => __( 'Mix Listing 16', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat' => array(
					__( 'Mix Listing', 'publisher' ),
				),
			),
		);
		$option['mix-4-7']   = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/listing-mix-4-7.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Mix 17', 'publisher' ),
			'current_label' => __( 'Mix Listing 17', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat' => array(
					__( 'Mix Listing', 'publisher' ),
				),
			),
		);
		$option['mix-4-8']   = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/listing-mix-4-8.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Mix 18', 'publisher' ),
			'current_label' => __( 'Mix Listing 18', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat' => array(
					__( 'Mix Listing', 'publisher' ),
				),
			),
		);
		$option['text-1-2']  = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/listing-text-1-2.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Text 1', 'publisher' ),
			'current_label' => __( 'Text Listing 1 (2 column)', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat' => array(
					__( 'Text Listing', 'publisher' ),
				),
			),
			'badges'        => array(
				'2 Column',
				'NEW',
			),
		);
		$option['text-1-3']  = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/listing-text-1-3.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Text 1', 'publisher' ),
			'current_label' => __( 'Text Listing 1 (3 column)', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat' => array(
					__( 'Text Listing', 'publisher' ),
				),
			),
			'badges'        => array(
				'3 Column',
				'NEW',
			),
		);
		$option['text-2-2']  = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/listing-text-2-2.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Text 2', 'publisher' ),
			'current_label' => __( 'Text Listing 2 (2 column)', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat' => array(
					__( 'Text Listing', 'publisher' ),
				),
			),
			'badges'        => array(
				'2 Column',
				'NEW',
			),
		);
		$option['text-2-3']  = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/listing-text-2-3.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Text 2', 'publisher' ),
			'current_label' => __( 'Text Listing 2 (3 column)', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat' => array(
					__( 'Text Listing', 'publisher' ),
				),
			),
			'badges'        => array(
				'3 Column',
				'NEW',
			),
		);
		$option['text-3']    = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/listing-text-3.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Text 3', 'publisher' ),
			'current_label' => __( 'Text Listing 3 (1 column)', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat' => array(
					__( 'Text Listing', 'publisher' ),
				),
			),
			'badges'        => array(
				'1 Column',
				'NEW',
			),
		);
		$option['text-3-2']  = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/listing-text-3-2.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Text 3', 'publisher' ),
			'current_label' => __( 'Text Listing 3 (2 column)', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat' => array(
					__( 'Text Listing', 'publisher' ),
				),
			),
			'badges'        => array(
				'2 Column',
				'NEW',
			),
		);
		$option['text-3-3']  = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/listing-text-3-3.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Text 3', 'publisher' ),
			'current_label' => __( 'Text Listing 3 (3 column)', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat' => array(
					__( 'Text Listing', 'publisher' ),
				),
			),
			'badges'        => array(
				'3 Column',
				'NEW',
			),
		);
		$option['text-4']    = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/listing-text-4.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Text 4', 'publisher' ),
			'current_label' => __( 'Text Listing 4 (1 column)', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat' => array(
					__( 'Text Listing', 'publisher' ),
				),
			),
			'badges'        => array(
				'1 Column',
				'NEW',
			),
		);
		$option['text-4-2']  = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/listing-text-4-2.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Text 4', 'publisher' ),
			'current_label' => __( 'Text Listing 4 (2 column)', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat' => array(
					__( 'Text Listing', 'publisher' ),
				),
			),
			'badges'        => array(
				'2 Column',
				'NEW',
			),
		);
		$option['text-4-3']  = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/listing-text-4-3.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Text 4', 'publisher' ),
			'current_label' => __( 'Text Listing 4 (3 column)', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat' => array(
					__( 'Text Listing', 'publisher' ),
				),
			),
			'badges'        => array(
				'3 Column',
				'NEW',
			),
		);

		return $option;
	} // publisher_listing_option_list
} // if


if ( ! function_exists( 'publisher_is_valid_listing' ) ) {
	/**
	 * Checks parameter to be a theme valid listing
	 *
	 * @param $listing
	 *
	 * @return bool
	 */
	function publisher_is_valid_listing( $listing ) {

		return array_key_exists( $listing, publisher_listing_option_list() );
	} // publisher_is_valid_listing

} // if


if ( ! function_exists( 'publisher_get_page_listing' ) ) {
	/**
	 * Used to get current page posts listing
	 *
	 * @param WP_Query|null $wp_query
	 *
	 * @return mixed|string
	 */
	function publisher_get_page_listing( $wp_query = NULL ) {

		if ( is_null( $wp_query ) ) {
			$wp_query = publisher_get_query();
		}

		// Return from cache
		if ( publisher_get_global( 'page-listing' ) ) {
			return publisher_get_global( 'page-listing' );
		}

		$listing = 'default';

		// Homepage listing
		if ( $wp_query->is_home ) {
			$listing = publisher_get_option( 'home_listing' );
		} // Category Layout
		elseif ( $wp_query->get_queried_object_id() > 0 && publisher_is_valid_tax( 'category', $wp_query->get_queried_object() ) ) {

			$listing = bf_get_term_meta( 'page_listing', $wp_query->get_queried_object_id() );

			if ( $listing === 'default' ) {
				$listing = publisher_get_option( 'cat_listing' );
			}
		} // Tag Layout
		elseif ( $wp_query->get_queried_object_id() > 0 && publisher_is_valid_tax( 'tag', $wp_query->get_queried_object() ) ) {
			$listing = bf_get_term_meta( 'page_listing', $wp_query->get_queried_object_id() );

			if ( $listing === 'default' ) {
				$listing = publisher_get_option( 'tag_listing' );
			}
		} // Author Layout
		elseif ( $wp_query->is_author ) {

			if ( $user = bf_get_author_archive_user() ) {
				$listing = bf_get_user_meta( 'page_listing', $user->ID );
			}

			if ( $listing === 'default' ) {
				$listing = publisher_get_option( 'author_listing' );
			}
		} // Search Layout
		elseif ( $wp_query->is_search ) {
			$listing = publisher_get_option( 'search_listing' );
		}

		// check to be valid theme listing or use default
		if ( $listing === 'default' || ! publisher_is_valid_listing( $listing ) ) {
			$listing = publisher_get_option( 'general_listing' );
		}

		switch ( $listing ) {

			case 'grid-1';
				publisher_set_prop( 'listing-class', 'columns-2' );
				break;

			case 'grid-1-3';
				publisher_set_prop( 'listing-class', 'columns-3' );
				$listing = 'grid-1';
				break;

			case 'grid-2';
				publisher_set_prop( 'listing-class', 'columns-2' );
				break;

			case 'grid-2-3';
				publisher_set_prop( 'listing-class', 'columns-3' );
				$listing = 'grid-2';
				break;

			case 'tall-1';
				publisher_set_prop( 'listing-class', 'columns-3' );
				break;

			case 'tall-1-4';
				publisher_set_prop( 'listing-class', 'columns-4' );
				$listing = 'tall-1';
				break;

			case 'tall-2';
				publisher_set_prop( 'listing-class', 'columns-3' );
				break;

			case 'tall-2-4';
				publisher_set_prop( 'listing-class', 'columns-4' );
				$listing = 'tall-2';
				break;

			case 'text-1-2';
				publisher_set_prop( 'listing-class', 'columns-2' );
				$listing = 'text-1';
				break;

			case 'text-1-3';
				publisher_set_prop( 'listing-class', 'columns-3' );
				$listing = 'text-1';
				break;

			case 'text-2-2';
				publisher_set_prop( 'listing-class', 'columns-2' );
				$listing = 'text-2';
				break;

			case 'text-2-3';
				publisher_set_prop( 'listing-class', 'columns-3' );
				$listing = 'text-2';
				break;

			case 'text-3-2';
				publisher_set_prop( 'listing-class', 'columns-2' );
				$listing = 'text-3';
				break;

			case 'text-3-3';
				publisher_set_prop( 'listing-class', 'columns-3' );
				$listing = 'text-3';
				break;

			case 'text-4-2';
				publisher_set_prop( 'listing-class', 'columns-2' );
				$listing = 'text-4';
				break;

			case 'text-4-3';
				publisher_set_prop( 'listing-class', 'columns-3' );
				$listing = 'text-4';
				break;

		}


		// Cache
		publisher_set_global( 'page-listing', 'listing-' . $listing );

		return 'listing-' . $listing;

	} // publisher_get_page_listing
} // if


if ( ! function_exists( 'publisher_get_show_page_listing_excerpt' ) ) {
	/**
	 * Used to get  show excerpt of current page posts listing
	 *
	 * @param WP_Query|null $wp_query
	 *
	 * @return mixed|string
	 */
	function publisher_get_show_page_listing_excerpt( $wp_query = NULL ) {

		if ( is_null( $wp_query ) ) {
			$wp_query = publisher_get_query();
		}

		// Return from cache
		if ( publisher_get_global( 'page-listing-excerpt' ) ) {
			return publisher_get_global( 'page-listing-excerpt' );
		}

		$excerpt = 'default';

		// Homepage listing
		if ( $wp_query->is_home ) {
			$excerpt = publisher_get_option( 'home_listing_excerpt' );
		} // Category Layout
		elseif ( $wp_query->get_queried_object_id() > 0 && publisher_is_valid_tax( 'category', $wp_query->get_queried_object() ) ) {

			$excerpt = bf_get_term_meta( 'page_listing_excerpt', $wp_query->get_queried_object_id() );

			if ( $excerpt === 'default' ) {
				$excerpt = publisher_get_option( 'cat_listing_excerpt' );
			}
		} // Tag Layout
		elseif ( $wp_query->get_queried_object_id() > 0 && publisher_is_valid_tax( 'tag', $wp_query->get_queried_object() ) ) {
			$excerpt = bf_get_term_meta( 'page_listing_excerpt', $wp_query->get_queried_object_id() );

			if ( $excerpt === 'default' ) {
				$excerpt = publisher_get_option( 'tag_listing_excerpt' );
			}
		} // Author Layout
		elseif ( $wp_query->is_author ) {
			if ( $user = bf_get_author_archive_user() ) {
				$excerpt = bf_get_user_meta( 'page_listing_excerpt', $user->ID );
			}

			if ( $excerpt === 'default' ) {
				$excerpt = publisher_get_option( 'author_listing_excerpt' );
			}
		} // Search Layout
		elseif ( $wp_query->is_search ) {
			$excerpt = publisher_get_option( 'search_listing_excerpt' );
		}

		// check to be valid theme listing or use default
		if ( $excerpt === 'default' || is_null( $excerpt ) ) {
			$excerpt = publisher_get_option( 'general_listing_excerpt' );
		}

		if ( $excerpt === 'hide' ) {
			$excerpt = FALSE;
		} else {
			$excerpt = TRUE;
		}

		// Cache
		publisher_set_global( 'page-listing-excerpt', $excerpt );

		return $excerpt;

	} // publisher_get_page_listing
} // if


if ( ! function_exists( 'publisher_pagination_option_list' ) ) {
	/**
	 * Panels archives pagination field options
	 *
	 * @param bool $default
	 *
	 * @return array
	 */
	function publisher_pagination_option_list( $default = FALSE ) {

		$option = array();

		if ( $default ) {
			$option['default'] = __( '-- Default pagination --', 'publisher' );
		}

		// simple paginations
		$option['numbered'] = __( 'Numbered pagination buttons', 'publisher' );
		$option['links']    = __( 'Newer & Older buttons', 'publisher' );

		// advanced ajax pagination
		$option['ajax_next_prev']         = __( 'Ajax - Next Prev buttons', 'publisher' );
		$option['ajax_more_btn']          = __( 'Ajax - Load more button', 'publisher' );
		$option['ajax_more_btn_infinity'] = __( 'Ajax - Load more button + Infinity loading', 'publisher' );
		$option['ajax_infinity']          = __( 'Ajax - Infinity loading', 'publisher' );

		return $option;

	} // publisher_pagination_option_list
} // if


if ( ! function_exists( 'publisher_is_valid_pagination' ) ) {
	/**
	 * Checks parameter to be a theme valid pagination
	 *
	 * @param $pagination
	 *
	 * @return bool
	 */
	function publisher_is_valid_pagination( $pagination ) {

		return array_key_exists( $pagination, publisher_pagination_option_list() );
	} // publisher_is_valid_pagination
} // if


if ( ! function_exists( 'publisher_get_pagination_style' ) ) {
	/**
	 * Used to get current page pagination style
	 */
	function publisher_get_pagination_style() {

		// Return from cache
		if ( publisher_get_global( 'page-pagination' ) ) {
			return publisher_get_global( 'page-pagination' );
		}

		$pagination = 'default';

		$paged = bf_get_query_var_paged();

		// Homepage pagination
		if ( is_home() || ( ( 'page' === get_option( 'show_on_front' ) ) && is_front_page() && bf_get_query_var_paged( 1 ) > 1 ) ) {
			$pagination = publisher_get_option( 'home_pagination_type' );
		} // Categories pagination
		elseif ( publisher_is_valid_tax() ) {

			$pagination = bf_get_term_meta( 'term_pagination_type' );

			if ( $pagination === 'default' ) {
				$pagination = publisher_get_option( 'cat_pagination_type' );
			}

		} // Tags pagination
		elseif ( publisher_is_valid_tax( 'tag' ) ) {

			$pagination = bf_get_term_meta( 'term_pagination_type' );

			if ( $pagination === 'default' ) {
				$pagination = publisher_get_option( 'tag_pagination_type' );
			}

		} // Author pagination
		elseif ( is_author() ) {
			$pagination = bf_get_user_meta( 'author_pagination_type' );

			if ( $pagination === 'default' ) {
				$pagination = publisher_get_option( 'author_pagination_type' );
			}
		} // Search page pagination
		elseif ( is_search() ) {
			$pagination = publisher_get_option( 'search_pagination_type' );
		}

		// fix for when request is from robots,
		// e.g. user agent: 'Mozilla/5.0 (compatible; YandexBot/3.0; +http://yandex.com/bots)'
		// fix for when paged and is ajax pagination then it should show simple numbered pagination
		$ajax_pagins = array(
			'ajax_infinity'          => '',
			'ajax_more_btn'          => '',
			'ajax_next_prev'         => '',
			'ajax_more_btn_infinity' => '',
		);

		if (
			( $paged > 1 && isset( $ajax_pagins[ $pagination ] ) ) ||
			( bf_is_crawler() && isset( $ajax_pagins[ $pagination ] ) )
		) {
			$pagination = 'numbered';
		}

		unset( $ajax_pagins ); // clear memory

		// get default pagination
		if ( $pagination === 'default' ) {
			$pagination = publisher_get_option( 'pagination_type' );
		}

		// check to be valid theme pagination
		if ( ! publisher_is_valid_pagination( $pagination ) ) {
			$pagination = key( publisher_pagination_option_list() );
		}

		// Cache
		publisher_set_global( 'page-pagination', $pagination );

		return $pagination;

	} // publisher_get_pagination_style
}


if ( ! function_exists( 'publisher_header_style_option_list' ) ) {
	/**
	 * Panels header style field options
	 *
	 * @param bool $default
	 *
	 * @param bool $disable
	 *
	 * @return array
	 */
	function publisher_header_style_option_list( $default = FALSE, $disable = TRUE ) {

		$option = array();

		if ( $default ) {
			$option['default'] = array(
				'img'   => PUBLISHER_THEME_URI . 'images/options/header-default.png?v=' . PUBLISHER_THEME_VERSION,
				'label' => __( '-- Default --', 'publisher' ),
			);
		}

		$option['style-1'] = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/header-style-1.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( 'Style 1', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
		);
		$option['style-2'] = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/header-style-2.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( 'Style 2', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
		);
		$option['style-3'] = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/header-style-3.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( 'Style 3', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
		);
		$option['style-4'] = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/header-style-4.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( 'Style 4', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
		);
		$option['style-5'] = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/header-style-5.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( 'Style 5', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
		);
		$option['style-6'] = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/header-style-6.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( 'Style 6', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
		);
		$option['style-7'] = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/header-style-7.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( 'Style 7', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
		);
		$option['style-8'] = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/header-style-8.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( 'Style 8', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
		);

		if ( $disable ) {
			$option['disable'] = array(
				'img'   => PUBLISHER_THEME_URI . 'images/options/header-disable.png?v=' . PUBLISHER_THEME_VERSION,
				'label' => __( 'No Header', 'publisher' ),
				'class' => 'bf-flip-img-rtl',
			);
		}

		$option = apply_filters( 'publisher/headers/list', $option, $default, $disable );

		return $option;
	} // publisher_header_style_option_list
} // if


if ( ! function_exists( 'publisher_header_layout_option_list' ) ) {
	/**
	 * Header layouts list
	 *
	 * @param bool $default
	 *
	 * @return array
	 */
	function publisher_header_layout_option_list( $default = FALSE ) {

		$option = array(
			array(
				'label'   => 'Lock Inside Page Layout',
				'options' => array(
					'boxed'      => __( 'Boxed header', 'publisher' ),
					'full-width' => __( 'Full width header (Boxed Content)', 'publisher' ),
					'stretched'  => __( 'Full width header (Stretched Content)', 'publisher' ),
				)
			),
			array(
				'label'   => 'Out Of Page Layout',
				'options' => array(
					'out-full-width' => __( 'Full width header (Boxed Content)', 'publisher' ),
					'out-stretched'  => __( 'Full width header (Stretched Content)', 'publisher' ),
				)
			)
		);

		if ( $default ) {
			$option = array(
				          'default' => __( '-- Default --', 'publisher' ),
			          ) + $option;
		}

		return $option;
	} // publisher_header_layout_option_list
} // if


if ( ! function_exists( 'publisher_footer_style_option_list' ) ) {
	/**
	 * Panels footer style field options
	 *
	 * @param bool $default
	 *
	 * @return array
	 */
	function publisher_footer_style_option_list( $default = FALSE, $disable = TRUE ) {

		$option = array();

		if ( $default ) {
			$option['default'] = array(
				'img'   => PUBLISHER_THEME_URI . 'images/options/footer-default.png?v=' . PUBLISHER_THEME_VERSION,
				'label' => __( '-- Default --', 'publisher' ),
			);
		}

		if ( $disable ) {
			$option['disable'] = array(
				'img'   => PUBLISHER_THEME_URI . 'images/options/footer-disable.png?v=' . PUBLISHER_THEME_VERSION,
				'label' => __( 'No Footer', 'publisher' ),
			);
		}

		return $option;
	} // publisher_footer_style_option_list
} // if


if ( ! function_exists( 'publisher_get_footer_style' ) ) {
	/**
	 * Used to get current page footer style
	 *
	 * @return string
	 */
	function publisher_get_footer_style() {

		static $style;

		if ( $style ) {
			return $style;
		}

		$style = 'style-1';

		if ( publisher_is_valid_cpt() ) {
			$style = bf_get_post_meta( 'footer_style' );
		} // WooCommerce
		elseif ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {

			if ( is_shop() ) {
				$style = bf_get_post_meta( 'footer_style', wc_get_page_id( 'shop' ) );
			} elseif ( is_product() ) {
				$style = bf_get_post_meta( 'footer_style', get_the_ID() );
			} elseif ( is_cart() ) {
				$style = bf_get_post_meta( 'footer_style', wc_get_page_id( 'cart' ) );
			} elseif ( is_checkout() ) {
				$style = bf_get_post_meta( 'footer_style', wc_get_page_id( 'checkout' ) );
			} elseif ( is_account_page() ) {
				$style = bf_get_post_meta( 'footer_style', wc_get_page_id( 'myaccount' ) );
			} elseif ( is_product_category() || is_product_tag() ) {
				$style = bf_get_term_meta( 'footer_style', get_queried_object()->term_id );
			}
		}

		if ( $style === 'default' ) {
			$style = 'style-1';
		}

		return $style;

	} // publisher_get_footer_style
} // if


if ( ! function_exists( 'publisher_footer_layout_option_list' ) ) {
	/**
	 * Footer layouts list
	 *
	 * @param bool $default
	 *
	 * @return array
	 */
	function publisher_footer_layout_option_list( $default = FALSE ) {

		$option = array(
			array(
				'label'   => 'Lock Inside Page Layout',
				'options' => array(
					'boxed'      => __( 'Boxed footer', 'publisher' ),
					'full-width' => __( 'Full width footer (Boxed Content)', 'publisher' ),
					'stretched'  => __( 'Full width footer (Stretched Content)', 'publisher' ),
				)
			),
			array(
				'label'   => 'Out Of Page Layout',
				'options' => array(
					'out-full-width' => __( 'Full width footer (Boxed Content)', 'publisher' ),
					'out-stretched'  => __( 'Full width footer (Stretched Content)', 'publisher' ),
				)
			)
		);

		if ( $default ) {
			$option = array(
				          'default' => __( '-- Default --', 'publisher' ),
			          ) + $option;
		}

		return $option;
	} // publisher_footer_layout_option_list
} // if


if ( ! function_exists( 'publisher_get_footer_layout' ) ) {
	/**
	 * Returns footer layout for current page
	 *
	 * @return bool
	 */
	function publisher_get_footer_layout() {

		// Return from cache
		if ( publisher_get_global( 'footer-layout' ) ) {
			return publisher_get_global( 'footer-layout' );
		}

		$layout = publisher_get_option( 'footer_layout' );

		// Cache
		publisher_set_global( 'footer-layout', $layout );

		return $layout;

	} // publisher_get_footer_layout
}


if ( ! function_exists( 'publisher_get_footer_layout_class' ) ) {
	/**
	 * Returns footer layout class for current page
	 *
	 * @return bool
	 */
	function publisher_get_footer_layout_class() {

		static $class;

		if ( $class ) {
			return $class;
		}

		$class = publisher_get_footer_layout();

		$_check = array(
			'boxed'          => 'boxed',
			'full-width'     => 'full-width',
			'stretched'      => 'full-width stretched',
			'out-full-width' => 'full-width',
			'out-stretched'  => 'full-width stretched',
		);

		$class = $_check[ $class ];
		unset( $_check );

		return $class;

	} // publisher_get_footer_layout_class
}


if ( ! function_exists( 'publisher_topposts_option_list' ) ) {
	/**
	 * Panels category toposts field options
	 *
	 * @param bool $default
	 *
	 * @return array
	 */
	function publisher_topposts_option_list( $default = FALSE ) {

		$option = array();

		if ( $default ) {
			$option['default'] = array(
				'img'   => PUBLISHER_THEME_URI . 'images/options/cat-slider-default.png?v=' . PUBLISHER_THEME_VERSION,
				'label' => __( 'Default', 'publisher' ),
			);
		}

		$option['style-1']  = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/cat-slider-style-1.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( 'Style 1', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
			'info'  => array(
				'cat' => array(
					__( 'Large', 'publisher' ),
				),
			),
		);
		$option['style-2']  = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/cat-slider-style-2.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( 'Style 2', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
			'info'  => array(
				'cat' => array(
					__( 'Small', 'publisher' ),
				),
			),
		);
		$option['style-3']  = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/cat-slider-style-3.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( 'Style 3', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
			'info'  => array(
				'cat' => array(
					__( 'Large', 'publisher' ),
				),
			),
		);
		$option['style-4']  = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/cat-slider-style-4.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( 'Style 4', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
			'info'  => array(
				'cat' => array(
					__( 'Small', 'publisher' ),
				),
			),
		);
		$option['style-5']  = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/cat-slider-style-5.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( 'Style 5', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
			'info'  => array(
				'cat' => array(
					__( 'Large', 'publisher' ),
				),
			),
		);
		$option['style-6']  = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/cat-slider-style-6.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( 'Style 6', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
			'info'  => array(
				'cat' => array(
					__( 'Small', 'publisher' ),
				),
			),
		);
		$option['style-7']  = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/cat-slider-style-7.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( 'Style 7', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
			'info'  => array(
				'cat' => array(
					__( 'Large', 'publisher' ),
				),
			),
		);
		$option['style-8']  = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/cat-slider-style-8.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( 'Style 8', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
			'info'  => array(
				'cat' => array(
					__( 'Small', 'publisher' ),
				),
			),
		);
		$option['style-9']  = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/cat-slider-style-9.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( 'Style 9', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
			'info'  => array(
				'cat' => array(
					__( 'Large', 'publisher' ),
				),
			),
		);
		$option['style-10'] = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/cat-slider-style-10.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( 'Style 10', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
			'info'  => array(
				'cat' => array(
					__( 'Small', 'publisher' ),
				),
			),
		);
		$option['style-11'] = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/cat-slider-style-11.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( 'Style 11', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
			'info'  => array(
				'cat' => array(
					__( 'Large', 'publisher' ),
				),
			),
		);
		$option['style-12'] = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/cat-slider-style-12.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( 'Style 12', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
			'info'  => array(
				'cat' => array(
					__( 'Small', 'publisher' ),
				),
			),
		);
		$option['style-13'] = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/cat-slider-style-13.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( 'Style 13', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
			'info'  => array(
				'cat' => array(
					__( 'Large', 'publisher' ),
				),
			),
		);
		$option['style-14'] = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/cat-slider-style-14.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( 'Style 14', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
			'info'  => array(
				'cat' => array(
					__( 'Small', 'publisher' ),
				),
			),
		);
		$option['style-15'] = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/cat-slider-style-15.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( 'Style 15', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
			'info'  => array(
				'cat' => array(
					__( 'Large', 'publisher' ),
				),
			),
		);
		$option['style-16'] = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/cat-slider-style-16.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( 'Style 16', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
			'info'  => array(
				'cat' => array(
					__( 'Small', 'publisher' ),
				),
			),
		);
		$option['style-17'] = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/cat-slider-style-17.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( 'Style 17', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
			'info'  => array(
				'cat' => array(
					__( 'Large', 'publisher' ),
				),
			),
		);
		$option['style-18'] = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/cat-slider-style-18.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( 'Style 18', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
			'info'  => array(
				'cat' => array(
					__( 'Small', 'publisher' ),
				),
			),
		);
		$option['style-19'] = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/cat-slider-style-19.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( 'Style 19', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
			'info'  => array(
				'cat' => array(
					__( 'Large', 'publisher' ),
				),
			),
		);
		$option['style-20'] = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/cat-slider-style-20.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( 'Style 20', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
			'info'  => array(
				'cat' => array(
					__( 'Large', 'publisher' ),
				),
			),
		);
		$option['style-21'] = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/cat-slider-style-21.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( 'Style 21', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
			'info'  => array(
				'cat' => array(
					__( 'Large', 'publisher' ),
				),
			),
		);
		$option['style-22'] = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/cat-slider-style-22.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( 'Style 22', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
			'info'  => array(
				'cat' => array(
					__( 'Large', 'publisher' ),
				),
			),
		);
		$option['style-23'] = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/cat-slider-style-23.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( 'Style 23', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
			'info'  => array(
				'cat' => array(
					__( 'Small', 'publisher' ),
				),
			),
		);

		return $option;
	} // publisher_topposts_option_list
} // if


if ( ! function_exists( 'publisher_is_valid_topposts_style' ) ) {
	/**
	 * Check the parameter is theme valid topposts style
	 *
	 * @param $layout
	 *
	 * @return bool
	 */
	function publisher_is_valid_topposts_style( $layout ) {

		return array_key_exists( $layout, publisher_topposts_option_list() );
	} // publisher_is_valid_topposts_style
} // if


if ( ! function_exists( 'publisher_slider_types_option_list' ) ) {
	/**
	 * Panels category slider field options
	 *
	 * @param bool $default
	 *
	 * @return array
	 */
	function publisher_slider_types_option_list( $default = FALSE ) {

		$option = array();

		if ( $default ) {
			$option['default'] = __( '-- Default --', 'publisher' );
		}

		$option['disable']       = __( 'Disabled', 'publisher' );
		$option['custom-blocks'] = __( 'Top posts', 'publisher' );
		$option['rev_slider']    = __( 'Slider Revolution', 'publisher' );

		return $option;
	} // publisher_slider_types_option_list
} // if


if ( ! function_exists( 'publisher_is_valid_slider_type' ) ) {
	/**
	 * Check the parameter is theme valid slider type
	 *
	 * @param $layout
	 *
	 * @return bool
	 */
	function publisher_is_valid_slider_type( $layout ) {

		return ( is_string( $layout ) || is_int( $layout ) ) &&
		       array_key_exists( $layout, publisher_slider_types_option_list() );
	} // publisher_is_valid_slider_type
} // if


if ( ! function_exists( 'publisher_get_header_style' ) ) {
	/**
	 * Used to get current page header style
	 *
	 * @return bool|mixed|null|string
	 */
	function publisher_get_header_style() {

		static $style;

		if ( $style ) {
			return $style;
		}

		$style = 'default';

		if ( publisher_is_valid_tax( 'category' ) ) {
			$style = bf_get_term_meta( 'header_style' );
		} elseif ( publisher_is_valid_cpt() ) {

			$style = bf_get_post_meta( 'header_style' );

			// default -> Retrieve from parent category
			if ( $style === 'default' || ! publisher_is_valid_header_style( $style ) ) {

				$main_term = publisher_get_post_primary_cat();

				if ( ! is_wp_error( $main_term ) && is_object( $main_term ) && bf_get_term_meta( 'override_in_posts', $main_term ) ) {
					$style = bf_get_term_meta( 'header_style', $main_term );
				}
			}

		}// WooCommerce
		elseif ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {

			if ( is_shop() ) {
				$style = bf_get_post_meta( 'header_style', wc_get_page_id( 'shop' ) );
			} elseif ( is_product() ) {
				$style = bf_get_post_meta( 'header_style', get_the_ID() );
			} elseif ( is_cart() ) {
				$style = bf_get_post_meta( 'header_style', wc_get_page_id( 'cart' ) );
			} elseif ( is_checkout() ) {
				$style = bf_get_post_meta( 'header_style', wc_get_page_id( 'checkout' ) );
			} elseif ( is_account_page() ) {
				$style = bf_get_post_meta( 'header_style', wc_get_page_id( 'myaccount' ) );
			} elseif ( is_product_category() || is_product_tag() ) {
				$style = bf_get_term_meta( 'header_style', get_queried_object()->term_id );
			}

		}

		if ( $style === 'default' || ! publisher_is_valid_header_style( $style ) ) {
			$style = publisher_get_option( 'header_style' );
		}

		return $style;

	} // publisher_get_header_style
} // if


if ( ! function_exists( 'publisher_is_valid_header_style' ) ) {
	/**
	 * Check the parameter is theme valid layout or not!
	 *
	 * This is because of multiple theme that have same header_style id for page headers
	 *
	 * @param $layout
	 *
	 * @return bool
	 */
	function publisher_is_valid_header_style( $layout ) {

		return ( is_string( $layout ) || is_int( $layout ) ) &&
		       array_key_exists( $layout, publisher_header_style_option_list() );
	} // publisher_is_valid_header_style
} // if


if ( ! function_exists( 'publisher_get_header_layout' ) ) {
	/**
	 * Returns header layout for current page
	 *
	 * @return bool
	 */
	function publisher_get_header_layout() {

		// Return from cache
		if ( publisher_get_global( 'header-layout' ) ) {
			return publisher_get_global( 'header-layout' );
		}

		$layout = 'default';

		if ( publisher_is_valid_tax() ) {
			$layout = bf_get_term_meta( 'header_layout' );
		} elseif ( publisher_is_valid_cpt() ) {

			$layout = bf_get_post_meta( 'header_layout' );

			// default -> Retrieve from parent category
			if ( $layout === 'default' ) {

				$main_term = publisher_get_post_primary_cat();

				if ( ! is_wp_error( $main_term ) && is_object( $main_term ) && bf_get_term_meta( 'override_in_posts', $main_term ) ) {
					$layout = bf_get_term_meta( 'header_layout', $main_term );
				}
			}
		}

		if ( $layout === 'default' ) {
			$layout = publisher_get_option( 'header_layout' );
		}

		// Cache
		publisher_set_global( 'header-layout', $layout );

		return $layout;

	} // publisher_get_header_layout
}


if ( ! function_exists( 'publisher_get_header_layout_class' ) ) {
	/**
	 * Returns header layout class for current page
	 *
	 * @return bool
	 */
	function publisher_get_header_layout_class() {

		static $class;

		if ( $class ) {
			return $class;
		}

		$class = publisher_get_header_layout();

		$_check = array(
			'boxed'          => 'boxed',
			'full-width'     => 'full-width',
			'stretched'      => 'full-width stretched',
			'out-full-width' => 'full-width',
			'out-stretched'  => 'full-width stretched',
		);

		$class = $_check[ $class ];
		unset( $_check );

		return $class;

	} // publisher_get_header_layout_class
}


// Add filter for VC elements add-on
add_filter( 'better-framework/shortcodes/title', 'publisher_bf_shortcodes_title' );

if ( ! function_exists( 'publisher_bf_shortcodes_title' ) ) {
	/**
	 * Filter For Generating BetterFramework Shortcodes Title
	 *
	 * @param $atts
	 *
	 * @return mixed
	 */
	function publisher_bf_shortcodes_title( $atts ) {

		// Icon
		if ( ! empty( $atts['icon'] ) ) {
			$icon = bf_get_icon_tag( $atts['icon'] ) . ' ';
		} else {
			$icon = '';
		}

		// Title link
		if ( ! empty( $atts['title_link'] ) ) {
			$link = $atts['title_link'];
		} elseif ( ! empty( $atts['category'] ) ) {
			$link = get_category_link( $atts['category'] );
			if ( empty( $atts['title'] ) ) {
				$cat           = get_category( $atts['category'] );
				$atts['title'] = $cat->name;
			}
		} elseif ( ! empty( $atts['tag'] ) ) {
			$link = get_tag_link( $atts['tag'] );
			if ( empty( $atts['title'] ) ) {
				$tag           = get_tag( $atts['tag'] );
				$atts['title'] = $tag->name;
			}
		} else {
			$link = '';
		}

		if ( empty( $atts['title'] ) ) {
			$atts['title'] = publisher_translation_get( 'recent_posts' );
		}

		// Change title tag to p for adding more priority to content heading tags.
		$tag = 'h3';
		if ( bf_get_current_sidebar() ) {
			$tag = 'p';
		}

		$heading_style = publisher_get_heading_style( $atts );

		// Add SVG files for t6-s11 style
		if ( $heading_style === 't6-s11' ) {
			$atts = publisher_sh_t6_s11_fix( $atts );
		}

		?>
		<<?php echo $tag; ?> class="section-heading <?php echo publisher_get_block_heading_class( $heading_style ); ?>">
		<?php if ( ! empty( $link ) ) { ?>
			<a href="<?php echo esc_url( $link ); ?>">
		<?php } ?>
		<span class="h-text"><?php echo $icon . $atts['title']; // $icon escaped before ?></span>
		<?php if ( ! empty( $link ) ) { ?>
			</a>
		<?php } ?>
		</<?php echo $tag; ?>>
		<?php
	}
} // if


if ( ! function_exists( 'publisher_block_create_query_args' ) ) {
	/**
	 * Handy function to create master listing query args
	 *
	 * todo remove this!
	 *
	 * @param $$atts
	 *
	 * @return bool
	 */
	function publisher_block_create_query_args( &$atts ) {

		$args = array(
			'post_type' => array( 'post' ),
			'order'     => $atts['order'],
			'orderby'   => $atts['order_by'],
		);

		// Category
		if ( ! empty( $atts['category'] ) ) {
			$args['cat'] = $atts['category'];
		}

		// Tag
		if ( $atts['tag'] ) {
			$args['tag__and'] = explode( ',', $atts['tag'] );
		}

		// Post id filters
		if ( ! empty( $atts['post_ids'] ) ) {

			$post_id_array = explode( ',', $atts['post_ids'] );
			$post_in       = array();
			$post_not_in   = array();

			// Split ids into post_in and post_not_in
			foreach ( $post_id_array as $post_id ) {

				$post_id = trim( $post_id );

				if ( is_numeric( $post_id ) ) {
					if ( intval( $post_id ) < 0 ) {
						$post_not_in[] = str_replace( '-', '', $post_id );
					} else {
						$post_in[] = $post_id;
					}
				}
			}

			if ( ! empty( $post_not_in ) ) {
				$wp_query_args['post__not_in'] = $post_not_in;
			}

			if ( ! empty( $post_in ) ) {
				$args['post__in'] = $post_in;
				$args['orderby']  = 'post__in';
			}
		}


		// Custom post types
		if ( $atts['post_type'] ) {
			$args['post_type'] = explode( ',', $atts['post_type'] );
		}

		if ( ! empty( $atts['count'] ) && intval( $atts['count'] ) > 0 ) {
			$args['posts_per_page'] = $atts['count'];
		} else {
			switch ( $atts['style'] ) {

				//
				// Grid Listing
				//
				case 'listing-grid':

					switch ( $atts['columns'] ) {

						case 1:
							$args['posts_per_page'] = 4;
							break;

						case 2:
							$args['posts_per_page'] = 4;
							break;

						case 3:
							$args['posts_per_page'] = 6;
							break;

						case 4:
							$args['posts_per_page'] = 8;
							break;

						default:
							$args['posts_per_page'] = 6;
							break;

					}
					break;

				//
				// Thumbnail Listing 1
				//
				case 'listing-thumbnail-1':
					switch ( $atts['columns'] ) {

						case 1:
							$args['posts_per_page'] = 4;
							break;

						case 2:
							$args['posts_per_page'] = 6;
							break;

						case 3:
							$args['posts_per_page'] = 9;
							break;

						case 4:
							$args['posts_per_page'] = 12;
							break;

						default:
							$args['posts_per_page'] = 6;
							break;
					}
					break;

				//
				// Thumbnail Listing 2
				//
				case 'listing-thumbnail-2':
					$args['posts_per_page'] = 4;
					break;


				//
				// Blog Listing
				//
				case 'listing-blog':
					switch ( $atts['columns'] ) {

						case 1:
							$args['posts_per_page'] = 4;
							break;

						case 2:
							$args['posts_per_page'] = 6;
							break;

						case 3:
							$args['posts_per_page'] = 9;
							break;

						case 4:
							$args['posts_per_page'] = 12;
							break;


						default:
							$args['posts_per_page'] = 6;
							break;
					}
					break;


				//
				// mix Listing
				//
				case 'listing-mix-1-1':
					$args['posts_per_page'] = 5;
					break;
				case 'listing-mix-1-2':
					$args['posts_per_page'] = 5;
					break;
				case 'listing-mix-1-3':
					$args['posts_per_page'] = 7;
					break;
				case 'listing-mix-2-1':
					$args['posts_per_page'] = 8;
					break;
				case 'listing-mix-2-2':
					$args['posts_per_page'] = 10;
					break;
				case 'listing-mix-3-1':
					$args['posts_per_page'] = 4;
					break;
				case 'listing-mix-3-2':
					$args['posts_per_page'] = 5;
					break;
				case 'listing-mix-3-3':
					$args['posts_per_page'] = 5;
					break;


				//
				// Text Listing 1
				//
				case 'listing-text-1':
					switch ( $atts['columns'] ) {

						case 1:
							$args['posts_per_page'] = 3;
							break;

						case 2:
							$args['posts_per_page'] = 6;
							break;

						case 3:
							$args['posts_per_page'] = 9;
							break;

						case 4:
							$args['posts_per_page'] = 12;
							break;

						default:
							$args['posts_per_page'] = 3;
							break;
					}
					break;

				//
				// Text Listing 2
				//
				case 'listing-text-2':
					switch ( $atts['columns'] ) {

						case 1:
							$args['posts_per_page'] = 4;
							break;

						case 2:
							$args['posts_per_page'] = 8;
							break;

						case 3:
							$args['posts_per_page'] = 12;
							break;

						case 4:
							$args['posts_per_page'] = 16;
							break;

						default:
							$args['posts_per_page'] = 4;
							break;
					}
					break;


				//
				// Modern Grid Listing
				//
				case 'modern-grid-listing-1':
					$args['posts_per_page'] = 4;
					break;

				case 'modern-grid-listing-2':
					$args['posts_per_page'] = 5;
					break;

				case 'modern-grid-listing-3':
					$args['posts_per_page'] = 3;
					break;


				default:
					$args['posts_per_page'] = 6;
			}
		}


		/*

		compatibility for better reviews

		if( $atts['order_by'] === 'reviews' ){
			$args['orderby'] = 'date';
			$args['meta_key'] = '_bs_review_enabled';
			$args['meta_value'] = '1';
		}

		*/

		// Order by views count
		if ( $atts['order_by'] === 'views' ) {
			$args['meta_key'] = 'better-views-count';
			$args['orderby']  = 'meta_value_num';
		}

		// Time filter
		if ( $atts['time_filter'] != '' ) {
			$args['date_query'] = publisher_get_time_filter_query( $atts['time_filter'] );
		}

		return $args;
	}
}


if ( ! function_exists( 'publisher_block_create_tabs' ) ) {
	/**
	 * Handy function to create master listing tabs
	 *
	 * @param $atts
	 *
	 * todo check time filter and order by
	 *
	 * @return array
	 */
	function publisher_block_create_tabs( &$atts ) {

		// 1. collect all tabs array
		// 2. chose to be tab or single column
		// 3. print it
		$tabs = array();

		$active = TRUE; // flag to identify the main tab

		$main_cat = FALSE;

		//
		// First tab ( main )
		//
		if ( ! empty( $atts['query-main-term'] ) ) {
			$main_cat = $atts['query-main-term'];
		} elseif ( ! empty( $atts['category'] ) ) {
			$main_cat = $atts['category'];
		}

		if ( $main_cat ) {

			$cat = get_category( $main_cat );

			// is valid category
			if ( $cat && ! is_wp_error( $cat ) ) {

				if ( empty( $atts['title'] ) ) {
					$atts['title'] = $cat->name;
				}

				// Icon
				if ( ! empty( $atts['icon'] ) ) {
					$icon = bf_get_icon_tag( $atts['icon'] ) . ' ';
				} else {
					$icon = '';
				}

				$tabs[] = array(
					'title'   => $atts['title'],
					'link'    => get_category_link( $cat ),
					'type'    => 'category',
					'term_id' => $main_cat,
					'id'      => 'tab-' . mt_rand(),
					'icon'    => $icon,
					'class'   => 'main-term-' . $main_cat,
					'active'  => $active,
				);

				$active = FALSE; // only one active
			}

		} elseif ( ! empty( $atts['tag'] ) ) {

			$tags = explode( ',', $atts['tag'] );

			$tag = FALSE;

			foreach ( $tags as $_tag ) {
				$tag = get_tag( $_tag );
				if ( $tag && ! is_wp_error( $tag ) ) {
					break;
				}
			}

			if ( $tag && ! is_wp_error( $tag ) ) {

				if ( empty( $atts['title'] ) ) {
					$atts['title'] = $tag->name;
				}

				// Icon
				if ( ! empty( $atts['icon'] ) ) {
					$icon = bf_get_icon_tag( $atts['icon'] ) . ' ';
				} else {
					$icon = '';
				}

				$tabs[] = array(
					'title'   => $atts['title'],
					'link'    => get_tag_link( $tag->term_id ),
					'type'    => 'tag',
					'term_id' => $tag->term_id,
					'id'      => 'tab-' . mt_rand(),
					'icon'    => $icon,
					'class'   => 'main-term-none',
					'active'  => $active,
				);

				$active = FALSE; // only one active

			}
		} elseif ( ! empty( $atts['taxonomy'] ) ) {

			$tax = explode( ':', current( explode( ',', $atts['taxonomy'] ) ) );

			if ( count( $tax ) >= 2 ) {
				$tax_term = get_term( $tax[1], $tax[0] );

				if ( ! is_wp_error( $tax_term ) ) {

					if ( empty( $atts['title'] ) ) {
						$atts['title'] = $tax_term->name;
					}

					// Icon
					if ( ! empty( $atts['icon'] ) ) {
						$icon = bf_get_icon_tag( $atts['icon'] ) . ' ';
					} else {
						$icon = '';
					}

					$tabs[] = array(
						'title'   => $atts['title'],
						'link'    => get_tag_link( $tax_term ),
						'type'    => 'taxonomy',
						'term_id' => $tax_term->term_id,
						'id'      => 'tab-' . mt_rand(),
						'icon'    => $icon,
						'class'   => 'main-term-none',
						'active'  => $active,
					);

					$active = FALSE; // only one active
				}
			}
		}


		// Default tab for fallback
		if ( $active ) {
			$tabs[] = publisher_block_create_tabs_default_tab( $atts, $active );
			$active = FALSE;
		}

		// not return other tabs if they will not shown!
		if ( ( ! empty( $atts['hide_title'] ) && $atts['hide_title'] ) ||
		     ( ! empty( $atts['show_title'] ) && ! $atts['show_title'] )
		) {
			return $tabs;
		}

		//
		// Other Tabs
		//
		if ( isset( $atts['tabs'] ) && ! empty( $atts['tabs'] ) ) {

			$terms = array();
			switch ( $atts['tabs'] ) {

				//
				// Category tabs
				//
				case 'cat_filter':

					if ( empty( $atts['tabs_cat_filter'] ) ) {
						break;
					} elseif ( is_string( $atts['tabs_cat_filter'] ) ) {
						$atts['tabs_cat_filter'] = explode( ',', $atts['tabs_cat_filter'] );
					}

					$terms = get_categories( array( 'include' => $atts['tabs_cat_filter'] ) );

					break;

				case 'sub_cat_filter':

					if ( $main_cat ) {
						$terms = get_categories( array( 'child_of' => $main_cat, 'number' => 20 ) );
					}

					break;

				case 'tax_filter':

					if ( ! empty( $atts['tabs_tax_filter'] ) ) {

						if ( preg_match_all( '/ (\w+) \s* : \s*  ([^,]+)/isx', $atts['tabs_tax_filter'], $matches ) ) {

							$_all_terms = array();
							foreach ( $matches[1] as $idx => $taxonomy ) {
								$term_id = $matches[2][ $idx ];
								$section = $term_id[0] === '-' ? 'exclude' : 'include';

								$_all_terms[ $taxonomy ][ $section ][] = absint( $term_id );
							}

							foreach ( $_all_terms as $taxonomy => $_terms ) {

								$terms_id_include = isset( $_terms['include'] ) ? $_terms['include'] : array();
								$terms_id_exclude = isset( $_terms['exclude'] ) ? $_terms['exclude'] : array();


								$terms = array_merge(
									$terms,
									get_terms(
										array(
											'include' => bf_get_term_childs( $terms_id_include, $terms_id_exclude, $taxonomy )
										)
									)
								);
							}
						}

					}

					break;
			}

			foreach ( $terms as $term ) {

				$tabs[] = array(
					'title'   => $term->name,
					'link'    => get_term_link( $term ),
					'type'    => 'category',
					'term_id' => $term->term_id,
					'id'      => 'tab-' . mt_rand(),
					'icon'    => '',
					'class'   => 'main-term-' . $term->term_id,
					'active'  => $active,
				);

				// only one active
				if ( $active ) {
					$active = FALSE;
				}
			}

		}

		return $tabs;
	} // publisher_block_create_tabs
}

if ( ! function_exists( 'publisher_block_create_tabs_default_tab' ) ) {
	/**
	 * Handy internal function to get default tab from atts
	 *
	 * @param      $atts
	 * @param bool $active
	 *
	 * @return array
	 */
	function publisher_block_create_tabs_default_tab( &$atts, $active = TRUE ) {

		if ( empty( $atts['title'] ) ) {
			$atts['title'] = publisher_translation_get( 'recent_posts' );
		}

		// Icon
		if ( ! empty( $atts['icon'] ) ) {
			$icon = bf_get_icon_tag( $atts['icon'] ) . ' ';
		} else {
			$icon = '';
		}

		return array(
			'title'   => $atts['title'],
			'link'    => '',
			'type'    => 'custom',
			'term_id' => '',
			'id'      => 'tab-' . mt_rand(),
			'icon'    => $icon,
			'class'   => 'main-term-none',
			'active'  => $active,
		);

	}
}

if ( ! function_exists( 'publisher_block_the_heading' ) ) {
	/**
	 * Handy function to create master listing tabs
	 *
	 * @param   $tabs
	 * @param   $multi_tab
	 *
	 * @return  bool
	 */
	function publisher_block_the_heading( &$atts, &$tabs, $multi_tab = FALSE ) {

		$show_title = TRUE;

		if ( ! Better_Framework::widget_manager()->get_current_sidebar() ) {

			if ( ! empty( $atts['hide_title'] ) && $atts['hide_title'] ) {
				$show_title = FALSE;
			}

			if ( ! empty( $atts['show_title'] ) && ! $atts['show_title'] ) {
				$show_title = FALSE;
			}

		}

		if ( $show_title ) {

			// Change title tag to p for adding more priority to content heading tags.
			$tag = 'h3';
			if ( bf_get_current_sidebar() ) {
				$tag = 'p';
			}


			$main_tab_class = '';
			if ( $multi_tab && ! empty( $tabs[0]['class'] ) ) {
				$main_tab_class = 'mtab-' . $tabs[0]['class'] . ' ';
			}

			$heading_style = publisher_get_heading_style( $atts );

			if ( $heading_style === 't6-s11' ) {
				$tabs[0] = publisher_sh_t6_s11_fix( $tabs[0] );
			}

			?>
			<<?php echo $tag; ?> class="section-heading <?php echo publisher_get_block_heading_class( $heading_style ), ' ', $main_tab_class;

			echo esc_attr( $tabs[0]['class'] );

			if ( ! empty( $atts['deferred_load_tabs'] ) ) {
				echo esc_attr( ' bs-deferred-tabs' );
			}

			if ( $multi_tab ) {
				echo esc_attr( ' multi-tab' );
			}

			?>">

			<?php if ( ! $multi_tab ) { ?>

				<?php if ( ! empty( $tabs[0]['link'] ) ) { ?>
					<a href="<?php echo esc_url( $tabs[0]['link'] ); ?>" class="main-link">
							<span class="h-text <?php echo esc_attr( $tabs[0]['class'] ); ?>">
								<?php echo $tabs[0]['icon'], $tabs[0]['title']; // icon escaped before ?>
							</span>
					</a>
				<?php } else { ?>
					<span class="h-text <?php echo esc_attr( $tabs[0]['class'] ); ?> main-link">
						<?php echo $tabs[0]['icon'], $tabs[0]['title']; // icon escaped before ?>
					</span>
				<?php } ?>

			<?php } else {

				foreach ( (array) $tabs as $tab ) { ?>
					<a href="#<?php echo esc_attr( $tab['id'] ) ?>" data-toggle="tab"
					   aria-expanded="<?php echo $tab['active'] ? 'true' : 'false'; ?>"
					   class="<?php echo $tab['active'] ? 'main-link active' : 'other-link'; ?>"
						<?php if ( isset( $tab['data'] ) ) {
							foreach ( $tab['data'] as $key => $value ) {
								printf( ' data-%s="%s"', sanitize_key( $key ), esc_attr( $value ) );
							}
						} ?>
					>
							<span class="h-text <?php echo esc_attr( $tab['class'] ); ?>">
								<?php echo $tab['icon'] . $tab['title']; // icon escaped before ?>
							</span>
					</a>
				<?php }


			} ?>

			</<?php echo $tag; ?>>
			<?php

		}


	}// publisher_block_the_heading
}


add_filter( 'wpb_widget_title', 'publisher_vc_block_the_heading', 100, 2 );

if ( ! function_exists( 'publisher_vc_block_the_heading' ) ) {
	/**
	 * Handy function to customize VC blocks headings
	 *
	 *
	 * @return string
	 */
	function publisher_vc_block_the_heading( $output = '', $atts = array() ) {

		if ( empty( $atts['title'] ) ) {
			return $output;
		}

		$class = '';

		if ( ! empty( $atts['extraclass'] ) ) {
			$class = $atts['extraclass'];
		}

		// Change title tag to p for adding more priority to content heading tags.
		$tag = 'h3';
		if ( bf_get_current_sidebar() ) {
			$tag = 'p';
		}

		// Current customized heading style or read from panel!
		{
			$heading_style = 'default';

			$_check = array(
				''        => '',
				'default' => '',
			);

			if ( ! empty( $atts['heading_style'] ) ) {
				$heading_style = $atts['heading_style'];
			} elseif ( ! empty( $atts['bf-widget-title-style'] ) ) {
				$heading_style = $atts['bf-widget-title-style'];
			} elseif ( bf_get_current_sidebar() ) {

				$_check_2 = array(
					'footer-1' => '',
					'footer-2' => '',
					'footer-3' => '',
					'footer-4' => '',
				);

				if ( isset( $_check_2[ bf_get_current_sidebar() ] ) ) {
					$heading_style = publisher_get_option( 'footer_widgets_heading_style' );
				}

				if ( isset( $_check[ $heading_style ] ) ) {
					$heading_style = publisher_get_option( 'widgets_heading_style' );
				}
			}

			if ( isset( $_check[ $heading_style ] ) ) {
				$heading_style = publisher_get_option( 'section_heading_style' );
			}
		}

		$class .= ' ' . publisher_get_block_heading_class( $heading_style );

		// Add SVG files for t6-s11 style
		if ( $heading_style === 't6-s11' ) {
			$atts = publisher_sh_t6_s11_fix( $atts );
		}

		return "<{$tag} class='section-heading {$class}'>
			<span class='h-text main-link'>{$atts['title']}</span>
		</{$tag}>";

	}// publisher_block_the_heading
}

add_filter( 'vc_shortcodes_css_class', 'publisher_vc_block_class', 100, 2 );

if ( ! function_exists( 'publisher_vc_block_class' ) ) {
	/**
	 * Handy function to customize VC blocks classes
	 *
	 * @return string
	 */
	function publisher_vc_block_class( $class = '', $base = '', $atts = array() ) {

		$_check = array(
			'vc_gmaps'              => '',
			'vc_column_text'        => '',
			'vc_toggle'             => '',
			'vc_gallery'            => '',
			'vc_images_carousel'    => '',
			'vc_posts_slider'       => '',
			'vc_progress_bar'       => '',
			'vc_pie'                => '',
			'vc_round_chart'        => '',
			'vc_line_chart'         => '',
			'vc_media_grid'         => '',
			'vc_masonry_media_grid' => '',
		);

		if ( isset( $_check[ $base ] ) ) {
			$class .= ' bs-vc-block';
		}

		return $class;
	}// publisher_block_the_heading
}


if ( ! function_exists( 'publisher_format_icon' ) ) {
	/**
	 * Handy function used to get post format badge
	 *
	 * @param   bool $echo Echo or return
	 *
	 * @return string
	 */
	function publisher_format_icon( $echo = TRUE ) {

		$output = '';

		if ( get_post_type() === 'post' ) {

			$format = publisher_get_post_format();

			if ( $format ) {

				switch ( $format ) {

					case 'video':
						$output = '<span class="format-icon format-' . $format . '"><i class="fa fa-play"></i></span>';
						break;

					case 'aside':
						$output = '<span class="format-icon format-' . $format . '"><i class="fa fa-pencil"></i></span>';
						break;

					case 'quote':
						$output = '<span class="format-icon format-' . $format . '"><i class="fa fa-quote-left"></i></span>';
						break;

					case 'gallery':
					case 'image':
						$output = '<span class="format-icon format-' . $format . '"><i class="fa fa-camera"></i></span>';
						break;

					case 'status':
						$output = '<span class="format-icon format-' . $format . '"><i class="fa fa-refresh"></i></span>';
						break;

					case 'audio':
						$output = '<span class="format-icon format-' . $format . '"><i class="fa fa-music"></i></span>';
						break;

					case 'chat':
						$output = '<span class="format-icon format-' . $format . '"><i class="fa fa-coffee"></i></span>';
						break;

					case 'link':
						$output = '<span class="format-icon format-' . $format . '"><i class="fa fa-link"></i></span>';
						break;

				}

			}

		}

		if ( $echo ) {
			echo $output; // escaped before
		} else {
			return $output;
		}

	} // publisher_format_badge_code
} // if


if ( ! function_exists( 'publisher_get_links_pagination' ) ) {
	/**
	 * @param array $options
	 *
	 * @return string
	 */
	function publisher_get_links_pagination( $options = array() ) {

		// Default Options
		$default_options = array(
			'echo' => TRUE,
		);

		// Texts with RTL support
		if ( is_rtl() ) {
			$default_options['older-text'] = '<i class="fa fa-angle-double-right"></i> ' . publisher_translation_get( 'pagination_newer' );
			$default_options['next-text']  = publisher_translation_get( 'pagination_older' ) . ' <i class="fa fa-angle-double-left"></i>';
		} else {
			$default_options['next-text']  = '<i class="fa fa-angle-double-left"></i> ' . publisher_translation_get( 'pagination_older' );
			$default_options['older-text'] = publisher_translation_get( 'pagination_newer' ) . ' <i class="fa fa-angle-double-right"></i>';
		}

		// Merge default and passed options
		$options = bf_merge_args( $options, $default_options );

		if ( ! $options['echo'] ) {
			ob_start();
		}

		// fix category posts link because of offset
		if ( publisher_is_valid_tax() ) {
			$term_id       = get_queried_object()->term_id;
			$count         = bf_get_term_posts_count( $term_id, array( 'include_childs' => TRUE ) );
			$limit         = get_option( 'posts_per_page' );
			$slider_config = publisher_main_slider_config();

			// Custom count per category
			if ( bf_get_term_meta( 'term_posts_count', get_queried_object()->term_id, '' ) != '' ) {
				$limit = bf_get_term_meta( 'term_posts_count', get_queried_object()->term_id, '' );
			} // Custom count for all categories
			elseif ( publisher_get_option( 'cat_posts_count' ) != '' && intval( publisher_get_option( 'cat_posts_count' ) ) > 0 ) {
				$limit = publisher_get_option( 'cat_posts_count' );
			}

			if ( $slider_config['show'] && $slider_config['type'] === 'custom-blocks' ) {
				$max_items = ceil( ( $count - intval( $slider_config['posts'] ) ) / $limit );
			} else {
				$max_items = publisher_get_query()->max_num_pages;
			}

		} else {
			$max_items = publisher_get_query()->max_num_pages;
		}

		$paginated_front_page = ( 'page' === get_option( 'show_on_front' ) ) && is_front_page() && bf_get_query_var_paged( 1 ) > 1;

		// Change global $paged value to fix next_posts_link issue in static paginated homepages
		if ( $paginated_front_page ) {
			global $paged;
			$paged_c = $paged;
			$paged   = bf_get_query_var_paged();
		}

		if ( $max_items > 1 ) {

			add_filter( 'next_posts_link_attributes', 'publisher_filter_pagination_link_attr' );
			add_filter( 'previous_posts_link_attributes', 'publisher_filter_pagination_link_attr' );

			?>
			<div <?php publisher_attr( 'pagination', 'bs-links-pagination clearfix' ) ?>>
				<div class="older"><?php next_posts_link( $options['next-text'], $max_items ); ?></div>
				<div class="newer"><?php previous_posts_link( $options['older-text'] ); ?></div>
			</div>
			<?php

			remove_filter( 'next_posts_link_attributes', 'publisher_filter_pagination_link_attr' );
			remove_filter( 'previous_posts_link_attributes', 'publisher_filter_pagination_link_attr' );
		}

		// return bac the global $paged value
		if ( $paginated_front_page ) {
			$paged = $paged_c;
		}

		if ( ! $options['echo'] ) {
			return ob_get_clean();
		}

	} // publisher_get_links_pagination
} // if


if ( ! function_exists( 'publisher_filter_pagination_link_attr' ) ) {
	/**
	 * Adds rel attributed to pagintion next and previous links
	 *
	 * @hooked next_posts_link_attributes
	 * @hooked previous_posts_link_attributes
	 *
	 * @param $attr
	 *
	 * @return string
	 */
	function publisher_filter_pagination_link_attr( $attr ) {

		if ( current_filter() === 'next_posts_link_attributes' ) {
			$attr .= ' rel="next"';
		} else {
			$attr .= ' rel="prev"';
		}

		return $attr;
	}
}


if ( ! function_exists( 'publisher_get_pagination' ) ) {
	/**
	 * BetterTemplate Custom Pagination
	 *
	 * @param array $options extend options for paginate_links()
	 *
	 * @return array|mixed|string
	 *
	 * @see paginate_links()
	 */
	function publisher_get_pagination( $options = array() ) {

		global $wp_rewrite;

		// Default Options
		$default_options = array(
			'echo'            => TRUE,
			'use-wp_pagenavi' => TRUE,
			'users-per-page'  => 6,
		);

		// Prepare query
		if ( publisher_get_query() != NULL ) {
			$default_options['query'] = publisher_get_query();
		} else {
			global $wp_query;
			$default_options['query'] = $wp_query;
		}


		// Merge default and passed options
		$options = bf_merge_args( $options, $default_options );


		// Texts with RTL support
		if ( ! isset( $options['next-text'] ) && ! isset( $options['prev-text'] ) ) {
			if ( is_rtl() ) {
				$options['next-text'] = publisher_translation_get( 'pagination_next' ) . ' <i class="fa fa-angle-left"></i>';
				$options['prev-text'] = '<i class="fa fa-angle-right"></i> ' . publisher_translation_get( 'pagination_prev' );
			} else {
				$options['next-text'] = publisher_translation_get( 'pagination_next' ) . ' <i class="fa fa-angle-right"></i>';
				$options['prev-text'] = ' <i class="fa fa-angle-left"></i> ' . publisher_translation_get( 'pagination_prev' );
			}
		}


		// WP-PageNavi Plugin
		if ( $options['use-wp_pagenavi'] && function_exists( 'wp_pagenavi' ) && ! is_a( $options['query'], 'WP_User_Query' ) ) {

			ob_start();

			// Use WP-PageNavi plugin to generate pagination
			wp_pagenavi(
				array(
					'query' => $options['query']
				)
			);

			$pagination = ob_get_clean();

		} // Custom Pagination With WP Functionality
		else {

			$paged = $options['query']->get( 'paged', '' ) ? $options['query']->get( 'paged', '' ) : ( $options['query']->get( 'page', '' ) ? $options['query']->get( 'page', '' ) : 1 );

			if ( is_a( $options['query'], 'WP_User_Query' ) ) {

				$offset = $options['users-per-page'] * ( $paged - 1 );

				$total_pages = ceil( $options['query']->total_users / $options['users-per-page'] );

			} else {
				$total_pages = $options['query']->max_num_pages;

				// fix category posts link because of offset
				if ( publisher_is_valid_tax() ) {
					$term_id = get_queried_object()->term_id;
					$count   = bf_get_term_posts_count( $term_id, array( 'include_childs' => TRUE ) );

					$limit         = get_option( 'posts_per_page' );
					$slider_config = publisher_main_slider_config( array(
							'type'    => 'term',
							'term_id' => $term_id
						)
					);

					// Custom count per category
					if ( bf_get_term_meta( 'term_posts_count', $term_id, '' ) != '' ) {
						$limit = bf_get_term_meta( 'term_posts_count', $term_id, '' );
					} // Custom count for all categories
					elseif ( publisher_get_option( 'cat_posts_count' ) != '' && intval( publisher_get_option( 'cat_posts_count' ) ) > 0 ) {
						$limit = publisher_get_option( 'cat_posts_count' );
					}

					if ( $slider_config['show'] && $slider_config['type'] === 'custom-blocks' ) {
						$total_pages = ceil( ( $count - intval( $slider_config['posts'] ) ) / $limit );
					}
				}

			}

			if ( $total_pages <= 1 ) {
				return '';
			}

			$args = array(
				'base'      => add_query_arg( 'paged', '%#%' ),
				'current'   => max( 1, $paged ),
				'total'     => $total_pages,
				'next_text' => $options['next-text'],
				'prev_text' => $options['prev-text']
			);

			if ( is_a( $options['query'], 'WP_User_Query' ) ) {
				$args['offset'] = $offset;
			}

			if ( $wp_rewrite->using_permalinks() ) {
				$big          = 999999999;
				$args['base'] = str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) );
			}

			if ( is_search() ) {
				$args['add_args'] = array(
					's' => urlencode( get_query_var( 's' ) )
				);
			}

			// Fix: paginate_links use max_num_pages property to detect max page number
			// and it dosen't consider query offset so it make last pages 404
			if ( $options['query'] && ( $total = bf_get_wp_query_total_pages( $options['query'] ) ) ) {
				$args['total'] = $total;
			}

			$pagination = paginate_links( array_merge( $args, $options ) );

			$pagination = preg_replace( '/&#038;paged=1(\'|")/', '\\1', trim( $pagination ) );

		}

		$pagination = '<div ' . publisher_get_attr( 'pagination', 'bs-numbered-pagination' ) . '>' . $pagination . '</div>';

		if ( $options['echo'] ) {
			echo $pagination; // escaped before
		} else {
			return $pagination;
		}

	} // publisher_get_pagination
} // if


add_filter( 'publisher/archive/before-loop', 'publisher_archive_show_pagination' );
add_filter( 'publisher/archive/after-loop', 'publisher_archive_show_pagination' );
if ( ! function_exists( 'publisher_archive_show_pagination' ) ) {
	/**
	 * used to add pagination
	 *
	 * note: do not call this manually. it should be fire with following callbacks:
	 * 1. publisher/archive/before-loop
	 * 2. publisher/archive/after-loop
	 */
	function publisher_archive_show_pagination() {

		$wp_query = publisher_get_query();

		$pagination = publisher_get_pagination_style(); // determine current page pagination (with inner cache)

		$filter = current_filter();

		// Show/Hide excerpt
		if ( $filter === 'publisher/archive/before-loop' ) {

			$_check = array(
				'listing-mix-4-1' => '',
				'listing-mix-4-2' => '',
				'listing-mix-4-3' => '',
				'listing-mix-4-4' => '',
				'listing-mix-4-5' => '',
				'listing-mix-4-6' => '',
				'listing-mix-4-7' => '',
				'listing-mix-4-8' => ''
			);

			if ( isset( $_check[ publisher_get_page_listing() ] ) ) {
				publisher_set_prop( 'show-excerpt-small', publisher_get_show_page_listing_excerpt() );
				publisher_set_prop( 'show-excerpt-big', publisher_get_show_page_listing_excerpt() );
			} else {
				publisher_set_prop( 'show-excerpt', publisher_get_show_page_listing_excerpt() );
			}

		}

		// pagination
		switch ( TRUE ) {

			case $pagination === 'numbered' && $filter === 'publisher/archive/before-loop':
				return;
				break;

			case $pagination === 'numbered' && $filter === 'publisher/archive/after-loop':
				publisher_get_pagination();

				return;
				break;

			case $pagination === 'links' && $filter === 'publisher/archive/before-loop':
				return;
				break;

			case $pagination === 'links' && $filter === 'publisher/archive/after-loop':
				publisher_get_links_pagination();

				return;
				break;

			case $pagination === 'ajax_more_btn_infinity' && $filter === 'publisher/archive/before-loop':
			case $pagination === 'ajax_infinity' && $filter === 'publisher/archive/before-loop':
			case $pagination === 'ajax_more_btn' && $filter === 'publisher/archive/before-loop':
			case $pagination === 'ajax_next_prev' && $filter === 'publisher/archive/before-loop':

				$max_num_pages = bf_get_wp_query_total_pages( $wp_query );

				// fix for when there is no more pages
				if ( $max_num_pages <= 1 ) {
					return;
				}

				// Create valid name for BS_Pagination
				$pagin_style = str_replace( 'ajax_', '', $pagination );

				$atts = array(
					'paginate'        => $pagin_style,
					'have_pagination' => TRUE,
				);

				publisher_theme_pagin_manager()->wrapper_start( $atts );

				break;

			case $pagination === 'ajax_more_btn_infinity' && $filter === 'publisher/archive/after-loop':
			case $pagination === 'ajax_infinity' && $filter === 'publisher/archive/after-loop':
			case $pagination === 'ajax_more_btn' && $filter === 'publisher/archive/after-loop':
			case $pagination === 'ajax_next_prev' && $filter === 'publisher/archive/after-loop':

				$max_num_pages = bf_get_wp_query_total_pages( $wp_query );

				// fix for when there is no more pages
				if ( $max_num_pages <= 1 ) {
					return;
				}

				// Create valid name for BS_Pagination
				$pagin_style = str_replace( 'ajax_', '', $pagination );

				$atts = array(
					'paginate'        => $pagin_style,
					'have_pagination' => TRUE,
					'show_label'      => publisher_theme_pagin_manager()->get_pagination_label( 1, $max_num_pages ),
					'next_page_link'  => next_posts( 0, FALSE ), // next page link for better SEO
					'query_vars'      => bf_get_wp_query_vars( $wp_query ),
				);


				if ( publisher_prop_is_set( 'show-excerpt' ) ) {
					$atts['show_excerpt'] = publisher_get_prop( 'show-excerpt', FALSE );
				} elseif ( publisher_prop_is_set( 'show-excerpt-big' ) ) {
					$atts['show_excerpt'] = publisher_get_prop( 'show-excerpt-big', FALSE );
				} elseif ( publisher_prop_is_set( 'show-excerpt-small' ) ) {
					$atts['show_excerpt'] = publisher_get_prop( 'show-excerpt-small', FALSE );
				}

				publisher_theme_pagin_manager()->wrapper_end();

				publisher_theme_pagin_manager()->display_pagination( $atts, $wp_query, 'Publisher::bs_pagin_ajax_archive', 'custom' );
		}

	} // publisher_archive_show_pagination
} // if


if ( ! function_exists( 'publisher_general_fix_shortcode_vc_style' ) ) {
	/**
	 * Fixes shortcode style for generated style from VC -> General fixes
	 *
	 * @param $atts
	 */
	function publisher_general_fix_shortcode_vc_style( &$atts ) {


		switch ( $atts['shortcode-id'] ) {

			case 'bs-modern-grid-listing-5':

				if ( empty( $atts['_style_bg_color'] ) ) {
					return;
				}

				$code = '.' . $atts['css-class'] . ' .listing-mg-5-item-big .content-container{ background-color:' . $atts['_style_bg_color'] . ' !important}';

				if ( ! empty( $atts['css-code'] ) ) {
					$atts['css-code'] .= $code;
				} else {
					$atts['css-code'] = $code;
				}

				break;

			// Classic Listing 3 content BG Fix
			case 'bs-classic-listing-3':
			case 'bs-mix-listing-4-7':
			case 'bs-mix-listing-4-2':
			case 'bs-mix-listing-4-1':

				if ( empty( $atts['_style_bg_color'] ) ) {
					return;
				}

				$code = '.' . $atts['css-class'] . ' .listing-item-classic-3 .featured .title{ background-color:' . $atts['_style_bg_color'] . '}';

				if ( ! empty( $_t['code'] ) ) {
					$atts['css-code'] .= $code;
				} else {
					$atts['css-code'] = $code;
				}

				break;
		}


		return; // It's for inner style!
	}
}// publisher_general_fix_shortcode_vc_style


if ( ! function_exists( 'publisher_fix_shortcode_vc_style' ) ) {
	/**
	 * Fixes shortcode style for generated style from VC
	 *
	 * @param $atts
	 */
	function publisher_fix_shortcode_vc_style( &$atts ) {

		publisher_general_fix_shortcode_vc_style( $atts ); // general fixes

		return; // It's for inner style!
	}
}// publisher_fix_shortcode_vc_style


add_filter( 'better-framework/widgets/atts', 'publisher_fix_bs_listing_vc_atts' );
add_filter( 'better-framework/shortcodes/atts', 'publisher_fix_bs_listing_vc_atts' );

if ( ! function_exists( 'publisher_fix_bs_listing_vc_atts' ) ) {
	/**
	 * Used to customize bs listing atts for VC
	 *
	 * @param $atts
	 *
	 * @return mixed
	 */
	function publisher_fix_bs_listing_vc_atts( $atts ) {


		/**
		 *
		 * Current customized heading style or read from panel!
		 *
		 */
		{
			$heading_style = 'default';

			$_check = array(
				''        => '',
				'default' => '',
			);

			if ( isset( $atts['heading_style'] ) && ! isset( $_check[ $atts['heading_style'] ] ) ) {
				$heading_style = $atts['heading_style'];
			} elseif ( isset( $atts['bf-widget-title-style'] ) && ! isset( $_check[ $atts['bf-widget-title-style'] ] ) ) {
				$heading_style = $atts['bf-widget-title-style'];
			} elseif ( bf_get_current_sidebar() ) {

				$_check_2 = array(
					'footer-1' => '',
					'footer-2' => '',
					'footer-3' => '',
					'footer-4' => '',
				);

				if ( isset( $_check_2[ bf_get_current_sidebar() ] ) ) {
					$heading_style = publisher_get_option( 'footer_widgets_heading_style' );
				}

				if ( isset( $_check[ $heading_style ] ) ) {
					$heading_style = publisher_get_option( 'widgets_heading_style' );
				}
			}

			if ( isset( $_check[ $heading_style ] ) ) {
				$heading_style = publisher_get_option( 'section_heading_style' );
			}
		}


		/**
		 *
		 * Detecting tabbed or single tab mode
		 *
		 */
		{
			$tabbed = FALSE;
			if ( ! empty( $atts['tabs'] ) ) {
				if ( $atts['tabs'] === 'cat_filter' && ! empty( $atts['tabs_cat_filter'] ) ) {
					$tabbed = TRUE;
				} elseif ( $atts['tabs'] === 'sub_cat_filter' ) {
					$tabbed = TRUE;
				} elseif ( $atts['tabs'] === 'tax_filter' && ! empty( $atts['tabs_tax_filter'] ) ) {
					$tabbed = TRUE;
				}
			}
		}


		/**
		 *
		 * Heading Custom Color
		 *
		 */
		if ( ( ! bf_get_current_sidebar() || bf_get_current_sidebar() == 'bs-vc-sidebar-column' ) && ( ! empty( $atts['heading_color'] ) || $heading_style !== publisher_get_option( 'section_heading_style' ) ) ) {

			$class = 'bscb-' . mt_rand( 10000, 100000 );

			// custom calss
			if ( empty( $atts['css-class'] ) ) {
				$atts['css-class'] = $class;
			} else {
				$atts['css-class'] = "$class {$atts['css-class']}";
			}

			//
			// Block color - or - category color - or - theme color
			//
			{
				$heading_color    = '';
				$generator_config = array(
					'type'   => 'block',
					'style'  => $heading_style,
					'tabbed' => $tabbed,
				);


				if ( ! empty( $atts['bs-text-color-scheme'] ) ) {

					$_check = array(
						'light' => '#ffffff',
						'dark'  => '#000000',
					);

					if ( isset( $_check[ $atts['bs-text-color-scheme'] ] ) ) {
						$heading_color                        = $_check[ $atts['bs-text-color-scheme'] ];
						$generator_config['fix-block-color']  = FALSE;
						$generator_config['fix-block-scheme'] = $atts['bs-text-color-scheme'];
					}
				}

				if ( empty( $heading_color ) && ! empty( $atts['heading_color'] ) ) {

					$heading_color = $atts['heading_color'];

					if ( ! empty( $heading_color ) ) {
						$atts['css-class'] .= ' bsb-have-heading-color';

					}
				}

				if ( empty( $heading_color ) && ! empty( $atts['category'] ) ) {
					$heading_color = bf_get_term_meta( 'term_color', $atts['category'] );
				}

				if ( empty( $heading_color ) ) {
					$heading_color                       = publisher_get_option( 'section_title_color' );
					$generator_config['fix-block-color'] = FALSE;
				}

				if ( empty( $heading_color ) ) {
					$heading_color                       = publisher_get_option( 'theme_color' );
					$generator_config['fix-block-color'] = FALSE;
				}
			}

			if ( ! empty( $heading_color ) ) {
				$blocks = array(
					'_BLOCK_ID' => $class,
				);

				publisher_cb_css_generator_section_heading(
					$blocks,
					$heading_color,
					$generator_config
				);

				foreach ( $blocks as $block ) {
					$_t = bf_render_css_block_array( $block, $heading_color );

					if ( empty( $_t['code'] ) ) {
						continue;
					}

					if ( ! empty( $atts['css-code'] ) ) {
						$atts['css-code'] .= $_t['code'];
					} else {
						$atts['css-code'] = $_t['code'];
					}
				}
			}
		}


		if ( ! empty( $atts['css'] ) ) {

			$atts['_style_bg_color'] = bf_shortcode_custom_css_prop( $atts['css'], 'background-color' );

			if ( ! empty( $atts['_style_bg_color'] ) ) {

				// custom calss
				if ( ! isset( $class ) ) {

					$class = 'bscb-' . mt_rand( 10000, 100000 );

					// custom calss
					if ( empty( $atts['css-class'] ) ) {
						$atts['css-class'] = $class;
					} else {
						$atts['css-class'] = "$class  {$atts['css-class']}";
					}
				}

				$blocks = array(
					'_BLOCK_ID' => $class,
				);


				publisher_cb_css_generator_section_heading(
					$blocks,
					$atts['_style_bg_color'],
					array(
						'type'    => 'block',
						'style'   => $heading_style,
						'tabbed'  => $tabbed,
						'section' => 'bg_fix',
					)
				);

				foreach ( $blocks as $block ) {
					$_t = bf_render_css_block_array( $block, $atts['_style_bg_color'] );

					if ( empty( $_t['code'] ) ) {
						continue;
					}

					if ( ! empty( $atts['css-code'] ) ) {
						$atts['css-code'] .= $_t['code'];
					} else {
						$atts['css-code'] = $_t['code'];
					}
				}
			}

			publisher_fix_shortcode_vc_style( $atts );

			if ( ! empty( $atts['_style_bg_color'] ) ) {
				$atts['css-class'] .= ' have_bg';
			}
		}


		return $atts;
	}
}


if ( ! function_exists( 'publisher_is_valid_cpt' ) ) {
	/**
	 * Handy function to detect current post is valid post type for post options or not!
	 *
	 * @param string $type
	 *
	 * @since 1.7
	 * @return bool
	 */
	function publisher_is_valid_cpt( $type = 'both' ) {

		if ( ! is_singular() ) {
			return FALSE;
		}

		static $valid;

		if ( ! is_null( $valid ) && isset( $valid[ $type ] ) ) {
			return $valid[ $type ];
		}

		if ( publisher_get_option( 'advanced_post_options_types' ) ) {
			$post_types = array_flip( explode( ',', publisher_get_option( 'advanced_post_options_types' ) ) );
		} else {
			$post_types = array();
		}

		if ( $type === 'both' ) {
			$post_types['post'] = '';
			$post_types['page'] = '';
		} elseif ( $type === 'post' ) {
			$post_types['post'] = '';
		} elseif ( $type === 'page' ) {
			$post_types['page'] = '';
		}

		return $valid[ $type ] = isset( $post_types[ get_post_type() ] );

	}
}


if ( ! function_exists( 'publisher_is_valid_tax' ) ) {
	/**
	 * Handy function to detect current post is valid taxonomy for category options or not!
	 *
	 * @param string $type
	 * @param bool   $queried_object
	 *
	 * @since 1.7
	 * @return bool
	 */
	function publisher_is_valid_tax( $type = 'category', $queried_object = FALSE ) {

		static $valid;

		if ( ! is_null( $valid ) && isset( $valid[ $type ] ) ) {
			return $valid[ $type ];
		}

		if ( $type === 'category' ) {

			if ( ! $queried_object ) {
				if ( is_category() ) {
					return $valid[ $type ] = TRUE;
				} elseif ( ! is_tax() ) {
					return $valid[ $type ] = FALSE;
				}
			}

			if ( publisher_get_option( 'advanced_category_options_tax' ) ) {
				$taxonomies = array_flip( explode( ',', publisher_get_option( 'advanced_category_options_tax' ) ) );
			}

		} elseif ( $type === 'tag' ) {

			$type = 'post_tag'; // Tag taxonomy

			if ( ! $queried_object ) {
				if ( is_tag() ) {
					return $valid[ $type ] = TRUE;
				} elseif ( ! is_tax() ) {
					return $valid[ $type ] = FALSE;
				}
			}

			if ( publisher_get_option( 'advanced_tag_options_tax' ) ) {
				$taxonomies = array_flip( explode( ',', publisher_get_option( 'advanced_tag_options_tax' ) ) );
			}

		} else {
			return $valid[ $type ] = FALSE;
		}

		$taxonomies[ $type ] = '';

		if ( ! is_object( $queried_object ) ) {
			$queried_object = get_queried_object();
		}

		if ( ! isset( $queried_object->taxonomy ) ) {
			return $valid[ $type ] = FALSE;
		}

		return $valid[ $type ] = isset( $taxonomies[ $queried_object->taxonomy ] );

	}
}


if ( ! function_exists( 'publisher_get_single_template' ) ) {
	/**
	 * Used to get template for single page
	 *
	 * @return string
	 */
	function publisher_get_single_template() {

		static $template;

		if ( $template ) {
			return $template;
		}

		// default not valid post types
		if ( ! publisher_is_valid_cpt() ) {
			return $template = 'style-1';
		}

		$_check = array(
			''        => '',
			'default' => '',
		);

		$template = 'default';

		// Customized template in post
		if ( isset( $_check[ $template ] ) ) {
			$template = bf_get_post_meta( 'post_template' );
		}

		// Customized in category
		if ( isset( $_check[ $template ] ) ) {

			$main_term = publisher_get_post_primary_cat();

			if ( ! is_wp_error( $main_term ) && is_object( $main_term ) ) {
				$template = bf_get_term_meta( 'single_template', $main_term );
			}
		}

		// General Post Template
		if ( isset( $_check[ $template ] ) ) {
			$template = publisher_get_option( 'post_template' );
		}

		// validate
		if ( $template != 'default' && ! publisher_is_valid_single_template( $template ) ) {
			$template = 'default';
		}

		// default is style-1
		if ( isset( $_check[ $template ] ) ) {
			$template = 'style-1';
		}

		return $template;

	}
}// publisher_get_single_template


if ( ! function_exists( 'publisher_get_single_template_option' ) ) {
	/**
	 * Used to get template for single page
	 *
	 * @return string
	 */
	function publisher_get_single_template_option( $default = FALSE ) {

		$option = array();

		if ( $default ) {
			$option['default'] = array(
				'img'           => PUBLISHER_THEME_URI . 'images/options/post-default.png?v=' . PUBLISHER_THEME_VERSION,
				'label'         => __( 'Default', 'publisher' ),
				'current_label' => __( 'Default Template', 'publisher' ),
			);
		}

		$option['style-1']  = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/post-style-1.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( 'Template 1', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
			'info'  => array(
				'cat' => array(
					__( 'Normal', 'publisher' ),
				),
			),
		);
		$option['style-2']  = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/post-style-2.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( 'Template 2', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
			'info'  => array(
				'cat' => array(
					__( 'Wide', 'publisher' ),
				),
			),
		);
		$option['style-3']  = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/post-style-3.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( 'Template 3', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
			'info'  => array(
				'cat' => array(
					__( 'Wide', 'publisher' ),
				),
			),
		);
		$option['style-4']  = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/post-style-4.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( 'Template 4', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
			'info'  => array(
				'cat' => array(
					__( 'Wide', 'publisher' ),
				),
			),
		);
		$option['style-5']  = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/post-style-5.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( 'Template 5', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
			'info'  => array(
				'cat' => array(
					__( 'Wide', 'publisher' ),
				),
			),
		);
		$option['style-6']  = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/post-style-6.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( 'Template 6', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
			'info'  => array(
				'cat' => array(
					__( 'Wide', 'publisher' ),
				),
			),
		);
		$option['style-7']  = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/post-style-7.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( 'Template 7', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
			'info'  => array(
				'cat' => array(
					__( 'Wide', 'publisher' ),
				),
			),
		);
		$option['style-8']  = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/post-style-8.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( 'Template 8', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
			'info'  => array(
				'cat' => array(
					__( 'Normal', 'publisher' ),
				),
			),
		);
		$option['style-9']  = array(
			'img'    => PUBLISHER_THEME_URI . 'images/options/post-style-9.png?v=' . PUBLISHER_THEME_VERSION,
			'label'  => __( 'Template 9', 'publisher' ),
			'class'  => 'bf-flip-img-rtl',
			'info'   => array(
				'cat' => array(
					__( 'No Thumbnail', 'publisher' ),
				),
			),
			'Badges' => array(
				'cat' => array(
					__( 'No Thumbnail', 'publisher' ),
				),
			),
		);
		$option['style-10'] = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/post-style-10.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( 'Template 10', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
			'info'  => array(
				'cat' => array(
					__( 'Normal', 'publisher' ),
				),
			),
		);
		$option['style-11'] = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/post-style-11.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( 'Template 11', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
			'info'  => array(
				'cat' => array(
					__( 'Normal', 'publisher' ),
				),
			),
		);
		$option['style-12'] = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/post-style-12.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( 'Template 12', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
			'info'  => array(
				'cat' => array(
					__( 'Video', 'publisher' ),
				),
			),
		);
		$option['style-13'] = array(
			'img'   => PUBLISHER_THEME_URI . 'images/options/post-style-13.png?v=' . PUBLISHER_THEME_VERSION,
			'label' => __( 'Template 13', 'publisher' ),
			'class' => 'bf-flip-img-rtl',
			'info'  => array(
				'cat' => array(
					__( 'Wide', 'publisher' ),
				),
			),
		);

		return $option;
	}
}// publisher_get_single_template_option


if ( ! function_exists( 'publisher_is_valid_single_template' ) ) {
	/**
	 * Checks parameter to be a theme valid single template
	 *
	 * @param $template
	 *
	 * @return bool
	 */
	function publisher_is_valid_single_template( $template ) {

		return array_key_exists( $template, publisher_get_single_template_option() );
	} // publisher_is_valid_listing
}


if ( ! function_exists( 'publisher_social_counter_options_list_callback' ) ) {
	/**
	 * Handy deferred function for improving performance
	 *
	 * @return array
	 */
	function publisher_social_counter_options_list_callback() {

		if ( ! class_exists( 'Better_Social_Counter' ) ) {
			return array();
		} else {
			return Better_Social_Counter_Data_Manager::self()->get_widget_options_list();
		}

	}
}

if ( ! function_exists( 'publisher_is_animated_thumbnail_active' ) ) {
	/**
	 * Returns the condition of animated thumbnail activation
	 *
	 * @return bool
	 */
	function publisher_is_animated_thumbnail_active() {

		return TRUE;
	}
}


if ( ! function_exists( 'publisher_get_related_post_type' ) ) {
	/**
	 * Returns type of related posts for current page
	 *
	 * @return bool|mixed|null|string|void
	 */
	function publisher_get_related_post_type() {

		static $related_post;

		if ( $related_post ) {
			return $related_post;
		}

		$related_post = 'default';

		if ( publisher_is_valid_cpt() ) {
			$related_post = bf_get_post_meta( 'post_related' );
		}

		if ( $related_post === 'default' || ! $related_post ) {
			$related_post = publisher_get_option( 'post_related' );
		}

		return $related_post;

	}
}


if ( ! function_exists( 'publisher_get_post_comments_type' ) ) {
	/**
	 * Returns type of comments for current page
	 *
	 * @return bool|mixed|null|string
	 */
	function publisher_get_post_comments_type() {

		// Return from cache
		if ( publisher_get_global( 'post-comments-type-' . get_the_ID(), FALSE ) ) {
			return publisher_get_global( 'post-comments-type-' . get_the_ID(), FALSE );
		}

		$type = 'default';

		// for pages and posts
		if ( publisher_is_valid_cpt() ) {
			$type = bf_get_post_meta( 'post_comments', get_the_ID(), 'default' );
		}


		// get default from panel
		if ( empty( $type ) || $type === 'default' ) {
			if ( is_singular( 'page' ) ) {
				$type = publisher_get_option( 'page_comments' );
			} else {
				$type = publisher_get_option( 'post_comments' );
			}
		}


		// if ajaxify is not enabled
		if ( $type === 'show-ajaxified' && ! publisher_is_ajaxified_comments_active() ) {
			$type = 'show-simple';
		}

		$_check = array(
			'show-ajaxified' => '',
			'show-simple'    => '',
			'hide'           => '',
		);

		// if type is not valid
		if ( ! isset( $_check[ $type ] ) ) {
			$type = 'show-simple';
		}

		unset( $_check ); // clear memory

		//
		// If related post is infinity then posts loaded from ajax should have show comments button
		//
		if ( ! is_page() && publisher_get_related_post_type() === 'infinity-related-post' || ( defined( 'PUBLISHER_THEME_AJAXIFIED_LOAD_POST' ) && PUBLISHER_THEME_AJAXIFIED_LOAD_POST ) ) {
			$type = 'show-ajaxified';
		}

		// Change ajaxified to show simple when user submitted an comment before
		if ( $type === 'show-ajaxified' && ! empty( $_GET['bs-comment-added'] ) && $_GET['bs-comment-added'] === '1' ) {
			$type = 'show-simple';
		}

		// Cache it
		publisher_set_global( 'post-comments-type-' . get_the_ID(), $type );

		return $type;
	}
}


if ( ! function_exists( 'publisher_comments_template' ) ) {
	/**
	 * Handy function to getting correct comments file
	 */
	function publisher_comments_template() {


		switch ( publisher_get_post_comments_type() ) {

			case 'show-simple':
				comments_template();
				break;

			case 'show-ajaxified':
				comments_template( '/comments-ajaxified.php' );
				break;

			case FALSE:
			case '':
			case 'hide':
				return;

		}

	}
}


if ( ! function_exists( 'publisher_is_review_active' ) ) {
	/**
	 * Returns state of review for current post
	 *
	 * Supported Plugins:
	 *
	 * - Better Reviews     : Not public
	 * - WP Reviews         : https://wordpress.org/plugins/wp-review/
	 *
	 * @since 1.7
	 */
	function publisher_is_review_active() {

		/**
		 * Better Reviews plugin
		 */
		if ( function_exists( 'Better_Reviews' ) ) {
			if ( function_exists( 'better_reviews_is_review_active' ) ) {
				return better_reviews_is_review_active();
			} // compatibility for Better Reviews before v1.2.0
			else {
				return Better_Reviews::get_meta( '_bs_review_enabled' );
			}
		}


		/**
		 * WP Reviews plugin
		 *
		 * https://wordpress.org/plugins/wp-review/
		 */
		if ( function_exists( 'wp_review_get_post_review_type' ) ) {
			return wp_review_get_post_review_type();
		}


		return FALSE;
	}
}


if ( ! function_exists( 'publisher_get_rating' ) ) {
	/**
	 * Shows rating bar
	 *
	 * Supported Plugins:
	 *
	 * - Better Reviews     : Not public
	 * - WP Reviews         : https://wordpress.org/plugins/wp-review/
	 *
	 * @param bool $show_rate
	 *
	 * @since 1.7
	 *
	 * @return string
	 */
	function publisher_get_rating( $show_rate = FALSE ) {

		if ( ! publisher_is_review_active() ) {
			return;
		}

		$rate = FALSE;
		$type = '';


		/**
		 * Better Reviews plugin
		 */
		if ( function_exists( 'better_reviews_is_review_active' ) ) {

			if ( function_exists( 'better_reviews_get_review_type' ) ) {
				$type = better_reviews_get_review_type();
				$rate = better_reviews_get_total_rate();
			} // compatibility for Better Reviews before v1.2.0
			else {
				$type = Better_Reviews::get_meta( '_bs_review_rating_type' );
				$rate = Better_Reviews()->generator()->calculate_overall_rate();
			}

		}


		/**
		 * WP Reviews plugin
		 *
		 * https://wordpress.org/plugins/wp-review/
		 */
		if ( $rate == FALSE && function_exists( 'wp_review_get_post_review_type' ) ) {

			$rate = get_post_meta( get_the_ID(), 'wp_review_total', TRUE );
			$type = wp_review_get_post_review_type();

			if ( $type === 'star' ) {
				$type = 'stars';
				$rate *= 20;
			} elseif ( $type === 'point' ) {
				$type = 'points';
				$rate *= 10;
			}

		}


		if ( $rate == FALSE ) {
			return;
		}


		if ( $show_rate ) {
			if ( $type === 'points' ) {
				$show_rate = '<span class="rate-number">' . round( $rate / 10, 1 ) . '</span>';
			} else {
				$show_rate = '<span class="rate-number">' . esc_html( $rate ) . '%</span>';
			}
		} else {
			$show_rate = '';
		}

		if ( $type === 'points' || $type === 'percentage' ) {
			$type = 'bar';
		}

		echo '<div class="rating rating-' . esc_attr( $type ) . '"><span style="width: ' . esc_attr( $rate ) . '%;"></span>' . $show_rate . '</div>';

	} // publisher_get_rating
}


if ( ! function_exists( 'publisher_vc_widgetised_sidebar_params' ) ) {
	/**
	 * Callback: Fixes widget params for Visual Composer sidebars that are custom sidebar!
	 * Filter: dynamic_sidebar_params
	 *
	 * @param $params
	 *
	 * @since 1.7.0.3
	 *
	 * @return mixed
	 */
	function publisher_vc_widgetised_sidebar_params( $params ) {

		if ( ! isset( $params[0] ) ) {
			return $params;
		}

		if ( empty( $params[0]['before_title'] ) ) {
			$params[0]['before_title'] = '<h5 class="section-heading ' . publisher_get_block_heading_class() . '"><span class="h-text">';
		}

		if ( empty( $params[0]['after_title'] ) ) {
			$params[0]['after_title'] = '</span></h5>';
		}

		if ( empty( $params[0]['before_widget'] ) ) {

			$widget_class = '';
			$widget_id    = ! empty( $params[0]['widget_id'] ) ? $params[0]['widget_id'] : '';

			global $wp_registered_widgets;

			// Create class list for widget
			if ( isset( $wp_registered_widgets[ $params[0]['widget_id'] ] ) ) {
				foreach ( (array) $wp_registered_widgets[ $params[0]['widget_id'] ]['classname'] as $cn ) {
					if ( is_string( $cn ) ) {
						$widget_class .= '_' . $cn;
					} elseif ( is_object( $cn ) ) {
						$widget_class .= '_' . get_class( $cn );
					}
				}
				$widget_class = ltrim( $widget_class, '_' );
			}

			$params[0]['before_widget'] = '<div id="' . $widget_id . '" class="widget vc-widget ' . $widget_class . '">';
		}

		if ( empty( $params['after_widget'] ) ) {
			$params[0]['after_widget'] = '</div>';
		}

		return $params;
	}
}


if ( ! function_exists( 'publisher_show_breadcrumb' ) ) {
	/**
	 * Defines the breadcrumb should be shown or not
	 *
	 * @return bool
	 */
	function publisher_show_breadcrumb() {

		static $show;

		if ( ! is_null( $show ) ) {
			return $show;
		}

		$paginated_front_page = ( 'page' === get_option( 'show_on_front' ) ) && is_front_page() && bf_get_query_var_paged( 1 ) > 1;

		// hide breadcrumb in home
		if ( ( is_home() || is_front_page() ) && ! $paginated_front_page ) {
			return $show = FALSE;
		}

		$show = 'default';

		if ( is_singular() && ! $paginated_front_page ) {
			$show = bf_get_post_meta( 'post_breadcrumb', NULL, 'default' );
		}

		if ( $show === 'default' || empty( $show ) ) {
			$show = publisher_get_option( 'breadcrumb' );
		}

		$show = $show !== 'hide';

		return $show;

	} // publisher_show_breadcrumb
}


if ( ! function_exists( 'publisher_loop_meta' ) ) {
	/**
	 * Meta of loops
	 *
	 * @return bool
	 */
	function publisher_loop_meta() {

		$show_comments = TRUE;
		$show_reviews  = publisher_is_review_active();
		$show_author   = TRUE;
		$show_date     = TRUE;
		$show_view     = TRUE;
		$show_share    = TRUE;


		/**
		 *
		 * Single Logic Conditions
		 *
		 */

		if ( publisher_get_prop( 'hide-meta-date', FALSE ) ) {
			$show_date = FALSE;
		}

		if ( ! function_exists( 'The_Better_Views_Count' ) || publisher_get_prop( 'hide-meta-view', FALSE ) ) {
			$show_view = FALSE;
		}

		if ( publisher_get_prop( 'hide-meta-share', FALSE ) ) {
			$show_share = FALSE;
		}

		if ( publisher_get_prop( 'hide-meta-comment', FALSE ) || ! comments_open() ) {
			$show_comments = FALSE;
		}

		if ( publisher_get_prop( 'hide-meta-author', FALSE ) ) {
			$show_author = FALSE;
		}

		if ( $show_reviews && publisher_get_prop( 'hide-meta-review', FALSE ) ) {
			$show_reviews = FALSE;
		}


		/**
		 *
		 * Multiple Logic Conditions
		 *
		 */

		// Hide comments to make space for review
		if ( $show_reviews && $show_comments && publisher_get_prop( 'hide-meta-comment-if-review', FALSE ) ) {
			$show_comments = FALSE;
		}

		// Hide author to make space for review
		if ( $show_reviews && $show_author && publisher_get_prop( 'hide-meta-author-if-review', 0 ) ) {
			$show_author = FALSE;
		}

		?>
		<div class="post-meta">

			<?php if ( $show_author ) { ?>
				<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"
				   title="<?php echo publisher_translation_echo( 'browse_auth_articles' ); ?>"
				   class="post-author-a">
					<i class="post-author author">
						<?php the_author(); ?>
					</i>
				</a>
			<?php }


			if ( $show_date ) {

				global $post;

				$date_type = publisher_get_prop( 'meta-date-format', 'standard' );

				?>
				<span class="time"><time class="post-published updated"
				                         datetime="<?php echo mysql2date( DATE_W3C, $post->post_date, FALSE ); ?>"><?php
						if ( $date_type === 'standard' ) {
							the_time( publisher_translation_get( 'comment_time' ) );
						} else {

							switch ( $date_type ) {

								case 'readable':
									echo publisher_get_readable_date();
									break;

								case 'readable-month':

									if ( strtotime( $post->post_date ) < strtotime( 'first day of this month' ) ) {
										the_time( publisher_translation_get( 'comment_time' ) );
									} else {
										echo publisher_get_readable_date();
									}
									break;

								case 'readable-week':
									if ( strtotime( $post->post_date ) < strtotime( 'this week' ) ) {
										the_time( publisher_translation_get( 'comment_time' ) );
									} else {
										echo publisher_get_readable_date();
									}
									break;

								case 'readable-day':
									if ( strtotime( $post->post_date ) < strtotime( 'today' ) ) {
										the_time( publisher_translation_get( 'comment_time' ) );
									} else {
										echo publisher_get_readable_date();
									}
									break;

								default:
									echo publisher_get_readable_date();
							}

						}

						?></time></span>
				<?php
			}


			if ( $show_view ) {

				$rank = publisher_get_ranking_icon( The_Better_Views_Count(), 'views_ranking', 'fa-eye' );

				if ( isset( $rank['show'] ) && $rank['show'] ) {
					The_Better_Views(
						TRUE,
						'<span class="views post-meta-views ' . $rank['id'] . '" data-bpv-post="' . get_the_ID() . '">' . $rank['icon'],
						'</span>',
						'show',
						'%VIEW_COUNT%'
					);
				}
			}


			if ( $show_share ) {

				$count = array_sum( bf_social_shares_count( publisher_get_option( 'social_share_sites' ) ) );
				$rank  = publisher_get_ranking_icon( $count, 'shares_ranking', 'fa-share-alt' );

				if ( isset( $rank['show'] ) && $rank['show'] ) {

					?>
					<span class="share <?php echo $rank['id']; ?>"><?php echo $rank['icon'], ' ', $count; ?></span>
					<?php

				}
			}


			if ( $show_reviews ) {
				publisher_get_rating();
			}


			if ( $show_comments ) {

				$title  = apply_filters( 'better-studio/theme/meta/comments/title', publisher_get_the_title() );
				$link   = apply_filters( 'better-studio/theme/meta/comments/link', publisher_get_comments_link() );
				$number = apply_filters( 'better-studio/theme/meta/comments/number', publisher_get_comments_number() );

				$text = '<i class="fa fa-comments-o"></i> ' . apply_filters( 'better-studio/themes/meta/comments/text', $number );

				echo sprintf( '<a href="%1$s" title="%2$s" class="comments">%3$s</a>',
					$link,
					esc_attr( sprintf( publisher_translation_get( 'leave_comment_on' ), $title ) ),
					$text
				);

			}

			?>
		</div>
		<?php

	} // publisher_loop_meta
}


if ( ! function_exists( 'publisher_setup_paged_frontpage_query' ) ) {
	/**
	 * Setups paged front page query
	 * -> When homepage is static but pagination used for next pages
	 *
	 * @return bool
	 */
	function publisher_setup_paged_frontpage_query() {

		$home_args = array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => 10,
			'paged'          => bf_get_query_var_paged( 1 ),
		);

		// Home posts count
		if ( publisher_get_option( 'home_posts_count' ) != '' ) {
			$home_args['posts_per_page'] = publisher_get_option( 'home_posts_count' );
		}

		// Home category filters
		if ( publisher_get_option( 'home_cat_include' ) != '' ) {
			$home_args['cat'] = publisher_get_option( 'home_cat_include' );
		}

		// Home exclude category filters
		if ( publisher_get_option( 'home_cat_exclude' ) != '' ) {
			$home_args['category__not_in'] = publisher_get_option( 'home_cat_exclude' );
		}

		// Home tag filters
		if ( publisher_get_option( 'home_tag_include' ) != '' ) {
			$home_args['tag__in'] = publisher_get_option( 'home_tag_include' );
		}

		$front_page_query = new WP_Query( $home_args );

		publisher_set_query( $front_page_query );

	} // publisher_setup_paged_frontpage_query
}


if ( ! function_exists( 'publisher_get_ranking_icon' ) ) {
	/**
	 * Returns icon of rank from panel
	 *
	 *
	 * @param int    $rank
	 * @param string $type
	 * @param string $default
	 * @param bool   $force_show
	 *
	 * @return array
	 * @since 1.8.0
	 *
	 */
	function publisher_get_ranking_icon( $rank = 0, $type = 'views_ranking', $default = 'fa-eye', $force_show = FALSE ) {

		static $ranks;

		if ( is_null( $ranks ) ) {
			$ranks = array();
		}

		// prepare ranks
		if ( ! isset( $ranks[ $type ] ) ) {

			$ranks[ $type ] = array();

			$field = publisher_get_option( $type );


			foreach ( $field as $_value ) {
				if ( $_value['rate'] == '' ) {
					continue;
				}

				$ranks[ $type ][ $_value['rate'] ]         = $_value;
				$ranks[ $type ][ $_value['rate'] ]['icon'] = bf_get_icon_tag( $_value['icon'] );
			}

			ksort( $ranks[ $type ] );

			$_ranks = array();

			foreach ( $ranks[ $type ] as $_rank => $_rank_v ) {

				$_ranks[ $_rank ]       = $_rank_v;
				$_ranks[ $_rank ]['id'] = 'rank-' . $_rank;
			}

			$ranks[ $type ] = $_ranks;

			$ranks[ $type ]['default'] = array(
				'rate' => '',
				'id'   => 'rank-default',
				'show' => TRUE,
				'icon' => bf_get_icon_tag( $default ),
			);

		}

		$icon = FALSE;

		// Check rank
		foreach ( $ranks[ $type ] as $_rank_i => $_rank ) {

			if ( $_rank_i === 'default' ) {
				continue;
			}

			if ( $_rank['rate'] > $rank &&
			     isset( $ranks[ $type ][ $_rank_i - 1 ] ) && $ranks[ $type ][ $_rank_i - 1 ]['rate'] < $rank
			) {
				$icon = $ranks[ $type ][ $_rank_i - 1 ];
				continue;
			}

			if ( $_rank['rate'] <= $rank &&
			     (
				     ( isset( $ranks[ $type ][ $_rank_i + 1 ] ) && $ranks[ $type ][ $_rank_i + 1 ]['rate'] > $rank ) ||
				     ! isset( $ranks[ $type ][ $_rank_i + 1 ] )
			     )
			) {
				$icon = $_rank;
			}

		}

		if ( $force_show ) {
			$icon['show'] = TRUE;
		}

		if ( $icon ) {
			return $icon;
		} else {
			return $ranks[ $type ]['default'];
		}

	}
}


if ( ! function_exists( 'publisher_social_login_providers' ) ) {
	/**
	 * Get social login providers urls
	 *
	 * Supported plugins:
	 * http://miled.github.io/wordpress-social-login/
	 *
	 *
	 * @since 1.8.0
	 *
	 * @return array
	 */
	function publisher_social_login_providers() {

		if ( ! defined( 'WORDPRESS_SOCIAL_LOGIN_ABS_PATH' ) ) {
			return array();
		}

		global $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG;

		if ( empty( $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG ) || ! is_array( $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG ) ) {
			return array();
		}

		$current_url = site_url( add_query_arg( FALSE, FALSE ) );

		$login_url = add_query_arg(
			array(
				'action'      => 'wordpress_social_authenticate',
				'mode'        => 'login',
				'redirect_to' => urlencode( $current_url ),
			),
			site_url( 'wp-login.php', 'login_post' )
		);

		$use_popup = function_exists( 'wp_is_mobile' ) && wp_is_mobile() ? 2 : get_option( 'wsl_settings_use_popup' );

		$providers = array();

		foreach ( $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG as $provider ) {

			$provider_id = isset( $provider["provider_id"] ) ? $provider["provider_id"] : '';
			$is_enable   = get_option( 'wsl_settings_' . $provider_id . '_enabled' );

			if ( ! $is_enable ) {
				continue;
			}

			$provider_url = add_query_arg( 'provider', $provider_id, $login_url );
			$provider_url = apply_filters( 'wsl_render_auth_widget_alter_authenticate_url', $provider_url, $provider_id, 'login', $current_url, $use_popup );

			$providers[ $provider_id ] = $provider_url;
		}

		return $providers;
	}
}


if ( $GLOBALS['pagenow'] !== 'wp-login.php' || ! empty( $_REQUEST['action'] ) && $_REQUEST['action'] !== 'register' ) {
	add_filter( 'wsl_render_auth_widget_alter_provider_icon_markup', 'publisher_wsl_get_button', 10, 3 );
}

if ( ! function_exists( 'publisher_wsl_get_button' ) ) {
	/**
	 * Used to change codes of WSL plugin to make it high compatible with Publisher
	 *
	 * @param      $provider_id
	 * @param      $provider_name
	 * @param      $authenticate_url
	 * @param bool $full
	 */
	function publisher_wsl_get_button( $provider_id, $provider_name, $authenticate_url, $full = TRUE ) {

		$icons = array(
			'foursquare'    => 'fa-foursquare',
			'reddit'        => 'fa-reddit-alien',
			'disqus'        => 'bsfi-disqus',
			'linkedin'      => 'bsfi-linkedin',
			'yahoo'         => 'fa-yahoo',
			'instagram'     => 'bsfi-instagram',
			'wordpress'     => 'fa-wordpress',
			'google'        => 'bsfi-gplus',
			'twitter'       => 'bsfi-twitter',
			'facebook'      => 'bsfi-facebook',
			'lastfm'        => 'fa-lastfm',
			'tumblr'        => 'bsfi-tumblr',
			'stackoverflow' => 'fa-stack-overflow',
			'github'        => 'bsfi-github',
			'Dribbble'      => 'bsfi-dribbble',
			'500px'         => 'fa-500px',
			'steam'         => 'bsfi-steam',
			'twitchtv'      => 'fa-twitch',
			'vkontakte'     => 'bsfi-vk',
			'odnoklassniki' => 'fa-odnoklassniki',
			'aol'           => 'fa-odnoklassniki',
		);

		$provider_id_lower = strtolower( $provider_id );

		$icon = FALSE;

		if ( isset( $icons[ $provider_id_lower ] ) ) {
			$icon = bf_get_icon_tag( $icons[ $provider_id_lower ] );
		}

		?>
		<a
				rel="nofollow"
				href="<?php echo $authenticate_url; ?>"
				data-provider="<?php echo $provider_id ?>"
				class="btn social-login-btn social-login-btn-<?php echo $provider_id_lower, ' ', ! empty( $icon ) ? 'with-icon' : ''; ?>"><?php

			if ( $full ) {
				echo $icon, sprintf( publisher_translation_get( 'login_with' ), ucfirst( $provider_name ) );
			} else {
				echo $icon, $provider_id;
			}

			?>
		</a>
		<?php

	} // publisher_wsl_get_button
}


if ( ! function_exists( 'publisher_wsl_disable_for_login_form' ) ) {
	/**
	 * Handy function used to disable WSL login buttons in bottom of login form.
	 *
	 * @param $settings
	 *
	 * @return int
	 */
	function publisher_wsl_disable_for_login_form( $settings ) {

		return 2;
	}
}


if ( ! function_exists( 'publisher_multiple_comments_choices' ) ) {
	/**
	 * Multiple comment option panel choices
	 *
	 * @todo  add disqus icon
	 *
	 * @since 1.8.0
	 * @return array
	 */
	function publisher_multiple_comments_choices() {

		return array(
			'wordpress' => array(
				'label'     => '<i class="fa fa-wordpress"></i> ' . __( 'WordPress', 'publisher' ),
				'css-class' => 'active-item'
			),
			'facebook'  => array(
				'label'     => '<i class="fa fa-facebook"></i> ' . __( 'Facebook', 'publisher' ),
				'css-class' => is_callable( 'Better_Facebook_Comments::factory' ) ? 'active-item' : 'disable-item',
			),
			'disqus'    => array(
				'label'     => '<i class="bf-icon bsfi-disqus"></i>' . __( 'Disqus', 'publisher' ),
				'css-class' => is_callable( 'Better_Disqus_Comments::factory' ) ? 'active-item' : 'disable-item',
			),
		);
	}
}


if ( ! function_exists( 'publisher_multiple_comments_form' ) ) {
	/**
	 * Multiple comment option panel choices
	 *
	 * @todo  add disqus icon
	 *
	 * @since 1.8.0
	 * @return array
	 */
	function publisher_multiple_comments_form() {

		Publisher_Comments::multiple_comments_form();
	}
}


if ( ! function_exists( 'publisher_more_stories_listing_option_list' ) ) {
	/**
	 * Panels more stories listing field option
	 *
	 * @param bool $default_choice
	 *
	 *
	 * @since 1.8.0
	 * @return array
	 */
	function publisher_more_stories_listing_option_list( $default_choice = FALSE ) {

		$option = array();


		if ( $default_choice ) {
			$option['default'] = array(
				'img'           => PUBLISHER_THEME_URI . 'images/options/post-default.png?v=' . PUBLISHER_THEME_VERSION,
				'label'         => __( 'Default', 'publisher' ),
				'current_label' => __( 'Default [ From Theme Panel ]', 'publisher' ),
			);
		}

		$option['thumbnail-1'] = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/ms-listing-thumbnail-1.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Thumbnail 1', 'publisher' ),
			'current_label' => __( 'Thumbnail Listing 1', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat' => array(
					__( 'Thumbnail Listing', 'publisher' ),
				),
			),
		);
		$option['thumbnail-2'] = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/ms-listing-thumbnail-2.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Thumbnail 2', 'publisher' ),
			'current_label' => __( 'Thumbnail Listing 2', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat' => array(
					__( 'Thumbnail Listing', 'publisher' ),
				),
			),
		);
		$option['thumbnail-3'] = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/ms-listing-thumbnail-3.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Thumbnail 3', 'publisher' ),
			'current_label' => __( 'Thumbnail Listing 3', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat' => array(
					__( 'Thumbnail Listing', 'publisher' ),
				),
			),
		);

		$option['text-1'] = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/ms-listing-text-1.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Text 1', 'publisher' ),
			'current_label' => __( 'Text Listing 1', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat' => array(
					__( 'Text Listing', 'publisher' ),
				),
			),
		);
		$option['text-2'] = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/ms-listing-text-2.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Text 2', 'publisher' ),
			'current_label' => __( 'Text Listing 2', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat' => array(
					__( 'Text Listing', 'publisher' ),
				),
			),
		);
		$option['text-3'] = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/ms-listing-text-3.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Text 3', 'publisher' ),
			'current_label' => __( 'Text Listing 3', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat' => array(
					__( 'Text Listing', 'publisher' ),
				),
			),
		);
		$option['text-4'] = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/ms-listing-text-4.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Text 4', 'publisher' ),
			'current_label' => __( 'Text Listing 4', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat' => array(
					__( 'Text Listing', 'publisher' ),
				),
			),
		);

		return $option;
	} // publisher_more_stories_listing_option_list
}


if ( ! function_exists( 'publisher_irp_listing_option_list' ) ) {
	/**
	 * Panels inline related posts listing field
	 *
	 * @param bool $default_choice
	 *
	 *
	 * @since 1.8.0
	 * @return array
	 */
	function publisher_irp_listing_option_list( $default_choice = FALSE ) {

		$option = array();


		if ( $default_choice ) {
			$option['default'] = array(
				'img'           => PUBLISHER_THEME_URI . 'images/options/post-default.png?v=' . PUBLISHER_THEME_VERSION,
				'label'         => __( 'Default', 'publisher' ),
				'current_label' => __( 'Default [ From Theme Panel ]', 'publisher' ),
			);
		}

		$option['thumbnail-1']      = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/irp-listing-thumbnail-1.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Thumbnail 1', 'publisher' ),
			'current_label' => __( 'Thumbnail Listing 1', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat'  => array(
					__( 'Thumbnail Listing', 'publisher' ),
				),
				'type' => array(
					__( 'Aligned', 'publisher' ),
				),
			),
		);
		$option['thumbnail-2']      = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/irp-listing-thumbnail-2.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Thumbnail 2', 'publisher' ),
			'current_label' => __( 'Thumbnail Listing 2', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat'  => array(
					__( 'Thumbnail Listing', 'publisher' ),
				),
				'type' => array(
					__( 'Aligned', 'publisher' ),
				),
			),
		);
		$option['thumbnail-3']      = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/irp-listing-thumbnail-3.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Thumbnail 3', 'publisher' ),
			'current_label' => __( 'Thumbnail Listing 3', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat'  => array(
					__( 'Thumbnail Listing', 'publisher' ),
				),
				'type' => array(
					__( 'Aligned', 'publisher' ),
				),
			),
		);
		$option['thumbnail-1-full'] = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/irp-listing-thumbnail-1-full.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Thumbnail 1 Full', 'publisher' ),
			'current_label' => __( 'Full Thumbnail Listing 1', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat'  => array(
					__( 'Thumbnail Listing', 'publisher' ),
				),
				'type' => array(
					__( 'Full', 'publisher' ),
				),
			),
		);
		$option['thumbnail-2-full'] = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/irp-listing-thumbnail-2-full.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Thumbnail 2 Full', 'publisher' ),
			'current_label' => __( 'Full Thumbnail Listing 2', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat'  => array(
					__( 'Thumbnail Listing', 'publisher' ),
				),
				'type' => array(
					__( 'Full', 'publisher' ),
				),
			),
		);
		$option['thumbnail-3-full'] = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/irp-listing-thumbnail-3-full.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Thumbnail 3 Full', 'publisher' ),
			'current_label' => __( 'Full Thumbnail Listing 3', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat'  => array(
					__( 'Thumbnail Listing', 'publisher' ),
				),
				'type' => array(
					__( 'Full', 'publisher' ),
				),
			),
		);
		$option['text-1']           = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/irp-listing-text-1.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Text 1', 'publisher' ),
			'current_label' => __( 'Text Listing 1', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat'  => array(
					__( 'Text Listing', 'publisher' ),
				),
				'type' => array(
					__( 'Aligned', 'publisher' ),
				),
			),
		);
		$option['text-2']           = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/irp-listing-text-2.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Text 2', 'publisher' ),
			'current_label' => __( 'Text Listing 2', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat'  => array(
					__( 'Text Listing', 'publisher' ),
				),
				'type' => array(
					__( 'Aligned', 'publisher' ),
				),
			),
		);
		$option['text-3']           = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/irp-listing-text-3.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Text 3', 'publisher' ),
			'current_label' => __( 'Text Listing 3', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat'  => array(
					__( 'Text Listing', 'publisher' ),
				),
				'type' => array(
					__( 'Aligned', 'publisher' ),
				),
			),
		);
		$option['text-4']           = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/irp-listing-text-4.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Text 4', 'publisher' ),
			'current_label' => __( 'Text Listing 4', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat'  => array(
					__( 'Text Listing', 'publisher' ),
				),
				'type' => array(
					__( 'Aligned', 'publisher' ),
				),
			),
		);
		$option['text-1-full']      = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/irp-listing-text-1-full.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Text 1 Full', 'publisher' ),
			'current_label' => __( 'Full Text Listing 1', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat'  => array(
					__( 'Text Listing', 'publisher' ),
				),
				'type' => array(
					__( 'Full', 'publisher' ),
				),
			),
		);
		$option['text-2-full']      = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/irp-listing-text-2-full.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Text 2 Full', 'publisher' ),
			'current_label' => __( 'Full Text Listing 2', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat'  => array(
					__( 'Text Listing', 'publisher' ),
				),
				'type' => array(
					__( 'Full', 'publisher' ),
				),
			),
		);
		$option['text-3-full']      = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/irp-listing-text-3-full.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Text 3 Full', 'publisher' ),
			'current_label' => __( 'Full Text Listing 3', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat'  => array(
					__( 'Text Listing', 'publisher' ),
				),
				'type' => array(
					__( 'Full', 'publisher' ),
				),
			),
		);
		$option['text-4-full']      = array(
			'img'           => PUBLISHER_THEME_URI . 'images/options/irp-listing-text-4-full.png?v=' . PUBLISHER_THEME_VERSION,
			'label'         => __( 'Text 4 Full', 'publisher' ),
			'current_label' => __( 'Full Text Listing 4', 'publisher' ),
			'class'         => 'bf-flip-img-rtl',
			'info'          => array(
				'cat'  => array(
					__( 'Text Listing', 'publisher' ),
				),
				'type' => array(
					__( 'Full', 'publisher' ),
				),
			),
		);

		return $option;
	}
}


if ( ! function_exists( 'publisher_search_query' ) ) {

	/**
	 * Set Custom Search Query
	 *
	 * @param string $search search query string
	 * @param array  $args   additional query args
	 *
	 * @since 1.8.0
	 * @return WP_Query
	 */
	function publisher_search_query( $search = '', $args = array() ) {

		return Publisher_Search::set_search_page_query( $search, $args );
	}
}


if ( ! function_exists( 'publisher_search_terms' ) ) {

	/**
	 * Search terms
	 *
	 * @param string $query the search query
	 * @param string $taxonomy
	 * @param int    $max_items
	 *
	 * @since 1.8.0
	 * @return array
	 */
	function publisher_search_terms( $query, $taxonomy = 'category', $max_items = 4 ) {

		return Publisher_Search::search_terms( $query, $taxonomy, $max_items );
	}
}


if ( ! function_exists( 'publisher_mega_menus_list' ) ) {

	/**
	 * List of publisher mega menus
	 *
	 * @since 1.8.0
	 * @return array
	 */
	function publisher_mega_menus_list() {

		return array(
			'disabled'          => array(
				'img'   => PUBLISHER_THEME_URI . 'images/options/mega-disable.png?v=' . PUBLISHER_THEME_VERSION,
				'label' => __( '-- Disabled --', 'publisher' ),
			),
			'link-list'         => array(
				'img'   => PUBLISHER_THEME_URI . 'images/options/mega-link-list.png?v=' . PUBLISHER_THEME_VERSION,
				'label' => __( 'Horizontal links', 'publisher' ),
				'depth' => 0,
				'info'  => array(
					'cat' => array(
						__( 'Link', 'publisher' ),
					),
				),
			),
			'link-2-column'     => array(
				'img'   => PUBLISHER_THEME_URI . 'images/options/mega-link-2-column.png?v=' . PUBLISHER_THEME_VERSION,
				'label' => __( '2 Column links', 'publisher' ),
				'depth' => 0,
				'info'  => array(
					'cat' => array(
						__( 'Link', 'publisher' ),
					),
				),
			),
			'link-3-column'     => array(
				'img'   => PUBLISHER_THEME_URI . 'images/options/mega-link-3-column.png?v=' . PUBLISHER_THEME_VERSION,
				'label' => __( '3 Column links', 'publisher' ),
				'depth' => 0,
				'info'  => array(
					'cat' => array(
						__( 'Link', 'publisher' ),
					),
				),
			),
			'link-4-column'     => array(
				'img'   => PUBLISHER_THEME_URI . 'images/options/mega-link-4-column.png?v=' . PUBLISHER_THEME_VERSION,
				'label' => __( '4 Column links', 'publisher' ),
				'depth' => 0,
				'info'  => array(
					'cat' => array(
						__( 'Link', 'publisher' ),
					),
				),
			),
			'link-5-column'     => array(
				'img'   => PUBLISHER_THEME_URI . 'images/options/mega-link-5-column.png?v=' . PUBLISHER_THEME_VERSION,
				'label' => __( '5 Column links', 'publisher' ),
				'depth' => 0,
				'info'  => array(
					'cat' => array(
						__( 'Link', 'publisher' ),
					),
				),
			),
			'tabbed-grid-posts' => array(
				'img'   => PUBLISHER_THEME_URI . 'images/options/mega-tabbed-grid-posts.png?v=' . PUBLISHER_THEME_VERSION,
				'label' => __( 'Tabbed sub categories with posts', 'publisher' ),
				'depth' => 0,
				'info'  => array(
					'cat' => array(
						__( 'Posts', 'publisher' ),
					),
				),
			),
			'grid-posts'        => array(
				'img'   => PUBLISHER_THEME_URI . 'images/options/mega-grid-posts.png?v=' . PUBLISHER_THEME_VERSION,
				'label' => __( 'Latest posts with image', 'publisher' ),
				'depth' => 0,
				'info'  => array(
					'cat' => array(
						__( 'Posts', 'publisher' ),
					),
				),
			),
			'page-builder'      => array(
				'img'   => PUBLISHER_THEME_URI . 'images/options/mega-page-builder.png?v=' . PUBLISHER_THEME_VERSION,
				'label' => __( 'Page Builder', 'publisher' ),
				'depth' => 0,
				'info'  => array(
					'cat' => array(
						__( 'Posts', 'publisher' ),
					),
				),
			),
		);
	}
}


if ( ! function_exists( 'publisher_lazy_load_image_sizes' ) ) {
	/**
	 * Get image alternative sizes
	 *
	 * @since 1.8.0
	 *
	 * @return array
	 */
	function publisher_lazy_load_image_sizes() {

		return array(
			// Rectangle sizes
			'publisher-tb1'      => array(
				'publisher-sm',
			),
			'publisher-sm'       => array(
				'publisher-tb1',
				'publisher-md',
				'publisher-mg2',
				'publisher-lg',
			),
			'publisher-mg2'      => array(
				'publisher-sm',
				'publisher-mg2',
				'publisher-md',
				'publisher-lg',
			),
			'publisher-md'       => array(
				'publisher-sm',
				'publisher-mg2',
				'publisher-lg',
			),
			'publisher-lg'       => array(
				'publisher-sm',
				'publisher-mg2',
				'publisher-md',
				'publisher-lg',
			),
			// Tall sizes
			'publisher-tall-sm'  => array(
				'publisher-tall-lg',
				'publisher-tall-big',
			),
			'publisher-tall-lg'  => array(
				'publisher-tall-sm',
				'publisher-tall-big',
			),
			'publisher-tall-big' => array(
				'publisher-tall-sm',
				'publisher-tall-lg',
			),
		);
	}
}


if ( ! function_exists( 'publisher_share_option_list' ) ) {
	/**
	 * Panels layout field options
	 *
	 * @param bool $default
	 *
	 * @return array
	 */
	function publisher_share_option_list( $default = FALSE ) {

		$option = array(
			'style-1'  => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/share-style-1-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/share-style-1.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 1', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat'  => array(
						__( 'Small', 'publisher' ),
					),
					'type' => array(
						__( 'Icon Button', 'publisher' ),
					),
				)
			),
			'style-2'  => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/share-style-2-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/share-style-2.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 2', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat'  => array(
						__( 'Small', 'publisher' ),
					),
					'type' => array(
						__( 'Text Button', 'publisher' ),
					),
				)
			),
			'style-3'  => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/share-style-3-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/share-style-3.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 3', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat'  => array(
						__( 'Large', 'publisher' ),
					),
					'type' => array(
						__( 'Icon Button', 'publisher' ),
					),
				)
			),
			'style-4'  => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/share-style-4-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/share-style-4.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 4', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat'  => array(
						__( 'Large', 'publisher' ),
					),
					'type' => array(
						__( 'Text Button', 'publisher' ),
					),
				)
			),
			'style-5'  => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/share-style-5-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/share-style-5.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 5', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat'  => array(
						__( 'Small', 'publisher' ),
					),
					'type' => array(
						__( 'Icon Button', 'publisher' ),
					),
				)
			),
			'style-6'  => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/share-style-6-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/share-style-6.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 6', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat'  => array(
						__( 'Small', 'publisher' ),
					),
					'type' => array(
						__( 'Text Button', 'publisher' ),
					),
				)
			),
			'style-7'  => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/share-style-7-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/share-style-7.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 7', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat'  => array(
						__( 'Small', 'publisher' ),
					),
					'type' => array(
						__( 'Icon Button', 'publisher' ),
					),
				)
			),
			'style-8'  => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/share-style-8-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/share-style-8.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 8', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat'  => array(
						__( 'Small', 'publisher' ),
					),
					'type' => array(
						__( 'Text Button', 'publisher' ),
					),
				)
			),
			'style-9'  => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/share-style-9-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/share-style-9.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 9', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat'  => array(
						__( 'Large', 'publisher' ),
					),
					'type' => array(
						__( 'Icon Button', 'publisher' ),
					),
				)
			),
			'style-10' => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/share-style-10-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/share-style-10.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 10', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat'  => array(
						__( 'Large', 'publisher' ),
					),
					'type' => array(
						__( 'Text Button', 'publisher' ),
					),
				)
			),
			'style-11' => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/share-style-11-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/share-style-11.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 11', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat'  => array(
						__( 'Large', 'publisher' ),
					),
					'type' => array(
						__( 'Icon Button', 'publisher' ),
					),
				)
			),
			'style-12' => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/share-style-12-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/share-style-12.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 12', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat'  => array(
						__( 'Large', 'publisher' ),
					),
					'type' => array(
						__( 'Text Button', 'publisher' ),
					),
				)
			),
			'style-13' => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/share-style-13-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/share-style-13.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 13', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat'  => array(
						__( 'Large', 'publisher' ),
					),
					'type' => array(
						__( 'Icon Button', 'publisher' ),
					),
				)
			),
			'style-14' => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/share-style-14-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/share-style-14.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 14', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat'  => array(
						__( 'Large', 'publisher' ),
					),
					'type' => array(
						__( 'Text Button', 'publisher' ),
					),
				)
			),
			'style-15' => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/share-style-15-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/share-style-15.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 15', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat'  => array(
						__( 'Large', 'publisher' ),
					),
					'type' => array(
						__( 'Icon Button', 'publisher' ),
					),
				)
			),
			'style-16' => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/share-style-16-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/share-style-16.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 16', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat'  => array(
						__( 'Large', 'publisher' ),
					),
					'type' => array(
						__( 'Text Button', 'publisher' ),
					),
				)
			),
			'style-17' => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/share-style-17-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/share-style-17.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 17', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat'  => array(
						__( 'Small', 'publisher' ),
					),
					'type' => array(
						__( 'Icon Button', 'publisher' ),
					),
				)
			),
			'style-18' => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/share-style-18-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/share-style-18.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 18', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat'  => array(
						__( 'Small', 'publisher' ),
					),
					'type' => array(
						__( 'Text Button', 'publisher' ),
					),
				)
			),
			'style-19' => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/share-style-19-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/share-style-19.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 19', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat'  => array(
						__( 'Small', 'publisher' ),
					),
					'type' => array(
						__( 'Icon Button', 'publisher' ),
					),
				)
			),
			'style-20' => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/share-style-20-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/share-style-20.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 20', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat'  => array(
						__( 'Small', 'publisher' ),
					),
					'type' => array(
						__( 'Text Button', 'publisher' ),
					),
				)
			),
			'style-21' => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/share-style-21-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/share-style-21.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 21', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat'  => array(
						__( 'Small', 'publisher' ),
					),
					'type' => array(
						__( 'Icon Button', 'publisher' ),
					),
				)
			),
			'style-22' => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/share-style-22-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/share-style-22.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 22', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat'  => array(
						__( 'Small', 'publisher' ),
					),
					'type' => array(
						__( 'Text Button', 'publisher' ),
					),
				)
			),
		);

		if ( $default ) {
			$option = array(
				          'default' => array(
					          'img'           => PUBLISHER_THEME_URI . 'images/options/share-style-default-full.png?v=' . PUBLISHER_THEME_VERSION,
					          'current_img'   => PUBLISHER_THEME_URI . 'images/options/share-style-default.png?v=' . PUBLISHER_THEME_VERSION,
					          'label'         => __( 'Default', 'publisher' ),
					          'current_label' => __( 'Default Layout', 'publisher' ),
				          )
			          ) + $option;
		}

		return $option;
	} // publisher_share_option_list
} // if


if ( ! function_exists( 'publisher_get_top_share_style' ) ) {
	/**
	 * Return post top share buttons style
	 *
	 * @return array
	 */
	function publisher_get_top_share_style() {

		return publisher_get_option( 'social_share_top_style' );
	} // publisher_get_top_share_style
} // if


if ( ! function_exists( 'publisher_get_bottom_share_style' ) ) {
	/**
	 * Return post bottom share buttons style
	 *
	 * @return array
	 */
	function publisher_get_bottom_share_style() {

		if ( publisher_get_option( 'social_share_bottom_style' ) && publisher_get_option( 'social_share_bottom_style' ) != 'default' ) {
			return publisher_get_option( 'social_share_bottom_style' );
		}

		return publisher_get_top_share_style();
	} // publisher_get_bottom_share_style
} // if


if ( ! function_exists( 'publisher_set_blocks_title_tag' ) ) {
	/**
	 * Return post bottom share buttons style
	 *
	 * @param string $type Change to specific type. // added for future!
	 *
	 * @return array
	 */
	function publisher_set_blocks_title_tag( $type = 'p' ) {

		publisher_set_prop( 'item-tag', 'div' );
		publisher_set_prop( 'item-heading-tag', 'p' );
		publisher_set_prop( 'item-sub-heading-tag', 'p' );

	} // publisher_set_blocks_title_tag
} // if


if ( ! function_exists( 'publisher_unset_blocks_title_tag' ) ) {
	/**
	 * Return post bottom share buttons style
	 *
	 * @return array
	 */
	function publisher_unset_blocks_title_tag() {

		publisher_unset_prop( 'item-tag' );
		publisher_unset_prop( 'item-heading-tag' );
		publisher_unset_prop( 'item-sub-heading-tag' );

	} // publisher_unset_blocks_title_tag
} // if

if ( ! function_exists( 'publisher_get_block_heading_style' ) ) {
	/**
	 * Returns heading style of blocks
	 *
	 * @return mixed|null
	 */
	function publisher_get_block_heading_style() {

		return publisher_get_option( 'section_heading_style' );
	}
}


if ( ! function_exists( 'publisher_get_block_heading_class' ) ) {
	/**
	 * Returns heading class name of blocks
	 *
	 * @param string $style
	 *
	 * @return string
	 */
	function publisher_get_block_heading_class( $style = '' ) {

		if ( empty( $style ) || $style === 'default' ) {
			$style = publisher_get_block_heading_style();
		}


		$style = explode( '-', $style );

		if ( isset( $style[0] ) && isset( $style[1] ) ) {
			return "sh-{$style[0]} sh-{$style[1]}";
		}

		return 'sh-t1 sh-s1';
	} // publisher_get_block_heading_class
}


if ( ! function_exists( 'publisher_cb_heading_option_list' ) ) {
	/**
	 * Section Heading styles list
	 *
	 * @param bool $default
	 *
	 * @return array
	 */
	function publisher_cb_heading_option_list( $default = FALSE ) {

		$option = array(
			't2-s1'  => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/t2-s1-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/t2-s1-small.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 1', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat' => array(
						__( 'Boxed', 'publisher' ),
					),
				)
			),
			't2-s2'  => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/t2-s2-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/t2-s2-small.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 2', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat' => array(
						__( 'Boxed', 'publisher' ),
					),

				)
			),
			't2-s3'  => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/t2-s3-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/t2-s3-small.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 3', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat' => array(
						__( 'Boxed', 'publisher' ),
					),
				)
			),
			't2-s4'  => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/t2-s4-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/t2-s4-small.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 4', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat' => array(
						__( 'Boxed', 'publisher' ),
					),
				)
			),
			't1-s1'  => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/t1-s1-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/t1-s1-small.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 5', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat' => array(
						__( 'Lined', 'publisher' ),
					),
				)
			),
			't1-s2'  => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/t1-s2-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/t1-s2-small.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 6', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat' => array(
						__( 'Lined', 'publisher' ),
					),
				)
			),
			't1-s3'  => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/t1-s3-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/t1-s3-small.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 7', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat' => array(
						__( 'Lined', 'publisher' ),
					),
				)
			),
			't1-s4'  => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/t1-s4-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/t1-s4-small.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 8', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat' => array(
						__( 'Simple', 'publisher' ),
					),
				)
			),
			't1-s5'  => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/t1-s5-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/t1-s5-small.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 9', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat' => array(
						__( 'Lined', 'publisher' ),
					),
				)
			),
			't3-s1'  => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/t3-s1-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/t3-s1-small.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 10', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat' => array(
						__( 'Lined', 'publisher' ),
					),
				),
			),
			't3-s2'  => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/t3-s2-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/t3-s2-small.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 11', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat' => array(
						__( 'Boxed', 'publisher' ),
					),
				),
			),
			't3-s3'  => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/t3-s3-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/t3-s3-small.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 12', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat' => array(
						__( 'Boxed', 'publisher' ),
					),
				),
			),
			't3-s4'  => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/t3-s4-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/t3-s4-small.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 13', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat' => array(
						__( 'Boxed', 'publisher' ),
					),
				),
			),
			't3-s5'  => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/t3-s5-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/t3-s5-small.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 14', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat' => array(
						__( 'Boxed', 'publisher' ),
					),
				),
			),
			't3-s6'  => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/t3-s6-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/t3-s6-small.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 15', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat' => array(
						__( 'Boxed', 'publisher' ),
					),
				),
			),
			't3-s7'  => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/t3-s7-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/t3-s7-small.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 16', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat' => array(
						__( 'Boxed', 'publisher' ),
						__( 'Creative', 'publisher' ),
					),
				),
			),
			't3-s8'  => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/t3-s8-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/t3-s8-small.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 17', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat' => array(
						__( 'Lined', 'publisher' ),
					),
				),
			),
			't4-s1'  => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/t4-s1-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/t4-s1-small.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 18', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat' => array(
						__( 'Boxed', 'publisher' ),
					),
				)
			),
			't4-s2'  => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/t4-s2-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/t4-s2-small.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 19', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat' => array(
						__( 'Boxed', 'publisher' ),
						__( 'Simple', 'publisher' ),
					),
				)
			),
			't4-s3'  => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/t4-s3-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/t4-s3-small.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 20', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat' => array(
						__( 'Boxed', 'publisher' ),
					),
				)
			),
			't5-s1'  => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/t5-s1-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/t5-s1-small.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 21', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat' => array(
						__( 'Lined', 'publisher' ),
					),
				)
			),
			't5-s2'  => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/t5-s2-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/t5-s2-small.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 22', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat' => array(
						__( 'Lined', 'publisher' ),
					),
				)
			),


			/***
			 *
			 * Type 6
			 *
			 */
			't6-s1'  => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/t6-s1-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/t6-s1-small.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 23', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat' => array(
						__( 'Creative', 'publisher' ),
					),
				)
			),
			't6-s2'  => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/t6-s2-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/t6-s1-small.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 24', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat' => array(
						__( 'Creative', 'publisher' ),
					),
				)
			),
			't6-s3'  => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/t6-s3-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/t6-s3-small.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 25', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat' => array(
						__( 'Creative', 'publisher' ),
					),
				)
			),
			't6-s4'  => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/t6-s4-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/t6-s4-full.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 26', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat' => array(
						__( 'Creative', 'publisher' ),
					),
				)
			),
			't6-s5'  => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/t6-s5-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/t6-s5-small.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 27', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat' => array(
						__( 'Creative', 'publisher' ),
					),
				)
			),
			't6-s6'  => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/t6-s6-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/t6-s6-small.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 28', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat' => array(
						__( 'Creative', 'publisher' ),
					),
				)
			),
			't6-s7'  => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/t6-s7-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/t6-s7-small.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 29', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat' => array(
						__( 'Creative', 'publisher' ),
					),
				)
			),
			't6-s8'  => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/t6-s8-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/t6-s8-small.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 30', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat' => array(
						__( 'Creative', 'publisher' ),
					),
				)
			),
			't6-s9'  => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/t6-s9-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/t6-s9-small.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 31', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat' => array(
						__( 'Creative', 'publisher' ),
					),
				)
			),
			't6-s10' => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/t6-s10-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/t6-s10-small.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 32', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat' => array(
						__( 'Creative', 'publisher' ),
					),
				)
			),
			't6-s11' => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/t6-s11-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/t6-s11-small.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 33', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat' => array(
						__( 'Creative', 'publisher' ),
					),
				)
			),
			't6-s12' => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/t6-s12-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/t6-s12-small.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 34', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat' => array(
						__( 'Creative', 'publisher' ),
					),
				)
			),
			't1-s6'  => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/t1-s6-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/t1-s6-small.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 35', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat' => array(
						__( 'Lined', 'publisher' ),
					),
				)
			),
			't1-s7'  => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/t1-s7-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/t1-s7-small.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 36', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat' => array(
						__( 'Lined', 'publisher' ),
					),
				)
			),
			't7-s1'  => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/t7-s1-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/t7-s1-small.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 37', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat' => array(
						__( 'Lined', 'publisher' ),
					),
				)
			),
			't1-s8'  => array(
				'img'         => PUBLISHER_THEME_URI . 'images/options/t7-s2-full.png?v=' . PUBLISHER_THEME_VERSION,
				'current_img' => PUBLISHER_THEME_URI . 'images/options/t7-s2-small.png?v=' . PUBLISHER_THEME_VERSION,
				'label'       => __( 'Style 38', 'publisher' ),
				'views'       => FALSE,
				'info'        => array(
					'cat' => array(
						__( 'Lined', 'publisher' ),
					),
				)
			),
		);


		// Add technical name of heading to label for making it easy to develop
		if ( defined( 'BF_DEV_MODE' ) && BF_DEV_MODE ) {
			foreach ( $option as $key => $value ) {
				$option[ $key ]['label'] = $option[ $key ]['label'] . ' - ' . strtoupper( str_replace( '-', ' ', $key ) );
			}
		}

		if ( $default ) {
			$option = array(
				          'default' => array(
					          'img'           => PUBLISHER_THEME_URI . 'images/options/sh-style-default-full.png?v=' . PUBLISHER_THEME_VERSION,
					          'current_img'   => PUBLISHER_THEME_URI . 'images/options/sh-style-default.png?v=' . PUBLISHER_THEME_VERSION,
					          'label'         => __( 'Default', 'publisher' ),
					          'current_label' => __( 'Default Layout', 'publisher' ),
				          )
			          ) + $option;
		}

		return $option;
	} // publisher_cb_heading_option_list
} // if


if ( ! function_exists( 'publisher_sh_t6_s11_fix' ) ) {
	/**
	 * Adds needed svg files into t6-s11 section heading.
	 *
	 * @param array $attr
	 *
	 * @return array
	 */
	function publisher_sh_t6_s11_fix( $attr = array() ) {

		if ( ! empty( $attr['title'] ) ) {

			// Left align SVG
			$attr['title'] .= '<svg xmlns="http://www.w3.org/2000/svg" class="sh-svg-l" width="61" height="33"><path d="M10.2 25.4C10.3 25.4 10.3 25.4 10.3 25.4 10.3 25.4 10.3 25.4 10.3 25.4 10.2 25.4 10.2 25.4 10.2 25.4 10.1 25.4 10.2 25.4 10.2 25.4ZM11.1 25.4C11.1 25.4 11.3 25.4 11.4 25.4 11.5 25.4 11.5 25.4 11.5 25.4 11.4 25.4 11.3 25.5 11.1 25.5 11.1 25.5 11 25.4 11.1 25.4ZM11.2 26.8C10.5 26.9 9.7 26.9 8.9 26.9 8.7 26.9 8.5 26.8 8.3 26.8 8.1 26.7 7.8 26.7 7.6 26.7 7.1 26.5 6.7 26.4 6.9 26.2 7 26 7.5 25.9 7.9 25.7 7.9 25.7 7.8 25.7 7.8 25.7 7.9 25.6 8.1 25.6 8.3 25.6 8.7 25.6 8.9 25.5 9.4 25.5 9 25.6 8.9 25.6 8.7 25.7 9.3 25.7 9.9 25.6 10.5 25.7 11.1 25.7 11.6 25.8 11.9 25.9 12.2 26 12.6 26.1 12.3 26.2 12.3 26.3 12.4 26.3 12.4 26.3 12.4 26.4 12.4 26.6 12 26.6L12 26.6C11.8 26.7 11.5 26.8 11.2 26.8ZM8.9 14.7C8.9 14.7 8.8 14.8 8.6 14.8 8.5 14.8 8.4 14.8 8.4 14.7 8.4 14.7 8.5 14.7 8.7 14.6 8.9 14.6 8.9 14.7 8.9 14.7ZM60.2 31.2C60.1 31.2 60 31.2 59.9 31.2 59.6 31.3 59.3 31.3 58.9 31.3 58.6 31.3 58.5 31.4 58.2 31.4 58.1 31.4 58 31.4 57.9 31.4 57.5 31.3 57.1 31.4 56.8 31.4 56.6 31.4 56.5 31.5 56.3 31.5 55.9 31.4 55.5 31.5 55.3 31.6 55.2 31.6 55 31.6 54.9 31.6 54.4 31.5 54.1 31.6 53.8 31.6 53.5 31.7 53.4 31.7 53 31.7 52.6 31.7 52.5 31.8 52.2 31.8 52.1 31.9 52.5 31.9 52.6 31.9 52.6 31.9 52.6 31.9 52.5 31.9 52.2 32 51.7 32 51.3 32.1 51.2 32.1 51.1 32.1 51 32.1 50.9 32 51 32 51.1 32 51.1 32 51.2 32 51.2 32 51.4 31.9 51.7 31.9 51.4 31.8 51.2 31.8 50.9 31.8 50.8 31.9 50.6 32 50.3 32 49.9 32 49.3 32 49 32.1 48.6 32.1 48.6 32.1 48.5 32.2 48.6 32.2 48.9 32.3 48.5 32.3 48.3 32.3 48.2 32.3 48 32.4 47.9 32.3 47.7 32.3 47.7 32.2 47.9 32.2 48.3 32.2 48.3 32.1 48.1 32.1 48 32 48 32 48.2 31.9 48.5 31.9 48.4 31.8 48.1 31.7 48 31.7 48 31.7 47.9 31.7 47.6 31.6 47.6 31.6 47 31.6 46.2 31.6 45.3 31.6 44.5 31.7 43.7 31.7 42.8 31.7 42 31.7 41.1 31.7 40.2 31.8 39.3 31.8 38.9 31.8 38.6 31.7 38.6 31.6 38.7 31.5 38.6 31.5 38.3 31.5 37.8 31.5 37.3 31.5 36.9 31.5 36.6 31.5 36.4 31.5 36.3 31.4 36.3 31.4 36.2 31.3 36.3 31.3 36.4 31.2 36.3 31.1 36.3 31.1 36.2 30.9 36.2 30.9 36.9 30.9 37.3 30.8 37.2 30.8 36.9 30.8 36.7 30.8 36.5 30.8 36.4 30.7 36.3 30.7 36.3 30.7 36.2 30.7 36.1 30.7 36 30.7 36 30.8 36 30.8 35.9 30.8 35.9 30.8 35.6 30.8 35.4 30.9 35.2 30.9 34.7 30.7 34.4 30.6 33.7 30.6 33.6 30.7 33.6 30.7 33.7 30.7 33.8 30.7 33.9 30.8 33.7 30.8 33.5 30.8 33.4 30.8 33.3 30.8 33.2 30.7 33.1 30.7 33 30.7 32.9 30.6 32.7 30.6 32.7 30.7 32.6 30.8 32.5 30.7 32.3 30.7 31.9 30.7 31.5 30.6 31.2 30.7 30.9 30.7 30.5 30.7 30.2 30.7 29.9 30.8 29.8 30.8 29.7 30.7 29.7 30.7 29.7 30.6 29.6 30.6 29.6 30.6 29.5 30.6 29.4 30.6 29.3 30.6 29.2 30.6 29.2 30.6 29.2 30.7 28.9 30.8 28.5 30.8 28.1 30.8 27.7 30.8 27.3 30.8 27 30.8 26.7 30.8 26.5 30.8 26.4 30.7 26.3 30.7 26.1 30.7 25.8 30.6 25.7 30.6 25.6 30.7 25.5 30.9 25.5 30.9 24.8 30.8 24.5 30.8 24.3 30.8 24.2 30.9 24.2 30.9 24.1 31 24 31 23.6 30.9 23.2 30.9 22.9 30.9 22.7 30.8 23 30.8 22.9 30.7 22.2 30.8 22.1 30.8 22 30.9 22 30.9 22 30.9 22 31 22 31 21.9 31.1 21.7 31.1 21.4 31.1 21.3 31 21.4 31 21.5 30.9 21.3 30.9 21.1 30.9 20.9 30.8 20.8 30.9 20.7 30.9 20.3 30.9 19.9 31 19.6 31.1 19.6 31.1 19.5 31.1 19.4 31.1 19.2 31.1 19.2 31.1 19.2 31.1 19.2 31 19.4 31 19.5 30.9 19.5 30.9 19.6 30.9 19.5 30.9 19.4 30.9 19.3 30.9 19.3 30.9 19.1 31 18.6 31 18.6 31.1 18.5 31.2 18 31.3 17.5 31.2 17.3 31.1 17 31.1 16.8 31.1 16.5 31.2 16 31.2 15.7 31.2 15.6 31.3 15.5 31.3 15.4 31.2 15.2 31.2 15.3 31.2 15.5 31.1 15.8 31.1 16 31.1 16.3 31 16.3 31 16.3 31 16.3 31 16.2 30.9 16.1 30.9 16 31 15.6 31 15.1 31 14.5 31 14.2 31 13.7 31 13.5 31 13.3 31.1 12.9 31.2 12.7 31.2 12.4 31.3 12.4 31.3 12.1 31.2 11.9 31.2 11.6 31.2 11.3 31.2 10.9 31.2 11 31.2 10.8 31.3 10.5 31.4 10.3 31.5 9.6 31.5 9.2 31.5 8.9 31.5 8.7 31.4 8.6 31.4 8.4 31.4 8.3 31.4 7.7 31.3 7.2 31.3 6.6 31.4 6.5 31.4 6.4 31.4 6.3 31.3 6.3 31.3 6.4 31.3 6.5 31.3 6.9 31.3 7.2 31.2 7.5 31.2 8.6 31.2 9.4 31 10.5 31.1 10.9 31.1 11.2 31 11 30.9 10.9 30.9 10.8 30.8 10.6 30.8 10.5 30.8 10.3 30.8 10.1 30.8 10 30.8 9.8 30.8 9.7 30.8 10.2 30.7 10.2 30.7 10.3 30.5 10.4 30.4 10.4 30.4 10.5 30.4 10.8 30.3 10.9 30.3 11.3 30.3 11.5 30.3 11.6 30.3 11.6 30.2 11.6 30.2 11.5 30.2 11.4 30.2 11.2 30.1 10.9 30.1 10.7 30.1 10.6 30.1 10.5 30.1 10.5 30.1 10.4 30.1 10.5 30 10.6 30 10.7 30 10.9 30 11.1 30L11.1 30C11.3 30 11.5 30 11.5 29.9 11.6 29.9 11.7 29.9 11.8 29.9 12 29.9 12.2 29.9 12.3 29.9 12.3 29.9 12.5 29.8 12.5 29.8 12.1 29.8 11.8 29.8 11.9 29.6 12 29.6 11.8 29.6 11.7 29.6 11.5 29.6 11.3 29.7 11 29.7 11 29.6 10.9 29.6 10.9 29.6 10.8 29.6 10.9 29.6 11 29.6 11.4 29.5 11.7 29.5 12 29.4 12.1 29.4 12.3 29.4 12.5 29.4 13.1 29.4 13.6 29.3 13.9 29.2 13.9 29.2 14.1 29.1 14.1 29.2 14.2 29.3 14.5 29.2 14.6 29.2 15 29.1 15.4 29.1 15.7 29.1 16.2 29.1 16.7 29.1 17.1 29.1 17 29 17 29 16.9 28.9 16.7 28.9 16.5 28.9 16.5 28.8 17.6 28.7 19 28.7 20.1 28.5 20.9 28.6 21.7 28.5 22.5 28.4 22.7 28.4 22.9 28.4 23.1 28.4 23.3 28.4 23.6 28.4 23.5 28.3 23.4 28.2 23.2 28.2 22.9 28.2 22.5 28.2 22.1 28.2 21.8 28.3 21.5 28.3 21.3 28.3 21 28.3 20.8 28.2 20.4 28.3 20.2 28.3 19.9 28.3 19.7 28.3 19.4 28.3 18.3 28.3 17.2 28.4 16.2 28.4 16.1 28.4 15.9 28.5 15.7 28.4 15.9 28.4 16.2 28.4 16.4 28.3 17.3 28.3 18.2 28.3 19 28.2 19.3 28.2 19.5 28.1 19.8 28.1 20.3 28.1 20.8 28.1 21.3 28.1 21.7 28.1 22.1 27.9 22.7 28 22.7 28 22.8 28 22.8 28 23.1 27.9 23.6 27.9 24.1 27.8 24.3 27.8 24.4 27.7 24.4 27.7 24.2 27.7 24 27.7 23.7 27.7 23.5 27.7 23.3 27.7 23 27.8 22.9 27.8 22.7 27.8 22.6 27.8 22.5 27.7 22.6 27.7 22.7 27.7 22.7 27.7 22.8 27.6 22.8 27.6 22.9 27.6 22.7 27.6 22.6 27.6 22.5 27.6 22.3 27.6 22.3 27.7 22.3 27.8 21.9 27.8 21.5 27.8 20.8 27.9 20.1 27.9 19.4 28 19.1 28 18.8 28 18.9 27.8 18.9 27.8 18.7 27.7 18.6 27.7 18.5 27.6 18.6 27.6 18.9 27.6 18.9 27.6 18.9 27.6 19 27.6 19 27.6 19 27.6 19 27.6 19 27.6 18.9 27.6 18.9 27.6 19.1 27.5 19.2 27.5 18.8 27.4 18.6 27.4 18.5 27.4 18.6 27.3 18.6 27.3 18.6 27.3 18.5 27.2 18.5 27.2 18.5 27.2 18.5 27.2 18.5 27.2 18.5 27.2 18.5 27.2L18.5 27.2C18.5 27.1 18.6 27 19.1 27 19.2 26.9 19.4 27 19.5 26.9 19.6 26.9 19.6 26.9 19.7 26.9 19.6 26.9 19.4 26.9 19.3 26.8 18.9 26.8 18.5 26.8 18.5 26.7 18.5 26.6 18.3 26.6 18 26.6 17.7 26.6 17.5 26.6 17.2 26.6 16.5 26.6 15.7 26.7 15 26.5 15 26.5 14.9 26.5 14.8 26.5 14.8 26.5 14.7 26.5 14.7 26.5 14.7 26.4 14.8 26.4 15 26.4 15.1 26.4 15.1 26.3 15.2 26.3 15.2 26.3 14.8 26.2 14.9 26.2 15 26.1 14.8 26.1 14.7 26.1 14.2 26.1 14.2 26.1 14.6 26 14.9 26 15 25.9 15.2 25.8 15.2 25.8 14.9 25.8 14.8 25.8 14.8 25.7 14.8 25.7 15 25.6 15.5 25.6 15.1 25.5 14.9 25.5 14.7 25.4 14.6 25.4 14.9 25.4 14.8 25.3 14.6 25.3 14.6 25.3 14.7 25.2 14.7 25.2 14.6 25.2 14.6 25.1 14.7 25.1 14.9 25.1 15 25.1 15 25.1 15 25.1 15 25 14.9 25 14.9 24.9 14.6 24.9 14.3 24.9 14 24.9 13.7 25 13.3 25 13.1 25 12.4 25.1 11.6 25.1 11 25.1 10.5 25.2 10 25.2 9.5 25.2 9 25.2 8.5 25.3 8.2 25.4 8.1 25.3 8 25.2 7.8 25.1 7.6 24.9 7.6 24.9 8.2 24.9 8.4 24.9 8.5 24.8 8.3 24.8 7.8 24.8 7.8 24.7 8.1 24.6 8.2 24.6 8.2 24.5 8.1 24.5 7.8 24.5 7.7 24.6 7.4 24.6 7.3 24.6 7.2 24.6 7.2 24.6 7.1 24.6 7.1 24.5 7.2 24.5 7.6 24.5 7.9 24.3 8.5 24.3 8.5 24.2 8.9 24.2 8.9 24.1 8.8 24.1 8.8 24 9 24 9.3 23.9 9.3 23.9 8.8 23.9 8.4 23.8 8.4 23.8 8.7 23.8 8.8 23.7 8.9 23.7 8.9 23.7 8.9 23.7 9 23.6 9 23.6 8.8 23.5 8.8 23.3 9.3 23.3 9.7 23.2 9.2 23.2 9.2 23.1 9.2 23.1 9.4 23.1 9.6 23.1 9.5 23.1 9.4 23.1 9.3 23.1 8.9 23 8.9 23.2 8.7 23.2 8.1 23.1 8.1 23.1 7.5 23.2 7.4 23.2 7.2 23.2 7.1 23.2 6.6 23.1 5.7 23.2 5.4 23 5.4 23 5.3 23 5.3 23 4.9 23 4.4 23 4 23.1 4 23.1 3.9 23.1 3.8 23.1 3.8 23.1 3.8 23.1 3.8 23 3.8 23 3.8 23 3.9 23 4.2 22.9 4.2 22.9 3.8 22.8 3.7 22.8 3.5 22.8 3.5 22.7 3.6 22.7 3.4 22.6 3.2 22.6 2.9 22.6 2.6 22.6 2.4 22.6 2.3 22.6 2.1 22.6 2.2 22.5 2.2 22.5 2.3 22.4 2.5 22.4 2.7 22.4 2.9 22.4 3.1 22.4 3.1 22.4 3.2 22.4 3.2 22.4 3.2 22.4 3.1 22.4 3.2 22.4 3.2 22.2 3.3 22.2 3.8 22.3 4.1 22.3 4.4 22.3 4.7 22.3 4.8 22.3 4.9 22.3 5 22.3 5.1 22.3 5 22.2 4.9 22.2 4.6 22.2 4.4 22.1 3.9 22.1 3.7 22.1 3.6 22.1 3.7 22 3.8 22 3.8 21.9 3.6 21.9 3.4 21.9 3.2 21.9 3.2 22 3.1 22 3 22 2.6 22 2.1 22 1.5 22 1 22.1 0.8 22.1 0.6 22.1 0.4 22.1 0.2 22.1 0 22 0 21.9 0 21.9 0.3 21.8 0.5 21.8 0.8 21.8 1.1 21.8 1.3 21.8 1.8 21.9 2.3 21.9 2.8 21.8 3.5 21.8 4.2 21.8 4.8 21.7 5.4 21.6 5.9 21.5 6.5 21.5 7.1 21.5 7.6 21.4 8.1 21.4 8.4 21.4 8.6 21.4 8.8 21.4 9.2 21.4 9.5 21.3 10 21.3 10.1 21.4 10.2 21.3 10.3 21.3 10.5 21.2 10.7 21.2 11.1 21.3 11.2 21.3 11.3 21.3 11.3 21.2 11.8 21.1 12.3 21.2 12.8 21.1 13.2 21.1 13.6 21.1 14 21.1 14 21 14 21 13.9 21 13.8 20.9 13.8 20.9 14.1 20.8 14.1 20.8 14 20.8 13.9 20.8 13.7 20.8 13.5 20.8 13.5 20.7 13.5 20.7 13.5 20.6 13.8 20.6 14.2 20.6 14.3 20.5 14.6 20.5 14.9 20.4 14.9 20.4 14.7 20.4 14.3 20.3 14.1 20.3 14.1 20.1 14.1 20.1 14.1 20.1 14 20.1 13.6 20 13.7 19.9 13.8 19.9 13.5 19.8 13.1 19.8 12.9 19.7 13 19.7 13.3 19.7 13.5 19.6 13.8 19.6 13.7 19.5 13.5 19.5 12.7 19.5 12.7 19.4 12.7 19.2 12.7 19.2 12.6 19.2 12.6 19.2 12.2 19.1 12.4 18.9 12.2 18.8 12 18.7 11.8 18.7 11.6 18.6 11.2 18.5 11.2 18.5 11.6 18.4 11.8 18.4 11.9 18.4 11.9 18.3 11.8 18.3 11.8 18.3 11.7 18.3 11.1 18.4 11 18.4 11 18.2 11 18.2 10.9 18.1 10.6 18.1 10.4 18.1 10.1 18.1 9.9 18.1 9.4 18.1 9.2 18.1 9.2 18 9.2 17.9 9.1 17.9 8.9 17.8 8.7 17.8 8.7 17.7 8.9 17.7 9.1 17.7 9.2 17.7 9.3 17.6 9.4 17.6 9.5 17.5 9.8 17.6 10 17.6 10.1 17.5 10.3 17.5 10.4 17.5 10.4 17.5 10.4 17.4 10.3 17.4 10.2 17.4 10.1 17.4 9.8 17.4 9.6 17.4 9.3 17.4 9.1 17.4 8.8 17.5 8.7 17.4 8.5 17.3 8.9 17.3 9.1 17.3 9.3 17.3 9.5 17.2 9.7 17.2 9.8 17.2 9.9 17.2 9.9 17.1 9.6 17.2 9.3 17.2 8.9 17.2 8.7 17.1 8.5 17.1 8.6 17.1 8.7 17 8.3 16.9 8.3 16.8 8.3 16.7 8.2 16.6 7.6 16.5 7.3 16.5 7.3 16.4 7.6 16.4 7.8 16.4 7.9 16.4 8.1 16.3 8.3 16.3 8.3 16.3 8 16.3 7.6 16.2 7.5 16.1 7.8 16 8 16 8.1 16 8.3 16 8.3 15.9 8.3 15.9 8.3 15.8 8.1 15.8 8 15.8 7.8 15.8 7.3 15.8 7.2 15.7 7.6 15.6 7.9 15.5 8.2 15.4 7.9 15.3 7.9 15.3 7.9 15.3 7.9 15.3 8.3 15.2 8.6 15.1 8.9 15.1 9.1 15 9.4 15 9.6 15.1 9.7 15.1 9.8 15.1 9.9 15.1 10.2 15.1 10.2 15 10.4 15 10.7 14.9 10.7 14.9 10.2 14.8 10.1 14.8 10.1 14.8 10.1 14.8 10.5 14.7 10.8 14.7 11 14.6 11.1 14.5 11.2 14.5 11.4 14.5 12.3 14.4 12.9 14.3 13.8 14.2 14 14.2 14.2 14.2 14.4 14.2 14.7 14.2 14.9 14.2 15.2 14.2 15.5 14.1 15.9 14.1 16.2 14 15.9 14 15.7 14 15.5 13.9 15.4 13.9 15.2 13.9 15 13.9 14.2 13.9 13.5 13.9 12.8 13.9 11.9 14 10.9 14 10 14.1 9.8 14.1 9.7 14.1 9.5 14.1 9.7 14 10 14 10.2 14 11.1 13.9 11.9 13.8 13 13.7 13.7 13.7 14.4 13.6 15.1 13.6 15.8 13.5 16.1 13.4 16.2 13.2 16.2 13.2 16.1 13.2 16 13.2 16 13.2 16 13.2 15.9 13.2 15.9 13.2 15.8 13.2 15.8 13.2 15.8 13.2 15.8 13.2 15.8 13.2 15 13.2 14.1 13.2 13.3 13.2 13.1 13.2 12.8 13.3 12.7 13.2 12.6 13.2 12.5 13.1 12.3 13.1 12 13.1 11.8 13.1 11.7 13 11 12.8 10.4 12.6 10.3 12.3 10.3 12.2 10.3 12.2 10.2 12.2 9.5 11.7 10.1 11.2 11 10.8 11 10.8 11.1 10.8 11.2 10.8 11.4 10.6 12 10.6 12.5 10.6 12.9 10.6 13.3 10.6 13.7 10.6 13.7 10.6 13.7 10.6 13.7 10.6 13.7 10.6 13.7 10.6 13.7 10.6L13.7 10.6 13.7 10.6C13.7 10.6 13.8 10.6 13.8 10.6 13.9 10.5 14.3 10.5 14.4 10.5 14.3 10.4 14 10.4 13.8 10.3 13.4 10.2 13.4 10.2 13.7 10.2 13.9 10.1 14.1 10.1 14.3 10.1 14.4 10 14.5 10 14.3 10 13.6 9.9 13.8 9.7 13.7 9.6 13.6 9.4 13.5 9.3 14.2 9.2 14.3 9.2 14.2 9.2 14.1 9.1 14 9.1 13.8 9.1 13.6 9.1 13.2 9 13.1 9 13.4 8.9 13.7 8.8 13.9 8.8 13.7 8.6 13.7 8.6 13.8 8.5 13.9 8.4 13.8 8.4 13.7 8.4 13.5 8.4 13.4 8.4 13.3 8.4 13.3 8.4 13.3 8.4 13.3 8.3 13.4 8.3 14 8.2 14.6 8.1 15.4 8.1 15.2 8.1 15 8.1 14.9 8 14.9 8 14.8 8 14.8 8 14.2 7.8 14.1 7.7 14.2 7.5 13.9 7.5 13.8 7.4 13.9 7.4 14.2 7.3 14 7.2 14 7.1 14 7.1 14 7.1 14 7 13.9 7 14.3 6.9 14.2 6.8 14 6.8 13.8 6.7 13.7 6.6 13.6 6.6 13.4 6.6 13.2 6.5 13.1 6.5 13.1 6.5 13 6.5 12.8 6.5 12.5 6.5 12.5 6.4 12.5 6.4 12.5 6.4 12.5 6.4 12 6.3 11.7 6.2 11.7 6.1 11.8 6.1 11.6 6 11.6 6 11.6 6 11.5 5.9 11.5 5.9 11.8 5.8 11.5 5.8 11.6 5.7 11.8 5.6 11.7 5.5 11.8 5.5 11.9 5.4 11.8 5.4 11.6 5.4 11.4 5.3 11.2 5.3 11.2 5.3 11.3 5.2 11.2 5.1 11.5 5 11.8 4.9 11.8 4.9 11.5 4.8 11.4 4.8 11.3 4.8 11.4 4.7 11.5 4.7 11.3 4.6 10.9 4.6 10.3 4.6 9.7 4.6 9.1 4.7 8.5 4.7 7.8 4.7 7.4 4.5 7.2 4.5 6.9 4.4 6.7 4.4 6.5 4.3 6.3 4.3 6.4 4.2 6.1 4.2 6.2 4.1 6.2 4.1 6.1 4 5.9 4 5.8 4 5.7 3.9 5.6 3.9 5.6 3.8 5.5 3.7 5.4 3.5 5.5 3.3 5.6 3.1 5.9 2.9 6.2 2.7 6.3 2.6 6.7 2.5 7.2 2.5 9.2 2.4 11.2 2.4 13.1 2.4 14 2.3 14.9 2.3 15.7 2.3 16.7 2.3 17.7 2.3 18.6 2.3 19.2 2.2 19.8 2.2 20.4 2.2 21.1 2.2 21.8 2.2 22.5 2.2L22.6 2.2C22.6 2.2 22.6 2.2 22.6 2.2 22.6 2.2 22.5 2.2 22.5 2.2 22.8 2.1 23.1 2.1 23.4 2.1 24.7 2.1 25.9 2 27.2 2 27.4 2 27.6 2 27.8 2 28.1 1.9 28.5 1.9 28.8 1.9 29.5 1.9 30.2 1.9 30.9 1.9 32.1 1.9 33.2 1.8 34.3 1.8 34.6 1.8 34.9 1.8 35.2 1.7 36.1 1.7 36.9 1.7 37.8 1.7 37.9 1.7 38 1.7 38.1 1.7 39.2 1.6 40.3 1.6 41.4 1.5 42.5 1.5 43.6 1.5 44.7 1.5 45.4 1.5 46.1 1.4 46.8 1.4 47.2 1.4 47.7 1.4 48.1 1.4 48.7 1.3 49.2 1.3 49.8 1.3 50.7 1.3 51.7 1.3 52.5 1.3 53.1 1.2 53.6 1.2 54.1 1.2 54.5 1.2 54.9 1.2 55.4 1.2 55.6 1.2 55.9 1.2 56.2 1.2 56.6 1.2 57.1 1.2 57.6 1.2 58.2 1.1 59.7 1.1 60.3 1.1L60.3 31.1C60.3 31.2 60.2 31.2 60.2 31.2ZM14.4 30C14.4 30 14.4 30 14.4 30 14.4 30 14.4 30 14.4 30 14.4 30 14.4 30 14.4 30ZM12.8 24L12.7 24C12.8 24 12.8 24 12.8 24 12.8 24 12.8 24 12.8 24ZM10.4 16.6C10.4 16.6 10.4 16.6 10.4 16.6 10.4 16.6 10.4 16.6 10.5 16.6 10.5 16.6 10.5 16.6 10.4 16.6ZM14.2 10.6L14.1 10.6 14.2 10.6 14.2 10.6ZM9.6 4.5C9.6 4.5 9.7 4.6 9.6 4.6 9.7 4.6 9.7 4.6 9.7 4.6 9.7 4.6 9.6 4.5 9.6 4.5ZM13.8 16.4C13.8 16.4 13.8 16.4 13.8 16.4 13.8 16.4 13.8 16.4 13.8 16.4L13.8 16.4ZM14.3 14.5C14.2 14.5 14.1 14.5 13.9 14.5 13.6 14.5 13.4 14.6 13.1 14.6 13.2 14.6 13.2 14.5 13.3 14.5 13.7 14.6 14 14.6 14.3 14.5 14.4 14.5 14.4 14.5 14.4 14.5 14.5 14.5 14.7 14.5 14.9 14.5 14.7 14.5 14.5 14.5 14.3 14.5ZM16.7 18.4C16.7 18.4 16.7 18.4 16.7 18.4 16.8 18.4 16.8 18.4 16.8 18.4 16.7 18.4 16.7 18.4 16.7 18.4ZM15.6 18.9C15.4 18.9 15.3 19 15.1 19 14.9 19 14.7 19 14.5 19 14.3 19 14.1 19.1 13.9 19.1 14.3 19.1 14.7 19 15.1 19 15.2 19 15.4 18.9 15.5 18.9 15.5 18.9 15.6 18.9 15.6 18.9 16 18.9 16.5 18.8 16.9 18.8 16.5 18.8 16 18.8 15.6 18.9ZM17.9 18.3C17.9 18.3 17.9 18.3 17.9 18.3L17.9 18.3C17.9 18.3 17.9 18.3 17.9 18.3ZM18.3 20.6C18.3 20.6 18.3 20.6 18.2 20.7 18.2 20.6 18.3 20.6 18.3 20.6L18.3 20.6ZM19 15.6C19.1 15.6 19.2 15.6 19.2 15.6 19.2 15.6 19.2 15.6 19.3 15.6 19.2 15.6 19.1 15.6 19 15.6ZM21.1 27.5C21.2 27.5 21.2 27.5 21.2 27.5 21 27.5 20.9 27.5 20.7 27.5 20.7 27.5 20.7 27.5 20.7 27.5 20.8 27.5 21 27.4 21.1 27.5ZM20.4 26.8C20.4 26.8 20.4 26.8 20.4 26.8 20.4 26.8 20.4 26.8 20.4 26.8 20.4 26.8 20.4 26.8 20.4 26.8ZM20.6 23.2C20.6 23.2 20.7 23.2 20.8 23.2 20.8 23.2 20.9 23.2 20.9 23.2 21 23.2 21 23.2 21.1 23.2 20.9 23.2 20.7 23.2 20.6 23.2ZM21.2 20L21.2 20C21.2 20 21.2 20 21.3 20 21.2 20 21.2 20 21.2 20ZM21.2 24.2C21.3 24.2 21.3 24.3 21.3 24.3 21.3 24.3 21.3 24.2 21.4 24.2 21.3 24.2 21.3 24.2 21.2 24.2ZM24.5 18.7C24.5 18.7 24.5 18.7 24.5 18.7 24.5 18.7 24.5 18.7 24.5 18.7 24.5 18.7 24.5 18.7 24.5 18.7ZM24 14.8C24.1 14.8 24 14.8 24.1 14.8 24 14.8 24 14.8 24 14.8 24 14.8 24 14.8 24 14.8ZM23.1 26.5C23 26.5 22.9 26.5 23 26.6 23 26.6 23 26.5 23.1 26.5ZM22.9 26.6C22.9 26.6 23 26.6 23 26.6 23 26.6 22.9 26.6 22.8 26.6 22.9 26.6 22.9 26.6 22.9 26.6ZM22.5 27.4C22.5 27.4 22.4 27.4 22.4 27.4 22.4 27.4 22.4 27.4 22.4 27.4 22.4 27.4 22.5 27.4 22.5 27.4ZM23 2.3C22.7 2.3 22.4 2.3 22.2 2.3 22.5 2.3 22.8 2.3 23.1 2.3 23.1 2.3 23 2.3 23 2.3ZM24 4.9C23.6 4.9 23.2 5 22.8 5 23.5 5 24.2 5 24.9 4.9 24.6 4.9 24.3 4.9 24 4.9ZM24.1 16.6C24.4 16.6 24.7 16.6 25 16.6 24.7 16.6 24.4 16.6 24.1 16.6ZM25.2 17.9C25.2 17.9 25.2 17.9 25.1 17.9 25.2 17.9 25.3 17.9 25.3 17.9 25.3 17.9 25.3 17.9 25.2 17.9ZM32.2 17.1L32.2 17.1C32.2 17.1 32.1 17.1 32.1 17.1 32.1 17.1 32.2 17.1 32.2 17.1ZM31.6 17.2C31.6 17.2 31.6 17.2 31.6 17.2 31.6 17.2 31.6 17.2 31.6 17.2 31.6 17.2 31.6 17.2 31.6 17.2ZM33 15.5C32.5 15.4 31.9 15.4 31.2 15.4 31.6 15.5 32.1 15.4 32.5 15.4 32.7 15.5 32.9 15.5 33 15.5 33.2 15.5 33.3 15.5 33.4 15.5 33.4 15.4 33.6 15.4 33.7 15.4 33.5 15.4 33.3 15.4 33 15.5ZM33.4 25C33.8 25 34.2 24.9 34.5 24.9 34.6 24.9 34.6 24.9 34.7 24.9 34.3 24.9 33.9 25 33.4 25ZM36.2 16.7C36.3 16.7 36.4 16.7 36.4 16.7 36.5 16.7 36.5 16.7 36.6 16.6 36.4 16.7 36.3 16.6 36.1 16.6 36.1 16.6 36.1 16.7 36.2 16.7ZM35.7 24C35.6 24 35.6 24 35.6 24 35.6 24 35.6 24 35.6 24 35.6 24 35.6 24 35.7 24ZM36.5 1.8C36.1 1.8 35.7 1.9 35.3 1.9 35.7 1.9 36.2 1.8 36.7 1.8 36.6 1.8 36.6 1.8 36.5 1.8ZM36.8 31L36.8 31C36.8 31 36.8 31 36.8 31 36.8 31 36.8 31 36.8 31ZM37.4 31.2C37.4 31.2 37.3 31.2 37.3 31.2 37.1 31.2 36.9 31.2 36.8 31.2 36.9 31.2 37.1 31.2 37.3 31.2 37.4 31.2 37.4 31.2 37.5 31.2 37.5 31.2 37.4 31.2 37.4 31.2ZM38.5 30.8C38.5 30.8 38.5 30.8 38.5 30.8 38.5 30.8 38.6 30.8 38.6 30.8 38.6 30.8 38.6 30.8 38.5 30.8ZM38.4 30.9C38.2 31 38.1 31 38 31 38.3 31 38.7 31 39.1 31 38.8 31 38.6 31 38.4 30.9ZM39.6 31.6C39.7 31.6 39.8 31.7 39.9 31.7 40 31.7 40.3 31.7 40.4 31.7 40.5 31.7 40.5 31.7 40.5 31.7 40.2 31.7 39.8 31.7 39.6 31.6ZM44.3 24.7C44.3 24.7 44.4 24.7 44.4 24.7 44.3 24.7 44.2 24.7 44.1 24.7 44.1 24.7 44.2 24.7 44.3 24.7ZM42 23.9L42 23.9C42 23.9 41.9 23.9 41.8 24 41.9 23.9 42.1 23.9 42.2 23.9 42.1 23.9 42.1 23.9 42 23.9ZM42.3 24C42.3 24 42.2 23.9 42.2 23.9 42.3 23.9 42.4 24 42.4 24 42.4 24 42.4 24 42.3 24ZM42.5 24C42.5 24 42.5 24 42.4 24 42.5 24 42.5 24 42.5 24 42.5 24 42.5 24 42.5 24 42.5 24 42.5 24 42.5 24ZM44.2 1.7C44 1.7 43.9 1.8 43.8 1.8 44.2 1.8 44.6 1.7 45 1.7 44.7 1.7 44.4 1.8 44.2 1.7ZM46.7 1.5C46.7 1.5 46.7 1.5 46.7 1.5L46.7 1.5C46.7 1.5 46.7 1.5 46.7 1.5 46.8 1.5 46.9 1.5 46.9 1.4 46.8 1.5 46.8 1.5 46.7 1.5ZM47.3 31.1C47.2 31.1 47.2 31.1 47.2 31.2 47.2 31.2 47.3 31.2 47.4 31.2 47.3 31.1 47.4 31.1 47.3 31.1ZM49.2 30.9C49.1 30.9 49.1 30.9 49.1 30.9 48.9 31 48.7 31 48.5 31 48.7 31 49 31 49.2 30.9 49.2 30.9 49.2 30.9 49.2 30.9ZM49.6 31.6C49.6 31.6 49.6 31.6 49.7 31.6 49.7 31.6 49.7 31.6 49.7 31.6 49.7 31.6 49.6 31.6 49.6 31.6ZM5.7 13.9C5.7 13.9 5.6 13.9 5.5 13.9 5.4 13.9 5.3 13.9 5.2 13.9 5.2 13.9 5.3 13.9 5.4 13.9 5.5 13.9 5.6 13.9 5.7 13.9ZM4.4 15.3C4.1 15.4 3.8 15.3 3.7 15.3 3.6 15.2 3.7 15.1 4 15.1 4.2 15.1 4.3 15.2 4.6 15.3 4.7 15.3 4.7 15.3 4.6 15.3 4.6 15.3 4.5 15.3 4.4 15.3ZM3.6 14.2C3.6 14.1 3.7 14.1 4 14.1 4 14.1 3.9 14.2 3.6 14.2ZM3.2 14.6C3.3 14.5 3.4 14.4 3.3 14.3 3.2 14.3 3.3 14.3 3.5 14.2 3.6 14.4 3.9 14.5 3.2 14.6ZM0.5 23.1C0.7 23.2 1 23.2 1.4 23.2 1.6 23.1 1.8 23.2 1.9 23.2 2 23.3 2 23.3 1.7 23.3 1.5 23.3 1.2 23.3 1.1 23.4 0.5 23.4 0.1 23.2 0.2 23.1 0.3 23.1 0.4 23.1 0.5 23.1ZM1.6 23.8C1.6 23.8 1.5 23.8 1.5 23.8 1.4 23.8 1.4 23.8 1.4 23.8 1.4 23.8 1.5 23.8 1.6 23.8 1.6 23.8 1.7 23.8 1.6 23.8ZM2.2 23.7C2.2 23.7 2.3 23.7 2.2 23.7 2.2 23.8 2.2 23.8 2.1 23.8 2 23.8 2 23.7 2 23.7 2.1 23.7 2.1 23.7 2.2 23.7ZM3.9 24.5C3.9 24.5 3.8 24.5 3.7 24.5 3.5 24.5 3.4 24.5 3.4 24.5 3.4 24.5 3.5 24.5 3.7 24.5 3.8 24.5 3.9 24.5 3.9 24.5ZM4.6 24.7C4.9 24.7 4.6 24.8 4.5 24.8 4.4 24.9 4.5 24.9 4.2 24.9 4.1 24.9 4 24.8 4.1 24.8 4.2 24.7 4.3 24.7 4.6 24.7ZM5.7 24.5C6 24.5 6.3 24.6 6.3 24.6 6.3 24.7 6.1 24.8 5.8 24.8 5.4 24.8 5 24.7 5 24.6 5 24.6 5.5 24.5 5.7 24.5ZM39.9 33.2C40.1 33.2 40.1 33.2 40.2 33.2 40.2 33.3 40.1 33.3 40 33.3 39.9 33.3 39.8 33.3 39.8 33.3 39.8 33.2 39.8 33.2 39.9 33.2ZM46.2 33.1C46.3 33.1 46.4 33.1 46.5 33.1 46.7 33.1 47 33.1 47.1 33.1 47.3 33.2 47 33.2 47 33.2 46.7 33.1 46.5 33.1 46.2 33.1ZM47.1 33.8C46.7 33.8 46.4 33.9 46.1 33.9 46 33.9 45.9 33.9 45.9 33.8 45.9 33.8 45.9 33.8 46 33.8 46.3 33.8 46.7 33.8 47.1 33.8ZM47.7 33.5C47.9 33.5 48.1 33.5 48.1 33.6 48.1 33.6 48.1 33.6 48 33.6 47.8 33.6 47.6 33.6 47.5 33.6 47.5 33.5 47.6 33.5 47.7 33.5ZM47.8 33.1C47.7 33 47.9 33 48 33 48.1 33 48.2 33 48.3 33 48.3 33.1 48.1 33.1 48.1 33.1 47.9 33.1 47.8 33.1 47.8 33.1ZM49 33.6C49 33.6 49 33.6 49 33.6 49 33.6 49 33.6 48.9 33.6 48.9 33.6 48.9 33.6 48.9 33.6 48.9 33.6 49 33.6 49 33.6ZM49.7 33.8C49.9 33.8 50 33.9 49.9 33.9 49.8 34 48.9 34 48.7 34 48.6 34 48.5 33.9 48.5 33.9 48.5 33.9 48.7 33.8 48.8 33.8 49.1 33.8 49.4 33.9 49.7 33.8ZM50 32.5C50.1 32.5 50.2 32.5 50.2 32.6 50.2 32.6 50.1 32.6 50 32.6 49.9 32.6 49.8 32.6 49.7 32.6 49.8 32.5 49.9 32.5 50 32.5ZM51 32.9C51 32.9 50.9 32.9 50.9 32.8 51.1 32.9 51.3 32.9 51 32.9ZM53.2 31.8C53.3 31.9 53.4 31.9 53.4 31.9 53.4 31.9 53.3 31.9 53.2 31.9 53.1 31.9 53.1 31.9 53 31.9 53.1 31.8 53.1 31.8 53.2 31.8ZM53.1 33.2C53.4 33.2 53.5 33.2 53.5 33.2 53.6 33.3 53.4 33.3 53.3 33.3 53 33.3 52.8 33.3 52.9 33.2 52.9 33.2 53 33.2 53.1 33.2ZM55.1 33C55.1 33.1 54.8 33.1 54.6 33.1 54.4 33.1 54.3 33.1 54.4 33 54.4 33 54.6 32.9 54.8 33 55 33 55.1 33 55.1 33ZM55 33.4C55 33.4 55 33.4 54.9 33.4 54.8 33.4 54.7 33.4 54.8 33.4 54.8 33.4 54.8 33.4 54.9 33.4 54.9 33.4 55 33.4 55 33.4ZM56.7 33.3C56.9 33.3 57.3 33.3 57.3 33.4 57.3 33.4 57 33.5 56.8 33.5 56.4 33.5 56.6 33.4 56.5 33.3 56.5 33.3 56.5 33.3 56.7 33.3Z"/></svg>';

			// Right Align SVG
			$attr['title'] .= '<svg xmlns="http://www.w3.org/2000/svg" class="sh-svg-r" width="61" height="33"><path d="M48.92 25.39C49.06 25.38 49.2 25.4 49.26 25.44 49.29 25.44 49.26 25.47 49.2 25.47 49.03 25.47 48.92 25.44 48.85 25.43 48.8 25.41 48.85 25.39 48.92 25.39ZM49.99 25.38C50.02 25.37 50.05 25.36 50.12 25.36 50.14 25.37 50.19 25.38 50.15 25.39 50.15 25.39 50.09 25.4 50.04 25.41 50.02 25.39 49.99 25.38 49.99 25.38ZM53.43 26.16C53.58 26.36 53.27 26.52 52.67 26.65 52.48 26.7 52.24 26.75 52.03 26.8 51.87 26.85 51.67 26.87 51.44 26.87 50.66 26.88 49.87 26.91 49.11 26.81 48.78 26.76 48.54 26.7 48.32 26.64L48.34 26.64C47.91 26.55 47.96 26.44 47.91 26.32 47.91 26.3 48.04 26.26 47.99 26.24 47.7 26.1 48.13 26.01 48.42 25.91 48.77 25.81 49.21 25.74 49.77 25.68 50.43 25.62 51.03 25.67 51.64 25.7 51.45 25.63 51.27 25.55 50.96 25.5 51.42 25.47 51.65 25.55 52 25.58 52.23 25.61 52.46 25.63 52.48 25.7 52.49 25.72 52.44 25.73 52.43 25.75 52.85 25.87 53.3 25.99 53.43 26.16ZM51.44 14.7C51.44 14.67 51.44 14.63 51.62 14.64 51.8 14.65 51.97 14.68 51.97 14.73 51.97 14.76 51.85 14.79 51.73 14.79 51.52 14.78 51.45 14.74 51.44 14.7ZM59.88 22.08C59.71 22.09 59.5 22.1 59.37 22.07 58.85 21.96 58.24 22.01 57.68 22.03 57.37 22.04 57.22 22.04 57.16 21.97 57.12 21.91 56.94 21.91 56.76 21.92 56.55 21.95 56.48 21.97 56.63 22.02 56.74 22.06 56.63 22.08 56.45 22.08 55.97 22.07 55.75 22.17 55.41 22.21 55.31 22.22 55.24 22.25 55.31 22.29 55.38 22.31 55.51 22.33 55.62 22.31 55.89 22.29 56.2 22.3 56.48 22.27 56.98 22.21 57.14 22.23 57.16 22.36 57.17 22.37 57.14 22.38 57.14 22.39 57.14 22.4 57.17 22.41 57.21 22.42 57.4 22.43 57.63 22.43 57.85 22.44 58.03 22.44 58.1 22.48 58.14 22.51 58.23 22.55 58.06 22.56 57.96 22.59 57.72 22.64 57.44 22.64 57.12 22.64 56.91 22.64 56.7 22.65 56.78 22.71 56.84 22.78 56.66 22.81 56.48 22.84 56.09 22.92 56.09 22.92 56.46 23.02 56.48 23.03 56.5 23.04 56.51 23.05 56.53 23.06 56.51 23.06 56.5 23.07 56.45 23.09 56.35 23.09 56.31 23.07 55.97 22.95 55.43 23.05 55.01 23.01 55 23 54.95 23.01 54.95 23.01 54.62 23.19 53.71 23.06 53.27 23.18 53.14 23.21 52.94 23.19 52.79 23.16 52.23 23.06 52.23 23.06 51.67 23.19 51.39 23.16 51.42 23.05 50.99 23.06 50.91 23.06 50.83 23.07 50.75 23.07 50.89 23.07 51.08 23.08 51.16 23.11 51.16 23.17 50.63 23.21 50.98 23.27 51.49 23.34 51.49 23.45 51.37 23.56 51.29 23.62 51.44 23.67 51.42 23.72 51.45 23.73 51.54 23.74 51.59 23.75 51.92 23.83 51.92 23.83 51.55 23.89 51.06 23.92 51.03 23.93 51.34 24 51.49 24.03 51.49 24.06 51.42 24.1 51.42 24.18 51.78 24.21 51.87 24.27 52.41 24.33 52.67 24.46 53.15 24.53 53.23 24.53 53.23 24.57 53.15 24.59 53.08 24.62 52.99 24.6 52.89 24.6 52.62 24.58 52.51 24.51 52.23 24.5 52.15 24.55 52.11 24.59 52.21 24.61 52.48 24.7 52.48 24.76 52.01 24.82 51.87 24.83 51.97 24.87 52.11 24.88 52.71 24.94 52.71 24.94 52.48 25.06 52.31 25.16 52.21 25.26 52.16 25.39 51.83 25.26 51.31 25.25 50.86 25.21 50.35 25.16 49.79 25.2 49.31 25.14 48.69 25.06 47.88 25.09 47.23 25.01 46.99 24.98 46.62 24.98 46.34 24.94 46.05 24.88 45.73 24.88 45.44 24.94 45.44 24.98 45.37 25.03 45.27 25.07 45.32 25.09 45.37 25.09 45.44 25.11 45.64 25.11 45.73 25.13 45.73 25.18 45.6 25.21 45.6 25.24 45.73 25.27 45.77 25.32 45.54 25.35 45.47 25.38 45.7 25.41 45.59 25.43 45.42 25.47 45.24 25.51 44.8 25.57 45.34 25.65 45.49 25.67 45.57 25.73 45.49 25.78 45.44 25.82 45.16 25.78 45.08 25.84 45.32 25.88 45.42 25.96 45.69 26 46.1 26.07 46.1 26.08 45.67 26.11 45.54 26.13 45.34 26.14 45.41 26.16 45.49 26.23 45.08 26.26 45.11 26.33 45.18 26.35 45.24 26.37 45.31 26.39 45.49 26.41 45.59 26.44 45.6 26.48 45.59 26.49 45.57 26.5 45.52 26.5 45.44 26.5 45.34 26.5 45.31 26.5 44.65 26.65 43.87 26.59 43.13 26.58 42.85 26.58 42.59 26.57 42.32 26.57 42.06 26.57 41.86 26.59 41.85 26.65 41.8 26.76 41.45 26.81 41.07 26.84 40.89 26.85 40.73 26.86 40.66 26.91 40.71 26.91 40.76 26.93 40.81 26.93 40.87 26.99 41.12 26.94 41.2 26.99 41.7 27.03 41.83 27.1 41.81 27.18L41.81 27.19C41.81 27.19 41.81 27.2 41.8 27.2 41.78 27.22 41.83 27.23 41.8 27.25 41.75 27.28 41.73 27.31 41.76 27.34 41.85 27.39 41.76 27.41 41.5 27.43 41.09 27.46 41.24 27.52 41.45 27.57 41.42 27.58 41.35 27.58 41.34 27.57 41.37 27.58 41.34 27.58 41.37 27.58 41.38 27.58 41.4 27.57 41.45 27.57 41.75 27.58 41.86 27.62 41.73 27.69 41.6 27.75 41.42 27.8 41.45 27.85 41.53 27.97 41.24 27.96 40.96 27.96 40.2 27.93 39.47 27.89 38.78 27.84 38.42 27.82 37.99 27.8 38.06 27.68 38.06 27.64 37.86 27.61 37.76 27.58 37.63 27.58 37.38 27.55 37.5 27.63 37.54 27.64 37.61 27.65 37.63 27.67 37.76 27.69 37.82 27.73 37.71 27.75 37.58 27.79 37.44 27.76 37.3 27.76 37.03 27.73 36.85 27.66 36.57 27.68 36.31 27.71 36.14 27.67 35.95 27.66 35.9 27.72 36.03 27.84 36.26 27.85 36.7 27.88 37.18 27.9 37.51 27.98 37.54 27.99 37.61 28 37.64 28 38.2 27.95 38.58 28.07 39.06 28.08 39.54 28.11 40.03 28.13 40.53 28.13 40.81 28.13 41.04 28.17 41.29 28.2 42.13 28.27 43.05 28.27 43.89 28.34 44.14 28.35 44.38 28.38 44.63 28.42 44.45 28.45 44.27 28.44 44.12 28.43 43.08 28.35 41.98 28.35 40.91 28.3 40.66 28.29 40.41 28.28 40.16 28.27 39.88 28.26 39.57 28.22 39.27 28.28 39.06 28.33 38.78 28.29 38.55 28.27 38.2 28.23 37.81 28.22 37.41 28.21 37.1 28.19 36.89 28.24 36.82 28.31 36.75 28.37 37 28.39 37.2 28.42 37.38 28.44 37.58 28.43 37.78 28.44 38.62 28.46 39.37 28.56 40.23 28.55 41.32 28.7 42.69 28.66 43.79 28.81 43.77 28.87 43.58 28.9 43.39 28.92 43.33 28.97 43.28 29.02 43.23 29.07 43.66 29.11 44.12 29.11 44.58 29.12 44.96 29.13 45.37 29.12 45.72 29.19 45.87 29.22 46.08 29.25 46.2 29.16 46.25 29.12 46.39 29.17 46.44 29.19 46.72 29.32 47.22 29.37 47.83 29.37 48.04 29.37 48.24 29.39 48.37 29.42 48.67 29.48 48.95 29.55 49.36 29.57 49.44 29.58 49.51 29.6 49.44 29.63 49.43 29.64 49.34 29.64 49.28 29.65 49.05 29.66 48.83 29.64 48.64 29.62 48.54 29.62 48.37 29.63 48.41 29.64 48.57 29.77 48.26 29.8 47.86 29.8 47.83 29.85 48.01 29.86 48.04 29.9 48.16 29.93 48.36 29.9 48.49 29.93 48.59 29.93 48.69 29.93 48.78 29.93 48.87 29.97 49.06 29.98 49.23 30L49.26 30C49.43 30 49.61 30 49.74 30.03 49.81 30.05 49.89 30.07 49.87 30.09 49.87 30.13 49.74 30.13 49.61 30.14 49.39 30.14 49.16 30.13 48.95 30.16 48.85 30.17 48.74 30.19 48.74 30.22 48.74 30.25 48.85 30.28 49 30.28 49.38 30.28 49.56 30.34 49.79 30.39 49.91 30.42 49.95 30.45 49.99 30.48 50.12 30.68 50.12 30.68 50.66 30.77 50.55 30.82 50.35 30.79 50.2 30.79 50.02 30.79 49.84 30.75 49.71 30.78 49.53 30.83 49.38 30.88 49.28 30.93 49.16 30.99 49.44 31.08 49.81 31.07 50.89 31.04 51.77 31.2 52.81 31.23 53.14 31.24 53.42 31.3 53.79 31.29 53.93 31.28 54.03 31.31 53.99 31.35 53.96 31.37 53.83 31.37 53.75 31.36 53.15 31.29 52.59 31.34 52.01 31.38 51.9 31.39 51.72 31.39 51.67 31.41 51.44 31.52 51.09 31.47 50.71 31.48 49.99 31.48 49.82 31.37 49.48 31.28 49.31 31.24 49.38 31.15 49.05 31.16 48.77 31.16 48.44 31.17 48.21 31.21 47.96 31.25 47.91 31.27 47.61 31.21 47.38 31.15 47.07 31.13 46.86 31.04 46.64 30.97 46.15 31 45.8 31.02 45.24 31.04 44.75 31.04 44.28 30.96 44.2 30.95 44.09 30.95 44.04 30.97 43.99 30.99 43.99 31.02 44.07 31.03 44.28 31.08 44.53 31.12 44.81 31.15 45.03 31.16 45.08 31.19 44.96 31.24 44.85 31.28 44.71 31.26 44.6 31.24 44.28 31.16 43.87 31.16 43.49 31.15 43.28 31.14 43.06 31.15 42.87 31.19 42.36 31.28 41.78 31.25 41.75 31.1 41.71 30.98 41.25 30.96 41.05 30.89 41.04 30.88 40.91 30.88 40.86 30.89 40.76 30.91 40.77 30.93 40.84 30.95 40.92 30.98 41.09 31.01 41.12 31.05 41.14 31.08 41.09 31.1 40.97 31.12 40.84 31.15 40.76 31.13 40.67 31.1 40.41 31.01 40.05 30.95 39.67 30.89 39.54 30.87 39.44 30.82 39.23 30.85 39.03 30.87 38.85 30.91 38.91 30.98 38.98 31.03 38.93 31.08 38.65 31.08 38.42 31.08 38.32 31.03 38.3 30.98 38.29 30.95 38.3 30.91 38.29 30.87 38.22 30.78 38.12 30.75 37.46 30.73 37.3 30.77 37.63 30.82 37.43 30.85 37.1 30.89 36.72 30.93 36.34 30.96 36.23 30.97 36.11 30.94 36.08 30.91 36 30.84 35.8 30.83 35.52 30.84 34.84 30.87 34.84 30.87 34.68 30.72 34.59 30.63 34.55 30.63 34.18 30.68 34.03 30.7 33.88 30.72 33.8 30.76 33.66 30.84 33.34 30.84 33.03 30.83 32.62 30.82 32.2 30.79 31.79 30.78 31.43 30.76 31.1 30.74 31.13 30.63 31.15 30.6 31.05 30.59 30.95 30.6 30.85 30.6 30.74 30.6 30.69 30.63 30.67 30.64 30.65 30.67 30.64 30.69 30.54 30.82 30.46 30.82 30.09 30.74 29.8 30.67 29.4 30.74 29.07 30.67 28.79 30.62 28.4 30.66 28.07 30.71 27.87 30.75 27.67 30.77 27.62 30.67 27.59 30.63 27.41 30.63 27.29 30.67 27.18 30.69 27.1 30.73 26.98 30.76 26.9 30.78 26.78 30.8 26.65 30.78 26.45 30.76 26.55 30.73 26.63 30.7 26.68 30.69 26.72 30.67 26.67 30.65 25.88 30.63 25.6 30.67 25.12 30.89 24.89 30.86 24.76 30.79 24.46 30.79 24.41 30.8 24.33 30.78 24.33 30.78 24.33 30.74 24.21 30.74 24.15 30.72 24.06 30.72 24.05 30.74 23.95 30.75 23.8 30.76 23.62 30.76 23.44 30.77 23.07 30.78 23.06 30.82 23.4 30.9 24.15 30.93 24.11 30.93 24 31.1 23.98 31.15 23.95 31.21 24.01 31.25 24.15 31.32 24 31.37 23.98 31.44 23.91 31.51 23.77 31.52 23.44 31.52 22.98 31.51 22.53 31.52 22.07 31.52 21.77 31.53 21.61 31.53 21.67 31.63 21.77 31.75 21.44 31.81 21.03 31.8 20.12 31.78 19.27 31.74 18.36 31.72 17.5 31.71 16.63 31.71 15.77 31.68 14.98 31.64 14.16 31.64 13.35 31.6 12.76 31.56 12.76 31.56 12.41 31.69 12.36 31.72 12.3 31.73 12.21 31.75 11.88 31.79 11.85 31.85 12.1 31.92 12.3 31.97 12.35 32.03 12.2 32.09 12.06 32.14 12.03 32.18 12.38 32.2 12.62 32.22 12.58 32.27 12.44 32.32 12.3 32.37 12.16 32.34 12.02 32.32 11.84 32.28 11.44 32.28 11.74 32.18 11.8 32.17 11.74 32.14 11.69 32.13 11.34 32.05 11.04 31.96 10.43 31.98 10.06 31.99 9.71 31.98 9.49 31.88 9.38 31.84 9.13 31.78 8.88 31.84 8.62 31.9 8.88 31.94 9.08 31.97 9.13 31.98 9.18 31.98 9.23 31.99 9.33 32 9.43 32.03 9.35 32.06 9.26 32.09 9.12 32.09 9 32.08 8.61 32.04 8.16 32.01 7.81 31.95 7.75 31.93 7.71 31.92 7.73 31.9 7.81 31.85 8.18 31.9 8.16 31.81 7.83 31.81 7.68 31.69 7.37 31.72 6.96 31.75 6.79 31.69 6.54 31.65 6.23 31.58 5.93 31.5 5.46 31.6 5.34 31.62 5.09 31.6 4.99 31.56 4.78 31.47 4.45 31.45 4.06 31.46 3.86 31.46 3.71 31.44 3.56 31.42 3.18 31.36 2.84 31.28 2.37 31.39 2.34 31.39 2.24 31.39 2.16 31.39 1.8 31.39 1.68 31.27 1.39 31.27 1.04 31.26 0.71 31.3 0.38 31.25 0.28 31.22 0.2 31.21 0.15 31.18 0.1 31.16 0.05 31.16 0 31.15L0.01 1.1C0.64 1.08 2.16 1.11 2.77 1.18 3.22 1.24 3.68 1.24 4.15 1.21 4.42 1.19 4.7 1.18 4.96 1.2 5.37 1.24 5.8 1.22 6.21 1.23 6.74 1.23 7.25 1.23 7.78 1.25 8.67 1.3 9.59 1.32 10.5 1.33 11.08 1.34 11.65 1.35 12.2 1.38 12.64 1.39 13.1 1.37 13.5 1.43 14.22 1.42 14.95 1.45 15.66 1.47 16.73 1.52 17.82 1.49 18.91 1.54 20.01 1.58 21.16 1.57 22.2 1.69 22.32 1.7 22.4 1.71 22.5 1.7 23.4 1.67 24.26 1.71 25.13 1.74 25.43 1.76 25.71 1.8 25.97 1.81 27.11 1.84 28.25 1.86 29.39 1.88 30.11 1.9 30.82 1.93 31.55 1.93 31.86 1.94 32.24 1.94 32.53 1.99 32.73 1.99 32.91 1.99 33.11 2 34.38 2.05 35.6 2.11 36.89 2.09 37.23 2.09 37.51 2.12 37.81 2.15 37.79 2.16 37.76 2.16 37.74 2.17 37.74 2.17 37.74 2.17 37.76 2.17L37.81 2.15C38.5 2.16 39.19 2.17 39.88 2.17 40.51 2.17 41.09 2.23 41.7 2.26 42.65 2.29 43.63 2.29 44.6 2.32 45.47 2.34 46.34 2.32 47.19 2.35 49.16 2.41 51.16 2.43 53.1 2.5 53.66 2.52 53.99 2.56 54.14 2.68 54.47 2.89 54.7 3.1 54.85 3.32 54.95 3.49 54.78 3.67 54.75 3.85 54.73 3.89 54.67 3.92 54.54 3.95 54.39 3.99 54.22 4.02 54.14 4.07 54.12 4.14 54.22 4.2 53.96 4.24 54.01 4.31 53.81 4.34 53.66 4.38 53.42 4.43 53.15 4.48 52.95 4.54 52.51 4.68 51.87 4.7 51.22 4.65 50.61 4.61 49.99 4.64 49.39 4.63 49.06 4.63 48.83 4.67 48.92 4.75 48.98 4.79 48.9 4.81 48.8 4.84 48.52 4.9 48.54 4.95 48.85 5.01 49.16 5.07 49 5.17 49.11 5.25 49.16 5.3 48.88 5.33 48.69 5.36 48.49 5.38 48.42 5.42 48.52 5.48 48.65 5.54 48.55 5.63 48.72 5.69 48.83 5.76 48.54 5.84 48.8 5.91 48.85 5.92 48.75 5.96 48.72 5.99 48.69 6.02 48.55 6.05 48.59 6.09 48.65 6.24 48.29 6.32 47.84 6.4 47.84 6.41 47.81 6.42 47.81 6.43 47.81 6.52 47.56 6.53 47.28 6.54 47.25 6.54 47.2 6.54 47.17 6.54 46.92 6.56 46.72 6.58 46.61 6.64 46.51 6.71 46.28 6.75 46.16 6.81 46 6.89 46.41 6.96 46.34 7.04 46.28 7.07 46.31 7.1 46.31 7.13 46.34 7.21 46.11 7.29 46.43 7.37 46.56 7.4 46.39 7.45 46.16 7.47 46.26 7.67 46.08 7.84 45.57 7.99 45.52 8.01 45.47 8.01 45.42 8.03 45.34 8.1 45.13 8.06 44.96 8.05 45.69 8.11 46.36 8.2 46.96 8.33 47.02 8.34 47.07 8.36 47.04 8.37 46.99 8.39 46.87 8.41 46.79 8.4 46.62 8.37 46.53 8.42 46.38 8.42 46.48 8.49 46.64 8.57 46.58 8.65 46.46 8.75 46.62 8.8 46.89 8.87 47.19 8.95 47.1 9.04 46.72 9.09 46.56 9.11 46.34 9.11 46.2 9.15 46.1 9.18 46 9.22 46.16 9.24 46.84 9.31 46.72 9.44 46.61 9.56 46.48 9.71 46.71 9.87 46 9.97 45.83 9.99 45.92 10.04 46.05 10.06 46.21 10.1 46.41 10.12 46.58 10.15 46.96 10.23 46.94 10.25 46.56 10.32 46.34 10.36 46 10.38 45.92 10.46 46.05 10.51 46.39 10.5 46.48 10.56 46.53 10.56 46.58 10.56 46.62 10.56L46.62 10.58 46.62 10.6C46.64 10.6 46.64 10.6 46.64 10.6 46.62 10.59 46.61 10.58 46.62 10.56 47.04 10.57 47.45 10.59 47.84 10.62 48.36 10.64 48.88 10.65 49.16 10.77 49.23 10.77 49.31 10.78 49.33 10.8 50.25 11.24 50.8 11.68 50.12 12.16 50.04 12.2 49.99 12.24 49.99 12.28 49.97 12.57 49.31 12.8 48.65 13.03 48.52 13.08 48.29 13.12 48.01 13.14 47.86 13.14 47.75 13.16 47.66 13.19 47.52 13.26 47.25 13.24 47.07 13.24 46.21 13.24 45.37 13.24 44.53 13.24 44.53 13.22 44.53 13.21 44.53 13.2 44.48 13.2 44.43 13.2 44.38 13.2 44.35 13.2 44.32 13.2 44.28 13.2 44.25 13.22 44.12 13.22 44.14 13.25 44.2 13.4 44.57 13.54 45.26 13.58 45.97 13.61 46.59 13.7 47.32 13.72 48.39 13.75 49.21 13.91 50.12 14.01 50.35 14.04 50.63 14.01 50.83 14.07 50.66 14.11 50.48 14.1 50.3 14.08 49.41 14 48.45 14 47.53 13.94 46.84 13.9 46.08 13.91 45.34 13.87 45.11 13.85 44.94 13.88 44.85 13.92 44.66 14 44.4 14.03 44.14 14.04 44.47 14.09 44.8 14.12 45.11 14.18 45.41 14.22 45.67 14.23 45.95 14.2 46.16 14.17 46.34 14.19 46.56 14.2 47.38 14.28 48.04 14.41 48.88 14.47 49.1 14.49 49.2 14.52 49.29 14.57 49.53 14.65 49.79 14.73 50.25 14.76 50.25 14.79 50.19 14.79 50.14 14.8 49.66 14.86 49.66 14.86 49.97 14.97 50.12 15.01 50.14 15.06 50.38 15.1 50.48 15.09 50.6 15.08 50.7 15.07 50.93 15.05 51.17 15.02 51.39 15.06 51.75 15.13 52.06 15.21 52.41 15.29 52.43 15.29 52.43 15.3 52.41 15.3 52.16 15.44 52.43 15.53 52.77 15.63 53.1 15.73 52.99 15.79 52.48 15.84 52.34 15.84 52.2 15.84 52.01 15.84 52.01 15.88 52.01 15.93 52.01 15.98 52.21 15.98 52.36 16 52.48 16.03 52.82 16.12 52.74 16.19 52.28 16.26 52.05 16.28 52.01 16.32 52.26 16.35 52.41 16.36 52.56 16.37 52.67 16.38 52.99 16.43 53.04 16.52 52.71 16.55 52.1 16.6 52.01 16.71 52.05 16.82 52.05 16.93 51.64 16.99 51.75 17.1 51.78 17.13 51.59 17.14 51.44 17.16 51.06 17.2 50.75 17.17 50.4 17.11 50.4 17.18 50.48 17.2 50.63 17.22 50.83 17.23 51.06 17.25 51.26 17.28 51.45 17.3 51.78 17.32 51.65 17.39 51.55 17.47 51.22 17.43 50.99 17.43 50.76 17.42 50.51 17.39 50.27 17.4 50.15 17.4 50.02 17.4 49.97 17.43 49.92 17.47 49.97 17.49 50.05 17.52 50.19 17.55 50.3 17.58 50.5 17.55 50.8 17.53 50.93 17.58 51.06 17.62 51.16 17.65 51.22 17.69 51.39 17.71 51.62 17.75 51.62 17.79 51.44 17.83 51.17 17.88 51.12 17.95 51.09 18.02 51.08 18.13 50.88 18.15 50.4 18.14 50.19 18.13 49.94 18.13 49.72 18.13 49.44 18.13 49.33 18.16 49.33 18.22 49.31 18.37 49.21 18.37 48.65 18.33 48.55 18.32 48.49 18.3 48.39 18.32 48.42 18.37 48.55 18.39 48.69 18.41 49.11 18.51 49.15 18.53 48.72 18.63 48.52 18.69 48.27 18.72 48.13 18.78 47.96 18.92 48.13 19.07 47.7 19.17 47.7 19.19 47.66 19.21 47.66 19.22 47.66 19.35 47.65 19.48 46.84 19.49 46.58 19.49 46.56 19.59 46.81 19.63 46.99 19.68 47.33 19.68 47.45 19.75 47.23 19.83 46.82 19.81 46.51 19.86 46.62 19.93 46.68 20.01 46.34 20.07 46.26 20.08 46.2 20.12 46.2 20.15 46.25 20.25 46.03 20.31 45.64 20.36 45.44 20.38 45.41 20.42 45.69 20.46 46 20.49 46.15 20.59 46.56 20.61 46.81 20.62 46.82 20.69 46.82 20.74 46.84 20.8 46.62 20.8 46.46 20.8 46.36 20.8 46.26 20.81 46.25 20.84 46.51 20.91 46.53 20.91 46.41 20.98 46.33 21.01 46.34 21.04 46.33 21.08 46.72 21.1 47.09 21.14 47.5 21.15 48.01 21.16 48.55 21.15 48.98 21.24 49.05 21.26 49.15 21.28 49.25 21.27 49.61 21.19 49.84 21.24 50.04 21.31 50.12 21.33 50.23 21.36 50.35 21.35 50.78 21.3 51.11 21.36 51.49 21.39 51.72 21.4 51.95 21.43 52.21 21.43 52.77 21.42 53.27 21.47 53.78 21.51 54.47 21.54 54.95 21.62 55.54 21.69 56.15 21.76 56.84 21.83 57.52 21.85 58 21.86 58.54 21.9 59.03 21.85 59.27 21.83 59.53 21.84 59.79 21.84 60.06 21.84 60.29 21.86 60.32 21.93 60.34 22.01 60.16 22.06 59.88 22.08ZM45.95 30.02C45.95 30.01 45.97 30.01 45.97 30.01 45.95 30.01 45.93 30.01 45.92 30.01 45.93 30.01 45.95 30.01 45.95 30.02ZM10.6 31.61C10.63 31.62 10.63 31.62 10.65 31.62 10.7 31.62 10.7 31.6 10.75 31.6 10.7 31.6 10.66 31.62 10.6 31.61ZM11.22 30.93C11.21 30.93 11.18 30.94 11.16 30.94 11.14 30.94 11.16 30.95 11.14 30.95 11.34 30.98 11.59 31 11.82 31.03 11.6 31.01 11.42 30.96 11.22 30.93ZM13.02 31.13C12.97 31.13 12.99 31.15 12.97 31.16 13.02 31.15 13.09 31.15 13.14 31.16 13.12 31.15 13.1 31.13 13.02 31.13ZM16.02 24.68C16.12 24.69 16.2 24.68 16.23 24.67 16.14 24.68 16.04 24.67 15.94 24.68 15.95 24.68 15.99 24.67 16.02 24.68ZM13.63 1.5C13.57 1.48 13.52 1.46 13.47 1.44 13.47 1.47 13.54 1.48 13.63 1.5 13.64 1.5 13.64 1.5 13.65 1.5L13.66 1.5C13.65 1.5 13.64 1.5 13.63 1.5ZM16.17 1.74C15.89 1.75 15.63 1.74 15.36 1.74 15.76 1.74 16.17 1.75 16.56 1.78 16.43 1.76 16.32 1.74 16.17 1.74ZM17.85 23.99C17.85 24 17.83 24 17.83 24 17.85 24 17.85 24 17.85 24 17.85 24 17.85 23.99 17.88 23.98 17.87 23.98 17.87 23.99 17.85 23.99ZM18.02 23.96C17.97 23.96 17.97 23.98 17.9 23.98 17.95 23.95 18.03 23.95 18.1 23.94 18.07 23.95 18.05 23.95 18.02 23.96ZM18.3 23.92L18.28 23.92C18.24 23.92 18.21 23.93 18.17 23.93 18.27 23.94 18.37 23.94 18.48 23.95 18.41 23.94 18.36 23.93 18.3 23.92ZM19.79 31.67C19.83 31.67 19.86 31.67 19.89 31.68 20.06 31.7 20.29 31.72 20.45 31.69 20.55 31.67 20.67 31.65 20.77 31.62 20.49 31.68 20.16 31.69 19.79 31.67ZM21.79 30.78C21.77 30.79 21.74 30.79 21.72 30.79 21.77 30.8 21.79 30.81 21.84 30.81 21.82 30.8 21.79 30.8 21.79 30.78ZM21.95 30.95C21.76 30.99 21.49 30.99 21.24 31.01 21.64 30.98 21.99 30.99 22.33 31.02 22.2 30.99 22.09 30.97 21.95 30.95ZM23.49 31.02C23.49 31.02 23.5 31.01 23.52 31.01L23.54 31.01C23.5 31.01 23.5 31.01 23.49 31.02ZM22.91 31.16C22.88 31.16 22.84 31.16 22.81 31.16 22.91 31.16 22.97 31.17 23.04 31.17 23.26 31.17 23.42 31.18 23.57 31.21 23.43 31.17 23.23 31.18 23.04 31.17 23 31.16 22.95 31.17 22.91 31.16ZM24.72 24C24.72 23.99 24.71 23.99 24.69 23.98 24.67 23.99 24.67 23.99 24.66 24 24.67 24 24.71 24 24.72 24ZM23.88 16.67C23.95 16.69 24.05 16.69 24.15 16.68 24.23 16.66 24.19 16.64 24.18 16.62 24.06 16.65 23.91 16.66 23.77 16.65 23.8 16.65 23.85 16.67 23.88 16.67ZM23.83 1.83C23.77 1.83 23.72 1.84 23.65 1.84 24.11 1.85 24.57 1.86 25.04 1.88 24.64 1.86 24.25 1.83 23.83 1.83ZM28.76 17.19C28.74 17.18 28.76 17.17 28.74 17.16 28.76 17.17 28.71 17.17 28.73 17.19 28.74 17.19 28.76 17.19 28.76 17.19ZM28.12 17.13C28.15 17.13 28.18 17.14 28.23 17.13 28.18 17.13 28.15 17.13 28.1 17.13L28.12 17.13ZM25.61 24.91C25.68 24.92 25.71 24.94 25.79 24.94 26.16 24.95 26.54 24.96 26.91 24.97 26.47 24.96 26.04 24.94 25.61 24.91ZM27.28 15.46C27.04 15.45 26.82 15.42 26.62 15.38 26.73 15.41 26.88 15.44 26.93 15.49 27.04 15.47 27.16 15.47 27.28 15.46 27.45 15.46 27.63 15.46 27.84 15.45 28.23 15.41 28.69 15.47 29.07 15.43 28.47 15.38 27.86 15.39 27.28 15.46ZM37.94 27.43C37.92 27.43 37.91 27.43 37.89 27.42 37.87 27.43 37.84 27.43 37.81 27.43 37.86 27.43 37.89 27.43 37.94 27.43ZM37.4 26.62C37.43 26.62 37.46 26.63 37.5 26.63 37.44 26.62 37.33 26.61 37.35 26.59 37.36 26.61 37.4 26.61 37.4 26.62ZM37.35 26.59C37.38 26.54 37.33 26.51 37.23 26.49 37.3 26.52 37.33 26.55 37.35 26.59ZM36.29 14.82C36.32 14.82 36.34 14.82 36.36 14.82 36.32 14.82 36.29 14.82 36.26 14.82 36.27 14.82 36.26 14.82 36.29 14.82ZM35.81 18.74C35.83 18.74 35.85 18.74 35.85 18.74 35.81 18.74 35.81 18.74 35.8 18.74 35.81 18.74 35.81 18.74 35.81 18.74ZM35.11 17.89C35.06 17.89 35.04 17.88 35.01 17.88 35.06 17.88 35.12 17.89 35.19 17.9 35.15 17.89 35.12 17.89 35.11 17.89ZM36.21 16.64C35.91 16.57 35.67 16.57 35.37 16.61 35.67 16.59 35.95 16.61 36.21 16.64ZM36.32 4.94C36.03 4.94 35.72 4.94 35.42 4.94 36.11 4.96 36.8 4.97 37.48 5 37.1 4.97 36.7 4.95 36.32 4.94ZM37.35 2.27C37.3 2.27 37.26 2.27 37.21 2.28 37.51 2.27 37.82 2.27 38.14 2.27 37.87 2.27 37.61 2.27 37.35 2.27ZM39.18 27.48C39.31 27.44 39.47 27.45 39.65 27.46 39.64 27.45 39.62 27.45 39.62 27.45 39.44 27.45 39.29 27.46 39.11 27.47 39.13 27.48 39.16 27.47 39.18 27.48ZM39.04 24.26C39.06 24.25 39.06 24.25 39.08 24.24 39.04 24.24 39.01 24.24 38.96 24.24 38.98 24.25 39.01 24.25 39.04 24.26ZM39.11 20.04C39.09 20.04 39.08 20.04 39.06 20.04 39.09 20.04 39.11 20.04 39.13 20.04L39.11 20.04ZM39.23 23.23C39.27 23.23 39.32 23.23 39.37 23.23 39.44 23.23 39.51 23.23 39.57 23.23 39.65 23.23 39.69 23.21 39.75 23.2 39.59 23.22 39.42 23.24 39.23 23.23ZM39.88 26.81C39.88 26.82 39.88 26.82 39.9 26.82 39.92 26.82 39.93 26.81 39.95 26.81 39.93 26.81 39.9 26.81 39.88 26.81ZM41.07 15.59C41.09 15.6 41.12 15.6 41.14 15.61 41.17 15.6 41.22 15.6 41.29 15.6 41.2 15.59 41.14 15.6 41.07 15.59ZM42.01 20.63L41.99 20.63C42.03 20.64 42.08 20.65 42.11 20.65 42.04 20.65 42.03 20.63 42.01 20.63ZM42.39 18.29L42.41 18.3C42.41 18.3 42.41 18.3 42.42 18.3 42.41 18.3 42.41 18.29 42.39 18.29ZM50.68 4.56C50.66 4.55 50.7 4.55 50.7 4.55 50.68 4.55 50.66 4.55 50.63 4.56 50.65 4.56 50.66 4.56 50.68 4.56ZM46.16 10.59L46.18 10.59 46.16 10.59 46.16 10.59ZM49.97 16.64C49.94 16.63 49.95 16.62 49.89 16.62 49.85 16.62 49.87 16.63 49.85 16.64 49.89 16.64 49.94 16.63 49.97 16.64ZM43.56 18.43C43.56 18.43 43.56 18.43 43.58 18.43 43.59 18.43 43.59 18.43 43.61 18.43 43.59 18.43 43.58 18.43 43.56 18.43ZM46.99 14.55C47.1 14.54 47.15 14.56 47.23 14.57 46.96 14.55 46.72 14.49 46.39 14.53 46.26 14.55 46.13 14.54 45.98 14.53 45.81 14.52 45.63 14.51 45.45 14.5 45.62 14.5 45.78 14.52 45.95 14.53 45.96 14.53 45.97 14.53 45.98 14.53 46.3 14.55 46.62 14.57 46.99 14.55ZM46.53 16.4L46.53 16.4C46.49 16.4 46.49 16.39 46.48 16.39 46.49 16.4 46.53 16.4 46.53 16.4ZM46.39 19.11C46.23 19.07 46.06 19.04 45.85 19.01 45.66 18.99 45.45 18.98 45.25 18.97 45.07 18.95 44.9 18.93 44.72 18.91 44.32 18.84 43.85 18.84 43.38 18.82 43.83 18.84 44.28 18.88 44.72 18.91 44.75 18.92 44.78 18.92 44.81 18.93 44.94 18.95 45.09 18.96 45.25 18.97 45.64 19.01 46.03 19.05 46.39 19.11ZM47.56 23.98C47.55 23.98 47.53 23.98 47.52 23.98 47.55 23.98 47.56 23.98 47.58 23.99L47.56 23.98ZM56.83 14.24C57.02 14.26 57.14 14.29 57.04 14.35 56.91 14.42 56.98 14.49 57.09 14.56 56.43 14.49 56.7 14.36 56.83 14.24ZM56.37 14.07C56.65 14.09 56.7 14.14 56.7 14.2 56.43 14.18 56.37 14.12 56.37 14.07ZM56.61 15.29C56.5 15.35 56.18 15.35 55.89 15.35 55.79 15.35 55.69 15.34 55.67 15.32 55.62 15.29 55.64 15.26 55.74 15.26 56.04 15.22 56.09 15.14 56.31 15.1 56.66 15.14 56.76 15.21 56.61 15.29ZM54.78 13.92C54.7 13.91 54.62 13.91 54.67 13.89 54.73 13.85 54.85 13.86 54.96 13.87 55.05 13.87 55.11 13.88 55.08 13.9 55.05 13.93 54.9 13.93 54.78 13.92ZM3.81 33.33C3.68 33.38 3.87 33.47 3.5 33.48 3.28 33.49 3.03 33.44 3.03 33.39 3.07 33.32 3.38 33.26 3.64 33.27 3.79 33.27 3.86 33.29 3.81 33.33ZM5.97 33.03C6 33.07 5.92 33.11 5.72 33.11 5.51 33.12 5.21 33.06 5.18 33.01 5.19 32.97 5.31 32.95 5.47 32.95 5.72 32.95 5.92 32.96 5.97 33.03ZM5.57 33.42C5.59 33.43 5.51 33.44 5.44 33.43 5.36 33.43 5.31 33.42 5.31 33.4 5.32 33.39 5.39 33.37 5.46 33.38 5.51 33.39 5.57 33.4 5.57 33.42ZM6.96 31.88C6.94 31.86 7.01 31.85 7.09 31.84 7.19 31.84 7.27 31.84 7.3 31.88 7.27 31.9 7.22 31.92 7.12 31.92 7.02 31.92 6.96 31.9 6.96 31.88ZM7.47 33.23C7.48 33.29 7.3 33.3 7.02 33.29 6.92 33.29 6.76 33.29 6.77 33.24 6.79 33.18 6.96 33.16 7.19 33.16 7.35 33.16 7.47 33.18 7.47 33.23ZM9.45 32.85C9.38 32.87 9.35 32.89 9.3 32.92 9.07 32.86 9.25 32.86 9.45 32.85ZM10.58 32.57C10.52 32.6 10.42 32.62 10.3 32.62 10.19 32.62 10.1 32.6 10.1 32.58 10.1 32.54 10.24 32.54 10.37 32.54 10.47 32.54 10.55 32.54 10.58 32.57ZM11.31 33.6C11.34 33.59 11.39 33.59 11.42 33.59 11.42 33.61 11.41 33.61 11.41 33.62 11.34 33.63 11.31 33.62 11.29 33.61 11.29 33.61 11.31 33.6 11.31 33.6ZM10.66 33.83C10.93 33.86 11.22 33.85 11.52 33.85 11.67 33.85 11.78 33.86 11.8 33.9 11.84 33.93 11.77 33.96 11.67 33.97 11.37 34.03 10.55 33.98 10.38 33.9 10.3 33.86 10.42 33.82 10.66 33.83ZM12.54 33.05C12.53 33.07 12.43 33.08 12.26 33.08 12.2 33.07 12.02 33.06 12.06 33.02 12.08 32.99 12.2 32.99 12.3 32.99 12.46 32.99 12.58 33 12.54 33.05ZM12.77 33.56C12.69 33.59 12.51 33.61 12.33 33.61 12.25 33.61 12.2 33.59 12.2 33.56 12.26 33.52 12.4 33.51 12.59 33.51 12.69 33.52 12.82 33.54 12.77 33.56ZM13.78 33.09C13.9 33.1 14.03 33.08 14.11 33.11 13.84 33.13 13.6 33.15 13.33 33.16 13.28 33.15 13.07 33.16 13.22 33.13 13.37 33.11 13.6 33.11 13.78 33.09ZM14.31 33.8C14.41 33.81 14.47 33.82 14.47 33.85 14.44 33.89 14.29 33.89 14.19 33.89 13.9 33.87 13.6 33.85 13.22 33.83 13.61 33.77 13.98 33.78 14.31 33.8ZM20.52 33.25C20.52 33.28 20.45 33.28 20.32 33.28 20.21 33.27 20.11 33.25 20.12 33.22 20.17 33.19 20.24 33.16 20.39 33.18 20.55 33.19 20.52 33.22 20.52 33.25ZM54.59 24.47C54.8 24.47 55.28 24.57 55.28 24.62 55.28 24.71 54.95 24.8 54.55 24.79 54.27 24.79 53.99 24.7 53.99 24.62 53.99 24.56 54.32 24.47 54.59 24.47ZM56.2 24.79C56.35 24.84 56.27 24.9 56.14 24.94 55.87 24.92 55.9 24.86 55.82 24.82 55.74 24.77 55.43 24.71 55.77 24.68 56.04 24.65 56.09 24.74 56.2 24.79ZM56.93 24.49C56.94 24.53 56.81 24.54 56.66 24.54 56.51 24.54 56.43 24.53 56.4 24.5 56.43 24.47 56.51 24.45 56.66 24.45 56.78 24.45 56.91 24.45 56.93 24.49ZM58.28 23.72C58.31 23.74 58.31 23.75 58.23 23.76 58.14 23.76 58.1 23.75 58.1 23.73 58.06 23.72 58.1 23.71 58.11 23.71 58.18 23.69 58.26 23.7 58.28 23.72ZM58.95 23.17C59.35 23.2 59.6 23.18 59.81 23.09 59.88 23.06 60.06 23.07 60.09 23.11 60.22 23.23 59.78 23.36 59.27 23.36 59.1 23.33 58.84 23.34 58.59 23.33 58.33 23.33 58.28 23.28 58.41 23.24 58.52 23.19 58.71 23.15 58.95 23.17ZM58.89 23.79C58.9 23.8 58.9 23.83 58.84 23.83 58.82 23.83 58.74 23.83 58.71 23.82 58.67 23.8 58.71 23.79 58.77 23.77 58.84 23.77 58.89 23.77 58.89 23.79Z"/></svg>';

		}

		return $attr;
	} // publisher_sh_t6_s11_fix
}


// Includes panel blocks setting field generator callback only in admin
if ( is_admin() ) {
	include PUBLISHER_THEME_PATH . 'includes/options/fields-cb.php';
}


if ( ! function_exists( 'publisher_improve_block_atts_for_size' ) ) {
	/**
	 * Calculate and improve attributes of blocks for making them in-column responsive
	 *
	 * @param        $atts
	 * @param string $block_type
	 *
	 * @return mixed
	 */
	function publisher_improve_block_atts_for_size( $atts, $block_type = 'columns' ) {

		switch ( $block_type ) {

			// Base columns grid
			case 'mg-3':
			case 'columns':

				$size = publisher_get_block_size();

				$_check = array(
					2 => array(
						4 => 1,
						3 => 1,
						2 => 1,
						1 => 1,
					),
					3 => array(
						6 => 2,
						5 => 2,
						4 => 1,
						3 => 1,
						2 => 1,
						1 => 1,
					),
					4 => array(
						10 => 4,
						9  => 3,
						8  => 3,
						7  => 3,
						6  => 2,
						5  => 2,
						4  => 1,
						3  => 1,
						2  => 1,
						1  => 1,
					),
				);

				if ( isset( $_check[ $atts['columns'] ][ $size ] ) ) {
					$atts['columns'] = $_check[ $atts['columns'] ][ $size ];
				}
				break;


			// small columns used in thumbnail listing 2
			case 'small-columns':

				$size = publisher_get_block_size();

				$_check = array(
					2 => array(
						4 => 2,
						3 => 2,
						2 => 2,
						1 => 2,
					),
					3 => array(
						6 => 3,
						5 => 3,
						4 => 2,
						3 => 2,
						2 => 2,
						1 => 2,
					),
					4 => array(
						9 => 4,
						8 => 4,
						7 => 4,
						6 => 3,
						5 => 3,
						4 => 2,
						3 => 2,
						2 => 2,
						1 => 2,
					),
					5 => array(
						10 => 4,
						9  => 4,
						8  => 4,
						7  => 4,
						6  => 3,
						5  => 3,
						4  => 2,
						3  => 2,
						2  => 2,
						1  => 2,
					),
				);

				if ( isset( $_check[ $atts['columns'] ][ $size ] ) ) {
					$atts['columns'] = $_check[ $atts['columns'] ][ $size ];
				}

				break;


			case 'mix-2';
			case 'mix-1';

				$size = publisher_get_block_size();

				if ( $size <= 6 ) {
					$atts['mix-layout'] = 'l-1-col';
				}
				break;

			case 'mix-1-4';

				$size = publisher_get_block_size();

				if ( $size <= 4 ) {
					$atts['mix-layout'] = 'l-1-col';
				}
				break;

			case 'mix-4';
			case 'mix-3';

				$size = publisher_get_block_size();

				if ( $size <= 5 ) {
					$atts['mix-layout'] = 'l-1-col';
				}
				break;

			case 'mg-9';
			case 'mg-2';
			case 'mg-1';

				$size = publisher_get_block_size();

				$_check = array(
					10 => 'l-1',
					9  => 'l-1',
					8  => 'l-2',
					7  => 'l-2',
					6  => 'l-3',
					5  => 'l-4',
					4  => 'l-5',
					3  => 'l-5',
					2  => 'l-5',
					1  => 'l-5',
				);

				if ( isset( $_check[ $size ] ) ) {
					$atts['mg-layout'] = $_check[ $size ];
				}

				break;

			case 'mg-4';

				$size = publisher_get_block_size( 50 );

				$_check = array(
					10 => 'l-1',
					9  => 'l-1',
					8  => 'l-2',
					7  => 'l-2',
					6  => 'l-3',
					5  => 'l-4',
					4  => 'l-5',
					3  => 'l-5',
					2  => 'l-5',
					1  => 'l-5',
				);

				if ( isset( $_check[ $size ] ) ) {
					$atts['mg-layout'] = $_check[ $size ];
				}

				$size = publisher_get_block_size( 100 );

				$_check = array(
					2 => array(
						4 => 1,
						3 => 1,
						2 => 1,
						1 => 1,
					),
					3 => array(
						6 => 2,
						5 => 2,
						4 => 1,
						3 => 1,
						2 => 1,
						1 => 1,
					),
					4 => array(
						10 => 4,
						9  => 3,
						8  => 3,
						7  => 3,
						6  => 2,
						5  => 2,
						4  => 1,
						3  => 1,
						2  => 1,
						1  => 1,
					),
				);

				if ( isset( $_check[ $atts['columns'] ][ $size ] ) ) {
					$atts['columns'] = $_check[ $atts['columns'] ][ $size ];
				}

				break;


			case 'mg-5';

				$size = publisher_get_block_size( 50 );

				if ( $size > 750 ) {
					$atts['mg-layout'] = 'l-1';
				} else {
					$atts['mg-layout'] = 'l-2';
				}

				break;

			case 'mg-10';
			case 'mg-8';

				$size = publisher_get_block_size();

				$_check = array(
					10 => 'l-1',
					9  => 'l-1',
					8  => 'l-2',
					7  => 'l-2',
					6  => 'l-3',
					5  => 'l-4',
					4  => 'l-4',
					3  => 'l-4',
					2  => 'l-4',
					1  => 'l-4',
				);

				if ( isset( $_check[ $size ] ) ) {
					$atts['mg-layout'] = $_check[ $size ];
				}

				break;

			case 'slider-1';

				$size = publisher_get_block_size();

				$_check = array(
					8 => 'l-1',
					7 => 'l-1',
					6 => 'l-1',
					5 => 'l-1',
					4 => 'l-2',
					3 => 'l-2',
					2 => 'l-2',
					1 => 'l-2',
				);

				if ( isset( $_check[ $size ] ) ) {
					$atts['mg-layout'] = $_check[ $size ];
				}

				break;

			case 'slider-2';

				$size = publisher_get_block_size();

				$_check = array(
					8 => 'l-1',
					7 => 'l-1',
					6 => 'l-1',
					5 => 'l-2',
					4 => 'l-2',
					3 => 'l-3',
					2 => 'l-3',
					1 => 'l-3',
				);

				if ( isset( $_check[ $size ] ) ) {
					$atts['mg-layout'] = $_check[ $size ];
				}

				break;

			case 'slider-3';

				$size = publisher_get_block_size();

				$_check = array(
					8 => 'l-1',
					7 => 'l-1',
					6 => 'l-1',
					5 => 'l-2',
					4 => 'l-3',
					3 => 'l-3',
					2 => 'l-3',
					1 => 'l-3',
				);

				if ( isset( $_check[ $size ] ) ) {
					$atts['mg-layout'] = $_check[ $size ];
				}

				break;

		}

		return $atts;
	}
}


if ( ! function_exists( 'publisher_the_post' ) ) {
	/**
	 * Overrided to support "after x posts ad" automatically.
	 *
	 * Custom the_post for custom counter functionality
	 */
	function publisher_the_post() {

		// If count customized
		if ( publisher_get_prop( 'posts-count', NULL ) != NULL ) {
			publisher_set_prop( 'posts-counter', absint( publisher_get_prop( 'posts-counter', 1 ) ) + 1 );
		}


		//
		// Injects ad after x number of posts
		//
		{
			if ( publisher_is_ad_plugin_active() ) {
				$inject_ads = publisher_get_prop( 'block-ad', FALSE );
			} else {
				$inject_ads = FALSE;
			}

			if ( $inject_ads ) {

				//
				// Smart after x post ads in ajax pagination
				///
				if ( bf_is_doing_ajax() ) {
					$paged          = publisher_get_query()->get( 'paged', 2 ) - 1;
					$posts_per_page = publisher_get_query()->get( 'posts_per_page', 1 );
					$current_plus   = ( $paged * $posts_per_page ) + publisher_get_query()->current_post + 1;
				} else {
					$current_plus = publisher_get_query()->current_post + 1;
				}

				$inject_ads_after = $inject_ads['after_each'];

				if ( $inject_ads_after &&
				     $current_plus > 1 &&
				     ( ( $current_plus % $inject_ads_after ) === 0 )
				) {
					publisher_show_after_posts_ad( array(
						'class' => publisher_get_prop( 'block-ad-class', '' ),
						'ad'    => $inject_ads,
					) );
				}
			}
		}


		// Do default the_post
		publisher_get_query()->the_post();

		// Clear post cache for this post
		publisher_clear_post_cache();
	}
}


if ( ! function_exists( 'publisher_get_heading_style' ) ) {
	/**
	 * Returns heading style for $atts of block or by the ID
	 *
	 * @param null $atts
	 *
	 * @return mixed|null|string
	 */
	function publisher_get_heading_style( $atts = NULL ) {

		$heading_style = 'default';

		$_check = array(
			''        => '',
			'default' => '',
		);


		//
		// Atts is null, sidebar heading or panel
		//
		if ( is_null( $atts ) ) {

			if ( bf_get_current_sidebar() ) {

				$_check_2 = array(
					'footer-1' => '',
					'footer-2' => '',
					'footer-3' => '',
					'footer-4' => '',
				);

				if ( isset( $_check_2[ bf_get_current_sidebar() ] ) ) {
					$heading_style = publisher_get_option( 'footer_widgets_heading_style' );
				}

				if ( isset( $_check[ $heading_style ] ) ) {
					$heading_style = publisher_get_option( 'widgets_heading_style' );
				}
			}
		} elseif ( $atts === 'general' ) {
			return $heading_style = publisher_get_option( 'section_heading_style' );
		} elseif ( $atts === 'widget' ) {

			$_check_2 = array(
				'footer-1' => '',
				'footer-2' => '',
				'footer-3' => '',
				'footer-4' => '',
			);

			if ( isset( $_check_2[ bf_get_current_sidebar() ] ) ) {
				$heading_style = publisher_get_option( 'footer_widgets_heading_style' );
			}

			if ( isset( $_check[ $heading_style ] ) ) {
				$heading_style = publisher_get_option( 'widgets_heading_style' );
			}
		} else {

			if ( isset( $atts['heading_style'] ) && ! isset( $_check[ $atts['heading_style'] ] ) ) {
				$heading_style = $atts['heading_style'];
			} elseif ( isset( $atts['bf-widget-title-style'] ) && ! isset( $_check[ $atts['bf-widget-title-style'] ] ) ) {
				$heading_style = $atts['bf-widget-title-style'];
			} elseif ( bf_get_current_sidebar() ) {

				$_check_2 = array(
					'footer-1' => '',
					'footer-2' => '',
					'footer-3' => '',
					'footer-4' => '',
				);

				if ( isset( $_check_2[ bf_get_current_sidebar() ] ) ) {
					$heading_style = publisher_get_option( 'footer_widgets_heading_style' );
				}

				if ( isset( $_check[ $heading_style ] ) ) {
					$heading_style = publisher_get_option( 'widgets_heading_style' );
				}
			}
		}


		if ( isset( $_check[ $heading_style ] ) ) {
			$heading_style = publisher_get_option( 'section_heading_style' );
		}

		return $heading_style;
	}
}


if ( ! function_exists( 'bf_get_current_sidebar' ) ) {
	/**
	 * Used For retrieving current sidebar
	 *
	 * @since 2.5.5
	 *
	 * @return string
	 */
	function bf_get_current_sidebar() {

		$current_sidebar = Better_Framework::widget_manager()->get_current_sidebar();

		if ( $current_sidebar ) {
			return $current_sidebar;
		}


		if ( publisher_get_global( 'bs-vc-sidebar-column', FALSE ) ) {
			return 'bs-vc-sidebar-column';
		}
	}
}

if ( ! function_exists( 'publisher_is_singular' ) ) {

	/**
	 * Is the query for an existing single post of any post type (post, attachment, page, ... )?
	 *
	 * @param string $post_types
	 *
	 * @since 4.0.0
	 * @return bool Whether the query is for an existing single post of any of the given post types.
	 */
	function publisher_is_singular( $post_types = '' ) {

		$is_singular = is_singular( $post_types );

		if ( ! $is_singular ) {

			$queried_object = get_queried_object();

			// "The Event Calendar" Plugin Hot Fix

			$is_singular =
				$queried_object instanceof WP_Post_Type && 'tribe_events' === $queried_object->name ||  #  WP >= 4.6.0
				$queried_object instanceof WP_Post && 'tribe_events' === $queried_object->post_type;    #  WP < 4.6.0
		}

		return $is_singular;
	}
}