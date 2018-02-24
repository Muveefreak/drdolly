<?php
/**
 * Post template style 9.
 *
 * @author     BetterStudio
 * @package    Publisher
 * @version    2.0
 */

global $post;

// Generate layout settings
$layout_setting = publisher_get_page_layout_setting();

$post_settings = publisher_get_option( 'post-page-settings' );

$social_share               = publisher_get_option( 'social_share_single' );
$is_top_social_share_active = $social_share == 'show' || $social_share == 'top-bottom';

if ( publisher_get_header_style() !== 'disable' ) {

	// Shows breadcrumb
	if ( publisher_show_breadcrumb() ) {
		Better_Framework()->breadcrumb()->generate( array(
			'before'       => '<div class="container bf-breadcrumb-container">',
			'after'        => '</div>',
			'custom_class' => 'bc-top-style'
		) );
		$layout_setting['container'] .= ' layout-bc-before';
	}

	// After header Ad
	publisher_show_ad_location( 'header_after', array(
			'container-class' => 'adloc-after-header',
			'before'          => '<div class="container adcontainer">',
			'after'           => '</div>',
		)
	);
}

// Show post excerpt ? Where?
if ( $show_excerpt = ! empty( $post->post_excerpt ) ) {
	$show_excerpt = bf_get_post_meta( 'single_excerpt_type', get_the_ID(), 'default' );
	if ( $show_excerpt === 'default' ) {
		if ( $post_settings['excerpt'] ) {
			$show_excerpt = $post_settings['excerpt_type'];
		} else {
			$show_excerpt = FALSE;
		}
	}
}

/**
 * Fires before ".content-wrap" start
 *
 * @since 1.9.0
 */
do_action( 'publisher/content-wrap/before' );

