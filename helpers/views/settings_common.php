<?php

defined('_JEXEC') or die('Restricted access');


	//require settings
	$galleryID = GlobalsUGGallery::$galleryID;
	$isNewGallery = empty($galleryID);
	
	require HelperGalleryUG::getFilepathSettings("gallery_settings");

	$outputMain = new UniteSettingsProductUG();
	$outputParams = new UniteSettingsProductSidebarUG();
	
	$filepathBeforeDraw = HelperGalleryUG::getPathView("settings_before_draw", false);
	
	if($isNewGallery){		
		$galleryTitle = GlobalsUGGallery::$galleryTypeTitle;
		$headerTitle = $galleryTitle . __(" - [settings]",UNITEGALLERY_TEXTDOMAIN);
		
		if(file_exists($filepathBeforeDraw))
			require_once $filepathBeforeDraw;		
		
		$outputMain->init($settingsMain);
		$outputParams->init($settingsParams);
		
		require HelperGalleryUG::getPathHelperTemplate("gallery_new");
		
	}else{		
		$galleryTitle = GlobalsUGGallery::$gallery->getTitle();
		$headerTitle = $galleryTitle . __(" - [settings]",UNITEGALLERY_TEXTDOMAIN);
		
		$galleryType = GlobalsUGGallery::$gallery->getTypeName();
		
		$arrValues = GlobalsUGGallery::$gallery->getParamsForSettings();

		//get categories select dialog
		$objCategories = new UniteGalleryCategories();		
		$arrCats = $objCategories->getCatsShort("component");
		$htmlSelectCats = UniteFunctionsUG::getHTMLSelect($arrCats, "", "id='ds_select_cats'", true);
		
		//set setting values from the slider
		$settingsMain->setStoredValues($arrValues);		
		$settingsParams->setStoredValues($arrValues);

		if(isset($filepathBeforeDraw) && file_exists($filepathBeforeDraw))
			require_once $filepathBeforeDraw;
		
		$outputMain->init($settingsMain);
		$outputParams->init($settingsParams);		
		
		require HelperGalleryUG::getPathHelperTemplate("gallery_edit");
	}

?>
