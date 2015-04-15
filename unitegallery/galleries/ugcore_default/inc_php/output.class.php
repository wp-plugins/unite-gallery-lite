<?php


defined('_JEXEC') or die('Restricted access');


	class UGDefaultThemeOutput extends UGMainOutput{
		
		
		/**
		 *
		 * construct the output object
		 */
		public function __construct(){
			
			$this->theme = UGMainOutput::THEME_DEFAULT;
						
			parent::__construct();
		}		
		
		
		/**
		 * 
		 * put theme related scripts
		 */
		protected function putScripts($putSkins = true){
			
			parent::putScripts();
			
			if($this->putJsToBody == false)
				HelperGalleryUG::addScriptAbsoluteUrl($this->urlPlugin."themes/default/ug-theme-default.js", "unitegallery_default_theme");
			
			HelperGalleryUG::addStyleAbsoluteUrl($this->urlPlugin."themes/default/ug-theme-default.css","ug-theme-default");
			
		}
		
		
		/**
		 * put javascript includes to the body before the gallery div
		 */
		protected function putJsIncludesToBody(){
			$output = parent::putJsIncludesToBody();
			$src = $this->urlPlugin."themes/default/ug-theme-default.js";
			
			$output .= "<script type='text/javascript' src='{$src}'></script>";
			return($output);
		}
		
		
		/**
		 * get theme options override
		 */
		protected function getArrJsOptions(){

			$arr = parent::getArrJsOptions();
			
			$arr[] = $this->buildJsParam("theme_enable_fullscreen_button", null, self::TYPE_BOOLEAN);
			$arr[] = $this->buildJsParam("theme_enable_play_button", null, self::TYPE_BOOLEAN);
			$arr[] = $this->buildJsParam("theme_enable_hidepanel_button", null, self::TYPE_BOOLEAN);
			$arr[] = $this->buildJsParam("theme_enable_text_panel", null, self::TYPE_BOOLEAN);
			$arr[] = $this->buildJsParam("theme_hide_panel_under_width", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			
			return($arr);
		}
		
		
	}

?>