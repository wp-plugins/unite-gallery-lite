<?php


defined('_JEXEC') or die('Restricted access');

	
	$output = new UGTilesGridOutput();
	echo $output->putGallery(GlobalsUGGallery::$galleryID);

?>