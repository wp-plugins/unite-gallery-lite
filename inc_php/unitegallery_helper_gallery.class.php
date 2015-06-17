<?php
/**
 * @package Unite Gallery
 * @author UniteCMS.net / Valiano
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('_JEXEC') or die('Restricted access');


/**
 * 
 * gallery helper functions class
 *
 */
	class HelperGalleryUG{
		
		/**
		 * 
		 * validate that the current gallery inited
		 */
		private static function validateInited(){
			
			if(empty(GlobalsUGGallery::$objGalleryType))
				UniteFunctionsUG::throwError("Gallery type not inited!");
		}
		
		
		/**
		 * 
		 * get template path of the gallery
		 */
		public static function getPathTemplate($template){
			self::validateInited();
			return HelperUG::getPathTemplate($template, GlobalsUGGallery::$pathTemplates);
		}
		
		/**
		 * get template path from the helper templates folder
		 * 
		 */
		public static function getPathHelperTemplate($template){
			
			return HelperUG::getPathTemplate($template, GlobalsUG::$pathHelpersTemplates);			
		}
		
		
		
		
		/**
		 * 
		 * get view path
		 */
		public static function getPathView($view, $validate=true){
			self::validateInited();
			$filepathView = GlobalsUGGallery::$pathViews.$view.".php";
			
			if($validate == true)
				UniteFunctionsUG::validateFilepath($filepathView, "View not found");
			
			return($filepathView);
		}
		
		/**
		 * 
		 * get viewhelper path
		 */
		public static function getPathViewHelper($view){
			self::validateInited();
			$filepathViewHelper = GlobalsUG::$pathHelpersViews.$view.".php";
			UniteFunctionsUG::validateFilepath($filepathViewHelper, "View not found");
			return($filepathViewHelper);
		}
		
		
		/**
		 * 
		 * get filepath of settings file by name
		 */
		public static function getFilepathSettings($settingsName){
			self::validateInited();
			$filepathSettings = GlobalsUGGallery::$pathSettings.$settingsName.".php";
			UniteFunctionsUG::validateFilepath($filepathSettings, "Settings file not found");
			return($filepathSettings);
		}
		
		
		/**
		 * 
		 * get galleries view url
		 */
		public static function getUrlViewGalleriesList(){
			$url = HelperUG::getViewUrl(GlobalsUG::VIEW_GALLERIES);
			
			return($url);
		}
		
		
		/**
		 * 
		 * get view gallery settings
		 */
		public static function getUrlViewGallery($galleryID = ""){
			
			if(empty($galleryID))
				$galleryID = GlobalsUGGallery::$galleryID;
			
			$url = HelperUG::getViewUrl(GlobalsUG::VIEW_GALLERY,"id={$galleryID}");
			return($url);
		}
		
		
		/**
		 * get items view
		 */
		public static function getUrlViewItems($options=""){
			
			$options = "";			
			if(empty($options)){
				$galleryID = GlobalsUGGallery::$galleryID;
				$options = "galleryid={$galleryID}";
			}
			
			$url = HelperUG::getViewUrl(GlobalsUG::VIEW_ITEMS, $options);
			return($url);	
		}
		
		
		
		/**
		 * 
		 * get view gallery settings
		 */
		public static function getUrlViewCurrentGallery(){
			
			$galleryID = GlobalsUGGallery::$galleryID;
			$url = HelperUG::getViewUrl(GlobalsUG::VIEW_GALLERY,"id={$galleryID}");
			return($url);
		}
		
		
		/**
		 * get view category settings of the current gallery
		 * @return string
		 */
		public static function getUrlViewCategoryTabs(){

			$galleryID = GlobalsUGGallery::$galleryID;
			
			$url = HelperUG::getViewUrl(GlobalsUG::VIEW_CATEGORY_TABS, "id={$galleryID}");
			return($url);
		
		}
		
		
		/**
		 * get view category settings of the current gallery
		 * @return string
		 */
		public static function getUrlViewAdvanced(){
		
			$galleryID = GlobalsUGGallery::$galleryID;
		
			$url = HelperUG::getViewUrl(GlobalsUG::VIEW_ADVANCED, "id={$galleryID}");
			return($url);
		}
		
		
		/**
		 * 
		 * get url ov preview gallery view
		 */
		public static function getUrlViewPreview($galleryID = ""){
			if(empty($galleryID)){
				$galleryID = GlobalsUGGallery::$galleryID;
			}
			
			UniteFunctionsUG::validateNotEmpty($galleryID, "gallery id");
			$url = HelperUG::getPreviewView($galleryID);
			
			return($url);
		}
		
		
		/**
		 * add script to gallery folder
		 * should be called from scripts.php file in gallery root folder
		 */
		public static function addScript($url, $name){
			
			self::addScriptAbsoluteUrl(GlobalsUGGallery::$urlBase.$url, $name);
					
		}
		
		/**
		 * add script absolute url
		 */
		public static function addScriptAbsoluteUrl($url, $name){
			
			HelperUG::addScriptAbsoluteUrl($url, $name);
		
		}
		
		/**
		 * add style to gallery folder
		 * should be called from scripts.php file in gallery root folder
		 */
		public static function addStyle($url, $name){
			
			self::addStyleAbsoluteUrl(GlobalsUGGallery::$urlBase.$url);
			
		}

		
		/**
		 * add style absolute url
		 */
		public static function addStyleAbsoluteUrl($url, $name){
			
			HelperUG::addStyleAbsoluteUrl($url, $name);
			
		}
		
		
		/**
		 * add item to javascript text array
		 */
		public static function addJsText($name, $text){
			
			GlobalsUG::$arrClientSideText[$name] = $text;
			
		}
		
		
		/**
		 * get gallery id from html id
		 */
		public static function getGalleryIDFromHtmlID($galleryHtmlID){
			
			if(empty($galleryHtmlID))
				return("");
			
			$arr = explode("_", $galleryHtmlID);
			if(count($arr) < 3)
				return("");
			
			$galleryID = $arr[count($arr)-2];	//one before the last
			
			return($galleryID);
		}
		
		
	}

?>