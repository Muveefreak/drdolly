<?php
/**
 * style-6.php
 *
 * The template for displaying "style 6" of newsletter pack shortcode
 *
 * @author    BetterStudio
 * @package   Newsletter Pack
 * @version   1.0
 */

if ( empty( $atts['css-class'] ) ) {
	$atts['css-class'] = '';
}

if ( ! empty( $atts['custom-css-class'] ) ) {
	$atts['css-class'] .= ' ' . sanitize_html_class( $atts['custom-css-class'] );
}

$custom_id = empty( $atts['custom-id'] ) ? '' : sanitize_html_class( $atts['custom-id'] );

$stack_items = array(
	'icon'         => TRUE,
	'title'        => TRUE,
	'desc'         => TRUE,
	'after'        => TRUE,
	'social-icons' => $atts['social_icons'],
);

// remove extra things in special locations
{
	$_check = array(
		'inline' => '',
	);

	if ( isset( $_check[ $atts['style-type'] ] ) ) {
		$stack_items['icon']  = FALSE;
		$stack_items['desc']  = FALSE;
		$stack_items['after'] = FALSE;
	}
}

// Custom css color for this newsletter.
if ( ! empty( $atts['color'] ) ) {

	if ( empty( $custom_id ) ) {
		$custom_id = 'bsnp-' . mt_rand();
	}

	bf_add_css( "
	#$custom_id.bs-newsletter-pack input.bsnp-input:focus{border-color: {$atts['color']}  !important}
	#$custom_id.bs-newsletter-pack input.bsnp-input:focus + .bsnp-icon{ color: {$atts['color']}  !important}
	#$custom_id.bs-newsletter-pack .bsnp-button { background-color: {$atts['color']} !important}
	", FALSE, TRUE );
}

?>
	<div <?php
	if ( $custom_id ) {
		echo 'id="', $custom_id, '"';
	}
	?> class="bs-shortcode bs-newsletter-pack bsnp-<?php echo $atts['type']; ?> bsnp-st-<?php echo $atts['style-type']; ?> bsnp-t1 bsnp-s6 bsnp-st-wide-2col clearfix <?php echo $atts['css-class']; ?>">
		<?php

		bf_shortcode_show_title( $atts ); // show title

		// Custom and Auto Generated CSS Codes
		if ( ! empty( $atts['css-code'] ) ) {
			bf_add_css( $atts['css-code'], TRUE, TRUE );
		}

		?>

		<?php if ( $stack_items['icon'] ) { ?>
			<div class="bsnp-ic bsnp-ic-img">
				<img src="<?php echo BS_Newsletter_Pack_Pro::dir_url( 'images/newsletters/envelope-1.png?v=' . BS_Newsletter_Pack_Pro::get_version() ) ?>"
				     alt="<?php echo ! empty( $atts['text_title'] ) ? $atts['text_title'] : 'Newsletter' ?>">
			</div>
		<?php } ?>

		<div class="bsnp-bc">

			<?php if ( $stack_items['title'] && ! empty( $atts['text_title'] ) ) { ?>
				<div class="bsnp-title heading-typo"><?php echo $atts['text_title']; ?></div>
			<?php } ?>

			<?php if ( $stack_items['desc'] && ! empty( $atts['text_desc'] ) ) { ?>
				<div class="bsnp-desc"><?php echo $atts['text_desc']; ?></div>
			<?php } ?>

			<?php echo bsnp_get_form_code( $atts, array(
				'form-fields-wrapper-class' => 'bsnp-2-row'
			) ); ?>

			<?php if ( $stack_items['after'] && ! empty( $atts['text_after'] ) ) { ?>
				<div class="bsnp-after"><?php echo $atts['text_after']; ?></div>
			<?php } ?>

			<?php

			if ( $stack_items['social-icons'] ) {

				$social_icons = bsnp_get_form_social_icons_code( $atts );

				if ( ! empty( $social_icons ) ) {

					$atts['social_icons_style'] = explode( '-', $atts['social_icons_style'] );

					$atts['social_icons_style'] = " bsnp-si-{$atts['social_icons_style'][0]} bsnp-si-{$atts['social_icons_style'][1]}";


					echo "<div class='bsnp-si {$atts['social_icons_style']}'>{$social_icons}</div>";
				}
			}

			?>
		</div>
	</div>
<?php

unset( $atts );
