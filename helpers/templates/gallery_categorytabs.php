<?php


defined('_JEXEC') or die('Restricted access');

require HelperGalleryUG::getPathHelperTemplate("header");

$selectedGalleryTab = "categorytabs";
require HelperGalleryUG::getPathHelperTemplate("gallery_edit_tabs");


?>

<div class="settings_panel">

    <div class="settings_panel_left">
        <div class="settings_panel_left_inner settings_panel_box">
            
            <?php $outputMain->draw("form_gallery_category_settings",true)?>

            <div class="vert_sap20"></div>
            
            <div>* The lite version has a limitation of 4 category tabs per gallery in output. <a target="_blank" href="http://codecanyon.net/item/unite-gallery-wordpress-plugin/10458750?ref=valiano">Get Full Version!</a> </div>
            
            <div class="vert_sap40"></div>
			
			<div id="update_button_wrapper" class="update_button_wrapper">
				<a class='unite-button-primary' href='javascript:void(0)' id="button_save_gallery" ><?php _e("Update Settings",UNITEGALLERY_TEXTDOMAIN); ?></a>
				<div id="loader_update" class="loader_round" style="display:none;"><?php _e("Updating",UNITEGALLERY_TEXTDOMAIN); ?>...</div>
				<div id="update_gallery_success" class="success_message" class="display:none;"></div>
			</div>
		
			<a id="button_close_gallery" class='unite-button-secondary float_left mleft_10' href='<?php echo HelperGalleryUG::getUrlViewGalleriesList() ?>' ><?php _e("Close",UNITEGALLERY_TEXTDOMAIN); ?></a>	
		
			<div class="vert_sap20"></div>
			
			<div id="error_message_settings" class="unite_error_message" style="display:none"></div>
            
        </div>
    </div>
    <div class="settings_panel_right">
        <?php $outputParams->draw("form_gallery_category_settings_params",true); ?>
    </div>

    <div class="unite-clear"></div>
</div>

<script type="text/javascript">

    jQuery(document).ready(function(){
        var objAdmin = new UGAdmin();
        objAdmin.initCategoryTabsView();
    });

</script>
	
