<?php


defined('_JEXEC') or die('Restricted access');

		
	$output = new UGVideoThemeOutput();
	echo $output->putGallery(GlobalsUGGallery::$galleryID);

?>