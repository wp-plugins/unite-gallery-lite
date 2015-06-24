<?php


defined('_JEXEC') or die('Restricted access');


	class UGVideoThemeOutput extends UGMainOutput{
		
		
		/**
		 *
		 * construct the output object
		 */
		public function __construct(){
			
			$this->theme = UGMainOutput::THEME_VIDEO;
			
			parent::__construct();
		}		
		
		
		/**
		 * 
		 * put theme related scripts
		 */
		protected function putScripts($putSkins = true){
									
			parent::putScripts(false);	//don't put skins
			
			if($this->putJsToBody == false)
				HelperGalleryUG::addScriptAbsoluteUrl($this->urlPlugin."themes/video/ug-theme-video.js", "unitegallery_video_theme");
						
			$skin = $this->getParam("theme_skin");
			$urlSkin = $this->urlPlugin."themes/video/skin-{$skin}.css";
			
			//if exists modified version, take the modified
			$filepath_modified = GlobalsUG::$path_media_ug."themes/video/skin-{$skin}-modified.css";
			if(file_exists($filepath_modified))
				$urlSkin = $this->urlPlugin."themes/video/skin-{$skin}-modified.css";				
			
			HelperGalleryUG::addStyleAbsoluteUrl($urlSkin, "ug-theme-video-{$skin}");
		}
		
		
		/**
		 * put javascript includes to the body before the gallery div
		 */
		protected function putJsIncludesToBody(){
			$output = parent::putJsIncludesToBody();
			
			$src = $this->urlPlugin."themes/video/ug-theme-video.js";
			
			$output .= "<script type='text/javascript' src='{$src}'></script>";
			return($output);

		}
		
		
		/**
		 * get theme options override
		 */
		protected function getArrJsOptions(){

			$arr = parent::getArrJsOptions();
			$arr[] = $this->buildJsParam("theme_autoplay", null, self::TYPE_BOOLEAN);
			$arr[] = $this->buildJsParam("theme_skin");
			
			return($arr);
		}
		
		
		/**
		 * filter items that are image types
		 */
		private function filterImageItems($arrItems){
			
			$arrItemsNew = array();
			
			foreach($arrItems as $item){
				$type = $item->getType();
				if($type != "image")
					$arrItemsNew[] = $item;
			}
			
			return($arrItemsNew);
		}
		
		
		/**
		 * put gallery items
		 */
		protected function putItems($arrItems){
			
			$arrItems = $this->filterImageItems($arrItems);
			
			return parent::putItems($arrItems);
		}
		
		
	}

?>