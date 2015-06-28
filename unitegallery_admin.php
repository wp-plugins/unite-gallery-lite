<?php
/**
 * @package Unite Gallery
 * @author UniteCMS.net / Valiano
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('_JEXEC') or die('Restricted access');


	class UniteGalleryAdmin extends UniteBaseAdminClassUG{
		
		const DEFAULT_VIEW = "galleries";

		public static $currentGalleryType;
		
		
		/**
		 * 
		 * the constructor
		 */
		public function __construct(){
			
			parent::__construct();

		}
		
		/**
		 * init the gallery framework by type name
		 */
		protected static function initGalleryFramework($galleryTypeName, $galleryID=""){
			
			$objGallery = "";
			if(!empty($galleryID)){
				$objGallery = new UniteGalleryGallery();
				$objGallery->initByID($galleryID);
				$galleryTypeName = $objGallery->getTypeName();
			}			
			
			UniteFunctionsUG::validateNotEmpty($galleryTypeName,"Gallery Type Name");
			
			$galleries = new UniteGalleryGalleries();
			
			self::$currentGalleryType = new UniteGalleryGalleryType();
			self::$currentGalleryType = $galleries->getGalleryTypeByName($galleryTypeName);
			
			GlobalsUGGallery::init(self::$currentGalleryType, $objGallery, $galleryID);
		}
		
		
		/**
		 * 
		 * init current gallery
		 * for gallery view only
		 */
		protected function initCurrentGallery(){
			
			switch(self::$view){
				case GlobalsUG::VIEW_GALLERY:
				case GlobalsUG::VIEW_PREVIEW:					
				case GlobalsUG::VIEW_CATEGORY_TABS:
				case GlobalsUG::VIEW_ADVANCED:
					$galleryID = UniteFunctionsUG::getPostGetVariable("id","", UniteFunctionsUG::VALIDATE_NUMERIC_OR_EMPTY);
				break;
				case GlobalsUG::VIEW_ITEMS:
					$galleryID = UniteFunctionsUG::getPostGetVariable("galleryid","",UniteFunctionsUG::VALIDATE_NUMERIC_OR_EMPTY);
					if(empty($galleryID))
						return(false);
				break;
				default:
					return(false);
				break;
			}
						
			$objGallery = "";
			if(!empty($galleryID)){
				$objGallery = new UniteGalleryGallery();
				$objGallery->initByID($galleryID);
				$galleryTypeName = $objGallery->getTypeName();
			}else{
				$galleryTypeName = UniteFunctionsUG::getPostGetVariable("type");				
			}
			
			self::initGalleryFramework($galleryTypeName, $galleryID);
		}

		
		/**
		 * 
		 * validate that current gallery inited
		 */
		protected static function validateCurrentGalleryInited(){
			if(empty(self::$currentGalleryType))
				UniteFunctionsUG::throwError("Curent galery don't inited");
		}
		
		
		/**
		 * 
		 * init all actions
		 */
		public function init(){
			
			GlobalsUG::$is_admin = true;
						
			$this->initCurrentGallery();
			
		}
		
		
		/**
		 * add scripts to normal pages
		 */
		public static function addScriptsNormal(){
			parent::addCommonScripts();
			
			HelperUG::addScript("unitegallery_admin");
			HelperUG::addScript("unitegallery_items");
			HelperUG::addStyle("unitegallery_styles","unitegallery_css","css");
						
			if(!empty(self::$currentGalleryType)){
				$pathGalleryScripts = self::$currentGalleryType->getPathScriptsIncludes();
				if(file_exists($pathGalleryScripts))
					require $pathGalleryScripts;
			}
			
			//provider admin always comes to end
			HelperUG::addStyleAbsoluteUrl(GlobalsUG::$url_provider."assets/provider_admin.css", "provider_admin_css");
			HelperUG::addScriptAbsoluteUrl(GlobalsUG::$url_provider."assets/provider_admin.js", "provider_admin_js");
			
		}
						
		
		/**
		 * 
		 * a must function. adds scripts on the page
		 * add all page scripts and styles here.
		 * pelase don't remove this function
		 * common scripts even if the plugin not load, use this function only if no choise.
		 */
		public static function onAddScripts(){
			
			if(self::$view != GlobalsUG::VIEW_MEDIA_SELECT)
				self::addScriptsNormal();	
					
		}
		
		
		/**
		 * 
		 * admin main page function.
		 */
		public static function adminPages(){
							
			if(self::$view != GlobalsUG::VIEW_MEDIA_SELECT)
				self::setMasterView("master_view");

			self::requireView(self::$view);
			
		}
		
		
		/**
		 * call gallery action, include gallery framework first
		 */
		public static function onGalleryAjaxAction($typeName, $action, $data, $galleryID){
			if(empty($data))
				$data = array();
			
			self::initGalleryFramework($typeName, $galleryID);
			
			$filepathAjax = GlobalsUGGallery::$pathBase."ajax_actions.php";
			UniteFunctionsUG::validateFilepath($filepathAjax, "Ajax request error: ");
			
			require $filepathAjax;
			
			UniteFunctionsUG::throwError("No ajax response from gallery: <b>{$typeName} </b> to action <b>{$action}</b>");
		}
		
		
		/**
		 * 
		 * onAjax action handler
		 */
		public static function onAjaxAction(){
			
			$actionType = UniteFunctionsUG::getPostGetVariable("action");
			
			if($actionType != "unitegallery_ajax_action")
				return(false);
			
			$gallery = new UniteGalleryGallery();
			$galleries = new UniteGalleryGalleries();
			$categories = new UniteGalleryCategories();
			$items = new UniteGalleryItems();
			
			$operations = new UGOperations();

			$action = UniteFunctionsUG::getPostGetVariable("client_action"); 

			$data = UniteFunctionsUG::getPostVariable("data"); 
			
			$data = UniteProviderFunctionsUG::normalizeAjaxInputData($data);
			
			$galleryType = UniteFunctionsUG::getPostVariable("gallery_type");
			
			$urlGalleriesView = HelperUG::getGalleriesView();
			
			try{
				
				switch($action){
					case "gallery_actions":
						$galleryID = UniteFunctionsUG::getVal($data, "galleryID");
						$galleryAction = UniteFunctionsUG::getVal($data, "gallery_action");
						$galleryData = UniteFunctionsUG::getVal($data, "gallery_data", array());
						self::onGalleryAjaxAction($galleryType, $galleryAction, $galleryData, $galleryID);
					break;
					case "get_thumb_url":
												
						$urlImage = UniteFunctionsUG::getVal($data, "urlImage");
						$imageID = UniteFunctionsUG::getVal($data, "imageID");
						
						$urlThumb = $operations->getThumbURLFromImageUrl($urlImage, $imageID);
						$arrData = array("urlThumb"=>$urlThumb);
						HelperUG::ajaxResponseData($arrData);
					break;
					case "add_category":
						$catData = $categories->addFromData();
						HelperUG::ajaxResponseData($catData);
					break;
					case "remove_category":
						$response = $categories->removeFromData($data);
						
						HelperUG::ajaxResponseSuccess(__("The category deleted successfully.",UNITEGALLERY_TEXTDOMAIN),$response);
					break;
					case "update_category":
						
						$categories->updateFromData($data);
						HelperUG::ajaxResponseSuccess(__("Category updated.",UNITEGALLERY_TEXTDOMAIN));
					break;
					case "update_cat_order":
						$categories->updateOrderFromData($data);
						HelperUG::ajaxResponseSuccess(__("Order updated.",UNITEGALLERY_TEXTDOMAIN));
					break;
					case "add_item":
						$itemData = $items->addFromData($data);						
						HelperUG::ajaxResponseData($itemData);
					break;
					case "get_item_data":			
						$response = $items->getItemData($data);
						HelperUG::ajaxResponseData($response);
					break;
					case "update_item_data":
						
						$response = $items->updateItemData($data);
												
						HelperUG::ajaxResponseSuccess(__("Item data updated!",UNITEGALLERY_TEXTDOMAIN), $response);
					break;
					case "remove_items":
						$response = $items->removeItemsFromData($data);
						HelperUG::ajaxResponseSuccess(__("Items Removed",UNITEGALLERY_TEXTDOMAIN),$response);						
					break;					
					case "get_cat_items":
						$responeData = $items->getCatItemsHtmlFromData($data);
												
						//update category param if inside gallery						
						$gallery->updateItemsCategoryFromData($data);
						
						HelperUG::ajaxResponseData($responeData);
					break;
					case "update_item_title":
						$items->updateItemTitleFromData($data);
						HelperUG::ajaxResponseSuccess(__("Item Title Updated",UNITEGALLERY_TEXTDOMAIN));
					break;
					case "duplicate_items":
						$response = $items->duplicateItemsFromData($data);
						HelperUG::ajaxResponseSuccess(__("Items Duplicated",UNITEGALLERY_TEXTDOMAIN),$response);
					break;
					case "update_items_order":
						$items->saveOrderFromData($data);
						HelperUG::ajaxResponseSuccess(__("Order Saved",UNITEGALLERY_TEXTDOMAIN));
					break;
					case "copy_move_items":
						$response = $items->copyMoveItemsFromData($data);
						HelperUG::ajaxResponseSuccess(__("Done Operation",UNITEGALLERY_TEXTDOMAIN),$response);
					break;
					case "create_gallery":
						$galleryID = $galleries->addGaleryFromData($galleryType, $data);
						$urlView = HelperUG::getGalleryView($galleryID);
						HelperUG::ajaxResponseSuccessRedirect(__("Gallery Created",UNITEGALLERY_TEXTDOMAIN),$urlView);
					break;
					case "delete_gallery":
						$galleries->deleteGalleryFromData($data);
						HelperUG::ajaxResponseSuccessRedirect(__("Gallery deleted",UNITEGALLERY_TEXTDOMAIN), $urlGalleriesView);
					break;
					case "update_gallery":
						
						$galleries->updateGalleryFromData($data);
						HelperUG::ajaxResponseSuccess(__("Gallery Updated"));
					break;
					case "duplicate_gallery":
						$galleries->duplicateGalleryFromData($data);
						HelperUG::ajaxResponseSuccessRedirect(__("Gallery duplicated",UNITEGALLERY_TEXTDOMAIN), $urlGalleriesView);
					break;
					case "update_plugin":
						
						if(method_exists("UniteProviderFunctionsUG", "updatePlugin"))
							UniteProviderFunctionsUG::updatePlugin();
						else{
							echo "Functionality Don't Exists";
						}
						
					break;
					case "export_gallery_settings":
						$galleryID = UniteFunctionsUG::getPostGetVariable("galleryid");
						$galleries->exportGallerySettings($galleryID);
						
					break;
					case "import_gallery_settings":
						$galleryID = UniteFunctionsUG::getPostGetVariable("galleryid");
						$galleries->importGallerySettingsFromUploadFile($galleryID);
					break;
					default:
						HelperUG::ajaxResponseError("wrong ajax action: <b>$action</b> ");
					break;
				}
				
			}
			catch(Exception $e){
				$message = $e->getMessage();
				
				$errorMessage = $message;
				if(GlobalsUG::SHOW_TRACE == true){
					$trace = $e->getTraceAsString();
					$errorMessage = $message."<pre>".$trace."</pre>";					
				}
				
				HelperUG::ajaxResponseError($errorMessage);
			}
			
			//it's an ajax action, so exit
			HelperUG::ajaxResponseError("No response output on <b> $action </b> action. please check with the developer.");
			exit();
		}
		
		
	}
	
	
?>