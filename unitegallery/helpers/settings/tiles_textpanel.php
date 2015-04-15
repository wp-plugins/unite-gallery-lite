<?php

defined('_JEXEC') or die('Restricted access');


$settings = new UniteGallerySettingsUG();
$settings->loadXMLFile(GlobalsUG::$pathHelpersSettings."tiles_textpanel.xml");

$settings->updateSelectToAlignHor("tile_textpanel_title_text_align");
