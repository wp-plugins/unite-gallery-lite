<?php
/**
 * @package Unite Gallery
 * @author UniteCMS.net / Valiano
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('_JEXEC') or die('Restricted access');

	
	$isGalleryPage = GlobalsUGGallery::$isInited;
	
	$objCats = new UniteGalleryCategories();
	$htmlCatList = $objCats->getHtmlCatList();
	$htmlCatSelect = $objCats->getHtmlSelectCats();
	
	$itemsType = "all";
	if($isGalleryPage)
	   $itemsType = GlobalsUGGallery::$objGalleryType->getItemsType();
		
	//init item menu
	$arrMenuItem = array();
	$arrMenuItem["edit_item"] = __("Edit Item",UNITEGALLERY_TEXTDOMAIN);
	$arrMenuItem["edit_title"] = __("Edit Title",UNITEGALLERY_TEXTDOMAIN);
	$arrMenuItem["preview_item"] = __("Preview Item",UNITEGALLERY_TEXTDOMAIN);
	$arrMenuItem["delete"] = __("Delete",UNITEGALLERY_TEXTDOMAIN);
	$arrMenuItem["duplicate"] = __("Duplicate",UNITEGALLERY_TEXTDOMAIN);
	
	//init multiple item menu
	$arrMenuItemMultiple = array();
	$arrMenuItemMultiple["delete"] = __("Delete",UNITEGALLERY_TEXTDOMAIN);
	$arrMenuItemMultiple["duplicate"] = __("Duplicate",UNITEGALLERY_TEXTDOMAIN);
	
	//init field menu
	$arrMenuField = array();
	if($itemsType != "video")
		$arrMenuField["add_image"] = __("Add Image",UNITEGALLERY_TEXTDOMAIN);
	
	if($itemsType != "images")
		$arrMenuField["add_video"] = __("Add Video",UNITEGALLERY_TEXTDOMAIN);
	
	$arrMenuField["select_all"] = __("Select All",UNITEGALLERY_TEXTDOMAIN);
	
	//init category menu
	$arrMenuCat = array();
	$arrMenuCat["edit_category"] = __("Edit Category",UNITEGALLERY_TEXTDOMAIN);
	$arrMenuCat["delete_category"] = __("Delete Category",UNITEGALLERY_TEXTDOMAIN);
	
	//init category field menu
	$arrMenuCatField = array();
	$arrMenuCatField["add_category"] = __("Add Category",UNITEGALLERY_TEXTDOMAIN);
	
	$headerTitle = __("Items", UNITEGALLERY_TEXTDOMAIN);
	$selectedCategory = "";
	
	//set gallery related items
	if($isGalleryPage == true){
		$galleryTitle = GlobalsUGGallery::$gallery->getTitle();
		$headerTitle = $galleryTitle ." - ". __("[images]", UNITEGALLERY_TEXTDOMAIN);		
		$selectedCategory = GlobalsUGGallery::$gallery->getParam("category");
	}
			
	require HelperUG::getPathTemplate("items");
?>
