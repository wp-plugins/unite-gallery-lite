<?php
/**
 * @package Unite Gallery
 * @author UniteCMS.net / Valiano
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('_JEXEC') or die('Restricted access');


	// advanced settings class. adds some advanced features
	class UniteSettingsAdvancedUG extends UniteSettingsUG{
		
		/**
		 * add boolean true/false select with custom names
		 */
		public function addSelect_boolean($name,$text,$bValue=true,$firstItem="Enable",$secondItem="Disable",$arrParams=array()){
			$arrItems = array("true"=>$firstItem,"false"=>$secondItem);
			$defaultText = "true";
			if($bValue == false) 
				$defaultText = "false";
			$this->addSelect($name,$arrItems,$text,$defaultText,$arrParams);
		}
		
		/**
		 * add radio item boolean true / false
		 */
		public function addRadioBoolean($name,$text,$bValue=true,$firstItem="Yes",$secondItem="No",$arrParams=array()){
			$arrItems = array("true"=>$firstItem,"false"=>$secondItem);
			$defaultText = "true";
			if($bValue == false)
				$defaultText = "false";
			
			$this->addRadio($name,$arrItems,$text,$defaultText,$arrParams);
		}
		
		//------------------------------------------------------------------------------
		//add float select
		public function addSelect_float($name,$defaultValue,$text,$arrParams=array()){
			$this->addSelect($name,array("left"=>"Left","right"=>"Right"),$text,$defaultValue,$arrParams);
		}
		
		//------------------------------------------------------------------------------
		//add align select
		public function addSelect_alignX($name,$defaultValue,$text,$arrParams=array()){
			$this->addSelect($name,array("left"=>"Left","center"=>"Center","right"=>"Right"),$text,$defaultValue,$arrParams);
		}

		//------------------------------------------------------------------------------
		//add align select
		public function addSelect_alignY($name,$defaultValue,$text,$arrParams=array()){
			$this->addSelect($name,array("top"=>"Top","middle"=>"Middle","bottom"=>"Bottom"),$text,$defaultValue,$arrParams);
		}
		
		//------------------------------------------------------------------------------
		//add transitions select
		public function addSelect_border($name,$defaultValue,$text,$arrParams=array()){
			$arrItems = array();
			$arrItems["solid"] = "Solid";
			$arrItems["dashed"] = "Dashed";
			$arrItems["dotted"] = "Dotted";
			$arrItems["double"] = "Double";
			$arrItems["groove"] = "Groove";
			$arrItems["ridge"] = "Ridge";
			$arrItems["inset"] = "Inset";
			$arrItems["outset"] = "Outset";
			$this->addSelect($name,$arrItems,$text,$defaultValue,$arrParams);			
		}
		
		//------------------------------------------------------------------------------
		//add transitions select
		public function addSelect_textDecoration($name,$defaultValue,$text,$arrParams=array()){
			$arrItems = array();
			$arrItems["none"] = "None";
			$arrItems["underline"] = "Underline";
			$arrItems["overline"] = "Overline";
			$arrItems["line-through"] = "Line-through";
			$this->addSelect($name,$arrItems,$text,$defaultValue,$arrParams);			
		}
		
		//------------------------------------------------------------------------------
		//add transitions select - arrExtensions may be string, and lower case
		public function addSelect_filescan($name,$path,$arrExtensions,$defaultValue,$text,$arrParams=array()){
			
			if(getType($arrExtensions) == "string")
				$arrExtensions = array($arrExtensions);
			elseif(getType($arrExtensions) != "array")
				$this->throwError("The extensions array is not array and not string in setting: $name, please check.");
			
			//make items array
			if(!is_dir($path))
				$this->throwError("path: $path not found");
			
			$arrItems = array();
			$files = scandir($path);
			foreach($files as $file){
				//general filter
				if($file == ".." || $file == "." || $file == ".svn")
					continue;
					
				$info = pathinfo($file);
				$ext = UniteFunctionsUG::getVal($info,"extension");
				$ext = strtolower($ext);
				
				if(array_search($ext,$arrExtensions) === FALSE)
					continue;
					
				$arrItems[$file] = $file;
			}
			
			//handle add data array
			if(isset($arrParams["addData"])){
				foreach($arrParams["addData"] as $key=>$value)
					$arrItems[$key] = $value;
			}
			
			if(empty($defaultValue) && !empty($arrItems))
				$defaultValue = current($arrItems);
			
			$this->addSelect($name,$arrItems,$text,$defaultValue,$arrParams);
		}
		
		
		/**
		 * get transitions array
		 */
		private function getArrEasing(){
			
			$arrItems = array();
			$arrItems["linear"] = "Linear";
			$arrItems["swing"] = "Swing";
						
			$arrItems["easeOutQuad"] = "EaseOut - Quad";
			$arrItems["easeOutQuint"] = "EaseOut - Quint";			
			$arrItems["easeOutBounce"] = "EaseOut - Bounce";
			$arrItems["easeOutElastic"] = "EaseOut - Elastic";
			$arrItems["easeOutBack"] = "EaseOut - Back";
			$arrItems["easeOutQuart"] = "EaseOut - Quart";
			$arrItems["easeOutExpo"] = "EaseOut - Expo";
			$arrItems["easeOutCubic"] = "EaseOut - Cubic";
			$arrItems["easeOutSine"] = "EaseOut - Sine";			
			$arrItems["easeOutCirc"] = "EaseOut - Circ";			
			
			
			$arrItems["easeInQuad"] = "EaseIn - Quad";
			$arrItems["easeInQuint"] = "EaseIn - Quint";						
			$arrItems["easeInBounce"] = "EaseIn - Bounce";
			$arrItems["easeInElastic"] = "EaseIn - Elastic";
			$arrItems["easeInBack"] = "EaseIn - Back";
			$arrItems["easeInQuart"] = "EaseIn - Quart";
			$arrItems["easeInExpo"] = "EaseIn - Expo";
			$arrItems["easeInCubic"] = "EaseIn - Cubic";
			$arrItems["easeInSine"] = "EaseIn - Sine";
			$arrItems["easeInCirc"] = "EaseIn - Circ";

			
			$arrItems["easeInOutQuad"] = "EaseInOut - Quad";
			$arrItems["easeInQuint"] = "EaseInOut - Quint";
			$arrItems["easeInOutBounce"] = "EaseInOut - Bounce";
			$arrItems["easeInOutElastic"] = "EaseInOut - Elastic";
			$arrItems["easeInOutBack"] = "EaseInOut - Back";
			$arrItems["easeInOutQuart"] = "EaseInOut - Quart";
			$arrItems["easeInOutExpo"] = "EaseInOut - Expo";
			$arrItems["easeInOutCubic"] = "EaseInOut - Cubic";
			$arrItems["easeInOutSine"] = "EaseInOut - Sine";
			$arrItems["easeInOutCirc"] = "EaseInOut - Circ";

			return($arrItems);
		}
		
		
		/**
		 * add transitions array item to some select
		 */
		public function updateSelectToEasing($name){
			
			$arrItems = $this->getArrEasing();
			$this->updateSettingItems($name, $arrItems);
			
		}
		
		/**
		 * add transitions array item to some select
		 */
		public function updateSelectToAlignHor($name, $default = null){
		
			$arrItems = array(
					"left"=>__("Left",UNITEGALLERY_TEXTDOMAIN),
					"center"=>__("Center", UNITEGALLERY_TEXTDOMAIN),
					"right"=>__("Right", UNITEGALLERY_TEXTDOMAIN)
			);
			
			$this->updateSettingItems($name, $arrItems, $default);
		}
		
		/**
		 * add transitions array item to some select
		 */
		public function updateSelectToAlignVert($name, $default = null){
		
			$arrItems = array(
					"top"=>__("Top",UNITEGALLERY_TEXTDOMAIN),
					"middle"=>__("Middle", UNITEGALLERY_TEXTDOMAIN),
					"bottom"=>__("Bottom", UNITEGALLERY_TEXTDOMAIN)
			);
		
			$this->updateSettingItems($name, $arrItems, $default);
		}

		
		/**
		 * add transitions array item to some select
		 */
		public function updateSelectToAlignCombo($name, $default = null){
		
			$arrItems = array(
					"left"=>__("Left",UNITEGALLERY_TEXTDOMAIN),
					"center"=>__("Center", UNITEGALLERY_TEXTDOMAIN),
					"right"=>__("Right", UNITEGALLERY_TEXTDOMAIN),					
					"top"=>__("Top",UNITEGALLERY_TEXTDOMAIN),
					"middle"=>__("Middle", UNITEGALLERY_TEXTDOMAIN),
					"bottom"=>__("Bottom", UNITEGALLERY_TEXTDOMAIN)
			);
		
			$this->updateSettingItems($name, $arrItems, $default);
		}
		
		
	}
	
?>