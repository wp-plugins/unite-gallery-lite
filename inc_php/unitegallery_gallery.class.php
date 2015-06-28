<?php
/**
 * @package Unite Gallery
 * @author UniteCMS.net / Valiano
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('_JEXEC') or die('Restricted access');


class UniteGalleryGallery extends UniteElementsBaseUG{	
	
	private $id;
	private $objType;
	private $type;
	private $typeTitle;
	private $title;
	private $alias;
	private $ordering;
	private $arrParams;
	private $isTypeExists;
	
	/**
	 * 
	 * construct
	 */
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * validate that the gallery inited
	 */
	private function validateInited(){
		if(empty($this->id))
			UniteFunctionsUG::throwError("The gallery not inited");
	}

	
	/**
	 * 
	 * init gallery by id
	 */
	public function initByID($id){
		
		UniteFunctionsUG::validateNumeric($id, "galleryID");
		
		$id = $this->db->escape($id);
				
		$response = $this->db->fetch(GlobalsUG::$table_galleries,"id={$id}");
		
		if(empty($response))
			UniteFunctionsUG::throwError("Gallery with id: {$id} not found");
		
		$record = $response[0];
		$this->initByRecord($record);
	}
	
	
	/**
	 * init gallery by alias
	 */
	public function initByAlias($alias){
		
		$alias = $this->db->escape($alias);
		
		$response = $this->db->fetch(GlobalsUG::$table_galleries,"alias='{$alias}'");
		if(empty($response))
			UniteFunctionsUG::throwError("Gallery with alias: {$alias} not found");
		
		$record = $response[0];
		$this->initByRecord($record);
	}
	
	
	/**
	 * 
	 * init gallery by record
	 */
	public function initByRecord($record){
		
		UniteFunctionsUG::validateNotEmpty($record,"record empty");
		
		$this->id = UniteFunctionsUG::getVal($record, "id");
		$this->title = UniteFunctionsUG::getVal($record, "title");
		$this->alias = UniteFunctionsUG::getVal($record, "alias");
		$this->ordering = UniteFunctionsUG::getVal($record, "ordering");
		$this->type = UniteFunctionsUG::getVal($record, "type");
		
		//set type title:
		if(!empty($this->type)){
			try{
				UniteFunctionsUG::validateNotEmpty($this->type,"Gallery Type");
				
				$objGalleries = new UniteGalleryGalleries();
				$this->objType = $objGalleries->getGalleryTypeByName($this->type);
				$this->typeTitle = $this->objType->getTypeTitle();
				$this->isTypeExists = true;
			}catch(Exception $e){
				$this->isTypeExists = false;
				$this->typeTitle = $this->type." - gallery type not exists!";
			}		
		}
		 
		
		$this->arrParams = array();
		$params = UniteFunctionsUG::getVal($record, "params");
		if(!empty($params))
			$this->arrParams = (array)json_decode($params);
		
	}
	
	
	
	/**
	 * get the ID
	 */
	public function getID(){
		$this->validateInited();
		return($this->id);
	}
	
	/**
	 * get type title
	 */
	public function getTypeTitle(){
		return($this->typeTitle);
	}
	
	/**
	 * get type name
	 */
	public function getTypeName(){
		return($this->type);
	}
	
	
	/**
	 * get true if that gallery type exists
	 */
	public function isTypeExists(){
		
		return($this->isTypeExists);
	}
	
	
	/**
	 * get gallery type object
	 */
	public function getObjType(){
		return($this->objType);
	}
	
	
	/**
	 * get combination of title (alias)
	 */
	public function getShowTitle(){
		$showTitle = $this->title." ($this->alias)";
		return($showTitle);
	}
	
	/**
	 * get alias
	 */
	public function getAlias(){
		return($this->alias);
	}
	
	/**
	 * get title
	 */
	public function getTitle(){
		return($this->title);
	}
	
	
	/**
	 *
	 * get slider shortcode
	 */
	public function getShortcode(){
		$shortCode = "[unitegallery {$this->alias}]";
		return($shortCode);
	}
	
	/**
	 * get params
	 */
	public function getParams(){
		return($this->arrParams);
	}
	
	
	/**
	 * get some param from params
	 */
	public function getParam($name){
		$this->validateInited();
		$value = UniteFunctionsUG::getVal($this->arrParams, $name);
		return($value);
	}
	
	
	/**
	 * get params for settings object
	 */
	public function getParamsForSettings(){
		$arrParams = $this->arrParams;
		$arrParams["title"] = $this->title;
		$arrParams["alias"] = $this->alias;
		
		return($arrParams);	
	}
	
	/**
	 * 
	 * get max order
	 */
	private function getMaxOrder(){
		
		$maxOrder = 0;
		$arrGaleryRecords = $this->db->fetch(GlobalsUG::$table_galleries,"","ordering desc","","limit 1");
		if(empty($arrGaleryRecords))
			return($maxOrder);
		$maxOrder = $arrGaleryRecords[0]["ordering"];
		
		return($maxOrder);
	}
	
	/**
	 * 
	 * check if alias exists in DB
	 */
	private function isAliasExistsInDB($alias){
		$alias = $this->db->escape($alias);
		
		$where = "alias='$alias'";
		if(!empty($this->id))
			$where .= " and id != '{$this->id}'";
			
		$response = $this->db->fetch(GlobalsUG::$table_galleries,$where);
		return(!empty($response));
		
	}
	
	/**
	 * 
	 * validate settings for add
	 */
	public function validateInputSettings($params, $checkAlias = true){
		
		$title = UniteFunctionsUG::getVal($params, "title");
		$alias = UniteFunctionsUG::getVal($params, "alias");
		
		UniteFunctionsUG::validateNotEmpty($title,"title");
		UniteFunctionsUG::validateNotEmpty($alias,"alias");
		
		$isMatch = preg_match("/^[A-Za-z0-9_]+$/", $alias);
		if(!$isMatch)
			UniteFunctionsUG::throwError("<b>Wrong Alias</b>! The alias should be contain from english letters and numbers without spaces, underscore alowed example: my_gallery1");
		
		if($checkAlias == true && $this->isAliasExistsInDB($alias))
			UniteFunctionsUG::throwError(__("Some other gallery with alias <b>'$alias'</b> already exists. Please write another alias",UNITEGALLERY_TEXTDOMAIN));			
	}
	
	/**
	 * 
	 * add gallery from params.
	 */
	public function create($type, $params){
		
		$title = UniteFunctionsUG::getVal($params, "title");
		$alias = UniteFunctionsUG::getVal($params, "alias");
		
		$this->validateInputSettings($params);
		
		$jsonParams = json_encode($params);
		
		$maxOrder = $this->getMaxOrder();
		$currentOrder = $maxOrder+1;
		
		//insert slider to database
		$arrData = array();
		$arrData["title"] = $title;
		$arrData["type"] = $type;
		$arrData["alias"] = $alias;
		$arrData["params"] = $jsonParams;
		$arrData["ordering"] = $currentOrder;
		
		$galleryID = $this->db->insert(GlobalsUG::$table_galleries,$arrData);
		return($galleryID);
	}	
	
	
	/**
	 * 
	 * update the gallery by params
	 */
	public function update($params){
		$this->validateInited();
		
		$title = UniteFunctionsUG::getVal($params, "title");
		$alias = UniteFunctionsUG::getVal($params, "alias");
		
		$this->validateInputSettings($params);
				
		$arrData = array();
		$arrData["title"] = $title;
		$arrData["alias"] = $alias;
		
		$this->db->update(GlobalsUG::$table_galleries,$arrData,array("id"=>$this->id));
		
		$this->updateParams($params);
	}
	
		
	
	/**
	 * udate gallery params, merge with existing
	 * no title and alias should exist in the given params
	 */
	public function updateParams($arrParams){
		
		$this->validateInited();
		$this->arrParams = array_merge($this->arrParams, $arrParams);
		
		$jsonParams = json_encode($this->arrParams);
		$arrData = array();
		$arrData["params"] = $jsonParams;
		
		$this->db->update(GlobalsUG::$table_galleries, $arrData, array("id"=>$this->id));
	}
	
	/**
	 * update some param name in database
	 */
	public function updateParam($name, $value){
		$arrParams = array();
		$arrParams[$name] = $value;
		$this->updateParams($arrParams);
	}
	
	
	/**
	 * delete the gallery
	 */
	public function delete(){
		$this->validateInited();
		$this->db->delete(GlobalsUG::$table_galleries,"id={$this->id}");
	}
	
	
	/**
	 * 
	 * duplicate gallery
	 */
	public function duplicate(){
		$this->validateInited();
		
		//get slider number:
		$response = $this->db->fetch(GlobalsUG::$table_galleries);
		$numGalleries = count($response);
		$newGallerySerial = $numGalleries + 1;

		$newTitle = __("Gallery",UNITEGALLERY_TEXTDOMAIN).$newGallerySerial;
		$newAlias = "gallery".$newGallerySerial;
		
		//insert a new gallery
		$sqlSelect = "select ".GlobalsUG::FIELDS_GALLERY." from ".GlobalsUG::$table_galleries." where id={$this->id}";
		$sqlInsert = "insert into ".GlobalsUG::$table_galleries." (".GlobalsUG::FIELDS_GALLERY.") ($sqlSelect)";
		
		$this->db->runSql($sqlInsert);
		$lastID = $this->db->getLastInsertID();
		UniteFunctionsUG::validateNotEmpty($lastID);
		
		$arrParams = $this->arrParams;
		$arrParams["title"] = $newTitle;
		$arrParams["alias"] = $newAlias;
		
		$jsonParams = json_encode($arrParams);
		
		//update the new slider with the title and the alias values
		$arrUpdate = array();
		$arrUpdate["title"] = $newTitle;
		$arrUpdate["alias"] = $newAlias;
		$arrUpdate["params"] = $jsonParams;
		
		$this->db->update(GlobalsUG::$table_galleries, $arrUpdate, array("id"=>$lastID));
	}
	
	/**
	 * update items category from data
	 * the gallery is not inited
	 */
	public function updateItemsCategoryFromData($data){
		
		$galleryID = UniteFunctionsUG::getVal($data, "galleryID");
		if(empty($galleryID))
			return(false);
		
		$this->initByID($galleryID);
		
		$catID = UniteFunctionsUG::getVal($data, "catID");
		UniteFunctionsUG::validateNumeric($catID,"category id");

		$this->updateParam("category", $catID);
		
	}
	
	
	/**
	 * export gallery settings to downloadable file
	 */
	public function exportSettings(){
		$this->validateInited();
		$galleryID = $this->id;
		
		$record = $this->db->fetchSingle(GlobalsUG::$table_galleries,"id={$galleryID}");
		UniteFunctionsUG::validateNotEmpty($record, "Gallery Record");
		unset($record["id"]);
		
		$strRecord = serialize($record);
		$filename = "unitegallery_".$record["alias"].".txt";
		
		UniteFunctionsUG::downloadFileFromContent($strRecord, $filename);
		
		//the download content should exit
		UniteFunctionsUG::throwError("Something wrong witht the export, please try again");
		exit();
	}
	
	
	/**
	 * import settings
	 */
	public function importSettings($arrContent){
		
		$this->validateInited();
		
		$jsonParams = UniteFunctionsUG::getVal($arrContent, "params");
		$paramsNew = (array)json_decode($jsonParams);
		
		if(empty($paramsNew))
			return(false);
		
		//unset variables
		$arrUnset = array(
				"title",
				"alias",
				"category",
				"gallery_width",
				"gallery_height",
				"full_width",
				"enable_categories",
				"shortcode",
				"gallery_min_width",
				"include_jquery",
				"js_to_body",
				"compress_output",
				"gallery_debug_errors",
				"categories",
				"enable_category_tabs",
				"gallery_min_width",
				"gallery_min_height"
		);
		
		foreach($arrUnset as $key){
			if(array_key_exists($key, $paramsNew))
				unset($paramsNew[$key]);
		}
		
		$this->updateParams($paramsNew);
	}
	
	
	
}

?>