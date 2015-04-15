<?php


defined('_JEXEC') or die('Restricted access');

	
	$output = new UGGridThemeOutput();
	echo $output->putGallery(GlobalsUGGallery::$galleryID);

?>