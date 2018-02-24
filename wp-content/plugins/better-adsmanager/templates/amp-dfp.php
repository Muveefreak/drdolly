<?php

better_amp_enqueue_ad( 'dfp' );

if ( $banner_data['dfp_spot'] === 'custom' ) {
	$ad_code = $banner_data['custom_dfp_code'];
} else {

	$ad_code = '<amp-ad width=' . $banner_data['dfp_spot_width'] . ' height=' . $banner_data['dfp_spot_height'] . '
    type="doubleclick"
    data-slot="' . $banner_data['dfp_spot_id'] . '">
</amp-ad>';

}

if ( ! empty( $banner_data['caption'] ) && $args['show-caption'] ) {
	$_ad .= '<span class="bsac-caption">' . $banner_data['caption'] . '</span>';
}

return $ad_code;
