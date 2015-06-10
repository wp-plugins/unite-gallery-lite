<?php
/**
 * @package Unite Gallery
 * @author UniteCMS.net / Valiano
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('_JEXEC') or die('Restricted access');

	
	$action = UniteFunctionsUG::getGetVar("action");
	if($action == "connector"){
		require GlobalsUG::$path_elfinder."php/connector.minimal.php";
		runElfinderConnector();
		exit();
	}
	
	//$urlBase = glo
	$urlBase = GlobalsUG::$url_elfinder;
		
	require HelperUG::getPathTemplate("mediaselect");
	exit();
?>