<?php
/**
 * The template for smart list style 13
 *
 * @var $smart_list
 *
 * @author     BetterStudio
 * @package    Smart Lists Pack Pro
 * @version    1.1
 */

$page = bf_get_query_var_paged();
if ( ! isset( $smart_list['items'][ $page - 1 ] ) ) {
	$page = 1;
}

$item = $smart_list['items'][ $page - 1 ];

if ( empty( $item['title'] ) ) {
	$item['title'] = get_the_title();
}

// permalink for nav links
{
	$permalink = add_query_arg( 'page', '%%page%%', get_permalink() );

	// prev link
	{
		$prev = $page - 1;


		if ( ! isset( $smart_list['items'][ $prev - 1 ] ) ) {
			$prev = $smart_list['items-count'];
		}

		$prev = str_replace( '%%page%%', $prev, $permalink );
	}

	// next link
	{
		$next = $page + 1;

		if ( ! isset( $smart_list['items'][ $next - 1 ] ) ) {
			$next = 1;
		}

		$next = str_replace( '%%page%%', $next, $permalink );
	}
}

bssl_show_ad_location( 'bssl_before' );

if ( isset( $smart_list['before'] ) ) {
	echo '<span class="bs-smart-list-start bssl-before-style-13"></span>';
}

?>
	<div class="bs-smart-list bssl-style-13 bssl-t1 bssl-s13">

		<div class="bssl-inner">

			<div class="bssl-items">

				<div class="bssl-item bs-slider-item">

					<h2 class="bssl-item-title heading-typo bssl-count-type-badge">
						<span class="bssl-count"><?php echo number_format_i18n( $page ); ?></span>
						<?php echo $item['title']; ?>
					</h2>
					<?php

					$src = $item['image_src'];
					$alt = ! empty( $item['alt'] ) ? $item['alt'] : $item['title'];

					if ( ! empty( $src ) ) {

						?>
						<figure class="bssl-image-w">

							<?php if ( ! empty( $item['image_link'] ) ){ ?><a
									href="<?php echo $item['image_link']; ?>"><?php } ?>

								<img class="bssl-image"
								     src="<?php echo $src; ?>"
								     alt="<?php echo $alt; ?>"
								/>

								<?php if ( ! empty( $item['image_link'] ) ){ ?></a><?php } ?>

							<?php if ( ! empty( $item['image_caption'] ) ) { ?>
								<figcaption class="wp-caption-text"><?php echo $item['image_caption']; ?></figcaption>
							<?php } ?>

						</figure>
						<?php
					}

					bssl_show_ad_location( 'bssl_style_13' );

					?>
					<div class="bssl-content the-content">
						<?php echo $item['content']; ?>
					</div>
				</div>
			</div>

			<div class="bssl-control-nav">
				<a rel="prev" class="bssl-nav-btn-big prev" href="<?php echo $prev; ?>">
					<i class="fa fa-angle-<?php echo is_rtl() ? 'right' : 'left'; ?>"
					   aria-hidden="true"></i> <?php echo BS_Smart_Lists_Pack_Pro::get_option( 'trans_page_prev' ); ?>
				</a>

				<a rel="next" class="bssl-nav-btn-big next" href="<?php echo $next; ?>">
					<?php echo BS_Smart_Lists_Pack_Pro::get_option( 'trans_page_next' ); ?> <i
							class="fa fa-angle-<?php echo is_rtl() ? 'left' : 'right'; ?>" aria-hidden="true"></i>
				</a>
			</div>

		</div>
	</div>
<?php

if ( isset( $smart_list['after'] ) ) {
	echo '<span class="bs-smart-list-end"></span>';
}

bssl_show_ad_location( 'bssl_after' );
