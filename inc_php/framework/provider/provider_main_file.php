<?php 

	require_once $currentFolder."/inc_php/framework/provider/unitegallery_widget.class.php";

try{

	//register the unitegallery slider widget	
	UniteFunctionsWPUG::registerWidget("UniteGallery_Widget");
	
	/**
	 * put unite gallery function
	 */
	function putUniteGallery($galleryAlias, $catID = null, $putIn = null){
		
		if(!empty($putIn)){
			$isPutInMatch = UniteFunctionsWPUG::isPutInStringMatch($putIn);
			if($isPutInMatch == false)
				return(false);
		}
			
		$content = HelperUG::outputGallery($galleryAlias, $catID);
		echo $content;
	}
	
	//add shortcode
	function unitegallery_shortcode($args){
		$galleryAlias = UniteFunctionsUG::getVal($args,0);
		$catID = UniteFunctionsUG::getVal($args,"catid");
		
		$content = HelperUG::outputGallery($galleryAlias, $catID);
		
		return($content);
	}
	
	add_shortcode( 'unitegallery', 'unitegallery_shortcode' );
	
	/**
	 * replace the post gallery with unite gallery
	 */
	function unitegallery_postgallery( $output = '', $atts, $content = false, $tag = false){
		
		$alias = UniteFunctionsUG::getVal($atts, "unitegallery");
		if(empty($alias))
			return $output;
			
		$ids = UniteFunctionsUG::getVal($atts, "ids");
		
		if(empty($ids))
			return $output;
		
		//get items
		$arrIDs = explode(",", $ids);
		$arrItems = UniteFunctionsWPUG::getArrItemsFromAttachments($arrIDs);
		
		//output gallery
		$objItems = new UniteGalleryItems();
		$arrUniteItems = $objItems->getItemsFromArray($arrItems);
		
		$content = HelperUG::outputGallery($alias, null, "alias", $arrUniteItems);
		
		return $content;
	}
	
	add_filter( 'post_gallery', 'unitegallery_postgallery', 60, 4 );
	
	//-------------------------------------------------------------
	
	// add another size
	
	add_image_size( "ug_big", 768, 768); 
	
	//-------------------------------------------------------------
	
	if(is_admin()){		//load admin part
		require_once $currentFolder."/unitegallery_admin.php";
		require_once $currentFolder . "/inc_php/framework/provider/provider_admin.class.php";
		
		$UGproductAdmin = new UniteProviderAdminUG($mainFilepath);
		
	}else{		//load front part
		require_once $currentFolder . "/inc_php/framework/provider/provider_front.class.php";
		$UGproductFront = new UniteProviderFrontUG($mainFilepath);
	}

	
	}catch(Exception $e){
		$message = $e->getMessage();
		$trace = $e->getTraceAsString();
		echo "Unite Gallery Error: <b>".$message."</b>";
	
		if(GlobalsUG::SHOW_TRACE == true)
			dmp($trace);
	}
	
	
?>