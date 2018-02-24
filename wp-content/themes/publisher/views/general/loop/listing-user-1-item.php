<?php
/**
 * Thumbnail listing template
 *
 * @author     BetterStudio
 * @package    Publisher
 * @version    1.9.0
 */

$heading_tag = publisher_get_prop( 'item-heading-tag', 'h5' );

$user = publisher_get_prop( 'user-object' );
/**
 * @var WP_User $user
 */

?>
<div class="listing-item listing-item-user type-1 style-1 clearfix">
	<div class="bs-user-item">
		<div class="user-avatar">
			<?php
			echo get_avatar( $user->ID, 60 );

			if ( $ranking = publisher_get_prop( 'user-rank' ) ) { ?>
				<div class="user-badge">
					<?php echo number_format_i18n( $ranking ) ?>
				</div>
			<?php } ?>
		</div>

		<div class="user-meta">
			<?php

			echo '<', $heading_tag, ' class="user-display-name">';
			publisher_echo_html_limit_words( get_the_author_meta( 'display_name', $user->ID ), publisher_get_prop( 'title-limit' ) );
			echo '</', $heading_tag, '>';

			if ( publisher_get_prop( 'show-posts-url' ) ) {
				?>
				<a href="<?php echo get_author_posts_url( $user->ID ) ?>"
				   class="btn btn-light"><?php publisher_translation_echo( 'view_all_posts' ) ?></a>
				<?php
			}

			if ( publisher_get_prop( 'social-icons' ) ) {

				publisher_the_author_social_icons( $user->ID, array(
					'wrapper_class' => 'user-social-icons',
					'max-links'     => publisher_get_prop( 'social-icons-limit' ),
				) );
			}

			?>
		</div>
	</div>
</div>