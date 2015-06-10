<?php

defined('_JEXEC') or die('Restricted access');

$settingsParams = new UniteGallerySettingsUG();
$settingsParams->loadXMLFile(GlobalsUG::$pathHelpersSettings."categorytab_params.xml");

$settingsParams->updateSelectToAlignHor("tabs_position");


?>