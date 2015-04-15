<?php

defined('_JEXEC') or die('Restricted access');


	require GlobalsUG::$pathHelpersSettings."main.php";	
	
	$settingsParams = new UniteGallerySettingsUG();
	$settingsParams->loadXMLFile(GlobalsUGGallery::$pathSettings."gallery_settings.xml");
	
	//set defaults
	$arrDefaults = array(
			"slider_controls_always_on" => "true",			//zoompanel
			"slider_zoompanel_offset_vert" => "12",
			
			"slider_textpanel_align" => "top",			//text panel
			"slider_textpanel_padding_top" => "0",
			"slider_textpanel_enable_title" => "false",
			"slider_textpanel_enable_description" => "true",
			
			"strippanel_background_color" => "#232323",		//strip panel
			"strippanel_padding_top" => "10",
			
			"slider_enable_text_panel" => "true",		//must options
			"slider_enable_play_button" => "false",		
			"slider_enable_fullscreen_button" => "false"		
	);
	
	$arrSettingsToHide = array(
			"slider_enable_text_panel",
			"slider_enable_play_button",
			"slider_enable_fullscreen_button",
			"hr_buttons1",
			"hr_buttons2",
			"slider_textpanel_height",
			"slider_textpanel_margin",
			"slider_textpanel_always_on",
			"hr_textpanel1",
			"slider_textpanel_align"
	);
	
	$settingsParams->hideSettings($arrSettingsToHide);
	
	$settingsParams->setStoredValues($arrDefaults);
	
	// get merged settings with values
	$valuesMain = $settingsMain->getArrValues();
	$valuesParams = $settingsParams->getArrValues();
	$valuesMerged = array_merge($valuesMain, $valuesParams);
	
	$valuesMerged["gallery_theme"] = "default";
	
?>