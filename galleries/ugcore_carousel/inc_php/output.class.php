<?php


defined('_JEXEC') or die('Restricted access');


	class UGCarouselOutput extends UGMainOutput{
		

		/**
		 *
		 * construct the output object
		 */
		public function __construct(){
			
			$this->theme = UGMainOutput::THEME_CAROUSEL;
			$this->isTilesType = true;
			
			parent::__construct();
		}		
		
		
		/**
		 * 
		 * put theme related scripts
		 */
		protected function putScripts($putSkins = true){
			
			parent::putScripts();
			
			if($this->putJsToBody == false)
				HelperGalleryUG::addScriptAbsoluteUrl($this->urlPlugin."themes/carousel/ug-theme-carousel.js", "unitegallery_carousel_theme");
			
		}
		
		
		/**
		 * put javascript includes to the body before the gallery div
		 */
		protected function putJsIncludesToBody(){
			$output = parent::putJsIncludesToBody();
			
			$src = $this->urlPlugin."themes/carousel/ug-theme-carousel.js";
			
			$output .= "<script type='text/javascript' src='{$src}'></script>";
			return($output);

		}
		
		
		/**
		 * get theme options override
		 */
		protected function getArrJsOptions(){

			$arr = parent::getArrJsOptions();
			
			$arr[] = $this->buildJsParam("theme_gallery_padding", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("theme_carousel_align");
			$arr[] = $this->buildJsParam("theme_carousel_offset", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("carousel_padding", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("carousel_space_between_tiles", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("carousel_scroll_duration", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("carousel_scroll_easing");
			$arr[] = $this->buildJsParam("carousel_autoplay", null, self::TYPE_BOOLEAN);
			$arr[] = $this->buildJsParam("carousel_autoplay_timeout", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("carousel_autoplay_direction");
			$arr[] = $this->buildJsParam("carousel_autoplay_pause_onhover", null, self::TYPE_BOOLEAN);
			$arr[] = $this->buildJsParam("theme_enable_navigation", null, self::TYPE_BOOLEAN);
			$arr[] = $this->buildJsParam("theme_navigation_enable_play", null, self::TYPE_BOOLEAN);
			$arr[] = $this->buildJsParam("theme_navigation_align");
			$arr[] = $this->buildJsParam("theme_navigation_offset_hor", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("theme_navigation_position");
			$arr[] = $this->buildJsParam("theme_navigation_margin", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("theme_space_between_arrows", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			$arr[] = $this->buildJsParam("carousel_navigation_numtiles", self::VALIDATE_NUMERIC, self::TYPE_NUMBER);
			
			
			return($arr);
		}
		
		
	}

?>