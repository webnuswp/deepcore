<?php
/**
* @package   The_Grid
* @author    WEBNUS TEAM <www.deeptem.com>
* @copyright 2017 deeptem.com
*
* Skin Name: photography-home
* Skin Slug: photography-home
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

$media = $tg_el->get_media();

// if there is a media
if ($media) {

	// Media wrapper start
	$output .= $tg_el->get_media_wrapper_start();

	// Media content (image, gallery, audio, video)
	$output .= $media;

	// if there is an image
	if ($image || in_array($format, array('gallery', 'video'))) {

		// Media content holder start
		$output .= $tg_el->get_media_content_start();

		// Overlay
		$output .= $tg_el->get_overlay();

		// Top wrapper start
		$output .= '<div class="tg-top-holder">';
			$output .= $tg_el->get_the_likes_number(array('action' => array('type' => 'link', 'link_target' => '_self', 'link_url' => 'post_url', 'custom_url' => '', 'meta_data_url' => '')), 'tg-element-4');
		$output .= '</div>';
		// Top wrapper end

		//center
		$output .= '<div class="full">';
			$output .= $tg_el->get_media_button(array('action' => array('type' => 'lightbox')), 'tg-element-5 full');
		$output .= '</div>';

		// Bottom wrapper start
		$output .= '<div class="tg-bottom-holder">';
			$output .= $tg_el->get_the_title(array('link' => false, 'action' => array('type' => 'lightbox')), 'tg-element-1');
			$output .= '<div class="liner"></div>';
			$output .= $tg_el->get_the_terms(array('taxonomy' => '', 'link' => true, 'color' => '', 'separator' => ', ', 'override' => true), 'tg-element-2');
		$output .= '</div>';
		// Bottom wrapper end

		// Media content holder end
		$output .= $tg_el->get_media_content_end();

	}

	$output .= $tg_el->get_media_wrapper_end();
	// Media wrapper end

}


return $output;