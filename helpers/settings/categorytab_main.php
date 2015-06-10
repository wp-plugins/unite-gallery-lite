<?php

defined('_JEXEC') or die('Restricted access');


	$settingsMain = new UniteGallerySettingsUG();
	
	$settingsMain->addRadioBoolean("enable_category_tabs", __("Enable Category Tabs", UNITEGALLERY_TEXTDOMAIN), false);
	
	$settingsMain->addHr();
	
	//add categories select
	$objCategories = new UniteGalleryCategories();
	$arrCats = $objCategories->getCatsShort();		
	$settingsMain->addSelect("available_cats", $arrCats, __("Available Categories", UNITEGALLERY_TEXTDOMAIN),"");
	
	$settingsMain->addTextBox("categorytabs_ids", "", "Hidden Cats", array("hidden"=>true));
	
	$settingsMain->addSelect("tabs_init_catid", array(), __("First Selected Tab", UNITEGALLERY_TEXTDOMAIN),"");
	
	
?>