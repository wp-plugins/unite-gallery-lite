<?php
/**
 * @package Unite Gallery
 * @author UniteCMS.net / Valiano
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('_JEXEC') or die('Restricted access');


	class GlobalsUG{
		
		const SHOW_TRACE = false;
		const ENABLE_TRANSLATIONS = false;
		
		const PLUGIN_TITLE = "Unite Gallery Lite";
		const PLUGIN_NAME = "unitegallery";
		
		const PERMISSION = "admin";			//admin, editor
		
		const TABLE_GALLERIES_NAME = "unitegallery_galleries";
		const TABLE_ITEMS_NAME = "unitegallery_items";
		const TABLE_CATEGORIES_NAME = "unitegallery_categories";
				
		const DIR_THUMBS = "unitegallery_thumbs";
		const THUMB_WIDTH = 300;
		const THUMB_WIDTH_LARGE = 768;
		const THUMB_WIDTH_BIG = 1024;
		
		const VIEW_DEFAULT = "galleries";
		const VIEW_ITEMS = "items";
		const VIEW_GALLERIES = "galleries";
		const VIEW_GALLERY = "gallery";
      	        const VIEW_CATEGORY_TABS = "categorytabs";
       	        const VIEW_ADVANCED = "advanced";
		const VIEW_PREVIEW = "preview";
		const VIEW_MEDIA_SELECT = "mediaselect";
		
		const FIELDS_ITEMS = "published,title,alias,type,ordering,catid,imageid,url_image,url_thumb,contentid,content,params";
		const FIELDS_GALLERY = "type,title,alias,ordering,params";
		const DEFAULT_JPG_QUALITY = 81;
		
		const YOUTUBE_EXAMPLE_ID = "A3PDXmYoF5U";
		const VIMEO_EXAMPLE_ID = "73234449";
		const WISTIA_EXAMPLE_ID = "9oedgxuciv";
		
		public static $table_galleries;
		public static $table_categories;
		public static $table_items;
		
		public static $pathSettings;
		public static $filepathItemSettings;
		public static $pathPlugin;
		public static $pathGalleries;
		public static $pathTemplates;
		public static $pathViews;
		public static $pathHelpersViews;
		public static $pathHelpersTemplates;
		public static $pathHelpersClasses;
		public static $pathHelpersSettings;
		
		public static $url_base;
		public static $url_media_ug;
		public static $url_images;
		public static $url_component_client;
		public static $url_component_admin;
		public static $url_ajax;
		public static $url_ajax_front;
		public static $urlPlugin;
		public static $urlGalleries;
		public static $url_elfinder;
		public static $url_provider;
		
		public static $is_admin;
		public static $path_base;
		public static $path_media_ug;		
		public static $path_cache;
		public static $path_images;
		public static $path_elfinder;
		
		public static $arrClientSideText = array();
		
		
		/**
		 * init globals
		 */
		public static function initGlobals($pluginFolder){

			UniteProviderFunctionsUG::initGlobalsBase($pluginFolder);
			
			self::$is_admin == false;	//this var set from admin object
			
			self::$pathSettings = self::$pathPlugin."settings/";
			self::$pathGalleries = self::$pathPlugin."galleries/";
			self::$path_elfinder = self::$pathPlugin."libraries/elfinder/";
			self::$pathTemplates = self::$pathPlugin."views/templates/";
			self::$pathViews = self::$pathPlugin."views/";
			self::$pathHelpersViews = self::$pathPlugin."helpers/views/";
			self::$pathHelpersTemplates = self::$pathPlugin."helpers/templates/";
			self::$pathHelpersClasses = self::$pathPlugin."helpers/classes/";
			self::$pathHelpersSettings = self::$pathPlugin."helpers/settings/";
			
			UniteFunctionsUG::validateDir(self::$pathGalleries);
			
			self::$filepathItemSettings = self::$pathSettings."item_settings.php";

			self::$urlGalleries = self::$urlPlugin."galleries/";
			self::$url_elfinder = self::$urlPlugin."libraries/elfinder/";
			self::$url_provider = self::$urlPlugin."inc_php/framework/provider/";
			
			self::initClientSideText();
		}

		
		/**
		 * init client side text for globals
		 */
		public static function initClientSideText(){
		
			self::$arrClientSideText = array(
					"please_fill_item_title"=>__("Please fill in item title",UNITEGALLERY_TEXTDOMAIN),
					"updating_item_data"=>__("Updating item data...",UNITEGALLERY_TEXTDOMAIN),
					"loading_item_data"=>__("Loading item data...",UNITEGALLERY_TEXTDOMAIN),
					"edit_item"=>__("Edit Item",UNITEGALLERY_TEXTDOMAIN),
					"edit_media_item"=>__("Edit Media Item",UNITEGALLERY_TEXTDOMAIN),
					"add_image"=>__("Add image (use shift or ctrl for choosing multiple images)",UNITEGALLERY_TEXTDOMAIN),
					"adding_category"=>__("Adding Category...",UNITEGALLERY_TEXTDOMAIN),
					"do_you_sure_remove"=>__("Do you sure to remove this category and it's items?",UNITEGALLERY_TEXTDOMAIN),
					"removing_category"=>__("Removing Category...",UNITEGALLERY_TEXTDOMAIN),
					"cancel"=>__("Cancel",UNITEGALLERY_TEXTDOMAIN),
					"update"=>__("Update",UNITEGALLERY_TEXTDOMAIN),
					"restore"=>__("Restore",UNITEGALLERY_TEXTDOMAIN),
					"updating"=>__("Updating...",UNITEGALLERY_TEXTDOMAIN),
					"restoring"=>__("Restoring...",UNITEGALLERY_TEXTDOMAIN),
					"updating_category"=>__("Updating Category...",UNITEGALLERY_TEXTDOMAIN),
					"adding_item"=>__("Adding Item...",UNITEGALLERY_TEXTDOMAIN),
					"updating_categories_order"=>__("Updating Categories Order...",UNITEGALLERY_TEXTDOMAIN),
					"removing_items"=>__("Removing Items...",UNITEGALLERY_TEXTDOMAIN),
					"updating_title"=>__("Updating Title...",UNITEGALLERY_TEXTDOMAIN),
					"duplicating_items"=>__("Duplicating Items...",UNITEGALLERY_TEXTDOMAIN),
					"updating_items_order"=>__("Updating Items Order...",UNITEGALLERY_TEXTDOMAIN),
					"copying_items"=>__("Copying Items...",UNITEGALLERY_TEXTDOMAIN),
					"moving_items"=>__("Moving Items...",UNITEGALLERY_TEXTDOMAIN),
					"confirm_remove_items"=>__("Are you sure you want to delete these items?",UNITEGALLERY_TEXTDOMAIN),
					"confirm_remove_gallery"=>__("Are you sure you want to delete this gallery?",UNITEGALLERY_TEXTDOMAIN)
			);
		
		}

	}

	//init the globals
	GlobalsUG::initGlobals($currentFolder);
	
?>
