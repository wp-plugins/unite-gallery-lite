<?php


defined('_JEXEC') or die('Restricted access');


	class UGTilesJustifiedOutput extends UGMainOutput{
		

		/**
		 *
		 * construct the output object
		 */
		public function __construct(){
			
			$this->theme = UGMainOutput::THEME_TILES;
			$this->isTilesType = true;
			
			parent::__construct();
		}		
		
		
		/**
		 * 
		 * add justified function
		 */
		protected function modifyOptions(){
			
			parent::modifyOptions();
			$this->arrParams["tiles_type"] = "justified";
			
		}
		
		
		
		/**
		 * 
		 * put theme related scripts
		 */
		protected function putScripts($putSkins = true){
			
			parent::putScripts();
			
			if($this->putJsToBody == false)
				HelperGalleryUG::addScriptAbsoluteUrl($this->urlPlugin."themes/tiles/ug-theme-tiles.js", "unitegallery_tiles_theme");
			
		}
		
		
		/**
		 * put javascript includes to the body before the gallery div
		 */
		protected function putJsIncludesToBody(){
			$output = parent::putJsIncludesToBody();
			
			$src = $this->urlPlugin."themes/tiles/ug-theme-tiles.js";
			
			$output .= "<script type='text/javascript' src='{$src}'></script>";
			return($output);

		}
		
		
		/**
		 * get theme options override
		 */
		protected function getArrJsOptions(){

			$arr = parent::getArrJsOptions();
			
			$arr[] = $this->buildJsParam("tiles_type");
			$arr[] = $this->buildJsParam("theme_gallery_padding", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			
			$arr[] = $this->buildJsParam("tiles_justified_space_between", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("tiles_justified_row_height", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			
			$arr[] = $this->buildJsParam("theme_enable_preloader", null, self::TYPE_BOOLEAN);
			$arr[] = $this->buildJsParam("theme_preloading_height", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("theme_preloader_vertpos", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			
			$arr[] = $this->buildJsParam("tiles_enable_transition", null, self::TYPE_BOOLEAN);
			
			return($arr);
		}
		
		
	}

?>