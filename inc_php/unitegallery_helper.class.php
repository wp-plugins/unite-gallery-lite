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
	class HelperUG extends UniteHelperBaseUG{

		public static $operations;
		
		
		/**
		 * convert url to full url
		 */
		public static function URLtoFull($url){
			$url = trim($url);
			
			if(empty($url))
				return("");
				
			$urlLower = strtolower($url);
			
			if(strpos($urlLower, "http://") !== false || strpos($urlLower, "https://") !== false)
				return($url);
			
			$url = GlobalsUG::$url_base.$url;
			return($url);
		}
		
			/**
			 * convert some url to relative
			 */
		public static function URLtoRelative($url){
						
			$url = str_replace(GlobalsUG::$url_base, "", $url);
		
			return($url);
		}
		
		
		/**
		 * strip base path part from the path
		 */
		public static function pathToRelative($path, $addDots = true){

			$realpath = realpath($path);
			if(!$realpath)
				return($path);
			
			$len = strlen($realpath);
			$realBase = realpath(GlobalsUG::$path_base);
			$relativePath = str_replace($realBase, "", $realpath);
			
			//add dots
			if($addDots == true && strlen($relativePath) != strlen($realpath))
				$relativePath = "..".$relativePath;				
			
			return $relativePath;
		}
		
		
		/**
		 * get details of the image by the image url.
		 */
		public static function getImageDetails($urlImage){
		
			$info = UniteFunctionsUG::getPathInfo($urlImage);
			$urlDir = UniteFunctionsUG::getVal($info, "dirname");
			if(!empty($urlDir))
				$urlDir = $urlDir."/";
		
			$arrInfo = array();
			$arrInfo["url_full"] = GlobalsUG::$url_base.$urlImage;
			$arrInfo["url_dir_image"] = $urlDir;
			$arrInfo["url_dir_thumbs"] = $urlDir.GlobalsUG::DIR_THUMBS."/";
		
			$filepath = GlobalsUG::$path_base.urldecode($urlImage);
			$filepath = realpath($filepath);
		
			$path = dirname($filepath)."/";
			$pathThumbs = $path.GlobalsUG::DIR_THUMBS."/";
		
			$arrInfo["filepath"] = $filepath;
			$arrInfo["path"] = $path;
			$arrInfo["path_thumbs"] = $pathThumbs;
		
			return($arrInfo);
		}
		
		
		/**
		 *
		 * get gallery view
		 */
		public static function getGalleryView($galleryID = ""){
			$urlView = self::getViewUrl(GlobalsUG::VIEW_GALLERY,"id={$galleryID}");
			return($urlView);
		}
		
		
		/**
		 * get advanced gallery view
		 */
		public static function getAdvancedView($galleryID){
			
			$url = self::getViewUrl(GlobalsUG::VIEW_ADVANCED, "id={$galleryID}");
			return($url);
		}
		
		/**
		 *
		 * get preview view
		 * @param $galleryID
		 */
		public static function getPreviewView($galleryID){
			$urlView = self::getViewUrl(GlobalsUG::VIEW_PREVIEW,"id={$galleryID}");
			return($urlView);
		}
		
		/**
		 * get galleries view
		 */
		public static function getGalleriesView(){
			$urlView = self::getViewUrl(GlobalsUG::VIEW_GALLERIES);
			return($urlView);
		}

		/**
		 * get items view
		 */
		public static function getItemsView($galleryID=null){
			
			$options = "";
			if(!empty($galleryID))
				$options = "galleryid={$galleryID}";
			
			$urlView = self::getViewUrl(GlobalsUG::VIEW_ITEMS, $options);
			return($urlView);
		}
		
		
		/**
		 * get ajax url with params for actions in admin only
		 */
		public static function getUrlAjaxActions($clientAction, $params = ""){
			
			$nonce = "";
			if(method_exists("UniteProviderFunctionsUG", "getNonce"))
				$nonce = "&nonce=".UniteProviderFunctionsUG::getNonce();
			
			$urlAjax = GlobalsUG::$url_ajax."?action=unitegallery_ajax_action{$nonce}&client_action={$clientAction}";
			if(!empty($params))
				$urlAjax .= "&".$params;
			
			return($urlAjax);
		}
		
		
		/**
		 *
		 * get url to some view.
		 */
		public static function getViewUrl($viewName,$urlParams=""){
				
			$params = "&view=".$viewName;
			
			if(!empty($urlParams))
				$params .= "&".$urlParams;
			
			$link = GlobalsUG::$url_component_admin.$params;
			
			$link = UniteFunctionsUG::normalizeLink($link);
			
			return($link);
		}
				
		
		/**
		 * require some template from "templates" folder
		 */
		public static function getPathTemplate($templateName, $path = null){
		
			if($path == null)
				$path = GlobalsUG::$pathTemplates;
		
			$pathTemplate = $path.$templateName.".php";
			UniteFunctionsUG::validateFilepath($pathTemplate,"Template");
		
			return($pathTemplate);
		}
		
		/**
		 * get filename title from some url
		 * used to get item title from image url
		 */
		public static function getTitleFromUrl($url, $defaultTitle = "item"){
			
			$info = pathinfo($url);
			$filename = UniteFunctionsUG::getVal($info, "filename");
			$filename = urldecode($filename);
			
			$title = $defaultTitle;
			if(!empty($filename))
				$title = $filename;
			
			
			return($title);
		}
		
		
		/**
		 *
		 * register script helper function
		 * @param $scriptFilename
		 */
		public static function addScript($scriptName, $folder="js", $handle=null){
			if($handle == null)
				$handle = GlobalsUG::PLUGIN_NAME."-".$scriptName;
			
			UniteProviderFunctionsUG::addScript($handle, GlobalsUG::$urlPlugin .$folder."/".$scriptName.".js");
		}
		
		
		/**
		 *
		 * register common script helper function
		 * the handle for the common script is coming without plugin name
		 */
		public static function addScriptCommon($scriptName, $handle=null, $folder="js"){
			if($handle == null)
				$handle = $scriptName;
		
			self::addScript($scriptName, $folder, $handle);
		}

		/**
		 *
		 * register script helper function
		 * @param $scriptFilename
		 */
		public static function addScriptAbsoluteUrl($urlScript, $handle){
		
			UniteProviderFunctionsUG::addScript($handle, $urlScript);
			
		}
		
		
		/**
		 *
		 * register style helper function
		 * @param $styleFilename
		 */
		public static function addStyle($styleName,$handle=null,$folder="css"){
			if($handle == null)
				$handle = GlobalsUG::PLUGIN_NAME."-".$styleName;
			
			UniteProviderFunctionsUG::addStyle($handle, GlobalsUG::$urlPlugin .$folder."/".$styleName.".css");
			
		}
		
		
		/**
		 *
		 * register common script helper function
		 * the handle for the common script is coming without plugin name
		 */
		public static function addStyleCommon($styleName,$handle=null,$folder="css"){
			if($handle == null)
				$handle = $styleName;
			self::addStyle($styleName,$handle,$folder);
		
		}
		
		
		/**
		 *
		 * register style absolute url helper function
		 */
		public static function addStyleAbsoluteUrl($styleUrl, $handle){
			
			UniteProviderFunctionsUG::addStyle($handle, $styleUrl);
			
		}

		
		/**
		 * put style inside the page
		 */
		public static function addStyleInline($style){
			
			UniteProviderFunctionsUG::addStyleInline($style);
			
		}
		
		
		/**
		 * put scripts of some gallery
		 */
		public static function putGalleryScripts($objGallery){
		
			$galleryID = $objGallery->getID();
		
			$objType = $objGallery->getObjType();
			GlobalsUGGallery::init($objType, $objGallery, $galleryID);
		
			//require the gallery includes
			$filepathIncludes = GlobalsUGGallery::$pathBase."includes.php";
			if(file_exists($filepathIncludes))
				require_once $filepathIncludes;
		
			$filepathOutput = GlobalsUGGallery::$pathBase."client_output.php";
			UniteFunctionsUG::validateFilepath($filepathOutput);
		
			//get the output object
			$arrOptions = array("scriptsonly"=>true);
			require $filepathOutput;
		}
		
		
		/**
		 * output some gallery by alias
		 * mixed - alias or id
		 * outputType - can be alias or ID
		 * arrItems - alternative items
		 */
		public static function outputGallery($mixed, $catID = null, $outputType = "alias", $arrItems = null){
			
			try{
			
				if ($mixed instanceof UniteGalleryGallery) {
					
					$objGallery = $mixed;
					
				}else{
					
					//init the gallery enviropment
					$objGallery = new UniteGalleryGallery();
					
					if($outputType == "alias")
						$objGallery->initByAlias($mixed);
					else
						$objGallery->initByID($mixed);
				}
				
				$galleryID = $objGallery->getID();
				
				$objType = $objGallery->getObjType();
				GlobalsUGGallery::init($objType, $objGallery, $galleryID);
				
				$filepathOutput = GlobalsUGGallery::$pathBase."client_output.php";
				UniteFunctionsUG::validateFilepath($filepathOutput);
				
				//require the gallery includes
				$filepathIncludes = GlobalsUGGallery::$pathBase."includes.php";
				if(file_exists($filepathIncludes))
					require_once $filepathIncludes;
	
				$arrOptions = array();
				$arrOptions["categoryid"] = "";
				
				if($catID && is_numeric($catID) && $catID > 0)
					$arrOptions["categoryid"] = $catID;
				
				if($arrItems !== null)
					$arrOptions["items"] = $arrItems;
				
				//run the output
				require $filepathOutput;
				
				if(!isset($uniteGalleryOutput))
					UniteFunctionsUG::throwError("uniteGalleryOutput variable not found");
				
				return($uniteGalleryOutput);
				
			}catch(Exception $e){
				$message = "<b>Unite Gallery Error:</b><br><br> ".$e->getMessage();
			
				$operations = new UGOperations();
				$operations->putModuleErrorMessage($message);
			}
				
		}
		
		
		/**
		 * function that never used, for autocomplete only
		 */
		private function init(){
			self::$operations = new UGOperations();
		}
		
	}
	
	//init the operations
	HelperUG::$operations = new UGOperations();
	
	
?>
