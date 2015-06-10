<?php

defined('_JEXEC') or die('Restricted access');

	//require settings
	$galleryID = GlobalsUGGallery::$galleryID;

	//enable tabs if disabled
	$enableTabs = GlobalsUGGallery::$gallery->getParam("enable_category_tabs");
	$enableTabs = UniteFunctionsUG::strToBool($enableTabs);
	
	if($enableTabs == false){
		GlobalsUGGallery::$gallery->updateParam("enable_category_tabs", "true");
	}
	
	require GlobalsUG::$pathHelpersSettings."categorytab_main.php";
	require GlobalsUG::$pathHelpersSettings."categorytab_params.php";
	
	$outputMain   = new UniteSettingsProductUG();
	$outputParams = new UniteSettingsProductSidebarUG();
	
	$galleryTitle = GlobalsUGGallery::$gallery->getTitle();
		
	$headerTitle = $galleryTitle . __(" - [settings]",UNITEGALLERY_TEXTDOMAIN);

    $arrValues = GlobalsUGGallery::$gallery->getParams();
	
    //set setting values from the slider
    $settingsMain->setStoredValues($arrValues);
    $settingsParams->setStoredValues($arrValues);

    $outputMain->init($settingsMain);
    $outputParams->init($settingsParams);
    require HelperGalleryUG::getPathHelperTemplate("gallery_categorytabs");
?>
