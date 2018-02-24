<?php

Better_Ads_Manager()->enqueue_adblocker_detector();

if ( $banner_data['dfp_spot'] === 'custom' ) {
	$ad_code = $banner_data['custom_dfp_code'];
} else {

	$ad_code = '<!-- ' . $banner_data['dfp_spot_id'] . ' -->
<div id="' . $banner_data['dfp_spot_tag'] . '" style="width:' . $banner_data['dfp_spot_width'] . 'px; height:' . $banner_data['dfp_spot_height'] . 'px;">
<script>
googletag.cmd.push(function() { googletag.display("' . $banner_data['dfp_spot_tag'] . '"); });
</script>
</div>';

}

if ( ! empty( $banner_data['caption'] ) && $args['show-caption'] ) {
	$ad_code .= '<p class="bsac-caption">' . $banner_data['caption'] . '</p>';
}

return $ad_code;
