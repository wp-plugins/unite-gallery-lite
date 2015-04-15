<?php

defined('_JEXEC') or die('Restricted access');


$settings = new UniteGallerySettingsUG();
$settings->loadXMLFile(GlobalsUG::$pathHelpersSettings."slider_textpanel.xml");

$settings->updateSelectToAlignVert("slider_textpanel_text_valign");

