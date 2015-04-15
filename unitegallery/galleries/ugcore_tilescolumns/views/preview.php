<?php


defined('_JEXEC') or die('Restricted access');

	
	$output = new UGTilesColumnsOutput();
	echo $output->putGallery(GlobalsUGGallery::$galleryID);

?>