?>
<div class="content-wrap">
	<?php

	/**
	 * Fires in start of".content-wrap"
	 *
	 * @since 1.9.0
	 */
	do_action( 'publisher/content-wrap/start' );

	?>
	<main <?php publisher_attr( 'content', '' ); ?>>

		<div class="container <?php echo $layout_setting['container']; // escaped before ?> post-template-9">

			<div class="row main-section">
				<?php

				foreach ( $layout_setting['columns'] as $column ) {

					if ( $column['id'] == 'content' ) {
						?>
						<div class="<?php echo $column['class']; ?>">
							<div class="single-container">
								<?php

								// Above Post Ad
								publisher_show_ad_location( 'post_box_above', array( 'container-class' => 'adloc-above-post-box' ) );

								?>
								<article <?php publisher_attr( 'post', 'single-post-content' ); ?>>
									<div class="post-header-inner">
										<div class="post-header-title">
											<?php

											if ( $post_settings['term'] || $post_settings['format-icon'] ) {
												publisher_set_prop( 'term-badge-tax', $post_settings['term-tax'] );
												publisher_cats_badge_code(
													$post_settings['term-count'],
													'',
													$post_settings['format-icon'],
													TRUE,
													'floated',
													$post_settings['term']
												);
											}

											?>
											<h1 class="single-post-title">
												<span <?php publisher_attr( 'post-title' ); ?>><?php the_title(); ?></span>
											</h1>
											<?php

											if ( function_exists( 'publisher_the_subtitle' ) ) {
												publisher_the_subtitle( '<h2 class="post-subtitle">', '</h2>' );
											}

											if ( $show_excerpt === 'after-title' ) { ?>
												<div
														class="single-post-excerpt post-excerpt-at"><?php the_excerpt(); ?></div><?php
											}

											if ( $post_settings['meta']['show'] ) {
												publisher_set_prop( 'hide-meta-author', ! $post_settings['meta']['author'] );
												publisher_set_prop( 'hide-meta-author-avatar', ! $post_settings['meta']['author_avatar'] );
												publisher_set_prop( 'hide-meta-date', ! $post_settings['meta']['date'] );
												publisher_set_prop( 'hide-meta-views', $is_top_social_share_active || ! $post_settings['meta']['views'] );
												publisher_set_prop( 'hide-meta-comment', $is_top_social_share_active || ! $post_settings['meta']['comment'] );
												publisher_set_prop( 'hide-meta-review', ! $post_settings['meta']['review'] );
												publisher_get_view( 'post', '_meta' );
											}

											?>
										</div>
									</div>
									<?php

									// Social share buttons
									if ( $is_top_social_share_active ) {
										publisher_listing_social_share( array(
												'style'         => publisher_get_top_share_style(),
												'type'          => 'single',
												'class'         => 'single-post-share top-share clearfix',
												'show_count'    => publisher_get_option( 'social_share_count' ) !== 'hide',
												'show_views'    => $post_settings['meta']['views'],
												'show_comments' => $post_settings['meta']['comment'],
											)
										);
									}

									if ( $show_excerpt === 'before-content' ) { ?>
										<div
												class="single-post-excerpt post-excerpt-bc"><?php the_excerpt(); ?></div><?php
									}

									?>
									<div <?php publisher_attr( 'post-content', 'clearfix single-post-content' ); ?>>
										<?php publisher_the_content(); ?>
									</div>
									<?php

									// Shows source
									if ( bf_get_post_meta( '_bs_source_url' ) || bf_get_post_meta( '_bs_source_url_2' ) || bf_get_post_meta( '_bs_source_url_3' ) ) {
										publisher_get_view( 'post', '_source', 'default' );
									}

									// Shows via
									if ( bf_get_post_meta( '_bs_via_url' ) || bf_get_post_meta( '_bs_via_url_2' ) || bf_get_post_meta( '_bs_via_url_3' ) ) {
										publisher_get_view( 'post', '_via', 'default' );
									}

									// Shows post tags
									if ( $post_settings['tag'] && publisher_has_tag() ) {
										publisher_get_view( 'post', '_tags', 'default' );
									}

									// Social share buttons
									if ( $social_share == 'bottom' || $social_share == 'top-bottom' ) {
										publisher_listing_social_share( array(
												'style'         => publisher_get_bottom_share_style(),
												'type'          => 'single',
												'class'         => 'single-post-share bottom-share clearfix',
												'show_count'    => publisher_get_option( 'social_share_count' ) !== 'hide',
												'show_views'    => $post_settings['meta']['views'],
												'show_comments' => $post_settings['meta']['comment'],
											)
										);
									}

									?>
								</article>
								<?php

								// Newsletter before author box
								publisher_show_newsletter_location( 'post_before_author', array(
									'custom-data' => array(
										'style-type' => 'wide'
									),
									'show-error'  => FALSE,
								) );

								// Before author box ads
								publisher_show_ad_location( 'post_before_author_box', array( 'container-class' => 'adloc-post-before-author' ) );

								// Author box
								if ( publisher_get_option( 'post_author_box' ) == 'show' ) {
									publisher_get_view( 'post', '_author' );
								}

								// Newsletter before nex/prev
								publisher_show_newsletter_location( 'post_before_nextprev', array(
									'custom-data' => array(
										'style-type' => 'wide'
									),
									'show-error'  => FALSE,
								) );

								// Next/Prev posts link
								if ( publisher_get_option( 'post_next_prev' ) !== 'hide' ) {
									publisher_get_view( 'post', '_next_prev_post' );
								}

								?>
							</div>
							<?php

							// Newsletter before related
							publisher_show_newsletter_location( 'post_before_related', array(
								'custom-data' => array(
									'style-type' => 'wide'
								),
								'show-error'  => FALSE,
							) );

							// Related posts
							if ( publisher_get_related_post_type() == 'show' ) {
								publisher_get_view( 'post', '_related' );
							}

							// After Related Posts
							publisher_show_ad_location( 'post_after_related', array(
									'container-class' => 'adloc-after-related',
								)
							);

							// Newsletter before comment
							publisher_show_newsletter_location( 'post_before_comment', array(
								'custom-data' => array(
									'style-type' => 'wide'
								),
								'show-error'  => FALSE,
							) );

							// Comments and comment form
							publisher_comments_template();

							// Ad after first post for related posts
							if ( publisher_get_related_post_type() == 'infinity-related-post' ) {
								publisher_show_ad_location( 'post_ajax_related', array( 'container-class' => 'adloc-ajaxed-related' ) );
							}

							// Newsletter after comment
							publisher_show_newsletter_location( 'post_after_comment', array(
								'custom-data' => array(
									'style-type' => 'wide'
								),
								'show-error'  => FALSE,
							) );

							?>
						</div><!-- .content-column -->
						<?php

						// Clear all props
						publisher_clear_props();

					} else {
						?>
						<div class="<?php echo $column['class']; // escaped before ?>">
							<?php get_sidebar( $column['id'] ); ?>
						</div><!-- .<?php echo $column['id']; // escaped before ?>-sidebar-column -->
						<?php
					}
				}

				?>
			</div><!-- .main-section -->
		</div><!-- .layout-2-col -->

	</main><!-- main -->
	<?php

	/**
	 * Fires before close of".content-wrap"
	 *
	 * @since 1.9.0
	 */
	do_action( 'publisher/content-wrap/end' );

	?>
</div><!-- .content-wrap -->
<?php

/**
 * Fires after close of".content-wrap"
 *
 * @since 1.9.0
 */
do_action( 'publisher/content-wrap/after' );

publisher_get_view( 'post', 'more-stories' );

?>