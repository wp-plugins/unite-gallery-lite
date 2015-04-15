<?php

defined('_JEXEC') or die('Restricted access');

	require GlobalsUG::$pathHelpersSettings."main_tiles.php";	
	
	$settingsParams = new UniteGallerySettingsUG();
	$settingsParams->loadXMLFile(GlobalsUGGallery::$pathSettings."gallery_settings.xml");
	
	//set defaults
	$arrDefaults = array(
			//"slider_enable_fullscreen_button" => "false"		
	);
	
	$arrSettingsToHide = array(
			//"slider_textpanel_align"
	);
	
	$settingsParams->hideSettings($arrSettingsToHide);
	
	$settingsParams->setStoredValues($arrDefaults);
	
	// get merged settings with values
	$valuesMain = $settingsMain->getArrValues();
	$valuesParams = $settingsParams->getArrValues();
	$valuesMerged = array_merge($valuesMain, $valuesParams);
	
	$valuesMerged["gallery_theme"] = "tiles";
	
?>