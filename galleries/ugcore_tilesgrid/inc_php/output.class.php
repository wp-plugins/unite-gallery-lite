<?php


defined('_JEXEC') or die('Restricted access');


	class UGTilesGridOutput extends UGMainOutput{
		

		/**
		 *
		 * construct the output object
		 */
		public function __construct(){
			
			$this->theme = UGMainOutput::THEME_TILESGRID;
			$this->isTilesType = true;
			
			parent::__construct();
		}		
		
		
		/**
		 * modify optoins
		 */
		protected function modifyOptions(){
			parent::modifyOptions();
			
			$enableNavigation = $this->getParam("custom_enable_navigation", self::FORCE_BOOLEAN);
			
			if($enableNavigation === false)
				$this->arrParams["grid_num_rows"] = 9999;
			
		}
		
		/**
		 * 
		 * put theme related scripts
		 */
		protected function putScripts($putSkins = true){
			
			parent::putScripts();
			
			if($this->putJsToBody == false)
				HelperGalleryUG::addScriptAbsoluteUrl($this->urlPlugin."themes/tilesgrid/ug-theme-tilesgrid.js", "unitegallery_tilesgrid_theme");
			
		}
		
		
		/**
		 * put javascript includes to the body before the gallery div
		 */
		protected function putJsIncludesToBody(){
			$output = parent::putJsIncludesToBody();
			
			$src = $this->urlPlugin."themes/tilesgrid/ug-theme-tilesgrid.js";
			
			$output .= "<script type='text/javascript' src='{$src}'></script>";
			return($output);
			
		}
		
		
		/**
		 * get theme options override
		 */
		protected function getArrJsOptions(){

			$arr = parent::getArrJsOptions();
			
			$arr[] = $this->buildJsParam("theme_gallery_padding", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("grid_padding", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("grid_num_rows", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("theme_navigation_type");
			$arr[] = $this->buildJsParam("theme_arrows_margin_top", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("theme_space_between_arrows", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("theme_bullets_color");
			$arr[] = $this->buildJsParam("bullets_space_between", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("theme_bullets_margin_top", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			
			
			
			
			return($arr);
		}
		
		
	}

?>