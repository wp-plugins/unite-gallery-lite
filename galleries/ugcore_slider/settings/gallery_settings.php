<?php


defined('_JEXEC') or die('Restricted access');


	require GlobalsUG::$pathHelpersSettings."main.php";	
	
	$settingsParams = new UniteGallerySettingsUG();
	$settingsParams->loadXMLFile(GlobalsUGGallery::$pathSettings."gallery_settings.xml");
	
	//set defaults
	$arrDefaults = array(
			"gallery_autoplay" => "true",
			"slider_scale_mode" => "fill",
			"slider_controls_always_on" => "true",
			"slider_enable_text_panel" => "false",
			"slider_controls_appear_ontap" => "true",
			"slider_enable_bullets" => "true",
			"slider_enable_arrows" => "true",
			"slider_enable_play_button" => "false",
			"slider_enable_fullscreen_button" => "false",
			"slider_enable_zoom_panel" => "false"
	);
	
	$arrSettingsToHide = array(
	);
	
	//$settingsParams->hideSettings($arrSettingsToHide);
	
	$settingsParams->setStoredValues($arrDefaults);
	
	// get merged settings with values
	$valuesMain = $settingsMain->getArrValues();
	$valuesParams = $settingsParams->getArrValues();
	$valuesMerged = array_merge($valuesMain, $valuesParams);
	
	$valuesMerged["gallery_theme"] = "slider";
	
?>