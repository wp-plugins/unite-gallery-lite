<?php
/**
 * @package Unite Gallery
 * @author UniteCMS.net / Valiano
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('_JEXEC') or die('Restricted access');


	class UniteSettingsProductUG extends UniteSettingsOutputUG{
		
		private $showDescAsTips = false;
		private $showSaps = true;

		
		/**
		 *
		 * set show descriptions as tips true / false
		 */
		public function setShowDescAsTips($show){
			$this->showDescAsTips = $show;
		}
		
		
		/**
		 *
		 * show saps true / false
		 */
		public function setShowSaps($show){
			$this->showSaps = $show;
		}
		
		
		/**
		 * draw text as input
		 * @param $setting
		 */
		protected function drawTextInput($setting) {
			$disabled = "";
			$style="";
			$readonly = "";
			
			if(isset($setting["style"])) 
				$style = "style='".$setting["style"]."'";
			if(isset($setting["disabled"])) 
				$disabled = 'disabled="disabled"';
				
			if(isset($setting["readonly"])){
				$readonly = "readonly='readonly'";
			}
			
			$class = UniteFunctionsUG::getVal($setting, "class", "input-regular");
			
			if(!empty($class))
				$class = "class='$class'";
			
			$settingValue = htmlspecialchars($setting["value"]);
			
			?>
				<input type="text" <?php echo $class?> <?php echo $style?> <?php echo $disabled?><?php echo $readonly?> id="<?php echo $setting["id"]?>" name="<?php echo $setting["name"]?>" value="<?php echo $settingValue?>" />
			<?php
		}
		
		
		
		/**
		 * 
		 * draw imaeg input:
		 * @param $setting
		 */
		protected function drawImageInput($setting){
			
			$class = UniteFunctionsUG::getVal($setting, "class");
			
			if(!empty($class))
				$class = "class='$class'";
			
			$settingsID = $setting["id"];
			
			$buttonID = $settingsID."_button";
			$buttonRemoveID = $settingsID."_button_remove";
			
			$spanPreviewID = $buttonID."_preview";
			
			$img = "";
			$value = UniteFunctionsUG::getVal($setting, "value");
			
			if(!empty($value)){
				$urlImage = $value;
				$imagePath = UniteFunctionsWPUG::getImageRealPathFromUrl($urlImage);							
				$img = '<div style="width:100px;height:70px;background:url('.$urlImage.'); background-position:center center; background-size:contain;background-repeat:no-repeat;"></div>';
			}
			
			?>
				<span id='<?php echo $spanPreviewID?>' class='setting-image-preview'><?php echo $img?></span>
				
				<input type="hidden" id="<?php echo $setting["id"]?>" name="<?php echo $setting["name"]?>" value="<?php echo $setting["value"]?>" />
				
				<input type="button" id="<?php echo $buttonID?>" style="width: 110px !important; float: left;" class='button-image-select button-primary revblue<?php echo $class?>' value="<?php _e('Choose Image',UNITEGALLERY_TEXTDOMAIN); ?>"></input>
				<input type="button" class="button-image-remove button-primary revred" style="width: 110px !important;" id="<?php echo $buttonRemoveID; ?>" value="<?php _e('Remove',UNITEGALLERY_TEXTDOMAIN); ?>" />
				<div class="unite-clear"></div>
			<?php
		}
		
		
		//-----------------------------------------------------------------------------------------------
		//draw a color picker
		protected function drawColorPickerInput($setting){	
			$bgcolor = $setting["value"];
			$bgcolor = str_replace("0x","#",$bgcolor);			
			// set the forent color (by black and white value)
			$rgb = UniteFunctionsUG::html2rgb($bgcolor);
			$bw = UniteFunctionsUG::yiq($rgb[0],$rgb[1],$rgb[2]);
			$color = "#000000";
			if($bw<128) $color = "#ffffff";
			
			
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
		//draw a date picker
		protected function drawDatePickerInput($setting){			
			$date = $setting["value"];
			//$date = str_replace("0x","#",$date);			
			
			//$rgb = UniteFunctionsUG::html2rgb($date);
			//$bw = UniteFunctionsUG::yiq($rgb[0],$rgb[1],$rgb[2]);
			

			
			?>
				<input type="text" class="inputDatePicker" id="<?php echo $setting["id"]?>" name="<?php echo $setting["name"]?>" value="<?php echo $date?>"></input>
			<?php
		}
		
		
		/**
		 * draw setting input by type
		 */
		protected function drawInputs($setting){
			switch($setting["type"]){
				case UniteSettingsUG::TYPE_TEXT:
					$this->drawTextInput($setting);
				break;
				case UniteSettingsUG::TYPE_COLOR:
					$this->drawColorPickerInput($setting);
				break;
				case UniteSettingsUG::TYPE_DATE:
					$this->drawDatePickerInput($setting);
				break;
				case UniteSettingsUG::TYPE_SELECT:
					$this->drawSelectInput($setting);
				break;
				case UniteSettingsUG::TYPE_CHECKLIST:
					$this->drawChecklistInput($setting);
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
				case UniteSettingsUG::TYPE_IMAGE:
					$this->drawImageInput($setting);
				break;
				case UniteSettingsUG::TYPE_CUSTOM:
					if(method_exists($this,"drawCustomInputs") == false){
						UniteFunctionsUG::throwError("Method don't exists: drawCustomInputs, please override the class");
					}
					$this->drawCustomInputs($setting);
				break;
				default:
					throw new Exception("wrong setting type - ".$setting["type"]);
				break;
			}

			
		}		
		
		
		
		//-----------------------------------------------------------------------------------------------
		// draw text area input
		
		protected function drawTextAreaInput($setting){
			
			$disabled = "";
			if (isset($setting["disabled"])) $disabled = 'disabled="disabled"';
			
			$style = "";
			if(isset($setting["style"]))
				$style = "style='".$setting["style"]."'";

			$rows = UniteFunctionsUG::getVal($setting, "rows");
			if(!empty($rows))
				$rows = "rows='$rows'";
				
			$cols = UniteFunctionsUG::getVal($setting, "cols");
			if(!empty($cols))
				$cols = "cols='$cols'";
			
			$settingValue = $setting["value"];
			$settingValue = htmlspecialchars($settingValue);
			
			?>
				<textarea id="<?php echo $setting["id"]?>" name="<?php echo $setting["name"]?>" <?php echo $style?> <?php echo $disabled?> <?php echo $rows?> <?php echo $cols?>  ><?php echo $settingValue?></textarea>
			<?php
			if(!empty($cols))
				echo "<br>";	//break line on big textareas.
		}		
		
		
		/**
		 * draw radio input
		 */
		protected function drawRadioInput($setting){
			$items = $setting["items"];
			$counter = 0;
			$settingID = $setting["id"];
			$isDisabled = UniteFunctionsUG::getVal($setting, "disabled");
			$isDisabled = UniteFunctionsUG::strToBool($isDisabled);
			$settingName = $setting["name"];
			
			?>
			<span id="<?php echo $settingID ?>" class="radio_wrapper">
			<?php 
			foreach($items as $value=>$text):
				$counter++;
				$radioID = $settingID."_".$counter;
				
				$strChecked = "";				
				if($value == $setting["value"]) 
					$strChecked = " checked";

				$strDisabled = "";
				if($isDisabled)
					$strDisabled = 'disabled = "disabled"';
				
				$props = "style=\"cursor:pointer;\" {$strChecked} $strDisabled";
				
				?>					
					<input type="radio" id="<?php echo $radioID?>" value="<?php echo $value?>" name="<?php echo $settingName?>" <?php echo $props?>/>
					<label for="<?php echo $radioID?>" ><?php echo $text?></label>
					&nbsp; &nbsp;
				<?php				
			endforeach;
			
			?>
			</span>
			<?php 
		}
		
		
		
		/**
		 * draw checkbox
		 */
		protected function drawCheckboxInput($setting){
			$checked = "";
			if($setting["value"] == true) $checked = 'checked="checked"';
			?>
				<input type="checkbox" id="<?php echo $setting["id"]?>" class="inputCheckbox" name="<?php echo $setting["name"]?>" <?php echo $checked?>/>
			<?php
		}		
		
		/**
		 * draw select input
		 */
		protected function drawSelectInput($setting){
			
			$className = "";
			if(isset($this->arrControls[$setting["name"]])) 
				$className = "control";
				
			$class = "";
			if($className != "") $class = "class='".$className."'";
			
			$disabled = "";
			if(isset($setting["disabled"])) $disabled = 'disabled="disabled"';
			
			$args = UniteFunctionsUG::getVal($setting, "args");
			
			$settingValue = $setting["value"];
			
			if(strpos($settingValue,",") !== false)
				$settingValue = explode(",", $settingValue);
				
			?>
			<select id="<?php echo $setting["id"]?>" name="<?php echo $setting["name"]?>" <?php echo $disabled?> <?php echo $class?> <?php echo $args?>>
			<?php
			foreach($setting["items"] as $value=>$text):
				//set selected
				$selected = "";
				$addition = "";
				if(strpos($value,"option_disabled") === 0){
					$addition = "disabled";					
				}else{
					if(is_array($settingValue)){
						if(array_search($value, $settingValue) !== false) 
							$selected = 'selected="selected"';
					}else{
						if($value == $settingValue) 
							$selected = 'selected="selected"';
					}
				}
									
				
				?>
					<option <?php echo $addition?> value="<?php echo $value?>" <?php echo $selected?>><?php echo $text?></option>
				<?php
			endforeach
			?>
			</select>
			<?php
		}

		
		/**
		 * 
		 * draw checklist input
		 * @param $setting
		 */
		protected function drawChecklistInput($setting){
			
			$className = "input_checklist";
			if(isset($this->arrControls[$setting["name"]])) 
				$className .= " control";
							
			$class = "";
			if($className != "") $class = "class='".$className."'";
			
			$disabled = "";
			if(isset($setting["disabled"])) $disabled = 'disabled="disabled"';
			
			$args = UniteFunctionsUG::getVal($setting, "args");
			
			$settingValue = $setting["value"];
			
			if(strpos($settingValue,",") !== false)
				$settingValue = explode(",", $settingValue);
			
			$style = "z-index:1000;";
			$minWidth = UniteFunctionsUG::getVal($setting, "minwidth");
			
			if(!empty($minWidth)){
				$style .= "min-width:".$minWidth."px;";
				$args .= " data-minwidth='".$minWidth."'";
			}
			
			?>
			<select id="<?php echo $setting["id"]?>" name="<?php echo $setting["name"]?>" <?php echo $disabled?> multiple <?php echo $class?> <?php echo $args?> size="1" style="<?php echo $style?>">
			<?php
			foreach($setting["items"] as $value=>$text):
				//set selected
				$selected = "";
				$addition = "";
				if(strpos($value,"option_disabled") === 0){
					$addition = "disabled";					
				}else{
					if(is_array($settingValue)){
						if(array_search($value, $settingValue) !== false) 
							$selected = 'selected="selected"';
					}else{
						if($value == $settingValue) 
							$selected = 'selected="selected"';
					}
				}
									
				
				?>
					<option <?php echo $addition?> value="<?php echo $value?>" <?php echo $selected?>><?php echo $text?></option>
				<?php
			endforeach
			?>
			</select>
			<?php
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
			if(isset($setting["hidden"])) 
				$rowStyle .= "display:none;";
				
			if(!empty($rowStyle))
				$rowStyle = "style='$rowStyle'";
			
			?>
				<tr id="<?php echo $setting["id_row"]?>" <?php echo $rowStyle ?> valign="top">
					<td colspan="4" align="right" <?php echo $cellStyle?>>
						<span class="spanSettingsStaticText"><?php echo $setting["text"]?></span>
					</td>
				</tr>
			<?php 
		}
		
		//-----------------------------------------------------------------------------------------------
		//draw hr row
		protected function drawHrRow($setting){
			//set hidden
			$rowStyle = "";
			if(isset($setting["hidden"])) $rowStyle = "style='display:none;'";
			
			$class = UniteFunctionsUG::getVal($setting, "class");
			if(!empty($class))
				$class = "class='$class'";
			
			?>
			<tr id="<?php echo $setting["id_row"]?>" <?php echo $rowStyle ?>>
				<td colspan="4" align="left" style="text-align:left;">
					 <hr <?php echo $class; ?> /> 
				</td>
			</tr>
			<?php 
		}
		
		/**
		 * draw input additinos like unit / description etc
		 */
		private function drawInputAdditions($setting){
			
			$description = UniteFunctionsUG::getVal($setting, "description");
			$unit = UniteFunctionsUG::getVal($setting, "unit");
			$required = UniteFunctionsUG::getVal($setting, "required");
			$addHtml = UniteFunctionsUG::getVal($setting, UniteSettingsUG::PARAM_ADDTEXT);
			
			?>
			
			<?php if(!empty($unit)):?>
			<span class='setting_unit'><?php echo $unit?></span>
			<?php endif?>
			<?php if(!empty($required)):?>
			<span class='setting_required'>*</span>
			<?php endif?>
			<?php if(!empty($addHtml)):?>
			<span class="settings_addhtml"><?php echo $addHtml?></span>
			<?php endif?>					
			<?php if(!empty($description) && $this->showDescAsTips == false):?>
			<span class="description"><?php echo $description?></span>
			<?php endif?>
			
			<?php 
		}
		
		
		/**
		 * draw settings row
		 * @param $setting
		 */
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
			if(isset($setting["hidden"]))
				 $rowStyle = "display:none;";
			
			if(!empty($rowStyle)) 
				$rowStyle = "style='$rowStyle'";
			
			//set text class:
			$class = "";
			if(isset($setting["disabled"]))
				 $class = "class='setting-disabled'";
			
			//modify text:
			$text = UniteFunctionsUG::getVal($setting,"text","");				
			// prevent line break (convert spaces to nbsp)
			$text = str_replace(" ","&nbsp;",$text);
			switch($setting["type"]){					
				case UniteSettingsUG::TYPE_CHECKBOX:
					$text = "<label for='".$setting["id"]."' style='cursor:pointer;'>$text</label>";
				break;
			}			
			
			$description = UniteFunctionsUG::getVal($setting,"description");

			
			//set settings text width:
			$textWidth = "";
			if(isset($setting["textWidth"])) 
				$textWidth = 'width="'.$setting["textWidth"].'"';
			
			$addField = UniteFunctionsUG::getVal($setting, UniteSettingsUG::PARAM_ADDFIELD);
			
			?>
						
			<?php
			if(!empty($addField)):
				
				$addSetting = $this->settings->getSettingByName($addField);
				UniteFunctionsUG::validateNotEmpty($addSetting,"AddSetting {$addField}");
			
				//set hidden
				$rowStyleAdd = "";
				if(isset($addSetting["hidden"]))
					$rowStyleAdd = "display:none;";
				
				if(!empty($rowStyleAdd))
					$rowStyleAdd = "style='$rowStyleAdd'";
							
				$addSettingText = UniteFunctionsUG::getVal($addSetting,"text","");
				$addSettingText = str_replace(" ","&nbsp;", $addSettingText);
				
				?>
				<tr <?php echo $class?> valign="top">
				
				<td <?php echo $cellStyle?> class="unite-settings-onecell" colspan="2">
					<span id="<?php echo $setting["id_row"]?>" <?php echo $rowStyle ?>>
						<span class='setting_onecell_text'><?php echo $text?></span>				
							<?php 
								$this->drawInputs($setting);
								$this->drawInputAdditions($setting);
							?>
						<span class="setting_onecell_horsap"></span>
					</span>
					
					<span id="<?php echo $addSetting["id_row"]?>" <?php echo $rowStyleAdd ?>>
						<span class='setting_onecell_text'><?php echo $addSettingText?></span>				
						<?php
							$this->drawInputs($addSetting);
							$this->drawInputAdditions($addSetting);
						?>
					</span>
				</td>
				</tr>
				<?php
			?>
			<?php else:	?>
				<tr id="<?php echo $setting["id_row"]?>" <?php echo $rowStyle ?> <?php echo $class?> valign="top">
			
					<th <?php echo $textStyle?> scope="row" <?php echo $textWidth ?>>
						<?php if($this->showDescAsTips == true): ?>
					    	<span class='setting_text' title="<?php echo $description?>"><?php echo $text?></span>
					    <?php else:?>
					    	<?php echo $text?>
					    <?php endif?>
					</th>
					<td <?php echo $cellStyle?>>
						<?php 
							$this->drawInputs($setting);
							$this->drawInputAdditions($setting);
						?>
					</td>
				</tr>
			<?php
			endif;
		}

		
		
			/**
		 * draw all settings
		 */
		public function drawSettings(){
			$this->drawHeaderIncludes();
			$this->prepareToDraw();
			
			//draw main div
			$lastSectionKey = -1;
			$visibleSectionKey = 0;
			$lastSapKey = -1;
			
			$arrSections = $this->settings->getArrSections();
			$arrSettings = $this->settings->getArrSettings();
			
			$this->arrSettings = $arrSettings;
			
			//draw settings - simple
			if(empty($arrSections)):
					?><table class='unite_table_settings_wide'><?php
					foreach($arrSettings as $key=>$setting){
						
						if(isset($setting[UniteSettingsUG::PARAM_NODRAW]))
							continue;							
						
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
					?></table><?php					
			else:
			
				//draw settings - advanced - with sections
				foreach($arrSettings as $key=>$setting):

					//dmp($setting);
			
					//operate sections:
					if(!empty($arrSections) && isset($setting["section"])){										
						$sectionKey = $setting["section"];
												
						if($sectionKey != $lastSectionKey):	//new section					
							$arrSaps = $arrSections[$sectionKey]["arrSaps"];
							
							if(!empty($arrSaps)){
								//close sap
								if($lastSapKey != -1):
								?>
									</table>
									</div>
								<?php						
								endif;							
								$lastSapKey = -1;
							}
							
					 		$style = ($visibleSectionKey == $sectionKey)?"":"style='display:none'";
					 		
					 		//close section
					 		if($sectionKey != 0):
					 			if(empty($arrSaps))
					 				echo "</table>";
					 			echo "</div>\n";	 
					 		endif;					 		
					 		
							//if no saps - add table
							if(empty($arrSaps)):
							?><table class="unite_settings_table"><?php
							endif;								
						endif;
						$lastSectionKey = $sectionKey;
					}//end section manage
					
					//operate saps
					if(!empty($arrSaps) && isset($setting["sap"])){				
						$sapKey = $setting["sap"];
						if($sapKey != $lastSapKey){
							$sap = $this->settings->getSap($sapKey,$sectionKey);
							
							//draw sap end					
							if($sapKey != 0): ?>
							</table>
							<?php endif;
							
							//set opened/closed states:
							//$style = "style='display:none;'";
							$style = "";
							
							$class = "divSapControl";
							
							if($sapKey == 0 || isset($sap["opened"]) && $sap["opened"] == true){
								$style = "";
								$class = "divSapControl opened";						
							}
							
							//set hide / show sap controls
							$sapControlStyle = "";
							if($this->showSaps == false)
								$sapControlStyle = "style='display:none'";
							
							?>
								<div id="divSapControl_<?php echo $sectionKey."_".$sapKey?>" class="<?php echo $class?>" <?php echo $sapControlStyle?>>
									
									<h3><?php echo $sap["text"]?></h3>
								</div>
								<div id="divSap_<?php echo $sectionKey."_".$sapKey?>" class="divSap" <?php echo $style ?>>				
								<table class="unite_table_settings_wide">
							<?php 
							$lastSapKey = $sapKey;
						}
					}//saps manage
					
					//draw row:
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
				endforeach;
			endif;	
			 ?>
			</table>
			
			<?php
			if(!empty($arrSections)):
				if(empty($arrSaps))	 //close table settings if no saps 
					echo "</table>";
				echo "</div>\n";	 //close last section div
			endif;
			
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
		 * draw settings function
		 * @param $drawForm draw the form yes / no
		 */
		public function draw($formID=null,$drawForm = false){
			if(empty($formID))
				UniteFunctionsUG::throwError("The form ID can't be empty. you must provide it");
				
				$this->formID = $formID;
				
			?>
				<div class="settings_wrapper unite_settings_wide">
			<?php
			
			if($drawForm == true){
				?>
				<form name="<?php echo $formID?>" id="<?php echo $formID?>">
					<?php $this->drawSettings() ?>
				</form>
				<?php 				
			}else
				$this->drawSettings();
			
			?>
			</div>
			<?php 
		}
		
	}
?>