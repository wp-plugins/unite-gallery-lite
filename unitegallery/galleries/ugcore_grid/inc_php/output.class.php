<?php


defined('_JEXEC') or die('Restricted access');


	class UGGridThemeOutput extends UGMainOutput{
		
		/**
		 *
		 * construct the output object
		 */
		public function __construct(){

			$this->theme = UGMainOutput::THEME_GRID;
			parent::__construct();
		}		
		
		
		/**
		 * 
		 * put theme related scripts
		 */
		protected function putScripts($putSkins = true){
			
			parent::putScripts();
			
			if($this->putJsToBody == false)
				HelperGalleryUG::addScriptAbsoluteUrl($this->urlPlugin."themes/grid/ug-theme-grid.js", "unitegallery_grid_theme");
			
		}
		
		
		/**
		 * put javascript includes to the body before the gallery div
		 */
		protected function putJsIncludesToBody(){
			$output = parent::putJsIncludesToBody();
			
			$src = $this->urlPlugin."themes/grid/ug-theme-grid.js";
			
			$output .= "<script type='text/javascript' src='{$src}'></script>";
			return($output);
			
		}
		
		
		/**
		 * get default settings override.
		 * get them every time, take the position into the calculation
		 */
		protected function getDefautSettingsValues(){
			
			$panelPos = $this->getParam("theme_panel_position");
						
			require HelperGalleryUG::getFilepathSettings("gallery_settings");
						
			return($valuesMerged);
		}
		
		/**
		 * get theme options override
		 */
		protected function getArrJsOptions(){

			$arr = parent::getArrJsOptions();
			
			$arr[] = $this->buildJsParam("theme_hide_panel_under_width", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("theme_panel_position");
						
			return($arr);
		}
		
		
		/**
		 * get must fields array
		 */
		protected function getArrMustFields(){
			
			$arrMustFields = parent::getArrMustFields();
			$arrMustFields[] = "theme_panel_position";
			
			return($arrMustFields);
		}
		
	}

?>