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
 * unite settings class.
 * @version 1.1
 *
 */

	class UniteSettingsUG{
		
		const COLOR_OUTPUT_FLASH = "flash";
		const COLOR_OUTPUT_HTML = "html";
		
		//------------------------------------------------------------
		
		const RELATED_NONE = "";
		const TYPE_TEXT = "text";
		const TYPE_COLOR = "color";
		const TYPE_DATE = "date";
		const TYPE_SELECT = "list";
		const TYPE_CHECKBOX = "checkbox";
		const TYPE_RADIO = "radio";
		const TYPE_TEXTAREA = "textarea";
		const TYPE_STATIC_TEXT = "statictext";
		const TYPE_HR = "hr";
		const TYPE_CUSTOM = "custom";
		const ID_PREFIX = "";
		const TYPE_CONTROL = "control";
		const TYPE_BUTTON = "button";
		const TYPE_MULTIPLE_TEXT = "multitext";
		const TYPE_IMAGE = "image";
		const TYPE_CHECKLIST = "checklist";
		const TYPE_BOOLEAN = "boolean";
		const TYPE_HIDDEN   = "hidden";
		
		//------------------------------------------------------------
		//set data types  
		const DATATYPE_NUMBER = "number";
		const DATATYPE_NUMBEROREMTY = "number_empty";
		const DATATYPE_STRING = "string";
		const DATATYPE_FREE = "free";
		
		const CONTROL_TYPE_ENABLE = "enable";
		const CONTROL_TYPE_DISABLE = "disable";
		const CONTROL_TYPE_SHOW = "show";
		const CONTROL_TYPE_HIDE = "hide";
		
		//additional parameters that can be added to settings.
		const PARAM_TEXTSTYLE = "textStyle";		
		const PARAM_ADDPARAMS = "addparams";	//additional text after the field
		const PARAM_ADDTEXT = "addtext";	//additional text after the field
		const PARAM_ADDTEXT_BEFORE_ELEMENT = "addtext_before_element";	//additional text after the field
		const PARAM_CELLSTYLE = "cellStyle";	//additional text after the field
		const PARAM_NODRAW = "nodraw";			//don't draw the setting row
		const PARAM_ADDFIELD = "addfield";		//add field to draw 
		const PARAM_ADD_SETTING_AFTER = "add_setting_after";	//add setting after another existing setting, and not to the end
		
		//view defaults:
		protected $defaultText = "Enter value";
		protected $sap_size = 5;
		
		//other variables:
		protected $HRIdCounter = 0;	//counter of hr id
		
		protected $arrSettings = array();
		protected $arrSections = array();
		protected $arrIndex = array();	//index of name->index of the settings.
		protected $arrSaps = array();
		
		//controls:
		protected $arrControls = array();		//array of items that controlling others (hide/show or enabled/disabled) 
		protected $arrBulkControl = array();	//bulk cotnrol array. if not empty, every settings will be connected with control.
		 
		//custom functions:
		protected $customFunction_afterSections = null;
		protected $colorOutputType = self::COLOR_OUTPUT_HTML;
		
		
	    /**
	     * constructor
	     */
	    public function __construct(){
	    	
	    }
		
		//-----------------------------------------------------------------------------------------------
		// get where query according relatedTo and relatedID. 
		private function getWhereQuery(){
			$where = "relatedTo='".$this->relatedTo."' and relatedID='".$this->relatedID."'";
			return($where);
		}
		
		
		//-----------------------------------------------------------------------------------------------
		//set type of color output
		public function setColorOutputType($type){
			$this->colorOutputType = $type;
		}
		
		//-----------------------------------------------------------------------------------------------
		//set the related to/id for saving/restoring settings.
		public function setRelated($relatedTo,$relatedID){
			$this->relatedTo = $relatedTo;
			$this->relatedID = $relatedID;
		}
		
		
		//-----------------------------------------------------------------------------------------------
		//modify the data before save
		private function modifySettingsData($arrSettings){
			
			foreach($arrSettings as $key=>$content){
				switch(getType($content)){
					case "string":
						//replace the unicode line break (sometimes left after json)
						$content = str_replace("u000a","\n",$content);
						$content = str_replace("u000d","",$content);						
					break;
					case "object":
					case "array":
						$content = UniteFunctionsUG::convertStdClassToArray($content);
					break;					
				}
				
				$arrSettings[$key] = $content;												
			}
			
			return($arrSettings);
		}				
				
		//-----------------------------------------------------------------------------------------------
		// add the section value to the setting
		private function checkAndAddSectionAndSap($setting){
			//add section
			if(!empty($this->arrSections)){
				$sectionKey = count($this->arrSections)-1;
				$setting["section"] = $sectionKey;
				$section = $this->arrSections[$sectionKey];
				$sapKey = count($section["arrSaps"])-1;
				$setting["sap"] = $sapKey;
			}
			else{
				//please impliment add sap normal!!! - without sections
			}
			return($setting);
		}
		
		
		/**
		 * 
		 * validate items parameter. throw exception on error
		 * @throws Exception
		 */
		private function validateParamItems($arrParams){
			if(!isset($arrParams["items"])) throw new Exception("no select items presented");
			if(!is_array($arrParams["items"])) throw new Exception("the items parameter should be array");
			//if(empty($arrParams["items"])) throw new Exception("the items array should not be empty");			
		}
		

		/**
		 * add this setting to index
		 * @param $name
		 */
		private function addSettingToIndex($name){
			$this->arrIndex[$name] = count($this->arrSettings)-1;
		}
		
		/**
		 * regenerate index array from the existing settings
		 */
		private function regenerateIndex(){
			
			$this->arrIndex = array();
			foreach($this->arrSettings as $index=>$setting){
				$name = UniteFunctionsUG::getVal($setting, "name");
				if(empty($name))
					continue;
				$this->arrIndex[$name] = $index;
			}
		}
		
		private function ________________GETTERS________________(){}
		
		//-----------------------------------------------------------------------------------------------
		//get types array from all the settings:
		protected function getArrTypes(){
			$arrTypesAssoc = array();
			$arrTypes = array();
			foreach($this->arrSettings as $setting){	
				$type = $setting["type"];
				if(!isset($arrTypesAssoc[$type])) $arrTypes[] = $type;
				$arrTypesAssoc[$type] = "";				
			}			
			return($arrTypes);
		}
						
		
		/**
		 * 
		 * get settings array
		 */
		public function getArrSettings(){
			return($this->arrSettings);
		}
		
		
		/**
		 * 
		 * get the keys of the settings
		 */
		public function getArrSettingNames(){
			$arrKeys = array();
			$arrNames = array();
			foreach($this->arrSettings as $setting){
				$name = UniteFunctionsUG::getVal($setting, "name");
				if(!empty($name))
					$arrNames[] = $name;
			}
			
			return($arrNames);
		}

		/**
		 * 
		 * get the keys of the settings
		 */
		public function getArrSettingNamesAndTitles(){
			$arrKeys = array();
			$arrNames = array();
			foreach($this->arrSettings as $setting){
				$name = UniteFunctionsUG::getVal($setting, "name");
				$title = UniteFunctionsUG::getVal($setting, "text");
				if(!empty($name))
					$arrNames[$name] = $title;
			}
			
			return($arrNames);
		}
		
		
		/**
		 * 
		 * get sections
		 */
		public function getArrSections(){
			return($this->arrSections);
		}
		
		
		/**
		 * 
		 * get controls
		 */
		public function getArrControls(){
			return($this->arrControls);
		}

		
		/**
		 * 
		 * set settings array
		 */
		public function setArrSettings($arrSettings){
			$this->arrSettings = $arrSettings;
		}
		
		
		//-----------------------------------------------------------------------------------------------
		//get number of settings
		public function getNumSettings(){
			$counter = 0;
			foreach($this->arrSettings as $setting){
				switch($setting["type"]){
					case self::TYPE_HR:
					case self::TYPE_STATIC_TEXT:
					break;
					default:
						$counter++;
					break;
				}
			}
			return($counter);
		}
		
		private function ______________________ADD________________(){}
		
		//private function 
		//-----------------------------------------------------------------------------------------------
		// add radio group
		public function addRadio($name,$arrItems,$text = "",$defaultItem="",$arrParams = array()){
			$params = array("items"=>$arrItems);
			$params = array_merge($params,$arrParams);
			$this->add($name,$defaultItem,$text,self::TYPE_RADIO,$params);
		}
		
		//-----------------------------------------------------------------------------------------------
		//add text area control
		public function addTextArea($name,$defaultValue,$text,$arrParams = array()){
			$this->add($name,$defaultValue,$text,self::TYPE_TEXTAREA,$arrParams);
		}

		//-----------------------------------------------------------------------------------------------
		//add button control
		public function addButton($name, $value, $text, $arrParams = array()){
			$this->add($name,$value,$text,self::TYPE_BUTTON,$arrParams);
		}
		
		
		//-----------------------------------------------------------------------------------------------
		// add checkbox element
		public function addCheckbox($name,$defaultValue = false,$text = "",$arrParams = array()){
			$this->add($name,$defaultValue,$text,self::TYPE_CHECKBOX,$arrParams);
		}
		
		//-----------------------------------------------------------------------------------------------
		//add text box element
		public function addTextBox($name,$defaultValue = "",$text = "",$arrParams = array()){
			$this->add($name,$defaultValue,$text,self::TYPE_TEXT,$arrParams);
		}
		
		//-----------------------------------------------------------------------------------------------
		//add multiple text box element
		public function addMultipleTextBox($name,$defaultValue = "",$text = "",$arrParams = array()){
			$this->add($name,$defaultValue,$text,self::TYPE_MULTIPLE_TEXT,$arrParams);
		}

		//-----------------------------------------------------------------------------------------------
		//add image selector
		public function addImage($name,$defaultValue = "",$text = "",$arrParams = array()){
			$this->add($name,$defaultValue,$text,self::TYPE_IMAGE,$arrParams);
		}
		
		//-----------------------------------------------------------------------------------------------
		//add color picker setting
		public function addColorPicker($name,$defaultValue = "",$text = "",$arrParams = array()){
			$this->add($name,$defaultValue,$text,self::TYPE_COLOR,$arrParams);
		}
		
		//-----------------------------------------------------------------------------------------------
		//add date picker setting
		public function addDatePicker($name,$defaultValue = "",$text = "",$arrParams = array()){
			$this->add($name,$defaultValue,$text,self::TYPE_DATE,$arrParams);
		}
		
		//-----------------------------------------------------------------------------------------------
		//add code mirror editor
		public function addCodemirror($name,$defaultValue = "",$text = "",$arrParams = array()){
			$this->add($name,$defaultValue,$text,'codemirror',$arrParams);
		}
		
		/**
		 * 
		 * add custom setting
		 */
		public function addCustom($customType,$name,$defaultValue = "",$text = "",$arrParams = array()){
			$params = array();
			$params["custom_type"] = $customType;
			$params = array_merge($params,$arrParams);
			
			$this->add($name,$defaultValue,$text,self::TYPE_CUSTOM,$params);
		}
		
		
		/**
		 * add horezontal sap
		 */
		public function addHr($name="",$params=array()){
			
			$setting = array();
			$setting["type"] = self::TYPE_HR;
			
		
			//set item name
			$itemName = "";
			if($name != "")
				$itemName = $name;
			else{	//generate hr id
			  $this->HRIdCounter++;
			  $itemName = "hr_".UniteFunctionsUG::getRandomString();
			  
			  if(array_key_exists($itemName, $this->arrIndex))
			  	$itemName = "hr_".UniteFunctionsUG::getRandomString();
			  
			  if(array_key_exists($itemName, $this->arrIndex))
			  	$itemName = "hr_".UniteFunctionsUG::getRandomString();			  
			}
			
			$setting["id"] = self::ID_PREFIX.$itemName;
			$setting["id_row"] = $setting["id"]."_row";
			$setting["name"] = $itemName;
			
			//addsection and sap keys
			$setting = $this->checkAndAddSectionAndSap($setting);
			
			$this->checkAddBulkControl($itemName);
			
			$setting = array_merge($params,$setting);
			
			//add after another setting
			if(array_key_exists(self::PARAM_ADD_SETTING_AFTER, $setting)){
			
				$this->addSettingAfter($setting);
			
			}else{
				$this->arrSettings[] = $setting;
			
				//add to settings index
				$this->addSettingToIndex($itemName);
			}
			
		}
		
		
		/**
		 * add static text
		 */
		public function addStaticText($text,$name="",$params=array()){
			$setting = array();
			$setting["type"] = self::TYPE_STATIC_TEXT;
			
			//set item name
			$itemName = "";
			if($name != "") $itemName = $name;
			else{	//generate hr id
			  $this->HRIdCounter++;
			  $itemName = "textitem".$this->HRIdCounter;
			}
			
			$setting["id"] = self::ID_PREFIX.$itemName;
			$setting["id_row"] = $setting["id"]."_row";
			$setting["text"] = $text;
			
			$this->checkAddBulkControl($itemName);
			
			$params = array_merge($params,$setting);
			
			//addsection and sap keys
			$setting = $this->checkAndAddSectionAndSap($setting);
			
			$this->arrSettings[] = $setting;
			
			//add to settings index
			$this->addSettingToIndex($itemName);
		}

		/**
		 * add select setting
		 */
		public function addSelect($name,$arrItems,$text,$defaultItem="",$arrParams=array()){
			$params = array("items"=>$arrItems);
			$params = array_merge($params,$arrParams);
			$this->add($name,$defaultItem,$text,self::TYPE_SELECT,$params);
		}
		
		
		/**
		 * add select setting
		 */
		public function addChecklist($name,$arrItems,$text,$defaultItem="",$arrParams=array()){
			$params = array("items"=>$arrItems);
			$params = array_merge($params,$arrParams);
			$this->add($name,$defaultItem,$text,self::TYPE_CHECKLIST,$params);
		}
		
		
		/**
		 * 
		 * add saporator
		 */
		public function addSap($text, $name="", $opened = false, $icon=""){
			
			if(empty($text))
				UniteFunctionsUG::throwError("sap $name must have a text");
			
			//create sap array
			$sap = array();
			$sap["name"] = $name; 
			$sap["text"] = $text; 
			$sap["icon"] = $icon;
			
			if($opened == true) $sap["opened"] = true;
			
			//add sap to current section
			if(!empty($this->arrSections)){
				$lastSection = end($this->arrSections);
				$section_keys = array_keys($this->arrSections);
				$lastSectionKey = end($section_keys);
				$arrSaps = $lastSection["arrSaps"];
				$arrSaps[] = $sap;
				$this->arrSections[$lastSectionKey]["arrSaps"] = $arrSaps; 				
				$sap_keys = array_keys($arrSaps);
				$sapKey = end($sap_keys);
			}
			else{
				$this->arrSaps[] = $sap;
			}
		}
		
		//-----------------------------------------------------------------------------------------------
		//get sap data:
		public function getSap($sapKey,$sectionKey=-1){
			//get sap without sections:
			if($sectionKey == -1) return($this->arrSaps[$sapKey]);
			if(!isset($this->arrSections[$sectionKey])) throw new Exception("Sap on section:".$sectionKey." doesn't exists");
			$arrSaps = $this->arrSections[$sectionKey]["arrSaps"];
			if(!isset($arrSaps[$sapKey])) throw new Exception("Sap with key:".$sapKey." doesn't exists");
			$sap = $arrSaps[$sapKey];
			return($sap);
		}
		
		//-----------------------------------------------------------------------------------------------
		// add a new section. Every settings from now on will be related to this section
		public function addSection($label,$name=""){
						
			if(!empty($this->arrSettings) && empty($this->arrSections))
				UniteFunctionsUG::throwError("You should add first section before begin to add settings. (section: $text)");
				
			if(empty($label)) 
				UniteFunctionsUG::throwError("You have some section without text");

			$arrSection = array(
				"text"=>$label,
				"arrSaps"=>array(),
				"name"=>$name
			);
			
			$this->arrSections[] = $arrSection;
		}
		
		/**
		 * add some setting after another setting
		 */
		private function addSettingAfter($setting){
			
			$addAfter = $setting[self::PARAM_ADD_SETTING_AFTER];
			
			if(array_key_exists($addAfter, $this->arrIndex) == false)
				UniteFunctionsUG::throwError("The setting with key: {$addAfter} don't exists");
			
			unset($setting[self::PARAM_ADD_SETTING_AFTER]);
			
			$insertPos = $this->arrIndex[$addAfter];
			
			//duplicate sap and section
			$settingBefore = $this->arrSettings[$insertPos];
			
			$setting["sap"] = $settingBefore["sap"];
			$setting["section"] = $settingBefore["section"];
			
			//insert after pos
			array_splice($this->arrSettings, $insertPos+1, 0, array($setting));
			
			//regenerate index array
			$this->regenerateIndex();
			
		}
		
		/**
		 * add setting, may be in different type, of values
		 */
		protected function add($name,$defaultValue = "",$text = "",$type = self::TYPE_TEXT,$arrParams = array()){
			
			//validation:
			if(empty($name)) throw new Exception("Every setting should have a name!");
			
			switch($type){
				case self::TYPE_RADIO:
				case self::TYPE_SELECT:
					$this->validateParamItems($arrParams);
				break;
				case self::TYPE_CHECKBOX:
					if(!is_bool($defaultValue)) 
						throw new Exception("The checkbox value should be boolean");
				break;
			}
			
			//validate name:
			if(isset($this->arrIndex[$name])) 
				throw new Exception("Duplicate setting name:".$name);
			
			$this->checkAddBulkControl($name);
						
			//set defaults:
			if($text == "") 
				$text = $this->defaultText;
			
			$setting = array();
			$setting["name"] = $name;
			$setting["id"] = self::ID_PREFIX.$name;
			$setting["id_service"] = $setting["id"]."_service";
			$setting["id_row"] = $setting["id"]."_row";
			$setting["type"] = $type;
			$setting["text"] = $text;
			$setting["value"] = $defaultValue;

			$setting = array_merge($setting,$arrParams);
			
			//set datatype
			if(!isset($setting["datatype"])){
				$datatype = self::DATATYPE_STRING;
				switch ($type){
					case self::TYPE_TEXTAREA:
						$datatype = self::DATATYPE_FREE;
					break;
					default:
						$datatype = self::DATATYPE_STRING;
					break;
				}
				
				$setting["datatype"] = $datatype;
			}
			
			//addsection and sap keys
			$setting = $this->checkAndAddSectionAndSap($setting);
			
			//add after another setting
			if(array_key_exists(self::PARAM_ADD_SETTING_AFTER, $setting)){
				
				$this->addSettingAfter($setting);
				
			}else{
				$this->arrSettings[] = $setting;
				
				//add to settings index
				$this->addSettingToIndex($name);
			}
			
		}
						
		
		private function ______________________CONTROLS________________(){}
		
		
		/**
		 * add a item that controlling visibility of enabled/disabled of other.
		 */
		public function addControl($control_item_name,$controlled_item_name,$control_type,$value){
		
			UniteFunctionsUG::validateNotEmpty($control_item_name,"control parent");
			UniteFunctionsUG::validateNotEmpty($controlled_item_name,"control child");
			UniteFunctionsUG::validateNotEmpty($control_type,"control type");
			UniteFunctionsUG::validateNotEmpty($value,"control value");
			
			//check for multiple control items
			if(strpos($controlled_item_name, ",") !== false){
				$controlled_item_name = explode(",", $controlled_item_name);
				
				foreach($controlled_item_name as $key=>$cvalue)
					$controlled_item_name[$key] = trim($cvalue);				
			}
						
			//modify for multiple values
			$arrValues = array();
			if(strpos($value, ",") !== false){
		
				$arrValues = explode(",", $value);
		
				foreach($arrValues as $key=>$value)
					$arrValues[$key] = trim($value);
				
				$value = $arrValues;
			}
			
			//get the control by parent, or create new
			$arrControl = array();
			if(isset($this->arrControls[$control_item_name]))
				$arrControl = $this->arrControls[$control_item_name];
			
			if(is_array($controlled_item_name)){
				foreach($controlled_item_name as $cname)
					$arrControl[] = array("name"=>$cname, "type"=>$control_type, "value"=>$value);
					
			}else
				$arrControl[] = array("name"=>$controlled_item_name, "type"=>$control_type, "value"=>$value);
			
			$this->arrControls[$control_item_name] = $arrControl;
		}
		
		
		/**
		 * start control of all settings that comes after this function (between startBulkControl and endBulkControl)
		 */
		public function startBulkControl($control_item_name,$control_type,$value){
			$this->arrBulkControl = array("control_name"=>$control_item_name,"type"=>$control_type,"value"=>$value);
		}
		
		
		/**
		 * end bulk control
		 */
		public function endBulkControl(){
			$this->arrBulkControl = array();
		}
		
		
		/**
		 * compare if the control values are equal
		 */
		private function isControlValuesEqual($parentValue, $value){
			
			if(is_array($value))
				return (in_array($parentValue, $value) === true);
			else {
				$value = strtolower($value);
				return ($parentValue === $value);				
			}
			
		}
		
		
		/**
		 * set sattes of the settings (enabled/disabled, visible/invisible) by controls
		 */
		public function setSettingsStateByControls(){
		
			//dmp($this->arrControls);
			//exit();
			
			foreach($this->arrControls as $control_name => $arrControlled){
				//take the control value
				if(!isset($this->arrIndex[$control_name])) throw new Exception("There is not sutch control setting: '$control_name'");
				$index = $this->arrIndex[$control_name];
				$parentValue = strtolower($this->arrSettings[$index]["value"]);
		
				//set child (controlled) attributes
				foreach($arrControlled as $controlled){
					if(!isset($this->arrIndex[$controlled["name"]])) throw new Exception("There is not sutch controlled setting: '".$controlled["name"]."'");
					$indexChild = $this->arrIndex[$controlled["name"]];
					$child = $this->arrSettings[$indexChild];
					$value = $controlled["value"];
					
					switch($controlled["type"]){
						case self::CONTROL_TYPE_ENABLE:
							if($this->isControlValuesEqual($parentValue, $value) == false) 
								$child["disabled"] = true;
							break;
						case self::CONTROL_TYPE_DISABLE:
							if($this->isControlValuesEqual($parentValue, $value) == true) 
								$child["disabled"] = true;
							break;
						case self::CONTROL_TYPE_SHOW:
							if($this->isControlValuesEqual($parentValue, $value) == false) 
								$child["hidden"] = true;
							break;
						case self::CONTROL_TYPE_HIDE:
							if($this->isControlValuesEqual($parentValue, $value) == true) 
								$child["hidden"] = true;
							break;
					}
					$this->arrSettings[$indexChild] = $child;
				}
			}//end foreach
		}
		
		
		//-----------------------------------------------------------------------------------------------
		//check that bulk control is available , and add some element to it.
		private function checkAddBulkControl($name){
			//add control
			if(!empty($this->arrBulkControl))
				$this->addControl($this->arrBulkControl["control_name"],$name,$this->arrBulkControl["type"],$this->arrBulkControl["value"]);
		}
		
		
		private function ________________OTHERS____________(){}
		
		
		//-----------------------------------------------------------------------------------------------
		//build name->(array index) of the settings. 
		private function buildArrSettingsIndex(){
			$this->arrIndex = array();
			foreach($this->arrSettings as $key=>$value)
				if(isset($value["name"])) $this->arrIndex[$value["name"]] = $key;
		}
		
		
		//-----------------------------------------------------------------------------------------------
		//set custom function that will be run after sections will be drawen
		public function setCustomDrawFunction_afterSections($func){
			$this->customFunction_afterSections = $func;
		}
		
		
		/**
		 * 
		 * parse options from xml field
		 * @param $field
		 */
		private function getOptionsFromXMLField($field,$fieldName){
			$arrOptions = array();
			
			$arrField = (array)$field;
			$options = UniteFunctionsUG::getVal($arrField, "option");
			
			if(empty($options))
				return($arrOptions);
				
			foreach($options as $option){
				
				if(gettype($option) == "string")
					UniteFunctionsUG::throwError("Wrong options type: ".$option." in field: $fieldName");
				
				$attribs = $option->attributes();
				
				$optionValue = (string)UniteFunctionsUG::getVal($attribs, "value");							
				$optionText = (string)UniteFunctionsUG::getVal($attribs, "text");
				
				//validate options:
				UniteFunctionsUG::validateNotEmpty($optionValue,"option value");
				UniteFunctionsUG::validateNotEmpty($optionText,"option text");
				
				$arrOptions[$optionValue] = $optionText;				 
			}
			
			return($arrOptions);
		}
		
		
		/**
		 * 
		 * merge settings with another settings object
		 */
		public function mergeSettings(UniteSettingsUG $settings){
			
			$arrSectionsNew = $settings->getArrSections();
			if(empty($arrSectionsNew))
				UniteFunctionsUG::throwError("the section should not be empty");

			$arrSapsNew = $arrSectionsNew[0]["arrSaps"];
			
			//old and new saps array
			$arrNewSapKeys = array();
			$arrSapsCurrent = $this->arrSections[0]["arrSaps"];
			
			//add new saps to saps array and remember keys
			foreach($arrSapsNew as $key => $sap){
				$arrSapsCurrent[] = $sap;
				$arrNewSapKeys[$key] = count($arrSapsCurrent)-1;								
			}
			
			$this->arrSections[0]["arrSaps"] = $arrSapsCurrent;
			
			//add settings
			$arrSettingsNew = $settings->getArrSettings();
			foreach($arrSettingsNew as $setting){
				$name = $setting["name"];
				$sapOld = $setting["sap"];
				if(array_key_exists($sapOld, $arrNewSapKeys) == false)
					UniteFunctionsUG::throwError("sap {$sapOld} should be exists in sap keys array");
				
				$sapNew = $arrNewSapKeys[$sapOld];
				
				$setting["sap"] = $sapNew;
				$this->arrSettings[] = $setting;
					
				if(array_key_exists($name, $this->arrIndex))
					UniteFunctionsUG::throwError("The setting <b>{$name} </b> already exists. ");
					
				$this->arrIndex[$name] = count($this->arrSettings)-1;
				
			}
			
			//add controls
			$arrControlsNew = $settings->getArrControls();
						
			$this->arrControls = array_merge($this->arrControls, $arrControlsNew);
			
		}
		
		
		/**
		 * add settings from external file
		 */
		private function addExternalSettings($filename, $loadfrom){
			
			switch($loadfrom){
				case "helper":
					$filepathSettings = GlobalsUG::$pathHelpersSettings.$filename.".php";
				break;
				case "current":
					$filepathSettings = GlobalsUGGallery::$pathSettings.$filename.".php";
				break;
				default:
					UniteFunctionsUG::throwError("addExternalSettings error: unknown helper foldername: {$loadfrom}");
				break;
			}
			
			
			require $filepathSettings;
			
			if(!isset($settings))
				UniteFunctionsUG::throwError("The settings include file: {$filepathSettings} must include '\$settings' object.");
			
			$this->mergeSettings($settings);
			
		}
		
		
		/**
		 * 
		 * load settings from xml file
		 */
		public function loadXMLFile($filepath){
			
			if(!file_exists($filepath))
				UniteFunctionsUG::throwError("File: '$filepath' not exists!!!");
			
			$obj = simplexml_load_file($filepath);
			
			if(empty($obj))
				UniteFunctionsUG::throwError("Wrong xml file format: $filepath");
						
			$fieldsets = $obj->fieldset;
            if(!@count($obj->fieldset)){
                $fieldsets = array($fieldsets);
            }
			
			$this->addSection("Xml Settings");
			

			foreach($fieldsets as $fieldset){
				
				//Add Section
				$attribs = $fieldset->attributes();
				
				$sapName = (string)UniteFunctionsUG::getVal($attribs, "name");
				$sapLabel = (string)UniteFunctionsUG::getVal($attribs, "label");
				$sapIcon = (string)UniteFunctionsUG::getVal($attribs, "icon");				
				$loadFrom = (string)UniteFunctionsUG::getVal($attribs, "loadfrom");				
				
				UniteFunctionsUG::validateNotEmpty($sapName,"sapName");
				
				if(!empty($loadFrom)){
					$this->addExternalSettings($sapName, $loadFrom);
					continue;
				}
				
				
				UniteFunctionsUG::validateNotEmpty($sapLabel,"sapLabel");
				
				$this->addSap($sapLabel,$sapName,false,$sapIcon);
				
				//--- add fields
				$fieldset = (array)$fieldset;
				
				$fields = UniteFunctionsUG::getVal($fieldset, "field");
				
				if(empty($fields))
					$fields = array();
				else
				if(is_array($fields) == false)
					$fields = array($fields);
				
				foreach($fields as $field){
					$attribs = $field->attributes();
					$fieldType = (string)UniteFunctionsUG::getVal($attribs, "type");
					$fieldName = (string)UniteFunctionsUG::getVal($attribs, "name");
					$fieldLabel = (string)UniteFunctionsUG::getVal($attribs, "label");
					$fieldDefaultValue = (string)UniteFunctionsUG::getVal($attribs, "default");
					
					//all other params will be added to "params array".
					$arrMustParams = array("type","name","label","default"); 
					
					$arrParams = array();
					
					foreach($attribs as $key=>$value){
						$key = (string)$key;
						$value = (string)$value;
						
						//skip must params:
						if(in_array($key, $arrMustParams))
							continue;
							
						$arrParams[$key] = $value;
					}
					
					$options = $this->getOptionsFromXMLField($field,$fieldName);
					
					//validate must fields:
					UniteFunctionsUG::validateNotEmpty($fieldType,"type");
					
					//validate name
					if($fieldType != self::TYPE_HR && $fieldType != self::TYPE_CONTROL &&
						$fieldType != "bulk_control_start" && $fieldType != "bulk_control_end")
						UniteFunctionsUG::validateNotEmpty($fieldName,"name");		
					switch ($fieldType){
						case self::TYPE_CHECKBOX:
							$fieldDefaultValue = UniteFunctionsUG::strToBool($fieldDefaultValue);
							$this->addCheckbox($fieldName,$fieldDefaultValue,$fieldLabel,$arrParams);
						break;
						case self::TYPE_COLOR:
							$this->addColorPicker($fieldName,$fieldDefaultValue,$fieldLabel,$arrParams);
						break;
						case self::TYPE_HR:
							$this->addHr($fieldName);
						break;
						case self::TYPE_TEXT:
							$this->addTextBox($fieldName,$fieldDefaultValue,$fieldLabel,$arrParams);
						break;
						case self::TYPE_MULTIPLE_TEXT:
							$this->addMultipleTextBox($fieldName,$fieldDefaultValue,$fieldLabel,$arrParams);
						break;
						case self::TYPE_STATIC_TEXT:
							$this->addStaticText($fieldLabel, $fieldName, $arrParams);
						break;
						case self::TYPE_IMAGE:
							$this->addImage($fieldName,$fieldDefaultValue,$fieldLabel,$arrParams);
						break;						
						case self::TYPE_SELECT:	
							$this->addSelect($fieldName, $options, $fieldLabel,$fieldDefaultValue,$arrParams);
						break;
						case self::TYPE_CHECKBOX:
							$this->addChecklist($fieldName, $options, $fieldLabel,$fieldDefaultValue,$arrParams);
						break;
						case self::TYPE_RADIO:
							$this->addRadio($fieldName, $options, $fieldLabel,$fieldDefaultValue,$arrParams);
						break;
						case self::TYPE_BOOLEAN:
							$options = array("true"=>"Yes","false"=>"No");
							$this->addRadio($fieldName, $options, $fieldLabel,$fieldDefaultValue,$arrParams);							
						break;
						case self::TYPE_TEXTAREA:
							$this->addTextArea($fieldName, $fieldDefaultValue, $fieldLabel, $arrParams);
						break;
						case self::TYPE_CUSTOM:
							$this->add($fieldName, $fieldDefaultValue, $fieldLabel, self::TYPE_CUSTOM, $arrParams);
						break;
						case self::TYPE_BUTTON:
							$this->addButton($fieldName, $fieldDefaultValue, $fieldLabel, $arrParams);
						break;
						case self::TYPE_CONTROL:
							$parent = UniteFunctionsUG::getVal($arrParams, "parent");
							$child =  UniteFunctionsUG::getVal($arrParams, "child");
							$ctype =  UniteFunctionsUG::getVal($arrParams, "ctype");
							$value =  UniteFunctionsUG::getVal($arrParams, "value");
							$this->addControl($parent, $child, $ctype, $value);
						break;			
						case "bulk_control_start":
							$parent = UniteFunctionsUG::getVal($arrParams, "parent");
							$ctype =  UniteFunctionsUG::getVal($arrParams, "ctype");
							$value =  UniteFunctionsUG::getVal($arrParams, "value");
							
							$this->startBulkControl($parent, $ctype, $value);
						break;
						case "bulk_control_end":
							$this->endBulkControl();
						break;	
						case "codemirror":
							$this->addCodemirror($fieldName,$fieldDefaultValue,$fieldLabel,$arrParams);
						break;
						default:
							UniteFunctionsUG::throwError("wrong type: $fieldType");
						break;						
					}
					
				}
			}
		}
		
		
		/**
		 * 
		 * get titles and descriptions array
		 */
		public function getArrTextFromAllSettings(){
			$arr = array();
			$arrUnits = array();
			
			//get saps array:
			foreach($this->arrSections as $section){
				$arrSaps = UniteFunctionsUG::getVal($section, "arrSaps");
				if(empty($arrSaps))
					continue;
				foreach($arrSaps as $sap){
					$text = $sap["text"];
					if(!empty($text))
						$arr[] = $text;
				}
			}
			
			foreach($this->arrSettings as $setting){
				
				$text = UniteFunctionsUG::getVal($setting, "text");				
				$desc = UniteFunctionsUG::getVal($setting, "description");
				$unit = UniteFunctionsUG::getVal($setting, "unit");
								
				if(!empty($text))
					$arr[] = $text;
					
				if(!empty($desc))
					$arr[] = $desc;
					
				if(!empty($unit)){
					if(!isset($arrUnits[$unit]))
						$arr[] = $unit;	
					$arrUnits[$unit] = true;
				}

				$items = UniteFunctionsUG::getVal($setting, "items");
				if(!empty($items)){
					foreach($items as $item){
						if(!isset($arrUnits[$item]))
							$arr[] = $item;	
						$arrUnits[$item] = true;
					}
				}				
			}
			
			return($arr);
		}

		
		/**
		 * 
		 * get setting array by name
		 */
		public function getSettingByName($name){
			
			//if index present
			if(!empty($this->arrIndex)){
				if(array_key_exists($name, $this->arrIndex) == false)
					UniteFunctionsUG::throwError("setting $name not found");
				$index = $this->arrIndex[$name];
				$setting = $this->arrSettings[$index];
				return($setting);
			}
			
			//if no index
			foreach($this->arrSettings as $setting){
				$settingName = UniteFunctionsUG::getVal($setting, "name");
				if($settingName == $name)
					return($setting);
			}
			
			UniteFunctionsUG::throwError("Setting with name: $name don't exists");
		}
		
		
		/**
		 * 
		 * get value of some setting
		 * @param $name
		 */
		public function getSettingValue($name,$default=""){
			$setting = $this->getSettingByName($name);
			$value = UniteFunctionsUG::getVal($setting, "value",$default);

			return($value);
		}
		
		
		/**
		 * 
		 * update setting array by name
		 */
		public function updateArrSettingByName($name,$setting){
			
			foreach($this->arrSettings as $key => $settingExisting){
				$settingName = UniteFunctionsUG::getVal($settingExisting,"name");
				if($settingName == $name){
					$this->arrSettings[$key] = $setting;
					return(false);
				}
			}
			
			UniteFunctionsUG::throwError("Setting with name: $name don't exists");
		}
		
		/**
		 * hide some setting
		 * @param unknown_type $name
		 */
		public function hideSetting($name){
			$this->updateSettingProperty($name, "hidden", "true");
		}
		
		/**
		 * hide multiple settings from array
		 * 
		 */
		public function hideSettings($arrSettings){
			
			foreach($arrSettings as $settingName)
				$this->hideSetting($settingName);
		}
		
		/**
		 *
		 * modify some value by it's datatype
		 */
		public function modifyValueByDatatype($value,$datatype){
			if(is_array($value)){
				foreach($value as $key => $val){
					$value[$key] = $this->modifyValueByDatatypeFunc($val,$datatype);
				}
			}else{
				$value = $this->modifyValueByDatatypeFunc($value,$datatype);
			}
			return($value);
		}
		
		/**
		 *
		 * modify some value by it's datatype
		 */
		public function modifyValueByDatatypeFunc($value,$datatype){
			switch($datatype){
				case self::DATATYPE_STRING:
					$value = strip_tags($value, '<link>');
					break;
				case self::DATATYPE_NUMBER:
					$value = floatval($value);	//turn every string to float
					if(!is_numeric($value))
						$value = 0;
					break;
				case self::DATATYPE_NUMBEROREMTY:
					$value = trim($value);
					if($value !== "")
						$value = floatval($value);	//turn every string to float
					break;
			}
		
			return $value;
		}
		
		/**
		 *
		 * set values from array of stored settings elsewhere.
		 */
		public function setStoredValues($arrValues){
		
			foreach($this->arrSettings as $key=>$setting){
		
				$name = UniteFunctionsUG::getVal($setting, "name");
		
				//type consolidation
				$type = UniteFunctionsUG::getVal($setting, "type");
		
				$datatype = UniteFunctionsUG::getVal($setting, "datatype");
		
				//skip custom type
				$customType = UniteFunctionsUG::getVal($setting, "custom_type");
		
				if(!empty($customType))
					continue;
		
				if(array_key_exists($name, $arrValues)){
					$value = $arrValues[$name];
					$value = $this->modifyValueByDatatype($value, $datatype);
					$this->arrSettings[$key]["value"] = $value;
					$arrValues[$name] = $value;
				}
		
			}//end foreach
		
			return($arrValues);
		}
		
		
		/**
		 * get setting values. replace from stored ones if given
		 */
		public function getArrValues(){
		
			$arrSettingsOutput = array();
		
			//modify settings by type
			foreach($this->arrSettings as $setting){
				if($setting["type"] == self::TYPE_HR
						||$setting["type"] == self::TYPE_STATIC_TEXT)
					continue;
		
				$value = $setting["value"];
		
				//modify value by type
				switch($setting["type"]){
					case self::TYPE_COLOR:
						$value = strtolower($value);
						//set color output type
						if($this->colorOutputType == self::COLOR_OUTPUT_FLASH)
							$value = str_replace("#","0x",$value);
						break;
					case self::TYPE_CHECKBOX:
						if($value == true) $value = "true";
						else $value = "false";
						break;
				}
		
				//remove lf
				if(isset($setting["remove_lf"])){
					$value = str_replace("\n","",$value);
					$value = str_replace("\r\n","",$value);
				}
		
				$arrSettingsOutput[$setting["name"]] = $value;
			}
		
			return($arrSettingsOutput);
		}
		
		
		/**
		 * Update values from post meta
		 * wordpress only
		 */
		public function updateValuesFromPostMeta($postID){
		
			//update setting values array from meta
			$arrNames = $this->getArrSettingNames();
			$arrValues = array();
			$arrMeta = get_post_meta($postID);
		
			if(!empty($arrMeta) && is_array($arrMeta)){
				foreach($arrNames as $name){
					if(array_key_exists($name, $arrMeta) == false)
						continue;
		
					$value = get_post_meta($postID, $name,true);
					$arrValues[$name] = $value;
				}
			}
		
			//dmp($postID);dmp($arrValues);exit();
		
			$this->setStoredValues($arrValues);
		
		}
		
		
		private function ______________UPDATE_________(){}
		
		/**
		 * set addtext to the setting
		 */
		public function updateSettingAddHTML($name, $html){
			$this->updateSettingProperty($name, self::PARAM_ADDTEXT, $html);
		}
		
		/**
		 * update setting property
		 */
		public function updateSettingProperty($settingName, $propertyName, $value){
			
			$setting = $this->getSettingByName($settingName);
			$setting[$propertyName] = $value;

			$this->updateArrSettingByName($settingName, $setting);			
		}
		
		
		/**
		 * 
		 * update default value in the setting
		 */
		public function updateSettingValue($name,$value){
			$setting = $this->getSettingByName($name);
			$setting["value"] = $value;
			
			$this->updateArrSettingByName($name, $setting);
		}
		
		/**
		 *
		 * update default value in the setting
		 */
		public function updateSettingItems($name, $items, $default = null){
			$setting = $this->getSettingByName($name);
			$setting["items"] = $items;
			if($default !== null)
				$setting["value"] = $default;
			
			$this->updateArrSettingByName($name, $setting);
		}
		
		
		
	}
	
?>