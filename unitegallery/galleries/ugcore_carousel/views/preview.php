<?php


defined('_JEXEC') or die('Restricted access');

	
	$output = new UGCarouselOutput();
	echo $output->putGallery(GlobalsUGGallery::$galleryID);

?>