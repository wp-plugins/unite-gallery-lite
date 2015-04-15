<?php


defined('_JEXEC') or die('Restricted access');

	
	$output = new UGDefaultThemeOutput();
	echo $output->putGallery(GlobalsUGGallery::$galleryID);

?>