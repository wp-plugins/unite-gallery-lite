<?php


defined('_JEXEC') or die('Restricted access');

	
	$output = new UGCompactThemeOutput();
	echo $output->putGallery(GlobalsUGGallery::$galleryID);

?>