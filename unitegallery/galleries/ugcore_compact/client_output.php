<?php
defined('_JEXEC') or die('Restricted access');

/**
 * passed $arrOptions to this file
 */
	
	$output = new UGCompactThemeOutput();
	
	$uniteGalleryOutput = $output->putGallery(GlobalsUGGallery::$galleryID, $arrOptions);
?>