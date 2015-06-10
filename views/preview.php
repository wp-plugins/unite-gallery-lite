<?php
/**
 * @package Unite Gallery
 * @author UniteCMS.net / Valiano
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('_JEXEC') or die('Restricted access');

	
	$galleryTitle = GlobalsUGGallery::$gallery->getTitle();
	$headerTitle = $galleryTitle ." - ". __("[preview]", UNITEGALLERY_TEXTDOMAIN);
	
	require HelperUG::getPathTemplate("preview");
		
?>
