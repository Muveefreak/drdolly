<?php
/**
 * Custom content from pages in menu
 *
 * @author     BetterStudio
 * @package    Publisher
 * @version    2.0.0
 */

// get all args
$args = publisher_get_prop( 'mega-menu-args', array() );

$page_id = isset( $args['current-item']->custom_menu_page_content ) ? $args['current-item']->custom_menu_page_content : null;

if( ! $page_id){
	if( bf_is_user_logged_in() ){
		$content = '<span>' . __( 'Please select a page for "Page Builder Mega Menu" in Apperance -> Menu.', 'publisher' ) . '</span>';
	}else{
		$content = '';
	}

}else{

	// get selected page content 
	$page = get_post($page_id);
	$content =do_shortcode( $page->post_content );
}

?>
	<div class="mega-menu mega-type-page-builder">
		<?php echo $content ; // escaped before ?>
	</div>
<?php

publisher_clear_props();
