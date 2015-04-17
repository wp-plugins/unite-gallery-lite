<?php
/**
 * @package Unite Gallery
 * @author UniteCMS.net / Valiano
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('_JEXEC') or die('Restricted access');

	$pathViewSettings = HelperGalleryUG::getPathView("settings", false);
	
	if(file_exists($pathViewSettings))	
		require $pathViewSettings;
	else
		require HelperGalleryUG::getPathViewHelper("settings_common");

?>