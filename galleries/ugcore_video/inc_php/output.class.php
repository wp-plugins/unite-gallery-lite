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
			
			HelperGalleryUG::addStyleAbsoluteUrl($urlSkin, "ug-theme-video");
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
		 * put gallery items
		 */
		protected function putItems($arrItems){
			
			$itemsOutput = "";
			
			foreach($arrItems as $objItem):
		
				$urlThumb = $objItem->getUrlThumb();
				$urlImage = $objItem->getUrlImage();
			
				$title = $objItem->getTitle();
				$type = $objItem->getType();
				
				$description = $objItem->getParam("ug_item_description");
						
				$title = htmlspecialchars($title);
				$description = htmlspecialchars($description);
				
				if($type == UniteGalleryItem::TYPE_IMAGE)
					continue;

				$addHtml = $this->getVideoAddHtml($type, $objItem);
				
				$br = "\n";
				$linePrefix = "\n			";
				$linePrefix2 = "\n				";
				
				$output = "";
				$output .= $br;
				$output .= $linePrefix."<div data-type=\"{$type}\"";
				$output .= $linePrefix2."data-title=\"{$title}\"";
				$output .= $linePrefix2."data-description=\"{$description}\"";
				$output .= $linePrefix2."data-thumb=\"{$urlThumb}\"";
				$output .= $linePrefix2."data-image=\"{$urlImage}\"";
				$output .= $linePrefix2."{$addHtml}></div>";
				
				$itemsOutput .= $output;
				
			endforeach;
			
			return($itemsOutput);
		}
		
		
		
	}

?>