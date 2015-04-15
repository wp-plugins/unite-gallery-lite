<?php


defined('_JEXEC') or die('Restricted access');


$settings = new UniteGallerySettingsUG();
$settings->loadXMLFile(GlobalsUG::$pathHelpersSettings."strip_panel.xml");

$settings->updateSelectToSkins("strippanel_buttons_skin", "");
$settings->updateSelectToSkins("strippanel_handle_skin", "");

$settings->updateSelectToAlignCombo("strippanel_handle_align");
$settings->updateSelectToEasing("strip_scroll_to_thumb_easing");
$settings->updateSelectToAlignCombo("strip_thumbs_align");


?>