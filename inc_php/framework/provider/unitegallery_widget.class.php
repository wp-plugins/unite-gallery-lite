<?php

class UniteGallery_Widget extends WP_Widget {
	
    public function __construct(){
    	
        // widget actual processes
     	$widget_ops = array('classname' => 'widget_unitegallery', 'description' => __('Show the Unite Gallery') );
        parent::__construct('unitegallery-widget', __('Unite Gallery', UNITEGALLERY_TEXTDOMAIN), $widget_ops);
    }

    
    /**
     * 
     * the form
     */
    public function form($instance) {
	
		$galleries = new UniteGalleryGalleries();
    	$arrGalleries = $galleries->getArrGalleriesShort();
    	    	
    	$galleryID = UniteFunctionsUG::getVal($instance, "unitegallery");
    	    	
		if(empty($arrGalleries))
			echo __("No galleries found, Please create a gallery", UNITEGALLERY_TEXTDOMAIN);
		else{
			$fieldCat = "unitegallery_cat";
			$fieldIDCat = $this->get_field_id( $fieldCat );
			$fieldNameCat = $this->get_field_name( $fieldCat );
						
			$categoryID = UniteFunctionsUG::getVal($instance, "unitegallery_cat");
						
			$objCategories = new UniteGalleryCategories();
			$arrCats = $objCategories->getCatsShort("component");
			$selectCats = UniteFunctionsUG::getHTMLSelect($arrCats, $categoryID, 'name="'.$fieldNameCat.'" id="'.$fieldIDCat.'"', true);
			
			//output gallries select
			$field = "unitegallery";
			$fieldID = $this->get_field_id( $field );
			$fieldName = $this->get_field_name( $field );

			$selectGalleries = UniteFunctionsUG::getHTMLSelect($arrGalleries, $galleryID,'name="'.$fieldName.'" id="'.$fieldID.'"',true);
			
			?>
				<div style="padding-top:10px;padding-bottom:10px;">
				
				<?php _e("Title", UNITEGALLERY_TEXTDOMAIN)?>: 
				<input type="text" id="<?php echo $this->get_field_id( "title" );?>" name="<?php echo $this->get_field_name( "title" )?>" value="<?php echo UniteFunctionsUG::getVal($instance, 'title')?>" />
				
				<br><br>
				
				<?php _e("Choose Gallery", UNITEGALLERY_TEXTDOMAIN)?>: 
				<?php echo $selectGalleries?>
				
				<br><br>
				<?php _e("Choose Category", UNITEGALLERY_TEXTDOMAIN)?>: 
				
				<?php echo $selectCats?>
				
				</div>
				
				<br>
			<?php
			
		}

    }
 
    
    /**
     * 
     * update
     */
    public function update($new_instance, $old_instance) {
    	
        return($new_instance);
    }

    
    /**
     * 
     * widget output
     */
    public function widget($args, $instance) {
    	
    	$title = UniteFunctionsUG::getVal($instance, "title");

    	$galleryID =  UniteFunctionsUG::getVal($instance, "unitegallery");
    	$categoryID =  UniteFunctionsUG::getVal($instance, "unitegallery_cat");
    	
    	if(empty($galleryID))
    		return(false);
    	    	
    	//widget output
    	$beforeWidget = UniteFunctionsUG::getVal($args, "before_widget");
    	$afterWidget = UniteFunctionsUG::getVal($args, "after_widget");
    	$beforeTitle = UniteFunctionsUG::getVal($args, "before_title");
    	$afterTitle = UniteFunctionsUG::getVal($args, "after_title");
    	
    	echo $beforeWidget;
    	
    	if(!empty($title))
    		echo $beforeTitle.$title.$afterTitle;
    	
    	$content = HelperUG::outputGallery($galleryID, $categoryID, "id");
    	echo $content;
    	
    	echo $afterWidget;
    	
    }
 
    
}


?>