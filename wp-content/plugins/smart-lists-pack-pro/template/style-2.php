<?php
/**
 * The template for smart list style 2
 *
 * @var $smart_list
 *
 * @author     BetterStudio
 * @package    Smart Lists Pack Pro
 * @version    1.0
 */

bssl_show_ad_location( 'bssl_before' );

if ( isset( $smart_list['before'] ) ) {
	echo '<span class="bs-smart-list-start bssl-before-style-2"></span>';
}

?>
	<div class="bs-smart-list bssl-style-2 bssl-t1 bssl-s2">
		<div class="bssl-inner">

			<div class="bssl-items bs-slider-slider">
				<?php

				$counter = 0;

				foreach ( $smart_list['items'] as $item ) {

					$counter = bs_smart_lists_get_current_item_number( $counter, $smart_list['items-count'], $smart_list['order'] );

					if ( empty( $item['title'] ) ) {
						$item['title'] = get_the_title();
					}

					?>
					<div class="bssl-item bs-slider-item clearfix">

						<h2 class="bssl-item-title heading-typo bssl-count-type-text">
							<span class="bssl-count"><?php echo number_format_i18n( $counter ); ?>.</span>
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
									<figcaption
											class="wp-caption-text"><?php echo $item['image_caption']; ?></figcaption>
								<?php } ?>

							</figure>
							<?php

						}

						?>
						<div class="bssl-content the-content">
							<?php echo $item['content']; ?>
						</div>
					</div>

				<?php } ?>

			</div>

			<div class="bssl-control-nav full-row-both-side clearfix"></div>

		</div>
	</div>
<?php

if ( isset( $smart_list['after'] ) ) {
	echo '<span class="bs-smart-list-end"></span>';
}

bssl_show_ad_location( 'bssl_after' );
