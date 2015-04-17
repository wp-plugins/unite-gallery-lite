<?php
/**
 * @package Unite Gallery
 * @author UniteCMS.net / Valiano
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('_JEXEC') or die('Restricted access');


	class UGOperations extends UniteElementsBaseUG{
		
		
		/**
		 * get error message html
		 */
		public function getErrorMessageHtml($message){
			
			$html = '<div style="width:100%;min-width:400px;height:300px;margin-bottom:10px;border:1px solid black;margin:0px auto;overflow:auto;">';
			$html .= '<div style="padding-left:20px;padding-right:20px;line-height:1.5;padding-top:40px;color:red;font-size:16px;text-align:left;">';
			$html .= $message;
			$html .= '</div></div>';
			
			return($html);
		}
		
		
		/**
		 * put error mesage from the module
		 */
		public function putModuleErrorMessage($message, $trace = ""){
			
			?>
			<div style="width:100%;min-width:400px;height:300px;margin-bottom:10px;border:1px solid black;margin:0px auto;overflow:auto;">
				<div style="padding-left:20px;padding-right:20px;line-height:1.5;padding-top:40px;color:red;font-size:16px;text-align:left;">
					<?php echo $message?>
				</div>
				
				<?php if(!empty($trace)):?>
				
				<div style="text-align:left;padding-left:20px;padding-top:20px;">
					<pre><?php echo $trace?></pre>
				</div>
				
				<?php endif?>
			
			</div>	
			<?php
		}
		
		
		/**
		 * put top menu with some view
		 */
		public function putTopMenu($view){
			
			$viewGalleries = HelperUG::getGalleriesView();
			$viewItems = HelperUG::getItemsView();
			
			$activeGalleries = "";
			$activeItems = "";
			switch($view){
				default:
				case GlobalsUG::VIEW_GALLERIES:
					$activeGalleries = "class='active'";
				break;
				case GlobalsUG::VIEW_ITEMS:
					$activeItems = "class='active'";
				break;
			}
			
			?>
			
			<div class="top_menu_wrapper">
				<ul class="unite-top-main-menu">
					<li <?php echo $activeGalleries?>><a class="unite-button-secondary" href="<?php echo $viewGalleries?>"><?php _e("Gallery List", UNITEGALLERY_TEXTDOMAIN)?></a></li>
					<li <?php echo $activeItems?>><a class="unite-button-secondary" href="<?php echo $viewItems?>"><?php _e("Edit Items", UNITEGALLERY_TEXTDOMAIN)?></a></li>
				</ul>
			</div>
			
			<?php
		}
		
		
		/**
		 * create thumbs from image by url
		 * the image must be relative path to the platform base
		 */
		public function createThumbs($urlImage, $thumbWidth = null){
			
			if($thumbWidth === null)
				$thumbWidth = GlobalsUG::THUMB_WIDTH;
			
			$urlImage = HelperUG::URLtoRelative($urlImage);
			
			$info = HelperUG::getImageDetails($urlImage);
										
			//check thumbs path
			$pathThumbs = $info["path_thumbs"];
			if(!is_dir($pathThumbs))
				@mkdir($pathThumbs);
			
			if(!is_dir($pathThumbs))
				UniteFunctionsUG::throwError("Can't make thumb folder: {$pathThumbs}. Please check php and folder permissions");
			
			$filepathImage = $info["filepath"];
			
			$filenameThumb = $this->imageView->makeThumb($filepathImage, $pathThumbs, $thumbWidth);
			
			$urlThumb = "";
			if(!empty($filenameThumb)){
				$urlThumbs = $info["url_dir_thumbs"];
				$urlThumb = $urlThumbs.$filenameThumb;
			}
			
			return($urlThumb);
		}
		
		
		/**
		 * return thumb url from image url, return full url of the thumb
		 * if some error occured, return empty string
		 */
		public function getThumbURLFromImageUrl($urlImage, $imageID){
			
			try{
				$imageID = trim($imageID);
				if(!empty($imageID)){
					$urlThumb = UniteProviderFunctionsUG::getThumbUrlFromImageID($imageID);
				}else{
					$urlThumb = $this->createThumbs($urlImage);	
				}
				
				$urlThumb = HelperUG::URLtoFull($urlThumb);
				return($urlThumb);
				
			}catch(Exception $e){
				
				return("");
			}
			
			return("");			
		}
		
		
		
	}

?>