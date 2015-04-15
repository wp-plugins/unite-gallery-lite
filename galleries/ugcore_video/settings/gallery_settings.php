<?php


defined('_JEXEC') or die('Restricted access');


	require GlobalsUG::$pathHelpersSettings."main.php";	
	
	$settingsParams = new UniteGallerySettingsUG();
	$settingsParams->loadXMLFile(GlobalsUGGallery::$pathSettings."gallery_settings.xml");
	
	// get merged settings with values
	$valuesMain = $settingsMain->getArrValues();
	$valuesParams = $settingsParams->getArrValues();
	$valuesMerged = array_merge($valuesMain, $valuesParams);
	
	$valuesMerged["gallery_theme"] = "video";
		
?>