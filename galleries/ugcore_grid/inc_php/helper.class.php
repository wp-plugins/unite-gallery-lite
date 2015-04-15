<?php


defined('_JEXEC') or die('Restricted access');

	class UGGridThemeHelper{
		
		/**
		 * update thumb panel defaults of some gallery settings
		 * should be inited gallery inside the framework
		 */
		public static function updateThumbPanelDefaults($data){
						
			UniteFunctionsUG::validateNotEmpty(GlobalsUGGallery::$gallery, "The gallery shouold be inited");
			
			//$valuesParams["theme_panel_position"] = $position;
			
			//include settings
			$panelPos = UniteFunctionsUG::getVal($data, "position");
			require HelperGalleryUG::getFilepathSettings("gallery_settings");			
			$posRelatedFields = self::getPositionRelatedSettings();
			$valuesParams = UniteFunctionsUG::filterArrFields($valuesParams, $posRelatedFields);
			$valuesParams["theme_panel_position"] = $panelPos;
					
			GlobalsUGGallery::$gallery->updateParams($valuesParams);
		}
		
		
		/**
		 * get array of position related settings
		 */
		private static function getPositionRelatedSettings(){
			
			$arrSettings = array(
					"slider_enable_text_panel",
					"slider_zoompanel_align_hor",
					"slider_zoompanel_align_vert",
					"slider_zoompanel_offset_hor",
					"slider_zoompanel_offset_vert",
					"slider_progress_indicator_align_hor",
					"slider_progress_indicator_align_vert",
					"slider_progress_indicator_offset_hor",
					"slider_progress_indicator_offset_vert",
					"slider_play_button_align_hor",
					"slider_play_button_align_vert",
					"slider_play_button_offset_hor",
					"slider_play_button_offset_ver",
					"slider_fullscreen_button_align_hor",
					"slider_fullscreen_button_align_vert",
					"slider_fullscreen_button_offset_hor",
					"slider_fullscreen_button_offset_vert",
					"gridpanel_padding_border_top",
					"gridpanel_padding_border_bottom"
			);
			
			return($arrSettings);
		}
		
		
		/**
		 * get defauot setting for left position, take the right setting for base
		 */
		public static function getDefautsLeft(){
			
			$arrDefaults = self::getDefaultsRight();
			
			$arrDefaults["slider_zoompanel_align_hor"] = "right";
			$arrDefaults["slider_fullscreen_button_align_hor"] = "right";
			$arrDefaults["slider_play_button_align_hor"] = "right";
						
			return($arrDefaults);
		}
		
		/**
		 * get defauot setting for right position
		 */
		public static function getDefaultsRight(){
			
			$arrDefaults = array(
					"slider_zoompanel_offset_vert"=>"9",
					"slider_zoompanel_offset_hor"=>"11",
					"slider_play_button_offset_hor"=>"88",
					"slider_play_button_offset_vert"=>"8",
					"slider_fullscreen_button_offset_hor"=>"52",
					"slider_fullscreen_button_offset_vert"=>"9"
			);
			
			return($arrDefaults);
		}
		
		
		/**
		 * get default setting for top position
		 */
		public static function getDefaultsTop(){
		
			$arrDefaults = array(
					"slider_enable_text_panel"=>"false",
					"slider_zoompanel_align_vert"=>"bottom",
					"slider_zoompanel_offset_vert"=>"10",
					"slider_play_button_align_hor"=>"right",
					"slider_play_button_align_vert"=>"bottom",
					"slider_fullscreen_button_align_vert"=>"bottom",
					"slider_fullscreen_button_align_hor"=>"right",
					"slider_progress_indicator_align_vert"=>"bottom",
					"slider_progress_indicator_offset_vert"=>"40",
					"gridpanel_padding_border_top"=>"4",
					"gridpanel_padding_border_bottom"=>"10"
			);
		
			return($arrDefaults);
		}
		
		
		/**
		 * get default setting for top position
		 */
		public static function getDefaultsBottom(){
		
			$arrDefaults = array(
					"slider_enable_text_panel"=>"false",
					"slider_zoompanel_align_hor"=>"right",
					"slider_zoompanel_offset_vert"=>"10",
					"slider_progress_indicator_align_hor"=>"left",
					"slider_progress_indicator_offset_vert"=>"36",
					"slider_progress_indicator_offset_hor"=>"16"
			);
		
			return($arrDefaults);
		}

		
		/**
		 * get settings defaults by thumbs panel position
		 */
		public static function getDefautlsByPosition($pos){
			
			$pos = strtolower($pos);
			if($pos == "")
				$pos = "right";
			
			switch($pos){
				case "left":
					return self::getDefautsLeft();
				break;
				case "right":
					return self::getDefaultsRight();
				break;
				case "top":
					return self::getDefaultsTop();
				break;
				case "bottom":
					return self::getDefaultsBottom();
				break;
				default:
					UniteFunctionsUG::throwError("Wrong position: {$pos}");
				break;
			}			
		}

		
	}
?>