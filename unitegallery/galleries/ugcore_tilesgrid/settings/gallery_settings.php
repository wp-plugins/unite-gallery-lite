<?php

defined('_JEXEC') or die('Restricted access');

	require GlobalsUG::$pathHelpersSettings."main_tiles.php";	
	
	$settingsParams = new UniteGallerySettingsUG();
	$settingsParams->loadXMLFile(GlobalsUGGallery::$pathSettings."gallery_settings.xml");
	
	//set defaults
	$arrDefaults = array(
			"tile_width" => "180",
			"tile_height" => "150",
			"grid_num_rows" => "3",
			"grid_padding" => "10",
			"tile_enable_border" => "true",
			"tile_enable_shadow" => "true",
			"tile_border_radius" => "2",
			"grid_space_between_cols" => "20",
			"grid_space_between_rows" => "20",
			"bullets_space_between" => "12",
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
	
	$valuesMerged["gallery_theme"] = "tilesgrid";
	
?>