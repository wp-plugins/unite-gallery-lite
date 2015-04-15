<?php


defined('_JEXEC') or die('Restricted access');


$settings = new UniteGallerySettingsUG();
$settings->loadXMLFile(GlobalsUG::$pathHelpersSettings."thumbnail.xml");

$settings->updateSelectToEasing("thumb_transition_easing");


?>