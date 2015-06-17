<?php


defined('_JEXEC') or die('Restricted access');


$settings = new UniteGallerySettingsUG();

$settings->loadXMLFile(GlobalsUG::$pathHelpersSettings."slider_main.xml");

$settings->updateSelectToEasing("slider_transition_easing");

$settings->updateSelectToSkins("slider_bullets_skin", "");
$settings->updateSelectToAlignHor("slider_bullets_align_hor");
$settings->updateSelectToAlignVert("slider_bullets_align_vert");

$settings->updateSelectToSkins("slider_arrows_skin", "");
$settings->updateSelectToAlignHor("slider_arrow_left_align_hor");
$settings->updateSelectToAlignVert("slider_arrow_left_align_vert");
$settings->updateSelectToAlignHor("slider_arrow_right_align_hor");
$settings->updateSelectToAlignVert("slider_arrow_right_align_vert");

$settings->updateSelectToAlignHor("slider_arrow_right_align_hor");
$settings->updateSelectToAlignVert("slider_arrow_right_align_vert");

$settings->updateSelectToAlignHor("slider_progress_indicator_align_hor");
$settings->updateSelectToAlignVert("slider_progress_indicator_align_vert");

$settings->updateSelectToSkins("slider_play_button_skin", "");
$settings->updateSelectToAlignHor("slider_play_button_align_hor");
$settings->updateSelectToAlignVert("slider_play_button_align_vert");

$settings->updateSelectToSkins("slider_fullscreen_button_skin", "");
$settings->updateSelectToAlignHor("slider_fullscreen_button_align_hor");
$settings->updateSelectToAlignVert("slider_fullscreen_button_align_vert");

$settings->updateSelectToSkins("slider_zoompanel_skin", "");
$settings->updateSelectToAlignHor("slider_zoompanel_align_hor");
$settings->updateSelectToAlignVert("slider_zoompanel_align_vert");

if(method_exists("UniteProviderFunctionsUG", "addBigImageSizeSettings"))
	$settings = UniteProviderFunctionsUG::addBigImageSizeSettings($settings, false, "slider_scale_mode_fullscreen");


?>