<?php


defined('_JEXEC') or die('Restricted access');

/**
 * passed $arrOptions to this file
 */
	
	$output = new UGTilesColumnsOutput();
	
	$uniteGalleryOutput = $output->putGallery(GlobalsUGGallery::$galleryID, $arrOptions);
?>