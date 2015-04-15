<?php

defined('_JEXEC') or die('Restricted access');

	require GlobalsUG::$pathHelpersSettings."main_tiles.php";	
	
	$settingsParams = new UniteGallerySettingsUG();
	$settingsParams->loadXMLFile(GlobalsUGGallery::$pathSettings."gallery_settings.xml");
	
	//set defaults
	$arrDefaults = array(
			"tile_width" => "160",
			"tile_height" => "160",
			"tile_enable_border" => "true",
			"tile_enable_outline" => "true"
	);
	
	$arrSettingsToHide = array(
			//"slider_textpanel_align"
	);
	
	//update settings
	$settingsParams->updateSelectToEasing("carousel_scroll_easing");
	$settingsParams->updateSelectToAlignHor("theme_carousel_align");
	$settingsParams->updateSelectToAlignHor("theme_navigation_align");
	
	$settingsParams->hideSettings($arrSettingsToHide);
	
	$settingsParams->setStoredValues($arrDefaults);
	
	// get merged settings with values
	$valuesMain = $settingsMain->getArrValues();
	$valuesParams = $settingsParams->getArrValues();
	$valuesMerged = array_merge($valuesMain, $valuesParams);
	
	$valuesMerged["gallery_theme"] = "carousel";
	
?>