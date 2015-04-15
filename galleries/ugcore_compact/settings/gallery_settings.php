<?php


defined('_JEXEC') or die('Restricted access');


	require GlobalsUG::$pathHelpersSettings."main.php";	
		
	$settingsParams = new UniteGallerySettingsUG();
	$settingsParams->loadXMLFile(GlobalsUGGallery::$pathSettings."gallery_settings.xml");
	
	//set defaults
	$arrDefaults = array(
			"slider_controls_always_on" => "true",		
			"slider_enable_text_panel" => "false",		
			"strippanel_enable_buttons" => "false"		
	);
	
	
	//set defaults by position
	if(!isset($panelPos))
		$panelPos = UniteFunctionsUG::getGetVar("thumbpos","bottom");
	
	
	$arrPosDefaults = UGCompactThemeHelper::getDefautlsByPosition($panelPos);
	$arrDefaults = array_merge($arrDefaults, $arrPosDefaults);
	
	
	$arrSettingsToHide = array(
	);
	
	//$settingsParams->hideSettings($arrSettingsToHide);
	$settingsParams->setStoredValues($arrDefaults);
	
	// get merged settings with values
	$valuesMain = $settingsMain->getArrValues();
	$valuesParams = $settingsParams->getArrValues();
	$valuesMerged = array_merge($valuesMain, $valuesParams);
	$valuesMerged["gallery_theme"] = "compact";
	
	
?>