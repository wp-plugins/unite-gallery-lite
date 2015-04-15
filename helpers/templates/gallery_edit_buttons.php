<?php


defined('_JEXEC') or die('Restricted access');

?>
	<div class="vert_sap40"></div>
	
	<div id="update_button_wrapper" class="update_button_wrapper">
		<a class='unite-button-primary' href='javascript:void(0)' id="button_save_gallery" ><?php _e("Update Gallery",UNITEGALLERY_TEXTDOMAIN); ?></a>
		<div id="loader_update" class="loader_round" style="display:none;"><?php _e("Updating",UNITEGALLERY_TEXTDOMAIN); ?>...</div>
		<div id="update_gallery_success" class="success_message" class="display:none;"></div>
	</div>
	
	<a id="button_delete_gallery" class='unite-button-secondary float_left mleft_10' href='javascript:void(0)'  ><?php _e("Delete Gallery",UNITEGALLERY_TEXTDOMAIN); ?></a>
	
	<a id="button_close_gallery" class='unite-button-secondary float_left mleft_10' href='<?php echo HelperGalleryUG::getUrlViewGalleriesList() ?>' ><?php _e("Close",UNITEGALLERY_TEXTDOMAIN); ?></a>	

	<div class="vert_sap20"></div>
	
	<div id="error_message_settings" class="unite_error_message" style="display:none"></div>
	