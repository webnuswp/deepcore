<?php
/**
* @package   The_Grid
* @author    WEBNUS TEAM <www.deeptem.com>
* @copyright 2017 deeptem.com
*
* Skin Name: portfolio-hover-8
* Skin Slug: portfolio-hover-8
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

	// Media content (image, gallery, audio, video)
	$output .= $tg_el->get_media();

		// Media content holder start
		$output .= $tg_el->get_media_content_start();

		// Overlay
		$output .= $tg_el->get_overlay();

		// Center wrapper start
		$output .= $tg_el->get_center_wrapper_start(); 

			$output .= $tg_el->get_the_title(array('link' => false, 'action' => array('type' => 'link', 'link_target' => '_self', 'link_url' => 'post_url', 'custom_url' => '', 'meta_data_url' => '')), 'tg-element-1');
			$output .= $tg_el->get_the_terms(array('taxonomy' => '', 'link' => true, 'color' => '', 'separator' => ', ', 'override' => true, 'action' => array('type' => 'link', 'link_target' => '_self', 'link_url' => 'meta_data_url', 'custom_url' => '', 'meta_data_url' => '')), 'tg-element-2');
			$output .= '<div class="footer-elements">';
				$output .= $tg_el->get_media_button(array('icons' => array('image' => '<i class="tg-icon-eye"></i>', 'audio' => '<i class="tg-icon-eye"></i>', 'video' => '<i class="tg-icon-eye"></i>', 'pause' => ''), 'action' => array('type' => 'lightbox')), 'tg-element-4');
				$output .= $tg_el->get_icon_element(array('icon' => 'tg-icon-link', 'action' => array('type' => 'link', 'link_target' => '_self', 'link_url' => 'post_url', 'custom_url' => '', 'meta_data_url' => '')), 'tg-element-3 colorr');
			$output .= '</div>';

		$output .= $tg_el->get_center_wrapper_end();
		// Center wrapper end

		// Media content holder end
		$output .= $tg_el->get_media_content_end();

	$output .= $tg_el->get_media_wrapper_end();
	// Media wrapper end


return $output;