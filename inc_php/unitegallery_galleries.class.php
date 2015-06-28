<?php
/**
 * @package Unite Gallery
 * @author UniteCMS.net / Valiano
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('_JEXEC') or die('Restricted access');


class UniteGalleryGalleries extends UniteElementsBaseUG{	
	
	private static $arrGalleryTypes;		/* one time init only*/
	
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * get order array of the galleries
	 * order file: order.xml in the galleries folder.
	 */
	private function getArrOrder(){
		$filepathOrder = GlobalsUG::$pathGalleries."order.xml";
		if(!file_exists($filepathOrder))
			return(array());
		
		$objOrder = simplexml_load_file($filepathOrder);
		
		if(empty($objOrder))
			return(array());
		
		if(!isset($objOrder->item))
			return(array());
		
		$arrOrder = array();
		foreach($objOrder->item as $item){
			$strItem = (string)$item;
			$arrOrder[] = $strItem;
		}
		
		return($arrOrder);		
	}
		
	
	/**
	 * 
	 * get galleries array from galleris folder with settings from config.xml from each gallery
	 */
	private function initArrGalleryTypes(){
		
		$arrOrder = $this->getArrOrder();
		
		$strErrorPrefix = __("Load galleries error",UNITEGALLERY_TEXTDOMAIN);
		$arrDirs = UniteFunctionsUG::getDirList(GlobalsUG::$pathGalleries);
		
		$arrGalleries = array();
		
		//reorder arrdirs:
		$arrDirsNew = array();
		$arrDirs = UniteFunctionsUG::arrayToAssoc($arrDirs);
				
		foreach($arrOrder as $dir){
			if(array_key_exists($dir, $arrDirs)){
				$arrDirsNew[] = $dir;
				unset($arrDirs[$dir]);
			}				
		}
		
		$arrDirsNew = array_merge($arrDirsNew, $arrDirs);
		
		foreach($arrDirsNew as $dir){
				
			$pathGallery = GlobalsUG::$pathGalleries.$dir."/";
			if(is_dir($pathGallery) == false)
				continue;
										
			$objGallery = new UniteGalleryGalleryType();
			$objGallery->initByFolder($dir);
			$galleryName = $objGallery->getName();
			$arrGalleries[$galleryName] = $objGallery;
		}
		
		self::$arrGalleryTypes = $arrGalleries;
	}
	
	
	/**
	 * 
	 * get galleries array
	 */
	public function getArrGalleryTypes(){
		if(empty(self::$arrGalleryTypes))
			$this->initArrGalleryTypes();
			
		return(self::$arrGalleryTypes);
	}
	
	
	/**
	 * 
	 * get galleries as simple array
	 * get published only galleries
	 */
	public function getArrGalleryTypesShort(){
		$arrGalleries = $this->getArrGalleryTypes();
		
		$arrShort = array();
		foreach($arrGalleries as $objGallery){
			$isPublishded = $objGallery->isPublished();
			if($isPublishded == false)
				continue;
			
			$name = $objGallery->getName();
			$arr = array();
			$arr["name"] = $name;
			$arr["title"] = $objGallery->getTypeTitle();
			$arr["folder"] = $objGallery->getFolder();
			$arrShort[$name] = $arr;
		}
		
		return($arrShort);
	}
	
	
	
	/**
	 * 
	 * get gallery by name
	 */
	public function getGalleryTypeByName($galleryName){
		UniteFunctionsUG::validateNotEmpty($galleryName, "gallery name");
		$arrGalleryTypes = $this->getArrGalleryTypes();
		
		$objGalleryType = UniteFunctionsUG::getVal($arrGalleryTypes, $galleryName);
		if(empty($objGalleryType))
			UniteFunctionsUG::throwError("getGalleryTypeByName error, Gallery {$galleryName} not found!");
		
		return($objGalleryType);
	}
	
	/**
	 *
	 * get galleries array
	 */
	public function getArrGalleries($order = ""){
		
		$arrGalleries = array();
		$response = $this->db->fetch(GlobalsUG::$table_galleries, "", $order);
	
		foreach($response as $record){
			$objGallery = new UniteGalleryGallery();
			$objGallery->initByRecord($record);
			$arrGalleries[] = $objGallery;
		}
	
		return($arrGalleries);
	}
	
	/**
	 * get id - title array of the galleries
	 */
	public function getArrGalleriesShort($addEmpty = false){
		
		$arrGalleries = array();
		$response = $this->db->fetch(GlobalsUG::$table_galleries);
		
		if($addEmpty == true)
			$arrGalleries["empty"] = __("[Not Selected]", UNITEGALLERY_TEXTDOMAIN);
		
		foreach($response as $record){
			$id = UniteFunctionsUG::getVal($record, "id");
			$title = UniteFunctionsUG::getVal($record, "title");
						
			$arrGalleries[$id] = $title;
		}		
		
		return($arrGalleries);
	}
	
	
	/**
	 * 
	 * add gallery from data by gallery name
	 */
	public function addGaleryFromData($type, $data){
		
		$objGallery = new UniteGalleryGallery();
		$params = UniteFunctionsUG::getVal($data, "params",array());
		if(is_array($params) == false)
			$params = array();
		
		$mainParams =  UniteFunctionsUG::getVal($data, "main");
				
		if(!empty($mainParams))
			$params = array_merge($mainParams, $params);
		
		//create items category if needed
		$category = UniteFunctionsUG::getVal($params, "category");
		
		if($category == "new"){
			$objGallery->validateInputSettings($params, false);
			$title = $params["title"];
			$objCategories = new UniteGalleryCategories();
			$response = $objCategories->add($title);
			$newCategoryID = $response["id"];
			$params["category"] = $newCategoryID;
			$params["categories"] = $newCategoryID;
		}
		

		$galleryID = $objGallery->create($type, $params);
		
		return($galleryID);	
	}
	
	
	/**
	 * update gallery from data
	 */
	public function updateGalleryFromData($data){
		
		$galleryID = UniteFunctionsUG::getVal($data, "galleryID");
		UniteFunctionsUG::validateNotEmpty($galleryID,"Gallery ID");
		$updateParamsOnly = UniteFunctionsUG::getVal($data, "updateParamsOnly");
				
		$objGallery = new UniteGalleryGallery();
		$objGallery->initByID($galleryID);
		
		$params = UniteFunctionsUG::getVal($data, "params",array());
		$mainParams =  UniteFunctionsUG::getVal($data, "main");
				
		if(!empty($mainParams))
			$params = array_merge($mainParams, $params);
		
		
		if($updateParamsOnly == true)
			$objGallery->updateParams($params);
		else
			$objGallery->update($params);		//update with title and alias
		
	}

	/**
	 * get gallery by id
	 */
	private function getGalleryByID($galleryID){
		
		UniteFunctionsUG::validateNotEmpty($galleryID,"gallery id");
		UniteFunctionsUG::validateNumeric($galleryID);
		$gallery = new UniteGalleryGallery();
		$gallery->initByID($galleryID);
		
		return($gallery);
	}
	
	/**
	 * 
	 * delete gallery from data
	 */
	public function deleteGalleryFromData($data){
		
		$galleryID = UniteFunctionsUG::getVal($data, "galleryID");
		UniteFunctionsUG::validateNotEmpty($galleryID,"gallery id");
		
		$gallery = new UniteGalleryGallery();
		$gallery->initByID($galleryID);
		$gallery->delete();
		
	}
	
	/**
	 * 
	 * duplicate gallery from data
	 */
	public function duplicateGalleryFromData($data){
				
		$galleryID = UniteFunctionsUG::getVal($data, "galleryID");
		UniteFunctionsUG::validateNotEmpty($galleryID,"gallery id");
		
		$gallery = new UniteGalleryGallery();
		$gallery->initByID($galleryID);
		
		$gallery->duplicate();
	}
	
	/**
	 * export gallery settings from data
	 */
	public function exportGallerySettings($galleryID){
		
		$gallery = $this->getGalleryByID($galleryID);
		$gallery->exportSettings();
	}
	
	
	/**
	 * import gallery settings from upload file
	 */
	public function importGallerySettingsFromUploadFile($galleryID){
		
		$gallery = $this->getGalleryByID($galleryID);
		
		$arrFile = UniteFunctionsUG::getVal($_FILES, "export_file");
		try{
		
			$linkBack = HelperUG::getAdvancedView($galleryID);
			$htmlLinkBack = UniteFunctionsUG::getHtmlLink($linkBack, "Go Back");
			
			if(empty($arrFile))
				UniteFunctionsUG::throwError("Import file not found");
			
			//get content
			$filepath = UniteFunctionsUG::getVal($arrFile, "tmp_name");
			$content = file_get_contents($filepath);
			
			//remove temp path
			@unlink($filepath);
			
			if(empty($content))
				UniteFunctionsUG::throwError("No content found");
			
			$arrContent = @unserialize($content);
			
			if(empty($arrContent))
				UniteFunctionsUG::throwError("No content format");
			
			$gallery->importSettings($arrContent);
			
			//redirect back to settings
			dmp("gallery settings imported, redirecting...");
			UniteFunctionsUG::putRedirectJS($linkBack);
			exit();
			
		}catch(Exception $e){
			$message = $e->getMessage();
			echo "<div style='color:#B80A0A;font-size:18px;'><b>Import Settings Error: </b> $message</div><br>";
			echo $htmlLinkBack;
			exit();
		}
		
	}
	
}

?>
