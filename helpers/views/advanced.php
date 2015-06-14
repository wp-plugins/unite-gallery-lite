<?php

defined('_JEXEC') or die('Restricted access');

	$galleryID = GlobalsUGGallery::$galleryID;

	//add codemirror scripts
	HelperUG::addScriptAbsoluteUrl(GlobalsUG::$urlPlugin."js/codemirror/codemirror.js", "codemirror_js");
	HelperUG::addScriptAbsoluteUrl(GlobalsUG::$urlPlugin."js/codemirror/css.js", "codemirror_cssjs");
	HelperUG::addScriptAbsoluteUrl(GlobalsUG::$urlPlugin."js/codemirror/javascript.js", "codemirror_jsjs");
	HelperUG::addStyleAbsoluteUrl(GlobalsUG::$urlPlugin."js/codemirror/codemirror.css", "codemirror_css");
	
	//enable advanced tab if disabled
	$showAdvanced = GlobalsUGGallery::$gallery->getParam("show_advanced_tab");
	$showAdvanced = UniteFunctionsUG::strToBool($showAdvanced);
	
	if($showAdvanced == false){
		GlobalsUGGallery::$gallery->updateParam("show_advanced_tab", "true");
	}
	
	require GlobalsUG::$pathHelpersSettings."advancedtab_main.php";
	
	$outputMain   = new UniteSettingsProductUG();
	
	$galleryTitle = GlobalsUGGallery::$gallery->getTitle();
		
	$headerTitle = $galleryTitle . __(" - [advanced settings]",UNITEGALLERY_TEXTDOMAIN);

    $arrValues = GlobalsUGGallery::$gallery->getParams();
	
    //set setting values from the slider
    $settingsMain->setStoredValues($arrValues);

    $outputMain->init($settingsMain);
    
    require HelperGalleryUG::getPathHelperTemplate("gallery_advanced");
?>
