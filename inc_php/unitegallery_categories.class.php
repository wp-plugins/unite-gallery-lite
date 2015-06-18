<?php
/**
 * @package Unite Gallery
 * @author UniteCMS.net / Valiano
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('_JEXEC') or die('Restricted access');


class UniteGalleryCategories extends UniteElementsBaseUG{
	
	private $titleBase = "New Category";
	
	public function __construct(){
		parent::__construct();
		
	}
	
	
	/**
	 * 
	 * validate that category exists
	 */
	public function validateCatExist($catID){
		$this->getCat($catID);
	}
	
	/**
	 * 
	 * add category
	 */
	public function add($titleBase = null){
		
		$maxOrder = $this->getMaxOrder();
		if(empty($titleBase))
			$titleBase = $this->titleBase;
			
		//set title
		$arrTitles = $this->getArrCatTitlesAssoc();
		$title = $titleBase;
		$titleExists = array_key_exists($title, $arrTitles);
		if($titleExists == true){
			$counter = 1;
			do{
				$counter++;
				$title = $titleBase." ".$counter;
				$titleExists = array_key_exists($title, $arrTitles);				
			}while($titleExists == true);
		}
		
    
		//prepare insert array
		$arrInsert = array();
		$arrInsert["title"] = $title;
		$arrInsert["ordering"] = $maxOrder+1;
    $arrInsert["params"] = '';
		
		//insert the category
		$catID = $this->db->insert(GlobalsUG::$table_categories,$arrInsert);
				
		//prepare output
		$returnData = array("id"=>$catID,"title"=>$title);
		return($returnData);		
	}
	
	
	/**
	 * 
	 * get categories list
	 */
	public function getList(){
		$tableCats = GlobalsUG::$table_categories;
		$tableItems = GlobalsUG::$table_items;
		
		$query = "select cats.*, count(items.id) as num_items from {$tableCats} as cats";
		$query .= " left join $tableItems as items on items.catid=cats.id GROUP BY cats.id order by cats.ordering";
		
		$arrCats = $this->db->fetchSql($query);
		
		return($arrCats);
	}
	
	
	/**
	 * get category list by id's string
	 */
	public function getListByIds( $ids ) {
		
		$ids = $this->db->escape($ids);
		
		$tableCats = GlobalsUG::$table_categories;
		$query = "select cats.* from {$tableCats} as cats WHERE cats.id IN(" . $ids . ")";
		$arrCats = $this->db->fetchSql($query);
		
		$arrCats = UniteFunctionsUG::arrayToAssoc($arrCats, "id");
		
		//order by IDs
		$arrIDs = explode(",", $ids);
		$arrCatsFinal = array();
		foreach($arrIDs as $id){
			if(array_key_exists($id, $arrCats))
				$arrCatsFinal[] = $arrCats[$id];
		}
		
		
		return($arrCatsFinal);
	}
	
	
	/**
	 * 
	 * get category records simple without num items
	 */
	public function getCatRecords(){
		$arrCats = $this->db->fetch(GlobalsUG::$table_categories,"","ordering");
		return($arrCats);
	}
	
	/**
	 * 
	 * get categories list short
	 * addtype: empty (empty category), new (craete new category)
	 */
	public function getCatsShort($addType = ""){
		
		$arrCats = $this->getCatRecords();
		$arrCatsOutput = array();
		
		switch($addType){
			case "empty":
				$arrCatsOutput[""] = __("[Not Selected]", UNITEGALLERY_TEXTDOMAIN);
			break;
			case "new":
				$arrCatsOutput["new"] = __("[Add New Category]", UNITEGALLERY_TEXTDOMAIN);
			break;
			case "component":
				$arrCatsOutput[""] = __("[From Gallery Settings]", UNITEGALLERY_TEXTDOMAIN);
			break;
		}
		
		foreach($arrCats as $cat){
			$catID = UniteFunctionsUG::getVal($cat, "id");
			$title = UniteFunctionsUG::getVal($cat, "title");
			$arrCatsOutput[$catID] = $title;
		}
		
		return($arrCatsOutput);
	}
	
	
	/**
	 * 
	 * get assoc value of category name
	 */
	private function getArrCatTitlesAssoc(){
		$arrCats = $this->getList();
		$arrAssoc = array();
		foreach($arrCats as $cat){
			$arrAssoc[$cat["title"]] = true;
		}
		return($arrAssoc);
	}
	
	
	/**
	 * 
	 * get max order from categories list
	 */
	private function getMaxOrder(){
		
		$query = "select MAX(ordering) as maxorder from ".GlobalsUG::$table_categories;
		
		///$query = "select * from ".self::TABLE_CATEGORIES;
		$rows = $this->db->fetchSql($query);
				
		$maxOrder = 0;
		if(count($rows)>0) $maxOrder = $rows[0]["maxorder"];
		
		if(!is_numeric($maxOrder))
			$maxOrder = 0;
		
		return($maxOrder);
	}
	
	
	/**
	 * get true/false if some category exists
	 */
	public function isCatExists($catID){
		
		UniteFunctionsUG::validateNumeric($catID, "category id");
		
		$arrCat = $this->db->fetchSingle(GlobalsUG::$table_categories,"id=$catID");
		return !empty($arrCat);		
	}
	
	
	/**
	 * 
	 * get category
	 */
	public function getCat($catID){
		
		UniteFunctionsUG::validateNumeric($catID, "category id");
		
		$catID = (int)$catID;
		
		$arrCat = $this->db->fetchSingle(GlobalsUG::$table_categories,"id=$catID");
		if(empty($arrCat))
			UniteFunctionsUG::throwError("Category with id: $catID not found");
			
		return($arrCat);
	}
	
	
	/**
	 * 
	 * remove the category.
	 */
	private function remove($catID){
		$catID = (int)$catID;
		
		//remove category
		$this->db->delete(GlobalsUG::$table_categories,"id=".$catID);
		
		//remove items
		$this->db->delete(GlobalsUG::$table_items,"catid=".$catID);
	}
	
	
	/**
	 * 
	 * update category
	 */
	private function update($catID,$title){
		$catID = (int)$catID;
		$title = $this->db->escape($title);
		$arrUpdate = array();
		$arrUpdate["title"] = $title;
		$this->db->update(GlobalsUG::$table_categories,$arrUpdate,array("id"=>$catID));
	}
	
	/**
	 * 
	 * update categories order
	 */
	private function updateOrder($arrCatIDs){
		
		foreach($arrCatIDs as $index=>$catID){
			$order = $index+1;
			$arrUpdate = array("ordering"=>$order);
			$where = array("id"=>$catID);
			$this->db->update(GlobalsUG::$table_categories,$arrUpdate,$where);
		}
	}
	
	
	/**
	 * 
	 * remove category from data
	 */
	public function removeFromData($data){
		$catID = UniteFunctionsUG::getVal($data, "catID");
						
		$this->remove($catID);
		
		$response = array();
		$response["htmlSelectCats"] = $this->getHtmlSelectCats();
		
		return($response);
	}
	
	
	/**
	 * 
	 * update category from data
	 */
	public function updateFromData($data){
		$catID = UniteFunctionsUG::getVal($data, "catID");
		$title = UniteFunctionsUG::getVal($data, "title");
		
		$this->update($catID, $title);
	}
	
	
	
	/**
	 * 
	 * update order from data
	 */
	public function updateOrderFromData($data){
		$arrCatIDs = UniteFunctionsUG::getVal($data, "cat_order");
		if(is_array($arrCatIDs) == false)
			UniteFunctionsUG::throwError("Wrong categories array");
			
		$this->updateOrder($arrCatIDs);
	}
	
	
	/**
	 * 
	 * add catgory from data, return cat select html list
	 */
	public function addFromData(){
		
		$response = $this->add();
		
		$arrCat = array("id"=>$response["id"],"title"=>$response["title"],"num_items"=>0);
		$html = $this->getCatHTML($arrCat);
		
		$response["htmlSelectCats"] = $this->getHtmlSelectCats();
		$response["htmlCat"] = $html;
		
		return($response);
		
	}
	
	/**
	 * 
	 * get html of category
	 */
	private function getCatHTML($cat, $class = ""){
		
		$id = $cat["id"];
		$title = $cat["title"];							
		$numItems = $cat["num_items"];
		
		$title = $cat["title"];							
		$numItems = $cat["num_items"];
		
		$showTitle = $title;
		
		if(!empty($numItems))
			$showTitle .= " ($numItems)";
		
		$html = "";
		$html .= "<li id=\"category_{$id}\" {$class} data-id=\"{$id}\" data-numitems=\"{$numItems}\" data-title=\"{$title}\">\n";
		$html .= "	<span class=\"cat_title\">{$showTitle}</span>\n";
		$html .= "</li>\n";
		
		return($html);
	}
	
	
	/**
	 * get list of categories 
	 */
	public function getHtmlCatList($selecteCatID = false){
		
		$arrCats = $this->getList();
		
		$html = "";
			
		foreach($arrCats as $index => $cat):
			$id = $cat["id"];
			
			$class = "";
			if($index == 0)			
				$class = "first-item";
			
			if(!empty($selecteCatID) && $id == $selecteCatID){
				if(!empty($class))
				$class .= " ";
				$class .= "selected-item";
			}
			
			if(!empty($class))
				$class = "class=\"{$class}\"";
			
			$html .= $this->getCatHTML($cat, $class);
		endforeach;
						
		return($html);
	}
	
	
	/**
	 * 
	 * get items for select categories
	 */
	public function getHtmlSelectCats(){
		
		$arrCats = $this->getList();
		
		$html = "";
		foreach($arrCats as $cat):
			$catID = $cat["id"];
			$title = $cat["title"];
			$html .= "<option value=\"{$catID}\">{$title}</option>";
		endforeach;
		
		return($html);
	}
	
	
}

?>