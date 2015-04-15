<?php


defined('_JEXEC') or die('Restricted access');

	
	$output = new UGTilesJustifiedOutput();
	echo $output->putGallery(GlobalsUGGallery::$galleryID);

?>