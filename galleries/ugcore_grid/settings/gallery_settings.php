<?php


defined('_JEXEC') or die('Restricted access');


	require GlobalsUG::$pathHelpersSettings."main.php";	
		
	$settingsParams = new UniteGallerySettingsUG();
	$settingsParams->loadXMLFile(GlobalsUGGallery::$pathSettings."gallery_settings.xml");
	
	//set defaults
	$arrDefaults = array(
			"slider_controls_always_on" => "true",		
	);
	
	//set defaults by position
	if(!isset($panelPos))
		$panelPos = UniteFunctionsUG::getGetVar("thumbpos","right");
		
	$arrPosDefaults = UGGridThemeHelper::getDefautlsByPosition($panelPos);
	$arrDefaults = array_merge($arrDefaults, $arrPosDefaults);
	
	$arrSettingsToHide = array(
	);
	
	//$settingsParams->hideSettings($arrSettingsToHide);
	$settingsParams->setStoredValues($arrDefaults);
	
	// get merged settings with values
	$valuesMain = $settingsMain->getArrValues();
	$valuesParams = $settingsParams->getArrValues();
	$valuesMerged = array_merge($valuesMain, $valuesParams);
	
	$valuesMerged["gallery_theme"] = "grid";
	
?>