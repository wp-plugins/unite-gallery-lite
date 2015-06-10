<?php


defined('_JEXEC') or die('Restricted access');


$settings = new UniteGallerySettingsUG();
$settings->loadXMLFile(GlobalsUG::$pathHelpersSettings."troubleshooting.xml");

if(method_exists("UniteProviderFunctionsUG", "modifyTroubleshooterSettings"))
	$settings = UniteProviderFunctionsUG::modifyTroubleshooterSettings($settings);


?>