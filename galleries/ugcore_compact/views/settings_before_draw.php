<?php


defined('_JEXEC') or die('Restricted access');

	//set panel position from get
	if(empty($galleryID)){
		$getPanelPos = UniteFunctionsUG::getGetVar("thumbpos","bottom");
		$settingsParams->updateSettingValue("theme_panel_position", $getPanelPos);
	}
	
	HelperGalleryUG::addJsText("changedefauts_confirm", __("Do you sure to set the theme panel position defaults?", UNITEGALLERY_TEXTDOMAIN));
	HelperGalleryUG::addJsText("changedefauts_success", __("The default settings has been succssfully changed.", UNITEGALLERY_TEXTDOMAIN));
	HelperGalleryUG::addJsText("changedefauts_template", __("Set [pos] Defaults.", UNITEGALLERY_TEXTDOMAIN));
	
	$panelPosition = $settingsParams->getSettingValue("theme_panel_position", "bottom");

	$posName = ucfirst($panelPosition);
	$settingsParams->updateSettingValue("theme_button_set_defaults", "Set {$posName} Defaults");
	
	
?>