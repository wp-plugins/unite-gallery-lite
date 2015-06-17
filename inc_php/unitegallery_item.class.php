<?php
/**
 * @package Unite Gallery
 * @author UniteCMS.net / Valiano
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('_JEXEC') or die('Restricted access');


	class UniteGalleryItem extends UniteElementsBaseUG{
		
		const TYPE_IMAGE = "image";
		const TYPE_YOUTUBE = "youtube";
		const TYPE_VIMEO = "vimeo";
		const TYPE_HTML5VIDEO = "html5video";
		const TYPE_SOUNDCLOUD = "soundcloud";
		const TYPE_WISTIA = "wistia";
		
		private $itemTitleBase = "Item";	
		
		private $id = null;
		private $isInited = false;
		private $title,$data,$type,$urlImage,$urlThumb,$imageID,$alt;
		private $content, $contentID, $params;
		private $isPublished; 
		
		
		/**
		 * 
		 * constructor
		 */
		public function __construct(){
			parent::__construct();
			$itemTitleBase = __("Item",UNITEGALLERY_TEXTDOMAIN);
		}

		/**
		 * 
		 * validate that the item inited
		 */
		private function validateInited(){
			if($this->isInited == false)
				UniteFunctionsUG::throwError("The item is not inited!");
		}
		
		
		/**
		 * init item by ID
		 */
		public function initByID($id){
			
			UniteFunctionsUG::validateNumeric($id, "item id");
			
			$record = $this->db->fetchSingle(GlobalsUG::$table_items,"id={$id}");
			$this->initByDBRecord($record);
		}
		
		
		/**
		 * add data to params array by type
		 * for better clearance of the item params
		 */
		private function initParamsByType(){
			
			//set description
			if($this->type != self::TYPE_IMAGE)
				$this->params["ug_item_description"] = $this->content;
			
			//set content id by type
			switch($this->type){
				case self::TYPE_WISTIA:
				case self::TYPE_VIMEO:
				case self::TYPE_YOUTUBE:
					$this->params["videoid"] = $this->contentID;
				break;
				case self::TYPE_SOUNDCLOUD:
					$this->params["trackid"] = $this->contentID;
				break;
			}
			
		}
		
		
		/**
		 *
		 * init item by db record
		 */
		public function initByDBRecord($record){
						
			$this->isInited = true;
			
			$this->data = $record;
			$jsonParams = UniteFunctionsUG::getVal($record, "params");
						
			$this->id = UniteFunctionsUG::getVal($record, "id");
			
			$this->params = array();
			
			if(!empty($jsonParams))
				$this->params = (array)json_decode($jsonParams);
							
			$this->title = UniteFunctionsUG::getVal($record, "title");
						
			$this->imageID = UniteFunctionsUG::getVal($record, "imageid");
			$this->type = UniteFunctionsUG::getVal($record, "type");
			$this->content = UniteFunctionsUG::getVal($record, "content");
			$this->contentID = UniteFunctionsUG::getVal($record, "contentid");
		
			$published = UniteFunctionsUG::getVal($record, "published");
			$this->isPublished = UniteFunctionsUG::strToBool($published);
		
			//set image by url
			$this->urlImage = UniteFunctionsUG::getVal($record, "url_image");
			if(empty($this->urlImage))
				$this->urlImage = $this->getParam("image");
			
			//HelperUG::URLtoFull($this->getParam("image"));
			$this->urlThumb = UniteFunctionsUG::getVal($record, "url_thumb");
			if(empty($this->urlThumb))
				$this->urlThumb = $this->getParam("thumb");

			if(!empty($this->imageID)){
				
				$this->urlImage = UniteProviderFunctionsUG::getImageUrlFromImageID($this->imageID);
				$this->urlThumb = UniteProviderFunctionsUG::getThumbUrlFromImageID($this->imageID);
			}

			$this->urlImage = HelperUG::URLtoFull($this->urlImage);
			$this->urlThumb = HelperUG::URLtoFull($this->urlThumb);

			$this->initParamsByType();
			
			$this->migrateParamsNewValues();
		}
		
		
		/**
		 * migrate params for new values
		 * example: title to ug_item_title
		 */
		private function migrateParamsNewValues(){
			
			$this->migrateNewValue("title", "ug_item_title");
			$this->migrateNewValue("description", "ug_item_description");
			$this->migrateNewValue("enable_link", "ug_item_enable_link");
			$this->migrateNewValue("link", "ug_item_link");
			$this->migrateNewValue("link_open_in", "ug_item_link_open_in");
			
		}
		
		
		/**
		 * migrate the param to new value
		 */
		private function migrateNewValue($oldName, $newName){
			
			if(is_array($this->params) && array_key_exists($oldName, $this->params) && array_key_exists($newName, $this->params) == false)
				$this->params[$newName] = $this->params[$oldName];
			
		}
		
		
		/**
		 * init by data array
		 */
		public function initByData($data){
			if(empty($data))
				UniteFunctionsUG::throwError("init item error - the data is empty");
			
			$this->isInited = true;
			$this->type = self::TYPE_IMAGE;
			
			$this->title = UniteFunctionsUG::getVal($data, "title");
			
			$params = array();
			$params["ug_item_description"] = UniteFunctionsUG::getVal($data, "description");
			
			$this->alt = UniteFunctionsUG::getVal($data, "alt");
			
			$this->imageID = UniteFunctionsUG::getVal($data, "image_id");
			$this->urlImage = UniteFunctionsUG::getVal($data, "url_image");
			$this->urlThumb = UniteFunctionsUG::getVal($data, "url_thumb");
			
			if(!empty($this->imageID) && empty($this->urlImage))
				$this->urlImage = UniteProviderFunctionsUG::getImageUrlFromImageID($this->imageID);
				
			if(!empty($this->imageID) && empty($this->urlThumb))
				$this->urlThumb = UniteProviderFunctionsUG::getThumbUrlFromImageID($this->imageID);

			$this->urlImage = HelperUG::URLtoFull($this->urlImage);
			$this->urlThumb = HelperUG::URLtoFull($this->urlThumb);
			
			$this->isPublished = true;
			
			$this->params = $params;
		}

		
		/**
		 * 
		 * get some param
		 */
		public function getParam($name, $defaultValue = ""){
			$this->validateInited();
			$value = UniteFunctionsUG::getVal($this->params, $name, $defaultValue);
			return($value);
		}
		
		
		/**
		 * get item params plus title and alias for settings
		 */
		private function getArrValues(){
			$this->validateInited();
			$arrValues = $this->params;
			
			$arrValues["ug_item_title"] = $this->title;
			$arrValues["ug_item_alias"] = $this->getAlias();
			
			$arrValues["title"] = $this->title;
			$arrValues["alias"] = $this->getAlias();
			
			//set old style values comatability
			$arrValues["ug_item_title"] = $this->title;
			
			return($arrValues);
		}
		
		
		
		/**
		 * get item settings object
		 */
		public function getObjSettings(){
			$this->validateInited();
			$arrValues = $this->getArrValues();
						
			//get settingItem object		
			require GlobalsUG::$filepathItemSettings;
			
			$settingsItem->setStoredValues($arrValues);
			
			return($settingsItem);
		}
		
		
		/**
		 * get item alias
		 */
		public function getAlias(){
			$this->validateInited();
			$alias = UniteFunctionsUG::getVal($this->data, "alias");
			return($alias);
		}
		
		
		/**
		 * get item type
		 */
		public function getType(){
			$this->validateInited();
			return($this->type);
		}
		
		
		/**
		 * get item title
		 */
		public function getTitle(){
			$this->validateInited();
			return($this->title);
		}
		
		
		/**
		 * get alt text
		 */
		public function getAlt(){
			$this->validateInited();
			if(!empty($this->alt))
				return($this->alt);
			
			return($this->title);
		}
		
		/**
		 * 
		 * get url image
		 */
		public function getUrlImage($thumbSize = ""){
			
			if(empty($thumbSize) || $thumbSize == "full")
				return($this->urlImage);
			else
				return($this->getUrlThumb($thumbSize));
		}
		
		
		/**
		 * 
		 * get thumb url
		 */
		public function getUrlThumb($thumbSize = ""){
			
			$thumbSize = trim($thumbSize);
			if(empty($thumbSize))
				return($this->urlThumb);
			
			//get thumb url by image url
			if(empty($this->imageID)){
				
				if($thumbSize == "full")
					return($this->urlImage);
				
				if(method_exists("UniteProviderFunctionsUG", "getThumbWidth") == false)
					return($this->urlThumb);
				
				$thumbWidth = UniteProviderFunctionsUG::getThumbWidth($thumbSize);
				$operations = new UGOperations();
				
				try{
					
					$urlThumb = $operations->createThumbs($this->urlImage, $thumbWidth);
					$urlThumb = HelperUG::URLtoFull($urlThumb);
				
				}catch(Exception $error){
					if(!empty($this->urlThumb))
						return($this->urlThumb);
					
					throw new Error($error);
				}
				
				return($urlThumb);
			}else{		//with image id
				
				if(method_exists("UniteProviderFunctionsUG", "getThumbUrlFromImageID")){
					$urlThumb = UniteProviderFunctionsUG::getThumbUrlFromImageID($this->imageID, $thumbSize);
					if(!empty($urlThumb)){
						$urlThumb = HelperUG::URLtoFull($urlThumb);
						return($urlThumb);
					}
				}
			}
			
			
			return($this->urlThumb);
		}
		
		
		/**
		 * 
		 * get params
		 */
		public function getParams(){
			return($this->params);
		}
		
		
		/**
		 * get item data including params
		 */
		public function getData(){
			$this->validateInited();
			
			$data = array();
			
			//merge params
			$data = array_merge($data, $this->params);
			
			//fill with item data
			$data["id"] = $this->id;
			$data["title"] = $this->title;
			$data["type"] = $this->type;
			$data["url_image"] = $this->urlImage;
			$data["url_thumb"] = $this->urlThumb;
			$data["image_id"] = $this->imageID;
			$data["is_published"] = $this->isPublished;
			$data["alias"] = $this->getAlias();
			
			return($data);
		}
		
		/**
		 * get additional item insert data from media items
		 */
		private function getAddDataFromMedia($data, $arrInsert){
			
			$params = array();
			$contentID = "";
			$type = UniteFunctionsUG::getVal($data, "type");
			
			$content = UniteFunctionsUG::getVal($data, "description");
			
			switch($type){
				case self::TYPE_YOUTUBE:
				case self::TYPE_VIMEO:
				case self::TYPE_WISTIA:
					$contentID = UniteFunctionsUG::getVal($data, "videoid");
				break;
				case self::TYPE_HTML5VIDEO:
					$params["video_mp4"] = UniteFunctionsUG::getVal($data, "urlVideo_mp4");
					$params["video_webm"] = UniteFunctionsUG::getVal($data, "urlVideo_webm");
					$params["video_ogv"] = UniteFunctionsUG::getVal($data, "urlVideo_ogv");
				break;
				default:
					UniteFunctionsUG::throwError("Wrong media type: ".$type);
				break;
			}
			
			$arrInsert["contentid"] = trim($contentID);
			$arrInsert["content"] = trim($content);
			
			if(!empty($params))
				$arrInsert["params"] = json_encode($params);
			
			return($arrInsert);
		}
		
		
		/**
		 * 
		 * add item to database from data, init the item on the way by the record.
		 * return item id
		 */
		public function add($data){
						
			$catID = UniteFunctionsUG::getVal($data, "catID");

			$type = UniteFunctionsUG::getVal($data, "type");
						
			$params = "";
			
			$urlImage = UniteFunctionsUG::getVal($data, "urlImage");
			$urlImage = HelperUG::URLtoRelative($urlImage);
						
			$urlThumb = UniteFunctionsUG::getVal($data, "urlThumb");
			$urlThumb = HelperUG::URLtoRelative($urlThumb);

			
			//get max items order
			$items = new UniteGalleryItems();
			$maxOrder = $items->getMaxOrder($catID);
			
			$arrInsert = array();
			$arrInsert["type"] = $type;
			$arrInsert["published"] = 1;
			$arrInsert["ordering"] = $maxOrder+1;
			$arrInsert["catid"] = $catID;
			$arrInsert["url_image"] = $urlImage;
			$arrInsert["url_thumb"] = $urlThumb;
			
			switch($type){
				case self::TYPE_IMAGE:
					
					$urlImage = UniteFunctionsUG::getVal($data, "urlImage");
					$arrInsert["catid"] = $catID;
					
					//set params					
					$title = HelperUG::getTitleFromUrl($urlImage, $this->itemTitleBase);
					$arrInsert["imageid"] = UniteFunctionsUG::getVal($data, "imageID" , 0);
          
				          if(empty($arrInsert["imageid"]))
				            $arrInsert["imageid"] = 0;
        
				break;
				default:			//add media item
					$title = UniteFunctionsUG::getVal($data, "title");
					$arrInsert = $this->getAddDataFromMedia($data, $arrInsert);
				break;
				
			}
			
			UniteFunctionsUG::validateNotEmpty($title, "title");
			
			$arrInsert["title"] = $title;
  
			//insert the category
			$itemID = $this->db->insert(GlobalsUG::$table_items,$arrInsert);
			
			$arrInsert["id"] = $itemID;
			$this->initByDBRecord($arrInsert);
			
			return($itemID);
		}
		
		
		/**
		 * 
		 * get html for admin browsing
		 */
		public function getHtmlForAdmin(){
			$this->validateInited();
			
			$title =  $this->title;
			
			$title_small = UniteFunctionsUG::limitStringSize($title, 25);
			
			$title = htmlspecialchars($title);
			$title_small = htmlspecialchars($title_small);
			
			$itemID = $this->id;
			
			//set thumb
			$urlThumb = $this->urlThumb;
			
			$style = "";
			$imageText = "";
			if(!empty($urlThumb)){
				$style = "style=\"background-image:url('{$urlThumb}')\"";
			}				
			else{
				$imageText = __("No Image",UNITEGALLERY_TEXTDOMAIN);
			}			
			
			$urlImage = $this->urlImage;
			
			$type = $this->type;
			
			$addHtml = "";
			switch($this->type){
				case self::TYPE_VIMEO:
				case self::TYPE_YOUTUBE:
				case self::TYPE_WISTIA:
					$videoID = $this->getParam("videoid");
					$addHtml = "data-videoid=\"{$videoID}\"";
				break;
				case self::TYPE_HTML5VIDEO:
					$videoMp4 = $this->getParam("video_mp4");
					$videoWebm = $this->getParam("video_webm");
					$videoOgv = $this->getParam("video_ogv");
										
					$addHtml = "data-mp4=\"{$videoMp4}\" data-webm=\"{$videoWebm}\" data-ogv=\"{$videoOgv}\"";
					
				break;
			}

			
			//set html output			
			$htmlItem  = "<li id=\"item_{$itemID}\" class=\"item_type_{$type}\" data-id=\"{$itemID}\" data-title=\"{$title}\" data-image=\"{$urlImage}\" data-type=\"{$type}\" {$addHtml} >";
			$htmlItem .= "	<div class=\"item_title unselectable\" unselectable=\"on\">{$title_small}</div>";
			$htmlItem .= "	<div class=\"item_image unselectable\" unselectable=\"on\" {$style}>{$imageText}</div>";
			$htmlItem .= "	<div class=\"item_icon unselectable\" unselectable=\"on\"></div>";
			$htmlItem .= "</li>";
			
			return($htmlItem);
		}
		
		
		/**
		 * 
		 * update item data image in db
		 */
		private function updateItemData_image($data){
			
			$newParams = UniteFunctionsUG::getVal($data, "params");
			if(is_array($newParams) == false)
				$newParams = array();
			
			$updateParams = array_merge($this->params, $newParams);
			
			$title = UniteFunctionsUG::getVal($updateParams, "ug_item_title");
			UniteFunctionsUG::validateNotEmpty($title, "Item Title");
			
			$jsonUpdateParams = json_encode($updateParams);
						
			$arrUpdate = array();
			
			$arrUpdate["title"] = $title;
			$arrUpdate["params"] = $jsonUpdateParams;
						
			$this->data = array_merge($arrUpdate, $this->data);
			
			$this->db->update(GlobalsUG::$table_items,$arrUpdate,array("id"=>$this->id));
			
			$this->initByDBRecord($this->data);
		}
		
		
		/**
		 * update item data - media in db
		 */
		private function updateItemData_media($data){
			
			$title = UniteFunctionsUG::getVal($data, "title");
			UniteFunctionsUG::validateNotEmpty($title, "Item Title");
			
			$type = UniteFunctionsUG::getVal($data, "type");
			
			$urlImage = UniteFunctionsUG::getVal($data, "urlImage");
			$urlThumb = UniteFunctionsUG::getVal($data, "urlThumb");
	
			$arrUpdate = array();
		
			$arrUpdate["type"] = $type;
			$arrUpdate["url_image"] = HelperUG::URLtoRelative($urlImage);
			$arrUpdate["url_thumb"] = HelperUG::URLtoRelative($urlThumb);
			$arrUpdate["title"] = trim($title);
			
			$arrUpdate = $this->getAddDataFromMedia($data, $arrUpdate);
			
			$this->db->update(GlobalsUG::$table_items,$arrUpdate,array("id"=>$this->id));
			
			//init the item again from the new record
			$this->data = array_merge($this->data, $arrUpdate);
						
			$this->initByDBRecord($this->data);
		}
		
		
		/**
		 * update item data
		 */
		public function updateItemData($data){
			
			$this->validateInited();
						
			switch($this->type){
				case self::TYPE_IMAGE:
					$this->updateItemData_image($data);
				break;
				default:		//update media item
					$this->updateItemData_media($data);
				break;
			}
			
		}
		
	}

?>