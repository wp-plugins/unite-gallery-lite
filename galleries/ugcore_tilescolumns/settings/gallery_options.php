<?php 
defined('_JEXEC') or die('Restricted access');


	$settings = new UniteGallerySettingsUG();
	$settings->loadXMLFile(GlobalsUGGallery::$pathSettings."gallery_options.xml");
	
	if(method_exists("UniteProviderFunctionsUG", "addTilesSizeSettings"))
		$settings = UniteProviderFunctionsUG::addTilesSizeSettings($settings);

	if(method_exists("UniteProviderFunctionsUG", "addBigImageSizeSettings"))
		$settings = UniteProviderFunctionsUG::addBigImageSizeSettings($settings, true);
	
	
?>