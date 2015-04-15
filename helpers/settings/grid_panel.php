<?php


defined('_JEXEC') or die('Restricted access');


$settings = new UniteGallerySettingsUG();
$settings->loadXMLFile(GlobalsUG::$pathHelpersSettings."grid_panel.xml");


$settings->updateSelectToAlignCombo("gridpanel_grid_align");
$settings->updateSelectToSkins("gridpanel_arrows_skin", "");
$settings->updateSelectToAlignCombo("gridpanel_handle_align");
$settings->updateSelectToSkins("gridpanel_handle_skin", "");
$settings->updateSelectToEasing("grid_transition_easing");


?>