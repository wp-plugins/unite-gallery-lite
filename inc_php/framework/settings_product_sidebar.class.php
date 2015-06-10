<?php
/**
 * @package Unite Gallery
 * @author UniteCMS.net / Valiano
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('_JEXEC') or die('Restricted access');

	class UniteSettingsProductSidebarUG extends UniteSettingsOutputUG{
		
		private $addClass = "";		//add class to the main div
		private $arrButtons = array();
		private $isAccordion = true;
		private $defaultTextClass;
		
		const INPUT_CLASS_SHORT = "text-sidebar";
		const INPUT_CLASS_NORMAL = "text-sidebar-normal";
		const INPUT_CLASS_LONG = "text-sidebar-long";
		const INPUT_CLASS_LINK = "text-sidebar-link";
		

		/**
		 * 
		 * construction
		 */
		public function __construct(){
			$this->defaultTextClass = self::INPUT_CLASS_SHORT;
		}
		
		
		/**
		 * 
		 * set default text class
		 */
		public function setDefaultInputClass($defaultClass){
			$this->defaultTextClass = $defaultClass;
		}
		
		
		/**
		 * 
		 * add buggon
		 */
		public function addButton($title,$id,$class = "unite-button-secondary"){
			
			$button = array(
				"title"=>$title,
				"id"=>$id,
				"class"=>$class
			);
			
			$this->arrButtons[] = $button;			
		}
		
		
		
		/**
		 * 
		 * set add class for the main div
		 */
		public function setAddClass($addClass){
			$this->addClass = $addClass;
		}
		
		
		//-----------------------------------------------------------------------------------------------
		//draw text as input
		protected function drawTextInput($setting) {
			
			$disabled = "";
			$style="";
			if(isset($setting["style"])) 
				$style = "style='".$setting["style"]."'";
			if(isset($setting["disabled"])) 
				$disabled = 'disabled="disabled"';

			$class = UniteFunctionsUG::getVal($setting, "class");
			
			if(empty($class)){
				$unit = UniteFunctionsUG::getVal($setting, "unit");
				if(!empty($unit))
					$class = $this->defaultTextClass;
				else
					$class = self::INPUT_CLASS_NORMAL;
			}
			
			//modify class:
			switch($class){
				case "normal":
				case "regular":
					$class = self::INPUT_CLASS_NORMAL;
				break;
				case "long":
					$class = self::INPUT_CLASS_LONG;
				break;
				case "link":
					$class = self::INPUT_CLASS_LINK;
				break;
			}
			
			if(!empty($class))
				$class = "class='$class'";
			
			$attribs = UniteFunctionsUG::getVal($setting, "attribs");
			
			?>
				<input type="text" <?php echo $attribs?> <?php echo $class?> <?php echo $style?> <?php echo $disabled?> id="<?php echo $setting["id"]?>" name="<?php echo $setting["name"]?>" value="<?php echo $setting["value"]?>" />
			<?php
		}
		
		//-----------------------------------------------------------------------------------------------
		//draw multiple text boxes as input
		protected function drawMultipleText($setting) {
			$disabled = "";
			$style="";
			if(isset($setting["style"])) 
				$style = "style='".$setting["style"]."'";
			if(isset($setting["disabled"])) 
				$disabled = 'disabled="disabled"';

			$class = UniteFunctionsUG::getVal($setting, "class",$this->defaultTextClass);
							
			//modify class:
			switch($class){
				case "normal":
				case "regular":
					$class = self::INPUT_CLASS_NORMAL;
				break;
				case "long":
					$class = self::INPUT_CLASS_LONG;
				break;
				case "link":
					$class = self::INPUT_CLASS_LINK;
				break;
			}
			
			if(!empty($class))
				$class = "class='$class'";
			
			$attribs = UniteFunctionsUG::getVal($setting, "attribs");
			$values = $setting["value"];
			if(!empty($values) && is_array($values)){
				foreach($values as $key => $value){
					
					$value = stripslashes($value);
					$value = htmlspecialchars($value);
					
				?>
					<div class="fontinput_wrapper">
					<input type="text" <?php echo $attribs?> <?php echo $class?> <?php echo $style?> <?php echo $disabled?> id="<?php echo $setting["id"].'_'.$key?>" name="<?php echo $setting["name"]?>[]" value="<?php echo $value?>" /> <a href="javascript:void(0);" data-remove="<?php echo $setting["id"].'_'.$key?>" class="remove_multiple_text"><i class="revicon-trash redicon withhover"></i></a>
					</div>
				<?php
				}
			}else{ //fallback to old version
				$key = 0;
				$value = $setting["value"];
				$value = stripslashes($value);
				$value = htmlspecialchars($value);
				
			?>
				<div class="fontinput_wrapper">
					<input type="text" <?php echo $attribs?> <?php echo $class?> <?php echo $style?> <?php echo $disabled?> id="<?php echo $setting["id"].'_'.$key?>" name="<?php echo $setting["name"]?>[]" value="<?php echo $value?>" /> <a href="javascript:void(0);" data-remove="<?php echo $setting["id"].'_'.$key?>" class="remove_multiple_text"><i class="revicon-trash redicon withhover"></i></a>
				</div>
			<?php
			}
			?>
			
			<div class="<?php echo $setting["id"]?>_TEMPLATE" style="display: none;">
				<div class="fontinput_wrapper">
					<input type="text" <?php echo $attribs?> <?php echo $class?> <?php echo $style?> id="##ID##" name="##NAME##[]" value="" /> <a href="javascript:void(0);" data-remove="##ID##" class="remove_multiple_text"><i class="revicon-trash redicon withhover"></i></a>
				</div>
			</div>
			
			<script type="text/javascript">
				UniteAdminRev.setMultipleTextKey('<?php echo $setting["id"]?>', <?php echo $key?>);
			</script>
			<?php
		}
		
		//-----------------------------------------------------------------------------------------------
		//draw a color picker
		protected function drawColorPickerInput($setting){	
			
			$bgcolor = $setting["value"];
			
			$bgcolor = str_replace("0x","#",$bgcolor);			
			
			// set the forent color (by black and white value)
			$color = "#000000";			
			if(!empty($bgcolor)){
				$rgb = UniteFunctionsUG::html2rgb($bgcolor);
				$bw = UniteFunctionsUG::yiq($rgb[0],$rgb[1],$rgb[2]);
				if($bw<128) 
					$color = "#ffffff";
			}
						
			
			$disabled = "";
			if(isset($setting["disabled"])){
				$color = "";
				$disabled = 'disabled="disabled"';
			}
			
			$style="style='background-color:$bgcolor;color:$color'";
			
			?>
				<input type="text" class="inputColorPicker" id="<?php echo $setting["id"]?>" <?php echo $style?> name="<?php echo $setting["name"]?>" value="<?php echo $bgcolor?>" <?php echo $disabled?>></input>
			<?php
		}
		
		
		//-----------------------------------------------------------------------------------------------
		//draw code mirror input
		protected function drawCodeMirror($setting){
			?>
			<textarea name="<?php echo $setting['name']; ?>" id="<?php echo $setting['id']; ?>"><?php echo $setting["value"]; ?></textarea>
			
			<script type="text/javascript">

				rev_cm_<?php echo $setting['id']; ?> = null;
				jQuery(document).ready(function(){
						rev_cm_<?php echo $setting['id']; ?> = CodeMirror.fromTextArea(document.getElementById("<?php echo $setting['id']; ?>"), {
						onChange: function(){ },
						lineNumbers: true
					});
			
					jQuery('.postbox.unite-postbox').click(function(){
						rev_cm_<?php echo $setting['id']; ?>.refresh();
						});
				});
			
			</script>
		<?php
		}
		
		
		
		//-----------------------------------------------------------------------------------------------
		// draw setting input by type
		protected function drawInputs($setting){
			switch($setting["type"]){
				case UniteSettingsUG::TYPE_TEXT:
					$this->drawTextInput($setting);
				break;
				case UniteSettingsUG::TYPE_HIDDEN:
					$this->drawHiddenInput($setting);
				break;
				case UniteSettingsUG::TYPE_COLOR:
					$this->drawColorPickerInput($setting);
				break;
				case UniteSettingsUG::TYPE_SELECT:
					$this->drawSelectInput($setting);
				break;
				case UniteSettingsUG::TYPE_CHECKBOX:
					$this->drawCheckboxInput($setting);
				break;
				case UniteSettingsUG::TYPE_RADIO:
					$this->drawRadioInput($setting);
				break;
				case UniteSettingsUG::TYPE_TEXTAREA:
					$this->drawTextAreaInput($setting);
				break;
				case UniteSettingsUG::TYPE_CUSTOM:
					$this->drawCustom($setting);
				break;
				case UniteSettingsUG::TYPE_BUTTON:
					$this->drawButtonSetting($setting);
				break;
				case UniteSettingsUG::TYPE_MULTIPLE_TEXT:
					$this->drawMultipleText($setting);
				break;
				case 'codemirror':
					$this->drawCodeMirror($setting);
				break;
				default:
					throw new Exception("wrong setting type - ".$setting["type"]);
				break;
			}			
		}		
		
		//-----------------------------------------------------------------------------------------------
		//draw advanced order box
		protected function drawOrderbox_advanced($setting){
			
			$items = $setting["items"];
			if(!is_array($items))
				$this->throwError("Orderbox error - the items option must be array (items)");
				
			//get arrItems modify items by saved value			
			
			if(!empty($setting["value"]) && 
				getType($setting["value"]) == "array" &&
				count($setting["value"]) == count($items)):
				
				$savedItems = $setting["value"];
				
				//make assoc array by id:
				$arrAssoc = array();
				foreach($items as $item)
					$arrAssoc[$item[0]] = $item[1];
				
				foreach($savedItems as $item){
					$value = $item["id"];
					$text = $value;
					if(isset($arrAssoc[$value]))
						$text = $arrAssoc[$value];
					$arrItems[] = array($value,$text,$item["enabled"]);
				}
			else: 
				$arrItems = $items;
			endif;
			
			?>	
			<ul class="orderbox_advanced" id="<?php echo $setting["id"]?>">
			<?php 
			foreach($arrItems as $arrItem){
				switch(getType($arrItem)){
					case "string":
						$value = $arrItem;
						$text = $arrItem;
						$enabled = true;
					break;
					case "array":
						$value = $arrItem[0];
						$text = (count($arrItem)>1)?$arrItem[1]:$arrItem[0];
						$enabled = (count($arrItem)>2)?$arrItem[2]:true;
					break;
					default:
						$this->throwError("Error in setting:".$setting.". unknown item type.");
					break;
				}
				$checkboxClass = $enabled ? "div_checkbox_on" : "div_checkbox_off";
				
					?>
						<li>
							<div class="div_value"><?php echo $value?></div>
							<div class="div_checkbox <?php echo $checkboxClass?>"></div>
							<div class="div_text"><?php echo $text?></div>
							<div class="div_handle"></div>
						</li>
					<?php 
			}
			
			?>
			</ul>
			<?php 			
		}
		
		//-----------------------------------------------------------------------------------------------
		//draw order box
		protected function drawOrderbox($setting){
						
			$items = $setting["items"];
			
			//get arrItems by saved value
			$arrItems = array();
					
			if(!empty($setting["value"]) && 
				getType($setting["value"]) == "array" &&
				count($setting["value"]) == count($items)){
				
				$savedItems = $setting["value"];
								
				foreach($savedItems as $value){
					$text = $value;
					if(isset($items[$value]))
						$text = $items[$value];
					$arrItems[] = array("value"=>$value,"text"=>$text);	
				}
			}		//get arrItems only from original items
			else{
				foreach($items as $value=>$text)
					$arrItems[] = array("value"=>$value,"text"=>$text);
			}
			
			
			?>
			<ul class="orderbox" id="<?php echo $setting["id"]?>">
			<?php 
				foreach($arrItems as $item){
					$itemKey = $item["value"];
					$itemText = $item["text"];
					
					$value = (getType($itemKey) == "string")?$itemKey:$itemText;
					?>
						<li>
							<div class="div_value"><?php echo $value?></div>
							<div class="div_text"><?php echo $itemText?></div>
						</li>
					<?php 
				} 
			?>
			</ul>
			<?php 
		}
		
		/**
		 * 
		 * draw button
		 */
		protected function drawButtonSetting($setting){
			//set class
			$class = UniteFunctionsUG::getVal($setting, "class");
			
			if(!empty($class))
				$class = "class='$class'";
			
			$addParams = UniteFunctionsUG::getVal($setting, UniteSettingsUG::PARAM_ADDPARAMS);
			
			?>
				<input type="button" id="<?php echo $setting["id"]?>" value="<?php echo $setting["value"]?>" <?php echo $class?> <?php echo $addParams?>>
			<?php 
		}
		
		
		//-----------------------------------------------------------------------------------------------
		// draw text area input
		protected function drawTextAreaInput($setting){
			$disabled = "";
			if (isset($setting["disabled"])) $disabled = 'disabled="disabled"';
			
			//set style
			$style = UniteFunctionsUG::getVal($setting, "style");	
			if(!empty($style)) 
				$style = "style='".$style."'";

			//set class
			$class = UniteFunctionsUG::getVal($setting, "class");
			if(!empty($class))
				$class = "class='$class'";
			
			?>
				<textarea id="<?php echo $setting["id"]?>" <?php echo $class?> name="<?php echo $setting["name"]?>" <?php echo $style?> <?php echo $disabled?>><?php echo $setting["value"]?></textarea>				
			<?php
		}		
		
		//-----------------------------------------------------------------------------------------------
		// draw radio input
		protected function drawRadioInput($setting){
			$items = $setting["items"];
			$counter = 0;
			$id = $setting["id"];
			$isDisabled = UniteFunctionsUG::getVal($setting, "disabled",false); 
			
			?>
			<span id="<?php echo $id?>" class="radio_wrapper">
			<?php 
			foreach($items as $value=>$text):
				$counter++;
				$radioID = $id."_".$counter;
				$checked = "";
				if($value == $setting["value"]) $checked = " checked";

				$disabled = "";
				if($isDisabled == true)
					$disabled = 'disabled="disabled"';
				
				?>
					<input type="radio" id="<?php echo $radioID?>" value="<?php echo $value?>" name="<?php echo $setting["name"]?>" <?php echo $disabled?> <?php echo $checked?>/>
					<label for="<?php echo $radioID?>" style="cursor:pointer;"><?php _e($text)?></label>
					&nbsp; &nbsp;
				<?php				
			endforeach;
			?>
			</span>
			<?php 
		}
		
		
		//-----------------------------------------------------------------------------------------------
		// draw checkbox
		protected function drawCheckboxInput($setting){
			$checked = "";
			if($setting["value"] == true) $checked = 'checked="checked"';
			?>
				<input type="checkbox" id="<?php echo $setting["id"]?>" class="inputCheckbox" name="<?php echo $setting["name"]?>" <?php echo $checked?>/>
			<?php
		}		
		
		//-----------------------------------------------------------------------------------------------
		//draw select input
		protected function drawSelectInput($setting){
			
			$className = "";
			if(isset($this->arrControls[$setting["name"]])) $className = "control";
			$class = "";
			if($className != "") $class = "class='".$className."'";
			
			$disabled = "";
			if(isset($setting["disabled"])) 
				$disabled = 'disabled="disabled"';
			
			?>
			<select id="<?php echo $setting["id"]?>" name="<?php echo $setting["name"]?>" <?php echo $disabled?> <?php echo $class?>>
			<?php
			foreach($setting["items"] as $value=>$text):
				$text = __($text,UNITEGALLERY_TEXTDOMAIN);
				$selected = "";
				if($value == $setting["value"]) $selected = 'selected="selected"';
				?>
					<option value="<?php echo $value?>" <?php echo $selected?>><?php echo $text?></option>
				<?php
			endforeach
			?>
			</select>
			<?php
		}

		/**
		 * 
		 * draw custom setting
		 */
		protected function drawCustom($setting){
			dmp($setting);
			exit();
		}
		
		//-----------------------------------------------------------------------------------------------
		//draw hr row
		protected function drawTextRow($setting){
			
			//set cell style
			$cellStyle = "";
			if(isset($setting["padding"])) 
				$cellStyle .= "padding-left:".$setting["padding"].";";
				
			if(!empty($cellStyle))
				$cellStyle="style='$cellStyle'";
				
			//set style
			$rowStyle = "";					
			if(isset($setting["hidden"]) && $setting["hidden"] == true) 
				$rowStyle .= "display:none;";
				
			if(!empty($rowStyle))
				$rowStyle = "style='$rowStyle'";
			
			?>
				<span class="spanSettingsStaticText"><?php echo __($setting["text"],UNITEGALLERY_TEXTDOMAIN)?></span>
			<?php 
		}
		
		//-----------------------------------------------------------------------------------------------
		//draw hr row
		protected function drawHrRow($setting){
			//set hidden
			$rowStyle = "";
			if(isset($setting["hidden"]) && $setting["hidden"] == true) 
				$rowStyle = "style='display:none;'";
			
			?>
				<li id="<?php echo $setting["id"]?>_row" <?php echo $rowStyle?> class="hrrow">
					<hr />
				</li>
			<?php 
		}
		
		
		//-----------------------------------------------------------------------------------------------
		//draw settings row
		protected function drawSettingRow($setting){
		
			//set cellstyle:
			$cellStyle = "";
			if(isset($setting[UniteSettingsUG::PARAM_CELLSTYLE])){
				$cellStyle .= $setting[UniteSettingsUG::PARAM_CELLSTYLE];
			}
			
			//set text style:
			$textStyle = $cellStyle;
			if(isset($setting[UniteSettingsUG::PARAM_TEXTSTYLE])){
				$textStyle .= $setting[UniteSettingsUG::PARAM_TEXTSTYLE];
			}
			
			if($textStyle != "") 
				$textStyle = "style='".$textStyle."'";
			
			if($cellStyle != "") 
				$cellStyle = "style='".$cellStyle."'";
			
			//set hidden
			$rowStyle = "";
			if(isset($setting["hidden"]) && $setting["hidden"] == true) $rowStyle = "display:none;";
			if(!empty($rowStyle)) $rowStyle = "style='$rowStyle'";
			
			//set row class:
			$rowClass = "";
			if(isset($setting["disabled"])) 
				$rowClass = "setting-disabled";
			
			if($setting["type"] == UniteSettingsUG::TYPE_TEXTAREA){
				if(!empty($rowClass))
					$rowClass .= " ";
				$rowClass .= "setting_row_textarea";
			}
			
			if(!empty($rowClass))
				$rowClass = "class='{$rowClass}'";
			
			
			//modify text:
			$text = UniteFunctionsUG::getVal($setting,"text","");
			$text = __($text,UNITEGALLERY_TEXTDOMAIN);
			
			// prevent line break (convert spaces to nbsp)
			$text = str_replace(" ","&nbsp;",$text);
			
			if($setting["type"] == UniteSettingsUG::TYPE_CHECKBOX)
				$text = "<label for='{$setting["id"]}'>{$text}</label>";
			
			//set settings text width:
			$textWidth = "";
			if(isset($setting["textWidth"])) 
				$textWidth = 'width="'.$setting["textWidth"].'"';
			
			$description = UniteFunctionsUG::getVal($setting, "description");
			$description = __($description,UNITEGALLERY_TEXTDOMAIN);
			
			$unit = UniteFunctionsUG::getVal($setting, "unit");
			$unit = __($unit,UNITEGALLERY_TEXTDOMAIN);
			
			$required = UniteFunctionsUG::getVal($setting, "required");
			
			$addHtml = UniteFunctionsUG::getVal($setting, UniteSettingsUG::PARAM_ADDTEXT);			
			$addHtmlBefore = UniteFunctionsUG::getVal($setting, UniteSettingsUG::PARAM_ADDTEXT_BEFORE_ELEMENT);			
			
			
			//set if draw text or not.
			$toDrawText = true;
			//if($setting["type"] == UniteSettingsUG::TYPE_BUTTON || $setting["type"] == UniteSettingsUG::TYPE_MULTIPLE_TEXT)
				//$toDrawText = false;
				
			$settingID = $setting["id"];
			$attribsText = UniteFunctionsUG::getVal($setting, "attrib_text");
			

			?>
				<li id="<?php echo $settingID?>_row" <?php echo $rowStyle." ".$rowClass?>>
					
					<?php if($toDrawText == true):?>
						<div id="<?php echo $settingID?>_text" class='setting_text' title="<?php echo $description?>" <?php echo $attribsText?>><?php echo $text ?></div>
					<?php endif?>
					
					<?php if(!empty($addHtmlBefore)):?>
						<div class="settings_addhtmlbefore"><?php echo $addHtmlBefore?></div>
					<?php endif?>
					
					<div class='setting_input'>
						<?php $this->drawInputs($setting);?>
					<?php if(!empty($unit)):?>
						<div class='setting_unit'><?php echo $unit?></div>
					<?php endif?>
					<?php if(!empty($required)):?>
						<div class='setting_required'>*</div>
					<?php endif?>
					<?php if(!empty($addHtml)):?>
						<div class="settings_addhtml"><?php echo $addHtml?></div>
					<?php endif?>					
					</div>
					<div class="unite-clear"></div>
				</li>
				<?php
				if($setting['name'] == 'shadow_type'){ //For shadow types, add box with shadow types
					$this->drawShadowTypes($setting['value']);
				}
		}
		
		/**
		 * 
		 * insert settings into saps array
		 */
		private function groupSettingsIntoSaps(){
			$arrSections = $this->settings->getArrSections();
			$arrSaps = $arrSections[0]["arrSaps"];
			$arrSettings = $this->settings->getArrSettings(); 
			
			//group settings by saps
			foreach($arrSettings as $key=>$setting){
				
				$sapID = $setting["sap"];
				
				if(isset($arrSaps[$sapID]["settings"]))
					$arrSaps[$sapID]["settings"][] = $setting;
				else 
					$arrSaps[$sapID]["settings"] = array($setting);
			}
			return($arrSaps);
		}
		
		/**
		 * 
		 * draw buttons that defined earlier
		 */
		private function drawButtons(){
			foreach($this->arrButtons as $key=>$button){
				if($key>0)
				echo "<span class='hor_sap'></span>";
				echo UniteFunctionsUG::getHtmlLink("#", $button["title"],$button["id"],$button["class"]);
			}
		}
		
		/**
		 * 
		 * draw some setting, can be setting array or name
		 */
		public function drawSetting($setting,$state = null){
			if(gettype($setting) == "string")
				$setting = $this->settings->getSettingByName($setting);
			
			switch($state){
				case "hidden":
					$setting["hidden"] = true;
				break;
			}
				
			switch($setting["type"]){
				case UniteSettingsUG::TYPE_HR:
					$this->drawHrRow($setting);
				break;
				case UniteSettingsUG::TYPE_STATIC_TEXT:
					$this->drawTextRow($setting);
				break;
				default:
					$this->drawSettingRow($setting);
				break;
			}
		}
		
		/**
		 * 
		 * draw setting by bulk names
		 */
		public function drawSettingsByNames($arrSettingNames,$state=null){
			if(gettype($arrSettingNames) == "string")
				$arrSettingNames = explode(",",$arrSettingNames);
				
			foreach($arrSettingNames as $name)
				$this->drawSetting($name,$state);
		}
		
		
		/**
		 * 
		 * draw all settings
		 */
		public function drawSettings(){
			$this->prepareToDraw();
			$this->drawHeaderIncludes();
			
			
			$arrSaps = $this->groupSettingsIntoSaps();			
			
			$class = "unite-postbox";
			if(!empty($this->addClass))
				$class .= " ".$this->addClass;
			
			//draw wrapper
			echo "<div class='settings_wrapper'>";
				
			//draw settings - advanced - with sections
			foreach($arrSaps as $key=>$sap):

				//set accordion closed
				$style = "";
				if($this->isAccordion == false){
					$h3Class = " no-accordion";
				}else{
					$h3Class = "";
					if($key>0){
						$style = "style='display:none;'";
						$h3Class = " box_closed";
					}
				}
					
				$text = $sap["text"];
				$classIcon = UniteFunctionsUG::getVal($sap, "icon");
				$text = __($text,UNITEGALLERY_TEXTDOMAIN);
				
				?>
					<div class="<?php echo $class?>">
						<div class="unite-postbox-title<?php echo $h3Class?>">
						
						<?php if(!empty($classIcon)):?>
						<i style="float:left;margin-top:4px;font-size:14px;" class="<?php echo $classIcon?>"></i>
						<?php endif?>
						
						<?php if($this->isAccordion == true):?>
							<div class="unite-postbox-arrow"></div>
						<?php endif?>
						
							<span><?php echo $text ?></span>
						</div>			
												
						<div class="inside" <?php echo $style?> >
							<ul class="list_settings">
						<?php
							
							$settings = UniteFunctionsUG::getVal($sap, "settings", array());
								
							foreach($settings as $setting)
								$this->drawSetting($setting);
							
							?>
							</ul>
							
							<?php 
							if(!empty($this->arrButtons)){
								?>
								<div class="unite-clear"></div>
								<div class="settings_buttons">
								<?php 
									$this->drawButtons();
								?>
								</div>	
								<div class="unite-clear"></div>
								<?php 								
							}								
						?>
						
							<div class="unite-clear"></div>
						</div>
					</div>
				<?php 			
														
			endforeach;
			
			echo "</div>";	//wrapper close
		}
		
		
		//-----------------------------------------------------------------------------------------------
		// draw sections menu
		public function drawSections($activeSection=0){
			if(!empty($this->arrSections)):
				echo "<ul class='listSections' >";
				for($i=0;$i<count($this->arrSections);$i++):
					$class = "";
					if($activeSection == $i) $class="class='selected'";
					$text = $this->arrSections[$i]["text"];
					echo '<li '.$class.'><a onfocus="this.blur()" href="#'.($i+1).'"><div>'.$text.'</div></a></li>';
				endfor;
				echo "</ul>";
			endif;
				
			//call custom draw function:
			if($this->customFunction_afterSections) call_user_func($this->customFunction_afterSections);
		}
		
		/**
		 * 
		 * init accordion
		 */
		private function putAccordionInit(){
			?>
			<script type="text/javascript">
				jQuery(document).ready(function(){
					var settings = new UniteSettingsUG();					
					settings.initAccordion("<?php echo $this->formID?>");
				});				
			</script>
			<?php 
		}
		
		/**
		 * 
		 * activate the accordion
		 */
		public function isAccordion($activate){
			$this->isAccordion = $activate;
		}
		
		
		/**
		 * 
		 * draw settings function
		 */
		public function draw($formID=null){
			if(empty($formID))
				UniteFunctionsUG::throwError("You must provide formID to side settings.");
			
			$this->formID = $formID;
			
			if(!empty($formID)){
				?>
				<form name="<?php echo $formID?>" id="<?php echo $formID?>">
					<?php $this->drawSettings() ?>
				</form>
				<?php 
			}else
				$this->drawSettings();
			
			if($this->isAccordion == true)
				$this->putAccordionInit();
			
		}
		
	}
		
?>