<?php
/**
* @package   The_Grid
* @author    WEBNUS TEAM <www.deeptem.com>
* @copyright 2017 deeptem.com
*
* Skin Name: freelancer-2
* Skin Slug: freelancer-2
* Date: 08/12/17 - 01:06:35PM
*
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit;
}

// Init The Grid Elements instance
$tg_el = The_Grid_Elements();

// Prepare main data for futur conditions
$image  = $tg_el->get_attachment_url();
$format = $tg_el->get_item_format();

$output = null;

	// Media wrapper start
	$output .= $tg_el->get_media_wrapper_start();
		$output .= '<div class="border-box"></div>';
	// Media content (image, gallery, audio, video)
	$output .= $tg_el->get_media();

		// Media content holder start
		$output .= $tg_el->get_media_content_start();




		// Absolute element(s) in Media wrapper
$output .='<div class="text-box">';
		$output .= $tg_el->get_the_title(array('link' => false, 'action' => array('type' => 'link', 'link_target' => '_self', 'link_url' => 'post_url', 'custom_url' => '', 'meta_data_url' => '')), 'tg-element-1');
		$output .= $tg_el->get_the_terms(array('taxonomy' => '', 'link' => true, 'color' => '', 'separator' => ' / ', 'override' => true), 'tg-element-2');
$output .= '</div>';
		// Media content holder end
		$output .= $tg_el->get_media_content_end();
	$output .= $tg_el->get_media_wrapper_end();
	// Media wrapper end


return $output;