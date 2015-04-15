<?php


defined('_JEXEC') or die('Restricted access');


$settings = new UniteGallerySettingsUG();
$settings->loadXMLFile(GlobalsUG::$pathHelpersSettings."general.xml");

$settings->updateSelectToSkins("gallery_skin", "default", true);

?>