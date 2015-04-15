<?php


defined('_JEXEC') or die('Restricted access');


	class UGSliderThemeOutput extends UGMainOutput{
		
		
		/**
		 *
		 * construct the output object
		 */
		public function __construct(){

			$this->theme = UGMainOutput::THEME_SLIDER;		
				
			parent::__construct();
		}		
		
		
		/**
		 * 
		 * put theme related scripts
		 */
		protected function putScripts($putSkins = true){
			
			parent::putScripts();
			
			if($this->putJsToBody == false)
				HelperGalleryUG::addScriptAbsoluteUrl($this->urlPlugin."themes/slider/ug-theme-slider.js", "unitegallery_slider_theme");
						
		}
		
		
		/**
		 * put javascript includes to the body before the gallery div
		 */
		protected function putJsIncludesToBody(){
			$output = parent::putJsIncludesToBody();
			
			$src = $this->urlPlugin."themes/slider/ug-theme-slider.js";
			
			$output .= "<script type='text/javascript' src='{$src}'></script>";
			return($output);
		}
		
				
		
	}

?>