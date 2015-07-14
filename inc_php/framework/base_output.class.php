<?php
/**
 * @package Unite Gallery
 * @author UniteCMS.net / Valiano
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('_JEXEC') or die('Restricted access');

	
	class UniteOutputBaseUG{
		
		protected $arrParams;
		protected $arrOriginalParams;		//params as they came originally from the database
		
		protected $skipJsOptions = array();
		
		const TYPE_NUMBER = "number";
		const TYPE_BOOLEAN = "boolean";
		const TYPE_OBJECT = "object";
		const TYPE_SIZE = "size";
		
		const VALIDATE_EXISTS = "validate";
		const VALIDATE_NUMERIC = "numeric";
		const VALIDATE_SIZE = "size";
		const FORCE_NUMERIC = "force_numeric";
		const FORCE_BOOLEAN = "force_boolean";
		const FORCE_SIZE = "force_size";

		
		/**
		 * add js option to skip
		 */
		protected function addSkipJsOption($name){
			
			$this->skipJsOptions[$name] = true;
			
		}
		
		
		/**
		 * check if some param exists in params array
		 */
		protected function isParamExists($name){
			$exists = array_key_exists($name, $this->arrParams);	
			return $exists;		
		}
		
		
		/**
		 * return true if the param exists and not empty
		 */
		protected function isParamExistsAndNotEmpty($name){
			$exists = array_key_exists($name, $this->arrParams);
			
			if($exists && $this->arrParams[$name] !== "")
				return(true);
			
			return false;
		}
		
		
		/**
		 * add some param to style array, if not exists and not emtpy
		 */
		protected function addParamToStyleArray($arrStyle, $name, $attr, $suffix = "", $valiateMode = ""){
			
			if($this->isParamExists($name) == false)
				return($arrStyle);
			
			$value = $this->getParam($name, $valiateMode);
			
			$arrStyle[$attr] = $value.$suffix;
			
			return($arrStyle);
		}
		
		
		/**
		 *
		 * get some param
		 */
		protected function getParam($name, $validateMode = null){
			
			if(is_array($this->arrParams) == false)
				$this->arrParams = array();
			
			if(array_key_exists($name, $this->arrParams)){
				$arrParams = $this->arrParams;
				$value = $this->arrParams[$name];				
			}
			else{
				if(is_array($this->arrOriginalParams) == false)
					$this->arrOriginalParams = array();
				
				$arrParams = $this->arrOriginalParams;
				$value = UniteFunctionsUG::getVal($this->arrOriginalParams, $name);				
			}
			
			switch ($validateMode) {
				case self::VALIDATE_EXISTS:
					if (array_key_exists($name, $arrParams) == false)
						UniteFunctionsUG::throwError("The param: {$name} don't exists");
				break;
				case self::VALIDATE_NUMERIC:
					if (is_numeric($value) == false)
						UniteFunctionsUG::throwError("The param: {$name} is not numeric");
				break;
				case self::VALIDATE_SIZE:
					if(strpos($value, "%") === false && is_numeric($value) == false)
						UniteFunctionsUG::throwError("The param: {$name} is not size");
				break;
				case self::FORCE_SIZE:
					$isPercent = (strpos($value, "%") !== false);
					if($isPercent == false && is_numeric($value) == false)
						UniteFunctionsUG::throwError("The param: {$name} is not size");
					
					if($isPercent == false)
						$value .= "px";
				break;
				case self::FORCE_NUMERIC:
					$value = floatval($value);
					$value = (double) $value;
				break;			
				case self::FORCE_BOOLEAN:
					$value = UniteFunctionsUG::strToBool($value);
				break;
			}
			
			return($value);
		}

		
		/**
		 * rename option (if source exists)
		 */
		protected function renameOption($keySource, $keyDest, $deleteDestFirst = false){
			
			if($deleteDestFirst == true){
				if(array_key_exists($keyDest, $this->arrParams))
					unset($this->arrParams[$keyDest]);
			}
			
			if(array_key_exists($keySource, $this->arrParams)){
		
				$this->arrParams[$keyDest] = $this->arrParams[$keySource];
				unset($this->arrParams[$keySource]);
			}
		
		}
		
		
		
		/**
		 * delete options from keys array
		 */
		protected function deleteOptions($arrKeys){
			
			foreach($arrKeys as $key){
				$this->deleteOption($key);
			}
		
		}
		
		
		/**
		 * delete some option if exists
		 */
		protected function deleteOption($key){

			if(array_key_exists($key, $this->arrParams))
				unset($this->arrParams[$key]);
		}
		
		
		/**
		 * build javascript param
		 */
		protected function buildJsParam($paramName, $validate = null, $type = null){
			
			if(array_key_exists($paramName, $this->arrJsParamsAssoc))
				UniteFunctionsUG::throwError("Unable to biuld js param: <b>$paramName</b> already exists");
			
			$output = array("name"=>$paramName, "validate"=>$validate, "type"=>$type);
			
			$this->arrJsParamsAssoc[$paramName] = true;
			
			return($output);
		}
		
		
		/**
		 * build and get js settings
		 */
		protected function buildJsParams(){
		
			$arrJsParams = $this->getArrJsOptions();
			$jsOutput = "";
			$counter = 0;
			$tabs = "								";
			
			
			foreach($arrJsParams as $arrParam){
				$name = $arrParam["name"];
				$validate = $arrParam["validate"];
				$type = $arrParam["type"];
				
				if(array_key_exists($name, $this->skipJsOptions) == true)
					continue;
				
				if($this->isParamExists($name)){
					$value = $this->getParam($name, $validate);
					
					$putInBrackets = false;
					switch($type){
						case self::TYPE_NUMBER:
						case self::TYPE_BOOLEAN:
						case self::TYPE_OBJECT:
						break;
						case self::TYPE_SIZE:
							if(strpos($value, "%") !== 0)
								$putInBrackets = true;
						break;
						default:	//string
							$putInBrackets = true;						
						break;
					}
		
					if($putInBrackets == true){
						$value = str_replace('"','\\"', $value);
						$value = '"'.$value.'"';
					}
					
					if($counter > 0)
						$jsOutput .= ",\n".$tabs;
					$jsOutput .= "{$name}:{$value}";
		
					$counter++;
				}
			}
		
			$jsOutput .= "\n";
		
			return($jsOutput);
		}
		
		
		/**
		 * get string from position options
		 */
		protected function getPositionString($isTylesType = false){
			
			$position = $this->getParam("position");
			
			$wrapperStyle = "";
			
			switch($position){
				case "default":
				break;
				case "center":
				default:
					$wrapperStyle .= "margin:0px auto;";
				break;
				case "left":
					if($isTylesType == false)
						$wrapperStyle .= "float:left;";
					break;
				case "right":
					if($isTylesType == false)
						$wrapperStyle .= "float:right;";
					break;
			}
			
			//add left / right margin
			if($position != "center"){
				$marginLeft = $this->getParam("margin_left", self::FORCE_NUMERIC);
				$marginRight = $this->getParam("margin_right", self::FORCE_NUMERIC);
			
				if($marginLeft != 0)
					$wrapperStyle .= "margin-left:{$marginLeft}px;";
			
				if($marginRight != 0)
					$wrapperStyle .= "margin-right:{$marginRight}px;";
			
			}
			
			//add top / bottom margin
			$marginTop = $this->getParam("margin_top", self::FORCE_NUMERIC);
			$marginBottom = $this->getParam("margin_bottom", self::FORCE_NUMERIC);
			
			if($marginTop != 0)
				$wrapperStyle .= "margin-top:{$marginTop}px;";
			
			if($marginBottom != 0)
				$wrapperStyle .= "margin-bottom:{$marginBottom}px;";
			
			return($wrapperStyle);
		}
		
		
	}
?>
