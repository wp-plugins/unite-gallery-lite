<?php
/**
 * @package Unite Gallery
 * @author UniteCMS.net / Valiano
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('_JEXEC') or die('Restricted access');


	$objGalleries = new UniteGalleryGalleries();
	$arrGalleryTypes = $objGalleries->getArrGalleryTypesShort();
	$arrGalleries = $objGalleries->getArrGalleries("title");
	$headerTitle = __("Galleries", UNITEGALLERY_TEXTDOMAIN);
	
	require HelperUG::getPathTemplate("galleries");
?>