<?php
/**
 * @package Unite Gallery
 * @author UniteCMS.net / Valiano
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('_JEXEC') or die('Restricted access');


	class GlobalsUGGallery{
		
		public static $isInited = false;
		public static $gallery;
		public static $objGalleryType;
		public static $galleryTypeName;
		public static $galleryTypeTitle;
		public static $galleryID;
		
		public static $type;
		public static $pathBase;
		public static $pathViews;
		public static $pathTemplates;
		public static $pathSettings;
		
		public static $urlBase;
		public static $urlJs;
		public static $urlCss;
		public static $urlImages;
		
		
		/**
		 * 
		 * init globals static vars by the gallery object
		 */
		public static function init(UniteGalleryGalleryType $galleryType, $objGallery, $galleryID){
			
			self::$objGalleryType = new UniteGalleryGalleryType();
			self::$objGalleryType = $galleryType;
						
			self::$galleryTypeName = self::$objGalleryType->getName();
			self::$galleryTypeTitle = self::$objGalleryType->getTypeTitle();
			
			self::$gallery = new UniteGalleryGallery();
			self::$gallery = $objGallery;
			
			self::$galleryID = $galleryID;
			
			self::$type = self::$objGalleryType->getName();
			self::$pathBase = self::$objGalleryType->getPathGallery();
			self::$urlBase = self::$objGalleryType->getUrlGalleryBase();
			
			self::$pathViews = self::$pathBase."views/";
			self::$pathTemplates = self::$pathViews."templates/";
			self::$pathSettings = self::$pathBase."settings/";
			
			self::$urlCss = self::$urlBase."css/";
			self::$urlJs = self::$urlBase."js/";
			self::$urlImages = self::$urlBase."images/";
			
			self::$isInited = true;
			
		}
		
	}
?>