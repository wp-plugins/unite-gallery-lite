<?php


defined('_JEXEC') or die('Restricted access');

	
	$output = new UGSliderThemeOutput();
	echo $output->putGallery(GlobalsUGGallery::$galleryID);

?>