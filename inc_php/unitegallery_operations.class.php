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
		
		
		/**
		 * run client ajax actions
		 */
		function onClientAjaxActions(){
			
			$action = UniteFunctionsUG::getPostGetVariable("action");
			if($action != "unitegallery_ajax_action"){
				echo "nothing here";exit();
			}
			
			$clientAction = UniteFunctionsUG::getPostGetVariable("client_action");
			$objItems = new UniteGalleryItems();
			$galleryHtmlID = UniteFunctionsUG::getPostVariable("galleryID");
			$data = UniteFunctionsUG::getPostVariable("data");
			
			if(empty($data))
				$data = array();
			
			$data["galleryID"] = HelperGalleryUG::getGalleryIDFromHtmlID($galleryHtmlID);
			
			try{
				
				switch($clientAction){
					case "front_get_cat_items":
												
						$html = $objItems->getHtmlFrontFromData($data);
						
						$output = array("html"=>$html);
						
						HelperUG::ajaxResponseData($output);
					break;
					default:
						HelperUG::ajaxResponseError("wrong ajax action: <b>$action</b> ");
					break;
				}
			
			}catch(Exception $e){
				$message = $e->getMessage();
			
				$errorMessage = $message;
				if(GlobalsUG::SHOW_TRACE == true){
					$trace = $e->getTraceAsString();
					$errorMessage = $message."<pre>".$trace."</pre>";
				}
			
				HelperUG::ajaxResponseError($errorMessage);
			}
			
			
			//it's an ajax action, so exit
			HelperUG::ajaxResponseError("No response output on <b> $action </b> action. please check with the developer.");
			exit();
			
		}
		
		
		/**
		 * put first 3 fields of the ajax form
		 */
		public function putAjaxFormFields($clientAction, $galleryID = null){
			?>
				<input type="hidden" name="action" value="unitegallery_ajax_action">		
				<input type="hidden" name="client_action" value="<?php echo $clientAction?>">
				
			<?php if(method_exists("UniteProviderFunctionsUG", "getNonce")):?>
				
				<input type="hidden" name="nonce" value="<?php echo UniteProviderFunctionsUG::getNonce(); ?>">
			<?php endif;
			 if(!empty($galleryID)): ?>
				<input type="hidden" name="galleryid" value="<?php echo $galleryID ?>">
			<?php endif;
			
		}
		
		
		/**
		* encode category id for exporting
		*/
		private function export_encodeCatID($catID){
			
			//if multiple string
			if(strpos($catID,",") !== false){
				$arrIDs = explode(",", $catID);
				foreach($arrIDs as $key=>$id)
					$arrIDs[$key] = "cat_".$id;
				$strIDs = implode(",", $arrIDs);
				return($strIDs);
			}
			
			//if simple number
			$catID = "cat_".$catID;
			
			return($catID);
		}
		
		
		/**
		 * decode category id
		 */
		private function export_decodeCatID($catID, $index){

			//if multiple string
			if(strpos($catID,",") !== false){
				$arrIDs = explode(",", $catID);
				foreach($arrIDs as $key=>$id)
					$arrIDs[$key] = $index[$id];

				$strIDs = implode(",", $arrIDs);
				return($strIDs);
			}
			
			//if simple number
			$catID = $index[$catID];
			
			return($catID);
		}
		
		
		/**
		 * export all gallery content
		 */
		public function exportAllContent(){
			
			//prepare categories			
			$arrCats = $this->db->fetch(GlobalsUG::$table_categories);
			
			//update aliases
			foreach($arrCats as $key=>$cat){
				$cat["alias"] = $this->export_encodeCatID($cat["id"]);
				$arrCats[$key] = $cat;
			}
			
			//-----------------------
			
			//prepare galleries
			$arrGalleries = $this->db->fetch(GlobalsUG::$table_galleries);
			foreach($arrGalleries as $key=>$gallery){
				
				$params = $gallery["params"];
				$arrParams = (array)json_decode($params);
				
				//change category id to alias
				if(array_key_exists("category", $arrParams))
					$arrParams["category"] = $this->export_encodeCatID($arrParams["category"]);
				
				if(array_key_exists("categorytabs_ids", $arrParams)){
					$arrParams["categorytabs_ids"] = $this->export_encodeCatID($arrParams["categorytabs_ids"]);
				}
									
				$params = json_encode($arrParams);
				$arrGalleries[$key]["params"] = $params;
			}
			
			//-----------------------
			//prepare items
			$arrItems = $this->db->fetch(GlobalsUG::$table_items);
			foreach($arrItems as $key=>$item){
				$item["catid"] = $this->export_encodeCatID($item["catid"]);
				$arrItems[$key] = $item;
			}
			
			$output = array();
			$output["categories"] = $arrCats;
			$output["galleries"] = $arrGalleries;
			$output["items"] = $arrItems;
			
			$strOutput = serialize($output);
			$filename = "unitegallery_export.txt";
			 
			header( 'Content-Description: File Transfer' );
			header( 'Content-Disposition: attachment; filename=' . $filename );
			
			echo $strOutput;
			exit();
		}
		
		
		
		/**
		 * import all content
		 */
		public function importAllContent($content){
			
			$arrData = unserialize($content);
			$arrCats = $arrData["categories"];
			
			//insert categories
			$this->db->runSql("delete from ".GlobalsUG::$table_categories);
			
			foreach($arrCats as $cat){
				unset($cat["id"]);
				$this->db->insert(GlobalsUG::$table_categories, $cat);
			}
			
			$arrCatsNew = $this->db->fetch(GlobalsUG::$table_categories);
			
			//prepare cats index
			$indexCats = array();
			foreach($arrCatsNew as $cat){
				$indexCats[$cat["alias"]] = $cat["id"];
			}
			
			//--------------
			//import galleries
			$arrGalleries = $arrData["galleries"];
			$this->db->runSql("delete from ".GlobalsUG::$table_galleries);
			
			foreach($arrGalleries as $gallery){
				unset($gallery["id"]);
				
				$params = $gallery["params"];
				$arrParams = (array)json_decode($params);
				
				//change category id to alias
				if(array_key_exists("category", $arrParams))
					$arrParams["category"] = $this->export_decodeCatID($arrParams["category"], $indexCats);
				
				if(array_key_exists("categorytabs_ids", $arrParams)){
					$arrParams["categorytabs_ids"] = $this->export_decodeCatID($arrParams["categorytabs_ids"], $indexCats);
				}
				
				$gallery["params"] = json_encode($arrParams);
				
				$this->db->insert(GlobalsUG::$table_galleries, $gallery);
			}
			
			
			//--------------
			// import items
			$arrItems = $arrData["items"];
			$this->db->runSql("delete from ".GlobalsUG::$table_items);
			
			foreach($arrItems as $item){
				unset($item["id"]);
				$item["catid"] = $this->export_decodeCatID($item["catid"], $indexCats);
				
				$this->db->insert(GlobalsUG::$table_items, $item);
			}
			
		}
		
		
	}

?>