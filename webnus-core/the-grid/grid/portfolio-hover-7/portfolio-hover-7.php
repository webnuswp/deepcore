<?php
/**
* @package   The_Grid
* @author    WEBNUS TEAM <www.deeptem.com>
* @copyright 2017 deeptem.com
*
* Skin Name: portfolio-hover-7
* Skin Slug: portfolio-hover-7
* Date: 08/12/17 - 01:06:35PM
*
*/

// Exit if accessed directly
if (!defined('ABSPATH')) { 
	exit;
}

$tg_el = The_Grid_Elements();

$format    = $tg_el->get_item_format();
$permalink = $tg_el->get_the_permalink();
$target    = $tg_el->get_the_permalink_target();

$terms_args = array(
	'color' => 'color',
	'separator' => ', '
);

$output  = $tg_el->get_content_wrapper_start();
	$output .= $tg_el->get_center_wrapper_start();
	
	// if ($format != 'standard') {
		$output .= '<div class="tg-button-holder">';
			$output .= $tg_el->get_overlay();	
			$output .= $tg_el->get_media_button();
		$output .= '</div>';
	// }
	
		$output .= $tg_el->get_the_title();
		$output .= $tg_el->get_the_terms($terms_args);
	$output .= $tg_el->get_center_wrapper_end();
$output .= $tg_el->get_content_wrapper_end();
$output .= $tg_el->get_media_wrapper_start();
	$output .= $tg_el->get_media();
	$output .= ($permalink && !in_array($format, array('audio', 'video'))) ? '<a class="tg-item-link" href="'.$permalink .'" target="'.$target.'"></a>' : null;

$output .= $tg_el->get_media_wrapper_end();
		
return $output;