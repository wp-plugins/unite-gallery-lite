<?php


defined('_JEXEC') or die('Restricted access');

	HelperGalleryUG::addScript("js/video_admin.js", "video_admin");
	
	//add codemirror scripts
	HelperGalleryUG::addScriptAbsoluteUrl(GlobalsUG::$urlPlugin."js/codemirror/codemirror.js", "codemirror_js");
	HelperGalleryUG::addScriptAbsoluteUrl(GlobalsUG::$urlPlugin."js/codemirror/css.js", "codemirror_cssjs");
	HelperGalleryUG::addStyleAbsoluteUrl(GlobalsUG::$urlPlugin."js/codemirror/codemirror.css", "codemirror_css");
	
?>