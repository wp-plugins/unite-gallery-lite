<?php


defined('_JEXEC') or die('Restricted access');

/**
 * gets $action, $data , and working framework, so globals and helper works
 * response from helperUG
 */

	switch($action){
		case "get_skin_css":
			$skin = UniteFunctionsUG::getVal($data, "skin");
			$filepath = GlobalsUG::$path_media_ug."themes/video/skin-{$skin}.css";
			$filepath_modified = GlobalsUG::$path_media_ug."themes/video/skin-{$skin}-modified.css";
			
			UniteFunctionsUG::validateFilepath($filepath);
			
			//if exists modified version - get the modified
			if(file_exists($filepath_modified))
				$filepath = $filepath_modified;
				
			$content = file_get_contents($filepath);
			$data = array();
			$data["content"] = $content;
			
			$relativePath = HelperUG::pathToRelative($filepath);
			
			$data["filepath"] = $relativePath;
			HelperUG::ajaxResponseData($data);
		break;
		case "update_skin_css":
			$skin = UniteFunctionsUG::getVal($data, "skin");
			$filepath_modified = GlobalsUG::$path_media_ug."themes/video/skin-{$skin}-modified.css";
			
			$content = UniteFunctionsUG::getVal($data, "css");
			
			UniteFunctionsUG::writeFile($content, $filepath_modified);
			
			HelperUG::ajaxResponseSuccess("Content Updated");
		break;
		case "get_original_skin_css":
			$skin = UniteFunctionsUG::getVal($data, "skin");
			$filepath = GlobalsUG::$path_media_ug."themes/video/skin-{$skin}.css";
			$filepath_modified = GlobalsUG::$path_media_ug."themes/video/skin-{$skin}-modified.css";
			
			if(file_exists($filepath_modified) == false)
				HelperUG::ajaxResponseSuccess("The file is original");

			$content = file_get_contents($filepath);
			$data = array();
			$data["content"] = $content;
			
			HelperUG::ajaxResponseData($data);			
		break;
		default:
			HelperUG::ajaxResponseError("wrong ajax action (Video Theme): <b>$action</b> ");
		break;	
	}

?>