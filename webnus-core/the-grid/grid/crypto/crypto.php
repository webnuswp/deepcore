<?php
/**
* @package   The_Grid
* @author    Themeone <themeone.master@gmail.com>
* @copyright 2015 Themeone
*
* Skin Name: crypto
* Skin Slug: crypto
* Date: 03/10/18 - 09:02:45AM
*
*/

// Exit if accessed directly
if (!defined('ABSPATH')) { 
	exit;
}

$tg_el = The_Grid_Elements();

$colors    = $tg_el->get_colors();
$permalink = $tg_el->get_the_permalink();
$target    = $tg_el->get_the_permalink_target();

$media_args = array(
	'icons' => array(
		'image' => ' ',
		'audio' => ' ',
		'video' => ' ',
	)
);

$terms_args = array(
	'color' => 'color',
	'separator' => ', '
);

$output = '<div class="webnus">';
	$output .= '<div class="tg-atv-shadow"></div>';
	$output .= $tg_el->get_media_wrapper_start();
		$output .= $tg_el->get_media();
	$output .= $tg_el->get_media_wrapper_end();
	$output .= $tg_el->get_overlay();
	$output .= '<div class="tg-item-content-holder tg-item-atv-layer '.$colors['overlay']['class'].'">';
		$output .= '<div class="tg-item-content-inner">';
			$output .= $tg_el->get_center_wrapper_start();	
			    $output .= $tg_el->get_the_date(array('format' => 'F j, Y'), 'tg-element-1');
			    $output .= $tg_el->get_the_title(array('link' => false, 'action' => array('type' => 'link', 'link_target' => '_self', 'link_url' => 'post_url', 'custom_url' => '', 'meta_data_url' => '')), 'tg-element-2');
			$output .= $tg_el->get_center_wrapper_end();
		$output .= '</div>';
	$output .= '</div>';

$output .= '</div>';
		
return $output;